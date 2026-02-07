<?php

/**
 * Валидатор загруженного изображения.
 *
 * @param string $fieldName Имя поля формы с файлом
 *
 * @return string|null Сообщение об ошибке при невалидном файле или null, если файл валидный
 */
function validateImage(string $fieldName): ?string
{
    if (!isset($_FILES[$fieldName]) || empty($_FILES[$fieldName]['tmp_name'])) {
        return 'Загрузите файл с изображением лота';
    }

    if ($_FILES[$fieldName]['error'] !== UPLOAD_ERR_OK) {
        return 'Не получилось загрузить файл';
    }

    $fileName = $_FILES[$fieldName]['tmp_name'];
    $fileSize = $_FILES[$fieldName]['size'];

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $fileType = finfo_file($finfo, $fileName);

    if ($fileType !== 'image/jpeg' && $fileType !== 'image/png') {
        return 'Допустимые форматы файлов: jpg, jpeg, png';
    }

    if ($fileSize > 2 * 1024 * 1024) {
        return 'Максимальный размер файла: 2 Мб';
    }

    return null;
}

/**
 * Возвращает валидатор, проверяющий, что поле было задано.
 *
 * @return callable(mixed): bool
 */
function required(): callable
{
    return function ($value): bool {
        return !($value === null || $value === '');
    };
}

/**
 * Возвращает валидатор, проверяющий, что длина строки не превышает данное значение.
 *
 * @param int $max Максимальная длина
 *
 * @return callable(mixed): bool
 */
function maxLength(int $max): callable
{
    return function ($value) use ($max): bool {
        return is_string($value) && mb_strlen($value) <= $max;
    };
}

/**
 * Возвращает валидатор, проверяющий, что длина строки не меньше данного значения.
 *
 * @param int $min Минимальная длина
 *
 * @return callable(mixed): bool
 */
function minLength(int $min): callable
{
    return function ($value) use ($min): bool {
        return is_string($value) && mb_strlen($value) >= $min;
    };
}

/**
 * Возвращает валидатор, проверяющий, что значение является целым положительным числом.
 *
 * @param int $maxValue Максимальное допустимое значение (по умолчанию 1_000_000_000)
 *
 * @return callable(mixed): bool
 */
function positiveInt(int $maxValue = 1_000_000_000): callable
{
    return function ($value) use ($maxValue): bool {
        return filter_var($value, FILTER_VALIDATE_INT) !== false && $value > 0 && $value <= $maxValue;
    };
}

/**
 * Возвращает валидатор, проверяющий, что дата не раньше начала завтрашнего дня.
 *
 * Ожидается строка в формате 'ГГГГ-ММ-ДД'! (Будет работать для любой совместимой с strtotime())
 *
 * @return callable(mixed): bool
 */
function dateAtLeastTomorrow(): callable
{
    return function ($value): bool {
        $date = strtotime($value);
        $tomorrow = strtotime('tomorrow');
        return $date >= $tomorrow;
    };
}


/**
 *  Возвращает валидатор, проверяющий, что дата соответствует формату 'ГГГГ-ММ-ДД'.
 *
 * @return callable(mixed): bool
 */
function dateYmd(): callable
{
    return function ($value): bool {
        if (!is_string($value)) {
            return false;
        }

        $format = 'Y-m-d';
        $dateTimeObj = date_create_from_format($format, $value);
        $errors = date_get_last_errors();

        return $dateTimeObj !== false && ($errors === false || array_sum($errors) === 0);
    };
}

/**
 * Возвращает валидатор, проверяющий, что значение является корректным email-адресом.
 *
 * @return callable(mixed): bool
 */
function email(): callable
{
    return function ($value): bool {
        return is_string($value) && filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    };
}

/**
 * Возвращает валидатор, проверяющий, что e-mail ещё не зарегистрирован.
 *
 * @param mysqli $conn Соединение с базой данных
 *
 * @return callable(mixed): bool
 *
 * @throws RuntimeException В случае ошибки выполнения запроса
 */
function emailNotExists(mysqli $conn): callable
{
    return function ($email) use ($conn): bool {
        $sql = 'SELECT id FROM users WHERE email = ?';

        $stmt = dbGetPrepareStmt($conn, $sql, [$email]);

        if (!$stmt->execute()) {
            throw new RuntimeException('Не удалось проверить е-mail');
        }

        $result = $stmt->get_result();

        return $result->num_rows === 0;
    };
}

/**
 * Возвращает валидатор, проверяющий, что был соблюден минимальный шаг.
 *
 * @param int $price Текущая цена лота
 * @param int $step Минимальный шаг ставки
 * @param bool $hasBids Есть ли другие ставки
 *
 * @return callable(mixed): bool
 */
function validateBidStep(int $price, int $step, bool $hasBids): callable
{
    return function ($bid) use ($price, $step, $hasBids): bool {
        return $hasBids ? (int)$bid >= $price + $step : (int)$bid >= $price;
    };
}

/**
 * Возвращает валидатор, проверяющий, что выбранная категория существует.
 *
 * @param array $categories Массив категорий
 *
 * @return callable(mixed): bool
 */
function categoryExists(array $categories): callable
{
    $catIds = array_map('intval', array_column($categories, 'id'));

    return function (int $categoryId) use ($catIds): bool {
        return in_array($categoryId, $catIds, true);
    };
}
