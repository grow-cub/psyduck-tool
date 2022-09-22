<?php

namespace Psyduck\Util;

class Param
{
    /**
     * 调用此函数 过滤参数中的value值
     * @param $params
     * @param array $tmp
     * @return array|mixed
     */
    public static function filterParams(&$params, array $tmp = array())
    {
        if(is_array($params)){
            foreach($params as $k => &$v){
                if(is_array($v))
                {
                    self::filterParams($v);
                }else{
                    self::filterWords($v);

                }
            }
        }
        else
        {
            $arr[] = self::filterWords($params);
        }
        return $params;
    }

    /**
     * 实际过滤函数 可适当修改其中的正则表示式
     * @param $str
     * @return string
     */
    private static function filterWords(&$str): string
    {
        $farr = array(
            "/<(\\/?)(script|i?frame|style|html|body|title|link|meta|object|\\?|\\%)([^>]*?)>/isU",
            "/(<[^>]*)on[a-zA-Z]+\s*=([^>]*>)/isU",
            "/select\b|insert\b|update\b|delete\b|drop\b|;|\"|\'|\/\*|\*|\.\.\/|\.\/|union|into|load_file|outfile|dump/is"
        );
        $str = preg_replace($farr,'',$str);
        $str = strip_tags($str);
        return $str;
    }
}