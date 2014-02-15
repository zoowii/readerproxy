<?php
/**
 * Created by PhpStorm.
 * User: zoowii
 * Date: 14-2-3
 * Time: 下午10:10
 */

namespace RP\controllers;


use Composer\Cache;

class SiteController extends \RP\core\CController
{
    public function __construct()
    {
        parent::__construct();
        $this->setLayout('_layout/website.php');
    }

    public function indexAction()
    {
        $this->bind('title', 'Home');
//        $id = rand(1, 99999999);
//        $this->db()->insert('test', array(
//            'id' => $id,
//            'name' => "test$id"
//        ));
//        var_dump($this->db()->count('test'));
        $cache = \RP\core\CCache::instance();
        $cache->create_table_if_not_exist();
        $cache->set('test', 'test', -1);
//        var_dump($cache->get('test'));
//        $cache->remove('test');
        return $this->render('site/index.php');
    }
} 