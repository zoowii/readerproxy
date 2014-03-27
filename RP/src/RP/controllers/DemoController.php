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
        $this->any('/hello/:name', 'hello');
        $this->get('/test_url_for', 'testUrlReverse');
    }

    public function helloAction()
    {
        return $this->renderPartial('demo/hello.php');
    }

    public function hello($name, $welcome = 'Hello')
    {
        return "$welcome, $name!";
    }

    public function testUrlReverse()
    {
        $url1 = $this->urlFor(null, 'hello', 'zoowii');
        $url2 = $this->urlFor('XiamiController', 'searchAction', 'WOW');
        var_dump($url1);
        var_dump($url2);
    }
} 