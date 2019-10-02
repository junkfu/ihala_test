<?php
namespace App\Helpers;
use DB;
use Exception;
use Config;

class IcryoHelper
{
    public static function insert($insert_data){


        //Logreturn '123';

        $client = new \GuzzleHttp\Client();

        try{
            $res = $client->post(Config::get('icryo.api.addBU.url'), [
                'json' => $insert_data
            ]);

            if($res->getStatusCode() != 200){
                throw new Exception($res->getReasonphrase(), $res->getStatusCode());
            }

            //var_export(json_decode($res->getBody()->getContents(), true));

            return json_decode($res->getBody());
        }catch (Exception $e) {
            //Log::emergency('[api] APP API '.$e->getMessage().' (checkEmailPwd)：'.\request()->ip());
            throw new Exception($e->getMessage(), $e->getCode());
        }

    }

    /*
    public static function update($update_data){

         $client = new \GuzzleHttp\Client();

        try{
            $res = $client->post(Config::get('crm.api.updateBU.url'), [
                'json' => $update_data
            ]);

            if($res->getStatusCode() != 200){
                throw new Exception($res->getReasonphrase(), $res->getStatusCode());
            }

            return $res->getBody();
        } catch (Exception $e) {
            //Log::emergency('[api] APP API '.$e->getMessage().' (checkEmailPwd)：'.\request()->ip());
            throw new Exception($e->getMessage(), $e->getCode());
        }

    }
    */
}

