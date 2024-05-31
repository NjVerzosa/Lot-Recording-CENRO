-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 20, 2024 at 01:25 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `denr-lotmanagement`
--

-- --------------------------------------------------------

--
-- Table structure for table `land_titles`
--

CREATE TABLE `land_titles` (
  `id` int(11) NOT NULL,
  `lot_number` varchar(255) NOT NULL,
  `application_no` int(11) NOT NULL,
  `date_filed` date DEFAULT NULL,
  `applicant_name` varchar(255) DEFAULT NULL,
  `date_approved` date NOT NULL,
  `area` varchar(255) NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `position` varchar(255) DEFAULT '0',
  `status` varchar(5) NOT NULL DEFAULT '0' COMMENT '0 = no action, 1 subdivided, 2 = titled'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subdivided_titles`
--

CREATE TABLE `subdivided_titles` (
  `id` int(11) NOT NULL,
  `lot_number` varchar(255) NOT NULL,
  `date_filed` date DEFAULT NULL,
  `applicant_name` varchar(255) DEFAULT NULL,
  `area` varchar(255) NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `position` varchar(10) DEFAULT NULL,
  `status` varchar(5) NOT NULL DEFAULT '0' COMMENT '0 = no action, 1 subdivided, 2 = titled',
  `land_title_id` int(11) DEFAULT NULL,
  `subdivided_to` varchar(10) DEFAULT NULL COMMENT 'null = not subdivided, put the subdivided address if subdivided',
  `date_approved` date NOT NULL DEFAULT current_timestamp(),
  `application_no` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `land_titles`
--
ALTER TABLE `land_titles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subdivided_titles`
--
ALTER TABLE `subdivided_titles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `land_title_id` (`land_title_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `land_titles`
--
ALTER TABLE `land_titles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `subdivided_titles`
--
ALTER TABLE `subdivided_titles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `subdivided_titles`
--
ALTER TABLE `subdivided_titles`
  ADD CONSTRAINT `subdivided_titles_ibfk_1` FOREIGN KEY (`land_title_id`) REFERENCES `land_titles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
