<?php

require_once __DIR__ . '/init.php';

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
    try {
        $errors = validateRegisterForm($_POST, $conn);
    } catch (RuntimeException $e) {
        error_log($e->getMessage());
        exit('Ошибка при проверке уникальности e-mail в БД');
    }

    if (!empty($errors)) {
        $form['errors'] = $errors;
        $form['data'] = $_POST;
    } else {
        $user = [
            'name' => $_POST['name'],
            'email' => $_POST['email'],
            'password_hash' => password_hash($_POST['password'], PASSWORD_DEFAULT),
            'contact_info' => $_POST['contactInfo'],
        ];

        try {
            addUser($conn, $user);
        } catch (RuntimeException $e) {
            error_log($e->getMessage());
            exit('Ошибка при регистрации нового аккаунта');
        }

        header("Location: /");
        exit();
    }
}

$navigation = includeTemplate(
    'navigation.php',
    ['categories' => $categories]
);

$mainContent = includeTemplate(
    'register.php',
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
