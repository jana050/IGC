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
-- Table structure for table `sd_electrical`
--

CREATE TABLE `sd_electrical` (
  `ID` int(11) NOT NULL,
  `title` varchar(1000) NOT NULL,
  `description` text DEFAULT NULL,
  `location` text NOT NULL,
  `sd_mt_userdb_id` int(100) NOT NULL,
  `app_id` int(11) NOT NULL DEFAULT 0,
  `app_time` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `app_remarks` varchar(255) DEFAULT NULL,
  `admin_id` int(11) DEFAULT 0,
  `admin_time` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `admin_remarks` varchar(255) DEFAULT NULL,
  `created_time` datetime(6) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 5 COMMENT '5=waiting,10=approved,4=rejected',
  `last_modified_by` varchar(255) DEFAULT NULL,
  `last_modified_remarks` text DEFAULT NULL,
  `last_modified_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `date_of_closure` date DEFAULT NULL,
  `supervisor_description` longtext DEFAULT NULL,
  `supervisor` varchar(255) DEFAULT NULL,
  `supervisor_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `sd_electrical`
--

INSERT INTO `sd_electrical` (`ID`, `title`, `description`, `location`, `sd_mt_userdb_id`, `app_id`, `app_time`, `app_remarks`, `admin_id`, `admin_time`, `admin_remarks`, `created_time`, `status`, `last_modified_by`, `last_modified_remarks`, `last_modified_time`, `date_of_closure`, `supervisor_description`, `supervisor`, `supervisor_time`) VALUES
(1, 'test', 'test', 'l-6', 1, 0, '2023-12-13 17:37:44', NULL, 0, '2023-12-13 17:37:44', NULL, '2023-12-13 08:28:54.000000', 15, '68', NULL, '2023-12-13 12:07:44', NULL, NULL, NULL, '2025-05-16 10:42:53'),
(2, 'sdfsdfs', 'sdfsf', '23', 68, 0, '2024-01-06 08:29:03', NULL, 0, '2024-01-06 08:29:03', NULL, '2023-12-16 07:57:43.000000', 11, '1', NULL, '2024-01-06 08:29:03', NULL, NULL, NULL, '2025-05-16 10:42:53'),
(3, 'Tube Light issue', 'Tube Light issue', '123/4', 78, 0, '2024-01-05 17:24:02', NULL, 0, '2024-01-05 17:24:02', NULL, '2023-12-25 10:30:38.000000', 11, '68', NULL, '2024-01-05 17:24:02', NULL, NULL, NULL, '2025-05-16 10:42:53'),
(4, 'dfgsdfd', 'asdfasdfasdf', 'asdfdfdfdf', 68, 0, '2024-02-16 05:40:29', NULL, 0, '2024-02-16 05:40:29', NULL, '2023-12-25 12:36:46.000000', 15, '1', NULL, '2024-02-16 05:40:29', NULL, NULL, NULL, '2025-05-16 10:42:53'),
(5, 'TEST', 'sample', 'Room: 87, Build No: A', 55, 0, '2023-12-30 08:41:04', NULL, 0, '2023-12-30 08:41:04', NULL, '2023-12-30 07:45:00.000000', 11, '1', NULL, '2023-12-30 08:41:04', NULL, NULL, NULL, '2025-05-16 10:42:53'),
(6, 'Test2', 'Sample2', 'Room No; 4, Build No: A', 55, 0, '2023-12-30 08:59:49', NULL, 0, '2023-12-30 08:59:49', NULL, '2023-12-30 08:59:49.000000', 10, NULL, NULL, '2023-12-30 08:59:49', NULL, NULL, NULL, '2025-05-16 10:42:53'),
(7, 'test', 'test', 'test', 1, 0, '2024-01-04 05:50:46', NULL, 0, '2024-01-04 05:50:46', NULL, '2024-01-04 05:41:12.000000', 11, '68', NULL, '2024-01-04 05:50:46', NULL, NULL, NULL, '2025-05-16 10:42:53'),
(8, 'TV plug issue', 'sdfsdfd', '123,sdfdsvfhjsd', 78, 0, '2024-01-05 17:23:42', NULL, 0, '2024-01-05 17:23:42', NULL, '2024-01-05 17:21:40.000000', 15, '68', NULL, '2024-01-05 17:23:42', NULL, NULL, NULL, '2025-05-16 10:42:53'),
(9, 'Light Fluctuating', 'Light blinking and the power supply is not constant', 'RCL - 2003', 1, 0, '2024-01-06 13:09:59', NULL, 0, '2024-01-06 13:09:59', 'Members not available', '2024-01-06 13:07:57.000000', 11, '68', NULL, '2024-01-06 13:09:59', NULL, NULL, NULL, '2025-05-16 10:42:53'),
(10, 'test', 'test', 'test', 1, 0, '2024-01-06 16:20:18', NULL, 0, '2024-01-06 16:20:18', 'test', '2024-01-06 16:19:39.000000', 11, '1', NULL, '2024-01-06 16:20:18', NULL, NULL, NULL, '2025-05-16 10:42:53'),
(11, 'testinng 07-0-2024', 'sdfsd', 'adas', 1, 0, '2024-01-07 01:17:41', NULL, 0, '2024-01-07 01:17:41', NULL, '2024-01-07 01:16:33.000000', 15, '1', NULL, '2024-01-07 01:17:41', NULL, NULL, NULL, '2025-05-16 10:42:53'),
(12, 'test on 08-01-2024', 'test', 'test', 1, 0, '2024-01-08 01:54:36', NULL, 0, '2024-01-08 01:54:36', NULL, '2024-01-08 01:54:36.000000', 10, NULL, NULL, '2024-01-08 01:54:36', NULL, NULL, NULL, '2025-05-16 10:42:53'),
(13, 'test', 'test', 'test', 1, 0, '2024-02-16 05:40:47', NULL, 0, '2024-02-16 05:40:47', '', '2024-01-24 06:52:44.000000', 11, '1', NULL, '2024-02-16 05:40:47', NULL, NULL, NULL, '2025-05-16 10:42:53'),
(14, 'Electrical wire shortage', 'test test test', 'RB - 29.', 1, 0, '2024-01-26 08:17:30', NULL, 0, '2024-01-26 08:17:30', NULL, '2024-01-26 08:17:30.000000', 10, NULL, NULL, '2024-01-26 08:17:30', NULL, NULL, NULL, '2025-05-16 10:42:53'),
(15, 'testnew', 'testnewcomplaint', 'testlocation', 1, 0, '2025-04-28 07:09:28', NULL, 0, '2025-04-28 07:09:28', NULL, '2025-04-28 07:09:28.000000', 10, NULL, NULL, '2025-04-28 07:09:28', NULL, NULL, NULL, '2025-05-16 10:42:53'),
(16, 'water likage', 'water likage', 'D block', 66, 0, '2025-05-13 12:04:02', NULL, 0, '2025-05-13 12:04:02', 'working on progress', '2025-05-03 07:13:32.000000', 30, '1', NULL, '2025-05-13 12:04:02', NULL, NULL, NULL, '2025-05-16 10:42:53'),
(17, 'test on 15-05-2025', 'test on 15-05-2025', 'test on 15-05-2025', 1, 0, '2025-05-16 04:45:41', NULL, 0, '2025-05-16 04:45:41', 'Please check this complaint', '2025-05-15 04:38:29.000000', 30, '1', NULL, '2025-05-16 04:45:41', NULL, NULL, '65', '2025-05-16 10:42:53'),
(18, 'test', 'test', 'test', 60, 0, '2025-05-15 10:54:44', NULL, 0, '2025-05-15 10:54:44', 'test', '2025-05-15 10:51:58.000000', 15, '60', NULL, '2025-05-15 10:54:44', NULL, NULL, '60', '2025-05-16 10:42:53'),
(19, 'test', 'test', 'test', 109, 0, '2025-05-19 10:59:12', NULL, 0, '2025-05-19 10:59:12', NULL, '2025-05-19 10:59:12.000000', 10, NULL, NULL, '2025-05-19 10:59:12', NULL, NULL, '0', '2025-05-19 10:59:12'),
(20, 'Complaint tested by sutha 21-05-2025', 'testing fvjh', '123,fdgfg', 78, 0, '2025-05-21 09:47:39', NULL, 0, '2025-05-21 09:47:39', NULL, '2025-05-21 09:47:39.000000', 10, NULL, NULL, '2025-05-21 09:47:39', NULL, NULL, '0', '2025-05-21 09:47:39'),
(21, 'Electrical Complaint New', 'Electrical Complaint New request', '65', 109, 0, '2025-05-21 11:42:55', NULL, 0, '2025-05-21 11:42:55', NULL, '2025-05-21 11:39:00.000000', 15, NULL, NULL, '2025-05-21 11:42:55', NULL, 'checked', '0', NULL),
(22, 'Fan Not working', 'Fan Not working', 'No.747,Varatharayan Flat', 112, 0, '2025-05-22 17:47:25', NULL, 0, '2025-05-22 17:47:25', 'Ok Please fix this issue', '2025-05-22 17:46:24.000000', 15, '68', NULL, '2025-05-22 17:47:25', NULL, 'Ok Closed', '60', NULL),
(23, 'l', 'L2', 'G-15', 112, 0, '2025-05-23 07:53:24', NULL, 0, '2025-05-23 07:53:24', 'WORKING', '2025-05-23 07:50:28.000000', 30, '68', NULL, '2025-05-23 07:53:24', NULL, NULL, '68', NULL),
(24, 'test 09-07-2025', 'test 09-07-2025', 'test 09-07-2025', 112, 0, '2025-07-09 05:56:21', NULL, 0, '2025-07-09 05:56:21', NULL, '2025-07-09 05:14:41.000000', 15, NULL, NULL, '2025-07-09 05:56:21', NULL, 'test', '0', NULL),
(25, 'Electrical Complaint by 9 july 2025', 'test for complaint', 'chennai', 109, 0, '2025-07-09 05:46:24', NULL, 0, '2025-07-09 05:46:24', 'approved for admin', '2025-07-09 05:19:24.000000', 15, '1', NULL, '2025-07-09 05:46:24', NULL, 'approved supervisor', '25', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sd_electrical`
--
ALTER TABLE `sd_electrical`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sd_electrical`
--
ALTER TABLE `sd_electrical`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
