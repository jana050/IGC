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
-- Table structure for table `sd_acv_shutdown`
--

CREATE TABLE `sd_acv_shutdown` (
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
-- Dumping data for table `sd_acv_shutdown`
--

INSERT INTO `sd_acv_shutdown` (`ID`, `from_date`, `to_date`, `from_time`, `to_time`, `description`, `location`, `sd_mt_userdb_id`, `admin_id`, `admin_time`, `admin_remarks`, `created_time`, `status`, `last_modified_by`, `last_modified_remarks`, `last_modified_time`, `hos_id`, `hos_remarks`, `hos_time`, `hod_id`, `hod_remarks`, `hod_time`) VALUES
(2, '2025-05-15', '2025-05-23', '06:30:00', '11:30:00', 'testq', 'file.pdf', 1, 0, '2025-05-16 04:57:25', NULL, '2025-05-16 04:57:25.000000', 15, NULL, NULL, '2025-05-16 04:57:25', NULL, NULL, '2025-05-16 04:57:25', NULL, NULL, '2025-05-16 04:57:25'),
(3, '2025-05-17', '2025-05-17', '01:30:00', '07:30:00', 'Today power Shutdown', 'file.pdf', 79, 0, '2025-05-17 05:44:04', NULL, '2025-05-17 05:17:17.000000', 35, NULL, NULL, '2025-05-17 05:44:04', 25, 'ok test', '2025-05-17 05:44:04', 60, 'test ok', NULL),
(5, '2025-05-21', '2025-05-23', '01:30:00', '01:30:00', 'ACV Shutdown Request', 'file.pdf', 109, 0, '2025-05-19 11:11:51', NULL, '2025-05-19 11:08:32.000000', 35, NULL, NULL, '2025-05-19 11:11:51', 68, 'approved by hos', '2025-05-19 11:11:51', 60, 'approved by hod', NULL),
(6, '2025-05-22', '2025-05-31', '03:30:00', '05:00:00', 'ACV Shutdown Requisition on may 22', 'file.pdf', 109, 0, '2025-05-22 04:53:42', NULL, '2025-05-22 04:52:17.000000', 35, NULL, NULL, '2025-05-22 04:53:42', 68, 'approved by hos', '2025-05-22 04:53:42', 60, 'approved by hod', NULL),
(7, '2025-05-31', '2025-05-31', '01:30:00', '02:30:00', 'Ok ADD', 'file.pdf', 112, 0, '2025-05-22 17:44:33', NULL, '2025-05-22 17:44:33.000000', 15, NULL, NULL, '2025-05-22 17:44:33', NULL, NULL, '2025-05-22 17:44:33', NULL, NULL, '2025-05-22 17:44:33'),
(8, '2025-07-31', '2025-07-31', '01:30:00', '03:30:00', 'test', 'file.pdf', 60, 0, '2025-07-09 06:27:13', NULL, '2025-07-09 06:27:13.000000', 15, NULL, NULL, '2025-07-09 06:27:13', NULL, NULL, '2025-07-09 06:27:13', NULL, NULL, '2025-07-09 06:27:13'),
(9, '2025-07-09', '2025-07-09', '22:30:00', '22:30:00', 'ACV Shutdown Requisition test now', 'file.pdf', 68, 0, '2025-07-09 06:45:28', NULL, '2025-07-09 06:45:28.000000', 15, NULL, NULL, '2025-07-09 06:45:28', NULL, NULL, '2025-07-09 06:45:28', NULL, NULL, '2025-07-09 06:45:28'),
(10, '2025-07-11', '2025-07-11', '07:30:00', '17:30:00', 'test', 'file.pdf', 68, 0, '2025-07-10 08:41:51', NULL, '2025-07-10 08:41:51.000000', 15, NULL, NULL, '2025-07-10 08:41:51', NULL, NULL, '2025-07-10 08:41:51', NULL, NULL, '2025-07-10 08:41:51'),
(11, '2025-07-12', '2025-07-12', '05:30:00', '19:30:00', 'test for now', 'file.pdf', 25, 0, '2025-07-11 06:32:15', NULL, '2025-07-11 06:32:15.000000', 15, NULL, NULL, '2025-07-11 06:32:15', NULL, NULL, '2025-07-11 06:32:15', NULL, NULL, '2025-07-11 06:32:15'),
(12, '2025-07-29', '2025-07-29', '07:30:00', '17:30:00', 'test', 'file.pdf', 68, 0, '2025-07-28 11:16:34', NULL, '2025-07-28 11:15:57.000000', 35, NULL, NULL, '2025-07-28 11:16:34', 68, 'test', '2025-07-28 11:16:34', 60, 'teset new', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sd_acv_shutdown`
--
ALTER TABLE `sd_acv_shutdown`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sd_acv_shutdown`
--
ALTER TABLE `sd_acv_shutdown`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
