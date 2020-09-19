<?php

namespace Service;

class QueryBuilder
{   
    private $sql = '';
    private $parameters = [];
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function select( string $table, string ...$columns)
    {
        $columns = (empty($columns)) ? '*' : implode(',', $columns);
        $this->sql = 'SELECT ' . $columns . ' FROM ' . $table;
        return $this;
    }

    public function insert(string $table, array $columns, array $preparedData = []) // переписать
    {
        $strColumn = implode(', ', $columns);

        $PreparedColumn = array_map(function($item){
            return ':' . $item;
        }, $columns);
        $strPreparedColumn = implode(', ', $PreparedColumn);

        $this->sql = 'INSERT INTO ' . $table . ' (' . $strColumn . ') VALUES (' . $strPreparedColumn . ')';
        $this->parameters = $preparedData;
        return $this;
    }

    public function update(string $table, $data)
    {
        if(!is_object($data))
        {
            foreach($data as $fieldName=>$fieldValue)
            {
                $strPreparedColumn = $fieldName . '=:' . $fieldName;
                $preparedParameters[':' . $fieldName] = $fieldValue;
            }
            $this->sql = 'UPDATE ' . $table . ' SET ' . $strPreparedColumn;
            $this->parameters = $preparedParameters;
            return $this;
        }
        elseif(is_object($data))
        {
            $object = $data;
            $columns = $object->getFillable();
            unset($columns[array_search('id', $columns)]);
    
            $strPreparedColumns = array_map(function($item){
                if($item !== 'id')
                {
                    return $item . '=:' . $item;
                }
            },  $columns);
    
            $strPreparedColumn = implode(', ', $strPreparedColumns);
            $this->sql = 'UPDATE ' . get_class($object)::getTable() . ' SET ' . $strPreparedColumn;
            $this->parameters = $object->getPropertiesObject();
            unset($this->parameters['id']);
            return $this;
        }
        return false;
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