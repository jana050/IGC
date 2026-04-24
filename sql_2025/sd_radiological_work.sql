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
-- Table structure for table `sd_radiological_work`
--

CREATE TABLE `sd_radiological_work` (
  `ID` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `nature_of_work` varchar(255) DEFAULT NULL,
  `signature_medical_officer` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL,
  `sd_mt_userdb_id` int(100) DEFAULT NULL,
  `created_time` datetime NOT NULL DEFAULT current_timestamp(),
  `last_modified_by` varchar(255) DEFAULT NULL,
  `last_modified_time` datetime NOT NULL DEFAULT current_timestamp(),
  `app_id` int(11) DEFAULT 0,
  `app_time` datetime DEFAULT current_timestamp(),
  `app_remarks` varchar(255) DEFAULT NULL,
  `admin_id` int(11) DEFAULT 0,
  `admin_time` datetime DEFAULT current_timestamp(),
  `last_modified_remarks` text DEFAULT NULL,
  `job_description` varchar(255) DEFAULT NULL,
  `rubber_instruction` text DEFAULT NULL,
  `cover_all` enum('Yes','No') DEFAULT NULL,
  `rubber_glove` enum('Yes','No') DEFAULT NULL,
  `dosimeter` enum('Yes','No') DEFAULT NULL,
  `complete_dress` enum('Yes','No') DEFAULT NULL,
  `surgical_gloves` enum('Yes','No') DEFAULT NULL,
  `respirator` enum('Yes','No') DEFAULT NULL,
  `over_shoes` enum('Yes','No') DEFAULT NULL,
  `dust_gas_mask` enum('Yes','No') DEFAULT NULL,
  `plastic_suit` enum('Yes','No') DEFAULT NULL,
  `change_of_clothes` enum('Yes','No') DEFAULT NULL,
  `air_activity` text DEFAULT NULL,
  `airline_breathing_apparatus` enum('Yes','No') DEFAULT NULL,
  `rubber_station` enum('Yes','No') DEFAULT NULL,
  `single_rubber_area` enum('Yes','No') DEFAULT NULL,
  `double_rubber_area` enum('Yes','No') DEFAULT NULL,
  `shower_after_work` enum('Yes','No') DEFAULT NULL,
  `admin_remarks` varchar(255) DEFAULT NULL,
  `lab_incharge_id` int(11) DEFAULT NULL,
  `lab_incharge_remarks` text DEFAULT NULL,
  `lab_incharge_time` datetime DEFAULT NULL,
  `hp_id` int(11) DEFAULT NULL,
  `hp_remarks` text DEFAULT NULL,
  `hp_time` datetime DEFAULT NULL,
  `rwp_no` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sd_radiological_work`
--

INSERT INTO `sd_radiological_work` (`ID`, `name`, `nature_of_work`, `signature_medical_officer`, `status`, `sd_mt_userdb_id`, `created_time`, `last_modified_by`, `last_modified_time`, `app_id`, `app_time`, `app_remarks`, `admin_id`, `admin_time`, `last_modified_remarks`, `job_description`, `rubber_instruction`, `cover_all`, `rubber_glove`, `dosimeter`, `complete_dress`, `surgical_gloves`, `respirator`, `over_shoes`, `dust_gas_mask`, `plastic_suit`, `change_of_clothes`, `air_activity`, `airline_breathing_apparatus`, `rubber_station`, `single_rubber_area`, `double_rubber_area`, `shower_after_work`, `admin_remarks`, `lab_incharge_id`, `lab_incharge_remarks`, `lab_incharge_time`, `hp_id`, `hp_remarks`, `hp_time`, `rwp_no`) VALUES
(7, 'Radiology', 'Nature of Work', 'test', 15, 60, '2025-05-15 11:18:51', NULL, '2025-05-15 11:18:51', 0, '2025-05-15 11:18:51', NULL, 0, '2025-05-15 11:18:51', NULL, 'new work', 'Rubber station', 'Yes', 'No', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'No', 'No', 'Yes', 'air', 'No', 'No', 'Yes', 'Yes', 'Yes', NULL, 110, '', NULL, NULL, '', '2025-05-19 15:10:39', ''),
(8, 'Radiology', 'Nature of Work', 'test', 10, 60, '2025-05-15 11:22:27', NULL, '2025-05-15 11:22:27', 0, '2025-05-15 11:22:27', NULL, 0, '2025-05-15 11:22:27', NULL, 'new work', 'Rubber station', 'Yes', 'No', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'No', 'No', 'Yes', 'air', 'No', 'No', 'Yes', 'Yes', 'Yes', NULL, NULL, NULL, '2025-05-19 15:10:00', NULL, '', '2025-05-19 15:10:39', ''),
(10, 'Jana', 'RCl New work permit', 'ok', 10, 60, '2025-05-15 11:37:40', NULL, '2025-05-15 11:37:40', 0, '2025-05-15 11:37:40', NULL, 0, '2025-05-15 11:37:40', NULL, 'test', 'test', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'No', 'Yes', 'Yes', 'air', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', NULL, NULL, NULL, '2025-05-19 15:10:00', NULL, '', '2025-05-19 15:10:39', ''),
(11, 'S.Parameswari', 'inspection and tightening of pipe joints', 's.parameswari', 20, 109, '2025-05-19 11:34:36', NULL, '2025-05-19 15:04:36', 0, '2025-05-19 15:04:36', NULL, 0, '2025-05-19 15:04:36', NULL, 'Heat exchange room -valve replacement work', 'for test', 'Yes', 'Yes', 'No', 'Yes', 'Yes', 'No', 'No', 'Yes', 'Yes', 'No', '5 dac', 'No', 'Yes', 'Yes', 'Yes', 'Yes', NULL, 1, '', '2025-05-19 15:19:30', 1, '', '0000-00-00 00:00:00', ''),
(13, 's.parameswari', 'Radiology Form New', 's.parameswari', 20, 109, '2025-05-22 06:11:08', NULL, '2025-05-22 06:11:08', 0, '2025-05-22 06:11:08', NULL, 0, '2025-05-22 06:11:08', NULL, 'anupuram', 'test', 'Yes', 'No', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'test', 'No', 'Yes', 'Yes', 'No', 'No', NULL, 110, '', NULL, 110, '', NULL, ''),
(14, 'Udayakumar', 'detector maintenance', 'Ajoy', 20, 112, '2025-05-23 10:04:57', NULL, '2025-05-23 10:04:57', 0, '2025-05-23 10:04:57', NULL, 0, '2025-05-23 10:04:57', NULL, 'H-1, detector maintenance', 'nil', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'No', 'Yes', 'Yes', 'Yes', 'Yes', '0.5', 'Yes', 'No', 'Yes', 'Yes', 'Yes', NULL, 110, '', NULL, 110, '', NULL, ''),
(15, 's.parameswari', 'Replacement of corroded shielding components', 's.parameswari', 20, 109, '2025-07-09 06:53:19', NULL, '2025-07-09 06:53:19', 0, '2025-07-09 06:53:19', NULL, 0, '2025-07-09 06:53:19', NULL, 'Hot Lab - Area C, for repair and inspection of shielded valves', 'test', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'No', 'Yes', 'Yes', 'No', 'No', '0.3 DAC', 'Yes', 'Yes', 'No', 'Yes', 'Yes', NULL, 110, '', NULL, 110, '', NULL, ''),
(16, 'test', 'new form', NULL, 20, 1, '2025-07-22 09:15:18', NULL, '2025-07-22 09:15:18', 0, '2025-07-22 09:15:18', NULL, 0, '2025-07-22 09:15:18', NULL, 'test', 'test', 'Yes', 'No', 'Yes', 'Yes', 'No', 'No', 'No', 'Yes', 'No', 'Yes', 'test', 'No', 'Yes', 'No', 'No', 'Yes', NULL, 110, '', NULL, 110, '', NULL, ''),
(17, 'Jana', 'RCL TEXT WORK', NULL, 20, 112, '2025-07-26 11:32:26', NULL, '2025-07-26 11:32:26', 0, '2025-07-26 11:32:26', NULL, 0, '2025-07-26 11:32:26', NULL, 'Test', 'Test', 'Yes', 'Yes', 'Yes', 'No', 'Yes', 'Yes', 'Yes', 'No', 'Yes', 'No', '100', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', NULL, 110, '', NULL, 110, '', NULL, 'RWP-20250726-001'),
(18, 'paramu', 'Radiology Form on today 31 july', NULL, 20, 109, '2025-07-31 05:34:00', NULL, '2025-07-31 05:34:00', 0, '2025-07-31 05:34:00', NULL, 0, '2025-07-31 05:34:00', NULL, 'anupuram', 'test', 'Yes', 'Yes', 'No', 'Yes', 'Yes', 'Yes', 'No', 'Yes', 'Yes', 'Yes', 'normal', 'No', 'No', 'No', 'Yes', 'Yes', NULL, 110, '', NULL, 111, '', NULL, 'RWP-20250731-001');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sd_radiological_work`
--
ALTER TABLE `sd_radiological_work`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sd_radiological_work`
--
ALTER TABLE `sd_radiological_work`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
