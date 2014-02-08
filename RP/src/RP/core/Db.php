<?php
/**
 * Created by PhpStorm.
 * User: zoowii
 * Date: 14-2-3
 * Time: 下午10:49
 */

namespace RP\core;

$config = $_ENV['_CONFIG'];


class Db extends medoo
{
    protected $database_type = 'mysql';

    // For MySQL, MSSQL, Sybase
    protected $server = 'localhost';

    protected $username = 'root';

    protected $password = 'qazsedcft';

    // Optional
    protected $port = 3306;

    protected $charset = 'utf8';

    protected $database_name = 'readerproxy';
} 