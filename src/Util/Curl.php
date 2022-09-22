<?php

namespace Psyduck\Util;

use Exception;

class Curl
{

    /**
     * @Author 可达鸭
     * @Description
     * @Date 2022/8/30 23:44:12
     * @param $url
     * @return mixed
     * @throws Exception
     */
    public static function getUrl($url){
        $headerArray = array("Content-type:application/json;","Accept:application/json");
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch,CURLOPT_HTTPHEADER,$headerArray);
        $output = curl_exec($ch);
        curl_close($ch);
        if($output === FALSE){
            throw new Exception(curl_error($ch));
        }
        return json_decode($output,true);
    }

    /**
     * @Author 可达鸭
     * @Description
     * @Date 2022/8/30 23:45:00
     * @param $url
     * @param $data
     * @return mixed
     * @throws Exception
     */
    public static function postUrl($url,$data){
        $data  = json_encode($data);
        $headerArray = array("Content-type:application/json;charset='utf-8'","Accept:application/json");
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,FALSE);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl,CURLOPT_HTTPHEADER,$headerArray);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        if($output === FALSE){
            throw new Exception(curl_error($curl));
        }
        return json_decode($output,true);
    }
}