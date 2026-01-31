<?php

/**
 * @var array $categories
 * @var string $navigation
 * @var array $bids
 * @var int $userId
 */

?>

<main>
    <?= $navigation; ?>
    <section class="rates container">
        <h2>Мои ставки</h2>
        <table class="rates__list">
            <?php
            foreach ($bids as $bid): ?>
                <tr class="rates__item <?= getBidRowClass($bid); ?> ">
                    <td class="rates__info">
                        <div class="rates__img">
                            <img src="<?= htmlspecialchars(
                                $bid['lotImage']
                            ); ?>" width="54" height="40" alt="<?= htmlspecialchars(
                                $bid['lotTitle']
                            ); ?>">
                        </div>
                        <div>
                            <h3 class="rates__title">
                                <a href="lot.php?id=<?= (int)$bid['lotId']; ?>"><?= htmlspecialchars(
                                        $bid['lotTitle']
                                    ); ?></a>
                            </h3>
                            <p><?= $bid['isWinner'] ? htmlspecialchars($bid['contactInfo']) : ''; ?></p>
                        </div>
                    </td>
                    <td class="rates__category">
                        <?= htmlspecialchars($bid['category']); ?>
                    </td>
                    <td class="rates__timer">
                        <div class="timer <?= getBidTimerClass($bid); ?>">
                            <?= getBidTimerText($bid); ?>
                        </div>
                    </td>
                    <td class="rates__price">
                        <?= formatPrice($bid['amount'], false); ?> р
                    </td>
                    <td class="rates__time">
                        <?= formatTimeAgo($bid['createdAt']); ?>
                    </td>
                </tr>
            <?php
            endforeach; ?>
        </table>
    </section>
</main>
