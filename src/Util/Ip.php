<?php

namespace Psyduck\Util;

class Ip
{
    /**
     * 获取客户端ip
     * @return mixed|string
     */
    public static function getClientIp()
    {
        $ip = '';
        if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
            $ip = getenv('HTTP_CLIENT_IP');
        } elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
            $ip = getenv('REMOTE_ADDR');
        } elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return preg_match ( '/[\d\.]{7,15}/', $ip, $matches ) ? $matches [0] : '';
    }

    /**
     * ip转int
     * @param $ip
     * @return float|int
     */
    public static function ipInt($ip)
    {
        return bindec(decbin(ip2long($ip)));
    }

    /**
     * ip int转string
     * @param $ip
     * @return false|string
     */
    public static function ipString($ip)
    {
        return long2ip($ip);
    }
}
