<?php
/**
 * Created by PhpStorm.
 * User: zoowii
 * Date: 14-3-7
 * Time: 下午9:40
 */

namespace RP\controllers;

use RP\core\CCache;
use RP\core\CController;
use RP\models\Role;
use RP\models\User;
use RP\models\CloudApp;
use RP\util\Common;
use RP\util\UserCommon;


class BaseController extends CController
{
    public function __construct()
    {
        parent::__construct();
        $this->setLayout('_layout/website.php');
        $this->block('_layout/left_nav.php');
        $currentUser = $this->currentUser();
        $this->bind('currentUser', $currentUser);
        $this->bind('title', '我的小工具们');
        $cloudApps = CloudApp::getAllGroupByCategory();
        $this->bind('cloudApps', $cloudApps);
        $apps = CloudApp::getAll();
        $this->bind('currentAppId', $apps[0]->app_id);
    }

    public function ajaxResponse($data, $type = 'json')
    {
        switch ($type) {
            case 'json':
            {
                return json_encode($data);
            }
            case 'xml':
            {
                return strval($data); // TODO
            }
            default:
                {
                return strval($data);
                }
        }
    }

    public function ajaxSuccess($data, $type = 'json')
    {
        return $this->ajaxResponse(array(
            'data' => $data,
            'success' => true
        ), $type);
    }

    public function ajaxFail($data, $type = 'json')
    {
        return $this->ajaxResponse(array(
            'data' => $data,
            'success' => false
        ), $type);
    }

} 