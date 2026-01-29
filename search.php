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

$params = [];

if ($categoryId !== null) {
    $params['category'] = $categoryId;
}

if ($search !== '') {
    $params['search'] = $search;
}

$query = http_build_query($params);
$titlePage = $search !== '' ? 'Результаты поиска' : 'Все лоты';

if ($search !== '') {
    $message = "Результаты поиска по запросу «<span>{$search}</span>»";
} elseif ($categoryId !== null) {
    $cat = findCategoryById($categories, $categoryId);
    $message = $cat ? "Все лоты в категории {$cat['name']}" : 'Все лоты';
} else {
    $message = '';
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
        'query' => $query,
        'message' => $message,
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
