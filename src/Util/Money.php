<?php

namespace Psyduck\Util;

class Money
{
    /**
     * 元转分
     * @param $price
     * @return float|int
     */
    public static function rmbToPenny($price)
    {
        return floatval($price) * 100;
    }

    /**
     * 分转元
     * @param $money
     * @return string
     */
    public static function pennyToRmb($money): string
    {
        return sprintf("%.2f", $money / 100);
    }
}