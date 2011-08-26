-- phpMyAdmin SQL Dump
-- version 3.3.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 26. August 2011 um 17:17
-- Server Version: 5.1.54
-- PHP-Version: 5.3.5-1ubuntu7.2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `xing_contacts`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `admins`
--

CREATE TABLE IF NOT EXISTS `admins` (
  `name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `admins`
--

INSERT INTO `admins` (`name`, `password`) VALUES
('admin', 'admin'),
('robbi', 'robbi');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `contacts`
--

CREATE TABLE IF NOT EXISTS `contacts` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telephone` varchar(255) NOT NULL,
  `status` varchar(12) NOT NULL,
  `first_contact_at` date NOT NULL,
  `first_contact_over_profile` varchar(255) NOT NULL,
  `first_contact_from` varchar(255) NOT NULL,
  `last_update` date DEFAULT NULL,
  `infos` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=29 ;

--
-- Daten für Tabelle `contacts`
--

INSERT INTO `contacts` (`id`, `firstname`, `name`, `email`, `telephone`, `status`, `first_contact_at`, `first_contact_over_profile`, `first_contact_from`, `last_update`, `infos`) VALUES
(1, 'adsad', 'asdsadsa', 'asdasdsa', '21321321', 'FINAL (G)', '2011-08-24', 'sewqesss', 'wqewqe', '2011-08-24', 'sadsadsad'),
(2, 'adsad', 'asdsadsa', 'asdasdsa', '21321321', 'FINAL (R)', '2011-08-24', 'ewqeww', 'wqewqewww', '2011-08-24', 'sadsadsad'),
(3, 'abc', 'abc', 'abc', 'abc', 'FINAL (G)', '0000-00-00', 'abc', 'abc', '0000-00-00', 'abc'),
(4, 'asdfsaf', 'saf', 'safsafsa', 'f', 'FORWARDED', '0000-00-00', 'dsad', 'sadsadcc', '0000-00-00', 'xycxycyxcyxc'),
(5, 'qwewqe', 'wewqewqe', 'wqewqe', 'wqewqe', 'TERMIN', '0000-00-00', 'wqe', 'wqewqe', '0000-00-00', 'wqewqewqewqe'),
(6, 'qwewqe', 'wewqewqe', 'wqewqe', 'wqewqe', 'PENDING (O)', '0000-00-00', 'wqe', 'wqewqe', '0000-00-00', 'wqewqewqewqe'),
(7, 'abc', 'abc', 'abc', 'abc', 'PENDING (V)', '0000-00-00', 'abc', 'abc', '0000-00-00', 'abc'),
(8, 'abc', 'abc', 'abc', 'abc', 'POOL', '0000-00-00', 'abc', 'abc', '0000-00-00', 'abc'),
(9, 'abc', 'abc', 'abc', 'abc', 'FINAL (R)', '0000-00-00', 'abc', 'abc', '0000-00-00', 'abc'),
(10, 'abc', 'abc', 'abc', 'abc', 'FINAL (R)', '0000-00-00', 'abc', 'abc', '0000-00-00', 'abc'),
(11, 'abc', 'abc', 'abc', 'abc', 'PENDING (V)', '0000-00-00', 'abc', 'abc', '0000-00-00', 'abc'),
(12, 'abc', 'abc', 'abc', 'abc', 'POOL', '0000-00-00', 'abc', 'abc', '0000-00-00', 'abc'),
(13, 'abc', 'abc', 'abc', 'abc', 'PENDING (V)', '0000-00-00', 'abc', 'abc', '0000-00-00', 'abc'),
(14, 'abc', 'abc', 'abc', 'abc', 'TERMIN', '0000-00-00', 'abc', 'abc', '0000-00-00', 'abc'),
(15, 'abc', 'abc', 'abc', 'abc', 'FINAL (G)', '0000-00-00', 'abc', 'abc', '0000-00-00', 'abc'),
(16, 'abc', 'abc', 'abc', 'abc', 'POOL', '0000-00-00', 'abc', 'abc', '0000-00-00', 'abc'),
(17, 'abc', 'abc', 'abc', 'abc', 'PENDING (V)', '0000-00-00', 'abc', 'abc', '0000-00-00', 'abc'),
(18, 'abc', 'abc', 'abc', 'abc', 'PENDING (O)', '0000-00-00', 'abc', 'abc', '0000-00-00', 'abc'),
(19, 'abc', 'abc', 'abc', 'abc', 'TERMIN', '0000-00-00', 'abc', 'abc', '0000-00-00', 'abc'),
(20, 'abc', 'abc', 'abc', 'abc', 'FINAL (G)', '0000-00-00', 'abc', 'abc', '0000-00-00', 'abc'),
(21, 'abc', 'abc', 'abc', 'abc', 'TERMIN', '0000-00-00', 'abc', 'abc', '0000-00-00', 'abc'),
(22, 'abc', 'abc', 'abc', 'abc', 'PENDING (V)', '0000-00-00', 'abc', 'abc', '0000-00-00', 'abc'),
(23, 'abc', 'abc', 'abc', 'abc', 'FINAL (R)', '0000-00-00', 'abc', 'abc', '0000-00-00', 'abc'),
(24, 'abc', 'abc', 'abc', 'abc', 'FINAL (R)', '0000-00-00', 'abc', 'abc', '0000-00-00', 'abc'),
(25, 'abcd', 'abc', 'abc', 'abc', 'FORWARDED', '0000-00-00', 'abc', 'abc', '0000-00-00', 'abc'),
(26, 'abcd', 'ab23232c', 'abc', 'abc', 'FINAL (R)', '0000-00-00', 'abc', 'abc', '0000-00-00', 'abc'),
(27, 'ab', 'ab23232c', 'abc', 'abc', 'FORWARDED', '0000-00-00', 'abc', 'abc', '0000-00-00', 'abc'),
(28, 'abcd', 'ab23232c', 'abc', 'a222bc', 'FORWARDED', '0000-00-00', 'abc', 'abc', '0000-00-00', 'abc');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `zugriffe`
--

CREATE TABLE IF NOT EXISTS `zugriffe` (
  `zugriffe` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(222) NOT NULL,
  PRIMARY KEY (`zugriffe`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=77 ;

--
-- Daten für Tabelle `zugriffe`
--

INSERT INTO `zugriffe` (`zugriffe`, `name`) VALUES
(1, 'unlogged'),
(2, 'unlogged'),
(3, 'unlogged'),
(4, 'logged'),
(5, 'logged'),
(6, 'logged'),
(7, 'unlogged'),
(8, 'unlogged'),
(9, 'logged'),
(10, 'logged'),
(11, 'logged'),
(12, 'logged'),
(13, 'logged'),
(14, 'logged'),
(15, 'unlogged'),
(16, 'unlogged'),
(17, 'unlogged'),
(18, 'unlogged'),
(19, 'unlogged'),
(20, 'unlogged'),
(21, 'true'),
(22, 'true'),
(23, 'true'),
(24, 'true'),
(25, 'true'),
(26, 'true'),
(27, 'true'),
(28, 'true'),
(29, 'true'),
(30, 'true'),
(31, 'true'),
(32, 'true'),
(33, 'true'),
(34, 'true'),
(35, 'true'),
(36, 'true'),
(37, 'true'),
(38, 'true'),
(39, 'true'),
(40, 'true'),
(41, 'true'),
(42, 'true'),
(43, 'true'),
(44, 'true'),
(45, 'true'),
(46, 'true'),
(47, 'true'),
(48, 'true'),
(49, 'true'),
(50, ''),
(51, ''),
(52, 'unlogged'),
(53, 'unlogged'),
(54, 'logged'),
(55, 'logged'),
(56, 'logged'),
(57, 'logged'),
(58, 'logged'),
(59, 'logged'),
(60, 'logged'),
(61, 'logged'),
(62, 'unlogged'),
(63, 'unlogged'),
(64, 'unlogged'),
(65, 'unlogged'),
(66, 'unlogged'),
(67, 'unlogged'),
(68, 'logged'),
(69, 'logged'),
(70, 'unlogged'),
(71, 'unlogged'),
(72, 'unlogged'),
(73, 'unlogged'),
(74, 'unlogged'),
(75, 'logged'),
(76, '');
