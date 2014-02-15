<?php
/**
 * Created by PhpStorm.
 * User: zoowii
 * Date: 14-2-8
 * Time: 下午5:39
 */

namespace RP\core;


class DbCache extends CCache
{
    private $db = null;

    public function __construct()
    {
        $this->db = Db::instance();
    }

    public function get($name)
    {
        $this->create_table_if_not_exist();
        $db = $this->db;
        $now = time(); // date('Y-m-d H:i:s');
        $db_res = $db->query("select * from s_cache where name='$name' and (expire_time>$now or expire_time<=0) limit 1");
        $res = array();
        if ($row = $db_res->fetch(\PDO::FETCH_ASSOC)) {
            $res[] = $row;
        }
//        $res = $db->get('s_cache',
//            array('value'),
//            array('AND' => array(
//                "name='$name'",
//                "(expire_time>$now or expire_time<=0)")));
        if (count($res) > 0) {
            return $res[0]['value'];
        } else {
            return null;
        }
    }

    public function remove($name)
    {
        $this->create_table_if_not_exist();
        $db = $this->db;
        $db->delete('s_cache', array('AND' => array(
            "name='$name'"
        )));
        $db->exec("delete from s_cache where name='$name'");
    }

    public function set($name, $value, $expires)
    {
        $this->create_table_if_not_exist();
        if ($expires > 0) {
            $expire_time = time() + $expires; // date('Y-m-d H:i:s', time() + $expires);
        } else {
            $expire_time = -1;
        }
        $db = $this->db;
        $this->remove($name);
        $db->insert('s_cache', array(
            'name' => $name,
            'value' => strval($value),
            'expire_time' => $expire_time
        ));
    }

    public function create_table_if_not_exist()
    {
        // TODO: now just support MySQL
        $db = $this->db;
//        $db->exec("create table if not exists s_cache ('id' int auto increment primary key, name varchar(255) unique, 'value' text, 'version' int default 1, 'expire_time' timestamp)");
    }
} 