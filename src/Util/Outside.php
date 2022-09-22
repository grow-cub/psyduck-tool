<?php

namespace Psyduck\Util;

/**
 * 防止站外提交数据的方法
 */

class Outside
{
    private static $outside = 'Outside:';

    /**
     * 这个方法只能防止手动在浏览栏上输入的URL
     * @return bool|false
     */
    public static function checkHttpUrl(): bool
    {
        $serverName = $_SERVER['SERVER_NAME'];
        $subFrom = $_SERVER["HTTP_REFERER"];

        $subLen = strlen($serverName);
        $checkFrom = substr($subFrom,7,$subLen);

        if($checkFrom != $serverName){
            return false;
        }
        return true;
    }

    /**
     * 创建令牌防止外站提交,适用于多场景
     * 可存入session进行比较
     * @return string
     */
    public static function createUniqidToken(): string
    {
        return self::$outside.md5(uniqid(rand(), true));
    }

    /**
     *
     * 可通过生成session的方式进行校验
     * @return bool
     */
    public static function checkUniqidToken(): bool
    {
        return $_POST['token'] == self::$outside.$_SESSION['token'] ? true : false;
    }


}