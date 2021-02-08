<?php 
	$db_host = '';
	$db_username = '';
	$db_password = '';
	$db_database = '';
	$db_charset = '';
	mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
	$db_connect = new mysqli($db_host, $db_username, $db_password, $db_database);
	$db_connect->set_charset($db_charset);
?>