<?php

require_once __DIR__ . '/init.php';

/**
 * @var mysqli $conn
 */

$isAuth = rand(0, 1);
$userName = 'Pavel';
$title = 'Главная';

$categories = getCategories($conn);
$lots = getLots($conn);

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
