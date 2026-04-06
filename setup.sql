-- setup.sql
-- Run this once to create the SnoBlo database and Login table.
-- If the database already exists, skip the CREATE DATABASE line.

USE xue43_db;

CREATE TABLE IF NOT EXISTS Login (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);