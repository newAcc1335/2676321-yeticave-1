<?php

require_once __DIR__ . '/init.php';

/**
 * @var mysqli $conn
 * @var array $user
 */

try {
    $categories = getCategories($conn);
} catch (RuntimeException $e) {
    error_log($e->getMessage());
    exit('Ошибка при загрузке данных из БД');
}

$form = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = validateLoginForm($_POST);
    $userId = null;

    if (!empty($errors)) {
        $form['errors'] = $errors;
        $form['data'] = $_POST;
    } elseif ($userId = authenticateUser($conn, $_POST['email'], $_POST['password']) === null) {
        $form['errors'] = ['email' => 'Вы ввели неверный email/пароль', 'password' => 'Вы ввели неверный email/пароль'];
        $form['data'] = $_POST;
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
        'form' => $form,
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
