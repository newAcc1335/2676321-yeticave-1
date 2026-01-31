<?php

return [
    'app' => [
        'url' => 'http://localhost:8000/',
    ],

    'db' => [
        'host' => 'localhost',
        'user' => 'root',
        'password' => 'password',
        'database' => 'yeticave'
    ],

    'mailer' => [
        'login' => 'login',
        'password' => 'password',
        'host' => 'smtp.gmail.com',
        'port' => 587,
        'emailFrom' => 'keks@phpdemo.ru',
    ],

    'pagination' => [
        'lots_per_page' => 9,
    ],
];
