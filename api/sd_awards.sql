-- phpMyAdmin SQL Dump
-- version 5.2.1-1.el9
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 12, 2024 at 06:31 AM
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
-- Table structure for table `sd_awards`
--

CREATE TABLE `sd_awards` (
  `ID` int(5) NOT NULL,
  `title` varchar(255) NOT NULL,
  `img_loc` longtext DEFAULT NULL,
  `award_date` date NOT NULL,
  `created_by` int(5) NOT NULL,
  `created_time` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `sd_awards`
--

INSERT INTO `sd_awards` (`ID`, `title`, `img_loc`, `award_date`, `created_by`, `created_time`) VALUES
(1, 'TEST', 'file.png', '2024-01-11', 1, '2024-01-11 10:35:22'),
(2, 'welcom to ig card', 'file.jpeg', '2024-01-11', 1, '2024-01-11 12:04:52'),
(3, 'Modi Award', 'file.jpeg', '2024-01-11', 1, '2024-01-11 12:13:27'),
(4, 'test', 'file.jpg', '2024-01-11', 1, '2024-01-11 12:13:54'),
(5, 'Users have the ability to upload documents to the system. This can be done through a user-friendly interface that allows for file uploads. The system may have specific restrictions on acc', 'file.jpg', '2024-01-11', 1, '2024-01-11 15:57:58');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sd_awards`
--
ALTER TABLE `sd_awards`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sd_awards`
--
ALTER TABLE `sd_awards`
  MODIFY `ID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
