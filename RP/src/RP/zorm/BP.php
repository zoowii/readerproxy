<?php

namespace RP\zorm;

class BP
{

    private static $instance = null;

    private $conn = null;


    /**
     * @return BP
     */
    private static function instance()
    {
        if (self::$instance == null) {
            self::init();
        }
        return self::$instance;
    }

    public static function init()
    {
        self::$instance = new self();
    }

    /**
     * @return \PDO
     */
    public static function db()
    {
        $_this = self::instance();
        if (is_null($_this->conn)) {
            // init db conn
            $dsn = DB_ADAPTER . ':host=' . DB_HOST . ';dbname=' . DB_NAME;
            try {
                $_this->conn = new \PDO($dsn, DB_USER, DB_PASS, array(PDO::ATTR_PERSISTENT => true));
            } catch (\Exception $e) {
                var_dump($e);
                exit;
            }
            $_this->conn->exec('set names ' . DB_CHARSET);
        }
        return $_this->conn;
    }

}
