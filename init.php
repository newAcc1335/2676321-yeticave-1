<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
ini_set("error_log", __DIR__ . "/error.log");
error_reporting(E_ALL);

require_once __DIR__ . '/functions/template.php';
require_once __DIR__ . '/functions/validators.php';
require_once __DIR__ . '/functions/database.php';

if (!file_exists('config.php')) {
    exit('Файл конфигурации не найден');
}

$config = require_once __DIR__ . '/config.php';

try {
    $conn = getDbConnect($config['db']);
} catch (RuntimeException $e) {
    error_log($e->getMessage());
    exit('Ошибка подключения к базе данных');
}

session_start();
$user = [];

if (isset($_SESSION['userId'])) {
    $user = getUserById($conn, $_SESSION['userId']) ?? [];
}
