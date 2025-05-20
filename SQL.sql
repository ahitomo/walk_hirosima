CREATE DATABASE IF NOT EXISTS walk_hiroshima;
USE walk_hiroshima;

CREATE TABLE user_management (
    user_id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    user_name varchar(20) NOT NULL UNIQUE,
    user_password varchar(200) NOT NULL,
    registration_date datetime DEFAULT NULL,
    update_date datetime DEFAULT NULL,
    deletion_date datetime DEFAULT NULL,
    deletion_flag boolean DEFAULT 0
);

CREATE TABLE article_management (
    article_id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    post_date datetime NOT NULL,
    user_id int(11) NOT NULL,
    category_id tinyint(1) NOT NULL,
    season_id tinyint(1) NOT NULL,
    title varchar(100) NOT NULL,
    text text NOT NULL,
    point_x varchar(20),
    point_y varchar(20),
    registration_date datetime NOT NULL,
    update_date datetime,
    deletion_date datetime,
    deletion_flag boolean DEFAULT 0
);

CREATE TABLE selected_season_management (
    selected_season_id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    article_id int(11) NOT NULL,
    season_id tinyint(1) NOT NULL,
    selected_date datetime,
    registration_date datetime,
    update_date datetime,
    deletion_date datetime,
    deletion_flag boolean DEFAULT 0
);

CREATE TABLE read_status_management (
    read_id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    article_id int(11) NOT NULL,
    user_id int(11) NOT NULL,
    registration_date datetime,
    update_date datetime,
    deletion_date datetime,
    deletion_flag boolean DEFAULT 0
);

CREATE TABLE image_management (
    image_id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    article_id int(11) NOT NULL,
    display_order tinyint(1) NOT NULL,
    image_url text NOT NULL,
    registration_date datetime,
    update_date datetime,
    deletion_date datetime,
    deletion_flag boolean DEFAULT 0
);

CREATE TABLE category_management (
    category_id  tinyint(1) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    category_name varchar(12) NOT NULL UNIQUE,
    registration_date datetime,
    update_date datetime,
    deletion_date datetime,
    deletion_flag boolean DEFAULT 0
);

CREATE TABLE season_management (
    season_id tinyint(1) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    season_name char(2) NOT NULL UNIQUE,
    registration_date datetime,
    update_date datetime,
    deletion_date datetime,
    deletion_flag boolean DEFAULT 0
);

INSERT INTO category_management (category_id, category_name)
VALUES (1, 'イベント'), (2, '情報'), (3, '発見'), (4, '連絡');

INSERT INTO season_management (season_id, season_name)
VALUES (1, '春'), (2,'夏'), (3, '秋'), (4, '冬');