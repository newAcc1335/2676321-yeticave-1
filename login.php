<?php

require_once __DIR__ . '/init.php';

/**
 * @var mysqli $conn
 * @var array $user
 * @var array $categories
 */

$errors = [];
$data = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = validateLoginForm($_POST);
    $userId = null;

    if (!empty($errors)) {
        $data = $_POST;
    } elseif (($userId = authenticateUser(
            $conn,
            $_POST[LoginField::EMAIL->value],
            $_POST[LoginField::PASSWORD->value]
        )) === null) {
        $errors = [
            LoginField::EMAIL->value => 'Вы ввели неверный email/пароль',
            LoginField::PASSWORD->value => 'Вы ввели неверный email/пароль'
        ];
        $data = $_POST;
    } else {
        $_SESSION['userId'] = $userId;
        header("Location: /");
        exit();
    }
}

$navigation = includeTemplate(
    'navigation.php',
    ['categories' => $categories]
);

$mainContent = includeTemplate(
    'login.php',
    [
        'navigation' => $navigation,
        'errors' => $errors,
        'data' => $data,
    ]
);

$layoutContent = includeTemplate(
    'layout.php',
    [
        'title' => 'Вход',
        'content' => $mainContent,
        'navigation' => $navigation,
        'user' => $user,
        'categories' => $categories,
    ]
);

print($layoutContent);
