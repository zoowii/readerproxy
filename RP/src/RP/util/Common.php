<?php
/**
 * Created by PhpStorm.
 * User: zoowii
 * Date: 14-2-18
 * Time: 下午10:37
 */

namespace RP\util;


class Common
{
    public static function randomString($length = 20)
    {
        $source = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $res = '';
        for ($i = 0; $i < $length; ++$i) {
            $idx = rand(0, strlen($source) - 1);
            $res .= $source[$idx];
        }
        return $res;
    }

    public static function guid($prefix = '')
    {
        return uniqid($prefix);
    }

    public static function md5($source)
    {
        return md5($source);
    }
} 