-- MySQL dump 10.13  Distrib 5.7.23, for Win64 (x86_64)
--
-- Host: localhost    Database: native
-- ------------------------------------------------------
-- Server version	5.7.23

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
-- Table structure for table `carscars`
--

DROP TABLE IF EXISTS `carscars`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `carscars` (
  `category` varchar(50) DEFAULT NULL,
  `color` varchar(50) DEFAULT NULL,
  `Date Listed` date DEFAULT NULL,
  `descr` longtext,
  `EPACity` varchar(50) DEFAULT NULL,
  `EPAHighway` varchar(50) DEFAULT NULL,
  `features` longtext,
  `Horsepower` int(11) DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Make` varchar(50) DEFAULT NULL,
  `Model` varchar(50) DEFAULT NULL,
  `Phone #` varchar(50) DEFAULT NULL,
  `Picture` longblob,
  `Price` int(11) DEFAULT NULL,
  `UserID` varchar(50) DEFAULT NULL,
  `YearOfMake` int(11) DEFAULT NULL,
  `zipcode` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carscars`
--

LOCK TABLES `carscars` WRITE;
/*!40000 ALTER TABLE `carscars` DISABLE KEYS */;
INSERT INTO `carscars` VALUES ('Passenger Cars','Galaxy Gray Metallic','2007-04-05','Concept and name\r\rThe TT was first shown as a concept car at the 1995 Frankfurt Motor Show. The design is credited to J Mays and Freeman Thomas of Volkswagen\'s California design studio, with Martin Smith contributing to the award winning interior design. The TT name does not indicate \"twin turbo\" as is sometimes assumed. The car is named for the NSU TT, a small rear-engine model with a formidable racing pedigree that NSU produced in the 1960s and was based on the NSU Prinz, although the modern TT shares next to nothing with that model\'s design concepts. The NSU TT was, in turn, named for the famous Isle of Man TT (Tourist Trophy) motorcycle races.\r\rDesign\r\rThe TT\'s styling is regarded by many as a watershed moment in automobile design. From its introduction as a concept car in 1995, and as a production car in 1998, the design was regarded by many as bold, innovative, and revolutionary. While the car borrowed a few design elements from earlier vehicles, the overall design was considered by many to be truly unique. Despite its smooth-curved appeal, the design does not lead to revolutionary aerodynamics â€” the drag coefficient of the body is actually a relatively high 0.35 [1]. But with its distinctive, rounded bodywork, bold use of bare anodized aluminum, and a lack of defined bumpers, the TT represented a departure from much of the styling that dominated the car market at that time.\r\rThe success and popularity of the TT\'s iconic design gave many automotive designers (and manufacturers) greater latitude to experiment with bold, distinctive design. The TT\'s influence can be seen in the design elements of many vehicles released after the TT.\r\rThe TT is often regarded as the vehicle that made people take a second look at Audi. No longer just a second-tier European maker, Audi emerged as a serious competitor for the likes of BMW and Mercedes-Benz. The then-new B5-platform A4 model was a substantial improvement on its Audi 80 predecessor; these two models firmly secured Audi\'s position as a prestige marque.\r\rPerformance Models\r\rIn the 2008 Detroit Motor Show, Audi released the TTS with a 2.0TFSI engine tuned to 268 hp (200 kW). It is also rumoured that a higher end TT-RS is under development, using an all-new turbocharged 2.5L 5-cylinder engine capable of up to 350 PS (345 hp/257 kW).\r\rAwards\r\rThe TT was nominated for the North American Car of the Year award for 2000. It was also on Car and Driver magazine\'s Ten Best list for 2000 and 2001.\r\rThe second generation TT has been honored with many awards including the inaugural Drive Car of the Year, Top Gear Coupe of the Year 2006, Fifth Gear Car of the Year 2006, Autobild \'Most Beautiful Car\' and World Design Car of the Year 2007, as well as being a finalist for World Car of the Year..','','','',197,2,'Audi','TT','555-12-34',_binary 'ÿ\Øÿ\à',57900,'admin',2000,12345),('Sports Cars','Nighthawk Black Pearl','2007-04-24','The 5 Series got its name by being the fifth of the \"new series\" cars after the V-8 and Isetta era. The preceding models were the 700, the \"New Class\", the \"New Six\" 2500/2800/Bavaria and the CS. The 5 Series was intended to replace the smaller New Class sedans, leaving the coupes as the company\'s low-end model.\r\rThe body was styled by Marcello Gandini, based on the Bertone 1970 BMW Garmisch 2002ti Geneva show car. Gandini also did the Fiat 132 and Alfa Romeo Alfetta, two other cars that have a similar design.\r\rThere have been five generations of the 5 Series to date. To differentiate between them, they are referred to by their unique chassis numbers (EXX).\r\rThe 5 Series began the BMW tradition of being named with a three-digit number. The first digit (5 in this case) represents the model, and the following two digits (usually) represent the size of the engine in decilitres, which is the main distinguishing difference. Additional letters or words may be added to the end of the three-digit number to define the fuel type (petrol or diesel), engine or transmission details, and the body style. The \'i\' originally stood for (fuel) \'injection\'.\rThe BMW E28 was the second BMW 5 Series, a stylistic evolution of the E12.\r\rThe following models were sold in Europe:\r    * 518/518i- 1.8 L M10B18\r    * 520i - 2.0 L M20B20 I6\r    524d - 2.4 L M21 diesel I6\r    * 524td - 2.4 L M21 turbodiesel I6\r    * 525i - 2.5 L M30B25 I6\r    * 525e - 2.7 L M20B27 I6\rHigh performance 5 Series - 24-valve DOHC, I6, six throttle bodies, Bosch Motronic integrated fuel injection. At its launch in 1984, the European specification E28 M5 was the fastest production sedan in the world.\r\rVisible changes to this model included revised headlights, Thicker rubber bumper surrounds and large rectangular taillights. The shape was more box shaped than rounded at the rear.\r\rAwards\r\rThe E39 5 Series was on Car and Driver magazine\'s annual Ten Best list for six years straight, from its introduction in 1997 through 2002. It was also Motor Trend\'s Import Car of the Year for 1997 and What Car? Executive Car of the Year 1997 through 2002. The E60 was named \"Best New Luxury / Prestige Car\" in the 2006 Canadian Car of the Year awards. Active Seat [3] continuous passive motion seating comfort technology recognized as one of the Best Inventions of 1998 by Popular Science magazine. Consumer Reports found the E39 5 series their best car tested in 2001-2002.','','','',215,3,'BMW','525i','1234567',_binary 'ÿ\Øÿ\à',41000,'admin',2004,1234567);
/*!40000 ALTER TABLE `carscars` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `carsmake`
--

DROP TABLE IF EXISTS `carsmake`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `carsmake` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `make` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carsmake`
--

LOCK TABLES `carsmake` WRITE;
/*!40000 ALTER TABLE `carsmake` DISABLE KEYS */;
INSERT INTO `carsmake` VALUES (9,'Audi'),(10,'BMW'),(11,'Hyundai'),(12,'Nissan'),(13,'Porsche'),(14,'Mazda'),(15,'Lexus');
/*!40000 ALTER TABLE `carsmake` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `carsmodels`
--

DROP TABLE IF EXISTS `carsmodels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `carsmodels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `make` varchar(50) DEFAULT NULL,
  `model` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=41 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carsmodels`
--

LOCK TABLES `carsmodels` WRITE;
/*!40000 ALTER TABLE `carsmodels` DISABLE KEYS */;
INSERT INTO `carsmodels` VALUES (5,'Honda','Civic'),(6,'Honda','HR-V'),(7,'Honda','Pilot'),(8,'Mersedes','Cords'),(9,'Toyota','Corona'),(10,'Toyota','RAV4'),(11,'Toyota','Vitz'),(12,'Subaru','Forester'),(13,'Subaru','Impreza'),(14,'Subaru','Legacy'),(15,'Saab','9000'),(16,'Volkswagen','Golf'),(17,'Volkswagen','Passat 1.8 Turbo Sedan'),(18,'Jaguar','XJ 3.0 i V6 24V'),(19,'Audi','A3 2.0'),(20,'Audi','A4 1.8T Quattro'),(21,'Audi','A4 3.0 Cabrio'),(22,'Audi','A6 2.4'),(23,'Audi','TT'),(24,'BMW','520i'),(25,'BMW','525i'),(26,'BMW','Z4'),(27,'Hyundai','Accent'),(28,'Hyundai','Elantra'),(29,'Hyundai','Getz'),(30,'Hyundai','Sonata'),(31,'Hyundai','Tucson'),(32,'Nissan','Maxima'),(33,'Nissan','Primera'),(34,'Nissan','Terrano'),(35,'Porsche','Cayenne'),(36,'Mazda','3'),(37,'Mazda','6 2.3'),(38,'Mazda','626'),(39,'Lexus','RX 300'),(40,'Honda','Civic Hybrid');
/*!40000 ALTER TABLE `carsmodels` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `carsusers`
--

DROP TABLE IF EXISTS `carsusers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `carsusers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `password` varchar(50) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carsusers`
--

LOCK TABLES `carsusers` WRITE;
/*!40000 ALTER TABLE `carsusers` DISABLE KEYS */;
INSERT INTO `carsusers` VALUES (1,'admin','admin'),(2,'user','user');
/*!40000 ALTER TABLE `carsusers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `project39_blocking`
--

DROP TABLE IF EXISTS `project39_blocking`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `project39_blocking` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tablename` varchar(250) NOT NULL,
  `startdatetime` datetime NOT NULL,
  `confirmdatetime` datetime NOT NULL,
  `keys` varchar(250) NOT NULL,
  `sessionid` varchar(100) NOT NULL,
  `userid` varchar(250) NOT NULL,
  `action` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=174 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project39_blocking`
--

LOCK TABLES `project39_blocking` WRITE;
/*!40000 ALTER TABLE `project39_blocking` DISABLE KEYS */;
INSERT INTO `project39_blocking` VALUES (17,'carsmodels','2009-10-19 11:14:17','2009-10-19 11:14:17','12','f43h2u4g1g232c6pla44r77ad6','1',1),(61,'carsmodels','2009-10-19 11:19:55','2009-10-19 11:19:55','4','ll3e73bj0pv7d52stc3kjj8c33','admin',1),(172,'carsmake','2009-10-20 11:23:01','2009-10-20 11:23:01','1','jbbhp99if6hmqtuqjfoe770e92','admin',1),(173,'carsmake','2009-10-20 11:23:02','2009-10-20 11:23:02','1','jbbhp99if6hmqtuqjfoe770e92','admin',1);
/*!40000 ALTER TABLE `project39_blocking` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `project41_audit`
--

DROP TABLE IF EXISTS `project41_audit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `project41_audit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `datetime` datetime NOT NULL,
  `ip` varchar(15) NOT NULL,
  `user` varchar(250) NOT NULL,
  `table` varchar(250) NOT NULL,
  `action` varchar(250) NOT NULL,
  `description` longtext,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=53 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project41_audit`
--

LOCK TABLES `project41_audit` WRITE;
/*!40000 ALTER TABLE `project41_audit` DISABLE KEYS */;
INSERT INTO `project41_audit` VALUES (33,'2009-10-20 10:54:42','127.0.0.1','Guest','carsusers','login',''),(34,'2009-10-20 10:54:45','127.0.0.1','Guest','carsusers','logout',''),(35,'2009-10-20 10:54:49','127.0.0.1','','carsusers','failed login',''),(37,'2009-10-20 10:55:34','127.0.0.1','Guest','carsusers','login',''),(38,'2009-10-20 10:55:43','127.0.0.1','Guest','carsusers','logout',''),(39,'2009-10-20 10:55:47','127.0.0.1','Guest','carsusers','login',''),(40,'2009-10-20 10:55:56','127.0.0.1','Guest','carsusers','logout',''),(41,'2009-10-20 10:56:00','127.0.0.1','','carsusers','logout',''),(42,'2009-10-20 10:56:06','127.0.0.1','','carsusers','logout',''),(43,'2009-10-20 11:48:05','127.0.0.1','admin','carsusers','logout',''),(44,'2009-10-20 11:48:11','127.0.0.1','admin','carsusers','login',''),(45,'2009-10-20 11:49:27','127.0.0.1','admin','carsmodels','add',''),(46,'2009-10-20 11:49:41','127.0.0.1','admin','carsmake','add','---Keys\r\nid : 16\r\n---Fields\r\nmake [new]: 1\r\n'),(47,'2009-10-20 12:07:38','127.0.0.1','admin','carsmake','delete','---Keys\r\nid : 8\r\n---Fields\r\nmake [old]: Jaguar\r\n'),(48,'2009-10-20 12:09:39','127.0.0.1','admin','carsmodels','delete',''),(49,'2009-10-20 12:09:53','127.0.0.1','admin','carsmake','delete','---Keys\r\nid : 5\r\n---Fields\r\nmake [old]: Subaru\r\n'),(50,'2009-10-20 12:19:10','127.0.0.1','admin','carsmake','delete','---Keys\r\nid : 17\r\n---Fields\r\nmake [old]: 1\r\n'),(51,'2009-10-20 12:19:23','127.0.0.1','admin','carsmodels','delete','---Keys\r\nid : 41\r\n---Fields\r\nmake [old]: 1\r\nmodel [old]: 1\r\n'),(52,'2009-10-20 12:19:23','127.0.0.1','admin','carsmodels','delete','---Keys\r\nid : 42\r\n---Fields\r\nmake [old]: 1\r\nmodel [old]: 1\r\n');
/*!40000 ALTER TABLE `project41_audit` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-02-21 23:01:49
