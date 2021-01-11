<?php
	function price_format ($price) {
		$ceil_price = ceil($price);
		if ($ceil_price >= 1000) {
			echo number_format($ceil_price, 0, '', ' ') . " ₽";
		} else {
			echo $ceil_price . " ₽";
		}
	}
?>