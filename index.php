<?php
require_once 'vendor/autoload.php'; // use PCRE patterns you need Pux\PatternCompiler class.
define('ROOT_DIR', __DIR__);
$config = require 'config.php';
//define('CONFIG', $config);
$_ENV['_CONFIG'] = $config;
use Pux\Executor;

$mux = require 'routes.php';
$route = $mux->dispatch(isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '/');
echo Executor::execute($route);
