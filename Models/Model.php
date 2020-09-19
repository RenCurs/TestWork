<?php

namespace Models;

use Service\Paginator;
use Service\Database;

abstract class Model
{
    protected $db;
    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public static function getClass()
    {
        return static::$class;
    }

    public static function getTable()
    {
        return static::$table;
    }

    public static function getDependency()
    {
        return static::$dependency;
    }

    public function find($fieldName, $fieldValue)
    {
        $sql = 'SELECT * FROM ' . static::getTable() . ' WHERE ' . $fieldName . '=:fieldValue';
        $result = $this->db->query($sql, [':fieldValue'=> $fieldValue],  static::getClass(), static::getDependency());
        if(!empty($result))
        {
            return $result[0];
        }
        return null;
    }
}