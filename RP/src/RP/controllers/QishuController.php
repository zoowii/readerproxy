<?php
/**
 * Created by PhpStorm.
 * User: zoowii
 * Date: 14-2-3
 * Time: 下午11:26
 */

namespace RP\controllers;


class QishuController extends \RP\core\CController
{
    private $crawler;

    public function __construct()
    {
        parent::__construct();
        $this->setLayout('_layout/website.php');
        $this->crawler = new \RP\SiteCrawl\QishuCrawler();
    }

    public function searchAction($name)
    {
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $result = $this->crawler->search($name, $page);
        $this->bind('result', $result);
        return $this->render('search/list.php');
    }
} 