<?php

require_once __DIR__ . '/init.php';

/**
 * @var mysqli $conn
 */

$isAuth = rand(0, 1);
$userName = 'Pavel';
$title = '';

$lotId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

try {
    $categories = getCategories($conn);
    $lot = getLotById($conn, $lotId);
} catch (RuntimeException $e) {
    error_log($e->getMessage());
    exit('Ошибка при загрузке данных из БД');
}

$lotContent = includeTemplate(
    'lot.php',
    ['categories' => $categories, 'lot' => $lot]
);

$layoutContent = includeTemplate(
    'layout.php',
    [
        'title' => $title,
        'content' => $lotContent,
        'isAuth' => $isAuth,
        'userName' => $userName,
        'categories' => $categories,
    ]
);

print($layoutContent);
