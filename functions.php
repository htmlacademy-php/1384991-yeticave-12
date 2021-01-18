<?php
	//Функция форматирования цены
	function price_format ($price) {
		return number_format(ceil($price), 0, '', ' ') . " ₽";
	}
	//Упрощенная функция очистки html-символов
	function clear_spec ($var) {
		return htmlspecialchars($var, ENT_QUOTES);
	}
?>