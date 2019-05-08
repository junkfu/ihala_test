<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use DateTime;

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
        //var_export($id);
        //return;
        
        
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


}
