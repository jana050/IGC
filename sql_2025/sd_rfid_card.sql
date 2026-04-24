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
-- Table structure for table `sd_rfid_card`
--

CREATE TABLE `sd_rfid_card` (
  `ID` int(11) NOT NULL,
  `from_date` date DEFAULT NULL,
  `to_date` date DEFAULT NULL,
  `nature_of_visitor` varchar(255) DEFAULT NULL,
  `igcar_entry_permit_no` varchar(255) DEFAULT NULL,
  `igcar_entry_date_of_validity` date DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `age` int(100) DEFAULT NULL,
  `area_of_visit` varchar(255) DEFAULT NULL,
  `intercom_no` int(100) DEFAULT NULL,
  `institute_address` varchar(255) DEFAULT NULL,
  `mobile_no` varchar(15) DEFAULT NULL,
  `rfid_req_number` varchar(1000) DEFAULT NULL,
  `sd_mt_userdb_id` int(100) NOT NULL,
  `created_time` datetime(6) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 5 COMMENT '5=waiting,10=approved,4=rejected	',
  `hos_id` int(11) NOT NULL DEFAULT 0,
  `hos_remarks` text DEFAULT NULL,
  `hos_time` datetime DEFAULT NULL,
  `hod_id` int(11) DEFAULT NULL,
  `hod_remarks` text DEFAULT NULL,
  `hod_time` datetime DEFAULT NULL,
  `supervisor` int(11) DEFAULT NULL,
  `supervisor_remarks` text DEFAULT NULL,
  `supervisor_time` datetime DEFAULT NULL,
  `card_no` varchar(255) DEFAULT NULL,
  `card_date` date DEFAULT NULL,
  `deposited_name` varchar(255) DEFAULT NULL,
  `deposited_date` date DEFAULT NULL,
  `last_modified_by` int(11) DEFAULT 0,
  `last_modified_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sd_rfid_card`
--

INSERT INTO `sd_rfid_card` (`ID`, `from_date`, `to_date`, `nature_of_visitor`, `igcar_entry_permit_no`, `igcar_entry_date_of_validity`, `name`, `gender`, `age`, `area_of_visit`, `intercom_no`, `institute_address`, `mobile_no`, `rfid_req_number`, `sd_mt_userdb_id`, `created_time`, `status`, `hos_id`, `hos_remarks`, `hos_time`, `hod_id`, `hod_remarks`, `hod_time`, `supervisor`, `supervisor_remarks`, `supervisor_time`, `card_no`, `card_date`, `deposited_name`, `deposited_date`, `last_modified_by`, `last_modified_time`) VALUES
(9, '2025-07-12', '2025-07-12', 'Project Student', '43434', '2025-07-13', 'tamizh', 'Male', 23, '36', 43553, 'test', '6537356436', 'RF-20250711-002', 1, '2025-07-11 00:00:00.000000', 30, 0, NULL, NULL, NULL, NULL, '2025-07-11 08:48:22', NULL, 'test', NULL, '536436', '2025-07-15', NULL, NULL, 1, '2025-07-11 10:25:30'),
(10, '2025-07-13', '2025-07-13', 'Guest Scientist', '354635', '2025-07-14', 'Tamizh', 'Male', 23, '536', 35653, 'test', '6434736643647', 'RF-20250711-002', 1, '2025-07-11 00:00:00.000000', 30, 0, NULL, NULL, NULL, NULL, '2025-07-11 09:57:52', NULL, 'test', NULL, '4536453', NULL, NULL, NULL, 1, '2025-07-11 09:58:18'),
(11, '2025-07-12', '2025-07-12', 'Guest Scientist', '43434', '2025-07-13', 'tamil', 'Male', 24, '36', 43553, 'test', '6537356436', 'RF-20250726-001', 1, '2025-07-26 00:00:00.000000', 5, 0, NULL, NULL, NULL, NULL, '2025-07-26 04:56:11', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-07-26 06:01:08'),
(12, '2025-07-12', '2025-07-12', 'Project Student', '43434', '2025-07-13', 'tamizh', 'Male', 23, '36', 43553, 'test', '6537356436', 'RF-20250726-002', 1, '2025-07-26 00:00:00.000000', 5, 0, NULL, NULL, NULL, NULL, '2025-07-26 05:32:26', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-26 05:32:26'),
(13, '2025-07-26', '2025-07-26', 'Guest Scientist', '86575467', '2025-07-26', 'Janarthanaa', 'Male', 26, 'RCL MFRG', 24994, 'Kanish', '8234567886', 'RF-20250726-003', 112, '2025-07-26 00:00:00.000000', 5, 0, NULL, NULL, NULL, NULL, '2025-07-26 06:07:32', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-26 06:07:32'),
(14, '2025-07-09', '2025-07-24', 'Project Student', '5462', '2025-07-24', 'tamizh', 'Male', 23, 'no.7', 543534, 'Test', '6536263275', 'RF-20250726-004', 112, '2025-07-26 00:00:00.000000', 5, 0, NULL, NULL, NULL, NULL, '2025-07-26 06:19:17', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-26 06:19:17'),
(15, '2025-07-24', '2025-07-31', 'Trade Apprentice', '6', '2025-08-01', 'Tamizh', 'Male', 23, 'test', 54653, 'test', '76745363536', 'RF-20250726-005', 111, '2025-07-26 00:00:00.000000', 30, 0, NULL, NULL, 60, 'test', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-07-26 11:05:37'),
(16, '2025-07-17', '2025-07-28', 'Project Student', '45346563', '2025-07-28', 'Test', 'Male', 34, 'test', 64573, 'test', '8763746374', 'RF-20250728-001', 111, '2025-07-28 00:00:00.000000', 15, 0, 'test', NULL, 60, 'test', NULL, NULL, 'test', NULL, '76763', NULL, NULL, NULL, 0, '2025-07-28 06:47:25'),
(17, '2025-07-25', '2025-07-28', 'Project Student', '53443', '2025-07-27', 'test new', 'Male', 54, 'test', 86536, 'tetq', '473476537', 'RF-20250728-002', 111, '2025-07-28 00:00:00.000000', 30, 0, NULL, NULL, 60, 'test', NULL, NULL, 'test', NULL, '3564374', NULL, NULL, NULL, 0, '2025-07-28 06:53:26'),
(18, '2025-07-25', '2025-07-27', 'Guest Scientist', '454365', '2025-07-28', 'test', 'Male', 24, 'test', 53542, 'test', '4223254', 'RF-20250728-003', 111, '2025-07-28 00:00:00.000000', 20, 0, NULL, NULL, 60, 'test', NULL, NULL, 'test', NULL, '43526', NULL, NULL, NULL, 0, '2025-07-28 08:47:20'),
(19, '2025-08-02', '2025-08-02', 'Trade Apprentice', '5454545', '2025-08-02', 'Jana PS', 'Male', 19, 'RCL', 5434, 'no.5 GST ROAD', '6787654765', 'RF-20250802-001', 112, '2025-08-02 00:00:00.000000', 30, 0, NULL, NULL, 60, 'test', NULL, NULL, 'ok test', NULL, '3244', NULL, NULL, NULL, 0, '2025-08-02 08:27:20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sd_rfid_card`
--
ALTER TABLE `sd_rfid_card`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sd_rfid_card`
--
ALTER TABLE `sd_rfid_card`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
