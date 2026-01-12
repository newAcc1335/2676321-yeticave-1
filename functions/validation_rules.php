<?php

/**
 * @param string $fieldName
 * @return string|null возвращает либо ошибку, либо нал
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

function required($value): bool
{
    return !($value === null || $value === '');
}

function maxLength(int $max): callable
{
    return function ($value) use ($max): bool {
        return is_string($value) && mb_strlen($value) <= $max;
    };
}

function positiveInt($value): bool
{
    return filter_var($value, FILTER_VALIDATE_INT) !== false && $value > 0;
}

function dateAtLeastTomorrow(): callable
{
    return function ($value): bool {
        if (!$value) {
            return false;
        }

        $inputDate = strtotime($value);
        $tomorrow = strtotime('+1 day');

        return $inputDate >= $tomorrow;
    };
}


/**
 * Проверяет переданную дату на соответствие формату 'ГГГГ-ММ-ДД'
 *
 * Примеры использования:
 * isDateValid('2019-01-01'); // true
 * isDateValid('2016-02-29'); // true
 * isDateValid('2019-04-31'); // false
 * isDateValid('10.10.2010'); // false
 * isDateValid('10/10/2010'); // false
 *
 * @param string $date Дата в виде строки
 *
 * @return bool true при совпадении с форматом 'ГГГГ-ММ-ДД', иначе false
 */
function isDateValid($date): bool
{
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);

    $errors = date_get_last_errors();

    if ($errors === false) {
        return $dateTimeObj !== false;
    }

    return $dateTimeObj !== false && array_sum($errors) === 0;
}
