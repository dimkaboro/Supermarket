-- MySQL dump 10.13  Distrib 8.0.19, for Win64 (x86_64)
--
-- Host: localhost    Database: supermarket
-- ------------------------------------------------------
-- Server version	5.7.44

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `Zbozi`
--

DROP TABLE IF EXISTS `Zbozi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Zbozi` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Nazev` varchar(255) NOT NULL,
  `Cena` decimal(10,2) NOT NULL,
  `Hmotnost` decimal(10,3) NOT NULL,
  `Obrazek` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Zbozi`
--

LOCK TABLES `Zbozi` WRITE;
/*!40000 ALTER TABLE `Zbozi` DISABLE KEYS */;
INSERT INTO `Zbozi` VALUES (1,'Fresh Tomatoes',2.49,1.200,'app/images/fresh-tomatoes-white.jpg'),(2,'Organic Bananas',1.99,1.000,'app/images/banana.jpg'),(3,'Whole Wheat Bread',3.49,0.500,'app/images/bread.png'),(4,'Fresh Chicken Breast',6.99,2.000,'app/images/chicken.png'),(5,'Almond Milk',4.29,1.000,'app/images/almond_milk.png'),(6,'Free-Range Eggs',3.99,0.600,'app/images/eggs.png'),(7,'Cheddar Cheese',5.79,0.800,'app/images/chedar.png'),(8,'Orange Juice',3.89,1.500,'app/images/orangejuice.png'),(9,'Organic Spinach',2.99,0.300,'app/images/spinach.png'),(10,'Salmon Fillet',8.99,1.000,'app/images/salmon.png');
/*!40000 ALTER TABLE `Zbozi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `gender` enum('male','female','other') NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'fwfwfwefwe','wrfwefwefwef','nlmnlmagt@gmail.com','+420739031929','male','fwefwf','$2y$10$s0ryh/wFYI4.Rvsjk03yIee7dBZXZcg0yEAdSPHgS0wT/ewOHMTaK','uploads/6731fb2dd32e3.jpg','2024-11-11 12:40:13'),(2,'Nazar','Bobovnykov','pavlo22222@gmail.com','+42073232323','male','Loverqwer','$2y$10$rUjJ3AUGbhlF5IDGfHhvquay0ExGRK/wTJEoqIl3WET6JkmrLxTtO','uploads/67320529af21f.jpg','2024-11-11 13:22:49'),(3,'Sashko','Aharomn','sasha@gmail.com','+42014882288','other','Silnyj','$2y$10$18K26SPyxOwOoFKjAe9LR.EZW7g16QJ2xRq4XcbCXP3afuOBGIkUK','uploads/67320f4c3e9f6.jpg','2024-11-11 14:06:04'),(4,'Nazar','Bobovnykov','Vojavy@gmail.com','+420777777777','other','Boss','$2y$10$lWoh5utn2VKqS4ffyz2L7.jOMoaV5bltiHn.dY21URnP.SeAC4evC','uploads/675f1158135f1.jpg','2024-12-15 17:26:48'),(5,'Aronov','Sosat','Aharon@gmail.com','+42066778899','other','Haraon','$2y$10$.MqBPfb1cJBgUE5NCvCkxOcVqdezqz0/VCGMkuGz9iXpyPWBAw25W','uploads/675f648b91449.jpg','2024-12-15 23:21:47'),(6,'Nazar','Programist','nazarprogramist228@mail.ru','+420111222333','other','nazaruou','$2y$10$MdlEejZThb4AMuQDsH.aG.uz98DvJQ9QFTPns92m2E50iBl/.nUHC','uploads/675f6a136a0cf.jpeg','2024-12-15 23:45:23');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'supermarket'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-12-16  5:55:32
