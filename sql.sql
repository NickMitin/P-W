-- MySQL dump 10.13  Distrib 5.1.54, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: ff
-- ------------------------------------------------------
-- Server version	5.1.54-1ubuntu4

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
-- Table structure for table `dataObjectField`
--

DROP TABLE IF EXISTS `dataObjectField`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dataObjectField` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `propertyName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `fieldName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `dataType` int(10) unsigned NOT NULL,
  `defaultValue` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `localName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dataObjectField`
--

LOCK TABLES `dataObjectField` WRITE;
/*!40000 ALTER TABLE `dataObjectField` DISABLE KEYS */;
INSERT INTO `dataObjectField` VALUES (1,'email','email',1,'0','a:6:{s:10:\"nominative\";s:5:\"email\";s:8:\"genitive\";s:5:\"email\";s:6:\"dative\";s:5:\"email\";s:8:\"accusive\";s:5:\"email\";s:8:\"creative\";s:5:\"email\";s:13:\"prepositional\";s:5:\"email\";}',0),(2,'password','password',1,'0','a:6:{s:10:\"nominative\";s:8:\"password\";s:8:\"genitive\";s:8:\"password\";s:6:\"dative\";s:8:\"password\";s:8:\"accusive\";s:8:\"password\";s:8:\"creative\";s:8:\"password\";s:13:\"prepositional\";s:8:\"password\";}',0),(3,'type','type',2,'0','a:6:{s:10:\"nominative\";s:4:\"type\";s:8:\"genitive\";s:4:\"type\";s:6:\"dative\";s:4:\"type\";s:8:\"accusive\";s:4:\"type\";s:8:\"creative\";s:4:\"type\";s:13:\"prepositional\";s:4:\"type\";}',0);
/*!40000 ALTER TABLE `dataObjectField` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dataObjectMap`
--

DROP TABLE IF EXISTS `dataObjectMap`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dataObjectMap` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dataObjectMap`
--

LOCK TABLES `dataObjectMap` WRITE;
/*!40000 ALTER TABLE `dataObjectMap` DISABLE KEYS */;
INSERT INTO `dataObjectMap` VALUES (1,'user',1);
/*!40000 ALTER TABLE `dataObjectMap` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `link_dataObjectMap_dataObjectField`
--

DROP TABLE IF EXISTS `link_dataObjectMap_dataObjectField`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `link_dataObjectMap_dataObjectField` (
  `dataObjectMapId` int(10) unsigned NOT NULL,
  `dataObjectFieldId` int(10) unsigned NOT NULL,
  PRIMARY KEY (`dataObjectMapId`,`dataObjectFieldId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `link_dataObjectMap_dataObjectField`
--

LOCK TABLES `link_dataObjectMap_dataObjectField` WRITE;
/*!40000 ALTER TABLE `link_dataObjectMap_dataObjectField` DISABLE KEYS */;
INSERT INTO `link_dataObjectMap_dataObjectField` VALUES (1,1),(1,2),(1,3);
/*!40000 ALTER TABLE `link_dataObjectMap_dataObjectField` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `link_referenceField_dataObjectMap`
--

DROP TABLE IF EXISTS `link_referenceField_dataObjectMap`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `link_referenceField_dataObjectMap` (
  `dataObjectMapId` int(10) unsigned NOT NULL,
  `referenceFieldId` int(10) unsigned NOT NULL,
  UNIQUE KEY `referenceFieldId` (`referenceFieldId`,`dataObjectMapId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `link_referenceField_dataObjectMap`
--

LOCK TABLES `link_referenceField_dataObjectMap` WRITE;
/*!40000 ALTER TABLE `link_referenceField_dataObjectMap` DISABLE KEYS */;
/*!40000 ALTER TABLE `link_referenceField_dataObjectMap` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `link_referenceMap_referenceField`
--

DROP TABLE IF EXISTS `link_referenceMap_referenceField`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `link_referenceMap_referenceField` (
  `referenceMapId` int(10) unsigned NOT NULL,
  `referenceFieldId` int(10) unsigned NOT NULL,
  `referenceFieldType` int(11) NOT NULL COMMENT '1 - основной объект, 2 - зависимый объект, 3 - дополнительный объект; 4 - свойство.',
  PRIMARY KEY (`referenceMapId`,`referenceFieldId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `link_referenceMap_referenceField`
--

LOCK TABLES `link_referenceMap_referenceField` WRITE;
/*!40000 ALTER TABLE `link_referenceMap_referenceField` DISABLE KEYS */;
/*!40000 ALTER TABLE `link_referenceMap_referenceField` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `referenceField`
--

DROP TABLE IF EXISTS `referenceField`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `referenceField` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `propertyName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `fieldName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `dataType` int(10) unsigned NOT NULL,
  `defaultValue` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `localName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `referenceField`
--

LOCK TABLES `referenceField` WRITE;
/*!40000 ALTER TABLE `referenceField` DISABLE KEYS */;
/*!40000 ALTER TABLE `referenceField` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `referenceMap`
--

DROP TABLE IF EXISTS `referenceMap`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `referenceMap` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `referenceMap`
--

LOCK TABLES `referenceMap` WRITE;
/*!40000 ALTER TABLE `referenceMap` DISABLE KEYS */;
/*!40000 ALTER TABLE `referenceMap` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `type` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'','guest',0);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2011-05-14 20:39:09
