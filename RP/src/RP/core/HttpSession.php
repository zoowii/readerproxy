<?php
/**
 * Created by PhpStorm.
 * User: zoowii
 * Date: 14-3-12
 * Time: 上午12:18
 */

namespace RP\core;


class HttpSession
{
    private static $sessionStarted = false;

    public static function startSession()
    {
        if (!self::$sessionStarted) {
            session_start();
            self::$sessionStarted = true;
        }
    }
} 