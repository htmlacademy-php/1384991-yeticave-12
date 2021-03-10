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
function replace_specialchars ($var) {
	return htmlspecialchars($var, ENT_QUOTES);
}
//Функция подсчета оставшегося времени
/*function get_expiry_time ($date) {
	$cur_date = time();
	$exp_date = strtotime($date);
	$diff = $exp_date - $cur_date;
	$diff_hours = str_pad(floor(($diff / 60) / 60), 2, "0", STR_PAD_LEFT);
	$diff_min = str_pad(($diff / 60) % 60, 2, "0", STR_PAD_LEFT);
	$diff_array = [$diff_hours, $diff_min];
	return $diff_array;
}*/
function get_expiry_time ($date) {
    $cur_date = time();
    $exp_date = strtotime($date);
    $diff = $exp_date - $cur_date;
    $diff_array = [];
    $diff_array[] = str_pad(floor($diff / 3600), 2, "0", STR_PAD_LEFT);
    $diff = $diff % 3600;
    $diff_array[] = str_pad(floor($diff / 60), 2, "0", STR_PAD_LEFT);
    $diff_array[] = str_pad(($diff % 60), 2, "0", STR_PAD_LEFT);
    return $diff_array;
}
//Функция подсчета времени с момента публикации
function get_pub_date ($date) {
    $cur_date = time();
    $pub_date = strtotime($date);
    $diff = $cur_date - $pub_date;
    $pub_date = '';
    if ($diff <= 3600) {
        $diff_date = str_pad(floor(abs($diff / 60)), 1, "0", STR_PAD_LEFT);
        $pub_date = $diff_date . get_noun_plural_form($diff_date, ' минуту', ' минуты', ' минут') . " назад";
    } elseif ($diff > 3600 AND $diff <= 86400) {
        $diff_date = str_pad(floor(abs($diff / 60 / 60)), 1, "0", STR_PAD_LEFT);  
        $pub_date = $diff_date . get_noun_plural_form($diff_date, ' час', ' часа', ' часов') . " назад";
    } elseif ($diff > 86400 AND $diff <= 604800) {
        $diff_date = str_pad(floor(abs($diff / 60 / 60 / 24)), 1, "0", STR_PAD_LEFT); 
        $pub_date = $diff_date . get_noun_plural_form($diff_date, ' день', ' дня', ' дней') . " назад";
    } elseif ($diff > 2678400) {
        $pub_date = "Более месяца назад";
    }
    return $pub_date;
}
//Функция вывода страниц с ошибкой 
function getErrorPage ($err_code, $categories_arr) {
	http_response_code($err_code);
	$err_temp = include_template("$err_code.php");
	$err_titles = [
        '403' => 'Доступ запрещен',
        '404' => 'Страница не существует',
    ];
	$layout_content = include_template('layout.php', ['page_content' => $err_temp, 'page_title' => $err_titles[$err_code], 
		'categories_arr' => $categories_arr]);
	print $layout_content;
	exit;
}
//Автозаполнение поля при ошибке валидации
function getFillVal ($name, $method = '') {
	if (empty($method)) {
		return $_POST[$name] ?? "";
	} elseif ($method == 'get') {
		return $_GET[$name] ?? "";
	}
}

//выполнение подготовленного запроса
function getSqlPrepare ($db, $sql, $param, $types = "") {
	$types = $types ?: str_repeat("s", count($param));
	$stmt = $db->prepare($sql);
	$stmt->bind_param($types, ...$param);
	$stmt->execute();
	return $stmt;
}