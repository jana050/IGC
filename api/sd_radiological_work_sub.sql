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
-- Table structure for table `sd_radiological_work_sub`
--

CREATE TABLE `sd_radiological_work_sub` (
  `ID` int(11) NOT NULL,
  `sd_radiological_work_id` int(11) DEFAULT NULL,
  `name_of_personnel` varchar(255) DEFAULT NULL,
  `tld_no` varchar(100) DEFAULT NULL,
  `activity_nature` text DEFAULT NULL,
  `signature` varchar(255) DEFAULT NULL,
  `drd_no` varchar(1000) DEFAULT NULL,
  `planned_exposure` varchar(1000) DEFAULT NULL,
  `dose_as_per` varchar(1000) DEFAULT NULL,
  `created_time` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sd_radiological_work_sub`
--

INSERT INTO `sd_radiological_work_sub` (`ID`, `sd_radiological_work_id`, `name_of_personnel`, `tld_no`, `activity_nature`, `signature`, `drd_no`, `planned_exposure`, `dose_as_per`, `created_time`) VALUES
(1, 10, 'Jana', '123', NULL, NULL, '123', '1', '1', '2025-05-15 11:37:40'),
(2, 11, 'jana', '658789', NULL, NULL, '56798o', '4', '4', '2025-05-19 15:04:36'),
(3, 11, 'tamizh', '656789', NULL, NULL, '76567899', '4', '4', '2025-05-19 15:04:36'),
(4, 12, 'tAMIZH', 'ASDFSDG', NULL, NULL, '55656', '2', '3', '2025-05-19 15:07:26'),
(5, 13, 'tamizh', '577', NULL, NULL, '789', '4', '6', '2025-05-22 06:11:08'),
(6, 13, 'jana', '8787', NULL, NULL, '6879', '3', '3', '2025-05-22 06:11:08'),
(7, 14, 'guna', '256', NULL, NULL, '1', '1', '2', '2025-05-23 10:04:57'),
(8, 14, 'sekaran', '257', NULL, NULL, '2', '2', '2', '2025-05-23 10:04:57'),
(9, 15, 'S. Anitha', '56567', NULL, NULL, '6677', '1', '1', '2025-07-09 06:53:19'),
(10, 16, 'test', '74654', NULL, NULL, '6546', '644', '3437', '2025-07-22 09:15:18'),
(11, 17, 'Jana', '1234', NULL, NULL, '1234', '222', '222', '2025-07-26 11:32:26'),
(12, 17, 'Tamil', '43', NULL, NULL, '234', '3', '2', '2025-07-26 11:32:26'),
(13, 18, 'parameswari', '54678', NULL, NULL, '6578', '56', '73', '2025-07-31 05:34:00'),
(14, 18, 'priya', '987654', NULL, NULL, '8976', '12', '12', '2025-07-31 05:34:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sd_radiological_work_sub`
--
ALTER TABLE `sd_radiological_work_sub`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sd_radiological_work_sub`
--
ALTER TABLE `sd_radiological_work_sub`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
