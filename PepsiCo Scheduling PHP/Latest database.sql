-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 13, 2021 at 03:03 AM
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
(1, 'Mon', '06:00:00', '22:00:00'),
(1, 'Sat', NULL, NULL),
(1, 'Sun', '08:00:00', '14:00:00'),
(1, 'Thu', '08:00:00', '10:00:00'),
(1, 'Tue', NULL, NULL),
(1, 'Wed', NULL, NULL),
(2, 'Fri', NULL, NULL),
(2, 'Mon', NULL, NULL),
(2, 'Sat', NULL, NULL),
(2, 'Sun', NULL, NULL),
(2, 'Thu', NULL, NULL),
(2, 'Tue', NULL, NULL),
(2, 'Wed', '08:00:00', '16:00:00'),
(3, 'Fri', '10:00:00', '20:00:00'),
(3, 'Mon', NULL, NULL),
(3, 'Sat', NULL, NULL),
(3, 'Sun', NULL, NULL),
(3, 'Thu', NULL, NULL),
(3, 'Tue', NULL, NULL),
(3, 'Wed', NULL, NULL),
(4, 'Fri', NULL, NULL),
(4, 'Mon', NULL, NULL),
(4, 'Sat', NULL, NULL),
(4, 'Sun', NULL, NULL),
(4, 'Thu', NULL, NULL),
(4, 'Tue', '12:00:00', '20:00:00'),
(4, 'Wed', '08:00:00', '16:00:00'),
(32, 'Fri', NULL, NULL),
(32, 'Mon', '08:30:00', '20:30:00'),
(32, 'Sat', NULL, NULL),
(32, 'Sun', NULL, NULL),
(32, 'Thu', NULL, NULL),
(32, 'Tue', NULL, NULL),
(32, 'Wed', NULL, NULL),
(33, 'Fri', NULL, NULL),
(33, 'Mon', NULL, NULL),
(33, 'Sat', NULL, NULL),
(33, 'Sun', NULL, NULL),
(33, 'Thu', NULL, NULL),
(33, 'Tue', NULL, NULL),
(33, 'Wed', NULL, NULL);

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
  PRIMARY KEY (`BlackoutID`,`EmployeeID`),
  KEY `EmployeeID` (`EmployeeID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `blackout`
--

INSERT INTO `blackout` (`BlackoutID`, `EmployeeID`, `StartTime`, `EndTime`, `BDate`) VALUES
(1, 1, '12:00:00', '13:00:00', '2021-04-12'),
(2, 32, '08:00:00', '09:00:00', '2021-04-12'),
(3, 32, '12:00:00', '15:00:00', '2021-04-12');

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
(1, 'bulk'),
(2, 'bulk'),
(3, 'bulk'),
(4, 'stuff');

-- --------------------------------------------------------

--
-- Table structure for table `email`
--

DROP TABLE IF EXISTS `email`;
CREATE TABLE IF NOT EXISTS `email` (
  `Email` varchar(30) NOT NULL,
  `EmployeeID` bigint(20) NOT NULL,
  `Type` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`Email`,`EmployeeID`),
  KEY `Employee_ID_idx` (`EmployeeID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `email`
--

INSERT INTO `email` (`Email`, `EmployeeID`, `Type`) VALUES
('d@r5', 4, 'Personal'),
('f', 33, 'Work'),
('f@ggrr', 1, 'Personal'),
('felixXXX@ioan.edu', 4, 'Work'),
('fmuno1899@gmail.com', 1, 'Personal'),
('frankthetank@aol.com', 32, 'Work'),
('itsmekevin@hotmail.com', 3, 'Work'),
('julianquack@yahoo.com', 2, 'Work'),
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
  `Epassword` varchar(50) NOT NULL,
  PRIMARY KEY (`EmployeeID`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`EmployeeID`, `firstName`, `lastName`, `Epassword`) VALUES
(1, 'Frankie', 'Munoz', '123'),
(2, 'Julian', 'TP', 'duckduck'),
(3, 'Kevin', 'G', 'words123'),
(4, 'Felix', 'P', 'tryguessingthis'),
(32, 'table ', 'show', 'h'),
(33, 'manager', 'man', '123');

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
(3, 'M'),
(33, 'M');

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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `location`
--

INSERT INTO `location` (`LocationID`, `LocationName`, `StreetAdress`, `City`, `State`, `Zip`) VALUES
(1, 'warehouse', '715 North Ave', 'Houston', 'TX', 11111),
(2, 'my house', '515 tenicey ave', 'city in TN', 'TN', 69696),
(3, 'ivanov backyard', '169 ivanov road', 'jersey place', 'NJ', 12345),
(4, 'place', '23 road', 'city city', 'OH', 39671),
(5, 'Iona', '715 North Ave', 'New Rochelle', 'NY', 10801);

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
('3470003005', 32, 'Work'),
('3477173005', 1, 'Work'),
('5511146264', 33, 'Work'),
('5527938400', 2, 'Work'),
('6467965987', 1, 'Home'),
('6467965987', 4, 'Personal'),
('6477758312', 4, 'Work'),
('911', 2, 'Home'),
('9354637584', 3, 'Work');

-- --------------------------------------------------------

--
-- Table structure for table `wi_schedule`
--

DROP TABLE IF EXISTS `wi_schedule`;
CREATE TABLE IF NOT EXISTS `wi_schedule` (
  `ScheduleID` bigint(20) NOT NULL AUTO_INCREMENT,
  `ItemID` bigint(20) NOT NULL,
  `ActualEndTime` time DEFAULT NULL,
  `StartTime` time DEFAULT NULL,
  `EndTime` time DEFAULT NULL,
  `Date` date NOT NULL,
  PRIMARY KEY (`ScheduleID`),
  KEY `SIID_idx` (`ItemID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wi_schedule`
--

INSERT INTO `wi_schedule` (`ScheduleID`, `ItemID`, `ActualEndTime`, `StartTime`, `EndTime`, `Date`) VALUES
(1, 1, NULL, '09:00:00', '12:00:00', '2021-04-12'),
(2, 2, NULL, '10:00:00', '11:00:00', '2021-04-12'),
(3, 3, NULL, '09:00:00', '09:15:00', '2021-04-12'),
(4, 4, NULL, '16:00:00', '20:00:00', '2021-04-12');

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
  `Description` varchar(50) DEFAULT NULL,
  `InviteSent` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Boolean 0->F, 1->T',
  `PreferredE1` bigint(20) DEFAULT NULL,
  `PreferredE2` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`ItemID`),
  KEY `EWID_idx` (`EmployeeID`),
  KEY `LID_idx` (`LocationID`),
  KEY `DID_idx` (`DeliveryID`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `workitem`
--

INSERT INTO `workitem` (`ItemID`, `EmployeeID`, `LocationID`, `DeliveryID`, `Description`, `InviteSent`, `PreferredE1`, `PreferredE2`) VALUES
(1, 1, 1, 1, 'stuff', 0, 2, 3),
(2, 32, 2, 3, NULL, 0, NULL, NULL),
(3, 32, 3, 4, 'things and stuff', 0, 2, NULL),
(4, 1, 4, 3, 'even more ~stuff~', 0, NULL, NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
