<?php
/**
 * Created by PhpStorm.
 * User: zoowii
 * Date: 14-2-3
 * Time: 下午9:34
 */

namespace RP\controllers;

class DemoController extends BaseController
{
    protected $baseUrl = '/demo';

    protected function routes()
    {
        $this->get('/get', 'helloAction');
    }

    public function helloAction()
    {
        return $this->render('demo/hello.php');
    }
} 