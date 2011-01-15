-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generato il: 15 gen, 2011 at 01:25 PM
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


-- --------------------------------------------------------

--
-- Struttura della tabella `azienda`
--

DROP TABLE IF EXISTS `azienda`;
CREATE TABLE IF NOT EXISTS `azienda` (
  `cf_iva` varchar(20) NOT NULL,
  `nome` char(30) NOT NULL,
  `mail` varchar(100) DEFAULT NULL,
  `tel` varchar(10) NOT NULL,
  `persona` char(50) NOT NULL,
  PRIMARY KEY (`cf_iva`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `azienda`
--


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
(3, 'obiettivi e responsabilit', NULL, NULL, 'long'),
(4, 'scopo', NULL, NULL, 'small'),
(5, 'abstract', NULL, NULL, 'medium'),
(6, 'step delle attivit', NULL, NULL, 'long'),
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
  `versione` varchar(5) NOT NULL,
  `id_campo` int(11) NOT NULL,
  `opzionale` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_classe`,`id_campo`),
  KEY `id_classe` (`id_classe`,`versione`),
  KEY `id_campo` (`id_campo`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `campo_classe`
--

INSERT INTO `campo_classe` (`id_classe`, `versione`, `id_campo`, `opzionale`) VALUES
(3, '1.0', 2, 0),
(3, '1.0', 1, 0),
(3, '1.0', 3, 0),
(3, '1.0', 15, 1),
(3, '1.0', 16, 1),
(4, '1.0', 4, 0),
(4, '1.0', 5, 0),
(4, '1.0', 6, 0),
(4, '1.0', 7, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `classe_documenti`
--

DROP TABLE IF EXISTS `classe_documenti`;
CREATE TABLE IF NOT EXISTS `classe_documenti` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `versione` varchar(5) NOT NULL,
  `nome` varchar(100) NOT NULL,
  PRIMARY KEY (`id`,`versione`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dump dei dati per la tabella `classe_documenti`
--

INSERT INTO `classe_documenti` (`id`, `versione`, `nome`) VALUES
(1, '1.0', 'allegati'),
(2, '1.0', 'documenti per assicurare la qualita'''),
(3, '1.0', 'documenti per la organizzazione aziendale'),
(4, '1.0', 'procedure operative'),
(5, '1.0', 'documenti tecnici'),
(6, '1.0', 'documenti statistici e gestionali'),
(7, '1.0', 'verbali di riunione'),
(8, '1.0', 'documenti provenienti dal''esterno'),
(9, '1.0', 'leggi, prescrizioni e regolamenti');

-- --------------------------------------------------------

--
-- Struttura della tabella `documento`
--

DROP TABLE IF EXISTS `documento`;
CREATE TABLE IF NOT EXISTS `documento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cont` int(11) NOT NULL,
  `versione` varchar(5) NOT NULL,
  `revisione` varchar(5) NOT NULL,
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
  UNIQUE KEY `cont` (`cont`,`versione`,`anno`,`classe`,`revisione`),
  KEY `classe` (`classe`,`versione`),
  KEY `approvatore` (`approvatore`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dump dei dati per la tabella `documento`
--


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


-- --------------------------------------------------------

--
-- Struttura della tabella `gruppo_doc`
--

DROP TABLE IF EXISTS `gruppo_doc`;
CREATE TABLE IF NOT EXISTS `gruppo_doc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome_gruppo` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dump dei dati per la tabella `gruppo_doc`
--


-- --------------------------------------------------------

--
-- Struttura della tabella `gruppo_utente`
--

DROP TABLE IF EXISTS `gruppo_utente`;
CREATE TABLE IF NOT EXISTS `gruppo_utente` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome_gruppo` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dump dei dati per la tabella `gruppo_utente`
--


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
  `nome` char(30) NOT NULL,
  `cognome` char(30) NOT NULL,
  `username` varchar(30) NOT NULL,
  `passwd` varchar(30) NOT NULL,
  `email` varchar(100) NOT NULL,
  `ruolo` enum('0','1','2') NOT NULL,
  PRIMARY KEY (`matricola`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dump dei dati per la tabella `utente`
--

INSERT INTO `utente` (`matricola`, `nome`, `cognome`, `username`, `passwd`, `email`, `ruolo`) VALUES
(1, 'Amministratore', 'di Sistema', 'admin', 'admin', 'admin@yourcompany.com', '0');

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

