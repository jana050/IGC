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
-- Table structure for table `sd_temporary_advance`
--

CREATE TABLE `sd_temporary_advance` (
  `ID` int(11) NOT NULL,
  `temporary_advance_number` varchar(1000) DEFAULT NULL,
  `raised_by` varchar(1000) DEFAULT NULL,
  `advance_amount` bigint(20) DEFAULT NULL,
  `settlement` varchar(1000) DEFAULT NULL,
  `balance` bigint(20) DEFAULT NULL,
  `applied_on_date` date DEFAULT NULL,
  `sanction_date` date DEFAULT NULL,
  `stock_reg_no` bigint(20) DEFAULT NULL,
  `physically_verified_by` varchar(10000) DEFAULT NULL,
  `title` varchar(1000) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `sd_mt_userdb_id` int(100) NOT NULL,
  `admin_id` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 5 COMMENT '5=waiting,10=approved,4=rejected	',
  `created_time` datetime(6) NOT NULL,
  `last_modified_by` varchar(255) DEFAULT NULL,
  `last_modified_remarks` text DEFAULT NULL,
  `app_id` int(11) NOT NULL DEFAULT 0,
  `app_time` datetime NOT NULL DEFAULT current_timestamp(),
  `app_remarks` varchar(255) DEFAULT NULL,
  `admin_time` datetime NOT NULL DEFAULT current_timestamp(),
  `admin_remarks` varchar(255) DEFAULT NULL,
  `last_modified_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
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
  `supervisor` int(11) DEFAULT NULL,
  `supervisor_description` varchar(255) DEFAULT NULL,
  `supervisor_time` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `purchase_amount` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sd_temporary_advance`
--

INSERT INTO `sd_temporary_advance` (`ID`, `temporary_advance_number`, `raised_by`, `advance_amount`, `settlement`, `balance`, `applied_on_date`, `sanction_date`, `stock_reg_no`, `physically_verified_by`, `title`, `description`, `sd_mt_userdb_id`, `admin_id`, `status`, `created_time`, `last_modified_by`, `last_modified_remarks`, `app_id`, `app_time`, `app_remarks`, `admin_time`, `admin_remarks`, `last_modified_time`, `hos_id`, `hos_remarks`, `hos_time`, `hod_id`, `hod_remarks`, `hod_time`, `ad_id`, `ad_remarks`, `ad_time`, `gd_id`, `gd_remarks`, `gd_time`, `supervisor`, `supervisor_description`, `supervisor_time`, `purchase_amount`) VALUES
(21, 'TA-20250512-001', NULL, 2000, 'SET-20250512-001', 40000, NULL, NULL, 322, NULL, 'test for now', 'test', 25, 0, 15, '2025-05-12 00:00:00.000000', '1', NULL, 0, '2025-05-12 15:20:18', NULL, '2025-05-12 15:20:18', 'test', '2025-05-12 09:53:54', NULL, NULL, '2025-05-15 11:52:55', NULL, NULL, '2025-05-15 11:52:55', NULL, NULL, '2025-05-15 11:52:55', NULL, NULL, '2025-05-15 11:52:55', NULL, NULL, '2025-05-20 05:22:29', NULL),
(22, 'TA-20250512-002', NULL, 342, 'SET-20250512-002', 32531, NULL, NULL, 363, NULL, 'ewrew', 'test', 25, 0, 10, '2025-05-12 00:00:00.000000', NULL, NULL, 0, '2025-05-12 16:57:17', NULL, '2025-05-12 16:57:17', NULL, '2025-05-12 11:27:17', NULL, NULL, '2025-05-15 11:52:55', NULL, NULL, '2025-05-15 11:52:55', NULL, NULL, '2025-05-15 11:52:55', NULL, NULL, '2025-05-15 11:52:55', NULL, NULL, '2025-05-20 05:22:29', NULL),
(23, 'TA-20250513-001', NULL, 46563, 'SET-20250513-001', 6534366, NULL, NULL, 5462, NULL, 'test', 'test', 1, 0, 10, '2025-05-13 00:00:00.000000', NULL, NULL, 0, '2025-05-13 14:54:02', NULL, '2025-05-13 14:54:02', NULL, '2025-05-13 09:24:02', NULL, NULL, '2025-05-15 11:52:55', NULL, NULL, '2025-05-15 11:52:55', NULL, NULL, '2025-05-15 11:52:55', NULL, NULL, '2025-05-15 11:52:55', NULL, NULL, '2025-05-20 05:22:29', NULL),
(24, 'TA-20250515-001', NULL, 10000, 'SET-20250515-001', 10, NULL, NULL, 1234, NULL, 'for advance', 'test', 60, 0, 30, '2025-05-15 00:00:00.000000', '1', NULL, 0, '2025-05-15 11:41:13', NULL, '2025-05-15 11:41:13', 'test', '2025-05-22 06:12:15', NULL, NULL, '2025-05-22 06:12:15', NULL, NULL, '2025-05-22 06:12:15', NULL, NULL, '2025-05-22 06:12:15', 65, '', NULL, NULL, NULL, '2025-05-22 06:12:15', NULL),
(27, 'TA-20250521-001', NULL, 17000, 'SET-20250521-001', 10000, NULL, NULL, 343, NULL, 'Test', 'Test', 1, 0, 5, '2025-05-21 00:00:00.000000', NULL, NULL, 0, '2025-05-21 13:08:09', NULL, '2025-05-21 13:08:09', NULL, '2025-05-21 13:08:09', NULL, NULL, '2025-05-21 13:08:09', NULL, NULL, '2025-05-21 13:08:09', NULL, NULL, '2025-05-21 13:08:09', NULL, NULL, '2025-05-21 13:08:09', NULL, NULL, '2025-05-21 13:08:09', NULL),
(28, 'TA-20250522-001', NULL, 18000, 'SET-20250522-001', 500000, NULL, NULL, 1254, NULL, 'New advance', 'test', 79, 0, 35, '2025-05-22 00:00:00.000000', NULL, NULL, 0, '2025-05-22 04:41:02', NULL, '2025-05-22 04:41:02', NULL, '2025-05-22 06:06:54', 25, '', NULL, NULL, NULL, '2025-05-22 06:06:54', NULL, NULL, '2025-05-22 06:06:54', NULL, NULL, '2025-05-22 06:06:54', NULL, NULL, '2025-05-22 06:06:54', NULL),
(29, 'TA-20250522-002', NULL, 28000, 'SET-20250522-002', 389489, NULL, NULL, 233, NULL, 'New advance 2', 'test 2', 79, 0, 35, '2025-05-22 00:00:00.000000', NULL, NULL, 0, '2025-05-22 04:41:24', NULL, '2025-05-22 04:41:24', NULL, '2025-05-22 06:08:54', 25, '', '2025-05-22 06:08:54', 60, '', NULL, NULL, NULL, '2025-05-22 06:08:54', NULL, NULL, '2025-05-22 06:08:54', NULL, NULL, '2025-05-22 06:08:54', NULL),
(30, 'TA-20250522-003', NULL, 58000, 'SET-20250522-003', 342872, NULL, NULL, 718, NULL, 'New Advance 3', 'new one', 79, 0, 35, '2025-05-22 00:00:00.000000', NULL, NULL, 0, '2025-05-22 04:42:00', NULL, '2025-05-22 04:42:00', NULL, '2025-05-22 06:10:49', 25, '', '2025-05-22 06:10:49', 60, '', '2025-05-22 06:10:49', 63, '', NULL, NULL, NULL, '2025-05-22 06:10:49', NULL, NULL, '2025-05-22 06:10:49', NULL),
(31, 'TA-20250522-004', NULL, 20000, 'SET-20250522-004', 100000, NULL, NULL, 212, NULL, 'test', 'test-1', 1, 0, 5, '2025-05-22 00:00:00.000000', NULL, NULL, 0, '2025-05-22 04:50:00', NULL, '2025-05-22 04:50:00', NULL, '2025-05-22 04:50:00', NULL, NULL, '2025-05-22 04:50:00', NULL, NULL, '2025-05-22 04:50:00', NULL, NULL, '2025-05-22 04:50:00', NULL, NULL, '2025-05-22 04:50:00', NULL, NULL, '2025-05-22 04:50:00', NULL),
(32, 'TA-20250522-005', NULL, 68000, 'SET-20250522-005', 10000, NULL, NULL, 362, NULL, 'add new', 'TEST', 1, 0, 5, '2025-05-22 00:00:00.000000', NULL, NULL, 0, '2025-05-22 04:50:46', NULL, '2025-05-22 04:50:46', NULL, '2025-05-22 04:50:46', NULL, NULL, '2025-05-22 04:50:46', NULL, NULL, '2025-05-22 04:50:46', NULL, NULL, '2025-05-22 04:50:46', NULL, NULL, '2025-05-22 04:50:46', NULL, NULL, '2025-05-22 04:50:46', NULL),
(33, 'TA-20250522-006', NULL, 100000, 'SET-20250522-006', 326326, NULL, NULL, 362, NULL, 'test', 'test', 79, 0, 10, '2025-05-22 00:00:00.000000', NULL, NULL, 0, '2025-05-22 08:34:56', NULL, '2025-05-22 08:34:56', NULL, '2025-05-22 08:36:16', NULL, NULL, '2025-05-22 08:36:16', NULL, NULL, '2025-05-22 08:36:16', NULL, NULL, '2025-05-22 08:36:16', NULL, NULL, '2025-05-22 08:36:16', NULL, NULL, NULL, NULL),
(34, 'TA-20250522-007', NULL, 5000, 'SET-20250522-007', 10, NULL, NULL, 0, NULL, 'Note Book', 'Ok Test', 112, 0, 35, '2025-05-22 00:00:00.000000', NULL, NULL, 0, '2025-05-22 17:14:10', NULL, '2025-05-22 17:14:10', NULL, '2025-05-22 17:22:55', 68, '', NULL, NULL, NULL, '2025-05-22 17:22:55', NULL, NULL, '2025-05-22 17:22:55', NULL, NULL, '2025-05-22 17:22:55', NULL, NULL, '2025-05-22 17:22:55', NULL),
(35, 'TA-20250522-008', NULL, 45000, 'SET-20250522-008', 5000, NULL, NULL, 0, NULL, 'TEST HOD', 'TMP-AD-98876', 112, 0, 35, '2025-05-22 00:00:00.000000', NULL, NULL, 0, '2025-05-22 17:33:39', NULL, '2025-05-22 17:33:39', NULL, '2025-05-22 17:37:40', 68, '', '2025-05-22 17:37:40', 60, '', NULL, NULL, NULL, '2025-05-22 17:37:40', NULL, NULL, '2025-05-22 17:37:40', NULL, NULL, '2025-05-22 17:37:40', NULL),
(36, 'TA-20250523-001', NULL, 7500, 'SET-20250523-001', 5, NULL, NULL, 6, NULL, 'temp2', 'resistor -2\ntransformer-1', 112, 0, 19, '2025-05-23 00:00:00.000000', NULL, NULL, 0, '2025-05-23 06:58:41', NULL, '2025-05-23 06:58:41', NULL, '2025-07-09 06:13:50', 68, '', '2025-07-09 06:13:50', 60, '', NULL, NULL, NULL, '2025-07-09 06:13:50', NULL, NULL, '2025-07-09 06:13:50', NULL, NULL, '2025-07-09 06:13:50', NULL),
(37, 'TA-20250709-001', NULL, 1000, 'SET-20250709-001', 200, NULL, NULL, 123, NULL, 'test', 'test', 112, 0, 35, '2025-07-09 00:00:00.000000', NULL, NULL, 0, '2025-07-09 06:10:29', NULL, '2025-07-09 06:10:29', NULL, '2025-07-09 06:13:22', 68, '', NULL, NULL, NULL, '2025-07-09 06:13:22', NULL, NULL, '2025-07-09 06:13:22', NULL, NULL, '2025-07-09 06:13:22', NULL, NULL, '2025-07-09 06:13:22', 800),
(38, 'TA-20250730-001', NULL, 5000, 'SET-20250730-001', 500, NULL, NULL, 0, NULL, 'BATTERY', 'FOR CLOCK', 1, 0, 5, '2025-07-30 00:00:00.000000', NULL, NULL, 0, '2025-07-30 10:59:47', NULL, '2025-07-30 10:59:47', NULL, '2025-07-30 10:59:47', NULL, NULL, '2025-07-30 10:59:47', NULL, NULL, '2025-07-30 10:59:47', NULL, NULL, '2025-07-30 10:59:47', NULL, NULL, '2025-07-30 10:59:47', NULL, NULL, NULL, 4500),
(39, 'TA-20250731-001', NULL, 5000, 'SET-20250731-001', 4000, NULL, NULL, 97543, NULL, 'Temporary Advance Settlement Form on  july 31', 'Temporary Advance Settlement', 109, 0, 35, '2025-07-31 00:00:00.000000', NULL, NULL, 0, '2025-07-31 05:53:52', NULL, '2025-07-31 05:53:52', NULL, '2025-07-31 06:00:05', 68, '', '2025-07-31 06:00:05', 60, '', NULL, NULL, NULL, '2025-07-31 06:00:05', NULL, NULL, '2025-07-31 06:00:05', NULL, NULL, '2025-07-31 06:00:05', 1000),
(40, 'TA-20250731-002', NULL, 4000, 'SET-20250731-002', 1000, NULL, NULL, 9876543, NULL, 'Temporary Advance Settlement on july 31st', 'Temporary Advance Settlement', 109, 0, 35, '2025-07-31 00:00:00.000000', NULL, NULL, 0, '2025-07-31 06:01:25', NULL, '2025-07-31 06:01:25', NULL, '2025-07-31 06:02:18', 68, '', NULL, NULL, NULL, '2025-07-31 06:02:18', NULL, NULL, '2025-07-31 06:02:18', NULL, NULL, '2025-07-31 06:02:18', NULL, NULL, '2025-07-31 06:02:18', 3000),
(41, 'TA-20250731-003', NULL, 14000, 'SET-20250731-003', 13000, NULL, NULL, 98765, NULL, 'Temporary Advance on 31', 'Temporary Advance', 109, 0, 35, '2025-07-31 00:00:00.000000', NULL, NULL, 0, '2025-07-31 06:04:44', NULL, '2025-07-31 06:04:44', NULL, '2025-07-31 06:06:11', 68, '', '2025-07-31 06:06:11', 60, '', '2025-07-31 06:06:11', 63, '', NULL, NULL, NULL, '2025-07-31 06:06:11', NULL, NULL, '2025-07-31 06:06:11', 1000),
(42, 'TA-20250731-004', NULL, 120000, 'SET-20250731-004', 20000, NULL, NULL, 678909, NULL, 'Temporary Advance Settlement on today', 'test', 109, 0, 30, '2025-07-31 00:00:00.000000', NULL, NULL, 0, '2025-07-31 06:07:25', NULL, '2025-07-31 06:07:25', NULL, '2025-07-31 06:10:11', 68, '', '2025-07-31 06:10:11', 60, '', '2025-07-31 06:10:11', 63, '', '2025-07-31 06:10:11', 61, '', NULL, NULL, NULL, '2025-07-31 06:10:11', 100000);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sd_temporary_advance`
--
ALTER TABLE `sd_temporary_advance`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sd_temporary_advance`
--
ALTER TABLE `sd_temporary_advance`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
