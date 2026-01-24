<?php
/**
 * @var string $navigation
 * @var string $message
 * @var int $codeErr
 */

?>

<main>
    <?= $navigation; ?>
    <section class="lot-item container">
        <h2>Ошибка <?= $codeErr; ?></h2>
        <p><?= $message; ?></p>
    </section>
</main>
