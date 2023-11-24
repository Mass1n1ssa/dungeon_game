-- MySQL dump 10.13  Distrib 8.0.19, for Win64 (x86_64)
--
-- Host: localhost    Database: game
-- ------------------------------------------------------
-- Server version	11.3.0-MariaDB

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
-- Table structure for table `monstres`
--

DROP TABLE IF EXISTS `monstres`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `monstres` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) DEFAULT NULL,
  `points_de_vie` int(11) DEFAULT NULL,
  `points_attaque` int(11) DEFAULT NULL,
  `points_defense` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `monstres`
--

LOCK TABLES `monstres` WRITE;
/*!40000 ALTER TABLE `monstres` DISABLE KEYS */;
INSERT INTO `monstres` VALUES (1,'Gobelin',80,10,10),(2,'Ogre',130,25,25),(3,'Spectre',170,30,50),(4,'Dragon',200,50,40),(5,'Hydre',240,80,50);
/*!40000 ALTER TABLE `monstres` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personnages`
--

DROP TABLE IF EXISTS `personnages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personnages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) DEFAULT NULL,
  `points_de_vie` int(11) DEFAULT NULL,
  `points_attaque` int(11) DEFAULT NULL,
  `points_defense` int(11) DEFAULT NULL,
  `experience` int(11) DEFAULT NULL,
  `niveau` int(11) DEFAULT NULL,
  `arme_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `arme_id` (`arme_id`),
  CONSTRAINT `personnages_ibfk_1` FOREIGN KEY (`arme_id`) REFERENCES `armes` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personnages`
--

LOCK TABLES `personnages` WRITE;
/*!40000 ALTER TABLE `personnages` DISABLE KEYS */;
INSERT INTO `personnages` VALUES (1,'Ilyass',80,16,10,0,1,1),(2,'Maxence',120,12,26,0,1,1),(3,'Thomas',110,14,12,0,1,1);
/*!40000 ALTER TABLE `personnages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventaire`
--

DROP TABLE IF EXISTS `inventaire`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventaire` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `personnage_id` int(11) DEFAULT NULL,
  `objet_id` int(11) DEFAULT NULL,
  `quantite` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `personnage_id` (`personnage_id`),
  KEY `objet_id` (`objet_id`),
  CONSTRAINT `inventaire_ibfk_1` FOREIGN KEY (`personnage_id`) REFERENCES `personnages` (`id`),
  CONSTRAINT `inventaire_ibfk_2` FOREIGN KEY (`objet_id`) REFERENCES `objets` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventaire`
--

LOCK TABLES `inventaire` WRITE;
/*!40000 ALTER TABLE `inventaire` DISABLE KEYS */;
INSERT INTO `inventaire` VALUES (1,3,3,1),(2,3,13,1),(3,3,15,1),(4,3,4,1),(5,3,6,1),(6,3,9,1),(7,1,15,1),(8,2,12,1);
/*!40000 ALTER TABLE `inventaire` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `salles`
--

DROP TABLE IF EXISTS `salles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `salles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `salles`
--

LOCK TABLES `salles` WRITE;
/*!40000 ALTER TABLE `salles` DISABLE KEYS */;
INSERT INTO `salles` VALUES (1,'enigme','Une salle remplie d\'enigmes a resoudre.'),(2,'piege','Une salle remplie de pièges mortels.'),(3,'marchand','Une salle ou se trouve un marchand proposant des echanges.'),(4,'monstre','Une salle peuplee de monstres redoutables.');
/*!40000 ALTER TABLE `salles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `objets`
--

DROP TABLE IF EXISTS `objets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `objets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `points_attaque_bonus` int(11) DEFAULT NULL,
  `niveau_requis` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `objets`
--

LOCK TABLES `objets` WRITE;
/*!40000 ALTER TABLE `objets` DISABLE KEYS */;
INSERT INTO `objets` VALUES (1,'Parchemin de feu','parchemin',3,2),(2,'Parchemin de glace','parchemin',5,4),(3,'Parchemin de foudre','parchemin',8,7),(4,'Epee en bois','arme',5,1),(5,'Hache de fer','arme',10,3),(6,'Dague empoisonnee','arme',12,4),(7,'Marteau de guerre','arme',15,5),(8,'Lance','arme',18,6),(9,'Epee longue','arme',20,7),(10,'Faux du destin','arme',25,8),(11,'Hallebarde infernale','arme',30,9),(12,'Excalibur','arme',40,10),(13,'Arc long','arme',15,5),(14,'Elixir de force','potion',0,3),(15,'Elixir de puissance','potion',0,7),(16,'Amulette de protection','accessoire',5,4),(17,'Anneau de resistance','accessoire',8,6);
/*!40000 ALTER TABLE `objets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `enigmes`
--

DROP TABLE IF EXISTS `enigmes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `enigmes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question` text NOT NULL,
  `reponse` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `enigmes`
--

LOCK TABLES `enigmes` WRITE;
/*!40000 ALTER TABLE `enigmes` DISABLE KEYS */;
INSERT INTO `enigmes` VALUES (1,'Quel est le nom de la capitale de la France ?','Paris'),(2,'Combien de continents y a-t-il sur Terre ?','7');
/*!40000 ALTER TABLE `enigmes` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-11-24 16:43:58
