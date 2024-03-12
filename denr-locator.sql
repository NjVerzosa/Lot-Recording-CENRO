-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 04, 2024 at 03:59 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `denr-locator`
--

-- --------------------------------------------------------

--
-- Table structure for table `boxes`
--

CREATE TABLE `boxes` (
  `id` int(11) NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `cadastral` varchar(255) DEFAULT NULL,
  `case_number` int(4) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `range_val` varchar(255) DEFAULT NULL,
  `no_records` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `boxes`
--

INSERT INTO `boxes` (`id`, `category`, `cadastral`, `case_number`, `location`, `range_val`, `no_records`) VALUES
(14, 'Lot Data Computation', 'Dasol', 1, '3, 4, 1', '2-272', '152, 153, 155, 156, 181, 182, 235, 236, 245, 247, 249'),
(15, 'Lot Data Computation', 'Dasol', 1, '3, 4, 1', '286-10205', '287, 301, 359-363, 390, 394, 476, 478, 481-482'),
(16, 'Lot Data Computation', 'Dasol', 2, '3, 4, 1', '1040-1104', '1037, 1058'),
(17, 'Lot Data Computation', 'Dasol', 2, '3, 4, 2', '1151-1104', '1240 - 1250, 1254 - 1255, 1261 - 1263'),
(18, 'Lot Data Computation', 'Alaminos', 1, '3, 1, 1', '510-786', '513-610, 607, 736 - 739, 765'),
(19, 'Lot Data Computation', 'Alaminos', 2, '3, 1, 2', '1401-1600', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `boxes`
--
ALTER TABLE `boxes`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `boxes`
--
ALTER TABLE `boxes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
