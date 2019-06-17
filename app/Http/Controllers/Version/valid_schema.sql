-- MySQL dump 10.13  Distrib 5.7.20, for macos10.12 (x86_64)
--
-- Host: localhost    Database: schema104
-- ------------------------------------------------------
-- Server version	5.7.20

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `account`
--

DROP TABLE IF EXISTS `account`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `account` (
  `account_id` int(11) NOT NULL AUTO_INCREMENT,
  `account_number` varchar(64) NOT NULL,
  `account_name` varchar(64) NOT NULL,
  `address_id` int(11) DEFAULT NULL,
  `bill_to` int(11) DEFAULT NULL,
  `ship_to` int(11) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`account_id`),
  KEY `address_id` (`address_id`),
  KEY `bill_to` (`bill_to`),
  KEY `ship_to` (`ship_to`),
  CONSTRAINT `account_ibfk_1` FOREIGN KEY (`address_id`) REFERENCES `address` (`address_id`),
  CONSTRAINT `account_ibfk_2` FOREIGN KEY (`bill_to`) REFERENCES `address` (`address_id`),
  CONSTRAINT `account_ibfk_3` FOREIGN KEY (`ship_to`) REFERENCES `address` (`address_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `account`
--

LOCK TABLES `account` WRITE;
/*!40000 ALTER TABLE `account` DISABLE KEYS */;
INSERT INTO `account` VALUES (1,'92888ad8-90ed-11e8-bee4-913450f3d44e','Default customer',NULL,NULL,NULL,'2018-07-26 16:04:27',NULL,'2018-07-26 16:04:27',NULL);
/*!40000 ALTER TABLE `account` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `accumulator`
--

DROP TABLE IF EXISTS `accumulator`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `accumulator` (
  `accumulator_id` int(11) NOT NULL AUTO_INCREMENT,
  `data` text,
  `type` varchar(45) DEFAULT NULL,
  `storeTime` datetime DEFAULT NULL,
  `processTime` datetime DEFAULT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `destination` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`accumulator_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `accumulator`
--

LOCK TABLES `accumulator` WRITE;
/*!40000 ALTER TABLE `accumulator` DISABLE KEYS */;
/*!40000 ALTER TABLE `accumulator` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `address`
--

DROP TABLE IF EXISTS `address`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `address` (
  `address_id` int(11) NOT NULL AUTO_INCREMENT,
  `address_line_1` varchar(64) NOT NULL,
  `address_line_2` varchar(64) DEFAULT NULL,
  `city` varchar(64) NOT NULL,
  `state` varchar(64) NOT NULL,
  `zip` varchar(32) DEFAULT NULL,
  `province` varchar(64) DEFAULT NULL,
  `country` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`address_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `address`
--

LOCK TABLES `address` WRITE;
/*!40000 ALTER TABLE `address` DISABLE KEYS */;
/*!40000 ALTER TABLE `address` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `allergyintolerance`
--

DROP TABLE IF EXISTS `allergyintolerance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `allergyintolerance` (
  `AllergyIntolerance_id` int(11) NOT NULL AUTO_INCREMENT,
  `onset` datetime DEFAULT NULL,
  `recordedDate` datetime DEFAULT CURRENT_TIMESTAMP,
  `user_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `reporter_id` int(11) DEFAULT NULL,
  `substance` varchar(64) NOT NULL,
  `status` varchar(16) DEFAULT NULL,
  `criticality` varchar(10) DEFAULT NULL,
  `type` varchar(16) DEFAULT NULL,
  `catagory` varchar(16) DEFAULT NULL,
  `lastOccurence` datetime DEFAULT NULL,
  `note` text,
  PRIMARY KEY (`AllergyIntolerance_id`),
  KEY `fk_AllergyIntolerance_user_id` (`user_id`),
  KEY `fk_AllergyIntolerance_patient_id` (`patient_id`),
  CONSTRAINT `fk_AllergyIntolerance_patient_id` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`patient_id`),
  CONSTRAINT `fk_AllergyIntolerance_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `allergyintolerance`
--

LOCK TABLES `allergyintolerance` WRITE;
/*!40000 ALTER TABLE `allergyintolerance` DISABLE KEYS */;
/*!40000 ALTER TABLE `allergyintolerance` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `answer`
--

DROP TABLE IF EXISTS `answer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `answer` (
  `answer_id` int(11) NOT NULL AUTO_INCREMENT,
  `question` varchar(255) NOT NULL,
  `response` varchar(16) NOT NULL,
  `ask` varchar(16) NOT NULL,
  `comment` text,
  `date` datetime NOT NULL,
  `patient_id` int(11) NOT NULL,
  `reviewedby` varchar(45) DEFAULT NULL,
  `locked` varchar(5) NOT NULL DEFAULT 'F',
  `question_id` int(11) DEFAULT NULL,
  `questionnaire_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`answer_id`),
  KEY `patient_id` (`patient_id`),
  KEY `questionnaire_id` (`questionnaire_id`),
  KEY `question_id` (`question_id`),
  CONSTRAINT `answer_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`patient_id`) ON DELETE CASCADE,
  CONSTRAINT `answer_ibfk_2` FOREIGN KEY (`questionnaire_id`) REFERENCES `questionnaire` (`questionnaire_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `answer_ibfk_3` FOREIGN KEY (`question_id`) REFERENCES `question` (`question_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `answer`
--

LOCK TABLES `answer` WRITE;
/*!40000 ALTER TABLE `answer` DISABLE KEYS */;
/*!40000 ALTER TABLE `answer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `antigen`
--

DROP TABLE IF EXISTS `antigen`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `antigen` (
  `antigen_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `compatibility_class_id` int(11) DEFAULT NULL,
  `clinic_part_number` varchar(32) NOT NULL DEFAULT '-',
  `deleted` varchar(45) NOT NULL DEFAULT 'F',
  `extract_id` int(11) DEFAULT NULL,
  `test_order` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`antigen_id`),
  KEY `extract_id` (`extract_id`),
  KEY `compatibility_class_id` (`compatibility_class_id`),
  CONSTRAINT `antigen_compatibility_class_ibfk_1` FOREIGN KEY (`compatibility_class_id`) REFERENCES `compatibility_class` (`compatibility_class_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `antigen_extract_ibfk_1` FOREIGN KEY (`extract_id`) REFERENCES `extract` (`extract_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `antigen`
--

LOCK TABLES `antigen` WRITE;
/*!40000 ALTER TABLE `antigen` DISABLE KEYS */;
/*!40000 ALTER TABLE `antigen` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `api_log`
--

DROP TABLE IF EXISTS `api_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `api_log` (
  `api_log_id` int(11) NOT NULL AUTO_INCREMENT,
  `path` varchar(255) DEFAULT NULL,
  `method` varchar(16) DEFAULT NULL,
  `json_parameters` text,
  `requester_ip` varchar(32) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `response_code` int(11) DEFAULT NULL,
  `timestamp` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`api_log_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `fk_api_log_user_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `api_log`
--

LOCK TABLES `api_log` WRITE;
/*!40000 ALTER TABLE `api_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `api_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `billing_log`
--

DROP TABLE IF EXISTS `billing_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `billing_log` (
  `billing_id` int(11) NOT NULL AUTO_INCREMENT,
  `billing_date` datetime NOT NULL,
  `user_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `provider_id` int(11) NOT NULL,
  `skintest_id` int(11) NOT NULL,
  `event` varchar(128) NOT NULL,
  `billing_code` varchar(32) NOT NULL,
  `diagnosis_code` varchar(32) NOT NULL,
  `num_units` decimal(8,2) NOT NULL,
  PRIMARY KEY (`billing_id`),
  KEY `user_id` (`user_id`),
  KEY `provider_id` (`provider_id`),
  KEY `patient_id` (`patient_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `billing_log`
--

LOCK TABLES `billing_log` WRITE;
/*!40000 ALTER TABLE `billing_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `billing_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `class_incompatibility`
--

DROP TABLE IF EXISTS `class_incompatibility`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `class_incompatibility` (
  `class_id_1` int(11) NOT NULL,
  `class_id_2` int(11) NOT NULL,
  UNIQUE KEY `class_id_1` (`class_id_1`,`class_id_2`),
  CONSTRAINT `fk_antigen_compatibility_class_1` FOREIGN KEY (`class_id_1`) REFERENCES `compatibility_class` (`compatibility_class_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `class_incompatibility`
--

LOCK TABLES `class_incompatibility` WRITE;
/*!40000 ALTER TABLE `class_incompatibility` DISABLE KEYS */;
/*!40000 ALTER TABLE `class_incompatibility` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clinic`
--

DROP TABLE IF EXISTS `clinic`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `clinic` (
  `clinic_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) DEFAULT NULL,
  `name2` varchar(150) DEFAULT NULL,
  `abbreviation` varchar(45) DEFAULT NULL,
  `contact` varchar(45) DEFAULT NULL,
  `addr1` varchar(100) DEFAULT NULL,
  `addr2` varchar(100) DEFAULT NULL,
  `city` varchar(45) DEFAULT NULL,
  `state` varchar(45) DEFAULT NULL,
  `zip` varchar(45) DEFAULT NULL,
  `phone` varchar(45) DEFAULT NULL,
  `fax` varchar(45) DEFAULT NULL,
  `deleted` varchar(45) NOT NULL DEFAULT 'F',
  `province` varchar(45) DEFAULT NULL,
  `country` varchar(45) DEFAULT NULL,
  `phone2` varchar(45) DEFAULT NULL,
  `nonXtract` varchar(45) NOT NULL DEFAULT 'F',
  `external_id` varchar(100) DEFAULT '',
  `account_id` int(11) DEFAULT NULL,
  `address_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`clinic_id`),
  KEY `account_id` (`account_id`),
  KEY `address_id` (`address_id`),
  CONSTRAINT `clinic_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `account` (`account_id`),
  CONSTRAINT `clinic_ibfk_2` FOREIGN KEY (`address_id`) REFERENCES `address` (`address_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clinic`
--

LOCK TABLES `clinic` WRITE;
/*!40000 ALTER TABLE `clinic` DISABLE KEYS */;
INSERT INTO `clinic` VALUES (1,'empty',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'T','','','','F',NULL,1,NULL);
/*!40000 ALTER TABLE `clinic` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `compatibility_class`
--

DROP TABLE IF EXISTS `compatibility_class`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `compatibility_class` (
  `compatibility_class_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`compatibility_class_id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `compatibility_class`
--

LOCK TABLES `compatibility_class` WRITE;
/*!40000 ALTER TABLE `compatibility_class` DISABLE KEYS */;
/*!40000 ALTER TABLE `compatibility_class` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `compound`
--

DROP TABLE IF EXISTS `compound`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `compound` (
  `compound_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `inventory_scan` int(11) DEFAULT NULL,
  `dose_scan` int(11) DEFAULT NULL,
  `compound_note` text,
  `timestamp` timestamp NULL DEFAULT NULL,
  `size` varchar(20) DEFAULT NULL,
  `name` varchar(150) DEFAULT NULL,
  `color` varchar(45) DEFAULT NULL,
  `dilution` varchar(45) DEFAULT NULL,
  `bottleNum` varchar(45) DEFAULT NULL,
  `active` varchar(5) DEFAULT 'F',
  `currVol` decimal(5,2) DEFAULT '0.00',
  `rx_id` int(11) NOT NULL,
  `provider_config_id` int(11) DEFAULT '-1',
  `shipMethod` varchar(20) DEFAULT '',
  `shipWith` varchar(20) DEFAULT '',
  `tracking` varchar(50) DEFAULT '',
  `external_id` varchar(100) DEFAULT '',
  `DIN` varchar(20) DEFAULT '',
  `compound_receipt_id` int(11) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) DEFAULT NULL,
  `treatment_set_id` int(11) NOT NULL,
  PRIMARY KEY (`compound_id`),
  KEY `fk_rx_id_idx` (`rx_id`),
  KEY `fk_compound_compound_receipt_id` (`compound_receipt_id`),
  KEY `treatment_set_id` (`treatment_set_id`),
  CONSTRAINT `compound_ibfk_1` FOREIGN KEY (`treatment_set_id`) REFERENCES `treatment_set` (`treatment_set_id`),
  CONSTRAINT `fk_compound_compound_receipt_id` FOREIGN KEY (`compound_receipt_id`) REFERENCES `compound_receipt` (`compound_receipt_id`),
  CONSTRAINT `fk_rx_id` FOREIGN KEY (`rx_id`) REFERENCES `prescription` (`prescription_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `compound`
--

LOCK TABLES `compound` WRITE;
/*!40000 ALTER TABLE `compound` DISABLE KEYS */;
/*!40000 ALTER TABLE `compound` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER auto_create_treatment_set BEFORE INSERT ON `compound`
    FOR EACH ROW BEGIN
      IF (NEW.treatment_set_id IS NULL) THEN
        insert into treatment_set (patient_id, provider_id, prescription_id, clinic_id, source, created_at, priority) select patient_id, provider_id, prescription_id, clinic_id, 'AUTO', CURRENT_TIMESTAMP, priority from prescription where prescription_id = NEW.rx_id;
          set NEW.treatment_set_id=last_insert_id();
      END IF;
    END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `compound_receipt`
--

DROP TABLE IF EXISTS `compound_receipt`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `compound_receipt` (
  `compound_receipt_id` int(11) NOT NULL AUTO_INCREMENT,
  `supplier` varchar(128) DEFAULT '',
  `external_rx_num` varchar(64) DEFAULT NULL,
  `note` text,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `compName` varchar(100) DEFAULT '',
  PRIMARY KEY (`compound_receipt_id`),
  KEY `fk_compound_receipt_created_by_user` (`created_by`),
  KEY `fk_compound_receipt_updated_by_user` (`updated_by`),
  CONSTRAINT `fk_compound_receipt_created_by_user` FOREIGN KEY (`created_by`) REFERENCES `user` (`user_id`),
  CONSTRAINT `fk_compound_receipt_updated_by_user` FOREIGN KEY (`updated_by`) REFERENCES `user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `compound_receipt`
--

LOCK TABLES `compound_receipt` WRITE;
/*!40000 ALTER TABLE `compound_receipt` DISABLE KEYS */;
/*!40000 ALTER TABLE `compound_receipt` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `config`
--

DROP TABLE IF EXISTS `config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `config` (
  `config_id` int(11) NOT NULL AUTO_INCREMENT,
  `section` varchar(32) NOT NULL,
  `name` varchar(32) NOT NULL,
  `value` text NOT NULL,
  `compname` varchar(100) DEFAULT 'ALL',
  `app` varchar(16) NOT NULL,
  PRIMARY KEY (`config_id`),
  UNIQUE KEY `config_unique` (`section`,`name`,`compname`,`app`)
) ENGINE=InnoDB AUTO_INCREMENT=151 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `config`
--

LOCK TABLES `config` WRITE;
/*!40000 ALTER TABLE `config` DISABLE KEYS */;
INSERT INTO `config` VALUES (1,'hot_text','editInj1','VIAL#: §CR§DOSE: LOCATION: §CR§SIZE OF REACTION: §CR§REACTION START TIME:§CR§RESOLUTION:','ALL','XIS'),(2,'hot_text','editInj2','HIVES','ALL','XIS'),(3,'hot_text','editInj3','','ALL','XIS'),(4,'hot_text','editInj4','HYPOTENSIVE ','ALL','XIS'),(5,'hot_text','editInj5','','ALL','XIS'),(6,'hot_text','editInj6','','ALL','XIS'),(7,'hot_text','inject1','','ALL','XIS'),(8,'hot_text','inject2','','ALL','XIS'),(9,'hot_text','inject3','','ALL','XIS'),(10,'hot_text','inject4','','ALL','XIS'),(11,'hot_text','inject5','','ALL','XIS'),(12,'hot_text','inject6','','ALL','XIS'),(13,'hot_text','injectAdjust1','Patient Late','ALL','XIS'),(14,'hot_text','injectAdjust2','Recent Local','ALL','XIS'),(15,'hot_text','injectAdjust3','Recent Systemic','ALL','XIS'),(16,'hot_text','injectAdjust4','','ALL','XIS'),(17,'hot_text','injectAdjust5','','ALL','XIS'),(18,'hot_text','injectAdjust6','','ALL','XIS'),(19,'hot_text','bottleNote1','','ALL','XPS'),(20,'hot_text','bottleNote2','','ALL','XPS'),(21,'hot_text','bottleNote3','','ALL','XPS'),(22,'hot_text','bottleNote4','','ALL','XPS'),(23,'hot_text','bottleNote5','','ALL','XPS'),(24,'hot_text','bottleNote6','','ALL','XPS'),(25,'hot_text','bottleNote7','','ALL','XPS'),(26,'hot_text','bottleNote8','','ALL','XPS'),(27,'hot_text','bottleNote9','','ALL','XPS'),(28,'hot_text','bottleNote10','','ALL','XPS'),(29,'hot_text','bottleNote11','','ALL','XPS'),(30,'hot_text','bottleNote12','','ALL','XPS'),(31,'hot_text','bottleName1',' SET 1 OF 1','ALL','XPS'),(32,'hot_text','bottleName2','','ALL','XPS'),(33,'hot_text','bottleName3','','ALL','XPS'),(34,'hot_text','bottleName4',' SET 1 OF 2','ALL','XPS'),(35,'hot_text','bottleName5',' SET 2 OF 2','ALL','XPS'),(36,'hot_text','bottleName6','','ALL','XPS'),(37,'hot_text','bottleName7',' SET 1 OF 3','ALL','XPS'),(38,'hot_text','bottleName8',' SET 2 OF 3','ALL','XPS'),(39,'hot_text','bottleName9',' SET 3 OF 3','ALL','XPS'),(40,'hot_text','bottleName10','','ALL','XPS'),(41,'hot_text','bottleName11','','ALL','XPS'),(42,'hot_text','bottleName12','','ALL','XPS'),(43,'hot_text','patientNote1','','ALL','XPS'),(44,'hot_text','patientNote2','','ALL','XPS'),(45,'hot_text','patientNote3','','ALL','XPS'),(46,'hot_text','patientNote4','','ALL','XPS'),(47,'hot_text','patientNote5','','ALL','XPS'),(48,'hot_text','patientNote6','','ALL','XPS'),(49,'hot_text','patientNote7','','ALL','XPS'),(50,'hot_text','patientNote8','','ALL','XPS'),(51,'hot_text','patientNote9','','ALL','XPS'),(52,'hot_text','patientNote10','','ALL','XPS'),(53,'hot_text','patientNote11','','ALL','XPS'),(54,'hot_text','patientNote12','','ALL','XPS'),(55,'boxNames','box1','Systemic','ALL','XIS'),(56,'boxNames','box2','Asthma','ALL','XIS'),(57,'boxNames','box3','Medicare','ALL','XIS'),(58,'lobbyDisplay','slideDuration','5','ALL','Lobby'),(59,'lobbyDisplay','imagesDuration','20','ALL','Lobby'),(60,'lobbyDisplay','dashDuration','120','ALL','Lobby'),(61,'sounds','soundEnabled','1','ALL','XIS'),(62,'prefs','dftExport?','F','ALL','ALL'),(63,'prefs','adtForProvider?','F','ALL','XPS'),(64,'prefs','logDBenabled?','F','ALL','XPS'),(65,'prefs','dftExport?','F','ALL','XPS'),(66,'prefs','vialStartLetter','','ALL','XPS'),(67,'prefs','hrLogEnabled?','F','ALL','XPS'),(68,'prefs','show1stDilENT?','F','ALL','XPS'),(69,'prefs','showTP?','F','ALL','XPS'),(70,'prefs','sizes','5 mL,10 mL,15 mL','ALL','XPS'),(71,'prefs','showShotLoc?','F','ALL','XPS'),(72,'prefs','showConditions?','F','ALL','XPS'),(73,'ui','rx_pdf_default_download','F','ALL','XST'),(74,'ui','rx_pdf_default_upload','F','ALL','XST'),(75,'ui','test_pdf_default_download','F','ALL','XST'),(76,'ui','test_pdf_default_upload','F','ALL','XST'),(77,'ui','test_billing_default_upload','F','ALL','XST'),(78,'emr','source','none','ALL','XST'),(79,'emr','pdf_destination','','ALL','XST'),(80,'emr','dft_destination','','ALL','XST'),(81,'prefs','lockRxDoses?','F','ALL','XST'),(82,'prefs','zeroUntested?','F','ALL','XST'),(83,'ui','sort_extracts_by','test_order','ALL','XST'),(84,'ui','positive_scores_at_top','T','ALL','XST'),(85,'ui','sort_positives_by_score','T','ALL','XST'),(86,'ui','num_keys','30','ALL','XST'),(87,'vial_label_template','-','Zebra ZPL format PRN templates with \"name\" being a number and \"value\" being the template. The highest numbered template will be used.','ALL','XST'),(88,'receiving','enabled','F','ALL','XST'),(89,'sounds','soundEnabled','T','ALL','XST'),(90,'billing','diagnosis_code2','477.1 - Allergic rhinitis due to food','ALL','XST'),(91,'billing','diagnosis_code3','477.2 - Allergic rhinitis due to animal hair and dander','ALL','XST'),(92,'billing','diagnosis_code4','477.8 - Allergic rhinitis due to other allergen','ALL','XST'),(93,'billing','diagnosis_code5','477.9 - Allergic rhinitis, cause unspecified','ALL','XST'),(94,'billing','diagnosis_code6','493.00 - Extrinsic asthma, unspecified','ALL','XST'),(95,'billing','diagnosis_code7','493.01 - Extrinsic asthma, with status asthmaticus','ALL','XST'),(96,'billing','diagnosis_code8','493.02 - Extrinsic asthma, with (acute) exacerbation','ALL','XST'),(97,'billing','diagnosis_code9','493.90 - Unspecified asthma, unspecified','ALL','XST'),(98,'billing','diagnosis_code10','493.91 - Unspecified asthma, with status asthmaticus','ALL','XST'),(99,'billing','diagnosis_code11','493.92 - Unspecified asthma, with (acute) exacerbation','ALL','XST'),(100,'billing','billing_code1','95004 - Percutanteous Skin Test','ALL','XST'),(101,'billing','billing_code2','95024 - Intradermal Skin Test  ','ALL','XST'),(102,'test_status','0','In progress','ALL','XST'),(103,'test_status','99','Completed','ALL','XST'),(104,'supplier','1','Greer','ALL','XST'),(105,'supplier','2','Hollister','ALL','XST'),(106,'supplier','3','ALK','ALL','XST'),(107,'supplier','4','Allergy Labs','ALL','XST'),(110,'ICD','code1','J30.1','ALL','ALL'),(111,'ICD','code1Desc','Allergic rhinitis due to pollen','ALL','ALL'),(112,'ICD','code2','J30.89','ALL','ALL'),(113,'ICD','code2Desc','Other allergic rhinitis','ALL','ALL'),(114,'ICD','code3','J30.81','ALL','ALL'),(115,'ICD','code3Desc','Allergic rhinitis due to animal (cat)(dog) hair and dander','ALL','ALL'),(116,'CPT','code1','95165','ALL','ALL'),(117,'CPT','code1Desc','5 DOSE VIAL','ALL','ALL'),(118,'CPT','code2','95117','ALL','ALL'),(119,'CPT','code2Desc','Two or more injections','ALL','ALL'),(120,'CPT','code3','95115','ALL','ALL'),(121,'CPT','code3Desc','One Injection','ALL','ALL'),(122,'CPT-ICD default','comp','95165§J30.1§§§0','ALL','ALL'),(123,'CPT-ICD default','shot1','95117§J30.1§J30.89§J30.81§1','ALL','ALL'),(124,'CPT-ICD default','shot2','95165§J30.1§§§0','ALL','ALL'),(125,'CPT-ICD default','shot3','95165§J30.1§§§0','ALL','ALL'),(150,'safety','requireScanVial','F','ALL','XIS');
/*!40000 ALTER TABLE `config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `diagnosis`
--

DROP TABLE IF EXISTS `diagnosis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `diagnosis` (
  `diagnosis_id` int(11) NOT NULL AUTO_INCREMENT,
  `formalname` varchar(45) DEFAULT NULL,
  `displayname` varchar(45) DEFAULT NULL,
  `diagDose` float DEFAULT NULL,
  `deleted` varchar(45) NOT NULL DEFAULT 'F',
  `extract_id` int(11) NOT NULL,
  PRIMARY KEY (`diagnosis_id`),
  KEY `fk_diagnosis_extract1_idx` (`extract_id`),
  CONSTRAINT `fk_diagnosis_extract1` FOREIGN KEY (`extract_id`) REFERENCES `extract` (`extract_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `diagnosis`
--

LOCK TABLES `diagnosis` WRITE;
/*!40000 ALTER TABLE `diagnosis` DISABLE KEYS */;
INSERT INTO `diagnosis` VALUES (1,NULL,NULL,-1,'T',1),(2,NULL,'ALLERGIC RHINITIS & ASTHMA',NULL,'T',1),(3,NULL,'ANAPHYLAXIS',NULL,'T',1),(4,NULL,'ALLERGIC RHINITIS',NULL,'T',1);
/*!40000 ALTER TABLE `diagnosis` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doseruledetails`
--

DROP TABLE IF EXISTS `doseruledetails`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `doseruledetails` (
  `doseRuleDetails_id` int(11) NOT NULL AUTO_INCREMENT,
  `start` varchar(20) DEFAULT NULL,
  `end` varchar(20) DEFAULT NULL,
  `reactType` varchar(2) DEFAULT NULL,
  `reactVal` varchar(20) DEFAULT NULL,
  `delta` varchar(20) DEFAULT NULL,
  `oldStyle` text,
  `deleted` varchar(2) DEFAULT NULL,
  `doseRuleNames_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`doseRuleDetails_id`),
  KEY `doseRuleDetails_doseRuleNames_id_idx` (`doseRuleNames_id`),
  CONSTRAINT `fk_doseRuleDetails_doseRuleName_id` FOREIGN KEY (`doseRuleNames_id`) REFERENCES `doserulenames` (`doseRuleNames_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doseruledetails`
--

LOCK TABLES `doseruledetails` WRITE;
/*!40000 ALTER TABLE `doseruledetails` DISABLE KEYS */;
/*!40000 ALTER TABLE `doseruledetails` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doserulenames`
--

DROP TABLE IF EXISTS `doserulenames`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `doserulenames` (
  `doseRuleNames_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) DEFAULT NULL,
  `deleted` char(1) DEFAULT 'F',
  PRIMARY KEY (`doseRuleNames_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doserulenames`
--

LOCK TABLES `doserulenames` WRITE;
/*!40000 ALTER TABLE `doserulenames` DISABLE KEYS */;
INSERT INTO `doserulenames` VALUES (1,'Default','F');
/*!40000 ALTER TABLE `doserulenames` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dosing`
--

DROP TABLE IF EXISTS `dosing`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dosing` (
  `dosing_id` int(11) NOT NULL AUTO_INCREMENT,
  `dose` decimal(6,3) DEFAULT NULL,
  `ent_dilution` int(11) DEFAULT NULL,
  `clickOrder` int(11) DEFAULT NULL,
  `prescription_id` int(11) NOT NULL,
  `extract_id` int(11) NOT NULL,
  `weight` decimal(7,3) DEFAULT '-999.999',
  PRIMARY KEY (`dosing_id`),
  KEY `fk_prescription_id` (`prescription_id`),
  KEY `fk_extract_id` (`extract_id`),
  CONSTRAINT `fk_extract_id` FOREIGN KEY (`extract_id`) REFERENCES `extract` (`extract_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_prescription_id` FOREIGN KEY (`prescription_id`) REFERENCES `prescription` (`prescription_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dosing`
--

LOCK TABLES `dosing` WRITE;
/*!40000 ALTER TABLE `dosing` DISABLE KEYS */;
/*!40000 ALTER TABLE `dosing` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `extract`
--

DROP TABLE IF EXISTS `extract`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `extract` (
  `extract_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `latinname` varchar(100) DEFAULT NULL,
  `manufacturer` varchar(45) DEFAULT NULL,
  `code` varchar(45) DEFAULT NULL,
  `ndc` varchar(13) DEFAULT NULL,
  `abbreviation` varchar(45) DEFAULT NULL,
  `visible` varchar(10) DEFAULT NULL,
  `percentGlycerin` decimal(5,2) DEFAULT '0.00',
  `percentPhenol` decimal(5,2) DEFAULT '0.00',
  `percentHSA` decimal(5,2) DEFAULT '0.00',
  `dilution` varchar(45) DEFAULT NULL,
  `units` int(11) DEFAULT '5',
  `cost` varchar(45) DEFAULT NULL,
  `sub` varchar(50) DEFAULT '',
  `specificgravity` varchar(45) DEFAULT NULL,
  `outdatealert` varchar(45) DEFAULT NULL,
  `compatibility_class_id` int(11) DEFAULT NULL,
  `imagefile` varchar(150) DEFAULT NULL,
  `isDiluent` varchar(5) DEFAULT 'F',
  `silhouette` varchar(45) DEFAULT NULL,
  `color` varchar(45) DEFAULT NULL,
  `topline` varchar(45) DEFAULT NULL,
  `firstline` varchar(45) DEFAULT NULL,
  `secondline` varchar(45) DEFAULT NULL,
  `seasonStart` varchar(45) DEFAULT NULL,
  `seasonEnd` varchar(45) DEFAULT NULL,
  `deleted` varchar(45) NOT NULL DEFAULT 'F',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`extract_id`),
  KEY `compatibility_class_id` (`compatibility_class_id`),
  KEY `fk_extract_units_units_id` (`units`),
  CONSTRAINT `fk_extract_compatibility_class` FOREIGN KEY (`compatibility_class_id`) REFERENCES `compatibility_class` (`compatibility_class_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_extract_units_units_id` FOREIGN KEY (`units`) REFERENCES `units` (`units_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `extract`
--

LOCK TABLES `extract` WRITE;
/*!40000 ALTER TABLE `extract` DISABLE KEYS */;
INSERT INTO `extract` VALUES (1,NULL,NULL,NULL,'-999',NULL,'',NULL,0.00,0.00,0.00,NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,'F',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'T','0000-00-00 00:00:00',NULL,'2017-11-16 01:04:33',NULL);
/*!40000 ALTER TABLE `extract` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `extract_mix`
--

DROP TABLE IF EXISTS `extract_mix`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `extract_mix` (
  `extract_mix_id` int(11) NOT NULL AUTO_INCREMENT,
  `constit_extract_id` int(11) NOT NULL,
  `dose` decimal(5,3) DEFAULT NULL,
  `deleted` varchar(45) NOT NULL DEFAULT 'F',
  `extract_id` int(11) NOT NULL,
  PRIMARY KEY (`extract_mix_id`),
  KEY `fk_extractMix_extract` (`extract_id`),
  CONSTRAINT `fk_extractMix_extract` FOREIGN KEY (`extract_id`) REFERENCES `extract` (`extract_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `extract_mix`
--

LOCK TABLES `extract_mix` WRITE;
/*!40000 ALTER TABLE `extract_mix` DISABLE KEYS */;
/*!40000 ALTER TABLE `extract_mix` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `flag`
--

DROP TABLE IF EXISTS `flag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `flag` (
  `flag_id` int(11) NOT NULL AUTO_INCREMENT,
  `catagory` varchar(16) DEFAULT NULL,
  `status` varchar(16) DEFAULT 'active',
  `period_start` datetime NOT NULL,
  `period_end` datetime DEFAULT NULL,
  `period_interval` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `code` tinytext,
  `identifier` varchar(32) DEFAULT NULL,
  `last_alert` datetime DEFAULT NULL,
  PRIMARY KEY (`flag_id`),
  KEY `fk_flag_patient_id` (`patient_id`),
  KEY `fk_flag_user_id` (`user_id`),
  CONSTRAINT `fk_flag_patient_id` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`patient_id`),
  CONSTRAINT `fk_flag_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `flag`
--

LOCK TABLES `flag` WRITE;
/*!40000 ALTER TABLE `flag` DISABLE KEYS */;
/*!40000 ALTER TABLE `flag` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `flags`
--

DROP TABLE IF EXISTS `flags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `flags` (
  `flag_id` int(11) NOT NULL AUTO_INCREMENT,
  `text` varchar(255) DEFAULT NULL,
  `urgency` varchar(45) DEFAULT NULL,
  `deleted` varchar(5) NOT NULL DEFAULT 'F',
  `code` varchar(45) DEFAULT NULL,
  `adminReq` varchar(5) DEFAULT 'F',
  `showMsg` varchar(5) DEFAULT 'F',
  PRIMARY KEY (`flag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `flags`
--

LOCK TABLES `flags` WRITE;
/*!40000 ALTER TABLE `flags` DISABLE KEYS */;
/*!40000 ALTER TABLE `flags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `flaguse`
--

DROP TABLE IF EXISTS `flaguse`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `flaguse` (
  `flagUse_id` int(11) NOT NULL AUTO_INCREMENT,
  `flag_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  PRIMARY KEY (`flagUse_id`),
  KEY `flagUse_flag_id_idx` (`flag_id`),
  KEY `fk_flagUSe_patient_id_idx` (`patient_id`),
  CONSTRAINT `fk_flagUSe_patient_id` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`patient_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_flagUse_flag_id` FOREIGN KEY (`flag_id`) REFERENCES `flags` (`flag_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `flaguse`
--

LOCK TABLES `flaguse` WRITE;
/*!40000 ALTER TABLE `flaguse` DISABLE KEYS */;
/*!40000 ALTER TABLE `flaguse` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hrlog`
--

DROP TABLE IF EXISTS `hrlog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hrlog` (
  `hrLog_id` int(11) NOT NULL AUTO_INCREMENT,
  `event` varchar(150) DEFAULT NULL,
  `userName` varchar(45) DEFAULT NULL,
  `timestamp` datetime DEFAULT NULL,
  `botNums` varchar(45) DEFAULT NULL,
  `prescription_id` int(11) NOT NULL,
  PRIMARY KEY (`hrLog_id`),
  KEY `fk_hrLog_prescription_idx` (`prescription_id`),
  CONSTRAINT `fk_hrLog_prescription1` FOREIGN KEY (`prescription_id`) REFERENCES `prescription` (`prescription_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hrlog`
--

LOCK TABLES `hrlog` WRITE;
/*!40000 ALTER TABLE `hrlog` DISABLE KEYS */;
/*!40000 ALTER TABLE `hrlog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `identification`
--

DROP TABLE IF EXISTS `identification`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `identification` (
  `identification_id` int(11) NOT NULL AUTO_INCREMENT,
  `finger` int(11) NOT NULL,
  `fmd` varchar(1024) DEFAULT NULL,
  `fmd_construct` varchar(8192) DEFAULT NULL,
  `patient_id` int(11) NOT NULL,
  PRIMARY KEY (`identification_id`),
  KEY `fk_patient_id_idx` (`patient_id`),
  CONSTRAINT `fk_patient_id2` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`patient_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `identification`
--

LOCK TABLES `identification` WRITE;
/*!40000 ALTER TABLE `identification` DISABLE KEYS */;
/*!40000 ALTER TABLE `identification` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `image`
--

DROP TABLE IF EXISTS `image`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `image` (
  `image_id` int(11) NOT NULL AUTO_INCREMENT,
  `data` text NOT NULL,
  `type` varchar(32) NOT NULL,
  PRIMARY KEY (`image_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `image`
--

LOCK TABLES `image` WRITE;
/*!40000 ALTER TABLE `image` DISABLE KEYS */;
/*!40000 ALTER TABLE `image` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `injadjust`
--

DROP TABLE IF EXISTS `injadjust`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `injadjust` (
  `injAdjust_id` int(11) NOT NULL AUTO_INCREMENT,
  `dose` float DEFAULT NULL,
  `dilution` varchar(45) DEFAULT NULL,
  `color` varchar(45) DEFAULT NULL,
  `reason` varchar(150) DEFAULT NULL,
  `date` varchar(45) DEFAULT NULL,
  `prescription_id` int(11) NOT NULL,
  `adjby` varchar(45) DEFAULT NULL,
  `reviewby` varchar(150) DEFAULT NULL,
  `deleted` varchar(45) DEFAULT 'F',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`injAdjust_id`),
  KEY `fk_injAdjust_prescription1_idx` (`prescription_id`),
  CONSTRAINT `fk_injAdjust_prescription1` FOREIGN KEY (`prescription_id`) REFERENCES `prescription` (`prescription_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `injadjust`
--

LOCK TABLES `injadjust` WRITE;
/*!40000 ALTER TABLE `injadjust` DISABLE KEYS */;
/*!40000 ALTER TABLE `injadjust` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `injection`
--

DROP TABLE IF EXISTS `injection`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `injection` (
  `injection_id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime DEFAULT NULL,
  `timecheckin` datetime DEFAULT NULL,
  `timeinjection` datetime DEFAULT NULL,
  `timeexcuse` text,
  `dose` decimal(6,3) DEFAULT NULL,
  `color` varchar(45) DEFAULT NULL,
  `site` varchar(45) DEFAULT NULL,
  `code` varchar(45) DEFAULT NULL,
  `question` int(11) DEFAULT NULL,
  `vials` int(11) DEFAULT NULL,
  `notespatient` text,
  `notesuser` text,
  `reactionimage` varchar(45) DEFAULT NULL,
  `timestamp` datetime DEFAULT NULL,
  `reaction` varchar(32) DEFAULT 'F',
  `sysreaction` varchar(32) DEFAULT 'F',
  `user_id` int(11) NOT NULL,
  `compound_id` int(11) NOT NULL,
  `deleted` varchar(45) DEFAULT 'F',
  `attending` varchar(45) DEFAULT NULL,
  `tpdetails_id` varchar(45) DEFAULT '-1',
  `inj_adjust_id` int(11) DEFAULT NULL,
  `predicted_tpdetails_id` int(11) DEFAULT NULL,
  `treatment_plan_id` int(11) DEFAULT NULL,
  `tp_step` int(11) DEFAULT NULL,
  `is_rule_adjust` char(1) DEFAULT 'F',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`injection_id`),
  KEY `fk_injection_user1_idx` (`user_id`),
  KEY `fk_compound_id_idx` (`compound_id`),
  KEY `predicted_tpdetails_id` (`predicted_tpdetails_id`),
  KEY `inj_adjust_id` (`inj_adjust_id`),
  CONSTRAINT `fk_injection_compound1` FOREIGN KEY (`compound_id`) REFERENCES `compound` (`compound_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_injection_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `injection_ibfk_1` FOREIGN KEY (`predicted_tpdetails_id`) REFERENCES `treatplandetails` (`treatPlanDetails_id`),
  CONSTRAINT `injection_ibfk_2` FOREIGN KEY (`inj_adjust_id`) REFERENCES `injadjust` (`injAdjust_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `injection`
--

LOCK TABLES `injection` WRITE;
/*!40000 ALTER TABLE `injection` DISABLE KEYS */;
/*!40000 ALTER TABLE `injection` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `install_config`
--

DROP TABLE IF EXISTS `install_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `install_config` (
  `install_config_id` int(11) NOT NULL AUTO_INCREMENT,
  `section` varchar(32) NOT NULL,
  `name` varchar(32) NOT NULL,
  `value` text NOT NULL,
  `comment` text NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`install_config_id`),
  UNIQUE KEY `install_config_unique` (`section`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `install_config`
--

LOCK TABLES `install_config` WRITE;
/*!40000 ALTER TABLE `install_config` DISABLE KEYS */;
/*!40000 ALTER TABLE `install_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventory`
--

DROP TABLE IF EXISTS `inventory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inventory` (
  `inventory_id` int(11) NOT NULL AUTO_INCREMENT,
  `barcode` varchar(45) DEFAULT NULL,
  `outdate` date DEFAULT NULL,
  `lotnumber` varchar(45) DEFAULT NULL,
  `dilutionENT` int(11) DEFAULT NULL,
  `vialSize` decimal(7,3) DEFAULT NULL,
  `volumeNew` decimal(7,3) DEFAULT NULL,
  `volumeCurrent` decimal(7,3) DEFAULT NULL,
  `installtime` timestamp NULL DEFAULT NULL,
  `removetime` timestamp NULL DEFAULT NULL,
  `changereason` varchar(150) DEFAULT NULL,
  `timestamp` timestamp NULL DEFAULT NULL,
  `discardDate` date DEFAULT NULL,
  `door` int(11) NOT NULL,
  `page` int(11) NOT NULL DEFAULT '1',
  `location` int(11) DEFAULT '0',
  `percentHSA` decimal(5,2) DEFAULT '0.00',
  `percentPhenol` decimal(5,2) DEFAULT '0.00',
  `percentGlycerin` decimal(5,2) DEFAULT '0.00',
  `deleted` varchar(45) NOT NULL DEFAULT 'F',
  `extract_id` int(11) NOT NULL,
  `installBy` varchar(50) DEFAULT NULL,
  `removeBy` varchar(50) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`inventory_id`),
  KEY `fk_inventory_extract1_idx` (`extract_id`),
  CONSTRAINT `fk_inventory_extract1` FOREIGN KEY (`extract_id`) REFERENCES `extract` (`extract_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory`
--

LOCK TABLES `inventory` WRITE;
/*!40000 ALTER TABLE `inventory` DISABLE KEYS */;
/*!40000 ALTER TABLE `inventory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `location`
--

DROP TABLE IF EXISTS `location`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `location` (
  `location_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `deleted` varchar(11) DEFAULT 'F',
  PRIMARY KEY (`location_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `location`
--

LOCK TABLES `location` WRITE;
/*!40000 ALTER TABLE `location` DISABLE KEYS */;
/*!40000 ALTER TABLE `location` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `log_xa`
--

DROP TABLE IF EXISTS `log_xa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log_xa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reportname` varchar(256) DEFAULT NULL,
  `message` text,
  `patient` int(11) DEFAULT NULL,
  `username` varchar(45) DEFAULT NULL,
  `compName` varchar(100) DEFAULT NULL,
  `timestamp` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log_xa`
--

LOCK TABLES `log_xa` WRITE;
/*!40000 ALTER TABLE `log_xa` DISABLE KEYS */;
/*!40000 ALTER TABLE `log_xa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `login`
--

DROP TABLE IF EXISTS `login`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `login` (
  `login_id` int(11) NOT NULL AUTO_INCREMENT,
  `patient_id` int(11) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `loginTime` datetime DEFAULT NULL,
  `excuseTime` datetime DEFAULT NULL,
  `timeNext` datetime DEFAULT NULL,
  `timeOut` datetime DEFAULT NULL,
  `timeLeft` datetime DEFAULT NULL,
  `imagepath` varchar(150) DEFAULT NULL,
  `state` int(11) NOT NULL,
  `clinic_id` int(11) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`login_id`),
  KEY `patient_id` (`patient_id`),
  KEY `fk_clinic_login` (`clinic_id`),
  CONSTRAINT `fk_login_clinic` FOREIGN KEY (`clinic_id`) REFERENCES `clinic` (`clinic_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_login_patient_id` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`patient_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `login`
--

LOCK TABLES `login` WRITE;
/*!40000 ALTER TABLE `login` DISABLE KEYS */;
/*!40000 ALTER TABLE `login` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `message`
--

DROP TABLE IF EXISTS `message`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `message` (
  `message_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) COLLATE utf8mb4_bin NOT NULL,
  `days` int(7) DEFAULT NULL,
  `sql_code` text COLLATE utf8mb4_bin,
  `content` text COLLATE utf8mb4_bin,
  `method` char(1) COLLATE utf8mb4_bin NOT NULL DEFAULT 'E',
  `patientSelect` varchar(45) COLLATE utf8mb4_bin DEFAULT NULL,
  `singleSend` char(1) COLLATE utf8mb4_bin NOT NULL DEFAULT 'T',
  `custom` char(1) COLLATE utf8mb4_bin NOT NULL DEFAULT 'F',
  `enabled` char(1) COLLATE utf8mb4_bin NOT NULL DEFAULT 'T',
  `schedule` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` char(1) COLLATE utf8mb4_bin NOT NULL DEFAULT 'F',
  `subject` varchar(250) COLLATE utf8mb4_bin DEFAULT '',
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`message_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `message`
--

LOCK TABLES `message` WRITE;
/*!40000 ALTER TABLE `message` DISABLE KEYS */;
/*!40000 ALTER TABLE `message` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `message_log`
--

DROP TABLE IF EXISTS `message_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `message_log` (
  `message_log_id` int(11) NOT NULL AUTO_INCREMENT,
  `patient_id` int(11) NOT NULL,
  `message_id` int(11) NOT NULL,
  `status` char(1) NOT NULL DEFAULT 'P',
  `sentTime` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`message_log_id`),
  KEY `patient_id` (`patient_id`),
  KEY `message_id` (`message_id`),
  CONSTRAINT `message_log_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`patient_id`),
  CONSTRAINT `message_log_ibfk_2` FOREIGN KEY (`message_id`) REFERENCES `message` (`message_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `message_log`
--

LOCK TABLES `message_log` WRITE;
/*!40000 ALTER TABLE `message_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `message_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mirth_log`
--

DROP TABLE IF EXISTS `mirth_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mirth_log` (
  `mirth_log_id` int(11) NOT NULL AUTO_INCREMENT,
  `app` varchar(16) NOT NULL,
  `channel_message_id` int(11) NOT NULL,
  `xtract_order_number` varchar(64) NOT NULL,
  `timestamp` datetime DEFAULT CURRENT_TIMESTAMP,
  `event` varchar(128) DEFAULT NULL,
  `error` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`mirth_log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mirth_log`
--

LOCK TABLES `mirth_log` WRITE;
/*!40000 ALTER TABLE `mirth_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `mirth_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_access_tokens`
--

DROP TABLE IF EXISTS `oauth_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `client_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `scopes` text COLLATE utf8_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_access_tokens_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_access_tokens`
--

LOCK TABLES `oauth_access_tokens` WRITE;
/*!40000 ALTER TABLE `oauth_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_auth_codes`
--

DROP TABLE IF EXISTS `oauth_auth_codes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `scopes` text COLLATE utf8_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_auth_codes`
--

LOCK TABLES `oauth_auth_codes` WRITE;
/*!40000 ALTER TABLE `oauth_auth_codes` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_auth_codes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_clients`
--

DROP TABLE IF EXISTS `oauth_clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oauth_clients` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `secret` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `redirect` text COLLATE utf8_unicode_ci NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_clients_user_id_index` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_clients`
--

LOCK TABLES `oauth_clients` WRITE;
/*!40000 ALTER TABLE `oauth_clients` DISABLE KEYS */;
INSERT INTO `oauth_clients` VALUES (2,NULL,'Xtract Solutions Password Grant','4fhvhEGt000xB89ibIAJqSMMxGLTIl5K99ET4dBe','http://localhost',0,1,0,NULL,NULL);
/*!40000 ALTER TABLE `oauth_clients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_personal_access_clients`
--

DROP TABLE IF EXISTS `oauth_personal_access_clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oauth_personal_access_clients` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_personal_access_clients_client_id_index` (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_personal_access_clients`
--

LOCK TABLES `oauth_personal_access_clients` WRITE;
/*!40000 ALTER TABLE `oauth_personal_access_clients` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_personal_access_clients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_refresh_tokens`
--

DROP TABLE IF EXISTS `oauth_refresh_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `access_token_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_refresh_tokens`
--

LOCK TABLES `oauth_refresh_tokens` WRITE;
/*!40000 ALTER TABLE `oauth_refresh_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_refresh_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `padlock`
--

DROP TABLE IF EXISTS `padlock`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `padlock` (
  `lock_id` int(11) NOT NULL AUTO_INCREMENT,
  `locked_until` datetime NOT NULL,
  `locked_by` varchar(16) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`lock_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `padlock`
--

LOCK TABLES `padlock` WRITE;
/*!40000 ALTER TABLE `padlock` DISABLE KEYS */;
/*!40000 ALTER TABLE `padlock` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `panel`
--

DROP TABLE IF EXISTS `panel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `panel` (
  `panel_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `class` varchar(45) DEFAULT NULL,
  `panelcol` varchar(45) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`panel_id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `panel`
--

LOCK TABLES `panel` WRITE;
/*!40000 ALTER TABLE `panel` DISABLE KEYS */;
/*!40000 ALTER TABLE `panel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `panel_antigen`
--

DROP TABLE IF EXISTS `panel_antigen`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `panel_antigen` (
  `panel_antigen_id` int(11) NOT NULL AUTO_INCREMENT,
  `panel_id` int(11) NOT NULL,
  `antigen_id` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`panel_antigen_id`),
  KEY `panel_id` (`panel_id`,`antigen_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `panel_antigen`
--

LOCK TABLES `panel_antigen` WRITE;
/*!40000 ALTER TABLE `panel_antigen` DISABLE KEYS */;
/*!40000 ALTER TABLE `panel_antigen` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `patient`
--

DROP TABLE IF EXISTS `patient`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `patient` (
  `patient_id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(45) DEFAULT NULL,
  `lastname` varchar(45) DEFAULT NULL,
  `mi` varchar(45) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `botCheck` date DEFAULT '2039-12-18',
  `maintStart` date DEFAULT NULL,
  `shotStart` date DEFAULT NULL,
  `phone` varchar(45) DEFAULT NULL,
  `addr1` varchar(100) DEFAULT NULL,
  `addr2` varchar(100) DEFAULT NULL,
  `city` varchar(45) DEFAULT NULL,
  `state` varchar(45) DEFAULT NULL,
  `zip` varchar(45) DEFAULT NULL,
  `displayname` varchar(45) DEFAULT NULL,
  `faceimage` varchar(45) DEFAULT NULL,
  `chart` varchar(45) DEFAULT NULL,
  `lockState` int(11) DEFAULT '0',
  `lockby` varchar(45) DEFAULT NULL,
  `lock_id` int(11) DEFAULT NULL,
  `patient_notes` text,
  `eContact` varchar(45) DEFAULT NULL,
  `eContactNum` varchar(45) DEFAULT NULL,
  `idcode` varchar(45) DEFAULT NULL,
  `user_id` varchar(45) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `smsphone` varchar(45) DEFAULT NULL,
  `archived` varchar(45) NOT NULL DEFAULT 'F',
  `box1` varchar(5) DEFAULT NULL,
  `box2` varchar(5) DEFAULT NULL,
  `box3` varchar(5) DEFAULT NULL,
  `contactby` varchar(5) DEFAULT NULL,
  `listOptIn` varchar(65) DEFAULT NULL,
  `reviewby` varchar(150) DEFAULT NULL,
  `PV1segment` text,
  `PIDsegment` text,
  `gender` char(1) DEFAULT NULL,
  `ssn` char(11) DEFAULT NULL,
  `provider_id` int(11) DEFAULT NULL,
  `province` varchar(45) DEFAULT NULL,
  `country` varchar(45) DEFAULT NULL,
  `fax` varchar(45) DEFAULT NULL,
  `phoneNotes` varchar(400) DEFAULT NULL,
  `external_id` varchar(100) DEFAULT '',
  `login_notes` text,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) DEFAULT NULL,
  `numLateInjections` int(11) DEFAULT '0',
  `lateInjectionsStartDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `address_id` int(11) DEFAULT NULL,
  `bill_to` int(11) DEFAULT NULL,
  `ship_to` int(11) DEFAULT NULL,
  `face_image_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`patient_id`),
  KEY `fk_patient_provider_id1` (`provider_id`),
  KEY `lock_id` (`lock_id`),
  KEY `idx_patient_lastname_archived` (`lastname`,`archived`),
  KEY `idx_patient_chart_archived` (`chart`,`archived`),
  KEY `address_id` (`address_id`),
  KEY `bill_to` (`bill_to`),
  KEY `ship_to` (`ship_to`),
  KEY `face_image_id` (`face_image_id`),
  CONSTRAINT `fk_patient_padlock` FOREIGN KEY (`lock_id`) REFERENCES `padlock` (`lock_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_patient_padlock_lock_id` FOREIGN KEY (`lock_id`) REFERENCES `padlock` (`lock_id`) ON DELETE SET NULL ON UPDATE SET NULL,
  CONSTRAINT `fk_patient_provider_id1` FOREIGN KEY (`provider_id`) REFERENCES `provider` (`provider_id`),
  CONSTRAINT `patient_ibfk_1` FOREIGN KEY (`address_id`) REFERENCES `address` (`address_id`),
  CONSTRAINT `patient_ibfk_2` FOREIGN KEY (`bill_to`) REFERENCES `address` (`address_id`),
  CONSTRAINT `patient_ibfk_3` FOREIGN KEY (`ship_to`) REFERENCES `address` (`address_id`),
  CONSTRAINT `patient_ibfk_4` FOREIGN KEY (`face_image_id`) REFERENCES `image` (`image_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `patient`
--

LOCK TABLES `patient` WRITE;
/*!40000 ALTER TABLE `patient` DISABLE KEYS */;
INSERT INTO `patient` VALUES (1,NULL,NULL,NULL,NULL,'2039-12-18',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'manualIn',NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'T',NULL,NULL,NULL,NULL,NULL,NULL,'PV1|PV1_WILL_GO_HERE_BUT_NONE_HAVE_BEEN_SENT_THROUGH_ADT||||||',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'0000-00-00 00:00:00',NULL,'2017-11-16 17:09:34',NULL,0,'2018-04-02 22:35:15',NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `patient` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER auto_manage_legacy_patient_config_lock BEFORE UPDATE ON `patient`
    FOR EACH ROW BEGIN
      IF (NEW.lock_id is not null && (select count(*) from patient_config where lock_id=NEW.lock_id) < 1) THEN
        set @LockByName = '';
        set @LockBy = 0;
        set @LockAt = 0;
        select locked_by, created_at INTO @LockByName, @LockAt from padlock where lock_id=NEW.lock_id;
        select user_id INTO @LockBy from terminal where compname = @LockByName limit 1;
        insert into patient_config (name, value, patient_id, lock_id, created_by, created_at) values('lock', concat('legacy_lock',NEW.lock_id), OLD.patient_id, NEW.lock_id, @LockBy, @LockAt);
      ELSEIF (NEW.lock_id is null && OLD.lock_id is not NULL && (select count(*) from patient_config where lock_id=OLD.lock_id) > 0) THEN
        delete from patient_config where lock_id=OLD.lock_id;
      END IF;
    END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `patient_config`
--

DROP TABLE IF EXISTS `patient_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `patient_config` (
  `patient_config_id` int(11) NOT NULL AUTO_INCREMENT,
  `patient_id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `value` text NOT NULL,
  `lock_id` int(11) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`patient_config_id`),
  KEY `fk_patient_config_patient_id_idx` (`patient_id`),
  KEY `lock_id` (`lock_id`),
  CONSTRAINT `fk_patient_config_patient_id` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`patient_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `patient_config_ibfk_1` FOREIGN KEY (`lock_id`) REFERENCES `padlock` (`lock_id`)
) ENGINE=InnoDB AUTO_INCREMENT=257 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `patient_config`
--

LOCK TABLES `patient_config` WRITE;
/*!40000 ALTER TABLE `patient_config` DISABLE KEYS */;
/*!40000 ALTER TABLE `patient_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `patient_files`
--

DROP TABLE IF EXISTS `patient_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `patient_files` (
  `patient_files_id` int(11) NOT NULL AUTO_INCREMENT,
  `path1` varchar(150) DEFAULT NULL,
  `path2` varchar(150) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `deleted` varchar(11) DEFAULT 'F',
  `prescription_num` varchar(10) DEFAULT '',
  PRIMARY KEY (`patient_files_id`),
  KEY `patient_file_user_id_idx` (`user_id`),
  KEY `patient_files_id_idx` (`patient_id`),
  CONSTRAINT `fk_paFiles_patient_id` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`patient_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_paFiles_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `patient_files`
--

LOCK TABLES `patient_files` WRITE;
/*!40000 ALTER TABLE `patient_files` DISABLE KEYS */;
/*!40000 ALTER TABLE `patient_files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `patient_questionnaire`
--

DROP TABLE IF EXISTS `patient_questionnaire`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `patient_questionnaire` (
  `patient_id` int(11) NOT NULL,
  `questionnaire_id` int(11) NOT NULL,
  `recurring` char(1) DEFAULT 'F',
  `frequency` int(11) DEFAULT '0',
  UNIQUE KEY `patient_id` (`patient_id`,`questionnaire_id`),
  KEY `questionnaire_id` (`questionnaire_id`),
  CONSTRAINT `patient_questionnaire_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`patient_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `patient_questionnaire_ibfk_2` FOREIGN KEY (`questionnaire_id`) REFERENCES `questionnaire` (`questionnaire_id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `patient_questionnaire`
--

LOCK TABLES `patient_questionnaire` WRITE;
/*!40000 ALTER TABLE `patient_questionnaire` DISABLE KEYS */;
/*!40000 ALTER TABLE `patient_questionnaire` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `patient_temp`
--

DROP TABLE IF EXISTS `patient_temp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `patient_temp` (
  `patient_temp_id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(45) DEFAULT NULL,
  `lastname` varchar(45) DEFAULT NULL,
  `mi` varchar(45) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `chart` varchar(45) DEFAULT NULL,
  `external_id` varchar(100) DEFAULT NULL,
  `MSHsegment` varchar(1114) DEFAULT NULL,
  `eContact` varchar(45) DEFAULT NULL,
  `eContactNum` varchar(45) DEFAULT NULL,
  `PV1segment` text,
  `PIDsegment` text,
  `MRGsegment` varchar(500) DEFAULT NULL,
  `gender` char(1) DEFAULT '',
  `ssn` char(11) DEFAULT NULL,
  `deferred` varchar(45) DEFAULT 'F',
  `deferReason` text,
  `deferdate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `emaildate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `successdate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `successResult` text,
  `home_phone` varchar(20) DEFAULT NULL,
  `addr1` varchar(100) DEFAULT NULL,
  `addr2` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(45) DEFAULT NULL,
  `zip` varchar(45) DEFAULT NULL,
  `guar_last` varchar(45) DEFAULT NULL,
  `guar_first` varchar(45) DEFAULT NULL,
  `guar_mi` varchar(5) DEFAULT NULL,
  `guar_suffix` varchar(15) DEFAULT NULL,
  `guar_addr1` varchar(100) DEFAULT NULL,
  `guar_addr2` varchar(100) DEFAULT NULL,
  `guar_city` varchar(100) DEFAULT NULL,
  `guar_state` varchar(30) DEFAULT NULL,
  `guar_zip` varchar(20) DEFAULT NULL,
  `prim_carrier` varchar(20) DEFAULT NULL,
  `sec_carrier` varchar(20) DEFAULT NULL,
  `paLocPOC` varchar(45) DEFAULT '',
  `paLocFacility` varchar(45) DEFAULT '',
  `provFirst` varchar(45) DEFAULT '',
  `provLast` varchar(45) DEFAULT '',
  `provMI` varchar(45) DEFAULT '',
  `provCode` varchar(45) DEFAULT '',
  `provSuffix` varchar(45) DEFAULT '',
  `botCheck` date DEFAULT '2039-12-18',
  `maintStart` date DEFAULT '2039-12-18',
  `shotStart` date DEFAULT '2039-12-18',
  `phone` varchar(45) DEFAULT NULL,
  `displayName` varchar(45) DEFAULT NULL,
  `faceImage` varchar(45) DEFAULT NULL,
  `lockState` int(11) DEFAULT '0',
  `lockBy` varchar(45) DEFAULT NULL,
  `patient_notes` text,
  `idCode` varchar(45) DEFAULT NULL,
  `user_id` varchar(45) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `smsphone` varchar(45) DEFAULT NULL,
  `archived` varchar(45) NOT NULL DEFAULT 'F',
  `asthma` varchar(5) DEFAULT 'F',
  `medicare` varchar(5) DEFAULT 'F',
  `systemic` varchar(5) DEFAULT 'F',
  `contactBy` varchar(10) DEFAULT NULL,
  `listOptIn` varchar(65) DEFAULT NULL,
  `reviewBy` varchar(150) DEFAULT NULL,
  `owner` varchar(50) DEFAULT '',
  PRIMARY KEY (`patient_temp_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `patient_temp`
--

LOCK TABLES `patient_temp` WRITE;
/*!40000 ALTER TABLE `patient_temp` DISABLE KEYS */;
/*!40000 ALTER TABLE `patient_temp` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `postpone`
--

DROP TABLE IF EXISTS `postpone`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `postpone` (
  `postpone_id` int(11) NOT NULL AUTO_INCREMENT,
  `compound_id1` varchar(45) NOT NULL DEFAULT '0',
  `compound_id2` varchar(45) NOT NULL DEFAULT '0',
  `compound_id3` varchar(45) NOT NULL DEFAULT '0',
  `compound_id4` varchar(45) NOT NULL DEFAULT '0',
  `compound_id5` varchar(45) NOT NULL DEFAULT '0',
  `compound_id6` varchar(45) NOT NULL DEFAULT '0',
  `compound_id7` varchar(45) NOT NULL DEFAULT '0',
  `compound_id8` varchar(45) NOT NULL DEFAULT '0',
  `postponeDate` date DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `labelPrinted` varchar(11) DEFAULT 'F',
  `deleted` varchar(45) NOT NULL DEFAULT 'F',
  PRIMARY KEY (`postpone_id`),
  KEY `user_id_idx` (`user_id`),
  KEY `idx_postpone_deleted` (`deleted`),
  KEY `idx_postpone_compound1` (`compound_id1`),
  KEY `idx_postpone_compound2` (`compound_id2`),
  KEY `idx_postpone_compound3` (`compound_id3`),
  KEY `idx_postpone_compound4` (`compound_id4`),
  KEY `idx_postpone_compound5` (`compound_id5`),
  KEY `idx_postpone_compound6` (`compound_id6`),
  KEY `idx_postpone_compound7` (`compound_id7`),
  KEY `idx_postpone_compound8` (`compound_id8`),
  CONSTRAINT `user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `postpone`
--

LOCK TABLES `postpone` WRITE;
/*!40000 ALTER TABLE `postpone` DISABLE KEYS */;
/*!40000 ALTER TABLE `postpone` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary table structure for view `pp_bottles`
--

DROP TABLE IF EXISTS `pp_bottles`;
/*!50001 DROP VIEW IF EXISTS `pp_bottles`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `pp_bottles` AS SELECT 
 1 AS `postpone_id`,
 1 AS `bottles`,
 1 AS `user_id`,
 1 AS `labelPrinted`,
 1 AS `deleted`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `prescription`
--

DROP TABLE IF EXISTS `prescription`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prescription` (
  `prescription_id` int(11) NOT NULL AUTO_INCREMENT,
  `prescription_num` varchar(45) DEFAULT NULL,
  `provider_signature` varchar(45) DEFAULT NULL,
  `prescription_note` text,
  `strikethrough` varchar(10) DEFAULT NULL,
  `strikethrough_reason` text,
  `5or10` varchar(45) NOT NULL,
  `customUnits` varchar(45) DEFAULT 'v/v',
  `multiplier` int(11) DEFAULT '1',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `treatment_plan_id` int(11) DEFAULT '-1',
  `clinic_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `provider_id` int(11) NOT NULL,
  `diagnosis_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `provider_config_id` int(11) DEFAULT NULL,
  `priority` varchar(45) DEFAULT '-1',
  `site` varchar(64) DEFAULT NULL,
  `doseRuleNames_id` int(11) DEFAULT '-1',
  `source` varchar(45) DEFAULT 'XPS',
  `external_id` varchar(100) DEFAULT '',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`prescription_id`),
  KEY `fk_prescription_clinic1_idx` (`clinic_id`),
  KEY `fk_prescription_user1_idx` (`user_id`),
  KEY `fk_prescription_provider1_idx` (`provider_id`),
  KEY `fk_prescription_diagnosis1_idx` (`diagnosis_id`),
  KEY `fk_prescription_patient1_idx` (`patient_id`),
  KEY `fk_prescription_provider_config1` (`provider_config_id`),
  KEY `idx_prescription_strikethrough` (`strikethrough`),
  CONSTRAINT `fk_prescription_clinic1` FOREIGN KEY (`clinic_id`) REFERENCES `clinic` (`clinic_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_prescription_diagnosis1` FOREIGN KEY (`diagnosis_id`) REFERENCES `diagnosis` (`diagnosis_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_prescription_patient1` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`patient_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_prescription_provider1` FOREIGN KEY (`provider_id`) REFERENCES `provider` (`provider_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_prescription_provider_config1` FOREIGN KEY (`provider_config_id`) REFERENCES `provider_config` (`provider_config_id`),
  CONSTRAINT `fk_prescription_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prescription`
--

LOCK TABLES `prescription` WRITE;
/*!40000 ALTER TABLE `prescription` DISABLE KEYS */;
INSERT INTO `prescription` VALUES (1,'900000',NULL,NULL,'F',NULL,'10','v/v',1,'2016-09-08 15:41:28',4,2,2,1,1,1,2,'-1','upperR',-1,'XPS',NULL,'2018-04-02 22:04:24',NULL,'2018-04-02 22:04:24',NULL);
/*!40000 ALTER TABLE `prescription` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER auto_fix_treatment_set_priority AFTER UPDATE ON `prescription`
    FOR EACH ROW BEGIN
      UPDATE treatment_set set priority=NEW.priority where prescription_id=OLD.prescription_id and priority=OLD.priority;
    END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `printlog`
--

DROP TABLE IF EXISTS `printlog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `printlog` (
  `printLog_id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(200) DEFAULT NULL,
  `printDate` timestamp NULL DEFAULT NULL,
  `printErrMsg` text,
  `mirthDate` timestamp NULL DEFAULT NULL,
  `mirthErrMsg` text,
  `emrDate` timestamp NULL DEFAULT NULL,
  `emrErrMsg` text,
  `transaction` varchar(15) DEFAULT NULL,
  `deleted` varchar(5) NOT NULL DEFAULT 'F',
  `patient_id` int(11) NOT NULL,
  `terminal_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`printLog_id`),
  KEY `fk_printLog_patient_idx` (`patient_id`),
  KEY `fk_printLog_terminal_idx` (`terminal_id`),
  KEY `fk_printLog_user_idx` (`user_id`),
  CONSTRAINT `fk_printLog_patient` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`patient_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_printLog_terminal` FOREIGN KEY (`terminal_id`) REFERENCES `terminal` (`terminal_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_printLog_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `printlog`
--

LOCK TABLES `printlog` WRITE;
/*!40000 ALTER TABLE `printlog` DISABLE KEYS */;
/*!40000 ALTER TABLE `printlog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `protocol`
--

DROP TABLE IF EXISTS `protocol`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `protocol` (
  `protocol_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `test_config` text,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`protocol_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `protocol`
--

LOCK TABLES `protocol` WRITE;
/*!40000 ALTER TABLE `protocol` DISABLE KEYS */;
/*!40000 ALTER TABLE `protocol` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `provider`
--

DROP TABLE IF EXISTS `provider`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `provider` (
  `provider_id` int(11) NOT NULL AUTO_INCREMENT,
  `first` varchar(45) DEFAULT NULL,
  `last` varchar(45) DEFAULT NULL,
  `mi` varchar(45) DEFAULT NULL,
  `displayname` varchar(45) DEFAULT NULL,
  `displaynameS` varchar(45) DEFAULT NULL,
  `suffix` varchar(45) DEFAULT NULL,
  `addr1` varchar(45) DEFAULT NULL,
  `addr2` varchar(45) DEFAULT NULL,
  `city` varchar(45) DEFAULT NULL,
  `state` varchar(45) DEFAULT NULL,
  `zip` varchar(45) DEFAULT NULL,
  `phone` varchar(45) DEFAULT NULL,
  `fax` varchar(45) DEFAULT NULL,
  `provider_notes` text,
  `rate` int(11) DEFAULT '10',
  `faceimage` varchar(45) DEFAULT NULL,
  `licensenumber` varchar(45) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `providerNum` varchar(45) DEFAULT NULL,
  `deaNum` varchar(45) DEFAULT NULL,
  `contactName` varchar(100) DEFAULT NULL,
  `contactPhone` varchar(100) DEFAULT NULL,
  `account` varchar(45) DEFAULT '',
  `deleted` varchar(45) NOT NULL DEFAULT 'F',
  `emrCode` varchar(11) DEFAULT NULL,
  `nonXtract` varchar(45) NOT NULL DEFAULT 'F',
  `general` varchar(150) DEFAULT NULL,
  `external_id` varchar(100) DEFAULT '',
  `country` varchar(45) DEFAULT NULL,
  `province` varchar(45) DEFAULT NULL,
  `account_id` int(11) DEFAULT NULL,
  `address_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`provider_id`),
  KEY `account_id` (`account_id`),
  KEY `address_id` (`address_id`),
  CONSTRAINT `provider_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `account` (`account_id`),
  CONSTRAINT `provider_ibfk_2` FOREIGN KEY (`address_id`) REFERENCES `address` (`address_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `provider`
--

LOCK TABLES `provider` WRITE;
/*!40000 ALTER TABLE `provider` DISABLE KEYS */;
/*!40000 ALTER TABLE `provider` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `provider_config`
--

DROP TABLE IF EXISTS `provider_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `provider_config` (
  `provider_config_id` int(11) NOT NULL AUTO_INCREMENT,
  `numorder` int(11) DEFAULT NULL,
  `color` varchar(100) DEFAULT NULL,
  `colorNames` varchar(45) DEFAULT NULL,
  `dilutions10` varchar(80) DEFAULT NULL,
  `dilutions5` varchar(45) DEFAULT NULL,
  `expirations10` varchar(45) DEFAULT NULL,
  `expirations5` varchar(45) DEFAULT NULL,
  `billrate10` varchar(45) DEFAULT NULL,
  `billrate5` varchar(45) DEFAULT NULL,
  `profileRate` int(11) DEFAULT '10',
  `offset` int(11) DEFAULT '0',
  `profileName` varchar(45) NOT NULL DEFAULT '',
  `lowGlyc` decimal(5,2) DEFAULT '0.00',
  `highGlyc` decimal(5,2) DEFAULT '50.00',
  `defVialSize` varchar(15) DEFAULT '5 mL',
  `paAlertLast` varchar(11) DEFAULT '2015-1-1',
  `paAlertPeriod` varchar(11) DEFAULT '0',
  `paAlertEvent` varchar(11) DEFAULT '0',
  `paAlertVol` decimal(5,2) DEFAULT '0.00',
  `inclDilName` varchar(5) DEFAULT 'F',
  `prefGlycDil` int(11) DEFAULT NULL,
  `prefAqDil` int(11) DEFAULT NULL,
  `deleted` varchar(45) NOT NULL DEFAULT 'F',
  `provider_id` int(11) NOT NULL,
  `custDils` varchar(100) DEFAULT '',
  `doseRules` text,
  PRIMARY KEY (`provider_config_id`),
  KEY `fk_provider_config_provider1_idx` (`provider_id`),
  CONSTRAINT `fk_provider_config_provider1` FOREIGN KEY (`provider_id`) REFERENCES `provider` (`provider_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `provider_config`
--

LOCK TABLES `provider_config` WRITE;
/*!40000 ALTER TABLE `provider_config` DISABLE KEYS */;
/*!40000 ALTER TABLE `provider_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `provider_def`
--

DROP TABLE IF EXISTS `provider_def`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `provider_def` (
  `provider_def_id` int(11) NOT NULL AUTO_INCREMENT,
  `dose` varchar(45) DEFAULT NULL,
  `inseasonstart` varchar(45) DEFAULT NULL,
  `inseasonend` varchar(45) DEFAULT NULL,
  `outdates10` varchar(45) DEFAULT NULL,
  `outdates5` varchar(45) DEFAULT NULL,
  `deleted` varchar(45) NOT NULL DEFAULT 'F',
  `extract_id` int(11) NOT NULL,
  `provider_id` int(11) NOT NULL,
  `provider_config_id` int(11) NOT NULL,
  PRIMARY KEY (`provider_def_id`),
  KEY `fk_provider_def_extract1_idx` (`extract_id`),
  KEY `fk_provider_def_provider1_idx` (`provider_id`),
  KEY `fk_provDef_provider_config1` (`provider_config_id`),
  CONSTRAINT `fk_provDef_provider_config1` FOREIGN KEY (`provider_config_id`) REFERENCES `provider_config` (`provider_config_id`),
  CONSTRAINT `fk_provider_def_extract1` FOREIGN KEY (`extract_id`) REFERENCES `extract` (`extract_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_provider_def_provider1` FOREIGN KEY (`provider_id`) REFERENCES `provider` (`provider_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `provider_def`
--

LOCK TABLES `provider_def` WRITE;
/*!40000 ALTER TABLE `provider_def` DISABLE KEYS */;
/*!40000 ALTER TABLE `provider_def` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary table structure for view `pt`
--

DROP TABLE IF EXISTS `pt`;
/*!50001 DROP VIEW IF EXISTS `pt`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `pt` AS SELECT 
 1 AS `patient_id`,
 1 AS `firstname`,
 1 AS `lastname`,
 1 AS `mi`,
 1 AS `chart`,
 1 AS `dob`,
 1 AS `displayname`,
 1 AS `gender`,
 1 AS `provider_id`,
 1 AS `archived`,
 1 AS `PV1segment`,
 1 AS `PIDsegment`,
 1 AS `patient_notes`,
 1 AS `ssn`,
 1 AS `addr1`,
 1 AS `city`,
 1 AS `state`,
 1 AS `zip`,
 1 AS `phone`,
 1 AS `smsphone`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `pt_provider`
--

DROP TABLE IF EXISTS `pt_provider`;
/*!50001 DROP VIEW IF EXISTS `pt_provider`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `pt_provider` AS SELECT 
 1 AS `patient_id`,
 1 AS `firstname`,
 1 AS `lastname`,
 1 AS `chart`,
 1 AS `provider_id`,
 1 AS `displayname`,
 1 AS `last`,
 1 AS `first`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `pt_rx_inj`
--

DROP TABLE IF EXISTS `pt_rx_inj`;
/*!50001 DROP VIEW IF EXISTS `pt_rx_inj`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `pt_rx_inj` AS SELECT 
 1 AS `patient_id`,
 1 AS `chart`,
 1 AS `firstname`,
 1 AS `lastname`,
 1 AS `provider_config_id`,
 1 AS `prescription_id`,
 1 AS `prescription_num`,
 1 AS `rx_treatment_plan_id`,
 1 AS `compound_id`,
 1 AS `BottleName`,
 1 AS `size`,
 1 AS `color`,
 1 AS `dilution`,
 1 AS `bottleNum`,
 1 AS `active`,
 1 AS `currVol`,
 1 AS `injection_id`,
 1 AS `dose`,
 1 AS `site`,
 1 AS `injection_date`,
 1 AS `user_id`,
 1 AS `inj_adjust_id`,
 1 AS `reaction`,
 1 AS `sysreaction`,
 1 AS `timestamp`,
 1 AS `inj_treatment_plan_id`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `pt_rx_provider`
--

DROP TABLE IF EXISTS `pt_rx_provider`;
/*!50001 DROP VIEW IF EXISTS `pt_rx_provider`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `pt_rx_provider` AS SELECT 
 1 AS `patient_id`,
 1 AS `firstname`,
 1 AS `lastname`,
 1 AS `chart`,
 1 AS `prescription_id`,
 1 AS `prescription_num`,
 1 AS `provider_id`,
 1 AS `displayname`,
 1 AS `last`,
 1 AS `first`,
 1 AS `last_injection`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `purchase_order`
--

DROP TABLE IF EXISTS `purchase_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `purchase_order` (
  `purchase_order_id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` int(11) NOT NULL,
  `bill_to` int(11) DEFAULT NULL,
  `ship_to` int(11) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`purchase_order_id`),
  KEY `bill_to` (`bill_to`),
  KEY `ship_to` (`ship_to`),
  KEY `account_id` (`account_id`),
  CONSTRAINT `purchase_order_ibfk_1` FOREIGN KEY (`bill_to`) REFERENCES `address` (`address_id`),
  CONSTRAINT `purchase_order_ibfk_2` FOREIGN KEY (`ship_to`) REFERENCES `address` (`address_id`),
  CONSTRAINT `purchase_order_ibfk_3` FOREIGN KEY (`account_id`) REFERENCES `account` (`account_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchase_order`
--

LOCK TABLES `purchase_order` WRITE;
/*!40000 ALTER TABLE `purchase_order` DISABLE KEYS */;
INSERT INTO `purchase_order` VALUES (1,1,NULL,NULL,'2018-07-26 16:04:28',NULL,'1970-01-01 08:00:00',NULL);
/*!40000 ALTER TABLE `purchase_order` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `question`
--

DROP TABLE IF EXISTS `question`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `question` (
  `question_id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(45) DEFAULT NULL,
  `text` varchar(255) DEFAULT NULL,
  `BOX1` varchar(5) NOT NULL DEFAULT 'F',
  `BOX2` varchar(5) NOT NULL DEFAULT 'F',
  `BOX3` varchar(5) NOT NULL DEFAULT 'F',
  `goodAns` varchar(16) NOT NULL DEFAULT 'Either',
  `deleted` varchar(5) DEFAULT 'F',
  `qorder` int(11) NOT NULL DEFAULT '-1',
  `all` varchar(5) NOT NULL DEFAULT 'T',
  `note` text,
  `type` varchar(64) DEFAULT 'yes,no',
  `allow_multiple` char(1) DEFAULT 'F',
  `bad_answer` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`question_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `question`
--

LOCK TABLES `question` WRITE;
/*!40000 ALTER TABLE `question` DISABLE KEYS */;
/*!40000 ALTER TABLE `question` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `questionnaire`
--

DROP TABLE IF EXISTS `questionnaire`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `questionnaire` (
  `questionnaire_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) DEFAULT 'un-named questionnaire',
  `minimum_frequency` int(11) DEFAULT '0',
  `box1` char(1) DEFAULT 'F',
  `box2` char(1) DEFAULT 'F',
  `box3` char(1) DEFAULT 'F',
  `allPatients` char(1) DEFAULT 'F',
  `deleted` char(1) DEFAULT 'F',
  PRIMARY KEY (`questionnaire_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `questionnaire`
--

LOCK TABLES `questionnaire` WRITE;
/*!40000 ALTER TABLE `questionnaire` DISABLE KEYS */;
/*!40000 ALTER TABLE `questionnaire` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `questionnaire_question`
--

DROP TABLE IF EXISTS `questionnaire_question`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `questionnaire_question` (
  `questionnaire_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  UNIQUE KEY `question_id` (`question_id`,`questionnaire_id`),
  KEY `questionnaire_id` (`questionnaire_id`),
  CONSTRAINT `questionnaire_question_ibfk_1` FOREIGN KEY (`questionnaire_id`) REFERENCES `questionnaire` (`questionnaire_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `questionnaire_question_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `question` (`question_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `questionnaire_question`
--

LOCK TABLES `questionnaire_question` WRITE;
/*!40000 ALTER TABLE `questionnaire_question` DISABLE KEYS */;
/*!40000 ALTER TABLE `questionnaire_question` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reports`
--

DROP TABLE IF EXISTS `reports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reports` (
  `reports_id` int(11) NOT NULL AUTO_INCREMENT,
  `patient_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `terminal_id` int(11) NOT NULL,
  `xml` text NOT NULL,
  `report_path` varchar(256) DEFAULT NULL,
  `output_path` varchar(256) DEFAULT NULL,
  `report_type` varchar(32) NOT NULL,
  `submit_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `complete_time` datetime DEFAULT NULL,
  `app` varchar(32) NOT NULL,
  `retries` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`reports_id`),
  KEY `patient_id` (`patient_id`),
  KEY `user_id` (`user_id`),
  KEY `terminal_id` (`terminal_id`),
  CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`patient_id`),
  CONSTRAINT `reports_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`),
  CONSTRAINT `reports_ibfk_3` FOREIGN KEY (`terminal_id`) REFERENCES `terminal` (`terminal_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reports`
--

LOCK TABLES `reports` WRITE;
/*!40000 ALTER TABLE `reports` DISABLE KEYS */;
/*!40000 ALTER TABLE `reports` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary table structure for view `rx_bottles`
--

DROP TABLE IF EXISTS `rx_bottles`;
/*!50001 DROP VIEW IF EXISTS `rx_bottles`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `rx_bottles` AS SELECT 
 1 AS `prescription_id`,
 1 AS `prescription_num`,
 1 AS `bottles`,
 1 AS `user_id`,
 1 AS `strikethrough`,
 1 AS `priority`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `rx_co_vi_pt`
--

DROP TABLE IF EXISTS `rx_co_vi_pt`;
/*!50001 DROP VIEW IF EXISTS `rx_co_vi_pt`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `rx_co_vi_pt` AS SELECT 
 1 AS `mixdate`,
 1 AS `rx_id`,
 1 AS `rx_num`,
 1 AS `strikethrough`,
 1 AS `treatment_plan_id`,
 1 AS `compound_id`,
 1 AS `compound_receipt_id`,
 1 AS `VialName`,
 1 AS `size`,
 1 AS `color`,
 1 AS `dilution`,
 1 AS `bottleNum`,
 1 AS `active`,
 1 AS `currVol`,
 1 AS `dosing_id`,
 1 AS `inventory_id`,
 1 AS `patient_id`,
 1 AS `lastname`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `rx_extract`
--

DROP TABLE IF EXISTS `rx_extract`;
/*!50001 DROP VIEW IF EXISTS `rx_extract`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `rx_extract` AS SELECT 
 1 AS `rx_id`,
 1 AS `prescription_num`,
 1 AS `name`,
 1 AS `dilution`,
 1 AS `dose`,
 1 AS `extract_id`,
 1 AS `deleted`,
 1 AS `abbreviation`,
 1 AS `latinname`,
 1 AS `manufacturer`,
 1 AS `code`,
 1 AS `isDiluent`,
 1 AS `percentGlycerin`,
 1 AS `percentPhenol`,
 1 AS `percentHSA`,
 1 AS `units`,
 1 AS `seasonStart`,
 1 AS `seasonEnd`,
 1 AS `cost`,
 1 AS `sub`,
 1 AS `specificgravity`,
 1 AS `outdatealert`,
 1 AS `visible`,
 1 AS `imagefile`,
 1 AS `color`,
 1 AS `silhouette`,
 1 AS `topline`,
 1 AS `firstline`,
 1 AS `secondline`,
 1 AS `compatibility_class_id`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `score`
--

DROP TABLE IF EXISTS `score`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `score` (
  `score_id` int(11) NOT NULL AUTO_INCREMENT,
  `skintest_id` int(11) NOT NULL,
  `antigen_id` int(11) DEFAULT NULL,
  `notes` text NOT NULL,
  `score1` int(11) DEFAULT NULL,
  `score2` int(11) DEFAULT NULL,
  `score3` int(11) DEFAULT NULL,
  `score4` int(11) DEFAULT NULL,
  `score5` int(11) DEFAULT NULL,
  `score6` int(11) DEFAULT NULL,
  `score7` int(11) DEFAULT NULL,
  `score8` int(11) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` varchar(45) NOT NULL DEFAULT 'F',
  PRIMARY KEY (`score_id`),
  KEY `antigen_id` (`antigen_id`),
  KEY `skintest_id` (`skintest_id`),
  CONSTRAINT `fk_score_antigen_antigen_id` FOREIGN KEY (`antigen_id`) REFERENCES `antigen` (`antigen_id`),
  CONSTRAINT `fk_score_skintest_skintest_id` FOREIGN KEY (`skintest_id`) REFERENCES `skintest` (`skintest_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `score`
--

LOCK TABLES `score` WRITE;
/*!40000 ALTER TABLE `score` DISABLE KEYS */;
/*!40000 ALTER TABLE `score` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `skintest`
--

DROP TABLE IF EXISTS `skintest`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `skintest` (
  `skintest_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `state` int(11) DEFAULT '0',
  `patient_id` int(11) NOT NULL,
  `provider_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `provider_config_id` int(11) NOT NULL,
  `test_notes` text NOT NULL,
  `test_log` text NOT NULL,
  `protocol_id` int(11) NOT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `complete_time` datetime DEFAULT NULL,
  `deleted` varchar(45) NOT NULL DEFAULT 'F',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`skintest_id`),
  KEY `patient_id` (`patient_id`),
  KEY `provider_id` (`provider_id`),
  KEY `user_id` (`user_id`),
  KEY `provider_config_id` (`provider_config_id`),
  KEY `skintest_temp_ibfk_5` (`protocol_id`),
  KEY `state` (`state`),
  CONSTRAINT `skintest_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`patient_id`),
  CONSTRAINT `skintest_ibfk_2` FOREIGN KEY (`provider_id`) REFERENCES `provider` (`provider_id`),
  CONSTRAINT `skintest_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`),
  CONSTRAINT `skintest_ibfk_4` FOREIGN KEY (`provider_config_id`) REFERENCES `provider_config` (`provider_config_id`),
  CONSTRAINT `skintest_ibfk_5` FOREIGN KEY (`protocol_id`) REFERENCES `protocol` (`protocol_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `skintest`
--

LOCK TABLES `skintest` WRITE;
/*!40000 ALTER TABLE `skintest` DISABLE KEYS */;
/*!40000 ALTER TABLE `skintest` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `terminal`
--

DROP TABLE IF EXISTS `terminal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `terminal` (
  `terminal_id` int(11) NOT NULL AUTO_INCREMENT,
  `labeltypeprescription` int(11) DEFAULT NULL,
  `labeltypevial` int(11) DEFAULT NULL,
  `labelTypeDoor` int(11) DEFAULT NULL,
  `backups` varchar(150) DEFAULT NULL,
  `workflow` varchar(150) DEFAULT NULL,
  `changereasondefault1` varchar(45) DEFAULT NULL,
  `changereasondefault2` varchar(45) DEFAULT NULL,
  `changereasondefault3` varchar(45) DEFAULT NULL,
  `labelPrinter` varchar(100) DEFAULT NULL,
  `formPrinter` varchar(100) DEFAULT NULL,
  `discardCheck` varchar(100) DEFAULT NULL,
  `outdateCheck` varchar(45) DEFAULT NULL,
  `hardcopydwell` int(11) DEFAULT NULL,
  `startVols` varchar(45) DEFAULT NULL,
  `redThresh` varchar(45) DEFAULT NULL,
  `yellowThresh` varchar(45) DEFAULT NULL,
  `pagenames` varchar(150) DEFAULT '1,2,3,4,5,6',
  `names` text,
  `compName` varchar(100) DEFAULT '',
  `termName` varchar(100) DEFAULT '',
  `currUser` int(11) DEFAULT NULL,
  `emailServer` varchar(150) DEFAULT '',
  `emailUser` varchar(150) DEFAULT '',
  `emailPort` varchar(45) DEFAULT '',
  `emailPwd` varchar(150) DEFAULT '',
  `emailFrom` varchar(150) DEFAULT '',
  `smsSID` varchar(45) DEFAULT '',
  `smsNumber` varchar(45) DEFAULT '',
  `smsToken` varchar(45) DEFAULT '',
  `locAccess` varchar(100) DEFAULT '',
  `labelPrinter2` varchar(100) DEFAULT '',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `location` int(11) DEFAULT NULL,
  PRIMARY KEY (`terminal_id`),
  KEY `location` (`location`),
  CONSTRAINT `fk_clinic_terminal` FOREIGN KEY (`location`) REFERENCES `clinic` (`clinic_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `terminal`
--

LOCK TABLES `terminal` WRITE;
/*!40000 ALTER TABLE `terminal` DISABLE KEYS */;
INSERT INTO `terminal` VALUES (1,2,1,NULL,'T,6/12/2015,T,,','T,T,T,F,F,F,F,F,F,T,T,F,F,F,F,F,F,F,T,F,T,T,T,T,F,T,F,F,F,F,F,T,T,F,T,F,F,F,F,T,F,F,F,F,T,F,F,F,F,T,F',NULL,NULL,NULL,'ZDesigner GX420t (Copy 1)','Open in Web Browser',' ,,T,,, , , , , , , ','T,7/6/2015,T,1,2',0,'4.50,9.50,0.00',NULL,NULL,'Trees,Weeds,Grasses,Molds,Misc.,Misc.','printReportEnabled?,printLabelEnabled?,showScaleMsgs?,loggingEnabled?,showCostInfo?,requireScanBottle?,requireScanKey?,showGlycInfo?,useOldSilhouettes?,confirmRxEntry?,form1multiple?,form2multiple?,form3multiple?,form4multiple?,form5multiple?,form6multiple?,form7multiple?,inclDiagnosis?,enqueueReceipt?,showDshbrd?,useDshbrd?,useRxLabels?,configRxLbls?,showTrayLocDialogs?,multiScanEnabled?,autoTo1:1?,endOfMonth?,detailedLogging?,reqChart#?,2xRxFor10mL?,>72 drugs?,>12 provider,show provider note,track diluent,useProvProfiles?,promptQprint?,custmNames?,allow0Doses?,paAddr?,paNote?,quickMix?,allFormsAtQ?,extENTDepth?,estMixDateAtQ?,bypassScans?,clinFullNames?,doPaImport?,ask2xPaLabel?,formExport?,showHotKeys?, showDiltMath?','TEMPLATE','TEMPLATE',0,'','','0','','','','','','7,8,9,10,11,13,14,15,16','',0,NULL);
/*!40000 ALTER TABLE `terminal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `terminalstate`
--

DROP TABLE IF EXISTS `terminalstate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `terminalstate` (
  `terminalState_id` int(11) NOT NULL AUTO_INCREMENT,
  `compName` varchar(100) DEFAULT NULL,
  `type` varchar(32) DEFAULT NULL,
  `data1` text,
  `data2` text,
  `data3` text,
  PRIMARY KEY (`terminalState_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `terminalstate`
--

LOCK TABLES `terminalstate` WRITE;
/*!40000 ALTER TABLE `terminalstate` DISABLE KEYS */;
/*!40000 ALTER TABLE `terminalstate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tracking`
--

DROP TABLE IF EXISTS `tracking`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tracking` (
  `tracking_id` int(11) NOT NULL AUTO_INCREMENT,
  `trackingName` varchar(32) NOT NULL,
  `value` decimal(18,2) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `patient_id` int(11) NOT NULL,
  PRIMARY KEY (`tracking_id`),
  KEY `fk_tracking_patient_id` (`patient_id`),
  CONSTRAINT `fk_tracking_patient_id` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`patient_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tracking`
--

LOCK TABLES `tracking` WRITE;
/*!40000 ALTER TABLE `tracking` DISABLE KEYS */;
/*!40000 ALTER TABLE `tracking` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `trackingconfig`
--

DROP TABLE IF EXISTS `trackingconfig`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `trackingconfig` (
  `trackingConfig_id` int(11) NOT NULL AUTO_INCREMENT,
  `trackingName` varchar(32) NOT NULL,
  `min` decimal(5,2) DEFAULT NULL,
  `max` decimal(5,2) DEFAULT NULL,
  `patient_id` int(11) NOT NULL,
  PRIMARY KEY (`trackingConfig_id`),
  UNIQUE KEY `trackingConfig_unique` (`trackingName`,`patient_id`),
  KEY `patient_id` (`patient_id`),
  CONSTRAINT `trackingconfig_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`patient_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trackingconfig`
--

LOCK TABLES `trackingconfig` WRITE;
/*!40000 ALTER TABLE `trackingconfig` DISABLE KEYS */;
/*!40000 ALTER TABLE `trackingconfig` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `treatment_plan`
--

DROP TABLE IF EXISTS `treatment_plan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `treatment_plan` (
  `treatment_plan_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `type` varchar(45) DEFAULT NULL,
  `deleted` varchar(5) NOT NULL DEFAULT 'F',
  `maint_steps_back` varchar(45) DEFAULT NULL,
  `doserulenames_id` int(11) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`treatment_plan_id`),
  KEY `doserulenames_id` (`doserulenames_id`),
  CONSTRAINT `treatment_plan_ibfk_1` FOREIGN KEY (`doserulenames_id`) REFERENCES `doserulenames` (`doseRuleNames_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `treatment_plan`
--

LOCK TABLES `treatment_plan` WRITE;
/*!40000 ALTER TABLE `treatment_plan` DISABLE KEYS */;
/*!40000 ALTER TABLE `treatment_plan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `treatment_set`
--

DROP TABLE IF EXISTS `treatment_set`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `treatment_set` (
  `treatment_set_id` int(11) NOT NULL AUTO_INCREMENT,
  `transaction` varchar(32) DEFAULT NULL,
  `purchase_order_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `provider_id` int(11) NOT NULL,
  `prescription_id` int(11) NOT NULL,
  `clinic_id` int(11) NOT NULL,
  `priority` varchar(45) DEFAULT NULL,
  `source` varchar(45) DEFAULT 'API',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`treatment_set_id`),
  KEY `patient_id` (`patient_id`),
  KEY `provider_id` (`provider_id`),
  KEY `prescription_id` (`prescription_id`),
  KEY `clinic_id` (`clinic_id`),
  KEY `purchase_order_id` (`purchase_order_id`),
  CONSTRAINT `treatment_set_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`patient_id`),
  CONSTRAINT `treatment_set_ibfk_2` FOREIGN KEY (`provider_id`) REFERENCES `provider` (`provider_id`),
  CONSTRAINT `treatment_set_ibfk_3` FOREIGN KEY (`prescription_id`) REFERENCES `prescription` (`prescription_id`),
  CONSTRAINT `treatment_set_ibfk_4` FOREIGN KEY (`clinic_id`) REFERENCES `clinic` (`clinic_id`),
  CONSTRAINT `treatment_set_ibfk_5` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_order` (`purchase_order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `treatment_set`
--

LOCK TABLES `treatment_set` WRITE;
/*!40000 ALTER TABLE `treatment_set` DISABLE KEYS */;
/*!40000 ALTER TABLE `treatment_set` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER auto_create_purchase_order BEFORE INSERT ON `treatment_set`
    FOR EACH ROW BEGIN
      IF (NEW.purchase_order_id IS NULL) THEN
        insert into purchase_order (account_id) select min(account_id) from account;
        set NEW.purchase_order_id = last_insert_id();
      END IF;
    END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `treatplandetails`
--

DROP TABLE IF EXISTS `treatplandetails`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `treatplandetails` (
  `treatPlanDetails_id` int(11) NOT NULL AUTO_INCREMENT,
  `dose` decimal(6,3) DEFAULT NULL,
  `minInterval` float DEFAULT NULL,
  `maxInterval` float DEFAULT NULL,
  `minIntervalUnit` int(11) DEFAULT NULL,
  `maxIntervalUnit` int(11) DEFAULT NULL,
  `color` varchar(45) DEFAULT NULL,
  `5or10` varchar(45) DEFAULT NULL,
  `dilution` int(11) DEFAULT NULL,
  `treatment_plan_id` int(11) NOT NULL,
  `step` int(11) NOT NULL,
  PRIMARY KEY (`treatPlanDetails_id`),
  KEY `fk_treatment_plan_id_idx` (`treatment_plan_id`),
  CONSTRAINT `fk_treatment_plan_id1` FOREIGN KEY (`treatment_plan_id`) REFERENCES `treatment_plan` (`treatment_plan_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `treatplandetails`
--

LOCK TABLES `treatplandetails` WRITE;
/*!40000 ALTER TABLE `treatplandetails` DISABLE KEYS */;
/*!40000 ALTER TABLE `treatplandetails` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `units`
--

DROP TABLE IF EXISTS `units`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `units` (
  `units_id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  PRIMARY KEY (`units_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `units`
--

LOCK TABLES `units` WRITE;
/*!40000 ALTER TABLE `units` DISABLE KEYS */;
INSERT INTO `units` VALUES (0,'BAU/mL'),(1,'AU/mL'),(2,'W/V'),(3,'PNU/mL'),(4,'IU/mL'),(5,'--');
/*!40000 ALTER TABLE `units` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(45) DEFAULT NULL,
  `lastname` varchar(45) DEFAULT NULL,
  `displayname` varchar(45) DEFAULT NULL,
  `title` varchar(45) DEFAULT NULL,
  `password` varchar(60) DEFAULT NULL,
  `lockState` int(11) DEFAULT '0',
  `privilege` varchar(45) DEFAULT NULL,
  `faceimage` varchar(45) DEFAULT NULL,
  `deleted` varchar(45) NOT NULL DEFAULT 'F',
  `general` varchar(150) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) DEFAULT NULL,
  `account_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  KEY `account_id` (`account_id`),
  CONSTRAINT `user_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `account` (`account_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'Admin',NULL,'Admin',NULL,NULL,0,NULL,NULL,'T','',NULL,NULL,'2018-07-26 16:04:27',NULL,'2018-04-02 22:04:24',NULL,1),(2,'Xtract','Admin','Xtract Admin',NULL,'$2a$10$Okp.dWAMf9fWjTGlW77MxOYDbbK81wA8YPSHjTTiohAFSiCAiJVF2',0,'Admin',NULL,'F','','xps@xtractsolutions.com',NULL,'2018-07-26 16:04:27',NULL,'2018-04-02 22:04:24',NULL,1);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_config`
--

DROP TABLE IF EXISTS `user_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_config` (
  `user_config_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`user_config_id`),
  UNIQUE KEY `user_config_unique` (`user_id`,`name`),
  KEY `fk_user_config_user_id_idx` (`user_id`),
  CONSTRAINT `fk_user_config_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=257 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_config`
--

LOCK TABLES `user_config` WRITE;
/*!40000 ALTER TABLE `user_config` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `version`
--

DROP TABLE IF EXISTS `version`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `version` (
  `version` decimal(5,2) DEFAULT NULL,
  `installDate` date DEFAULT NULL,
  `ENT` varchar(5) DEFAULT 'F',
  `minimum_version` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `version`
--

LOCK TABLES `version` WRITE;
/*!40000 ALTER TABLE `version` DISABLE KEYS */;
INSERT INTO `version` VALUES (0.00,NULL,'F',0.00);
/*!40000 ALTER TABLE `version` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vial`
--

DROP TABLE IF EXISTS `vial`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vial` (
  `vial_id` int(11) NOT NULL AUTO_INCREMENT,
  `barcode` varchar(45) DEFAULT NULL,
  `outdate` date DEFAULT NULL,
  `mixdate` datetime DEFAULT NULL,
  `traylocation` varchar(45) DEFAULT NULL,
  `bottleNote` varchar(255) DEFAULT NULL,
  `postponed` varchar(10) NOT NULL DEFAULT 'F',
  `mixAfter` date DEFAULT NULL,
  `labelOutdate` date DEFAULT NULL,
  `transaction` int(11) DEFAULT '0',
  `cost` decimal(6,2) DEFAULT '0.00',
  `diltPos` varchar(10) DEFAULT '',
  `sterilityStart` date DEFAULT '2001-03-01',
  `sterilityEnd` date DEFAULT '2001-03-01',
  `shipDate` date DEFAULT '2001-03-01',
  `approvalDate` date DEFAULT '2001-03-01',
  `treatment_plan_id` int(11) DEFAULT NULL,
  `dosing_id` int(11) NOT NULL,
  `inventory_id` int(11) NOT NULL,
  `compound_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`vial_id`),
  KEY `fk_dosing_id_idx` (`dosing_id`),
  KEY `fk_inventory_id_idx` (`inventory_id`),
  KEY `fk_compund_id_idx` (`compound_id`),
  KEY `fk_user_id_idx` (`user_id`),
  KEY `idx_vial_postponed` (`postponed`),
  CONSTRAINT `fk_compound_id` FOREIGN KEY (`compound_id`) REFERENCES `compound` (`compound_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_dosing_id` FOREIGN KEY (`dosing_id`) REFERENCES `dosing` (`dosing_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_inventory_id` FOREIGN KEY (`inventory_id`) REFERENCES `inventory` (`inventory_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vial`
--

LOCK TABLES `vial` WRITE;
/*!40000 ALTER TABLE `vial` DISABLE KEYS */;
/*!40000 ALTER TABLE `vial` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER auto_fix_treatment_set_transaction BEFORE INSERT ON `vial`
    FOR EACH ROW BEGIN
      IF ((select transaction from treatment_set, compound where NEW.compound_id = compound.compound_id and compound.treatment_set_id=treatment_set.treatment_set_id) IS NULL) THEN
        update treatment_set, compound set transaction = new.transaction where NEW.compound_id = compound.compound_id and compound.treatment_set_id=treatment_set.treatment_set_id;
      END IF;
    END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `xis_log`
--

DROP TABLE IF EXISTS `xis_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xis_log` (
  `xis_log_id` int(11) NOT NULL AUTO_INCREMENT,
  `event` varchar(256) DEFAULT NULL,
  `userName` varchar(45) DEFAULT NULL,
  `timestamp` datetime DEFAULT NULL,
  `compName` varchar(100) DEFAULT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `prescription_id` int(11) DEFAULT NULL,
  `error` text,
  PRIMARY KEY (`xis_log_id`),
  KEY `fk_xis_log_prescription_idx` (`prescription_id`),
  KEY `fk_xis_log_patient_idx` (`patient_id`),
  CONSTRAINT `fk_xis_log_patient1` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`patient_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_xis_log_prescription1` FOREIGN KEY (`prescription_id`) REFERENCES `prescription` (`prescription_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `xis_log`
--

LOCK TABLES `xis_log` WRITE;
/*!40000 ALTER TABLE `xis_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `xis_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `xisprefs`
--

DROP TABLE IF EXISTS `xisprefs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xisprefs` (
  `prefSet1` varchar(45) DEFAULT NULL,
  `prefSet2` varchar(45) DEFAULT NULL,
  `prefSet3` varchar(45) DEFAULT NULL,
  `waitTime` decimal(5,2) DEFAULT '20.00',
  `boxNames` varchar(500) DEFAULT 'SYSTEMIC,ASTHMA,MEDICARE',
  `reactNamesS` varchar(500) DEFAULT 'F,T',
  `reactNamesL` varchar(500) DEFAULT 'F,T',
  `accInt` decimal(1,0) DEFAULT '4',
  `loginPIN` int(11) NOT NULL DEFAULT '1111'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `xisprefs`
--

LOCK TABLES `xisprefs` WRITE;
/*!40000 ALTER TABLE `xisprefs` DISABLE KEYS */;
INSERT INTO `xisprefs` VALUES ('T,F,F,F,F,F,F,F,F,F','F,F,F,F,F,F,F,F,F,F','F,F,F,F,F,F,F,F,F,F',20.00,'SYSTEMIC,ASTHMA,MEDICARE','N,Y','None,Dime,Nickel,Quarter',4,1111);
/*!40000 ALTER TABLE `xisprefs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `xisversion`
--

DROP TABLE IF EXISTS `xisversion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xisversion` (
  `version` decimal(5,2) NOT NULL,
  `installDate` date DEFAULT NULL,
  `ENT` varchar(5) DEFAULT 'F',
  `xpsExist` tinyint(4) DEFAULT NULL,
  `xpsLocal` tinyint(4) DEFAULT NULL,
  `xpsAddr` varchar(45) DEFAULT NULL,
  `minimum_version` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `xisversion`
--

LOCK TABLES `xisversion` WRITE;
/*!40000 ALTER TABLE `xisversion` DISABLE KEYS */;
INSERT INTO `xisversion` VALUES (0.00,NULL,'F',NULL,NULL,NULL,0.00);
/*!40000 ALTER TABLE `xisversion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `xps_log`
--

DROP TABLE IF EXISTS `xps_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xps_log` (
  `xps_log_id` int(11) NOT NULL AUTO_INCREMENT,
  `event` varchar(256) DEFAULT NULL,
  `userName` varchar(45) DEFAULT NULL,
  `timestamp` datetime DEFAULT NULL,
  `compName` varchar(100) DEFAULT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `prescription_id` int(11) DEFAULT NULL,
  `error` text,
  PRIMARY KEY (`xps_log_id`),
  KEY `fk_xps_log_prescription_idx` (`prescription_id`),
  KEY `fk_xps_log_patient_idx` (`patient_id`),
  CONSTRAINT `fk_xps_log_patient1` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`patient_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_xps_log_prescription1` FOREIGN KEY (`prescription_id`) REFERENCES `prescription` (`prescription_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `xps_log`
--

LOCK TABLES `xps_log` WRITE;
/*!40000 ALTER TABLE `xps_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `xps_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `xpsprefs`
--

DROP TABLE IF EXISTS `xpsprefs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xpsprefs` (
  `priority_names` varchar(500) DEFAULT NULL,
  `prefset1` varchar(500) DEFAULT NULL,
  `halocolors` varchar(500) DEFAULT '6618880,15750402'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `xpsprefs`
--

LOCK TABLES `xpsprefs` WRITE;
/*!40000 ALTER TABLE `xpsprefs` DISABLE KEYS */;
INSERT INTO `xpsprefs` VALUES ('PENDED,SIGNED,PREVERIFIED,FILL,VERIFIED,CMD','T,F,F,F,F,F,F,F,F,F','6618880,16750402');
/*!40000 ALTER TABLE `xpsprefs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `xst_log`
--

DROP TABLE IF EXISTS `xst_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xst_log` (
  `xst_log_id` int(11) NOT NULL AUTO_INCREMENT,
  `event` varchar(256) DEFAULT NULL,
  `username` varchar(45) DEFAULT NULL,
  `timestamp` datetime DEFAULT NULL,
  `compName` varchar(100) DEFAULT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `prescription_id` int(11) DEFAULT NULL,
  `error` text,
  `skintest_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`xst_log_id`),
  KEY `fk_xst_log_prescription_idx` (`prescription_id`),
  KEY `fk_xst_log_patient_idx` (`patient_id`),
  CONSTRAINT `fk_xst_log_patient1` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`patient_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_xst_log_prescription1` FOREIGN KEY (`prescription_id`) REFERENCES `prescription` (`prescription_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `xst_log`
--

LOCK TABLES `xst_log` WRITE;
/*!40000 ALTER TABLE `xst_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `xst_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `xstprefs`
--

DROP TABLE IF EXISTS `xstprefs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xstprefs` (
  `section` varchar(128) NOT NULL,
  `entry` varchar(128) NOT NULL,
  `value` text NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`section`,`entry`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `xstprefs`
--

LOCK TABLES `xstprefs` WRITE;
/*!40000 ALTER TABLE `xstprefs` DISABLE KEYS */;
INSERT INTO `xstprefs` VALUES ('license','skintest','1','2016-08-08 22:40:48'),('session_timeout','default','30','2016-08-08 22:40:48'),('session_warning_timeout','default','1','2016-08-08 22:40:48'),('test_timer_default','default','20','2016-08-08 22:40:48'),('test_timer_options','default','5, 10, 15, 20, 25, 30','2016-08-08 22:40:48');
/*!40000 ALTER TABLE `xstprefs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `xtract_schema`
--

DROP TABLE IF EXISTS `xtract_schema`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xtract_schema` (
  `version` varchar(10) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `xtract_schema`
--

LOCK TABLES `xtract_schema` WRITE;
/*!40000 ALTER TABLE `xtract_schema` DISABLE KEYS */;
INSERT INTO `xtract_schema` VALUES ('1.04_rc10','2018-04-02 22:04:24');
/*!40000 ALTER TABLE `xtract_schema` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'schema104'
--
/*!50003 DROP PROCEDURE IF EXISTS `fix_all_tp_steps` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `fix_all_tp_steps`()
BEGIN
  DECLARE done INT DEFAULT 0;
  DECLARE tpid INT;
  DECLARE cur1 CURSOR FOR SELECT DISTINCT treatment_plan_id FROM treatplandetails;
  DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

  OPEN cur1;

  REPEAT
    FETCH cur1 INTO tpid;
    IF NOT done THEN
      call fix_tp_steps(tpid);
    END IF;
  UNTIL done END REPEAT;

  CLOSE cur1;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `fix_tp_steps` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `fix_tp_steps`(IN tpid INT)
BEGIN
    DECLARE maxid INT;
    DECLARE minid INT;
    set @maxid = (SELECT MAX(treatplandetails_id) FROM treatplandetails WHERE treatment_plan_id=tpid);
    set @minid = (SELECT MIN(treatplandetails_id) FROM treatplandetails WHERE treatment_plan_id=tpid);
    IF (SELECT treatplandetails_id FROM treatplandetails WHERE treatment_plan_id=tpid ORDER BY dilution desc, dose asc, treatplandetails_id asc limit 1) = @minid AND (SELECT treatplandetails_id FROM treatplandetails WHERE treatment_plan_id=tpid ORDER by dilution asc, dose desc, treatplandetails_id desc limit 1) = @maxid THEN
        
        UPDATE treatplandetails SET step=(treatplandetails_id - @minid) WHERE treatment_plan_id=tpid;
    ELSE
        SELECT concat('TP ',tpid,' does not pass required checks. See if plan is sorted by dilution and dose correctly.') as '';
    END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `xis_do_printlog` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `xis_do_printlog`(rid int, err text)
begin
    if exists (select * from reports where reports_id=rid and retries < 3) then
      update reports set retries=retries+1 where reports_id=rid;
      if exists (select * from reports where reports_id=rid and retries=3) then
        update reports set retries=3 where reports_id=rid;
        insert into printlog (filename, printdate, patient_id, user_id, terminal_id, printErrMsg) select output_path, submit_time, patient_id, user_id, terminal_id, `err` from reports where reports_id=rid;
      end if;
    end if;
  end ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Final view structure for view `pp_bottles`
--

/*!50001 DROP VIEW IF EXISTS `pp_bottles`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `pp_bottles` AS select `postpone`.`postpone_id` AS `postpone_id`,(case when (`postpone`.`compound_id1` = '0') then '0' when (`postpone`.`compound_id2` = '0') then `postpone`.`compound_id1` when (`postpone`.`compound_id3` = '0') then concat(`postpone`.`compound_id1`,',',`postpone`.`compound_id2`) when (`postpone`.`compound_id4` = '0') then concat(`postpone`.`compound_id1`,',',`postpone`.`compound_id2`,',',`postpone`.`compound_id3`) when (`postpone`.`compound_id5` = '0') then concat(`postpone`.`compound_id1`,',',`postpone`.`compound_id2`,',',`postpone`.`compound_id3`,',',`postpone`.`compound_id4`) when (`postpone`.`compound_id6` = '0') then concat(`postpone`.`compound_id1`,',',`postpone`.`compound_id2`,',',`postpone`.`compound_id3`,',',`postpone`.`compound_id4`,',',`postpone`.`compound_id5`) when (`postpone`.`compound_id7` = '0') then concat(`postpone`.`compound_id1`,',',`postpone`.`compound_id2`,',',`postpone`.`compound_id3`,',',`postpone`.`compound_id4`,',',`postpone`.`compound_id5`,',',`postpone`.`compound_id6`) when (`postpone`.`compound_id8` = '0') then concat(`postpone`.`compound_id1`,',',`postpone`.`compound_id2`,',',`postpone`.`compound_id3`,',',`postpone`.`compound_id4`,',',`postpone`.`compound_id5`,',',`postpone`.`compound_id6`,',',`postpone`.`compound_id7`) else concat(`postpone`.`compound_id1`,',',`postpone`.`compound_id2`,',',`postpone`.`compound_id3`,',',`postpone`.`compound_id4`,',',`postpone`.`compound_id5`,',',`postpone`.`compound_id6`,',',`postpone`.`compound_id7`,',',`postpone`.`compound_id8`) end) AS `bottles`,`postpone`.`user_id` AS `user_id`,`postpone`.`labelPrinted` AS `labelPrinted`,`postpone`.`deleted` AS `deleted` from `postpone` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `pt`
--

/*!50001 DROP VIEW IF EXISTS `pt`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `pt` AS select `patient`.`patient_id` AS `patient_id`,`patient`.`firstname` AS `firstname`,`patient`.`lastname` AS `lastname`,`patient`.`mi` AS `mi`,`patient`.`chart` AS `chart`,`patient`.`dob` AS `dob`,`patient`.`displayname` AS `displayname`,`patient`.`gender` AS `gender`,`patient`.`provider_id` AS `provider_id`,`patient`.`archived` AS `archived`,`patient`.`PV1segment` AS `PV1segment`,`patient`.`PIDsegment` AS `PIDsegment`,`patient`.`patient_notes` AS `patient_notes`,`patient`.`ssn` AS `ssn`,`patient`.`addr1` AS `addr1`,`patient`.`city` AS `city`,`patient`.`state` AS `state`,`patient`.`zip` AS `zip`,`patient`.`phone` AS `phone`,`patient`.`smsphone` AS `smsphone` from `patient` order by `patient`.`lastname`,`patient`.`firstname` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `pt_provider`
--

/*!50001 DROP VIEW IF EXISTS `pt_provider`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `pt_provider` AS select `p1`.`patient_id` AS `patient_id`,`p1`.`firstname` AS `firstname`,`p1`.`lastname` AS `lastname`,`p1`.`chart` AS `chart`,`p1`.`provider_id` AS `provider_id`,`p2`.`displayname` AS `displayname`,`p2`.`last` AS `last`,`p2`.`first` AS `first` from (`patient` `p1` left join `provider` `p2` on((`p1`.`provider_id` = `p2`.`provider_id`))) order by `p1`.`lastname`,`p1`.`firstname`,`p2`.`last` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `pt_rx_inj`
--

/*!50001 DROP VIEW IF EXISTS `pt_rx_inj`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `pt_rx_inj` AS select `p1`.`patient_id` AS `patient_id`,`p1`.`chart` AS `chart`,`p1`.`firstname` AS `firstname`,`p1`.`lastname` AS `lastname`,`p2`.`provider_config_id` AS `provider_config_id`,`p2`.`prescription_id` AS `prescription_id`,`p2`.`prescription_num` AS `prescription_num`,`p2`.`treatment_plan_id` AS `rx_treatment_plan_id`,`c`.`compound_id` AS `compound_id`,`c`.`name` AS `BottleName`,`c`.`size` AS `size`,`c`.`color` AS `color`,`c`.`dilution` AS `dilution`,`c`.`bottleNum` AS `bottleNum`,`c`.`active` AS `active`,`c`.`currVol` AS `currVol`,`i`.`injection_id` AS `injection_id`,`i`.`dose` AS `dose`,`i`.`site` AS `site`,`i`.`date` AS `injection_date`,`i`.`user_id` AS `user_id`,`i`.`inj_adjust_id` AS `inj_adjust_id`,`i`.`reaction` AS `reaction`,`i`.`sysreaction` AS `sysreaction`,`i`.`timestamp` AS `timestamp`,`i`.`treatment_plan_id` AS `inj_treatment_plan_id` from (((`patient` `p1` left join `prescription` `p2` on((`p1`.`patient_id` = `p2`.`patient_id`))) left join `compound` `c` on((`p2`.`prescription_id` = `c`.`rx_id`))) left join `injection` `i` on((`c`.`compound_id` = `i`.`compound_id`))) where (`i`.`deleted` = 'F') order by `p1`.`lastname`,`p1`.`firstname`,`p2`.`prescription_num`,`c`.`dilution`,`i`.`date` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `pt_rx_provider`
--

/*!50001 DROP VIEW IF EXISTS `pt_rx_provider`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `pt_rx_provider` AS select `p1`.`patient_id` AS `patient_id`,`p1`.`firstname` AS `firstname`,`p1`.`lastname` AS `lastname`,`p1`.`chart` AS `chart`,`p2`.`prescription_id` AS `prescription_id`,`p2`.`prescription_num` AS `prescription_num`,`p2`.`provider_id` AS `provider_id`,`p3`.`displayname` AS `displayname`,`p3`.`last` AS `last`,`p3`.`first` AS `first`,max(`i`.`date`) AS `last_injection` from ((((`patient` `p1` left join `prescription` `p2` on((`p1`.`patient_id` = `p2`.`patient_id`))) left join `provider` `p3` on((`p2`.`provider_id` = `p3`.`provider_id`))) left join `compound` `c` on((`p2`.`prescription_id` = `c`.`rx_id`))) left join `injection` `i` on((`c`.`compound_id` = `i`.`compound_id`))) group by `p2`.`prescription_id` order by `p1`.`lastname`,`p1`.`firstname`,`p2`.`prescription_num` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `rx_bottles`
--

/*!50001 DROP VIEW IF EXISTS `rx_bottles`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `rx_bottles` AS select `prescription`.`prescription_id` AS `prescription_id`,`prescription`.`prescription_num` AS `prescription_num`,group_concat(`c`.`compound_id` separator ',') AS `bottles`,`prescription`.`user_id` AS `user_id`,`prescription`.`strikethrough` AS `strikethrough`,`prescription`.`priority` AS `priority` from (`prescription` left join `compound` `c` on((`prescription`.`prescription_id` = `c`.`rx_id`))) group by `prescription`.`prescription_num` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `rx_co_vi_pt`
--

/*!50001 DROP VIEW IF EXISTS `rx_co_vi_pt`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `rx_co_vi_pt` AS select `v`.`mixdate` AS `mixdate`,`p2`.`prescription_id` AS `rx_id`,`p2`.`prescription_num` AS `rx_num`,`p2`.`strikethrough` AS `strikethrough`,`p2`.`treatment_plan_id` AS `treatment_plan_id`,`c`.`compound_id` AS `compound_id`,`c`.`compound_receipt_id` AS `compound_receipt_id`,`c`.`name` AS `VialName`,`c`.`size` AS `size`,`c`.`color` AS `color`,`c`.`dilution` AS `dilution`,`c`.`bottleNum` AS `bottleNum`,`c`.`active` AS `active`,`c`.`currVol` AS `currVol`,`v`.`dosing_id` AS `dosing_id`,`v`.`inventory_id` AS `inventory_id`,`p1`.`patient_id` AS `patient_id`,`p1`.`lastname` AS `lastname` from (((`prescription` `p2` left join `patient` `p1` on((`p1`.`patient_id` = `p2`.`patient_id`))) left join `compound` `c` on((`p2`.`prescription_id` = `c`.`rx_id`))) left join `vial` `v` on((`c`.`compound_id` = `v`.`compound_id`))) order by `p2`.`prescription_id` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `rx_extract`
--

/*!50001 DROP VIEW IF EXISTS `rx_extract`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `rx_extract` AS select `prescription`.`prescription_id` AS `rx_id`,`prescription`.`prescription_num` AS `prescription_num`,`extract`.`name` AS `name`,`extract`.`dilution` AS `dilution`,`dosing`.`dose` AS `dose`,`extract`.`extract_id` AS `extract_id`,`extract`.`deleted` AS `deleted`,`extract`.`abbreviation` AS `abbreviation`,`extract`.`latinname` AS `latinname`,`extract`.`manufacturer` AS `manufacturer`,`extract`.`code` AS `code`,`extract`.`isDiluent` AS `isDiluent`,`extract`.`percentGlycerin` AS `percentGlycerin`,`extract`.`percentPhenol` AS `percentPhenol`,`extract`.`percentHSA` AS `percentHSA`,`extract`.`units` AS `units`,`extract`.`seasonStart` AS `seasonStart`,`extract`.`seasonEnd` AS `seasonEnd`,`extract`.`cost` AS `cost`,`extract`.`sub` AS `sub`,`extract`.`specificgravity` AS `specificgravity`,`extract`.`outdatealert` AS `outdatealert`,`extract`.`visible` AS `visible`,`extract`.`imagefile` AS `imagefile`,`extract`.`color` AS `color`,`extract`.`silhouette` AS `silhouette`,`extract`.`topline` AS `topline`,`extract`.`firstline` AS `firstline`,`extract`.`secondline` AS `secondline`,`extract`.`compatibility_class_id` AS `compatibility_class_id` from ((`dosing` join `extract`) join `prescription`) where ((`dosing`.`extract_id` = `extract`.`extract_id`) and (`dosing`.`prescription_id` = `prescription`.`prescription_id`)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-07-27  7:46:46
