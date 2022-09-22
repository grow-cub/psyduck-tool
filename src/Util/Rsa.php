<?php

namespace Psyduck\Util;

/**
 * js如何使用RSA加密或解密数据
 * github https://github.com/travist/jsencrypt
 * 官方主页 http://www.travistidwell.com/jsencrypt
 */

class Rsa
{
    private static $publicKey = './other/Rsa/rsa_public_key.pem';
    private static $privateKey = './other/Rsa/rsa_private_key.pem';

    private static $decodeFail = 'Decryption failed. Please check the RSA key';

    private static function getPublicKey()
    {
        return file_get_contents(self::$publicKey);
    }

    private static function getPrivateKey()
    {
        return file_get_contents(self::$privateKey);
    }

    /**
     * 公钥加密，私钥解密（推荐）
     */

    /**
     * 公钥加密
     * @param $data
     * @return string
     */
    public static function publicEncode($data): string
    {
        $key = openssl_pkey_get_public(self::getPublicKey());
        if (!$key) {
            return Result::fail('公钥不可用');
        }
        $_ret = openssl_public_encrypt($data, $crypted, $key);
        if (!$_ret) {
            return Result::fail(self::$decodeFail);
        }
        return base64_encode($crypted);
    }

    /**
     * 私钥解密
     * @param $data
     * @return mixed|string
     */
    public static function privateDecode($data){
        $private_key = openssl_pkey_get_private(self::getPrivateKey());
        if (!$private_key) {
            return Result::fail('私钥不可用');
        }
        $_ret = openssl_private_decrypt(base64_decode($data), $decrypted, $private_key);
        if (!$_ret) {
            return Result::fail(self::$decodeFail);
        }
        return $decrypted;
    }

    /**
     * 私钥加密，公钥解密
     */

    /**
     * 私钥加密
     * @param $data
     * @return string
     */
    public static function privateEncode($data): string
    {
        $key = openssl_pkey_get_private(self::getPrivateKey());
        if (!$key) {
            return Result::fail('私钥不可用');
        }
        $_ret = openssl_private_encrypt($data, $crypted, $key);
        if (!$_ret) {
            return Result::fail(self::$decodeFail);
        }
        return base64_encode($crypted);
    }

    /**
     * 公钥解密
     * @param $data
     * @return mixed|string
     */
    public static function publicDecode($data)
    {
        $key = openssl_pkey_get_public(self::getPublicKey());
        if (!$key) {
            return Result::fail('公钥不可用');
        }
        $_ret = openssl_public_decrypt(base64_decode($data), $decrypted, $key);
        if (!$_ret) {
            return Result::fail(self::$decodeFail);
        }
        return $decrypted;
    }

    /**
     * 私钥加密数据
     * 报错详情可见openssl_error_string()
     * @param $strData //待加密的字符串
     * @return false|string
     */
    public static function encryptDataByPrivate($strData)
    {
        $privateKeyString = self::getPrivateKey();
        // 私钥加密
        $privateKey = openssl_pkey_get_private($privateKeyString);

        $signature = null;
        if (openssl_sign($strData, $signature, $privateKey, OPENSSL_ALGO_SHA256)) {
            return base64_encode($signature);
        } else {
            return false;
        }
    }

    /**
     * 公钥验证数据是否被修改
     * 报错详情可见openssl_error_string()
     * @param $strData
     * @param $signature
     * @return bool
     */
    public static function verifyDataByPublic($strData,$signature): bool
    {
        $publicKeyString = self::getPublicKey();
        $publicKey = openssl_pkey_get_public($publicKeyString);

        $success = openssl_verify($strData, base64_decode($signature), $publicKey, OPENSSL_ALGO_SHA256);
        if ($success === 1) {
            return true;
        } else {
            return false;
        }
    }
}