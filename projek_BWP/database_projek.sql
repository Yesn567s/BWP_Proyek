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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `order_items` */

insert  into `order_items`(`order_item_id`,`order_id`,`product_id`,`schedule_id`,`seat_id`,`price`) values 
(1,1,1,1,1,45000.00),
(2,1,1,1,2,45000.00),
(3,2,6,4,4,750000.00),
(4,3,17,11,3,50000.00);

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `orders` */

insert  into `orders`(`order_id`,`user_id`,`order_date`,`total_price`) values 
(1,1,'2025-11-19 16:30:00',90000.00),
(2,2,'2025-11-19 17:00:00',750000.00),
(3,2,'2025-10-01 17:17:45',50000.00);

/*Table structure for table `product_media` */

DROP TABLE IF EXISTS `product_media`;

CREATE TABLE `product_media` (
  `media_id` int NOT NULL AUTO_INCREMENT,
  `product_id` int DEFAULT NULL,
  `media_type` enum('poster','trailer','image','video') DEFAULT NULL,
  `media_url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`media_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `product_media_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `ticket_products` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `product_media` */

insert  into `product_media`(`media_id`,`product_id`,`media_type`,`media_url`) values 
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
(20,19,'trailer','https://youtu.be/UWMzKXsY9A4?si=pfPCWSIYVt3m0zDp');

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
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `schedules` */

insert  into `schedules`(`schedule_id`,`product_id`,`venue_id`,`start_datetime`,`end_datetime`) values 
(1,1,1,'2025-11-20 18:00:00','2025-11-20 20:00:00'),
(2,4,4,'2025-11-21 14:00:00','2025-11-21 16:00:00'),
(3,5,4,'2025-11-22 10:00:00','2025-11-22 11:00:00'),
(4,6,5,'2025-12-01 19:00:00','2025-12-01 22:00:00'),
(5,7,7,'2026-01-15 12:00:00','2026-02-15 20:00:00'),
(6,12,9,'2025-12-31 12:00:00','2026-01-31 20:00:00'),
(7,13,8,'2026-01-09 10:00:00','2026-02-04 23:30:00'),
(8,14,1,'2026-01-02 11:00:00','2026-02-14 21:30:00'),
(9,15,6,'2026-02-11 22:00:00','2026-03-18 23:00:00'),
(10,16,1,'2025-11-01 08:25:45','2025-12-31 08:25:54'),
(11,17,8,'2025-09-01 08:31:10','2025-11-30 08:31:16'),
(12,18,9,'2025-10-01 09:04:01','2025-11-30 09:04:07'),
(13,19,8,'2025-05-01 09:06:21','2025-06-30 09:06:30');

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
  `icons` varchar(50) NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `ticket_categories` */

insert  into `ticket_categories`(`category_id`,`category_name`,`icons`) values 
(1,'Movie','icons/watching-a-movie.png'),
(2,'Zoo','icons/zoo.png'),
(3,'Museum','icons/art-museum.png'),
(4,'Arcade','icons/arcade-machine.png'),
(5,'Amusement Park','icons/theme-park.png'),
(6,'Sport','icons/basketball.png'),
(7,'Event','icons/event-list.png');

/*Table structure for table `ticket_instances` */

DROP TABLE IF EXISTS `ticket_instances`;

CREATE TABLE `ticket_instances` (
  `ticket_instance_id` int NOT NULL AUTO_INCREMENT,
  `order_item_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `qr_code` varchar(255) DEFAULT NULL,
  `is_used` tinyint(1) DEFAULT '0',
  `status` enum('active','used','cancelled','expired') DEFAULT 'active',
  `valid_until` datetime DEFAULT NULL,
  `used_at` datetime DEFAULT NULL,
  PRIMARY KEY (`ticket_instance_id`),
  KEY `order_item_id` (`order_item_id`),
  KEY `fk_ticket_instances_user` (`user_id`),
  CONSTRAINT `fk_ticket_instances_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  CONSTRAINT `ticket_instances_ibfk_1` FOREIGN KEY (`order_item_id`) REFERENCES `order_items` (`order_item_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `ticket_instances` */

insert  into `ticket_instances`(`ticket_instance_id`,`order_item_id`,`user_id`,`qr_code`,`is_used`,`status`,`valid_until`,`used_at`) values 
(1,1,1,'QR-MOVIE-A1',0,'active',NULL,NULL),
(2,2,1,'QR-MOVIE-A2',0,'active',NULL,NULL),
(3,3,2,'QR-CONCERT-VIP1',0,'active',NULL,NULL),
(4,4,2,'QR-MOVIE-FN',1,'used',NULL,'2025-11-01 17:20:01');

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
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `ticket_products` */

insert  into `ticket_products`(`product_id`,`category_id`,`name`,`description`,`base_price`,`requires_schedule`,`requires_seat`) values 
(1,1,'Inside Out 2','Inside Out 2 returns to the mind of newly minted teenager Riley just as headquarters is undergoing a sudden demolition to make room for something entirely unexpected: new Emotions! Joy, Sadness, Anger, Fear, and Disgust, who\'ve long been running a successful operation by all accounts, aren\'t sure how to feel when Anxiety shows up and it looks like she\'s not alone.',45000.00,1,1),
(2,2,'Zoo Entrance Ticket','All-day zoo access',30000.00,0,0),
(3,3,'Museum Ticket','Historical museum entry',25000.00,0,0),
(4,4,'Arcade 2-Hour Pass','Unlimited games for 2 hours',40000.00,1,0),
(5,6,'Trampoline Arena','1-hour trampoline session',60000.00,1,0),
(6,7,'Coldplay Concert','Live concert event',750000.00,1,1),
(7,1,'A Minecraft Movie','A mysterious portal pulls four misfits into the Overworld, a bizarre, cubic wonderland that thrives on imagination. To get back home, they\'ll have to master the terrain while embarking on a magical quest with an unexpected crafter named Steve.',50000.00,1,1),
(8,1,'Zootopia 2','Detectives Judy Hopps and Nick Wilde find themselves on the twisting trail of a mysterious reptile who turns the mammal metropolis of Zootopia upside down. Testing their growing partnership like never before, they go under cover in new parts of town to crack the case.',40000.00,1,1),
(9,1,'Five Nights At Freddys 2','One year has passed since the supernatural nightmare at Freddy Fazbear\'s Pizza. Former security guard Mike has kept the truth from his 11-year-old sister, Abby, concerning the fate of her animatronic friends. When Abby sneaks out to reconnect with Freddy, Bonnie, Chica and Foxy, she sets into motion a terrifying series of events that reveal dark secrets about the true origin of Freddy\'s.',45000.00,1,1),
(10,1,'Avatar: Fire and Ash','The conflict on Pandora escalates as Jake and Neytiri\'s family encounter a new, aggressive Na\'vi tribe.',50000.00,1,1),
(11,1,'Agak Laen: Menyala Pantiku!','Setelah berulang kali gagal menjalankan misi, Detektif Bene, Boris, Jegel, dan Oki diberi satu kesempatan terakhir: Menyamar dan menyusup ke sebuah panti jompo, untuk mencari buronan kasus pembunuhan anak wali kota.',40000.00,1,1),
(12,1,'Now You See Me: Now You Don\'t','The Four Horsemen and a new generation of illusionists join forces to steal the world\'s largest diamond from a South African mogul who heads an international crime syndicate.',50000.00,1,1),
(13,1,'Alice in Borderland','Obsessed gamer Arisu suddenly finds himself in a strange, emptied-out version of Tokyo in which he and his friends must compete in dangerous games in order to survive.',45000.00,1,1),
(14,1,'Arcane','The origins of two iconic League champions, set in the utopian Piltover and the oppressed underground of Zaun.',40000.00,1,1),
(15,1,'The SpongeBob Movie: Search for SquarePants','Hoping to prove his bravery to Mr. Krabs, SpongeBob follows a mysterious, swashbuckling ghost pirate known as the Flying Dutchman on a seafaring adventure that takes him to the deepest depths of the ocean.',50000.00,1,1),
(16,1,'KKN di Desa Penari','When a group of students visits a village for community service, their project turns deadly. They encounter the wrathful spirit of a dancer, which unleashes a terrifying cascade of horrors upon them.',40000.00,1,1),
(17,1,'Finding Nemo','After his son gets abducted from the Great Barrier Reef and is dispatched to Sydney, Marlin, a meek clownfish, enlists the help of a forgetful fish and embarks on a journey to bring him home.',40000.00,1,1),
(18,1,'Moana 2','After receiving an unexpected call from her wayfinding ancestors, a strong-willed girl journeys with her crew to the far seas of Oceania and into dangerous, long-lost waters for an adventure unlike anything she has ever faced.',45000.00,1,1),
(19,1,'Final Destination Bloodlines','Plagued by a violent and recurring nightmare, a college student heads home to track down the one person who might be able to break the cycle of death and save her family from the grisly demise that inevitably awaits them all.',50000.00,1,1);

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `users` */

insert  into `users`(`user_id`,`name`,`email`,`password`) values 
(1,'Joni','joni@mail.com','12345'),
(2,'Ferlinda','fer@mail.com','12345'),
(3,'admin','admin@gmail.com','123');

/*Table structure for table `venues` */

DROP TABLE IF EXISTS `venues`;

CREATE TABLE `venues` (
  `venue_id` int NOT NULL AUTO_INCREMENT,
  `venue_name` varchar(100) DEFAULT NULL,
  `venue_type` varchar(50) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`venue_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `venues` */

insert  into `venues`(`venue_id`,`venue_name`,`venue_type`,`location`) values 
(1,'XXI Tunjungan Plaza','Cinema','Surabaya'),
(2,'Surabaya Zoo','Zoo','Surabaya'),
(3,'City Museum','Museum','Surabaya'),
(4,'Trampoline Arena Galaxy','Sport Arena','Surabaya'),
(5,'Gelora Bung Tomo','Stadium','Surabaya'),
(6,'XXI Galaxy Mall','Cinema','Surabaya'),
(7,'XXI Ciputra World','Cinema','Surabaya'),
(8,'CGV BG Junction','Cinema','Surabaya'),
(9,'CGV Marvel City','Cinema',NULL);

/*Table structure for table `vouchers` */

DROP TABLE IF EXISTS `vouchers`;

CREATE TABLE `vouchers` (
  `voucher_id` int NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text,
  `discount_type` enum('percent','fixed') NOT NULL,
  `discount_value` int NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `max_usage` int DEFAULT NULL,
  `used_count` int DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`voucher_id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `vouchers` */

insert  into `vouchers`(`voucher_id`,`code`,`title`,`description`,`discount_type`,`discount_value`,`start_date`,`end_date`,`max_usage`,`used_count`,`is_active`,`created_at`,`updated_at`) values 
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
