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
-- Table structure for table `sd_mbook_issue`
--

CREATE TABLE `sd_mbook_issue` (
  `ID` int(11) NOT NULL,
  `mbook_number` varchar(100) NOT NULL,
  `date_of_issue` date NOT NULL,
  `title` varchar(1000) NOT NULL,
  `description` text DEFAULT NULL,
  `sd_mt_userdb_id` int(100) NOT NULL,
  `app_id` int(11) DEFAULT 0,
  `app_time` datetime DEFAULT current_timestamp(),
  `app_remarks` varchar(255) DEFAULT NULL,
  `admin_id` int(11) NOT NULL DEFAULT 0,
  `admin_time` datetime DEFAULT current_timestamp(),
  `admin_remarks` varchar(255) DEFAULT NULL,
  `created_time` datetime NOT NULL DEFAULT current_timestamp(),
  `status` int(11) NOT NULL DEFAULT 5 COMMENT '5=waiting,10=approved,4=rejected	',
  `last_modified_by` varchar(255) DEFAULT NULL,
  `last_modified_remarks` text DEFAULT NULL,
  `last_modified_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `name_of_work` text DEFAULT NULL,
  `work_order_number` varchar(255) DEFAULT NULL,
  `date_of_work_order` date DEFAULT NULL,
  `work_order_value` varchar(255) DEFAULT NULL,
  `budget_type` varchar(255) DEFAULT NULL,
  `budget_pin` varchar(255) DEFAULT NULL,
  `period` varchar(255) DEFAULT NULL,
  `contact_name` varchar(255) DEFAULT NULL,
  `technical_sanction_number` varchar(255) DEFAULT NULL,
  `file_type` text DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `others` varchar(255) DEFAULT NULL,
  `doc_loc` text DEFAULT NULL,
  `cc_number` varchar(255) DEFAULT NULL,
  `pan_number` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `mobile_no` bigint(20) DEFAULT NULL,
  `wages_count` int(11) DEFAULT NULL,
  `salary_amount` int(11) DEFAULT NULL,
  `uploaded_file` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sd_mbook_issue`
--

INSERT INTO `sd_mbook_issue` (`ID`, `mbook_number`, `date_of_issue`, `title`, `description`, `sd_mt_userdb_id`, `app_id`, `app_time`, `app_remarks`, `admin_id`, `admin_time`, `admin_remarks`, `created_time`, `status`, `last_modified_by`, `last_modified_remarks`, `last_modified_time`, `name_of_work`, `work_order_number`, `date_of_work_order`, `work_order_value`, `budget_type`, `budget_pin`, `period`, `contact_name`, `technical_sanction_number`, `file_type`, `start_date`, `end_date`, `others`, `doc_loc`, `cc_number`, `pan_number`, `email`, `mobile_no`, `wages_count`, `salary_amount`, `uploaded_file`) VALUES
(26, 'MCMFCG/CFED/2025/01', '2025-05-27', 'MBook Issue', NULL, 109, 0, '2025-05-27 05:53:38', NULL, 0, '2025-05-27 05:53:38', NULL, '2025-05-27 05:53:38', 10, NULL, NULL, '2025-05-27 05:53:38', NULL, '3456', NULL, '567', 'Revenue', '456', NULL, 'parameswari', '4657', 'NIQ', '2025-05-27', '2025-05-30', 'null', 'file.pdf', '4657', '24356', 'dfgcvg', 9876543210, 13245, 456, NULL),
(27, 'MCMFCG/CFED/2025/02', '2025-05-08', 'new one', NULL, 111, 0, '2025-05-27 05:53:44', NULL, 0, '2025-05-27 05:53:44', NULL, '2025-05-27 05:53:44', 15, '68', NULL, '2025-05-27 05:53:56', NULL, '343422', NULL, '677666', 'Capital', '764', NULL, 'Tamizh', '09728', 'NIQ', '2024-11-12', '2026-01-02', 'null', 'file.pdf', '76767', 'FETRII2254Q', 'tamil@gmail.com', 7919483748, 569, 56999, NULL),
(28, 'MCMFCG/CFED/2025/03', '2025-05-31', 'TEST RXL', NULL, 112, 0, '2025-05-27 06:25:04', NULL, 0, '2025-05-27 06:25:04', NULL, '2025-05-27 06:25:04', 15, '68', NULL, '2025-05-27 06:27:32', NULL, 'WOK-123', NULL, '26000', 'Revenue', '234', NULL, 'JANA', '345', 'NIQ', '2025-05-04', '2026-05-28', 'null', 'file.pdf', 'CC-09898', 'FDGDV765HHFH', 'test@gmail.com', 3456783456, 12, 1233, NULL),
(29, 'MCMFCG/CFED/2025/04', '2025-05-31', 'JARA', NULL, 112, 0, '2025-05-27 08:27:48', NULL, 0, '2025-05-27 08:27:48', NULL, '2025-05-27 08:27:48', 15, '68', NULL, '2025-05-27 08:28:15', NULL, 'NO.234', NULL, '26000', 'Revenue', '234', NULL, 'JANA', '3456', 'NIT', '2025-05-03', '2026-05-31', 'null', 'file.pdf', '234', 'lkjuytr4567', 'JAna@gmail.com', 234567654, 34, 654, NULL),
(30, 'MCMFCG/CFED/2025/05', '2025-05-20', 'new work order', NULL, 111, 0, '2025-05-27 10:21:39', NULL, 0, '2025-05-27 10:21:39', NULL, '2025-05-27 10:21:39', 15, '68', NULL, '2025-05-27 10:21:51', NULL, '5343', NULL, '6464373', 'Revenue', '6564', NULL, 'tamizl', '5472', 'NIQ', '2025-05-13', '2026-02-28', 'null', 'file.pdf', '635435', 'GFSEW5543S', 'test', 7674675647, 63, 50000, NULL),
(31, 'MCMFCG/CFED/2025/06', '2025-05-31', 'TAMIL Test', NULL, 112, 0, '2025-05-27 10:46:27', NULL, 0, '2025-05-27 10:46:27', NULL, '2025-05-27 10:46:27', 15, '68', NULL, '2025-05-27 10:46:37', NULL, 'edfghj', NULL, '26000', 'Revenue', '234', NULL, 'Jana', '45', 'NIT', '2025-05-02', '2025-05-31', 'null', 'file.pdf', 'sdf', 'sdfg345', 'test@gmail.com', 3456564, 34, 54, NULL),
(32, 'MCMFCG/CFED/2025/07', '2025-05-31', 'NEW TEST 0n 28-05-25', NULL, 112, 0, '2025-05-28 04:32:55', NULL, 0, '2025-05-28 04:32:55', NULL, '2025-05-28 04:32:55', 15, '68', NULL, '2025-05-28 04:33:43', NULL, 'OK', NULL, '25500', 'Capital', '4567', NULL, 'JANA', '454', 'NIT', '2025-05-03', '2025-05-02', 'null', 'file.pdf', 'CCCCC', 'rdftgyhujik345', 'Jana@gmail.com', 34567654, 43, 443, NULL),
(33, 'MCMFCG/CFED/2025/08', '2025-05-31', 'ok Test', NULL, 112, 0, '2025-05-28 04:56:38', NULL, 0, '2025-05-28 04:56:38', NULL, '2025-05-28 04:56:38', 15, '68', NULL, '2025-05-28 04:56:57', NULL, 'OK 123', NULL, '5000', 'Capital', '234', NULL, 'sdfg', '54e', 'NIQ', '2025-05-03', '2025-05-08', 'null', 'file.pdf', 'cdfvg', 'JAna', 'Jana@gmail.com', 2345654, 4, 454, NULL),
(36, 'MCMFCG/CFED/2025/11', '2025-05-22', 'new mbook', NULL, 68, 0, '2025-05-29 10:10:42', NULL, 0, '2025-05-29 10:10:42', NULL, '2025-05-29 10:10:42', 15, '68', NULL, '2025-05-29 10:12:15', NULL, '5454', NULL, '65454', 'Revenue', '434', NULL, 'tamil', '67', 'NIQ', '2025-05-07', '2025-09-13', 'null', 'file.pdf', '565', 'FDEDt2284W', 'tamil@gmail.com', 4983863747, 463, 33299, NULL),
(37, 'MCMFCG/CFED/2025/10', '2025-07-01', 'test1 m book', NULL, 1, 0, '2025-07-30 10:15:55', NULL, 0, '2025-07-30 10:15:55', NULL, '2025-07-30 10:15:55', 15, '1', NULL, '2025-07-30 10:20:26', NULL, '1100', NULL, '7.6', 'Revenue', '20002', NULL, 'Contractor 1', 'IGC/TEST', 'NIT', '2025-07-01', '2026-06-30', 'null', 'file.pdf', '0011', 'gfkjhkg145', 'test@test.com', 0, 893, 15000, NULL),
(39, 'MCMFCG/CFED/2025/11', '2025-07-30', 'EXAM1', NULL, 1, 0, '2025-07-30 10:42:45', NULL, 0, '2025-07-30 10:42:45', NULL, '2025-07-30 10:42:45', 15, '1', NULL, '2025-07-30 10:43:05', NULL, '0000', NULL, '8', 'Capital', '5555', NULL, 'exam 1', 'exam/TEST', 'NIT', '2025-07-02', '2026-07-01', 'null', 'file.pdf', '6611', 'exam1', 'eXAM@EXAM', 0, 20, 15000, NULL),
(40, 'MCMFCG/CFED/2025/12', '2025-08-01', 'My nw Test', NULL, 111, 0, '2025-07-31 05:48:45', NULL, 0, '2025-07-31 05:48:45', NULL, '2025-07-31 05:48:45', 15, '68', NULL, '2025-07-31 05:55:14', NULL, '543536', NULL, '25000', 'Revenue', '11', NULL, 'Test', '5365363', 'NIQ', '2025-07-31', '2025-08-01', 'null', 'file.pdf', '76752632', 'GRYII2256A', 'test@gmail.com', 62475347343, 20, 20000, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sd_mbook_issue`
--
ALTER TABLE `sd_mbook_issue`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sd_mbook_issue`
--
ALTER TABLE `sd_mbook_issue`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
