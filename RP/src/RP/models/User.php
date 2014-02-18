<?php
/**
 * Created by PhpStorm.
 * User: zoowii
 * Date: 14-2-18
 * Time: 下午9:51
 */

namespace RP\models;

use RP\util\UserCommon;

/**
 * Class User
 * @package RP\models
 */
class User extends CModel
{
    protected static $tableName = 'user';
    protected static $pkColumn = 'id';
    protected static $columnTypes = array(
        'id' => 'string',
        'version' => 'int',
        'created_time' => 'datetime',
        'username' => 'string',
        'email' => 'string',
        'password' => 'string',
        'salt' => 'string',
        'role_id' => 'string',
        'last_update_time' => 'datetime',
        'alias_name' => 'string',
        'last_login_time' => 'datetime',
        'last_login_ip' => 'int',
        'avatar_id' => 'string',
        'is_deleted' => 'int'
    );

    public function __construct()
    {
        parent::__construct();
        $this->is_deleted = 0;
    }

    public function getAliasName()
    {
        return $this->alias_name ? $this->alias_name : $this->username;
    }

    public function isActive()
    {
        return intval($this->is_deleted) === 0;
    }

    /**
     * @param $username
     * @param $password
     * @return \RP\zorm\Model
     */
    public static function findByUsernameAndPassword($username, $password)
    {
        $user = self::findOneByAttributes(array(
            'username' => $username
        ));
        if ($user->password === UserCommon::encryptPassword($password, $user->salt)) {
            return $user;
        } else {
            return null;
        }
    }
}
