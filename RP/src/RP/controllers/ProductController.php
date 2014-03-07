<?php
/**
 * Created by PhpStorm.
 * User: zoowii
 * Date: 14-2-3
 * Time: 下午9:38
 */

namespace RP\controllers;


class ProductController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        // 这里可以设置默认layout
    }

    public function listAction()
    {
        return 'product list';
    }

    public function itemAction($id)
    {
        return "product $id";
    }

    public function updateAction($id)
    {
        return "update product $id";
    }

    public function deleteAction($id)
    {
        return "delete product $id";
    }
} 