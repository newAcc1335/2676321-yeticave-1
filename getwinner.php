<?php

/**
 * @var mysqli $conn
 * @var array $config
 */

require_once __DIR__ . '/functions/mail.php';

$finishedLots = getFinishedLots($conn);
$mailer = createMailer($config);

foreach ($finishedLots as $lot) {
    try {
        if (setLotWinner($conn, $lot['lotId'], $lot['winnerId'])) {
            sendEmail($mailer, $lot, $config);
        }
    } catch (Exception $e) {
        error_log($e->getMessage());
        exit('Ошибка при запросах установки победителей');
    }
}
