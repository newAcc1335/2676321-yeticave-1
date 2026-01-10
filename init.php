<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . '/functions/template.php';
require_once __DIR__ . '/functions/validators.php';
require_once __DIR__ . '/functions/database.php';

if (!file_exists("config.php")) {
    exit('Файл конфигурации не найден');
}

$config = require_once __DIR__ . '/config.php';

try {
    $conn = getDbConnect($config['db']);
} catch (RuntimeException $e) {
    error_log($e->getMessage());
    exit('Ошибка подключения к базе данных');
}

$isAuth = rand(0, 1);
$userName = 'Pavel';
