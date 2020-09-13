<?php

namespace Validators;

use Service\Database;

abstract class AbstractValidator
{
    protected $errors = [];
    protected $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }
    abstract public function validate(array $data, array $rules);

    protected function addErrors(string $fieldName, string $message)
    {
        $this->errors[$fieldName] = $message;
    }

    public function getErrors():array
    {
        return $this->errors;
    }

    protected function minLen($fieldName, $item_value, $rule_value)
    {
        if(mb_strlen($item_value) < $rule_value)
        {
            $this->addErrors($fieldName, $fieldName. ' don\'t less than '. $rule_value . ' characters');
        }
    }

    protected function unknown_symbols($fieldName, $item_value, $rule_value)
    {
        if(preg_match($rule_value, $item_value))
        {
            $this->addErrors($fieldName, ' the name contains invalid characters: ' . trim($rule_value, $rule_value[0]));
        }
    }

    protected function unique($fieldName, $item_value)
    {
        $sql = 'SELECT ' . $fieldName . ' FROM '. $this->table .' WHERE '. $fieldName. '=:'.$fieldName;
        $count = count($this->db->query($sql, [':'.$fieldName => $item_value]));
        if($count !== 0)
        {
            $this->addErrors($fieldName, 'this '. $fieldName . ' already exists');
        }
    }

}