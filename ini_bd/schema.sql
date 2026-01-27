CREATE DATABASE IF NOT EXISTS yeticave
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE yeticave;

CREATE TABLE IF NOT EXISTS categories (
    id SMALLINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    modifier VARCHAR(50) NOT NULL,

    UNIQUE INDEX idx_name (name)
);

CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    contact_info TEXT NOT NULL,

    UNIQUE INDEX idx_email (email)
);

CREATE TABLE IF NOT EXISTS lots (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    description TEXT NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    start_time DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    end_time DATETIME NOT NULL,
    starting_price INT UNSIGNED NOT NULL,
    bid_step INT UNSIGNED NOT NULL,
    category_id SMALLINT UNSIGNED NOT NULL,
    creator_id INT UNSIGNED NOT NULL,
    winner_id INT UNSIGNED NULL,

    FOREIGN KEY (category_id) REFERENCES categories(id),
    FOREIGN KEY (creator_id) REFERENCES users(id),
    FOREIGN KEY (winner_id) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS bids (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    user_id    INT UNSIGNED NOT NULL,
    lot_id     INT UNSIGNED NOT NULL,
    amount     INT UNSIGNED NOT NULL,

    FOREIGN KEY (user_id) REFERENCES users (id),
    FOREIGN KEY (lot_id) REFERENCES lots (id),
    INDEX idx_created_at (created_at)
);

CREATE FULLTEXT INDEX ft_search ON lots (title, description);
