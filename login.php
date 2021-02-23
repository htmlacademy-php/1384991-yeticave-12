<?php
require 'init.php';
if (isset($_SESSION['user'])) {
  	header("Location: /");
  	exit();
}
$err = [];
if (!empty($_POST)) {
	//Валидация формы входа
	$err = [];
	$checkEmail = "SELECT * FROM users WHERE user_email = ?";
	$stmt = getSqlPrepare($db_connect, $checkEmail, [$_POST['email']]);
	$result = $stmt->get_result();
	$checkArr = $result->fetch_assoc();
	if (!isset($checkArr['user_email'])) {
		$err['email'] = 'Email введен неверно';
	} else {
		if (password_verify($_POST['password'], $checkArr['password'])) {
			$_SESSION['user'] = $checkArr['id'];
			$_SESSION['email'] = $checkArr['user_email'];
			$_SESSION['name'] = $checkArr['user_name'];
			$_SESSION['contacts'] = $checkArr['contacts'];
			header("Location: /");
  			exit;
		} else {
			$err['password'] = 'Пароль введен неверно!';
		}
	}
	//echo $checkArr['user_email'] . ", " . $checkArr['password'];
	foreach ($_POST as $key => $value) {
		if ($key == 'email') {
			if(empty($value)) {
				$err[$key] = 'Введите email';
			} elseif(!filter_var($value, FILTER_VALIDATE_EMAIL)) {
				$err[$key] = 'Введен некорректный email';
			}
		}
		elseif ($key == 'password') {
			if(empty($value)) {
				$err[$key] = 'Укажите пароль';
			}
		}
	}
}

$page_content = include_template('login_template.php', ['err' => $err]);

$layout_content = include_template('layout.php', ['page_content' => $page_content, 'page_title' => 'Вход на сайт', 
	'categories_arr' => $categories_arr, 'user_name' => $user_name, 'is_auth' => $is_auth]);
print $layout_content;