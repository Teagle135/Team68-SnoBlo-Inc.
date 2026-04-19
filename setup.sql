-- setup.sql
-- Run this once to create the SnoBlo database and tables.
-- If the database already exists, skip the CREATE DATABASE line.

CREATE DATABASE IF NOT EXISTS renz59_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE renz59_db;

CREATE TABLE IF NOT EXISTS Login (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS Testimonials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    rating TINYINT NOT NULL,
    review_text TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
