<?php
require 'init.php';
if (isset($_SESSION['user'])) {
  	get_403($categories_arr);
}
//Валидация формы входа
$err = [];
if (!empty($_POST)) {
	if (!empty($_POST['email']) AND !empty($_POST['password'])) {
		if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
			$err['email'] = 'Введен некорректный email';
		} else {
			$checkEmail = "SELECT * FROM users WHERE user_email = ?";
			$stmt = getSqlPrepare($db_connect, $checkEmail, [$_POST['email']]);
			$result = $stmt->get_result();
			$checkArr = $result->fetch_assoc();
			if (!isset($checkArr['user_email'])) {
				$err['email'] = 'Email не найден';
			} else {
				if (!password_verify($_POST['password'], $checkArr['password'])) {
					$err['password'] = 'Пароль введен неверно!';
				} else {
					$_SESSION['user'] = $checkArr;
					header("Location: /");
					exit;
				}
			}
		} 
	} else {
		if (empty($_POST['email'])) {
			$err['email'] = 'Введите Email';
		} 
		if (empty($_POST['password'])) {
			$err['password'] = 'Введите пароль';
		}
	}
}
$page_content = include_template('login_template.php', ['err' => $err]);

$layout_content = include_template('layout.php', ['page_content' => $page_content, 'page_title' => 'Вход на сайт', 
	'categories_arr' => $categories_arr, 'user_name' => $user_name, 'is_auth' => $is_auth]);
print $layout_content;