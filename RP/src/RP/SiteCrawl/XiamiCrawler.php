<?php
/**
 * Created by PhpStorm.
 * User: zoowii
 * Date: 14-2-15
 * Time: 下午4:28
 */

namespace RP\SiteCrawl;

use RP\core\CCache;
use RP\util\HttpClient;
use Sunra\PhpSimple\HtmlDomParser;

class XiamiCrawler
{

    /**
     * 反向解码虾米网的下载链接，来自虾米网播放器的flash的反向工程
     * 解码算法来自：https://github.com/dndx/xiamiurl/blob/master/xiami_decode.py
     * @param $location
     */
    public function decodeXiamiLocation($location)
    {
        $location = trim($location);
        $result = array();
        $line = intval($location[0]);
        $locLen = strlen($location);
        $rows = intval(($locLen - 1) / $line);
        $extra = ($locLen - 1) % $line;
        $location = substr($location, 1);
        for ($i = 0; $i < $extra; ++$i) {
            $start = ($rows + 1) * $i;
            $end = ($rows + 1) * ($i + 1);
            $result[] = substr($location, $start, $end - $start);
        }
        for ($i = 0; $i < $line - $extra; ++$i) {
            $start = ($rows + 1) * $extra + ($rows * $i);
            $end = ($rows + 1) * $extra + ($rows * $i) + $rows;
            $result[] = substr($location, $start, $end - $start);
        }
        $url = '';
        for ($i = 0; $i < $rows + 1; ++$i) {
            for ($j = 0; $j < $line; ++$j) {
                if ($j >= count($result) || $i >= strlen($result[$j])) {
                    continue;
                }
                $url .= $result[$j][$i];
            }
        }
        $url = urldecode($url);
        $url = str_replace('^', '0', $url);
        return $url;
    }

    /**
     * 获取歌曲的元信息
     * @param $songId
     * @return array
     */
    public function getTrackInfo($songId)
    {
        $url = "http://www.xiami.com/song/playlist/id/$songId/object_name/default/object_id/0";
        $cache = CCache::instance();
        $cache_key = "crawler_$url";
        $res = $cache->get($cache_key);
        if ($res == null) {
            $res = HttpClient::fetch_page('xiami', $url);
            $cache->set($cache_key, $res, -1);
        }
        $dom = HtmlDomParser::str_get_html($res);
        $trackEl = $dom->find('track', 0);
        $songId = $trackEl->find('song_id', 0)->innertext;
        $albumId = $trackEl->find('album_id', 0)->innertext;
        $artistId = $trackEl->find('artist_id', 0)->innertext;
        $location = $trackEl->find('location', 0)->innertext; // TODO: decode
        $lyricUrl = $trackEl->find('lyric', 0)->innertext;
        $picUrl = $trackEl->find('pic', 0)->innertext;
        $url = $this->decodeXiamiLocation($location);
        return array(
            'song_id' => $songId,
            'album_id' => $albumId,
            'artist_id' => $artistId,
            'location' => $location,
            'url' => $url,
            'lyric_url' => $lyricUrl,
            'picture_url' => $picUrl
        );
    }

    public function search($name, $page = 1)
    {
        $url = "http://www.xiami.com/search/song/page/$page?key=" . urlencode(trim($name));
        $cache = CCache::instance();
        $cache_key = "crawler_$url";
        $res = $cache->get($cache_key);
        if ($res === null) {
            $res = HttpClient::fetch_page('xiami', $url);
            $cache->set($cache_key, $res, 3600);
        }
        $dom = HtmlDomParser::str_get_html($res);
        $mainDom = $dom->find('.search_result', 0);
        $total = intval($mainDom->find('.seek_counts', 0)->find('b', 0)->innertext);
        $trackEles = $mainDom->find('table.track_list tr');
        $tracks = array();
        foreach ($trackEles as $trackEle) {
            try {
                $nameEle = $trackEle->find('.song_name a', 0);
                if (!$nameEle) {
                    continue;
                }
                $name = $nameEle->innertext;
                $songIdText = $nameEle->href;
                preg_match('/\\d+/', $songIdText, $matches);
                $songId = $matches[0];
                $artistEl = $trackEle->find('.song_artist a', 0);
                $artist = $artistEl->title;
                $albumEl = $trackEle->find('.song_album a', 0);
                $album = $albumEl->innertext;
                $trackMetaInfo = $this->getTrackInfo($songId);
                // TODO: get track id and download url
                $tracks[] = array(
                    'name' => $name,
                    'artist' => $artist,
                    'album' => $album,
                    'artist_id' => $trackMetaInfo['artist_id'],
                    'album_id' => $trackMetaInfo['album_id'],
                    'source' => '虾米网',
                    'url' => $trackMetaInfo['url'],
                    'picture_url' => $trackMetaInfo['picture_url'],
                    'id' => $songId,
                    'listen_url' => "http://www.xiami.com/song/play?ids=/song/playlist/id/$songId/object_name/default/object_id/0",
                    'meta_info' => $trackMetaInfo
                );
            } catch (\Exception $e) {

            }
        }
        $pagerEl = $mainDom->find('.all_page', 0);
        $currentPage = intval($pagerEl->find('.p_curpage', 0)->innertext);
        $result = array(
            'paginator' => array(
                'current' => $currentPage,
                'total' => $total
            ),
            'data' => $tracks
        );
        return $result;
    }
} 