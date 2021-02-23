<?php 
require 'functions.php';
session_start();
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

// запрос на получение списка категорий
$get_categories_sql = "SELECT * FROM categories";
$result_cat = $db_connect->query($get_categories_sql);
$categories_arr = [];
while ($row = $result_cat->fetch_assoc()) {
    $categories_arr[$row['id']] = $row;
}
/*$result_categories = $db_connect->query($get_categories_sql);
$categories_arr = $result_categories->fetch_all(MYSQLI_ASSOC);*/




