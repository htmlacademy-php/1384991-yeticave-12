<?php
require 'init.php';

if (!empty($_GET['search'])) {
	$searchSql = "SELECT lots.*, categories.cat_name FROM lots 
	JOIN categories ON lots.cat_id = categories.id
	WHERE MATCH(name_lot, description_lot) AGAINST(?)";
	$stmt = getSqlPrepare($db_connect, $searchSql, [$_GET['search']]);
	$result = $stmt->get_result();
	$searchResult = $result->fetch_all(MYSQLI_ASSOC);
	$find = $_GET['search'];
} else {
	getErrorPage(404, $categories_arr);
}

$page_content = include_template('search_template.php', ['searchResult' => $searchResult]);

$layout_content = include_template('layout.php', ['page_content' => $page_content, 'page_title' => "Результаты поиска по запросу $find", 
	'categories_arr' => $categories_arr]);
print $layout_content;