<?php
/**
 * Created by PhpStorm.
 * User: zoowii
 * Date: 14-2-8
 * Time: 下午5:37
 */

namespace RP\core;


class CCache
{
    private static $_instance = null;

    public static function instance()
    {
        if (self::$_instance === null) {
            // TODO: use alternate cache set by settings
            self::$_instance = new DbCache();
        }
        return self::$_instance;
    }

    public function get($name)
    {
        return self::instance()->get($name);
    }

    public function remove($name)
    {
        self::instance()->remove($name);
    }

    /**
     * @param $name
     * @param $value
     * @param int $expires 超时秒数，<= 0 时，表示永久缓存
     */
    public function set($name, $value, $expires)
    {
        self::instance()->set($name, $value, $expires);
    }
} 