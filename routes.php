<?php
require_once 'vendor/autoload.php';
use RP\Px\Router;

$router = new Router();
//$router->addRoute('GET', '/post/:id/:name', 'test');
$router->includeActions(array(
    'RP\controllers\SiteController',
    'RP\controllers\XiamiController',
    'RP\controllers\QishuController',
    'RP\controllers\OAuthController',
    'RP\controllers\MusicController',
    'RP\controllers\CloudController',
));
return $router;

