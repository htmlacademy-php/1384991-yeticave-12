<main class="rates container">
  <h2>Мои ставки</h2>
  <table class="rates__list">
    <?php foreach ($list_bets as $item): ?>
    <?php list($hours, $minuts, $seconds) = get_expiry_time($item['end_date']); ?>
    <tr class="rates__item <?php if ($hours <= 0 && $minuts <= 0): ?>rates__item--end<?php endif; ?>">
      <td class="rates__info">
        <div class="rates__img">
          <img src="/uploads/<?=my_htmlspecialchars($item['lot_id'] . '.' . my_htmlspecialchars($item['img_url']))?>" width="54" height="40" alt="<?=my_htmlspecialchars($item['name_lot']) ?>">
        </div>
        <h3 class="rates__title"><a href="lot.php?id=<?=my_htmlspecialchars($item['lot_id'])?>"><?=my_htmlspecialchars($item['name_lot'])?></a></h3>
      </td>
      <td class="rates__category">
        <?=my_htmlspecialchars($item['cat_name'])?>
      </td>
      <td class="rates__timer">
        <div class="timer <?php if ($hours <= 0 && $minuts <= 0): ?>timer--end<?php elseif ($hours < 1): ?>timer--finishing<?php endif; ?>">
          <?php if($hours <= 0 && $minuts <= 0): ?>
            Торги окончены
            <?php else: ?>
            <?=my_htmlspecialchars($hours . ":" . $minuts . ":" . $seconds); ?>
          <?php endif; ?>
        </div>
      </td>
      <td class="rates__price">
        <?=my_htmlspecialchars(price_format($item['current_price']))?>
      </td>
      <td class="main_price">
        <?php if ($item['user_bet'] == $item['current_price']): ?>
          Вы лидер
        <?php else: ?>
          <?=my_htmlspecialchars(price_format($item['user_bet']))?>
        <?php endif; ?>
      </td>
      <td class="rates__time">
        <?=my_htmlspecialchars(get_pub_date($item['add_date']))?>
      </td>
    </tr>
  <?php endforeach; ?>
  </table>
</main>