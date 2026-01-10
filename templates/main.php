<?php
/**
 * @var array<int, array{name: string, modifier: string}> $categories
 * @var array<int, array{
 *      id: int,
 *      name: string,
 *      category: string,
 *      price: int,
 *      startingPrice: int,
 *      imageUrl: string,
 *      endTime: string
 *  }> $lots
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
        <ul class="lots__list">
            <?php
            foreach ($lots as $lot) : ?>
                <li class="lots__item lot">
                    <div class="lot__image">
                        <img src="<?= $lot['imageUrl']; ?>" width="350" height="260" alt="">
                    </div>
                    <div class="lot__info">
                        <span class="lot__category"><?= htmlspecialchars($lot['category']); ?></span>
                        <h3 class="lot__title">
                            <a class="text-link" href="/lot.php?id=<?= $lot['id']; ?>">
                                <?= htmlspecialchars($lot['name']); ?>
                            </a></h3>
                        <div class="lot__state">
                            <div class="lot__rate">
                                <span class="lot__amount">Стартовая цена</span>
                                <span class="lot__cost"><?= formatPrice($lot['startingPrice']); ?></span>
                            </div>
                            <?php
                            $dtRange = getDtRange($lot['endTime']);
                            $timerClass = $dtRange['hours'] === 0 ? 'timer--finishing' : '';
                            ?>
                            <div class="lot__timer timer <?= $timerClass; ?>">
                                <?= formatRange($dtRange); ?>
                            </div>
                        </div>
                    </div>
                </li>
            <?php
            endforeach; ?>
        </ul>
    </section>
</main>

