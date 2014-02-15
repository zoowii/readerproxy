<?php
/**
 * Created by PhpStorm.
 * User: zoowii
 * Date: 14-2-15
 * Time: 下午4:26
 */

namespace RP\controllers;

use RP\core\CController;
use RP\SiteCrawl\XiamiCrawler;


class XiamiController extends CController
{
    /**
     * @var \RP\SiteCrawl\XiamiCrawler
     */
    private $crawler;

    public function __construct()
    {
        parent::__construct();
        $this->setLayout('_layout/website.php');
        $this->crawler = new XiamiCrawler();
    }

    public function searchAction($name)
    {
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $result = $this->crawler->search($name, $page);
        $this->bind('result', $result);
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