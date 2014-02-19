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

    /**
     * 获取主机名，或者域名，或者IP
     */
    public static function getHostName()
    {
        return $_SERVER['HTTP_HOST'];
    }

    /**
     * 获取形如：http://localhost:8080 或者http://host 这一类的，和用户请求的一样
     */
    public static function getHost()
    {
        return 'http://' . self::getHostName(); // TODO
    }
} 