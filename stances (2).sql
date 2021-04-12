-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 12, 2021 at 08:55 AM
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
-- Table structure for table `stances`
--

CREATE TABLE `stances` (
  `id` int(1) NOT NULL,
  `name` varchar(100) NOT NULL,
  `multiplier` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `stances`
--

INSERT INTO `stances` (`id`, `name`, `multiplier`) VALUES
(-2, 'Improved Defend', '0.7'),
(-1, 'Defend', '0.8'),
(1, 'Cautious Attack', '1'),
(2, 'Normal Attack', '1.2'),
(3, 'Jump and Attack', '1.3'),
(4, 'Fierce Attack', '1.4'),
(5, 'Berserk Attack', '1.5');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `stances`
--
ALTER TABLE `stances`
  ADD PRIMARY KEY (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
