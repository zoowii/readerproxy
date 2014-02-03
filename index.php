<?php
require 'vendor/autoload.php'; // use PCRE patterns you need Pux\PatternCompiler class.
use Pux\Executor;

class ProductController {
    public function listAction() {
        return 'product list';
    }
    public function itemAction($id) {
        return "product $id";
    }
    public function updateAction($id) {
        return "update product $id";
    }
    public function deleteAction($id) {
        return "delete product $id";
    }
}
class HelloController {
    public function helloAction() {
        return 'hello';
    }
}
$mux = require 'routes.php';
//$mux = new Pux\Mux;
//$mux->get('/product', ['ProductController','listAction']);
//$mux->get('/product/:id', ['ProductController','itemAction'] , [
//    'require' => [ 'id' => '\d+', ],
//    'default' => [ 'id' => '1', ]
//]);
//$mux->post('/product/:id', ['ProductController','updateAction'] , [
//    'require' => [ 'id' => '\d+', ],
//    'default' => [ 'id' => '1', ]
//]);
//$mux->delete('/product/:id', ['ProductController','deleteAction'] , [
//    'require' => [ 'id' => '\d+', ],
//    'default' => [ 'id' => '1', ]
//]);
$route = $mux->dispatch($_SERVER['PATH_INFO'] );
//$route = $mux->dispatch('/product/1');
echo Executor::execute($route);
