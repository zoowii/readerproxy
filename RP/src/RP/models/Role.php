<?php
/**
 * Created by PhpStorm.
 * User: zoowii
 * Date: 14-2-19
 * Time: 上午1:02
 */

namespace RP\models;


class Role extends CModel
{
    protected static $tableName = 'role';
    protected static $pkColumn = 'id';
    protected static $columnTypes = array(
        'id' => 'string',
        'version' => 'int',
        'created_time' => 'datetime',
        'name' => 'string',
        'description' => 'string'
    );

    /**
     * 获取用于显示的文本信息
     */
    public function getDisplayText()
    {
        if (empty($this->description)) {
            return $this->name;
        } else {
            return $this->description;
        }
    }

    /**
     * 获取root用户角色
     * @return Role|\RP\zorm\Model
     */
    public static function getRootRole()
    {
        $role = Role::findByName('root');
        if ($role !== null) {
            return $role;
        } else {
            $role = new Role();
            $role->name = 'root';
            $role->description = '超级管理员';
            $role->save();
            $role = Role::findByName('root');
//            $role->refresh();
            return $role;
        }
    }

    /**
     * 获取普通用户角色
     */
    public static function getCommonUser()
    {
        $role = Role::findByName('default');
        if ($role !== null) {
            return $role;
        } else {
            $role = new Role();
            $role->name = 'default';
            $role->description = '普通用户';
            $role->save();
            $role = Role::findByName('default');
            return $role;
        }
    }

    public static function findByName($name)
    {
        $role = Role::findOneByAttributes(array(
            'name' => $name
        ));
        return $role;
    }

} 