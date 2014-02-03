<?php
require_once 'vendor/autoload.php';
use Pux\Mux;
use \RP\controllers\ProductController;
use \RP\controllers\DemoController;

$mux = new Mux();
$mux->expand = true;
$demoMux = new Mux();
$demoMux->add('/get', ['\RP\controllers\DemoController', 'helloAction']);
$mux->mount('/demo', $demoMux);
$mux->get('/product', ['\RP\controllers\ProductController', 'listAction']);
$mux->get('/product/:id', ['\RP\controllers\ProductController', 'itemAction'], [
    'require' => ['id' => '\d+',],
    'default' => ['id' => '1',]
]);
$mux->post('/product/:id', ['\RP\controllers\ProductController', 'updateAction'], [
    'require' => ['id' => '\d+',],
    'default' => ['id' => '1',]
]);
$mux->delete('/product/:id', ['\RP\controllers\ProductController', 'deleteAction'], [
    'require' => ['id' => '\d+',],
    'default' => ['id' => '1',]
]);
$mux->get('/sites/qishu/search/:name', [
    '\RP\controllers\QishuController', 'searchAction'
], [
    'require' => ['name' => '\w+']
]);
$mux->get('/', ['\RP\controllers\SiteController', 'indexAction']);
return $mux;