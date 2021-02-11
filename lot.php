<?php
require 'connections.php';

// запрос на получение списка категорий
$get_categories_sql = "SELECT * FROM categories";
$result_categories = $db_connect->query($get_categories_sql);
$categories_from_db = $result_categories->fetch_all(MYSQLI_ASSOC);
$categories_arr = [];
foreach ($categories_from_db as $item) {
	$categories_arr[$item['id']] = $item;
}

if (isset($_GET['id'])):
// запрос на получение информации о лоте
	$get_lot_sql = "SELECT lots.*, COALESCE (max(bets.bet_price), lots.start_price) AS current_price, categories.cat_name FROM lots 
	LEFT JOIN bets ON lots.id = bets.lot_id 
	JOIN categories ON lots.cat_id = categories.id
	WHERE lots.id=?
	GROUP BY lots.id";
	$stmt_lot = $db_connect->prepare($get_lot_sql);
	$stmt_lot->bind_param("s", $_GET['id']);
	$stmt_lot->execute();
	$result_lot = $stmt_lot->get_result();
	$row_lot = $result_lot->fetch_assoc();
endif;

// выводим 404 если нет параметра запроса или id не существует
if (!isset($_GET['id']) || !$row_lot):
	http_response_code(404);
	$get_404 = include_template('404.php', ['page_title' => 'Страница не существует', 'categories_arr' => $categories_arr]);
	$layout_content = include_template('layout.php', ['page_content' => $get_404, 'page_title' => 'Страница не существует', 'user_name' => $user_name, 'is_auth' => $is_auth, 'categories_arr' => $categories_arr]);
	print $layout_content;
	exit;
endif;

if (isset($_GET['id'])):
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

$page_content = include_template('lot_template.php', ['row_lot' => $row_lot, 'list_bets' => $list_bets, 'categories_arr' => $categories_arr]);

$layout_content = include_template('layout.php', ['page_content' => $page_content, 'page_title' => $row_lot['name_lot'], 'categories_arr' => $categories_arr, 'user_name' => $user_name, 'is_auth' => $is_auth]);
print $layout_content;
endif;