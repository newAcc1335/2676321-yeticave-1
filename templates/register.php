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
    <form class="form container <?= !empty($errors) ? 'form--invalid' : '' ?>" action="/register.php" method="post" autocomplete="off">
        <h2>Регистрация нового аккаунта</h2>
        <div class="form__item <?= isset($errors['email']) ? 'form__item--invalid' : '' ?>">
            <label for="email">E-mail <sup>*</sup></label>
            <input id="email" type="text" name="email" placeholder="Введите e-mail"
                   value="<?= htmlspecialchars($data['email'] ?? ''); ?>">
            <span class="form__error"><?= $errors['email'] ?? '' ?></span>
        </div>
        <div class="form__item <?= isset($errors['password']) ? 'form__item--invalid' : '' ?>">
            <label for="password">Пароль <sup>*</sup></label>
            <input id="password" type="password" name="password" placeholder="Введите пароль">
            <span class="form__error"><?= $errors['password'] ?? '' ?></span>
        </div>
        <div class="form__item <?= isset($errors['name']) ? 'form__item--invalid' : '' ?>">
            <label for="name">Имя <sup>*</sup></label>
            <input id="name" type="text" name="name" placeholder="Введите имя"
                   value="<?= htmlspecialchars($data['name'] ?? '') ?>">
            <span class="form__error"><?= $errors['name'] ?? '' ?></span>
        </div>
        <div class="form__item <?= isset($errors['contactInfo']) ? 'form__item--invalid' : '' ?>">
            <label for="message">Контактные данные <sup>*</sup></label>
            <textarea id="message" name="contactInfo" placeholder="Напишите как с вами связаться"><?= htmlspecialchars(
                    $data['contactInfo'] ?? ''
                ) ?></textarea>
            <span class="form__error"><?= $errors['contactInfo'] ?? '' ?></span>
        </div>
        <span class="form__error form__error--bottom">
            <?= !empty($errors) ? 'Пожалуйста, исправьте ошибки в форме.' : '' ?>
        </span>
        <button type="submit" class="button">Зарегистрироваться</button>
        <a class="text-link" href="#">Уже есть аккаунт</a>
    </form>
</main>
