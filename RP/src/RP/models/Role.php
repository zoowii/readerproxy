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
    );

    public static function getRootRole()
    {
        $role = Role::findByName('root');
        if ($role !== null) {
            return $role;
        } else {
            $role = new Role();
            $role->name = 'root';
            $role->save();
            $role = Role::findByName('root');
//            $role->refresh();
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