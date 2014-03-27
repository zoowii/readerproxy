<?php
/**
 * Created by PhpStorm.
 * User: zoowii
 * Date: 14-3-11
 * Time: 下午10:30
 */

namespace RP\Px;

use RP\util\StringUtil;

class UrlPattern
{
    /**
     * 用来匹配路径的模式
     * 模式的规则是，如果有变量，则变量只能是字母或下划线开头，只包括字母或数字或下划线
     * 变量前冠以冒号 :
     * 比如 /post/:id (这种只匹配不包含带有/的字符串)
     * TODO: 另外一种情况是，可以指定类型(int, string, path, boolean等)，比如 /site/<path:siteUrl>
     */
    protected $pattern = null;
    protected $variablesCount = 0;
    protected $sourcePattern = null;

    /**
     * @param $pattern
     */
    public function __construct($pattern)
    {
        $this->sourcePattern = $pattern;
        $this->loadPattern($pattern);
    }

    /**
     * 找到模式字符串中所有变量（包括开头的冒号:）
     * @param $pattern
     */
    protected function loadPattern($pattern)
    {
//        preg_match_all('/:[a-zA-Z_][a-zA-Z_0-9]*/', $pattern, $matches);
//        $this->variablesCount = count($matches[0]);
        $pattern = preg_replace('/(:[a-zA-Z_][a-zA-Z_0-9]*)/', '([^/]+)', $pattern);
        $this->pattern = $pattern;
    }

    /**
     * 判断模式和路径是否匹配
     * 如果不匹配，返回false
     * 如果匹配，返回匹配参数的列表（如果没有匹配参数，返回空数组）
     */
    public function match($baseUrl, $path)
    {
        $pattern = $baseUrl . ($this->pattern);
        $pattern = preg_replace('/\\//', '\\/', $pattern);
        $pattern = '/^' . $pattern . '$/';
        $matchResult = preg_match_all($pattern, $path, $matches);
        if ($matchResult < 1) {
            return false;
        } else {
            $matches = array_slice($matches, 1);
            $matches = array_map(function ($match) {
                return $match[0];
            }, $matches);
            return $matches;
        }
    }

    /**
     * 和match函数相反，根据参数反向构造出url
     */
    public function unMatch($baseUrl, $params)
    {
        $matchResult = preg_match_all('/(:[a-zA-Z_][a-zA-Z_0-9]*)/', $this->sourcePattern, $matches);
        if ($matchResult < 1) {
            return $baseUrl . $this->sourcePattern;
        } else {
            $pattern = $this->sourcePattern;
            $matches = array_slice($matches, 1);
            $matches = array_map(function ($match) {
                return $match[0];
            }, $matches);
            $len = count($matches) < count($params) ? count($matches) : count($params);
            // FIXME: 当$params的值中有:abc这种形式的内容时可能出现BUG，要换成使用position替换或者正则替换的方式，或者把原字符串拆成多份，替换后再合并
            for ($i = 0; $i < $len; ++$i) {
                $match = $matches[$i];
                $param = $params[$i];
                $pattern = str_replace($match, $param, $pattern);
            }
            // find positions for $matches
//            $positions = StringUtil::findPositions($this->sourcePattern, $matches);
//            $offset = 0; // 因为替换了字符串导致的偏差
//            for($i=0;$i<$len;++$i) {
//                $pos = $positions[$i];
//                $param = $params[$i];
//                $match = $matches[$i];
//                if($pos>=0) {
//                    $sLen = count($match);
//                    str
//                }
//            }
            return $baseUrl . $pattern;
        }
    }
} 