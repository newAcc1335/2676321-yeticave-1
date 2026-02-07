<?php

/**
 * @var array $categories
 * @var string $navigation
 * @var array $data
 * @var array $errors
 */

?>
<main>
    <?= $navigation; ?>
    <form class="form form--add-lot container <?= !empty($errors) ? 'form--invalid' : ''; ?>"
          action="/add.php" method="post" enctype="multipart/form-data">
        <h2>Добавление лота</h2>
        <div class="form__container-two">
            <div class="form__item <?= isset($errors[LotField::TITLE->value]) ? 'form__item--invalid' : ''; ?>">
                <label for="lot-name">Наименование <sup>*</sup></label>
                <input id="lot-name" type="text" name="<?= LotField::TITLE->value; ?>" placeholder="Введите наименование лота"
                       value="<?= htmlspecialchars($data[LotField::TITLE->value] ?? ''); ?>">
                <span class="form__error"><?= $errors[LotField::TITLE->value] ?? '' ?></span>
            </div>
            <div class="form__item <?= isset($errors[LotField::CATEGORY->value]) ? 'form__item--invalid' : ''; ?>">
                <label for="category">Категория <sup>*</sup></label>
                <select id="category" name="<?= LotField::CATEGORY->value; ?>">
                    <option value="">Выберите категорию</option>
                    <?php
                    foreach ($categories as $category) : ?>
                        <option value="<?= htmlspecialchars($category['id']); ?>"
                            <?= (int)($data[LotField::CATEGORY->value] ?? 0) === (int)$category['id'] ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($category['name']); ?>
                        </option>
                    <?php
                    endforeach; ?>
                </select>
                <span class="form__error"><?= $errors[LotField::CATEGORY->value] ?? ''; ?></span>
            </div>
        </div>
        <div class="form__item form__item--wide <?= isset($errors[LotField::DESCRIPTION->value]) ? 'form__item--invalid' : ''; ?>">
            <label for="message">Описание <sup>*</sup></label>
            <textarea id="message" name="<?= LotField::DESCRIPTION->value; ?>" placeholder="Напишите описание лота"><?= htmlspecialchars(
                    $data[LotField::DESCRIPTION->value] ?? ''
                ); ?></textarea>
            <span class="form__error"><?= $errors[LotField::DESCRIPTION->value] ?? ''; ?></span>
        </div>
        <div class="form__item form__item--file <?= isset($errors[LotField::IMAGE->value]) ? 'form__item--invalid' : ''; ?>">
            <label>Изображение <sup>*</sup></label>
            <div class="form__input-file">
                <input class="visually-hidden" type="file" id="lot-img" value="" name="<?= LotField::IMAGE->value; ?>">
                <label for="lot-img">
                    Добавить
                </label>
            </div>
            <span class="form__error"><?= $errors[LotField::IMAGE->value] ?? ''; ?></span>
        </div>
        <div class="form__container-three">
            <div class="form__item form__item--small <?= isset($errors[LotField::STARTING_PRICE->value]) ? 'form__item--invalid' : ''; ?>">
                <label for="lot-rate">Начальная цена <sup>*</sup></label>
                <input id="lot-rate" type="text" name="<?= LotField::STARTING_PRICE->value; ?>" placeholder="0"
                       value="<?= htmlspecialchars($data[LotField::STARTING_PRICE->value] ?? ''); ?>">
                <span class="form__error"><?= $errors[LotField::STARTING_PRICE->value] ?? ''; ?></span>
            </div>
            <div class="form__item form__item--small <?= isset($errors[LotField::BID_STEP->value]) ? 'form__item--invalid' : ''; ?>">
                <label for="lot-step">Шаг ставки <sup>*</sup></label>
                <input id="lot-step" type="text" name="<?= LotField::BID_STEP->value; ?>" placeholder="0"
                       value="<?= htmlspecialchars($data[LotField::BID_STEP->value] ?? ''); ?>">
                <span class="form__error"><?= $errors[LotField::BID_STEP->value] ?? ''; ?></span>
            </div>
            <div class="form__item <?= isset($errors[LotField::END_TIME->value]) ? 'form__item--invalid' : ''; ?>">
                <label for="lot-date">Дата окончания торгов <sup>*</sup></label>
                <input class="form__input-date" id="lot-date" type="text" name="<?= LotField::END_TIME->value; ?>"
                       placeholder="Введите дату в формате ГГГГ-ММ-ДД"
                       value="<?= htmlspecialchars($data[LotField::END_TIME->value] ?? ''); ?>">
                <span class="form__error"><?= $errors[LotField::END_TIME->value] ?? ''; ?></span>
            </div>
        </div>
        <span class="form__error form__error--bottom">
            <?= !empty($errors) ? 'Пожалуйста, исправьте ошибки в форме.' : ''; ?>
        </span>
        <button type="submit" class="button">Добавить лот</button>
    </form>
</main>
