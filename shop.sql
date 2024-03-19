-- MySQL dump 10.13  Distrib 8.0.36, for Win64 (x86_64)
--
-- Host: localhost    Database: shop
-- ------------------------------------------------------
-- Server version	8.0.36

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `catID` int NOT NULL AUTO_INCREMENT,
  `catName` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `catDescription` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci,
  `ordering` int DEFAULT NULL,
  `visibility` tinyint DEFAULT '0',
  `Allow_comments` tinyint DEFAULT '0',
  `Allow_Ads` tinyint DEFAULT '0',
  `parent` int DEFAULT '0',
  PRIMARY KEY (`catID`),
  UNIQUE KEY `catName` (`catName`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (8,'Hand made','Hand made items',1,0,0,0,0),(9,'Computers','Computers items',2,0,0,0,0),(10,'Cell Phones','Cell Phones',3,0,0,0,0),(11,'Clothes','Clothes items',4,0,0,0,0),(12,'Tools','Tools items',5,0,0,0,0),(14,'Nokia','cell phone',6,0,0,0,10);
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `comments` (
  `c_id` int NOT NULL AUTO_INCREMENT,
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `status` tinyint DEFAULT NULL,
  `comment_date` date DEFAULT NULL,
  `item_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  PRIMARY KEY (`c_id`),
  KEY `items_comment` (`item_id`),
  KEY `comment_user` (`user_id`),
  CONSTRAINT `comment_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `items_comment` FOREIGN KEY (`item_id`) REFERENCES `items` (`itemID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comments`
--

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
INSERT INTO `comments` VALUES (11,'Excellent gaming device',1,'2024-03-16',23,56);
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `items`
--

DROP TABLE IF EXISTS `items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `items` (
  `itemID` int NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `Description` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci,
  `Price` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `AddedDate` date DEFAULT NULL,
  `Country` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `Rating` smallint DEFAULT NULL,
  `Approve` tinyint DEFAULT '0',
  `Cat_ID` int DEFAULT NULL,
  `Member_ID` int DEFAULT NULL,
  `tags` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`itemID`),
  KEY `fk_cats` (`Cat_ID`),
  KEY `fk_members` (`Member_ID`),
  CONSTRAINT `fk_cats` FOREIGN KEY (`Cat_ID`) REFERENCES `categories` (`catID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_members` FOREIGN KEY (`Member_ID`) REFERENCES `users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `items`
--

LOCK TABLES `items` WRITE;
/*!40000 ALTER TABLE `items` DISABLE KEYS */;
INSERT INTO `items` VALUES (19,'Screw Driver','Screw','5','2024-03-08','Germany',NULL,'1',NULL,1,12,56,NULL),(20,'Saw','saw','10','2024-03-08','USA',NULL,'1',NULL,0,12,57,NULL),(21,'Melton','comfy pants','60','2024-03-10','Germany',NULL,'1',NULL,1,11,56,NULL),(22,'Galaxy Fold','Good phone','100','2024-03-11','korea',NULL,'1',NULL,1,10,56,'Creative'),(23,'Dell','Good laptop','500','2024-03-11','Germany',NULL,'1',NULL,1,9,56,NULL),(25,'MSI','Bad laptop','200','2024-03-16','UK',NULL,'3',NULL,1,9,56,'Bad');
/*!40000 ALTER TABLE `items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `userID` int NOT NULL AUTO_INCREMENT,
  `userName` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL COMMENT 'to login',
  `Pass` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL COMMENT 'to login',
  `Email` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `Fullname` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `GroupID` int DEFAULT '0' COMMENT 'Identify user group',
  `TrustStatus` int DEFAULT '0' COMMENT 'Seller rank',
  `RegStatus` int DEFAULT '0' COMMENT 'pending approval',
  `currDate` date DEFAULT NULL,
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`userID`),
  UNIQUE KEY `userID` (`userID`),
  UNIQUE KEY `userName` (`userName`)
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Osama','40bd001563085fc35165329ea1ff5c5ecbdbbeef','osama@gmail.com','osama elzero',1,0,1,NULL,NULL),(56,'sameh','40bd001563085fc35165329ea1ff5c5ecbdbbeef','ahmed@gmail.com','Elashry',0,0,1,'2024-03-07',NULL),(57,'smaeh','40bd001563085fc35165329ea1ff5c5ecbdbbeef','medo@gmail.com','medomedo000',0,0,1,'2024-03-07',NULL),(58,'Ahmed','7c4a8d09ca3762af61e59520943dc26494f8941b','ahmed@gmail.com',NULL,0,0,0,'2024-03-10',NULL),(59,'Abdo','7c4a8d09ca3762af61e59520943dc26494f8941b','abdo@gmail.com',NULL,0,0,0,'2024-03-10',NULL),(60,'Emad','7c4a8d09ca3762af61e59520943dc26494f8941b','emad@gmail.com',NULL,0,0,0,'2024-03-10',NULL),(61,'Esam','7c4a8d09ca3762af61e59520943dc26494f8941b','medo@gmail.com',NULL,0,0,0,'2024-03-10',NULL),(63,'lolo','40bd001563085fc35165329ea1ff5c5ecbdbbeef','abdo@gmail.com','lolo',0,0,1,'2024-03-19','755949.6362329.jpg');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-03-19 12:21:03
