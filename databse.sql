DROP DATABASE IF EXISTS restaurant;
CREATE DATABASE restaurant;
USE restaurant;

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    table_number INT NOT NULL DEFAULT 1, 
    order_data JSON NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);