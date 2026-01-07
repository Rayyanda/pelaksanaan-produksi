-- phpMyAdmin SQL Dump
-- version 6.0.0-dev+20251215.aa153def95
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 28, 2025 at 11:24 AM
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
-- Table structure for table `divisions`
--


--
-- Dumping data for table `divisions`
--

INSERT INTO `divisions` (`id`, `name`, `code`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Wax Room', 'DIV-4BFF9F', NULL, 1, '2025-12-27 13:11:48', '2025-12-27 13:11:48'),
(2, 'Mould Room', 'DIV-73CA9B', NULL, 1, '2025-12-27 13:23:35', '2025-12-27 13:23:35'),
(3, 'Melting Room', 'DIV-D58D14', NULL, 1, '2025-12-27 13:25:17', '2025-12-27 13:25:17'),
(4, 'Cut Off', 'DIV-17E81F', NULL, 1, '2025-12-27 20:46:25', '2025-12-27 20:46:25'),
(5, 'Heat Treatment', 'DIV-510A67', NULL, 1, '2025-12-27 21:07:49', '2025-12-27 21:07:49'),
(6, 'Finishing', 'DIV-E84BF8', NULL, 1, '2025-12-27 21:08:30', '2025-12-27 21:08:30'),
(7, 'Machining', 'DIV-6ACF12', NULL, 1, '2025-12-27 21:08:38', '2025-12-27 21:08:38'),
(8, 'Quality Control', 'DIV-ADAD95', NULL, 1, '2025-12-27 21:08:58', '2025-12-27 21:08:58');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `divisions`
--

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
