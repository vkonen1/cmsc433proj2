-- phpMyAdmin SQL Dump
-- version 4.6.0
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 20, 2016 at 06:09 PM
-- Server version: 10.0.23-MariaDB
-- PHP Version: 5.6.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `advisingInfo`
--

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `CID` varchar(6) NOT NULL,
  `type` varchar(4) NOT NULL,
  `name` varchar(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Course IDs';

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`CID`, `type`, `name`) VALUES
('050049', 'cmsc', 'CMSC352'),
('051115', 'sci', 'GES120'),
('051203', 'sci', 'GES110'),
('051363', 'sci', 'GES111'),
('052484', 'sci', 'BIOL251'),
('052485', 'sci', 'BIOL251'),
('052486', 'sci', 'BIOL252'),
('052487', 'sci', 'BIOL252'),
('052488', 'sci', 'BIOL275'),
('052489', 'sci', 'BIOL275'),
('052494', 'sci', 'BIOL302'),
('052495', 'sci', 'BIOL302'),
('052496', 'sci', 'BIOL303'),
('052498', 'sci', 'BIOL303'),
('052499', 'sci', 'BIOL304'),
('052500', 'sci', 'BIOL304'),
('052501', 'sci', 'BIOL305'),
('052502', 'sci', 'BIOL305'),
('052671', 'sci', 'CHEM101'),
('052673', 'sci', 'CHEM102'),
('052675', 'sci', 'CHEM102'),
('052879', 'cmsc', 'CMSC201'),
('052881', 'cmsc', 'CMSC202'),
('052883', 'cmsc', 'CMSC203'),
('052896', 'cmsc', 'CMSC232'),
('052903', 'cmsc', 'CMSC291'),
('052904', 'cmsc', 'CMSC299'),
('052907', 'cmsc', 'CMSC304'),
('052911', 'cmsc', 'CMSC313'),
('052913', 'cmsc', 'CMSC331'),
('052914', 'cmsc', 'CMSC341'),
('052920', 'cmsc', 'CMSC391'),
('052921', 'cmsc', 'CMSC404'),
('052922', 'cmsc', 'CMSC411'),
('052928', 'cmsc', 'CMSC421'),
('052931', 'cmsc', 'CMSC426'),
('052932', 'cmsc', 'CMSC427'),
('052933', 'cmsc', 'CMSC431'),
('052934', 'cmsc', 'CMSC432'),
('052935', 'cmsc', 'CMSC433'),
('052936', 'cmsc', 'CMSC435'),
('052937', 'cmsc', 'CMSC436'),
('052938', 'cmsc', 'CMSC437'),
('052940', 'cmsc', 'CMSC441'),
('052941', 'cmsc', 'CMSC442'),
('052942', 'cmsc', 'CMSC443'),
('052943', 'cmsc', 'CMSC444'),
('052944', 'cmsc', 'CMSC448'),
('052945', 'cmsc', 'CMSC446'),
('052947', 'cmsc', 'CMSC451'),
('052948', 'cmsc', 'CMSC452'),
('052949', 'cmsc', 'CMSC453'),
('052951', 'cmsc', 'CMSC455'),
('052952', 'cmsc', 'CMSC456'),
('052954', 'cmsc', 'CMSC461'),
('052955', 'cmsc', 'CMSC465'),
('052956', 'cmsc', 'CMSC466'),
('052958', 'cmsc', 'CMSC471'),
('052960', 'cmsc', 'CMSC473'),
('052962', 'cmsc', 'CMSC475'),
('052963', 'cmsc', 'CMSC476'),
('052964', 'cmsc', 'CMSC477'),
('052965', 'cmsc', 'CMSC478'),
('052966', 'cmsc', 'CMSC479'),
('052968', 'cmsc', 'CMSC481'),
('052970', 'cmsc', 'CMSC483'),
('052971', 'cmsc', 'CMSC484'),
('052972', 'cmsc', 'CMSC486'),
('052973', 'cmsc', 'CMSC487'),
('052975', 'cmsc', 'CMSC491'),
('052976', 'cmsc', 'CMSC493'),
('052977', 'cmsc', 'CMSC495'),
('052980', 'cmsc', 'CMSC498'),
('052981', 'cmsc', 'CMSC499'),
('054546', 'sci', 'GES286'),
('055205', 'math', 'MATH151'),
('055208', 'math', 'MATH152'),
('055216', 'math', 'MATH221'),
('055218', 'sci', 'MATH225'),
('055219', 'sci', 'MATH251'),
('055224', 'sci', 'MATH301'),
('055232', 'cmsc', 'MATH381'),
('055253', 'cmsc', 'MATH430'),
('055255', 'cmsc', 'MATH441'),
('055256', 'cmsc', 'MATH452'),
('055265', 'cmsc', 'MATH475'),
('055269', 'cmsc', 'MATH481'),
('055271', 'cmsc', 'MATH483'),
('056129', 'sci', 'PHYS121'),
('056131', 'sci', 'PHYS122'),
('056132', 'sci', 'PHYS122'),
('056138', 'sci', 'PHYS224'),
('056141', 'sci', 'PHYS304'),
('057054', 'math', 'STAT355'),
('100191', 'cmsc', 'CMSC457'),
('100315', 'sci', 'BIOL141'),
('100316', 'sci', 'BIOL142'),
('100317', 'sci', 'BIOL300'),
('101927', 'cmsc', 'CMSC447');

-- --------------------------------------------------------

--
-- Table structure for table `userClassLink`
--

CREATE TABLE `userClassLink` (
  `SID` varchar(8) NOT NULL,
  `CID` varchar(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Links students to courses taken by ids (entry per link)';

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `SID` varchar(8) NOT NULL,
  `fname` tinytext NOT NULL,
  `minitial` varchar(1) NOT NULL,
  `lname` tinytext NOT NULL,
  `email` tinytext NOT NULL,
  `phone` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='User submitted information';

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`SID`, `fname`, `minitial`, `lname`, `email`, `phone`) VALUES
('FT03090', 'a', 'a', 'a', 'asd@aw.ca', '144-151-541');

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
