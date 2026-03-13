-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: bkc00oow4so4oc004g4s4o08
-- Generation Time: Aug 04, 2025 at 11:49 AM
-- Server version: 11.6.2-MariaDB-ubu2404
-- PHP Version: 8.3.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sds_igcdoc`
--

-- --------------------------------------------------------

--
-- Table structure for table `sd_mbook_entry`
--

CREATE TABLE `sd_mbook_entry` (
  `ID` int(11) NOT NULL,
  `sd_mbook_issue_id` int(11) NOT NULL,
  `created_time` datetime DEFAULT NULL,
  `sd_mt_userdb_id` int(100) NOT NULL,
  `app_id` int(11) NOT NULL DEFAULT 0,
  `app_time` date NOT NULL DEFAULT current_timestamp(),
  `app_remarks` varchar(255) DEFAULT NULL,
  `admin_id` int(11) NOT NULL DEFAULT 0,
  `admin_time` datetime NOT NULL DEFAULT current_timestamp(),
  `admin_remarks` varchar(255) DEFAULT NULL,
  `last_modified_by` varchar(255) DEFAULT NULL,
  `last_modified_remarks` text DEFAULT NULL,
  `last_modified_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `entry_status` int(11) NOT NULL DEFAULT 5 COMMENT '5=waiting,10=approved,4=rejected',
  `hos_id` int(11) DEFAULT NULL,
  `hos_remarks` text DEFAULT NULL,
  `hos_time` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `hod_id` int(11) DEFAULT NULL,
  `hod_remarks` text DEFAULT NULL,
  `hod_time` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ad_id` int(11) DEFAULT NULL,
  `ad_remarks` text DEFAULT NULL,
  `ad_time` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `gd_id` int(11) DEFAULT NULL,
  `gd_remarks` text DEFAULT NULL,
  `gd_time` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ra_number` varchar(255) DEFAULT NULL,
  `ra_amount` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `date_of_issue` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sd_mbook_entry`
--

INSERT INTO `sd_mbook_entry` (`ID`, `sd_mbook_issue_id`, `created_time`, `sd_mt_userdb_id`, `app_id`, `app_time`, `app_remarks`, `admin_id`, `admin_time`, `admin_remarks`, `last_modified_by`, `last_modified_remarks`, `last_modified_time`, `entry_status`, `hos_id`, `hos_remarks`, `hos_time`, `hod_id`, `hod_remarks`, `hod_time`, `ad_id`, `ad_remarks`, `ad_time`, `gd_id`, `gd_remarks`, `gd_time`, `ra_number`, `ra_amount`, `title`, `date_of_issue`) VALUES
(30, 27, '2025-05-27 05:54:20', 111, 0, '2025-05-27', NULL, 0, '2025-05-27 05:54:20', NULL, NULL, NULL, '2025-05-27 05:54:20', 35, 68, 'new check', '2025-05-27 05:56:05', 60, 'test', NULL, NULL, NULL, '2025-05-27 05:56:05', NULL, NULL, '2025-05-27 05:56:05', '65235', 46000, 'new one', '2025-05-08'),
(31, 28, '2025-05-27 06:28:08', 112, 0, '2025-05-27', NULL, 0, '2025-05-27 06:28:08', NULL, NULL, NULL, '2025-05-27 06:28:08', 35, 68, 'ok', '2025-05-27 06:43:34', 60, 'ok', NULL, NULL, NULL, '2025-05-27 06:43:34', NULL, NULL, '2025-05-27 06:43:34', 'RA-1', 25000, 'TEST RXL', '2025-05-31'),
(32, 29, '2025-05-27 08:28:54', 112, 0, '2025-05-27', NULL, 0, '2025-05-27 08:28:54', NULL, NULL, NULL, '2025-05-27 08:28:54', 35, 68, 'HOS UPDATE', NULL, NULL, NULL, '2025-05-27 08:29:22', NULL, NULL, '2025-05-27 08:29:22', NULL, NULL, '2025-05-27 08:29:22', 'RA-1', 20000, 'JARA', '2025-05-31'),
(33, 31, '2025-05-27 10:47:15', 112, 0, '2025-05-27', NULL, 0, '2025-05-27 10:47:15', NULL, NULL, NULL, '2025-05-27 10:47:15', 35, 68, 'OK HOS', '2025-05-27 10:49:20', 60, 'ok hod', NULL, NULL, NULL, '2025-05-27 10:49:20', NULL, NULL, '2025-05-27 10:49:20', 'RA-1', 10000, 'TAMIL Test', '2025-05-31'),
(34, 31, '2025-05-27 10:50:41', 112, 0, '2025-05-27', NULL, 0, '2025-05-27 10:50:41', NULL, NULL, NULL, '2025-05-27 10:50:41', 10, NULL, NULL, '2025-05-27 10:50:41', NULL, NULL, '2025-05-27 10:50:41', NULL, NULL, '2025-05-27 10:50:41', NULL, NULL, '2025-05-27 10:50:41', 'RA-2', 1234, 'TAMIL Test', '2025-05-31'),
(35, 32, '2025-05-28 04:35:29', 112, 0, '2025-05-28', NULL, 0, '2025-05-28 04:35:29', NULL, NULL, NULL, '2025-05-28 04:35:29', 35, 68, '', '2025-05-28 05:04:33', 60, '', NULL, NULL, NULL, '2025-05-28 05:04:33', NULL, NULL, '2025-05-28 05:04:33', 'RA-1', 3000, 'NEW TEST 0n 28-05-25', '2025-05-31'),
(36, 33, '2025-05-28 05:01:49', 112, 0, '2025-05-28', NULL, 0, '2025-05-28 05:01:49', NULL, NULL, NULL, '2025-05-28 05:01:49', 35, 68, 'ok hos', NULL, NULL, NULL, '2025-05-28 05:03:22', NULL, NULL, '2025-05-28 05:03:22', NULL, NULL, '2025-05-28 05:03:22', 'RA-1', 567, 'ok Test', '2025-05-31'),
(38, 32, '2025-05-28 05:06:11', 112, 0, '2025-05-28', NULL, 0, '2025-05-28 05:06:11', NULL, NULL, NULL, '2025-05-28 05:06:11', 35, 68, '', '2025-07-11 05:23:23', 60, '', NULL, NULL, NULL, '2025-07-11 05:23:23', NULL, NULL, '2025-07-11 05:23:23', 'RA-2', 188, 'NEW TEST 0n 28-05-25', '2025-05-31'),
(40, 33, '2025-05-29 06:28:31', 112, 0, '2025-05-29', NULL, 0, '2025-05-29 06:28:31', NULL, NULL, NULL, '2025-05-29 06:28:31', 35, 68, 'ok', NULL, NULL, NULL, '2025-07-11 05:21:23', NULL, NULL, '2025-07-11 05:21:23', NULL, NULL, '2025-07-11 05:21:23', 'RA-2', 123, 'ok Test', '2025-05-31'),
(41, 36, '2025-05-29 10:12:25', 68, 0, '2025-05-29', NULL, 0, '2025-05-29 10:12:25', NULL, NULL, NULL, '2025-05-29 10:12:25', 10, NULL, NULL, '2025-05-29 10:12:25', NULL, NULL, '2025-05-29 10:12:25', NULL, NULL, '2025-05-29 10:12:25', NULL, NULL, '2025-05-29 10:12:25', '5454', 5454, 'new mbook', '2025-05-22'),
(42, 32, '2025-07-11 05:24:33', 112, 0, '2025-07-11', NULL, 0, '2025-07-11 05:24:33', NULL, NULL, NULL, '2025-07-11 05:24:33', 35, 68, '', '2025-07-11 05:25:15', 60, '', NULL, NULL, NULL, '2025-07-11 05:25:15', NULL, NULL, '2025-07-11 05:25:15', 'RA-3', 100, 'NEW TEST 0n 28-05-25', '2025-05-31'),
(43, 37, '2025-07-30 10:20:55', 1, 0, '2025-07-30', NULL, 0, '2025-07-30 10:20:55', NULL, NULL, NULL, '2025-07-30 10:20:55', 10, NULL, NULL, '2025-07-30 10:20:55', NULL, NULL, '2025-07-30 10:20:55', NULL, NULL, '2025-07-30 10:20:55', NULL, NULL, '2025-07-30 10:20:55', '1', 3, 'test1 m book', '2025-07-01'),
(44, 39, '2025-07-30 10:45:32', 1, 0, '2025-07-30', NULL, 0, '2025-07-30 10:45:32', NULL, NULL, NULL, '2025-07-30 10:45:32', 10, NULL, NULL, '2025-07-30 10:45:32', NULL, NULL, '2025-07-30 10:45:32', NULL, NULL, '2025-07-30 10:45:32', NULL, NULL, '2025-07-30 10:45:32', '1', 3, 'EXAM1', '2025-07-30'),
(45, 40, '2025-07-31 06:01:31', 111, 0, '2025-07-31', NULL, 0, '2025-07-31 06:01:31', NULL, NULL, NULL, '2025-07-31 06:01:31', 35, 68, 'tst', '2025-07-31 06:02:30', 60, 'test', NULL, NULL, NULL, '2025-07-31 06:02:30', NULL, NULL, '2025-07-31 06:02:30', '3473', 25000, 'My nw Test', '2025-08-01');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sd_mbook_entry`
--
ALTER TABLE `sd_mbook_entry`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sd_mbook_entry`
--
ALTER TABLE `sd_mbook_entry`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
