<?php

namespace Controllers;

use Service\View;
use Models\Job;

class MainController
{
    public function __construct(Job $job)
    {
        $this->job = $job;
    }

    public function index()
    { 
        $jobsAndPaginator = $this->job->paginate(5);
        extract($jobsAndPaginator);
        return View::render('index', ['jobs' => $jobs, 'paginator'=>$paginator]);
    }
}