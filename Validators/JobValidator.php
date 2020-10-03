<?php

namespace Validators;

class JobValidator extends AbstractValidator
{ 
    protected $table = 'jobs';

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
}