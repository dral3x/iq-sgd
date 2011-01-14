-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generato il: 14 gen, 2011 at 12:12 PM
-- Versione MySQL: 5.1.44
-- Versione PHP: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `demo`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `allegato`
--

DROP TABLE IF EXISTS `allegato`;
CREATE TABLE IF NOT EXISTS `allegato` (
  `id_doc` int(11) NOT NULL,
  `id_doc_allegato` int(11) NOT NULL,
  KEY `id_doc` (`id_doc`,`id_doc_allegato`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `allegato`
--


-- --------------------------------------------------------

--
-- Struttura della tabella `autore`
--

DROP TABLE IF EXISTS `autore`;
CREATE TABLE IF NOT EXISTS `autore` (
  `id_doc` int(11) NOT NULL,
  `mat_utente` int(11) NOT NULL,
  PRIMARY KEY (`id_doc`,`mat_utente`),
  KEY `mat_utente` (`mat_utente`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `autore`
--

INSERT INTO `autore` (`id_doc`, `mat_utente`) VALUES
(1, 2),
(1, 3),
(2, 3),
(3, 3),
(4, 3),
(6, 3),
(7, 3),
(8, 3),
(9, 3),
(12, 2),
(12, 3);

-- --------------------------------------------------------

--
-- Struttura della tabella `azienda`
--

DROP TABLE IF EXISTS `azienda`;
CREATE TABLE IF NOT EXISTS `azienda` (
  `cf_iva` varchar(20) NOT NULL,
  `nome` char(30) NOT NULL,
  `mail` varchar(20) DEFAULT NULL,
  `tel` varchar(10) NOT NULL,
  `persona` char(30) NOT NULL,
  PRIMARY KEY (`cf_iva`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `azienda`
--

INSERT INTO `azienda` (`cf_iva`, `nome`, `mail`, `tel`, `persona`) VALUES
('ABRACADABRA00Q77', 'Telecom', 'telecom@gmail.com', '340-456789', 'pippo');

-- --------------------------------------------------------

--
-- Struttura della tabella `campo`
--

DROP TABLE IF EXISTS `campo`;
CREATE TABLE IF NOT EXISTS `campo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome_it` varchar(60) DEFAULT NULL,
  `nome_eng` varchar(60) DEFAULT NULL,
  `nome_de` varchar(60) DEFAULT NULL,
  `tipo` enum('small','medium','long') DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

--
-- Dump dei dati per la tabella `campo`
--

INSERT INTO `campo` (`id`, `nome_it`, `nome_eng`, `nome_de`, `tipo`) VALUES
(1, 'descrizione', 'description', 'beschrijving', 'medium'),
(2, 'soggetto del documento', NULL, NULL, 'small'),
(3, 'obiettivi e responsabilit&agrave;', NULL, NULL, 'long'),
(4, 'scopo', NULL, NULL, 'small'),
(5, 'abstract', NULL, NULL, 'medium'),
(6, 'step delle attivit&agrave;', NULL, NULL, 'long'),
(7, 'riferimenti ad altre norme', NULL, NULL, 'medium'),
(8, 'categoria', NULL, NULL, 'small'),
(9, 'prodotto', NULL, NULL, 'small'),
(10, 'contenuto', NULL, NULL, 'long'),
(11, 'istruzioni e configurazione', NULL, NULL, 'long'),
(12, 'riferimenti ad altre norme', NULL, NULL, 'medium'),
(13, 'dati statistici analizzati', NULL, NULL, 'long'),
(14, 'conclusioni tratte dai dati ricevuti ', NULL, NULL, 'long'),
(15, 'requisiti', NULL, NULL, 'medium'),
(16, 'personale e/o beni aziendali a servizio del soggetto', NULL, NULL, 'medium');

-- --------------------------------------------------------

--
-- Struttura della tabella `campo_classe`
--

DROP TABLE IF EXISTS `campo_classe`;
CREATE TABLE IF NOT EXISTS `campo_classe` (
  `id_classe` int(11) NOT NULL,
  `id_campo` int(11) NOT NULL,
  `opzionale` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_classe`,`id_campo`),
  KEY `id_campo` (`id_campo`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `campo_classe`
--

INSERT INTO `campo_classe` (`id_classe`, `id_campo`, `opzionale`) VALUES
(3, 2, 0),
(3, 1, 0),
(3, 3, 0),
(3, 15, 1),
(3, 16, 1),
(4, 4, 0),
(4, 5, 0),
(4, 6, 0),
(4, 7, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `classe_documenti`
--

DROP TABLE IF EXISTS `classe_documenti`;
CREATE TABLE IF NOT EXISTS `classe_documenti` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dump dei dati per la tabella `classe_documenti`
--

INSERT INTO `classe_documenti` (`id`, `nome`) VALUES
(1, 'allegati'),
(2, 'documenti per assicurare la qualit&agrave;'),
(3, 'documenti per la organizzazione aziendale'),
(4, 'procedure operative'),
(5, 'documenti tecnici'),
(6, 'documenti statistici e gestionali'),
(7, 'verbali di riunione'),
(8, 'documenti provenienti dal''esterno'),
(9, 'leggi, prescrizioni e regolamenti');

-- --------------------------------------------------------

--
-- Struttura della tabella `documento`
--

DROP TABLE IF EXISTS `documento`;
CREATE TABLE IF NOT EXISTS `documento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cont` int(11) NOT NULL,
  `versione` varchar(4) NOT NULL,
  `anno` smallint(6) NOT NULL,
  `classe` int(11) DEFAULT NULL,
  `mese` smallint(6) NOT NULL,
  `giorno` smallint(6) NOT NULL,
  `sede` char(30) NOT NULL,
  `stato` enum('bozza','approvazione','approvato','obsoleto','distribuzione') DEFAULT NULL,
  `allegati` smallint(6) DEFAULT NULL,
  `liv_conf` enum('0','1','2','3') DEFAULT NULL,
  `supp_it` tinyint(1) NOT NULL,
  `supp_eng` tinyint(1) NOT NULL,
  `supp_de` tinyint(1) NOT NULL,
  `approvatore` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cont` (`cont`,`versione`,`anno`,`classe`),
  KEY `classe` (`classe`),
  KEY `approvatore` (`approvatore`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dump dei dati per la tabella `documento`
--

INSERT INTO `documento` (`id`, `cont`, `versione`, `anno`, `classe`, `mese`, `giorno`, `sede`, `stato`, `allegati`, `liv_conf`, `supp_it`, `supp_eng`, `supp_de`, `approvatore`) VALUES
(1, 1, '1.0', 2010, 4, 12, 24, 'sede di padova', 'approvato', 0, '1', 1, 0, 0, 1),
(2, 2, '1.0', 2010, 4, 12, 23, 'sede di padova', 'approvato', 0, '2', 1, 0, 0, 1),
(3, 3, '2.1', 2010, 4, 12, 23, 'london', 'approvazione', 0, '2', 0, 1, 0, 2),
(4, 1, '1.0', 2010, 3, 12, 29, 'sede di padova', 'bozza', 0, '2', 1, 0, 0, 1),
(5, 2, '1.1', 2010, 4, 12, 29, 'sede di padova', 'distribuzione', 0, '2', 1, 0, 0, 2),
(7, 5, '1.0', 2011, 4, 1, 10, 'gfgdgfdgd', 'bozza', 0, '2', 1, 0, 0, 2),
(6, 4, '5.3', 2011, 4, 1, 10, 'sedeeeee', 'bozza', 0, '2', 1, 0, 0, 2),
(8, 6, '1.0', 2011, 4, 1, 12, 'casa mia', 'bozza', 0, '2', 1, 0, 0, 0),
(9, 7, '1.0', 2011, 4, 1, 12, 'casa mia', 'bozza', 0, '2', 1, 0, 0, 0),
(10, 2, '1.0', 2011, 3, 1, 13, 'sadada', 'bozza', 0, '2', 1, 0, 0, 0),
(11, 3, '1.0', 2011, 3, 1, 13, 'sadada', 'bozza', 0, '2', 1, 0, 0, 0),
(12, 4, '1.0', 2011, 3, 1, 13, 'sadada', 'bozza', 0, '2', 1, 0, 0, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `doc_gruppo`
--

DROP TABLE IF EXISTS `doc_gruppo`;
CREATE TABLE IF NOT EXISTS `doc_gruppo` (
  `id_gruppo` int(11) NOT NULL,
  `id_doc` int(11) NOT NULL,
  PRIMARY KEY (`id_gruppo`,`id_doc`),
  KEY `id_doc` (`id_doc`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `doc_gruppo`
--

INSERT INTO `doc_gruppo` (`id_gruppo`, `id_doc`) VALUES
(1, 4),
(2, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `gruppo_doc`
--

DROP TABLE IF EXISTS `gruppo_doc`;
CREATE TABLE IF NOT EXISTS `gruppo_doc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome_gruppo` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dump dei dati per la tabella `gruppo_doc`
--

INSERT INTO `gruppo_doc` (`id`, `nome_gruppo`) VALUES
(1, 'documenti riservati per grupppo documentazione'),
(2, 'documenti riservati per grupppo software');

-- --------------------------------------------------------

--
-- Struttura della tabella `gruppo_utente`
--

DROP TABLE IF EXISTS `gruppo_utente`;
CREATE TABLE IF NOT EXISTS `gruppo_utente` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome_gruppo` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dump dei dati per la tabella `gruppo_utente`
--

INSERT INTO `gruppo_utente` (`id`, `nome_gruppo`) VALUES
(1, 'progetto-Database'),
(2, 'Progetto-software'),
(3, 'documentazione');

-- --------------------------------------------------------

--
-- Struttura della tabella `gruppo_utente_doc`
--

DROP TABLE IF EXISTS `gruppo_utente_doc`;
CREATE TABLE IF NOT EXISTS `gruppo_utente_doc` (
  `id_gruppo_doc` int(11) NOT NULL,
  `id_gruppo_utente` int(11) NOT NULL,
  PRIMARY KEY (`id_gruppo_doc`,`id_gruppo_utente`),
  KEY `id_gruppo_utente` (`id_gruppo_utente`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `gruppo_utente_doc`
--

INSERT INTO `gruppo_utente_doc` (`id_gruppo_doc`, `id_gruppo_utente`) VALUES
(1, 3),
(2, 2);

-- --------------------------------------------------------

--
-- Struttura della tabella `notifica`
--

DROP TABLE IF EXISTS `notifica`;
CREATE TABLE IF NOT EXISTS `notifica` (
  `id_doc` int(11) NOT NULL,
  `mat_utente` int(11) NOT NULL,
  `risposta` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id_doc`,`mat_utente`),
  KEY `mat_utente` (`mat_utente`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `notifica`
--

INSERT INTO `notifica` (`id_doc`, `mat_utente`, `risposta`) VALUES
(5, 5, 0),
(5, 6, 0),
(5, 7, 0),
(5, 8, 0),
(5, 9, 1),
(5, 10, 1),
(5, 11, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `provenienza_doc`
--

DROP TABLE IF EXISTS `provenienza_doc`;
CREATE TABLE IF NOT EXISTS `provenienza_doc` (
  `id_doc` int(11) NOT NULL,
  `cf_iva_azienda` char(20) NOT NULL,
  KEY `id_doc` (`id_doc`),
  KEY `cf_iva_azienda` (`cf_iva_azienda`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `provenienza_doc`
--


-- --------------------------------------------------------

--
-- Struttura della tabella `utente`
--

DROP TABLE IF EXISTS `utente`;
CREATE TABLE IF NOT EXISTS `utente` (
  `matricola` int(11) NOT NULL AUTO_INCREMENT,
  `nome` char(10) NOT NULL,
  `cognome` char(20) NOT NULL,
  `username` varchar(20) NOT NULL,
  `passwd` varchar(20) NOT NULL,
  `email` varchar(25) NOT NULL,
  `ruolo` enum('0','1','2') NOT NULL,
  PRIMARY KEY (`matricola`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dump dei dati per la tabella `utente`
--

INSERT INTO `utente` (`matricola`, `nome`, `cognome`, `username`, `passwd`, `email`, `ruolo`) VALUES
(1, 'Admin', '', 'admin', 'admin', 'admin@sgdsystem.com', '0'),
(2, 'fabio', 'masarin', 'fabio', 'fabio', 'fabio@gmail.com', '0'),
(3, 'enrico', 'cappelletto', 'enrico', 'enrico', 'enricocappelletto@gmail.c', '1'),
(4, 'alessandro', 'calzavara', 'alessandro', 'alessandro', 'ale@gmail.com', '2');

-- --------------------------------------------------------

--
-- Struttura della tabella `utente_gruppo`
--

DROP TABLE IF EXISTS `utente_gruppo`;
CREATE TABLE IF NOT EXISTS `utente_gruppo` (
  `id_gruppo` int(11) NOT NULL,
  `mat_utente` int(11) NOT NULL,
  PRIMARY KEY (`id_gruppo`,`mat_utente`),
  KEY `mat_utente` (`mat_utente`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `utente_gruppo`
--

INSERT INTO `utente_gruppo` (`id_gruppo`, `mat_utente`) VALUES
(1, 3),
(1, 4),
(1, 5),
(2, 6),
(2, 7),
(2, 8),
(2, 9),
(3, 10),
(3, 11),
(3, 12);

-- --------------------------------------------------------

--
-- Struttura della tabella `valori_campo_long`
--

DROP TABLE IF EXISTS `valori_campo_long`;
CREATE TABLE IF NOT EXISTS `valori_campo_long` (
  `id_doc` int(11) NOT NULL,
  `id_campo` int(11) NOT NULL,
  `valore_it` text,
  `valore_eng` text,
  `valore_de` text,
  PRIMARY KEY (`id_doc`,`id_campo`),
  KEY `id_campo` (`id_campo`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `valori_campo_long`
--

INSERT INTO `valori_campo_long` (`id_doc`, `id_campo`, `valore_it`, `valore_eng`, `valore_de`) VALUES
(1, 6, '1. Collaudo delle singole unit&agrave; di progettazione software: verifica dell interfaccia in base a quanto progettato integrit&agrave; dei dati presenti nelle strutture di dati localiesercitare i cammini base della struttura di controllo (particolare attenzione ai valori di inizializzazione delle variabili, confronti di dati di tipo diverso, collaudo dei casi limite)', NULL, NULL),
(4, 3, '1. pianificare risorse e attivit&agrave; sul lungo e medio termine di progettazione e produzione 2.prioritizzazione alle attivit&agrave; di progettazione e produzone 3.contrattazione con i fornitori su modi e tempi delle forniture', NULL, NULL),
(6, 6, 'sdfsdfadsfdf', NULL, NULL),
(7, 6, 'gjfgjhfgjfg', NULL, NULL),
(8, 6, 'ssssssssssssssss', NULL, NULL),
(9, 6, 'ssssssssssssssss', NULL, NULL),
(10, 3, 'ooooooooooooooo', NULL, NULL),
(11, 3, 'ooooooooooooooo', NULL, NULL),
(12, 3, 'ooooooooooooooo', NULL, NULL);

-- --------------------------------------------------------

--
-- Struttura della tabella `valori_campo_medium`
--

DROP TABLE IF EXISTS `valori_campo_medium`;
CREATE TABLE IF NOT EXISTS `valori_campo_medium` (
  `id_doc` int(11) NOT NULL,
  `id_campo` int(11) NOT NULL,
  `valore_it` varchar(255) DEFAULT NULL,
  `valore_eng` varchar(255) DEFAULT NULL,
  `valore_de` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_doc`,`id_campo`),
  KEY `id_campo` (`id_campo`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `valori_campo_medium`
--

INSERT INTO `valori_campo_medium` (`id_doc`, `id_campo`, `valore_it`, `valore_eng`, `valore_de`) VALUES
(1, 5, 'strategia incrementale per il collaudo di un sistema software', NULL, NULL),
(1, 7, 'PO-4 / Norma sulla creazione dei casi di test per un sistema software', NULL, NULL),
(4, 1, 'settore sotto la direzione de Chief Operating Officier', NULL, NULL),
(4, 15, '1.documentazione sui fornitori', NULL, NULL),
(4, 16, 'accesso alle finanze dell''azienda', NULL, NULL),
(7, 5, 'hgjghjjfj', NULL, NULL),
(7, 7, 'jgfjgjg', NULL, NULL),
(6, 5, 'abasdfsdfadf', NULL, NULL),
(6, 7, 'sdfsdf', NULL, NULL),
(8, 5, 'aaaaaaaaaaaaaaaaaaa', NULL, NULL),
(8, 7, 'rrrrrrrrrrrrrrrrrrrrr', NULL, NULL),
(9, 5, 'aaaaaaaaaaaaaaaaaaa', NULL, NULL),
(9, 7, 'rrrrrrrrrrrrrrrrrrrrr', NULL, NULL),
(10, 1, 'ddddddddddd', NULL, NULL),
(10, 15, 'rrrrrrrrrrrr', NULL, NULL),
(10, 16, 'pppppppppppp', NULL, NULL),
(11, 1, 'ddddddddddd', NULL, NULL),
(11, 15, 'rrrrrrrrrrrr', NULL, NULL),
(11, 16, 'pppppppppppp', NULL, NULL),
(12, 16, 'pppppppppppp', NULL, NULL),
(12, 15, 'rrrrrrrrrrrr', NULL, NULL),
(12, 1, 'ddddddddddd', NULL, NULL);

-- --------------------------------------------------------

--
-- Struttura della tabella `valori_campo_small`
--

DROP TABLE IF EXISTS `valori_campo_small`;
CREATE TABLE IF NOT EXISTS `valori_campo_small` (
  `id_doc` int(11) NOT NULL,
  `id_campo` int(11) NOT NULL,
  `valore_it` varchar(30) DEFAULT NULL,
  `valore_eng` varchar(30) DEFAULT NULL,
  `valore_de` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id_doc`,`id_campo`),
  KEY `id_campo` (`id_campo`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `valori_campo_small`
--

INSERT INTO `valori_campo_small` (`id_doc`, `id_campo`, `valore_it`, `valore_eng`, `valore_de`) VALUES
(1, 4, 'norma per il collaudo', NULL, NULL),
(4, 2, 'settore Operation Management', NULL, NULL),
(6, 4, 'scsadad', NULL, NULL),
(7, 4, 'jhgjhgjfhjfh', NULL, NULL),
(8, 4, 'sssssssssssssssssss', NULL, NULL),
(9, 4, 'sssssssssssssssssss', NULL, NULL),
(10, 2, 'sssssssssss', NULL, NULL),
(11, 2, 'sssssssssss', NULL, NULL),
(12, 2, 'sssssssssss', NULL, NULL);
