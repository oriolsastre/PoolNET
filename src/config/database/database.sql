CREATE TABLE `usuari` (
    `usuariId` int(11) NOT NULL PRIMARY KEY,
    `usuari` varchar(20) NOT NULL,
    `email` varchar(50) NOT NULL,
    `salt` varchar(20) NOT NULL,
    `hash` char(32) NOT NULL,
    `nivell` tinyint(4) NOT NULL DEFAULT 2,
    `data_creacio` date NOT NULL
);

CREATE TABLE `control` (
    `controlId` int(11) NOT NULL PRIMARY KEY,
    `data_hora` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `ph` decimal(3, 2) DEFAULT NULL,
    `clor` decimal(3, 2) DEFAULT NULL,
    `alcali` float DEFAULT NULL,
    `temperatura` tinyint(4) DEFAULT NULL,
    `transparent` tinyint(4) DEFAULT NULL,
    `fons` tinyint(4) DEFAULT NULL,
    `usuari` int(11) NOT NULL,
    FOREIGN KEY (`usuari`) REFERENCES `usuari` (`usuariId`) ON UPDATE CASCADE ON DELETE RESTRICT
);

CREATE TABLE `accio` (
    `accioId` int(11) NOT NULL PRIMARY KEY,
    `data_hora` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `ph` tinyint(4) DEFAULT NULL,
    `clor` tinyint(4) DEFAULT NULL,
    `antialga` tinyint(4) DEFAULT NULL,
    `fluoculant` tinyint(4) DEFAULT NULL,
    `aspirar` tinyint(4) DEFAULT NULL,
    `alcali` tinyint(4) DEFAULT NULL,
    `aglutinant` tinyint(4) DEFAULT NULL,
    `usuari` int(11) NOT NULL,
    FOREIGN KEY (`usuari`) REFERENCES `usuari` (`usuariId`) ON UPDATE CASCADE ON DELETE RESTRICT
);
