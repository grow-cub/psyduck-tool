<?php

namespace Psyduck\Util;

class Header
{
    /**
     * 获取headre头部数据
     * @return array
     */
    public static function getHeader(): array
    {
        // 忽略获取的header数据。这个函数后面会用到。主要是起过滤作用
        $ignore = array('host', 'accept', 'content-length', 'content-type');

        $headers = array();
        //这里大家有兴趣的话，可以打印一下。会出来很多的header头信息。咱们想要的部分，都是‘http_'开头的。所以下面会进行过滤输出。
        /*    var_dump($_SERVER);
            exit;*/

        foreach ($_SERVER as $key => $value) {
            if (substr($key, 0, 5) === 'HTTP_') {
                //这里取到的都是'http_'开头的数据。
                //前去开头的前5位
                $key = substr($key, 5);
                //把$key中的'_'下划线都替换为空字符串
                $key = str_replace('_', ' ', $key);
                //再把$key中的空字符串替换成‘-’
                $key = str_replace(' ', '-', $key);
                //把$key中的所有字符转换为小写
                $key = strtolower($key);

                //这里主要是过滤上面写的$ignore数组中的数据
                if (!in_array($key, $ignore)) {
                    $headers[$key] = $value;
                }
            }
        }
        //输出获取到的header
        return $headers;
    }
}