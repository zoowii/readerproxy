<?php
/**
 * Created by PhpStorm.
 * User: zoowii
 * Date: 14-2-18
 * Time: 下午11:09
 */

namespace RP\models;

/**
 * 帐号绑定，比如绑定到QQ，百度，新浪微博等
 * Class AccountBinding
 * @package RP\models
 */
class AccountBinding extends CModel
{
    protected static $tableName = 'account_binding';
    protected static $pkColumn = 'id';
    protected static $columnTypes = array(
        'id' => 'string',
        'version' => 'int',
        'created_time' => 'datetime',
        'user_id' => 'string',
        'source' => 'string', // qq, baidu, weibo
        'binding_id' => 'string', // 绑定到的第三方ID,
    );
}