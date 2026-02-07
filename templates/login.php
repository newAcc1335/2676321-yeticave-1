<?php

/**
 * @var string $navigation
 * @var array $data
 * @var array $errors
 */

?>

<main>
    <?= $navigation; ?>
    <form class="form container <?= !empty($errors) ? 'form--invalid' : ''; ?>" action="/login.php" method="post">
        <h2>Вход</h2>
        <div class="form__item <?= isset($errors[LoginField::EMAIL->value]) ? 'form__item--invalid' : ''; ?>">
            <label for="email">E-mail <sup>*</sup></label>
            <input id="email" type="text" name="<?= LoginField::EMAIL->value; ?>" placeholder="Введите e-mail"
                   value="<?= htmlspecialchars($data[LoginField::EMAIL->value] ?? ''); ?>">
            <span class="form__error"><?= $errors[LoginField::EMAIL->value] ?? ''; ?></span>
        </div>
        <div class="form__item form__item--last <?= isset($errors[LoginField::PASSWORD->value]) ? 'form__item--invalid' : ''; ?>">
            <label for="password">Пароль <sup>*</sup></label>
            <input id="password" type="password" name="<?= LoginField::PASSWORD->value; ?>" placeholder="Введите пароль">
            <span class="form__error"><?= $errors[LoginField::PASSWORD->value] ?? ''; ?></span>
        </div>
        <button type="submit" class="button">Войти</button>
    </form>
</main>
