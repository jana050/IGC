-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: bkc00oow4so4oc004g4s4o08
-- Generation Time: Aug 04, 2025 at 12:23 PM
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
-- Table structure for table `sd_network`
--

CREATE TABLE `sd_network` (
  `ID` int(11) NOT NULL,
  `title` varchar(1000) NOT NULL,
  `description` text DEFAULT NULL,
  `location` text NOT NULL,
  `sd_mt_userdb_id` int(100) NOT NULL,
  `app_id` int(11) NOT NULL DEFAULT 0,
  `app_time` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `app_remarks` varchar(255) DEFAULT NULL,
  `admin_id` int(11) NOT NULL DEFAULT 0,
  `admin_time` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `admin_remarks` varchar(255) DEFAULT NULL,
  `created_time` datetime(6) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 5,
  `last_modified_by` varchar(255) DEFAULT NULL,
  `last_modified_remarks` text DEFAULT NULL,
  `last_modified_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `date_of_closure` date DEFAULT NULL,
  `supervisor_description` longtext DEFAULT NULL,
  `supervisor_time` datetime DEFAULT NULL,
  `supervisor` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `sd_network`
--

INSERT INTO `sd_network` (`ID`, `title`, `description`, `location`, `sd_mt_userdb_id`, `app_id`, `app_time`, `app_remarks`, `admin_id`, `admin_time`, `admin_remarks`, `created_time`, `status`, `last_modified_by`, `last_modified_remarks`, `last_modified_time`, `date_of_closure`, `supervisor_description`, `supervisor_time`, `supervisor`) VALUES
(1, 'test', 'teset', 'l-6', 1, 0, '2025-05-08 07:33:38', NULL, 0, '2025-05-08 07:33:38', NULL, '2023-12-13 08:29:31.000000', 15, '1', NULL, '2025-05-08 07:33:38', NULL, NULL, '2025-05-16 10:42:33', NULL),
(2, 'test', 'sc', 'test', 1, 0, '2024-01-03 08:50:19', NULL, 0, '2024-01-03 08:50:19', NULL, '2024-01-03 08:48:47.000000', 15, '68', NULL, '2024-01-03 08:50:19', NULL, NULL, '2025-05-16 10:42:33', NULL),
(3, 'test', 'test', 'test', 1, 0, '2025-05-08 07:42:05', NULL, 0, '2025-05-08 07:42:05', NULL, '2024-01-03 08:50:49.000000', 15, '1', NULL, '2025-05-08 07:42:05', NULL, NULL, '2025-05-16 10:42:33', NULL),
(4, 'test', 'test for tamil', 'new block', 25, 0, '2025-05-09 05:52:38', NULL, 0, '2025-05-09 05:52:38', NULL, '2025-05-09 05:47:26.000000', 19, '25', NULL, '2025-05-09 05:52:38', NULL, NULL, '2025-05-16 10:42:33', NULL),
(5, 'Network Complaint on may 22', 'Network Complaint on ,may 22', '325', 109, 0, '2025-05-22 05:19:02', NULL, 0, '2025-05-22 05:19:02', 'assigned supervisor', '2025-05-22 05:17:05.000000', 15, '1', NULL, '2025-05-22 05:19:02', NULL, 'approved supervisor', NULL, 66),
(6, 'Network Complaint on july 9 2025', 'Network Complaint test', 'chennai', 109, 0, '2025-07-09 05:56:36', NULL, 0, '2025-07-09 05:56:36', 'approved admin', '2025-07-09 05:54:43.000000', 15, '1', NULL, '2025-07-09 05:56:36', NULL, 'approved supervisor', NULL, 25);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sd_network`
--
ALTER TABLE `sd_network`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sd_network`
--
ALTER TABLE `sd_network`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
