-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Machine: localhost
-- Genereertijd: 14 Feb 2010 om 20:36
-- Serverversie: 5.1.41
-- PHP-Versie: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `swift`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `process`
--

CREATE TABLE IF NOT EXISTS `process` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `process_name` varchar(40) NOT NULL,
  `process_start` datetime NOT NULL,
  `process_active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Gegevens worden uitgevoerd voor tabel `process`
--

INSERT INTO `process` (`id`, `process_name`, `process_start`, `process_active`) VALUES
(1, 'twitter', '0000-00-00 00:00:00', 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

--
-- Tabelstructuur voor tabel `twitter_users`
--

CREATE TABLE IF NOT EXISTS `twitter_search` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Gegevens worden uitgevoerd voor tabel `twitter_search`
--

INSERT INTO `twitter_search` (`id`, `name`, `created`) VALUES
(1, 'main', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `twitter_search_geo`
--

CREATE TABLE IF NOT EXISTS `twitter_search_geo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `search_id` int(11) NOT NULL,
  `placename` varchar(100) NOT NULL,
  `lattitude` double NOT NULL,
  `longitude` double NOT NULL,
  `radius` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `search_id` (`search_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `twitter_search_keywords`
--

CREATE TABLE IF NOT EXISTS `twitter_search_keywords` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `search_id` int(11) NOT NULL,
  `keyword` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;


-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `twitter_search_users`
--

CREATE TABLE IF NOT EXISTS `twitter_search_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `search_id` int(11) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE` (`search_id`,`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `twitter_tweets`
--

CREATE TABLE IF NOT EXISTS `twitter_tweets` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `text` varchar(140) NOT NULL,
  `created_at` datetime NOT NULL,
  `longitude` double DEFAULT NULL,
  `lattitude` double DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Tabelstructuur voor tabel `twitter_users`
--

CREATE TABLE IF NOT EXISTS `twitter_users` (
  `id` bigint(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `screen_name` varchar(20) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`,`screen_name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


