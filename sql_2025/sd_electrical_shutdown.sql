-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: bkc00oow4so4oc004g4s4o08
-- Generation Time: Aug 04, 2025 at 12:22 PM
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
-- Table structure for table `sd_electrical_shutdown`
--

CREATE TABLE `sd_electrical_shutdown` (
  `ID` int(11) NOT NULL,
  `from_date` date DEFAULT NULL,
  `to_date` date DEFAULT NULL,
  `from_time` time NOT NULL,
  `to_time` time NOT NULL,
  `description` text DEFAULT NULL,
  `location` text DEFAULT NULL,
  `sd_mt_userdb_id` int(100) NOT NULL,
  `admin_id` int(11) DEFAULT 0,
  `admin_time` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `admin_remarks` varchar(255) DEFAULT NULL,
  `created_time` datetime(6) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 5 COMMENT '5=waiting,10=approved,4=rejected',
  `last_modified_by` varchar(255) DEFAULT NULL,
  `last_modified_remarks` text DEFAULT NULL,
  `last_modified_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `hos_id` int(11) DEFAULT NULL,
  `hos_remarks` text DEFAULT NULL,
  `hos_time` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `hod_id` int(11) DEFAULT NULL,
  `hod_remarks` text DEFAULT NULL,
  `hod_time` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `sd_electrical_shutdown`
--

INSERT INTO `sd_electrical_shutdown` (`ID`, `from_date`, `to_date`, `from_time`, `to_time`, `description`, `location`, `sd_mt_userdb_id`, `admin_id`, `admin_time`, `admin_remarks`, `created_time`, `status`, `last_modified_by`, `last_modified_remarks`, `last_modified_time`, `hos_id`, `hos_remarks`, `hos_time`, `hod_id`, `hod_remarks`, `hod_time`) VALUES
(1, '2025-05-16', '2025-05-22', '07:30:00', '14:30:00', 'ELECTRICAL Shutdown Requisition Form', 'file.pdf', 1, 0, '2025-05-15 12:02:44', NULL, '2025-05-15 11:59:19.000000', 35, NULL, NULL, '2025-05-15 12:02:44', NULL, NULL, '2025-05-15 12:02:44', NULL, NULL, '2025-05-15 12:02:44'),
(2, '2025-05-24', '2025-05-31', '04:30:00', '07:30:00', 'Today power Shutdown', 'file.pdf', 79, 0, '2025-05-17 12:16:26', NULL, '2025-05-17 12:04:05.000000', 35, NULL, NULL, '2025-05-17 12:16:26', 25, 'okey', '2025-05-17 12:16:26', 60, 'ok', NULL),
(3, '2025-05-18', '2025-05-18', '03:00:00', '06:00:00', 'test', 'file.pdf', 79, 0, '2025-05-22 04:54:21', NULL, '2025-05-19 04:37:46.000000', 35, NULL, NULL, '2025-05-22 04:54:21', 25, 'TEST', '2025-05-22 04:54:21', 60, 'NEW ONE', NULL),
(5, '2025-05-22', '2025-05-22', '01:30:00', '22:30:00', 'TEST', 'file.pdf', 1, 0, '2025-05-22 04:53:21', NULL, '2025-05-22 04:53:21.000000', 15, NULL, NULL, '2025-05-22 04:53:21', NULL, NULL, '2025-05-22 04:53:21', NULL, NULL, '2025-05-22 04:53:21'),
(6, '2025-07-09', '2025-07-09', '22:30:00', '22:30:00', 'ELECTRICAL Shutdown Requisition on july 2025', 'file.pdf', 68, 0, '2025-07-09 06:25:46', NULL, '2025-07-09 06:12:08.000000', 0, '1', NULL, '2025-07-09 06:25:46', NULL, NULL, '2025-07-09 06:25:46', NULL, NULL, '2025-07-09 06:25:46'),
(7, '2025-07-31', '2025-07-31', '04:30:00', '06:30:00', 'tesr', NULL, 68, 0, '2025-07-09 06:24:26', NULL, '2025-07-09 06:24:26.000000', 15, NULL, NULL, '2025-07-09 06:24:26', NULL, NULL, '2025-07-09 06:24:26', NULL, NULL, '2025-07-09 06:24:26'),
(8, '2025-07-09', '2025-07-09', '06:30:00', '14:30:00', 'test for today', 'file.pdf', 68, 0, '2025-07-09 07:34:49', NULL, '2025-07-09 07:34:49.000000', 15, NULL, NULL, '2025-07-09 07:34:49', NULL, NULL, '2025-07-09 07:34:49', NULL, NULL, '2025-07-09 07:34:49'),
(9, '2025-07-09', '2025-07-09', '22:30:00', '22:30:00', 'test today', 'file.pdf', 68, 0, '2025-07-09 07:34:52', NULL, '2025-07-09 07:34:52.000000', 15, NULL, NULL, '2025-07-09 07:34:52', NULL, NULL, '2025-07-09 07:34:52', NULL, NULL, '2025-07-09 07:34:52'),
(10, '2025-07-12', '2025-07-12', '06:30:00', '17:30:00', 'test', 'file.pdf', 1, 0, '2025-07-11 06:43:58', NULL, '2025-07-11 06:43:58.000000', 15, NULL, NULL, '2025-07-11 06:43:58', NULL, NULL, '2025-07-11 06:43:58', NULL, NULL, '2025-07-11 06:43:58'),
(11, '2025-07-29', '2025-07-29', '05:30:00', '19:30:00', 'test', 'file.pdf', 68, 0, '2025-07-28 11:26:58', NULL, '2025-07-28 11:17:49.000000', 35, NULL, NULL, '2025-07-28 11:26:58', 68, 'test', '2025-07-28 11:26:58', 60, 'new test', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sd_electrical_shutdown`
--
ALTER TABLE `sd_electrical_shutdown`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sd_electrical_shutdown`
--
ALTER TABLE `sd_electrical_shutdown`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
