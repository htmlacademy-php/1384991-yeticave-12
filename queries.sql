/* Пишем запросы для добавления информации в БД */

/* Добавляем категории и их символьные коды в таблицу categories */

INSERT INTO categories(cat_name, cat_url) VALUES 
('Доски и лыжи', 'boards'),
('Крепления', 'attachment'),
('Ботинки', 'boots'),
('Одежда', 'clothing'),
('Инструменты', 'tools'),
('Разное', 'other');

/* Добавляем пользователей в таблицу users */

INSERT INTO users(user_email, user_name, password, contacts) VALUES 
('vasya@mail.ru', 'Василий', '12345', 'Телефон: +79161234567'),
('igor@mail.ru', 'Игорь', '1111111', 'Телефон: +79260001234'),
('andrey@mail.ru', 'Андрей', '030303', 'Телефон: +79902254545'),
('gosha@m.com', 'Георгий', '321', 'Телефон: +79291887744'),
('viktor@mqs.com', 'Виктор', '321123', 'Телефон: +79190109089'),
('zhenya@mg.com', 'Евгений', '777333', 'Телефон: +79900181714');

/* Добавляем таблицу с лотами */

INSERT INTO lots(name_lot, description_lot, img_url, start_price, end_date, step_bet, cat_id, user_id) VALUES 
('2014 Rossignol District Snowboard', 'Отличное состояние', 'img/lot-1.jpg', 10999, '2021-02-10', 1, 1, 2),
('DC Ply Mens 2016/2017 Snowboard', 'Хорошее состояние', 'img/lot-2.jpg', 159999, '2021-02-07', 1, 1, 1),
('Крепления Union Contact Pro 2015 года размер L/XL', 'Супер состояние', 'img/lot-3.jpg', 8000, '2021-02-06', 100, 2, 3),
('Ботинки для сноуборда DC Mutiny Charocal', 'Хорошие ботинки', 'img/lot-4.jpg', 10999, '2021-02-09', 1, 3, 1),
('Куртка для сноуборда DC Mutiny Charocal', 'Отличное состояние', 'img/lot-5.jpg', 7500, '2021-02-10', 500, 4, 3),
('Маска Oakley Canopy', 'Среднее состояние', 'img/lot-6.jpg', 5400, '2021-02-05', 50, 6, 1);

/* Добавляем несколько ставок для теста */

INSERT INTO bets(bet_price, user_id, lot_id) VALUES 
(5450, 3, 6),
(5500, 2, 6),
(11100, 4, 4),
(11300, 6, 4),
(12500, 5, 4),
(15000, 4, 4),
(8000, 6, 5),
(9000, 4, 5),
(10000, 6, 5),
(161000, 4, 2);

/* Пишем запросы для действий в БД */

/* Получаем все категории */

SELECT * FROM categories;

/* Получаем самые новые, открытые лоты. Каждый лот должен включать название, стартовую цену, ссылку на изображение, текущую цену, название категории */

SELECT name_lot, start_price, img_url, max(bets.bet_price), categories.cat_name FROM lots 
LEFT JOIN bets ON lots.id = bets.lot_id 
JOIN categories ON lots.cat_id = categories.id
WHERE end_date > CURRENT_TIMESTAMP 
GROUP BY lots.id 
ORDER BY lots.add_date DESC;

/* Показываем лот по его id, также выводим название категории, к которой относится лот */

SELECT lots.*, categories.cat_name FROM lots 
JOIN categories ON lots.cat_id = categories.id
WHERE lots.id = 3;

/* Обновляем название лота по его идентификатору */

UPDATE lots SET name_lot = 'Union Contact Pro 2019 года размер XL' WHERE id = 3;

/* Получаем список ставок для лота по его идентификатору с сортировкой по дате */

SELECT bet_price FROM bets WHERE lot_id = 6 ORDER BY add_date DESC;