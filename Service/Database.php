<?php

namespace Service;

class Database
{
    private $driver;
    private static $instance;

    private function __construct()
    {
        $setting = require_once __DIR__ .'/../configs/dbsetting.php';
        try
        {
            $this->driver = new \PDO("mysql:host ={$setting['host']};dbname={$setting['dbname']}", $setting['user'], $setting['password']);
            $this->driver->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->driver->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
            return $this->driver;
        }
        catch(\PDOException $e)
        {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public function getDriver()
    {
        return $this->driver;
    }

    public function getLastInsertId()
	{
		return $this->driver->lastInsertId();
    }
    
    public static function getInstance()
    {
        if(empty(self::$instance))
        {
            return self::$instance = new self;
        }
        return self::$instance;
    }

    public function query(string $sql, array $params = [], $class = 'stdClass')
    {
        $statement = $this->driver->prepare($sql);
        $result = $statement->execute($params);

        if($result === false)
        {
            return null;
        }
        return $statement->fetchAll(\PDO::FETCH_CLASS, $class);
    }
}