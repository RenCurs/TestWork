<?php

namespace Controllers;
use Service\Database;
use Service\View;
use Models\Job;

class JobController
{
    public function __construct(Job $job)
    {
        $this->job = $job;
    }

    public function create()
    {
        if(!empty($_POST))
        {
            $result = $this->job->create($_POST);
            if($result !== true)
            {
                return View::render('jobs/create', ['errors' => $result]);
            }
            return View::render('jobs/create', ['result' => $result]); 
        }
        return View::render('jobs/create');
    }
}