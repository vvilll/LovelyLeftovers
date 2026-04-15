-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 15, 2026 at 06:00 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `projectmain`
--

-- --------------------------------------------------------

--
-- Table structure for table `edges`
--

CREATE TABLE `edges` (
  `EdgeID` int(11) NOT NULL,
  `SourceID` int(11) NOT NULL,
  `TargetID` int(11) NOT NULL,
  `EdgeType` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `edges`
--

INSERT INTO `edges` (`EdgeID`, `SourceID`, `TargetID`, `EdgeType`) VALUES
(1, 6, 7, 'Has_Pref'),
(2, 6, 8, 'Has_Interest'),
(3, 6, 9, 'Has_Interest'),
(4, 6, 10, 'Has_Interest');

-- --------------------------------------------------------

--
-- Table structure for table `nodes`
--

CREATE TABLE `nodes` (
  `NodeID` int(11) NOT NULL,
  `NodeType` varchar(50) NOT NULL,
  `Name` varchar(100) DEFAULT NULL,
  `Age` int(10) UNSIGNED DEFAULT NULL,
  `Gender` varchar(50) DEFAULT NULL,
  `Occupation` varchar(100) DEFAULT NULL,
  `City` varchar(100) DEFAULT NULL,
  `State` varchar(100) DEFAULT NULL,
  `Zipcode` varchar(10) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Password` varchar(100) DEFAULT NULL,
  `Mobile` varchar(15) DEFAULT NULL,
  `Label` varchar(100) DEFAULT NULL,
  `Category` varchar(50) DEFAULT NULL,
  `MinAge` int(10) UNSIGNED DEFAULT NULL,
  `MaxAge` int(10) UNSIGNED DEFAULT NULL,
  `MatchStatus` varchar(50) DEFAULT NULL,
  `MatchDate` int(11) DEFAULT NULL,
  `Other` varchar(255) DEFAULT NULL,
  `ImagePath` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nodes`
--

INSERT INTO `nodes` (`NodeID`, `NodeType`, `Name`, `Age`, `Gender`, `Occupation`, `City`, `State`, `Zipcode`, `Email`, `Password`, `Mobile`, `Label`, `Category`, `MinAge`, `MaxAge`, `MatchStatus`, `MatchDate`, `Other`, `ImagePath`) VALUES
(3, 'User', 'Chris Soto', NULL, 'male', NULL, 'Hayden', 'Idaho', '83835', 'csoto550@aol.com', '1234', '45', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(5, 'User', 'John Doe', NULL, 'male', NULL, 'Hayden', 'Idaho', '83835', 'dsoto5958@yahoo.com', '1234', '42', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(6, 'User', 'meow zzzz', 1996, 'male', 'Dishwasher', 'Hayden', 'Idaho', '83835', 'test@email.com', '1234', '78', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(7, 'Pref', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', 'Food', NULL, NULL, NULL, NULL, NULL, NULL),
(8, 'Interest', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Acrobatics', 'Hobby', NULL, NULL, NULL, NULL, NULL, NULL),
(9, 'Interest', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Aerobics', 'Hobby', NULL, NULL, NULL, NULL, NULL, NULL),
(10, 'Interest', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Airbrushing', 'Hobby', NULL, NULL, NULL, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `edges`
--
ALTER TABLE `edges`
  ADD PRIMARY KEY (`EdgeID`),
  ADD KEY `EnforceValidEdge1` (`SourceID`),
  ADD KEY `EnforceValidEdge2` (`TargetID`);

--
-- Indexes for table `nodes`
--
ALTER TABLE `nodes`
  ADD PRIMARY KEY (`NodeID`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `edges`
--
ALTER TABLE `edges`
  MODIFY `EdgeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `nodes`
--
ALTER TABLE `nodes`
  MODIFY `NodeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `edges`
--
ALTER TABLE `edges`
  ADD CONSTRAINT `EnforceValidEdge1` FOREIGN KEY (`SourceID`) REFERENCES `nodes` (`NodeID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `EnforceValidEdge2` FOREIGN KEY (`TargetID`) REFERENCES `nodes` (`NodeID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
