<?php
/**
 * Проверяет переданную дату на соответствие формату 'ГГГГ-ММ-ДД'
 *
 * Примеры использования:
 * is_date_valid('2019-01-01'); // true
 * is_date_valid('2016-02-29'); // true
 * is_date_valid('2019-04-31'); // false
 * is_date_valid('10.10.2010'); // false
 * is_date_valid('10/10/2010'); // false
 *
 * @param string $date Дата в виде строки
 *
 * @return bool true при совпадении с форматом 'ГГГГ-ММ-ДД', иначе false
 */
function is_date_valid(string $date) : bool {
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);

    return $dateTimeObj !== false && array_sum(date_get_last_errors()) === 0;
}

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function db_get_prepare_stmt($link, $sql, $data = []) {
    $stmt = mysqli_prepare($link, $sql);

    if ($stmt === false) {
        $errorMsg = 'Не удалось инициализировать подготовленное выражение: ' . mysqli_error($link);
        die($errorMsg);
    }

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = 's';

            if (is_int($value)) {
                $type = 'i';
            }
            else if (is_string($value)) {
                $type = 's';
            }
            else if (is_double($value)) {
                $type = 'd';
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);

        if (mysqli_errno($link) > 0) {
            $errorMsg = 'Не удалось связать подготовленное выражение с параметрами: ' . mysqli_error($link);
            die($errorMsg);
        }
    }

    return $stmt;
}

/**
 * Возвращает корректную форму множественного числа
 * Ограничения: только для целых чисел
 *
 * Пример использования:
 * $remaining_minutes = 5;
 * echo "Я поставил таймер на {$remaining_minutes} " .
 *     get_noun_plural_form(
 *         $remaining_minutes,
 *         'минута',
 *         'минуты',
 *         'минут'
 *     );
 * Результат: "Я поставил таймер на 5 минут"
 *
 * @param int $number Число, по которому вычисляем форму множественного числа
 * @param string $one Форма единственного числа: яблоко, час, минута
 * @param string $two Форма множественного числа для 2, 3, 4: яблока, часа, минуты
 * @param string $many Форма множественного числа для остальных чисел
 *
 * @return string Рассчитанная форма множественнго числа
 */
function get_noun_plural_form (int $number, string $one, string $two, string $many): string
{
    $number = (int) $number;
    $mod10 = $number % 10;
    $mod100 = $number % 100;

    switch (true) {
        case ($mod100 >= 11 && $mod100 <= 20):
            return $many;

        case ($mod10 > 5):
            return $many;

        case ($mod10 === 1):
            return $one;

        case ($mod10 >= 2 && $mod10 <= 4):
            return $two;

        default:
            return $many;
    }
}

/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 * @param string $name Путь к файлу шаблона относительно папки templates
 * @param array $data Ассоциативный массив с данными для шаблона
 * @return string Итоговый HTML
 */

function include_template($name, array $data = []) {
    $name = 'templates/' . $name;

    ob_start();
    extract($data);
    require $name;

    return ob_get_clean();
}


// МОИ ФУНКЦИИ //


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
	return $diff_array;
}
//Функция вывода 404
function get_404 ($categories_arr, $user_name, $is_auth) {
	http_response_code(404);
	$get_404 = include_template('404.php', ['page_title' => 'Страница не существует', 'categories_arr' => $categories_arr]);
	$layout_content = include_template('layout.php', ['page_content' => $get_404, 'page_title' => 'Страница не существует', 
		'user_name' => $user_name, 'is_auth' => $is_auth, 'categories_arr' => $categories_arr]);
	print $layout_content;
	exit;
}
//Автозаполнение поля при ошибке валидации
function getPostVal($name) {
    return $_POST[$name] ?? "";
}
//Валидация формы добавления лота
function checkField($param, $fileinfo) {
	$err = [];
	foreach ($param as $key => $value) {
		if ($key == 'lot-name') {
			if(empty($value)) {
				$err[$key] = 'Введите название лота';
			}
		}
		elseif ($key == 'category') {
			if(empty($value)) {
				$err[$key] = 'Выберите категорию';
			}
		}

		elseif ($key == 'message') {
			if(empty($value)) {
				$err[$key] = 'Заполните описание лота';
			}
		}
		elseif ($key == 'lot-rate') {
			if ($value == NULL) {
				$err[$key] = 'Укажите стоимость лота';
			} elseif (!ctype_digit($value) OR $value <= 0) { 
				$err[$key] = 'Введите целое число больше 0';
			}
		}
		elseif ($key == 'lot-step') {
			if ($value == NULL) {
				$err[$key] = 'Укажите шаг ставки';
			} elseif (!ctype_digit($value) OR $value <= 0) { 
				$err[$key] = 'Введите целое число больше 0';
			}
		}
		elseif ($key == 'lot-date') {
			if(empty($value)) {
				$err[$key] = 'Выберите дату завершения';
			} else {
				$dateUnix = strtotime($value);
				$correctDateUnix = time() + 86400;
				if ($dateUnix < $correctDateUnix) {
					$err[$key] = 'Минимальная продолжительность аукциона 24 часа';
				}
			}
		}
	}
	if ($fileinfo['name'] == NULL) {
		$err['image-lot'] = 'Выберите изображение лота';
	} else {
		$file_name = $fileinfo['name'];
		$file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
		if (($file_extension !== 'jpeg' and $file_extension !== 'jpg' and $file_extension !== 'png') OR ($fileinfo['type'] !== 'image/jpeg' 
			and $fileinfo['type'] !== 'image/jpg' and $fileinfo['type'] !== 'image/png')) {
			$err['image-lot'] = 'Выберите изображение в формате jpeg или png';
		}

		if ($fileinfo['size'] > 200000) {
			$err['image-lot'] = "Максимальный размер файла: 200Кб";
		}
	}
	return $err;
}