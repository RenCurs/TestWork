<?php

namespace Service;

class View
{
    public static function render($template, $args = [], int $code = 200 )
    {
        $template = __DIR__ . '/../Views/' . $template . '.php';

        (!empty($args)) ? extract($args) : '';

        http_response_code($code);
        include $template;
    }
}