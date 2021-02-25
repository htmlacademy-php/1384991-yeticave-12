<?php
require 'init.php';
if (isset($_SESSION['user'])) {
  	get_403($categories_arr);
}
//Валидация формы входа


/*
1. Валидируем входящие данные
1.1 Проверяем на ошибки поля
1.1.1 Если пустой маил - заполните поле
1.1.2 Если не пустой маил - валидируем поле
1.1.3 Если нет @ - некорректный маил
1.1.4 Если пустой пароль - заполните поле

2 Если ошибок нет - выполняем запрос в базу
2.1 Если имэйл не найден - такого пользователя не существует
2.1.1 Если найден - сравниваем пароль с паролем в базе
2.1.2 Если не совпал - неверный пароль
2.1.3 Если совпал - авторизируем
*/
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
	'categories_arr' => $categories_arr, 'user_name' => $user_name, 'is_auth' => $is_auth]);
print $layout_content;