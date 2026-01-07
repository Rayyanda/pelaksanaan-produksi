-- phpMyAdmin SQL Dump
-- version 6.0.0-dev+20251215.aa153def95
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 28, 2025 at 11:23 AM
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
(1, 1, 1, 'CLASS industrial Grade : C\r\nINJECT/POUR RUNNER NO : DEVELOPMENT\r\nPATTERN DIE :\r\n2282B PSI 125/P9 SEC 120\r\n2282C PSI 400/P6 SEC 60\r\nInject Sol Die :\r\n2282A PSI 600/P4 SEC 60\r\nWax Temp : 70+-5C', 1, NULL, '2025-12-27 13:18:07', '2025-12-27 13:18:07'),
(2, 1, 1, 'PATTERN ASSY PER WRA CARD DEVELOPMENT\r\nVISUAL CHECK FOR DEFECTS\r\nBerat assembling 4,75 kg', 2, NULL, '2025-12-27 13:21:35', '2025-12-27 13:21:35'),
(3, 1, 2, 'MOULD PREPARE PER QC INSTRUCTION\r\nPRIMARY NORMAL, AIR-DRY 4HRS\r\nSECY 2 F + 1 C + ceramic core + 3 C (20/06/2024_Pak Parlan)\r\nEXTRA AIR DRY 12 HRS PER COAT\r\n12 Jam dry sebelum dewax', 3, NULL, '2025-12-27 13:35:45', '2025-12-27 13:35:45'),
(4, 1, 3, 'DEWAX 10 MINS\r\nVISAL INSPECTION & CLEAN OUT', 4, NULL, '2025-12-27 13:37:19', '2025-12-27 14:09:37'),
(5, 1, 3, 'FIRE 2/3 HRS 700C Cast 1580+-5C\r\nIN VACUUM ASSIST^AT....................................M BAR\r\nPenuangan normal, ditaruh di atas fixture alumunium dan blower pada bagian bawah (pastikan angin pas dilubang)\r\nUpdate perubahan gating 2', 5, NULL, '2025-12-27 20:46:00', '2025-12-27 20:46:00'),
(6, 1, 4, 'MECH. KNOCK OUT :      YES', 6, NULL, '2025-12-27 20:47:17', '2025-12-27 20:47:17'),
(7, 1, 4, 'CUT-OF T/P TO Q.T', 7, NULL, '2025-12-27 20:47:46', '2025-12-27 20:47:46'),
(8, 1, 4, 'Bersihkan Keramik/ShotBlast awal', 8, NULL, '2025-12-27 21:07:20', '2025-12-27 21:07:20'),
(9, 1, 5, 'GRIND GATES TO 0.5mm Max, buang pin', 9, NULL, '2025-12-27 21:10:03', '2025-12-27 21:10:03'),
(10, 1, 5, 'HEAT TREATMENT:\r\nSOLUTION TREATMENT\r\nTemp = 1130C\r\nWaktu = 70 Menit\r\nPendinginan = Celup dalam air', 10, NULL, '2025-12-27 21:10:57', '2025-12-27 21:10:57'),
(11, 1, 5, 'HARDNESS CHECK                              RESULT:\r\nTENSILE TEST                               UTS RESULT:\r\n                                                           YS RESULT:\r\n%ELONGATION                                    RESULT:', 11, NULL, '2025-12-27 21:13:37', '2025-12-27 21:13:37'),
(12, 1, 6, 'HAND DRESS AS REQUIRED', 12, NULL, '2025-12-27 21:13:59', '2025-12-27 21:13:59'),
(13, 1, 6, 'Straightening IK No : IK/SM/P18-03 rev.00', 13, NULL, '2025-12-27 21:14:36', '2025-12-27 21:14:36'),
(14, 1, 6, 'GRIT BLAST', 14, NULL, '2025-12-27 21:14:54', '2025-12-27 21:14:54'),
(15, 1, 6, 'Pikling WI No. : PP/CF/001', 15, NULL, '2025-12-27 21:15:23', '2025-12-27 21:15:23'),
(16, 1, 6, 'DYE PENETRATION:', 16, NULL, '2025-12-27 21:16:10', '2025-12-27 21:16:10'),
(17, 1, 7, 'Machining refer to drawing P18-03d.26 MC', 17, NULL, '2025-12-27 21:16:49', '2025-12-27 21:16:49'),
(18, 1, 7, 'X-RAY TECH NO:', 18, NULL, '2025-12-27 21:17:44', '2025-12-27 21:17:44'),
(19, 1, 8, 'Cek dimensi dan visual', 19, NULL, '2025-12-27 21:18:06', '2025-12-27 21:18:06'),
(20, 1, 8, 'FINAL INSPECTION TO DWG & ORDER', 20, NULL, '2025-12-27 21:18:26', '2025-12-27 21:18:26');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `part_operations`
--
ALTER TABLE `part_operations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `part_operations_division_id_foreign` (`division_id`),
  ADD KEY `part_operations_part_internal_id_index` (`part_internal_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `part_operations`
--
ALTER TABLE `part_operations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `part_operations`
--
ALTER TABLE `part_operations`
  ADD CONSTRAINT `part_operations_division_id_foreign` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `part_operations_part_internal_id_foreign` FOREIGN KEY (`part_internal_id`) REFERENCES `part_internals` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
