<?php

require_once __DIR__ . '/init.php';
require_once __DIR__ . '/functions/file.php';

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

$form = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = validateAddLotForm($_POST);

    if (!empty($errors)) {
        $form['errors'] = $errors;
        $form['data'] = $_POST;
    } else {
        try {
            $filePath = __DIR__ . '/uploads/';
            $imgUrl = saveUploadedImage('image', $filePath);

            //пока id = 1
            $lotData = [
                'title' => $_POST['title'],
                'description' => $_POST['description'],
                'image_url' => '/uploads/' . $imgUrl,
                'end_time' => $_POST['end_time'],
                'starting_price' => (int)($_POST['starting_price']),
                'bid_step' => (int)($_POST['bid_step']),
                'category_id' => (int)($_POST['category']),
                'creator_id' => 1,
            ];

            $lotId = addLot($conn, $lotData);

            header("Location: /lot.php?id={$lotId}");
            exit();
        } catch (RuntimeException $e) {
            error_log($e->getMessage());
            exit('Не уверен, что надо завершать сценарий если проблема была в загрузке файла, но пока вот так =)');
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
        'isAuth' => $isAuth,
        'userName' => $userName,
        'categories' => $categories,
    ]
);

print($layoutContent);
