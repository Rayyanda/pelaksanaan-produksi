-- phpMyAdmin SQL Dump
-- version 6.0.0-dev+20251215.aa153def95
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 12, 2026 at 12:40 PM
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
-- Database: `pelaksanaan-produksi-sa`
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
(1, 1, 3, 'Injection', 8, NULL, NULL, '2026-02-09 22:07:34', '2026-02-09 22:10:47'),
(2, 1, NULL, 'Assembly', 6, NULL, NULL, '2026-02-09 22:07:34', '2026-02-09 22:07:34'),
(3, 2, NULL, 'Coating', 3, NULL, NULL, '2026-02-09 23:24:52', '2026-02-09 23:24:52'),
(4, 2, NULL, 'Stuccoing', 2, NULL, NULL, '2026-02-09 23:24:52', '2026-02-09 23:24:52'),
(5, 2, NULL, 'Drying', 3, NULL, NULL, '2026-02-09 23:24:52', '2026-02-09 23:24:52'),
(6, 3, NULL, 'Dewaxing', 2, NULL, NULL, '2026-02-09 23:26:12', '2026-02-09 23:26:12'),
(7, 3, NULL, 'Pre-Heat', 3, NULL, NULL, '2026-02-09 23:26:12', '2026-02-09 23:26:12'),
(8, 3, NULL, 'Pouring', 5, NULL, NULL, '2026-02-09 23:26:12', '2026-02-09 23:26:12'),
(9, 4, NULL, 'Knock-out', NULL, NULL, NULL, '2026-02-09 23:27:12', '2026-02-09 23:27:12'),
(10, 4, NULL, 'Cutting', 3, NULL, NULL, '2026-02-09 23:27:12', '2026-02-09 23:27:23'),
(11, 5, NULL, 'Normalizing', 2, NULL, NULL, '2026-02-09 23:33:04', '2026-02-09 23:33:04'),
(12, 5, NULL, 'Quenching', 1, NULL, NULL, '2026-02-09 23:33:04', '2026-02-09 23:33:04'),
(13, 5, NULL, 'Tempering', 2, NULL, NULL, '2026-02-09 23:33:04', '2026-02-09 23:33:04'),
(14, 6, NULL, 'Gate Grinding', 3, NULL, NULL, '2026-02-09 23:40:20', '2026-02-09 23:40:20'),
(15, 6, NULL, 'Sand Blasting', 3, NULL, NULL, '2026-02-09 23:40:20', '2026-02-09 23:40:20'),
(16, 7, NULL, 'Turning Op 1', 3, 'Bubut Manual 1', NULL, '2026-02-09 23:44:45', '2026-02-09 23:44:45'),
(17, 7, NULL, 'Turning Op 2', 3, 'Bubut Manual 2', NULL, '2026-02-09 23:44:45', '2026-02-09 23:44:45'),
(18, 7, NULL, 'Milling', 2, NULL, NULL, '2026-02-09 23:44:45', '2026-02-09 23:44:45'),
(19, 7, NULL, 'Milling Manual', 2, NULL, NULL, '2026-02-09 23:44:45', '2026-02-09 23:44:45'),
(20, 7, NULL, 'Milling CNC Op 1', 1, NULL, NULL, '2026-02-09 23:44:45', '2026-02-09 23:44:45'),
(21, 7, NULL, 'Milling CNC Op 2', 1, NULL, NULL, '2026-02-09 23:44:45', '2026-02-09 23:44:45'),
(22, 7, NULL, 'Milling CNC Op 3', 1, NULL, NULL, '2026-02-09 23:44:45', '2026-02-09 23:44:45'),
(23, 7, NULL, 'Milling CNC Op 4', 1, NULL, NULL, '2026-02-09 23:44:45', '2026-02-09 23:44:45'),
(24, 7, NULL, 'Milling CNC Op 5', 1, NULL, NULL, '2026-02-09 23:44:45', '2026-02-09 23:44:45'),
(25, 8, NULL, 'Visual Inspection', 2, NULL, NULL, '2026-02-10 00:10:30', '2026-02-10 00:10:30'),
(26, 8, NULL, 'Dimensional Check', 2, NULL, NULL, '2026-02-10 00:10:30', '2026-02-10 00:10:30'),
(27, 8, NULL, 'NDT', 3, 'Non-Destructive Test', NULL, '2026-02-10 00:10:30', '2026-02-10 00:10:30');

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `areas`
--
ALTER TABLE `areas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `areas`
--
ALTER TABLE `areas`
  ADD CONSTRAINT `areas_division_id_foreign` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `areas_foreman_id_foreign` FOREIGN KEY (`foreman_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
