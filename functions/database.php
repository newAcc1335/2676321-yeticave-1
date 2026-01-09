<?php

/**
 * Создаёт и возвращает соединение с базой данных MySQL.
 *
 * @param array{host: string, user: string, password: string, database: string} $db_config
 * @return mysqli Соединение с базой данных
 * @throws RuntimeException Исключение в случие ошибки при подключении
 */
function getDbConnect(array $db_config): mysqli
{
    $conn = new mysqli(
        $db_config['host'],
        $db_config['user'],
        $db_config['password'],
        $db_config['database']
    );

    if ($conn->connect_error) {
        throw new RuntimeException($conn->connect_error);
    }

    $conn->set_charset('utf8mb4');

    return $conn;
}

/**
 * Выполняет SQL-запрос и возвращает все строки в форме массива ассоциативных массивов.
 *
 * @param mysqli $conn Соединение с базой данных MySQL
 * @param string $sql SQL-запрос
 * @return array<int, array<string, string|float|int>>|null Результат запроса или null, если строк не найдено
 * @throws RuntimeException В случае ошибки выполнения запроса
 */
function dbFetchAll(mysqli $conn, string $sql): ?array
{
    $result = $conn->query($sql);

    if (!$result) {
        throw new RuntimeException($conn->error);
    }

    return $result->fetch_all(MYSQLI_ASSOC) ?: null;
}

/**
 * Выполняет SQL-запрос и возвращает одну строку в форме ассоциативного массива.
 *
 * @param mysqli $conn Соединение с базой данных MySQL
 * @param string $sql SQL-запрос
 * @return array<string, string|float|int>|null Результат запроса или null, если строка не найдена
 * @throws RuntimeException В случае ошибки выполнения запроса
 */
function dbFetchOne(mysqli $conn, string $sql): ?array
{
    $result = $conn->query($sql);

    if (!$result) {
        throw new RuntimeException($conn->error);
    }

    return $result->fetch_assoc() ?: null;
}

/**
 * Возвращает названия и модификаторы всех категорий.
 *
 * @param mysqli $conn Соединение с базой данных
 * @return array<int, array{name: string, modifier: string}> Список категорий с названием и модификатором
 * @throws RuntimeException В случае ошибки выполнения запроса
 */
function getCategories(mysqli $conn): array
{
    $sql = 'SELECT name, modifier FROM categories';
    return dbFetchAll($conn, $sql);
}

/**
 * Возвращает последние активные лоты. Максимум 6 штук
 *
 * @param mysqli $conn Соединение с базой данных
 * @return array<int, array{
 *     name: string,
 *     startingPrice: int,
 *     price: int,
 *     imageUrl: string,
 *     endTime: string,
 *     category: string
 * }> Массив, состоящий из последних лотов
 * @throws RuntimeException В случае ошибки выполнения запроса
 */
function getLots(mysqli $conn): array
{
    $sql = '
        SELECT
            l.id as id,
            l.title AS name,
            l.starting_price AS startingPrice,
            l.image_url AS imageUrl,
            l.end_time AS endTime,
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

    return dbFetchAll($conn, $sql);
}

/**
 * Возвращает данные лота по его id.
 *
 * @param mysqli $conn Соединение с базой данных
 * @param int $lotId id лота
 * @return array<string, string|int|float>|null Ассоциативный массив с данными лота или null, если лот не найден
 * @throws RuntimeException В случае ошибки выполнения запроса
 */
function getLotById(mysqli $conn, int $lotId): ?array
{
    $sql = '
        SELECT
            l.title AS name,
            l.description,
            l.starting_price AS startingPrice,
            l.image_url AS imageUrl,
            l.start_time as startTime,
            l.end_time AS endTime,
            l.bid_step as step,
            c.name AS category,
            COALESCE(MAX(b.amount), l.starting_price) AS price
        FROM lots l
        JOIN categories c ON c.id = l.category_id
        LEFT JOIN bids b ON b.lot_id = l.id
        WHERE l.id = ' . $lotId . '
        GROUP BY l.id
    ';

    return dbFetchOne($conn, $sql);
}
