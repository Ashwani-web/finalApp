-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 03, 2019 at 11:09 AM
-- Server version: 5.7.27-0ubuntu0.16.04.1
-- PHP Version: 7.0.33-10+ubuntu16.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `myApp`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`, `icon`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'Work', 'test', 'work', '2019-04-16 07:37:15', '2019-04-16 07:37:15'),
(2, 'Family', 'test', 'family', '2019-04-16 07:37:15', '2019-04-16 07:37:15'),
(3, 'Friends', 'test', 'friends', '2019-04-16 07:37:15', '2019-04-16 07:37:15'),
(4, 'My Time', 'test', 'my-time', '2019-04-16 07:37:15', '2019-04-16 07:37:15');

-- --------------------------------------------------------

--
-- Table structure for table `forgot_password`
--

CREATE TABLE `forgot_password` (
  `id` int(10) UNSIGNED NOT NULL,
  `code` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `forgot_password`
--

INSERT INTO `forgot_password` (`id`, `code`, `user_id`, `created_at`, `updated_at`) VALUES
(32, '$2y$10$qspRrWvE8Eb6HK0OY4oEeOeEJiLd0PpJIhhSNUpyl4lTSla8vEhUq', 78, '2019-05-13 08:05:06', '2019-05-13 08:05:06');

-- --------------------------------------------------------

--
-- Table structure for table `goal`
--

CREATE TABLE `goal` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `cat_id` int(10) UNSIGNED NOT NULL,
  `total_seconds` int(10) UNSIGNED NOT NULL,
  `is_active` tinyint(4) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `goal`
--

INSERT INTO `goal` (`id`, `user_id`, `cat_id`, `total_seconds`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 82, 3, 5559, 1, '2019-04-24 23:28:20', '2019-04-24 23:28:20'),
(2, 82, 1, 5500, 1, '2019-04-24 23:28:41', '2019-04-24 23:28:41'),
(3, 82, 2, 3333, 1, '2019-04-24 23:28:47', '2019-04-24 23:28:47'),
(5, 82, 4, 5555, 1, '2019-04-27 02:33:12', '2019-04-27 02:33:12'),
(7, 94, 3, 1000, 1, '2019-05-09 00:06:20', '2019-05-09 00:06:20');

-- --------------------------------------------------------

--
-- Table structure for table `goal_status`
--

CREATE TABLE `goal_status` (
  `id` int(10) UNSIGNED NOT NULL,
  `cat_id` int(10) UNSIGNED NOT NULL,
  `sub_cat_id` int(10) UNSIGNED DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `start_time` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `end_time` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_seconds` int(10) UNSIGNED NOT NULL,
  `allocated_st` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `goal_status`
--

INSERT INTO `goal_status` (`id`, `cat_id`, `sub_cat_id`, `user_id`, `start_time`, `end_time`, `total_seconds`, `allocated_st`, `note`, `created_at`, `updated_at`) VALUES
(1, 3, 11, 0, '1558601608', '3333', 5555, '1111', NULL, '2019-04-22 01:29:43', '2019-04-22 01:29:43'),
(3, 3, 1, 82, '1558601608', '1558605208', 500, '1111', NULL, '2019-04-22 08:00:58', '2019-05-03 01:56:27'),
(4, 3, 11, 82, '3232', '223', 5555, '1111', NULL, '2019-04-22 23:33:50', '2019-04-22 23:33:50'),
(6, 2, 7, 82, '8888', '1000', 500, '11', NULL, '2019-05-03 01:31:16', '2019-05-03 02:00:50'),
(8, 2, 28, 82, '3232', '33', 22, '993990', NULL, '2019-05-03 04:12:54', '2019-05-03 04:12:54'),
(9, 1, 25, 82, '3232', '33', 22, '5559', NULL, '2019-05-03 04:18:23', '2019-05-03 04:18:23'),
(10, 3, 0, 97, '3232', '33', 22, '0', NULL, '2019-05-07 07:43:32', '2019-05-07 07:43:32'),
(11, 3, 0, 97, '3232', '33', 22, '0', NULL, '2019-05-07 07:46:55', '2019-05-07 07:46:55'),
(12, 3, 0, 94, '111111', '2222', 22, '0', NULL, '2019-05-08 23:59:49', '2019-05-08 23:59:49'),
(15, 1, 2, 82, '111111', '2222', 86000, '0', NULL, '2019-05-21 23:42:40', '2019-05-21 23:42:40');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(58, '2014_10_12_000000_create_users_table', 1),
(59, '2014_10_12_100000_create_password_resets_table', 1),
(60, '2019_03_04_124830_create_social_credentials_table', 1),
(61, '2019_03_28_114834_create_forgot_password_table', 2),
(72, '2019_03_28_122558_create_goal_status_table', 8),
(73, '2019_04_12_133525_create_goal_table', 8),
(74, '2019_04_19_112052_create_task_table', 8),
(75, '2019_04_22_120013_create_subjective_goal_table', 9),
(76, '2019_03_28_115330_create_category_table', 10),
(77, '2019_03_28_115346_create_sub_category_table', 10);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`email`, `token`, `created_at`) VALUES
('ashwani.enthuons@gmail.com', '$2y$10$pIFUNQMhVf7JtnQ.5cmHH.NwtI0M6Rs1BQmeBMMIRVlQ3yJ7kg0y6', '2019-04-03 05:34:18');

-- --------------------------------------------------------

--
-- Table structure for table `social_credentials`
--

CREATE TABLE `social_credentials` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `platform` enum('facebook','google','linkedIn','inApp') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `social_id` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `social_token` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subjective_goal`
--

CREATE TABLE `subjective_goal` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subjective_goal`
--

INSERT INTO `subjective_goal` (`id`, `user_id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 82, 'test', 'sdfsdfsdf', '2019-04-22 07:16:32', '2019-04-22 07:16:32'),
(2, 82, 'test', 'sdfsdfsdf', '2019-04-22 07:27:40', '2019-04-22 07:27:40'),
(3, 82, 'test', 'test', '2019-04-22 07:37:11', '2019-04-22 07:37:11'),
(4, 82, 'test', 'test', '2019-04-22 23:31:17', '2019-04-22 23:31:17'),
(5, 82, 'Ž', 'ŽŽŽŽ', '2019-05-29 23:40:46', '2019-05-29 23:40:46'),
(6, 82, 'Ž', 'ŽŽŽŽ', '2019-05-29 23:43:25', '2019-05-29 23:43:25'),
(7, 82, 'Ž', 'ŽŽŽŽ', '2019-05-29 23:46:00', '2019-05-29 23:46:00');

-- --------------------------------------------------------

--
-- Table structure for table `sub_category`
--

CREATE TABLE `sub_category` (
  `id` int(10) UNSIGNED NOT NULL,
  `fk_cat_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `flag` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sub_category`
--

INSERT INTO `sub_category` (`id`, `fk_cat_id`, `name`, `slug`, `icon`, `user_id`, `flag`, `created_at`, `updated_at`) VALUES
(1, 1, 'Run', 'Run', 'test', 6, 0, '2019-04-16 07:27:28', '2019-04-16 07:27:28'),
(3, 1, 'Running', 'Running', 'test', 6, 1, '2019-04-16 07:47:06', '2019-04-16 07:47:06'),
(4, 1, 'Other', 'Other', 'test', 52, 0, '2019-04-17 07:06:51', '2019-04-17 07:06:51'),
(5, 1, 'Run', 'Run', 'test', 52, 1, '2019-04-17 07:11:07', '2019-04-17 07:11:07'),
(6, 1, 'Run', 'Run', 'test', 52, 0, '2019-04-17 07:12:14', '2019-04-17 07:12:14'),
(7, 2, 'Sleep', 'sleep', 'test', 82, 0, '2019-04-17 07:13:46', '2019-04-17 07:13:46'),
(8, 3, 'Talking', 'talking', 'test', 82, 0, '2019-04-17 07:18:43', '2019-04-17 07:18:43'),
(9, 4, 'Run', 'Run', 'test', 82, 0, '2019-04-17 07:19:17', '2019-04-17 07:19:17'),
(10, 4, 'fish jag', 'fish jag', 'test', 52, 0, '2019-04-17 23:46:27', '2019-04-17 23:46:27'),
(11, 4, 'queer are', 'queer are', 'test', 52, 0, '2019-04-17 23:51:03', '2019-04-17 23:51:03'),
(12, 2, 'sleeping', 'sleeping', 'dsd', 0, 0, '2019-04-30 06:35:25', '2019-04-30 06:35:25'),
(26, 3, 'RunandW', 'runandw', 'test', 82, 0, '2019-05-01 01:00:04', '2019-05-01 01:00:04'),
(27, 3, 'RunandWalk', 'runandwalk', 'test', 82, 0, '2019-05-01 01:00:24', '2019-05-01 01:00:24'),
(29, 3, 'Enth', 'enth', 'test', 82, 0, '2019-05-10 04:57:30', '2019-05-10 04:57:30');

-- --------------------------------------------------------

--
-- Table structure for table `task`
--

CREATE TABLE `task` (
  `id` int(10) UNSIGNED NOT NULL,
  `cat_id` int(10) UNSIGNED NOT NULL,
  `sub_cat_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `total_seconds` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `task`
--

INSERT INTO `task` (`id`, `cat_id`, `sub_cat_id`, `user_id`, `total_seconds`, `created_at`, `updated_at`) VALUES
(1, 1, 6, 82, 555, '2019-04-24 23:30:07', '2019-04-24 23:30:07'),
(2, 3, 8, 82, 5555210, '2019-04-24 23:31:05', '2019-04-24 23:31:05'),
(3, 2, 7, 82, 5555, '2019-04-25 00:11:36', '2019-04-25 00:11:36'),
(5, 4, 8, 82, 99990, '2019-04-27 02:32:01', '2019-04-27 02:32:01'),
(6, 3, 19, 82, 99990, '2019-04-30 07:36:53', '2019-04-30 07:36:53'),
(7, 3, 27, 82, 993990, '2019-05-01 01:00:24', '2019-05-01 01:00:24'),
(8, 3, 28, 82, 993990, '2019-05-01 01:01:57', '2019-05-01 01:01:57'),
(10, 3, 29, 82, 666666, '2019-05-10 04:59:03', '2019-05-10 04:59:03');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `first_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` enum('male','female') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `facebook_token` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `google_token` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `linkedIn_token` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user_image` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_type` enum('facebook','linkedIn','inApp') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `verified` tinyint(1) NOT NULL DEFAULT '0',
  `remember_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `appToken` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `user_name`, `gender`, `email`, `country`, `city`, `password`, `facebook_token`, `google_token`, `linkedIn_token`, `last_login`, `user_image`, `image_type`, `verified`, `remember_token`, `created_at`, `updated_at`, `appToken`) VALUES
(1, 'Rahul111', 'Rai', 'rahu@gmaili.com', 'male', 'rahu@gmaili.com', NULL, NULL, '$2y$10$/V6.Q/GOUm5DHSZA1vt3q.TisYHXTY3Y8UqLet50NU/V4gHNdkhhu', NULL, NULL, NULL, '2019-04-09 07:32:14', 'https://www.pexels.com/photo/nature-red-love-romantic-67636/', 'inApp', 0, NULL, '2019-03-06 01:15:40', '2019-04-09 02:02:14', ''),
(78, 'Akm', 'Kumar', 'ashwani.enthuons@gmail.com', 'male', 'ashwani.enthuons@gmail.com', 'IN', 'noida', '$2y$10$vl/Lw7RJ1UgXTUIJnV5NEOsW0kCMpenLjRjcXxV/faXVFgitordXK', NULL, NULL, NULL, '2019-05-13 10:11:21', 'http://timetable.codesk.work/images/1557228369download.jpeg', 'inApp', 0, NULL, '2019-04-06 00:10:07', '2019-05-13 04:41:21', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xOTIuMTY4LjAuMjU6ODAwMFwvYXBpXC9sb2dpbiIsImlhdCI6MTU1Nzc0MjI4MSwiZXhwIjoxNTU3NzQ1ODgxLCJuYmYiOjE1NTc3NDIyODEsImp0aSI6IlJOeXRaTWplbktDeGhIaFIiLCJzdWIiOjc4LCJwcnYiOiI4N2UwYWYxZWY5ZmQxNTgxMmZkZWM5NzE1M2ExNGUwYjA0NzU0NmFhIn0.LQRoPc9p9nLbo7TCu7D2ZNJ_eSz0dkZGSs4IE7SwanU'),
(82, 'Ashwani', 'kumar', 'ashwani2@gmail.com', 'male', 'ashwani2@gmail.com', 'IN', 'noida', '$2y$10$bVWnSuBjWUDRTnHFPC/tm.ye2L4Mkh/EVZB2OIZm6JosRS004F0YO', NULL, NULL, NULL, '2019-06-22 11:43:37', NULL, 'inApp', 0, NULL, '2019-04-16 02:08:12', '2019-06-22 06:13:37', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xOTIuMTY4LjAuMjU6ODAwMFwvYXBpXC9sb2dpbiIsImlhdCI6MTU2MTIwMzgxNywiZXhwIjoxNTYxMjA3NDE3LCJuYmYiOjE1NjEyMDM4MTcsImp0aSI6InJXMEZhQVZjc05BOWlmNjYiLCJzdWIiOjgyLCJwcnYiOiI4N2UwYWYxZWY5ZmQxNTgxMmZkZWM5NzE1M2ExNGUwYjA0NzU0NmFhIn0.qcZxAB0pZZjkAU45b7stMStKQDTEmYic-EeLS7AzwYk'),
(83, 'Tkm', 'km', 'ashwani3@gmail.com', 'male', 'ashwani3@gmail.com', 'IN', 'noida', '$2y$10$FPTdBoKdmvd4QNpQTV3rJu5Ix8Z0fXhz4XUMixhZhlPPHd4iA1OPe', NULL, NULL, NULL, '2019-04-16 07:38:26', NULL, 'inApp', 0, NULL, '2019-04-16 02:08:26', '2019-04-16 02:08:26', ''),
(84, 'Tkm', 'km', 'ashwani4@gmail.com', 'male', 'ashwani4@gmail.com', 'IN', 'noida', '$2y$10$ptM4.UH2amAtng7ocmGmPuRfiGb17KMuAnXxtMdc381/eV4B/sfye', NULL, NULL, NULL, '2019-04-22 04:56:12', NULL, 'inApp', 0, NULL, '2019-04-21 23:26:12', '2019-04-21 23:26:12', ''),
(85, 'Tkmm', 'km', 'ashwani5@gmail.com', 'male', 'ashwani5@gmail.com', 'IN', 'noida', '$2y$10$7I.eAR0ab5jp0NpEDDzxIu/HcZnHmlC/nLUDiaNNIQ5lqaov3erP2', NULL, NULL, NULL, '2019-05-07 04:50:45', NULL, 'inApp', 0, NULL, '2019-05-06 23:20:45', '2019-05-06 23:20:45', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xOTIuMTY4LjAuMjU6ODAwMFwvYXBpXC9yZWdpc3RlciIsImlhdCI6MTU1NzIwNDY0NSwiZXhwIjoxNTU3MjA4MjQ1LCJuYmYiOjE1NTcyMDQ2NDUsImp0aSI6IlRHY3dJeW5YNlpoN3ZKa20iLCJzdWIiOm51bGwsInBydiI6Ijg3ZTBhZjFlZjlmZDE1ODEyZmRlYzk3MTUzYTE0ZTBiMDQ3NTQ2YWEifQ.8DWlbFlFqM8gK2jq6_kUrBXvM4OfWWCJuzSokmXHLNs'),
(86, 'Tkmm', 'km', 'ashwani52@gmail.com', 'male', 'ashwani52@gmail.com', 'IN', 'noida', '$2y$10$udZmUoMml6vHxQEILDtKn.adag6VrpoiC5Bjntp602rM8NfWH.Gym', NULL, NULL, NULL, '2019-05-07 04:53:05', NULL, 'inApp', 0, NULL, '2019-05-06 23:23:05', '2019-05-06 23:23:05', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xOTIuMTY4LjAuMjU6ODAwMFwvYXBpXC9yZWdpc3RlciIsImlhdCI6MTU1NzIwNDc4NSwiZXhwIjoxNTU3MjA4Mzg1LCJuYmYiOjE1NTcyMDQ3ODUsImp0aSI6IndOZ0FqeW5aMzVqZzhTN08iLCJzdWIiOm51bGwsInBydiI6Ijg3ZTBhZjFlZjlmZDE1ODEyZmRlYzk3MTUzYTE0ZTBiMDQ3NTQ2YWEifQ.2idh-C3NYNbSROarPygJDZ2tHXMmoAFF-1VbRuwvVPg'),
(87, 'Tkmm', 'km', 'ashwani53@gmail.com', 'male', 'ashwani53@gmail.com', 'IN', 'noida', '$2y$10$.sXwWQJHgU2gBn3x54rUzOsHbCYqpEA35XFlL0GHhrYCFwlWKXeSO', NULL, NULL, NULL, '2019-05-07 06:56:04', NULL, 'inApp', 0, NULL, '2019-05-07 01:26:04', '2019-05-07 01:26:04', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xOTIuMTY4LjAuMjU6ODAwMFwvYXBpXC9yZWdpc3RlciIsImlhdCI6MTU1NzIxMjE2NCwiZXhwIjoxNTU3MjE1NzY0LCJuYmYiOjE1NTcyMTIxNjQsImp0aSI6ImNXU01XSjljUmpsZGpscVgiLCJzdWIiOm51bGwsInBydiI6Ijg3ZTBhZjFlZjlmZDE1ODEyZmRlYzk3MTUzYTE0ZTBiMDQ3NTQ2YWEifQ.H_1BTNtOc4EopaCmOFKiv6kjy7s3TYt9T17TmKjg6HY'),
(88, 'Tkmm', 'km', 'ashwani54@gmail.com', 'male', 'ashwani54@gmail.com', 'IN', NULL, '$2y$10$0/bhazNDkEXKQKPBqcozNOhfQKIkMroYORdDhuT2O12W8bnER9KJW', NULL, NULL, NULL, '2019-05-07 07:04:49', NULL, 'inApp', 0, NULL, '2019-05-07 01:34:49', '2019-05-07 01:34:49', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xOTIuMTY4LjAuMjU6ODAwMFwvYXBpXC9yZWdpc3RlciIsImlhdCI6MTU1NzIxMjY4OSwiZXhwIjoxNTU3MjE2Mjg5LCJuYmYiOjE1NTcyMTI2ODksImp0aSI6InFSSGdNTmpSbWR1R0M1eDUiLCJzdWIiOm51bGwsInBydiI6Ijg3ZTBhZjFlZjlmZDE1ODEyZmRlYzk3MTUzYTE0ZTBiMDQ3NTQ2YWEifQ.s9VyZdBdqQ9lZCLUiTtV06Mf8FC1b7M8G4GBOJ7o0Ug'),
(89, 'Tkmm', 'km', 'ashwani55@gmail.com', 'male', 'ashwani55@gmail.com', 'IN', NULL, '$2y$10$lwaZ68MNULzANhNXYGdB3uXPy.zHq1yBc/zr6lAQwqityuqE4XUaa', NULL, NULL, NULL, '2019-05-07 07:09:58', NULL, 'inApp', 0, NULL, '2019-05-07 01:39:58', '2019-05-07 01:39:58', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xOTIuMTY4LjAuMjU6ODAwMFwvYXBpXC9yZWdpc3RlciIsImlhdCI6MTU1NzIxMjk5OCwiZXhwIjoxNTU3MjE2NTk4LCJuYmYiOjE1NTcyMTI5OTgsImp0aSI6Inc1UnhoS1BDSHBjVjhuUGEiLCJzdWIiOm51bGwsInBydiI6Ijg3ZTBhZjFlZjlmZDE1ODEyZmRlYzk3MTUzYTE0ZTBiMDQ3NTQ2YWEifQ.EeVRREPcjsZBLhkeRlU0xjPmTcVGW8itkoBGKWjCwC4'),
(90, 'Tkmm', 'km', 'ashwani56@gmail.com', 'male', 'ashwani56@gmail.com', 'IN', 'Delhi', '$2y$10$kZNu/ASyDyqAuuVAQFZ4eORCok5N7uUYXMq/sOOg4.kt3pmCTZDgW', NULL, NULL, NULL, '2019-05-07 07:34:28', NULL, 'inApp', 0, 'zLaevhjfWn3YskCwwzEgsCQykbQnc4cN4BDrjMw7S8Ie3JJmmDKFQXrYahe9', '2019-05-07 01:46:31', '2019-05-07 02:04:11', ''),
(93, 'Tkm', 'km', 'ashwani8@gmail.com', 'male', 'ashwani8@gmail.com', 'IN', 'noida', '$2y$10$5c7jTBexbeP0M39CbTnlxulS.eF9USOzKoGP66ibsAQeFtlnQJ3WG', NULL, NULL, NULL, '2019-05-07 10:29:13', NULL, 'inApp', 0, 'YKyRsClBaxQpBXLW33HKUifj67jdkaFhD7eSjy0IfnpIyH8qMII1y34HcrEk', '2019-05-07 04:56:39', '2019-05-07 04:56:39', ''),
(94, 'Tkm hhhh', 'km hhh', 'ashwani9@gmail.com', 'male', 'ashwani9@gmail.com', 'IN', 'noida', '$2y$10$9UG1aJYlFbRl8eCuXD2N.eR3QIlNuat92cnN/XXI8kNjl16cj3pgq', NULL, NULL, NULL, '2019-05-09 07:26:35', NULL, 'inApp', 0, NULL, '2019-05-07 07:07:25', '2019-05-09 01:56:35', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xOTIuMTY4LjAuMjU6ODAwMFwvYXBpXC9sb2dpbiIsImlhdCI6MTU1NzM4Njc5NSwiZXhwIjoxNTU3MzkwMzk1LCJuYmYiOjE1NTczODY3OTUsImp0aSI6InVvNElLVzVwSUZINEg4VksiLCJzdWIiOjk0LCJwcnYiOiI4N2UwYWYxZWY5ZmQxNTgxMmZkZWM5NzE1M2ExNGUwYjA0NzU0NmFhIn0.Q534m_et44ryUeAjHSLmy5loWiy3TziK4asrkDeFzwo'),
(95, 'Tkm mm', 'km', 'ashwani10@gmail.com', 'male', 'ashwani10@gmail.com', 'IN', 'noida', '$2y$10$lLHgqz6ZtfYgtFS2YStm3eroqhcfX1AfFfQ3.WOgxfqe1hnEsAEhm', NULL, NULL, NULL, '2019-05-07 12:38:00', NULL, 'inApp', 0, NULL, '2019-05-07 07:08:00', '2019-05-07 07:08:00', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xOTIuMTY4LjAuMjU6ODAwMFwvYXBpXC9yZWdpc3RlciIsImlhdCI6MTU1NzIzMjY4MCwiZXhwIjoxNTU3MjM2MjgwLCJuYmYiOjE1NTcyMzI2ODAsImp0aSI6IjExc2tvWWU4QUJIZDFyenkiLCJzdWIiOjk1LCJwcnYiOiI4N2UwYWYxZWY5ZmQxNTgxMmZkZWM5NzE1M2ExNGUwYjA0NzU0NmFhIn0._0L1NUMgcUF4ZdC7QLuWMlGinqxHvYAbnJAkMMVydbE'),
(96, '111', 'k m', 'ashwani11@gmail.com', 'male', 'ashwani11@gmail.com', 'IN', 'noida', '$2y$10$0HGCfG/MVhiULdaKjf4MQOzeFa.3LcMdapwNiIKM3g21WErlUWVM2', NULL, NULL, NULL, '2019-05-07 12:50:58', NULL, 'inApp', 0, NULL, '2019-05-07 07:20:58', '2019-05-07 07:20:58', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xOTIuMTY4LjAuMjU6ODAwMFwvYXBpXC9yZWdpc3RlciIsImlhdCI6MTU1NzIzMzQ1OCwiZXhwIjoxNTU3MjM3MDU4LCJuYmYiOjE1NTcyMzM0NTgsImp0aSI6IkpTQVZEOXpFSDRhbWY3SDgiLCJzdWIiOjk2LCJwcnYiOiI4N2UwYWYxZWY5ZmQxNTgxMmZkZWM5NzE1M2ExNGUwYjA0NzU0NmFhIn0.DOHGZuvzYB1ireUdiLQ1yPlS4v6pSi6giGPSO2Wrpjo'),
(97, 'qw', 'km', 'ashwani12@gmail.com', 'male', 'ashwani12@gmail.com', 'IN', 'noida', '$2y$10$QYDYfPsUj0q7jrQJM0Y9bukCITsF848.ZMQiACsV.m2PCGMtuBcn2', NULL, NULL, NULL, '2019-05-07 13:01:03', NULL, 'inApp', 0, NULL, '2019-05-07 07:31:02', '2019-05-07 07:31:02', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xOTIuMTY4LjAuMjU6ODAwMFwvYXBpXC9yZWdpc3RlciIsImlhdCI6MTU1NzIzNDA2MywiZXhwIjoxNTU3MjM3NjYzLCJuYmYiOjE1NTcyMzQwNjMsImp0aSI6Ijl0dXJlVEx6WTVCSnl2TWQiLCJzdWIiOjk3LCJwcnYiOiI4N2UwYWYxZWY5ZmQxNTgxMmZkZWM5NzE1M2ExNGUwYjA0NzU0NmFhIn0.7wM6AKfmMzDV21TTHkG0scx127d8A8VXO6MWfMlDU0Q');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `forgot_password`
--
ALTER TABLE `forgot_password`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `goal`
--
ALTER TABLE `goal`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `goal_status`
--
ALTER TABLE `goal_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `social_credentials`
--
ALTER TABLE `social_credentials`
  ADD KEY `social_credentials_user_id_foreign` (`user_id`);

--
-- Indexes for table `subjective_goal`
--
ALTER TABLE `subjective_goal`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sub_category`
--
ALTER TABLE `sub_category`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sub_category_fk_cat_id_foreign` (`fk_cat_id`);

--
-- Indexes for table `task`
--
ALTER TABLE `task`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_user_name_unique` (`user_name`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `forgot_password`
--
ALTER TABLE `forgot_password`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
--
-- AUTO_INCREMENT for table `goal`
--
ALTER TABLE `goal`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `goal_status`
--
ALTER TABLE `goal_status`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;
--
-- AUTO_INCREMENT for table `subjective_goal`
--
ALTER TABLE `subjective_goal`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `sub_category`
--
ALTER TABLE `sub_category`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
--
-- AUTO_INCREMENT for table `task`
--
ALTER TABLE `task`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `social_credentials`
--
ALTER TABLE `social_credentials`
  ADD CONSTRAINT `social_credentials_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sub_category`
--
ALTER TABLE `sub_category`
  ADD CONSTRAINT `sub_category_fk_cat_id_foreign` FOREIGN KEY (`fk_cat_id`) REFERENCES `category` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
