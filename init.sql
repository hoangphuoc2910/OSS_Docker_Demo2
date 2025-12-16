-- File: init.sql
CREATE DATABASE IF NOT EXISTS demosomee_db;
USE demosomee_db;

-- 1. Tạo bảng
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


INSERT INTO products (name, price, description) VALUES 
('iPhone 15 Pro Max', 34990000, 'Titan Black'),
('Google Pixel 8 Pro', 24990000, 'Obsidian Black'),
('Samsung S24 Ultra', 29990000, 'Titan Black');