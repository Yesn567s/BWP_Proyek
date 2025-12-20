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
  `order_item_id` int NOT NULL AUTO_INCREMENT,
  `order_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `schedule_id` int DEFAULT NULL,
  `seat_id` int DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`order_item_id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`),
  KEY `schedule_id` (`schedule_id`),
  KEY `seat_id` (`seat_id`),
  CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `ticket_products` (`product_id`),
  CONSTRAINT `order_items_ibfk_3` FOREIGN KEY (`schedule_id`) REFERENCES `schedules` (`schedule_id`),
  CONSTRAINT `order_items_ibfk_4` FOREIGN KEY (`seat_id`) REFERENCES `seats` (`seat_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `order_items` */

insert  into `order_items`(`order_item_id`,`order_id`,`product_id`,`schedule_id`,`seat_id`,`price`) values 
(1,1,1,1,1,45000.00),
(2,1,1,1,2,45000.00),
(3,2,6,4,4,750000.00);

/*Table structure for table `orders` */

DROP TABLE IF EXISTS `orders`;

CREATE TABLE `orders` (
  `order_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `order_date` datetime DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`order_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `orders` */

insert  into `orders`(`order_id`,`user_id`,`order_date`,`total_price`) values 
(1,1,'2025-11-19 16:30:00',90000.00),
(2,2,'2025-11-19 17:00:00',750000.00);

/*Table structure for table `schedules` */

DROP TABLE IF EXISTS `schedules`;

CREATE TABLE `schedules` (
  `schedule_id` int NOT NULL AUTO_INCREMENT,
  `product_id` int DEFAULT NULL,
  `venue_id` int DEFAULT NULL,
  `start_datetime` datetime DEFAULT NULL,
  `end_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`schedule_id`),
  KEY `product_id` (`product_id`),
  KEY `venue_id` (`venue_id`),
  CONSTRAINT `schedules_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `ticket_products` (`product_id`),
  CONSTRAINT `schedules_ibfk_2` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`venue_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `schedules` */

insert  into `schedules`(`schedule_id`,`product_id`,`venue_id`,`start_datetime`,`end_datetime`) values 
(1,1,1,'2025-11-20 18:00:00','2025-11-20 20:00:00'),
(2,4,4,'2025-11-21 14:00:00','2025-11-21 16:00:00'),
(3,5,4,'2025-11-22 10:00:00','2025-11-22 11:00:00'),
(4,6,5,'2025-12-01 19:00:00','2025-12-01 22:00:00');

/*Table structure for table `seats` */

DROP TABLE IF EXISTS `seats`;

CREATE TABLE `seats` (
  `seat_id` int NOT NULL AUTO_INCREMENT,
  `venue_id` int DEFAULT NULL,
  `seat_code` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`seat_id`),
  KEY `venue_id` (`venue_id`),
  CONSTRAINT `seats_ibfk_1` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`venue_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `seats` */

insert  into `seats`(`seat_id`,`venue_id`,`seat_code`) values 
(1,1,'A1'),
(2,1,'A2'),
(3,1,'A3'),
(4,5,'VIP1'),
(5,5,'VIP2'),
(6,5,'VIP3');

/*Table structure for table `ticket_categories` */

DROP TABLE IF EXISTS `ticket_categories`;

CREATE TABLE `ticket_categories` (
  `category_id` int NOT NULL AUTO_INCREMENT,
  `category_name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `ticket_categories` */

insert  into `ticket_categories`(`category_id`,`category_name`) values 
(1,'Movie'),
(2,'Zoo'),
(3,'Museum'),
(4,'Arcade'),
(5,'Amusement Park'),
(6,'Sport'),
(7,'Event');

/*Table structure for table `ticket_instances` */

DROP TABLE IF EXISTS `ticket_instances`;

CREATE TABLE `ticket_instances` (
  `ticket_instance_id` int NOT NULL AUTO_INCREMENT,
  `order_item_id` int DEFAULT NULL,
  `qr_code` varchar(255) DEFAULT NULL,
  `is_used` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`ticket_instance_id`),
  KEY `order_item_id` (`order_item_id`),
  CONSTRAINT `ticket_instances_ibfk_1` FOREIGN KEY (`order_item_id`) REFERENCES `order_items` (`order_item_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `ticket_instances` */

insert  into `ticket_instances`(`ticket_instance_id`,`order_item_id`,`qr_code`,`is_used`) values 
(1,1,'QR-MOVIE-A1',0),
(2,2,'QR-MOVIE-A2',0),
(3,3,'QR-CONCERT-VIP1',0);

/*Table structure for table `ticket_products` */

DROP TABLE IF EXISTS `ticket_products`;

CREATE TABLE `ticket_products` (
  `product_id` int NOT NULL AUTO_INCREMENT,
  `category_id` int DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `description` text,
  `base_price` decimal(10,2) DEFAULT NULL,
  `requires_schedule` tinyint(1) DEFAULT NULL,
  `requires_seat` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`product_id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `ticket_products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `ticket_categories` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `ticket_products` */

insert  into `ticket_products`(`product_id`,`category_id`,`name`,`description`,`base_price`,`requires_schedule`,`requires_seat`) values 
(1,1,'Inside Out 2','Animated movie',45000.00,1,1),
(2,2,'Zoo Entrance Ticket','All-day zoo access',30000.00,0,0),
(3,3,'Museum Ticket','Historical museum entry',25000.00,0,0),
(4,4,'Arcade 2-Hour Pass','Unlimited games for 2 hours',40000.00,1,0),
(5,6,'Trampoline Arena','1-hour trampoline session',60000.00,1,0),
(6,7,'Coldplay Concert','Live concert event',750000.00,1,1);

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `users` */

insert  into `users`(`user_id`,`name`,`email`,`password`) values 
(1,'Joni','joni@mail.com','12345'),
(2,'Ferlinda','fer@mail.com','12345');

/*Table structure for table `venues` */

DROP TABLE IF EXISTS `venues`;

CREATE TABLE `venues` (
  `venue_id` int NOT NULL AUTO_INCREMENT,
  `venue_name` varchar(100) DEFAULT NULL,
  `venue_type` varchar(50) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`venue_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `venues` */

insert  into `venues`(`venue_id`,`venue_name`,`venue_type`,`location`) values 
(1,'XXI Tunjungan Plaza','Cinema','Surabaya'),
(2,'Surabaya Zoo','Zoo','Surabaya'),
(3,'City Museum','Museum','Surabaya'),
(4,'Trampoline Arena Galaxy','Sport Arena','Surabaya'),
(5,'Gelora Bung Tomo','Stadium','Surabaya');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
