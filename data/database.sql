--  USE copy path on this file and launch SOURCE path on mysql to load the script and the database will be usable
-- Create Database 
CREATE DATABASE IF NOT EXISTS PHP_LP_NM; 
--  Create User and Grant Permissions 
CREATE USER 'admin'@'localhost' IDENTIFIED BY 'admin'; 
GRANT ALL PRIVILEGES ON PHP_LP_NM.* TO 'admin'@'localhost'; 
FLUSH PRIVILEGES; 
-- Use the created database 
USE PHP_LP_NM; 
-- Create Table Users 
CREATE TABLE users ( 
    id INT AUTO_INCREMENT PRIMARY KEY, 
    username VARCHAR(100) NOT NULL, 
    password VARCHAR(255) NOT NULL
);