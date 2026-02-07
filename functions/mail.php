<?php

use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

/**
 * Создает и возвращает объект Mailer
 *
 * @param array $config Массив с данными конфигурации
 *
 * @return Mailer объект Mailer
 */
function createMailer(array $config): Mailer
{
    $dsn = sprintf(
        'smtp://%s:%s@%s:%s',
        $config['mailer']['login'],
        $config['mailer']['password'],
        $config['mailer']['host'],
        $config['mailer']['port']
    );
    $transport = Transport::fromDsn($dsn);

    return new Mailer($transport);
}

/**
 * Отправляет письмо
 *
 * @param Mailer $mailer Объект почтового отправителя
 * @param array $lot Массив с данными лота
 * @param array $config Массив с данными конфигурации
 *
 * @return bool возвращает true, если сообщение отправлено, и false, если нет
 */
function sendEmail(Mailer $mailer, array $lot, array $config): bool
{
    $url = $config['app']['url'];
    $lotId = (int)$lot['lotId'];

    $lotUrl = "$url/lot.php?id=$lotId";
    $bidUrl = "$url/my-bets.php";

    $message = includeTemplate(
        'email.php',
        [
            'userName' => $lot['winnerName'],
            'lotId' => $lotId,
            'lotName' => $lot['lotTitle'],
            'lotUrl' => $lotUrl,
            'bidUrl' => $bidUrl,
            'url' => $url,
        ],
    );

    $email = new Email();
    $email->to($lot['winnerEmail']);
    $email->from($config['mailer']['emailFrom']);
    $email->subject('Ваша ставка победила');
    $email->html($message);

    $sent = false;
    try {
        $mailer->send($email);
        $sent = true;
    } catch (TransportExceptionInterface $e) {
        error_log($e->getMessage());
    }

    return $sent;
}
