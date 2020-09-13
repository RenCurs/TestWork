<?php

session_start();
require __DIR__ . '/components/autoload.php';

require __DIR__ . '/components/Router.php';

require __DIR__ . '/components/DIContainer.php';

try
{
    $container = new DIContainer();
    $router = $container->get('Router');
    $router->run();
}

catch(Exception $e)
{
    \Service\View::render('errors/404', ['error'=>$e->getMessage()], 404);
}
