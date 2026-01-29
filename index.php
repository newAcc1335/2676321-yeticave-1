<?php

require_once __DIR__ . '/init.php';
require_once __DIR__ . '/getwinner.php';

/**
 * @var mysqli $conn
 * @var string $userName
 * @var int $isAuth
 * @var array $user
 * @var array $categories
 *
 */

try {
    $lots = getLots($conn);
} catch (RuntimeException $e) {
    error_log($e->getMessage());
    exit('Ошибка при загрузке данных лотов из БД');
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
        'title' => 'Главная',
        'content' => $mainContent,
        'navigation' => $navigation,
        'user' => $user,
        'categories' => $categories,
    ]
);

print($layoutContent);
