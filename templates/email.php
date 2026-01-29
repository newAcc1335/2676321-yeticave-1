<?php

/**
 * @var string $userName
 * @var int $lotId
 * @var string $lotName
 * @var string $url
 */

$lotUrl = "{$url}/lot.php?id={$lotId}";
$bidUrl = "{$url}/my-bets.php";
?>

<h1>Поздравляем с победой</h1>
<p>Здравствуйте, <?= $userName; ?></p>
<p>Ваша ставка для лота <a href="<?= $lotUrl; ?>"><?= $lotName; ?></a> победила.</p>
<p>Перейдите по ссылке <a href="<?= $bidUrl; ?>">мои ставки</a>,
    чтобы связаться с автором объявления</p>
<small>Интернет-Аукцион "YetiCave"</small>
