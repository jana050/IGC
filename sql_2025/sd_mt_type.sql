-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: bkc00oow4so4oc004g4s4o08
-- Generation Time: Aug 04, 2025 at 12:24 PM
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
-- Table structure for table `sd_mt_type`
--

CREATE TABLE `sd_mt_type` (
  `ID` int(11) NOT NULL,
  `type_name` varchar(255) NOT NULL,
  `type_value` varchar(1000) NOT NULL,
  `order_number` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `sd_mt_type`
--

INSERT INTO `sd_mt_type` (`ID`, `type_name`, `type_value`, `order_number`) VALUES
(1, 'test', 'check', 0),
(2, 'test', 'test', 0),
(3, 'yuva', 'test', 0),
(4, 'test', 'jt6jtr', 0),
(5, 'OTHER', 'Jack Sparrow', 0),
(6, 'yuva', 'William Butcher', 0),
(29, 'MeetTypes', 'RCL Meeting Room', 0),
(30, 'MeetTypes', 'RCL Conference Room', 0),
(31, 'MeetTypes', 'Boron Meeting Room', 0),
(33, 'DocumentTypes', 'Internal Report', 0),
(34, 'DocumentTypes', 'Journals', 0),
(35, 'DocumentTypes', 'Conference', 0),
(36, 'DocumentTypes', 'IGC Report', 0),
(39, 'DocumentTypes', 'Forms', 0),
(43, 'orgTypes', 'MC&MFCG & Medical group', 0),
(44, 'orgTypes', 'Metal Fuel Recycle Group', 0),
(45, 'orgTypes', 'Fuel & Materials Chemistry Group', 0),
(46, 'orgTypes', 'CF & ED', 0),
(47, 'orgTypes', 'MFPD', 0),
(48, 'orgTypes', 'PPED', 0),
(49, 'orgTypes', 'ACSD', 0),
(50, 'orgTypes', 'FCD', 0),
(51, 'orgTypes', 'MCD', 0),
(53, 'DocumentTypes', 'LORC Minutes', 0),
(54, 'DocumentTypes', 'Safety Committee Minutes', 0),
(55, 'DocumentTypes', 'RASCOM Minutes', 0),
(56, 'orgTypes', 'ecis', 0),
(59, 'DocumentTypes', 'Drawings-Mechanical', 0),
(61, 'DocumentTypes', 'Drawings-Electrical', 0),
(63, 'orgTypes', 'Materials', 0),
(64, 'DocumentTypes', 'E-books', 0),
(65, 'DocumentTypes', 'Book Chapters', 0),
(66, 'MeetTypes', 'test', 0),
(67, 'MeetTypes', 'test3', 0),
(68, 'MeetTypes', 'test2', 0),
(70, 'MeetTypes', 'test-111', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sd_mt_type`
--
ALTER TABLE `sd_mt_type`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sd_mt_type`
--
ALTER TABLE `sd_mt_type`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
