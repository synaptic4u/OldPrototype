-- MariaDB dump 10.19  Distrib 10.6.8-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: api_logs
-- ------------------------------------------------------
-- Server version	10.6.8-MariaDB-1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `api_logs`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `api_logs` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;

USE `api_logs`;

--
-- Table structure for table `logs`
--

DROP TABLE IF EXISTS `logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `logs` (
  `logid` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'activity',
  `loggedon` datetime NOT NULL DEFAULT current_timestamp(),
  `location` varchar(255) DEFAULT NULL,
  `log` longtext DEFAULT NULL,
  `params` text DEFAULT NULL,
  `data` text DEFAULT NULL,
  `post` text DEFAULT NULL,
  `calls` text DEFAULT NULL,
  `result` text DEFAULT NULL,
  `reply` text DEFAULT NULL,
  `template` text DEFAULT NULL,
  PRIMARY KEY (`logid`),
  KEY `userid` (`userid`),
  KEY `type` (`type`),
  KEY `loggedon` (`loggedon`),
  KEY `location` (`location`)
) ENGINE=InnoDB AUTO_INCREMENT=424510 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping events for database 'api_logs'
--

--
-- Dumping routines for database 'api_logs'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-08-11 19:49:47
