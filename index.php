<?php

include_once __DIR__ . '/helpers.php';

$isAuth = rand(0, 1);
$userName = 'Pavel';
$title = 'Главная';

$categories = [
    [
        'name' => 'Доски и лыжи',
        'modifier' => 'boards',
    ],
    [
        'name' => 'Крепления',
        'modifier' => 'attachment',
    ],
    [
        'name' => 'Ботинки',
        'modifier' => 'boots ',
    ],
    [
        'name' => 'Одежда',
        'modifier' => 'clothing',
    ],
    [
        'name' => 'Инструменты',
        'modifier' => 'tools',
    ],
    [
        'name' => 'Разное',
        'modifier' => 'other',
    ],
];

$products = [
    [
        'name' => '2014 Rossignol District Snowboard',
        'category' => 'Доски и лыжи',
        'price' => 10999,
        'imageUrl' => '/img/lot-1.jpg',
    ],
    [
        'name' => 'DC Ply Mens 2016/2017 Snowboard',
        'category' => 'Доски и лыжи',
        'price' => 159999,
        'imageUrl' => '/img/lot-2.jpg',
    ],
    [
        'name' => 'Крепления Union Contact Pro 2015 года размер L/XL',
        'category' => 'Крепления',
        'price' => 8000,
        'imageUrl' => '/img/lot-3.jpg',
    ],
    [
        'name' => 'Ботинки для сноуборда DC Mutiny Charcoal',
        'category' => 'Ботинки',
        'price' => 10999,
        'imageUrl' => '/img/lot-4.jpg',
    ],
    [
        'name' => 'Куртка для сноуборда DC Mutiny Charcoal',
        'category' => 'Одежда',
        'price' => 7500,
        'imageUrl' => '/img/lot-5.jpg',
    ],
    [
        'name' => 'Маска Oakley Canopy',
        'category' => 'Разное',
        'price' => 5400,
        'imageUrl' => '/img/lot-6.jpg',
    ],
];

$mainContent = includeTemplate(
    'main.php',
    ['categories' => $categories, 'products' => $products]
);

$layoutContent = includeTemplate(
    'layout.php',
    [
        'title' => $title,
        'content' => $mainContent,
        'isAuth' => $isAuth,
        'userName' => $userName,
        'categories' => $categories,
        'products' => $products,
    ]
);

print($layoutContent);
