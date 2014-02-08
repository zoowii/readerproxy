<?php
require_once 'vendor/autoload.php';
use Pux\Mux;
use \RP\controllers\ProductController;
use \RP\controllers\DemoController;

$mux = new Mux();
$mux->expand = true;
$demoMux = new Mux();
$demoMux->add('/get', array('\RP\controllers\DemoController', 'helloAction'));
$mux->mount('/demo', $demoMux);
$mux->get('/product', array('\RP\controllers\ProductController', 'listAction'));
$mux->get('/product/:id', array('\RP\controllers\ProductController', 'itemAction'), array(
    'require' => array('id' => '\d+',),
    'default' => array('id' => '1',)
));
$mux->post('/product/:id', array('\RP\controllers\ProductController', 'updateAction'), array(
    'require' => array('id' => '\d+',),
    'default' => array('id' => '1',)
));
$mux->delete('/product/:id', array('\RP\controllers\ProductController', 'deleteAction'), array(
    'require' => array('id' => '\d+',),
    'default' => array('id' => '1',)
));
$mux->get('/sites/qishu/search/:name', array(
    '\RP\controllers\QishuController', 'searchAction'
), array(
    'require' => array('name' => '[\w\W]+')
));
$mux->get('/sites/qishu/download', array(
    '\RP\controllers\QishuController', 'download'
));
$mux->get('/', array('\RP\controllers\SiteController', 'indexAction'));
return $mux;