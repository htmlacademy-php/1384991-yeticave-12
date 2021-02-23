<?php
require 'init.php';
if (isset($_SESSION['user'])) {
  	get_403($categories_arr);
}
//Валидация формы входа
$err = [];
if (!empty($_POST)) {
	foreach ($_POST as $key => $value) {
		if ($key == 'email') {
			if(empty($value)) {
				$err[$key] = 'Введите email';
			} elseif(!filter_var($value, FILTER_VALIDATE_EMAIL)) {
				$err[$key] = 'Введен некорректный email';
			}
		} elseif ($key == 'password') {
			if(empty($value)) {
				$err[$key] = 'Укажите пароль';
			}
		}
	}
	$checkEmail = "SELECT * FROM users WHERE user_email = ?";
	$stmt = getSqlPrepare($db_connect, $checkEmail, [$_POST['email']]);
	$result = $stmt->get_result();
	$checkArr = $result->fetch_assoc();
	if (!isset($checkArr['user_email'])) {
		$err['email'] = 'Email введен неверно';
	} elseif (!password_verify($_POST['password'], $checkArr['password'])) {
		$err['password'] = 'Пароль введен неверно!';
	} else {
		$_SESSION['user'] = $checkArr;
		header("Location: /");
	  	exit;
	}
}
$page_content = include_template('login_template.php', ['err' => $err]);

$layout_content = include_template('layout.php', ['page_content' => $page_content, 'page_title' => 'Вход на сайт', 
	'categories_arr' => $categories_arr, 'user_name' => $user_name, 'is_auth' => $is_auth]);
print $layout_content;