<?php
//Функция форматирования цены
function price_format ($price) {
	return number_format(ceil($price), 0, '', ' ') . " ₽";
}
//Упрощенная функция очистки html-символов
function clear_spec ($var) {
	return htmlspecialchars($var, ENT_QUOTES);
}
//Функция подсчета оставшегося времени
function get_expiry_time ($date) {
	$cur_date = time();
	$exp_date = strtotime($date);
	$diff = $exp_date - $cur_date;
	$diff_hours = str_pad(floor(($diff / 60) / 60), 2, "0", STR_PAD_LEFT);
	$diff_min = str_pad(($diff / 60) % 60, 2, "0", STR_PAD_LEFT);
	$diff_array = [$diff_hours, $diff_min];
	return $diff_array;
}
//Функция вывода 404
function get_404 ($categories_arr, $user_name, $is_auth) {
	http_response_code(404);
	$get_404 = include_template('404.php', ['page_title' => 'Страница не существует', 'categories_arr' => $categories_arr]);
	$layout_content = include_template('layout.php', ['page_content' => $get_404, 'page_title' => 'Страница не существует', 'user_name' => $user_name, 'is_auth' => $is_auth, 'categories_arr' => $categories_arr]);
	print $layout_content;
	exit;
}