<?php
return [

    '~^jobs/edit/([0-9]+)$~'=>[Controllers\AdminController::class, 'edit'],
    '~^done$~'=>[Controllers\AdminController::class, 'done'],

    '~^register$~'=>[Controllers\UserController::class, 'register'],
    '~^login$~'=>[Controllers\UserController::class, 'login'],
    '~^logout$~'=>[Controllers\UserController::class, 'logout'],


    '~^jobs/store$~'=>[Controllers\JobController::class, 'store'],
    '~^jobs/create$~'=>[Controllers\JobController::class, 'create'],
    
    '~^$~' => [Controllers\MainController::class, 'index'],
];