use cosmetologia;

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `users`;

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

CREATE INDEX idx_username ON users(user);

SET FOREIGN_KEY_CHECKS = 1;

INSERT INTO users (user, password, email, is_admin, active) 
VALUES ('admin', 'admin', 'admin@yourdomain.com', TRUE, TRUE);