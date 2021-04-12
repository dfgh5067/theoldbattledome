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
-- Table structure for table `playerdb`
--

CREATE TABLE `playerdb` (
  `id` int(3) NOT NULL,
  `user` varchar(16) NOT NULL,
  `password` varchar(255) NOT NULL,
  `pets` varchar(85) NOT NULL,
  `active_pet` varchar(16) NOT NULL,
  `opponents` varchar(1000) NOT NULL,
  `opp1` varchar(255) NOT NULL,
  `opp6` varchar(255) NOT NULL,
  `opp7` varchar(255) NOT NULL,
  `opp8` varchar(255) NOT NULL,
  `opp9` varchar(255) NOT NULL,
  `opp10` varchar(255) NOT NULL,
  `opp11` varchar(255) NOT NULL,
  `opp12` varchar(255) NOT NULL,
  `opp13` varchar(255) NOT NULL,
  `opp14` varchar(255) NOT NULL,
  `opp16` varchar(255) NOT NULL,
  `opp17` varchar(255) NOT NULL,
  `opp18` varchar(255) NOT NULL,
  `opp19` varchar(255) NOT NULL,
  `opp22` varchar(255) NOT NULL,
  `opp24` varchar(255) NOT NULL,
  `opp27` varchar(255) NOT NULL,
  `opp28` varchar(255) NOT NULL,
  `opp29` varchar(255) NOT NULL,
  `opp30` varchar(255) NOT NULL,
  `opp31` varchar(255) NOT NULL,
  `opp33` varchar(255) NOT NULL,
  `opp34` varchar(255) NOT NULL,
  `opp35` varchar(255) NOT NULL,
  `opp36` varchar(255) NOT NULL,
  `opp37` varchar(255) NOT NULL,
  `opp39` varchar(255) NOT NULL,
  `opp40` varchar(255) NOT NULL,
  `opp41` varchar(255) NOT NULL,
  `opp42` varchar(255) NOT NULL,
  `opp44` varchar(255) NOT NULL,
  `opp45` varchar(255) NOT NULL,
  `opp46` varchar(255) NOT NULL,
  `opp47` varchar(255) NOT NULL,
  `opp48` varchar(255) NOT NULL,
  `opp49` varchar(255) NOT NULL,
  `opp50` varchar(255) NOT NULL,
  `opp51` varchar(255) NOT NULL,
  `opp52` varchar(255) NOT NULL,
  `opp54` varchar(255) NOT NULL,
  `opp55` varchar(255) NOT NULL,
  `opp56` varchar(255) NOT NULL,
  `opp57` varchar(255) NOT NULL,
  `opp58` varchar(255) NOT NULL,
  `opp59` varchar(255) NOT NULL,
  `opp60` varchar(255) NOT NULL,
  `opp61` varchar(255) NOT NULL,
  `opp62` varchar(255) NOT NULL,
  `opp63` varchar(255) NOT NULL,
  `opp64` varchar(255) NOT NULL,
  `opp65` varchar(255) NOT NULL,
  `opp66` varchar(255) NOT NULL,
  `opp185` varchar(255) NOT NULL,
  `opp189` varchar(255) NOT NULL,
  `opp207` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `playerdb`
--

INSERT INTO `playerdb` (`id`, `user`, `password`, `pets`, `active_pet`, `opponents`, `opp1`, `opp6`, `opp7`, `opp8`, `opp9`, `opp10`, `opp11`, `opp12`, `opp13`, `opp14`, `opp16`, `opp17`, `opp18`, `opp19`, `opp22`, `opp24`, `opp27`, `opp28`, `opp29`, `opp30`, `opp31`, `opp33`, `opp34`, `opp35`, `opp36`, `opp37`, `opp39`, `opp40`, `opp41`, `opp42`, `opp44`, `opp45`, `opp46`, `opp47`, `opp48`, `opp49`, `opp50`, `opp51`, `opp52`, `opp54`, `opp55`, `opp56`, `opp57`, `opp58`, `opp59`, `opp60`, `opp61`, `opp62`, `opp63`, `opp64`, `opp65`, `opp66`, `opp185`, `opp189`, `opp207`) VALUES
(1, 'dfgh5067', '', 'Samuelosaurus:test:bestspecies', 'Samuelosaurus', '1:3:5:2:4:6:7:19:189', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(11, 'test', '', 'tester', '', '', '', '', '', '', '', '', '', '', '1:0:0:160:0:', '', '1009:2:0:2520:0:', '', '', '13:3:2:5188:0:', '24:1:0:3025:0:', '', '', '', '', '', '', '', '', '', '', '0:1:0:0:0:', '454', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '2:1:0:1455:0:', '', '9:1:2:2749:0:', '', '', '1:0:0:185:0:', '0:1:0:0:0:', ''),
(4, 'test2', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(6, 'addpettest', '', 'temp_pet_6', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(7, 'addpettest2', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(8, 'addpettest3', '', 'temp_pet_8', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(9, 'logintest', '', 'temp_pet_9', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(10, 'testagain', '', 'temp_pet_10', '', '', '', '', '', '', '', '', '', '', '', '', '2:0:0:280:0:', '', '', '', '1:0:0:135:0:', '', '', '', '', '', '', '', '', '', '', '', '2:0:0:284:0:', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(12, 'pwverify', '', 'temp_pet_12', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(13, 'cblogtest', '', 'temp_pet_13', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(14, 'cblogtest2', '', 'temp_pet_14', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(15, 'cblogtest3', '', 'newname', '', '', '', '', '', '', '', '', '', '', '', '', '6:0:0:836:0:', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(16, 'hsttest', '', 'temp_pet_16', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(17, 'testdon', '', 'temp_pet_17', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(18, 'testopp', '', 'temp_pet_18', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(19, '2pchallenger', '', 'temp_pet_19', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(20, '2pdraintest', '', 'draintest', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `playerdb`
--
ALTER TABLE `playerdb`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `playerdb`
--
ALTER TABLE `playerdb`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
