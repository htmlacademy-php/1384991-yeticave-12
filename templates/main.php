    <section class="promo">
        <h2 class="promo__title">Нужен стафф для катки?</h2>
        <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
        <ul class="promo__list">
            <!--заполните этот список из массива категорий-->
            <?php foreach($categories as $value): ?>
                <li class="promo__item promo__item--boards">
                    <a class="promo__link" href="pages/all-lots.html"><?=clear_spec($value);?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>
    <section class="lots">
        <div class="lots__header">
            <h2>Открытые лоты</h2>
        </div>
        <ul class="lots__list">
            <!--заполните этот список из массива с товарами-->
            <?php foreach ($products as $item): ?>
                <li class="lots__item lot">
                    <div class="lot__image">
                        <img src="<?=clear_spec($item['img_url']) ?>" width="350" height="260" alt="">
                    </div>
                    <div class="lot__info">
                        <span class="lot__category"><?=clear_spec($item['category']) ?></span>
                        <h3 class="lot__title"><a class="text-link" href="pages/lot.html"><?=clear_spec($item['name']) ?></a></h3>
                        <div class="lot__state">
                            <div class="lot__rate">
                                <span class="lot__amount">Стартовая цена</span>
                                <span class="lot__cost"><?php echo price_format(clear_spec($item['price'])) ?></span>
                            </div>
                            <div class="lot__timer timer <?php if (add_finishing_class($item['exp_date'])): ?><?='timer--finishing';?><?php endif;?>">
                            <?php if(get_finish_lot($item['exp_date'])): ?>
                                <?='00:00';?>
                                <?php else: ?>
                                <?=implode(":", (get_expiry_time(clear_spec($item['exp_date'])))); ?>
                            <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>
