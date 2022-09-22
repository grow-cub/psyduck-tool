<?php

namespace Psyduck\Util;

class Pako
{
    /**
     * 加密
     * @return string
     */
    public static function encrypt(): string
    {
        $array = array(
            "title"       => "this is pako.defalte test",
            "author"      => "slongzhang@qq.com",
            "date"        => "2021-04-02",
            "content"       => "test echo string"

        );
        return base64_encode(gzdeflate(json_encode($array,256), 9));
    }

    /**
     * 解密
     * @return false|string
     */
    public static function decode()
    {
        $post = 'PYw7DoMwEESvYm0dLMeiospVVrDBFo4X8KQJ4u5ZGqTRFPN5ByGjCA2ElJszrbyon+TNBeIgDfQg/iLpbqNWtM6/xHV+bZsf9WPlxLj+McRnF/ouRMtGrZCKC2sEdxudfw==';
        $base64String = $post;
        return gzinflate(base64_decode(($base64String)));
    }
}