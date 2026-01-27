<?php

require_once __DIR__ . '/init.php';

/**
 * @var mysqli $conn
 * @var array $user
 * @var array $categories
 */

$search = trim(filter_input(INPUT_GET, 'search', FILTER_SANITIZE_SPECIAL_CHARS));
$page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?? 1;

$limit = 9;
$lots = [];
$totalPages = 0;

if ($page > 0 && $search !== '') {
    $search = mb_substr($search, 0, 100);

    try {
        $totalPages = (int)ceil(countLotsBySearch($conn, $search) / $limit);
        if ($totalPages >= $page) {
            $lots = getLotsBySearch($conn, $search, $page, $limit);
        } else {
            $totalPages = 0;
        }
    } catch (RuntimeException $e) {
        error_log($e->getMessage());
        exit('Ошибка при поиске данных в БД');
    }
}

$navigation = includeTemplate(
    'navigation.php',
    ['categories' => $categories]
);

$mainContent = includeTemplate(
    'search.php',
    [
        'navigation' => $navigation,
        'search' => $search,
        'page' => $page,
        'totalPages' => $totalPages,
        'lots' => $lots,
    ]
);

$layoutContent = includeTemplate(
    'layout.php',
    [
        'title' => 'Результаты поиска',
        'content' => $mainContent,
        'navigation' => $navigation,
        'user' => $user,
        'categories' => $categories,
        'search' => $search,
    ]
);

print($layoutContent);
