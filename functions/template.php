<?php

/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 *
 * @param string $name Путь к файлу шаблона относительно папки templates
 * @param array $data Ассоциативный массив с данными для шаблона
 * @return string Итоговый HTML
 */
function includeTemplate(string $name, array $data = []): string
{
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    return ob_get_clean();
}


/**
 * Форматирует строку с ценой, разделяя по разрядам и добавляя знак рубля.
 *
 * @param int|float $price Стоимость товара в рублях
 * @param bool $withRub Добавлять ли знак рубля. Если true - то с знаком, если false - без знака
 * @return string Отформатированная строка с ценой
 */
function formatPrice(int|float $price, bool $withRub = true): string
{
    $formatted = number_format(ceil($price), 0, '', ' ');

    if ($withRub) {
        $formatted .= ' <b class="rub">р</b>';
    }

    return $formatted;
}

/**
 * Считает количество минут и часов до окончания аукциона.
 *
 * @param string $endDate Дата окончания аукциона
 * @return array{hours: int, minutes: int} Ассоциативный массив с количеством минут и часов
 */
function getDtRange(string $endDate): array
{
    $now = new DateTime();
    try {
        $end = new DateTime($endDate);
    } catch (Exception $e) {
        error_log($e->getMessage());
        return [
            'hours' => 0,
            'minutes' => 0
        ];
    }


    $diff = $now->diff($end);
    $hours = 0;
    $minutes = 0;

    if ($diff->invert === 0) {
        $hours = $diff->h + $diff->days * 24;
        $minutes = $diff->i;
    }

    return [
        'hours' => $hours,
        'minutes' => $minutes
    ];
}

/**
 * Возвращает CSS-класс таймера в зависимости от оставшегося времени. Если осталось меньше часа, то возвращается
 * CSS-класс "timer--finishing", иначе — пустая строка.
 *
 * @param string $endDate Дата окончания аукциона
 *
 * @return string CSS-класс таймера или пустая строка
 */
function getTimerClass(string $endDate): string
{
    $dtRange = getDtRange($endDate);
    return $dtRange['hours'] === 0 ? 'timer--finishing' : '';
}

/**
 * Преобразует массив с временем до конца аукциона в строку формата "ЧЧ:ММ".
 *
 * @param array{hours: int, minutes: int} $dtRange Ассоциативный массив с количеством минут и часов до конца аукциона
 * @return string Отформатированная строка
 */
function formatRange(array $dtRange): string
{
    $hours = str_pad($dtRange['hours'], 2, '0', STR_PAD_LEFT);
    $minutes = str_pad($dtRange['minutes'], 2, '0', STR_PAD_LEFT);

    return $hours . ':' . $minutes;
}

/**
 * Возвращает корректную форму множественного числа
 * Ограничения: только для целых чисел
 *
 * Пример использования:
 * $remaining_minutes = 5;
 * echo "Я поставил таймер на {$remaining_minutes} " .
 *     get_noun_plural_form(
 *         $remaining_minutes,
 *         'минута',
 *         'минуты',
 *         'минут'
 *     );
 * Результат: "Я поставил таймер на 5 минут"
 *
 * @param int $number Число, по которому вычисляем форму множественного числа
 * @param string $one Форма единственного числа: яблоко, час, минута
 * @param string $two Форма множественного числа для 2, 3, 4: яблока, часа, минуты
 * @param string $many Форма множественного числа для остальных чисел
 *
 * @return string Рассчитанная форма множественнго числа
 */
function getNounPluralForm(int $number, string $one, string $two, string $many): string
{
    $number = (int)$number;
    $mod10 = $number % 10;
    $mod100 = $number % 100;

    switch (true) {
        case ($mod100 >= 11 && $mod100 <= 20):
            return $many;

        case ($mod10 > 5):
            return $many;

        case ($mod10 === 1):
            return $one;

        case ($mod10 >= 2 && $mod10 <= 4):
            return $two;

        default:
            return $many;
    }
}

/**
 * Формирует страницу с кодом ошибки без редиректа.
 *
 * @param array $user Массив с данными юзера, который авторизован на сайте, или пустой массив если такого нет
 * @param array $categories Массив категорий
 * @param int $codeErr Код ошибки
 * @param string $message Сообщение, которое будет показано на странице ошибки
 * @return void
 */
function renderErrorPage(array $user, array $categories, int $codeErr, string $message): void
{
    $navigation = includeTemplate(
        'navigation.php',
        ['categories' => $categories]
    );

    $mainContent = includeTemplate(
        'error.php',
        [
            'navigation' => $navigation,
            'message' => $message,
            'codeErr' => $codeErr,
        ]
    );

    $layoutContent = includeTemplate(
        'layout.php',
        [
            'title' => "Ошибка {$codeErr}",
            'content' => $mainContent,
            'navigation' => $navigation,
            'user' => $user,
            'categories' => $categories,
        ]
    );

    http_response_code($codeErr);

    print $layoutContent;

    exit();
}

/**
 * Возвращает строку, показывающую сколько времени прошло с объявления ставки.
 *
 * @param string $createdAt Время создания ставки (формат, поддерживающий DateTime)
 * @return string Строка с указанием прошедшего времени или 'недавно', если произошла ошибка
 */
function formatTimeAgo(string $createdAt): string
{
    $now = new DateTime();
    try {
        $created = new DateTime($createdAt);
    } catch (Exception $e) {
        error_log($e->getMessage());
        return 'недавно';
    }

    $diff = $now->diff($created);

    if ($diff->invert === 0) {
        return 'недавно';
    }

    $hours = $diff->h + $diff->days * 24;
    $minutes = $diff->i;

    if ($hours === 0) {
        if ($minutes === 0) {
            return 'менее минуты назад';
        }
        return $minutes . ' ' . getNounPluralForm($minutes, 'минута', 'минуты', 'минут') . ' назад';
    }

    return $hours . ' ' . getNounPluralForm($hours, 'час', 'часа', 'часов') . ' назад';
}
