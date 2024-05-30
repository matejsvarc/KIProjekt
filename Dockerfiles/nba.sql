SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

-- tabulka registrovaných uživatelů (kteří moho nahrávat recepty)
DROP TABLE IF EXISTS `Hraci`;
CREATE TABLE `Hraci` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `jmeno` varchar(100) NOT NULL,
  `prijmeni` varchar(100) NOT NULL,
  `ID_tym` int(100) NOT NULL,
  `ID_kontrakt` int(100) NOT NULL,
  `ID_statistky` int(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `jmeno` (`jmeno`)
);
