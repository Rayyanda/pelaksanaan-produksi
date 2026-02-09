-- phpMyAdmin SQL Dump
-- version 6.0.0-dev+20251215.aa153def95
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 04, 2026 at 01:02 AM
-- Server version: 8.0.30
-- PHP Version: 8.4.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pelaksanaan-produksi`
--

-- --------------------------------------------------------

--
-- Table structure for table `batches`
--

CREATE TABLE `batches` (
  `id` bigint UNSIGNED NOT NULL,
  `batch_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `po_production_id` bigint UNSIGNED NOT NULL,
  `part_internal_id` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL DEFAULT '0',
  `target_completed` date DEFAULT NULL,
  `status` enum('pending','in_progress','completed','on_hold','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `part_no_customer` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `part_no_customer_source` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `drawing_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `drawing_number_source` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `batches`
--

INSERT INTO `batches` (`id`, `batch_number`, `po_production_id`, `part_internal_id`, `quantity`, `target_completed`, `status`, `part_no_customer`, `part_no_customer_source`, `drawing_number`, `drawing_number_source`, `deleted_at`, `created_at`, `updated_at`) VALUES
(2, '3802-009-011', 1, 1, 3, '2026-05-07', 'pending', NULL, NULL, NULL, NULL, NULL, '2026-01-04 02:13:11', '2026-01-04 02:13:11'),
(3, '5678343-1', 1, 1, 8, '2026-06-13', 'pending', NULL, NULL, NULL, NULL, NULL, '2026-01-04 23:59:57', '2026-01-04 23:59:57'),
(4, '454234', 1, 1, 10, '2026-06-19', 'pending', NULL, NULL, NULL, NULL, NULL, '2026-01-07 04:08:33', '2026-01-07 04:08:33'),
(5, '5987645-0', 2, 3, 20, '2026-07-04', 'pending', NULL, NULL, NULL, NULL, NULL, '2026-01-08 09:42:41', '2026-01-08 09:42:41'),
(6, '21012026-84441', 3, 1, 8, '2026-07-18', 'pending', NULL, NULL, '66785A', NULL, NULL, '2026-01-21 00:18:45', '2026-01-21 00:18:45');

-- --------------------------------------------------------

--
-- Table structure for table `batch_operations`
--

CREATE TABLE `batch_operations` (
  `id` bigint UNSIGNED NOT NULL,
  `batch_id` bigint UNSIGNED NOT NULL,
  `part_operation_id` bigint UNSIGNED NOT NULL,
  `qty_target` int DEFAULT NULL,
  `qty_pass` int DEFAULT NULL,
  `operation_date` date DEFAULT NULL,
  `operator_id` bigint UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `batch_operations`
--

INSERT INTO `batch_operations` (`id`, `batch_id`, `part_operation_id`, `qty_target`, `qty_pass`, `operation_date`, `operator_id`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 2, 8, 3, 3, '2026-01-04', 1, NULL, '2026-01-04 13:08:18', '2026-01-04 13:08:18'),
(2, 2, 9, 3, 3, '2026-01-04', 1, NULL, '2026-01-04 13:09:27', '2026-01-04 13:09:27'),
(3, 2, 10, 3, 3, '2026-01-04', 1, NULL, '2026-01-04 13:11:32', '2026-01-04 13:11:32'),
(4, 2, 11, 3, 3, '2026-01-05', 1, NULL, '2026-01-05 00:17:58', '2026-01-05 00:17:58'),
(5, 3, 1, 8, 8, '2026-01-07', 2, NULL, '2026-01-07 03:42:23', '2026-01-07 03:42:23'),
(6, 3, 2, 8, 8, '2026-01-08', 1, NULL, '2026-01-08 09:44:14', '2026-01-08 09:44:14'),
(8, 6, 1, 8, 8, '2026-01-21', 1, NULL, '2026-01-21 03:58:43', '2026-01-21 03:58:43');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--



-- --------------------------------------------------------

--
-- Table structure for table `divisions`
--

CREATE TABLE `divisions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `divisions`
--

INSERT INTO `divisions` (`id`, `name`, `code`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Wax Room', 'DIV-4BFF9F', 'waxing', 1, '2025-12-27 06:11:48', '2025-12-27 06:11:48'),
(2, 'Mould Room', 'DIV-73CA9B', 'mould_room', 1, '2025-12-27 06:23:35', '2025-12-27 06:23:35'),
(3, 'Melting Room', 'DIV-D58D14', 'melting', 1, '2025-12-27 06:25:17', '2025-12-27 06:25:17'),
(4, 'Cut Off', 'DIV-17E81F', 'cut_off', 1, '2025-12-27 13:46:25', '2025-12-27 13:46:25'),
(5, 'Heat Treatment', 'DIV-510A67', 'heat_treatment', 1, '2025-12-27 14:07:49', '2025-12-27 14:07:49'),
(6, 'Finishing', 'DIV-E84BF8', 'finishing', 1, '2025-12-27 14:08:30', '2025-12-27 14:08:30'),
(7, 'Machining', 'DIV-6ACF12', 'machining', 1, '2025-12-27 14:08:38', '2025-12-27 14:08:38'),
(8, 'Quality Control', 'DIV-ADAD95', 'quality_control', 1, '2025-12-27 14:08:58', '2025-12-27 14:08:58');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

-----

--
-- Table structure for table `part_internals`
--

CREATE TABLE `part_internals` (
  `id` bigint UNSIGNED NOT NULL,
  `part_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nointernal` int DEFAULT NULL,
  `part_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `matl_spec` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `matl_req` decimal(15,2) DEFAULT NULL,
  `deoxidation` text COLLATE utf8mb4_unicode_ci,
  `comp_per_mould` int DEFAULT NULL,
  `target_qty_waxing` int DEFAULT NULL,
  `target_qty_mould_room` int DEFAULT NULL,
  `target_qty_melting` int DEFAULT NULL,
  `target_qty_heat_treatment` int DEFAULT NULL,
  `target_qty_cut_off` int DEFAULT NULL,
  `target_qty_finishing` int DEFAULT NULL,
  `target_qty_machining` int DEFAULT NULL,
  `target_qty_quality_control` int DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `part_internals`
--

INSERT INTO `part_internals` (`id`, `part_number`, `nointernal`, `part_name`, `matl_spec`, `matl_req`, `deoxidation`, `comp_per_mould`, `target_qty_waxing`, `target_qty_mould_room`, `target_qty_melting`, `target_qty_heat_treatment`, `target_qty_cut_off`, `target_qty_finishing`, `target_qty_machining`, `target_qty_quality_control`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'P18-03a.MC.26', 26, 'Pump Bowl Leitschaufelgehause PL08-0095/1', 'G-X5CrNiMo 19-11-2', 35.88, 'Deoxidation I :\r\nFe Si = 0,15%\r\nCa Si = 0,15%\r\nAl = 0,10%\r\n\r\nDeoxidation II :\r\nFe Si = 0,15%\r\nCa Si = 0,15%\r\nAl = 0,10%', 1, 7, 20, 20, 30, 50, 40, 30, 50, NULL, '2026-01-03 16:04:45', '2026-01-04 23:59:20'),
(2, 'VB-001', 32, 'Valve Body Type A', 'G-X5CrNiMo 19-11-2', 55.00, NULL, 10, 7, 50, 50, 50, 100, 80, 60, 100, NULL, '2026-01-04 23:57:25', '2026-01-04 23:57:25'),
(3, 'D2-74MC', 51, NULL, 'G-X5CrNiMoNb', 142.60, NULL, 1, 30, 20, 50, 30, 20, 20, 20, 50, NULL, '2026-01-08 07:02:50', '2026-01-08 07:02:50');

-- --------------------------------------------------------

--
-- Table structure for table `part_operations`
--

CREATE TABLE `part_operations` (
  `id` bigint UNSIGNED NOT NULL,
  `part_internal_id` bigint UNSIGNED NOT NULL,
  `division_id` bigint UNSIGNED DEFAULT NULL,
  `operation_data` text COLLATE utf8mb4_unicode_ci,
  `route_order` int NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `part_operations`
--

INSERT INTO `part_operations` (`id`, `part_internal_id`, `division_id`, `operation_data`, `route_order`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'CLASS industrial Grade : C\r\nINJECT/POUR RUNNER NO : DEVELOPMENT\r\nPATTERN DIE :\r\n2282B PSI 125/P9 SEC 120\r\n2282C PSI 400/P6 SEC 60\r\nInject Sol Die :\r\n2282A PSI 600/P4 SEC 60\r\nWax Temp : 70+-5C', 1, NULL, '2025-12-27 06:18:07', '2025-12-27 06:18:07'),
(2, 1, 1, 'PATTERN ASSY PER WRA CARD DEVELOPMENT\r\nVISUAL CHECK FOR DEFECTS\r\nBerat assembling 4,75 kg', 2, NULL, '2025-12-27 06:21:35', '2025-12-27 06:21:35'),
(3, 1, 2, 'MOULD PREPARE PER QC INSTRUCTION\r\nPRIMARY NORMAL, AIR-DRY 4HRS\r\nSECY 2 F + 1 C + ceramic core + 3 C (20/06/2024_Pak Parlan)\r\nEXTRA AIR DRY 12 HRS PER COAT\r\n12 Jam dry sebelum dewax', 3, NULL, '2025-12-27 06:35:45', '2025-12-27 06:35:45'),
(4, 1, 3, 'DEWAX 10 MINS\r\nVISAL INSPECTION & CLEAN OUT', 4, NULL, '2025-12-27 06:37:19', '2025-12-27 07:09:37'),
(5, 1, 3, 'FIRE 2/3 HRS 700C Cast 1580+-5C\r\nIN VACUUM ASSIST^AT....................................M BAR\r\nPenuangan normal, ditaruh di atas fixture alumunium dan blower pada bagian bawah (pastikan angin pas dilubang)\r\nUpdate perubahan gating 2', 5, NULL, '2025-12-27 13:46:00', '2025-12-27 13:46:00'),
(6, 1, 4, 'MECH. KNOCK OUT :      YES', 6, NULL, '2025-12-27 13:47:17', '2025-12-27 13:47:17'),
(7, 1, 4, 'CUT-OF T/P TO Q.T', 7, NULL, '2025-12-27 13:47:46', '2025-12-27 13:47:46'),
(8, 1, 4, 'Bersihkan Keramik/ShotBlast awal', 8, NULL, '2025-12-27 14:07:20', '2025-12-27 14:07:20'),
(9, 1, 5, 'GRIND GATES TO 0.5mm Max, buang pin', 9, NULL, '2025-12-27 14:10:03', '2025-12-27 14:10:03'),
(10, 1, 5, 'HEAT TREATMENT:\r\nSOLUTION TREATMENT\r\nTemp = 1130C\r\nWaktu = 70 Menit\r\nPendinginan = Celup dalam air', 10, NULL, '2025-12-27 14:10:57', '2025-12-27 14:10:57'),
(11, 1, 5, 'HARDNESS CHECK                              RESULT:\r\nTENSILE TEST                               UTS RESULT:\r\n                                                           YS RESULT:\r\n%ELONGATION                                    RESULT:', 11, NULL, '2025-12-27 14:13:37', '2025-12-27 14:13:37'),
(12, 1, 6, 'HAND DRESS AS REQUIRED', 12, NULL, '2025-12-27 14:13:59', '2025-12-27 14:13:59'),
(13, 1, 6, 'Straightening IK No : IK/SM/P18-03 rev.00', 13, NULL, '2025-12-27 14:14:36', '2025-12-27 14:14:36'),
(14, 1, 6, 'GRIT BLAST', 14, NULL, '2025-12-27 14:14:54', '2025-12-27 14:14:54'),
(15, 1, 6, 'Pikling WI No. : PP/CF/001', 15, NULL, '2025-12-27 14:15:23', '2025-12-27 14:15:23'),
(16, 1, 6, 'DYE PENETRATION:', 16, NULL, '2025-12-27 14:16:10', '2025-12-27 14:16:10'),
(17, 1, 7, 'Machining refer to drawing P18-03d.26 MC', 17, NULL, '2025-12-27 14:16:49', '2025-12-27 14:16:49'),
(18, 1, 7, 'X-RAY TECH NO:', 18, NULL, '2025-12-27 14:17:44', '2025-12-27 14:17:44'),
(19, 1, 8, 'Cek dimensi dan visual', 19, NULL, '2025-12-27 14:18:06', '2025-12-27 14:18:06'),
(20, 1, 8, 'FINAL INSPECTION TO DWG & ORDER', 20, NULL, '2025-12-27 14:18:26', '2025-12-27 14:18:26'),
(21, 3, 1, 'CLASS industrial Grade C\r\nINJECT/POUR RUNNER NO : Development\r\nPATTERN DIE NO :\r\n2237A/B/C PSI 500/P7 SEC 120\r\n2237D PSI 500/P6 SEC 60\r\nInject Sol Die : -\r\nWax Temp : 70Â±5Â°C', 1, NULL, '2026-01-08 07:14:39', '2026-01-08 07:14:39'),
(22, 3, 1, 'PATTERN ASSY PER WRA CARD Development\r\nVISUAL CHECK FOR DEFECTS', 2, NULL, '2026-01-08 07:14:39', '2026-01-08 07:14:39'),
(23, 3, 2, 'MOULD PREPARE PER QC.INSTRUCTION\r\nPRIMARY NORMAL, AIR-DRY 4 HRS \r\nSECY 1 F + 3 C\r\nEXTRA AIR DRY 12 HRS PER COAT\r\n12 Jam dry sebelum dewax', 3, NULL, '2026-01-08 07:14:39', '2026-01-08 07:14:39'),
(24, 3, 2, 'DEWAX 10 MINS \r\nVISUAL INSPECTION & CLEAN OUT', 4, NULL, '2026-01-08 07:14:39', '2026-01-08 07:14:39'),
(25, 3, 3, 'Firing Temp.1100Â°C di tahan selama 2 jam', 5, NULL, '2026-01-08 07:14:39', '2026-01-08 07:14:39'),
(26, 3, 3, 'Reshell 2 C', 6, NULL, '2026-01-08 07:14:39', '2026-01-08 07:14:39'),
(27, 3, 3, 'FIRE 2/3 HRS 700Â°C Cast 1580+5Â°C\r\nIN VACUUM ASSIST^AT ....................... M BAR \r\nSemprot angin bagian dalam dan lekukan', 7, NULL, '2026-01-08 07:14:39', '2026-01-08 07:14:39'),
(28, 3, 4, 'MECH. KNOCK OUT : Yes', 8, NULL, '2026-01-08 07:14:39', '2026-01-08 07:14:39'),
(29, 3, 4, 'CUT-OF T/P TO Q.T', 9, NULL, '2026-01-08 07:14:39', '2026-01-08 07:14:39'),
(30, 3, 4, 'Bersihkan Keramik / ShotBlast Awal', 10, NULL, '2026-01-08 07:14:39', '2026-01-08 07:14:39'),
(31, 3, 4, 'GRIND GATES TO flush Max, buang pin', 11, NULL, '2026-01-08 07:14:39', '2026-01-08 07:14:39'),
(32, 3, 5, 'HEAT TREATMENT :\r\nSOLUTION TREATMENT :\r\nTemp. = 850Â°C ; Waktu 60 menit\r\nTemp. = 1080Â°C ; Waktu 90 menit\r\nPendinginan = Celup dalam air', 12, NULL, '2026-01-08 07:14:39', '2026-01-08 07:14:39'),
(33, 3, 5, 'HARDNESS CHECK 130-200 HB RESULT :\r\nTENSILE TEST N/A UTS RESULT :\r\n YS RESULT :\r\n%ELONGATION N/A RESULT :', 13, NULL, '2026-01-08 07:14:39', '2026-01-08 07:14:39'),
(34, 3, 6, 'HAND DRESS AS REQUIRED', 15, NULL, '2026-01-08 07:14:39', '2026-01-08 07:14:39'),
(35, 3, 6, 'Straightening IK No. : IK/SM/D2-74 rev.00', 16, NULL, '2026-01-08 07:14:39', '2026-01-08 07:14:39'),
(36, 3, 6, 'GRIT BLAST', 17, NULL, '2026-01-08 07:14:39', '2026-01-08 07:14:39'),
(37, 3, 7, 'Pikling WI No. : PP/CF/001', 18, NULL, '2026-01-08 07:14:39', '2026-01-08 07:14:39'),
(38, 3, 7, 'DYE PENETRATION : N/A', 19, NULL, '2026-01-08 07:14:39', '2026-01-08 07:14:39'),
(39, 3, 7, 'Machining sesuai drawing D2-74.MC', 20, NULL, '2026-01-08 07:14:39', '2026-01-08 07:14:39'),
(40, 3, 8, 'X-RAY TECH NO : N/A', 21, NULL, '2026-01-08 07:14:39', '2026-01-08 07:14:39'),
(41, 3, 8, 'Cek dimensi dan visual', 22, NULL, '2026-01-08 07:14:39', '2026-01-08 07:14:39'),
(42, 3, 6, 'FINAL INSPECTION TO DWG & ORDER', 23, NULL, '2026-01-08 07:14:39', '2026-01-08 07:14:39');

-- --------------------------------------------------------



--
-- Table structure for table `po_productions`
--

CREATE TABLE `po_productions` (
  `id` bigint UNSIGNED NOT NULL,
  `po_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `po_snapshot` json DEFAULT NULL,
  `quantity` int NOT NULL,
  `due_date` date DEFAULT NULL,
  `po_source` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `po_productions`
--

INSERT INTO `po_productions` (`id`, `po_number`, `po_snapshot`, `quantity`, `due_date`, `po_source`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, '21004463', NULL, 3, '2026-05-07', NULL, NULL, '2026-01-03 15:57:46', '2026-01-03 15:57:46'),
(2, '34252464', NULL, 20, '2026-07-04', NULL, NULL, '2026-01-08 06:51:52', '2026-01-08 06:52:06'),
(3, '667785-088', NULL, 8, '2026-07-18', NULL, NULL, '2026-01-21 00:18:07', '2026-01-21 00:18:07');

-- --------------------------------------------------------

--
-- Table structure for table `production_schedules`
--

CREATE TABLE `production_schedules` (
  `id` bigint UNSIGNED NOT NULL,
  `batch_id` bigint UNSIGNED NOT NULL,
  `process_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `duration_weeks` int NOT NULL,
  `plan_start_date` date NOT NULL,
  `plan_end_date` date NOT NULL,
  `plan_qty` int NOT NULL,
  `actual_start_date` date DEFAULT NULL,
  `actual_end_date` date DEFAULT NULL,
  `actual_qty` int DEFAULT NULL,
  `status` enum('planned','in_progress','completed','delayed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'planned',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `production_schedules`
--

INSERT INTO `production_schedules` (`id`, `batch_id`, `process_name`, `duration_weeks`, `plan_start_date`, `plan_end_date`, `plan_qty`, `actual_start_date`, `actual_end_date`, `actual_qty`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(1, 3, 'waxing', 2, '2026-01-05', '2026-01-18', 8, '2026-01-05', '2026-01-08', 8, 'completed', '', '2026-01-04 23:59:57', '2026-01-08 09:44:14'),
(2, 3, 'mould_room', 1, '2026-01-19', '2026-01-25', 8, NULL, NULL, NULL, 'planned', NULL, '2026-01-04 23:59:57', '2026-01-04 23:59:57'),
(3, 3, 'heat_treatment', 1, '2026-01-26', '2026-02-01', 8, NULL, NULL, NULL, 'planned', '', '2026-01-04 23:59:57', '2026-01-05 00:17:58'),
(4, 3, 'melting', 1, '2026-02-02', '2026-02-08', 8, NULL, NULL, NULL, 'planned', NULL, '2026-01-04 23:59:57', '2026-01-04 23:59:57'),
(5, 3, 'cut_off', 1, '2026-02-09', '2026-02-15', 8, NULL, NULL, NULL, 'planned', NULL, '2026-01-04 23:59:57', '2026-01-04 23:59:57'),
(6, 3, 'finishing', 1, '2026-02-16', '2026-02-22', 8, NULL, NULL, NULL, 'planned', NULL, '2026-01-04 23:59:57', '2026-01-04 23:59:57'),
(7, 3, 'machining', 1, '2026-02-23', '2026-03-01', 8, NULL, NULL, NULL, 'planned', NULL, '2026-01-04 23:59:57', '2026-01-04 23:59:57'),
(8, 3, 'quality_control', 1, '2026-03-02', '2026-03-08', 8, NULL, NULL, NULL, 'planned', NULL, '2026-01-04 23:59:57', '2026-01-04 23:59:57'),
(9, 4, 'waxing', 2, '2026-01-07', '2026-01-20', 10, NULL, NULL, NULL, 'planned', NULL, '2026-01-07 04:08:33', '2026-01-07 04:08:33'),
(10, 4, 'mould_room', 1, '2026-01-21', '2026-01-27', 10, NULL, NULL, NULL, 'planned', NULL, '2026-01-07 04:08:33', '2026-01-07 04:08:33'),
(11, 4, 'heat_treatment', 1, '2026-01-28', '2026-02-03', 10, NULL, NULL, NULL, 'planned', NULL, '2026-01-07 04:08:33', '2026-01-07 04:08:33'),
(12, 4, 'melting', 1, '2026-02-04', '2026-02-10', 10, NULL, NULL, NULL, 'planned', NULL, '2026-01-07 04:08:33', '2026-01-07 04:08:33'),
(13, 4, 'cut_off', 1, '2026-02-11', '2026-02-17', 10, NULL, NULL, NULL, 'planned', NULL, '2026-01-07 04:08:33', '2026-01-07 04:08:33'),
(14, 4, 'finishing', 1, '2026-02-18', '2026-02-24', 10, NULL, NULL, NULL, 'planned', NULL, '2026-01-07 04:08:33', '2026-01-07 04:08:33'),
(15, 4, 'machining', 1, '2026-02-25', '2026-03-03', 10, NULL, NULL, NULL, 'planned', NULL, '2026-01-07 04:08:33', '2026-01-07 04:08:33'),
(16, 4, 'quality_control', 1, '2026-03-04', '2026-03-10', 10, NULL, NULL, NULL, 'planned', NULL, '2026-01-07 04:08:33', '2026-01-07 04:08:33'),
(17, 5, 'waxing', 1, '2026-01-08', '2026-01-14', 20, '2026-01-21', NULL, NULL, 'in_progress', NULL, '2026-01-08 09:42:41', '2026-01-21 04:15:41'),
(18, 5, 'mould_room', 1, '2026-01-15', '2026-01-21', 20, NULL, NULL, NULL, 'planned', NULL, '2026-01-08 09:42:41', '2026-01-08 09:42:41'),
(19, 5, 'heat_treatment', 1, '2026-01-22', '2026-01-28', 20, NULL, NULL, NULL, 'planned', NULL, '2026-01-08 09:42:41', '2026-01-08 09:42:41'),
(20, 5, 'melting', 1, '2026-01-29', '2026-02-04', 20, NULL, NULL, NULL, 'planned', NULL, '2026-01-08 09:42:41', '2026-01-08 09:42:41'),
(21, 5, 'cut_off', 1, '2026-02-05', '2026-02-11', 20, NULL, NULL, NULL, 'planned', NULL, '2026-01-08 09:42:41', '2026-01-08 09:42:41'),
(22, 5, 'finishing', 1, '2026-02-12', '2026-02-18', 20, NULL, NULL, NULL, 'planned', NULL, '2026-01-08 09:42:41', '2026-01-08 09:42:41'),
(23, 5, 'machining', 1, '2026-02-19', '2026-02-25', 20, NULL, NULL, NULL, 'planned', NULL, '2026-01-08 09:42:41', '2026-01-08 09:42:41'),
(24, 5, 'quality_control', 1, '2026-02-26', '2026-03-04', 20, NULL, NULL, NULL, 'planned', NULL, '2026-01-08 09:42:41', '2026-01-08 09:42:41'),
(25, 6, 'waxing', 2, '2026-01-21', '2026-02-03', 8, '2026-01-21', '2026-01-21', 8, 'completed', '', '2026-01-21 00:18:45', '2026-01-21 03:58:43'),
(26, 6, 'mould_room', 1, '2026-02-04', '2026-02-10', 8, NULL, NULL, NULL, 'planned', NULL, '2026-01-21 00:18:45', '2026-01-21 00:18:45'),
(27, 6, 'heat_treatment', 1, '2026-02-11', '2026-02-17', 8, NULL, NULL, NULL, 'planned', NULL, '2026-01-21 00:18:45', '2026-01-21 00:18:45'),
(28, 6, 'melting', 1, '2026-02-18', '2026-02-24', 8, NULL, NULL, NULL, 'planned', NULL, '2026-01-21 00:18:45', '2026-01-21 00:18:45'),
(29, 6, 'cut_off', 1, '2026-02-25', '2026-03-03', 8, NULL, NULL, NULL, 'planned', NULL, '2026-01-21 00:18:45', '2026-01-21 00:18:45'),
(30, 6, 'finishing', 1, '2026-03-04', '2026-03-10', 8, NULL, NULL, NULL, 'planned', NULL, '2026-01-21 00:18:45', '2026-01-21 00:18:45'),
(31, 6, 'machining', 1, '2026-03-11', '2026-03-17', 8, NULL, NULL, NULL, 'planned', NULL, '2026-01-21 00:18:45', '2026-01-21 00:18:45'),
(32, 6, 'quality_control', 1, '2026-03-18', '2026-03-24', 8, NULL, NULL, NULL, 'planned', NULL, '2026-01-21 00:18:45', '2026-01-21 00:18:45');

-- --------------------------------------------------------


--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('ppc','operator','supervisor produksi','admin') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'operator',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `division_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `is_active`, `deleted_at`, `remember_token`, `created_at`, `updated_at`, `division_id`) VALUES
(1, 'Manajer Produksi', 'manajerproduksi@example.com', '2026-01-03 15:56:50', '$2y$12$O91hW1pTlWA8e4cWVo2oOu3CQwKrrVVCz57pRcQYk.TzMOb.u2aRm', 'admin', 1, NULL, 'PN1S8ZSZ4wfh8ZwNJhBuhfX9yGpl1rCAhlmrPvUkPBDdfTKdasHYikcFZCJp', '2026-01-03 15:56:50', '2026-02-03 07:00:36', NULL),
(2, 'Operator Wax Room 1', 'operatorwax1@example.com', NULL, '$2y$12$a5o6gFDJg6UNmtkl41zldu3XKF65tBLl1zBsN8LGZvLvngfocNJmm', 'operator', 1, NULL, NULL, '2026-01-04 01:36:44', '2026-02-03 06:38:22', 1),
(3, 'Dani', 'dani@example.com', NULL, '$2y$12$Wq7NuWhz316n1fKtOJP0l.pt6bpSaY4kzpy0dCLB5A6TYqqWyAhBa', 'operator', 1, NULL, NULL, '2026-01-04 02:39:03', '2026-01-04 02:39:03', 3),
(4, 'Production Planning Control', 'ppc@example.com', NULL, '$2y$12$VXzbU5fGxDLAqdcbrT4T5.JCCwv/95sF2yLdqTIlMYDOOIwk7dcX2', 'ppc', 1, NULL, NULL, '2026-01-05 16:17:17', '2026-01-13 12:15:01', NULL),
(5, 'SPV Wax Room', 'spvwaxroom@example.com', NULL, '$2y$12$DQZvw..qT0e7YXQGUB9Uz.IdP843Xz80rcl8HvA4kbg5qPMIYm7XC', 'supervisor produksi', 1, NULL, NULL, '2026-01-05 21:21:32', '2026-02-03 06:37:08', 1),
(6, 'Deni', 'deni@example.com', NULL, '$2y$12$Em97TG6TXprUGU559MFwNeiMq7nYk4XY3.nbTtJ7ZMZSt8iloMjT.', 'operator', 1, NULL, NULL, '2026-01-05 21:23:01', '2026-01-05 21:23:01', 7),
(7, 'Operator Mould Room', 'operatormould@example.com', NULL, '$2y$12$jjOeUwT0mlUVmR2Hdnluw.6VXBFQGVG0jtT8Sa.ixW9nGtzXBqmu2', 'operator', 1, NULL, NULL, '2026-01-07 03:04:15', '2026-02-03 06:39:10', 2),
(8, 'Deri', 'deri@example.com', NULL, '$2y$12$Sw7xGSJ6m7xaW076qHocaO87gcAoCjeVh/aVXwho7n3ONuBVt0j6C', 'operator', 1, NULL, NULL, '2026-01-07 03:04:52', '2026-01-07 03:04:52', 5);

-- --------------------------------------------------------

--
-- Table structure for table `wip_trackings`
--

CREATE TABLE `wip_trackings` (
  `id` bigint UNSIGNED NOT NULL,
  `part_internal_id` bigint UNSIGNED NOT NULL,
  `part_operation_id` bigint UNSIGNED NOT NULL,
  `batch_id` bigint UNSIGNED DEFAULT NULL,
  `wip_qty` int NOT NULL DEFAULT '0',
  `step` enum('quality_check','process','done','rework') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'process',
  `status` enum('in_progress','completed','waiting') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'waiting',
  `started_at` date DEFAULT NULL,
  `finished_at` date DEFAULT NULL,
  `operation_notes` text COLLATE utf8mb4_unicode_ci,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wip_trackings`
--

INSERT INTO `wip_trackings` (`id`, `part_internal_id`, `part_operation_id`, `batch_id`, `wip_qty`, `step`, `status`, `started_at`, `finished_at`, `operation_notes`, `deleted_at`, `created_at`, `updated_at`) VALUES
(3, 1, 1, 2, 3, 'process', 'completed', '2026-01-04', '2026-01-04', NULL, NULL, '2026-01-04 02:13:11', '2026-01-04 02:17:43'),
(4, 1, 2, 2, 3, 'process', 'completed', '2026-01-04', '2026-01-04', NULL, NULL, '2026-01-04 02:17:43', '2026-01-04 02:19:36'),
(5, 1, 3, 2, 3, 'process', 'completed', '2026-01-04', '2026-01-04', NULL, NULL, '2026-01-04 02:19:36', '2026-01-04 02:20:27'),
(6, 1, 4, 2, 3, 'process', 'completed', '2026-01-04', '2026-01-04', NULL, NULL, '2026-01-04 02:20:27', '2026-01-04 02:37:52'),
(7, 1, 5, 2, 3, 'process', 'completed', '2026-01-04', '2026-01-04', NULL, NULL, '2026-01-04 02:37:52', '2026-01-04 02:39:57'),
(8, 1, 6, 2, 3, 'process', 'completed', '2026-01-04', '2026-01-04', NULL, NULL, '2026-01-04 02:39:57', '2026-01-04 07:15:17'),
(9, 1, 7, 2, 3, 'process', 'completed', '2026-01-04', '2026-01-04', NULL, NULL, '2026-01-04 07:15:17', '2026-01-04 07:15:54'),
(10, 1, 8, 2, 3, 'process', 'completed', '2026-01-04', '2026-01-04', NULL, NULL, '2026-01-04 07:15:54', '2026-01-04 13:08:18'),
(11, 1, 9, 2, 3, 'process', 'completed', '2026-01-04', '2026-01-04', NULL, NULL, '2026-01-04 13:08:18', '2026-01-04 13:09:27'),
(12, 1, 10, 2, 3, 'process', 'completed', '2026-01-04', '2026-01-04', NULL, NULL, '2026-01-04 13:09:27', '2026-01-04 13:11:32'),
(13, 1, 11, 2, 3, 'process', 'completed', '2026-01-05', '2026-01-05', NULL, NULL, '2026-01-04 13:11:32', '2026-01-05 00:17:58'),
(15, 1, 1, 3, 8, 'process', 'completed', '2026-01-05', '2026-01-07', NULL, NULL, '2026-01-04 23:59:57', '2026-01-07 03:42:23'),
(16, 1, 12, 2, 3, 'process', 'in_progress', '2026-01-21', NULL, NULL, NULL, '2026-01-05 00:17:58', '2026-01-21 03:28:57'),
(17, 1, 2, 3, 8, 'process', 'completed', '2026-01-07', '2026-01-08', NULL, NULL, '2026-01-07 03:42:23', '2026-01-08 09:44:14'),
(18, 1, 1, 4, 10, 'process', 'waiting', NULL, NULL, NULL, NULL, '2026-01-07 04:08:33', '2026-01-07 04:08:33'),
(19, 3, 21, 5, 20, 'process', 'in_progress', '2026-01-21', NULL, NULL, NULL, '2026-01-08 09:42:41', '2026-01-21 04:15:41'),
(20, 1, 3, 3, 8, 'quality_check', 'in_progress', NULL, NULL, NULL, NULL, '2026-01-08 09:44:14', '2026-01-08 09:44:14'),
(21, 1, 1, 6, 8, 'process', 'completed', '2026-01-21', '2026-01-21', NULL, NULL, '2026-01-21 00:18:45', '2026-01-21 03:58:43'),
(22, 1, 2, 6, 8, 'process', 'in_progress', NULL, NULL, NULL, NULL, '2026-01-21 03:58:43', '2026-02-03 06:58:44');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `batches`
--
ALTER TABLE `batches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `batches_batch_number_unique` (`batch_number`),
  ADD KEY `batches_po_production_id_foreign` (`po_production_id`),
  ADD KEY `batches_part_internal_id_foreign` (`part_internal_id`);

--
-- Indexes for table `batch_operations`
--
ALTER TABLE `batch_operations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `batch_operations_batch_id_foreign` (`batch_id`),
  ADD KEY `batch_operations_part_operation_id_foreign` (`part_operation_id`),
  ADD KEY `batch_operations_operator_id_foreign` (`operator_id`);


--
-- Indexes for table `divisions`
--
ALTER TABLE `divisions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `divisions_code_unique` (`code`);

--

--
-- Indexes for table `part_internals`
--
ALTER TABLE `part_internals`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `part_internals_part_number_unique` (`part_number`);

--
-- Indexes for table `part_operations`
--
ALTER TABLE `part_operations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `part_operations_division_id_foreign` (`division_id`),
  ADD KEY `part_operations_part_internal_id_index` (`part_internal_id`);


--
-- Indexes for table `po_productions`
--
ALTER TABLE `po_productions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `po_productions_po_number_unique` (`po_number`);

--
-- Indexes for table `production_schedules`
--
ALTER TABLE `production_schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `production_schedules_batch_id_foreign` (`batch_id`);


--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_division_id_foreign` (`division_id`);

--
-- Indexes for table `wip_trackings`
--
ALTER TABLE `wip_trackings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wip_trackings_part_internal_id_foreign` (`part_internal_id`),
  ADD KEY `wip_trackings_part_operation_id_foreign` (`part_operation_id`),
  ADD KEY `wip_trackings_batch_id_foreign` (`batch_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `batches`
--
ALTER TABLE `batches`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `batch_operations`
--
ALTER TABLE `batch_operations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `divisions`
--
ALTER TABLE `divisions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `part_internals`
--
ALTER TABLE `part_internals`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `part_operations`
--
ALTER TABLE `part_operations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `po_productions`
--
ALTER TABLE `po_productions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `production_schedules`
--
ALTER TABLE `production_schedules`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `wip_trackings`
--
ALTER TABLE `wip_trackings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `batches`
--
ALTER TABLE `batches`
  ADD CONSTRAINT `batches_part_internal_id_foreign` FOREIGN KEY (`part_internal_id`) REFERENCES `part_internals` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `batches_po_production_id_foreign` FOREIGN KEY (`po_production_id`) REFERENCES `po_productions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `batch_operations`
--
ALTER TABLE `batch_operations`
  ADD CONSTRAINT `batch_operations_batch_id_foreign` FOREIGN KEY (`batch_id`) REFERENCES `batches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `batch_operations_operator_id_foreign` FOREIGN KEY (`operator_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `batch_operations_part_operation_id_foreign` FOREIGN KEY (`part_operation_id`) REFERENCES `part_operations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `part_operations`
--
ALTER TABLE `part_operations`
  ADD CONSTRAINT `part_operations_division_id_foreign` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `part_operations_part_internal_id_foreign` FOREIGN KEY (`part_internal_id`) REFERENCES `part_internals` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `production_schedules`
--
ALTER TABLE `production_schedules`
  ADD CONSTRAINT `production_schedules_batch_id_foreign` FOREIGN KEY (`batch_id`) REFERENCES `batches` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_division_id_foreign` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `wip_trackings`
--
ALTER TABLE `wip_trackings`
  ADD CONSTRAINT `wip_trackings_batch_id_foreign` FOREIGN KEY (`batch_id`) REFERENCES `batches` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `wip_trackings_part_internal_id_foreign` FOREIGN KEY (`part_internal_id`) REFERENCES `part_internals` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wip_trackings_part_operation_id_foreign` FOREIGN KEY (`part_operation_id`) REFERENCES `part_operations` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
