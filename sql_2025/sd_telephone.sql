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
-- Table structure for table `sd_telephone`
--

CREATE TABLE `sd_telephone` (
  `ID` int(11) NOT NULL,
  `title` varchar(1000) NOT NULL,
  `description` text DEFAULT NULL,
  `telephone_number` text NOT NULL,
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
-- Dumping data for table `sd_telephone`
--

INSERT INTO `sd_telephone` (`ID`, `title`, `description`, `telephone_number`, `location`, `sd_mt_userdb_id`, `app_id`, `app_time`, `app_remarks`, `admin_id`, `admin_time`, `admin_remarks`, `created_time`, `status`, `last_modified_by`, `last_modified_remarks`, `last_modified_time`, `date_of_closure`, `supervisor_description`, `supervisor_time`, `supervisor`) VALUES
(1, 'test', 'test', '24002', 'l-6', 1, 0, '2023-12-16 13:09:21', NULL, 0, '2023-12-16 13:09:21', NULL, '2023-12-13 08:29:13.000000', 15, '60', NULL, '2023-12-16 07:39:21', NULL, NULL, '2025-05-16 10:41:54', NULL),
(2, 'test', 'test', '1223232', 'esttt', 1, 0, '2024-01-06 16:21:23', NULL, 0, '2024-01-06 16:21:23', 'test', '2024-01-06 16:21:00.000000', 11, '1', NULL, '2024-01-06 16:21:23', NULL, NULL, '2025-05-16 10:41:54', NULL),
(3, 'test', 'test', '123456789', 'i7', 1, 0, '2024-02-16 05:41:55', NULL, 0, '2024-02-16 05:41:55', NULL, '2024-02-16 05:41:55.000000', 10, NULL, NULL, '2024-02-16 05:41:55', NULL, NULL, '2025-05-16 10:41:54', NULL),
(4, 'test-2', 'test', '123', 'i5', 1, 0, '2024-02-16 05:43:06', NULL, 0, '2024-02-16 05:43:06', NULL, '2024-02-16 05:43:06.000000', 10, NULL, NULL, '2024-02-16 05:43:06', NULL, NULL, '2025-05-16 10:41:54', NULL),
(5, 'test', 'new test', '325632', 'A block', 66, 0, '2025-05-03 06:57:11', NULL, 0, '2025-05-03 06:57:11', NULL, '2025-05-03 06:57:11.000000', 10, NULL, NULL, '2025-05-03 06:57:11', NULL, NULL, '2025-05-16 10:41:54', NULL),
(6, 'Telephone Complaint on may 22', 'Telephone Complaint', '9876532109', '878', 109, 0, '2025-05-22 05:12:18', NULL, 0, '2025-05-22 05:12:18', 'assigned this supervisor', '2025-05-22 04:57:45.000000', 15, '1', NULL, '2025-05-22 05:12:18', NULL, 'approved supervisor', NULL, 66),
(7, 'Telephone Complaint on 9 july 2025', 'Telephone Complaint o test', '9876543210', 'chennai', 109, 0, '2025-07-09 05:54:00', NULL, 0, '2025-07-09 05:54:00', 'approved admin', '2025-07-09 05:49:58.000000', 15, '1', NULL, '2025-07-09 05:54:00', NULL, 'approved supervisor', NULL, 25);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sd_telephone`
--
ALTER TABLE `sd_telephone`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sd_telephone`
--
ALTER TABLE `sd_telephone`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
