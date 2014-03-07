<?php
/**
 * Created by PhpStorm.
 * User: zoowii
 * Date: 14-3-7
 * Time: 下午10:49
 */

namespace RP\core;


use RP\models\CModel;

/**
 * Class DbCacheModel
 * @package RP\core
 * @property string id
 * @property int version
 * @property string name
 * @property string value
 * @property int expire_time
 */
class DbCacheModel extends CModel
{
    protected static $fullTableName = 'rp_cache';
    protected static $pkColumn = 'id';
    protected static $columnTypes = array(
        'id' => 'string',
        'version' => 'int',
        'name' => 'string',
        'value' => 'string',
        'expire_time' => 'int'
    );
} 