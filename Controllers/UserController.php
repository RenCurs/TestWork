<?php
namespace Controllers;

use Service\View;
use Models\User;
use Models\Job;

class UserController
{
    private $user;

    public function __construct(User $user, Job $job)
    {
        $this->user = $user;
        $this->job = $job;
    }

    public function logout()
    {
        session_unset();
        return \header('Location: /');
    }

    public function showLogin()
    {
        return View::render('auth/login');
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
        return header('Location: /login/show');
    }

    public function showRegister()
    {
        return View::render('auth/register');
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
        return header('Location: /register/show');
    } 

    public function myJobs(int $idUser)
    {
        $jobs = $this->job->myJobs($idUser);
        return View::render('user/index', ['jobs' => $jobs]);
    }
}