<?php
require 'init.php';

if (!isset($_GET['id'])) {
	getErrorPage(404, $categories_arr);
}
// запрос на получение информации о лоте
$get_lot_sql = "SELECT lots.*, COALESCE (max(bets.bet_price), lots.start_price) AS current_price, categories.cat_name FROM lots 
LEFT JOIN bets ON lots.id = bets.lot_id 
JOIN categories ON lots.cat_id = categories.id
WHERE lots.id=?
GROUP BY lots.id";

$stmt_lot = getSqlPrepare($db_connect, $get_lot_sql, [$_GET['id']]);
$result_lot = $stmt_lot->get_result();
$row_lot = $result_lot->fetch_assoc();
$min_bet = '';
if (!$row_lot) {
	getErrorPage(404, $categories_arr);
}
$min_bet = $row_lot['current_price'] + $row_lot['step_bet'];

$err = [];
if (isset($_SESSION['user'])) {
	if ($_POST) {
		if (empty($_POST['cost'])) {
			$err['cost'] = 'Введите сумму ставки';
		} elseif (!ctype_digit($_POST['cost']) OR $_POST['cost'] <= 0) {
			$err['cost'] = 'Введите целое положительное число';
		} elseif ($_POST['cost'] < $min_bet) {
			$err['cost'] = 'Ставка не может быть ниже, чем ' .  price_format($min_bet);
		} else {
			$addBetSql = "INSERT INTO bets SET bet_price = ?, user_id = ?, lot_id = ?";
			getSqlPrepare($db_connect, $addBetSql, [$_POST['cost'], $_SESSION['user']['id'], $row_lot['id']]);
			header("Location: /lot.php?id=" . $row_lot['id']);
			exit;
		}
	}
}

// запрос на получение списка ставок
$get_bets_sql = "SELECT bets.*, users.user_name FROM bets
JOIN users ON users.id = bets.user_id
WHERE lot_id=?
ORDER BY add_date DESC";
$stmt_bets = getSqlPrepare($db_connect, $get_bets_sql, [$_GET['id']]);
$result_bets = $stmt_bets->get_result();
$list_bets = $result_bets->fetch_all(MYSQLI_ASSOC);
// добавление ставки

$page_content = include_template('lot_template.php', ['row_lot' => $row_lot, 'list_bets' => $list_bets, 'categories_arr' => $categories_arr, 'min_bet' => $min_bet, 'err' => $err]);

$layout_content = include_template('layout.php', ['page_content' => $page_content, 'page_title' => $row_lot['name_lot'], 'categories_arr' => $categories_arr]);
print $layout_content;