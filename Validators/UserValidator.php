<?php
namespace Validators;

class UserValidator extends AbstractValidator
{
    protected $table = 'users';
    
    public function validate(array $items, array $rules)
    {
        foreach($items as $item => $item_value)
        {
            if(array_key_exists($item, $rules))
            {
                foreach($rules[$item] as $rule => $rule_value)
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


