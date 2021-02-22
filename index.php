<?php
require 'init.php';

// запрос на получение списка активных лотов
$get_lots_sql = "SELECT lots.id, name_lot, end_date, start_price, img_url, max(bets.bet_price), categories.cat_name FROM lots 
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