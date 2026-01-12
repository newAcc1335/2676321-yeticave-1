<?php
/**
 * @var array<int, array{name: string, modifier: string}> $categories
 * @var array{
 *     id: int,
 *     name: string,
 *     description: string,
 *     imageUrl: string,
 *     startTime: string,
 *     endTime: string,
 *     startingPrice: int,
 *     price: int,
 *     step: int,
 *     category: string,
 * } $lot
 * @var string $navigation
 * @var array $lotBids
 */

?>

<main>
    <?= $navigation; ?>
    <section class="lot-item container">
        <h2><?= htmlspecialchars($lot['name']); ?></h2>
        <div class="lot-item__content">
            <div class="lot-item__left">
                <div class="lot-item__image">
                    <img src=<?= htmlspecialchars($lot['imageUrl']); ?> width="730" height="548"
                         alt="<?= htmlspecialchars($lot['category']); ?>">
                </div>
                <p class="lot-item__category">Категория: <span><?= htmlspecialchars($lot['category']); ?></span></p>
                <p class="lot-item__description"><?= htmlspecialchars($lot['description']); ?></p>
            </div>
            <div class="lot-item__right">
                <div class="lot-item__state">
                    <div class="lot-item__timer timer <?= getTimerClass($lot['endTime']); ?>">
                        <?= formatRange(getDtRange($lot['endTime'])); ?>
                    </div>
                    <div class="lot-item__cost-state">
                        <div class="lot-item__rate">
                            <span class="lot-item__amount">Текущая цена</span>
                            <span class="lot-item__cost"><?= formatPrice($lot['price'], false); ?></span>
                        </div>
                        <div class="lot-item__min-cost">
                            Мин. ставка <span><?= formatPrice($lot['price'] + $lot['step'], false); ?> р</span>
                        </div>
                    </div>
                    <form class="lot-item__form" action="https://echo.htmlacademy.ru" method="post" autocomplete="off">
                        <p class="lot-item__form-item form__item form__item--invalid">
                            <label for="cost">Ваша ставка</label>
                            <input id="cost" type="text" name="cost" placeholder="<?= formatPrice(
                                $lot['price'] + $lot['step'],
                                false
                            ); ?>">
                            <span class="form__error">Введите наименование лота</span>
                        </p>
                        <button type="submit" class="button">Сделать ставку</button>
                    </form>
                </div>
                <div class="history">
                    <h3>История ставок (<span><?= count($lotBids); ?></span>)</h3>
                    <table class="history__list">
                        <?php
                        foreach ($lotBids as $bid) : ?>
                            <tr class="history__item">
                                <td class="history__name"><?= htmlspecialchars($bid['userName']); ?></td>
                                <td class="history__price"><?= formatPrice($bid['amount'], false); ?> р</td>
                                <td class="history__time">5 минут назад</td>
                            </tr>
                        <?php
                        endforeach; ?>
                    </table>
                </div>
            </div>
        </div>
    </section>
</main>

