<?php
/**
 * @var array $categories
 * @var string $navigation
 * @var array $form
 */

$data = $form['data'] ?? [];
$errors = $form['errors'] ?? [];
?>
<main>
    <?= $navigation; ?>
    <form class="form form--add-lot container <?= !empty($errors) ? 'form--invalid' : '' ?>"
          action="/add.php" method="post" enctype="multipart/form-data">
        <h2>Добавление лота</h2>
        <div class="form__container-two">
            <div class="form__item <?= isset($errors['title']) ? 'form__item--invalid' : '' ?>">
                <label for="lot-name">Наименование <sup>*</sup></label>
                <input id="lot-name" type="text" name="title" placeholder="Введите наименование лота"
                       value="<?= htmlspecialchars($data['title'] ?? ''); ?>">
                <span class="form__error"><?= $errors['title'] ?? '' ?></span>
            </div>
            <div class="form__item <?= isset($errors['category']) ? 'form__item--invalid' : '' ?>">
                <label for="category">Категория <sup>*</sup></label>
                <select id="category" name="category">
                    <option value="">Выберите категорию</option>
                    <?php
                    foreach ($categories as $category) : ?>
                        <option value="<?= htmlspecialchars($category['id']); ?>"
                            <?= ($data['category'] ?? '') === $category['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($category['name']) ?>
                        </option>
                    <?php
                    endforeach; ?>
                </select>
                <span class="form__error"><?= $errors['category'] ?? '' ?></span>
            </div>
        </div>
        <div class="form__item form__item--wide <?= isset($errors['description']) ? 'form__item--invalid' : '' ?>">
            <label for="message">Описание <sup>*</sup></label>
            <textarea id="message" name="description" placeholder="Напишите описание лота"><?= htmlspecialchars(
                    $data['description'] ?? ''
                ) ?></textarea>
            <span class="form__error"><?= $errors['description'] ?? '' ?></span>
        </div>
        <div class="form__item form__item--file <?= isset($errors['image']) ? 'form__item--invalid' : '' ?>">
            <label>Изображение <sup>*</sup></label>
            <div class="form__input-file">
                <input class="visually-hidden" type="file" id="lot-img" value="" name="image">
                <label for="lot-img">
                    Добавить
                </label>
            </div>
            <span class="form__error"><?= $errors['image'] ?? 'ываыва' ?></span>
        </div>
        <div class="form__container-three">
            <div class="form__item form__item--small <?= isset($errors['starting_price']) ? 'form__item--invalid' : '' ?>">
                <label for="lot-rate">Начальная цена <sup>*</sup></label>
                <input id="lot-rate" type="text" name="starting_price" placeholder="0"
                       value="<?= htmlspecialchars($data['starting_price'] ?? '') ?>">
                <span class="form__error"><?= $errors['starting_price'] ?? '' ?></span>
            </div>
            <div class="form__item form__item--small <?= isset($errors['bid_step']) ? 'form__item--invalid' : '' ?>">
                <label for="lot-step">Шаг ставки <sup>*</sup></label>
                <input id="lot-step" type="text" name="bid_step" placeholder="0"
                       value="<?= htmlspecialchars($data['bid_step'] ?? '') ?>">
                <span class="form__error"><?= $errors['bid_step'] ?? '' ?></span>
            </div>
            <div class="form__item <?= isset($errors['end_time']) ? 'form__item--invalid' : '' ?>">
                <label for="lot-date">Дата окончания торгов <sup>*</sup></label>
                <input class="form__input-date" id="lot-date" type="text" name="end_time"
                       placeholder="Введите дату в формате ГГГГ-ММ-ДД"
                       value="<?= htmlspecialchars($data['end_time'] ?? '') ?>">
                <span class="form__error"><?= $errors['end_time'] ?? '' ?></span>
            </div>
        </div>
        <span class="form__error form__error--bottom">
            <?= !empty($errors) ? 'Пожалуйста, исправьте ошибки в форме.' : '' ?>
        </span>
        <button type="submit" class="button">Добавить лот</button>
    </form>
</main>
