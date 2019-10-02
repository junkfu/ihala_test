<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use DateTime;
use Exception;
use App\Helpers\CrmHelper;


class Action extends Controller{
    public function __construct() {
        $this->page_items = 10;
    }

    public function pages(){

        $actions = DB::select('select id from action');
        if(count($actions) > 0){            
            return floor(count($actions) / $this->page_items) + 1;
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
            
            $actions = DB::table('action')->orderBy('id', 'desc')->skip($page)->take($this->page_items)->get();
        }
        if(count($actions) > 0){   
            return response()->json([$actions]);
        }
        else{
            return array();
        }
    }
    

    public static function insert($data){
        DB::table('action')->insert(
            $data
        );
    }

    public function test(Request $req){
        return response()->json('aaaa');
    }

    public function updateBySuper8(Request $req){

        //var_export($req->input());
        $msg = CrmHelper::insert($req->input());
        return response()->json($msg);
    }

    public function updateCrmByEmployee(Request $req){

        //var_export($req->input());
        //$msg = CrmHelper::insert($req->input());
        //var_export()
        $data = $req->input();
        //var_export($data);return;
        $update_data = array();
        //$update_data['attributes'] = array();
        foreach($data as $key => $value){
            if($key=='id'){
                $update_data['id'] = $value;
                
            }else{
                $update_data['attributes'][$key] = $value;
            }
        }

        $updata_data['attributes']['created_by_name'] = 'iHala';

        //var_export($update_data);
       // return;

        //$msg = CrmHelper::update(JSON_encode($update_data));
        $msg = CrmHelper::update($update_data);
        // returnecho $msg;
        //return;
        return $msg;
    }


}
