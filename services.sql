/*
 Created by            : DanCruise
 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 50733

 Target Server Type    : MySQL
 Target Server Version : 50733
 File Encoding         : 65001

 Date: 02/11/2022 11:00:55
*/

CREATE DATABASE cosmetologia;
use cosmetologia;

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `services`;
CREATE TABLE `services`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `description` varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `price` decimal(20, 6) NULL DEFAULT NULL,
  `img` varchar(255),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;

INSERT INTO services (name,description,price,img)
VALUES( "depliación", "depliación laser", 400, 'https://harmonycosmetologia.com/wp-content/uploads/2021/05/Depilacion-Laser.jpg')
