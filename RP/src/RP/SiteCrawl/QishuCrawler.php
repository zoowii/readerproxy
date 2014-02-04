<?php
/**
 * 奇书网www.qishu.com的爬虫
 * Created by PhpStorm.
 * User: zoowii
 * Date: 14-2-3
 * Time: 下午11:23
 */

namespace RP\SiteCrawl;

use Sunra\PhpSimple\HtmlDomParser;

class QishuCrawler
{
    public function search($name, $page = 1)
    {
        // p=1 表示页数
        $p = $page - 1;
        $url = "http://s.qisuu.com/cse/search?q=" . urlencode(trim($name)) . "&p=$p&s=2672242722776283010";
        $res = \RP\util\HttpClient::fetch_page('qishu', $url);
        $dom = HtmlDomParser::str_get_html($res);
        $elements = $dom->find('div.result');
        $result = ['data' => [], 'paginator' => []];
        foreach ($elements as $ele) {
            $title = $ele->find('h3.c-title', 0)->find('a', 0)->innertext;
            $title = str_replace(' - txt全集下载,电子书 - 奇书网', '', $title);
            $title = str_replace('<em>', '', $title);
            $title = str_replace('</em>', '', $title);
            // TODO: 把$title中的《标题》全集中的标题抽取出来
            $description = $ele->find('div.c-content', 0)->find('div.c-abstract', 0)->innertext;
            $url = $ele->find('h3.c-title', 0)->find('a', 0)->href;
            $result['data'][] = [
                'title' => $title,
                'description' => $description,
                'url' => $url
            ];
        }
        $curPage = $dom->find('span.pager-current', 0)->innertext;
        $totalCountText = $dom->find('span.support-text', 1)->innertext;
        $totalCountText = str_replace('找到相关结果', '', $totalCountText);
        $totalCountText = str_replace('个', '', $totalCountText);
        if (strstr($totalCountText, ',')) {
            $totalCountText = strstr($totalCountText, ',');
            $totalCountText = str_replace(',', '', $totalCountText);
        }
        $result['paginator']['current'] = intval($curPage);
        $result['paginator']['total'] = intval($totalCountText);
        return $result;
    }
}