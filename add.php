<?php

require_once __DIR__ . '/init.php';
require_once __DIR__ . '/functions/file.php';

/**
 * @var mysqli $conn
 * @var array $user
 * @var array $categories
 */

if (empty($user)) {
    renderErrorPage($user, $categories, 403, 'Доступ запрещен. Необходимо авторизоваться');
}

$form = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = validateAddLotForm($_POST);

    if (!empty($errors)) {
        $form['errors'] = $errors;
        $form['data'] = $_POST;
    } else {
        try {
            $filePath = __DIR__ . '/uploads/';
            $fileName = saveUploadedImage('image', $filePath);

            $lot = [
                'title' => $_POST['title'],
                'description' => $_POST['description'],
                'image_url' => '/uploads/' . $fileName,
                'end_time' => $_POST['end_time'],
                'starting_price' => (int)($_POST['starting_price']),
                'bid_step' => (int)($_POST['bid_step']),
                'category_id' => (int)($_POST['category']),
                'creator_id' => $user['id'],
            ];

            $lotId = addLot($conn, $lot);

            header("Location: /lot.php?id={$lotId}");
            exit();
        } catch (RuntimeException $e) {
            error_log($e->getMessage());
            exit('Ошибка при добавлении лота');
        }
    }
}

$navigation = includeTemplate(
    'navigation.php',
    ['categories' => $categories]
);

$mainContent = includeTemplate(
    'add.php',
    [
        'categories' => $categories,
        'navigation' => $navigation,
        'form' => $form,
    ]
);

$layoutContent = includeTemplate(
    'layout.php',
    [
        'title' => 'Добавление лота',
        'content' => $mainContent,
        'navigation' => $navigation,
        'user' => $user,
        'categories' => $categories,
    ]
);

print($layoutContent);
