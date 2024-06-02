-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: database
-- Generation Time: Jun 02, 2024 at 03:14 PM
-- Server version: 8.4.0
-- PHP Version: 8.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nba`
--

-- --------------------------------------------------------

--
-- Table structure for table `Hraci`
--

CREATE TABLE `Hraci` (
  `ID` int NOT NULL,
  `jmeno` varchar(100) NOT NULL,
  `prijmeni` varchar(100) NOT NULL,
  `ID_tym` int NOT NULL,
  `ID_kontrakt` int NOT NULL,
  `ID_statistiky` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Hraci`
--

INSERT INTO `Hraci` (`ID`, `jmeno`, `prijmeni`, `ID_tym`, `ID_kontrakt`, `ID_statistiky`) VALUES
(39, 'Stephen', 'Curry', 64, 1, 1),
(40, 'Jayson', 'Tatum', 65, 2, 2),
(41, 'Kevin', 'Durant', 66, 1, 1),
(42, 'Kyrie', 'Irving', 66, 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `Kontrakty`
--

CREATE TABLE `Kontrakty` (
  `ID` int NOT NULL,
  `Castka` int NOT NULL,
  `zacatek` date NOT NULL,
  `konec` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Kontrakty`
--

INSERT INTO `Kontrakty` (`ID`, `Castka`, `zacatek`, `konec`) VALUES
(39, 2000000, '2024-04-01', '2027-04-01'),
(40, 1800000, '2024-05-01', '2026-05-01'),
(41, 2500000, '2024-06-01', '2027-06-01'),
(42, 2200000, '2024-07-01', '2026-07-01');

-- --------------------------------------------------------

--
-- Table structure for table `statistiky`
--

CREATE TABLE `statistiky` (
  `ID` int NOT NULL,
  `ID_zapasu` int NOT NULL,
  `body` int NOT NULL,
  `asistence` int NOT NULL,
  `doskoky` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `statistiky`
--

INSERT INTO `statistiky` (`ID`, `ID_zapasu`, `body`, `asistence`, `doskoky`) VALUES
(17, 19, 35, 12, 7),
(18, 20, 28, 9, 11),
(19, 7, 40, 15, 10),
(20, 8, 22, 7, 14);

-- --------------------------------------------------------

--
-- Table structure for table `Tymy`
--

CREATE TABLE `Tymy` (
  `ID` int NOT NULL,
  `nazev` varchar(255) NOT NULL,
  `datum_zalozeni` date NOT NULL,
  `mesto` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Tymy`
--

INSERT INTO `Tymy` (`ID`, `nazev`, `datum_zalozeni`, `mesto`) VALUES
(64, 'Celtics', '1946-01-01', 'Boston'),
(65, 'Warriors', '1946-04-15', 'San Francisco'),
(66, 'Nets', '1967-06-01', 'Brooklyn'),
(67, 'Knicks', '1946-01-01', 'New York');

-- --------------------------------------------------------

--
-- Table structure for table `Zapasy`
--

CREATE TABLE `Zapasy` (
  `ID` int NOT NULL,
  `ID_hrace` int NOT NULL,
  `ID_statistiky` int NOT NULL,
  `datum` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Zapasy`
--

INSERT INTO `Zapasy` (`ID`, `ID_hrace`, `ID_statistiky`, `datum`) VALUES
(19, 1, 1, '2024-07-10'),
(20, 2, 2, '2024-07-15'),
(21, 1, 1, '2024-08-10'),
(22, 2, 2, '2024-08-15');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Hraci`
--
ALTER TABLE `Hraci`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ID_tym` (`ID_tym`),
  ADD KEY `ID_kontrakt` (`ID_kontrakt`,`ID_statistiky`),
  ADD KEY `ID_statistiky` (`ID_statistiky`);

--
-- Indexes for table `Kontrakty`
--
ALTER TABLE `Kontrakty`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `statistiky`
--
ALTER TABLE `statistiky`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ID_zapasu` (`ID_zapasu`);

--
-- Indexes for table `Tymy`
--
ALTER TABLE `Tymy`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `Zapasy`
--
ALTER TABLE `Zapasy`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ID_hrace` (`ID_hrace`),
  ADD KEY `ID_statiistiky` (`ID_statistiky`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Hraci`
--
ALTER TABLE `Hraci`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `Kontrakty`
--
ALTER TABLE `Kontrakty`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `statistiky`
--
ALTER TABLE `statistiky`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `Tymy`
--
ALTER TABLE `Tymy`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `Zapasy`
--
ALTER TABLE `Zapasy`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
