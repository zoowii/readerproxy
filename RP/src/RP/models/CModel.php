<?php
/**
 * Created by PhpStorm.
 * User: zoowii
 * Date: 14-2-18
 * Time: 下午11:02
 */

namespace RP\models;


use RP\util\Common;
use RP\zorm\Model;

class CModel extends Model
{
    public function __construct()
    {
        if (isset($this::$columnTypes['version'])) {
            $this->version = 1;
        }
        if (isset($this::$columnTypes['id']) && $this::$columnTypes['id'] === 'string') {
            $this->id = Common::guid();
        }
    }
} 