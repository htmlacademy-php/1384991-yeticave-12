<main>
    <form class="form container <?php if(count($err)): ?>form--invalid <?php endif; ?>" action="/login.php" method="post"> <!-- form--invalid -->
      <h2>Вход</h2>
      <div class="form__item <?php if($err['email']): ?> form__item--invalid<?php endif; ?>"> <!-- form__item--invalid -->
        <label for="email">E-mail <sup>*</sup></label>
        <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?=replace_specialchars(getFillVal('email')); ?>">
        <span class="form__error"><?=replace_specialchars($err['email']); ?></span>
      </div>
      <div class="form__item form__item--last <?php if($err['password']): ?> form__item--invalid<?php endif; ?>">
        <label for="password">Пароль <sup>*</sup></label>
        <input id="password" type="password" name="password" placeholder="Введите пароль">
        <span class="form__error"><?=replace_specialchars($err['password']); ?></span>
      </div>
      <button type="submit" class="button">Войти</button>
    </form>
</main>