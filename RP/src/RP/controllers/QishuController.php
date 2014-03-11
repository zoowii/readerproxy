<?php
/**
 * Created by PhpStorm.
 * User: zoowii
 * Date: 14-2-3
 * Time: 下午11:26
 */

namespace RP\controllers;


use RP\SiteCrawl\QishuCrawler;

class QishuController extends BaseController
{
    private $crawler;

    protected $baseUrl = '/sites/qishu';

    protected function routes()
    {
        $this->get(array('', '/search/:name'), 'searchAction');
        $this->get('/download', 'download');
    }

    public function __construct()
    {
        parent::__construct();
        $this->crawler = new QishuCrawler();
        $this->bind('title', '我的小工具们 -- 小说');
        $this->bind('currentAppId', 'tool_novel');
        $this->bind('search_keyword', '红楼梦');
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