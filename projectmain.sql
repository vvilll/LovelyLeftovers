-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 01, 2026 at 01:44 AM
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
  ADD PRIMARY KEY (`NodeID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `edges`
--
ALTER TABLE `edges`
  MODIFY `EdgeID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `nodes`
--
ALTER TABLE `nodes`
  MODIFY `NodeID` int(11) NOT NULL AUTO_INCREMENT;

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
