<?php
/**
 * @var array $categories
 * @var array $lots
 */

?>

<main class="container">
    <section class="promo">
        <h2 class="promo__title">Нужен стафф для катки?</h2>
        <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное
            снаряжение.</p>
        <ul class="promo__list">
            <?php
            foreach ($categories as $category) : ?>
                <li class="promo__item promo__item--<?= htmlspecialchars($category['modifier']); ?>">
                    <a class="promo__link" href="/pages/all-lots.html"><?= htmlspecialchars($category['name']); ?></a>
                </li>
            <?php
            endforeach; ?>
        </ul>
    </section>
    <section class="lots">
        <div class="lots__header">
            <h2>Открытые лоты</h2>
        </div>
        <?= includeTemplate('lots-list.php', ['lots' => $lots]); ?>
    </section>
</main>

