
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";



CREATE TABLE `Hraci` (
  `ID` int NOT NULL,
  `jmeno` varchar(100) NOT NULL,
  `prijmeni` varchar(100) NOT NULL,
  `ID_tym` int NOT NULL,
  `body` int NOT NULL,
  `asistence` int NOT NULL,
  `doskoky` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
-- Indexes for dumped tables
--

--
-- Indexes for table `Hraci`
--
ALTER TABLE `Hraci`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ID_tym` (`ID_tym`);

--
-- Indexes for table `Tymy`
--
ALTER TABLE `Tymy`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Hraci`
--
ALTER TABLE `Hraci`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Tymy`
--
ALTER TABLE `Tymy`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Hraci`
--
ALTER TABLE `Hraci`
  ADD CONSTRAINT `Hraci_ibfk_1` FOREIGN KEY (`ID_tym`) REFERENCES `Tymy` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
