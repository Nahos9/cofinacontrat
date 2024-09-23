-- MySQL dump 10.13  Distrib 8.0.36, for Linux (x86_64)
--
-- Host: localhost    Database: cofina_credit
-- ------------------------------------------------------
-- Server version	8.0.36-0ubuntu0.23.10.1

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
-- Table structure for table `c_a_t_s`
--

DROP TABLE IF EXISTS `c_a_t_s`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `c_a_t_s` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `contract_id` bigint unsigned DEFAULT NULL,
  `notification_id` bigint unsigned DEFAULT NULL,
  `credit_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sector` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_deadline` date NOT NULL,
  `last_deadline` date NOT NULL,
  `source_of_reimbursement` enum('revenue_from_the_activity','final_payer_settlement','resale_of_goods') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `instructions_from_the_risk_and_credit_department` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `outstanding_number_ready_to_settle` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `other_expenses` decimal(30,3) NOT NULL,
  `teg` decimal(30,3) NOT NULL,
  `validator_user_id` bigint unsigned DEFAULT NULL,
  `validation_status` enum('waiting','rejected','validated') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `validation_comment` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unblocker_user_id` bigint unsigned DEFAULT NULL,
  `unblock_status` enum('waiting','rejected','validated') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `unblock_comment` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `c_a_t_s_contract_id_foreign` (`contract_id`),
  KEY `c_a_t_s_notification_id_foreign` (`notification_id`),
  KEY `c_a_t_s_validator_user_id_foreign` (`validator_user_id`),
  KEY `c_a_t_s_unblocker_user_id_foreign` (`unblocker_user_id`),
  CONSTRAINT `c_a_t_s_contract_id_foreign` FOREIGN KEY (`contract_id`) REFERENCES `contracts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `c_a_t_s_notification_id_foreign` FOREIGN KEY (`notification_id`) REFERENCES `notifications` (`id`) ON DELETE CASCADE,
  CONSTRAINT `c_a_t_s_unblocker_user_id_foreign` FOREIGN KEY (`unblocker_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `c_a_t_s_validator_user_id_foreign` FOREIGN KEY (`validator_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `c_a_t_s`
--

LOCK TABLES `c_a_t_s` WRITE;
/*!40000 ALTER TABLE `c_a_t_s` DISABLE KEYS */;
/*!40000 ALTER TABLE `c_a_t_s` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `companies`
--

DROP TABLE IF EXISTS `companies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `companies` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `contract_id` bigint unsigned NOT NULL,
  `denomination` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `legal_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `head_office_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `rccm_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `companies_contract_id_foreign` (`contract_id`),
  CONSTRAINT `companies_contract_id_foreign` FOREIGN KEY (`contract_id`) REFERENCES `contracts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `companies`
--

LOCK TABLES `companies` WRITE;
/*!40000 ALTER TABLE `companies` DISABLE KEYS */;
INSERT INTO `companies` VALUES (1,2,'VICKY ORISAKWE','SARL U','LOME ADAWLATO','TG-LFW-01-2023-B13-00557','+228 92313128','2024-05-13 15:09:57','2024-05-13 15:09:57');
/*!40000 ALTER TABLE `companies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contracts`
--

DROP TABLE IF EXISTS `contracts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contracts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `verbal_trial_id` bigint unsigned NOT NULL,
  `representative_birth_date` date NOT NULL,
  `representative_birth_place` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `representative_nationality` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `representative_home_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `representative_phone_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `representative_type_of_identity_document` enum('cni','passport','residence_certificate','driving_licence') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cni',
  `representative_number_of_identity_document` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `representative_date_of_issue_of_identity_document` date NOT NULL,
  `risk_premium_percentage` double(8,2) NOT NULL,
  `total_amount_of_interest` decimal(30,10) NOT NULL,
  `number_of_due_dates` int NOT NULL,
  `type` enum('particular','company','individual_business') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `has_pledges` tinyint(1) NOT NULL DEFAULT '0',
  `creator_id` bigint unsigned NOT NULL,
  `signed_contract_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `signed_promissory_note_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('waiting','rejected','validated') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'waiting',
  `status_observation` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `contracts_verbal_trial_id_foreign` (`verbal_trial_id`),
  KEY `contracts_creator_id_foreign` (`creator_id`),
  CONSTRAINT `contracts_creator_id_foreign` FOREIGN KEY (`creator_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `contracts_verbal_trial_id_foreign` FOREIGN KEY (`verbal_trial_id`) REFERENCES `verbals_trials` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contracts`
--

LOCK TABLES `contracts` WRITE;
/*!40000 ALTER TABLE `contracts` DISABLE KEYS */;
INSERT INTO `contracts` VALUES (1,1,'1988-09-13','TOGBLEKOPE','Togolaise','LOME','90155860','cni','0003-796-5064','2021-07-21',0.00,152369.0000000000,12,'particular',0,4,NULL,NULL,'waiting',NULL,'2024-05-13 07:53:45','2024-05-13 07:53:45'),(2,4,'1980-05-26','ANAMBRA','NIGERIA','LOME','+228 92313128','residence_certificate','0023-ne1275-0007908','2024-02-14',0.00,1545193.0000000000,18,'company',0,5,NULL,NULL,'waiting',NULL,'2024-05-13 15:09:57','2024-05-13 15:09:57'),(3,9,'1974-04-26','KINI KONDJI','Togolaise','LOME KPAGAN','+228 90374494','cni','0576-447-3062','2022-07-08',0.00,772597.0000000000,18,'particular',0,5,NULL,NULL,'waiting',NULL,'2024-05-14 08:22:27','2024-05-14 08:22:27');
/*!40000 ALTER TABLE `contracts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `deadline_postponed_files`
--

DROP TABLE IF EXISTS `deadline_postponed_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `deadline_postponed_files` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `deadline_postponed_id` bigint unsigned NOT NULL,
  `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `deadline_postponed_files_deadline_postponed_id_foreign` (`deadline_postponed_id`),
  CONSTRAINT `deadline_postponed_files_deadline_postponed_id_foreign` FOREIGN KEY (`deadline_postponed_id`) REFERENCES `deadline_postponeds` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `deadline_postponed_files`
--

LOCK TABLES `deadline_postponed_files` WRITE;
/*!40000 ALTER TABLE `deadline_postponed_files` DISABLE KEYS */;
/*!40000 ALTER TABLE `deadline_postponed_files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `deadline_postponeds`
--

DROP TABLE IF EXISTS `deadline_postponeds`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `deadline_postponeds` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `caf_id` bigint unsigned NOT NULL,
  `credit_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `deadline_number` int NOT NULL,
  `old_date` date NOT NULL,
  `new_date` date NOT NULL,
  `request_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `memo_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('waiting_ca','rejected_by_ca','waiting_dex','rejected_by_dex','waiting_head','rejected_by_head','waiting_md','rejected_by_md','waiting_credit_admin','rejected_by_credit_admin','waiting_report','reported') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'waiting_ca',
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `extension` int NOT NULL,
  `beneficiary_label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `loan_amount` decimal(21,2) NOT NULL,
  `representative_civility` enum('Mr','Mme','Mlle') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `representative_last_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `representative_first_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `deadline_postponeds_caf_id_foreign` (`caf_id`),
  CONSTRAINT `deadline_postponeds_caf_id_foreign` FOREIGN KEY (`caf_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `deadline_postponeds`
--

LOCK TABLES `deadline_postponeds` WRITE;
/*!40000 ALTER TABLE `deadline_postponeds` DISABLE KEYS */;
/*!40000 ALTER TABLE `deadline_postponeds` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `guarantees`
--

DROP TABLE IF EXISTS `guarantees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `guarantees` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `verbal_trial_id` bigint unsigned NOT NULL,
  `value` decimal(21,2) NOT NULL,
  `expiration_date` date NOT NULL,
  `type_of_guarantee_id` bigint unsigned NOT NULL,
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `guarantees_verbal_trial_id_foreign` (`verbal_trial_id`),
  KEY `guarantees_type_of_guarantee_id_foreign` (`type_of_guarantee_id`),
  CONSTRAINT `guarantees_type_of_guarantee_id_foreign` FOREIGN KEY (`type_of_guarantee_id`) REFERENCES `types_of_guarantee` (`id`) ON DELETE CASCADE,
  CONSTRAINT `guarantees_verbal_trial_id_foreign` FOREIGN KEY (`verbal_trial_id`) REFERENCES `verbals_trials` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=196 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `guarantees`
--

LOCK TABLES `guarantees` WRITE;
/*!40000 ALTER TABLE `guarantees` DISABLE KEYS */;
INSERT INTO `guarantees` VALUES (4,1,300000.00,'2025-05-10',1,'•	Dépôt de garantie et compte de réserve du service de la dette (DG + CRSD) à hauteur de 15% du montant sollicité soit F CFA 300 000','2024-05-07 17:43:29','2024-05-07 17:43:29'),(5,1,2000000.00,'2025-05-10',2,'•	Caution personnelle et solidaire de M. PETHOS GBETOR RICO et de M. SOEDJE FOH KOUAKOU','2024-05-07 17:43:29','2024-05-07 17:43:29'),(6,1,2000000.00,'2025-05-10',5,'signature du billet à ordre','2024-05-07 17:43:29','2024-05-07 17:43:29'),(9,2,8000000.00,'2025-05-10',1,'Dépôt de garantie de 20%','2024-05-07 18:00:40','2024-05-07 18:00:40'),(10,2,8000000.00,'2025-05-10',2,'caution personnelle et solidaire de MIKEM KUDZO ELOM et ANANI-MEKLE RODRIGUE YAOVI','2024-05-07 18:00:40','2024-05-07 18:00:40'),(11,2,8000000.00,'2025-05-10',5,'Signature du billet à ordre','2024-05-07 18:00:40','2024-05-07 18:00:40'),(15,3,600000.00,'2025-11-10',1,'•	Dépôt de garantie et compte de réserve du service de la dette (DG + CRSD) à hauteur de 15% du montant sollicité soit F CFA 600 000','2024-05-07 18:12:35','2024-05-07 18:12:35'),(16,3,4000000.00,'2025-11-10',2,'•	Caution personnelle et solidaire de M. HOUANOU JEAN ALBERT et de Mme ADUKPO ABRA','2024-05-07 18:12:35','2024-05-07 18:12:35'),(17,3,4000000.00,'2025-11-10',5,'•	Signature du Billet à ordre','2024-05-07 18:12:35','2024-05-07 18:12:35'),(18,4,10000000.00,'2025-11-10',1,'Dépôt de garantie de 20%','2024-05-07 18:15:28','2024-05-07 18:15:28'),(19,4,10000000.00,'2025-11-10',2,'Caution personnelle et solidaire de Mr  ADJALLAH AMOUSSOU et de  Mr ORISAKWE NELSON MOORE','2024-05-07 18:15:28','2024-05-07 18:15:28'),(20,4,1000000.00,'2025-11-10',6,'Flux mensuel de 2 500 000 par mois','2024-05-07 18:15:28','2024-05-07 18:15:28'),(21,5,1000000.00,'2025-11-10',1,'•	Dépôt de garantie et compte de réserve du service de la dette (DG + CRSD) à hauteur de 20% du montant sollicité soit F CFA 1 000 000 ;','2024-05-07 18:53:36','2024-05-07 18:53:36'),(22,5,5000000.00,'2025-11-10',2,'•	Caution personnelle et solidaire de Madame AGBONON TCHOTCHO ROSE Epse UCHE et de Monsieur AMEGNIAVE ABLAM ;','2024-05-07 18:53:36','2024-05-07 18:53:36'),(23,5,5000000.00,'2025-11-10',5,'•	Signature du Billet à ordre.','2024-05-07 18:53:36','2024-05-07 18:53:36'),(26,6,1500000.00,'2026-02-05',1,'Dépôt de garantie de FCFA 1 500 000, soit 20% du montant du crédit ;','2024-05-08 17:14:34','2024-05-08 17:14:34'),(27,6,7500000.00,'2026-02-05',2,'Caution personnelle et solidaire de M. KUDONU ATSOU et de M. KAKOU TCHAOU KPATCHA ;\nDépôt libre de documents administratifs d\'un bien immeuble sis à Lomé KPOTA ADIDOME et appartenant à M. BODJRO KOFFI ;','2024-05-08 17:14:34','2024-05-08 17:14:34'),(28,6,125000.00,'2026-02-05',8,'Dépôts hebdomadaires de 125 000 ;\nDomiciliation des recettes de l\'activité dans nos livres ;','2024-05-08 17:14:34','2024-05-08 17:14:34'),(33,7,30000000.00,'2025-05-10',2,'Caution personnelle et solidaire de Mr NABEDE KPATCHA MANDJA et Mr TCHANDINE ASSETINA','2024-05-10 09:35:28','2024-05-10 09:35:28'),(34,7,3000000.00,'2025-05-10',1,'Dépôt de garantie de 10% du financement','2024-05-10 09:35:28','2024-05-10 09:35:28'),(35,7,30000000.00,'2025-05-10',5,'Billet à ordre à hauteur de 30 millions','2024-05-10 09:35:28','2024-05-10 09:35:28'),(36,7,36000000.00,'2025-05-10',9,'affectation hypothécaire d\'un bien immeuble évalué à 60M sis à Agoé-Zongo à hauteur de 120% du financement.','2024-05-10 09:35:28','2024-05-10 09:35:28'),(37,8,1500000.00,'2025-11-15',1,'Déposit de 15%','2024-05-13 09:38:33','2024-05-13 09:38:33'),(38,8,10000000.00,'2025-11-15',2,'CPS de M. SODJEDO MARTIN, et de M. TEIKO KOUESSAN','2024-05-13 09:38:33','2024-05-13 09:38:33'),(39,8,10000000.00,'2025-11-15',5,NULL,'2024-05-13 09:38:33','2024-05-13 09:38:33'),(40,9,5000000.00,'2025-11-15',2,'CPS de Mme EDAM AMELE et de M. AMOUZOU FOLI','2024-05-13 17:10:15','2024-05-13 17:10:15'),(41,9,750000.00,'2025-11-15',1,'Deposit de 15%','2024-05-13 17:10:15','2024-05-13 17:10:15'),(42,9,5000000.00,'2025-11-15',5,NULL,'2024-05-13 17:10:15','2024-05-13 17:10:15');
/*!40000 ALTER TABLE `guarantees` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `guarantors`
--

DROP TABLE IF EXISTS `guarantors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `guarantors` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `contract_id` bigint unsigned DEFAULT NULL,
  `notification_id` bigint unsigned DEFAULT NULL,
  `civility` enum('Mr','Mme','Mlle') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `home_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_of_identity_document` enum('cni','passport','residence_certificate','driving_licence') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cni',
  `number_of_identity_document` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_of_issue_of_identity_document` date NOT NULL,
  `birth_date` date NOT NULL,
  `birth_place` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nationality` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `function` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `signed_contract_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `signed_promissory_note_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `guarantors_contract_id_foreign` (`contract_id`),
  KEY `guarantors_notification_id_foreign` (`notification_id`),
  CONSTRAINT `guarantors_contract_id_foreign` FOREIGN KEY (`contract_id`) REFERENCES `contracts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `guarantors_notification_id_foreign` FOREIGN KEY (`notification_id`) REFERENCES `notifications` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `guarantors`
--

LOCK TABLES `guarantors` WRITE;
/*!40000 ALTER TABLE `guarantors` DISABLE KEYS */;
/*!40000 ALTER TABLE `guarantors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `individual_businesses`
--

DROP TABLE IF EXISTS `individual_businesses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `individual_businesses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `contract_id` bigint unsigned NOT NULL,
  `denomination` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `corporate_purpose` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `head_office_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `rccm_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `individual_businesses_contract_id_foreign` (`contract_id`),
  CONSTRAINT `individual_businesses_contract_id_foreign` FOREIGN KEY (`contract_id`) REFERENCES `contracts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `individual_businesses`
--

LOCK TABLES `individual_businesses` WRITE;
/*!40000 ALTER TABLE `individual_businesses` DISABLE KEYS */;
/*!40000 ALTER TABLE `individual_businesses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
INSERT INTO `jobs` VALUES (1,'default','{\"uuid\":\"f1f4c47f-a335-4559-9dc2-7bfdab39f44c\",\"displayName\":\"App\\\\Jobs\\\\SendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\SendEmail\\\":3:{s:16:\\\"\\u0000*\\u0000receiverEmail\\\";s:31:\\\"espoir.ayewoutse@cofinacorp.com\\\";s:10:\\\"\\u0000*\\u0000subject\\\";s:42:\\\"Notification de mise en place d\'un contrat\\\";s:10:\\\"\\u0000*\\u0000content\\\";s:1012:\\\"\\n                                <h1 style=\'color: #333333;font-size: 24px; margin-bottom: 20px;\'>Cher(e) Admin crédit,<\\/U><\\/h1>\\n        \\n                                <p style=\'color: #666666; font-size: 16px; line-height: 1.5;\'>Nous vous prions de vous connecter à l\'application cofina credit digital et de prendre en charge immédiatement le PV CFNTG-046-17-04-24-00201 en attente de contrat: <a href=\'http:\\/\\/cofcredit.cofina.localhost\\/contract\\/add\'>Créer le contrat<\\/a><\\/p>\\n        \\n                                <p style=\'color: #666666; font-size: 16px; line-height: 1.5;\'>Si vous avez des questions ou des préoccupations, n\'hésitez pas à nous contacter. Nous sommes là pour vous aider !<\\/p>\\n        \\n                                <hr style=\'border: none; border-top: 1px solid #dddddd; margin: 20px 0;\'>\\n        \\n                                <p style=\'color: #999999; font-size: 12px;\'>Cet e-mail est généré automatiquement. Veuillez ne pas y répondre.<\\/p>\\n                            \\\";}\"}}',0,NULL,1715103536,1715103536),(2,'default','{\"uuid\":\"48567f1e-120e-4fd1-85ee-335c3b41ce0f\",\"displayName\":\"App\\\\Jobs\\\\SendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\SendEmail\\\":3:{s:16:\\\"\\u0000*\\u0000receiverEmail\\\";s:31:\\\"tadjoudine.memem@cofinacorp.com\\\";s:10:\\\"\\u0000*\\u0000subject\\\";s:42:\\\"Notification de mise en place d\'un contrat\\\";s:10:\\\"\\u0000*\\u0000content\\\";s:1012:\\\"\\n                                <h1 style=\'color: #333333;font-size: 24px; margin-bottom: 20px;\'>Cher(e) Admin crédit,<\\/U><\\/h1>\\n        \\n                                <p style=\'color: #666666; font-size: 16px; line-height: 1.5;\'>Nous vous prions de vous connecter à l\'application cofina credit digital et de prendre en charge immédiatement le PV CFNTG-046-17-04-24-00201 en attente de contrat: <a href=\'http:\\/\\/cofcredit.cofina.localhost\\/contract\\/add\'>Créer le contrat<\\/a><\\/p>\\n        \\n                                <p style=\'color: #666666; font-size: 16px; line-height: 1.5;\'>Si vous avez des questions ou des préoccupations, n\'hésitez pas à nous contacter. Nous sommes là pour vous aider !<\\/p>\\n        \\n                                <hr style=\'border: none; border-top: 1px solid #dddddd; margin: 20px 0;\'>\\n        \\n                                <p style=\'color: #999999; font-size: 12px;\'>Cet e-mail est généré automatiquement. Veuillez ne pas y répondre.<\\/p>\\n                            \\\";}\"}}',0,NULL,1715103536,1715103536),(3,'default','{\"uuid\":\"69f00bc9-bd3a-499b-a054-a58760b52c4e\",\"displayName\":\"App\\\\Jobs\\\\SendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\SendEmail\\\":3:{s:16:\\\"\\u0000*\\u0000receiverEmail\\\";s:31:\\\"espoir.ayewoutse@cofinacorp.com\\\";s:10:\\\"\\u0000*\\u0000subject\\\";s:42:\\\"Notification de mise en place d\'un contrat\\\";s:10:\\\"\\u0000*\\u0000content\\\";s:1012:\\\"\\n                                <h1 style=\'color: #333333;font-size: 24px; margin-bottom: 20px;\'>Cher(e) Admin crédit,<\\/U><\\/h1>\\n        \\n                                <p style=\'color: #666666; font-size: 16px; line-height: 1.5;\'>Nous vous prions de vous connecter à l\'application cofina credit digital et de prendre en charge immédiatement le PV CFNTG-044-15-04-24-00706 en attente de contrat: <a href=\'http:\\/\\/cofcredit.cofina.localhost\\/contract\\/add\'>Créer le contrat<\\/a><\\/p>\\n        \\n                                <p style=\'color: #666666; font-size: 16px; line-height: 1.5;\'>Si vous avez des questions ou des préoccupations, n\'hésitez pas à nous contacter. Nous sommes là pour vous aider !<\\/p>\\n        \\n                                <hr style=\'border: none; border-top: 1px solid #dddddd; margin: 20px 0;\'>\\n        \\n                                <p style=\'color: #999999; font-size: 12px;\'>Cet e-mail est généré automatiquement. Veuillez ne pas y répondre.<\\/p>\\n                            \\\";}\"}}',0,NULL,1715104225,1715104225),(4,'default','{\"uuid\":\"00769770-2f89-4bbc-8e7c-c343d081ca90\",\"displayName\":\"App\\\\Jobs\\\\SendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\SendEmail\\\":3:{s:16:\\\"\\u0000*\\u0000receiverEmail\\\";s:31:\\\"tadjoudine.memem@cofinacorp.com\\\";s:10:\\\"\\u0000*\\u0000subject\\\";s:42:\\\"Notification de mise en place d\'un contrat\\\";s:10:\\\"\\u0000*\\u0000content\\\";s:1012:\\\"\\n                                <h1 style=\'color: #333333;font-size: 24px; margin-bottom: 20px;\'>Cher(e) Admin crédit,<\\/U><\\/h1>\\n        \\n                                <p style=\'color: #666666; font-size: 16px; line-height: 1.5;\'>Nous vous prions de vous connecter à l\'application cofina credit digital et de prendre en charge immédiatement le PV CFNTG-044-15-04-24-00706 en attente de contrat: <a href=\'http:\\/\\/cofcredit.cofina.localhost\\/contract\\/add\'>Créer le contrat<\\/a><\\/p>\\n        \\n                                <p style=\'color: #666666; font-size: 16px; line-height: 1.5;\'>Si vous avez des questions ou des préoccupations, n\'hésitez pas à nous contacter. Nous sommes là pour vous aider !<\\/p>\\n        \\n                                <hr style=\'border: none; border-top: 1px solid #dddddd; margin: 20px 0;\'>\\n        \\n                                <p style=\'color: #999999; font-size: 12px;\'>Cet e-mail est généré automatiquement. Veuillez ne pas y répondre.<\\/p>\\n                            \\\";}\"}}',0,NULL,1715104225,1715104225),(5,'default','{\"uuid\":\"591ffcf2-4c58-4a3f-89aa-90afc85132a8\",\"displayName\":\"App\\\\Jobs\\\\SendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\SendEmail\\\":3:{s:16:\\\"\\u0000*\\u0000receiverEmail\\\";s:31:\\\"espoir.ayewoutse@cofinacorp.com\\\";s:10:\\\"\\u0000*\\u0000subject\\\";s:42:\\\"Notification de mise en place d\'un contrat\\\";s:10:\\\"\\u0000*\\u0000content\\\";s:1012:\\\"\\n                                <h1 style=\'color: #333333;font-size: 24px; margin-bottom: 20px;\'>Cher(e) Admin crédit,<\\/U><\\/h1>\\n        \\n                                <p style=\'color: #666666; font-size: 16px; line-height: 1.5;\'>Nous vous prions de vous connecter à l\'application cofina credit digital et de prendre en charge immédiatement le PV CFNTG-044-16-04-24-00807 en attente de contrat: <a href=\'http:\\/\\/cofcredit.cofina.localhost\\/contract\\/add\'>Créer le contrat<\\/a><\\/p>\\n        \\n                                <p style=\'color: #666666; font-size: 16px; line-height: 1.5;\'>Si vous avez des questions ou des préoccupations, n\'hésitez pas à nous contacter. Nous sommes là pour vous aider !<\\/p>\\n        \\n                                <hr style=\'border: none; border-top: 1px solid #dddddd; margin: 20px 0;\'>\\n        \\n                                <p style=\'color: #999999; font-size: 12px;\'>Cet e-mail est généré automatiquement. Veuillez ne pas y répondre.<\\/p>\\n                            \\\";}\"}}',0,NULL,1715105352,1715105352),(6,'default','{\"uuid\":\"c2f0195a-00df-46af-82e0-a48181a69cf1\",\"displayName\":\"App\\\\Jobs\\\\SendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\SendEmail\\\":3:{s:16:\\\"\\u0000*\\u0000receiverEmail\\\";s:31:\\\"tadjoudine.memem@cofinacorp.com\\\";s:10:\\\"\\u0000*\\u0000subject\\\";s:42:\\\"Notification de mise en place d\'un contrat\\\";s:10:\\\"\\u0000*\\u0000content\\\";s:1012:\\\"\\n                                <h1 style=\'color: #333333;font-size: 24px; margin-bottom: 20px;\'>Cher(e) Admin crédit,<\\/U><\\/h1>\\n        \\n                                <p style=\'color: #666666; font-size: 16px; line-height: 1.5;\'>Nous vous prions de vous connecter à l\'application cofina credit digital et de prendre en charge immédiatement le PV CFNTG-044-16-04-24-00807 en attente de contrat: <a href=\'http:\\/\\/cofcredit.cofina.localhost\\/contract\\/add\'>Créer le contrat<\\/a><\\/p>\\n        \\n                                <p style=\'color: #666666; font-size: 16px; line-height: 1.5;\'>Si vous avez des questions ou des préoccupations, n\'hésitez pas à nous contacter. Nous sommes là pour vous aider !<\\/p>\\n        \\n                                <hr style=\'border: none; border-top: 1px solid #dddddd; margin: 20px 0;\'>\\n        \\n                                <p style=\'color: #999999; font-size: 12px;\'>Cet e-mail est généré automatiquement. Veuillez ne pas y répondre.<\\/p>\\n                            \\\";}\"}}',0,NULL,1715105352,1715105352),(7,'default','{\"uuid\":\"968ed5c6-a3cf-462e-9efe-3377d322c523\",\"displayName\":\"App\\\\Jobs\\\\SendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\SendEmail\\\":3:{s:16:\\\"\\u0000*\\u0000receiverEmail\\\";s:31:\\\"espoir.ayewoutse@cofinacorp.com\\\";s:10:\\\"\\u0000*\\u0000subject\\\";s:42:\\\"Notification de mise en place d\'un contrat\\\";s:10:\\\"\\u0000*\\u0000content\\\";s:1013:\\\"\\n                                <h1 style=\'color: #333333;font-size: 24px; margin-bottom: 20px;\'>Cher(e) Admin crédit,<\\/U><\\/h1>\\n        \\n                                <p style=\'color: #666666; font-size: 16px; line-height: 1.5;\'>Nous vous prions de vous connecter à l\'application cofina credit digital et de prendre en charge immédiatement le PV -CFNTG-046-24-04-24-00503 en attente de contrat: <a href=\'http:\\/\\/cofcredit.cofina.localhost\\/contract\\/add\'>Créer le contrat<\\/a><\\/p>\\n        \\n                                <p style=\'color: #666666; font-size: 16px; line-height: 1.5;\'>Si vous avez des questions ou des préoccupations, n\'hésitez pas à nous contacter. Nous sommes là pour vous aider !<\\/p>\\n        \\n                                <hr style=\'border: none; border-top: 1px solid #dddddd; margin: 20px 0;\'>\\n        \\n                                <p style=\'color: #999999; font-size: 12px;\'>Cet e-mail est généré automatiquement. Veuillez ne pas y répondre.<\\/p>\\n                            \\\";}\"}}',0,NULL,1715105728,1715105728),(8,'default','{\"uuid\":\"7cc3c21b-5469-428c-a7de-31023ae21c71\",\"displayName\":\"App\\\\Jobs\\\\SendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\SendEmail\\\":3:{s:16:\\\"\\u0000*\\u0000receiverEmail\\\";s:31:\\\"tadjoudine.memem@cofinacorp.com\\\";s:10:\\\"\\u0000*\\u0000subject\\\";s:42:\\\"Notification de mise en place d\'un contrat\\\";s:10:\\\"\\u0000*\\u0000content\\\";s:1013:\\\"\\n                                <h1 style=\'color: #333333;font-size: 24px; margin-bottom: 20px;\'>Cher(e) Admin crédit,<\\/U><\\/h1>\\n        \\n                                <p style=\'color: #666666; font-size: 16px; line-height: 1.5;\'>Nous vous prions de vous connecter à l\'application cofina credit digital et de prendre en charge immédiatement le PV -CFNTG-046-24-04-24-00503 en attente de contrat: <a href=\'http:\\/\\/cofcredit.cofina.localhost\\/contract\\/add\'>Créer le contrat<\\/a><\\/p>\\n        \\n                                <p style=\'color: #666666; font-size: 16px; line-height: 1.5;\'>Si vous avez des questions ou des préoccupations, n\'hésitez pas à nous contacter. Nous sommes là pour vous aider !<\\/p>\\n        \\n                                <hr style=\'border: none; border-top: 1px solid #dddddd; margin: 20px 0;\'>\\n        \\n                                <p style=\'color: #999999; font-size: 12px;\'>Cet e-mail est généré automatiquement. Veuillez ne pas y répondre.<\\/p>\\n                            \\\";}\"}}',0,NULL,1715105728,1715105728),(9,'default','{\"uuid\":\"b1762456-1295-4112-bf42-e9bc6d98fc71\",\"displayName\":\"App\\\\Jobs\\\\SendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\SendEmail\\\":3:{s:16:\\\"\\u0000*\\u0000receiverEmail\\\";s:31:\\\"espoir.ayewoutse@cofinacorp.com\\\";s:10:\\\"\\u0000*\\u0000subject\\\";s:42:\\\"Notification de mise en place d\'un contrat\\\";s:10:\\\"\\u0000*\\u0000content\\\";s:1012:\\\"\\n                                <h1 style=\'color: #333333;font-size: 24px; margin-bottom: 20px;\'>Cher(e) Admin crédit,<\\/U><\\/h1>\\n        \\n                                <p style=\'color: #666666; font-size: 16px; line-height: 1.5;\'>Nous vous prions de vous connecter à l\'application cofina credit digital et de prendre en charge immédiatement le PV CFNTG-044-18-04-24-00504 en attente de contrat: <a href=\'http:\\/\\/cofcredit.cofina.localhost\\/contract\\/add\'>Créer le contrat<\\/a><\\/p>\\n        \\n                                <p style=\'color: #666666; font-size: 16px; line-height: 1.5;\'>Si vous avez des questions ou des préoccupations, n\'hésitez pas à nous contacter. Nous sommes là pour vous aider !<\\/p>\\n        \\n                                <hr style=\'border: none; border-top: 1px solid #dddddd; margin: 20px 0;\'>\\n        \\n                                <p style=\'color: #999999; font-size: 12px;\'>Cet e-mail est généré automatiquement. Veuillez ne pas y répondre.<\\/p>\\n                            \\\";}\"}}',0,NULL,1715108016,1715108016),(10,'default','{\"uuid\":\"eaa21657-e1c1-4d50-838c-df7657795955\",\"displayName\":\"App\\\\Jobs\\\\SendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\SendEmail\\\":3:{s:16:\\\"\\u0000*\\u0000receiverEmail\\\";s:31:\\\"tadjoudine.memem@cofinacorp.com\\\";s:10:\\\"\\u0000*\\u0000subject\\\";s:42:\\\"Notification de mise en place d\'un contrat\\\";s:10:\\\"\\u0000*\\u0000content\\\";s:1012:\\\"\\n                                <h1 style=\'color: #333333;font-size: 24px; margin-bottom: 20px;\'>Cher(e) Admin crédit,<\\/U><\\/h1>\\n        \\n                                <p style=\'color: #666666; font-size: 16px; line-height: 1.5;\'>Nous vous prions de vous connecter à l\'application cofina credit digital et de prendre en charge immédiatement le PV CFNTG-044-18-04-24-00504 en attente de contrat: <a href=\'http:\\/\\/cofcredit.cofina.localhost\\/contract\\/add\'>Créer le contrat<\\/a><\\/p>\\n        \\n                                <p style=\'color: #666666; font-size: 16px; line-height: 1.5;\'>Si vous avez des questions ou des préoccupations, n\'hésitez pas à nous contacter. Nous sommes là pour vous aider !<\\/p>\\n        \\n                                <hr style=\'border: none; border-top: 1px solid #dddddd; margin: 20px 0;\'>\\n        \\n                                <p style=\'color: #999999; font-size: 12px;\'>Cet e-mail est généré automatiquement. Veuillez ne pas y répondre.<\\/p>\\n                            \\\";}\"}}',0,NULL,1715108016,1715108016),(11,'default','{\"uuid\":\"d25241a5-5d97-46d8-8ce1-25594af8256e\",\"displayName\":\"App\\\\Jobs\\\\SendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\SendEmail\\\":3:{s:16:\\\"\\u0000*\\u0000receiverEmail\\\";s:31:\\\"espoir.ayewoutse@cofinacorp.com\\\";s:10:\\\"\\u0000*\\u0000subject\\\";s:42:\\\"Notification de mise en place d\'un contrat\\\";s:10:\\\"\\u0000*\\u0000content\\\";s:1012:\\\"\\n                                <h1 style=\'color: #333333;font-size: 24px; margin-bottom: 20px;\'>Cher(e) Admin crédit,<\\/U><\\/h1>\\n        \\n                                <p style=\'color: #666666; font-size: 16px; line-height: 1.5;\'>Nous vous prions de vous connecter à l\'application cofina credit digital et de prendre en charge immédiatement le PV CFNTG-045-22-04-24-00101 en attente de contrat: <a href=\'http:\\/\\/cofcredit.cofina.localhost\\/contract\\/add\'>Créer le contrat<\\/a><\\/p>\\n        \\n                                <p style=\'color: #666666; font-size: 16px; line-height: 1.5;\'>Si vous avez des questions ou des préoccupations, n\'hésitez pas à nous contacter. Nous sommes là pour vous aider !<\\/p>\\n        \\n                                <hr style=\'border: none; border-top: 1px solid #dddddd; margin: 20px 0;\'>\\n        \\n                                <p style=\'color: #999999; font-size: 12px;\'>Cet e-mail est généré automatiquement. Veuillez ne pas y répondre.<\\/p>\\n                            \\\";}\"}}',0,NULL,1715188030,1715188030),(12,'default','{\"uuid\":\"01646967-969f-4dce-a98f-c3abb5cdabf9\",\"displayName\":\"App\\\\Jobs\\\\SendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\SendEmail\\\":3:{s:16:\\\"\\u0000*\\u0000receiverEmail\\\";s:31:\\\"tadjoudine.memem@cofinacorp.com\\\";s:10:\\\"\\u0000*\\u0000subject\\\";s:42:\\\"Notification de mise en place d\'un contrat\\\";s:10:\\\"\\u0000*\\u0000content\\\";s:1012:\\\"\\n                                <h1 style=\'color: #333333;font-size: 24px; margin-bottom: 20px;\'>Cher(e) Admin crédit,<\\/U><\\/h1>\\n        \\n                                <p style=\'color: #666666; font-size: 16px; line-height: 1.5;\'>Nous vous prions de vous connecter à l\'application cofina credit digital et de prendre en charge immédiatement le PV CFNTG-045-22-04-24-00101 en attente de contrat: <a href=\'http:\\/\\/cofcredit.cofina.localhost\\/contract\\/add\'>Créer le contrat<\\/a><\\/p>\\n        \\n                                <p style=\'color: #666666; font-size: 16px; line-height: 1.5;\'>Si vous avez des questions ou des préoccupations, n\'hésitez pas à nous contacter. Nous sommes là pour vous aider !<\\/p>\\n        \\n                                <hr style=\'border: none; border-top: 1px solid #dddddd; margin: 20px 0;\'>\\n        \\n                                <p style=\'color: #999999; font-size: 12px;\'>Cet e-mail est généré automatiquement. Veuillez ne pas y répondre.<\\/p>\\n                            \\\";}\"}}',0,NULL,1715188030,1715188030),(13,'default','{\"uuid\":\"fd6f148d-b97c-4286-a402-426cd7ef827c\",\"displayName\":\"App\\\\Jobs\\\\SendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\SendEmail\\\":3:{s:16:\\\"\\u0000*\\u0000receiverEmail\\\";s:31:\\\"espoir.ayewoutse@cofinacorp.com\\\";s:10:\\\"\\u0000*\\u0000subject\\\";s:42:\\\"Notification de mise en place d\'un contrat\\\";s:10:\\\"\\u0000*\\u0000content\\\";s:1012:\\\"\\n                                <h1 style=\'color: #333333;font-size: 24px; margin-bottom: 20px;\'>Cher(e) Admin crédit,<\\/U><\\/h1>\\n        \\n                                <p style=\'color: #666666; font-size: 16px; line-height: 1.5;\'>Nous vous prions de vous connecter à l\'application cofina credit digital et de prendre en charge immédiatement le PV CFNTG-044-22-04-24-00301 en attente de contrat: <a href=\'http:\\/\\/cofcredit.cofina.localhost\\/contract\\/add\'>Créer le contrat<\\/a><\\/p>\\n        \\n                                <p style=\'color: #666666; font-size: 16px; line-height: 1.5;\'>Si vous avez des questions ou des préoccupations, n\'hésitez pas à nous contacter. Nous sommes là pour vous aider !<\\/p>\\n        \\n                                <hr style=\'border: none; border-top: 1px solid #dddddd; margin: 20px 0;\'>\\n        \\n                                <p style=\'color: #999999; font-size: 12px;\'>Cet e-mail est généré automatiquement. Veuillez ne pas y répondre.<\\/p>\\n                            \\\";}\"}}',0,NULL,1715333472,1715333472),(14,'default','{\"uuid\":\"a4c8d215-1cb9-4cec-84b4-fc0bd9b854a7\",\"displayName\":\"App\\\\Jobs\\\\SendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\SendEmail\\\":3:{s:16:\\\"\\u0000*\\u0000receiverEmail\\\";s:31:\\\"tadjoudine.memem@cofinacorp.com\\\";s:10:\\\"\\u0000*\\u0000subject\\\";s:42:\\\"Notification de mise en place d\'un contrat\\\";s:10:\\\"\\u0000*\\u0000content\\\";s:1012:\\\"\\n                                <h1 style=\'color: #333333;font-size: 24px; margin-bottom: 20px;\'>Cher(e) Admin crédit,<\\/U><\\/h1>\\n        \\n                                <p style=\'color: #666666; font-size: 16px; line-height: 1.5;\'>Nous vous prions de vous connecter à l\'application cofina credit digital et de prendre en charge immédiatement le PV CFNTG-044-22-04-24-00301 en attente de contrat: <a href=\'http:\\/\\/cofcredit.cofina.localhost\\/contract\\/add\'>Créer le contrat<\\/a><\\/p>\\n        \\n                                <p style=\'color: #666666; font-size: 16px; line-height: 1.5;\'>Si vous avez des questions ou des préoccupations, n\'hésitez pas à nous contacter. Nous sommes là pour vous aider !<\\/p>\\n        \\n                                <hr style=\'border: none; border-top: 1px solid #dddddd; margin: 20px 0;\'>\\n        \\n                                <p style=\'color: #999999; font-size: 12px;\'>Cet e-mail est généré automatiquement. Veuillez ne pas y répondre.<\\/p>\\n                            \\\";}\"}}',0,NULL,1715333472,1715333472),(15,'default','{\"uuid\":\"68d19cf1-a5bd-4f6f-a6d3-b06478925dad\",\"displayName\":\"App\\\\Jobs\\\\SendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\SendEmail\\\":3:{s:16:\\\"\\u0000*\\u0000receiverEmail\\\";s:30:\\\"charles.gamligo@cofinacorp.com\\\";s:10:\\\"\\u0000*\\u0000subject\\\";s:37:\\\"Notification de mise en place d\'un pv\\\";s:10:\\\"\\u0000*\\u0000content\\\";s:842:\\\"\\n            <h1 style=\'color: #333333;text-align: center; font-size: 24px; margin-bottom: 20px;\'>Cher(e) Head Crédit,<\\/U><\\/h1>\\n\\n            <p style=\'color: #666666; font-size: 16px; line-height: 1.5;\'>Nous vous prions de vous connecter à l\'application cofina credit digital et de prendre en charge immédiatement la notification de validation: <a href=\'http:\\/\\/cofcredit.cofina.localhost\\/notification\'>Consulter l<\\/a><\\/p>\\n\\n            <p style=\'color: #666666; font-size: 16px; line-height: 1.5;\'>Si vous avez des questions ou des préoccupations, n\'hésitez pas à nous contacter. Nous sommes là pour vous aider !<\\/p>\\n\\n            <hr style=\'border: none; border-top: 1px solid #dddddd; margin: 20px 0;\'>\\n\\n            <p style=\'color: #999999; font-size: 12px;\'>Cet e-mail est généré automatiquement. Veuillez ne pas y répondre.<\\/p>\\n\\\";}\"}}',0,NULL,1715335208,1715335208),(16,'default','{\"uuid\":\"bc54665a-c4f3-45c6-8ecc-39e6a53f2ef2\",\"displayName\":\"App\\\\Jobs\\\\SendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\SendEmail\\\":3:{s:16:\\\"\\u0000*\\u0000receiverEmail\\\";s:34:\\\"nykpogbe.agbenowosi@cofinacorp.com\\\";s:10:\\\"\\u0000*\\u0000subject\\\";s:37:\\\"Notification de mise en place d\'un pv\\\";s:10:\\\"\\u0000*\\u0000content\\\";s:873:\\\"\\n            <h1 style=\'color: #333333;text-align: center; font-size: 24px; margin-bottom: 20px;\'>Cher(e) Nyokpogbe AGBENOWOSI,<\\/U><\\/h1>\\n\\n            <p style=\'color: #666666; font-size: 16px; line-height: 1.5;\'>Nous vous prions de vous connecter à l\'application cofina credit digital et de prendre en charge immédiatement le contrat en attente de signature par le client: <a href=\'http:\\/\\/cofcredit.cofina.localhost\\/contract\'>Consulter l<\\/a><\\/p>\\n\\n            <p style=\'color: #666666; font-size: 16px; line-height: 1.5;\'>Si vous avez des questions ou des préoccupations, n\'hésitez pas à nous contacter. Nous sommes là pour vous aider !<\\/p>\\n\\n            <hr style=\'border: none; border-top: 1px solid #dddddd; margin: 20px 0;\'>\\n\\n            <p style=\'color: #999999; font-size: 12px;\'>Cet e-mail est généré automatiquement. Veuillez ne pas y répondre.<\\/p>\\n        \\\";}\"}}',0,NULL,1715586825,1715586825),(17,'default','{\"uuid\":\"f7d622e1-9db0-45af-8fc2-f9b56bd3a0a8\",\"displayName\":\"App\\\\Jobs\\\\SendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\SendEmail\\\":3:{s:16:\\\"\\u0000*\\u0000receiverEmail\\\";s:31:\\\"espoir.ayewoutse@cofinacorp.com\\\";s:10:\\\"\\u0000*\\u0000subject\\\";s:42:\\\"Notification de mise en place d\'un contrat\\\";s:10:\\\"\\u0000*\\u0000content\\\";s:1012:\\\"\\n                                <h1 style=\'color: #333333;font-size: 24px; margin-bottom: 20px;\'>Cher(e) Admin crédit,<\\/U><\\/h1>\\n        \\n                                <p style=\'color: #666666; font-size: 16px; line-height: 1.5;\'>Nous vous prions de vous connecter à l\'application cofina credit digital et de prendre en charge immédiatement le PV CFNTG-045-27-04-24-00202 en attente de contrat: <a href=\'http:\\/\\/cofcredit.cofina.localhost\\/contract\\/add\'>Créer le contrat<\\/a><\\/p>\\n        \\n                                <p style=\'color: #666666; font-size: 16px; line-height: 1.5;\'>Si vous avez des questions ou des préoccupations, n\'hésitez pas à nous contacter. Nous sommes là pour vous aider !<\\/p>\\n        \\n                                <hr style=\'border: none; border-top: 1px solid #dddddd; margin: 20px 0;\'>\\n        \\n                                <p style=\'color: #999999; font-size: 12px;\'>Cet e-mail est généré automatiquement. Veuillez ne pas y répondre.<\\/p>\\n                            \\\";}\"}}',0,NULL,1715593113,1715593113),(18,'default','{\"uuid\":\"8d904c23-aca7-4984-b2c9-b37dd5e4ba92\",\"displayName\":\"App\\\\Jobs\\\\SendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\SendEmail\\\":3:{s:16:\\\"\\u0000*\\u0000receiverEmail\\\";s:31:\\\"tadjoudine.memem@cofinacorp.com\\\";s:10:\\\"\\u0000*\\u0000subject\\\";s:42:\\\"Notification de mise en place d\'un contrat\\\";s:10:\\\"\\u0000*\\u0000content\\\";s:1012:\\\"\\n                                <h1 style=\'color: #333333;font-size: 24px; margin-bottom: 20px;\'>Cher(e) Admin crédit,<\\/U><\\/h1>\\n        \\n                                <p style=\'color: #666666; font-size: 16px; line-height: 1.5;\'>Nous vous prions de vous connecter à l\'application cofina credit digital et de prendre en charge immédiatement le PV CFNTG-045-27-04-24-00202 en attente de contrat: <a href=\'http:\\/\\/cofcredit.cofina.localhost\\/contract\\/add\'>Créer le contrat<\\/a><\\/p>\\n        \\n                                <p style=\'color: #666666; font-size: 16px; line-height: 1.5;\'>Si vous avez des questions ou des préoccupations, n\'hésitez pas à nous contacter. Nous sommes là pour vous aider !<\\/p>\\n        \\n                                <hr style=\'border: none; border-top: 1px solid #dddddd; margin: 20px 0;\'>\\n        \\n                                <p style=\'color: #999999; font-size: 12px;\'>Cet e-mail est généré automatiquement. Veuillez ne pas y répondre.<\\/p>\\n                            \\\";}\"}}',0,NULL,1715593113,1715593113),(19,'default','{\"uuid\":\"84d35b76-7782-469d-91a1-ad855f3aba70\",\"displayName\":\"App\\\\Jobs\\\\SendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\SendEmail\\\":3:{s:16:\\\"\\u0000*\\u0000receiverEmail\\\";s:29:\\\"kossi.agblevon@cofinacorp.com\\\";s:10:\\\"\\u0000*\\u0000subject\\\";s:37:\\\"Notification de mise en place d\'un pv\\\";s:10:\\\"\\u0000*\\u0000content\\\";s:867:\\\"\\n            <h1 style=\'color: #333333;text-align: center; font-size: 24px; margin-bottom: 20px;\'>Cher(e) Kossi AGBLEVON,<\\/U><\\/h1>\\n\\n            <p style=\'color: #666666; font-size: 16px; line-height: 1.5;\'>Nous vous prions de vous connecter à l\'application cofina credit digital et de prendre en charge immédiatement le contrat en attente de signature par le client: <a href=\'http:\\/\\/cofcredit.cofina.localhost\\/contract\'>Consulter l<\\/a><\\/p>\\n\\n            <p style=\'color: #666666; font-size: 16px; line-height: 1.5;\'>Si vous avez des questions ou des préoccupations, n\'hésitez pas à nous contacter. Nous sommes là pour vous aider !<\\/p>\\n\\n            <hr style=\'border: none; border-top: 1px solid #dddddd; margin: 20px 0;\'>\\n\\n            <p style=\'color: #999999; font-size: 12px;\'>Cet e-mail est généré automatiquement. Veuillez ne pas y répondre.<\\/p>\\n        \\\";}\"}}',0,NULL,1715612998,1715612998),(20,'default','{\"uuid\":\"7ff5cbfd-56c1-4006-a4e8-6c696ac21186\",\"displayName\":\"App\\\\Jobs\\\\SendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\SendEmail\\\":3:{s:16:\\\"\\u0000*\\u0000receiverEmail\\\";s:31:\\\"espoir.ayewoutse@cofinacorp.com\\\";s:10:\\\"\\u0000*\\u0000subject\\\";s:42:\\\"Notification de mise en place d\'un contrat\\\";s:10:\\\"\\u0000*\\u0000content\\\";s:1012:\\\"\\n                                <h1 style=\'color: #333333;font-size: 24px; margin-bottom: 20px;\'>Cher(e) Admin crédit,<\\/U><\\/h1>\\n        \\n                                <p style=\'color: #666666; font-size: 16px; line-height: 1.5;\'>Nous vous prions de vous connecter à l\'application cofina credit digital et de prendre en charge immédiatement le PV CFNTG-044-25-03-24-01608 en attente de contrat: <a href=\'http:\\/\\/cofcredit.cofina.localhost\\/contract\\/add\'>Créer le contrat<\\/a><\\/p>\\n        \\n                                <p style=\'color: #666666; font-size: 16px; line-height: 1.5;\'>Si vous avez des questions ou des préoccupations, n\'hésitez pas à nous contacter. Nous sommes là pour vous aider !<\\/p>\\n        \\n                                <hr style=\'border: none; border-top: 1px solid #dddddd; margin: 20px 0;\'>\\n        \\n                                <p style=\'color: #999999; font-size: 12px;\'>Cet e-mail est généré automatiquement. Veuillez ne pas y répondre.<\\/p>\\n                            \\\";}\"}}',0,NULL,1715620215,1715620215),(21,'default','{\"uuid\":\"cb22f769-c614-41fe-81dc-3b84b5c1452e\",\"displayName\":\"App\\\\Jobs\\\\SendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\SendEmail\\\":3:{s:16:\\\"\\u0000*\\u0000receiverEmail\\\";s:31:\\\"tadjoudine.memem@cofinacorp.com\\\";s:10:\\\"\\u0000*\\u0000subject\\\";s:42:\\\"Notification de mise en place d\'un contrat\\\";s:10:\\\"\\u0000*\\u0000content\\\";s:1012:\\\"\\n                                <h1 style=\'color: #333333;font-size: 24px; margin-bottom: 20px;\'>Cher(e) Admin crédit,<\\/U><\\/h1>\\n        \\n                                <p style=\'color: #666666; font-size: 16px; line-height: 1.5;\'>Nous vous prions de vous connecter à l\'application cofina credit digital et de prendre en charge immédiatement le PV CFNTG-044-25-03-24-01608 en attente de contrat: <a href=\'http:\\/\\/cofcredit.cofina.localhost\\/contract\\/add\'>Créer le contrat<\\/a><\\/p>\\n        \\n                                <p style=\'color: #666666; font-size: 16px; line-height: 1.5;\'>Si vous avez des questions ou des préoccupations, n\'hésitez pas à nous contacter. Nous sommes là pour vous aider !<\\/p>\\n        \\n                                <hr style=\'border: none; border-top: 1px solid #dddddd; margin: 20px 0;\'>\\n        \\n                                <p style=\'color: #999999; font-size: 12px;\'>Cet e-mail est généré automatiquement. Veuillez ne pas y répondre.<\\/p>\\n                            \\\";}\"}}',0,NULL,1715620215,1715620215),(22,'default','{\"uuid\":\"6c28d777-a401-40e9-ba5b-5bf66099b0e5\",\"displayName\":\"App\\\\Jobs\\\\SendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\SendEmail\\\":3:{s:16:\\\"\\u0000*\\u0000receiverEmail\\\";s:27:\\\"komla.ahonde@cofinacorp.com\\\";s:10:\\\"\\u0000*\\u0000subject\\\";s:37:\\\"Notification de mise en place d\'un pv\\\";s:10:\\\"\\u0000*\\u0000content\\\";s:872:\\\"\\n            <h1 style=\'color: #333333;text-align: center; font-size: 24px; margin-bottom: 20px;\'>Cher(e) Komla Nopeli AHONDE,<\\/U><\\/h1>\\n\\n            <p style=\'color: #666666; font-size: 16px; line-height: 1.5;\'>Nous vous prions de vous connecter à l\'application cofina credit digital et de prendre en charge immédiatement le contrat en attente de signature par le client: <a href=\'http:\\/\\/cofcredit.cofina.localhost\\/contract\'>Consulter l<\\/a><\\/p>\\n\\n            <p style=\'color: #666666; font-size: 16px; line-height: 1.5;\'>Si vous avez des questions ou des préoccupations, n\'hésitez pas à nous contacter. Nous sommes là pour vous aider !<\\/p>\\n\\n            <hr style=\'border: none; border-top: 1px solid #dddddd; margin: 20px 0;\'>\\n\\n            <p style=\'color: #999999; font-size: 12px;\'>Cet e-mail est généré automatiquement. Veuillez ne pas y répondre.<\\/p>\\n        \\\";}\"}}',0,NULL,1715674947,1715674947);
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_reset_tokens_table',1),(3,'2019_08_19_000000_create_failed_jobs_table',1),(4,'2019_12_14_000001_create_personal_access_tokens_table',1),(5,'2024_02_07_094300_create_types_of_applicant_table',1),(6,'2024_02_07_094414_create_types_of_credit_table',1),(7,'2024_02_07_094425_create_types_of_guarantee_table',1),(8,'2024_02_07_094426_create_verbals_trials_table',1),(9,'2024_02_07_094427_create_notifications_table',1),(10,'2024_02_08_174040_create_guarantees_table',1),(11,'2024_02_09_173717_create_contracts_table',1),(12,'2024_02_14_122257_create_guarantors_table',1),(13,'2024_02_15_171745_create_companies_table',1),(14,'2024_02_15_191255_create_individual_businesses_table',1),(15,'2024_02_16_114611_create_pledges_table',1),(16,'2024_02_19_100444_create_c_a_t_s_table',1),(17,'2024_03_21_181921_create_jobs_table',1),(18,'2024_04_16_143533_create_deadline_postponeds_table',1),(19,'2024_04_16_182605_create_deadline_postponed_files_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `verbal_trial_id` bigint unsigned NOT NULL,
  `representative_phone_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `representative_home_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `number_of_due_dates` int NOT NULL,
  `risk_premium_percentage` double(8,2) NOT NULL,
  `head_credit_validation` enum('waiting','rejected','validated') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'waiting',
  `head_credit_observation` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('waiting','rejected','validated') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'waiting',
  `status_observation` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_amount_of_interest` decimal(30,10) NOT NULL,
  `representative_type_of_identity_document` enum('cni','passport','residence_certificate','driving_licence') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cni',
  `representative_number_of_identity_document` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `representative_date_of_issue_of_identity_document` date NOT NULL,
  `type` enum('particular','company','individual_business') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `business_denomination` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `signed_notification_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `signed_contract_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `signed_promissory_note_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `creator_id` bigint unsigned NOT NULL,
  `sent` tinyint(1) NOT NULL DEFAULT '0',
  `is_simple` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_verbal_trial_id_foreign` (`verbal_trial_id`),
  KEY `notifications_creator_id_foreign` (`creator_id`),
  CONSTRAINT `notifications_creator_id_foreign` FOREIGN KEY (`creator_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `notifications_verbal_trial_id_foreign` FOREIGN KEY (`verbal_trial_id`) REFERENCES `verbals_trials` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
INSERT INTO `notifications` VALUES (1,7,'+228 79 72 59 63','ATTIEGOU RUE LOME',12,0.50,'waiting',NULL,'waiting',NULL,30000000.0000000000,'passport','000993111','2024-03-22','individual_business','ETS SYMPIYA',NULL,NULL,NULL,5,0,0,'2024-05-10 10:00:08','2024-05-10 10:00:08');
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
INSERT INTO `personal_access_tokens` VALUES (1,'App\\Models\\User',1,'auth-token','8fb55a1d50842403ddc4ea7dc0c80a5d2e44eeb029f1077341babd46b68fe0ba','[\"*\"]',NULL,NULL,'2024-05-06 09:36:31','2024-05-06 09:36:31'),(2,'App\\Models\\User',1,'auth-token','96b91ec21dfc7acd2bb8489528e67dbabaf8d6da488267f72069ebb0f09658d6','[\"*\"]',NULL,NULL,'2024-05-06 09:36:31','2024-05-06 09:36:31'),(3,'App\\Models\\User',1,'admin@cofinacorp.com','2cbf5c93fa029c5f569aac322aee31d4cb2d13fa57a04978bbe3f1e4ebddf04a','[\"*\"]','2024-05-06 09:41:43',NULL,'2024-05-06 09:41:42','2024-05-06 09:41:43'),(4,'App\\Models\\User',4,'espoir.ayewoutse@cofinacorp.com','c0cc356a96a9edea1854fc8aea13f14c06da21d086340f626799623137a4592a','[\"*\"]','2024-05-06 09:42:15',NULL,'2024-05-06 09:42:14','2024-05-06 09:42:15'),(5,'App\\Models\\User',4,'espoir.ayewoutse@cofinacorp.com','7ed99131623b740369e80c6ebc0bbdf4f6f3b4fa83c36327e5bae36c3e58ed76','[\"*\"]','2024-05-06 19:50:25',NULL,'2024-05-06 19:49:36','2024-05-06 19:50:25'),(6,'App\\Models\\User',5,'tadjoudine.memem@cofinacorp.com','8ea887802c635446258fa354af2b30134a8e83cbfeffd96788f53c87762d904d','[\"*\"]','2024-05-07 08:33:20',NULL,'2024-05-07 08:33:00','2024-05-07 08:33:20'),(7,'App\\Models\\User',4,'espoir.ayewoutse@cofinacorp.com','640dd6314e26f8b8d19850089e36df71d33d9c25164cb077cdcefc32d3a05d08','[\"*\"]','2024-05-07 09:00:41',NULL,'2024-05-07 08:34:58','2024-05-07 09:00:41'),(8,'App\\Models\\User',9,'christele.febon@cofinacorp.com','59995f7d7de230fb49ae26748a36f5d16f15fc0873e98ee5ed08263083d2a0f3','[\"*\"]','2024-05-07 10:32:45',NULL,'2024-05-07 10:32:36','2024-05-07 10:32:45'),(9,'App\\Models\\User',3,'prudence.ayena@cofinacorp.com','d1100116d0fb3aa9c79758f56a4da4bc3ba6ca8753ec731ccc2800a83758762f','[\"*\"]','2024-05-07 10:33:18',NULL,'2024-05-07 10:33:06','2024-05-07 10:33:18'),(10,'App\\Models\\User',2,'ovidio.de-souza@cofinacorp.com','48a93c17ec434294bbb727ac76c06db3b45185b7fb90d61b94c65ff32929c420','[\"*\"]','2024-05-07 18:12:36',NULL,'2024-05-07 17:09:22','2024-05-07 18:12:36'),(11,'App\\Models\\User',2,'ovidio.de-souza@cofinacorp.com','d951ccaac7adc948d91feb13d1d8a8842ebfbf7996ef7558e8fc906437c7105c','[\"*\"]','2024-05-07 18:15:28',NULL,'2024-05-07 17:18:31','2024-05-07 18:15:28'),(12,'App\\Models\\User',2,'ovidio.de-souza@cofinacorp.com','137e6e8b51372d71bc2c751ed54ca2a39d49517a15f90c76073580373e9bd49d','[\"*\"]','2024-05-07 17:52:21',NULL,'2024-05-07 17:51:09','2024-05-07 17:52:21'),(13,'App\\Models\\User',2,'ovidio.de-souza@cofinacorp.com','db9d52c62b957bd9c6a6471380466b38a50ae64b3dc71a788d71dfa587bfc2c9','[\"*\"]','2024-05-07 19:36:39',NULL,'2024-05-07 18:25:58','2024-05-07 19:36:39'),(14,'App\\Models\\User',2,'ovidio.de-souza@cofinacorp.com','d1919d67d869ded2888d8bc155965b77b07572c942d97e6933aa232d3e88da46','[\"*\"]','2024-05-08 17:56:33',NULL,'2024-05-08 08:32:47','2024-05-08 17:56:33'),(15,'App\\Models\\User',5,'tadjoudine.memem@cofinacorp.com','c1d929f5ae12c73fdfad6c690155ef4e4729be3aa8b9abc41996dfca89ee91cf','[\"*\"]','2024-05-08 16:58:58',NULL,'2024-05-08 08:52:29','2024-05-08 16:58:58'),(16,'App\\Models\\User',2,'ovidio.de-souza@cofinacorp.com','c1159ac277f72c6b85455095072e88e2ab219515dc3eda10d9ca6c93c17421a3','[\"*\"]','2024-05-08 17:14:45',NULL,'2024-05-08 10:58:17','2024-05-08 17:14:45'),(17,'App\\Models\\User',4,'espoir.ayewoutse@cofinacorp.com','117a130b2839137872b6aef570948733752d525e3ee5211cb39d7f542d592fbf','[\"*\"]','2024-05-08 11:15:54',NULL,'2024-05-08 11:11:33','2024-05-08 11:15:54'),(18,'App\\Models\\User',3,'prudence.ayena@cofinacorp.com','d1f51cdbc915f24e8da964a7574b11bb927496dcc357feb4f966fc8fee20a84d','[\"*\"]','2024-05-10 09:50:16',NULL,'2024-05-10 08:57:14','2024-05-10 09:50:16'),(19,'App\\Models\\User',5,'tadjoudine.memem@cofinacorp.com','1e4443037bdf7763f44d44de48fa62fdf5f745d03b97bba1a6030c07a75ae158','[\"*\"]','2024-05-10 10:54:06',NULL,'2024-05-10 09:32:45','2024-05-10 10:54:06'),(20,'App\\Models\\User',5,'tadjoudine.memem@cofinacorp.com','2c2891d747771dce4416c6413c63e63886ed3d27262e1e1ee9664e7e6132ce69','[\"*\"]','2024-05-10 10:54:48',NULL,'2024-05-10 10:47:47','2024-05-10 10:54:48'),(21,'App\\Models\\User',4,'espoir.ayewoutse@cofinacorp.com','9d7990124c666ee79acfd6f5d28e0fba0af7fc88c3a5a36fa199065215e23382','[\"*\"]','2024-05-13 11:11:58',NULL,'2024-05-13 07:43:06','2024-05-13 11:11:58'),(22,'App\\Models\\User',5,'tadjoudine.memem@cofinacorp.com','aa97ad3535d29892f89340757a1cf3c423deac2979ce93b3b6d538d32e92fdfd','[\"*\"]','2024-05-13 16:53:12',NULL,'2024-05-13 08:07:55','2024-05-13 16:53:12'),(23,'App\\Models\\User',2,'ovidio.de-souza@cofinacorp.com','b1255d67bd6e7e6177f3ebbf94dc6981fa3996e6b10e62d3f4e48673e19f658b','[\"*\"]','2024-05-13 09:51:05',NULL,'2024-05-13 08:35:08','2024-05-13 09:51:05'),(24,'App\\Models\\User',2,'ovidio.de-souza@cofinacorp.com','5e8c1dde91709c1bc9fd700096ced188388516072eb6d73dd7c6a2a32aeb2026','[\"*\"]','2024-05-13 09:44:08',NULL,'2024-05-13 08:46:40','2024-05-13 09:44:08'),(25,'App\\Models\\User',2,'ovidio.de-souza@cofinacorp.com','603e1ccb6b7f215aa6909e12b20c8210711daf4874b2415e45d87f509ea57b79','[\"*\"]','2024-05-13 17:10:27',NULL,'2024-05-13 16:56:58','2024-05-13 17:10:27'),(26,'App\\Models\\User',5,'tadjoudine.memem@cofinacorp.com','bf9b7a58ce21e07b5cf7497e24dd20b56a92cdffd29432995bc25d5cad9fc98d','[\"*\"]','2024-05-14 09:25:59',NULL,'2024-05-14 08:07:50','2024-05-14 09:25:59'),(27,'App\\Models\\User',2,'ovidio.de-souza@cofinacorp.com','85a4be95aec0dfcf0f2a151f0f5a51ec697985dc773a3c53c2ee4711e221daee','[\"*\"]','2024-05-15 19:04:03',NULL,'2024-05-15 08:14:08','2024-05-15 19:04:03'),(28,'App\\Models\\User',4,'espoir.ayewoutse@cofinacorp.com','3e7bcb11dab15f46f42060d320a7f2070cfb5f16c08355f90d0634f48479defd','[\"*\"]','2024-05-15 10:38:59',NULL,'2024-05-15 10:38:57','2024-05-15 10:38:59'),(29,'App\\Models\\User',5,'tadjoudine.memem@cofinacorp.com','4677e19106c8f662bbb6670f03961e7eff78f05df8448f00bf57304174dd5222','[\"*\"]','2024-05-15 11:16:57',NULL,'2024-05-15 11:14:53','2024-05-15 11:16:57'),(30,'App\\Models\\User',2,'ovidio.de-souza@cofinacorp.com','b1c59a97cbd53b93ea58bb83e1ceb25e7fac48df144ae80ac9960feffabd4dd9','[\"*\"]','2024-05-15 11:25:07',NULL,'2024-05-15 11:19:40','2024-05-15 11:25:07'),(31,'App\\Models\\User',5,'tadjoudine.memem@cofinacorp.com','abfa945b77ee6886af7fb7ae25660b47a03bc24879467d0ec6635aed6150fbb5','[\"*\"]','2024-05-15 19:05:53',NULL,'2024-05-15 19:04:52','2024-05-15 19:05:53'),(32,'App\\Models\\User',2,'ovidio.de-souza@cofinacorp.com','0b20142f66ef1c288848320dc5f85d1fe9e9cf9dabc9d499f0becfffddcb16af','[\"*\"]','2024-05-16 08:05:07',NULL,'2024-05-16 08:05:06','2024-05-16 08:05:07');
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pledges`
--

DROP TABLE IF EXISTS `pledges`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pledges` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `contract_id` bigint unsigned NOT NULL,
  `type` enum('vehicle','stock') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pledges_contract_id_foreign` (`contract_id`),
  CONSTRAINT `pledges_contract_id_foreign` FOREIGN KEY (`contract_id`) REFERENCES `contracts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pledges`
--

LOCK TABLES `pledges` WRITE;
/*!40000 ALTER TABLE `pledges` DISABLE KEYS */;
/*!40000 ALTER TABLE `pledges` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `types_of_applicant`
--

DROP TABLE IF EXISTS `types_of_applicant`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `types_of_applicant` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `types_of_applicant_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `types_of_applicant`
--

LOCK TABLES `types_of_applicant` WRITE;
/*!40000 ALTER TABLE `types_of_applicant` DISABLE KEYS */;
INSERT INTO `types_of_applicant` VALUES (1,'Personne Physique','2024-05-06 09:36:26','2024-05-06 09:36:26'),(2,'Personne Morale','2024-05-06 09:36:26','2024-05-06 09:36:26');
/*!40000 ALTER TABLE `types_of_applicant` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `types_of_credit`
--

DROP TABLE IF EXISTS `types_of_credit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `types_of_credit` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `min_month` int NOT NULL,
  `max_month` int NOT NULL,
  `type_of_applicant_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `types_of_credit_name_min_month_max_month_unique` (`name`,`min_month`,`max_month`),
  KEY `types_of_credit_type_of_applicant_id_foreign` (`type_of_applicant_id`),
  CONSTRAINT `types_of_credit_type_of_applicant_id_foreign` FOREIGN KEY (`type_of_applicant_id`) REFERENCES `types_of_applicant` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `types_of_credit`
--

LOCK TABLES `types_of_credit` WRITE;
/*!40000 ALTER TABLE `types_of_credit` DISABLE KEYS */;
INSERT INTO `types_of_credit` VALUES (1,'AVANCE SUR FACTURE',0,6,2,'2024-05-06 09:36:26','2024-05-06 09:36:26'),(2,'AVANCE SUR FACTURE ',6,12,2,'2024-05-06 09:36:26','2024-05-06 09:36:26'),(3,'AVANCE SUR LOYER',0,6,1,'2024-05-06 09:36:26','2024-05-06 09:36:26'),(4,'AVANCE MARCHE/BC',0,6,2,'2024-05-06 09:36:26','2024-05-06 09:36:26'),(5,'AVANCE MARCHE/BC_SOLO ',6,12,2,'2024-05-06 09:36:26','2024-05-06 09:36:26'),(6,'AV SALAIRE/PENSION ',0,6,1,'2024-05-06 09:36:26','2024-05-06 09:36:26'),(7,'CREDIT DE CAMPAGNE',0,6,2,'2024-05-06 09:36:27','2024-05-06 09:36:27'),(8,'CREDIT DE CAMPAGNE',6,12,2,'2024-05-06 09:36:27','2024-05-06 09:36:27'),(9,'CREDIT EXPLOITATION',0,6,1,'2024-05-06 09:36:27','2024-05-06 09:36:27'),(10,'CREDIT EXPLOITATION',6,12,1,'2024-05-06 09:36:27','2024-05-06 09:36:27'),(11,'CREDIT DE GROUPE',6,12,2,'2024-05-06 09:36:27','2024-05-06 09:36:27'),(12,'CREDIT D\'INVESTISSEMENT',0,6,1,'2024-05-06 09:36:27','2024-05-06 09:36:27'),(13,'CREDIT D\'INVESTISSEMENT',6,12,1,'2024-05-06 09:36:27','2024-05-06 09:36:27'),(14,'CREDIT  CONSO',0,6,1,'2024-05-06 09:36:27','2024-05-06 09:36:27'),(15,'CREDIT  CONSO',6,12,1,'2024-05-06 09:36:27','2024-05-06 09:36:27'),(16,'ESCOMPTE DE CHEQUE',0,6,2,'2024-05-06 09:36:27','2024-05-06 09:36:27'),(17,'ESCOMPTE DE TRAITE',0,6,2,'2024-05-06 09:36:28','2024-05-06 09:36:28'),(18,'ESCOMPTE DE TRAITE_SOLO',6,12,2,'2024-05-06 09:36:28','2024-05-06 09:36:28'),(19,'CREDIT FDR ',0,6,2,'2024-05-06 09:36:28','2024-05-06 09:36:28'),(20,'CREDIT FDR ',6,12,2,'2024-05-06 09:36:28','2024-05-06 09:36:28'),(21,'CREDIT DE IMMOBILIER',0,6,1,'2024-05-06 09:36:28','2024-05-06 09:36:28'),(22,'CREDIT EXPLOITATION',12,24,1,'2024-05-06 09:36:28','2024-05-06 09:36:28'),(23,'CREDIT DE GROUPE',12,24,2,'2024-05-06 09:36:28','2024-05-06 09:36:28'),(24,'CREDIT D\'INVESTISSEMENT',12,24,1,'2024-05-06 09:36:28','2024-05-06 09:36:28'),(25,'CREDIT D\'INVESTISSEMENT',24,36,1,'2024-05-06 09:36:28','2024-05-06 09:36:28'),(26,'CREDIT  CONSO',12,24,1,'2024-05-06 09:36:28','2024-05-06 09:36:28'),(27,'CREDIT  CONSO',24,36,1,'2024-05-06 09:36:29','2024-05-06 09:36:29'),(28,'CREDIT FDR ',12,24,2,'2024-05-06 09:36:29','2024-05-06 09:36:29'),(29,'CREDIT IMMOBILIER',24,36,1,'2024-05-06 09:36:29','2024-05-06 09:36:29'),(30,'CREDIT D\'INVESTISSEMENT',36,120,1,'2024-05-06 09:36:29','2024-05-06 09:36:29'),(31,'CREDIT  CONSO',36,120,1,'2024-05-06 09:36:29','2024-05-06 09:36:29'),(32,'CREDIT  BFR',0,6,1,'2024-05-06 09:36:29','2024-05-06 09:36:29'),(33,'CREDIT  BFR',6,12,1,'2024-05-06 09:36:29','2024-05-06 09:36:29');
/*!40000 ALTER TABLE `types_of_credit` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `types_of_guarantee`
--

DROP TABLE IF EXISTS `types_of_guarantee`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `types_of_guarantee` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `types_of_guarantee_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `types_of_guarantee`
--

LOCK TABLES `types_of_guarantee` WRITE;
/*!40000 ALTER TABLE `types_of_guarantee` DISABLE KEYS */;
INSERT INTO `types_of_guarantee` VALUES (1,'Dépôt de garantie','2024-05-06 09:36:29','2024-05-06 09:36:29'),(2,'Caution personnelle et solidaire','2024-05-06 09:36:29','2024-05-06 09:36:29'),(3,'Gage de véhicule','2024-05-06 09:36:30','2024-05-06 09:36:30'),(4,'Gage d\'équipement','2024-05-06 09:36:30','2024-05-06 09:36:30'),(5,'Billet à ordre','2024-05-06 09:36:30','2024-05-06 09:36:30'),(6,'Engagement de domiciliation de paiement','2024-05-06 09:36:30','2024-05-06 09:36:30'),(7,'Constitution de PEP','2024-05-06 09:36:30','2024-05-06 09:36:30'),(8,'Constitution de dépôt hebdomadaire','2024-05-06 09:36:30','2024-05-06 09:36:30'),(9,'Hypothèque','2024-05-06 09:36:30','2024-05-06 09:36:30'),(10,'Nantissement de Dépôt à terme (DAT)','2024-05-06 09:36:31','2024-05-06 09:36:31');
/*!40000 ALTER TABLE `types_of_guarantee` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `full_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `profile` enum('admin','credit_analyst','credit_admin','head_credit','operation','legal','dex','caf','ca','md') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `si_profile_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activated` tinyint(1) NOT NULL DEFAULT '0',
  `password_change_required` tinyint(1) NOT NULL DEFAULT '1',
  `signatory_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_full_name_unique` (`full_name`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','admin@cofinacorp.com','admin','admin','2024-05-06 09:35:58','$2y$12$CTXkJzAADdLiudBRZlLK5ucC4AFGtv3fYjdWf/26m33sFXhgTRBQ6',NULL,1,0,NULL,'J2otqpthRA','2024-05-06 09:35:59','2024-05-06 09:35:59'),(2,'ovidio.de-souza','ovidio.de-souza@cofinacorp.com','Ovidio De SOUZA','credit_analyst','2024-05-06 09:35:59','$2y$12$VE1RDrm6VAvGFC7KThiOlOrESVro/pjRiIFmpXRfZGbDUWL5SotGa',NULL,1,0,NULL,'dXhsrrpI40','2024-05-06 09:36:00','2024-05-06 09:36:00'),(3,'prudence.ayena','prudence.ayena@cofinacorp.com','Prudence AYENA','credit_analyst','2024-05-06 09:36:00','$2y$12$n72iox7PKpSkHFTgD1MN8uFjwTacAolpx3nOkfxCsW8M/vIDXJ3DK',NULL,1,0,NULL,'DRmGcSqoGl','2024-05-06 09:36:01','2024-05-06 09:36:01'),(4,'espoir.ayewoutse','espoir.ayewoutse@cofinacorp.com','Komlan A. Espoir AYEWOUTSE','credit_admin','2024-05-06 09:36:01','$2y$12$terlenilWim8a4PfK59vTO6asjefnBY7wb6dEDTUVRHK9enROQ9wi',NULL,1,0,NULL,'rL6gK5IMpr','2024-05-06 09:36:01','2024-05-06 09:36:01'),(5,'tadjoudine.memem','tadjoudine.memem@cofinacorp.com','Tadjoudine MEMEM','credit_admin','2024-05-06 09:36:01','$2y$12$Vkxu27ayU5.NvlerXYxcDOFBGcrEJoS4bqQcdGupfMxtPmLyYeiTu',NULL,1,0,NULL,'tlbon4sDJu','2024-05-06 09:36:02','2024-05-06 09:36:02'),(6,'charles.gamado','charles.gamado@cofinacorp.com','Koffi Djramedo GAMADO','head_credit','2024-05-06 09:36:02','$2y$12$YsJ6qn2xsH8Fd/Ycu59UU.TvgMcJMcy4F.0s4l4RLu8aT65UmMd7G',NULL,1,0,NULL,'XTHN8PACsX','2024-05-06 09:36:02','2024-05-06 09:36:02'),(7,'samb.souleymane','samb.souleymane@cofinacorp.com','Souleymane Sambe','operation','2024-05-06 09:36:03','$2y$12$trZas84zSTh1JCIKAhXnDOgYc5wm7SInnf7kxxYq1/zU2SAW3lmxi',NULL,1,0,NULL,'zwgkrSGvo9','2024-05-06 09:36:03','2024-05-06 09:36:03'),(8,'mireille.messangan','mireille.messangan@cofinacorp.com','Mireille MESSANGAN','legal','2024-05-06 09:36:03','$2y$12$CF27x9p3jCB8/BOvzk/.tONld0RLK2esG0Lnp9hQP6Nkx/U0SrMqG',NULL,1,0,NULL,'WgOGp37MPA','2024-05-06 09:36:04','2024-05-06 09:36:04'),(9,'christele.febon','christele.febon@cofinacorp.com','Christele FEBON JOHNSON','dex','2024-05-06 09:36:04','$2y$12$XewfSQSjA8l2jhHXjbOQ1Owyl/sYCcUC6cwu3ldPF8wN3LZzAnLMa',NULL,1,0,NULL,'qLVrUdokXt','2024-05-06 09:36:04','2024-05-06 09:36:04'),(10,'yao.hoetowou','yao.hoetowou@cofinacorp.com','Yao HOETOWOU','caf','2024-05-06 09:36:04','$2y$12$y6V/d22b4wydRo2WPHvaB.8r7u5mczR4a0ltDGKaHU8E9PVaKQsCO',NULL,1,0,NULL,'y2yHDwNeoj','2024-05-06 09:36:05','2024-05-06 09:36:05'),(11,'simen.meba','simen.meba@cofinacorp.com','Simen Broklyn MEBA','caf','2024-05-06 09:36:05','$2y$12$NV/mNXlgoytd3U0ZLKCI9ebNmUg2Qku.21DiSDdt0zZR77BgoI.hK',NULL,1,0,NULL,'GYsZwGqfAE','2024-05-06 09:36:05','2024-05-06 09:36:05'),(12,'nykpogbe.agbenowosi','nykpogbe.agbenowosi@cofinacorp.com','Nyokpogbe AGBENOWOSI','caf','2024-05-06 09:36:06','$2y$12$WYg9OWYe8wZqO1.weIm3n.oZEPYmbR6izM04ExmAVkYpmAdEqKrX6',NULL,1,0,NULL,'8iWwT9A1FU','2024-05-06 09:36:06','2024-05-06 09:36:06'),(13,'kokou.dogbe','kokou.dogbe@cofinacorp.com','Mawule Kokou DOGBE','caf','2024-05-06 09:36:06','$2y$12$0L6otmFZi7/bqtrYr0wys.FOxOuAjwNgwDF3uByXggpmZLklLrAQ2',NULL,1,0,NULL,'qBspSV0B8q','2024-05-06 09:36:07','2024-05-06 09:36:07'),(14,'kossi.agblevon','kossi.agblevon@cofinacorp.com','Kossi AGBLEVON','caf','2024-05-06 09:36:07','$2y$12$G/yTat05XHza3de27z/nS.GRs1QI4JU3NsyTyMzVTOJXjX16QpuIG',NULL,1,0,NULL,'O8BYZezEn1','2024-05-06 09:36:07','2024-05-06 09:36:07'),(15,'dodji.amegbedji','dodji.amegbedji@cofinacorp.com','Kossi Dodji AMEGBEDJI','caf','2024-05-06 09:36:07','$2y$12$HoYRPNsiXaWoOjDftbY4ROoiEun/n/KiU9pstl10uR6RthdQcjR8e',NULL,1,0,NULL,'4NQdkOLv58','2024-05-06 09:36:08','2024-05-06 09:36:08'),(16,'kosi-mensa.duyiboe','kosi-mensa.duyiboe@cofinacorp.com','Kosi Mensa DUYIBOE','caf','2024-05-06 09:36:08','$2y$12$liEvTAz/sAPALPrraXpD6uUvj6zNX20onHzaeUVXQ75ziG9bYzE46',NULL,1,0,NULL,'fYzMihVnzE','2024-05-06 09:36:09','2024-05-06 09:36:09'),(17,'somefa.ahatefou','somefa.ahatefou@cofinacorp.com','Komlan Somefa AHATEFOU','caf','2024-05-06 09:36:09','$2y$12$3aijGBkcvg/1X3pdCwt7Lu8OnQ1HmRRp4RKUK5p1j02i3HEAmJ87O',NULL,1,0,NULL,'KaL6j4zCom','2024-05-06 09:36:09','2024-05-06 09:36:09'),(18,'komlan.dadzie','komlan.dadzie@cofinacorp.com','Komlan DADZIE','caf','2024-05-06 09:36:09','$2y$12$ylYMpCqpYXSvD8GEpukVF.8jB6YPeJjuwV1UEcBEQClZXyU8PZlvu',NULL,1,0,NULL,'w5POjhfA2t','2024-05-06 09:36:10','2024-05-06 09:36:10'),(19,'komla.ahonde','komla.ahonde@cofinacorp.com','Komla Nopeli AHONDE','caf','2024-05-06 09:36:10','$2y$12$hskUMjl8RVsZFpaVC4YcduHPH5KLSD2jOjn58GYAwL1JE1kn4Elf.',NULL,1,0,NULL,'haGdOosk2A','2024-05-06 09:36:10','2024-05-06 09:36:10'),(20,'komi.kunakey','komi.kunakey@cofinacorp.com','Komi Vienyeawu KUNAKEY','caf','2024-05-06 09:36:11','$2y$12$MfvHRPSslMh7H14XbAk6D.6n/DBJ5lagHJJoIdmD0oiYdqTd0vV1a',NULL,1,0,NULL,'WvWNH8vhj3','2024-05-06 09:36:11','2024-05-06 09:36:11'),(21,'elom.zagarago','elom.zagarago@cofinacorp.com','Komi Elom ZAGARAGO','caf','2024-05-06 09:36:11','$2y$12$y6l9bA/jkLrEO4zpv6VdwuHSQehQq0PV2BGcBY3dqgGW/4FnjzJ4W',NULL,1,0,NULL,'BrqtyA4oE9','2024-05-06 09:36:12','2024-05-06 09:36:12'),(22,'komi.noli','komi.noli@cofinacorp.com','Komi Dogbéda NOLI','caf','2024-05-06 09:36:12','$2y$12$rfUJ221cxL4E.EXHRGqlVu6hcRsBLCfeMo/sR8ChcoV/MxnIw7UJ6',NULL,1,0,NULL,'z26dMrHftV','2024-05-06 09:36:13','2024-05-06 09:36:13'),(23,'koffivi.tolessi','koffivi.tolessi@cofinacorp.com','Koffivi Amenyo TOLESSI','caf','2024-05-06 09:36:13','$2y$12$MKLYcsybA0vUYAwd4Oy6K.LDyWuHFadm8aIBUjv47VIqhvWNIbl0G',NULL,1,0,NULL,'pyyIt2k5mJ','2024-05-06 09:36:13','2024-05-06 09:36:13'),(24,'koffi.woekpo','koffi.woekpo@cofinacorp.com','Koffi WOEKPO','caf','2024-05-06 09:36:13','$2y$12$zMKroEzS21mDG8sMl3Cciu3OzXLaj07PoGlmoVi.Ei9ksvVbFlehO',NULL,1,0,NULL,'9S1VI8NzPa','2024-05-06 09:36:14','2024-05-06 09:36:14'),(25,'kadiko-pyalo.sohou','kadiko-pyalo.sohou@cofinacorp.com','Kadiko Pyalo SOHOU','caf','2024-05-06 09:36:14','$2y$12$DFshTEpB2LqB1YJJz.G12uHXcj5OtYJMoKCFy24K7ejVOVP4bUO5.',NULL,1,0,NULL,'KBYLBM4Siz','2024-05-06 09:36:14','2024-05-06 09:36:14'),(26,'joseph.agbi','joseph.agbi@cofinacorp.com','Joseph AGBI','caf','2024-05-06 09:36:14','$2y$12$QU77/fSHzN4zg7KdOVpcPO62.aheITTrkj8xk7v02apS4Ln8T9X3C',NULL,1,0,NULL,'8wMFNqQ0Jr','2024-05-06 09:36:15','2024-05-06 09:36:15'),(27,'gnonyarou.walla','gnonyarou.walla@cofinacorp.com','Gnonyarou WALLA','caf','2024-05-06 09:36:15','$2y$12$2QDniU2RVNXlhutZWpWTveGiDJdWQcXJidkz5X2AT42SdyAqKwvbS',NULL,1,0,NULL,'HE8S7nxoki','2024-05-06 09:36:15','2024-05-06 09:36:15'),(28,'gianni.koudossou','gianni.koudossou@cofinacorp.com','Gianni Patrick Attiogbe KOUDOSSOU','caf','2024-05-06 09:36:16','$2y$12$yXCdxpwWEjuEp1/dLexcJOfxb9QIXFhJJ0FFaa.DD6uuT.jd3Q9u2',NULL,1,0,NULL,'YjCFloJYWh','2024-05-06 09:36:16','2024-05-06 09:36:16'),(29,'gafar.nabine','gafar.nabine@cofinacorp.com','Gafar Tchapo NABINE','caf','2024-05-06 09:36:16','$2y$12$5ENlI73/YFLOhG1cmfBmF.7ZN2uRzw6mLfDWeu8jkQvmKkraE6z9W',NULL,1,0,NULL,'jDGeBCXvY4','2024-05-06 09:36:17','2024-05-06 09:36:17'),(30,'francis.gavisse','francis.gavisse@cofinacorp.com','Francis K GAVISSE','caf','2024-05-06 09:36:17','$2y$12$1wMkEEJcq3v833QH3y/aT.uc6zcPnkyyrCfkIAjxDE7B.KPvYACV.',NULL,1,0,NULL,'HU7TTHYPaX','2024-05-06 09:36:17','2024-05-06 09:36:17'),(31,'fatima.tcha-coroudou','fatima.tcha-coroudou@cofinacorp.com','Fatima Badoawè TCHA-COROUDOU','caf','2024-05-06 09:36:17','$2y$12$GmeU4f7jS2zFWYiEossW7eRD08jtEGm79.BFAFfjmeX5/ztNllcAO',NULL,1,0,NULL,'IrdLZ9zVo1','2024-05-06 09:36:18','2024-05-06 09:36:18'),(32,'david-martial.soussoukpo','david-martial.soussoukpo@cofinacorp.com','David Martial SOUSSOUKPO','caf','2024-05-06 09:36:18','$2y$12$QaDg3s1OaCuDG3ZdyVil6OSWoPkRkrNFmzCJTN3iEzBkSz0UMYC9q',NULL,1,0,NULL,'RUkAQyqpqR','2024-05-06 09:36:18','2024-05-06 09:36:18'),(33,'chimene.azegue','chimene.azegue@cofinacorp.com','Chimene AZEGUE','caf','2024-05-06 09:36:18','$2y$12$bnskxuNLEqEVTb9RfS6qO.NLkAccuGQrQuHSERdTc9uP01546TczC',NULL,1,0,NULL,'vglOk9nfok','2024-05-06 09:36:19','2024-05-06 09:36:19'),(34,'baliza.tekpezi','baliza.tekpezi@cofinacorp.com','Baliza TEKPEZI','caf','2024-05-06 09:36:19','$2y$12$qNJ6sfv1q7M05f5CJe.f2.h7HwQZEpz3i0TAKHbvfzHBAPM9I/LTO',NULL,1,0,NULL,'ENun2MX8WW','2024-05-06 09:36:19','2024-05-06 09:36:19'),(35,'assowe.mabougre','assowe.mabougre@cofinacorp.com','Assowè MABOUGRE','caf','2024-05-06 09:36:20','$2y$12$xOyCgOrZQAhHydUGBVihjeMW8ZJTs2xZmLSoRsiYeKLtK02atqL0q',NULL,1,0,NULL,'jRQUk36je4','2024-05-06 09:36:20','2024-05-06 09:36:20'),(36,'edwige.attila','edwige.attila@cofinacorp.com','Améyo Chimène Edwige ATTILA','caf','2024-05-06 09:36:20','$2y$12$.1FvzZk30TwDZ61slUYoje6wIHRBdGlnqbXJjvWE55bGSSDbg3sq.',NULL,1,0,NULL,'9uIs0ZL6Xb','2024-05-06 09:36:21','2024-05-06 09:36:21'),(37,'amah.ayikoe','amah.ayikoe@cofinacorp.com','Amah AYIKOE','caf','2024-05-06 09:36:21','$2y$12$pfNPRiObaNwlyM7HlEJMO.XJ0c4GpBYwA1sQeIiyA8YXoeHu6jOui',NULL,1,0,NULL,'NG5zALpNEC','2024-05-06 09:36:22','2024-05-06 09:36:22'),(38,'aime.hounsou-degbe','aime.hounsou-degbe@cofinacorp.com','Aimé Edem HOUNSOU DEGBE','caf','2024-05-06 09:36:22','$2y$12$ZB3PFgcXjKYql7fWAMxt6.gQzoKXr.yGLZ7XES/3U4foOJVT1XVFm',NULL,1,0,NULL,'lQau8UiFNz','2024-05-06 09:36:22','2024-05-06 09:36:22'),(39,'abra.dougame','abra.dougame@cofinacorp.com','Abra DOUGAME','caf','2024-05-06 09:36:22','$2y$12$.wTXEfEBzw3d.pIJqVLhiOAWP5oIccpSS65njiC2OW47Qj6BD8kEW',NULL,1,0,NULL,'8zTgqymhRV','2024-05-06 09:36:23','2024-05-06 09:36:23'),(40,'samuel.akakpo','samuel.akakpo@cofinacorp.com','Ablam Samuel AKAKPO','caf','2024-05-06 09:36:23','$2y$12$9H0OFnjpZUPhnTkhQzRw4e40F0LqBaVlItCC0A0KgamxTACELwY2i',NULL,1,0,NULL,'SOOSemCKuc','2024-05-06 09:36:23','2024-05-06 09:36:23'),(41,'abire.kpenifei','abire.kpenifei@cofinacorp.com','Abire KPENIFEI KPATCHA','caf','2024-05-06 09:36:23','$2y$12$v0Cf0aOSAZER3tF/mZUVreASL0p43E/Lbe3EiTmBGBZG8tgMIJBIG',NULL,1,0,NULL,'6TFjYxMAo9','2024-05-06 09:36:24','2024-05-06 09:36:24'),(42,'kabirou.yomenou','kabirou.yomenou@cofinacorp.com','Abdou Kabirou YOMENOU','caf','2024-05-06 09:36:24','$2y$12$5zDmmVpltE7UbxC729cz7O2cP7yFFvMijYBzE5nK/1qduvai.1noi',NULL,1,0,NULL,'GDViaymgWL','2024-05-06 09:36:25','2024-05-06 09:36:25'),(43,'mawulolo.yebovi','mawulolo.yebovi@cofinacorp.com','Ayi Mawulolo YEBOVI','ca','2024-05-06 09:36:25','$2y$12$UvzzRA6ICEaDjR/eIfWHcOHnkzx.YXTRNLo0Ztd/J4eHOSbU0wUHK',NULL,1,0,NULL,'dND6yccGYJ','2024-05-06 09:36:25','2024-05-06 09:36:25'),(44,'marcel.digbe','marcel.digbe@cofinacorp.com','Marcel DIGBE','md','2024-05-06 09:36:25','$2y$12$vKRoWeDPG.Ck1DCPy8rlVuBZTya.qWbhrK8LnNQQWKV4RuEJOPy4i',NULL,1,0,NULL,'j0JBXgBFky','2024-05-06 09:36:26','2024-05-06 09:36:26');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `verbals_trials`
--

DROP TABLE IF EXISTS `verbals_trials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `verbals_trials` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `committee_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `committee_date` date NOT NULL,
  `civility` enum('Mr','Mme','Mlle') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `applicant_first_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `applicant_last_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `activity` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `purpose_of_financing` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_of_credit_id` bigint unsigned NOT NULL,
  `amount` decimal(21,2) NOT NULL,
  `duration` int NOT NULL,
  `periodicity` enum('mensual','quarterly','semi-annual','annual','in-fine') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `taf` double(8,2) NOT NULL,
  `due_amount` decimal(21,2) NOT NULL,
  `administrative_fees_percentage` decimal(21,2) NOT NULL,
  `insurance_premium` decimal(21,2) NOT NULL,
  `tax_fee_interest_rate` double(8,2) NOT NULL,
  `caf_id` bigint unsigned NOT NULL,
  `creator_id` bigint unsigned NOT NULL,
  `validation_level` enum('dex','head_credit','md') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'head_credit',
  `status` enum('waiting','rejected','validated') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'waiting',
  `comment` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `verbals_trials_committee_id_unique` (`committee_id`),
  KEY `verbals_trials_type_of_credit_id_foreign` (`type_of_credit_id`),
  KEY `verbals_trials_caf_id_foreign` (`caf_id`),
  KEY `verbals_trials_creator_id_foreign` (`creator_id`),
  CONSTRAINT `verbals_trials_caf_id_foreign` FOREIGN KEY (`caf_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `verbals_trials_creator_id_foreign` FOREIGN KEY (`creator_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `verbals_trials_type_of_credit_id_foreign` FOREIGN KEY (`type_of_credit_id`) REFERENCES `types_of_credit` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `verbals_trials`
--

LOCK TABLES `verbals_trials` WRITE;
/*!40000 ALTER TABLE `verbals_trials` DISABLE KEYS */;
INSERT INTO `verbals_trials` VALUES (1,'CFNTG-046-17-04-24-00201','2024-05-05','Mr','APEDO KOMLAN','MONDJRO','251040810001','Vente de graviers et sables','Renforcer son stock',33,2000000.00,12,'mensual',10.00,184027.00,2.79,1.00,17.00,12,2,'head_credit','validated',NULL,'2024-05-07 17:38:56','2024-05-13 07:48:56'),(2,'CFNTG-044-15-04-24-00706','2024-05-05','Mr','ETS','ASSIODEI','254049708001','Produits alimentaires','S\'approvisionner en produits alimentaires',33,8000000.00,12,'mensual',10.00,736108.00,2.60,10.00,17.00,38,2,'head_credit','waiting',NULL,'2024-05-07 17:50:25','2024-05-07 18:00:40'),(3,'CFNTG-044-16-04-24-00807','2024-05-06','Mr','KOFFI','KPOTUFE','251050581001','Quincaillerie','S\'approvisionner en articles de quincaillerie',28,4000000.00,18,'mensual',10.00,256560.00,2.50,1.00,17.00,10,2,'head_credit','validated',NULL,'2024-05-07 18:09:12','2024-05-08 12:06:34'),(4,'-CFNTG-046-24-04-24-00503','2024-05-05','Mme','SARL U','VICKY ORISAKWE','251040182001','Commerce de vêtements','S\'approvisionner en habit femme',28,10000000.00,18,'mensual',10.00,641400.00,2.79,1.00,17.00,14,2,'head_credit','validated',NULL,'2024-05-07 18:15:28','2024-05-13 15:00:34'),(5,'CFNTG-044-18-04-24-00504','2024-05-05','Mme','ENTREPRISE INDIVIDUELLE','ETS MALIVIA','251040555001','REVENDEUSE','Acheter de nouvelles gammes de pagnes',28,5000000.00,18,'mensual',10.00,320700.00,2.40,1.00,17.00,14,2,'head_credit','validated',NULL,'2024-05-07 18:53:36','2024-05-08 12:07:56'),(6,'CFNTG-045-22-04-24-00101','2024-05-06','Mme','DJIGBODI ABRA','OKOUMA','251043664001','Revendeuse','Achat de céréales (maïs et haricot)',28,7500000.00,18,'mensual',10.00,481050.00,2.80,54620.00,17.00,26,2,'head_credit','waiting',NULL,'2024-05-08 17:07:10','2024-05-08 17:14:34'),(7,'CFNTG-044-22-04-24-00301','2024-05-06','Mr','OUMAR','BARRY','251051308001','Commerce des accessoires et pièces de portables','financement de stocks',33,30000000.00,12,'mensual',10.00,2760404.00,2.79,150000.00,17.00,25,3,'head_credit','validated',NULL,'2024-05-10 09:31:12','2024-05-10 09:57:16'),(8,'CFNTG-045-27-04-24-00202','2024-05-12','Mr','ETS AFRICA MODERNE ET FRERES','ENTREPRISE INDIVIDUELLE','251042525001','Vente d\'articles de quincaillerie','S\'approvisionner en articles de quincaillerie',28,10000000.00,18,'mensual',10.00,641400.00,2.70,1.00,17.00,11,2,'head_credit','waiting',NULL,'2024-05-13 09:38:33','2024-05-13 09:38:33'),(9,'CFNTG-044-25-03-24-01608','2024-05-12','Mme','WODOME','Massan','251049760001','Revendeuse de pagnes','Achat de pagnes',28,5000000.00,18,'mensual',10.00,320700.00,2.79,1.00,17.00,19,2,'head_credit','validated',NULL,'2024-05-13 17:10:15','2024-05-14 08:09:56');
/*!40000 ALTER TABLE `verbals_trials` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-05-16  9:32:46
