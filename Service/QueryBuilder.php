<?php

namespace Service;

class QueryBuilder
{   
    private $sql = '';
    private $type;
    private $parameters = [];
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getStrValuesInsert(array $column) : string
    {   
        $bindColumn = [];
        foreach($column as $columnName=>$columnValue)
        {
            if(is_int($columnName))
            {
                $columnName = $columnValue;
            }
            $bindColumn[] = ':' . $columnName;
        }
        return implode(', ', $bindColumn);
    }

    public function getBindParams(array $data) : array
    {
        $bindParams = [];
        foreach($data as $columnName=> $columnValue)
        {
            $bindParams[':' . $columnName] = $columnValue;
        }
        return $bindParams;
    }  
    
    public function getStrColumnsInsert(array $data) : string
    {
        $str = [];
        foreach($data as $columnName=>$columnValue)
        {
            $str[] = $columnName;
        }
        return implode(', ', $str);
    } 
    
    public function getStrColumnsUpdate($data) : string
    {
        $arr = [];
        foreach($data as $columnName=>$columnValue)
        {
            if(is_int($columnName))
            {
                $columnName = $columnValue;
            }
            $arr[] = $columnName . '=:' .$columnName;
        }
        return implode(', ', $arr);
    }
    
    public function select( string $table, string ...$columns)
    {
        $columns = (empty($columns)) ? '*' : implode(',', $columns);
        $this->sql = 'SELECT ' . $columns . ' FROM ' . $table;
        return $this;
    }

    public function insert(string $table)
    {
        $this->type = 'insert';
        $this->sql = 'INSERT INTO ' . $table;
        return $this;
    }

    public function values($data)
    {
        if(!is_object($data))
        {
            if($this->type === 'insert')
            {
                $strColumns = $this->getStrColumnsInsert($data);
                $strValuesInsert = $this->getStrValuesInsert($data);
                $bindParams = $this->getBindParams($data);
                $this->sql .= '(' . $strColumns . ' ) VALUES (' . $strValuesInsert. ')';
                $this->parameters = $bindParams;
                return $this;
            }
            elseif($this->type === 'update')
            {
                $strColumns = $this->getStrColumnsUpdate($data);
                $bindParams = $this->getBindParams($data);
                $this->sql .= ' SET ' . $strColumns;
                $this->parameters = $bindParams;
                return $this;
            }

        }
        elseif(is_object($data))
        {
            $object = $data;
            if($this->type === 'insert')
            {
                $strColumns = implode(', ', $data->getFillable());
                $strValuesInsert = $this->getStrValuesInsert($data->getFillable());
                $bindParams = $object->getPropertiesObject();
                $this->sql .= '(' . $strColumns . ' ) VALUES (' . $strValuesInsert. ')';
                $this->parameters = $bindParams;
                return $this;
            }
            elseif($this->type === 'update')
            {
                $strColumns = $this->getStrColumnsUpdate($object->getFillable());
                $bindParams = $object->getPropertiesObject();
                $this->sql .= ' SET ' . $strColumns;
                $this->parameters = $bindParams;
                return $this;
            }
        }
        return false;
    }

    public function update(string $table)
    {
        $this->type = 'update';
        $this->sql = 'UPDATE ' . $table;
        return $this;
    }

    public function where(array $where, $operator = '=')
    {
        foreach($where as $column => $value)
        {
            $strWhere[] = $column . $operator . ':' . $column;
            $this->parameters[':' . $column] = $value; 
        }
        $strWhere = implode(',' , $strWhere);
        $this->sql .= ' WHERE ' . $strWhere;
        return $this;
    }

    public function sort(string $sort)
    {
        $this->sql .= ' ' . $sort;
        return $this;
    }

    public function limit(int $count)
    {
        $this->sql .= ' LIMIT ' . $count;
        return $this;
    }

    public function offset(int $count)
    {
        $this->sql .= ' OFFSET ' . $count;
        return $this;
    }

    public function count(string $table, string $column = '*', bool $distinct = false)
    {
        $this->sql = 'SELECT COUNT(' . $column . ') FROM ' .$table;

        if($distinct === true)
        {
            $this->sql = 'SELECT COUNT( DISTINCT ' . $column . ') FROM ' . $table;
        }
        return $this; 
    }

    public function execute(string $className = 'stdClass', $count = false)
    {   
        if($count === true)
        {
            $query = $this->db->getDriver()->prepare($this->sql);
            $query->execute();
            return $query->fetchColumn();
        }
        
        return $this->db->query($this->sql, $this->parameters, $className,
                                (method_exists($className, 'getDependency') ? $className::getDependency() : []));
    }
}