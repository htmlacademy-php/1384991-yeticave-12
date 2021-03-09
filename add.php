<?php 
require 'init.php';
if (!isset($_SESSION['user'])) {
  	getErrorPage(403, $categories_arr);
}
$err = [];
if (!empty($_POST)) {
	//Валидация формы добавления лота
	function checkField($param, $fileinfo) {
		$err = [];
		foreach ($param as $key => $value) {
			if ($key == 'lot-name') {
				if(empty($value)) {
					$err[$key] = 'Введите название лота';
				}
			}
			elseif ($key == 'category') {
				if(empty($value)) {
					$err[$key] = 'Выберите категорию';
				}
			}

			elseif ($key == 'message') {
				if(empty($value)) {
					$err[$key] = 'Заполните описание лота';
				}
			}
			elseif ($key == 'lot-rate') {
				if ($value == NULL) {
					$err[$key] = 'Укажите стоимость лота';
				} elseif (!ctype_digit($value) OR $value <= 0) { 
					$err[$key] = 'Введите целое число больше 0';
				}
			}
			elseif ($key == 'lot-step') {
				if ($value == NULL) {
					$err[$key] = 'Укажите шаг ставки';
				} elseif (!ctype_digit($value) OR $value <= 0) { 
					$err[$key] = 'Введите целое число больше 0';
				}
			}
			elseif ($key == 'lot-date') {
				if(empty($value)) {
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
		if ($fileinfo['name'] == NULL) {
			$err['image-lot'] = 'Выберите изображение лота';
		} else {
			$file_name = $fileinfo['name'];
			$file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
			if (!in_array($file_extension, ['jpeg', 'jpg', 'png'], true) OR !in_array($fileinfo['type'], ['image/jpeg', 'image/png'], true)) {
				$err['image-lot'] = 'Выберите изображение в формате jpeg или png';
			}

			if ($fileinfo['size'] > 200000) {
				$err['image-lot'] = "Максимальный размер файла: 200Кб";
			}
		}
		return $err;
	}
	$err = checkField(['lot-name' => $_POST['lot-name'], 'category' => $_POST['category'], 'message' => $_POST['message'], 
		'lot-rate' => $_POST['lot-rate'], 'lot-step' => $_POST['lot-step'], 'lot-date' => $_POST['lot-date']], 
		$_FILES['image-lot']);
	if ($err == false) {
		$file_name = $_FILES['image-lot']['name'];
		$file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
		$sql = "INSERT INTO lots SET name_lot = ?, description_lot = ?, img_url = ?, start_price = ?, end_date = ?, 
		step_bet = ?, cat_id = ?, user_id = ?";
		getSqlPrepare($db_connect, $sql, [$_POST['lot-name'], $_POST['message'], $file_extension, $_POST['lot-rate'], $_POST['lot-date'], 
			$_POST['lot-step'], $_POST['category'], $_SESSION['user']['id']]);
		$last_id = mysqli_insert_id($db_connect);
		$file_name = $last_id . "." . $file_extension;
		$file_path = __DIR__ . '/uploads/';
		move_uploaded_file($_FILES['image-lot']['tmp_name'], $file_path . $file_name);
		header("Location: /lot.php?id=$last_id");
  		exit;
	}
}
$page_content = include_template('add_template.php', ['categories_arr' => $categories_arr, 'err' => $err]);

$layout_content = include_template('layout.php', ['page_content' => $page_content, 'page_title' => 'Добавление лота', 
	'categories_arr' => $categories_arr]);
print $layout_content;