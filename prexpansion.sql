-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 29, 2013 at 07:15 PM
-- Server version: 5.5.24-log
-- PHP Version: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `prexpansion`
--

-- --------------------------------------------------------

--
-- Table structure for table `12m_class_topic`
--

DROP TABLE IF EXISTS `12m_class_topic`;
CREATE TABLE IF NOT EXISTS `12m_class_topic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class_id` int(11) NOT NULL,
  `topic_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `12m_prob_ans`
--

DROP TABLE IF EXISTS `12m_prob_ans`;
CREATE TABLE IF NOT EXISTS `12m_prob_ans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prob_id` int(11) NOT NULL,
  `ans_num` tinyint(4) NOT NULL,
  `count` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `12m_topic_prob`
--

DROP TABLE IF EXISTS `12m_topic_prob`;
CREATE TABLE IF NOT EXISTS `12m_topic_prob` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `topic_id` int(11) NOT NULL,
  `problem_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `class`
--

DROP TABLE IF EXISTS `class`;
CREATE TABLE IF NOT EXISTS `class` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `problems`
--

DROP TABLE IF EXISTS `problems`;
CREATE TABLE IF NOT EXISTS `problems` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `url` varchar(300) NOT NULL,
  `correct` int(11) NOT NULL,
  `ans_count` tinyint(4) NOT NULL,
  `tot_tries` int(11) NOT NULL,
  `tot_correct` int(11) NOT NULL,
  `tot_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `problems`
--

INSERT INTO `problems` (`id`, `name`, `url`, `correct`, `ans_count`, `tot_tries`, `tot_correct`, `tot_time`) VALUES
(1, 'UM PHYSICS 482 Midterm 2 Fall 2012 Problem 04', 'http://google.com', 4, 5, 0, 0, 0),
(2, 'UM PHYSICS 482 Midterm 2 Fall 2012 Problem 04', 'http://google.com', 4, 5, 0, 0, 0),
(3, 'UM PHYSICS 481 Midterm 1 Fall 2011 Problem 03', 'http://bing.com', 3, 4, 0, 0, 0),
(4, 'UM PHYSICS 482 Midterm 2 Fall 2012 Problem 04', 'http://google.com', 4, 5, 0, 0, 0),
(5, 'UM PHYSICS 481 Midterm 1 Fall 2011 Problem 03', 'http://bing.com', 3, 4, 0, 0, 0),
(6, 'hi', 'url-somewhere', 2, 5, 0, 0, 0),
(7, 'hi', 'url-somewhere', 2, 5, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `responses`
--

DROP TABLE IF EXISTS `responses`;
CREATE TABLE IF NOT EXISTS `responses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `prob_id` int(11) NOT NULL,
  `answer` tinyint(4) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `stats`
--

DROP TABLE IF EXISTS `stats`;
CREATE TABLE IF NOT EXISTS `stats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `tot_tries` int(11) NOT NULL,
  `tot_correct` int(11) NOT NULL,
  `tot_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `topic`
--

DROP TABLE IF EXISTS `topic`;
CREATE TABLE IF NOT EXISTS `topic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `staff` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
