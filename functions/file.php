<?php

/**
 * Сохраняет загруженное изображение на диск (перемещает из временного хранилища в указанную директорию).
 *
 * @param string $fieldName Имя поля формы с загружаемым файлом
 * @param string $filePath Путь к директории для сохранения файла
 *
 * @return string Имя сохранённого файла
 *
 * @throws RuntimeException Если не удалось сохранить файл или недопустимый тип файла
 */
function saveUploadedImage(string $fieldName, string $filePath): string
{
    $tmpName = $_FILES[$fieldName]['tmp_name'];

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $fileType = finfo_file($finfo, $tmpName);
    finfo_close($finfo);

    $extensionMap = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
    ];

    if (!isset($extensionMap[$fileType])) {
        throw new RuntimeException('Недопустимый тип файла');
    }

    $extension = $extensionMap[$fileType];

    $fileName = uniqid('img_') . '.' . $extension;

    if (move_uploaded_file($tmpName, $filePath . $fileName) === false) {
        throw new RuntimeException('Не удалось сохранить файл');
    };

    return $fileName;
}
