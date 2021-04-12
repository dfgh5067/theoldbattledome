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
-- Table structure for table `petdb`
--

CREATE TABLE `petdb` (
  `id` int(3) NOT NULL,
  `name` varchar(16) NOT NULL,
  `owner` varchar(16) NOT NULL,
  `species` varchar(16) NOT NULL,
  `colour` varchar(20) NOT NULL DEFAULT 'blue',
  `level` int(6) NOT NULL,
  `hp` int(6) NOT NULL,
  `str` int(6) NOT NULL,
  `def` int(6) NOT NULL,
  `spd` int(6) NOT NULL,
  `intel` int(6) NOT NULL,
  `weapons` varchar(1000) NOT NULL,
  `act_abil` varchar(1000) NOT NULL,
  `all_abil` varchar(1000) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `petdb`
--

INSERT INTO `petdb` (`id`, `name`, `owner`, `species`, `colour`, `level`, `hp`, `str`, `def`, `spd`, `intel`, `weapons`, `act_abil`, `all_abil`) VALUES
(1, 'Samuelosaurus', 'dfgh5067', 'pteri', 'blue', 50, 20000, 750, 500, 201, 1000, '20:1586:757:3262:1416:1065:2614:1836:61:935:2484:1766:1016:76:148:148', '100:102:108:106:105:109:110:101', ''),
(11, 'temp_pet_17', 'testdon', 'kau', 'blue', 50, 1000, 700, 700, 201, 1000, '20:757:1766:1065:1586:1603:61:148:148', '102:105:109:110', ''),
(10, 'temp_pet_16', 'hsttest', 'kau', 'blue', 50, 1000, 700, 700, 201, 1000, '20:757:1766:1065:1586:1603:61:148:148', '102:105:109:110', ''),
(4, 'temp_pet_10', 'testagain', 'kau', 'blue', 50, 1000, 700, 700, 201, 1000, '20:757:1766:1065:1586:1603:148:148', '102:105:109:110', ''),
(5, 'tester', 'test', 'acara', 'blue', 50, 1000, 700, 700, 201, 1000, '20:35:1065:3564:1062:2288:1439:1416', '102:103:104:105:109', ''),
(6, 'temp_pet_12', 'pwverify', 'kau', 'blue', 50, 1000, 700, 700, 201, 1000, '20:757:1766:1065:1586:1603:61:148:148', '102:105:109:110', ''),
(7, 'temp_pet_13', 'cblogtest', 'kau', 'blue', 50, 1000, 700, 700, 201, 1000, '20:757:1766:1065:1586:1603:61:148:148', '102:105:109:110', ''),
(8, 'temp_pet_14', 'cblogtest2', 'kau', 'blue', 50, 1000, 700, 700, 201, 1000, '20:757:1766:1065:1586:1603:61:148:148', '102:105:109:110', ''),
(9, 'newname', 'cblogtest3', 'kau', 'blue', 1, 25000, 1, 1, 201, 1000, '1058:1767:76:558:552:800:538:538', '102:105:109:110', ''),
(12, 'temp_pet_18', 'testopp', 'kau', 'blue', 50, 1000, 700, 700, 201, 1000, '20:757:1766:1065:1586:1603:61:148:148', '102:105:109:110', ''),
(13, 'temp_pet_19', '2pchallenger', 'kau', 'blue', 50, 1000, 700, 700, 201, 1000, '20:757:1766:1065:1586:1603:61:148:148', '102:105:109:110', ''),
(14, 'draintest', '2pdraintest', 'scorchio', 'candy', 50, 1000, 700, 700, 201, 1000, '20:757:1766:1065:1586:1603:61:148:148', '102:105:109:110', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `petdb`
--
ALTER TABLE `petdb`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `petdb`
--
ALTER TABLE `petdb`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
