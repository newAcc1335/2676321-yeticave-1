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
$lot = null;

if ($lotId === null || $lotId === false || $lotId <= 0 || ($lot = getLotById($conn, $lotId)) === null) {
    error404($isAuth, $userName, $categories);
}

try {
    $lotBids = getLotBids($conn, $lotId);
} catch (RuntimeException $e) {
    error_log($e->getMessage());
    exit('Ошибка при загрузке данных из БД');
}

$navigation = includeTemplate(
    'navigation.php',
    ['categories' => $categories]
);

$mainContent = includeTemplate(
    'lot.php',
    [
        'categories' => $categories,
        'lot' => $lot,
        'lotBids' => $lotBids,
        'navigation' => $navigation
    ]
);

$layoutContent = includeTemplate(
    'layout.php',
    [
        'title' => $lot['name'],
        'content' => $mainContent,
        'navigation' => $navigation,
        'isAuth' => $isAuth,
        'userName' => $userName,
        'categories' => $categories,
    ]
);

print($layoutContent);
