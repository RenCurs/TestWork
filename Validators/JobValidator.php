<?php

namespace Validators;

class JobValidator extends AbstractValidator
{ 
    public function validate(array $jobData, array $rules)
    {
        foreach($jobData as $item => $item_value)
        {
            if(array_key_exists($item, $rules))
            {
                foreach($rules[$item] as $rule=>$rule_value)
                {
                    if(is_int($rule))
                    {
                        $rule = $rule_value;
                    }
                    if(method_exists($this, $rule))
                    {
                        $this->$rule($item, $item_value, $rule_value);
                    }
                }
            }
        }
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
        $db = Database::getInstance();
        $sql = 'SELECT ' . $fieldName . ' FROM `jobs` WHERE '. $fieldName. '=:'.$fieldName;
        $count = $db->query($sql, [':'.$fieldName => $item_value]);
        if(!empty($count))
        {
            $this->addErrors($fieldName, 'this '. $fieldName . ' already exists');
        }
    }
}