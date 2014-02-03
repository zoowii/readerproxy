<?php
/**
 * 奇书网www.qishu.com的爬虫
 * Created by PhpStorm.
 * User: zoowii
 * Date: 14-2-3
 * Time: 下午11:23
 */

namespace RP\SiteCrawl;


class QishuCrawler
{
    public function search($name)
    {
        $url = "http://s.qisuu.com/cse/search?q=" . urlencode(trim($name)) . "&s=2672242722776283010";
        var_dump($url);
        $res = file_get_contents($url);
        echo $res;
    }
} 