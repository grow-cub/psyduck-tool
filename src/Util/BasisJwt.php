<?php

namespace Psyduck\Util;

class BasisJwt
{
    //储存jwt数据
    public static $payLoadData;

    //头部
    private static $header = array(
        'alg' => 'HS256', //生成signature的算法
        'typ' => 'JWT'    //类型
    );

    //使用HMAC生成信息摘要时所使用的密钥
    private static $key = '123456';
    private static $iss = 'jwt_admin';
    private static $jti = 'JWT';

    /**
     * 获取jwt token
     * @param array $payload jwt载荷   格式如下非必须
     * [
     *  'iss'=>'jwt_admin',  //该JWT的签发者
     *  'iat'=>time(),  //签发时间
     *  'exp'=>time()+7200,  //过期时间
     *  'nbf'=>time()+60,  //该时间之前不接收处理该Token
     *  'sub'=>'www.admin.com',  //面向的用户
     *  'jti'=>md5(uniqid('JWT').time())  //该Token唯一标识
     * ]
     * @return bool|string
     */
    public static function getToken(array $payload)
    {
        if (is_array($payload)) {
            $base64header = self::base64UrlEncode(json_encode(self::$header, JSON_UNESCAPED_UNICODE));
            $base64payload = self::base64UrlEncode(json_encode($payload, JSON_UNESCAPED_UNICODE));
            return $base64header . '.' . $base64payload . '.' . self::signature($base64header . '.' . $base64payload, self::$key, self::$header['alg']);
        } else {
            return false;
        }
    }

    /**
     * 验证token是否有效,默认验证exp,nbf,iat时间
     * @param array $payload 需要验证的payload数据
     * @return array|false
     */
    private static function checkToken(array $payload)
    {
        //签发时间大于当前服务器时间验证失败
        if (isset($payload['iat']) && $payload['iat'] > time())
            return false;

        //过期时间小宇当前服务器时间验证失败
        if (isset($payload['exp']) && $payload['exp'] < time())
            return false;

        //该nbf时间之前不接收处理该Token
        if (isset($payload['nbf']) && $payload['nbf'] > time())
            return false;

        return $payload;
    }

    /**
     * base64UrlEncode   https://jwt.io/  中base64UrlEncode编码实现
     * @param string $input 需要编码的字符串
     * @return string
     */
    private static function base64UrlEncode(string $input)
    {
        return str_replace('=', '', strtr(base64_encode($input), '+/', '-_'));
    }

    /**
     * base64UrlEncode  https://jwt.io/  中base64UrlEncode解码实现
     * @param string $input 需要解码的字符串
     * @return bool|string
     */
    private static function base64UrlDecode(string $input)
    {
        $remainder = strlen($input) % 4;
        if ($remainder) {
            $addlen = 4 - $remainder;
            $input .= str_repeat('=', $addlen);
        }
        return base64_decode(strtr($input, '-_', '+/'));
    }

    /**
     * HMACSHA256签名   https://jwt.io/  中HMACSHA256签名实现
     * @param string $input 为base64UrlEncode(header).".".base64UrlEncode(payload)
     * @param string $key
     * @param string $alg   算法方式
     * @return mixed
     */
    private static function signature(string $input, string $key, string $alg = 'HS256')
    {
        $alg_config = array(
            'HS256' => 'sha256'
        );
        return self::base64UrlEncode(hash_hmac($alg_config[$alg], $input, $key, true));
    }

    /**
     * 获取jwt数据
     * @param $Token
     * @return false|mixed
     */
    private static function getPayload($Token)
    {
        $tokens = explode('.', $Token);
        if (count($tokens) != 3)
            return false;

        list($base64header, $base64payload, $sign) = $tokens;

        //获取jwt算法
        $base64decodeheader = json_decode(self::base64UrlDecode($base64header), JSON_OBJECT_AS_ARRAY);
        if (empty($base64decodeheader['alg']))
            return false;

        //签名验证
        if (self::signature($base64header . '.' . $base64payload, self::$key, $base64decodeheader['alg']) !== $sign)
            return false;

        return json_decode(self::base64UrlDecode($base64payload), JSON_OBJECT_AS_ARRAY);
    }

    /**
     * 组装jwt数据
     * @param $sub
     * @param $iss
     * @param $iat
     * @param $nfb
     * @return array
     */
    public static function createPayload($sub, $iss = null, $iat = null, $nfb = null): array
    {
        $iat = $iat ?? time();
        $iss = $iss ?? self::$iss;
        $nfb = $nfb ?? 0;
        return array(
            'iss' => $iss, //该JWT的签发者
            'iat' => $iat, //签发时间
            'exp' => $iat + 7200, //过期时间
            'nbf' => $iat + $nfb, //该时间之前不接收处理该Token
            'sub' => $sub, //面向的用户
            'jti' => md5(uniqid(self::$jti) . time()) //该Token唯一标识
        );
    }

    /**
     * 验证token
     * @return false
     */
    public static function verifyToken(): bool
    {
        $headerInfo = Header::getHeader();
        if(!isset($headerInfo['token'])){
            return false;
        }
        $payLoad = self::getPayload($headerInfo['token']);
        if (!$payLoad) {
            return false;
        }
        $result = self::checkToken($payLoad);
        if (!$result) {
            return false;
        }
        self::$payLoadData = $payLoad;
        return true;
    }

    /**
     * 刷新token
     * @return void
     */
    public static function refreshToken()
    {
        if(isset(self::$payLoadData['sub'])){
            $payLoad = self::createPayload(self::$payLoadData['sub']);
            $token = self::getToken($payLoad);
            header('token:' . $token);
        }
    }
}

////获取token
//$sub = 1;
//$jwt = new basisJwt;
//$array = $jwt->createPayload($sub);
//$token = $jwt->getToken($array);
//echo json_encode(['token' => $token]);
//
////使用并判断是否过期
//$result = $jwt::verifyToken();
//if(!$result){
//    $jwt::refreshToken();
//}