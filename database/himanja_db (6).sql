-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 17, 2025 at 12:10 PM
-- Server version: 8.0.30
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `himanja_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `action` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `ip_address`, `user_agent`, `created_at`) VALUES
(1, 9, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 12:23:54'),
(2, 9, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 12:24:33'),
(3, 9, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 12:24:42'),
(4, 9, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 12:25:24'),
(5, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 12:28:02'),
(6, 1, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 12:28:55'),
(7, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 12:28:58'),
(8, 1, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 12:30:18'),
(9, 9, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 12:30:24'),
(10, 9, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 12:33:04'),
(11, 9, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 12:33:09'),
(12, 9, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 12:33:14'),
(13, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 12:33:17'),
(14, 1, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 12:34:30'),
(15, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 12:34:35'),
(16, 1, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 12:36:21'),
(17, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 12:36:25'),
(18, 1, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 12:37:22'),
(19, 2, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 12:37:28'),
(20, 2, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 12:42:48'),
(21, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 12:42:51'),
(22, 1, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 12:44:08'),
(23, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 12:44:12'),
(24, 1, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 12:48:27'),
(25, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 12:48:32'),
(26, 1, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 12:54:58'),
(27, 9, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 12:55:04'),
(28, 9, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 12:57:50'),
(29, 9, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 12:58:46'),
(30, 9, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 12:59:49'),
(31, 2, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 12:59:53'),
(32, 2, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 13:00:39'),
(33, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 13:00:42'),
(34, 1, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 13:01:14'),
(35, 2, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 13:01:18'),
(36, 2, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 13:04:29'),
(37, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 13:04:32'),
(38, 1, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 13:10:04'),
(39, 9, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 22:10:07'),
(40, 9, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 22:44:09'),
(41, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 22:44:12'),
(42, 1, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 22:44:36'),
(43, 9, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 22:44:40'),
(44, 9, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 22:50:12'),
(45, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 22:50:15'),
(46, 1, 'himada_edit', 'Updated HIMADA: BALISTIS', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 22:51:51'),
(47, 1, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 22:55:53'),
(48, 2, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 22:56:07'),
(49, 2, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 23:02:04'),
(50, 9, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 23:02:08'),
(51, 9, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 23:05:28'),
(52, 2, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 23:05:31'),
(53, 2, 'order_status_update', 'Updated order item 1 status to processing', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 23:06:06'),
(54, 2, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 23:06:45'),
(55, 9, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 23:06:51'),
(56, 9, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 23:10:09'),
(57, 2, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 23:10:20'),
(58, 2, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 23:10:24'),
(59, 9, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 23:18:26'),
(60, 9, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 23:18:41'),
(61, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 23:18:45'),
(62, 1, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 23:20:26'),
(63, 9, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 23:20:30'),
(64, 9, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 23:34:24'),
(65, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 23:34:27'),
(66, 1, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 23:39:18'),
(67, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 23:39:26'),
(68, 1, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 23:40:00'),
(69, 9, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 23:44:59'),
(70, 9, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-26 00:19:03'),
(71, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-26 00:19:07'),
(72, 1, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-26 00:22:13'),
(73, 9, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-26 00:22:18'),
(74, 9, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-26 00:27:20'),
(75, 9, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-26 00:27:24'),
(76, 9, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-28 06:41:09'),
(77, 9, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-28 06:41:19'),
(78, 2, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-28 06:41:55'),
(79, 2, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-28 06:42:15'),
(80, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-28 06:42:18'),
(81, 1, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-28 06:42:38'),
(82, 2, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-28 06:42:44'),
(83, 2, 'order_status_update', 'Updated order item 1 status to completed', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-28 06:52:37'),
(84, 2, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-28 06:52:45'),
(85, 9, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-28 06:52:50'),
(86, 9, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-28 06:56:11'),
(87, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-28 07:33:48'),
(88, 1, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-28 07:33:56'),
(89, 9, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-28 07:34:00'),
(90, 9, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-28 08:28:16'),
(91, 9, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-28 08:28:21'),
(92, 9, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-28 08:30:31'),
(93, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-28 08:30:40'),
(94, 1, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-28 08:33:42'),
(95, 9, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-28 08:41:16'),
(96, 9, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-28 09:52:04'),
(97, 9, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-28 09:52:08'),
(98, 9, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-28 10:02:25'),
(99, 9, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-28 10:02:30'),
(100, 9, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-28 10:05:01'),
(101, 9, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-28 10:05:11'),
(102, 9, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-28 10:45:40'),
(103, 9, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-28 10:45:44'),
(104, 9, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-28 10:46:17'),
(105, 9, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-28 10:46:20'),
(106, 9, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-06-30 06:53:03'),
(107, 9, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-06-30 06:53:29'),
(108, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-06-30 06:53:33'),
(109, 1, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-06-30 06:53:59'),
(110, 2, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-06-30 06:54:03'),
(111, 2, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-06-30 06:55:03'),
(112, 9, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-02 02:10:44'),
(113, 9, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-02 02:10:54'),
(114, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-02 02:10:58'),
(115, 1, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-02 02:11:20'),
(116, 2, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-02 02:11:24'),
(117, 2, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-02 02:12:24'),
(118, 9, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-02 17:07:24'),
(119, 9, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-02 17:19:55'),
(120, 2, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-02 17:20:24'),
(121, 2, 'order_status_update', 'Updated order item 2 status to completed', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-02 17:21:26'),
(122, 2, 'order_status_update', 'Updated order item 2 status to completed', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-02 17:21:56'),
(123, 2, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-02 17:22:43'),
(124, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-02 17:22:51'),
(125, 1, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-02 17:26:17'),
(126, 9, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-02 17:26:21'),
(127, 9, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-02 17:26:33'),
(128, 2, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-09 08:44:20'),
(129, 2, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-09 08:51:18'),
(130, 2, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-09 08:51:29'),
(131, 2, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-09 08:55:57'),
(132, 2, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-09 08:56:04'),
(133, 2, 'order_status_update', 'Updated order item 2 status to pending', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-09 14:40:05'),
(134, 2, 'order_status_update', 'Updated order item 2 status to completed', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-09 14:40:09'),
(135, 2, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-09 14:47:19'),
(136, 9, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-09 14:47:26'),
(137, 9, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-09 14:47:55'),
(138, 2, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-09 14:48:01'),
(139, 2, 'order_status_update', 'Updated order item 3 status to completed', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-09 14:53:11'),
(140, 2, 'order_status_update', 'Updated order item 3 status to confirmed', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-09 14:53:31'),
(141, 2, 'order_status_update', 'Updated order item 3 status to processing', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-09 14:53:45'),
(142, 2, 'order_status_update', 'Updated order item 3 status to ready', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-09 14:53:54'),
(143, 2, 'order_status_update', 'Updated order item 3 status to completed', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-09 14:54:01'),
(144, 2, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-09 15:02:00'),
(145, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-09 15:02:06'),
(146, 1, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-09 15:16:45'),
(147, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-09 15:16:57'),
(148, 1, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-09 15:18:21'),
(149, 2, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-09 15:18:31'),
(150, 2, 'product_add', 'Added product: Sate gist', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-09 15:19:49'),
(151, 2, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-09 15:26:21'),
(152, 9, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-09 15:31:06'),
(153, 9, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-09 15:44:48'),
(154, 2, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-09 15:44:54'),
(155, 2, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-09 15:45:19'),
(156, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-09 15:45:36'),
(157, 1, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-09 16:15:48'),
(158, 9, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-09 16:15:54'),
(159, 9, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-09 17:23:10'),
(160, 9, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-09 17:27:10'),
(161, 9, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-09 17:33:38'),
(162, 2, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-09 17:33:45'),
(163, 2, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-09 17:33:58'),
(164, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-09 17:34:07'),
(165, 1, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-09 17:34:16'),
(166, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-09 17:34:34'),
(167, 1, 'user_add', 'Added user: admin_mpc (himada_admin)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-09 17:36:33'),
(168, 1, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-09 17:36:37'),
(169, 10, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-09 17:36:53'),
(170, 10, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-09 17:37:35'),
(171, 10, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-09 17:37:52'),
(172, 10, 'product_add', 'Added product: Batu  Papua', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-09 18:00:21'),
(173, 10, 'product_add', 'Added product: Batu  Papua', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-09 18:05:23'),
(174, 10, 'product_delete', 'Deleted product: Batu  Papua', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-09 18:05:30'),
(175, 10, 'product_add', 'Added product: batu', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-09 18:57:40'),
(176, 10, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-09 18:59:09'),
(177, 10, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-09 18:59:18'),
(178, 10, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-09 19:02:26'),
(179, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-09 19:02:37'),
(180, 1, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-09 19:03:05'),
(181, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-09 19:03:13'),
(182, 1, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-09 19:06:02'),
(183, 2, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-09 19:06:15'),
(184, 2, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-09 19:09:39'),
(185, 10, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-09 19:09:50'),
(186, 10, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-09 19:23:21'),
(187, 9, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-09 19:23:29'),
(188, 9, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 00:12:42'),
(189, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 00:12:54'),
(190, 1, 'user_add', 'Added user: admin_imassu (himada_admin)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 00:15:33'),
(191, 1, 'user_add', 'Added user: admin_ikmm (himada_admin)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 00:16:38'),
(192, 1, 'user_edit', 'Updated user: admin_ikmm', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 00:17:44'),
(193, 1, 'user_edit', 'Updated user: admin_imassu', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 00:17:54'),
(194, 1, 'user_edit', 'Updated user: admin_mpc', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 00:18:04'),
(195, 1, 'user_add', 'Added user: admin_ks3 (himada_admin)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 00:18:59'),
(196, 1, 'user_edit', 'Updated user: admin_himari', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 00:19:23'),
(197, 1, 'user_add', 'Added user: admin_sms (himada_admin)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 00:20:18'),
(198, 1, 'user_add', 'Added user: admin_kemass (himada_admin)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 00:21:29'),
(199, 1, 'user_edit', 'Updated user: admin_himamira', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 00:21:55'),
(200, 1, 'user_edit', 'Updated user: admin_kemass', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 00:22:10'),
(201, 1, 'user_edit', 'Updated user: admin_sms', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 00:22:19'),
(202, 1, 'user_edit', 'Updated user: admin_sms', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 00:22:28'),
(203, 1, 'user_edit', 'Updated user: admin_ks3', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 00:22:34'),
(204, 1, 'user_edit', 'Updated user: admin_ikmm', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 00:22:40'),
(205, 1, 'user_edit', 'Updated user: admin_imassu', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 00:22:51'),
(206, 1, 'user_edit', 'Updated user: admin_mpc', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 00:22:57'),
(207, 1, 'user_edit', 'Updated user: admin_gist', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 00:23:07'),
(208, 1, 'user_edit', 'Updated user: admin_himari', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 00:23:17'),
(209, 1, 'user_edit', 'Updated user: admin_himamira', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 00:23:29'),
(210, 1, 'user_edit', 'Updated user: admin_himamira', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 00:23:54'),
(211, 1, 'user_add', 'Added user: admin_saburai (himada_admin)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 00:24:36'),
(212, 1, 'user_add', 'Added user: admin_mavias (himada_admin)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 00:25:23'),
(213, 1, 'user_add', 'Added user: admin_kajaba (himada_admin)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 00:26:12'),
(214, 1, 'user_add', 'Added user: admin_jatengstis (himada_admin)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 00:26:52'),
(215, 1, 'user_add', 'Added user: admin_kbmsy (himada_admin)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 00:27:28'),
(216, 1, 'user_add', 'Added user: admin_bekisar (himada_admin)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 00:28:19'),
(217, 1, 'user_add', 'Added user: admin_balistis (himada_admin)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 00:29:04'),
(218, 1, 'user_add', 'Added user: admin_rinjani (himada_admin)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 00:29:46'),
(219, 1, 'user_add', 'Added user: admin_imsak (himada_admin)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 00:30:28'),
(220, 1, 'user_add', 'Added user: admin_imassi (himada_admin)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 00:31:08'),
(221, 1, 'user_add', 'Added user: admin_imf (himada_admin)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 00:31:47'),
(222, 1, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 00:32:31'),
(223, 2, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 00:32:50'),
(224, 2, 'product_add', 'Added product: Sate asli GIST', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 00:37:11'),
(225, 2, 'product_add', 'Added product: Sate asli GIST', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 00:39:04'),
(226, 2, 'product_delete', 'Deleted product: Sate asli GIST', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 00:39:17'),
(227, 2, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 00:48:19'),
(228, 2, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 00:48:28'),
(229, 2, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 02:21:19'),
(230, 10, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 02:21:32'),
(231, 10, 'product_edit', 'Updated product: Batu  Papua', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 02:21:51'),
(232, 10, 'product_delete', 'Deleted product: batu', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 02:21:54'),
(233, 10, 'product_edit', 'Updated product: Batu  Papua', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 02:22:56'),
(234, 10, 'product_edit', 'Updated product: Batu  Papua', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 02:23:09'),
(235, 10, 'product_edit', 'Updated product: Gantungan kunci khas Papua', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 02:27:48'),
(236, 10, 'product_edit', 'Updated product: Kain Tenun khas Ternate', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 02:31:38'),
(237, 10, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 02:35:56');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `ip_address`, `user_agent`, `created_at`) VALUES
(238, 9, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 02:36:19'),
(239, 9, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 02:37:39'),
(240, 2, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 02:37:45'),
(241, 2, 'product_edit', 'Updated product: Kopi Gayo Premium', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 02:38:52'),
(242, 2, 'product_delete', 'Deleted product: Sate asli GIST', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 02:39:33'),
(243, 2, 'product_delete', 'Deleted product: Sate gist', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 02:39:39'),
(244, 2, 'product_delete', 'Deleted product: Kopi Gayo Arabica Premium', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 02:39:46'),
(245, 2, 'product_edit', 'Updated product: Mie Aceh Instan', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 02:41:06'),
(246, 2, 'product_edit', 'Updated product: Kaos GIST Official', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 02:41:19'),
(247, 2, 'product_delete', 'Deleted product: Merchandise Gayo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 02:41:43'),
(248, 2, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 02:42:04'),
(249, 3, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 02:42:19'),
(250, 3, 'product_edit', 'Updated product: Gantungan Kunci Melayu', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 02:45:19'),
(251, 3, 'product_edit', 'Updated product: Kaos Riau Pride', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 02:45:35'),
(252, 3, 'product_edit', 'Updated product: Kerupuk Sanjai Balado', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 02:45:47'),
(253, 3, 'product_edit', 'Updated product: Bolu Kemojo Riau', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 02:46:01'),
(254, 3, 'product_edit', 'Updated product: Bolu Kemojo Riau', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 02:46:28'),
(255, 3, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 02:46:32'),
(256, 4, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 02:47:11'),
(257, 4, 'product_edit', 'Updated product: Kaos Raflesia Bloom', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 02:53:16'),
(258, 4, 'product_edit', 'Updated product: Gantungan Bunga Raflesia', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 02:53:29'),
(259, 4, 'product_edit', 'Updated product: Rendang Bengkulu', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 02:53:42'),
(260, 4, 'product_edit', 'Updated product: Kue Tat Bengkulu', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 02:54:01'),
(261, 4, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 02:54:17'),
(262, 12, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 02:54:35'),
(263, 12, 'product_edit', 'Updated product: Bolu Batik', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 02:57:24'),
(264, 12, 'product_edit', 'Updated product: Rendang khas Padang', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 02:59:05'),
(265, 12, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 02:59:10'),
(266, 11, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 02:59:26'),
(267, 11, 'product_edit', 'Updated product: Saksang Batak Frozen', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 03:01:33'),
(268, 11, 'product_edit', 'Updated product: Kaos Batak Heritage', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 03:02:54'),
(269, 11, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 03:03:10'),
(270, 15, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 03:03:28'),
(271, 15, 'product_delete', 'Deleted product: Merchandise Sriwijaya', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 03:05:10'),
(272, 15, 'product_edit', 'Updated product: Pempek Kapal Selam', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 03:05:59'),
(273, 15, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 03:06:10'),
(274, 15, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 03:06:19'),
(275, 15, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 03:06:27'),
(276, 13, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 03:06:43'),
(277, 13, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 03:07:52'),
(278, 9, 'login_failed', 'Failed login attempt for user: mahasiswa@stis.ac.id', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 03:08:03'),
(279, 9, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 03:08:07'),
(280, 9, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 03:08:31'),
(281, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 03:08:59'),
(282, 1, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 03:09:37'),
(283, 13, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 03:09:48'),
(284, 13, 'product_delete', 'Deleted product: Merchandise Timah', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 03:11:34'),
(285, 13, 'product_edit', 'Updated product: Kaos Bangka Belitung', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 03:11:49'),
(286, 13, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 03:12:53'),
(287, 14, 'login_failed', 'Failed login attempt for user: admin.sms@stis.ac.id', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 03:13:07'),
(288, 14, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 03:13:11'),
(289, 14, 'product_edit', 'Updated product: Oleh-oleh Dodol Jambi', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 03:14:46'),
(290, 14, 'product_edit', 'Updated product: Dodol Kentang', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 03:15:04'),
(291, 14, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 03:15:09'),
(292, 16, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 03:15:25'),
(293, 16, 'product_edit', 'Updated product: Kaos Seruit Lampung', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 03:18:49'),
(294, 16, 'product_delete', 'Deleted product: Kaos Seruit Lampung', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 03:19:00'),
(295, 16, 'product_edit', 'Updated product: Seruit Lampung', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 03:19:17'),
(296, 16, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 03:19:22'),
(297, 18, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 03:19:38'),
(298, 18, 'product_delete', 'Deleted product: Merchandise Kujang', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 03:19:47'),
(299, 18, 'product_edit', 'Updated product: Gantungan Kujang', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 03:20:30'),
(300, 18, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 03:20:33'),
(301, 17, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 03:20:45'),
(302, 17, 'product_delete', 'Deleted product: Kaos Betawi Banget', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 03:22:58'),
(303, 17, 'product_edit', 'Updated product: Ondel-ondel Mini', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 03:23:09'),
(304, 17, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 03:23:12'),
(305, 19, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 03:23:29'),
(306, 19, 'product_edit', 'Updated product: Gantungan Wayang Kulit', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 03:25:59'),
(307, 19, 'product_edit', 'Updated product: Wingko Babat', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 03:26:25'),
(308, 19, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 03:26:30'),
(309, 20, 'login_failed', 'Failed login attempt for user: admin.kbmsy@stis.ac.id', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 03:26:47'),
(310, 20, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 03:26:50'),
(311, 20, 'product_delete', 'Deleted product: Merchandise Gudeg Jogja', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 03:27:09'),
(312, 20, 'product_edit', 'Updated product: Bakpia Pathok Isi Kacang', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 03:28:00'),
(313, 20, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 03:28:05'),
(314, 21, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 03:28:23'),
(315, 21, 'product_delete', 'Deleted product: Kaos Rawon Surabaya', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 03:29:05'),
(316, 21, 'product_edit', 'Updated product: Rujak Cingur Surabaya', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 03:29:21'),
(317, 21, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 03:29:26'),
(318, 24, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 03:29:45'),
(319, 24, 'product_delete', 'Deleted product: Kaos Borneo Spirit', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 03:30:01'),
(320, 24, 'product_edit', 'Updated product: Oleh-oleh Amplang', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 03:30:36'),
(321, 24, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 03:30:56'),
(322, 25, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 03:31:23'),
(323, 25, 'product_delete', 'Deleted product: Merchandise Sulawesi', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 03:32:24'),
(324, 25, 'product_edit', 'Updated product: Gantungan Toraja', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 03:32:37'),
(325, 25, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 03:32:41'),
(326, 22, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 03:33:00'),
(327, 22, 'product_edit', 'Updated product: Bali Tote Bag', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 03:35:02'),
(328, 22, 'product_edit', 'Updated product: Ayam Betutu Bali', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 03:35:27'),
(329, 22, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 03:35:32'),
(330, 23, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 03:35:48'),
(331, 23, 'product_delete', 'Deleted product: Kaos Ayam Taliwang', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 03:35:56'),
(332, 23, 'product_edit', 'Updated product: Plecing Kangkung NTB', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 03:36:53'),
(333, 23, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 03:37:00'),
(334, 26, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 03:37:14'),
(335, 26, 'product_delete', 'Deleted product: Merchandise Flobamora', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 03:41:02'),
(336, 26, 'product_edit', 'Updated product: Kopi Bajawa', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 03:57:53'),
(337, 26, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 04:01:05'),
(338, 2, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 08:43:34'),
(339, 2, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 09:43:52'),
(340, 2, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 09:43:58'),
(341, 2, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 09:45:59'),
(342, 9, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 09:50:57'),
(343, 9, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 09:51:57'),
(344, 9, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 09:58:55'),
(345, 9, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-10 10:05:47'),
(346, 9, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-12 16:07:29'),
(347, 9, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-12 16:14:08'),
(348, 9, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-12 16:14:27'),
(349, 9, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-12 17:45:09'),
(350, 9, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-12 17:45:19'),
(351, 9, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-12 17:59:42'),
(352, 2, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-12 17:59:49'),
(353, 2, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-12 18:16:34'),
(354, 2, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-12 18:16:45'),
(355, 2, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-12 18:23:39'),
(356, 22, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-12 18:23:50'),
(357, 22, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-12 18:27:24'),
(358, 9, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-17 09:58:42'),
(359, 9, 'logout', 'User logged out successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-17 09:59:18');

-- --------------------------------------------------------

--
-- Table structure for table `himada`
--

CREATE TABLE `himada` (
  `id` int NOT NULL,
  `nama` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_lengkap` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `daerah_asal` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `warna_tema` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#b2e7e8',
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `koordinat_lat` decimal(10,8) DEFAULT NULL,
  `koordinat_lng` decimal(11,8) DEFAULT NULL,
  `logo_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `himada`
--

INSERT INTO `himada` (`id`, `nama`, `nama_lengkap`, `daerah_asal`, `warna_tema`, `deskripsi`, `koordinat_lat`, `koordinat_lng`, `logo_url`, `contact_email`, `contact_phone`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'GIST', 'Gam Inong Statistik', 'Aceh', '#b2e7e8', 'Himpunan mahasiswa dari Serambi Mekah dengan kopi gayo dan mie aceh yang legendaris.', 4.69510000, 96.74940000, 'https://www.istockphoto.com/id/vektor/template-simbol-teknologi-jaringan-internet-global-gm1550925116-526406865?searchscope=image%2Cfilm', 'gist@stis.ac.id', '081234567890', 1, '2025-06-25 12:22:15', '2025-07-09 15:30:49'),
(2, 'HIMARI', 'Himpunan Mahasiswa Riau dan Kepulauan Riau', 'Riau dan Kepulauan Riau', '#b0e8ce', 'Perwakilan mahasiswa dari tanah Melayu dengan kekayaan budaya dan kuliner khas.', 0.29330000, 101.70680000, NULL, 'himari@stis.ac.id', '081234567891', 1, '2025-06-25 12:22:15', '2025-06-25 12:22:15'),
(3, 'HIMAMIRA', 'Himpunan Mahasiswa Bumi Raflesia', 'Bengkulu', '#fbd2b6', 'Mahasiswa dari Bumi Raflesia dengan rendang bengkulu dan kue tat yang khas.', -3.79280000, 102.26080000, NULL, 'himamira@stis.ac.id', '081234567892', 1, '2025-06-25 12:22:15', '2025-06-25 12:22:15'),
(4, 'IKMM', 'Ikatan Kekeluargaan Mahasiswa Minang', 'Sumatera Barat', '#fbd3df', 'Representasi mahasiswa Minang dengan rendang dan kebudayaan adat yang kuat.', -0.78930000, 100.65430000, NULL, 'ikmm@stis.ac.id', '081234567893', 1, '2025-06-25 12:22:15', '2025-06-25 12:22:15'),
(5, 'IMASSU', 'Ikatan Mahasiswa Statistik Sumatera Utara', 'Sumatera Utara', '#b2e7e8', 'Mahasiswa dari tanah Batak dengan arsik dan saksang yang menggugah selera.', 3.59520000, 98.67220000, NULL, 'imassu@stis.ac.id', '081234567894', 1, '2025-06-25 12:22:15', '2025-06-25 12:22:15'),
(6, 'KEMASS', 'Kerukunan Mahasiswa Statistik Sriwijaya', 'Sumatera Selatan', '#b0e8ce', 'Perwakilan mahasiswa dari tanah Sriwijaya dengan pempek dan tekwan khasnya.', -2.97610000, 104.77540000, NULL, 'kemass@stis.ac.id', '081234567895', 1, '2025-06-25 12:22:15', '2025-06-25 12:22:15'),
(7, 'KS3', 'Kekeluargaan Statistisi Serumpun Sebalai', 'Bangka Belitung', '#fbd2b6', 'Mahasiswa dari kepulauan timah dengan lempah kuning dan gangan yang lezat.', -2.74100000, 106.44050000, NULL, 'ks3@stis.ac.id', '081234567896', 1, '2025-06-25 12:22:15', '2025-06-25 12:22:15'),
(8, 'SMS', 'Silaturahmi Mahasiswa Siginjai', 'Jambi', '#fbd3df', 'Representasi mahasiswa dari Bumi Serambi Mekah dengan gulai tempoyak dan dodol kentang.', -1.48510000, 103.60440000, NULL, 'sms@stis.ac.id', '081234567897', 1, '2025-06-25 12:22:15', '2025-06-25 12:22:15'),
(9, 'SABURAI', 'Statistisi Sang Bumi Ruwai Jurai', 'Lampung', '#b2e7e8', 'Mahasiswa dari Bumi Ruwa Jurai dengan seruit dan kerupuk kemplang yang renyah.', -4.55850000, 105.40680000, NULL, 'saburai@stis.ac.id', '081234567898', 1, '2025-06-25 12:22:15', '2025-06-25 12:22:15'),
(10, 'KAJABA', 'Kulawarga Jawa Barat Sareng Banten', 'Jawa Barat dan Banten', '#b0e8ce', 'Perwakilan mahasiswa Sunda dengan nasi liwet dan kerak telor yang autentik.', -6.91750000, 107.61910000, NULL, 'kajaba@stis.ac.id', '081234567899', 1, '2025-06-25 12:22:15', '2025-06-25 12:22:15'),
(11, 'MAVIAS', 'Mahasiswa Batavia dan Sekitarnya', 'Jakarta, Depok, Tangerang, Bekasi', '#fbd2b6', 'Mahasiswa dari ibu kota dengan kerak telor dan soto betawi yang ikonik.', -6.20880000, 106.84560000, NULL, 'mavias@stis.ac.id', '081234567800', 1, '2025-06-25 12:22:15', '2025-06-25 12:22:15'),
(12, 'JATENGSTIS', 'Himpunan Mahasiswa Daerah Jawa Tengah', 'Jawa Tengah', '#fbd3df', 'Representasi mahasiswa dari tanah Jawa dengan gudeg dan wingko babat yang manis.', -7.15090000, 110.14030000, NULL, 'jateng@stis.ac.id', '081234567801', 1, '2025-06-25 12:22:15', '2025-06-25 12:22:15'),
(13, 'KBMSY', 'Keluarga Besar Mahasiswa STIS Yogyakarta', 'DI Yogyakarta', '#b2e7e8', 'Mahasiswa dari kota pelajar dengan gudeg dan bakpia pathok yang terkenal.', -7.79560000, 110.36950000, NULL, 'kbmsy@stis.ac.id', '081234567802', 1, '2025-06-25 12:22:15', '2025-06-25 12:22:15'),
(14, 'BEKISAR', 'Himpunan Mahasiswa STIS Daerah Jawa Timur', 'Jawa Timur', '#b0e8ce', 'Perwakilan mahasiswa dari tanah Surabaya dengan rawon dan rujak cingur yang pedas.', -7.25750000, 112.75210000, NULL, 'bekisar@stis.ac.id', '081234567803', 1, '2025-06-25 12:22:15', '2025-06-25 12:22:15'),
(15, 'IMSAK', 'Ikatan Mahasiswa STIS Asal Kalimantan', 'Kalimantan', '#fbd2b6', 'Mahasiswa dari pulau Borneo dengan soto banjar dan amplang yang gurih.', -1.68150000, 113.38240000, NULL, 'imsak@stis.ac.id', '081234567804', 1, '2025-06-25 12:22:15', '2025-06-25 12:22:15'),
(16, 'IMASSI', 'Ikatan Mahasiswa Statistik Sulawesi', 'Sulawesi', '#fbd3df', 'Representasi mahasiswa dari Sulawesi dengan coto makassar dan konro yang kaya rempah.', -2.54890000, 120.16190000, NULL, 'imassi@stis.ac.id', '081234567805', 1, '2025-06-25 12:22:15', '2025-06-25 12:22:15'),
(17, 'BALISTIS', 'Himpunan Mahasiswa STIS Daerah Bali', 'Bali', '#b2e7e8', 'Mahasiswa dari Pulau Dewata dengan ayam betutu dan lawar yang eksotis.', -8.40950000, 115.18890000, NULL, 'balistis@stis.ac.id', '081234567806', 1, '2025-06-25 12:22:15', '2025-06-25 12:22:15'),
(18, 'RINJANI', 'Himpunan Mahasiswa STIS Daerah Nusa Tenggara Barat', 'Nusa Tenggara Barat', '#b0e8ce', 'Perwakilan mahasiswa dari pulau seribu masjid dengan plecing kangkung dan ayam taliwang.', -8.65000000, 117.36160000, NULL, 'rinjani@stis.ac.id', '081234567807', 1, '2025-06-25 12:22:15', '2025-06-25 12:22:15'),
(19, 'IMF', 'Ikatan Mahasiswa FLOBAMORA Politeknik Statistika STIS', 'Nusa Tenggara Timur dan Timor Leste', '#fbd2b6', 'Mahasiswa dari tanah Flores dengan jagung bose dan ikan asin yang khas.', -8.65730000, 121.07940000, NULL, 'imf@stis.ac.id', '081234567808', 1, '2025-06-25 12:22:15', '2025-06-25 12:22:15'),
(20, 'MPC', 'Moluccas Papauan Community', 'Maluku dan Papua', '#fbd3df', 'Representasi mahasiswa dari tanah rempah dan cendrawasih dengan papeda dan ikan kuah kuning.', -4.26990000, 138.08040000, NULL, 'mpc@stis.ac.id', '081234567809', 1, '2025-06-25 12:22:15', '2025-06-25 12:22:15');

-- --------------------------------------------------------

--
-- Table structure for table `himada_history`
--

CREATE TABLE `himada_history` (
  `id` int NOT NULL,
  `himada_id` int NOT NULL,
  `judul` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_panjang` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cerita` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `fun_facts` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `kategori` enum('sejarah','budaya','tradisi','kuliner','wisata','prestasi') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'sejarah',
  `is_featured` tinyint(1) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `gambar_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `himada_history`
--

INSERT INTO `himada_history` (`id`, `himada_id`, `judul`, `nama_panjang`, `cerita`, `fun_facts`, `kategori`, `is_featured`, `created_at`, `updated_at`, `gambar_url`) VALUES
(1, 1, 'GIST - Serambi Mekah', 'Gam Inong Statistik', 'GIST mewakili Aceh, daerah di ujung barat Indonesia yang terkenal dengan sebutan Serambi Mekah karena pengaruh Islam yang kuat. Aceh punya kopi Gayo terbaik di dunia, tari saman yang dinamis, hingga tsunami memorial yang mengharukan. Budaya mereka penuh nilai gotong-royong dan keteguhan hati.', 'Kopi Gayo pernah jadi salah satu kopi termahal di dunia|Tari Saman diakui UNESCO sebagai warisan dunia|Di Aceh ada masjid yang selamat dari tsunami 2004|Mie Aceh dikenal dengan pedasnya yang nendang|Ada tradisi Peusijuek untuk memberi berkah', 'budaya', 1, '2025-07-10 01:25:45', '2025-07-10 01:25:45', NULL),
(2, 2, 'HIMARI - Bumi Melayu Lancang Kuning', 'Himpunan Mahasiswa Riau', 'Riau dikenal sebagai tanah Melayu dengan budaya sopan santun, rumah Lontiok, dan pantun yang mendayu. Sungai Siak di Pekanbaru menyimpan sejarah Kesultanan Siak yang megah, dan hutan-hutan gambut Riau jadi paru-paru dunia.', 'Istana Siak disebut Versailles-nya Riau|Pantun Melayu Riau pernah dibacakan di PBB|Ada hutan bakau dengan jembatan cinta di Dumai|Rumah Lontiok unik dengan atap menyerupai perahu terbalik|Riau produsen minyak bumi terbesar di Indonesia', 'budaya', 1, '2025-07-10 01:25:45', '2025-07-10 01:25:45', NULL),
(3, 3, 'HIMAMIRA - Bumi Raflesia', 'Himpunan Mahasiswa Bumi Raflesia', 'Bengkulu dikenal dengan bunga Rafflesia Arnoldii, bunga terbesar di dunia yang mekar di hutan-hutannya. Pantainya indah dengan pasir hitam dan laut lepas, sementara tradisi Tabot jadi simbol keberanian rakyat Bengkulu.', 'Rafflesia Arnoldii hanya mekar beberapa hari dalam setahun|Pantai Panjang salah satu terpanjang di Asia Tenggara|Tradisi Tabot sudah berlangsung lebih dari 300 tahun|Bengkulu pernah jadi tempat pengasingan Soekarno|Kue Tat jadi simbol kasih sayang di sana', 'budaya', 1, '2025-07-10 01:25:45', '2025-07-10 01:25:45', NULL),
(4, 4, 'IKMM - Ranah Minang', 'Ikatan Kekeluargaan Mahasiswa Minang', 'Sumatera Barat kaya dengan tradisi, dari rumah gadang beratap gonjong hingga masakan rendang yang mendunia. Seni randai, pencak silat, dan legenda Malin Kundang hidup dalam keseharian orang Minang.', 'Rendang pernah dinobatkan sebagai makanan terenak di dunia|Ngarai Sianok mirip Grand Canyon versi Indonesia|Jam Gadang jadi ikon Bukittinggi|Minangkabau punya matrilineal terbesar di dunia|Festival Pacu Jawi (balap sapi) sangat unik', 'budaya', 1, '2025-07-10 01:25:45', '2025-07-10 01:25:45', NULL),
(5, 5, 'IMASSU - Horas Batak', 'Ikatan Mahasiswa Statistik Sumatera Utara', 'SUMUT penuh energi dengan Danau Toba sebagai danau vulkanik terbesar di dunia. Budaya Batak terkenal dengan ulos, gondang, tortor, dan filosofi Dalihan Na Tolu yang mengatur kehidupan masyarakat.', 'Danau Toba terbentuk dari letusan supervulkan 74.000 tahun lalu|Ulos digunakan untuk segala peristiwa hidup Batak|Ada tradisi Sigale-gale boneka penari di Samosir|Tugu Yesus terbesar di Asia ada di Sumut|Rasanya durian Sidikalang bikin ketagihan', 'budaya', 1, '2025-07-10 01:25:45', '2025-07-10 01:25:45', NULL),
(6, 6, 'KEMASS - Bumi Sriwijaya', 'Kerukunan Mahasiswa Statistik Sriwijaya', 'Sumatera Selatan, pusat kerajaan Sriwijaya yang dulu menguasai Asia Tenggara. Pempek, jembatan Ampera, dan Sungai Musi jadi identitas Palembang dengan nuansa sejarah yang kental.', 'Kerajaan Sriwijaya pernah jadi pusat agama Buddha terbesar di Asia Tenggara|Pempek aslinya dibuat dari ikan belida|Jembatan Ampera dulu bisa diangkat untuk kapal besar|Ada pasar terapung di Sungai Musi|Lemang dan tempoyak jadi makanan khas unik', 'budaya', 1, '2025-07-10 01:25:45', '2025-07-10 01:25:45', NULL),
(7, 7, 'KS3 - Negeri Timah', 'Kekeluargaan Statistisi Serumpun Sebalai', 'Bangka Belitung terkenal dengan tambang timah, pantai-pantai berpasir putih, dan batu-batu granit raksasa. Makanan gangan dan martabak Bangka bikin siapa saja rindu datang lagi.', 'Pantai Parai Tenggiri sering dijuluki Maldives-nya Indonesia|Martabak Bangka punya lebih dari 20 varian rasa|Di sana ada mercusuar tua dari era kolonial|Gangan ikan dimasak dengan nanas khas Bangka|Suku Sawang dulu dikenal sebagai pelaut ulung', 'budaya', 1, '2025-07-10 01:25:45', '2025-07-10 01:25:45', NULL),
(8, 8, 'SMS - Negeri Angso Duo', 'Silaturahmi Mahasiswa Siginjai', 'Jambi dikenal dengan batik motif angso duo, tari rentak besapih, dan dodol kentang yang legit. Gunung Kerinci menjulang sebagai atap Sumatera, dikelilingi kebun teh dan hutan tropis.', 'Gunung Kerinci adalah gunung tertinggi di Sumatera|Jambi punya kebun teh terluas se-Asia Tenggara|Batik Jambi punya lebih dari 300 motif|Ada Candi Muaro Jambi kompleks candi Hindu-Buddha terbesar di Asia Tenggara|Rendang jengkol di Jambi digemari wisatawan', 'budaya', 1, '2025-07-10 01:25:45', '2025-07-10 01:25:45', NULL),
(9, 9, 'SABURAI - Bumi Ruwa Jurai', 'Statistisi Sang Bumi Ruwai Jurai', 'Lampung memukau dengan kain tapis, seruit ikan, dan pantai-pantai eksotis seperti Teluk Kiluan yang jadi rumah lumba-lumba. Tari sembah mereka anggun penuh makna persaudaraan.', 'Tapis pernah dipakai Miss Universe Indonesia|Di Teluk Kiluan lumba-lumba sering muncul di pagi hari|Ada Gajah Way Kambas pusat konservasi gajah terbesar|Kopi robusta Lampung termasuk terbaik di Indonesia|Seruit selalu disantap ramai-ramai', 'budaya', 1, '2025-07-10 01:25:45', '2025-07-10 01:25:45', NULL),
(10, 10, 'KAJABA - Sunda Banten Bersatu', 'Kulawarga Jawa Barat Sareng Banten', 'Jawa Barat dan Banten penuh dengan liwet, angklung, wayang golek, dan pantai Anyer yang memesona. Rampak kendang mereka membawa semangat yang membakar semangat penonton.', 'Angklung diakui UNESCO sebagai warisan dunia|Wayang Golek jadi salah satu seni tertua|Banten punya Baduy, suku yang masih menjaga tradisi asli|Gunung Tangkuban Perahu penuh legenda Sangkuriang|Lotek jadi salad ala Sunda yang sehat', 'budaya', 1, '2025-07-10 01:25:45', '2025-07-10 01:25:45', NULL),
(11, 11, 'MAVIAS - Betawi Asik', 'Mahasiswa Batavia dan Sekitarnya', 'Jakarta dan sekitarnya identik dengan ondel-ondel, kerak telor, dan bajaj yang ramai. Budaya Betawi kaya dengan lenong, tanjidor, dan silat Cingkrik yang enerjik.', 'Ondel-ondel bisa setinggi 2,5 meter|Kerak telor dulunya makanan bangsawan Batavia|Ada kampung Cina di Glodok tertua di Indonesia|Setiap Lebaran Betawi ada festival besar di Monas|Tanjidor dulu dimainkan untuk menyambut tamu penting', 'budaya', 1, '2025-07-10 01:25:45', '2025-07-10 01:25:45', NULL),
(12, 12, 'JATENGSTIS - Guyub Rukun', 'Himpunan Mahasiswa Daerah Jawa Tengah', 'Jawa Tengah kaya akan sejarah dan budaya: Candi Borobudur, tari gambyong, hingga angkringan di malam hari. Filosofi “guyub rukun” selalu melekat pada masyarakatnya.', 'Candi Borobudur salah satu keajaiban dunia|Ada kereta kencana Keraton Yogyakarta di museum|Wayang kulit dimainkan semalaman penuh|Nasi kucing jadi ikon angkringan murah meriah|Batik Solo terkenal hingga mancanegara', 'budaya', 1, '2025-07-10 01:25:45', '2025-07-10 01:25:45', NULL),
(13, 13, 'KBMSY - Jogja Istimewa', 'Keluarga Besar Mahasiswa STIS Yogyakarta', 'Yogyakarta dikenal sebagai kota pelajar dengan keramahan, gudeg manis, bakpia legit, dan suasana Malioboro yang hangat. Seni angklung dan becak masih lestari di kota ini.', 'Gudeg Yogya disebut “sayur nangka paling manis”|Ada Tugu Jogja yang melegenda|Keraton Yogya masih dihuni Sultan hingga kini|Pantai Parangtritis punya mitos Nyi Roro Kidul|Bakpia Pathok jadi oleh-oleh wajib', 'budaya', 1, '2025-07-10 01:25:45', '2025-07-10 01:25:45', NULL),
(14, 14, 'BEKISAR - Arek Suroboyo', 'Himpunan Mahasiswa STIS Daerah Jawa Timur', 'Jawa Timur terkenal dengan rujak cingur pedas, rawon hitam gurih, dan semangat arek-arek Suroboyo yang berani. Tari remo dan boneka Suro & Boyo jadi ikon kota.', 'Patung Suro dan Boyo simbol keberanian Surabaya|Tugu Pahlawan jadi monumen pertempuran heroik|Rawon pakai kluwek yang unik|Rujak cingur pakai hidung sapi sungguhan|Festival Reog Ponorogo mendunia', 'budaya', 1, '2025-07-10 01:25:45', '2025-07-10 01:25:45', NULL),
(15, 15, 'IMSAK - Anak Borneo', 'Ikatan Mahasiswa STIS Asal Kalimantan', 'Kalimantan punya hutan hujan tropis, sungai-sungai besar, rumah panjang Dayak, dan tari datun julud yang elok. Amplang ikan jadi camilan khas di sini.', 'Ada sungai terpanjang Indonesia: Kapuas|Orangutan Kalimantan salah satu spesies langka|Rumah panjang Dayak bisa dihuni puluhan keluarga|Pasar terapung jadi tempat jual beli di sungai|Soto Banjar punya aroma rempah khas', 'budaya', 1, '2025-07-10 01:25:45', '2025-07-10 01:25:45', NULL),
(16, 16, 'IMASSI - Sulawesi Hebat', 'Ikatan Mahasiswa Statistik Sulawesi', 'Sulawesi menyuguhkan kekayaan budaya Bugis-Makassar dengan coto, konro, rumah tongkonan, dan tari pakarena. Lautnya juga terkenal dengan terumbu karang Taman Bunaken.', 'Rumah Tongkonan dihiasi tanduk kerbau|Tari Pakarena melambangkan keanggunan perempuan|Taman Laut Bunaken surga penyelam dunia|Konro biasanya disajikan dengan iga besar|Tradisi Pinisi kapal layar masih hidup di Bulukumba', 'budaya', 1, '2025-07-10 01:25:45', '2025-07-10 01:25:45', NULL),
(17, 17, 'BALISTIS - Pulau Dewata', 'Himpunan Mahasiswa STIS Daerah Bali', 'Bali dengan pura yang megah, tari kecak, ayam betutu, dan upacara keagamaan selalu memikat hati. Suasana pulau yang damai membuat siapa saja ingin kembali.', 'Pura Besakih jadi pura terbesar di Bali|Tari Kecak dimainkan ratusan penari sekaligus|Pantai Kuta jadi ikon turis mancanegara|Ayam Betutu dimasak hingga 6 jam|Ada tradisi Ngaben membakar jenazah secara sakral', 'budaya', 1, '2025-07-10 01:25:45', '2025-07-10 01:25:45', NULL),
(18, 18, 'RINJANI - NTB Istimewa', 'Himpunan Mahasiswa STIS Daerah Nusa Tenggara Barat', 'NTB dikenal dengan Gunung Rinjani yang menjulang tinggi, ayam taliwang pedas, plecing kangkung, dan tradisi Bau Nyale berburu cacing laut.', 'Gunung Rinjani punya danau Segara Anak di puncaknya|Bau Nyale melibatkan ribuan orang di pantai|Plecing Kangkung dimakan dengan sambal super pedas|Suku Sasak terkenal ramah|Taliwang disajikan dengan ayam bakar renyah', 'budaya', 1, '2025-07-10 01:25:45', '2025-07-10 01:25:45', NULL),
(19, 19, 'IMF - Flobamora Eksotis', 'Ikatan Mahasiswa FLOBAMORA Politeknik Statistika STIS', 'Nusa Tenggara Timur dengan tenun ikat berwarna-warni, jagung bose, rumah adat beratap lontar, dan tari likurai yang enerjik membuat FLOBAMORA selalu eksotis.', 'Tari Likurai dimainkan sambil memukul beduk kecil|Pulau Komodo rumah kadal terbesar di dunia|Tenun ikat punya makna filosofi mendalam|Jagung Bose jadi makanan pokok|Ada tradisi Pasola dengan kuda di Sumba', 'budaya', 1, '2025-07-10 01:25:45', '2025-07-10 01:25:45', NULL),
(20, 20, 'MPC - Pesona Timur Indonesia', 'Moluccas Papuan Community', 'Moluccas Papuan Community (MPC) adalah wadah bagi mahasiswa dari Maluku dan Papua, dua daerah paling timur Indonesia yang dikenal dengan kekayaan budaya, alam, dan sejarahnya.  \r\nMaluku, dijuluki sebagai Tanah Rempah, memiliki ratusan pulau dengan pantai eksotis, musik totobuang, dan tarian sawat.  \r\nPapua, dikenal sebagai Tanah Cenderawasih, memiliki pegunungan yang tinggi, suku-suku unik dengan adat yang kental, hingga danau-danau yang memukau seperti Danau Sentani.  \r\nMPC ingin mengenalkan keindahan alam dan keberagaman budaya timur Indonesia kepada teman-teman kampus melalui kuliner seperti papeda dan ikan kuah kuning, serta tarian penuh energi seperti Yospan dan Tifa.', 'Pulau Banda di Maluku dulu jadi rebutan bangsa Eropa karena pala dan fuli|Di Papua ada festival Danau Sentani dengan perahu hias yang meriah|Maluku punya “pantai berpasir merah muda” di Pulau Saparua|Papua punya salju abadi di Puncak Cartenz, satu-satunya di Indonesia|Suku-suku di Papua ada yang masih tinggal di rumah Honai dan berburu tradisional', 'budaya', 1, '2025-07-10 01:25:45', '2025-07-10 01:25:45', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `order_number` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_amount` decimal(12,2) NOT NULL,
  `status` enum('pending','confirmed','processing','ready','completed','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `payment_status` enum('pending','paid','failed','refunded') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `catatan_umum` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `shipping_address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `order_number`, `total_amount`, `status`, `payment_status`, `catatan_umum`, `shipping_address`, `created_at`, `updated_at`) VALUES
(1, 9, 'HMJ202506266530', 75000.00, 'pending', 'pending', '', NULL, '2025-06-25 23:03:10', '2025-06-25 23:03:10'),
(2, 9, 'HMJ202507026110', 165000.00, 'pending', 'pending', 'tes', NULL, '2025-07-02 17:18:46', '2025-07-02 17:18:46'),
(3, 9, 'HMJ202507090635', 150000.00, 'pending', 'pending', '', NULL, '2025-07-09 14:47:44', '2025-07-09 14:47:44'),
(4, 9, 'HMJ202507124926', 160000.00, 'pending', 'pending', '', NULL, '2025-07-12 16:31:27', '2025-07-12 16:31:27'),
(5, 9, 'ORD687292df6b329', 250000.00, 'pending', 'pending', NULL, NULL, '2025-07-12 16:52:47', '2025-07-12 16:52:47'),
(6, 9, 'ORD68729688677db', 250000.00, 'pending', 'pending', NULL, NULL, '2025-07-12 17:08:24', '2025-07-12 17:08:24'),
(7, 9, 'HMJ202507127104', 275000.00, 'pending', 'pending', '', NULL, '2025-07-12 17:09:55', '2025-07-12 17:09:55'),
(8, 9, 'HMJ202507128347', 480000.00, 'pending', 'pending', '', NULL, '2025-07-12 17:10:06', '2025-07-12 17:10:06'),
(9, 9, 'HMJ202507126337', 40000.00, 'pending', 'pending', '', NULL, '2025-07-12 17:10:16', '2025-07-12 17:10:16'),
(10, 9, 'HMJ202507120069', 40000.00, 'pending', 'pending', '', NULL, '2025-07-12 17:11:48', '2025-07-12 17:11:48'),
(11, 9, 'HMJ202507120831', 30000.00, 'pending', 'pending', '', NULL, '2025-07-12 17:16:27', '2025-07-12 17:16:27'),
(12, 9, 'HMJ202507126151', 40000.00, 'pending', 'pending', '', NULL, '2025-07-12 17:16:41', '2025-07-12 17:16:41'),
(13, 9, 'HMJ202507129764', 40000.00, 'pending', 'pending', '', NULL, '2025-07-12 17:26:09', '2025-07-12 17:26:09'),
(14, 9, 'ORD68729b410d31f', 250000.00, 'pending', 'pending', NULL, NULL, '2025-07-12 17:28:33', '2025-07-12 17:28:33'),
(15, 9, 'ORD68729b7fa9b51', 25000.00, 'pending', 'pending', NULL, NULL, '2025-07-12 17:29:35', '2025-07-12 17:29:35'),
(16, 9, 'HMJ202507129516', 15000.00, 'pending', 'pending', '', NULL, '2025-07-12 17:51:43', '2025-07-12 17:51:43'),
(17, 9, 'ORD6872a1fb6e5e2', 30000.00, 'pending', 'pending', NULL, NULL, '2025-07-12 17:57:15', '2025-07-12 17:57:15'),
(18, 9, 'ORD6872a21a74aff', 85000.00, 'pending', 'pending', NULL, NULL, '2025-07-12 17:57:46', '2025-07-12 17:57:46');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int NOT NULL,
  `order_id` int NOT NULL,
  `himada_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `subtotal` decimal(12,2) GENERATED ALWAYS AS ((`quantity` * `price`)) STORED,
  `catatan_produk` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','confirmed','processing','ready','completed','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `himada_id`, `product_id`, `quantity`, `price`, `catatan_produk`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 3, 1, 75000.00, '', 'completed', '2025-06-25 23:03:10', '2025-06-28 06:52:37'),
(3, 3, 1, 3, 2, 75000.00, '', 'completed', '2025-07-09 14:47:44', '2025-07-09 14:54:01'),
(4, 4, 17, 30, 1, 160000.00, '', 'pending', '2025-07-12 16:31:27', '2025-07-12 16:31:27'),
(5, 5, 20, 53, 1, 250000.00, NULL, 'pending', '2025-07-12 16:52:47', '2025-07-12 16:52:47'),
(6, 6, 20, 53, 1, 250000.00, NULL, 'pending', '2025-07-12 17:08:24', '2025-07-12 17:08:24'),
(7, 7, 10, 23, 11, 25000.00, '', 'pending', '2025-07-12 17:09:55', '2025-07-12 17:09:55'),
(8, 8, 18, 31, 12, 40000.00, '', 'pending', '2025-07-12 17:10:06', '2025-07-12 17:10:06'),
(9, 9, 18, 31, 1, 40000.00, '', 'pending', '2025-07-12 17:10:16', '2025-07-12 17:10:16'),
(10, 10, 11, 44, 1, 40000.00, '', 'pending', '2025-07-12 17:11:48', '2025-07-12 17:11:48'),
(11, 11, 6, 19, 1, 30000.00, '', 'pending', '2025-07-12 17:16:27', '2025-07-12 17:16:27'),
(12, 12, 18, 31, 1, 40000.00, '', 'pending', '2025-07-12 17:16:41', '2025-07-12 17:16:41'),
(13, 13, 18, 31, 1, 40000.00, '', 'pending', '2025-07-12 17:26:09', '2025-07-12 17:26:09'),
(14, 14, 20, 53, 1, 250000.00, NULL, 'pending', '2025-07-12 17:28:33', '2025-07-12 17:28:33'),
(15, 15, 16, 49, 1, 25000.00, NULL, 'pending', '2025-07-12 17:29:35', '2025-07-12 17:29:35'),
(16, 16, 3, 16, 1, 15000.00, '', 'pending', '2025-07-12 17:51:43', '2025-07-12 17:51:43'),
(17, 17, 12, 45, 1, 30000.00, NULL, 'pending', '2025-07-12 17:57:15', '2025-07-12 17:57:15'),
(18, 18, 17, 50, 1, 85000.00, NULL, 'pending', '2025-07-12 17:57:46', '2025-07-12 17:57:46');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int NOT NULL,
  `himada_id` int NOT NULL,
  `nama_produk` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori` enum('makanan','merchandise','kaos','gantungan_kunci','oleh_oleh') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `harga` decimal(12,2) NOT NULL,
  `stok` int DEFAULT '0',
  `stok_minimum` int DEFAULT '5',
  `gambar_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_available` tinyint(1) DEFAULT '1',
  `created_by` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `himada_id`, `nama_produk`, `kategori`, `deskripsi`, `harga`, `stok`, `stok_minimum`, `gambar_url`, `is_available`, `created_by`, `created_at`, `updated_at`) VALUES
(2, 1, 'Mie Aceh Instan', 'makanan', 'Mie Aceh dengan bumbu rempah yang pedas dan gurih, siap saji dalam kemasan praktis.', 18000.00, 50, 10, '../uploads/produk/686f2842d69e8-mie aceh.jpeg', 1, 2, '2025-06-25 12:22:16', '2025-07-10 02:41:06'),
(3, 1, 'Kaos GIST Official', 'kaos', 'Kaos resmi GIST dengan desain khas Aceh dan bahan cotton combed yang nyaman.', 75000.00, 25, 5, '../uploads/produk/686f284fce793-kaos gist.jpeg', 1, 2, '2025-06-25 12:22:16', '2025-07-10 02:41:19'),
(4, 2, 'Kerupuk Sanjai Balado', 'makanan', 'Kerupuk sanjai khas Riau dengan bumbu balado pedas manis yang menggugah selera.', 25000.00, 40, 10, '../uploads/produk/686f295b925ab-kerupuk sanjai.jpeg', 1, 3, '2025-06-25 12:22:16', '2025-07-10 02:45:47'),
(5, 2, 'Bolu Kemojo Riau', 'makanan', 'Bolu kemojo khas Riau dengan tekstur lembut dan rasa pandan yang harum.', 35000.00, 20, 5, '../uploads/produk/686f29845dc48-bolu kemojo.jpeg', 1, 3, '2025-06-25 12:22:16', '2025-07-10 02:46:28'),
(6, 3, 'Rendang Bengkulu', 'makanan', 'Rendang khas Bengkulu dengan bumbu rempah yang kaya dan daging yang empuk.', 45000.00, 15, 5, '../uploads/produk/686f2b360e6c0-rendang bengkulu.jpeg', 1, 4, '2025-06-25 12:22:16', '2025-07-10 02:53:42'),
(7, 3, 'Kue Tat Bengkulu', 'makanan', 'Kue tat khas Bengkulu dengan isian kelapa dan gula merah yang manis legit.', 45000.00, 30, 10, '../uploads/produk/686f2b490b639-kue tat.jpeg', 1, 4, '2025-06-25 12:22:16', '2025-07-10 02:54:01'),
(10, 20, 'Batu  Papua', 'oleh_oleh', 'Batu hias warna-warni dari Papua.', 5000.00, 20, 5, '../uploads/produk/686f240d3525b-batupapua.jpeg', 1, 10, '2025-07-09 18:05:23', '2025-07-10 02:23:09'),
(14, 1, 'Kopi Gayo Premium', 'makanan', 'Kopi Gayo khas Aceh dengan cita rasa kuat.', 55000.00, 15, 5, '../uploads/produk/686f27bcbe93d-kopi gayo.jpeg', 1, 1, '2025-07-10 00:59:17', '2025-07-10 02:38:52'),
(15, 2, 'Kaos Riau Pride', 'kaos', 'Kaos dengan motif Melayu modern.', 75000.00, 20, 5, '../uploads/produk/686f294f1c2d2-kaos riau.jpeg', 1, 1, '2025-07-10 00:59:17', '2025-07-10 02:45:35'),
(16, 3, 'Gantungan Bunga Raflesia', 'gantungan_kunci', 'Gantungan kunci berbentuk bunga raflesia.', 15000.00, 30, 5, '../uploads/produk/686f2b296b366-ganci raflesia.jpeg', 1, 1, '2025-07-10 00:59:17', '2025-07-10 02:53:29'),
(17, 4, 'Rendang khas Padang', 'makanan', 'Rendang khas Padang, daging sapi empuk dengan bumbu rempah kaya rasa yang dimasak hingga meresap sempurna', 79000.00, 50, 5, '../uploads/produk/686f2c791d2dc-rendang padang.jpeg', 1, 1, '2025-07-10 00:59:17', '2025-07-10 02:59:05'),
(18, 5, 'Kaos Batak Heritage', 'kaos', 'Kaos dengan ornamen ulos Batak.', 100000.00, 25, 5, '../uploads/produk/686f2d5e69c7e-kaos batak.jpeg', 1, 1, '2025-07-10 00:59:17', '2025-07-10 03:02:54'),
(19, 6, 'Pempek Kapal Selam', 'makanan', 'Pempek ikan dengan cuko pedas.', 30000.00, 40, 5, '../uploads/produk/686f2e1747d6a-pempek.jpeg', 1, 1, '2025-07-10 00:59:17', '2025-07-10 03:05:59'),
(21, 8, 'Dodol Kentang', 'makanan', 'Dodol khas Jambi dengan bahan kentang.', 45000.00, 15, 5, '../uploads/produk/686f303897468-dodol kentang.jpeg', 1, 1, '2025-07-10 00:59:17', '2025-07-10 03:15:04'),
(22, 9, 'Seruit Lampung', 'makanan', 'Ikan bakar dengan sambal terasi khas Lampung.', 69000.00, 10, 5, '../uploads/produk/686f313543cbc-seruit.jpeg', 1, 1, '2025-07-10 00:59:17', '2025-07-10 03:19:17'),
(23, 10, 'Gantungan Kujang', 'gantungan_kunci', 'Kujang mini gantungan khas Sunda.', 25000.00, 60, 5, '../uploads/produk/686f317eba1bc-gantungan kujang.jpeg', 1, 1, '2025-07-10 00:59:17', '2025-07-10 03:20:30'),
(25, 12, 'Wingko Babat', 'makanan', 'Kue kelapa khas Semarang.', 20000.00, 35, 5, '../uploads/produk/686f32e1238d5-wingko babat.jpeg', 1, 1, '2025-07-10 00:59:17', '2025-07-10 03:26:25'),
(26, 13, 'Bakpia Pathok Isi Kacang', 'makanan', 'Bakpia lembut isi kacang hijau.', 43000.00, 25, 5, '../uploads/produk/686f334045c37-baakpia.jpeg', 1, 1, '2025-07-10 00:59:17', '2025-07-10 03:28:00'),
(27, 14, 'Rujak Cingur Surabaya', 'makanan', 'Rujak sayur dengan bumbu petis.', 29000.00, 18, 5, '../uploads/produk/686f33916a957-cingur.jpeg', 1, 1, '2025-07-10 00:59:17', '2025-07-10 03:29:21'),
(30, 17, 'Ayam Betutu Bali', 'makanan', 'Ayam bumbu rempah khas Bali.', 160000.00, 14, 5, '../uploads/produk/686f34ff54050-betutu.jpeg', 1, 1, '2025-07-10 00:59:17', '2025-07-10 03:35:27'),
(31, 18, 'Plecing Kangkung NTB', 'makanan', 'Sayuran segar dengan sambal pedas.', 40000.00, 12, 5, '../uploads/produk/686f3555760cb-plecing.jpeg', 1, 1, '2025-07-10 00:59:17', '2025-07-10 03:36:53'),
(32, 19, 'Kopi Bajawa', 'makanan', 'Kopi Bajawa, kopi Arabika khas Flores dengan cita rasa lembut, sedikit fruity, dan aroma yang memikat', 40000.00, 28, 5, '../uploads/produk/686f3a41bae30-bajawa.jpeg', 1, 1, '2025-07-10 00:59:17', '2025-07-10 03:57:53'),
(33, 20, 'Gantungan kunci khas Papua', 'gantungan_kunci', 'Gantungan kunci melambangkan keindahan budaya dan alam Papua.', 5000.00, 40, 5, '../uploads/produk/686f25244dc3a-ganci papua.jpg', 1, 1, '2025-07-10 00:59:17', '2025-07-10 02:27:48'),
(35, 2, 'Gantungan Kunci Melayu', 'gantungan_kunci', 'Gantungan kayu dengan ukiran Melayu.', 10000.00, 25, 5, '../uploads/produk/686f293fe49ba-ganci melayu.jpeg', 1, 1, '2025-07-10 01:00:11', '2025-07-10 02:45:19'),
(36, 3, 'Kaos Raflesia Bloom', 'kaos', 'Kaos dengan print bunga raflesia besar.', 85000.00, 18, 5, '../uploads/produk/686f2b1c035b9-kaos raflesia.jpeg', 1, 1, '2025-07-10 01:00:11', '2025-07-10 02:53:16'),
(37, 4, 'Bolu Batik', 'makanan', 'Bolu batik, kue lembut dengan motif batik yang cantik, memadukan rasa manis dan seni dalam setiap potongannya', 55000.00, 30, 5, '../uploads/produk/686f2c1487fbe-bolu batik.jpeg', 1, 1, '2025-07-10 01:00:11', '2025-07-10 02:57:24'),
(38, 5, 'Saksang Batak Frozen', 'makanan', 'Saksang khas Batak siap masak.', 50000.00, 11, 5, '../uploads/produk/686f2d0de8f06-saksang frozen.jpeg', 1, 1, '2025-07-10 01:00:11', '2025-07-12 16:46:16'),
(40, 7, 'Kaos Bangka Belitung', 'kaos', 'Kaos motif timah dan pantai.', 85000.00, 22, 5, '../uploads/produk/686f2f75d8e1e-kaos bangka belitung.jpeg', 1, 1, '2025-07-10 01:00:11', '2025-07-10 03:11:49'),
(41, 8, 'Oleh-oleh Dodol Jambi', 'oleh_oleh', 'Dodol nanas khas oleh-oleh Jambi.', 40000.00, 35, 5, '../uploads/produk/686f3026b9d74-dodol jambi.jpeg', 1, 1, '2025-07-10 01:00:11', '2025-07-10 03:14:46'),
(44, 11, 'Ondel-ondel Mini', 'merchandise', 'Patung mini ondel-ondel warna-warni.', 40000.00, 15, 5, '../uploads/produk/686f321d08a97-ondel mini.jpeg', 1, 1, '2025-07-10 01:00:11', '2025-07-10 03:23:09'),
(45, 12, 'Gantungan Wayang Kulit', 'gantungan_kunci', 'Gantungan wayang berbahan kulit.', 30000.00, 27, 5, '../uploads/produk/686f32c757c54-wayang.jpeg', 1, 1, '2025-07-10 01:00:11', '2025-07-12 17:57:15'),
(48, 15, 'Oleh-oleh Amplang', 'oleh_oleh', 'Kue kering amplang ikan.', 37000.00, 40, 5, '../uploads/produk/686f33dca6b30-amplang.jpeg', 1, 1, '2025-07-10 01:00:11', '2025-07-10 03:30:36'),
(49, 16, 'Gantungan Toraja', 'gantungan_kunci', 'Gantungan ukir khas Toraja.', 25000.00, 43, 5, '../uploads/produk/686f34558f293-toraja.jpeg', 1, 1, '2025-07-10 01:00:11', '2025-07-12 17:29:35'),
(50, 17, 'Bali Tote Bag', 'merchandise', 'Totebag kanvas Bali motif lawar.', 85000.00, 29, 5, '../uploads/produk/686f34e6d00d9-totebag.jpeg', 1, 1, '2025-07-10 01:00:11', '2025-07-12 17:57:46'),
(53, 20, 'Kain Tenun khas Ternate', 'oleh_oleh', 'Kain tenun khas Ternate dengan motif tradisional yang anggun, mencerminkan kekayaan budaya Maluku Utara.', 250000.00, 47, 5, '../uploads/produk/686f260a82390-tenun ternate.jpg', 1, 1, '2025-07-10 01:00:11', '2025-07-12 17:28:33');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` int NOT NULL,
  `setting_key` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `setting_value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `kelas` varchar(20) DEFAULT NULL,
  `nim` varchar(20) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `role` enum('super_admin','himada_admin','user') DEFAULT 'user',
  `himada_id` int DEFAULT NULL,
  `email_verified` tinyint(1) DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `full_name`, `kelas`, `nim`, `phone`, `role`, `himada_id`, `email_verified`, `is_active`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@stis.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Super Administrator', 'ADMIN', NULL, NULL, 'super_admin', NULL, 1, 1, '2025-07-10 03:08:59', '2025-06-25 12:22:16', '2025-07-10 03:08:59'),
(2, 'admin_gist', 'admin.gist@stis.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin GIST', 'ADMIN', '', '', 'himada_admin', 1, 1, 1, '2025-07-12 18:16:45', '2025-06-25 12:22:16', '2025-07-12 18:16:45'),
(3, 'admin_himari', 'admin.himari@stis.ac.id', '$2y$10$XLom4y/9dra.rTRd9yMoVeXD50NTgu9nVcD1qHDp.f..t4bgjN90K', 'Admin HIMARI', 'ADMIN', '', '', 'himada_admin', 2, 1, 1, '2025-07-10 02:42:19', '2025-06-25 12:22:16', '2025-07-10 02:42:19'),
(4, 'admin_himamira', 'admin.himamira@stis.ac.id', '$2y$10$upmhoi7ocYDT3Qjjz5BkoOg3bTCKW3f2b21id8Nc4OE/tAkj4DssG', 'Admin HIMAMIRA', 'ADMIN', '', '', 'himada_admin', 3, 1, 1, '2025-07-10 02:47:11', '2025-06-25 12:22:16', '2025-07-10 02:47:11'),
(5, 'john_doe', 'john.doe@stis.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'John Doe', '3SI1', '222110001', NULL, 'user', 1, 1, 1, NULL, '2025-06-25 12:22:16', '2025-06-25 12:22:16'),
(6, 'jane_smith', 'jane.smith@stis.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Jane Smith', '3SI2', '222110002', NULL, 'user', 2, 1, 1, NULL, '2025-06-25 12:22:16', '2025-06-25 12:22:16'),
(7, 'ahmad_rizki', 'ahmad.rizki@stis.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Ahmad Rizki', '2ST1', '222110003', NULL, 'user', 3, 1, 1, NULL, '2025-06-25 12:22:16', '2025-06-25 12:22:16'),
(8, 'siti_nurhaliza', 'siti.nurhaliza@stis.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Siti Nurhaliza', '2ST2', '222110004', NULL, 'user', 4, 1, 1, NULL, '2025-06-25 12:22:16', '2025-06-25 12:22:16'),
(9, 'mahasiswa', 'mahasiswa@stis.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Mahasiswa Test', '2KS2', '222110005', NULL, 'user', 1, 1, 1, '2025-07-17 09:58:42', '2025-06-25 12:22:16', '2025-07-17 09:58:42'),
(10, 'admin_mpc', 'admin.mpc@stis.ac.id', '$2y$10$tXWvaM.JhjVsR5R7eTzdmuOg7Mulo32IpbgKqn9DFe33Hj/HJ5VT2', 'Admin MPC', 'ADMIN', '', '', 'himada_admin', 20, 1, 1, '2025-07-10 02:21:32', '2025-07-09 17:36:33', '2025-07-10 02:21:32'),
(11, 'admin_imassu', 'admin.imassu@stis.ac.id', '$2y$10$VfLbKIa5r8XiAmV9SK6vJeoUbrx6XYLg74EwcOUNXI4e7PIJQgt2e', 'Admin IMASSU', 'ADMIN', '', '', 'himada_admin', 5, 1, 1, '2025-07-10 02:59:26', '2025-07-10 00:15:33', '2025-07-10 02:59:26'),
(12, 'admin_ikmm', 'admin.ikmm@stis.ac.id', '$2y$10$hVvhM0CI2t7ZPraO7XAfge0GIISfWWDH5E33BU4O6iPTkiG1IJY1y', 'Admin IKMM', 'ADMIN', '', '', 'himada_admin', 4, 1, 1, '2025-07-10 02:54:35', '2025-07-10 00:16:38', '2025-07-10 02:54:35'),
(13, 'admin_ks3', 'admin.ks3@stis.ac.id', '$2y$10$M.3Ifx9k.h6SrR5AdD2A5.AuSzEF2vXC/peYfxZYz1df.UDZMdxh6', 'Admin KS3', 'ADMIN', '', '', 'himada_admin', 7, 1, 1, '2025-07-10 03:09:48', '2025-07-10 00:18:59', '2025-07-10 03:09:48'),
(14, 'admin_sms', 'admin.sms@stis.ac.id', '$2y$10$fk22aO0NfKbuPnihGsHMjuWpATo1J0piCyLIrHkHIsKjVLmMX9sou', 'Admin SMS', 'ADMIN', '', '', 'himada_admin', 8, 1, 1, '2025-07-10 03:13:11', '2025-07-10 00:20:18', '2025-07-10 03:13:11'),
(15, 'admin_kemass', 'admin.kemass@stis.ac.id', '$2y$10$Ee4mX9VwkvwRHTxZi6JJCu7SnW1BGbG8W.TVXhQfI0L8ADpI5vbsG', 'Admin KEMASS', 'ADMIN', '', '', 'himada_admin', 6, 1, 1, '2025-07-10 03:06:19', '2025-07-10 00:21:29', '2025-07-10 03:06:19'),
(16, 'admin_saburai', 'admin.saburai@stis.ac.id', '$2y$10$YrBwP/siCJqwEXgvuXlge.R2x.svZyT3DO9rVuS/RLNpyiTpqEoNi', 'Admin SABURAI', 'ADMIN', '', '', 'himada_admin', 9, 1, 1, '2025-07-10 03:15:25', '2025-07-10 00:24:36', '2025-07-10 03:15:25'),
(17, 'admin_mavias', 'admin.mavias@stis.ac.id', '$2y$10$mWMUX6VZ/uxF8U4xzo0G7uLrrs/yDEBHaqIw6Ib5fhyyNr/U89mb.', 'Admin MAVIAS', 'ADMIN', '', '', 'himada_admin', 11, 1, 1, '2025-07-10 03:20:45', '2025-07-10 00:25:23', '2025-07-10 03:20:45'),
(18, 'admin_kajaba', 'admin.kajaba@stis.ac.id', '$2y$10$ZbM.dB1tbwz0UYEhOXtH/evEn3FCyMBoNFx3Z/QpMisGjhkeP5TVC', 'Admin KAJABA', 'ADMIN', '', '', 'himada_admin', 10, 1, 1, '2025-07-10 03:19:38', '2025-07-10 00:26:12', '2025-07-10 03:19:38'),
(19, 'admin_jatengstis', 'admin.jatengstis@stis.ac.id', '$2y$10$vXkhw.8d/wldiACcbshgvunbS8hHRFo12j6Zl.8tlJ1pRi.pHh8MS', 'Admin JATENGSTIS', 'ADMIN', '', '', 'himada_admin', 12, 1, 1, '2025-07-10 03:23:29', '2025-07-10 00:26:52', '2025-07-10 03:23:29'),
(20, 'admin_kbmsy', 'admin.kbmsy@stis.ac.id', '$2y$10$8PUEmGNAVMhzkLe65Ac8b.1Wk/E9DjZYBwnbWrSK0zTlbZvprX062', 'Admin KBMSY', 'ADMIN', '', '', 'himada_admin', 13, 1, 1, '2025-07-10 03:26:50', '2025-07-10 00:27:28', '2025-07-10 03:26:50'),
(21, 'admin_bekisar', 'admin.bekisar@stis.ac.id', '$2y$10$zF3UoyVblf2xpGSHpV.2LuNS4C5KeNr/POJOxEapntDa3VfL3MsL6', 'Admin BEKISAR', 'ADMIN', '', '', 'himada_admin', 14, 1, 1, '2025-07-10 03:28:23', '2025-07-10 00:28:19', '2025-07-10 03:28:23'),
(22, 'admin_balistis', 'admin.balistis@stis.ac.id', '$2y$10$Qfj/kNUlRw8F7nXOg2X/JOr8nzcvF1K8p59IX45xYJGQ9CejDkL0a', 'Admin BALISTIS', 'ADMIN', '', '', 'himada_admin', 17, 1, 1, '2025-07-12 18:23:50', '2025-07-10 00:29:04', '2025-07-12 18:23:50'),
(23, 'admin_rinjani', 'admin.rinjani@stis.ac.id', '$2y$10$nqrxpQ79C4wNCNEL4iOng.Y.Y9BZ1FgSyS/Q7fg38MHG1S5hKyLYK', 'Admin RINJANI', 'ADMIN', '', '', 'himada_admin', 18, 1, 1, '2025-07-10 03:35:48', '2025-07-10 00:29:46', '2025-07-10 03:35:48'),
(24, 'admin_imsak', 'admin.imsak@stis.ac.id', '$2y$10$ZSOAxO1z0bsrqBuu6x2KoOIhQgzuBPTXi05lxx2TfsKqlModjEa5.', 'Admin IMSAK', 'ADMIN', '', '', 'himada_admin', 15, 1, 1, '2025-07-10 03:29:45', '2025-07-10 00:30:28', '2025-07-10 03:29:45'),
(25, 'admin_imassi', 'admin.imassi@stis.ac.id', '$2y$10$Uh3OiiFG7idz.l.om4qPcOhsEusXEFAQ6CEyotOaRuP7XWs/lgkdC', 'Admin IMASSI', 'ADMIN', '', '', 'himada_admin', 16, 1, 1, '2025-07-10 03:31:23', '2025-07-10 00:31:08', '2025-07-10 03:31:23'),
(26, 'admin_imf', 'admin.imf@stis.ac.id', '$2y$10$AxZKAi5H5SUqdLdy5JBgi.I4VZzzXuzWZzrBj3KcUDa66nWe2a5sC', 'Admin IMF', 'ADMIN', '', '', 'himada_admin', 19, 1, 1, '2025-07-10 03:37:14', '2025-07-10 00:31:47', '2025-07-10 03:37:14');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_activity_user` (`user_id`),
  ADD KEY `idx_activity_action` (`action`),
  ADD KEY `idx_activity_date` (`created_at`);

--
-- Indexes for table `himada`
--
ALTER TABLE `himada`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `himada_history`
--
ALTER TABLE `himada_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `himada_id` (`himada_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_number` (`order_number`),
  ADD KEY `idx_orders_user` (`user_id`),
  ADD KEY `idx_orders_status` (`status`),
  ADD KEY `idx_orders_number` (`order_number`),
  ADD KEY `idx_orders_date` (`created_at`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_order_items_order` (`order_id`),
  ADD KEY `idx_order_items_himada` (`himada_id`),
  ADD KEY `idx_order_items_product` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `idx_products_himada` (`himada_id`),
  ADD KEY `idx_products_available` (`is_available`),
  ADD KEY `idx_products_kategori` (`kategori`),
  ADD KEY `idx_products_search` (`nama_produk`,`kategori`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `himada_id` (`himada_id`),
  ADD KEY `idx_users_email` (`email`),
  ADD KEY `idx_users_username` (`username`),
  ADD KEY `idx_users_role` (`role`),
  ADD KEY `idx_users_login` (`email`,`password`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=360;

--
-- AUTO_INCREMENT for table `himada`
--
ALTER TABLE `himada`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `himada_history`
--
ALTER TABLE `himada_history`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `himada_history`
--
ALTER TABLE `himada_history`
  ADD CONSTRAINT `himada_history_ibfk_1` FOREIGN KEY (`himada_id`) REFERENCES `himada` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`himada_id`) REFERENCES `himada` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_3` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`himada_id`) REFERENCES `himada` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`himada_id`) REFERENCES `himada` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
