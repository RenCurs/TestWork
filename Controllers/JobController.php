<?php

namespace Controllers;

use Service\Database;
use Service\Session;
use Service\View;
use Models\Job;

class JobController
{
    public function __construct(Job $job)
    {
        $this->job = $job;
    }

    public function store()
    {
        $result = $this->job->create($_POST);
        if($result !== true)
        {
            return View::render('jobs/create', ['errors' => $result]);
        }
        Session::flash('job_result', 'Задача успешно создана!');
        return header('Location: /');
    }

    public function create()
    {
        return View::render('jobs/create');
    }
}