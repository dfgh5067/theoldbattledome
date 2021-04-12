-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 12, 2021 at 09:53 AM
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
-- Table structure for table `active_battles`
--

CREATE TABLE `active_battles` (
  `id` int(10) NOT NULL,
  `pet` varchar(16) NOT NULL,
  `opponent` varchar(100) NOT NULL,
  `pet_hp` varchar(100) NOT NULL,
  `pet_power` varchar(3) NOT NULL,
  `opp_hp` varchar(100) NOT NULL,
  `opp_power` varchar(3) NOT NULL,
  `opp_str` int(10) NOT NULL,
  `opp_def` int(10) NOT NULL,
  `pet_set` varchar(1000) NOT NULL,
  `pet_abilities` varchar(100) NOT NULL,
  `opp_set` varchar(1000) NOT NULL,
  `opp_abilities` varchar(100) NOT NULL,
  `pet_frozen` varchar(1) NOT NULL,
  `opp_frozen` varchar(1) NOT NULL,
  `combat_log` varchar(50000) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `active_battles`
--

INSERT INTO `active_battles` (`id`, `pet`, `opponent`, `pet_hp`, `pet_power`, `opp_hp`, `opp_power`, `opp_str`, `opp_def`, `pet_set`, `pet_abilities`, `opp_set`, `opp_abilities`, `pet_frozen`, `opp_frozen`, `combat_log`) VALUES
(512, 'tester', '189', '676/1000', '100', '16976/17110', '100', 17110, 17110, '20:35:1065:3564:1062:2288:1439:1416', '102:103:104:105:109', '850:50:763:1062:925:1416', '102:108', '-', '-', ''),
(513, 'draintest', '16', '1000/1000', '100', '50/50', '100', 50, 50, '20:757:1766:1065:1586:1603:61:148:148', '102:105:109:110', '914:2797:1242:448:449:636:909', '115:116:119:114:118', '0', '0', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `active_battles`
--
ALTER TABLE `active_battles`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `active_battles`
--
ALTER TABLE `active_battles`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=514;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
