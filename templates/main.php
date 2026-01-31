<?php

/**
 * @var array $categories
 * @var array $lots
 * @var string $listLots
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
                    <a class="promo__link" href="/search.php?category=<?= htmlspecialchars(
                        $category['id']
                    ); ?>"><?= htmlspecialchars($category['name']); ?></a>
                </li>
            <?php
            endforeach; ?>
        </ul>
    </section>
    <section class="lots">
        <div class="lots__header">
            <h2>Открытые лоты</h2>
        </div>
        <?= $listLots; ?>
    </section>
</main>

