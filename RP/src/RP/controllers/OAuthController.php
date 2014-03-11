<?php
/**
 * Created by PhpStorm.
 * User: zoowii
 * Date: 14-2-19
 * Time: 下午10:35
 */

namespace RP\controllers;

use RP\models\AccountBinding;
use RP\models\Role;
use RP\models\User;
use RP\util\Common;
use RP\util\OAuthUtil;
use RP\util\QQAuthUtil;
use RP\util\UserCommon;

class OAuthController extends BaseController
{
    protected function routes()
    {
        $this->get('/auth/qq/login', 'qqLoginAction');
        $this->get('/auth/callback/qq', 'qqGetAccessTokenAction');
        $this->post('/oauth/login', 'oauthLoginAction');
        $this->get('/oauth/bind', 'oauthBindPageAction');
        $this->get('/oauth/bind', 'oauthBindAction');
    }

    public function __construct()
    {
        parent::__construct();
        $this->setLayout('_layout/website.php');
    }

    public function qqLoginAction()
    {
        return $this->redirect(QQAuthUtil::getAuthUrl());
    }

    public function qqGetAccessTokenAction()
    {
        return $this->render('oauth/qq_get_access_token.php');
    }

    public function oauthLoginAction()
    {
        $access_token = $this->form('access_token');
        $openid = $this->form('openid');
        $expires = intval($this->form('expires_in'));
        $type = $this->form('type');
        $this->setSession("oauth2_access_token", $access_token);
        $this->setSession("oauth2_openid", $openid);
        $this->setSession("oauth2_source", $type);
        $this->setSession("oauth2_expires", time() + intval($expires));
        switch ($type) {
            case 'qq':
            {
                $userInfo = QQAuthUtil::getUserInfo($access_token, $openid);
                $userInfo = (array)$userInfo;
                $this->setSession("oauth2_userinfo", $userInfo);
                $user = OAuthUtil::getRelatedUser($type, $openid);
                if (is_null($user)) {
                    // 判断是否被邀请，如果没有，通知用户，如果有，则跳转入绑定用户页面（同时这里绑定到现有帐号或者自动生成一个帐号来绑定（用户名和密码让用户填））
//                    var_dump($userInfo);
                    return $this->ajaxSuccess('/index.php/oauth/bind');
                } else {
                    $this->login($user);
                    return $this->ajaxSuccess('/');
                }
            }
                break;
            default:
                {
                return $this->ajaxSuccess('/index.php/login');
                }
                break;
        }

    }

    public function oauthBindPageAction()
    {
        $type = $this->getSession('oauth2_source');
        $openid = $this->getSession('oauth2_openid');
        $expires = $this->getSession('oauth2_expires'); // TODO: access_token过期时去自动更新access_token
        $access_token = $this->getSession('oauth2_access_token');
        $userinfo = $this->getSession('oauth2_userinfo');
        $displayName = OAuthUtil::getDisplayName($type, $userinfo);
        $this->bind('displayName', $displayName);
        $this->bind('source', $type);
        return $this->render('oauth/bind_user.php');
    }

    public function oauthBindAction()
    {
        $type = $this->getSession('oauth2_source');
        $openid = $this->getSession('oauth2_openid');
        $expires = $this->getSession('oauth2_expires'); // TODO: access_token过期时去自动更新access_token
        $access_token = $this->getSession('oauth2_access_token');
        $userinfo = $this->getSession('oauth2_userinfo');
        $displayName = OAuthUtil::getDisplayName($type, $userinfo);
        $username = trim($this->form('username'));
        $password = trim($this->form('password'));
        if (strlen($username) < 4 || strlen($username) > 20) {
            return '用户名的长度最短4位，最长20位';
        }
        if (strlen($password) < 1 || strlen($password) > 30) {
            return '密码不能为空，同时最长30位';
        }
        $user = OAuthUtil::getRelatedUser($type, $openid);
        if ($user !== null) {
            return $this->redirect('/');
        }
        $user = User::findByUsername($username);
        if (is_null($user)) {
            $user = new User();
            $user->username = $username;
            $user->alias_name = $displayName;
            $user->salt = Common::randomString(10);
            $user->password = UserCommon::encryptPassword($password, $user->salt);
            $user->save();
            $user = User::findByUsername($username);
        } else {
            $user = User::findByUsernameAndPassword($username, $password);
            if (is_null($user)) {
                return '用户名已存在，或者用户名或密码错误';
            }
        }
        $accountBinding = new AccountBinding();
        $accountBinding->user_id = $user->id;
        $accountBinding->source = $type;
        $accountBinding->binding_id = $openid;
        $accountBinding->save();
        $this->login($user);
        return $this->redirect('/');
    }

} 