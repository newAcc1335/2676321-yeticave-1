<?php

/**
 * @var mysqli $conn
 */

require_once __DIR__ . '/init.php';

$isAuth = rand(0, 1);
$userName = 'Pavel';
$title = 'Главная';

$categories = getCategories($conn);
$lots = getLots($conn);

echo '<script>';
echo 'console.log(' . json_encode($lots, JSON_PRETTY_PRINT) . ');';
echo '</script>';

$products = [
    [
        'name' => '2014 Rossignol District Snowboard',
        'category' => 'Доски и лыжи',
        'price' => 10999,
        'imageUrl' => '/img/lot-1.jpg',
        'endDate' => '2025-12-02',
    ],
    [
        'name' => 'DC Ply Mens 2016/2017 Snowboard',
        'category' => 'Доски и лыжи',
        'price' => 159999,
        'imageUrl' => '/img/lot-2.jpg',
        'endDate' => '2025-12-03',
    ],
    [
        'name' => 'Крепления Union Contact Pro 2015 года размер L/XL',
        'category' => 'Крепления',
        'price' => 8000,
        'imageUrl' => '/img/lot-3.jpg',
        'endDate' => '2025-12-04',
    ],
    [
        'name' => 'Ботинки для сноуборда DC Mutiny Charcoal',
        'category' => 'Ботинки',
        'price' => 10999,
        'imageUrl' => '/img/lot-4.jpg',
        'endDate' => '2025-11-25',
    ],
    [
        'name' => 'Куртка для сноуборда DC Mutiny Charcoal',
        'category' => 'Одежда',
        'price' => 7500,
        'imageUrl' => '/img/lot-5.jpg',
        'endDate' => '2025-12-1 00:19',
    ],
    [
        'name' => 'Маска Oakley Canopy',
        'category' => 'Разное',
        'price' => 5400,
        'imageUrl' => '/img/lot-6.jpg',
        'endDate' => '2025-12-15',
    ],
];

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
