<?php
require 'init.php';

if (!isset($_SESSION['user'])) {
  	getErrorPage(403, $categories_arr);
  	exit;
}

/*
	1. Пишем запрос в таблицу bets с id текущего юзера для получения списка его ставок
		Объединяем в запросе таблицу bets c lots и categories:
			получаем следующие поля:
				- lots.id, lots.name_lot, lots.end_date, lots.cat_id, lots.user_id, lots.winner_id
				- bets *
				- categories.id, categories.cat_name

*/

$getBetsSql = "SELECT max(bets.add_date) AS add_date, bets.lot_id, max(bets.bet_price) AS user_bet, 
		(SELECT max(bet_price) FROM bets WHERE lot_id = lots.id) AS current_price,
	categories.cat_name, lots.id, lots.name_lot, lots.end_date, lots.cat_id, lots.img_url,  lots.user_id, lots.winner_id FROM bets 
	JOIN lots ON bets.lot_id = lots.id     
	JOIN categories ON lots.cat_id = categories.id
	WHERE bets.user_id = ? 
	GROUP BY bets.lot_id
	ORDER BY add_date DESC";

$stmt_bets = getSqlPrepare($db_connect, $getBetsSql, [$_SESSION['user']['id']]);
$result_bets = $stmt_bets->get_result();
$list_bets = $result_bets->fetch_all(MYSQLI_ASSOC);

$page_content = include_template('my-bets_template.php', ['categories_arr' => $categories_arr, 'list_bets' => $list_bets]);

$layout_content = include_template('layout.php', ['page_content' => $page_content, 'page_title' => 'Мои ставки', 'categories_arr' => $categories_arr]);
print $layout_content;