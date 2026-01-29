<?php
/**
 * @var array $categories
 * @var array $lot
 * @var string $navigation
 * @var array $lotBids
 * @var array $user
 * @var array $form
 */

$data = $form['data'] ?? [];
$errors = $form['errors'] ?? [];
?>

<main>
    <?= $navigation; ?>
    <section class="lot-item container">
        <h2><?= htmlspecialchars($lot['name']); ?></h2>
        <div class="lot-item__content">
            <div class="lot-item__left">
                <div class="lot-item__image">
                    <img src=<?= htmlspecialchars($lot['imageUrl']); ?> width="730" height="548"
                         alt="<?= htmlspecialchars($lot['name']); ?>">
                </div>
                <p class="lot-item__category">Категория: <span><?= htmlspecialchars($lot['category']); ?></span></p>
                <p class="lot-item__description"><?= htmlspecialchars($lot['description']); ?></p>
            </div>
            <div class="lot-item__right">
                <?php
                if (!empty($user) && !isLotFinished($lot['endTime'])) : ?>
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
                        <form class="lot-item__form" action="/lot.php?id=<?= $lot['id']; ?>" method="post" autocomplete="off">
                            <p class="lot-item__form-item form__item <?= isset($errors['cost']) ? 'form__item--invalid' : ''; ?>">
                                <label for="cost">Ваша ставка</label>
                                <input id="cost" type="text" name="cost" placeholder="<?= formatPrice(
                                    $lot['price'] + $lot['step'],
                                    false
                                ); ?>" value="<?= htmlspecialchars($data['cost'] ?? ''); ?>">
                                <span class="form__error"><?= $errors['cost'] ?? '' ?></span>
                            </p>
                            <button type="submit" class="button">Сделать ставку</button>
                        </form>
                    </div>
                <?php
                endif; ?>
                <div class="history">
                    <h3>История ставок (<span><?= count($lotBids); ?></span>)</h3>
                    <table class="history__list">
                        <?php
                        foreach ($lotBids as $bid) : ?>
                            <tr class="history__item">
                                <td class="history__name"><?= htmlspecialchars($bid['userName']); ?></td>
                                <td class="history__price"><?= formatPrice($bid['amount'], false); ?> р</td>
                                <td class="history__time"><?= formatTimeAgo($bid['createdAt']); ?></td>
                            </tr>
                        <?php
                        endforeach; ?>
                    </table>
                </div>
            </div>
        </div>
    </section>
</main>

