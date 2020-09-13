<?php
namespace Controllers;

use Service\View;
use Models\User;

class UserController
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function logout()
    {
        unset($_SESSION['user']);
        unset($_SESSION['admin']);
        return \header('Location: /');
    }

    public function login()
    {
        if(!empty($_POST))
        {
            $result = $this->user->login($_POST);
            if($result === true)
            {
                return header('Location: /');
            }
            return View::render('auth/login', ['error'=> $result]);
        }
        return View::render('auth/login');
    }

    public function register()
    {
        if(!empty($_POST))
        {
            $result = $this->user->create($_POST);
            if($result !== true)
            {
                return View::render('auth/register', ['errors' => $result]);
            }
            return View::render('auth/register', ['result' => $result]);
        }
        return View::render('auth/register');
    }

}