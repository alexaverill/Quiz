-- phpMyAdmin SQL Dump
-- version 4.0.6deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 11, 2014 at 03:50 AM
-- Server version: 5.5.35-0ubuntu0.13.10.2
-- PHP Version: 5.5.3-1ubuntu2.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `quizzing`
--

-- --------------------------------------------------------

--
-- Table structure for table `Events`
--

CREATE TABLE IF NOT EXISTS `Events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Event` varchar(45) DEFAULT NULL,
  `maxQuestions` int(11) NOT NULL,
  `totalApproved` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `Events`
--

INSERT INTO `Events` (`id`, `Event`, `maxQuestions`, `totalApproved`) VALUES
(1, 'Rocks and  Minerals', 16, 11),
(2, 'Astronomy', 6, 2);

-- --------------------------------------------------------

--
-- Table structure for table `Questions`
--

CREATE TABLE IF NOT EXISTS `Questions` (
  `idQuestions` int(11) NOT NULL AUTO_INCREMENT,
  `eventid` int(11) NOT NULL,
  `eventNumber` int(11) NOT NULL,
  `Approved` tinyint(1) DEFAULT '0',
  `Question` text,
  `optionA` varchar(140) DEFAULT NULL,
  `optionB` varchar(140) DEFAULT NULL,
  `optionC` varchar(140) DEFAULT NULL,
  `optionD` varchar(140) DEFAULT NULL,
  `optionE` varchar(140) DEFAULT NULL,
  `correctResponse` varchar(140) DEFAULT NULL,
  `questionType` int(11) DEFAULT NULL,
  `KeyWords` text NOT NULL,
  `year` int(11) NOT NULL,
  `imageLocation` varchar(288) NOT NULL,
  PRIMARY KEY (`idQuestions`,`eventid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=39 ;

--
-- Dumping data for table `Questions`
--

INSERT INTO `Questions` (`idQuestions`, `eventid`, `eventNumber`, `Approved`, `Question`, `optionA`, `optionB`, `optionC`, `optionD`, `optionE`, `correctResponse`, `questionType`, `KeyWords`, `year`, `imageLocation`) VALUES
(11, 1, 1, 1, 'Testing', 'This', 'Is ', 'A ', 'test', 'HA', '5', 1, '', 0, ''),
(12, 1, 2, 1, 'Test', 'Lol', 'Lol', 'LOl', 'LOL', 'LLLL', '1', 1, '', 0, ''),
(13, 1, 3, 1, 'This', 'should', 'work', 'I r', 'reall', 'hopw', '1', 1, '', 0, ''),
(14, 1, 4, 1, 'HEHE', 'hda', 'ads', 'afsd', 'asd', 'dds', '1', 1, '', 0, ''),
(15, 1, 5, 1, 'Test', 'Test', 'Test', 'Tst', 'tst', 'asg', '1', 1, '', 0, ''),
(16, 1, 6, 1, '', '', '', '', '', '', '1', 1, '', 0, ''),
(17, 1, 7, 1, 'Wht', 'do', 'u', 'hate', 'me ', 'lol', '1', 1, '', 0, ''),
(18, 1, 8, 1, 'ga', 'fasd', 'asfd', 'asd', 'asdfsaadfas', 'asdf', '1', 1, '', 0, ''),
(22, 1, 9, 1, 'fdasf', 'adsf', 'adsf', 'adsf', 'asdf', 'asdf', '1', 1, '', 0, ''),
(23, 2, 1, 1, 'adfdsa', 'adfdsa', 'adsfdsa', 'adsfads', 'adsfads', 'asdfadsfdsa', '1', 1, '', 0, ''),
(24, 2, 2, 1, 'dasfda', 'fadsfda', 'fdasfdsa', 'asdf', 'asdfdsaf', 'asdfdsafsdafsad', '1', 1, '', 0, ''),
(36, 1, 10, 0, 'Testing Images            \r\n        ', 'Imag', 'imag', 'imag', 'imag', 'imag', '1', 3, '', 0, 'images/clean.png'),
(37, 1, 11, 1, 'LOL           \r\n        ', 'LOL', 'LOL', 'MD5', 'MD5', 'MD5', '1', 3, '', 0, 'images/aad3a44747d595b1f02fe3a5c0ba6695.png'),
(38, 1, 16, 0, '     dfadsfdsa       \r\n        ', 'adfdsa', 'adsf', 'adsf', 'adsf', 'dafdsa', '1', 1, '', 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `userData`
--

CREATE TABLE IF NOT EXISTS `userData` (
  `iduserData` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `eventid` int(11) NOT NULL,
  `numberCorrect` int(11) DEFAULT NULL,
  `totalTaken` int(11) DEFAULT NULL,
  `totalMC` int(11) DEFAULT NULL,
  `totalFR` int(11) DEFAULT NULL,
  PRIMARY KEY (`iduserData`,`userid`,`eventid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
