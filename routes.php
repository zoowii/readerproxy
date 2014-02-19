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
$mux->get('/sites/xiami/search/:name', array(
    '\RP\controllers\XiamiController', 'searchAction'
), array(
    'require' => array('name' => '[\w\W]+')
));
$mux->get('/sites/qishu/download', array(
    '\RP\controllers\QishuController', 'download'
));
$mux->get('/sites/xiami/lyric/:id', array(
    '\RP\controllers\XiamiController', 'downloadLyricAction'
), array(
    'require' => array('id' => '\d+')
));
$mux->get('/sites/xiami/info/:id', array(
    '\RP\controllers\XiamiController', 'trackMetaInfoAction'
), array(
    'require' => array('id' => '\d+')
));
$mux->get('/', array('\RP\controllers\SiteController', 'indexAction'));
$mux->get('/login', array('RP\controllers\SiteController', 'loginPageAction'));
$mux->post('/login', array('RP\controllers\SiteController', 'loginAction'));
$mux->get('/logout', array('RP\controllers\SiteController', 'logoutAction'));
$mux->get('/profile', array('RP\controllers\SiteController', 'profileAction'));
$mux->get('/auth/qq/login', array('RP\controllers\OAuthController', 'qqLoginAction'));
$mux->get('/auth/callback/qq', array('RP\controllers\OAuthController', 'qqGetAccessTokenAction'));
$mux->post('/oauth/login', array('RP\controllers\OAuthController', 'oauthLoginAction'));
$mux->get('/oauth/bind', array('RP\controllers\OAuthController', 'oauthBindPageAction'));
$mux->post('/oauth/bind', array('RP\controllers\OAuthController', 'oauthBindAction'));
return $mux;