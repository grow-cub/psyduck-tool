<?php

namespace Psyduck\Util;

use Godruoyi\Snowflake;

class OrderSn
{
    public static function getSnByMemberId($memberId): string
    {
        //订单号主体
        $orderIdMain = date('YmdHis') . rand(10000000,99999999);
        //订单号长度
        $orderIdLen = strlen($orderIdMain);
        $orderIdSum = 0;
        for($i=0; $i<$orderIdLen; $i++){
            $orderIdSum += (int)(substr($orderIdMain,$i,1));
        }
        $orderIdSum .= sprintf('%03d', (int) $memberId % 1000);
        $orderIdSum .= str_pad((100 - $orderIdSum % 100) % 100,2,'0',STR_PAD_LEFT);
        //唯一订单号
        return $orderIdMain . $orderIdSum . sprintf('%03d', (int) self::getSnBySnowflake() % 1000);
    }

    public static function getSnBySnowflake(): string
    {
        require_once './vendor/autoload.php';
        $snowflake = new \Godruoyi\Snowflake\Snowflake;
        $snowflake->setStartTimeStamp(strtotime(date('Y-m-d'))*1000);

        return $snowflake->id();
    }
}