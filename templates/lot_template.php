<main>
    <section class="lot-item container">
      <h2><?=$row_lot['name_lot']?></h2>
      <div class="lot-item__content">
        <div class="lot-item__left">
          <div class="lot-item__image">
            <img src="/uploads/<?=clear_spec($row_lot['id'] . '.' . $row_lot['img_url'])?>" width="730" height="548" alt="Сноуборд">
          </div>
          <p class="lot-item__category">Категория: <span><?=clear_spec($categories_arr[$row_lot['cat_id']]['cat_name'])?></span></p>
          <p class="lot-item__description"><?=clear_spec($row_lot['description_lot'])?></p>
        </div>
        <div class="lot-item__right">
          <div class="lot-item__state">
            <?php list($hours, $minuts) = get_expiry_time($row_lot['end_date']); ?>
            <div class="lot-item__timer timer <?php if ($hours < 1): ?>timer--finishing<?php endif;?>">
              <?php if($hours <= 0 && $minuts <= 0): ?>
                00:00
              <?php else: ?>
                <?=clear_spec($hours . ":" . $minuts); ?>
              <?php endif; ?>
            </div>
            <div class="lot-item__cost-state">
              <div class="lot-item__rate">
                <span class="lot-item__amount">Текущая цена</span>
                <span class="lot-item__cost"><?=price_format(clear_spec($row_lot['current_price']))?></span>
              </div>
              <div class="lot-item__min-cost">
                Мин. ставка <span><?=price_format(clear_spec($min_bet))?></span>
              </div>
            </div>
            <?php if(isset($_SESSION['user'])): ?>
            <form class="lot-item__form" action="/lot.php?id=<?=$row_lot['id']?>" method="post" autocomplete="off">
              <p class="lot-item__form-item <?php if (count($err)): ?>form__item form__item--invalid<?php endif; ?>">
                <label for="cost">Ваша ставка</label>
                <input id="cost" type="text" name="cost" placeholder="<?=price_format(clear_spec($min_bet))?>">
                <span class="form__error"><?=clear_spec($err['cost'])?></span>
              </p>
              <button type="submit" class="button">Сделать ставку</button>
            </form>
            <?php endif; ?>
          </div>
          <div class="history">
            <h3>История ставок (<span><?=clear_spec(count($list_bets))?></span>)</h3>
            <table class="history__list">
              <?php foreach ($list_bets as $bet): ?>
              <tr class="history__item">
                <td class="history__name"><?=clear_spec($bet['user_name']) ?></td>
                <td class="history__price"><?=price_format(clear_spec($bet['bet_price'])) ?></td>
                <td class="history__time"><?=clear_spec(date('d.m.y в H:i', strtotime ($bet['add_date']))) ?><!--19.03.17 в 08:21--></td>
              </tr>
            <?php endforeach; ?>
            </table>
          </div>
        </div>
      </div>
    </section>
</main>