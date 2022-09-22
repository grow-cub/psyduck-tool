<?php

namespace Psyduck\Util;

class File
{
    /**
     * 获取文件信息
     * @param $file
     * @return array
     */
    public static function getFileInfo($file): array
    {
        return array(
            'file_name' => $file->getClientOriginalName(), // 获取文件名
            'file_ext' => $file->getClientOriginalExtension(), // 获取文件后缀
            'file_type' => $file->getClientMineType(), // 获取文件类型
            'file_byte' => $file->getSize(), // 文件字节
            'file_size' => self::byteSize($file->getSize()) // 文件大小
        );
    }

    /**
     * 获取具体的文件大小
     * @param $size
     * @return string
     */
    public static function byteSize($size): string
    {
        $kb = 1024; // 1KB（Kibibyte，千字节）=1024B，
        $mb = 1024 * $kb; //1MB（Mebibyte，兆字节，简称“兆”）=1024KB，
        $gb = 1024 * $mb; // 1GB（Gigabyte，吉字节，又称“千兆”）=1024MB，
        $tb = 1024 * $gb; // 1TB（Terabyte，万亿字节，太字节）=1024GB，
        $pb = 1024 * $tb; //1PB（Petabyte，千万亿字节，拍字节）=1024TB，
        $fb = 1024 * $pb; //1EB（Exabyte，百亿亿字节，艾字节）=1024PB，
        $zb = 1024 * $fb; //1ZB（Zettabyte，十万亿亿字节，泽字节）= 1024EB，
        $yb = 1024 * $zb; //1YB（Yottabyte，一亿亿亿字节，尧字节）= 1024ZB，
        $bb = 1024 * $yb; //1BB（Brontobyte，一千亿亿亿字节）= 1024YB

        if ($size < $kb) {
            return $size . " B";
        } else if ($size < $mb) {
            return round($size / $kb, 2) . " KB";
        } else if ($size < $gb) {
            return round($size / $mb, 2) . " MB";
        } else if ($size < $tb) {
            return round($size / $gb, 2) . " GB";
        } else if ($size < $pb) {
            return round($size / $tb, 2) . " TB";
        } else if ($size < $fb) {
            return round($size / $pb, 2) . " PB";
        } else if ($size < $zb) {
            return round($size / $fb, 2) . " EB";
        } else if ($size < $yb) {
            return round($size / $zb, 2) . " ZB";
        } else {
            return round($size / $bb, 2) . " YB";
        }
    }
}