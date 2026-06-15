CREATE DATABASE IF NOT EXISTS yallacode
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE yallacode;

CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100) NOT NULL,
    email VARCHAR(190) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    photo VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Register a user through the application, then promote that account:
-- UPDATE users SET role = 'admin' WHERE email = 'admin@example.com';
