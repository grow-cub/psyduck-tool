<?php

namespace Psyduck\Util;

class Time
{
    /**
     * @Author 可达鸭
     * @Description 格式化时间戳
     * @Date 2022/9/19 22:32:58
     * @param $timestamp
     * @return false|string
     */
    public static function formatTimeStamp($timestamp)
    {
        return date('Y-m-d H:i:s', $timestamp);
    }

    /**
     * @Author 可达鸭
     * @Description 获取时间戳
     * @Date 2022/9/19 22:29:56
     * @return int
     */
    public static function getTimeStamp(): int
    {
        return time();
    }

    /**
     * @Author 可达鸭
     * @Description 获取毫秒级时间戳
     * @Date 2022/9/19 22:39:02
     * @return float
     */
    public static function getMilliSecond(): float
    {
        list($msec, $sec) = explode(' ', microtime());
        return (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
    }
}