<?php
/**
 * Created by PhpStorm.
 * User: zoowii
 * Date: 14-2-19
 * Time: 上午12:54
 */

namespace RP\util;


class UserCommon
{

    public static function encryptPassword($password, $salt)
    {
        return Common::md5(Common::md5(trim($password)) . trim($salt));
    }

} 