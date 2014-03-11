<?php
/**
 * Created by PhpStorm.
 * User: zoowii
 * Date: 14-3-9
 * Time: 上午1:32
 */

namespace RP\util;


class TmpFsUtil
{
    public static function getTmpFsPath()
    {
        $config = $_ENV['_CONFIG'];
        return $config['tmp_fs'];
    }

    public static function generateRandomTmpFilepath($filename = null)
    {
        if (!$filename) {
            $filename = Common::randomString(20);
        }
        return self::getTmpFsPath() . '/' . $filename;
    }
} 