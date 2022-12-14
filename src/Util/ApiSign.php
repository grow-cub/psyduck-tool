<?php

namespace Psyduck\Util;

/**
 * 一般用于数据传输
 */

class ApiSign
{
    // 设置一个公钥(key)和私钥(secret)，公钥用于区分用户，私钥加密数据，不能公开
    private static $key = "c4ca4238a0b923820dcc509a6f75849b";
    private static $secret = "28c8edde3d61a0411511d3b1866f0636";

    /**
     * @Author 可达鸭
     * @Description
     * $data = array(
        'username' => 'abc@qq.com',
        'sex' => '1',
        'age' => '16',
        'addr' => 'guangzhou',
        'key' => $key,
        'timestamp' => time(),
        );
     * @Date 2022/9/19 22:24:15
     * @param $secret
     * @param $data
     * @return string
     */
    public static function getSign($secret, $data): string
    {
        $data['timestamp'] = Time::getTimeStamp();
        // 对数组的值按key排序
        ksort($data);
        // 生成url的形式
        $params = http_build_query($data);
        // 生成sign
        return md5($params . $secret);

        // 发送的数据加上sign
        //$data['sign'] = getSign($secret, $data);
    }

    /**
     * @Author 可达鸭
     * @Description 后台验证sign是否合法
     * @Date 2022/9/19 22:52:37
     * @param $secret
     * @param $data
     * @return bool|string|void
     */
    public static function verifySign($secret, $data) {
        // 验证参数中是否有签名
        if (!isset($data['sign']) || !$data['sign']) {
            return Result::fail('签名不存在');
        }
        if (!isset($data['timestamp']) || !$data['timestamp']) {
            return Result::fail('参数不合法');
        }
        // 验证请求， 10分钟失效
        if (time() - $data['timestamp'] > 600) {
            return Result::fail('签名已过期');
        }
        $sign = $data['sign'];
        unset($data['sign']);
        ksort($data);
        $params = http_build_query($data);
        // $secret是通过key在api的数据库中查询得到
        $sign2 = md5($params . $secret);
        if ($sign != $sign2) {
            return Result::fail('签名校验失败');
        }
    }
}