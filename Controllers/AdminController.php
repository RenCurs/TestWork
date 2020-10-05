<?php

namespace Controllers;
use Service\Database;
use Service\Session;
use Service\View;
use Models\Job;


class AdminController
{
    public function __construct(Job $job)
    {
        $access = (isset($_SESSION['admin']) && $_SESSION['admin'] === 1) ? $_SESSION['admin'] : null;
        if(is_null($access))
        {
            return header('Location: /login');
        }
        $this->job = $job;
    }

    public function edit(int $id)
    {  
        $result = false;
        $job = $this->job->find('id', (int) $id);
        if(!empty($_POST))
        {
            $_POST['isEdit'] = 1;
            $result = $job->update($_POST);
            $job = $this->job->find('id', (int) $id);
            Session::flash('editJob_result', 'Задача успешно обновлена!');
            return header('Location: /');
        }
        return View::render('/admin/edit_job', ['job'=> $job]);
    }

    public function done()
    {
        if(!empty($_POST['id']))
        {
            $idJob = (int) $_POST['id'];
            $job = $this->job->find('id', $idJob);
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