-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: bkc00oow4so4oc004g4s4o08
-- Generation Time: Aug 04, 2025 at 11:50 AM
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
-- Table structure for table `sd_work_permit`
--

CREATE TABLE `sd_work_permit` (
  `ID` int(11) NOT NULL,
  `permit_no` varchar(255) DEFAULT NULL,
  `system` varchar(255) DEFAULT NULL,
  `description_of_location` varchar(255) DEFAULT NULL,
  `team_involved` varchar(255) DEFAULT NULL,
  `shutdown_jobs` varchar(255) DEFAULT NULL,
  `ac_venitilation_type` varchar(255) DEFAULT NULL,
  `description_of_work` varchar(255) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `necessary_disconnection` varchar(255) DEFAULT NULL,
  `isolation` varchar(255) DEFAULT NULL,
  `no_of_item` varchar(255) DEFAULT NULL,
  `health_physical_instructions` varchar(255) DEFAULT NULL,
  `industrial_safety_permit` varchar(255) DEFAULT NULL,
  `welding_cutting_permit` varchar(255) DEFAULT NULL,
  `supervisor` int(11) DEFAULT NULL,
  `sd_mt_userdb_id` int(100) NOT NULL,
  `admin_id` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 5 COMMENT '	5=waiting,10=approved,4=rejected	',
  `created_time` datetime(6) DEFAULT NULL,
  `last_modified_by` varchar(255) DEFAULT NULL,
  `app_id` int(11) NOT NULL DEFAULT 0,
  `app_time` datetime NOT NULL DEFAULT current_timestamp(),
  `app_remarks` varchar(255) DEFAULT NULL,
  `admin_time` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `admin_remarks` varchar(255) DEFAULT NULL,
  `last_modified_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `supervisor_description` varchar(255) DEFAULT NULL,
  `supervisor_time` datetime DEFAULT NULL,
  `work_all_completed` varchar(255) DEFAULT NULL,
  `defect_rectified` varchar(255) DEFAULT NULL,
  `work_area_cleared` varchar(255) DEFAULT NULL,
  `tags_cleared` varchar(255) DEFAULT NULL,
  `protection_available` varchar(255) DEFAULT NULL,
  `operation_test` varchar(255) DEFAULT NULL,
  `system_return_normal` varchar(255) DEFAULT NULL,
  `comments` varchar(255) DEFAULT NULL,
  `details_work` varchar(255) DEFAULT NULL,
  `hos_remarks` varchar(255) DEFAULT NULL,
  `hos_id` int(11) DEFAULT NULL,
  `hos_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sd_work_permit`
--

INSERT INTO `sd_work_permit` (`ID`, `permit_no`, `system`, `description_of_location`, `team_involved`, `shutdown_jobs`, `ac_venitilation_type`, `description_of_work`, `start_date`, `end_date`, `necessary_disconnection`, `isolation`, `no_of_item`, `health_physical_instructions`, `industrial_safety_permit`, `welding_cutting_permit`, `supervisor`, `sd_mt_userdb_id`, `admin_id`, `status`, `created_time`, `last_modified_by`, `app_id`, `app_time`, `app_remarks`, `admin_time`, `admin_remarks`, `last_modified_time`, `supervisor_description`, `supervisor_time`, `work_all_completed`, `defect_rectified`, `work_area_cleared`, `tags_cleared`, `protection_available`, `operation_test`, `system_return_normal`, `comments`, `details_work`, `hos_remarks`, `hos_id`, `hos_time`) VALUES
(1, '4452', 'test', '0', '3452', '3423', 'etwrewt', 'test', '2025-05-14', '2025-05-31', 'rr', 'rere', NULL, 'rere', 'rer', 'rere', NULL, 1, 0, 35, '2025-05-24 00:00:00.000000', '1', 0, '2025-05-24 10:58:02', NULL, '2025-07-31 06:36:05', NULL, '2025-07-31 06:36:05', NULL, NULL, 'Yes', 'No', '0', 'Yes', 'No', 'Yes', 'Yes', NULL, NULL, 'test', 68, NULL),
(2, '6757', 'new system on may 26', '0', '435', '5', 'main', 'test', '2025-05-26', '2025-05-28', 'test', 'test', NULL, 'test', 'test', 'test', NULL, 109, 0, 35, '2025-05-26 00:00:00.000000', '1', 0, '2025-05-26 04:29:50', NULL, '2025-05-27 10:01:52', NULL, '2025-05-27 10:01:52', NULL, NULL, 'Yes', 'Yes', '0', 'Yes', 'Yes', 'Yes', 'Yes', 'test', 'yereyt', 'test', 1, NULL),
(3, 'No.234', 'TEST Systems', '0', 'M/M', 'Yes', 'Affecting', 'Ok', '2025-05-25', '2025-05-04', 'Yes', 'Yes', NULL, 'No', 'Yes', 'Yes', NULL, 112, 0, 0, '2025-05-27 00:00:00.000000', '1', 0, '2025-05-27 08:55:48', NULL, '2025-05-27 09:07:21', NULL, '2025-05-27 09:07:21', NULL, NULL, 'Yes', 'Yes', '0', 'No', 'Yes', 'No', 'Yes', 'trtrtr', 'dfhvghy', 'treew', 1, NULL),
(4, '1234', 'NEW TEST -1000', '0', 'M/M', 'Yes', 'Affecting', 'Ok', '2025-05-03', '2025-05-03', 'Yes', 'Yes', NULL, 'Yes', 'Yes', 'Yes', NULL, 112, 0, 35, '2025-05-28 00:00:00.000000', '112', 0, '2025-05-28 04:52:10', NULL, '2025-05-28 05:13:11', NULL, '2025-05-28 05:13:11', NULL, NULL, 'Yes', 'Yes', '0', 'Yes', 'Yes', 'Yes', 'Yes', 'ok', 'Ok', 'ok', 68, NULL),
(5, 'tyerye', 'test', '0', 'M/M', 'Yes', 'Affecting', 'test', '2025-07-23', '2025-07-24', 'Yes', 'Yes', NULL, 'Yes', 'Yes', 'Yes', NULL, 1, 0, 35, '2025-07-31 00:00:00.000000', '1', 0, '2025-07-31 06:26:54', NULL, '2025-07-31 06:30:11', NULL, '2025-07-31 06:30:11', NULL, NULL, 'Yes', 'Yes', '0', 'Yes', 'No', 'No', 'No', 'Comments', 'test', 'test for now', 60, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sd_work_permit`
--
ALTER TABLE `sd_work_permit`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sd_work_permit`
--
ALTER TABLE `sd_work_permit`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
