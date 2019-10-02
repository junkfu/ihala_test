<?php
namespace App\Helpers;
use DB;
//use Log;
use Exception;
use Config;

class CrmHelper
{
    public static function insert($insert_data){

        $client = new \GuzzleHttp\Client();

        try{
            $res = $client->post(Config::get('crm.api.addContract.url'), [
                'json' => $insert_data
            ]);

            if($res->getStatusCode() != 200){
                throw new Exception($res->getReasonphrase(), $res->getStatusCode());
            }

            //var_export(json_decode($res->getBody()->getContents(), true));
            return $res->getBody();
        }

        catch (Exception $e) {
            //Log::emergency('[api] APP API '.$e->getMessage().' (checkEmailPwd)：'.\request()->ip());
            throw new Exception($e->getMessage(), $e->getCode());
        }

    }

    public static function update($update_data){

         $client = new \GuzzleHttp\Client();

        //echo json_encode((array)$update_data);
        // return;
        // echo Config::get('crm.api.updateContract.url');
        // return;

        try{
            $res = $client->post(Config::get('crm.api.updateContract.url'), [
                'json' =>$update_data
            ]);

            if($res->getStatusCode() != 200){
                throw new Exception($res->getReasonphrase(), $res->getStatusCode());
            }
           // echo json_encode((array)$res);

            return $res->getBody();
            //return 'got crm res!!';
        }catch (Exception $e) {
            //Log::emergency('[api] APP API '.$e->getMessage().' (checkEmailPwd)：'.\request()->ip());
            throw new Exception($e->getMessage(), $e->getCode());
        }

    }
}

