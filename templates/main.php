<main class="container"> 
    <section class="promo">
        <h2 class="promo__title">Нужен стафф для катки?</h2>
        <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
        <ul class="promo__list">
            <!--заполните этот список из массива категорий-->
            <?php foreach($categories_arr as $item): ?>
                <li class="promo__item promo__item--<?=replace_specialchars($item['cat_url']);?>">
                    <a class="promo__link" href="pages/all-lots.html"><?=replace_specialchars($item['cat_name']);?></a>
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
            <?php foreach ($lots_arr as $item): ?>
                <li class="lots__item lot">
                    <?php list($hours, $minuts) = get_expiry_time($item['end_date']); ?>
                    <div class="lot__image">
                        <img src="/uploads/<?=replace_specialchars($item['id'] . '.' . replace_specialchars($item['img_url'])) ?>" width="350" height="260" alt="<?=replace_specialchars($item['name_lot']) ?>">
                    </div>
                    <div class="lot__info">
                        <span class="lot__category"><?=replace_specialchars($item['cat_name']) ?></span>
                        <h3 class="lot__title"><a class="text-link" href="/lot.php?id=<?=replace_specialchars($item['id'])?>"><?=replace_specialchars($item['name_lot']) ?></a></h3>
                        <div class="lot__state">
                            <div class="lot__rate">
                                <span class="lot__amount">Стартовая цена</span>
                                <span class="lot__cost"><?=replace_specialchars(price_format($item['start_price'])) ?></span>
                            </div>
                            <div class="lot__timer timer <?php if ($hours < 1): ?>timer--finishing<?php endif;?>">
                            <?php if($hours <= 0 && $minuts <= 0): ?>
                                00:00
                                <?php else: ?>
                                <?=replace_specialchars($hours . ":" . $minuts); ?>
                            <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>
</main>