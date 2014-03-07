<?php
/**
 * Created by PhpStorm.
 * User: zoowii
 * Date: 14-2-3
 * Time: 下午10:10
 */

namespace RP\controllers;


use RP\core\CCache;
use RP\models\Role;
use RP\models\User;
use RP\util\Common;
use RP\util\HttpClient;
use RP\util\UserCommon;

class SiteController extends BaseController
{

    public function indexAction()
    {
        $currentUser = $this->currentUser();
        if (!$currentUser) {
            return $this->redirect('/index.php/login');
        }
        $this->bind('title', '我的小工具们');
        $this->bind('currentUser', $currentUser);
        return $this->render('site/index.php');
    }

    public function loginPageAction()
    {
        $this->bind('title', '登录');
        return $this->render('site/login.php');
    }

    /**
     * 如果用户表为空，即初始化一个root用户，用于部署安装
     */
    private function createRootUserIfEmpty()
    {
        $usersCount = $this->db()->count(User::getFullTableName());
        if ($usersCount < 1) {
            $user = new User();
            $user->username = 'root';
            $user->email = 'root@localhost';
            $user->salt = Common::randomString(10);
            $user->password = UserCommon::encryptPassword('root', $user->salt);
            $rootRole = Role::getRootRole();
            $user->role_id = $rootRole->id;
            $user->alias_name = 'root';
            $user->save();
        }
    }

    public function loginAction()
    {
        $username = trim($this->POST('username'));
        $password = trim($this->POST('password'));
        $this->createRootUserIfEmpty();
        $user = User::findByUsernameAndPassword($username, $password);
        if ($user !== null && $user->isActive()) {
            $this->login($user);
            return $this->redirect('/');
        } else {
            return $this->redirect('/index.php/login'); // TODO: use flash message
        }
    }

    public function logoutAction()
    {
        $this->logout();
        return $this->redirect('/');
    }

    public function profileAction()
    {
        if ($this->isGuest()) {
            return $this->redirect('/');
        }
        $currentUser = $this->currentUser();
        $bindings = $currentUser->getAccountBindings();
        $this->bind('user', $currentUser);
        $this->bind('bindings', $bindings);
        return $this->render('site/profile.php');
    }

    /**
     * 跨域代理，因为浏览器有不允许跨域的限制，所以从服务器中转一下
     */
    public function crossProxyAction()
    {
        $url = $this->POST('url');
        $method = $this->POST('method');
        if (isset($_POST['params'])) {
            $params = $this->POST('params');
        } else {
            $params = array();
        }
        $res = HttpClient::fetch_page('proxy', $url, $method, $params);
        return $res;
    }

} 