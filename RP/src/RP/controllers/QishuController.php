<?php
/**
 * Created by PhpStorm.
 * User: zoowii
 * Date: 14-2-3
 * Time: 下午11:26
 */

namespace RP\controllers;


class QishuController extends BaseController
{
    private $crawler;

    public function __construct()
    {
        parent::__construct();
        $this->crawler = new \RP\SiteCrawl\QishuCrawler();
        $this->bind('title', '我的小工具们 -- 小说');
        $this->bind('currentAppId', 'tool_novel');
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
        return $this->render('search/novel_list.php');
    }

    public function download()
    {
        $info = array(
            'source' => $_GET['source']
        );
        $url = $this->getDownloadUrl($info);
        if ($url) {
            header("Location: $url");
            exit;
        } else {
            return <<<END
Can't find the download url.
END;

        }
    }

    public function getDownloadUrl($info)
    {
        $source = $info['source'];
        $result = $this->crawler->getDownloadUrl($source);
        return $result;
    }
} 