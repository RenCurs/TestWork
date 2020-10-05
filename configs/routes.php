<?php
return [

    '~^jobs/edit/([0-9]+)$~'=>[Controllers\AdminController::class, 'edit'],
    '~^done$~'=>[Controllers\AdminController::class, 'done'],


    '~^login/show$~'=>[Controllers\UserController::class, 'showLogin'],
    '~^login$~'=>[Controllers\UserController::class, 'login'],
    '~^register/show$~'=>[Controllers\UserController::class, 'showRegister'],
    '~^register$~'=>[Controllers\UserController::class, 'register'],
    '~^logout$~'=>[Controllers\UserController::class, 'logout'],

    '~^user/jobs/([0-9]+)$~'=>[Controllers\UserController::class , 'myJobs'],

    '~^jobs/create$~'=>[Controllers\JobController::class, 'create'],
    '~^jobs/store$~'=>[Controllers\JobController::class, 'store'],
    
    '~^$~' => [Controllers\MainController::class, 'index'],
];