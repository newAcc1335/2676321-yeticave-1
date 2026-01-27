<?php
/**
 * @var string $navigation
 * @var string $search
 * @var int $page
 * @var int $totalPages
 * @var array $lots
 */

?>

<main>
    <?= $navigation; ?>
    <div class="container">
        <section class="lots">
            <h2>Результаты поиска по запросу «<span><?= htmlspecialchars($search); ?></span>»</h2>
            <?php
            if (empty($lots)): ?>
                <p>Ничего не найдено по вашему запросу</p>
            <?php
            else: ?>
                <?= includeTemplate('lots-list.php', ['lots' => $lots]); ?>
            <?php
            endif; ?>
        </section>
        <?php
        if ($totalPages > 1): ?>
            <ul class="pagination-list">
                <li class="pagination-item pagination-item-prev">
                    <?php
                    if ($page > 1): ?>
                        <a href="?search=<?= urlencode($search); ?>&page=<?= $page - 1; ?>">Назад</a>
                    <?php
                    else: ?>
                        <a>Назад</a>
                    <?php
                    endif; ?>
                </li>

                <?php
                for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="pagination-item <?= $i === $page ? 'pagination-item-active' : ''; ?>">
                        <?php
                        if ($i === $page): ?>
                            <a><?= $i; ?></a>
                        <?php
                        else: ?>
                            <a href="?search=<?= urlencode($search); ?>&page=<?= $i; ?>"><?= $i; ?></a>
                        <?php
                        endif; ?>
                    </li>
                <?php
                endfor; ?>

                <li class="pagination-item pagination-item-next">
                    <?php
                    if ($page < $totalPages): ?>
                        <a href="?search=<?= urlencode($search) ?>&page=<?= $page + 1 ?>">Вперед</a>
                    <?php
                    else: ?>
                        <a>Вперед</a>
                    <?php
                    endif; ?>
                </li>
            </ul>
        <?php
        endif; ?>
    </div>
</main>
