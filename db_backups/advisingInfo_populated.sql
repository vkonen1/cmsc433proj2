-- phpMyAdmin SQL Dump
-- version 4.2.12deb2+deb8u1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 19, 2016 at 05:49 PM
-- Server version: 5.5.44-0+deb8u1
-- PHP Version: 5.6.17-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `advisingInfo`
--

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE IF NOT EXISTS `classes` (
  `CID` varchar(6) NOT NULL,
  `type` varchar(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Course IDs';

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`CID`, `type`) VALUES
('050049', 'cmsc'),
('051115', 'sci'),
('050049', 'cmsc'),
('051115', 'sci'),
('051203', 'sci'),
('051363', 'sci'),
('052484', 'sci'),
('052485', 'sci'),
('052486', 'sci'),
('052487', 'sci'),
('052488', 'sci'),
('052489', 'sci'),
('052494', 'sci'),
('052495', 'sci'),
('052496', 'sci'),
('052498', 'sci'),
('052499', 'sci'),
('052500', 'sci'),
('052501', 'sci'),
('052502', 'sci'),
('052671', 'sci'),
('052673', 'sci'),
('052675', 'sci'),
('052879', 'cmsc'),
('052881', 'cmsc'),
('052883', 'cmsc'),
('052896', 'cmsc'),
('052903', 'cmsc'),
('052904', 'cmsc'),
('052907', 'cmsc'),
('052911', 'cmsc'),
('052913', 'cmsc'),
('052914', 'cmsc'),
('052920', 'cmsc'),
('052921', 'cmsc'),
('052922', 'cmsc'),
('052928', 'cmsc'),
('052931', 'cmsc'),
('052932', 'cmsc'),
('052933', 'cmsc'),
('052934', 'cmsc'),
('052935', 'cmsc'),
('052936', 'cmsc'),
('052937', 'cmsc'),
('052938', 'cmsc'),
('052940', 'cmsc'),
('052941', 'cmsc'),
('052942', 'cmsc'),
('052943', 'cmsc'),
('052944', 'cmsc'),
('052945', 'cmsc'),
('052947', 'cmsc'),
('052948', 'cmsc'),
('052949', 'cmsc'),
('052951', 'cmsc'),
('052952', 'cmsc'),
('052954', 'cmsc'),
('052955', 'cmsc'),
('052956', 'cmsc'),
('052958', 'cmsc'),
('052960', 'cmsc'),
('052962', 'cmsc'),
('052963', 'cmsc'),
('052964', 'cmsc'),
('052965', 'cmsc'),
('052966', 'cmsc'),
('052968', 'cmsc'),
('052970', 'cmsc'),
('052971', 'cmsc'),
('052972', 'cmsc'),
('052973', 'cmsc'),
('052975', 'cmsc'),
('052976', 'cmsc'),
('052977', 'cmsc'),
('052980', 'cmsc'),
('052981', 'cmsc'),
('054546', 'sci'),
('055205', 'math'),
('055208', 'math'),
('055216', 'math'),
('055218', 'sci'),
('055219', 'sci'),
('055224', 'sci'),
('055232', 'cmsc'),
('055253', 'cmsc'),
('055255', 'cmsc'),
('055256', 'cmsc'),
('055265', 'cmsc'),
('055269', 'cmsc'),
('055271', 'cmsc'),
('056129', 'sci'),
('056131', 'sci'),
('056132', 'sci'),
('056138', 'sci'),
('056141', 'sci'),
('057054', 'math'),
('100191', 'cmsc'),
('100315', 'sci'),
('100316', 'sci'),
('100317', 'sci'),
('101927', 'cmsc');

-- --------------------------------------------------------

--
-- Table structure for table `userClassLink`
--

CREATE TABLE IF NOT EXISTS `userClassLink` (
  `SID` varchar(8) NOT NULL,
  `CID` varchar(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Links students to courses taken by ids (entry per link)';

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `SID` varchar(8) NOT NULL,
  `fname` tinytext NOT NULL,
  `minitial` varchar(1) NOT NULL,
  `lname` tinytext NOT NULL,
  `email` tinytext NOT NULL,
  `phone` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='User submitted information';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
 ADD PRIMARY KEY (`SID`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
