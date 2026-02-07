<?php

/**
 * @var string $navigation
 * @var string $search
 * @var int $page
 * @var int $totalPages
 * @var array $lots
 * @var string $lotsList
 * @var string $query
 * @var string $message
 */

?>

<main>
    <?= $navigation; ?>
    <div class="container">
        <section class="lots">
            <h2><?= $message; ?></h2>
            <?php
            if (empty($lots)): ?>
                <p>Ничего не найдено по вашему запросу</p>
            <?php
            else: ?>
                <?= $lotsList; ?>
            <?php
            endif; ?>
        </section>
        <?php
        if ($totalPages > 1): ?>
            <ul class="pagination-list">
                <li class="pagination-item pagination-item-prev">
                    <?php
                    if ($page > 1): ?>
                        <a href="?<?= $query; ?>&page=<?= $page - 1; ?>">Назад</a>
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
                            <a href="?<?= $query; ?>&page=<?= $i; ?>"><?= $i; ?></a>
                        <?php
                        endif; ?>
                    </li>
                <?php
                endfor; ?>

                <li class="pagination-item pagination-item-next">
                    <?php
                    if ($page < $totalPages): ?>
                        <a href="?<?= $query; ?>&page=<?= $page + 1; ?>">Вперед</a>
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
