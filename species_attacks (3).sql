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
-- Table structure for table `species_attacks`
--

CREATE TABLE `species_attacks` (
  `id` int(5) NOT NULL,
  `name` varchar(100) NOT NULL,
  `attack` varchar(100) NOT NULL,
  `defense` varchar(100) NOT NULL,
  `item_spawn` varchar(100) NOT NULL,
  `species` varchar(100) NOT NULL,
  `level` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `species_attacks`
--

INSERT INTO `species_attacks` (`id`, `name`, `attack`, `defense`, `item_spawn`, `species`, `level`) VALUES
(1003, 'Acara Dunk', 'water,2', '', '', 'acara', 1),
(1004, 'Acara Splash', 'water,2:air,2', '', '', 'acara', 14),
(1005, 'Aisha Pounce', 'phys,2', '', '', 'aisha', 3),
(1006, 'Aisha Sonar', 'air,2', '', '', 'aisha', 14),
(1007, 'Aisha Sneak', 'air,3:earth,3', '', '', 'aisha', 22),
(1008, 'Blumaroo Whistle', 'air,3:earth,3', '', '', 'blumaroo', 1),
(1009, 'Blumaroo Dodge', '', 'phys,3', '', 'blumaroo', 2),
(1010, 'Blumaroo Punch', 'light,2', '', '', 'blumaroo', 15),
(1011, 'Bori Scratch', 'phys,2', '', '', 'bori', 1),
(1012, 'Bori Roll', '', 'earth,2:phys,2', '', 'bori', 12),
(1013, 'Bori Bellow', 'air,3:phys,3', '', '', 'bori', 20),
(1014, 'Bruce Slide', '', 'water,(1;4;1;-1)', '', 'bruce', 1),
(1015, 'Bruce Jive', 'light,2', '', '', 'bruce', 1),
(1016, 'Bruce Thump', 'air,3:water,3', '', '', 'bruce', 1),
(1017, 'Buzz Sting', 'phys,2', '', '', 'buzz', 2),
(1018, 'Buzz Dive', 'air,2', '', '', 'buzz', 11),
(1019, 'Buzz Zoom', 'air,3:fire,3', '', '', 'buzz', 20),
(1020, 'Chia Smile', 'light,2', '', '', 'chia', 2),
(1021, 'Chia Giggle', 'air,2:light,2', '', '', 'chia', 20),
(1022, 'Chomby Spin', 'phys,2', '', '', 'chomby', 1),
(1023, 'Chomby Slam', 'earth,2:phys,2', '', '', 'chomby', 10),
(1024, 'Chomby Stomp', 'earth,3:air,3', '', '', 'chomby', 20),
(1025, 'Cybunny Hop', '', 'earth,2', '', 'cybunny', 1),
(1026, 'Cybunny Bump', 'earth,2', '', '', 'cybunny', 11),
(1027, 'Cybunny Freeze', 'air,3:water,3', '', '', 'cybunny', 20),
(1028, 'Draik Fire Spout', 'fire,2', '', '', 'draik', 1),
(1029, 'Draik Rocket', 'air,3', '', '', 'draik', 10),
(1030, 'Draik Gaze of Evil', 'fire,3:dark,3', '', '', 'draik', 20),
(1031, 'Elephante Stomp', 'phys,2', '', '', 'elephante', 1),
(1032, 'Elephante Trumpet', 'air,2:phys,2', '', '', 'elephante', 10),
(1033, 'Elephante Bump', 'earth,3:phys,3', '', '', 'elephante', 20),
(1034, 'Eyrie Scratch', 'phys,2', '', '', 'eyrie', 1),
(1035, 'Eyrie Dive', 'air,2:light,2', '', '', 'eyrie', 14),
(1036, 'Eyrie Glare', 'fire,3:dark,3', '', '', 'eyrie', 20),
(1037, 'Flotsam Flip', '', 'water,4', '', 'flotsam', 1),
(1038, 'Flotsam Whap', 'water,2', '', '', 'flotsam', 1),
(1039, 'Gelert Flash', 'phys,2', '', '', 'gelert', 1),
(1040, 'Gelert Bark', 'air,2', '', '', 'gelert', 10),
(1041, 'Gelert Jump', 'air,3:earth,3', '', '', 'gelert', 20),
(1042, 'Gnorbu Nudge', 'phys,2', '', '', 'gnorbu', 1),
(1043, 'Gnorbu Gnaw', 'fire,2', '', '', 'gnorbu', 12),
(1044, 'Gnorbu Cuff', 'earth,3:light,3', '', '', 'gnorbu', 20),
(1045, 'Grarrl Roar', 'earth,2', '', '', 'grarrl', 1),
(1046, 'Grarrl Swing', 'earth,2:dark,2', '', '', 'grarrl', 13),
(1047, 'Grarrl Snap', 'air,3:phys,3', '', '', 'grarrl', 20),
(1048, 'Grundo Blast', 'air,2', '', '', 'grundo', 1),
(1049, 'Grundo Star', 'dark,2', '', '', 'grundo', 10),
(1050, 'Hissi Wing Blast', 'dark,2', '', '', 'hissi', 3),
(1051, 'Hissi Strike', 'earth,2', '', '', 'hissi', 12),
(1052, 'Hissi Squeeze', 'light,2:phys,2', '', '', 'hissi', 20),
(1053, 'Ixi Charge', 'earth,2', '', '', 'ixi', 1),
(1054, 'Ixi Clout', 'light,3:phys,3', '', '', 'ixi', 1),
(1055, 'Ixi Leap', 'air,2:earth,2', '', '', 'ixi', 8),
(1056, 'Jetsam Snap', 'phys,2', '', '', 'jetsam', 3),
(1057, 'Jetsam Splash', 'water,2:dark,2', '', '', 'jetsam', 13),
(1058, 'Jetsam Growl', 'air,3:phys,3', '', '', 'jetsam', 20),
(1059, 'Jubjub Roll', 'phys,2', '', '', 'jubjub', 1),
(1060, 'Jubjub Scream', 'air,2', '', '', 'jubjub', 1),
(1061, 'Jubjub BAM', 'earth,3:phys,3', '', '', 'jubjub', 1),
(1062, 'Kacheek Roll', '', 'earth,2:phys,2', '', 'kacheek', 10),
(1063, 'Kacheek Smil', 'earth,3:light,3', '', '', 'kacheek', 20),
(1064, 'Kau Charge', 'phys,2', '', '', 'kau', 2),
(1065, 'Kau Kick', 'earth,2', '', '', 'kau', 14),
(1066, 'Kau Restore', '', 'fire,2', '1152,3', 'kau', 20),
(1067, 'Kiko Expectorate', 'water,2', '', '', 'kiko', 1),
(1068, 'Kiko Stare', 'earth,2:light,2', '', '', 'kiko', 1),
(1069, 'Koi Dive', '', 'water,4', '', 'koi', 2),
(1070, 'Koi Splash', 'water,3', '', '', 'koi', 10),
(1071, 'Koi Flap', 'water,3:air,3', '', '', 'koi', 20),
(1072, 'Korbat Shroud', '', 'light,4', '', 'korbat', 3),
(1073, 'Korbat Swoop', 'air,2', '', '', 'korbat', 10),
(1074, 'Korbat Hover', '', 'earth,4:phys,4', '', 'korbat', 20),
(1075, 'Kougra Scratch', 'phys,2', '', '', 'kougra', 1),
(1076, 'Kougra Growl', 'earth,2', '', '', 'kougra', 12),
(1077, 'Kougra Swat', 'earth,3:light,3', '', '', 'kougra', 20),
(1078, 'Krawk Bite', 'phys,2', '', '', 'krawk', 1),
(1079, 'Krawk Fire Breath', 'fire,2:dark2', '', '', 'krawk', 1),
(1080, 'Krawk Death Stare', 'air,3:dark,3', '', '', 'krawk', 20),
(1081, 'Kyrii Growl', 'dark,2', '', '', 'kyrii', 1),
(1082, 'Kyrii Tackle', 'earth,2', '', '', 'kyrii', 12),
(1083, 'Lenny Fade Out', '', 'air,4', '', 'lenny', 1),
(1084, 'Lenny Peck', 'earth,2:air,2', '', '', 'lenny', 1),
(1085, 'Lenny Chatter', 'air,3:light,3', '', '', 'lenny', 20),
(1086, 'Lupe Prowl', 'dark,2', '', '', 'lupe', 3),
(1087, 'Lupe Lunge', 'dark,3:phys,2', '', '', 'lupe', 15),
(1088, 'Lupe Breath', 'air,3:dark,3', '', '', 'lupe', 20),
(1089, 'Lutari Tail Slap', 'phys,2', '', '', 'lutari', 1),
(1090, 'Lutari Nibble', 'earth,2', '', '', 'lutari', 5),
(1091, 'Lutari Swoop', 'water,2:air,2', '', '', 'lutari', 15),
(1092, 'Meerca Bounce', '', 'air,2', '', 'meerca', 5),
(1093, 'Meerca Zoom', 'air,2:light,2', '', '', 'meerca', 15),
(1094, 'Meerca Squeel', 'air,3:light,3', '', '', 'meerca', 20),
(1095, 'Moehog Kick', 'phys,2', '', '', 'moehog', 1),
(1096, 'Moehog Ram', 'earth,2:light,2', '', '', 'moehog', 14),
(1097, 'Moehog Nip', 'fire,3:phys,3', '', '', 'moehog', 20),
(1098, 'Mynci Thwack', 'phys,2', '', '', 'mynci', 3),
(1099, 'Mynci Swing', 'air,2:earth,2', '', '', 'mynci', 13),
(1100, 'Mynci Wham', 'light,3:phys,3', '', '', 'mynci', 20),
(1101, 'Nimmo Leap', '', 'earth,2', '', 'nimmo', 2),
(1102, 'Nimmo Levitate', 'air,2', '', '', 'nimmo', 12),
(1103, 'Nimmo Chant', 'air,3:light,3', '', '', 'nimmo', 20),
(1104, 'Ogrin Stupefy', 'earth,2', '', '', 'ogrin', 1),
(1105, 'Ogrin Stench', 'air,2', '', '', 'ogrin', 1),
(1106, 'Ogrin Chomp', 'fire,3:phys,3', '', '', 'ogrin', 20),
(1107, 'Peophin Trample', 'phys,2', '', '', 'peophin', 3),
(1108, 'Peophin Dive', '', 'water,2:earth,2', '', 'peophin', 13),
(1109, 'Peophin Splash', 'water,3:light,3', '', '', 'peophin', 20),
(1110, 'Poogle Bite', 'phys,2', '', '', 'poogle', 1),
(1111, 'Poogle Roll', 'earth,2', '', '', 'poogle', 1),
(1112, 'Poogle Jump', 'air,3:light,3', '', '', 'poogle', 20),
(1113, 'Pteri Peck', 'earth,2', '', '', 'pteri', 3),
(1114, 'Pteri Evade', '', 'air,2', '', 'pteri', 10),
(1115, 'Pteri Dive', 'air,3:phys,3', '', '', 'pteri', 20),
(1116, 'Quiggle Stomp', 'air,2', '', '', 'quiggle', 2),
(1117, 'Quiggle SPlash', 'water,2', '', '', 'quiggle', 11),
(1118, 'Quiggle Jump', 'light,3:air,3', '', '', 'quiggle', 20),
(1119, 'Ruki Pinch', 'fire,2', '', '', 'ruki', 2),
(1120, 'Ruki Rage', 'phys,2', '', '', 'ruki', 10),
(1121, 'Ruki Radar', 'fire,3:light,3', '', '', 'ruki', 20),
(1122, 'Scorchio Flame', 'fire,2', '', '', 'scorchio', 3),
(1123, 'Scorchio Flap', 'air,2', '', '', 'scorchio', 13),
(1124, 'Scorchio Restore', '', '', '1152,3', 'scorchio', 20),
(1125, 'Shoyru Dive', 'air,2', '', '', 'shoyru', 3),
(1126, 'Shoyru Inferno', 'fire,3:light,3', '', '', 'shoyru', 20),
(1127, 'Skeith Squash', 'phys,2', '', '', 'skeith', 2),
(1128, 'Skeith Glare', 'dark,2:fire,2', '', '', 'skeith', 10),
(1129, 'Skeith Bite', 'earth,3:dark,3', '', '', 'skeith', 20),
(1130, 'Techo Glare', 'fire,2', '', '', 'techo', 2),
(1131, 'Techo Jab', 'earth,2:phys,2', '', '', 'techo', 15),
(1132, 'Techo Kick', 'light,3:phys,3', '', '', 'techo', 20),
(1133, 'Tonu Charge', 'earth,2:dark,2', '', '', 'tonu', 5),
(1134, 'Tonu Dodge', '', 'phys,3', '', 'tonu', 11),
(1135, 'Tonu Death Stomp', 'earth,2:phys,2', '', '', 'tonu', 21),
(1136, 'Tuskaninny Bounce', 'phys,2', '', '', 'tuskaninny', 1),
(1137, 'Tuskaninny Punch', 'air,2:phys,2', '', '', 'tuskaninny', 12),
(1138, 'Tuskaninny Splash', 'air,3:water,3', '', '', 'tuskaninny', 20),
(1139, 'Uni Kick', 'phys,2', '', '', 'uni', 3),
(1140, 'Uni Slap', 'air,2:earth,2', '', '', 'uni', 10),
(1141, 'Uni Rejuvenate', '', '', '1152,3', 'uni', 20),
(1142, 'Usul Bite', 'phys,2', '', '', 'usul', 1),
(1143, 'Usul Growl', 'earth,2:dark,2', '', '', 'usul', 1),
(1144, 'Usul Glare', 'fire,3:dark,3', '', '', 'usul', 20),
(1145, 'Wocky Growl', 'earth,2', '', '', 'wocky', 1),
(1146, 'Wocky leap', 'earth,2', '', '', 'wocky', 10),
(1147, 'Wocky Snarl', 'dark,3:fire,3', '', '', 'wocky', 20),
(1148, 'Xweetok Switch', 'earth,2', '', '', 'xweetok', 1),
(1149, 'Xweetock Snarl', 'fire,2:dark,2', '', '', 'xweetok', 15),
(1150, 'Yurble Yawn', 'air,2', '', '', 'yurble', 1),
(1151, 'Yurble Tumble', 'earth,2', '', '', 'yurble', 2),
(1152, 'Yurble Yodel', 'air,3:light,3', '', '', 'yurble', 20),
(1153, 'Zafara Flame', 'fire,2', '', '', 'zafara', 2),
(1154, 'Zafara Leap', 'air,2:earth,2', '', '', 'zafara', 14),
(1155, 'Zafara Strike', 'fire,3:phys,3', '', '', 'zafara', 20);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `species_attacks`
--
ALTER TABLE `species_attacks`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `species_attacks`
--
ALTER TABLE `species_attacks`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1156;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
