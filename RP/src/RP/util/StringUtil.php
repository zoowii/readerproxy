<?php
/**
 * Created by PhpStorm.
 * User: zoowii
 * Date: 14-3-28
 * Time: 上午12:34
 */

namespace RP\util;


class StringUtil
{
    /**
     * 在$source中找到$subjects中各个字符串出现的位置，如果某一项不存在，则存放-1
     * 并且查找要按照$subjects中的先后顺序，$subjects中后面的找到的位置一定要在前面的$subject找到的位置之后
     * @param $source
     * @param array $subjects
     * @return array
     */
    public static function findPositions($source, array $subjects)
    {
        $result = array();
        $lastPos = null;
        foreach ($subjects as $subject) {
            $pos = strpos($source, $subject, $lastPos);
            if ($pos >= 0) {
                $lastPos = $pos;
            }
            $result[] = $pos;
        }
        return $result;
    }

    /**
     * 获取$source中最后一次出现$needle后面剩下的内容
     * @param $source
     * @param $needle
     * @return string
     */
    public static function getStringAfterLastPositionOf($source, $needle)
    {
        $splitted = explode($needle, $source);
        if (count($splitted) < 1) {
            return $source;
        } else {
            return $splitted[count($splitted) - 1];
        }
    }
} 