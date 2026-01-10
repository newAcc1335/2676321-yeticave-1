<?php
/**
 * @var array<int, array{name: string, modifier: string}> $categories
 */

?>

<nav class="nav">
    <ul class="nav__list container">
        <?php
        foreach ($categories as $category) : ?>
            <li class="nav__item">
                <a href="all-lots.html"><?= htmlspecialchars($category['name']); ?></a>
            </li>
        <?php
        endforeach; ?>
    </ul>
</nav>
