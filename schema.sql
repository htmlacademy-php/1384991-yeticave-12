CREATE DATABASE yeticave
	DEFAULT CHARACTER SET utf8mb4;

USE yeticave;

CREATE TABLE categories (
	id INT PRIMARY KEY AUTO_INCREMENT,
	cat_name VARCHAR(255) UNIQUE,
	cat_url VARCHAR(255) UNIQUE
);

CREATE TABLE lots (
	id INT PRIMARY KEY AUTO_INCREMENT,
	add_date DATETIME DEFAULT CURRENT_TIMESTAMP,
	name_lot VARCHAR(255),
	description_lot TEXT,
	img_url VARCHAR(255),
	start_price INT,
	end_date TIMESTAMP,
	step_bet INT,
	cat_id INT,
	user_id INT,
	winner_id INT
);

CREATE INDEX s_name ON lots(name_lot);

CREATE TABLE bets (
	id INT PRIMARY KEY AUTO_INCREMENT,
	add_date DATETIME DEFAULT CURRENT_TIMESTAMP,
	bet_price INT,
	user_id INT,
	lot_id INT
);

CREATE TABLE users (
	id INT PRIMARY KEY AUTO_INCREMENT,
	reg_date DATETIME DEFAULT CURRENT_TIMESTAMP,
	user_email VARCHAR(255) UNIQUE,
	user_name VARCHAR(255),
	password VARCHAR(255),
	contacts TEXT
);