<?php

function getDbConnect(array $db_config): mysqli
{
    $conn = new mysqli(
        $db_config['host'],
        $db_config['user'],
        $db_config['password'],
        $db_config['database']
    );

    if ($conn->connect_error) {
        error_log($conn->connect_error);
        exit('Ошибка подключения к базе данных');
    }

    $conn->set_charset('utf8mb4');

    return $conn;
}

function getCategories(mysqli $conn): array
{
    $sql = 'SELECT name, modifier FROM categories';
    $result = $conn->query($sql);

    if (!$result) {
        error_log($conn->error);
        exit('Ошибка при получении категорий товаров');
    }

    return $result->fetch_all(MYSQLI_ASSOC);
}

function getLots(mysqli $conn): array
{
    $sql = '
        SELECT
            l.title AS name,
            l.starting_price AS startingPrice,
            l.image_url AS imageUrl,
            l.end_time AS endDate,
            c.name AS category,
            COALESCE(MAX(b.amount), l.starting_price) AS price
        FROM lots l
        JOIN categories c ON c.id = l.category_id
        LEFT JOIN bids b ON b.lot_id = l.id
        WHERE l.end_time > NOW()
        GROUP BY l.id
        ORDER BY l.start_time DESC
        LIMIT 6;
    ';

    $result = $conn->query($sql);

    if (!$result) {
        error_log($conn->error);
        exit('Ошибка при получении последних лотов');
    }

    return $result->fetch_all(MYSQLI_ASSOC);
}
