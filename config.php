<?php
$db_config = array(
    'type' => 'mysql',
    'host' => 'localhost',
    'port' => 3306,
    'user' => 'root',
    'pass' => 'qazsedcft',
    'charset' => 'utf8',
    'dbname' => 'readerproxy',
    'tbl_prefix' => 'rp_'
);
if (defined('SAE_MYSQL_DB')) {
    // if is run on SAE
    $db_config['type'] = 'mysql';
    $db_config['host'] = SAE_MYSQL_HOST_M;
    $db_config['port'] = SAE_MYSQL_PORT;
    $db_config['user'] = SAE_MYSQL_USER;
    $db_config['pass'] = SAE_MYSQL_PASS;
    $db_config['dbname'] = SAE_MYSQL_DB;
    $db_config['charset'] = 'utf8';
}
date_default_timezone_set('Asia/Shanghai');
return array(
    'BASE_URL' => '',
    'db' => array(
        'default' => $db_config
    )
);