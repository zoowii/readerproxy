<?php
require 'vendor/autoload.php';
use Pux\Mux;
$mux = new Mux();
$demoMux = new Mux();
$demoMux->add('/get', ['HelloController','helloAction']);
$mux->mount('/demo', $demoMux);
$mux->get('/product', ['ProductController','listAction']);
$mux->get('/product/:id', ['ProductController','itemAction'] , [
    'require' => [ 'id' => '\d+', ],
    'default' => [ 'id' => '1', ]
]);
$mux->post('/product/:id', ['ProductController','updateAction'] , [
    'require' => [ 'id' => '\d+', ],
    'default' => [ 'id' => '1', ]
]);
$mux->delete('/product/:id', ['ProductController','deleteAction'] , [
    'require' => [ 'id' => '\d+', ],
    'default' => [ 'id' => '1', ]
]);
return $mux;