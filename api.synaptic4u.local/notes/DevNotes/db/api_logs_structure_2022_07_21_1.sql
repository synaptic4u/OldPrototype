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
) ENGINE=InnoDB AUTO_INCREMENT=429630 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping events for database 'api_logs'
--

--
-- Dumping routines for database 'api_logs'
--
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `view_timer` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`api_logs`@`localhost` PROCEDURE `view_timer`()
BEGIN
			
select logid, userid, `type`, loggedon, 
		REPLACE(SUBSTRING(log, locate("controller:",log), (locate("method:",log) - locate("controller:",log) - 2)), "controller: ", "") as `controller`,
		REPLACE(SUBSTRING(log, locate("method:",log), (locate("start:",log) - locate("method:",log) - 2)), "method: ", "") as `method`,
		REPLACE(REPLACE(SUBSTRING(log, locate("time:",log)), "\\n\"", ""), "time:", "") as `time`,
		REPLACE(SUBSTRING(log, locate("start:",log), (locate("finish:",log) - locate("start:",log) - 2)), "start: ", "") as `start`,
		REPLACE(SUBSTRING(log, locate("finish:",log), (locate("time:",log) - locate("finish:",log) - 2)), "finish: ", "") as `finish`,
		REPLACE(SUBSTRING(log, locate("sessionid:",log), (locate("controller:",log) - locate("sessionid:",log) - 2)), "sessionid: ", "") as `sessionid`
  from api_logs.logs
 where `type`="timer"  
 order by logid DESC 
 limit 200;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-07-21  9:16:58
