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
    protected function routes()
    {
        $this->get('/', 'indexAction');
        $this->get('/login', 'loginPageAction');
        $this->post('/login', 'loginAction');
        $this->get('/logout', 'logoutAction');
        $this->get('/profile', 'profileAction');
        $this->post('/sites/cross_proxy', 'crossProxyAction');
        $this->get('/register', 'registerPageAction');
        $this->post('/register', 'registerAction');
        $this->post('/change_password', 'changePasswordAction');
    }

    public function indexAction()
    {
        $currentUser = $this->currentUser();
        if (!$currentUser) {
            return $this->redirect('/index.php/login');
        }
        $this->bind('title', '我的小工具们');
        $this->bind('currentUser', $currentUser);
        $this->bindAllFlashes();
        return $this->render('site/index.php');
    }

    public function loginPageAction()
    {
        $this->bind('title', '登录');
        $this->clearLayouts();
        $this->setLayout('_layout/website.php');
        $this->bindAllFlashes();
        return $this->render('site/login.php');
    }

    public function registerPageAction()
    {
        $this->bind('title', '注册');
        $this->clearLayouts();
        $this->setLayout('_layout/website.php');
        $this->bindAllFlashes();
        return $this->render('site/register.php');
    }

    public function registerAction()
    {
        $username = $this->form('username');
        $password = $this->form('password');
        if (strlen($username) < 5) {
            $this->errorFlash("用户名长度不允许小于5");
            return $this->redirectToAction(null, 'registerPageAction');
        }
        if (strlen($password) < 5) {
            $this->errorFlash('密码长度不允许小于5');
            return $this->redirectToAction(null, 'registerPageAction');
        }
        $user = User::findByUsername($username);
        if (!is_null($user)) {
            $this->errorFlash("用户$username 已存在");
            return $this->redirectToAction(null, 'registerPageAction');
        }
        $user = new User();
        $user->username = $username;
        $user->email = null;
        $user->salt = Common::randomString(10);
        $user->password = UserCommon::encryptPassword($password, $user->salt);
        $commonRole = Role::getCommonUser();
        $user->role_id = $commonRole->id;
        $user->alias_name = $username;
        $user->saveOrUpdate();
        $this->successFlash("用户$username 注册成功!");
        return $this->redirectToAction(null, 'loginPageAction');
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
        $username = trim($this->form('username'));
        $password = trim($this->form('password'));
        $this->createRootUserIfEmpty();
        $user = User::findByUsernameAndPassword($username, $password);
        if ($user !== null && $user->isActive()) {
            $this->login($user);
            return $this->redirectToAction(null, 'indexAction');
        } else {
            $this->warningFlash('用户名或密码错误');
            return $this->redirectToAction(null, 'loginPageAction');
        }
    }

    public function logoutAction()
    {
        $this->logout();
        return $this->redirectToAction(null, 'indexAction');
    }

    public function profileAction()
    {
        if ($this->isGuest()) {
            return $this->redirectToAction(null, 'indexAction');
        }
        $currentUser = $this->currentUser();
        $bindings = $currentUser->getAccountBindings();
        $this->bind('user', $currentUser);
        $this->bind('bindings', $bindings);
        $this->bindAllFlashes();
        return $this->render('site/profile.php');
    }

    public function changePasswordAction()
    {
        if ($this->isGuest()) {
            $this->warningFlash('请先登录');
            return $this->redirectToAction(null, 'profileAction');
        }
        $password = $this->form('password');
        if (strlen($password) < 5) {
            $this->warningFlash('密码长度需要不小于5');
            return $this->redirectToAction(null, 'profileAction');
        }
        $user = $this->currentUser();
        $user->password = UserCommon::encryptPassword($password, $user->salt);
        $user->saveOrUpdate();
        $this->successFlash('修改密码成功');
        return $this->redirectToAction(null, 'profileAction');
    }

    /**
     * 跨域代理，因为浏览器有不允许跨域的限制，所以从服务器中转一下
     */
    public function crossProxyAction()
    {
        $url = $this->form('url');
        $method = $this->form('method');
        if (isset($_POST['params'])) {
            $params = $this->form('params');
        } else {
            $params = array();
        }
        $res = HttpClient::fetch_page('proxy', $url, $method, $params);
        return $res;
    }

} 