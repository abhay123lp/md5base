-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Gostitelj: localhost
-- Čas nastanka: 22 Jul 2010 ob 02:49 PM
-- Različica strežnika: 5.1.41
-- Različica PHP: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Podatkovna baza: `andmer90_md5base`
--

-- --------------------------------------------------------

--
-- Struktura tabele `hashes`
--

CREATE TABLE IF NOT EXISTS `hashes` (
  `hashvalue` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `filename` varchar(128) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `filesize` int(11) NOT NULL DEFAULT '0',
  `comment` varchar(164) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `timestamp` varchar(20) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `addedby` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktura tabele `help-categories`
--

CREATE TABLE IF NOT EXISTS `help-categories` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Struktura tabele `help-contents`
--

CREATE TABLE IF NOT EXISTS `help-contents` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `category` int(3) NOT NULL,
  `topic` varchar(255) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Struktura tabele `user-settings`
--

CREATE TABLE IF NOT EXISTS `user-settings` (
  `user_id` int(8) NOT NULL,
  `show_email` int(1) NOT NULL,
  `show_profile` int(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktura tabele `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `password` varchar(40) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `nhashes` int(11) NOT NULL DEFAULT '0',
  `email` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `activation` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `temp` varchar(32) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
