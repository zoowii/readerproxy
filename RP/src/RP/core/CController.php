<?php
/**
 * Created by PhpStorm.
 * User: zoowii
 * Date: 14-2-3
 * Time: 下午9:33
 */

namespace RP\core;
//
//function staticUrlFor($filename)
//{
//    $config = CONFIG;
//    $url = $config['BASE_URL'] . '/static/' . $filename;
//    return $url;
//}

use RP\models\User;
use RP\zorm\Model;

class CController
{
    /**
     * @var \RP\core\Template
     */
    public $template;
    protected $_db = null;
    protected $_payload = null;
    protected $_session_started = false;

    protected function initSession()
    {
        if ($this->_session_started === false) {
            session_start();
            $this->_session_started = true;
        }
    }

    public function db()
    {
        if ($this->_db === null) {
            $this->_db = Db::instance();
        }
        return $this->_db;
    }

    public function __construct()
    {
        $this->template = new Template();
        $this->bind('controller', $this);
        $this->bind('template', $this->template);
        $this->bind('static_url', function ($filename) {
            $config = $_ENV['_CONFIG'];
            $url = $config['BASE_URL'] . '/static/' . $filename;
            return $url;
        });
        Model::$tbl_prefix = 'rp_';
        Model::setDb($this->db()->pdo);
    }

    public function setLayout($layout)
    {
        return $this->template->setLayout($layout);
    }

    public function render($tpl, $layout = false)
    {
        return $this->template->render($tpl, $layout);
    }

    /**
     * TODO: route to callable with url_for
     * @param $url
     * @return string
     */
    public function redirect($url)
    {
        header('Location: ' . $url);
        return '';
    }

    public function bind($name, $value)
    {
        return $this->template->bind($name, $value);
    }

    public function args($name = null)
    {
        return $name ? $_GET[$name] : $_GET;
    }

    public function POST($name = null)
    {
        if (!$name) {
            return $_POST;
        } else {
            return $_POST[$name];
        }
    }

    public function forms()
    {
        // TODO
        return $_POST;
    }

    public function payload()
    {
        if ($this->_payload === null) {
            $this->_payload = file_get_contents('php://input');
        }
        return $this->_payload;
    }

    /**
     * TODO: 使用KVDB替代PHP的默认session
     * @param null $name
     * @return mixed
     */
    public function getSession($name = null)
    {
        $this->initSession();
        return $name ? $_SESSION[$name] : $_SESSION;
    }

    public function setSession($name, $value)
    {
        $this->initSession();
        $_SESSION[$name] = $value;
    }

    /**
     * TODO: 增加类似Google那样的同时登录多个用户的功能
     * @param $user
     */
    public function login($user)
    {
        if ($user) {
            $this->setSession('user_id', $user->id);
            $this->setSession('username', $user->username);
            $this->setSession('role_id', $user->role_id);
            // TODO: set login user's role name
        }
    }

    public function logout()
    {
        $this->setSession('user_id', null);
        $this->setSession('username', null);
        $this->setSession('role_id', null);
        // TODO
    }

    public function currentUser()
    {
        $user_id = $this->getSession('user_id');
        if (!$user_id) {
            return null;
        } else {
            $user = User::findByPk($user_id);
            return $user;
        }
    }

    public function setFlash($name, $value)
    {
        // TODO: flash message支持
    }

    public function getFlash($name = null)
    {
        // TODO: flash message支持
    }

}