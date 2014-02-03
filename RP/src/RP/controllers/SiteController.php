<?php
/**
 * Created by PhpStorm.
 * User: zoowii
 * Date: 14-2-3
 * Time: 下午10:10
 */

namespace RP\controllers;


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
        return $this->render('site/index.php');
    }
} 