-- phpMyAdmin SQL Dump
-- version 6.0.0-dev+20251215.aa153def95
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 11, 2026 at 08:45 AM
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
-- Database: `pp`
--

-- --------------------------------------------------------

--
-- Table structure for table `areas`
--

CREATE TABLE `areas` (
  `id` bigint UNSIGNED NOT NULL,
  `division_id` bigint UNSIGNED NOT NULL,
  `foreman_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `max_operator` int DEFAULT NULL COMMENT 'jumlah operator maksimal yang tersedia',
  `description` text COLLATE utf8mb4_unicode_ci COMMENT 'deskripsi detail area/proses',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `areas`
--

INSERT INTO `areas` (`id`, `division_id`, `foreman_id`, `name`, `max_operator`, `description`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 3, 'Injection', 8, NULL, NULL, '2026-02-09 15:07:34', '2026-02-09 15:10:47'),
(2, 1, NULL, 'Assembly', 6, NULL, NULL, '2026-02-09 15:07:34', '2026-02-09 15:07:34'),
(3, 2, NULL, 'Coating', 3, NULL, NULL, '2026-02-09 16:24:52', '2026-02-09 16:24:52'),
(4, 2, NULL, 'Stuccoing', 2, NULL, NULL, '2026-02-09 16:24:52', '2026-02-09 16:24:52'),
(5, 2, NULL, 'Drying', 3, NULL, NULL, '2026-02-09 16:24:52', '2026-02-09 16:24:52'),
(6, 3, NULL, 'Dewaxing', 2, NULL, NULL, '2026-02-09 16:26:12', '2026-02-09 16:26:12'),
(7, 3, NULL, 'Pre-Heat', 3, NULL, NULL, '2026-02-09 16:26:12', '2026-02-09 16:26:12'),
(8, 3, NULL, 'Pouring', 5, NULL, NULL, '2026-02-09 16:26:12', '2026-02-09 16:26:12'),
(9, 4, NULL, 'Knock-out', NULL, NULL, NULL, '2026-02-09 16:27:12', '2026-02-09 16:27:12'),
(10, 4, NULL, 'Cutting', 3, NULL, NULL, '2026-02-09 16:27:12', '2026-02-09 16:27:23'),
(11, 5, NULL, 'Normalizing', 2, NULL, NULL, '2026-02-09 16:33:04', '2026-02-09 16:33:04'),
(12, 5, NULL, 'Quenching', 1, NULL, NULL, '2026-02-09 16:33:04', '2026-02-09 16:33:04'),
(13, 5, NULL, 'Tempering', 2, NULL, NULL, '2026-02-09 16:33:04', '2026-02-09 16:33:04'),
(14, 6, NULL, 'Gate Grinding', 3, NULL, NULL, '2026-02-09 16:40:20', '2026-02-09 16:40:20'),
(15, 6, NULL, 'Sand Blasting', 3, NULL, NULL, '2026-02-09 16:40:20', '2026-02-09 16:40:20'),
(16, 7, NULL, 'Turning Op 1', 3, 'Bubut Manual 1', NULL, '2026-02-09 16:44:45', '2026-02-09 16:44:45'),
(17, 7, NULL, 'Turning Op 2', 3, 'Bubut Manual 2', NULL, '2026-02-09 16:44:45', '2026-02-09 16:44:45'),
(18, 7, NULL, 'Milling', 2, NULL, NULL, '2026-02-09 16:44:45', '2026-02-09 16:44:45'),
(19, 7, NULL, 'Milling Manual', 2, NULL, NULL, '2026-02-09 16:44:45', '2026-02-09 16:44:45'),
(20, 7, NULL, 'Milling CNC Op 1', 1, NULL, NULL, '2026-02-09 16:44:45', '2026-02-09 16:44:45'),
(21, 7, NULL, 'Milling CNC Op 2', 1, NULL, NULL, '2026-02-09 16:44:45', '2026-02-09 16:44:45'),
(22, 7, NULL, 'Milling CNC Op 3', 1, NULL, NULL, '2026-02-09 16:44:45', '2026-02-09 16:44:45'),
(23, 7, NULL, 'Milling CNC Op 4', 1, NULL, NULL, '2026-02-09 16:44:45', '2026-02-09 16:44:45'),
(24, 7, NULL, 'Milling CNC Op 5', 1, NULL, NULL, '2026-02-09 16:44:45', '2026-02-09 16:44:45'),
(25, 8, NULL, 'Visual Inspection', 2, NULL, NULL, '2026-02-09 17:10:30', '2026-02-09 17:10:30'),
(26, 8, NULL, 'Dimensional Check', 2, NULL, NULL, '2026-02-09 17:10:30', '2026-02-09 17:10:30'),
(27, 8, NULL, 'NDT', 3, 'Non-Destructive Test', NULL, '2026-02-09 17:10:30', '2026-02-09 17:10:30');

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
  `approval_manager` bigint UNSIGNED DEFAULT NULL,
  `approval_manager_at` timestamp NULL DEFAULT NULL,
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

INSERT INTO `batches` (`id`, `batch_number`, `po_production_id`, `part_internal_id`, `quantity`, `target_completed`, `status`, `approval_manager`, `approval_manager_at`, `part_no_customer`, `part_no_customer_source`, `drawing_number`, `drawing_number_source`, `deleted_at`, `created_at`, `updated_at`) VALUES
(5, '12022026-37602', 2, 2, 6, '2026-04-11', 'completed', 1, '2026-02-12 07:13:55', NULL, NULL, NULL, NULL, NULL, '2026-02-12 07:12:41', '2026-03-03 03:53:38'),
(6, '12022026-41739', 2, 3, 6, '2026-04-11', 'in_progress', 1, '2026-02-12 07:50:42', NULL, NULL, NULL, NULL, NULL, '2026-02-12 07:37:03', '2026-02-12 07:50:42'),
(7, '12022026-04645', 3, 2, 20, '2026-08-04', 'in_progress', 1, '2026-02-12 11:17:54', NULL, NULL, NULL, NULL, NULL, '2026-02-12 11:17:34', '2026-02-12 11:17:54'),
(8, '13022026-34967', 5, 2, 2, '2026-04-04', 'completed', 1, '2026-02-13 03:32:58', NULL, NULL, NULL, NULL, NULL, '2026-02-13 03:30:17', '2026-02-13 03:32:58'),
(9, '13022026-67637', 5, 3, 2, '2026-04-04', 'in_progress', 1, '2026-02-13 03:33:08', NULL, NULL, NULL, NULL, NULL, '2026-02-13 03:31:18', '2026-02-13 03:33:08');

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
(2, 5, 7, 6, 6, '2026-02-12', 1, NULL, '2026-02-12 07:46:29', '2026-02-12 07:46:29'),
(3, 5, 8, 6, 6, '2026-02-12', 1, NULL, '2026-02-12 07:47:37', '2026-02-12 07:47:37'),
(4, 5, 9, 6, 6, '2026-02-12', 1, NULL, '2026-02-12 07:49:57', '2026-02-12 07:49:57'),
(5, 6, 13, 6, 6, '2026-02-12', 1, NULL, '2026-02-12 07:53:33', '2026-02-12 07:53:33'),
(6, 8, 7, 2, 2, '2026-02-13', 4, NULL, '2026-02-13 03:41:29', '2026-02-13 03:41:29'),
(7, 8, 8, 2, 2, '2026-02-13', 4, NULL, '2026-02-13 03:42:59', '2026-02-13 03:42:59'),
(8, 9, 13, 2, 2, '2026-02-13', 4, NULL, '2026-02-13 03:43:44', '2026-02-13 03:43:44'),
(9, 8, 9, 2, 2, '2026-02-13', 6, NULL, '2026-02-13 03:45:43', '2026-02-13 03:45:43'),
(10, 8, 10, 2, 2, '2026-02-13', 7, NULL, '2026-02-13 03:49:46', '2026-02-13 03:49:46'),
(11, 8, 11, 2, 2, '2026-02-13', 7, NULL, '2026-02-13 03:49:56', '2026-02-13 03:49:56'),
(12, 8, 12, 2, 2, '2026-02-13', 8, NULL, '2026-02-13 03:51:25', '2026-02-13 03:51:25'),
(13, 7, 7, 20, 20, '2026-03-03', 1, NULL, '2026-03-03 03:45:21', '2026-03-03 03:45:21'),
(14, 5, 10, 6, 6, '2026-03-03', 1, NULL, '2026-03-03 03:52:48', '2026-03-03 03:52:48'),
(15, 5, 11, 6, 6, '2026-03-03', 1, NULL, '2026-03-03 03:52:59', '2026-03-03 03:52:59'),
(16, 5, 12, 6, 6, '2026-03-03', 1, NULL, '2026-03-03 03:53:38', '2026-03-03 03:53:38'),
(17, 6, 14, 6, 6, '2026-03-03', 1, NULL, '2026-03-03 04:04:29', '2026-03-03 04:04:29');

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
(1, 'Wax Room', 'DIV-6490E6', 'waxing', 1, '2026-02-09 22:07:34', '2026-02-12 07:43:30'),
(2, 'Mould Room', 'DIV-486138', 'mould_room', 1, '2026-02-09 23:24:52', '2026-02-12 07:43:44'),
(3, 'Melting Room', 'DIV-4767DC', NULL, 1, '2026-02-09 23:26:12', '2026-02-09 23:26:12'),
(4, 'Cut Off', 'DIV-0DA497', NULL, 1, '2026-02-09 23:27:12', '2026-02-09 23:27:12'),
(5, 'Heat Treatment', 'DIV-05EE0F', NULL, 1, '2026-02-09 23:33:04', '2026-02-09 23:33:04'),
(6, 'Finishing', 'DIV-420307', NULL, 1, '2026-02-09 23:40:20', '2026-02-09 23:40:20'),
(7, 'Machining', 'DIV-D9F0B0', NULL, 1, '2026-02-09 23:44:45', '2026-02-09 23:44:45'),
(8, 'Quality Control', 'DIV-67461E', NULL, 1, '2026-02-10 00:10:30', '2026-02-10 00:10:30');

-- --------------------------------------------------------

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
(2, 'P18-03d.26MC', NULL, 'Pump Bowl Leitschaufelgehause PL08-0095/1', '1.4469', 35.88, NULL, 1, 4, 10, 10, 20, 20, 20, 30, 40, NULL, '2026-02-12 06:12:10', '2026-02-12 06:12:10'),
(3, 'D2-74MC', NULL, 'Deighel', '1.4469', 35.88, NULL, 1, 3, 8, 4, 10, 16, 20, 20, 40, NULL, '2026-02-12 07:36:36', '2026-02-12 07:36:36');

-- --------------------------------------------------------

--
-- Table structure for table `part_operations`
--

CREATE TABLE `part_operations` (
  `id` bigint UNSIGNED NOT NULL,
  `part_internal_id` bigint UNSIGNED NOT NULL,
  `division_id` bigint UNSIGNED NOT NULL,
  `area_id` bigint UNSIGNED DEFAULT NULL,
  `operation_data` text COLLATE utf8mb4_unicode_ci,
  `route_order` int NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `part_operations`
--

INSERT INTO `part_operations` (`id`, `part_internal_id`, `division_id`, `area_id`, `operation_data`, `route_order`, `deleted_at`, `created_at`, `updated_at`) VALUES
(7, 2, 1, 1, 'CLASS industrial Grade C\r\nINJECT/POUR RUNNER NO : Development\r\nPATTERN DIE NO :\r\n2282B PSI 125/P9 SEC 120\r\n2282C PSI 400/P6 SEC 60\r\nInject Sol Die :\r\n2282A PSI 600/P4 SEC 60\r\nWax Temp : 70±5℃', 1, NULL, '2026-02-12 06:12:10', '2026-02-12 06:12:10'),
(8, 2, 1, 2, 'PATTERN ASSY PER WRA CARD Development\r\nVISUAL CHECK FOR DEFECTS\r\nBerat assembling 4,75 kg', 2, NULL, '2026-02-12 06:12:10', '2026-02-12 06:12:10'),
(9, 2, 2, 3, 'MOULD PREPARE PER QC.INSTRUCTION\r\nPRIMARY NORMAL, AIR-DRY 4 HRS\r\nSECY 2 F+1 C+ ceramic core + 3 C (20/06/2024_Pak Parlan)\r\nEXTRA AIR DRY 12 HRS PER COAT\r\n12 Jam dry sebelum dewax', 3, NULL, '2026-02-12 06:12:10', '2026-02-12 06:12:10'),
(10, 2, 3, 6, 'DEWAX 10 MINS\r\nVISUAL INSPECTION & CLEAN OUT', 4, NULL, '2026-02-12 06:12:10', '2026-02-12 06:12:10'),
(11, 2, 3, 6, 'FIRE 2/3 HRS 700℃ Cast 1580±5℃\r\nM BAR\r\nIN VACUUM ASSIST^AT\r\nPenuangan normal, ditaruh di atas fixture alumunium dan blower pada bagian bawah (pastikan angin pas dilubang)\r\nUpdate perubahan gating 2', 5, NULL, '2026-02-12 06:17:00', '2026-02-12 06:17:00'),
(12, 2, 4, 9, 'MECH KNOCKUT :yes', 6, NULL, '2026-02-12 06:17:00', '2026-02-12 06:17:00'),
(13, 3, 1, 1, 'CLASS industrial Grade C\r\nINJECT/POUR RUNNER NO : Development\r\nPATTERN DIE NO :\r\n2237A/B/C PSI 500/P7 SEC 120\r\n2237D PSI 500/P6 SEC 60\r\nInject Sol Die : -\r\nWax Temp : 70Â±5Â°C', 1, NULL, '2026-02-12 07:36:36', '2026-02-12 07:36:36'),
(14, 3, 1, 2, 'PATTERN ASSY PER WRA CARD Development\r\nVISUAL CHECK FOR DEFECTS', 2, NULL, '2026-02-12 07:36:36', '2026-02-12 07:36:36'),
(15, 3, 2, 3, 'MOULD PREPARE PER QC.INSTRUCTION\r\nPRIMARY NORMAL, AIR-DRY 4 HRS \r\nSECY 1 F + 3 C\r\nEXTRA AIR DRY 12 HRS PER COAT\r\n12 Jam dry sebelum dewax', 3, NULL, '2026-02-12 07:36:36', '2026-02-12 07:36:36'),
(16, 3, 3, 6, 'DEWAX 10 MINS \r\nVISUAL INSPECTION & CLEAN OUT', 4, NULL, '2026-02-12 07:36:36', '2026-02-12 07:36:36'),
(17, 3, 3, 7, 'Firing Temp.1100Â°C di tahan selama 2 jam', 5, NULL, '2026-02-12 07:36:36', '2026-02-12 07:36:36'),
(18, 3, 3, 7, 'Reshell 2 C', 6, NULL, '2026-02-12 07:36:36', '2026-02-12 07:36:36'),
(19, 3, 3, 8, 'FIRE 2/3 HRS 700Â°C Cast 1580+5Â°C\r\nIN VACUUM ASSIST^AT ....................... M BAR \r\nSemprot angin bagian dalam dan lekukan', 7, NULL, '2026-02-12 07:36:36', '2026-02-12 07:36:36'),
(20, 3, 4, 9, 'MECH KNOCK OUT : YES', 8, NULL, '2026-02-12 07:36:36', '2026-02-12 07:36:36');

-- --------------------------------------------------------

--
-- Table structure for table `part_processes`
--

CREATE TABLE `part_processes` (
  `id` bigint UNSIGNED NOT NULL,
  `part_internal_id` bigint UNSIGNED NOT NULL,
  `area_id` bigint UNSIGNED NOT NULL,
  `part_operation_id` bigint UNSIGNED DEFAULT NULL,
  `process_order` int DEFAULT NULL COMMENT 'urutan dalam workflow produksi',
  `capacity` int DEFAULT NULL COMMENT 'kapasitas produksi per durasi kerja dalam menit',
  `operator_count` int DEFAULT NULL,
  `duration` int DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `equipment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'peralatan/mesin yang digunakan',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `part_processes`
--

INSERT INTO `part_processes` (`id`, `part_internal_id`, `area_id`, `part_operation_id`, `process_order`, `capacity`, `operator_count`, `duration`, `is_active`, `equipment`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 7, 1, 1, 5, 180, 1, NULL, NULL, '2026-02-12 06:12:10', '2026-02-12 06:12:10'),
(2, 2, 2, 8, 2, 1, 3, 120, 1, NULL, NULL, '2026-02-12 06:12:10', '2026-02-12 06:12:10'),
(3, 2, 3, 9, 3, 1, 3, 60, 1, NULL, NULL, '2026-02-12 06:12:10', '2026-02-12 06:12:10'),
(4, 2, 6, 10, 4, 1, 3, 60, 1, NULL, NULL, '2026-02-12 06:12:10', '2026-02-12 06:12:10'),
(5, 2, 6, 11, 5, 1, 5, 60, 1, NULL, NULL, '2026-02-12 06:17:00', '2026-02-12 06:17:00'),
(6, 2, 9, 12, 6, 1, 3, NULL, 1, NULL, NULL, '2026-02-12 06:17:00', '2026-02-12 06:17:00'),
(7, 3, 1, 13, 1, 1, 3, 240, 1, NULL, NULL, '2026-02-12 07:36:36', '2026-02-12 07:36:36'),
(8, 3, 2, 14, 2, 1, 3, 120, 1, NULL, NULL, '2026-02-12 07:36:36', '2026-02-12 07:36:36'),
(9, 3, 3, 15, 3, 1, 2, 45, 1, NULL, NULL, '2026-02-12 07:36:36', '2026-02-12 07:36:36'),
(10, 3, 6, 16, 4, 1, 2, 120, 1, NULL, NULL, '2026-02-12 07:36:36', '2026-02-12 07:36:36'),
(11, 3, 7, 17, 5, 1, 2, 120, 1, NULL, NULL, '2026-02-12 07:36:36', '2026-02-12 07:36:36'),
(12, 3, 7, 18, 6, 1, 2, 60, 1, NULL, NULL, '2026-02-12 07:36:36', '2026-02-12 07:36:36'),
(13, 3, 8, 19, 7, 1, 5, 180, 1, NULL, NULL, '2026-02-12 07:36:36', '2026-02-12 07:36:36'),
(14, 3, 9, 20, 8, 1, 3, 60, 1, NULL, NULL, '2026-02-12 07:36:36', '2026-02-12 07:36:36');

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
  `status` enum('pending','scheduled','on_production','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `po_productions`
--

INSERT INTO `po_productions` (`id`, `po_number`, `po_snapshot`, `quantity`, `due_date`, `po_source`, `status`, `deleted_at`, `created_at`, `updated_at`) VALUES
(2, '21004463', NULL, 6, '2026-04-11', NULL, 'on_production', NULL, '2026-02-12 06:18:29', '2026-02-12 07:50:42'),
(3, '12345', NULL, 20, '2026-08-04', NULL, 'on_production', NULL, '2026-02-12 11:17:15', '2026-02-12 11:17:54'),
(4, '3456', NULL, 10, '2026-05-07', NULL, 'pending', NULL, '2026-02-13 03:16:44', '2026-02-13 03:16:44'),
(5, '54321', NULL, 2, '2026-04-04', NULL, 'on_production', NULL, '2026-02-13 03:25:40', '2026-02-13 03:32:58');

-- --------------------------------------------------------

--
-- Table structure for table `production_calendars`
--

CREATE TABLE `production_calendars` (
  `id` bigint UNSIGNED NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `activity` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'nama libur/event',
  `day_type` enum('mass leave','holiday') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'holiday',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `production_calendars`
--

INSERT INTO `production_calendars` (`id`, `start_date`, `end_date`, `activity`, `day_type`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, '2026-02-13', '2026-02-13', 'Libur aja', 'holiday', NULL, '2026-02-12 11:16:23', '2026-02-12 11:16:23');

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
  `is_urgent` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `production_schedules`
--

INSERT INTO `production_schedules` (`id`, `batch_id`, `process_name`, `duration_weeks`, `plan_start_date`, `plan_end_date`, `plan_qty`, `actual_start_date`, `actual_end_date`, `actual_qty`, `status`, `notes`, `is_urgent`, `created_at`, `updated_at`) VALUES
(10, 5, 'Injection', 1, '2026-02-12', '2026-02-16', 6, '2026-02-12', '2026-02-13', 6, 'completed', 'Process Order: 1 | Operators: 5 | Capacity: 1 units/180 min | Duration: 1080 min (3 days)', 0, '2026-02-12 07:12:41', '2026-02-12 16:24:21'),
(11, 5, 'Assembly', 1, '2026-02-17', '2026-02-18', 6, NULL, '2026-02-12', 6, 'completed', 'Process Order: 2 | Operators: 3 | Capacity: 1 units/120 min | Duration: 720 min (2 days)', 0, '2026-02-12 07:12:41', '2026-02-12 07:47:37'),
(12, 5, 'Coating', 1, '2026-02-19', '2026-02-19', 6, NULL, '2026-02-12', 6, 'completed', 'Process Order: 3 | Operators: 3 | Capacity: 1 units/60 min | Duration: 360 min (1 days)', 0, '2026-02-12 07:12:41', '2026-02-12 07:49:57'),
(13, 5, 'Dewaxing', 1, '2026-02-20', '2026-02-20', 6, NULL, '2026-03-03', 6, 'delayed', 'Process Order: 4 | Operators: 3 | Capacity: 1 units/60 min | Duration: 360 min (1 days)', 0, '2026-02-12 07:12:41', '2026-03-03 03:52:59'),
(14, 5, 'Dewaxing', 1, '2026-02-23', '2026-02-23', 6, NULL, NULL, NULL, 'planned', 'Process Order: 5 | Operators: 5 | Capacity: 1 units/60 min | Duration: 360 min (1 days)', 0, '2026-02-12 07:12:41', '2026-02-12 07:12:41'),
(15, 5, 'Knock-out', 0, '2026-02-24', '2026-02-24', 6, NULL, '2026-03-03', 6, 'delayed', 'Process Order: 6 | Operators: 3 | Capacity: 1 units/ min | Duration: 0 min (0 days)', 0, '2026-02-12 07:12:41', '2026-03-03 03:53:38'),
(16, 6, 'Injection', 1, '2026-02-12', '2026-02-16', 6, '2026-02-12', '2026-02-12', 6, 'completed', 'Process Order: 1 | Operators: 3 | Capacity: 1 units/240 min | Duration: 1440 min (3 days)', 0, '2026-02-12 07:37:03', '2026-02-12 07:53:33'),
(17, 6, 'Assembly', 1, '2026-02-17', '2026-02-18', 6, NULL, '2026-03-03', 6, 'delayed', 'Process Order: 2 | Operators: 3 | Capacity: 1 units/120 min | Duration: 720 min (2 days)', 0, '2026-02-12 07:37:03', '2026-03-03 04:04:29'),
(18, 6, 'Coating', 1, '2026-02-19', '2026-02-19', 6, NULL, NULL, NULL, 'planned', 'Process Order: 3 | Operators: 2 | Capacity: 1 units/45 min | Duration: 270 min (1 days)', 0, '2026-02-12 07:37:03', '2026-02-12 07:37:03'),
(19, 6, 'Dewaxing', 1, '2026-02-20', '2026-02-23', 6, NULL, NULL, NULL, 'planned', 'Process Order: 4 | Operators: 2 | Capacity: 1 units/120 min | Duration: 720 min (2 days)', 0, '2026-02-12 07:37:03', '2026-02-12 07:37:03'),
(20, 6, 'Pre-Heat', 1, '2026-02-24', '2026-02-25', 6, NULL, NULL, NULL, 'planned', 'Process Order: 5 | Operators: 2 | Capacity: 1 units/120 min | Duration: 720 min (2 days)', 0, '2026-02-12 07:37:03', '2026-02-12 07:37:03'),
(21, 6, 'Pre-Heat', 1, '2026-02-26', '2026-02-26', 6, NULL, NULL, NULL, 'planned', 'Process Order: 6 | Operators: 2 | Capacity: 1 units/60 min | Duration: 360 min (1 days)', 0, '2026-02-12 07:37:03', '2026-02-12 07:37:03'),
(22, 6, 'Pouring', 1, '2026-02-27', '2026-03-03', 6, NULL, NULL, NULL, 'planned', 'Process Order: 7 | Operators: 5 | Capacity: 1 units/180 min | Duration: 1080 min (3 days)', 0, '2026-02-12 07:37:03', '2026-02-12 07:37:03'),
(23, 6, 'Knock-out', 1, '2026-03-04', '2026-03-04', 6, NULL, NULL, NULL, 'planned', 'Process Order: 8 | Operators: 3 | Capacity: 1 units/60 min | Duration: 360 min (1 days)', 0, '2026-02-12 07:37:03', '2026-02-12 07:37:03'),
(24, 7, 'Injection', 2, '2026-02-12', '2026-02-23', 20, '2026-03-03', '2026-03-03', 20, 'delayed', 'Process Order: 1 | Operators: 5 | Capacity: 1 units/180 min | Duration: 3600 min (8 days)', 0, '2026-02-12 11:17:54', '2026-03-03 03:45:21'),
(25, 7, 'Assembly', 1, '2026-02-24', '2026-03-02', 20, NULL, NULL, NULL, 'planned', 'Process Order: 2 | Operators: 3 | Capacity: 1 units/120 min | Duration: 2400 min (5 days)', 0, '2026-02-12 11:17:54', '2026-02-12 11:17:54'),
(26, 7, 'Coating', 1, '2026-03-03', '2026-03-05', 20, NULL, NULL, NULL, 'planned', 'Process Order: 3 | Operators: 3 | Capacity: 1 units/60 min | Duration: 1200 min (3 days)', 0, '2026-02-12 11:17:54', '2026-02-12 11:17:54'),
(27, 7, 'Dewaxing', 1, '2026-03-06', '2026-03-10', 20, NULL, NULL, NULL, 'planned', 'Process Order: 4 | Operators: 3 | Capacity: 1 units/60 min | Duration: 1200 min (3 days)', 0, '2026-02-12 11:17:54', '2026-02-12 11:17:54'),
(28, 7, 'Dewaxing', 1, '2026-03-11', '2026-03-13', 20, NULL, NULL, NULL, 'planned', 'Process Order: 5 | Operators: 5 | Capacity: 1 units/60 min | Duration: 1200 min (3 days)', 0, '2026-02-12 11:17:54', '2026-02-12 11:17:54'),
(29, 7, 'Knock-out', 0, '2026-03-16', '2026-03-16', 20, NULL, NULL, NULL, 'planned', 'Process Order: 6 | Operators: 3 | Capacity: 1 units/ min | Duration: 0 min (0 days)', 0, '2026-02-12 11:17:54', '2026-02-12 11:17:54'),
(30, 8, 'Injection', 1, '2026-02-13', '2026-02-13', 2, '2026-02-13', '2026-02-13', 2, 'delayed', 'Process Order: 1 | Operators: 5 | Capacity: 1 units/180 min | Duration: 360 min (1 days)', 0, '2026-02-13 03:32:58', '2026-02-13 03:41:29'),
(31, 8, 'Assembly', 1, '2026-02-16', '2026-02-16', 2, NULL, '2026-02-13', 2, 'completed', 'Process Order: 2 | Operators: 3 | Capacity: 1 units/120 min | Duration: 240 min (1 days)', 0, '2026-02-13 03:32:58', '2026-02-13 03:42:59'),
(32, 8, 'Coating', 1, '2026-02-17', '2026-02-17', 2, NULL, '2026-02-13', 2, 'completed', 'Process Order: 3 | Operators: 3 | Capacity: 1 units/60 min | Duration: 120 min (1 days)', 0, '2026-02-13 03:32:58', '2026-02-13 03:45:43'),
(33, 8, 'Dewaxing', 1, '2026-02-18', '2026-02-18', 2, NULL, '2026-02-13', 2, 'completed', 'Process Order: 4 | Operators: 3 | Capacity: 1 units/60 min | Duration: 120 min (1 days)', 0, '2026-02-13 03:32:58', '2026-02-13 03:49:56'),
(34, 8, 'Dewaxing', 1, '2026-02-19', '2026-02-19', 2, NULL, NULL, NULL, 'planned', 'Process Order: 5 | Operators: 5 | Capacity: 1 units/60 min | Duration: 120 min (1 days)', 0, '2026-02-13 03:32:58', '2026-02-13 03:32:58'),
(35, 8, 'Knock-out', 0, '2026-02-20', '2026-02-20', 2, NULL, '2026-02-13', 2, 'completed', 'Process Order: 6 | Operators: 3 | Capacity: 1 units/ min | Duration: 0 min (0 days)', 0, '2026-02-13 03:32:58', '2026-02-13 03:51:25'),
(36, 9, 'Injection', 1, '2026-02-13', '2026-02-13', 2, '2026-02-13', '2026-02-13', 2, 'delayed', 'Process Order: 1 | Operators: 3 | Capacity: 1 units/240 min | Duration: 480 min (1 days)', 0, '2026-02-13 03:33:08', '2026-02-13 03:43:44'),
(37, 9, 'Assembly', 1, '2026-02-16', '2026-02-16', 2, NULL, NULL, NULL, 'planned', 'Process Order: 2 | Operators: 3 | Capacity: 1 units/120 min | Duration: 240 min (1 days)', 0, '2026-02-13 03:33:08', '2026-02-13 03:33:08'),
(38, 9, 'Coating', 1, '2026-02-17', '2026-02-17', 2, NULL, NULL, NULL, 'planned', 'Process Order: 3 | Operators: 2 | Capacity: 1 units/45 min | Duration: 90 min (1 days)', 0, '2026-02-13 03:33:08', '2026-02-13 03:33:08'),
(39, 9, 'Dewaxing', 1, '2026-02-18', '2026-02-18', 2, NULL, NULL, NULL, 'planned', 'Process Order: 4 | Operators: 2 | Capacity: 1 units/120 min | Duration: 240 min (1 days)', 0, '2026-02-13 03:33:08', '2026-02-13 03:33:08'),
(40, 9, 'Pre-Heat', 1, '2026-02-19', '2026-02-19', 2, NULL, NULL, NULL, 'planned', 'Process Order: 5 | Operators: 2 | Capacity: 1 units/120 min | Duration: 240 min (1 days)', 0, '2026-02-13 03:33:08', '2026-02-13 03:33:08'),
(41, 9, 'Pre-Heat', 1, '2026-02-20', '2026-02-20', 2, NULL, NULL, NULL, 'planned', 'Process Order: 6 | Operators: 2 | Capacity: 1 units/60 min | Duration: 120 min (1 days)', 0, '2026-02-13 03:33:08', '2026-02-13 03:33:08'),
(42, 9, 'Pouring', 1, '2026-02-23', '2026-02-23', 2, NULL, NULL, NULL, 'planned', 'Process Order: 7 | Operators: 5 | Capacity: 1 units/180 min | Duration: 360 min (1 days)', 0, '2026-02-13 03:33:08', '2026-02-13 03:33:08'),
(43, 9, 'Knock-out', 1, '2026-02-24', '2026-02-24', 2, NULL, NULL, NULL, 'planned', 'Process Order: 8 | Operators: 3 | Capacity: 1 units/60 min | Duration: 120 min (1 days)', 0, '2026-02-13 03:33:08', '2026-02-13 03:33:08');

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
  `role` enum('ppc','operator','supervisor produksi','foreman','admin') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'operator',
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
(1, 'Manajer Produksi', 'manajerproduksi@example.com', '2026-02-12 05:35:43', '$2y$12$uyQJVqxPr101UVdWNzkohO44YaRLYnfiepOmIiBr3lliQvkfW0SJm', 'admin', 1, NULL, 'o4MGhEYUEnpBQ4YldqYO8opKLcShs7VM7xa9Eewg6nykJgH7zc8UTdozYzxn', '2026-02-12 05:35:44', '2026-02-12 05:35:44', NULL),
(2, 'Supervisor Wax Room', 'spvwax@example.com', NULL, '$2y$12$LD6/lQVxIR3KrR6tIXKDOOMcIr.0chDmfc8sXePqeq.jrSTD6M4sC', 'supervisor produksi', 1, NULL, NULL, '2026-02-12 05:42:36', '2026-02-12 05:42:36', 1),
(3, 'Foreman Wax 1', 'foremanwax1@example.com', NULL, '$2y$12$nlbyA4URPo9x1NXYhE2K3.Zu9X52X7vl26fjepiS5VIi3/4zXbX9e', 'foreman', 1, NULL, NULL, '2026-02-12 05:43:26', '2026-02-12 05:43:26', 1),
(4, 'Operator Wax Room 1', 'operatorwax1@example.com', NULL, '$2y$12$H5y.vwissAYi8BGQF3TnVemNzGKNUgE7dV7fddIToQluORpTpN75m', 'operator', 1, NULL, NULL, '2026-02-12 05:43:56', '2026-02-12 05:43:56', 1),
(5, 'Staff PPC', 'staffppc@example.com', NULL, '$2y$12$4EQ96MXGfTMx5sApjaiA0OvRsMh8FzHHY3c/w.RZar5Sa.HZn.0DK', 'ppc', 1, NULL, NULL, '2026-02-13 02:21:37', '2026-02-13 02:21:37', NULL),
(6, 'Operator Mould', 'operatormould@example.com', NULL, '$2y$12$h4NhWHK3jGiJpYQv47o6b.onAGeaB9vBR.aqJvdEKzi4ntR86NW62', 'operator', 1, NULL, NULL, '2026-02-13 03:44:42', '2026-02-13 03:44:42', 2),
(7, 'Operator Melting', 'operatormelting@example.com', NULL, '$2y$12$Qp7afoZtu3F.J1MNOWhXTu3uGQUNsmZxfj0kKPnQLvqkq.XP6Xb/y', 'operator', 1, NULL, NULL, '2026-02-13 03:46:46', '2026-02-13 03:46:46', 3),
(8, 'Operator Cut oFf', 'operatorcutoff@example.com', NULL, '$2y$12$v44CnXoRlkImsh4C1HtCaeinoGA1z.7D.NTXs9i4rUwQWAS1t.EEG', 'operator', 1, NULL, NULL, '2026-02-13 03:50:48', '2026-02-13 03:50:48', 4);

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
(1, 2, 7, 5, 6, 'process', 'completed', '2026-02-12', '2026-02-12', NULL, NULL, '2026-02-12 07:13:55', '2026-02-12 07:46:29'),
(2, 2, 8, 5, 6, 'process', 'completed', NULL, '2026-02-12', NULL, NULL, '2026-02-12 07:46:29', '2026-02-12 07:47:37'),
(3, 2, 9, 5, 6, 'process', 'completed', NULL, '2026-02-12', NULL, NULL, '2026-02-12 07:47:37', '2026-02-12 07:49:57'),
(4, 2, 10, 5, 6, 'process', 'completed', NULL, '2026-03-03', NULL, NULL, '2026-02-12 07:49:57', '2026-03-03 03:52:48'),
(5, 3, 13, 6, 6, 'process', 'completed', '2026-02-12', '2026-02-12', NULL, NULL, '2026-02-12 07:50:42', '2026-02-12 07:53:33'),
(6, 3, 14, 6, 6, 'process', 'completed', NULL, '2026-03-03', NULL, NULL, '2026-02-12 07:53:33', '2026-03-03 04:04:29'),
(7, 2, 7, 7, 20, 'process', 'completed', '2026-03-03', '2026-03-03', NULL, NULL, '2026-02-12 11:17:54', '2026-03-03 03:45:21'),
(8, 2, 7, 8, 2, 'process', 'completed', '2026-02-13', '2026-02-13', NULL, NULL, '2026-02-13 03:32:58', '2026-02-13 03:41:29'),
(9, 3, 13, 9, 2, 'process', 'completed', '2026-02-13', '2026-02-13', NULL, NULL, '2026-02-13 03:33:08', '2026-02-13 03:43:44'),
(10, 2, 8, 8, 2, 'process', 'completed', NULL, '2026-02-13', NULL, NULL, '2026-02-13 03:41:29', '2026-02-13 03:42:59'),
(11, 2, 9, 8, 2, 'process', 'completed', NULL, '2026-02-13', NULL, NULL, '2026-02-13 03:42:59', '2026-02-13 03:45:43'),
(12, 3, 14, 9, 2, 'quality_check', 'in_progress', NULL, NULL, NULL, NULL, '2026-02-13 03:43:44', '2026-02-13 03:43:44'),
(13, 2, 10, 8, 2, 'process', 'completed', NULL, '2026-02-13', NULL, NULL, '2026-02-13 03:45:43', '2026-02-13 03:49:46'),
(14, 2, 11, 8, 2, 'process', 'completed', NULL, '2026-02-13', NULL, NULL, '2026-02-13 03:49:46', '2026-02-13 03:49:56'),
(15, 2, 12, 8, 2, 'process', 'completed', NULL, '2026-02-13', NULL, NULL, '2026-02-13 03:49:56', '2026-02-13 03:51:25'),
(16, 2, 8, 7, 20, 'quality_check', 'in_progress', NULL, NULL, NULL, NULL, '2026-03-03 03:45:21', '2026-03-03 03:45:21'),
(17, 2, 11, 5, 6, 'process', 'completed', NULL, '2026-03-03', NULL, NULL, '2026-03-03 03:52:48', '2026-03-03 03:52:59'),
(18, 2, 12, 5, 6, 'process', 'completed', NULL, '2026-03-03', NULL, NULL, '2026-03-03 03:52:59', '2026-03-03 03:53:38'),
(19, 3, 15, 6, 6, 'quality_check', 'in_progress', NULL, NULL, NULL, NULL, '2026-03-03 04:04:29', '2026-03-03 04:04:29');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `areas`
--
ALTER TABLE `areas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `areas_division_id_foreign` (`division_id`),
  ADD KEY `areas_foreman_id_foreign` (`foreman_id`);

--
-- Indexes for table `batches`
--
ALTER TABLE `batches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `batches_batch_number_unique` (`batch_number`),
  ADD KEY `batches_po_production_id_foreign` (`po_production_id`),
  ADD KEY `batches_part_internal_id_foreign` (`part_internal_id`),
  ADD KEY `batches_approval_manager_foreign` (`approval_manager`);

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
  ADD KEY `part_operations_area_id_foreign` (`area_id`),
  ADD KEY `part_operations_part_internal_id_index` (`part_internal_id`);

--
-- Indexes for table `part_processes`
--
ALTER TABLE `part_processes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `part_processes_part_internal_id_foreign` (`part_internal_id`),
  ADD KEY `part_processes_area_id_foreign` (`area_id`),
  ADD KEY `part_processes_part_operation_id_foreign` (`part_operation_id`);

--
-- Indexes for table `po_productions`
--
ALTER TABLE `po_productions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `po_productions_po_number_unique` (`po_number`);

--
-- Indexes for table `production_calendars`
--
ALTER TABLE `production_calendars`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `areas`
--
ALTER TABLE `areas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `batches`
--
ALTER TABLE `batches`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `batch_operations`
--
ALTER TABLE `batch_operations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `part_processes`
--
ALTER TABLE `part_processes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `po_productions`
--
ALTER TABLE `po_productions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `production_calendars`
--
ALTER TABLE `production_calendars`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `production_schedules`
--
ALTER TABLE `production_schedules`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `wip_trackings`
--
ALTER TABLE `wip_trackings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `areas`
--
ALTER TABLE `areas`
  ADD CONSTRAINT `areas_division_id_foreign` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `areas_foreman_id_foreign` FOREIGN KEY (`foreman_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `batches`
--
ALTER TABLE `batches`
  ADD CONSTRAINT `batches_approval_manager_foreign` FOREIGN KEY (`approval_manager`) REFERENCES `users` (`id`) ON DELETE SET NULL,
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
  ADD CONSTRAINT `part_operations_area_id_foreign` FOREIGN KEY (`area_id`) REFERENCES `areas` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `part_operations_division_id_foreign` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `part_operations_part_internal_id_foreign` FOREIGN KEY (`part_internal_id`) REFERENCES `part_internals` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `part_processes`
--
ALTER TABLE `part_processes`
  ADD CONSTRAINT `part_processes_area_id_foreign` FOREIGN KEY (`area_id`) REFERENCES `areas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `part_processes_part_internal_id_foreign` FOREIGN KEY (`part_internal_id`) REFERENCES `part_internals` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `part_processes_part_operation_id_foreign` FOREIGN KEY (`part_operation_id`) REFERENCES `part_operations` (`id`) ON DELETE SET NULL;

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
