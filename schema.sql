CREATE DATABASE yeticave
	DEFAULT CHARACTER SET utf8
	DEFAULT COLLATE utf8_general_ci;

USE yeticave;

CREATE TABLE categories (
	id INT PRIMARY KEY AUTO_INCREMENT,
	cat_name VARCHAR(255) UNIQUE,
	cat_url VARCHAR(255) UNIQUE
);

CREATE TABLE lots (
	id INT PRIMARY KEY AUTO_INCREMENT,
	add_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	name_lot VARCHAR(255),
	description_lot TEXT,
	img_url VARCHAR(255),
	start_price FLOAT,
	end_date TIMESTAMP,
	step_bet FLOAT,
	cat_id INT,
	user_id INT,
	winner_id INT
);

CREATE TABLE bets (
	id INT PRIMARY KEY AUTO_INCREMENT,
	add_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	bet_price FLOAT,
	user_id INT,
	lot_id INT
);

CREATE TABLE users (
	id INT PRIMARY KEY AUTO_INCREMENT,
	reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	user_email VARCHAR(255) UNIQUE,
	user_name VARCHAR(255),
	password CHAR(32),
	contacts TEXT
	/*lots_ids TEXT,*/
	/*bets_ids TEXT*/
);