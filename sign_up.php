<?php
require 'init.php';
if (isset($_SESSION['user'])) {
  	getErrorPage(403, $categories_arr);
}
$err = [];
if (!empty($_POST)) {
	if (empty($_POST['email'])) {
		$err['email'] = 'Введите Email';
	} elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
		$err['email'] = 'Введен некорректный email';
	}
	if (empty($_POST['password'])) {
		$err['password'] = 'Укажите пароль';
	}
	if (empty($_POST['name'])) {
		$err['name'] = 'Введите имя';
	}
	if (empty($_POST['message'])) {
		$err['message'] = 'Укажите свои контактные данные';
	}
	if (empty($err)) {
		$checkEmail = "SELECT user_email FROM users WHERE user_email = ?";
		$stmt = getSqlPrepare($db_connect, $checkEmail, [$_POST['email']]);
		$result = $stmt->get_result();
		$email = $result->fetch_assoc();
		if ($email) {
			$err['email'] = 'Пользователь с таким email уже зарегистрирован!';
		} else {
			$sql = "INSERT INTO users SET user_email = ?, user_name = ?, password = ?, contacts = ?";
			getSqlPrepare($db_connect, $sql, [$_POST['email'], $_POST['name'], password_hash($_POST['password'], PASSWORD_DEFAULT), $_POST['message']]);
			header("Location: /login.php");
	  		exit;
		}
	}

}
$page_content = include_template('sign-up_template.php', ['err' => $err]);

$layout_content = include_template('layout.php', ['page_content' => $page_content, 'page_title' => 'Регистрация нового пользователя', 
	'categories_arr' => $categories_arr, 'user_name' => $user_name, 'is_auth' => $is_auth]);
print $layout_content;