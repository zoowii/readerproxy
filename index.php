<?php
require_once 'vendor/autoload.php';
define('ROOT_DIR', __DIR__);
define('PATH_BASE', '/index.php'); // 用来路由的那一部分path之前的内容，在使用路由反转时需要
$config = require 'config.php';
$_ENV['_CONFIG'] = $config;
$path = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '/';
$router = require 'routes.php';
echo $router->dispatch($path);
