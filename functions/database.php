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
    $sql = 'SELECT id, name, modifier FROM categories';
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
 * }> Массив, состоящий из последних лотов, или пустой массив, если активных лотов нет
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

    return dbFetchAll($conn, $sql) ?? [];
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
    $sql = "
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
        WHERE l.id = {$lotId}
        GROUP BY l.id
    ";

    return dbFetchOne($conn, $sql);
}

/**
 * Возвращает список всех ставок для указанного лота. Ставки сортируются по дате создания (сначала новые).
 *
 * @param mysqli $conn Соединение с базой данных
 * @param int $lotId ID лота
 *
 * @return array<int, array{
 *     amount: int,
 *     createdAt: string,
 *     userName: string,
 *     userId: int
 * }> Массив ставок или пустой массив, если ставок нет
 *
 * @throws RuntimeException В случае ошибки выполнения запроса
 */
function getLotBids(mysqli $conn, int $lotId): array
{
    $sql = "
        SELECT
            b.amount AS amount,
            b.created_at AS createdAt,
            u.name AS userName,
            u.id AS userId
        FROM bids b
        JOIN users u ON u.id = b.user_id
        WHERE b.lot_id = {$lotId}
        ORDER BY b.created_at DESC
    ";

    return dbFetchAll($conn, $sql) ?? [];
}

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 *
 * @throws RuntimeException В случае ошибки выполнения запроса
 */
function dbGetPrepareStmt(mysqli $link, string $sql, array $data = []): mysqli_stmt
{
    $stmt = mysqli_prepare($link, $sql);

    if ($stmt === false) {
        $errorMsg = 'Не удалось инициализировать подготовленное выражение: ' . mysqli_error($link);
        die($errorMsg);
    }

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = 's';

            if (is_int($value)) {
                $type = 'i';
            } elseif (is_string($value)) {
                $type = 's';
            } elseif (is_double($value)) {
                $type = 'd';
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);

        if (mysqli_errno($link) > 0) {
            $errorMsg = 'Не удалось связать подготовленное выражение с параметрами: ' . mysqli_error($link);
            throw new RuntimeException($errorMsg);
//            die($errorMsg);
        }
    }

    return $stmt;
}

/**
 * Добавляет новый лот в базу данных
 *
 * @param mysqli $conn Соединение с базой данных
 * @param array{
 *     title: string,
 *     description: string,
 *     image_url: string,
 *     end_time: string,
 *     starting_price: int,
 *     bid_step: int,
 *     category_id: int,
 *     creator_id: int
 * } $data
 *
 * @return int ID созданного лота
 *
 * @throws RuntimeException В случае ошибки выполнения запроса
 */
function addLot(mysqli $conn, array $data): int
{
    $sql = '
        INSERT INTO lots (
            title,
            description,
            image_url,
            end_time,
            starting_price,
            bid_step,
            category_id,
            creator_id
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ';

    $stmt = dbGetPrepareStmt($conn, $sql, [
        $data['title'],
        $data['description'],
        $data['image_url'],
        $data['end_time'],
        (int)$data['starting_price'],
        (int)$data['bid_step'],
        (int)$data['category_id'],
        (int)$data['creator_id'],
    ]);

    if (!$stmt->execute()) {
        throw new RuntimeException('Не удалось добавить лот в базу данных');
    }

    return $conn->insert_id;
}
