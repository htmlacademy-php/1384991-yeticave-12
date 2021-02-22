<?php
require 'init.php';
$err = [];

if (!empty($_POST)) {
	$err = checkFieldReg(['email' => $_POST['email'], 'password' => $_POST['password'], 'name' => $_POST['name'], 
		'message' => $_POST['message']]);

	$checkEmail = "SELECT user_email FROM users WHERE user_email = ?";
	$stmt = getSqlPrepare($db_connect, $checkEmail, [$_POST['email']]);
	$result = $stmt->get_result();
	$email = $result->fetch_assoc();
	if (isset($email)) {
		$err['email'] = 'Пользователь с таким email уже зарегистрирован!';
	}
	if ($err == false) {
		$sql = "INSERT INTO users SET user_email = ?, user_name = ?, password = ?, contacts = ?";
		getSqlPrepare($db_connect, $sql, [$_POST['email'], $_POST['name'], password_hash($_POST['password'], PASSWORD_DEFAULT), $_POST['message']]);
		header("Location: /login.php");
  		exit;
	}
}


$page_content = include_template('sign-up_template.php', ['err' => $err]);

$layout_content = include_template('layout.php', ['page_content' => $page_content, 'page_title' => 'Регистрация нового пользователя', 
	'categories_arr' => $categories_arr, 'user_name' => $user_name, 'is_auth' => $is_auth]);
print $layout_content;