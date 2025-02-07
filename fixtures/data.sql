-- MySQL dump 10.13  Distrib 8.0.40, for Linux (x86_64)
--
-- Host: db    Database: thedatabase
-- ------------------------------------------------------
-- Server version	8.0.40

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
-- Dumping data for table `assignment`
--

LOCK TABLES `assignment` WRITE;
/*!40000 ALTER TABLE `assignment` DISABLE KEYS */;
INSERT INTO `assignment` (`id`, `caption`, `description`, `classes`, `school_year`, `public`, `published`, `soft_deadline`, `hard_deadline`, `owner_id`) VALUES (1,'Maturitní práce',NULL,'I4',2024,1,1,NULL,NULL,1),(2,'Test DOCTRINE',NULL,'I4',2024,1,0,NULL,NULL,1);
/*!40000 ALTER TABLE `assignment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` (`id`, `username`, `name`, `original_role`, `original_student_class`, `effective_role`, `effective_student_class`, `restorable_role`, `last_login_at`) VALUES (1,'sterzik','Marek Sterzik','ROLE_TEACHER',NULL,'ROLE_SUPERADMIN',NULL,NULL,'2025-02-07 09:13:27'),(2,'ucitel.ucitelovic','Učitel Učitelovič','ROLE_TEACHER',NULL,'ROLE_STUDENT','AT1',NULL,NULL),(3,'skolnik.skolnikovic','Školník Školníkovič','ROLE_OTHER',NULL,NULL,NULL,NULL,NULL),(4,'student.studentovic','Student Studentovič','ROLE_STUDENT','I4',NULL,NULL,NULL,NULL),(5,'student.studentovic2','Student Studentovič Nezvěstný','ROLE_STUDENT',NULL,NULL,NULL,NULL,NULL),(6,'student.adminovic','Student Adminovič','ROLE_STUDENT','E4','ROLE_ADMIN',NULL,NULL,NULL),(7,'admin.studentovic','Učitel Studentovič','ROLE_TEACHER',NULL,'ROLE_STUDENT','E1',NULL,NULL),(8,'superadmin.superadminovic','Superadmin Superadminovič','ROLE_TEACHER',NULL,'ROLE_SUPERADMIN',NULL,NULL,NULL);
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

-- Dump completed on 2025-02-07  9:25:49
