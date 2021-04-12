-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 12, 2021 at 08:54 AM
-- Server version: 10.1.24-MariaDB
-- PHP Version: 7.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bdsim5067`
--

-- --------------------------------------------------------

--
-- Table structure for table `species_resist`
--

CREATE TABLE `species_resist` (
  `id` int(3) NOT NULL,
  `species` varchar(20) NOT NULL,
  `fire` varchar(10) NOT NULL,
  `water` varchar(10) NOT NULL,
  `earth` varchar(10) NOT NULL,
  `air` varchar(10) NOT NULL,
  `light` varchar(10) NOT NULL,
  `dark` varchar(10) NOT NULL,
  `phys` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `species_resist`
--

INSERT INTO `species_resist` (`id`, `species`, `fire`, `water`, `earth`, `air`, `light`, `dark`, `phys`) VALUES
(1, 'usul', '1.1', '1.1', '', '', '', '.8', ''),
(2, 'pteri', '', '', '', '.6', '', '', ''),
(3, 'kau', '.9', '.9', '.9', '.9', '.9', '', '.9'),
(4, 'blumaroo', '', '', '', '.9', '', '', ''),
(5, 'bruce', '', '.8', '', '', '', '', ''),
(6, 'buzz', '', '', '', '.8', '', '', ''),
(7, 'chomby', '.9', '', '', '', '', '', '.8'),
(8, 'cybunny', '', '', '.8', '', '', '', ''),
(9, 'draik', '.9', '', '.9', '', '', '', ''),
(10, 'elephante', '', '', '', '', '', '', '.8'),
(11, 'eyrie', '', '', '', '.8', '', '', ''),
(12, 'grarrl', '', '', '', '', '', '', '.8'),
(13, 'grundo', '', '', '', '', '', '.7', ''),
(14, 'jubjub', '1.2', '', '', '.7', '', '', '.8'),
(15, 'ixi', '', '', '.7', '', '', '1.2', ''),
(16, 'korbat', '', '', '', '', '1.3', '.7', ''),
(17, 'nimmo', '', '.8', '', '', '', '', ''),
(18, 'peophin', '1.15', '.8', '.9', '', '', '', ''),
(19, 'scorchio', '.7', '', '.9', '.9', '', '', ''),
(20, 'poogle', '.9', '', '.9', '', '', '', ''),
(21, 'shoyru', '.8', '', '', '', '', '', ''),
(22, 'skeith', '.9', '', '.9', '', '', '', '.9'),
(23, 'tonu', '', '', '', '', '', '', '.85'),
(24, 'tuskaninny', '', '.8', '', '', '', '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `species_resist`
--
ALTER TABLE `species_resist`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `species_resist`
--
ALTER TABLE `species_resist`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
