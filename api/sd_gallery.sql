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
-- Table structure for table `sd_gallery`
--

CREATE TABLE `sd_gallery` (
  `ID` int(5) NOT NULL,
  `event_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `img_loc` longtext DEFAULT NULL,
  `created_by` int(5) NOT NULL,
  `created_time` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `sd_gallery`
--

INSERT INTO `sd_gallery` (`ID`, `event_name`, `description`, `img_loc`, `created_by`, `created_time`) VALUES
(1, 'test', NULL, 'file.png', 1, '2024-01-11 10:04:42'),
(2, 'IGCAR', 'teststst', 'file.jpg', 1, '2024-01-11 11:20:12'),
(3, 'test', 'test', 'file.png', 1, '2024-01-11 16:50:20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sd_gallery`
--
ALTER TABLE `sd_gallery`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sd_gallery`
--
ALTER TABLE `sd_gallery`
  MODIFY `ID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
