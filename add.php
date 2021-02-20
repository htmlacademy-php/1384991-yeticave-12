<?php 
require 'init.php';
$err = [];
if (!empty($_POST)) {
	$checkValid = checkField(['lot-name' => $_POST['lot-name'], 'category' => $_POST['category'], 'message' => $_POST['message'], 'lot-rate' => $_POST['lot-rate'], 'lot-step' => $_POST['lot-step'], 'lot-date' => $_POST['lot-date']], $_FILES['image-lot']);
	if($checkValid == false) {
		$lot_img = '/uploads/' . $_FILES['image-lot']['name'];
		$sql = "INSERT INTO lots SET name_lot = ?, description_lot = ?, img_url = ?, start_price = ?, end_date = ?, step_bet = ?, cat_id = ?, user_id = ?";
		$stmt = $db_connect->prepare($sql);
		$stmt->bind_param("ssssssss", $_POST['lot-name'], $_POST['message'], $lot_img, $_POST['lot-rate'], $_POST['lot-date'], $_POST['lot-step'], $_POST['category'], $_POST['user_id']);
		$stmt->execute();
		$last_id = mysqli_insert_id($db_connect);
		header("Location: /lot.php?id=$last_id");
  		exit;
	} else {
		$err = $checkValid;
	}
}
$page_content = include_template('add_template.php', ['categories_arr' => $categories_arr, 'err' => $err]);

$layout_content = include_template('layout.php', ['page_content' => $page_content, 'page_title' => 'Добавление лота', 'categories_arr' => $categories_arr, 'user_name' => $user_name, 'is_auth' => $is_auth]);
print $layout_content;