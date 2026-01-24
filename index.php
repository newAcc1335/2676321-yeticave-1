<?php

require_once __DIR__ . '/init.php';

/**
 * @var mysqli $conn
 * @var string $userName
 * @var int $isAuth
 * @var array $user
 *
 */

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

$navigation = includeTemplate(
    'navigation.php',
    ['categories' => $categories]
);

$layoutContent = includeTemplate(
    'layout.php',
    [
        'title' => $title,
        'content' => $mainContent,
        'navigation' => $navigation,
        'user' => $user,
        'categories' => $categories,
    ]
);

print($layoutContent);
