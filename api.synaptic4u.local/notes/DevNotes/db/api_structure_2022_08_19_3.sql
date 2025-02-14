-- MariaDB dump 10.19  Distrib 10.5.12-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: api
-- ------------------------------------------------------
-- Server version	10.5.12-MariaDB-1

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
-- Current Database: `api`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `api` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;

USE `api`;

--
-- Table structure for table `application_modules`
--

DROP TABLE IF EXISTS `application_modules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `application_modules` (
  `moduleid` int(11) NOT NULL AUTO_INCREMENT,
  `appid` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `controller` varchar(255) DEFAULT NULL,
  `method` varchar(255) DEFAULT NULL,
  `submoduleid` int(11) DEFAULT NULL,
  `createdon` datetime NOT NULL DEFAULT current_timestamp(),
  `updatedon` datetime NOT NULL DEFAULT current_timestamp(),
  `view` int(11) DEFAULT 0,
  `create` int(11) DEFAULT 0,
  `edit` int(11) DEFAULT 0,
  `delete` int(11) DEFAULT 0,
  PRIMARY KEY (`moduleid`),
  KEY `appid` (`appid`),
  CONSTRAINT `application_modules_appid_fk` FOREIGN KEY (`appid`) REFERENCES `applications` (`appid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `application_users`
--

DROP TABLE IF EXISTS `application_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `application_users` (
  `appid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  KEY `appid` (`appid`),
  KEY `userid` (`userid`),
  CONSTRAINT `application_users_appid_fk` FOREIGN KEY (`appid`) REFERENCES `application_menus` (`appid`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `application_users_userid_fk` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `applications`
--

DROP TABLE IF EXISTS `applications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `applications` (
  `appid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `createdon` datetime NOT NULL DEFAULT current_timestamp(),
  `updatedon` datetime NOT NULL DEFAULT current_timestamp(),
  `description` text DEFAULT NULL,
  `core_system` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`appid`),
  KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `checklist`
--

DROP TABLE IF EXISTS `checklist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `checklist` (
  `checklistid` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `datedon` datetime NOT NULL,
  `deleted` int(11) NOT NULL DEFAULT 0,
  `qoutes` text DEFAULT NULL,
  `list` text DEFAULT NULL,
  PRIMARY KEY (`checklistid`),
  KEY `userid` (`userid`),
  CONSTRAINT `checklist_userid_fk` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `confirm_login_users`
--

DROP TABLE IF EXISTS `confirm_login_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `confirm_login_users` (
  `confirmid` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `datedon` datetime DEFAULT current_timestamp(),
  `nonce` text DEFAULT NULL,
  `mykey` text DEFAULT NULL,
  `cipher` text DEFAULT NULL,
  PRIMARY KEY (`confirmid`),
  KEY `userid` (`userid`),
  KEY `cipher` (`cipher`(768)),
  CONSTRAINT `confirm_login_users_userid_fk` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1704 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `confirm_register_users`
--

DROP TABLE IF EXISTS `confirm_register_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `confirm_register_users` (
  `registerid` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `inviteid` int(11) NOT NULL,
  `datedon` datetime DEFAULT current_timestamp(),
  `nonce` text DEFAULT NULL,
  `mykey` text DEFAULT NULL,
  `cipher` text DEFAULT NULL,
  PRIMARY KEY (`registerid`),
  KEY `userid` (`userid`),
  KEY `inviteid` (`inviteid`),
  CONSTRAINT `confirm_register_users_inviteid_fk` FOREIGN KEY (`inviteid`) REFERENCES `invites` (`inviteid`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `confirm_register_users_userid_fk` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=907 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hierachy`
--

DROP TABLE IF EXISTS `hierachy`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hierachy` (
  `hierachyid` int(11) NOT NULL AUTO_INCREMENT,
  `hierachysubid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `createdon` datetime NOT NULL DEFAULT current_timestamp(),
  `updatedon` datetime NOT NULL DEFAULT current_timestamp(),
  `visible` int(11) DEFAULT 1,
  `levelid` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`hierachyid`),
  KEY `hierachysubid` (`hierachysubid`),
  KEY `userid` (`userid`),
  CONSTRAINT `hierachy_userid_fk` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hierachy_applications`
--

DROP TABLE IF EXISTS `hierachy_applications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hierachy_applications` (
  `hierachyid` int(11) NOT NULL,
  `appid` int(11) NOT NULL,
  `updatedon` datetime NOT NULL DEFAULT current_timestamp(),
  KEY `hierachyid` (`hierachyid`),
  KEY `appid` (`appid`),
  CONSTRAINT `hierachy_applications_appid_fk` FOREIGN KEY (`appid`) REFERENCES `applications` (`appid`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `hierachy_applications_hierachyid_fk` FOREIGN KEY (`hierachyid`) REFERENCES `hierachy` (`hierachyid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hierachy_det`
--

DROP TABLE IF EXISTS `hierachy_det`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hierachy_det` (
  `detid` int(11) NOT NULL AUTO_INCREMENT,
  `hierachyid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `hierachytypeid` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `createdon` datetime NOT NULL DEFAULT current_timestamp(),
  `updatedon` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`detid`),
  KEY `hierachyid` (`hierachyid`),
  KEY `userid` (`userid`),
  KEY `hierachytypeid` (`hierachytypeid`),
  CONSTRAINT `hierachy_det_hierachyid_fk` FOREIGN KEY (`hierachyid`) REFERENCES `hierachy` (`hierachyid`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `hierachy_det_hierachytypeid_fk` FOREIGN KEY (`hierachytypeid`) REFERENCES `hierachy_type` (`hierachytypeid`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `hierachy_det_userid_fk` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hierachy_images`
--

DROP TABLE IF EXISTS `hierachy_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hierachy_images` (
  `imageid` int(11) NOT NULL AUTO_INCREMENT,
  `particularid` int(11) NOT NULL,
  `logo` text NOT NULL,
  `name` varchar(255) NOT NULL,
  `width` int(11) NOT NULL DEFAULT 0,
  `height` int(11) NOT NULL DEFAULT 0,
  `size` int(11) NOT NULL,
  PRIMARY KEY (`imageid`),
  KEY `particularid` (`particularid`),
  CONSTRAINT `hierachy_particulars_particularid_fk` FOREIGN KEY (`particularid`) REFERENCES `hierachy_particulars` (`particularid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hierachy_module_roles`
--

DROP TABLE IF EXISTS `hierachy_module_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hierachy_module_roles` (
  `hierachyid` int(11) NOT NULL,
  `roleid` int(11) NOT NULL,
  `moduleid` int(11) NOT NULL,
  `maintainedby` int(11) NOT NULL,
  `createdon` datetime NOT NULL DEFAULT current_timestamp(),
  `updatedon` datetime NOT NULL DEFAULT current_timestamp(),
  UNIQUE KEY `hierachy_module_roles_unique_key` (`hierachyid`,`roleid`,`moduleid`),
  KEY `hierachyid` (`hierachyid`),
  KEY `maintainedby` (`maintainedby`),
  KEY `roleid` (`roleid`),
  KEY `moduleid` (`moduleid`),
  CONSTRAINT `hierachy_module_roles_hierachyid_fk` FOREIGN KEY (`hierachyid`) REFERENCES `hierachy` (`hierachyid`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `hierachy_module_roles_moduleid_fk` FOREIGN KEY (`moduleid`) REFERENCES `application_modules` (`moduleid`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `hierachy_module_roles_roleid_fk` FOREIGN KEY (`roleid`) REFERENCES `hierachy_roles` (`roleid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hierachy_module_users`
--

DROP TABLE IF EXISTS `hierachy_module_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hierachy_module_users` (
  `hierachyid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `moduleid` int(11) NOT NULL,
  `maintainedby` int(11) NOT NULL,
  `include_exclude` int(11) NOT NULL DEFAULT 0,
  `createdon` datetime NOT NULL DEFAULT current_timestamp(),
  `updatedon` datetime NOT NULL DEFAULT current_timestamp(),
  UNIQUE KEY `hierachy_module_users_unique_key` (`hierachyid`,`userid`,`moduleid`),
  KEY `hierachyid` (`hierachyid`),
  KEY `userid` (`userid`),
  KEY `maintainedby` (`maintainedby`),
  KEY `include_exclude` (`include_exclude`),
  KEY `moduleid` (`moduleid`),
  CONSTRAINT `hierachy_module_users_hierachyid_fk` FOREIGN KEY (`hierachyid`) REFERENCES `hierachy` (`hierachyid`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `hierachy_module_users_moduleid_fk` FOREIGN KEY (`moduleid`) REFERENCES `application_modules` (`moduleid`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `hierachy_module_users_userid_fk` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hierachy_particulars`
--

DROP TABLE IF EXISTS `hierachy_particulars`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hierachy_particulars` (
  `particularid` int(11) NOT NULL AUTO_INCREMENT,
  `contactuserid` int(11) NOT NULL,
  `detid` int(11) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` text NOT NULL,
  `createdon` datetime NOT NULL DEFAULT current_timestamp(),
  `updatedon` datetime NOT NULL DEFAULT current_timestamp(),
  `ispostal` int(1) NOT NULL DEFAULT 0,
  `website` varchar(255) DEFAULT NULL,
  `postal` text DEFAULT NULL,
  PRIMARY KEY (`particularid`),
  KEY `detid` (`detid`),
  KEY `contactuserid` (`contactuserid`),
  CONSTRAINT `hierachy_particulars_contactuserid_fk` FOREIGN KEY (`contactuserid`) REFERENCES `users` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `hierachy_particulars_detid_fk` FOREIGN KEY (`detid`) REFERENCES `hierachy_det` (`detid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hierachy_roles`
--

DROP TABLE IF EXISTS `hierachy_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hierachy_roles` (
  `roleid` int(11) NOT NULL AUTO_INCREMENT,
  `hierachyid` int(11) DEFAULT NULL,
  `maintainedby` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `exclude` int(11) DEFAULT 0,
  `createdon` datetime NOT NULL DEFAULT current_timestamp(),
  `updatedon` datetime NOT NULL DEFAULT current_timestamp(),
  `dependants` int(11) NOT NULL DEFAULT 0,
  `view` int(11) NOT NULL DEFAULT 0,
  `create` int(11) NOT NULL DEFAULT 0,
  `edit` int(11) NOT NULL DEFAULT 0,
  `delete` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`roleid`),
  KEY `maintainedby` (`maintainedby`),
  CONSTRAINT `hierachy_roles_maintainedby_fk` FOREIGN KEY (`maintainedby`) REFERENCES `users` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hierachy_type`
--

DROP TABLE IF EXISTS `hierachy_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hierachy_type` (
  `hierachytypeid` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `createdon` datetime NOT NULL DEFAULT current_timestamp(),
  `updatedon` datetime NOT NULL DEFAULT current_timestamp(),
  `hierachyid` int(11) DEFAULT 0,
  `exclude` int(11) DEFAULT 0,
  PRIMARY KEY (`hierachytypeid`),
  KEY `userid` (`userid`),
  CONSTRAINT `hierachy_type_userid_fk` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hierachy_user_roles`
--

DROP TABLE IF EXISTS `hierachy_user_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hierachy_user_roles` (
  `hierachyid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `maintainedby` int(11) NOT NULL,
  `roleid` int(11) NOT NULL,
  `createdon` datetime NOT NULL DEFAULT current_timestamp(),
  `updatedon` datetime NOT NULL DEFAULT current_timestamp(),
  UNIQUE KEY `hierachy_user_roles_unique_key` (`hierachyid`,`userid`,`roleid`),
  KEY `hierachyid` (`hierachyid`),
  KEY `userid` (`userid`),
  KEY `maintainedby` (`maintainedby`),
  KEY `roleid` (`roleid`),
  CONSTRAINT `hierachy_user_roles_hierachyid_fk` FOREIGN KEY (`hierachyid`) REFERENCES `hierachy` (`hierachyid`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `hierachy_user_roles_maintainedby_fk` FOREIGN KEY (`maintainedby`) REFERENCES `users` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `hierachy_user_roles_roleid_fk` FOREIGN KEY (`roleid`) REFERENCES `hierachy_roles` (`roleid`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `hierachy_user_roles_userid_fk` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hierachy_users`
--

DROP TABLE IF EXISTS `hierachy_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hierachy_users` (
  `hierachyid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `maintainedby` int(11) DEFAULT NULL,
  `roleid` int(11) DEFAULT 7,
  `createdon` datetime NOT NULL DEFAULT current_timestamp(),
  `updatedon` datetime NOT NULL DEFAULT current_timestamp(),
  `personnel` int(11) DEFAULT 0,
  `invite` int(11) DEFAULT 0,
  `active` int(11) DEFAULT 1,
  UNIQUE KEY `hierachyid_2` (`hierachyid`,`userid`),
  KEY `hierachyid` (`hierachyid`),
  KEY `userid` (`userid`),
  KEY `maintainedby` (`maintainedby`),
  KEY `roleid` (`roleid`),
  CONSTRAINT `hierachy_users_hierachyid_fk` FOREIGN KEY (`hierachyid`) REFERENCES `hierachy` (`hierachyid`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `hierachy_users_userid_fk` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `invites`
--

DROP TABLE IF EXISTS `invites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `invites` (
  `inviteid` int(11) NOT NULL AUTO_INCREMENT,
  `hierachyid` int(11) NOT NULL,
  `from_userid` int(11) NOT NULL,
  `to_userid` int(11) NOT NULL,
  `appid` int(11) DEFAULT NULL,
  `email` text DEFAULT NULL,
  `datedon` datetime NOT NULL DEFAULT current_timestamp(),
  `accepted` int(11) DEFAULT 0,
  PRIMARY KEY (`inviteid`),
  KEY `hierachyid` (`hierachyid`),
  KEY `from_userid` (`from_userid`),
  KEY `to_userid` (`to_userid`),
  KEY `appid` (`appid`),
  CONSTRAINT `invites_appid_fk` FOREIGN KEY (`appid`) REFERENCES `applications` (`appid`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `invites_from_userid_fk` FOREIGN KEY (`from_userid`) REFERENCES `users` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `invites_hierachyid_fk` FOREIGN KEY (`hierachyid`) REFERENCES `hierachy` (`hierachyid`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `invites_to_userid_fk` FOREIGN KEY (`to_userid`) REFERENCES `users` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `journal`
--

DROP TABLE IF EXISTS `journal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `journal` (
  `journalid` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `datedon` datetime NOT NULL,
  `deleted` int(11) NOT NULL DEFAULT 0,
  `section1` text DEFAULT NULL,
  `section2` text DEFAULT NULL,
  `section3` text DEFAULT NULL,
  `section4` text DEFAULT NULL,
  `section5` text DEFAULT NULL,
  `section6` text DEFAULT NULL,
  `section7` text DEFAULT NULL,
  `section8` text DEFAULT NULL,
  `section9` text DEFAULT NULL,
  PRIMARY KEY (`journalid`),
  KEY `userid` (`userid`),
  CONSTRAINT `journal_userid_fk` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1004 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `journal_request`
--

DROP TABLE IF EXISTS `journal_request`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `journal_request` (
  `requestid` int(11) NOT NULL AUTO_INCREMENT,
  `datedon` datetime NOT NULL DEFAULT current_timestamp(),
  `my_userid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  PRIMARY KEY (`requestid`),
  KEY `userid` (`userid`),
  KEY `my_userid` (`my_userid`),
  CONSTRAINT `journal_request_my_userid_fk` FOREIGN KEY (`my_userid`) REFERENCES `users` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `journal_request_userid_fk` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `journal_section`
--

DROP TABLE IF EXISTS `journal_section`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `journal_section` (
  `sectionid` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `datedon` datetime NOT NULL,
  `title` varchar(50) NOT NULL,
  `orderby` int(11) NOT NULL,
  `active` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`sectionid`),
  KEY `userid` (`userid`),
  CONSTRAINT `journal_section_userid_fk` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `journal_sections`
--

DROP TABLE IF EXISTS `journal_sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `journal_sections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `journalid` int(11) NOT NULL,
  `sectionid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `journalid` (`journalid`),
  KEY `sectionid` (`sectionid`),
  CONSTRAINT `journal_sections_journalid_fk` FOREIGN KEY (`journalid`) REFERENCES `journal` (`journalid`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `journal_sections_sectionid_fk` FOREIGN KEY (`sectionid`) REFERENCES `journal_section` (`sectionid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `journal_shared`
--

DROP TABLE IF EXISTS `journal_shared`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `journal_shared` (
  `sharedid` int(11) NOT NULL AUTO_INCREMENT,
  `datedon` datetime NOT NULL DEFAULT current_timestamp(),
  `my_userid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  PRIMARY KEY (`sharedid`),
  KEY `userid` (`userid`),
  KEY `my_userid` (`my_userid`),
  CONSTRAINT `journal_shared_my_userid_fk` FOREIGN KEY (`my_userid`) REFERENCES `users` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `journal_shared_userid_fk` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `journal_sharing`
--

DROP TABLE IF EXISTS `journal_sharing`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `journal_sharing` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `sharing` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `sharing` (`sharing`),
  CONSTRAINT `journal_sharing_userid_fk` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `journal_user`
--

DROP TABLE IF EXISTS `journal_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `journal_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `journalid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `journalid` (`journalid`),
  CONSTRAINT `journal_user_journalid_fk` FOREIGN KEY (`journalid`) REFERENCES `journal` (`journalid`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `journal_user_userid_fk` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `login_users`
--

DROP TABLE IF EXISTS `login_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `login_users` (
  `loginid` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `loggedin` datetime NOT NULL DEFAULT current_timestamp(),
  `loggedout` datetime DEFAULT NULL,
  PRIMARY KEY (`loginid`),
  KEY `userid` (`userid`),
  KEY `ip` (`ip`),
  KEY `loggedin` (`loggedin`),
  KEY `loggedout` (`loggedout`)
) ENGINE=InnoDB AUTO_INCREMENT=2892 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mykeys`
--

DROP TABLE IF EXISTS `mykeys`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mykeys` (
  `keyid` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `keypair` varchar(1000) NOT NULL,
  `publickey` varchar(1000) NOT NULL,
  `secretkey` varchar(1000) NOT NULL,
  `datedon` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`keyid`),
  KEY `userid` (`userid`),
  KEY `keypair` (`keypair`(768)),
  KEY `publickey` (`publickey`(768)),
  KEY `secretkey` (`secretkey`(768)),
  KEY `datedon` (`datedon`)
) ENGINE=InnoDB AUTO_INCREMENT=232 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notifications` (
  `noteid` int(11) NOT NULL AUTO_INCREMENT,
  `hierachyid` int(11) NOT NULL,
  `from_userid` int(11) NOT NULL,
  `to_userid` int(11) NOT NULL,
  `appid` int(11) DEFAULT NULL,
  `request` text DEFAULT NULL,
  `datedon` datetime NOT NULL DEFAULT current_timestamp(),
  `noticed` int(11) DEFAULT NULL,
  PRIMARY KEY (`noteid`),
  KEY `hierachyid` (`hierachyid`),
  KEY `from_userid` (`from_userid`),
  KEY `to_userid` (`to_userid`),
  KEY `appid` (`appid`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `profile_users`
--

DROP TABLE IF EXISTS `profile_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `profile_users` (
  `profileid` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `datedon` datetime DEFAULT current_timestamp(),
  `profile` text DEFAULT NULL,
  PRIMARY KEY (`profileid`),
  KEY `userid` (`userid`),
  CONSTRAINT `profile_users_userid_fk` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `stream`
--

DROP TABLE IF EXISTS `stream`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stream` (
  `streamid` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `datedon` datetime DEFAULT current_timestamp(),
  `nonce` text DEFAULT NULL,
  `mykey` text DEFAULT NULL,
  PRIMARY KEY (`streamid`),
  KEY `userid` (`userid`),
  KEY `nonce` (`nonce`(768)),
  KEY `mykey` (`mykey`(768)),
  KEY `datedon` (`datedon`)
) ENGINE=InnoDB AUTO_INCREMENT=4243 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `userid` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `contactnu` varchar(255) NOT NULL,
  `passkey` blob DEFAULT NULL,
  `popi_compliance` int(11) NOT NULL DEFAULT 0,
  `active` int(11) NOT NULL DEFAULT 1,
  `system_core` int(11) NOT NULL DEFAULT 0,
  `createdon` datetime NOT NULL DEFAULT current_timestamp(),
  `modifiedon` datetime NOT NULL DEFAULT current_timestamp(),
  `deleted` int(11) DEFAULT NULL,
  `hierachyid` int(11) DEFAULT NULL,
  PRIMARY KEY (`userid`),
  UNIQUE KEY `email` (`email`),
  KEY `hierachyid` (`hierachyid`),
  CONSTRAINT `users_hierachyid_fk` FOREIGN KEY (`hierachyid`) REFERENCES `hierachy` (`hierachyid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users_forgot_password`
--

DROP TABLE IF EXISTS `users_forgot_password`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_forgot_password` (
  `forgotid` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `datedon` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`forgotid`),
  KEY `userid` (`userid`),
  CONSTRAINT `users_forgot_password_userid_fk` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping events for database 'api'
--

--
-- Dumping routines for database 'api'
--
/*!50003 DROP PROCEDURE IF EXISTS `check_login_user` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`api`@`localhost` PROCEDURE `check_login_user`(IN `minute_limit` INT)
BEGIN

  update login_users as ul
    join (select max(datedon) as datedon, userid from stream group by userid) as k
      on ul.userid = k.userid
     set ul.loggedout = k.datedon
   where truncate((UNIX_TIMESTAMP(now())-UNIX_TIMESTAMP(k.datedon))/60, 0) > minute_limit
     and ul.loggedout is null;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `GetHierachy` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`api`@`localhost` PROCEDURE `GetHierachy`(IN `searchid` INT, `query` INT)
BEGIN
	declare temphierachyid INTEGER DEFAULT 0;
	declare temphierachysubid INTEGER DEFAULT 0;
	declare tempid INTEGER DEFAULT 0;
	declare conditionvar  INTEGER default 1;
    declare finished INTEGER DEFAULT 0;
    declare levelid INTEGER default 0;
  
	declare curhierachy 
		CURSOR FOR 
			SELECT hu.hierachyid, h.hierachysubid 
        FROM hierachy_users hu
        JOIN hierachy h
          ON hu.hierachyid = h.hierachyid
       where hu.userid = searchid order by hu.hierachyid DESC;

	declare CONTINUE HANDLER 
        FOR NOT FOUND SET finished = 1;

  create temporary table get_hierachy(hierachyid INTEGER, hierachysubid INTEGER);
  create temporary table final_hierachy(hierachyid INTEGER, hierachysubid INTEGER);
 
	OPEN curhierachy;

	gethierachy: LOOP
		FETCH curhierachy INTO temphierachyid, temphierachysubid;
		IF finished = 1 THEN 
			LEAVE gethierachy;
		END IF;
		
      insert into get_hierachy(hierachyid, hierachysubid)values(temphierachyid, temphierachysubid);
       
      set tempid = temphierachysubid;
       
      WHILE conditionvar <> 0 DO

        if tempid = 0 
          then
            SET conditionvar = 0;
          else
        	
				  replace INTO get_hierachy 
			 	   select hierachyid, hierachysubid
				     from hierachy
				    where hierachyid = tempid;	 
				
		          set tempid = (select hierachysubid from hierachy where hierachyid = tempid);
			  end if;
       
 	    END WHILE;

	END LOOP gethierachy;
	CLOSE curhierachy;

  insert into final_hierachy 
  select *
    from get_hierachy 
   group by hierachyid 
   order by hierachysubid;

  drop temporary table get_hierachy;
 
 if query = 1
 then
  select f.hierachyid, f.hierachysubid, hd.name, "default" as logo
    from final_hierachy f
    join hierachy_det hd 
      on f.hierachyid = hd.hierachyid 
    left join hierachy_particulars hp
      on hd.detid = hp.detid
    left join hierachy_images hi
      on hp.particularid = hi.particularid
   order by f.hierachysubid, f.hierachyid;
 end if;
 if query = 2
 then
  select f.hierachyid, f.hierachysubid, h.levelid, hd.name, h.visible
    from final_hierachy f
    join hierachy h
      on f.hierachyid = h.hierachyid 
    join hierachy_det hd 
      on f.hierachyid = hd.hierachyid 
   order by f.hierachysubid, f.hierachyid;
 end if;

  drop temporary table final_hierachy;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `GetHierachyDependants` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`api`@`localhost` PROCEDURE `GetHierachyDependants`(IN `shierachyid` INT, `shierachytypeid` INT)
BEGIN

declare temphierachysubid INTEGER DEFAULT 0;
declare temphierachyid INTEGER DEFAULT 0;
declare conditionvar  INTEGER default 1;
declare rowcount  INTEGER default 1;

  create temporary table temp_hierachy(hierachyid INTEGER, hierachysubid INTEGER, unique(hierachyid, hierachysubid));

  insert into temp_hierachy(hierachyid, hierachysubid)
  select hierachyid, hierachysubid from hierachy where hierachysubid = shierachyid or hierachyid = shierachyid;
  set rowcount = (select ROW_COUNT());
  
gethierachy: WHILE conditionvar <> 0 DO

  if rowcount = 0 
    then
      SET conditionvar = 0;
  else

      insert into temp_hierachy(hierachyid, hierachysubid)
      select hierachyid, hierachysubid from hierachy where hierachysubid in (select distinct hierachyid from temp_hierachy) on duplicate key update temp_hierachy.hierachyid = hierachy.hierachyid;
      set rowcount = (select ROW_COUNT());
        
  end if;
    
END WHILE gethierachy;

 select hd.hierachyid, ht.hierachytypeid, hd.name as org, ht.name as `type` 
  from hierachy_det hd
  join temp_hierachy th
    on hd.hierachyid = th.hierachyid
  join hierachy_type ht 
    on hd.hierachytypeid = ht.hierachytypeid
 where ht.hierachytypeid = case when isnull(shierachytypeid) then ht.hierachytypeid else shierachytypeid end
 order by hd.name ASC;

  drop temporary table temp_hierachy;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `GetHierachyRoleDependants` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`api`@`localhost` PROCEDURE `GetHierachyRoleDependants`(IN `shierachyid` INT, `sroleid` INT)
BEGIN

declare temphierachysubid INTEGER DEFAULT 0;
declare temphierachyid INTEGER DEFAULT 0;
declare conditionvar  INTEGER default 1;
declare rowcount  INTEGER default 1;

  create temporary table temp_hierachy(hierachyid INTEGER, hierachysubid INTEGER, unique(hierachyid, hierachysubid));

  insert into temp_hierachy(hierachyid, hierachysubid)
  select hierachyid, hierachysubid from hierachy where hierachysubid = shierachyid or hierachyid = shierachyid;
  set rowcount = (select ROW_COUNT());
  
gethierachy: WHILE conditionvar <> 0 DO

  if rowcount = 0 
    then
      SET conditionvar = 0;
  else

      insert into temp_hierachy(hierachyid, hierachysubid)
      select hierachyid, hierachysubid from hierachy where hierachysubid in (select distinct hierachyid from temp_hierachy) on duplicate key update temp_hierachy.hierachyid = hierachy.hierachyid;
      set rowcount = (select ROW_COUNT());
        
  end if;
    
END WHILE gethierachy;

 select hd.hierachyid, hr.roleid, hd.name as org, hr.name as `role`, concat(u.firstname, ' ', u.surname) as `user`
  from hierachy_det hd
  join temp_hierachy th
    on hd.hierachyid = th.hierachyid
  join hierachy_users hu
    on hd.hierachyid = hu.hierachyid
  join hierachy_roles hr 
    on hu.roleid = hr.roleid
  join users u
    on hu.userid=u.userid
 where hr.roleid = case when isnull(sroleid) then hr.roleid else sroleid end
 order by hd.name ASC;

  drop temporary table temp_hierachy;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `GetHierachyRoles` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`api`@`localhost` PROCEDURE `GetHierachyRoles`(IN `searchid` INT)
BEGIN

declare tempid INTEGER DEFAULT 0;
declare temphierachyid INTEGER DEFAULT 0;
declare systemdefault INTEGER DEFAULT 1;
declare visible INTEGER DEFAULT 1;
declare conditionvar  INTEGER default 1;

set tempid = (select hierachysubid from hierachy where hierachyid = `searchid`);
set temphierachyid = (select hierachyid from hierachy where hierachyid = `searchid`);
set visible = (select visible from hierachy where hierachyid = `searchid`);

gethierachy: WHILE conditionvar <> 0 DO

  if visible = 0 
    then
      SET conditionvar = 0;
  elseif tempid = 0 
    then
      SET conditionvar = 0;
    else
        set temphierachyid = (select  hierachyid from hierachy where hierachyid = tempid);
        set visible = (select visible from hierachy where hierachyid = tempid);
        set tempid = (select hierachysubid from hierachy where hierachyid = tempid);
        
  end if;
    
END WHILE gethierachy;

 select hr.hierachyid, hr.roleid, hr.name as `role`, hr.updatedon, hr.maintainedby as userid, 
 		case when hr.maintainedby = 3 then "System Default" else concat(u.firstname, " ", u.surname) end as `user`, 
 		hr.`exclude`, hr.dependants, hr.`view`, hr.`create`, hr.`edit`, hr.`delete`
  from hierachy_roles hr 
  join users u 
    on hr.maintainedby = u.userid
 where hr.hierachyid = 0
    or hr.hierachyid = temphierachyid
 order by hr.name ASC;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `GetHierachyTypes` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`api`@`localhost` PROCEDURE `GetHierachyTypes`(IN `searchid` INT)
BEGIN

declare tempid INTEGER DEFAULT 0;
declare temphierachyid INTEGER DEFAULT 0;
declare systemdefault INTEGER DEFAULT 1;
declare visible INTEGER DEFAULT 1;
declare conditionvar  INTEGER default 1;

set tempid = (select hierachysubid from hierachy where hierachyid = `searchid`);
set temphierachyid = (select hierachyid from hierachy where hierachyid = `searchid`);
set visible = (select visible from hierachy where hierachyid = `searchid`);

gethierachy: WHILE conditionvar <> 0 DO

  if visible = 0 
    then
      SET conditionvar = 0;
  elseif tempid = 0 
    then
      SET conditionvar = 0;
    else
        set temphierachyid = (select hierachyid from hierachy where hierachyid = tempid);
        set visible = (select visible from hierachy where hierachyid = tempid);
        set tempid = (select hierachysubid from hierachy where hierachyid = tempid);
        
  end if;
    
END WHILE gethierachy;

 select ht.hierachyid, ht.hierachytypeid, ht.name as type, ht.updatedon, ht.userid, case when ht.userid = 3 then "System Default" else concat(u.firstname, " ", u.surname) end as user, visible, exclude
  from hierachy_type ht 
  join users u 
    on ht.userid = u.userid
 where ht.hierachyid = temphierachyid
    or ht.hierachyid = 0
 order by ht.userid DESC, ht.name ASC;

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

-- Dump completed on 2022-08-19 13:46:09
