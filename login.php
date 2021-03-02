<?php
require 'init.php';
if (isset($_SESSION['user'])) {
  	getErrorPage(403, $categories_arr);
}
//Валидация формы входа
$err = [];
if (!empty($_POST)) {
	if (empty($_POST['email'])) {
		$err['email'] = 'Введите Email';
	} elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
		$err['email'] = 'Некорректный Email';
	} 
	if (empty($_POST['password'])) {
		$err['password'] = 'Введите пароль';
	} 
	if (empty($err)) {
		$checkEmail = "SELECT * FROM users WHERE user_email = ?";
		$stmt = getSqlPrepare($db_connect, $checkEmail, [$_POST['email']]);
		$result = $stmt->get_result();
		$checkArr = $result->fetch_assoc();
		if (!$checkArr) {
			$err['email'] = 'Пользователь с таким Email не найден';
		} elseif (!password_verify($_POST['password'], $checkArr['password'])) {
			$err['password'] = 'Неверный пароль';
		} else {
			$_SESSION['user'] = $checkArr;
			header("Location: /");
			exit;
		}
	}
}
$page_content = include_template('login_template.php', ['err' => $err]);

$layout_content = include_template('layout.php', ['page_content' => $page_content, 'page_title' => 'Вход на сайт', 
	'categories_arr' => $categories_arr]);
print $layout_content;