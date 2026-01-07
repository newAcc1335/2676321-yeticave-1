<?php

require_once __DIR__ . '/init.php';

/**
 * @var mysqli $conn
 */

$isAuth = rand(0, 1);
$userName = 'Pavel';
$title = 'Главная';

try {
    $categories = getCategories($conn);
    $lots = getLots($conn);
} catch (RuntimeException $e) {
    error_log($e->getMessage());
    exit('Ошибка при загрузке данных из БД');
}

$mainContent = includeTemplate(
    'main.php',
    ['categories' => $categories, 'lots' => $lots]
);

$layoutContent = includeTemplate(
    'layout.php',
    [
        'title' => $title,
        'content' => $mainContent,
        'isAuth' => $isAuth,
        'userName' => $userName,
        'categories' => $categories,
    ]
);

print($layoutContent);
