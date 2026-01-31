<?php

require_once __DIR__ . '/init.php';

/**
 * @var mysqli $conn
 * @var array $user
 * @var array $categories
 */

$lotId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$lot = null;

if ($lotId === null || $lotId === false || $lotId <= 0 || ($lot = getLotById($conn, $lotId)) === null) {
    renderErrorPage($user, $categories, 404, 'Страница не найдена');
}

try {
    $lotBids = getLotBids($conn, $lotId);
} catch (RuntimeException $e) {
    error_log($e->getMessage());
    exit('Ошибка при загрузке данных из БД');
}

$data = [];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($user)) {
    $errors = validateAddBidForm($_POST, $lotBids, $lot);

    if (!empty($errors)) {
        $data = $_POST;
    } else {
        $bid = (int)$_POST['cost'];

        try {
            addBid($conn, $user['id'], $lotId, $bid);
            header("Location: /lot.php?id={$lotId}");
            exit();
        } catch (RuntimeException $e) {
            error_log($e->getMessage());
            exit('Ошибка при добавлении ставки');
        }
    }
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
        'navigation' => $navigation,
        'user' => $user,
        'data' => $data,
        'errors' => $errors,
    ]
);

$layoutContent = includeTemplate(
    'layout.php',
    [
        'title' => $lot['name'],
        'content' => $mainContent,
        'navigation' => $navigation,
        'user' => $user,
        'categories' => $categories,
    ]
);

print($layoutContent);
