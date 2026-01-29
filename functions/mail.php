<?php

require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

/**
 * Создает и возвращает объект Mailer
 *
 * @param array $config
 *
 * @return Mailer
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
 * @param Mailer $mailer
 * @param array $lot
 * @param array $config
 *
 * @return bool
 */
function sendEmail(
    Mailer $mailer,
    array $lot,
    array $config
): bool {
    //$url = 'http://localhost:8000/';

    $message = includeTemplate(
        'email.php',
        [
            'userName' => $lot['winnerName'],
            'lotId' => $lot['lotId'],
            'lotName' => $lot['lotTitle'],
            'url' => $config['mailer']['url'],
        ],
    );

    $email = new Email();
    $email->to($lot['winnerContact']);
    $email->from($config['mailer']['emailFrom']);
    $email->subject('Ваша ставка победила');
    $email->html($message);

    try {
        $mailer->send($email);
        return true;
    } catch (Exception $e) {
        error_log($e->getMessage());
        return false;
    }
}
