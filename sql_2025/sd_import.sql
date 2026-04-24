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
-- Table structure for table `sd_import`
--

CREATE TABLE `sd_import` (
  `ID` int(11) NOT NULL,
  `sd_import_type` varchar(20) NOT NULL,
  `sd_file` varchar(255) DEFAULT NULL,
  `sd_mt_userdb_id` int(11) NOT NULL,
  `last_modified_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `sd_import`
--

INSERT INTO `sd_import` (`ID`, `sd_import_type`, `sd_file`, `sd_mt_userdb_id`, `last_modified_time`) VALUES
(1, 'EMPLOYEE', 'excel_import/1/employees.xlsx', 1, '2025-03-08 08:17:24'),
(2, 'EMPLOYEE', 'excel_import/2/employees.xlsx', 1, '2025-03-08 08:18:43'),
(3, 'EMPLOYEE', 'excel_import/3/employees.xlsx', 1, '2025-03-08 08:19:05'),
(4, 'EMPLOYEE', 'excel_import/4/employees.xlsx', 1, '2025-03-08 08:19:36'),
(5, 'EMPLOYEE', 'excel_import/5/employees.xlsx', 1, '2025-03-08 08:22:13'),
(6, 'ATTENDANCE', 'excel_import/6/import.xlsx', 1, '2025-03-08 09:22:13'),
(7, 'EMPLOYEE', 'excel_import/7/employees.xlsx', 1, '2025-03-08 09:22:54'),
(8, 'EMPLOYEE', 'excel_import/8/employees.xlsx', 1, '2025-03-10 04:53:49'),
(9, 'EMPLOYEE', 'excel_import/9/employees.xlsx', 1, '2025-03-10 04:54:00'),
(10, 'ATTENDANCE', 'excel_import/10/import.xlsx', 1, '2025-03-10 05:04:58'),
(11, 'EMPLOYEE', 'excel_import/11/employees.xlsx', 1, '2025-03-10 07:13:27'),
(12, 'EMPLOYEE', 'excel_import/12/employees.xlsx', 1, '2025-03-10 07:13:50'),
(13, 'EMPLOYEE', 'excel_import/13/employees.xlsx', 1, '2025-03-10 07:19:24'),
(14, 'EMPLOYEE', 'excel_import/14/employees.xlsx', 1, '2025-03-10 07:22:05'),
(15, 'EMPLOYEE', 'excel_import/15/employees.xlsx', 1, '2025-03-10 10:10:19'),
(16, 'EMPLOYEE', 'excel_import/16/employees.xlsx', 1, '2025-03-10 10:11:08'),
(17, 'EMPLOYEE', 'excel_import/17/employees.xlsx', 1, '2025-03-10 10:11:29'),
(18, 'EMPLOYEE', 'excel_import/18/employees.xlsx', 1, '2025-03-10 10:29:13'),
(19, 'EMPLOYEE', 'excel_import/19/employees.xlsx', 1, '2025-03-10 10:30:17'),
(20, 'EMPLOYEE', 'excel_import/20/employees.xlsx', 1, '2025-03-10 10:30:26'),
(21, 'EMPLOYEE', 'excel_import/21/employees.xlsx', 1, '2025-03-15 05:37:59'),
(22, 'EMPLOYEE', 'excel_import/22/employees.xlsx', 1, '2025-03-15 05:38:30'),
(23, 'EMPLOYEE', 'excel_import/23/employees.xlsx', 1, '2025-03-15 05:41:27'),
(24, 'EMPLOYEE', 'excel_import/24/employees.xlsx', 1, '2025-03-15 05:42:46'),
(25, 'EMPLOYEE', 'excel_import/25/employees.xlsx', 1, '2025-03-15 05:44:31'),
(26, 'EMPLOYEE', 'excel_import/26/employees.xlsx', 1, '2025-03-15 05:45:02'),
(27, 'EMPLOYEE', 'excel_import/27/employees.xlsx', 1, '2025-03-15 05:45:53'),
(28, 'EMPLOYEE', 'excel_import/28/employees.xlsx', 1, '2025-03-15 05:47:33'),
(29, 'EMPLOYEE', 'excel_import/29/employees.xlsx', 1, '2025-03-15 05:48:38'),
(30, 'EMPLOYEE', 'excel_import/30/employees.xlsx', 1, '2025-03-15 05:48:53'),
(31, 'EMPLOYEE', 'excel_import/31/employees.xlsx', 1, '2025-03-15 06:00:44'),
(32, 'EMPLOYEE', 'excel_import/32/employees.xlsx', 1, '2025-03-15 06:27:14'),
(33, 'EMPLOYEE', 'excel_import/33/employees.xlsx', 1, '2025-03-18 08:49:20'),
(34, 'ATTENDANCE', 'excel_import/34/import.xlsx', 1, '2025-03-21 11:02:34'),
(35, 'EMPLOYEE', 'excel_import/35/employees.xlsx', 1, '2025-03-21 11:16:51'),
(36, 'ATTENDANCE', 'excel_import/36/import.xlsx', 1, '2025-03-21 11:25:12'),
(37, 'ATTENDANCE', 'excel_import/37/import.xlsx', 1, '2025-03-21 11:29:07'),
(38, 'ATTENDANCE', 'excel_import/38/import.xlsx', 1, '2025-03-21 11:30:00'),
(39, 'ATTENDANCE', 'excel_import/39/import.xlsx', 1, '2025-03-21 11:38:47'),
(40, 'ATTENDANCE', 'excel_import/40/import.xlsx', 1, '2025-03-21 11:41:11'),
(41, 'ATTENDANCE', 'excel_import/41/import.xlsx', 1, '2025-03-21 11:41:36'),
(42, 'ATTENDANCE', 'excel_import/42/import.xlsx', 1, '2025-03-22 06:19:46'),
(43, 'ATTENDANCE', 'excel_import/43/import.xlsx', 1, '2025-03-22 06:30:52'),
(44, 'EMPLOYEE', 'excel_import/44/employees.xlsx', 1, '2025-03-22 06:53:24'),
(45, 'EMPLOYEE', 'excel_import/45/employees.xlsx', 1, '2025-03-24 04:39:48'),
(46, 'EMPLOYEE', 'excel_import/46/employees.xlsx', 1, '2025-03-24 04:53:07'),
(47, 'ADMIN_ASSET', 'excel_import/47/import.xlsx', 1, '2025-03-24 05:30:02'),
(48, 'ADMIN_ASSET', 'excel_import/48/import.xlsx', 1, '2025-03-24 05:30:54'),
(49, 'ADMIN_ASSET', 'excel_import/49/import.xlsx', 1, '2025-03-24 05:31:23'),
(50, 'EMPLOYEE', 'excel_import/50/employees.xlsx', 1, '2025-03-24 06:38:49'),
(51, 'EMPLOYEE', 'excel_import/51/employees.xlsx', 1, '2025-03-24 06:50:52'),
(52, 'EMPLOYEE', 'excel_import/52/employees.xlsx', 1, '2025-03-24 07:44:44'),
(53, 'EMPLOYEE', 'excel_import/53/employees.xlsx', 1, '2025-03-24 07:47:24'),
(54, 'EMPLOYEE', 'excel_import/54/employees.xlsx', 1, '2025-03-24 07:47:27'),
(55, 'EMPLOYEE', 'excel_import/55/employees.xlsx', 1, '2025-03-24 07:47:31'),
(56, 'EMPLOYEE', 'excel_import/56/employees.xlsx', 1, '2025-03-24 07:48:00'),
(57, 'EMPLOYEE', 'excel_import/57/employees.xlsx', 1, '2025-03-24 07:48:04'),
(58, 'EMPLOYEE', 'excel_import/58/employees.xlsx', 1, '2025-03-24 07:48:07'),
(59, 'EMPLOYEE', 'excel_import/59/employees.xlsx', 1, '2025-03-24 07:53:25'),
(60, 'EMPLOYEE', 'excel_import/60/employees.xlsx', 1, '2025-03-24 07:53:36'),
(61, 'EMPLOYEE', 'excel_import/61/employees.xlsx', 1, '2025-03-24 07:54:06'),
(62, 'EMPLOYEE', 'excel_import/62/employees.xlsx', 1, '2025-03-24 07:54:49'),
(63, 'EMPLOYEE', 'excel_import/63/employees.xlsx', 1, '2025-03-24 07:55:14'),
(64, 'EMPLOYEE', 'excel_import/64/employees.xlsx', 1, '2025-03-24 08:43:26'),
(65, 'LEAVE', 'excel_import/65/import.xlsx', 1, '2025-03-27 04:36:45'),
(66, 'LEAVE', 'excel_import/66/import.xlsx', 1, '2025-03-27 05:19:28'),
(67, 'SALARY', 'excel_import/67/import.xlsx', 1, '2025-03-27 05:40:22'),
(68, 'SALARY', 'excel_import/68/import.xlsx', 1, '2025-03-27 05:41:05'),
(69, 'EMPLOYEE', 'excel_import/69/employees.xlsx', 1, '2025-03-27 05:43:02'),
(70, 'EMPLOYEE', 'excel_import/70/employees.xlsx', 1, '2025-03-27 05:43:27'),
(71, 'SALARY', 'excel_import/71/import.xlsx', 1, '2025-03-27 05:45:33'),
(72, 'SALARY', 'excel_import/72/import.xlsx', 1, '2025-03-27 05:48:28'),
(73, 'EMPLOYEE', 'excel_import/73/employees.xlsx', 1, '2025-03-28 04:42:33'),
(74, 'EMPLOYEE', 'excel_import/74/employees.xlsx', 1, '2025-03-28 06:44:29'),
(75, 'EMPLOYEE', 'excel_import/75/employees.xlsx', 1, '2025-03-28 07:01:12'),
(76, 'EMPLOYEE', 'excel_import/76/employees.xlsx', 1, '2025-03-28 08:04:16'),
(77, 'ATTENDANCE', 'excel_import/77/import.xlsx', 111, '2025-03-29 03:56:52'),
(78, 'ATTENDANCE', 'excel_import/78/import.xlsx', 1, '2025-03-29 04:06:28'),
(79, 'ATTENDANCE', 'excel_import/79/import.xlsx', 1, '2025-03-29 04:07:01'),
(80, 'EMPLOYEE', 'excel_import/80/employees.xlsx', 1, '2025-03-29 04:29:35'),
(81, 'EMPLOYEE', 'excel_import/81/employees.xlsx', 1, '2025-03-29 04:35:51'),
(82, 'EMPLOYEE', 'excel_import/82/employees.xlsx', 1, '2025-03-29 04:59:07'),
(83, 'EMPLOYEE', 'excel_import/83/employees.xlsx', 1, '2025-03-29 05:12:16'),
(84, 'ATTENDANCE', 'excel_import/84/import.xlsx', 1, '2025-03-29 05:32:37'),
(85, 'ATTENDANCE', 'excel_import/85/import.xlsx', 1, '2025-03-29 06:46:48'),
(86, 'EMPLOYEE', 'excel_import/86/employees.xlsx', 1, '2025-03-29 07:04:51'),
(87, 'ATTENDANCE', 'excel_import/87/import.xlsx', 1, '2025-03-29 07:06:20'),
(88, 'ATTENDANCE', 'excel_import/88/import.xlsx', 1, '2025-03-29 07:09:16'),
(89, 'EMPLOYEE', 'excel_import/89/employees.xlsx', 1, '2025-03-29 07:57:26'),
(90, 'EMPLOYEE', 'excel_import/90/employees.xlsx', 1, '2025-03-29 08:00:10'),
(91, 'EMPLOYEE', 'excel_import/91/employees.xlsx', 1, '2025-03-29 08:00:26'),
(92, 'ATTENDANCE', 'excel_import/92/import.xlsx', 1, '2025-03-29 09:33:48'),
(93, 'ATTENDANCE', 'excel_import/93/import.xlsx', 1, '2025-03-29 09:33:55'),
(94, 'ATTENDANCE', 'excel_import/94/import.xlsx', 1, '2025-03-29 09:37:50'),
(95, 'ATTENDANCE', 'excel_import/95/import.xlsx', 1, '2025-03-29 09:39:18'),
(96, 'ATTENDANCE', 'excel_import/96/import.xlsx', 1, '2025-03-29 10:34:01'),
(97, 'ATTENDANCE', 'excel_import/97/import.xlsx', 1, '2025-03-29 10:42:48'),
(98, 'EMPLOYEE', 'excel_import/98/employees.xlsx', 1, '2025-03-31 07:55:58'),
(99, 'EMPLOYEE', 'excel_import/99/employees.xlsx', 1, '2025-03-31 07:56:26'),
(100, 'ATTENDANCE', 'excel_import/100/import.xlsx', 1, '2025-03-31 10:18:48'),
(101, 'LEAVE', 'excel_import/101/import.xlsx', 1, '2025-03-31 10:19:37'),
(102, 'LEAVE', 'excel_import/102/import.xlsx', 1, '2025-03-31 10:19:54'),
(103, 'SALARY', 'excel_import/103/import.xlsx', 1, '2025-03-31 10:26:06'),
(104, 'SALARY', 'excel_import/104/import.xlsx', 1, '2025-03-31 10:52:46'),
(105, 'ATTENDANCE', 'excel_import/105/import.xlsx', 1, '2025-04-01 04:18:50'),
(106, 'SALARY', 'excel_import/106/import.xlsx', 1, '2025-04-01 04:25:16'),
(107, 'SALARY', 'excel_import/107/import.xlsx', 1, '2025-04-01 04:33:50'),
(108, 'SALARY', 'excel_import/108/import.xlsx', 1, '2025-04-01 04:46:58'),
(109, 'ATTENDANCE', 'excel_import/109/import.xlsx', 1, '2025-04-01 04:53:30'),
(110, 'ATTENDANCE', 'excel_import/110/import.xlsx', 1, '2025-04-01 04:53:42'),
(111, 'ATTENDANCE', 'excel_import/111/import.xlsx', 1, '2025-04-02 04:32:47'),
(112, 'ATTENDANCE', 'excel_import/112/import.xlsx', 1, '2025-04-02 08:38:07'),
(113, 'ATTENDANCE', 'excel_import/113/import.xlsx', 1, '2025-04-02 08:38:28'),
(114, 'EMPLOYEE', 'excel_import/114/employees.xlsx', 1, '2025-04-05 05:20:23'),
(115, 'EMPLOYEE', 'excel_import/115/employees.xlsx', 1, '2025-04-05 05:20:28'),
(116, 'ATTENDANCE', 'excel_import/116/import.xlsx', 1, '2025-04-05 09:49:22'),
(117, 'ATTENDANCE', 'excel_import/117/import.xlsx', 1, '2025-04-05 09:50:46'),
(118, 'EMPLOYEE', 'excel_import/118/employees.xlsx', 1, '2025-04-17 12:51:36'),
(119, 'EMPLOYEE', 'excel_import/119/employees.xlsx', 1, '2025-04-17 12:51:45'),
(120, 'EMPLOYEE', 'excel_import/120/employees.xlsx', 1, '2025-04-17 12:52:54'),
(121, 'EMPLOYEE', 'excel_import/121/employees.xlsx', 1, '2025-04-17 12:56:55'),
(122, 'EMPLOYEE', 'excel_import/122/employees.xlsx', 1, '2025-04-17 12:59:13'),
(123, 'EMPLOYEE', 'excel_import/123/employees.xlsx', 1, '2025-04-17 12:59:53'),
(124, 'EMPLOYEE', 'excel_import/124/employees.xlsx', 1, '2025-04-17 13:04:37'),
(125, 'EMPLOYEE', 'excel_import/125/employees.xlsx', 1, '2025-04-17 13:22:05'),
(126, 'EMPLOYEE', 'excel_import/126/employees.xlsx', 1, '2025-04-17 13:32:54'),
(127, 'EMPLOYEE', 'excel_import/127/employees.xlsx', 1, '2025-04-21 04:37:16'),
(128, 'EMPLOYEE', 'excel_import/128/employees.xlsx', 1, '2025-06-24 06:43:04'),
(129, 'SALARY', 'excel_import/129/import.xlsx', 1, '2025-06-28 10:40:19'),
(130, 'SALARY', 'excel_import/130/import.xlsx', 1, '2025-07-04 05:22:46'),
(131, 'EMPLOYEE', 'excel_import/131/employees.xlsx', 1, '2025-07-05 06:34:39'),
(132, 'EMPLOYEE', 'excel_import/132/employees.xlsx', 1, '2025-07-05 06:35:10'),
(133, 'EMPLOYEE', 'excel_import/133/employees.xlsx', 1, '2025-07-05 06:39:44'),
(134, 'EMPLOYEE', 'excel_import/134/employees.xlsx', 1, '2025-07-05 06:41:52'),
(135, 'EMPLOYEE', 'excel_import/135/employees.xlsx', 1, '2025-07-05 06:42:28'),
(136, 'EMPLOYEE', 'excel_import/136/employees.xlsx', 1, '2025-07-05 06:43:33'),
(137, 'EMPLOYEE', 'excel_import/137/employees.xlsx', 1, '2025-07-05 06:45:15'),
(138, 'EMPLOYEE', 'excel_import/138/employees.xlsx', 1, '2025-07-05 06:45:58'),
(139, 'EMPLOYEE', 'excel_import/139/employees.xlsx', 1, '2025-07-05 06:49:59'),
(140, 'ATTENDANCE', 'excel_import/140/import.xlsx', 1, '2025-07-05 06:53:18'),
(141, 'ATTENDANCE', 'excel_import/141/import.xlsx', 1, '2025-07-05 06:55:16'),
(142, 'LEAVE', 'excel_import/142/import.xlsx', 1, '2025-07-05 06:57:10'),
(143, 'LEAVE', 'excel_import/143/import.xlsx', 1, '2025-07-05 06:57:26'),
(144, 'EMPLOYEE', 'excel_import/144/employees.xlsx', 1, '2025-07-05 06:58:56'),
(145, 'EMPLOYEE', 'excel_import/145/employees.xlsx', 1, '2025-07-05 06:59:47'),
(146, 'EMPLOYEE', 'excel_import/146/employees.xlsx', 1, '2025-07-05 11:24:30'),
(147, 'EMPLOYEE', 'excel_import/147/employees.xlsx', 1, '2025-07-05 11:25:16'),
(148, 'SALARY', 'excel_import/148/import.xlsx', 1, '2025-07-07 04:59:08'),
(149, 'SALARY', 'excel_import/149/import.xlsx', 1, '2025-07-09 12:35:12'),
(150, 'SALARY', 'excel_import/150/import.xlsx', 1, '2025-07-09 12:36:03'),
(151, 'SALARY', 'excel_import/151/import.xlsx', 1, '2025-07-09 12:49:47'),
(152, 'SALARY', 'excel_import/152/import.xlsx', 1, '2025-07-09 12:56:28'),
(153, 'SALARY', 'excel_import/153/import.xlsx', 1, '2025-07-09 13:00:31'),
(154, 'SALARY', 'excel_import/154/import.xlsx', 1, '2025-07-09 13:48:10'),
(155, 'SALARY', 'excel_import/155/import.xlsx', 1, '2025-07-09 13:51:08'),
(156, 'SALARY', 'excel_import/156/import.xlsx', 1, '2025-07-09 13:51:30'),
(157, 'SALARY', 'excel_import/157/import.xlsx', 1, '2025-07-09 13:58:52'),
(158, 'SALARY', 'excel_import/158/import.xlsx', 1, '2025-07-09 13:59:02'),
(159, 'SALARY', 'excel_import/159/import.xlsx', 1, '2025-07-09 13:59:24'),
(160, 'SALARY', 'excel_import/160/import.xlsx', 1, '2025-07-09 14:02:04'),
(161, 'SALARY', 'excel_import/161/import.xlsx', 1, '2025-07-09 14:03:14'),
(162, 'SALARY', 'excel_import/162/import.xlsx', 1, '2025-07-09 14:03:25'),
(163, 'SALARY', 'excel_import/163/import.xlsx', 1, '2025-07-09 14:05:30'),
(164, 'SALARY', 'excel_import/164/import.xlsx', 1, '2025-07-09 14:05:52'),
(165, 'SALARY', 'excel_import/165/import.xlsx', 1, '2025-07-09 16:38:07'),
(166, 'SALARY', 'excel_import/166/import.xlsx', 1, '2025-07-09 16:38:30'),
(167, 'SALARY', 'excel_import/167/import.xlsx', 1, '2025-07-09 16:39:08'),
(168, 'EMPLOYEE', 'excel_import/168/employees.xlsx', 1, '2025-07-17 08:29:36'),
(169, 'EMPLOYEE', 'excel_import/169/employees.xlsx', 1, '2025-07-17 08:29:56'),
(170, 'EMPLOYEE', 'excel_import/170/employees.xlsx', 1, '2025-07-17 08:58:12'),
(171, 'EMPLOYEE', 'excel_import/171/employees.xlsx', 1, '2025-07-18 04:20:04'),
(172, 'EMPLOYEE', 'excel_import/172/employees.xlsx', 1, '2025-07-18 04:20:21'),
(173, 'EMPLOYEE', 'excel_import/173/employees.xlsx', 1, '2025-07-18 04:22:58'),
(174, 'EMPLOYEE', 'excel_import/174/employees.xlsx', 1, '2025-07-18 04:23:35'),
(175, 'EMPLOYEE', 'excel_import/175/employees.xlsx', 1, '2025-07-18 04:36:40'),
(176, 'ORGANISATION', 'excel_import/176/organisation.xlsx', 1, '2025-07-29 05:30:33'),
(177, 'ORGANISATION', 'excel_import/177/organisation.xlsx', 1, '2025-07-29 05:45:01'),
(178, 'ORGANISATION', 'excel_import/178/organisation.xlsx', 1, '2025-07-29 06:37:37'),
(179, 'ORGANISATION', 'excel_import/179/organisation.xlsx', 1, '2025-07-29 06:38:03'),
(180, 'ORGANISATION', 'excel_import/180/organisation.xlsx', 1, '2025-07-29 06:49:18'),
(181, 'ORGANISATION', 'excel_import/181/organisation.xlsx', 1, '2025-07-29 06:58:44'),
(182, 'ORGANISATION', 'excel_import/182/organisation.xlsx', 1, '2025-07-29 07:05:33'),
(183, 'ORGANISATION', 'excel_import/183/organisation.xlsx', 1, '2025-07-29 10:50:05'),
(184, 'ORGANISATION', 'excel_import/184/organisation.xlsx', 1, '2025-07-29 10:51:12'),
(185, 'ORGANISATION', 'excel_import/185/organisation.xlsx', 1, '2025-07-29 10:52:54'),
(186, 'ORGANISATION', 'excel_import/186/organisation.xlsx', 1, '2025-07-29 10:54:50'),
(187, 'ORGANISATION', 'excel_import/187/organisation.xlsx', 1, '2025-07-29 11:02:04'),
(188, 'ORGANISATION', 'excel_import/188/organisation.xlsx', 1, '2025-08-02 04:32:18');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sd_import`
--
ALTER TABLE `sd_import`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sd_import`
--
ALTER TABLE `sd_import`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=189;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
