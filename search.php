<?php

require_once __DIR__ . '/init.php';

/**
 * @var mysqli $conn
 * @var array $user
 * @var array $categories
 */

$search = trim(filter_input(INPUT_GET, 'search', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');
$search = mb_substr($search, 0, 100);
$page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;
$categoryId = filter_input(INPUT_GET, 'category', FILTER_VALIDATE_INT) ?: null;

if ($categoryId !== null && !categoryExists($categories)($categoryId)) {
    $categoryId = null;
}

$limit = 9;
$lots = [];
$totalPages = 0;

if ($page > 0 && ($search !== '' || $categoryId !== null)) {
    try {
        $totalPages = (int)ceil(countLotsBySearch($conn, $search, $categoryId) / $limit);

        if ($totalPages >= $page) {
            $lots = getLotsBySearch($conn, $search, $categoryId, $page, $limit);
        } else {
            $totalPages = 0;
        }
    } catch (RuntimeException $e) {
        error_log($e->getMessage());
        exit('Ошибка при поиске данных в БД');
    }
}

$query = getLotsQuery($categoryId, $search);
$titlePage = $search !== '' ? 'Результаты поиска' : 'Все лоты';
$lotsTitle = getLotsTitle($categoryId, $search, $categories);

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
        'query' => $query,
        'message' => $lotsTitle,
    ]
);

$layoutContent = includeTemplate(
    'layout.php',
    [
        'title' => $titlePage,
        'content' => $mainContent,
        'navigation' => $navigation,
        'user' => $user,
        'categories' => $categories,
        'search' => $search,
    ]
);

print($layoutContent);
