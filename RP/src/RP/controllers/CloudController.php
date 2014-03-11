<?php
/**
 * Created by PhpStorm.
 * User: zoowii
 * Date: 14-3-7
 * Time: 下午10:48
 */

namespace RP\controllers;


class CloudController extends BaseController
{
    protected $baseUrl = '/cloud';

    protected function routes()
    {
        $this->get('/appstore', 'appStoreAction');
    }

    public function appStoreAction()
    {
        return 'TODO';
    }

}