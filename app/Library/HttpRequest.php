<?php

namespace App\Library;

use Config;
use DB;

class HttpRequest 
{
    public static function get($url) 
    {
        try 
        {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_HTTPHEADER => array(
                    "cache-control: no-cache"
                ),
            ));

            $response = curl_exec($curl);
            $response = json_decode($response, true);
            $err = curl_error($curl);
            curl_close($curl);
            
            return [
                'success' => true,
                'data' => $response
            ];
        } 
        catch (Exception $e) 
        {
            //throw $th;
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}