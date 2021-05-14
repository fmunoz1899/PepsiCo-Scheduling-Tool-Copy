-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 14, 2021 at 11:03 PM
-- Server version: 5.7.26
-- PHP Version: 7.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pepsi_database`
--

-- --------------------------------------------------------

--
-- Table structure for table `ahours`
--

DROP TABLE IF EXISTS `ahours`;
CREATE TABLE IF NOT EXISTS `ahours` (
  `EmployeeID` bigint(20) NOT NULL,
  `DayID` varchar(3) NOT NULL,
  `StartTime` time DEFAULT NULL,
  `EndTime` time DEFAULT NULL,
  PRIMARY KEY (`EmployeeID`,`DayID`),
  KEY `EmployeeID` (`EmployeeID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ahours`
--

INSERT INTO `ahours` (`EmployeeID`, `DayID`, `StartTime`, `EndTime`) VALUES
(1, 'Fri', NULL, NULL),
(1, 'Mon', NULL, NULL),
(1, 'Sat', NULL, NULL),
(1, 'Sun', NULL, NULL),
(1, 'Thu', NULL, NULL),
(1, 'Tue', NULL, NULL),
(1, 'Wed', NULL, NULL),
(2, 'Fri', NULL, NULL),
(2, 'Mon', '06:00:00', '22:00:00'),
(2, 'Sat', '12:45:00', '22:00:00'),
(2, 'Sun', '06:00:00', '22:00:00'),
(2, 'Thu', NULL, NULL),
(2, 'Tue', '06:00:00', '22:00:00'),
(2, 'Wed', '06:00:00', '22:00:00'),
(3, 'Fri', NULL, NULL),
(3, 'Mon', NULL, NULL),
(3, 'Sat', NULL, NULL),
(3, 'Sun', NULL, NULL),
(3, 'Thu', NULL, NULL),
(3, 'Tue', NULL, NULL),
(3, 'Wed', NULL, NULL),
(4, 'Fri', '06:00:00', '22:00:00'),
(4, 'Mon', '06:00:00', '22:00:00'),
(4, 'Sat', '06:00:00', '22:00:00'),
(4, 'Sun', '06:00:00', '22:00:00'),
(4, 'Thu', '06:00:00', '22:00:00'),
(4, 'Tue', '06:00:00', '22:00:00'),
(4, 'Wed', '06:00:00', '22:00:00'),
(32, 'Fri', '06:00:00', '22:00:00'),
(32, 'Mon', '06:00:00', '22:00:00'),
(32, 'Sat', '06:00:00', '22:00:00'),
(32, 'Sun', '06:00:00', '22:00:00'),
(32, 'Thu', '06:00:00', '22:00:00'),
(32, 'Tue', '06:00:00', '22:00:00'),
(32, 'Wed', '12:00:00', '20:00:00'),
(33, 'Fri', NULL, NULL),
(33, 'Mon', NULL, NULL),
(33, 'Sat', NULL, NULL),
(33, 'Sun', NULL, NULL),
(33, 'Thu', NULL, NULL),
(33, 'Tue', NULL, NULL),
(33, 'Wed', NULL, NULL),
(50, 'Fri', '06:00:00', '07:30:00'),
(50, 'Mon', '08:00:00', '20:00:00'),
(50, 'Sat', NULL, NULL),
(50, 'Sun', NULL, NULL),
(50, 'Thu', NULL, NULL),
(50, 'Tue', NULL, NULL),
(50, 'Wed', NULL, NULL),
(52, 'Fri', NULL, NULL),
(52, 'Mon', '06:00:00', '07:45:00'),
(52, 'Sat', NULL, NULL),
(52, 'Sun', NULL, NULL),
(52, 'Thu', '06:00:00', '22:00:00'),
(52, 'Tue', NULL, NULL),
(52, 'Wed', NULL, NULL),
(64, 'Fri', '06:00:00', '22:00:00'),
(64, 'Mon', NULL, NULL),
(64, 'Sat', NULL, NULL),
(64, 'Sun', NULL, NULL),
(64, 'Thu', NULL, NULL),
(64, 'Tue', NULL, NULL),
(64, 'Wed', NULL, NULL),
(77, 'Fri', NULL, NULL),
(77, 'Mon', NULL, NULL),
(77, 'Sat', NULL, NULL),
(77, 'Sun', NULL, NULL),
(77, 'Thu', NULL, NULL),
(77, 'Tue', NULL, NULL),
(77, 'Wed', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `blackout`
--

DROP TABLE IF EXISTS `blackout`;
CREATE TABLE IF NOT EXISTS `blackout` (
  `BlackoutID` bigint(20) NOT NULL AUTO_INCREMENT,
  `EmployeeID` bigint(20) NOT NULL,
  `StartTime` time NOT NULL,
  `EndTime` time NOT NULL,
  `BDate` date NOT NULL,
  `Reason` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`BlackoutID`,`EmployeeID`),
  KEY `EmployeeID` (`EmployeeID`)
) ENGINE=InnoDB AUTO_INCREMENT=147 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `blackout`
--

INSERT INTO `blackout` (`BlackoutID`, `EmployeeID`, `StartTime`, `EndTime`, `BDate`, `Reason`) VALUES
(71, 32, '06:00:00', '06:15:00', '2021-04-28', ''),
(83, 32, '06:15:00', '06:30:00', '2021-04-28', ''),
(84, 32, '06:30:00', '06:45:00', '2021-04-28', ''),
(110, 4, '07:30:00', '08:00:00', '2021-05-02', 'More Testing'),
(111, 32, '08:00:00', '08:30:00', '2021-05-02', NULL),
(112, 32, '20:00:00', '22:00:00', '2021-05-02', ''),
(114, 32, '06:00:00', '06:15:00', '2021-05-05', ''),
(115, 32, '06:15:00', '06:45:00', '2021-05-10', ''),
(118, 32, '15:00:00', '17:00:00', '2021-05-04', 'test'),
(130, 32, '06:00:00', '06:15:00', '2021-05-14', ''),
(132, 32, '06:00:00', '06:15:00', '2021-05-21', ''),
(139, 32, '20:00:00', '20:15:00', '2021-05-11', ''),
(140, 4, '16:00:00', '17:30:00', '2021-05-12', 'Demo'),
(142, 32, '06:00:00', '07:00:00', '2021-05-13', 'This is a test to see what a long reason would look like on the engineer side'),
(144, 64, '20:00:00', '20:30:00', '2021-05-14', ''),
(145, 32, '18:30:00', '18:45:00', '2021-05-14', ''),
(146, 4, '18:30:00', '18:45:00', '2021-05-14', '');

-- --------------------------------------------------------

--
-- Table structure for table `delivery`
--

DROP TABLE IF EXISTS `delivery`;
CREATE TABLE IF NOT EXISTS `delivery` (
  `DeliveryID` bigint(20) NOT NULL AUTO_INCREMENT,
  `Method` varchar(5) NOT NULL,
  PRIMARY KEY (`DeliveryID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `delivery`
--

INSERT INTO `delivery` (`DeliveryID`, `Method`) VALUES
(1, 'BULK'),
(2, 'GEO'),
(3, 'FSV'),
(4, 'DBAY');

-- --------------------------------------------------------

--
-- Table structure for table `email`
--

DROP TABLE IF EXISTS `email`;
CREATE TABLE IF NOT EXISTS `email` (
  `Email` varchar(50) NOT NULL,
  `EmployeeID` bigint(20) NOT NULL,
  `Type` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`Email`,`EmployeeID`),
  KEY `Employee_ID_idx` (`EmployeeID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `email`
--

INSERT INTO `email` (`Email`, `EmployeeID`, `Type`) VALUES
('asd@ss', 4, 'Personal'),
('beep@boop', 50, 'Work'),
('d@rss', 4, 'Personal'),
('d@rsssfdsfsdf', 50, 'Personal'),
('example@email.com', 52, 'Work'),
('f', 33, 'Work'),
('f@ggrr', 1, 'Personal'),
('feliX@ioan.edu', 4, 'Work'),
('frankiemuno@gmail', 1, 'Personal'),
('g', 32, 'Work'),
('gfieri@pepsico.edu', 77, 'Work'),
('julianquack@yahoo.com', 2, 'Work'),
('k', 3, 'Work'),
('Pron@pepsico.edu', 64, 'Work'),
('r', 1, 'Work');

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

DROP TABLE IF EXISTS `employee`;
CREATE TABLE IF NOT EXISTS `employee` (
  `EmployeeID` bigint(20) NOT NULL AUTO_INCREMENT,
  `firstName` varchar(15) NOT NULL,
  `lastName` varchar(20) NOT NULL,
  `Epassword` varchar(64) NOT NULL,
  PRIMARY KEY (`EmployeeID`)
) ENGINE=InnoDB AUTO_INCREMENT=83 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`EmployeeID`, `firstName`, `lastName`, `Epassword`) VALUES
(1, 'Frankie', 'Munoz', 'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3'),
(2, 'Julian', 'TP', '175ec4662822c317c47638ef939e21b177dd99d8ca53d922a8de7b7c967ff416'),
(3, 'Kevin', 'G', 'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3'),
(4, 'Felix', 'P', 'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3'),
(32, 'Anakin', 'Skywalker', 'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3'),
(33, 'John', 'Smith', 'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3'),
(50, 'R2', 'D2', '1bda998a6a62d37df3171896eb1c4181fdcebef875c007d31d7f71859f9867c6'),
(52, 'Tim', 'John', '5270339f36116ff9ec8ee4b2bb881a38c34493dbd13614b4cc072c987fb95282'),
(64, 'Paul', 'Ron', '330eb31d7ecbf6cc920672905670a78adac5c44fc0d1cf369aec8f5a5d8ea756'),
(77, 'Guy', 'Fieri', '330eb31d7ecbf6cc920672905670a78adac5c44fc0d1cf369aec8f5a5d8ea756');

-- --------------------------------------------------------

--
-- Table structure for table `employeeprivlege`
--

DROP TABLE IF EXISTS `employeeprivlege`;
CREATE TABLE IF NOT EXISTS `employeeprivlege` (
  `EmployeeID` bigint(20) NOT NULL,
  `PrivilegeID` char(1) NOT NULL DEFAULT 'E',
  PRIMARY KEY (`EmployeeID`),
  KEY `PrivilegeID_idx` (`PrivilegeID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `employeeprivlege`
--

INSERT INTO `employeeprivlege` (`EmployeeID`, `PrivilegeID`) VALUES
(1, 'A'),
(2, 'E'),
(4, 'E'),
(32, 'E'),
(50, 'E'),
(52, 'E'),
(64, 'E'),
(3, 'M'),
(33, 'M'),
(77, 'M');

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

DROP TABLE IF EXISTS `location`;
CREATE TABLE IF NOT EXISTS `location` (
  `LocationID` bigint(20) NOT NULL AUTO_INCREMENT,
  `LocationName` varchar(45) NOT NULL,
  `StreetAdress` varchar(45) NOT NULL,
  `City` varchar(45) NOT NULL,
  `State` varchar(2) NOT NULL,
  `Zip` int(5) NOT NULL,
  PRIMARY KEY (`LocationID`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `location`
--

INSERT INTO `location` (`LocationID`, `LocationName`, `StreetAdress`, `City`, `State`, `Zip`) VALUES
(1, 'playground', '89 street road', 'that one', 'AZ', 42069),
(2, 'my house', '515 tenicey ave', 'city in TN', 'TN', 69696),
(3, 'ivanov backyard', '69 that road', 'Jersey place', 'NJ', 63209),
(4, 'Rec Center', '23 road', 'city city', 'OH', 39671);

-- --------------------------------------------------------

--
-- Table structure for table `phone`
--

DROP TABLE IF EXISTS `phone`;
CREATE TABLE IF NOT EXISTS `phone` (
  `PhoneNumber` varchar(10) NOT NULL,
  `EmployeeID` bigint(20) NOT NULL,
  `Type` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`PhoneNumber`,`EmployeeID`),
  KEY `EmployeeID_idx` (`EmployeeID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phone`
--

INSERT INTO `phone` (`PhoneNumber`, `EmployeeID`, `Type`) VALUES
('2312312312', 4, 'Personal'),
('2695126541', 4, 'Personal'),
('3463458634', 50, 'Personal'),
('3470003005', 32, 'Work'),
('3477173005', 1, 'Work'),
('5298219829', 2, 'Personal'),
('5511146264', 33, 'Work'),
('5527938400', 2, 'Work'),
('5915418416', 64, 'Work'),
('6467965987', 1, 'Home'),
('6477758312', 4, 'Work'),
('9191095084', 77, 'Work'),
('9354637584', 3, 'Work'),
('9513578264', 50, 'Work'),
('9814166515', 52, 'Work');

-- --------------------------------------------------------

--
-- Table structure for table `team`
--

DROP TABLE IF EXISTS `team`;
CREATE TABLE IF NOT EXISTS `team` (
  `ManagerID` bigint(20) DEFAULT NULL,
  `EmployeeID` bigint(20) NOT NULL,
  PRIMARY KEY (`EmployeeID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `team`
--

INSERT INTO `team` (`ManagerID`, `EmployeeID`) VALUES
(33, 32),
(33, 2),
(33, 4),
(3, 50),
(3, 52),
(NULL, 1),
(NULL, 3),
(NULL, 33),
(NULL, 64),
(NULL, 77);

-- --------------------------------------------------------

--
-- Table structure for table `wi_schedule`
--

DROP TABLE IF EXISTS `wi_schedule`;
CREATE TABLE IF NOT EXISTS `wi_schedule` (
  `ScheduleID` bigint(20) NOT NULL AUTO_INCREMENT,
  `ItemID` bigint(20) NOT NULL,
  `ActualEndTime` time DEFAULT NULL,
  `StartTime` time NOT NULL,
  `EndTime` time NOT NULL,
  `Date` date NOT NULL,
  PRIMARY KEY (`ScheduleID`),
  KEY `SIID_idx` (`ItemID`)
) ENGINE=InnoDB AUTO_INCREMENT=218 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wi_schedule`
--

INSERT INTO `wi_schedule` (`ScheduleID`, `ItemID`, `ActualEndTime`, `StartTime`, `EndTime`, `Date`) VALUES
(1, 1, NULL, '09:00:00', '16:00:00', '2021-04-28'),
(3, 3, NULL, '19:00:00', '21:00:00', '2021-04-29'),
(4, 4, NULL, '16:00:00', '20:00:00', '2021-04-28'),
(181, 202, NULL, '21:00:00', '22:00:00', '2021-05-10'),
(182, 203, NULL, '21:00:00', '22:00:00', '2021-05-17'),
(183, 204, NULL, '21:00:00', '22:00:00', '2021-05-24'),
(184, 205, NULL, '21:00:00', '22:00:00', '2021-05-31'),
(187, 208, NULL, '21:00:00', '22:00:00', '2021-05-11'),
(188, 209, NULL, '17:30:00', '18:00:00', '2021-05-12'),
(189, 210, NULL, '20:00:00', '20:15:00', '2021-05-13'),
(193, 213, NULL, '21:00:00', '21:15:00', '2021-05-12'),
(202, 229, NULL, '06:00:00', '06:15:00', '2021-05-14'),
(205, 232, NULL, '21:15:00', '21:45:00', '2021-05-14'),
(206, 233, NULL, '21:00:00', '21:45:00', '2021-05-14'),
(207, 234, NULL, '06:00:00', '07:00:00', '2021-05-14'),
(208, 235, NULL, '19:45:00', '21:30:00', '2021-05-21'),
(209, 236, NULL, '19:45:00', '21:30:00', '2021-05-28'),
(210, 237, NULL, '17:30:00', '18:00:00', '2021-05-14'),
(211, 238, NULL, '17:30:00', '18:00:00', '2021-05-21'),
(212, 239, NULL, '17:30:00', '18:00:00', '2021-05-28'),
(213, 240, NULL, '17:30:00', '18:00:00', '2021-06-04'),
(216, 243, NULL, '20:00:00', '20:15:00', '2021-05-14');

-- --------------------------------------------------------

--
-- Table structure for table `workitem`
--

DROP TABLE IF EXISTS `workitem`;
CREATE TABLE IF NOT EXISTS `workitem` (
  `ItemID` bigint(20) NOT NULL AUTO_INCREMENT,
  `EmployeeID` bigint(20) DEFAULT NULL,
  `LocationID` bigint(20) NOT NULL,
  `DeliveryID` bigint(20) NOT NULL,
  `Description` varchar(1000) DEFAULT NULL,
  `InviteSent` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Boolean 0->F, 1->T',
  `PreferredE1` bigint(20) DEFAULT NULL,
  `PreferredE2` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`ItemID`),
  KEY `EWID_idx` (`EmployeeID`),
  KEY `LID_idx` (`LocationID`),
  KEY `DID_idx` (`DeliveryID`)
) ENGINE=InnoDB AUTO_INCREMENT=245 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `workitem`
--

INSERT INTO `workitem` (`ItemID`, `EmployeeID`, `LocationID`, `DeliveryID`, `Description`, `InviteSent`, `PreferredE1`, `PreferredE2`) VALUES
(1, 4, 1, 1, 'stuff', 0, 2, 3),
(3, 2, 3, 4, 'things and stuff', 0, 2, NULL),
(4, 57, 4, 1, 'even more ~stuff~', 0, 0, 0),
(202, 32, 3, 4, '', 0, 0, 0),
(203, 32, 3, 1, 'Engineer must replace mechanical equipment in building 301 room 5', 0, 0, 0),
(204, 32, 3, 1, '', 0, 0, 0),
(205, 32, 3, 1, '', 0, 0, 0),
(208, 32, 2, 1, '', 0, 0, 0),
(209, 2, 4, 2, 'This is for the demo!', 0, 32, NULL),
(210, 4, 3, 1, '', 0, 0, 0),
(213, 63, 2, 1, '', 0, 0, 0),
(229, 64, 4, 1, '', 0, 0, 0),
(232, 4, 3, 1, '', 0, 0, 0),
(233, 64, 2, 1, '', 0, 0, 0),
(234, 32, 4, 1, 'adfsdfs', 0, 0, 0),
(235, 32, 4, 1, '', 0, 0, 0),
(236, 32, 4, 1, '', 0, 0, 0),
(237, 4, 4, 1, '', 0, 0, 0),
(238, 32, 4, 1, '', 0, 0, 0),
(239, 32, 4, 1, '', 0, 0, 0),
(240, 32, 4, 1, '', 0, 0, 0),
(243, 4, 3, 1, '', 0, 0, 0);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
