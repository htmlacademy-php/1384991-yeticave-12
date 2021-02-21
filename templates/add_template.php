<main>
    <form class="form form--add-lot container <?php if(count($err)): ?>form--invalid<?php endif ;?>" action="/add.php" method="post" enctype="multipart/form-data"> <!-- form--invalid -->
      <h2>Добавление лота</h2>
      <div class="form__container-two">
        <div class="form__item <?php if($err['lot-name']): ?> form__item--invalid<?php endif; ?>"> <!-- form__item--invalid -->
          <label for="lot-name">Наименование <sup>*</sup></label>
          <input id="lot-name" type="text" name="lot-name" placeholder="Введите наименование лота" value="<?=clear_spec(getPostVal('lot-name')); ?>">
          <span class="form__error"><?=clear_spec($err['lot-name']); ?></span>
        </div>
        <div class="form__item <?php if($err['category']): ?>form__item--invalid<?php endif; ?>">
          <label for="category">Категория <sup>*</sup></label>
          <select id="category" name="category">
            <option value="">Выберите категорию</option>
            <?php foreach ($categories_arr as $item): ?>
	        	<option value="<?=clear_spec($item['id'])?>" <?php if(getPostVal('category') == $item['id']): ?>selected<?php endif; ?>><?=clear_spec($item['cat_name'])?></option>
        	<?php endforeach; ?>
          </select>
          <span class="form__error"><?=clear_spec($err['category']); ?></span>
        </div>
      </div>
      <div class="form__item form__item--wide <?php if($err['message']): ?>form__item--invalid<?php endif; ?>">
        <label for="message">Описание <sup>*</sup></label>
        <textarea id="message" name="message" placeholder="Напишите описание лота"><?=clear_spec(getPostVal('message')); ?></textarea>
        <span class="form__error"><?=clear_spec($err['message']); ?></span>
      </div>
      <div class="form__item form__item--file <?php if($err['image-lot']): ?>form__item--invalid<?php endif; ?>">
        <label>Изображение <sup>*</sup></label>
        <div class="form__input-file">
          <input class="visually-hidden" type="file" name="image-lot" id="lot-img" value="">
          <label for="lot-img">   
            Добавить
          </label>
        </div>
        <span class="form__error"><?=clear_spec($err['image-lot']); ?></span>
      </div>
      <div class="form__container-three">
        <div class="form__item form__item--small <?php if($err['lot-rate']): ?>form__item--invalid<?php endif; ?>">
          <label for="lot-rate">Начальная цена <sup>*</sup></label>
          <input id="lot-rate" type="text" name="lot-rate" placeholder="0" value="<?=clear_spec(getPostVal('lot-rate')); ?>">
          <span class="form__error"><?=clear_spec($err['lot-rate']); ?></span>
        </div>
        <div class="form__item form__item--small <?php if($err['lot-step']): ?>form__item--invalid<?php endif; ?>">
          <label for="lot-step">Шаг ставки <sup>*</sup></label>
          <input id="lot-step" type="text" name="lot-step" placeholder="0" value="<?=clear_spec(getPostVal('lot-step')); ?>">
          <span class="form__error"><?=clear_spec($err['lot-step']); ?></span>
        </div>
        <div class="form__item <?php if($err['lot-date']): ?>form__item--invalid<?php endif; ?>">
          <label for="lot-date">Дата окончания торгов <sup>*</sup></label>
          <input class="form__input-date" id="lot-date" type="text" name="lot-date" placeholder="Введите дату в формате ГГГГ-ММ-ДД" value="<?=clear_spec(getPostVal('lot-date')); ?>">
          <span class="form__error"><?=clear_spec($err['lot-date']); ?></span>
        </div>
      </div>
      <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
      <button type="submit" class="button">Добавить лот</button>
    </form>
 </main>