/*
SQLyog Community v13.3.1 (64 bit)
MySQL - 8.0.30 : Database - db_ticketing
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`db_ticketing` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `db_ticketing`;

/*Table structure for table `order_items` */

DROP TABLE IF EXISTS `order_items`;

CREATE TABLE `order_items` (
  `order_item_id` INT NOT NULL AUTO_INCREMENT,
  `order_id` INT DEFAULT NULL,
  `product_id` INT DEFAULT NULL,
  `schedule_id` INT DEFAULT NULL,
  `seat_id` INT DEFAULT NULL,
  `price` DECIMAL(10,2) DEFAULT NULL,
  PRIMARY KEY (`order_item_id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`),
  KEY `schedule_id` (`schedule_id`),
  KEY `seat_id` (`seat_id`),
  CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `ticket_products` (`product_id`),
  CONSTRAINT `order_items_ibfk_3` FOREIGN KEY (`schedule_id`) REFERENCES `schedules` (`schedule_id`),
  CONSTRAINT `order_items_ibfk_4` FOREIGN KEY (`seat_id`) REFERENCES `seats` (`seat_id`)
) ENGINE=INNODB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `order_items` */

INSERT  INTO `order_items`(`order_item_id`,`order_id`,`product_id`,`schedule_id`,`seat_id`,`price`) VALUES 
(1,1,1,1,1,45000.00),
(2,1,1,1,2,45000.00),
(3,2,6,4,4,750000.00),
(4,3,17,11,3,50000.00);

/*Table structure for table `orders` */

DROP TABLE IF EXISTS `orders`;

CREATE TABLE `orders` (
  `order_id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT DEFAULT NULL,
  `order_date` DATETIME DEFAULT NULL,
  `total_price` DECIMAL(10,2) DEFAULT NULL,
  PRIMARY KEY (`order_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
) ENGINE=INNODB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `orders` */

INSERT  INTO `orders`(`order_id`,`user_id`,`order_date`,`total_price`) VALUES 
(1,1,'2025-11-19 16:30:00',90000.00),
(2,2,'2025-11-19 17:00:00',750000.00),
(3,2,'2025-10-01 17:17:45',50000.00);

/*Table structure for table `product_media` */

DROP TABLE IF EXISTS `product_media`;

CREATE TABLE `product_media` (
  `media_id` INT NOT NULL AUTO_INCREMENT,
  `product_id` INT DEFAULT NULL,
  `media_type` ENUM('poster','trailer','image','video') DEFAULT NULL,
  `media_url` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`media_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `product_media_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `ticket_products` (`product_id`)
) ENGINE=INNODB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `product_media` */

INSERT  INTO `product_media`(`media_id`,`product_id`,`media_type`,`media_url`) VALUES 
(1,1,'poster','posters/inside-out-2.jpg'),
(2,1,'trailer','https://www.youtube.com/embed/LEjhY15eCx0'),
(3,7,'poster','posters/minecraft-movie.jpg'),
(4,8,'poster','posters/zootopia-movie.jpg'),
(5,9,'poster','posters/fnf.jpg'),
(6,10,'poster','posters/avatar.jpg'),
(7,11,'poster','posters/agak-lain.jpg'),
(8,16,'poster','posters/kkn-desa-penari.jpg'),
(10,17,'poster','posters/nemo-movie.jpg'),
(11,12,'poster','posters/now-you-see-me.jpg'),
(12,13,'poster','posters/alice-in-borderland.jpg'),
(13,14,'poster','posters/arcane.jpg'),
(14,15,'poster','posters/spongebob.jpg'),
(15,18,'poster','posters/moana.jpg'),
(16,19,'poster','posters/final-destination.jpg'),
(17,16,'trailer','https://youtu.be/PAMx9m4Z2V4?si=0MoXUDuOt8NJEQak'),
(18,17,'trailer','https://youtu.be/SPHfeNgogVs?si=qH-hZ6IBmFKYNi9A'),
(19,18,'trailer','https://youtu.be/hDZ7y8RP5HE?si=XRfbVmOPIvWzkV_t'),
(20,19,'trailer','https://youtu.be/UWMzKXsY9A4?si=pfPCWSIYVt3m0zDp'),
(21,20,'poster','posters/popcorn.jpg'),
(22,21,'poster','posters/burger.jpg'),
(23,22,'poster','posters/hotdog.jpg'),
(24,23,'poster','posters/coca-cola.jpg');

/*Table structure for table `schedules` */

DROP TABLE IF EXISTS `schedules`;

CREATE TABLE `schedules` (
  `schedule_id` INT NOT NULL AUTO_INCREMENT,
  `product_id` INT DEFAULT NULL,
  `studio_id` INT DEFAULT NULL,
  `start_datetime` DATETIME DEFAULT NULL,
  `end_datetime` DATETIME DEFAULT NULL,
  PRIMARY KEY (`schedule_id`),
  KEY `product_id` (`product_id`),
  KEY `studio_id` (`studio_id`),
  CONSTRAINT `schedules_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `ticket_products` (`product_id`),
  CONSTRAINT `schedules_ibfk_3` FOREIGN KEY (`studio_id`) REFERENCES `studios` (`studio_id`)
) ENGINE=INNODB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `schedules` */

INSERT  INTO `schedules`(`schedule_id`,`product_id`,`studio_id`,`start_datetime`,`end_datetime`) VALUES 
(1,1,1,'2025-11-20 18:00:00','2026-01-10 20:00:00'),
(2,4,NULL,'2025-11-21 14:00:00','2025-11-21 16:00:00'),
(3,5,NULL,'2025-11-22 10:00:00','2025-11-22 11:00:00'),
(4,6,8,'2025-12-01 19:00:00','2025-12-01 22:00:00'),
(5,7,3,'2026-01-15 12:00:00','2026-02-15 20:00:00'),
(6,12,5,'2025-12-31 18:00:00','2026-01-31 20:00:00'),
(7,13,4,'2026-01-09 20:00:00','2026-02-04 21:30:00'),
(8,14,1,'2026-01-02 17:00:00','2026-02-14 19:00:00'),
(9,15,2,'2026-02-11 21:00:00','2026-03-18 23:00:00'),
(10,16,1,'2025-11-01 10:30:00','2025-12-31 12:15:00'),
(11,17,4,'2025-09-01 09:30:00','2025-11-30 11:30:00'),
(12,18,5,'2025-10-01 11:00:00','2025-11-30 13:15:00'),
(13,19,4,'2025-05-01 15:00:00','2025-06-30 17:30:00'),

(14,1,1,'2025-12-05 18:30:00','2025-12-05 20:15:00'),
(15,1,9,'2025-12-06 20:00:00','2025-12-06 21:45:00'),

(16,2,NULL,'2025-12-01 08:00:00','2025-12-01 17:00:00'),
(17,3,NULL,'2025-12-02 09:00:00','2025-12-02 16:00:00'),

(18,4,32,'2025-12-03 14:00:00','2025-12-03 16:00:00'),
(19,4,33,'2025-12-04 16:30:00','2025-12-04 18:30:00'),

(20,5,65,'2025-12-05 10:00:00','2025-12-05 11:00:00'),
(21,5,66,'2025-12-06 13:00:00','2025-12-06 14:00:00'),

(22,6,70,'2025-12-07 19:00:00','2025-12-07 22:00:00'),
(23,6,70,'2025-12-08 19:00:00','2025-12-08 22:00:00'),

(24,7,3,'2025-12-09 12:00:00','2025-12-09 14:00:00'),
(25,7,13,'2025-12-10 18:00:00','2025-12-10 20:00:00'),

(26,8,4,'2025-12-11 15:00:00','2025-12-11 17:00:00'),
(27,8,15,'2025-12-12 19:00:00','2025-12-12 21:00:00'),

(28,9,5,'2025-12-13 21:00:00','2025-12-13 23:00:00'),
(29,9,18,'2025-12-14 22:00:00','2025-12-15 00:00:00'),

(30,10,1,'2025-12-15 16:00:00','2025-12-15 18:30:00'),
(31,10,10,'2025-12-16 20:00:00','2025-12-16 22:30:00'),

(32,11,11,'2025-12-17 14:00:00','2025-12-17 16:00:00'),
(33,11,12,'2025-12-18 18:00:00','2025-12-18 20:00:00'),

(34,12,5,'2025-12-19 17:00:00','2025-12-19 19:00:00'),
(35,12,30,'2025-12-20 20:00:00','2025-12-20 22:00:00'),

(36,13,4,'2025-12-21 15:00:00','2025-12-21 17:30:00'),
(37,13,31,'2025-12-22 19:00:00','2025-12-22 21:30:00'),

(38,14,27,'2025-12-23 16:00:00','2025-12-23 18:00:00'),
(39,14,28,'2025-12-24 18:30:00','2025-12-24 20:30:00'),

(40,15,2,'2025-12-25 10:00:00','2025-12-25 12:00:00'),
(41,15,17,'2025-12-26 13:00:00','2025-12-26 15:00:00'),

(42,16,1,'2025-12-27 21:00:00','2025-12-27 23:00:00'),
(43,16,9,'2025-12-28 22:00:00','2025-12-29 00:00:00'),

(44,17,4,'2025-12-29 14:00:00','2025-12-29 16:00:00'),
(45,17,15,'2025-12-30 18:00:00','2025-12-30 20:00:00'),

(46,18,5,'2025-12-31 10:00:00','2025-12-31 12:00:00'),
(47,18,30,'2026-01-01 14:00:00','2026-01-01 16:00:00'),

(48,19,4,'2026-01-02 20:00:00','2026-01-02 22:00:00'),
(49,19,31,'2026-01-03 21:00:00','2026-01-03 23:00:00'),

(50,20,NULL,'2025-12-05 10:00:00','2025-12-05 22:00:00'),
(51,21,NULL,'2025-12-06 10:00:00','2025-12-06 22:00:00'),
(52,22,NULL,'2025-12-07 10:00:00','2025-12-07 22:00:00'),
(53,23,NULL,'2025-12-08 10:00:00','2025-12-08 22:00:00'),

-- filler but realistic
(54,2,NULL,'2026-01-04 08:00:00','2026-01-04 17:00:00'),
(55,3,NULL,'2026-01-05 09:00:00','2026-01-05 16:00:00'),
(56,4,32,'2026-01-06 14:00:00','2026-01-06 16:00:00'),
(57,5,65,'2026-01-07 10:00:00','2026-01-07 11:00:00'),
(58,6,70,'2026-01-08 19:00:00','2026-01-08 22:00:00'),
(59,7,3,'2026-01-09 12:00:00','2026-01-09 14:00:00'),
(60,8,4,'2026-01-10 15:00:00','2026-01-10 17:00:00'),
(61,9,5,'2026-01-11 21:00:00','2026-01-11 23:00:00'),
(62,10,1,'2026-01-12 16:00:00','2026-01-12 18:30:00'),
(63,11,11,'2026-01-13 14:00:00','2026-01-13 16:00:00'),
(64,12,5,'2026-01-14 17:00:00','2026-01-14 19:00:00'),
(65,13,4,'2026-01-15 15:00:00','2026-01-15 17:30:00'),
(66,14,27,'2026-01-16 16:00:00','2026-01-16 18:00:00'),
(67,15,2,'2026-01-17 10:00:00','2026-01-17 12:00:00'),
(68,16,1,'2026-01-18 21:00:00','2026-01-18 23:00:00'),
(69,17,4,'2026-01-19 14:00:00','2026-01-19 16:00:00'),
(70,18,5,'2026-01-20 10:00:00','2026-01-20 12:00:00'),

(71,1,10,'2026-01-21 18:30:00','2026-01-21 20:15:00'),

(72,20,NULL,'2026-01-22 10:00:00','2026-01-22 22:00:00'),
(73,21,NULL,'2026-01-23 10:00:00','2026-01-23 22:00:00'),
(74,22,NULL,'2026-01-24 10:00:00','2026-01-24 22:00:00'),
(75,23,NULL,'2026-01-25 10:00:00','2026-01-25 22:00:00'),

(76,6,70,'2026-01-26 19:00:00','2026-01-26 22:00:00'),
(77,7,13,'2026-01-27 18:00:00','2026-01-27 20:00:00'),
(78,8,15,'2026-01-28 19:00:00','2026-01-28 21:00:00'),
(79,9,18,'2026-01-29 22:00:00','2026-01-30 00:00:00'),
(80,10,10,'2026-01-30 20:00:00','2026-01-30 22:30:00'),

(81,11,12,'2026-01-31 18:00:00','2026-01-31 20:00:00'),
(82,12,30,'2026-02-01 20:00:00','2026-02-01 22:00:00'),
(83,13,31,'2026-02-02 19:00:00','2026-02-02 21:30:00'),
(84,14,28,'2026-02-03 18:30:00','2026-02-03 20:30:00'),
(85,15,17,'2026-02-04 13:00:00','2026-02-04 15:00:00'),

(86,16,9,'2026-02-05 22:00:00','2026-02-06 00:00:00'),
(87,17,15,'2026-02-06 18:00:00','2026-02-06 20:00:00'),
(88,18,30,'2026-02-07 14:00:00','2026-02-07 16:00:00'),
(89,19,31,'2026-02-08 21:00:00','2026-02-08 23:00:00'),

(90,1,1,'2026-02-09 19:00:00','2026-02-09 20:45:00'),

(91,2,NULL,'2026-02-10 08:00:00','2026-02-10 17:00:00'),
(92,3,NULL,'2026-02-11 09:00:00','2026-02-11 16:00:00'),
(93,4,32,'2026-02-12 14:00:00','2026-02-12 16:00:00'),
(94,5,65,'2026-02-13 10:00:00','2026-02-13 11:00:00'),
(95,6,70,'2026-02-14 19:00:00','2026-02-14 22:00:00'),
(96,7,3,'2026-02-15 12:00:00','2026-02-15 14:00:00'),
(97,8,4,'2026-02-16 15:00:00','2026-02-16 17:00:00'),
(98,9,5,'2026-02-17 21:00:00','2026-02-17 23:00:00'),
(99,10,1,'2026-02-18 16:00:00','2026-02-18 18:30:00'),
(100,11,11,'2026-02-19 14:00:00','2026-02-19 16:00:00'),
(101,1,9,'2025-11-20 21:00:00','2025-11-20 22:45:00');

/*Table structure for table `seats` */

DROP TABLE IF EXISTS `seats`;

CREATE TABLE `seats` (
  `seat_id` INT NOT NULL AUTO_INCREMENT,
  `studio_id` INT NOT NULL,
  `seat_code` VARCHAR(10) DEFAULT NULL,
  PRIMARY KEY (`seat_id`),
  UNIQUE KEY `uniq_studio_seat` (`studio_id`,`seat_code`),
  KEY `studio_id` (`studio_id`),
  CONSTRAINT `seats_ibfk_studio` FOREIGN KEY (`studio_id`) REFERENCES `studios` (`studio_id`) ON DELETE CASCADE
) ENGINE=INNODB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `seats` */

INSERT  INTO `seats`(`seat_id`,`studio_id`,`seat_code`) VALUES 
(1,1,'A1'),
(2,1,'A2'),
(3,1,'A3'),
(4,8,'VIP1'),
(5,8,'VIP2'),
(6,8,'VIP3');

/*Table structure for table `studios` */

DROP TABLE IF EXISTS `studios`;

CREATE TABLE `studios` (
  `studio_id` INT NOT NULL AUTO_INCREMENT,
  `venue_id` INT NOT NULL,
  `studio_name` VARCHAR(50) NOT NULL,
  `studio_type` VARCHAR(50) DEFAULT 'Regular',
  PRIMARY KEY (`studio_id`),
  KEY `venue_id` (`venue_id`),
  CONSTRAINT `studios_ibfk_1` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`venue_id`) ON DELETE CASCADE
) ENGINE=INNODB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `studios` */

INSERT  INTO `studios`(`studio_id`,`venue_id`,`studio_name`,`studio_type`) VALUES 
(1,1,'Studio 1','Regular'),
(2,6,'Studio 1','Regular'),
(3,7,'Studio 1','Regular'),
(4,8,'Studio 1','Regular'),
(5,9,'Studio 1','Regular'),
(8,5,'Main Studio','General'),
(9,1,'Studio 2','Regular'),
(10,1,'Studio 3','Regular'),
(11,6,'Studio 2','Regular'),
(12,6,'Studio 3','Regular'),
(13,7,'Studio 2','Regular'),
(14,7,'Studio 3','Regular'),
(15,8,'Studio 2','Regular'),
(16,8,'Studio 3','Regular'),
(17,9,'Studio 2','Regular'),
(18,9,'Studio 3','Regular'),
(19,10,'Studio A','Regular'),
(20,10,'Studio B','Regular'),
(21,11,'Studio A','Regular'),
(22,11,'Studio B','Regular'),
(23,12,'Studio A','Regular'),
(24,12,'Studio B','Regular'),
(25,13,'Studio A','Regular'),
(26,13,'Studio B','Regular'),
(27,14,'Studio A','Regular'),
(28,14,'Studio B','Regular'),
(29,8,'IMAX Hall','Regular'),
(30,12,'Premier Hall','Regular'),
(31,13,'Dolby Cinema','Regular'),
(32,20,'Arcade Hall 1','Regular'),
(33,20,'Arcade Hall 2','Regular'),
(34,21,'Arcade Hall 1','Regular'),
(35,21,'Arcade Hall 2','Regular'),
(36,22,'Arcade Hall 1','Regular'),
(37,23,'Arcade Hall 1','Regular'),
(38,24,'Play Zone 1','Regular'),
(39,24,'Play Zone 2','Regular'),
(40,25,'Kids Zone','Regular'),
(41,26,'Adventure Zone','Regular'),
(42,27,'Court A','Regular'),
(43,27,'Court B','Regular'),
(44,28,'Main Court','Regular'),
(45,29,'Arena 1','Regular'),
(46,30,'Hall A','Regular'),
(47,30,'Hall B','Regular'),
(48,31,'Convention Room 1','Regular'),
(49,31,'Convention Room 2','Regular'),
(50,32,'Expo Hall','Regular'),
(51,33,'Food Court A','Regular'),
(52,33,'Food Court B','Regular'),
(53,34,'Dining Hall','Regular'),
(54,35,'Festival Area','Regular'),
(55,36,'Street Food Zone','Regular'),
(56,2,'Zoo Area 1','Regular'),
(57,2,'Zoo Area 2','Regular'),
(58,15,'Safari Zone','Regular'),
(59,3,'Exhibition Room 1','Regular'),
(60,3,'Exhibition Room 2','Regular'),
(61,16,'History Hall','Regular'),
(62,17,'Gallery Room','Regular'),
(63,18,'Education Hall','Regular'),
(64,19,'Memorial Room','Regular'),
(65,4,'Training Hall','Regular'),
(66,4,'Fitness Room','Regular'),
(67,5,'North Stand','Regular'),
(68,5,'South Stand','Regular'),
(69,5,'VIP Stand','Regular'),
(70,32,'Main Event Hall','Regular');

/*Table structure for table `ticket_categories` */

DROP TABLE IF EXISTS `ticket_categories`;

CREATE TABLE `ticket_categories` (
  `category_id` INT NOT NULL AUTO_INCREMENT,
  `category_name` VARCHAR(50) DEFAULT NULL,
  `icons` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=INNODB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `ticket_categories` */

INSERT  INTO `ticket_categories`(`category_id`,`category_name`,`icons`) VALUES 
(1,'Movie','icons/watching-a-movie.png'),
(2,'Zoo','icons/zoo.png'),
(3,'Museum','icons/art-museum.png'),
(4,'Arcade','icons/arcade-machine.png'),
(5,'Amusement Park','icons/theme-park.png'),
(6,'Sport','icons/basketball.png'),
(7,'Event','icons/event-list.png'),
(8,'Food & Beverage','icons/fast-food.png');

/*Table structure for table `ticket_instances` */

DROP TABLE IF EXISTS `ticket_instances`;

CREATE TABLE `ticket_instances` (
  `ticket_instance_id` INT NOT NULL AUTO_INCREMENT,
  `order_item_id` INT DEFAULT NULL,
  `user_id` INT DEFAULT NULL,
  `qr_code` VARCHAR(255) DEFAULT NULL,
  `is_used` TINYINT(1) DEFAULT '0',
  `status` ENUM('active','used','cancelled','expired') DEFAULT 'active',
  `valid_until` DATETIME DEFAULT NULL,
  `used_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`ticket_instance_id`),
  KEY `order_item_id` (`order_item_id`),
  KEY `fk_ticket_instances_user` (`user_id`),
  CONSTRAINT `fk_ticket_instances_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  CONSTRAINT `ticket_instances_ibfk_1` FOREIGN KEY (`order_item_id`) REFERENCES `order_items` (`order_item_id`)
) ENGINE=INNODB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `ticket_instances` */

INSERT  INTO `ticket_instances`(`ticket_instance_id`,`order_item_id`,`user_id`,`qr_code`,`is_used`,`status`,`valid_until`,`used_at`) VALUES 
(1,1,1,'QR-MOVIE-A1',0,'active',NULL,NULL),
(2,2,1,'QR-MOVIE-A2',0,'active',NULL,NULL),
(3,3,2,'QR-CONCERT-VIP1',0,'active',NULL,NULL),
(4,4,2,'QR-MOVIE-FN',1,'used',NULL,'2025-11-01 17:20:01');

/*Table structure for table `ticket_products` */

DROP TABLE IF EXISTS `ticket_products`;

CREATE TABLE `ticket_products` (
  `product_id` INT NOT NULL AUTO_INCREMENT,
  `category_id` INT DEFAULT NULL,
  `name` VARCHAR(100) DEFAULT NULL,
  `description` TEXT,
  `base_price` DECIMAL(10,2) DEFAULT NULL,
  `rating` DECIMAL(2,1) DEFAULT NULL,
  `genre` VARCHAR(200) DEFAULT NULL,
  `requires_schedule` TINYINT(1) DEFAULT NULL,
  `requires_seat` TINYINT(1) DEFAULT NULL,
  `duration_minutes` INT DEFAULT NULL,
  `age_rating` VARCHAR(10) DEFAULT NULL,
  PRIMARY KEY (`product_id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `ticket_products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `ticket_categories` (`category_id`),
  CONSTRAINT `ticket_products_chk_1` CHECK ((`rating` BETWEEN 0 AND 5.0))
) ENGINE=INNODB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `ticket_products` */

INSERT  INTO `ticket_products`(`product_id`,`category_id`,`name`,`description`,`base_price`,`rating`,`genre`,`requires_schedule`,`requires_seat`,`duration_minutes`,`age_rating`) VALUES 
(1,1,'Inside Out 2','Inside Out 2 returns to the mind of newly minted teenager Riley just as headquarters is undergoing a sudden demolition to make room for something entirely unexpected: new Emotions!',45000.00,4.6,'Animation, Comedy, Family',1,1,96,'SU'),
(2,2,'Zoo Entrance Ticket','All-day zoo access',30000.00,NULL,NULL,0,0,NULL,NULL),
(3,3,'Museum Ticket','Historical museum entry',25000.00,NULL,NULL,0,0,NULL,NULL),
(4,4,'Arcade 2-Hour Pass','Unlimited games for 2 hours',40000.00,NULL,NULL,1,0,NULL,NULL),
(5,6,'Trampoline Arena','1-hour trampoline session',60000.00,NULL,NULL,1,0,NULL,NULL),
(6,7,'Coldplay Concert','Live concert event',750000.00,NULL,NULL,1,1,NULL,NULL),
(7,1,'A Minecraft Movie','A mysterious portal pulls four misfits into the Overworld, a bizarre, cubic wonderland that thrives on imagination.',50000.00,4.2,'Adventure, Fantasy, Family',1,1,NULL,NULL),
(8,1,'Zootopia 2','Detectives Judy Hopps and Nick Wilde uncover a mystery that shakes Zootopia.',40000.00,4.4,'Animation, Adventure, Comedy',1,1,108,'SU'),
(9,1,'Five Nights At Freddys 2','One year after the nightmare at Freddy Fazbear’s Pizza, dark secrets resurface.',45000.00,3.9,'Horror, Thriller',1,1,110,'13+'),
(10,1,'Avatar: Fire and Ash','Pandora faces a new conflict as a hostile Na’vi tribe emerges.',50000.00,4.5,'Science Fiction, Adventure',1,1,NULL,NULL),
(11,1,'Agak Laen: Menyala Pantiku!','Four detectives go undercover in a retirement home to solve a murder case.',40000.00,4.0,'Comedy, Mystery',1,1,NULL,NULL),
(12,1,'Now You See Me: Now You Don\'t','The Four Horsemen return to steal the world’s largest diamond.',50000.00,4.3,'Crime, Thriller',1,1,NULL,NULL),
(13,1,'Alice in Borderland','A gamer is trapped in a deadly alternate version of Tokyo.',45000.00,4.1,'Action, Thriller, Sci-Fi',1,1,NULL,NULL),
(14,1,'Arcane','The origins of two League of Legends champions in Piltover and Zaun.',40000.00,4.7,'Animation, Action, Drama',1,1,NULL,NULL),
(15,1,'The SpongeBob Movie: Search for SquarePants','SpongeBob follows the Flying Dutchman on a pirate adventure.',50000.00,4.2,'Animation, Adventure, Comedy',1,1,NULL,NULL),
(16,1,'KKN di Desa Penari','A community service project turns into a terrifying horror story.',40000.00,3.8,'Horror',1,1,NULL,NULL),
(17,1,'Finding Nemo','A clownfish travels across the ocean to rescue his son.',40000.00,4.6,'Animation, Adventure, Family',1,1,NULL,NULL),
(18,1,'Moana 2','Moana journeys beyond familiar seas on a dangerous new adventure.',45000.00,4.4,'Animation, Adventure, Fantasy',1,1,NULL,NULL),
(19,1,'Final Destination Bloodlines','A college student attempts to stop the cycle of death.',50000.00,4.1,'Horror, Mystery, Thriller',1,1,NULL,NULL),
(20,8,'Popcorn','A variety of corn kernel, which forcefully expands and puffs up when heated.',45000.00,NULL,NULL,0,0,NULL,NULL),
(21,8,'Hotdog','A grilled sausage served in a sliced bun.',20000.00,NULL,NULL,0,0,NULL,NULL),
(22,8,'Burger','A beef patty served in a bun with toppings.',25000.00,NULL,NULL,0,0,NULL,NULL),
(23,8,'Coke','Carbonated soft drink',10000.00,NULL,NULL,0,0,NULL,NULL);

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `user_id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) DEFAULT NULL,
  `email` VARCHAR(100) DEFAULT NULL,
  `password` VARCHAR(100) DEFAULT NULL,
  `role` ENUM('admin','user') DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=INNODB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `users` */

INSERT  INTO `users`(`user_id`,`name`,`email`,`password`,`role`) VALUES 
(1,'Joni','joni@mail.com','12345','user'),
(2,'Ferlinda','fer@mail.com','12345','user'),
(3,'admin','admin@gmail.com','123','admin');

/*Table structure for table `venues` */

DROP TABLE IF EXISTS `venues`;

CREATE TABLE `venues` (
  `venue_id` INT NOT NULL AUTO_INCREMENT,
  `venue_name` VARCHAR(100) DEFAULT NULL,
  `venue_type` VARCHAR(50) DEFAULT NULL,
  `location` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`venue_id`)
) ENGINE=INNODB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `venues` */

INSERT  INTO `venues`(`venue_id`,`venue_name`,`venue_type`,`location`) VALUES 
-- 🎬 CINEMA
(1,'XXI Tunjungan Plaza','Cinema','Surabaya'),
(2,'Surabaya Zoo','Zoo','Surabaya'),
(3,'City Museum','Museum','Surabaya'),
(4,'Trampoline Arena Galaxy','Sport Arena','Surabaya'),
(5,'Gelora Bung Tomo','Stadium','Surabaya'),
(6,'XXI Galaxy Mall','Cinema','Surabaya'),
(7,'XXI Ciputra World','Cinema','Surabaya'),
(8,'CGV BG Junction','Cinema','Surabaya'),
(9,'CGV Marvel City','Cinema','Surabaya'),

(10,'XXI Pakuwon Mall','Cinema','Surabaya'),
(11,'XXI Royal Plaza','Cinema','Surabaya'),
(12,'CGV Grand City','Cinema','Surabaya'),
(13,'CGV Pakuwon Mall','Cinema','Surabaya'),
(14,'Movimax Kaza City Mall','Cinema','Surabaya'),

-- 🐯 ZOO
(15,'Kebun Binatang Surabaya','Zoo','Surabaya'),

-- 🏛️ MUSEUM
(16,'Museum 10 November','Museum','Surabaya'),
(17,'House of Sampoerna Museum','Museum','Surabaya'),
(18,'Museum Pendidikan Surabaya','Museum','Surabaya'),
(19,'Museum WR Soepratman','Museum','Surabaya'),

-- 🕹️ ARCADE
(20,'Timezone Pakuwon Mall','Arcade','Surabaya'),
(21,'Timezone Galaxy Mall','Arcade','Surabaya'),
(22,'Funworld Tunjungan Plaza','Arcade','Surabaya'),
(23,'Amazone Grand City','Arcade','Surabaya'),

-- 🎡 AMUSEMENT PARK
(24,'Trans Studio Mini Pakuwon Mall','Amusement Park','Surabaya'),
(25,'Playtopia Pakuwon Mall','Amusement Park','Surabaya'),
(26,'KidZania Surabaya (Concept)','Amusement Park','Surabaya'),

-- 🏟️ SPORT ARENA
(27,'GOR Pancasila','Sport Arena','Surabaya'),
(28,'Lapangan Thor','Sport Arena','Surabaya'),
(29,'GOR CLS Kertajaya','Sport Arena','Surabaya'),

-- 🎤 EVENT VENUE
(30,'Balai Pemuda Surabaya','Event','Surabaya'),
(31,'Convention Hall Grand City','Event','Surabaya'),
(32,'Dyandra Convention Center','Event','Surabaya'),

-- 🍔 FOOD & BEVERAGE
(33,'Food Junction Grand Pakuwon','Food & Beverage','Surabaya'),
(34,'G-Walk Citraland','Food & Beverage','Surabaya'),
(35,'Pakuwon Food Festival','Food & Beverage','Surabaya'),
(36,'Tunjungan Street Food','Food & Beverage','Surabaya');

/*Table structure for table `vouchers` */

DROP TABLE IF EXISTS `vouchers`;

CREATE TABLE `vouchers` (
  `voucher_id` INT NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(50) NOT NULL,
  `title` VARCHAR(100) NOT NULL,
  `description` TEXT,
  `discount_type` ENUM('percent','fixed') NOT NULL,
  `discount_value` INT NOT NULL,
  `start_date` DATE NOT NULL,
  `end_date` DATE NOT NULL,
  `max_usage` INT DEFAULT NULL,
  `used_count` INT DEFAULT '0',
  `is_active` TINYINT(1) DEFAULT '1',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`voucher_id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=INNODB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `vouchers` */

INSERT  INTO `vouchers`(`voucher_id`,`code`,`title`,`description`,`discount_type`,`discount_value`,`start_date`,`end_date`,`max_usage`,`used_count`,`is_active`,`created_at`,`updated_at`) VALUES 
(1,'MOV01','Movie Voucher 10%',NULL,'percent',10,'2025-01-01','2025-02-01',NULL,0,1,'2025-12-23 10:17:44','2025-12-23 10:19:27'),
(2,'FAMILYB3G1','Family Pack','Buy 3 get 1 free for Waterpark','fixed',1,'2025-01-01','2025-12-31',NULL,0,1,'2025-12-23 10:23:23','2025-12-23 10:23:23'),
(3,'ARCADE2X','Arcade Mania','Double credits for every top-up','percent',100,'2025-01-01','2025-12-31',NULL,0,1,'2025-12-23 10:23:23','2025-12-23 10:23:23'),
(4,'NIGHT5','Night Show','Special price for late night movies','fixed',5,'2025-01-01','2025-12-31',500,0,1,'2025-12-23 10:23:23','2025-12-23 10:23:23'),
(5,'STUDENT20','Student Special','20% discount for students on selected shows','percent',20,'2025-01-01','2025-12-31',300,0,1,'2025-12-23 10:23:23','2025-12-23 10:23:23'),
(6,'WEEKEND15','Weekend Deal','15% off for weekend attractions','percent',15,'2025-01-01','2025-12-31',NULL,0,1,'2025-12-23 10:23:23','2025-12-23 10:23:23');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
