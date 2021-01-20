<?php
	//Функция форматирования цены
	function price_format ($price) {
		return number_format(ceil($price), 0, '', ' ') . " ₽";
	}
	//Упрощенная функция очистки html-символов
	function clear_spec ($var) {
		return htmlspecialchars($var, ENT_QUOTES);
	}
	//Функция подсчета оставшегося времени
	function get_expiry_time ($date) {
		$cur_date = time();
		$exp_date = strtotime($date);
		$diff = $exp_date - $cur_date;
		$diff_hours = str_pad(floor(($diff / 60) / 60), 2, "0", STR_PAD_LEFT);
		$diff_min = str_pad(($diff / 60) % 60, 2, "0", STR_PAD_LEFT);
		$diff_array = [$diff_hours, $diff_min];
		if ($diff_array['0'] <= 0 && $diff_array['1'] <= 0) {
			unset($diff_array);
			$diff_array = ['лот окончен'];
		}
		return $diff_array;
	}
	function add_finishing_class ($date) {
		$diff_array = get_expiry_time($date);
		if ($diff_array['0'] < 1) {
			return 'timer--finishing';
		}
	}
?>