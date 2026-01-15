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
    finfo_close($finfo);

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
 * Возвращает валидатор, проверяющий, что значение является целым положительным числом.
 *
 * @return callable(mixed): bool
 */
function positiveInt(): callable
{
    return function ($value): bool {
        return filter_var($value, FILTER_VALIDATE_INT) !== false && $value > 0;
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

        if ($errors === false) {
            return $dateTimeObj !== false;
        }

        return $dateTimeObj !== false && array_sum($errors) === 0;
    };
}
