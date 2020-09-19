<?php

namespace Traits;

trait PropertiesObject
{
    public function getPropertiesObject()
    {
        foreach($this as $key=>$value)
        {
            if(in_array($key, $this->fillable))
            {
                $keys[] = ':' . $key;
                $values[] = $value;
            }
        }
        if(!empty($keys) && !empty($values))
        {
            $properties = array_combine($keys, $values);
        }
        return $properties;
    } 
}