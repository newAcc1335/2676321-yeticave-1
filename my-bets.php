<?php

require_once __DIR__ . '/init.php';

/**
 * @var mysqli $conn
 * @var array $user
 * @var array $categories
 */

if (empty($user)) {
    renderErrorPage($user, $categories, 403, 'Доступ запрещен. Необходимо авторизоваться');
}

try {
    $bids = getUserBids($conn, $user['id']);
} catch (RuntimeException $e) {
    error_log($e->getMessage());
    exit('Ошибка при загрузке ставок пользователя');
}

$navigation = includeTemplate(
    'navigation.php',
    ['categories' => $categories]
);

$mainContent = includeTemplate(
    'my-bets.php',
    [
        'navigation' => $navigation,
        'bids' => $bids,
        'userId' => (int)$user['id'],
    ]
);

$layoutContent = includeTemplate(
    'layout.php',
    [
        'title' => 'Мои ставки',
        'content' => $mainContent,
        'navigation' => $navigation,
        'user' => $user,
        'categories' => $categories,
    ]
);

print($layoutContent);
