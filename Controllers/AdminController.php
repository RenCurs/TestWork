<?php

namespace Controllers;
use Service\Database;
use Service\View;
use Models\Job;


class AdminController
{
    public function __construct(Job $job)
    {
        $access = (isset($_SESSION['admin'])) ? $_SESSION['admin'] : null;
        if(is_null($access))
        {
            return header('Location: /login');
        }
        $this->job = $job;
    }

    public function edit($id)
    {  
        $result = false;
        $job = $this->job->find('id', (int) $id);
        if(!empty($_POST))
        {
            $result = $job->update($_POST);
        }
        return View::render('/admin/edit_job', ['job'=> $job, 'result'=> $result]);
    }

    public function done()
    {
        if(!empty($_POST['id']))
        {
            $IdJob = (int) $_POST['id'];
            $job = $this->job->find('id', $IdJob);
            if($job->isDone() == 0)
            {
                $job->update(['isDone' => 1]);
            }
            elseif($job->isDone() == 1)
            {
                $job->update(['isDone' => 0]); 
            }
        }
    }
}