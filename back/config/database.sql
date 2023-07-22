CREATE TABLE `user` (
  `userID` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `usuari` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `salt` varchar(20) NOT NULL,
  `hash` char(32) NOT NULL,
  `nivell` tinyint(4) NOT NULL DEFAULT 2,
  `data_creacio` date NOT NULL
);

CREATE TABLE `piscinacontrol` (
  `controlID` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `data_hora` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ph` decimal(3,2) DEFAULT NULL,
  `clor` decimal(3,2) DEFAULT NULL,
  `alcali` float DEFAULT NULL,
  `temperatura` tinyint(4) DEFAULT NULL,
  `transparent` tinyint(4) DEFAULT NULL,
  `fons` tinyint(4) DEFAULT NULL,
  `usuari` int(11) NOT NULL
);
ALTER TABLE `piscinacontrol`
  ADD CONSTRAINT `FK_Usuari_User` FOREIGN KEY (`usuari`) REFERENCES `user` (`userID`) ON UPDATE CASCADE;
COMMIT;

CREATE TABLE `piscinaaccio` (
  `accioID` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `data_hora` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ph` tinyint(4) DEFAULT NULL,
  `clor` tinyint(4) DEFAULT NULL,
  `antialga` tinyint(4) DEFAULT NULL,
  `fluoculant` tinyint(4) DEFAULT NULL,
  `aspirar` tinyint(4) DEFAULT NULL,
  `alcali` tinyint(4) DEFAULT NULL,
  `aglutinant` tinyint(4) DEFAULT NULL,
  `usuari` int(11) NOT NULL
);
ALTER TABLE `piscinaaccio`
  ADD CONSTRAINT `FK_Usuari_piscinaAccio` FOREIGN KEY (`usuari`) REFERENCES `user` (`userID`) ON UPDATE CASCADE;
COMMIT;

-- Seed
INSERT INTO `user` (`userID`, `usuari`, `email`, `salt`, `hash`, `nivell`, `data_creacio`) VALUES
(1, 'Admin', 'admin@example.cat', 'Some salt', '30fc87a9f1f0643c3169998da58d45f4', 0, '2023-07-21'),
(2, 'Test', 'test@example.cat', 'Some other salt', '045f4723fe5a7089075ed6381890a889', 1, '2023-07-21');

INSERT INTO `piscinacontrol` (`controlID`, `data_hora`, `ph`, `clor`, `alcali`, `temperatura`, `transparent`, `fons`, `usuari`) VALUES
(1, '2022-11-15 18:47:10', '6.80', '3.00', NULL, NULL, 1, 2, 1),
(2, '2022-11-19 16:53:03', '6.50', '3.00', 2, NULL, NULL, NULL, 1),
(3, '2022-11-25 18:08:48', '6.80', '3.00', NULL, 16, 1, 2, 1),
(4, '2022-12-18 14:02:12', '6.80', '3.00', NULL, 12, 1, 2, 2),
(5, '2023-06-05 12:25:06', NULL, '7.20', NULL, NULL, NULL, NULL, 1),
(6, '2023-07-04 17:54:22', '7.20', '0.10', NULL, NULL, NULL, NULL, 2),
(7, '2023-07-04 18:00:39', '8.10', '0.10', NULL, 28, 1, 1, 2),
(8, '2023-07-04 18:01:52', '8.10', '0.10', NULL, 28, 1, 1, 2),
(9, '2023-07-07 17:01:00', '7.20', '0.10', 1, 28, 1, 1, 1),
(110, '2023-07-08 17:30:19', '7.20', '0.10', 1, 28, 1, 1, 1);

INSERT INTO `piscinaaccio` (`accioID`, `data_hora`, `ph`, `clor`, `antialga`, `fluoculant`, `aspirar`, `alcali`, `aglutinant`, `usuari`) VALUES
(1, '2022-09-01 00:00:00', -1, 1, 1, 1, 1, 1, 1, 1),
(2, '2022-11-25 18:18:49', NULL, NULL, 1, NULL, NULL, NULL, NULL, 1);