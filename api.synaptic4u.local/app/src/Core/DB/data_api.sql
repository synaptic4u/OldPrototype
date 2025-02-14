-- MariaDB dump 10.19  Distrib 10.6.9-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: api
-- ------------------------------------------------------
-- Server version	10.6.9-MariaDB-1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Dumping data for table `application_modules`
--

LOCK TABLES `application_modules` WRITE;
/*!40000 ALTER TABLE `application_modules` DISABLE KEYS */;
INSERT INTO `application_modules` VALUES (1,1,'Admin Dashboard','AdminDash','load',NULL,'2021-07-10 00:06:39','2021-07-10 00:06:39',1,1,1,1),(2,1,'Modulator Dashboard','ModulatorDash','load',NULL,'2021-07-10 00:06:39','2021-07-10 00:06:39',1,1,1,1),(3,1,'Dashboard','Dashboard','load',NULL,'2021-07-10 00:06:39','2021-07-10 00:06:39',1,1,1,1),(4,1,'My Journal','JournalList','load',NULL,'2021-07-10 00:06:39','2021-07-10 00:06:39',1,1,1,0),(5,1,'Shared Journals','JournalList','loadShared',NULL,'2021-07-10 00:06:39','2021-07-10 00:06:39',1,1,1,1),(6,1,'Daily Checklist','Checklist','show',NULL,'2021-07-10 00:06:39','2021-07-10 00:06:39',1,1,1,1),(7,1,'Journal Configuration','ConfigJournal','loadlist',3,'2021-07-10 00:06:39','2021-07-10 00:06:39',1,1,1,1),(8,1,'Notifications','ConfigNotifications','loadRequests',3,'2021-07-10 00:06:39','2021-07-10 00:06:39',1,0,0,0),(9,1,'Messages','ConfigMessages','loadMessages',3,'2021-07-10 00:06:39','2021-07-10 00:06:39',1,1,1,1),(10,1,'Sharing','ConfigSharing','loadShareable',3,'2021-07-10 00:06:39','2021-07-10 00:06:39',1,1,1,1),(16,2,'Organization Builder','Hierachy','load',NULL,'2022-04-06 15:56:14','2022-04-06 15:56:14',1,1,1,1);
/*!40000 ALTER TABLE `application_modules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `application_users`
--

LOCK TABLES `application_users` WRITE;
/*!40000 ALTER TABLE `application_users` DISABLE KEYS */;
INSERT INTO `application_users` VALUES (1,7);
/*!40000 ALTER TABLE `application_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `applications`
--

LOCK TABLES `applications` WRITE;
/*!40000 ALTER TABLE `applications` DISABLE KEYS */;
INSERT INTO `applications` VALUES (1,'Journal','2021-07-07 20:02:23','2021-07-07 20:02:23','A journaling application for the group or individual. As a group you can share your journal and checklist to members of the same group or invite by email. All journal are securely encrypted in storage.',0),(2,'Hierachy','2021-12-03 16:21:19','2021-12-03 16:21:19','The hierachy application package is a default system package that manages all settings for your organizational hierachy structure.',1);
/*!40000 ALTER TABLE `applications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `checklist`
--

LOCK TABLES `checklist` WRITE;
/*!40000 ALTER TABLE `checklist` DISABLE KEYS */;
INSERT INTO `checklist` VALUES (2,7,'2021-07-16 19:04:22',0,'Values','Routines');
/*!40000 ALTER TABLE `checklist` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `confirm_login_users`
--

LOCK TABLES `confirm_login_users` WRITE;
/*!40000 ALTER TABLE `confirm_login_users` DISABLE KEYS */;
INSERT INTO `confirm_login_users` VALUES (
    55,7,'2021-07-03 00:22:49','af9312412ab17b47a06bac15b323332d8a120b547b71ed32','e570ffa4639227475830fc232b4f0cb6aceeb7bb45ba4c11b79b72d15b77be5d','f051bc86aa9775e5df32646f780898c9bd15f80c70f352a06759b8627d889c3d9842404159de60e81ed128d518bb0a8fbc6fd313ca447e0312a4648f8f879863a52a0cb7dc891f8a60ca1c4f7e74674342826b2792e35acbfc04e1c9d57f65fc'),(73,7,'2021-07-04 02:47:50','7e5411407d6c17830931c3632c70e3529750305a4ebcfa72','a63b2ab4b5fffbba7807740033b3f4f8ab8113124827b3414a7eceb473a1cc57','6c1793d9ebc56a5adc0c8f78407a17bcb3ec3b1f44517e37f56082583a32545c1e097dc054a86c8ac4c7e1b4b8c24c9e7fe20c9d7d2ec8e574680f0e310fd1fbedfd29bba85cfe7dd601cf29d325e935ebebc10d19fc11fb80cbc95d8a42'),(75,7,'2021-07-04 02:54:46','3ee312a22326d41626b98e1e8c2b81215d49b565f0df0857','5e8a76bdda91767b7945e41c63f055bf82a753e62f8d07097a72c1c4fb061e3b','b658de6a3b03299ae250fdcb814733b52981074722a2917de7ba6ff43f69c17d491d3dbca1d9ffc3b7fa206c1c8ad020747e37fe769c9955402d5a310b780aad22d78b5bb46a3dc12cc1cc8f5d4cc30e71164144d0b20b81f5b7a400d948599a'),(76,7,'2021-07-04 02:55:20','de6b7e3008cc28e5b465017e6e30250ffbf6a38958fa657a','c6f60254f36bef3043fdecf7635d79fc784552bf22ca8b8463c551576ba6ab8e','10b54b8a69fbe6fd3af67f31ead989a99dca52ae86924c1eb04d016d0fb5ef871dcb41ece4ba30fa7b6e16a736771e33b55dd58f88000e479a7e7ba3c24bac14e324cd00bdcbcb59e0f9a9fddfbc31cece006786bf14e04c5e8c6776b87764'),(77,7,'2021-07-04 02:56:36','4f7a0fff63cb3f2443926cbf2d71eb5238853ed50c2dce16','7dd0c2f24111742b64b5c5f5d440e39ec654b79ec7c3b04f4640a2e2365fe29b','3b506b07a7778292923c1852e96165cba436224f008832375f8cf97f42a0818caa1e8252f5bcc521c4af9cfb52215b64d53f1eb1f3b60c2e09f040f6fd6f8facd9aa4b9948b5adb857229062a79613d28180d2cc3b86d1f639950db67549ec79'),(89,7,'2021-07-04 03:39:58','6a5014221c3f27452fbc0c03644c94858afa7500c31e2c53','7da3912e149d3274c5cec0a5cfad35fedf6bd6bb7f732c5c1bafd3c8cf5ce2c3','6809edb9b87f866ec757acc04893a0bf3e8a6afa3c76dcfb82553cbaa3622c044e6fa4c73b7756eafe6a7b627b4fb2f1b8d2988688fa2a139f3bc084a166b49bcd82e1edb67a2116d3870ef2219a91211b859434ecab3d676d13455bb4e8a54d'),(100,7,'2021-07-04 19:07:38','13bb0318e91134fb7f187f0793124f40fe89c4e5312b94f5','b21aeb3c6cba7cc5b706c854185b243bb5d76dd77287602ecf1ae464077ab60c','392f7d265fd838c8e1428a97fc6dca7c3f839c6cea7fc3884151532373d96a8d2e11b4622c659d132907b5615e1c661c343c87553aecbce5c0f7d353d3eded3272550bed1dd3579993042b2c82c9c18164001cdf6e1190fa8974e39615'),(106,7,'2021-07-04 19:54:34','cd51d2ac22c7b453d2b5388b0735c80fe94f46b0baf66c18','96edc42a0abb6560aa2b1b8cefc28b31236ea46e35826dd0e0d9c43bdfefd041','0ecbca29bb27e23ba9ce7692f705d55d5b87836ecbec7d93e16a61eb04f12f155015e8bc0f6b77c94af79df389fe446753e2127ae2fbb4bec2493235a426beb79ee152558e412646d2a98a7111b7879eeb3a99876f564c79e8a3a3514941bae9'),(110,7,'2021-07-05 16:48:59','c37758cf9c58b959c0c11debed3e10497dedc6289847b9f6','1eb5ac51cb1059c07724696c396ffc1d238f184d2c3634cbc0baf0c0a35c8c77','debe8ce3c5fea310a7860a8d0c7563b22fd979728b3486f9a619053435eb75c84cd65e186f1f797f05ccd35f598a104c9409dd1debbe620bc80f306054567d1fd96deb6d0af6811b655ff80504181f0db323cc7e7f4f03d9c2d34f8387ab375f'),(113,7,'2021-07-05 16:56:51','8a60a7ac5b223120c59e5281ccd6a948724fca60b4206715','90a50bc6893ab1118a7e26526338c3ee6abbd621107cb21853344e81ffa1351a','5da0763604276c6855cead39436e4ae33a628d87f70e1a529c0b89642eaeaf2c3e4f6068f916c427973052439e8fa21dc3a658082c3e2e5f0490f3348c451f8ebdc876421fd8476be70048e2b5e8742a9d6110c69921f2ee264e788a40e9b586'),(115,7,'2021-07-05 17:02:36','6dbd983eea9ec6b32c1fc4a5ebd1bcad59cdf3e78bb62a26','225815ac99972ebac056203c48c325207673b9780d4e53807814355e40dbcfb4','9642a6cacfe4983262041287ec66631bdf17b38312bcceeab2c61bf8d879c3d394769dddc930bc662cb9e0605c1dab2f59c99b2291a6d5cca38d05f066f7ff8ef2f049e25bf508a482b26b81d9382578aff7a52926e43fd4121135751f8c00a6'),(116,7,'2021-07-05 17:03:23','2fa7a92753ce7b1ed795ead0a81030ca9a76668b007a07ad','c33eb0065434d5cf89f7f0a77fb6c5ce56bc8f38211b227ee64754e4992dc712','be513df20749f4c634636ffca4d9728510e1859d4d2ad291f16319a131b4219555919801dd8a17e95dabd23beaf789260fe080bc6d0085ffd6e6b0ceaddeedb4c1e2cdee16aeda3eeefa67d4be9dd719c63eb8d88bbd47528377bfc426dc793b'),(117,7,'2021-07-05 17:03:52','43f9169d4f74aa1efbdddd132417feb540fc139ed3a91427','e20dacf9439d932cb1acc725d72abbdb41b2db5642201e59a37d09a99bb9edcb','3595f62aa823a1ce4b5bd0d974857695f358f55d5fe661e02e9e08988d2a83225220784d646c8da1b2301402b0a142f6c870c61459adc44ecb910d82d5b41a259d32739b00ba6f20939159f8ec56a65bfb33011247bd269f4c7c582a2735'),(120,7,'2021-07-06 17:35:04','a4d541fa3aed7022312d80fb5ffc6bc47707d38725ab3b04','decdeea4ce39f45712feb2e3aa4fbe8b1a3cd3ecb6147aa91eb3181c8c290d3e','b8a16d4f8590597ccf016041b9f67dd4854e3009fc0641737b1754dd857a7eb885ca5c16157ca2220cd7f5961b8770ba87bbf45d0cc33dbbde71e4ae0b036771b8aa8d4644136ffca9319afa1c510d9f59bed6c2a50d7457f06d6bfade6d4a');
/*!40000 ALTER TABLE `confirm_login_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `confirm_register_users`
--

LOCK TABLES `confirm_register_users` WRITE;
/*!40000 ALTER TABLE `confirm_register_users` DISABLE KEYS */;
/*!40000 ALTER TABLE `confirm_register_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `hierachy`
--

LOCK TABLES `hierachy` WRITE;
/*!40000 ALTER TABLE `hierachy` DISABLE KEYS */;
INSERT INTO `hierachy` VALUES (40,0,7,'2021-07-30 18:09:41','2021-07-30 18:09:41',1,1),(42,0,7,'2021-07-30 18:15:24','2021-07-30 18:15:24',1,1),(43,40,7,'2021-07-30 18:17:23','2021-07-30 18:17:23',1,0),(44,42,7,'2021-07-30 18:18:29','2021-07-30 18:18:29',1,0),(45,42,7,'2021-07-30 18:19:25','2021-07-30 18:19:25',1,1),(46,40,7,'2021-07-30 18:20:19','2021-07-30 18:20:19',1,0),(47,42,7,'2021-07-31 12:31:57','2021-07-31 12:31:57',1,0),(56,42,7,'2021-08-04 16:49:30','2021-08-04 16:49:30',1,0),(58,40,7,'2021-08-05 12:07:15','2021-08-05 12:07:15',1,1),(62,58,7,'2021-08-05 13:28:46','2021-08-05 13:28:46',1,0),(65,0,7,'2021-08-05 14:35:25','2021-08-05 14:35:25',1,1),(66,58,7,'2021-08-05 14:38:23','2021-08-05 14:38:23',1,0),(67,58,7,'2021-08-05 15:14:08','2021-08-05 15:14:08',1,0),(68,65,7,'2021-08-13 22:24:42','2021-08-13 22:24:42',1,0),(69,45,7,'2021-08-16 15:06:25','2021-08-16 15:06:25',1,0),(70,45,7,'2021-08-16 15:06:57','2021-08-16 15:06:57',1,0),(71,40,7,'2021-12-08 19:36:40','2021-12-08 19:36:40',1,0),(72,65,7,'2022-01-17 15:42:19','2022-01-17 15:42:19',1,0),(73,0,7,'2022-06-12 13:48:54','2022-06-12 13:48:54',1,1),(74,73,7,'2022-06-12 19:22:34','2022-06-12 19:22:34',1,0),(75,45,7,'2022-07-08 13:57:05','2022-07-08 13:57:05',1,0);
/*!40000 ALTER TABLE `hierachy` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `hierachy_applications`
--

LOCK TABLES `hierachy_applications` WRITE;
/*!40000 ALTER TABLE `hierachy_applications` DISABLE KEYS */;
INSERT INTO `hierachy_applications` VALUES (40,2,'2022-03-01 20:44:21'),(40,1,'2022-03-01 21:07:51'),(42,1,'2022-06-13 16:13:17');
/*!40000 ALTER TABLE `hierachy_applications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `hierachy_det`
--

LOCK TABLES `hierachy_det` WRITE;
/*!40000 ALTER TABLE `hierachy_det` DISABLE KEYS */;
INSERT INTO `hierachy_det` VALUES (23,40,7,1,'Synaptic4U','Synaptic4U is a holding company! :)','2021-07-30 18:09:41','2021-07-30 18:09:41'),(25,42,7,5,'Emile Journal Group','Emile Journal Group.~~:newline:~~Emile Journal Group.~~:newline:~~Emile Journal Group.','2021-07-30 18:15:24','2021-07-30 18:15:24'),(26,43,7,4,'Software Architecture','Software Architecture Department.','2021-07-30 18:17:23','2021-07-30 18:17:23'),(27,44,7,5,'N.A. Sponsorship Group','N.A. Sponsorship Group.','2021-07-30 18:18:29','2021-07-30 18:18:29'),(28,45,7,5,'Beth Rapha Sponsorship Group','Beth Rapha Sponsorship Group.','2021-07-30 18:19:25','2021-07-30 18:19:25'),(29,46,7,4,'Business Intelligence','Business Intelligence Department','2021-07-30 18:20:19','2021-07-30 18:20:19'),(30,47,7,5,'Family Journal Group','Family Journal Group.~~:newline:~~Family Journal Group.~~:newline:~~Family Journal Group.','2021-07-31 12:31:58','2021-07-31 12:31:58'),(34,56,7,3,'To be deleted','To be deleted.~~:newline:~~To be deleted.~~:newline:~~To be deleted.','2021-08-04 16:49:30','2021-08-04 16:49:30'),(36,58,7,3,'Research & Development','Research & Development~~:newline:~~Research & Development~~:newline:~~Research & Development','2021-08-05 12:07:15','2021-08-05 12:07:15'),(40,62,7,4,'Research','','2021-08-05 13:28:46','2021-08-05 13:28:46'),(43,65,7,3,'Insights','Insights.Insights.~~:newline:~~Insights.~~:newline:~~Insights.Insights.','2021-08-05 14:35:25','2021-08-05 14:35:25'),(44,66,7,3,'Development','Development of all software!~~:newline:~~','2021-08-05 14:38:23','2021-08-05 14:38:23'),(45,67,7,5,'Focus','Focus.Focus.Focus.~~:newline:~~Focus.~~:newline:~~Focus.Focus.','2021-08-05 15:14:08','2021-08-05 15:14:08'),(46,68,7,3,'DeepThink','DeepThink~~:newline:~~DeepThinkDeepThinkDeepThink','2021-08-13 22:24:42','2021-08-13 22:24:42'),(47,69,7,5,'Group 1','Group 1','2021-08-16 15:06:25','2021-08-16 15:06:25'),(48,70,7,5,'Group 2','Group 2','2021-08-16 15:06:57','2021-08-16 15:06:57'),(49,71,7,5,'Employee health','Employee health group.','2021-12-08 19:36:40','2021-12-08 19:36:40'),(50,72,7,4,'Deep','Deep will be the new machine learning platform.','2022-01-17 15:42:19','2022-01-17 15:42:19'),(51,73,7,27,'TEST','TEST ORG','2022-06-12 13:48:54','2022-06-12 13:48:54'),(52,74,7,3,'SUB TEST','Sub test','2022-06-12 19:22:34','2022-06-12 19:22:34'),(53,75,7,5,'Group 3','Test group','2022-07-08 13:57:05','2022-07-08 13:57:05');
/*!40000 ALTER TABLE `hierachy_det` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `hierachy_images`
--


--
-- Dumping data for table `hierachy_module_roles`
--

LOCK TABLES `hierachy_module_roles` WRITE;
/*!40000 ALTER TABLE `hierachy_module_roles` DISABLE KEYS */;
INSERT INTO `hierachy_module_roles` VALUES (40,8,1,7,'2022-05-09 19:15:22','2022-05-09 19:15:22'),(40,8,4,7,'2022-01-19 14:29:40','2022-01-19 14:29:40'),(40,9,1,7,'2022-01-19 14:28:59','2022-01-19 14:28:59'),(40,9,2,7,'2022-01-19 14:29:11','2022-01-19 14:29:11'),(40,9,5,7,'2022-03-16 16:34:50','2022-03-16 16:34:50'),(40,9,6,7,'2022-03-16 16:35:12','2022-03-16 16:35:12'),(40,9,7,7,'2022-03-16 16:35:31','2022-03-16 16:35:31'),(40,9,8,7,'2022-03-16 16:35:52','2022-03-16 16:35:52'),(40,9,9,7,'2022-03-16 16:36:11','2022-03-16 16:36:11'),(40,9,10,7,'2022-03-16 16:36:43','2022-03-16 16:36:43'),(40,11,1,7,'2022-01-20 21:34:09','2022-01-20 21:34:09'),(40,11,2,7,'2022-05-09 23:10:28','2022-05-09 23:10:28'),(40,11,3,7,'2022-01-19 14:29:22','2022-01-19 14:29:22'),(40,11,4,7,'2022-01-19 14:29:49','2022-01-19 14:29:49'),(40,11,5,7,'2022-01-19 14:30:01','2022-01-19 14:30:01'),(40,11,6,7,'2022-01-19 14:30:06','2022-01-19 14:30:06'),(40,11,7,7,'2022-01-19 14:30:11','2022-01-19 14:30:11'),(40,11,8,7,'2022-01-19 14:30:16','2022-01-19 14:30:16'),(40,11,9,7,'2022-01-19 14:30:24','2022-01-19 14:30:24'),(40,11,10,7,'2022-01-19 14:30:30','2022-01-19 14:30:30');
/*!40000 ALTER TABLE `hierachy_module_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `hierachy_module_users`
--

LOCK TABLES `hierachy_module_users` WRITE;
/*!40000 ALTER TABLE `hierachy_module_users` DISABLE KEYS */;
INSERT INTO `hierachy_module_users` VALUES (40,7,2,7,0,'2022-01-21 16:54:09','2022-01-21 16:54:09'),(40,7,3,7,0,'2022-03-16 16:33:58','2022-03-16 16:33:58'),(40,7,4,7,0,'2022-03-16 16:34:15','2022-03-16 16:34:15'),(40,7,5,7,0,'2022-03-16 16:34:36','2022-03-16 16:34:36'),(40,7,6,7,0,'2022-03-16 16:35:15','2022-03-16 16:35:15'),(40,7,7,7,0,'2022-03-16 16:35:36','2022-03-16 16:35:36'),(40,7,8,7,0,'2022-03-16 16:35:57','2022-03-16 16:35:57'),(40,7,9,7,0,'2022-03-16 16:36:15','2022-03-16 16:36:15'),(40,7,10,7,0,'2022-03-16 16:36:34','2022-03-16 16:36:34');
/*!40000 ALTER TABLE `hierachy_module_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `hierachy_particulars`
--

LOCK TABLES `hierachy_particulars` WRITE;
/*!40000 ALTER TABLE `hierachy_particulars` DISABLE KEYS */;
INSERT INTO `hierachy_particulars` VALUES (2,7,23,'0632375654','33 First Crescant__:comma:__ Fish Hoek__:comma:__ Cape Town','2021-08-11 11:11:21','2021-08-11 11:11:21',1,'synaptic4u.co.za','aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa'),(4,7,25,'aaaaaaaaaaaaaaaaaaa','aaaaaaaaaaaaaaaaaaaa','2021-08-11 11:52:05','2021-08-11 11:52:05',1,'aaaaaaaaaaaaaaaaaaaaaaa',''),(5,7,43,'wwwwwwwwwwwwwwwwwwww','wwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwww','2021-08-11 12:12:17','2021-08-11 12:12:17',0,'wwwwwwwwwwwwww','wwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwww'),(6,7,29,'aqaqaqaqaqaqaq','aqaqaqaqaqaqaq','2021-08-11 15:17:19','2021-08-11 15:17:19',1,'aqaqaqaqaqaqaq',''),(7,7,26,'rrrrrrrrrrrrrrrrrrrrrrrr','rrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr','2021-08-11 15:37:17','2021-08-11 15:37:17',1,'rrrrrrrrrrrrrrrrrr',''),(8,7,44,'ddddddddddddddddd','ddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd','2021-08-11 15:54:06','2021-08-11 15:54:06',1,'dddddddddddddddddddddddddd',''),(9,7,40,'rrrrrrrrrrrrrrrrrrrrrrr','rrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr','2021-08-11 15:59:39','2021-08-11 15:59:39',1,'rrrrrrrrrrrrrrrrrrrrrr',''),(10,7,45,'fffffffffffffffffffffffff','ffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffff','2021-08-11 16:05:08','2021-08-11 16:05:08',1,'fffffffffffffffffff',''),(11,7,34,'tttttttttttttttttttttttt','ttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttt','2021-08-11 16:09:18','2021-08-11 16:09:18',1,'tttttttttttttttttttttttttt',''),(12,7,27,'nnnnnnnnnnnnn','nnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn','2021-08-11 16:18:11','2021-08-11 16:18:11',1,'nnnnnnnnnnnnnnnn',''),(13,7,53,'0632375654','123 Somewhere','2022-07-08 13:58:18','2022-07-08 13:58:18',1,'','');
/*!40000 ALTER TABLE `hierachy_particulars` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `hierachy_roles`
--

LOCK TABLES `hierachy_roles` WRITE;
/*!40000 ALTER TABLE `hierachy_roles` DISABLE KEYS */;
INSERT INTO `hierachy_roles` VALUES (8,0,3,'System Administrator',0,'2021-09-25 21:46:56','2021-09-25 21:46:56',1,1,1,1,1),(9,0,3,'Administrator',0,'2021-09-25 21:47:08','2021-09-29 22:55:24',1,1,1,1,1),(10,0,3,'Manager',0,'2021-09-25 21:47:08','2021-09-25 21:47:08',0,1,0,1,0),(11,0,3,'User',0,'2021-09-25 21:47:08','2021-09-30 22:32:51',0,1,0,0,0),(15,40,7,'Department Supervisor',0,'2021-09-27 21:59:38','2021-12-13 15:52:38',0,0,0,0,0),(16,40,7,'Supervisor',0,'2021-09-27 22:00:28','2021-10-06 21:52:07',0,0,0,0,0),(17,40,7,'Store Manager',0,'2021-09-29 17:19:48','2021-12-08 19:32:50',0,0,0,0,0),(18,40,7,'Staff',0,'2021-10-22 16:52:43','2021-10-22 16:52:43',0,0,0,0,0),(19,40,7,'Personnel',1,'2021-12-13 17:50:11','2022-05-10 11:35:55',0,1,1,1,1);
/*!40000 ALTER TABLE `hierachy_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `hierachy_type`
--

LOCK TABLES `hierachy_type` WRITE;
/*!40000 ALTER TABLE `hierachy_type` DISABLE KEYS */;
INSERT INTO `hierachy_type` VALUES (1,3,'Holding Company','2021-07-23 21:54:06','2021-09-30 23:48:03',0,0),(2,3,'Company','2021-07-23 21:54:06','2021-07-23 21:54:06',0,0),(3,3,'Branches','2021-07-23 21:54:06','2021-07-23 21:54:06',0,0),(4,3,'Department','2021-07-23 21:54:06','2021-07-23 21:54:06',0,0),(5,3,'Group','2021-07-23 21:54:06','2021-07-23 21:54:06',0,0),(24,7,'Parent Org','2021-09-06 20:47:02','2021-10-05 20:30:42',40,0),(27,7,'Corporation','2021-09-07 16:59:51','2021-10-05 20:34:27',40,0);
/*!40000 ALTER TABLE `hierachy_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `hierachy_user_roles`
--

LOCK TABLES `hierachy_user_roles` WRITE;
/*!40000 ALTER TABLE `hierachy_user_roles` DISABLE KEYS */;
/*!40000 ALTER TABLE `hierachy_user_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `hierachy_users`
--

LOCK TABLES `hierachy_users` WRITE;
/*!40000 ALTER TABLE `hierachy_users` DISABLE KEYS */;
INSERT INTO `hierachy_users` VALUES (40,7,7,8,'2021-07-30 18:09:41','2021-12-27 15:03:54',0,1,1),(40,56,7,10,'2021-12-30 13:07:05','2021-12-30 13:07:05',0,1,1),(42,7,7,8,'2021-07-30 18:15:24','2021-11-11 17:44:22',0,0,1),(43,7,7,8,'2021-07-30 18:17:23','2021-11-11 17:44:22',0,0,1),(44,7,7,8,'2021-07-30 18:18:29','2021-11-11 17:44:22',0,0,1),(45,7,7,8,'2021-07-30 18:19:25','2021-11-11 17:44:22',0,0,1),(46,7,7,8,'2021-07-30 18:20:19','2021-11-11 17:44:22',0,0,1),(47,7,7,8,'2021-07-31 12:31:58','2021-11-11 17:44:22',0,0,1),(56,7,7,8,'2021-08-04 16:49:30','2021-11-11 17:44:22',0,0,1),(58,7,7,8,'2021-08-05 12:07:15','2021-11-11 17:44:22',0,0,1),(62,7,7,8,'2021-08-05 13:28:46','2021-11-11 17:44:22',0,0,1),(65,7,7,8,'2021-08-05 14:35:25','2021-11-11 17:44:22',0,0,1),(66,7,7,8,'2021-08-05 14:38:23','2021-11-11 17:44:22',0,0,1),(67,7,7,8,'2021-08-05 15:14:08','2021-11-11 17:44:22',0,0,1),(68,7,7,8,'2021-08-13 22:24:42','2021-11-11 17:44:22',0,0,1),(69,7,7,8,'2021-08-16 15:06:26','2021-11-11 17:44:22',0,0,1),(70,7,7,8,'2021-08-16 15:06:57','2021-11-11 17:44:22',0,0,1),(71,7,7,7,'2021-12-08 19:36:40','2021-12-08 19:36:40',0,0,1),(72,7,7,7,'2022-01-17 15:42:19','2022-01-17 15:42:19',0,0,1),(73,7,NULL,7,'2022-06-12 13:48:54','2022-06-12 13:48:54',0,0,1),(74,7,NULL,7,'2022-06-12 19:22:34','2022-06-12 19:22:34',0,0,1),(75,7,NULL,7,'2022-07-08 13:57:05','2022-07-08 13:57:05',0,0,1);
/*!40000 ALTER TABLE `hierachy_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `invites`
--

LOCK TABLES `invites` WRITE;
/*!40000 ALTER TABLE `invites` DISABLE KEYS */;
INSERT INTO `invites` VALUES (45,40,7,56,NULL,'emileinitdaemon@gmail.com','2021-12-30 13:07:05',0);
/*!40000 ALTER TABLE `invites` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `journal`
--
--
-- Dumping data for table `journal_request`
--

LOCK TABLES `journal_request` WRITE;
/*!40000 ALTER TABLE `journal_request` DISABLE KEYS */;
/*!40000 ALTER TABLE `journal_request` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `journal_section`
--

LOCK TABLES `journal_section` WRITE;
/*!40000 ALTER TABLE `journal_section` DISABLE KEYS */;
INSERT INTO `journal_section` VALUES (1,7,'2021-08-04 18:40:53','Emile',1,1);
/*!40000 ALTER TABLE `journal_section` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `journal_sections`
--

LOCK TABLES `journal_sections` WRITE;
/*!40000 ALTER TABLE `journal_sections` DISABLE KEYS */;
INSERT INTO `journal_sections` VALUES (1,1,1);
/*!40000 ALTER TABLE `journal_sections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `journal_shared`
--

LOCK TABLES `journal_shared` WRITE;
/*!40000 ALTER TABLE `journal_shared` DISABLE KEYS */;
/*!40000 ALTER TABLE `journal_shared` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `journal_sharing`
--

LOCK TABLES `journal_sharing` WRITE;
/*!40000 ALTER TABLE `journal_sharing` DISABLE KEYS */;
/*!40000 ALTER TABLE `journal_sharing` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `journal_user`
--

LOCK TABLES `journal_user` WRITE;
/*!40000 ALTER TABLE `journal_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `journal_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `login_users`
--
-- Dumping data for table `mykeys`
--

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'DB Class','System User','db@synaptic4u.co.za','0632375654','JGFyZ29uMmlkJHY9MTkkbT02NTUzNix0PTIscD0xJDJjUlkvY3hFRUpQSkVjdHJNK2RJMUEkMnpOR0srVzZ5d2NrckhYVE1nN1hYeVpQekpBd01xRld4eWl5TE5hZksrdw~~~~~~~~',0,0,1,'2021-06-30 20:58:56','2021-06-30 20:58:56',NULL,NULL),(2,'Encryption Class','System User','encrypt@synaptic4u.co.za','0632375654','JGFyZ29uMmlkJHY9MTkkbT02NTUzNix0PTIscD0xJDJjUlkvY3hFRUpQSkVjdHJNK2RJMUEkMnpOR0srVzZ5d2NrckhYVE1nN1hYeVpQekpBd01xRld4eWl5TE5hZksrdw~~~~~~~~',0,0,1,'2021-06-30 20:58:56','2021-06-30 20:58:56',NULL,NULL),(3,'App System','System User','system@synaptic4u.co.za','0632375654','JGFyZ29uMmlkJHY9MTkkbT02NTUzNix0PTIscD0xJDJjUlkvY3hFRUpQSkVjdHJNK2RJMUEkMnpOR0srVzZ5d2NrckhYVE1nN1hYeVpQekpBd01xRld4eWl5TE5hZksrdw~~~~~~~~',0,0,1,'2021-06-30 20:58:56','2021-06-30 20:58:56',NULL,NULL),(4,'Flow Diagram','System User','mila@synaptic4u.co.za','0632375654','JGFyZ29uMmlkJHY9MTkkbT02NTUzNix0PTIscD0xJDJjUlkvY3hFRUpQSkVjdHJNK2RJMUEkMnpOR0srVzZ5d2NrckhYVE1nN1hYeVpQekpBd01xRld4eWl5TE5hZksrdw~~~~~~~~',0,0,1,'2021-06-30 20:58:56','2021-06-30 20:58:56',NULL,NULL),(5,'Emile','De Wilde','emile@synaptic4u.co.za','0632375654','JGFyZ29uMmlkJHY9MTkkbT02NTUzNix0PTIscD0xJDJjUlkvY3hFRUpQSkVjdHJNK2RJMUEkMnpOR0srVzZ5d2NrckhYVE1nN1hYeVpQekpBd01xRld4eWl5TE5hZksrdw~~~~~~~~',1,1,1,'2021-06-30 20:58:56','2021-06-30 20:58:56',NULL,NULL),(7,'Emile','De Wilde','emiledewilde2@gmail.com','0632375654','JGFyZ29uMmlkJHY9MTkkbT02NTUzNix0PTIscD0xJGhHK3FST1BmYjF2dXNHc0paZXdyRkEkQUUxSlBQdUpVd2NJelNCazF4V3AvWXU1MHZrM1NMYzBpQ3dDOGc4Mng2MA~~~~~~~~',1,1,0,'2021-07-03 00:09:50','2021-07-03 00:09:50',NULL,40),(56,'Mila','Dew','emileinitdaemon@gmail.com','0632375654','JGFyZ29uMmlkJHY9MTkkbT02NTUzNix0PTIscD0xJERNRms2RlRDQVN6MUg5YWxpTGZUeUEkckFwMDVuTHNUTHMrY2V4Um1ZS3FQdjJhUEdVdlhveG80VnFuU2Z5azZZYw~~~~~~~~',0,0,0,'2021-12-30 13:07:05','2021-12-30 13:07:05',NULL,40),(58,'System','Admin','admin@synaptic4u.co.za','0632375679','JGFyZ29uMmlkJHY9MTkkbT02NTUzNix0PTIscD0xJHRLMHFOa3pVZHplVHFXZUlNeE1YREEka1VQdUkxbzc1b3FMbnRLTXdGTmNVTlE1ZVFSQVo3K1ovb1VtMVZQYlRCSQ~~~~~~~~',1,1,0,'2022-02-18 13:57:24','2022-02-18 13:57:24',NULL,NULL),(59,'Mila','Maximus','connect@synaptic4u.co.za','__plus__27632375654','JGFyZ29uMmlkJHY9MTkkbT02NTUzNix0PTIscD0xJGNpL2Vzc0RER1RmOGxtWlJ3SG9jY0EkY3NTMFVOeUFOUFZ6VXBwdkMzQ1ZRdmhyVzBtVC81Zjd1NnNKdy9LZHIvbw~~~~~~~~',1,1,0,'2022-05-12 11:59:47','2022-05-12 11:59:47',NULL,NULL),(60,'yaya','mila','milame@synaptic4u.co.za','0632375654','JGFyZ29uMmlkJHY9MTkkbT02NTUzNix0PTIscD0xJHJQeVh6RHpsR0l2VkNjUENwbGtNSUEkZ2FGdWx4WXhER1BZWDRNZ0FvamVyR2Y1RVdrOWRxU3ZzRDBLVXd3M2lsVQ~~~~~~~~',1,1,0,'2022-06-12 14:04:45','2022-06-12 14:04:45',NULL,NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `users_forgot_password`
--

LOCK TABLES `users_forgot_password` WRITE;
/*!40000 ALTER TABLE `users_forgot_password` DISABLE KEYS */;
INSERT INTO `users_forgot_password` VALUES (1,7,'2021-12-27 13:25:37'),(2,7,'2021-12-31 16:37:18'),(3,7,'2021-12-31 17:18:25'),(4,7,'2022-01-02 16:53:40'),(5,7,'2022-01-02 17:00:29'),(6,7,'2022-01-02 17:09:08'),(7,7,'2022-01-02 17:21:02'),(8,7,'2022-02-18 10:26:20'),(9,7,'2022-02-18 10:31:48'),(10,7,'2022-02-18 10:34:30'),(11,7,'2022-02-18 10:49:53'),(12,7,'2022-02-18 10:56:12'),(13,7,'2022-02-18 11:02:59'),(14,7,'2022-02-18 11:04:53'),(15,7,'2022-02-18 11:08:54'),(16,7,'2022-02-18 11:28:55'),(17,7,'2022-02-18 11:36:51'),(18,7,'2022-02-18 11:54:39'),(19,7,'2022-02-18 11:57:00'),(20,7,'2022-02-18 11:58:23'),(21,7,'2022-02-18 12:01:02'),(22,7,'2022-02-18 12:37:27'),(23,7,'2022-02-18 12:46:19'),(24,7,'2022-02-18 13:00:43'),(25,56,'2022-02-18 13:39:00'),(26,56,'2022-05-12 11:57:56'),(27,59,'2022-05-12 12:04:32'),(28,59,'2022-05-12 12:17:10'),(29,7,'2022-05-12 12:19:37'),(30,59,'2022-05-12 12:25:17'),(31,7,'2022-06-13 19:42:46');
/*!40000 ALTER TABLE `users_forgot_password` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-09-27 21:01:59
