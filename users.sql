-- Ensure proper database and character set
CREATE DATABASE IF NOT EXISTS cosmetologia 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE cosmetologia;

-- Set session variables for configuration
SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- Drop existing table if it exists
DROP TABLE IF EXISTS `users`;

-- Create users table
CREATE TABLE `users` (
   `id` INT(11) NOT NULL AUTO_INCREMENT,
   `user` VARCHAR(50) NOT NULL UNIQUE,
   `password` VARCHAR(255) NOT NULL,
   `email` VARCHAR(100) NULL,
   `is_admin` BOOLEAN DEFAULT FALSE,
   `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   `last_login` TIMESTAMP NULL,
   `active` BOOLEAN DEFAULT TRUE,

   PRIMARY KEY (`id`),
   UNIQUE KEY `unique_username` (`user`)
) ENGINE = InnoDB 
  DEFAULT CHARSET = utf8mb4 
  COLLATE = utf8mb4_unicode_ci;

-- Create index for performance
CREATE INDEX idx_username ON users(user);

-- Set foreign key checks back to normal
SET FOREIGN_KEY_CHECKS = 1;

-- Initial admin user (use a secure password in actual implementation)
INSERT INTO users (user, password, email, is_admin, active) 
VALUES ('admin', 'admin_password_hash', 'admin@yourdomain.com', TRUE, TRUE);