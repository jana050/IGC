-- phpMyAdmin SQL Dump
-- version 5.2.1-1.el9
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 12, 2024 at 06:32 AM
-- Server version: 10.5.22-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `igcdoc`
--

-- --------------------------------------------------------

--
-- Table structure for table `sd_meet_proposal`
--

CREATE TABLE `sd_meet_proposal` (
  `ID` int(5) NOT NULL,
  `mom_type` varchar(50) DEFAULT NULL,
  `title` varchar(55) NOT NULL,
  `description` varchar(255) NOT NULL,
  `doc_loc` text DEFAULT NULL,
  `app_id` int(5) NOT NULL DEFAULT 0,
  `app_date` date DEFAULT NULL,
  `app_remarks` text DEFAULT NULL,
  `status` int(2) NOT NULL,
  `created_by` int(5) NOT NULL,
  `created_time` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `sd_meet_proposal`
--

INSERT INTO `sd_meet_proposal` (`ID`, `mom_type`, `title`, `description`, `doc_loc`, `app_id`, `app_date`, `app_remarks`, `status`, `created_by`, `created_time`) VALUES
(1, 'SORC', 'asdfasfasf', 'asdf asdfasdfa sdf', 'file.pdf', 0, '2024-01-11', NULL, 6, 1, '2024-01-11 10:38:48'),
(2, 'SRC', 'dfggagaga', 'asfas fa sdfasd fsdfa sdfasd f', 'file.pdf', 0, NULL, NULL, 5, 1, '2024-01-11 11:07:07');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sd_meet_proposal`
--
ALTER TABLE `sd_meet_proposal`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sd_meet_proposal`
--
ALTER TABLE `sd_meet_proposal`
  MODIFY `ID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
