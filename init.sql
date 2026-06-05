CREATE DATABASE IF NOT EXISTS taskhub_db;
USE taskhub_db;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    status ENUM('todo', 'doing', 'done') DEFAULT 'todo',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- INSERT INTO tasks (title, status) VALUES 
-- ('Проектиране на Docker архитектура', 'done'),
-- ('Създаване на PHP REST API', 'doing'),
-- ('Стилизиране на Канбан таблото', 'todo');

SELECT * FROM users;