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

    public function  __construct($options, $db_config)
    {
        $this->database_type = $db_config['type'];
        $this->server = $db_config['host'];
        $this->username = $db_config['user'];
        $this->password = $db_config['pass'];
        $this->port = $db_config['port'];
        $this->charset = $db_config['charset'];
        $this->database_name = $db_config['dbname'];
        parent::__construct($options);
    }

    private static $_instances = array();

    /**
     * @param string $name
     * @return medoo
     */
    public static function instance($name = 'default')
    {
        if (!isset(self::$_instances[$name])) {
            $config = $_ENV['_CONFIG'];
            $db = new Db(array(), $config['db'][$name]);
            self::$_instances[$name] = $db;
        }
        return self::$_instances[$name];
    }
}