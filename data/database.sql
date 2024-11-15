-- Create Database 
CREATE DATABASE IF NOT EXISTS my_database; 
--  Create User and Grant Permissions 
CREATE USER 'admin'@'localhost' IDENTIFIED BY 'admin'; 
GRANT ALL PRIVILEGES ON my_database.* TO 'admin'@'localhost'; 
FLUSH PRIVILEGES; 
-- Use the created database 
USE PHP_LP_NM; 
-- Create Table Users 
CREATE TABLE users ( 
    id INT AUTO_INCREMENT PRIMARY KEY, 
    username VARCHAR(100) NOT NULL, 
    password VARCHAR(255) NOT NULL
);