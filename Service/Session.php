<?php

namespace Service;

class Session
{
    public static function flash(string $key, string $message)
    {
        setcookie($key, $message, time() + 30, '/');
    }
}