<?php
require 'functions.php';
require 'helpers.php';
if (!file_exists('config.php')) {
     $msg = 'Создайте файл config.php на основе config.sample.php и внесите туда настройки сервера MySQL';
     trigger_error($msg,E_USER_ERROR);
}
require 'config.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$db_connect = new mysqli($db_host, $db_username, $db_password, $db_database);
$db_connect->set_charset($db_charset);
$is_auth = rand(0, 1);
$user_name = 'Oleh'; // укажите здесь ваше имя

// запрос на получение списка категорий
$get_categories_sql = "SELECT * FROM categories";
$result_categories = $db_connect->query($get_categories_sql);
$categories_from_db = $result_categories->fetch_all(MYSQLI_ASSOC);
$categories_arr = [];
foreach ($categories_from_db as $item) {
	$categories_arr[$item['id']] = $item;
}

// запрос на получение информации о лоте
$get_lot_sql = "SELECT * FROM lots WHERE id=?";
$stmt_lot = $db_connect->prepare($get_lot_sql);
$stmt_lot->bind_param("s", $_GET['id']);
$stmt_lot->execute();
$result_lot = $stmt_lot->get_result();
$row_lot = $result_lot->fetch_assoc();

// выводим 404 если нет параметра запроса или id не существует
if(!isset($_GET['id']) || !$row_lot) {
	http_response_code(404);
	header("Location: ../pages/404.html");
	exit;
}

// запрос на получение списка ставок
$get_bets_sql = "SELECT bets.*, users.user_name FROM bets
JOIN users ON users.id = bets.user_id
WHERE lot_id=?
ORDER BY add_date DESC";
$stmt_bets = $db_connect->prepare($get_bets_sql);
$stmt_bets->bind_param("s", $_GET['id']);
$stmt_bets->execute();
$result_bets = $stmt_bets->get_result();
$list_bets = $result_bets->fetch_all(MYSQLI_ASSOC);

// ищем максимальную ставку на лот
$max_bet_id = count($list_bets) - 1;

if ($max_bet_id >= 0) {
	$max_bet = $list_bets[$max_bet_id]['bet_price'];
}

// определяем текущую цену лота
if (isset($max_bet)) {
	$bet_price = $max_bet;
} else {
	$bet_price = $row_lot['start_price'];
}

if (isset($_GET['id'])) {
$lot_content = include_template('lot_template.php', ['row_lot' => $row_lot, 'list_bets' => $list_bets, 'categories_arr' => $categories_arr, 'bet_price' => $bet_price , 'user_name' => $user_name, 'page_title' => $row_lot['name_lot'], 'is_auth' => $is_auth]);
}
print $lot_content;