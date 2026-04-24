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
-- Table structure for table `sd_doc_list`
--

CREATE TABLE `sd_doc_list` (
  `ID` int(11) NOT NULL,
  `doc_title` varchar(1000) NOT NULL,
  `doc_type` int(11) NOT NULL,
  `doc_main_cat` varchar(250) DEFAULT NULL,
  `doc_loc` varchar(255) DEFAULT NULL,
  `wo_no` varchar(255) DEFAULT NULL,
  `wo_value` varchar(255) DEFAULT NULL,
  `wo_name` varchar(255) DEFAULT NULL,
  `wo_type` varchar(255) DEFAULT NULL,
  `wo_start_from` date DEFAULT NULL,
  `wo_start_to` date DEFAULT NULL,
  `contractor_name` varchar(255) DEFAULT NULL,
  `work_type` varchar(255) DEFAULT NULL,
  `doc_status` int(11) NOT NULL DEFAULT 5,
  `app_id` int(11) NOT NULL,
  `app_time` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `app_remarks` varchar(250) DEFAULT NULL,
  `admin_id` int(11) NOT NULL DEFAULT 0,
  `admin_time` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `admin_remarks` varchar(250) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_time` datetime NOT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `last_modified_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `entering_keyword` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `sd_doc_list`
--

INSERT INTO `sd_doc_list` (`ID`, `doc_title`, `doc_type`, `doc_main_cat`, `doc_loc`, `wo_no`, `wo_value`, `wo_name`, `wo_type`, `wo_start_from`, `wo_start_to`, `contractor_name`, `work_type`, `doc_status`, `app_id`, `app_time`, `app_remarks`, `admin_id`, `admin_time`, `admin_remarks`, `created_by`, `created_time`, `updated_by`, `last_modified_time`, `entering_keyword`) VALUES
(2, 'Preparation of Sodium Bicarbonate', 2, '1', 'file.pdf', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 10, 0, '2024-01-01 10:09:07', NULL, 0, '2024-01-01 10:09:07', NULL, 1, '2024-01-01 10:09:07', NULL, '2024-01-01 10:09:07', ''),
(8, 'Guidelines of RCL', 10, '1', 'file.pdf', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 10, 0, '2024-01-06 08:33:09', NULL, 0, '2024-01-06 08:33:09', NULL, 1, '2024-01-06 08:33:09', NULL, '2024-01-06 08:33:09', ''),
(10, 'Production of Hydro Nitrate', 10, '1', 'file.pdf', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 10, 0, '2024-01-06 09:34:44', NULL, 0, '2024-01-06 09:34:44', NULL, 1, '2024-01-06 09:34:44', NULL, '2024-01-06 09:34:44', ''),
(12, 'Celebration tools', 4, '2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 10, 0, '2024-01-06 12:27:42', NULL, 0, '2024-01-06 12:27:42', NULL, 1, '2024-01-06 12:27:42', NULL, '2024-01-06 12:27:42', ''),
(27, 'Four-Wheel Vehicle Production', 4, '2', 'file.pdf', '1234567', '561314', 'Production  Work', 'Production', '2024-02-16', '2024-02-10', NULL, NULL, 10, 0, '2024-02-05 06:57:01', NULL, 0, '2024-02-05 06:57:01', NULL, 1, '2024-02-05 06:57:01', NULL, '2024-02-05 06:57:01', ''),
(34, 'test', 22, '6', 'file.pdf', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 10, 0, '2025-07-28 06:43:38', NULL, 0, '2025-07-28 06:43:38', NULL, 60, '2025-07-28 06:43:38', NULL, '2025-07-28 06:43:38', '635'),
(35, 'test new', 22, '6', 'file.pdf', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 10, 0, '2025-07-28 06:44:19', NULL, 0, '2025-07-28 06:44:19', NULL, 60, '2025-07-28 06:44:19', NULL, '2025-07-28 06:44:19', '37453'),
(36, 'test for new', 9, '1', 'file.pdf', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 10, 0, '2025-07-29 05:23:59', NULL, 0, '2025-07-29 05:23:59', NULL, 1, '2025-07-29 05:23:58', NULL, '2025-07-29 05:23:59', '546343');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sd_doc_list`
--
ALTER TABLE `sd_doc_list`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sd_doc_list`
--
ALTER TABLE `sd_doc_list`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
