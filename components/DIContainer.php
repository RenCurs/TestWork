<?php

class DIContainer
{
    private $class;

    public function get($class)
    {
        return $this->resolve($class);
    }

    public function resolve($class)
    {
        $reflector = new ReflectionClass($class);
        
        if(!$reflector->isInstantiable())
        {
            throw new Exception("Class {$class} is not instantiable");
        }
        
        $constructor = $reflector->getConstructor();
        if(is_null($constructor))
        {
            return $reflector->newInstance();
        }
        $parameters = $constructor->getParameters();
        $dependencies = $this->getDependencies($parameters);
        return $reflector->newInstanceArgs($dependencies);
    }

    public function getDependencies($parameters)
    {
        $dependencies = [];

        foreach($parameters as $parameter)
        {
            $dependency = $parameter->getClass();
            if(is_null($dependency))
            {
                if ($parameter->isDefaultValueAvailable()) 
                {
                    $dependencies[] = $parameter->getDefaultValue();
                }
                else 
                {
					throw new Exception("Нельзя разрешить зависимость для св-ва {$parameter->name}");
				}
            }
            else
            {
                $dependencies[] = $this->get($dependency->name);
            }
        }
        return $dependencies;
    }
}