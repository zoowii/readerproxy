<?php
/**
 * Created by PhpStorm.
 * User: zoowii
 * Date: 14-2-18
 * Time: 下午9:41
 */

namespace RP\zorm;

use \Exception;


abstract class Model
{
    // 如果对象有默认值，可以给具体的model添加有默认值得属性，比如public $has_read=false
    public static $tbl_prefix = '';
    protected static $tableName;
    protected static $fullTableName = null;

    public static function getFullTableName()
    {
        $cls = get_called_class();
        if (!is_null($cls::$fullTableName)) {
            return $cls::$fullTableName;
        } else {
            return $cls::$tbl_prefix . $cls::$tableName;
        }
    }

    /**
     * @var \PDO
     */
    protected static $_db; // PDO instance
    protected $isNewRecord = true;
    protected static $pkColumn;
    protected static $columnTypes;
    protected static $relations = array(); // 外键，eg. array('name' => array('column' => '?', 'model' => 'model_name', 'key' => 'mapped_column_name'))
    protected $data = array();
    protected static $readOnlyColumns = array(); // 只从数据库中读取，但不在保存时写入数据库的字段，比如id, 自动时间等

    public static function setDb($db)
    {
        self::$_db = $db;
    }

    /**
     * @return \PDO
     */
    public static function getDb()
    {
        return self::$_db;
    }

    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function __get($name)
    {
        $class = get_called_class();
        if (in_array($name, array_keys($class::$relations))) {
            if (!isset($this->data[$name])) {
                if (isset($class::$relations[$name]['column'])) {
                    $columnName = $class::$relations[$name]['column'];
                } else {
                    $columnName = $class::$relations[$name][0];
                }
                $key_value = $this->$columnName;
                if (isset($class::$relations[$name]['model'])) {
                    $joinClass = $class::$relations[$name]['model'];
                } else {
                    $joinClass = $class::$relations[$name][1];
                }
                if (isset($class::$relations[$name]['key'])) {
                    $joinKey = $class::$relations[$name]['key'];
                } else {
                    $joinKey = $class::$relations[$name][2];
                }
                $model = $joinClass::findOneByAttributes(array($joinKey => $key_value));
                $this->data[$name] = $model;
                return $model;
            } else {
                return $this->data[$name];
            }
        } else if (isset($this->data[$name])) {
            return $this->data[$name];
        } else {
            return null;
        }
    }

    // 字段在sql中时字段值对应的sql字符串，即是否加单引号
    private static function columnSqling($columnType, $columnValue)
    {
        if (in_array($columnType, array('string', 'datetime', 'date', 'time', 'timestamp', 'enum'))) {
            return "'$columnValue'";
        } else {
            if ($columnValue === null) {
                return 'null';
            } else if (is_bool($columnValue)) {
                return $columnValue ? 'true' : 'false';
            }
            return $columnValue;
        }
    }

    /**
     * load record from db to update the model object
     */
    public function refresh()
    {
        if ($this->isNewRecord) {
            return false;
        } else {
            $pk = $this::$pkColumn;
            $obj = self::findByPk($this->$pk);
            foreach ($this::$columnTypes as $column => $value) {
                $this->$column = $obj->$column;
            }
            foreach ($this::$relations as $column => $value) {
                $this->$column = null;
            }
            return true;
        }
    }

    public function saveOrUpdate()
    {
        if ($this->isNewRecord) {
            $this->save();
        } else {
            $this->update();
        }
    }

    public function merge()
    {
        $this->isNewRecord = false;
    }

    public function save()
    {
        if ($this->isNewRecord) {
            $sql = "insert into " . $this::getFullTableName();
            $bindParams = array();
            $column_string = ' (';
            $value_string = '(';
            $columns = $this::$columnTypes;
            $i = 0;
            foreach ($columns as $column => $value) {
                if (in_array($column, $this::$readOnlyColumns)) {
                    continue;
                }
                if ($i > 0) {
                    $column_string .= ',';
                    $value_string .= ',';
                }
                $column_string .= "`$column`";
                $value_string .= '?';
                $bindParams[] = $this->$column;
                $i++;
            }
            $column_string .= ')';
            $value_string .= ')';
            $sql .= $column_string . ' values ' . $value_string;
            $stmt = self::getDb()->prepare($sql);
            $result = $stmt->execute($bindParams);
            $this->isNewRecord = false;
            $pk = $this::$pkColumn;
            $this->$pk = self::getDb()->lastInsertId();
            return $result;
        } else {
            return $this->update();
        }
    }

    public function update()
    {
        if ($this->isNewRecord) {
            return false; // only record in db can be update
        }
        $sql = "update " . $this::getFullTableName() . ' t';
        $bindParams = array();
        $set_string = ' set';
        $class = get_class($this);
        $columns = $class::$columnTypes;
        $i = 0;
        foreach ($columns as $column => $value) {
            if ($i > 0) {
                $set_string .= ",";
            }
            $set_string .= " `$column`=?";
            $bindParams[] = $this->$column;
            $i++;
        }
        $pk = $class::$pkColumn;
        $where_string = " where `$pk`=" . self::columnSqling($class::$columnTypes[$pk], $this->$pk);
        $sql .= $set_string . $where_string;
        $stmt = self::getDb()->prepare($sql);
        return $stmt->execute($bindParams);
    }

    public function delete()
    {
        $class = get_class($this);
        $pk = $class::$pkColumn;
        $sql = "delete from " . $this::getFullTableName() . " t where `$pk`=?";
        $bindParams = array($this->$pk);
        $stmt = self::getDb()->prepare($sql);
        return $stmt->execute($bindParams);
    }

    /**
     * @param $attr
     * @param array $with : 要在查询数据库时一起查询的relation的列表，即靠外键连接的对象
     * 使用方式比如：ModelA::findAllByAttributes(array('has_read'='false'), array('user'))
     * @return array
     * @throws Exception
     */
    public static function findAllByAttributes($attr, $order = '', $with = array(), $limit = null, $offset = null)
    {
        $model_name = get_called_class();
        $conn = self::getDb();
        if (!is_array($attr) || count($attr) <= 0) {
            $where_string = '';
        } else {
            $where_string = ' where';
            $i = 0;
            foreach ($attr as $col => $val) {
                if ($i > 0) {
                    $where_string .= ' and';
                }
                $where_string .= " t.`$col`=" . self::columnSqling($model_name::$columnTypes[$col], $val);
                $i++;
            }
        }
        $from_string = ' from ' . $model_name::getFullTableName() . ' t';
        if (!is_array($with) || count($with) <= 0) {
            $select_string = ' *';
        } else {
            // 使用 字段名 和 relation名（比如user_id对应的user)$字段名的方式区分不同字段，
            // 前者表示本张表的字段，后者表示外键对应的表的字段
            $select_string = ' ';
            $i = 0;
            foreach ($model_name::$columnTypes as $col => $type) {
                if ($i > 0) {
                    $select_string .= ',';
                }
                $select_string .= "t.`$col`";
                $i++;
            }
            $i = 0;
            foreach ($with as $name) {
                $select_string .= ',';
                $rel = $model_name::$relations[$name];
                if ($where_string != '') {
                    $where_string .= ' and';
                } else {
                    $where_string .= ' where';
                }
                $where_string .= ' t.`' . $rel['column'] . "`=t$i.`" . $rel['key'] . "`";
                $from_string .= ',' . $rel['model']::getFullTableName() . " t$i";
                $j = 0;
                foreach ($rel['model']::$columnTypes as $col => $type) {
                    if ($j > 0) {
                        $select_string .= ',';
                    }
                    $select_string .= "t$i.`$col` as `$name$$col`";
                    $j++;
                }
                $i++;
            }
        }
        $order_string = $order === '' ? '' : " order by $order";
        $sql = "select" . $select_string . $from_string . $where_string . $order_string;
        if (is_numeric($offset)) {
            $sql .= " offset $offset";
        }
        if (is_numeric($limit)) {
            $sql .= " limit $limit";
        }
        $rows = $conn->query($sql);
        if ($rows == false) {
            var_dump($conn->errorInfo());
            throw new Exception('DB error');
        }
        $objects = array();
        foreach ($rows as $row) {
            $data = array();
            foreach ($row as $key => $value) {
                if (!is_numeric($key)) {
                    $data[$key] = $value;
                }
            }
            $model = self::loadFromArray($model_name, $data);
            $objects[] = $model;
        }
        return $objects;
    }

    /**
     * @param $model_name
     * @param $data
     * @return Model
     */
    private static function loadFromArray($model_name, $data)
    {
        if (!is_array($data) || !is_string($model_name) || $model_name === '') {
            return null;
        } else {
            $model = self::createFromDb($model_name);
            $rels = array(); // related objects
            foreach ($data as $name => $value) {
                $idx = strpos($name, '$');
                if ($idx === false) { // 没有$符号，表示不是外键对象的属性
                    $model->$name = $value;
                } else {
                    $rels[substr($name, 0, $idx)][substr($name, $idx + 1)] = $value;
                }
            }
            foreach ($rels as $name => $rel) {
                $m = $model_name::$relations[$name]['model'];
                $model->$name = self::loadFromArray($m, $rel);
            }
            return $model;
        }
    }

    /**
     * @param $id
     * @param string $order
     * @param array $with
     * @param null $offset
     * @return Model
     */
    public static function findByPk($id, $order = '', $with = array(), $offset = null)
    {
        $model_name = get_called_class();
        $pk = $model_name::$pkColumn;
        return $model_name::findOneByAttributes(array($pk => $id), $order, $with, $offset);
    }

    /**
     * @param $attr
     * @param string $order
     * @param array $with
     * @param null $offset
     * @return Model
     */
    public static function findOneByAttributes($attr, $order = '', $with = array(), $offset = null)
    {
        $cls = get_called_class();
        $objs = $cls::findAllByAttributes($attr, $order, $with, 1, $offset);
        if (count($objs) <= 0) {
            return null;
        } else {
            return $objs[0];
        }
    }

    public static function count($condition = null)
    {
        // TODO
    }

    /**
     * @param $condition sql条件信息
     * @param string $order
     * @param array $with 要通过级联查询的relations中的字段，也就是外键对应的表
     * @param int $limit
     * @param int $offset
     * @return array
     * @throws \Exception
     */
    public static function findAll($condition, $order = '', $with = array(), $limit = null, $offset = null)
    {
        $model_name = get_called_class();
        $conn = self::getDb();
        $where_string = (is_null($condition) || $condition === '') ? '' : " where $condition";
        $from_string = ' from ' . $model_name::getFullTableName() . ' t';
        if (!is_array($with) || count($with) <= 0) {
            $select_string = ' *';
        } else {
            // 使用 字段名 和 relation名（比如user_id对应的user)$字段名的方式区分不同字段，
            // 前者表示本张表的字段，后者表示外键对应的表的字段
            $select_string = ' ';
            $i = 0;
            foreach ($model_name::$columnTypes as $col => $type) {
                if ($i > 0) {
                    $select_string .= ',';
                }
                $select_string .= "t.`$col`";
                $i++;
            }
            $i = 0;
            foreach ($with as $name) {
                if ($where_string != '') {
                    $where_string .= ' and';
                } else {
                    $where_string .= ' where';
                }
                $select_string .= ',';
                $rel = $model_name::$relations[$name];
                $where_string .= ' t.`' . $rel['column'] . "`=t$i.`" . $rel['key'] . "`";
                $from_string .= ',' . $rel['model']::getFullTableName() . " t$i";
                $j = 0;
                foreach ($rel['model']::$columnTypes as $col => $type) {
                    if ($j > 0) {
                        $select_string .= ',';
                    }
                    $select_string .= "t$i.`$col` as `$name$$col`";
                    $j++;
                }
                $i++;
            }
        }
        $order_string = $order === '' ? '' : " order by $order";
        $sql = "select" . $select_string . $from_string . $where_string . $order_string;
        if (is_numeric($offset)) {
            $sql .= " offset $offset";
        }
        if (is_numeric($limit)) {
            $sql .= " limit $limit";
        }
        $rows = $conn->query($sql);
        if ($rows == false) {
            var_dump($conn->errorInfo());
            throw new Exception('DB error');
        }
        $objects = array();
        foreach ($rows as $row) {
            $data = array();
            foreach ($row as $key => $value) {
                if (!is_numeric($key)) {
                    $data[$key] = $value;
                }
            }
            $model = self::loadFromArray($model_name, $data);
            $objects[] = $model;
        }
        return $objects;
    }

    /**
     * @param $condition
     * @param string $order
     * @param array $with
     * @param null $offset
     * @return Model
     */
    public static function findOne($condition, $order = '', $with = array(), $offset = null)
    {
        $cls = get_called_class();
        $objs = $cls::findAll($condition, $order, $with, 1, $offset);
        if (count($objs) <= 0) {
            return null;
        } else {
            return $objs[0];
        }
    }

    private static function createFromDb($model_name)
    {
        $model = new $model_name();
        $model->isNewRecord = false;
        return $model;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $res = array();
        foreach ($this::$columnTypes as $column => $type) {
            $res[$column] = $this->$column;
        }
        foreach ($this->data as $column => $value) {
            if ($value instanceof self) {
                $res[$column] = $value->toArray();
            } else {
                $res[$column] = $value;
            }
        }
        return $res;
    }

    /**
     * need override, call when migrate up
     */
    public static function up()
    {

    }

    /**
     * need override, call when migrate down
     */
    public static function down()
    {

    }

}
