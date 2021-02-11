<?php require 'functions.php';
require 'helpers.php';
if (!file_exists('config.php'))
 {
     $msg = 'Создайте файл config.php на основе config.sample.php и внесите туда настройки сервера MySQL';
     trigger_error($msg,E_USER_ERROR);
 }
require 'config.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$db_connect = new mysqli($db_host, $db_username, $db_password, $db_database);
$db_connect->set_charset($db_charset);
$is_auth = rand(0, 1);
$user_name = 'Oleh';