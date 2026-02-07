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
    <form class="form container <?= !empty($errors) ? 'form--invalid' : ''; ?>" action="/register.php" method="post" autocomplete="off">
        <h2>Регистрация нового аккаунта</h2>
        <div class="form__item <?= isset($errors[RegisterField::EMAIL->value]) ? 'form__item--invalid' : ''; ?>">
            <label for="email">E-mail <sup>*</sup></label>
            <input id="email" type="text" name="<?= RegisterField::EMAIL->value; ?>" placeholder="Введите e-mail"
                   value="<?= htmlspecialchars($data[RegisterField::EMAIL->value] ?? ''); ?>">
            <span class="form__error"><?= $errors[RegisterField::EMAIL->value] ?? ''; ?></span>
        </div>
        <div class="form__item <?= isset($errors[RegisterField::PASSWORD->value]) ? 'form__item--invalid' : ''; ?>">
            <label for="password">Пароль <sup>*</sup></label>
            <input id="password" type="password" name="<?= RegisterField::PASSWORD->value; ?>" placeholder="Введите пароль">
            <span class="form__error"><?= $errors[RegisterField::PASSWORD->value] ?? ''; ?></span>
        </div>
        <div class="form__item <?= isset($errors[RegisterField::NAME->value]) ? 'form__item--invalid' : ''; ?>">
            <label for="name">Имя <sup>*</sup></label>
            <input id="name" type="text" name="<?= RegisterField::NAME->value; ?>" placeholder="Введите имя"
                   value="<?= htmlspecialchars($data[RegisterField::NAME->value] ?? ''); ?>">
            <span class="form__error"><?= $errors[RegisterField::NAME->value] ?? ''; ?></span>
        </div>
        <div class="form__item <?= isset($errors[RegisterField::CONTACT_INFO->value]) ? 'form__item--invalid' : ''; ?>">
            <label for="message">Контактные данные <sup>*</sup></label>
            <textarea id="message" name="<?= RegisterField::CONTACT_INFO->value; ?>" placeholder="Напишите как с вами связаться"><?= htmlspecialchars(
                    $data[RegisterField::CONTACT_INFO->value] ?? ''
                ); ?></textarea>
            <span class="form__error"><?= $errors[RegisterField::CONTACT_INFO->value] ?? ''; ?></span>
        </div>
        <span class="form__error form__error--bottom">
            <?= !empty($errors) ? 'Пожалуйста, исправьте ошибки в форме.' : ''; ?>
        </span>
        <button type="submit" class="button">Зарегистрироваться</button>
        <a class="text-link" href="/login.php">Уже есть аккаунт</a>
    </form>
</main>
