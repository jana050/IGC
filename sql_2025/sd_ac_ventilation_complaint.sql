-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 28, 2025 at 08:13 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

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
-- Table structure for table `sd_ac_ventilation_complaint`
--

CREATE TABLE `sd_ac_ventilation_complaint` (
  `ID` int(11) NOT NULL,
  `title` varchar(1000) NOT NULL,
  `description` text DEFAULT NULL,
  `location` text NOT NULL,
  `sd_mt_userdb_id` int(100) NOT NULL,
  `app_id` int(11) NOT NULL DEFAULT 0,
  `app_time` datetime NOT NULL DEFAULT current_timestamp(),
  `app_remarks` varchar(255) DEFAULT NULL,
  `admin_id` int(11) NOT NULL DEFAULT 0,
  `admin_time` datetime NOT NULL DEFAULT current_timestamp(),
  `admin_remarks` varchar(255) DEFAULT NULL,
  `created_time` datetime NOT NULL,
  `status` int(11) NOT NULL DEFAULT 5,
  `last_modified_by` varchar(255) DEFAULT NULL,
  `last_modified_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_modified_remarks` text DEFAULT NULL,
  `date_of_closure` date DEFAULT NULL,
  `supervisor_description` longtext DEFAULT NULL,
  `supervisor` int(11) DEFAULT NULL,
  `supervisor_time` datetime DEFAULT NULL,
  `authority_type` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sd_ac_ventilation_complaint`
--

INSERT INTO `sd_ac_ventilation_complaint` (`ID`, `title`, `description`, `location`, `sd_mt_userdb_id`, `app_id`, `app_time`, `app_remarks`, `admin_id`, `admin_time`, `admin_remarks`, `created_time`, `status`, `last_modified_by`, `last_modified_time`, `last_modified_remarks`, `date_of_closure`, `supervisor_description`, `supervisor`, `supervisor_time`, `authority_type`) VALUES
(1, 'tedt', 'test', '21', 79, 0, '2025-05-22 05:12:42', NULL, 0, '2025-05-22 05:12:42', 'test', '2025-05-22 05:12:42', 15, '1', '2025-05-22 05:13:54', NULL, NULL, 'new', 25, NULL, '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sd_ac_ventilation_complaint`
--
ALTER TABLE `sd_ac_ventilation_complaint`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sd_ac_ventilation_complaint`
--
ALTER TABLE `sd_ac_ventilation_complaint`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
