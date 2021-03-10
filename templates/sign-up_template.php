<main>
    <form class="form container <?php if(count($err)): ?>form--invalid <?php endif; ?>" action="/sign_up.php" method="post" autocomplete="off"> <!-- form
    --invalid -->
      <h2>Регистрация нового аккаунта</h2>
      <div class="form__item <?php if($err['email']): ?> form__item--invalid<?php endif; ?>"> <!-- form__item--invalid -->
        <label for="email">E-mail <sup>*</sup></label>
        <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?=replace_specialchars(getFillVal('email')); ?>">
        <span class="form__error"><?=replace_specialchars($err['email']); ?></span>
      </div>
      <div class="form__item <?php if($err['password']): ?> form__item--invalid<?php endif; ?>">
        <label for="password">Пароль <sup>*</sup></label>
        <input id="password" type="password" name="password" placeholder="Введите пароль">
        <span class="form__error"><?=replace_specialchars($err['password']); ?></span>
      </div>
      <div class="form__item <?php if($err['name']): ?> form__item--invalid<?php endif; ?>">
        <label for="name">Имя <sup>*</sup></label>
        <input id="name" type="text" name="name" placeholder="Введите имя" value="<?=replace_specialchars(getFillVal('name')); ?>">
        <span class="form__error"><?=replace_specialchars($err['name']); ?></span>
      </div>
      <div class="form__item <?php if($err['message']): ?> form__item--invalid<?php endif; ?>">
        <label for="message">Контактные данные <sup>*</sup></label>
        <textarea id="message" name="message" placeholder="Напишите как с вами связаться"><?=replace_specialchars(getFillVal('message')); ?></textarea>
        <span class="form__error"><?=replace_specialchars($err['message']); ?></span>
      </div>
      <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
      <button type="submit" class="button">Зарегистрироваться</button>
      <a class="text-link" href="#">Уже есть аккаунт</a>
    </form>
</main>