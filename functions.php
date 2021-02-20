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
//Автозаполнение поля при ошибке валидации
function getPostVal($name) {
    return $_POST[$name] ?? "";
}
//Валидация формы добавления лота
function checkField($param, $fileinfo) {
$err = [];
foreach ($param as $key => $value) {
	if ($key == 'lot-name') {
		if($value == NULL) {
			$err[$key] = 'Введите название лота';
		}
	}

	if ($key == 'category') {
		if($value == NULL) {
			$err[$key] = 'Выберите категорию';
		}
	}

	if ($key == 'message') {
		if($value == NULL) {
			$err[$key] = 'Заполните описание лота';
		}
	}
	if ($key == 'lot-rate') {
		if ($value == NULL) {
			$err[$key] = 'Укажите стоимость лота';
		} elseif (!ctype_digit($value) OR $value <= 0) { 
			$err[$key] = 'Введите целое число больше 0';
		}
	}
	if ($key == 'lot-step') {
		if ($value == NULL) {
			$err[$key] = 'Укажите шаг ставки';
		} elseif (!ctype_digit($value) OR $value <= 0) { 
			$err[$key] = 'Введите целое число больше 0';
		}
	}
	if ($key == 'lot-date') {
		if($value == NULL) {
			$err[$key] = 'Выберите дату завершения';
		} else {
			$dateUnix = strtotime($value);
			$correctDateUnix = time() + 86400;
			if ($dateUnix < $correctDateUnix) {
				$err[$key] = 'Минимальная продолжительность аукциона 24 часа';
			}
		}
	}
}
if ($fileinfo == NULL) {
	$err['image-lot'] = 'Выберите изображение лота';
}
if ($fileinfo['type'] !== 'image/jpeg' and $fileinfo['type'] !== 'image/png') {
	$err['image-lot'] = 'Загрузите картинку в формате jpeg или png';
}
if ($fileinfo['size'] > 200000) {
	$err['image-lot'] = "Максимальный размер файла: 200Кб";
}
$file_name = $fileinfo['name'];
$file_path = __DIR__ . '/uploads/';
$file_url = '/uploads/' . $file_name;
if (!move_uploaded_file($fileinfo['tmp_name'], $file_path . $file_name)) {
	$err['image-lot'] = "Изображение не загружено";
}
if (count($err)) {
	return $err;
} else {
	return false;
}
}