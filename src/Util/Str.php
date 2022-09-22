<?php

namespace Psyduck\Util;

class Str
{
    /**
     * @Author 可达鸭
     * @Description 手机号隐藏中间四位
     * @Date 2022/9/3 19:49:00
     * @param $strPhone
     * @return array|string|string[]
     */
    public static function trimStrPhone($strPhone)
    {
        return substr_replace($strPhone, '****', 3, 4);
    }

    /**
     * @Author 可达鸭
     * @Description
     * @Date 2022/9/3 19:51:22
     * @param $data
     * @param $template
     * @return string
     * $data = [
        'name' => '张三',
        'age' => 18,
        'phone' => '123456'
        ];
     * $template = '尊敬的${name}你好，你的年龄为${age}，你的手机号码是${phone}';
     */
    public static function replaceTemplate($data,$template): string
    {
        preg_match_all('/\${.*?}/', $template, $matches);
        $params = $matches[0];
        $values = [];
        foreach ($params as $param) {
            $key = str_replace(['${', '}'], '', $param);
            $values[$param] = $data[$key];
        }

        return strtr($template, $values);
    }
}