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
use RP\Px\Router;
use RP\zorm\Model;

class CController extends Router
{
    /**
     * @var \RP\core\Template
     */
    public $template;
    protected $_db = null;
    protected $_payload = null;

    protected function initSession()
    {
        HttpSession::startSession();
    }

    protected function db()
    {
        if ($this->_db === null) {
            $this->_db = Db::instance();
        }
        return $this->_db;
    }

    public function __construct()
    {
        parent::__construct();
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

    protected function clearLayouts()
    {
        $this->template->clearLayouts();
    }

    protected function setLayout($layout)
    {
        $this->template->setLayout($layout);
    }

    protected function addLayout($layout)
    {
        $this->template->addLayout($layout);
    }

    protected function block($layout)
    {
        $this->addLayout($layout);
    }

    protected function render($tpl, $layout = false)
    {
        return $this->template->render($tpl, $layout);
    }

    /**
     * TODO: route to callable with url_for
     * @param $url
     * @return string
     */
    protected function redirect($url)
    {
        header('Location: ' . $url);
        return '';
    }

    protected function bind($name, $value)
    {
        $this->template->bind($name, $value);
    }

    protected function args($name = null)
    {
        return $name ? $_GET[$name] : $_GET;
    }

    /**
     * get data from $_POST
     */
    protected function form($name = null)
    {
        if ($name && isset($_POST[$name])) {
            return $_POST[$name];
        } elseif ($name) {
            return null;
        } else {
            return $_POST;
        }
    }

    protected function forms()
    {
        return $_POST;
    }

    protected function payload()
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
    protected function getSession($name = null)
    {
        $this->initSession();
        if (empty($name)) {
            return $_SESSION;
        }
        if (isset($_SESSION[$name])) {
            return $_SESSION[$name];
        } else {
            return null;
        }
    }

    protected function setSession($name, $value)
    {
        $this->initSession();
        $_SESSION[$name] = $value;
    }

    /**
     * TODO: 增加类似Google那样的同时登录多个用户的功能
     * @param $user
     */
    protected function login($user)
    {
        if ($user) {
            $this->setSession('user_id', $user->id);
            $this->setSession('username', $user->username);
            $this->setSession('role_id', $user->role_id);
            // TODO: set login user's role name
        }
    }

    protected function logout()
    {
        $this->setSession('user_id', null);
        $this->setSession('username', null);
        $this->setSession('role_id', null);
        // TODO
    }

    /**
     * @return \RP\models\User
     */
    protected function currentUser()
    {
        $user_id = $this->getSession('user_id');
        if (!$user_id) {
            return null;
        } else {
            $user = User::findByPk($user_id);
            return $user;
        }
    }

    protected function isGuest()
    {
        return $this->currentUser() === null;
    }

    public function setFlash($name, $value)
    {
        // TODO: flash message支持
    }

    protected function getFlash($name = null)
    {
        // TODO: flash message支持
    }

    protected function ajax($data)
    {
        return json_encode($data);
    }

    protected function ajaxSuccess($data)
    {
        return $this->ajax(array(
            'success' => true,
            'data' => $data
        ));
    }

    protected function ajaxFail($data)
    {
        return $this->ajax(array(
            'success' => false,
            'data' => $data
        ));
    }

}
