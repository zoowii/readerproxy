<?php
/**
 * Created by PhpStorm.
 * User: zoowii
 * Date: 14-2-15
 * Time: 下午4:26
 */

namespace RP\controllers;

use RP\SiteCrawl\XiamiCrawler;


class XiamiController extends BaseController
{
    /**
     * @var \RP\SiteCrawl\XiamiCrawler
     */
    private $crawler;

    protected $baseUrl = '/sites/xiami';

    protected function routes()
    {
        $this->get(array('', '/search/:name'), 'searchAction');
        $this->get('/lyric/:id', 'downloadLyricAction');
        $this->get('/info/:id', 'trackMetaInfoAction');
    }

    public function __construct()
    {
        parent::__construct();
        $this->crawler = new XiamiCrawler();
        $this->bind('title', '我的小工具们 -- 音乐');
        $this->bind('currentAppId', 'tool_music');
        $this->bind('search_keyword', '董贞');
    }

    public function searchAction($name = null)
    {
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        if (!empty($name)) {
            $result = $this->crawler->search($name, $page);
        } else {
            $result = array(
                'data' => array(),
                'paginator' => array(
                    'current' => 0,
                    'total' => 0
                )
            );
        }
        $this->bind('result', $result);
        if (!empty($name)) {
            $this->bind('search_keyword', $name);
        }
        return $this->render('search/music_list.php');
    }

    public function downloadLyricAction($id)
    {
        $trackInfo = $this->crawler->getTrackInfo($id);
        $lyricUrl = $trackInfo['lyric_url'];
        header('Location: ' . $lyricUrl);
        exit;
    }

    public function trackMetaInfoAction($id)
    {
        $trackMetaInfo = $this->crawler->getTrackInfo($id);
        return json_encode($trackMetaInfo);
    }

} 