<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . '/functions/helpers.php';
require_once __DIR__ . '/functions/database.php';

$config = require_once __DIR__ . '/config.php';

try {
    $conn = getDbConnect($config['db']);
} catch (RuntimeException $e) {
    error_log($e->getMessage());
    exit('Ошибка подключения к базе данных');
}
