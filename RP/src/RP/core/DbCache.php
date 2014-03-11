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
        $now = time(); // date('Y-m-d H:i:s');
        $cache = DbCacheModel::findOne("name='$name' and (expire_time>$now or expire_time<=0)");

        return is_null($cache) ? null : $cache->value;
    }

    public function remove($name)
    {
        $this->create_table_if_not_exist();
        $caches = DbCacheModel::findAllByAttributes(array(
            'name' => $name
        ));
        foreach ($caches as $cache) {
            $cache->delete();
        }
    }

    public function clearExpiredData()
    {
        // TODO
    }

    public function set($name, $value, $expires)
    {
        $this->create_table_if_not_exist();
        if ($expires > 0) {
            $expire_time = time() + $expires; // date('Y-m-d H:i:s', time() + $expires);
        } else {
            $expire_time = -1;
        }
        $this->remove($name);
        $cache = new DbCacheModel();
        $cache->name = $name;
        $cache->value = $value;
        $cache->expire_time = $expire_time;
        $cache->save();
    }

    public function create_table_if_not_exist()
    {
        // TODO: now just support MySQL
        $db = $this->db;
//        $db->exec("create table if not exists s_cache ('id' int auto increment primary key, name varchar(255) unique, 'value' text, 'version' int default 1, 'expire_time' timestamp)");
    }
} 