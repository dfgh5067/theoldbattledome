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
-- Table structure for table `abilities`
--

CREATE TABLE `abilities` (
  `id` int(2) NOT NULL,
  `name` varchar(100) NOT NULL,
  `power_req` varchar(5) NOT NULL,
  `attack` varchar(100) NOT NULL,
  `defense` varchar(100) NOT NULL,
  `heal` varchar(100) NOT NULL,
  `drain` varchar(100) NOT NULL,
  `freeze` varchar(100) NOT NULL,
  `useage` varchar(100) NOT NULL,
  `special_function` varchar(100) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `abilities`
--

INSERT INTO `abilities` (`id`, `name`, `power_req`, `attack`, `defense`, `heal`, `drain`, `freeze`, `useage`, `special_function`, `image`) VALUES
(101, 'Steam Shield', '0', '', 'light,1100', '', '', '', '2', '', 'http://images.neopets.com/abilities/steamshield.gif'),
(102, 'Drain Life', '40', '', '', '', '1011,100,c,h', '', '2', '', 'http://images.neopets.com/abilities/drainlife.gif'),
(103, 'Boil', '0', '', 'water,1100', '', '', '', '2', '', 'http://images.neopets.com/abilities/boil.gif'),
(104, 'Healing Vapour', '40', '', '', '1033,m', '', '', '2', '', 'http://images.neopets.com/abilities/healingvapour.gif'),
(105, 'Burrow', '40', '', 'air,1100:earth,1100:fire,1100:water,1100:phys,1100', '', '', '', '2', '', 'http://images.neopets.com/abilities/burrow.gif'),
(106, 'Regeneration', '40', '', '', '1033,m', '', '', '2', '', 'http://images.neopets.com/abilities/regeneration.gif'),
(107, 'Restore', '40', '', '', '1033,m', '', '', '2', '', 'http://images.neopets.com/abilities/restore.gif'),
(108, 'Diamond Dust', '0', '', '', '', '', '.2', '1', '', 'http://images.neopets.com/abilities/diamonddust.gif'),
(109, 'Sink', '40', '', 'dark,1100:light,1100:water,1100:fire,1100:phys,1100', '', '', '', '2', '', 'http://images.neopets.com/abilities/sink.gif'),
(110, 'Fiery Gaze', '0', '', '', '', '', '.2', '1', '', 'http://images.neopets.com/abilities/fierygaze.gif'),
(111, 'Aura', '0', 'light,1', 'dark,1', '', '', '', '', '', ''),
(112, 'Dark Shield', '0', '', 'light,1', '', '', '', '', '', ''),
(113, 'Demon Breath', '0', '', '', '', '', '', '', '', ''),
(114, 'Fireball', '0', '', '', '', '', '', '', '', ''),
(115, 'Frost', '0', 'water,1', 'fire,1', '', '', '', '', '', ''),
(116, 'Inertia', '0', 'air,1', 'earth,1', '', '', '', '', '', ''),
(117, 'Negg', '0', '', '', '', '', '', '', '', ''),
(118, 'Psychic Attack', '0', 'earth,1', 'earth,1', '', '', '', '', '', ''),
(119, 'Scorch', '0', 'fire,1:light,1', 'water,1:dark,1', '', '', '', '', '', ''),
(120, 'Stone Legion', '0', '', '', '', '', '', '', '', ''),
(121, 'Sun Ray', '0', 'air,1', 'dark,1', '', '', '', '', '', ''),
(122, 'Twisting Vines', '0', 'dark,1:phys,1', '', '', '', '', '', '', ''),
(123, 'Quench', '0', '', 'fire,1100', '', '', '', '2', '', 'http://images.neopets.com/abilities/quench.gif'),
(124, 'Water Breathing', '0', '', 'water,1100', '', '', '', '2', '', 'http://images.neopets.com/abilities/waterbreathing.gif');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `abilities`
--
ALTER TABLE `abilities`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `abilities`
--
ALTER TABLE `abilities`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=125;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
