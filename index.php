<?php
require 'functions.php';
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
$user_name = 'Oleh'; // укажите здесь ваше имя

// запрос на получение списка категорий
$get_categories_sql = "SELECT * FROM categories";
$result_categories = $db_connect->query($get_categories_sql);
$categories_arr = $result_categories->fetch_all(MYSQLI_ASSOC);

// запрос на получение списка активных лотов
$get_lots_sql = "SELECT name_lot, end_date, start_price, img_url, max(bets.bet_price), categories.cat_name FROM lots 
    LEFT JOIN bets ON lots.id = bets.lot_id 
    JOIN categories ON lots.cat_id = categories.id
    WHERE end_date > CURRENT_TIMESTAMP 
    GROUP BY lots.id 
    ORDER BY lots.add_date DESC";
$result_lots = $db_connect->query($get_lots_sql);
$lots_arr = $result_lots->fetch_all(MYSQLI_ASSOC);

$page_content = include_template('main.php', ['categories_arr' => $categories_arr, 'lots_arr' => $lots_arr]);
$layout_content = include_template('layout.php', ['page_content' => $page_content, 'categories_arr' => $categories_arr, 'user_name' => $user_name, 'page_title' => 'Главная', 'is_auth' => $is_auth]);
print $layout_content;

