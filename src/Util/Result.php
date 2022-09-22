<?php

namespace Psyduck\Util;

class Result
{
    /**
     * 成功响应
     * @param $data
     * @return bool|string
     */
    public static function success($data = null)
    {
        return self::response(200, "success", $data);
    }

    /**
     * 失败响应
     * @param $data
     * @return bool|string
     */
    public static function fail($data = null)
    {
        return self::response(403, "fail", $data);
    }

    /**
     * @param int $code
     * @param string $msg
     * @param $data
     * @return bool|string
     */
    public static function response(int $code = 0, string $msg = '', $data = null)
    {
        $obj = [
            'code' => $code,
            'msg' => $msg,
        ];
        if (!is_null($data)) {
            $obj['data'] = $data;
            $obj = Hump::ergodicArraySnakeToHump($obj);
        }
        echo json_encode($obj);die;
    }
}
