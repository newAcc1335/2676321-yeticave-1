<?php

/**
 * @var string $userName
 * @var int $lotId
 * @var string $lotUrl
 * @var string $bidUrl
 * @var string $lotName
 * @var string $url
 */

?>

<h1>Поздравляем с победой</h1>
<p>Здравствуйте, <?= $userName; ?></p>
<p>Ваша ставка для лота <a href="<?= $lotUrl; ?>"><?= $lotName; ?></a> победила.</p>
<p>Перейдите по ссылке <a href="<?= $bidUrl; ?>">мои ставки</a>,
    чтобы связаться с автором объявления</p>
<small>Интернет-Аукцион "YetiCave"</small>
