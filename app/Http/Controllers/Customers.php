<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use DateTime;
use Exception;
use Config;
use App\Helpers\CrmHelper;
use App\Helpers\IcryoHelper;
use App\Http\Controllers\Action;


class Customers extends Controller{
    public function __construct() {

        $this->page_items = 10;
        //$this->user_limit = 72;
    }

    public function pages(){

        $custs = DB::select('select id from customers');
        if(count($custs) > 0){            
            return floor(count($custs) / $this->page_items) + 1;
        }
        else{
            return 1;
        }
    }

    public function page($page){
        $page = ($page - 1) * $this->page_items;
        if($page < 0){
            return array();
        }else{
            //$custs = DB::select('select * from customers where deleted =0');
            $custs = DB::table('customers')->where('deleted', '=', 0)->orderBy('id', 'desc')->skip($page)->take($this->page_items)->get();
        }
        if(count($custs) > 0){   
            return response()->json([$custs]);
        }
        else{
            return array();
        }
    }

    public function edit(Request $req,$id){
        $data = array(
            'name' => $req->input('name'),
            's_id' => $req->input('s_id'),
            'obj_id' => $req->input('obj_id'),
            'c_id' => $req->input('c_id'),
            'comm_id' => $req->input('comm_id'),
            'cNumber' => $req->input('cNumber'),
            'phone' => $req->input('phone'),
            'email' => $req->input('email'),
            'origin_name' => $req->input('origin_name'),
            'sex' => $req->input('sex'),
            'birth' => $req->input('birth'),
            'about' => $req->input('about'),
            'treatment' => $req->input('treatment'),
            'update_time'=> date('Y-m-d H:i:s')
        );
            
        DB::table('customers')->where('id', $id)->update($data);
        return response()->json();
    }


    //新增CRM資料&愛生育療程資料 並在action留記錄
    public function insertCrm(Request $req){

        //return response()->json('OK');
        $s_id = $req->input('s_id');  
        //$gender = ($req->input('gender') =='男' )?('male'):('female');        

        $data = array(
            's_id' => $s_id,
            //'c_id' => $req->input('c_id'),
            'comm_id' => $req->input('comm_id'),
            'super8_url' => $req->input('super8_url'),
            'origin_name' => $req->input('origin_name'),
            'name' => $req->input('displayName'),
            'phone' => $req->input('phone'),
            'email' => $req->input('email'),
            'gender' =>  $req->input('gender'),
            'birth' => $req->input('birthday'),
            'create_by' => $req->input('create_by'),
            //'create_time'=> date('Y-m-d H:i:s'),
            'update_time'=> date('Y-m-d H:i:s')
        );

        $cust = DB::table('customers')
                ->select('id')
                ->where('s_id','=',$s_id)->get();
        
        $ihala_id='';
        if(count($cust)>0){
            $ihala_id = $cust[0]->id;
            DB::table('customers')
            ->where('s_id', $s_id)
            ->update($data);
        }else{
            $ihala_id =DB::table('customers')
            ->insertGetId($data);
        }

        $to_crm_data = array(
            'created_by_name'=>'iHala',
            'last_name'=>$req->input('displayName'),
            'phone_mobile'=>$req->input('phone'),
            'birthdate' =>$req->input('birthdate'),
            'email1'=>$req->input('email'),
            'gender_c'=>$req->input('gender'),
            'ihala_line_url_c'=>$req->input('super8_url'),
            'social_media_type_1_c'=>$req->input('create_by'), //line or 網頁登記
            'social_media_id_1_c'=>$req->input('comm_id'),
            'country_c'=>$req->input('country_c'),
            'state_c'=>$req->input('state_c'),
            'city_c'=>$req->input('city_c'),
            'blood_type_c'=>$req->input('blood_type_c'),
            'spouse_blood_type_c'=>$req->input('spouse_blood_type_c'),
            'spouse_email_c'=>$req->input('spouse_email_c'),
            'height_c'=>$req->input('height_c'),
            'weight_c'=>$req->input('weight_c'),
            'education_degree_c'=>$req->input('education_degree_c'),
            'marital_relationship_c'=>$req->input('marital_relationship_c'),

        );

        foreach($to_crm_data as $key => $value){
            if($value == '' || $value == null){
                unset($to_crm_data[$key]);
            }
        }

        
        $res = json_decode(CrmHelper::insert($to_crm_data))->response;
        $res_result = $res->result;
        $res_obj = $res->object;
        $res_msg = $res->message;
    
        //$res_result='fail';
        //$res_obj ='';
        //$res_msg='none';

        $c_id='';
        $action_data= 'array()';

        //return response()->json($res_result);
        //如果insert crm成功,要把CRM ID寫回到customer
        if($res_result =='ok'){
            $c_id = $res->object;

            DB::table('customers')
            ->where('id', $ihala_id)
            ->update(array('c_id'=>$c_id));

            $action_data = array(
                //等CRM上線再改回來
                //'c_id' => $c_id,
                //'import_flag'=>1,
                'ihala_id'=>$ihala_id,
                'data'=>json_encode($to_crm_data,JSON_UNESCAPED_UNICODE),
                'result'=>$res_result,
                'msg'=>$res_msg,
                'response_obj'=>json_encode($res_obj,JSON_UNESCAPED_UNICODE),
                'import_flag'=>0,
                'destination'=>'CRM',
                'source'=>'line'
            );

            Action::insert($action_data);

            $bu = $req->input('BU');
            //return response()->json('BU....'.$bu);
            //判斷BU是否存在而且不為空值
            if($bu !='' &&  isset($bu)){
                //療程為IUI或其他療程就跳過
                if($req->input('BU') == 'IUI' || $req->input('BU')=='其他療程'){return;};
                
                $icryo_content = array();
                //把submit的其他資料放到content內給愛生育後台
                $submit_data = $req->input();
                $icryo_cols = Config::get('icryo.to_icryo_column');
        
                foreach($req->input() as $key=>$value){
                    if(in_array($key,$icryo_cols)){
                        $icryo_content[$key] = $req->input($key);
                    }
                }
   
                $to_icryo_data =array(
                    'BU'=>$req->input('BU'),
                    'CRMID'=>$c_id,
                    'Content'=>$icryo_content
                );

                //var_export($to_icryo_data);

                $icryo_res = IcryoHelper::insert($to_icryo_data);
                $msg = $icryo_res->message;
                $result = $icryo_res->success;
                $icryo_action_data = array(
                    'ihala_id'=>$ihala_id,
                    'data'=>json_encode($to_icryo_data,JSON_UNESCAPED_UNICODE),
                    'result'=>$result,
                    'msg'=>$msg,
                    'destination'=>'ICRYO',
                );
                //Action::insert($icryo_action_data);
                return response()->json('OK');
            }

        //如果insert crm失敗,crm_id就不寫回了
        }else{
            $action_data = array(
                'ihala_id'=>$ihala_id,
                'data'=>json_encode($to_crm_data,JSON_UNESCAPED_UNICODE),
                'result'=>$res_result,
                'msg'=>$res_msg,
                'origin_data'=>json_encode($req->input(),JSON_UNESCAPED_UNICODE),
                'response_obj'=>json_encode($res_obj,JSON_UNESCAPED_UNICODE),
                'destination'=>'CRM',
                'source'=>'line'
            );  

            Action::insert($action_data);
            
        } 

        return response()->json('OK');
    }


    public function insertBySuper8(Request $req){

        $s_id = $req->input('s_id');   
        //$gender = ($req->input('gender') =='男' )?('male'):('female');        

        $data = array(
            's_id' => $s_id,
            //'c_id' => $req->input('c_id'),
            'comm_id' => $req->input('comm_id'),
            'super8_url' => $req->input('super8_url'),
            'origin_name' => $req->input('origin_name'),
            'name' => $req->input('displayName'),
            'phone' => $req->input('phone'),
            'email' => $req->input('email'),
            'gender' =>  $req->input('gender'),
            'birth' => $req->input('birthday'),
            //'create_time'=> date('Y-m-d H:i:s'),
            'update_time'=> date('Y-m-d H:i:s')
        );

        $cust = DB::table('customers')
                ->select('id')
                ->where('s_id','=',$s_id)->get();
        
        $ihala_id='';
        if(count($cust)>0){
            $ihala_id = $cust[0]->id;
            DB::table('customers')
            ->where('s_id', $s_id)
            ->update($data);
        }else{
            $ihala_id =DB::table('customers')
            ->insertGetId($data);
        }

        $to_crm_data = array(
            'created_by_name'=>'iHala',
            'last_name'=>$req->input('displayName'),
            'phone_mobile'=>$req->input('phone'),
            'email1'=>$req->input('email'),
            'gender_c'=>$req->input('gender'),
            'ihala_line_url_c'=>$req->input('super8_url'),
            'social_media_type_1_c'=>'line',
            'social_media_id_1_c'=>$req->input('comm_id')
        );

        
        $res = json_decode(CrmHelper::insert($to_crm_data))->response;
        $res_result = $res->result;
        $res_obj = $res->object;
        $res_msg = $res->message;

        //$res_result='fail';
        //$res_obj ='';
        //$res_msg='none';

        $c_id='';
        $action_data= 'array()';

        //如果insert crm成功,要把CRM ID寫回到customer
        if($res_result =='ok'){
            $c_id = $res->object;

            DB::table('customers')
            ->where('id', $ihala_id)
            ->update(array('c_id'=>$c_id));

            $action_data = array(
                //等CRM上線再改回來
                //'c_id' => $c_id,
                //'import_flag'=>1,
                'ihala_id'=>$ihala_id,
                'data'=>json_encode($to_crm_data,JSON_UNESCAPED_UNICODE),
                'result'=>$res_result,
                'msg'=>$res_msg,
                'response_obj'=>json_encode($res_obj,JSON_UNESCAPED_UNICODE),
                'import_flag'=>0,
                'destination'=>'CRM',
                'source'=>'line'
            );

        //如果insert crm失敗,crm_id就不寫回了
        }else{
            $action_data = array(
                'ihala_id'=>$ihala_id,
                'data'=>json_encode($to_crm_data,JSON_UNESCAPED_UNICODE),
                'result'=>$res_result,
                'msg'=>$res_msg,
                'response_obj'=>json_encode($res_obj,JSON_UNESCAPED_UNICODE),
                'destination'=>'CRM',
                'source'=>'line'
            );  

            Action::insert($action_data);
            
        } 

        return response()->json('OK');
    }

    public function updateBySuper8(Request $req){
        $crm_cols = Config::get('crm.crm_column');
        
        //return $req->input();
        //return in_array($str,$col).'...';
        $s_id = $req->input('s_id');  
        $comm_id = $req->input('comm_id'); 
        $cust = DB::table('customers')
                ->select('id','comm_id','c_id')
                ->where('s_id','=',$s_id)
                ->orWhere('comm_id', '=',$comm_id)
                ->get();
        
        $ihala_id='';
        $to_crm_data = array();
        $to_crm_data['attributes'] = array();
        if(count($cust)>0){
            $c_id = $cust[0]->c_id;
            $ihala_id = $cust[0]->id;
            if($c_id == '' || $c_id == NULL){
                
                $data = $req->input();
                $to_crm_data['attributes']['created_by_name'] = 'iHala';
                foreach($data as $key => $value){
                    //若是在CRM column白名單欄位,就放進去
                    if(in_array($key,$crm_cols)){
                        $to_crm_data['attributes'][$key] = $value;
                    }
                }
                $action_data = array(
                    'ihala_id'=>$ihala_id,
                    'data'=>json_encode($to_crm_data,JSON_UNESCAPED_UNICODE),
                    'result'=>$res_result,
                    'msg'=>'無CRM ID,沒傳',
                    'destination'=>'CRM',
                    'source'=>'line'
                ); 
                Action::insert($action_data);
                return response()->json('OK');
            }else{
                $data = $req->input();
                $to_crm_data['id'] = $c_id;
                $to_crm_data['attributes']['created_by_name'] = 'iHala';
                foreach($data as $key => $value){
                    //若是在CRM column白名單欄位,就放進去     
                    if(in_array($key,$crm_cols)){
                        $to_crm_data['attributes'][$key] = $value;
                    }
                }
                $res = json_decode(CrmHelper::update($to_crm_data))->response;
                $res_result = $res->result;
                //$res_obj = $res->object;
                $res_msg = $res->message;
                $action_data = array(
                    'ihala_id'=>$ihala_id,
                    'data'=>json_encode($to_crm_data,JSON_UNESCAPED_UNICODE),
                    'result'=>$res_result,
                    'msg'=>$res_msg,
                    //'response_obj'=>json_encode($res_obj,JSON_UNESCAPED_UNICODE),
                    'destination'=>'CRM',
                    'source'=>'line'
                );
                Action::insert($action_data);
                return response()->json('OK');

            }
            
        }else{
            $data = array(
                's_id'=>$req->input('s_id'),
                'comm_id'=>$req->input('comm_id'),
            );
            $ihala_id =DB::table('customers')->insertGetId($data);
        }

        
    }

    public function test(Request $req){
        $a = array (
            's_id' => NULL,
            'comm_id' => NULL,
            'super8_url' => NULL,
            'origin_name' => NULL,
            'displayName' => '張張張復',
            'phone' => '911217777145',
            'email' => 'junkfood119@gmail.com',
            'gender' => 'female',
            'birth' => '1988-11-07',
            'create_by' => 'line',
            'BU' => 'OD',
            'CallAvailable1' => '1',
            'CallAvailable2' => '1',
        );

        $icryo_cols = Config::get('icryo.to_icryo_column');
        foreach ($a as $key=>$value){
           echo $key;
            if(in_array($key,$icryo_cols)){
                echo $key.'in<br>';
                //$to_crm_data['attributes'][$key] = $value;
            }
        }

        return;

        $a='女';
        $gender = ($a=='男' )?('male'):('female');
        echo $gender;
        return;
        //return response()->json('aaaa');

        //echo env('CRM_SERVER');
        //$ip = Config::get('crm.api.addContract.url');
       // echo '111'.$ip;
       //var_export($ip);
        

        $s_id = '1205d3099c1f2841a2689f2324b14f33';
        $cust = DB::table('customers')->select('id')->where('s_id','=',$s_id)->get();
        //var_export($cust);
        echo '<br><br>'.$cust[0]->id;

    }

    public function test2(Request $req){
        
        $data = array(
            'name' => '張復',
            's_id' => '2659a64ac95c90b970eaf3e151ea8b6b',
            'comm_id' => 'U9fa87e79655930d93e1c748a770c2f62',
            'super8_url' => 'https://console.no8.io/organizations/8gqg2DLvFb/message-center/conversation/75998b43cba2caa2fe666fd979d8c839/customer/U19d09edfe3c5f732b6aa1335f3c76240',
            'phone' => '0917193045645',
            'email' => 'junkfood1106@gmail.com',
        );

        $s_id = $req->input('s_id');   
        $gender = ($req->input('gender') =='男' )?('male'):('female');        
        /*
        $data = array(
            's_id' => $s_id,
            'c_id' => $req->input('c_id'),
            'comm_id' => $req->input('comm_id'),
            'super8_url' => $req->input('super8_url'),
            'origin_name' => $req->input('origin_name'),
            'name' => $req->input('name'),
            'phone' => $req->input('phone'),
            'email' => $req->input('email'),
            'gender' => $req->input('gender'),
            'birth' => $req->input('birth'),
            'create_time'=> date('Y-m-d H:i:s'),
            'update_time'=> date('Y-m-d H:i:s')
        );
        */

        $cust = DB::table('customers')
                ->select('id')
                ->where('s_id','=',$s_id)->get();
        
         $ihala_id='';
        if(count($cust)>0){
            $ihala_id = $cust[0]->id;
            DB::table('customers')
            ->where('s_id', $s_id)
            ->update($data);
        }else{
            $ihala_id =DB::table('customers')
            ->insertGetId($data);
        }

        $to_crm_data = array(
            'created_by_name'=>'iHala',
            'last_name'=>$req->input('name'),
            'phone_mobile'=>$req->input('phone'),
            'email1'=>$req->input('email'),
            'gender_c'=>$gender,
            'ihala_line_url_c'=>$req->input('super8_url'),
            'social_media_type_1_c'=>'line',
            'social_media_id_1_c'=>$req->input('comm_id')
        );


        
        $res = json_decode(CrmHelper::insert($to_crm_data))->response;
       //$res =CrmHelper::insert($to_crm_data);
        //var_export($res);
        //return;
        $result = $res->result;
        $c_id='';
        $action_data= 'array()';

        if($result =='ok'){
            $c_id = $res->object;

            DB::table('customers')
            ->where('ihala_id', $ihala_id)
            ->update(array('c_id'=>$c_id));

            $action_data = array(
                'c_id' => $c_id,
                'ihala_id'=>$ihala_id,
                //'data'=>json_encode($to_crm_data,JSON_UNESCAPED_UNICODE),
                'data'=>json_encode($to_crm_data),
                'result'=>$result,
                'msg'=>$res->message,
                //'response_obj'=>$res->object
            );  
        }else{
            $action_data = array(
                'ihala_id'=>$ihala_id,
                'data'=>json_encode($to_crm_data,JSON_UNESCAPED_UNICODE),
                'result'=>$result,
                'msg'=>$res->message,
                'response_obj'=>json_encode($res->object,JSON_UNESCAPED_UNICODE)
    
            );  
        } 

        Action::insert($action_data);
        return response()->json('OK');

      
    }

    public function addCrm(Request $req){

        //var_export($req->input());
        $msg = CrmHelper::insert($req->input());
        return response()->json($msg);
    }


}
