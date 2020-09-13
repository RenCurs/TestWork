<?php

class Router
{
    private $container;
    
    public function  __construct(DiContainer $container)
    {
        $this->container = $container;
    }
    public function run()
    {
        $curRoute = $_GET['route'] ?? '';
        $routes = require_once __DIR__ . '/../configs/routes.php';
        $routeExist = false;

        foreach($routes as $pattern => $controller)
        {
            if(preg_match($pattern, $curRoute, $matches))
            {
                $routeExist = true;
                break;
            }
        }
        
        if(!$routeExist)
        {
            throw new Exception('Такой страницы нет, Вы зашли на несуществующую страницу.');
        }

        unset( $_GET['route']);
        unset($matches[0]);

        $controllerName = $controller[0];
        $actionName = $controller[1];
        
        $controller = $this->container->get($controllerName);

        return $controller->$actionName(...$matches);
    }
}