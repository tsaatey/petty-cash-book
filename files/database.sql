CREATE DATABASE IF NOT EXISTS cashbook DEFAULT CHARACTER SET utf8;

USE cashbook;

CREATE TABLE IF NOT EXISTS person(
    id VARCHAR(10) PRIMARY KEY,
    firstname VARCHAR(30) NOT NULL,
    lastname VARCHAR(30) NOT NULL,
    gender VARCHAR(4) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS users(
    id INTEGER AUTO_INCREMENT PRIMARY KEY,
    userId VARCHAR(10) NOT NULL UNIQUE,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(1000) NOT NULL,
    CONSTRAINT uid FOREIGN KEY(userId) REFERENCES person(id) ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS payment_analysis (
    id INTEGER AUTO_INCREMENT PRIMARY KEY,
    analysis VARCHAR(60) NOT NULL
);

CREATE TABLE IF NOT EXISTS payment (
    id INTEGER AUTO_INCREMENT PRIMARY KEY,
    userId VARCHAR(10) NOT NULL,
    amount_received DOUBLE DEFAULT 0,
    recorded_date VARCHAR(30) NOT NULL,
    recorded_datetime VARCHAR(30) NOT NULL,
    recorded_year VARCHAR(10) NOT NULL,
    recorded_month VARCHAR(15) NOT NULL,
    folio VARCHAR(10) NOT NULL,
    item_description VARCHAR(100) NOT NULL,
    voucher_number VARCHAR(10) NOT NULL,
    total_amount DOUBLE NOT NULL,
    payment_analysis_id INTEGER NOT NULL,
    CONSTRAINT us FOREIGN KEY(userId) REFERENCES person(id) ON UPDATE CASCADE,
    CONSTRAINT pay FOREIGN KEY(payment_analysis_id) REFERENCES payment_analysis(id) ON UPDATE CASCADE
);

INSERT INTO payment_analysis (analysis) VALUES ("Stationary");
INSERT INTO payment_analysis (analysis) VALUES ("Fare");
INSERT INTO payment_analysis (analysis) VALUES ("Postage");
INSERT INTO payment_analysis (analysis) VALUES ("Miscellaneous");