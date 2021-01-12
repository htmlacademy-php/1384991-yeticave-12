<?php
	function price_format ($price) {
		return number_format(ceil($price), 0, '', ' ');
	}
?>