<?php

namespace Psyduck\Util;

/**
 * 生成短网址/短链接
 * 核心逻辑待完善
 *
 * nginx
 * rewrite ^/(.*)$ /index.php?url=$1 last;
 *
 * 数据库字段
 * id
 * rand_key
 * short_url
 * member_id
 * create_time
 */

class ShortUrl
{
    protected static $domainName = 'https://www.baidu.com?';
    protected static $expireTime = 7200;

    /**
     * @Author 可达鸭
     * @Description 生成短链接
     * @Date 2022/8/30 23:13:25
     * @param $param
     * @param $member_id
     * @return bool|string
     */
    public static function createShortUrl($param = NULL, $member_id = NULL)
    {
        if (trim(empty($param))) {
            return Result::fail('参数校验失败');
        }
        if (trim(empty($member_id))) {
            return Result::fail('参数校验失败');
        }

        // 生成短链接插入数据库
        $shortUrl = self::$domainName . $param;
        $cipher = base64_encode($shortUrl. '&member_id=' . $member_id);
        $shortKey = self::createKey($cipher);

        self::checkShortExists($shortKey,$shortUrl,$member_id);

        return Result::success(array('url' => $shortKey));
    }

    /**
     * 生成短链接的key
     * @param $url
     * @return string
     */
    protected static function createKey($url): string
    {
        $doUrl = crc32($url);
        $result = sprintf("%u", $doUrl);
        $sUrl = '';
        while ($result > 0) {
            $s = $result % 62;
            if ($s > 35) {
                $s = chr($s + 61);
            } elseif ($s > 9 && $s <= 35) {
                $s = chr($s + 55);
            }
            $sUrl .= $s;
            $result = floor($result / 62);
        }
        return $sUrl;
    }

    /**
     * @Author 可达鸭
     * @Description 检测短链接是否生成
     * @Date 2022/8/30 23:08:14
     * @param $shortKey
     * @param $shortUrl
     * @param $member_id
     * @return bool|string|void
     */
    public static function checkShortExists($shortKey, $shortUrl, $member_id)
    {
        if (trim(empty($shortKey))) {
            return Result::fail('参数校验失败');
        }
        if (trim(empty($shortUrl))) {
            return Result::fail('参数校验失败');
        }

        /**
         * 检测短链接是否生成,确保key的唯一性
         * 1.过滤过期的key
         * 2.key member_id 是否存在
         */
        $result = '';
        $randKey = '';
        if($result){
            return Result::success(array('url' => $randKey));
        }
    }

    /**
     * @Author 可达鸭
     * @Description
     * @Date 2022/8/30 23:13:10
     * @return bool|string|void
     */
    public static function requestShortUrl()
    {
        $key = $_GET["id"];
        if (trim(empty($key))) {
            return Result::fail('参数校验失败');
        }

        // 数据库查询
        $result = '';
        if(!$result){
            return Result::fail('参数校验失败');
        }
        $ShortUrl = '';
        header("Location: $ShortUrl");
    }
}