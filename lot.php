<?php

require_once __DIR__ . '/init.php';

/**
 * @var mysqli $conn
 * @var string $userName
 * @var int $isAuth
 */

try {
    $categories = getCategories($conn);
} catch (RuntimeException $e) {
    error_log($e->getMessage());
    exit('Ошибка при загрузке данных из БД');
}

$lotId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($lotId === null || $lotId === false || $lotId <= 0) {
    error404($isAuth, $userName, $categories);
}

$lot = getLotById($conn, $lotId);
if ($lot === null) {
    error404($isAuth, $userName, $categories);
}

$navigation = includeTemplate(
    'navigation.php',
    ['categories' => $categories]
);

$lotContent = includeTemplate(
    'lot.php',
    [
        'categories' => $categories,
        'lot' => $lot,
        'navigation' => $navigation
    ]
);

$layoutContent = includeTemplate(
    'layout.php',
    [
        'title' => $lot['name'],
        'content' => $lotContent,
        'navigation' => $navigation,
        'isAuth' => $isAuth,
        'userName' => $userName,
        'categories' => $categories,
    ]
);

print($layoutContent);
