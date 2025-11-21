/*
SQLyog Community v13.3.1 (64 bit)
MySQL - 8.0.30 : Database - db_projek
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`db_projek` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `db_projek`;

/*Table structure for table `booked_seats` */

DROP TABLE IF EXISTS `booked_seats`;

CREATE TABLE `booked_seats` (
  `booked_id` int NOT NULL AUTO_INCREMENT,
  `ticket_id` int DEFAULT NULL,
  `seat_id` int DEFAULT NULL,
  PRIMARY KEY (`booked_id`),
  KEY `ticket_id` (`ticket_id`),
  KEY `seat_id` (`seat_id`),
  CONSTRAINT `booked_seats_ibfk_1` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`ticket_id`),
  CONSTRAINT `booked_seats_ibfk_2` FOREIGN KEY (`seat_id`) REFERENCES `seats` (`seat_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `booked_seats` */

insert  into `booked_seats`(`booked_id`,`ticket_id`,`seat_id`) values 
(1,1,1),
(2,2,2),
(3,3,6),
(4,4,7);

/*Table structure for table `cinemas` */

DROP TABLE IF EXISTS `cinemas`;

CREATE TABLE `cinemas` (
  `cinema_id` int NOT NULL AUTO_INCREMENT,
  `cinema_name` varchar(100) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`cinema_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `cinemas` */

insert  into `cinemas`(`cinema_id`,`cinema_name`,`location`) values 
(1,'Cinépolis Galaxy Mall','Surabaya'),
(2,'XXI Tunjungan Plaza','Surabaya');

/*Table structure for table `movies` */

DROP TABLE IF EXISTS `movies`;

CREATE TABLE `movies` (
  `movie_id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(100) DEFAULT NULL,
  `duration` int DEFAULT NULL,
  `genre` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`movie_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `movies` */

insert  into `movies`(`movie_id`,`title`,`duration`,`genre`) values 
(1,'Inside Out 2',100,'Animation'),
(2,'Deadpool & Wolverine',130,'Action');

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
(1,1,'2025-11-19 16:23:43',90000.00),
(2,2,'2025-11-19 16:23:43',120000.00);

/*Table structure for table `seats` */

DROP TABLE IF EXISTS `seats`;

CREATE TABLE `seats` (
  `seat_id` int NOT NULL AUTO_INCREMENT,
  `studio_id` int DEFAULT NULL,
  `seat_code` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`seat_id`),
  KEY `studio_id` (`studio_id`),
  CONSTRAINT `seats_ibfk_1` FOREIGN KEY (`studio_id`) REFERENCES `studios` (`studio_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `seats` */

insert  into `seats`(`seat_id`,`studio_id`,`seat_code`) values 
(1,1,'A1'),
(2,1,'A2'),
(3,1,'A3'),
(4,2,'C1'),
(5,2,'C2'),
(6,3,'VIP1'),
(7,3,'VIP2');

/*Table structure for table `showtimes` */

DROP TABLE IF EXISTS `showtimes`;

CREATE TABLE `showtimes` (
  `showtime_id` int NOT NULL AUTO_INCREMENT,
  `studio_id` int DEFAULT NULL,
  `movie_id` int DEFAULT NULL,
  `show_date` date DEFAULT NULL,
  `show_time` time DEFAULT NULL,
  PRIMARY KEY (`showtime_id`),
  KEY `studio_id` (`studio_id`),
  KEY `movie_id` (`movie_id`),
  CONSTRAINT `showtimes_ibfk_1` FOREIGN KEY (`studio_id`) REFERENCES `studios` (`studio_id`),
  CONSTRAINT `showtimes_ibfk_2` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`movie_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `showtimes` */

insert  into `showtimes`(`showtime_id`,`studio_id`,`movie_id`,`show_date`,`show_time`) values 
(1,1,1,'2025-11-20','18:00:00'),
(2,1,2,'2025-11-20','20:30:00'),
(3,3,2,'2025-11-21','19:00:00');

/*Table structure for table `studios` */

DROP TABLE IF EXISTS `studios`;

CREATE TABLE `studios` (
  `studio_id` int NOT NULL AUTO_INCREMENT,
  `cinema_id` int DEFAULT NULL,
  `studio_name` varchar(50) DEFAULT NULL,
  `capacity` int DEFAULT NULL,
  PRIMARY KEY (`studio_id`),
  KEY `cinema_id` (`cinema_id`),
  CONSTRAINT `studios_ibfk_1` FOREIGN KEY (`cinema_id`) REFERENCES `cinemas` (`cinema_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `studios` */

insert  into `studios`(`studio_id`,`cinema_id`,`studio_name`,`capacity`) values 
(1,1,'Studio 1',50),
(2,1,'Studio 2',75),
(3,2,'Studio Premier',40);

/*Table structure for table `tickets` */

DROP TABLE IF EXISTS `tickets`;

CREATE TABLE `tickets` (
  `ticket_id` int NOT NULL AUTO_INCREMENT,
  `order_id` int DEFAULT NULL,
  `showtime_id` int DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`ticket_id`),
  KEY `order_id` (`order_id`),
  KEY `showtime_id` (`showtime_id`),
  CONSTRAINT `tickets_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  CONSTRAINT `tickets_ibfk_2` FOREIGN KEY (`showtime_id`) REFERENCES `showtimes` (`showtime_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `tickets` */

insert  into `tickets`(`ticket_id`,`order_id`,`showtime_id`,`price`) values 
(1,1,1,45000.00),
(2,1,1,45000.00),
(3,2,3,60000.00),
(4,2,3,60000.00);

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
(1,'joni','joni@mail.com','12345'),
(2,'ferlinda','fer@mail.com','12345');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
