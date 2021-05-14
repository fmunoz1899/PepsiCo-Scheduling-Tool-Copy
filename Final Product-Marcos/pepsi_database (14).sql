-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 14, 2021 at 11:14 PM
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
(1, 'Wed', NULL, NULL);

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
('admin', 1, 'Work');

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
) ENGINE=InnoDB AUTO_INCREMENT=84 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`EmployeeID`, `firstName`, `lastName`, `Epassword`) VALUES
(1, 'admin', 'admin', 'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3');

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
(1, 'A');

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
('0000000000', 1, 'Work');

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
(NULL, 1);

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
