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
-- Table structure for table `sd_home_images`
--

CREATE TABLE `sd_home_images` (
  `ID` int(11) NOT NULL,
  `home_image` longtext DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `sd_home_images`
--

INSERT INTO `sd_home_images` (`ID`, `home_image`, `title`, `description`) VALUES
(6, NULL, '', ''),
(7, NULL, '', ''),
(8, NULL, '', ''),
(19, 'file.jpg', 'Advanced Materials for Nuclear Innovation', 'Exploring cutting-edge materials chemistry to support the next generation of nuclear technologies and sustainable fuel cycles'),
(20, 'file.jpg', 'Metal Fuel Cycle Research & Development', 'Investigating the chemical behavior of reactor materials under extreme conditions to ensure safety, longevity, and performance'),
(21, 'file.jpg', 'Chemistry Behind Sustainable Reactors', 'Investigating the chemical behavior of reactor materials under extreme conditions to ensure safety, longevity, and performance'),
(22, 'file.jpeg', 'Innovating Materials for a Clean Energy Future', 'Driving advancements in materials science to enable closed-loop fuel cycles and reduce the environmental impact of nuclear energy');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sd_home_images`
--
ALTER TABLE `sd_home_images`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sd_home_images`
--
ALTER TABLE `sd_home_images`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
