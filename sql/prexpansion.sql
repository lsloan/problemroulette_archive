-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 18, 2013 at 02:20 PM
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `12m_class_topic`
--

INSERT INTO `12m_class_topic` (`id`, `class_id`, `topic_id`) VALUES
(1, 1, 2),
(2, 1, 5),
(3, 1, 7),
(4, 2, 1),
(5, 2, 3),
(6, 2, 4),
(7, 2, 6);

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
-- Table structure for table `12m_prob_ans_stats`
--

DROP TABLE IF EXISTS `12m_prob_ans_stats`;
CREATE TABLE IF NOT EXISTS `12m_prob_ans_stats` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `12m_topic_prob`
--

INSERT INTO `12m_topic_prob` (`id`, `topic_id`, `problem_id`) VALUES
(1, 2, 1),
(2, 2, 7),
(3, 5, 2),
(4, 5, 6),
(5, 7, 4),
(6, 7, 5),
(7, 7, 3);

-- --------------------------------------------------------

--
-- Table structure for table `class`
--

DROP TABLE IF EXISTS `class`;
CREATE TABLE IF NOT EXISTS `class` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `class`
--

INSERT INTO `class` (`id`, `name`) VALUES
(1, 'Course 1'),
(2, 'Course 2');

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
(1, 'UM PHYSICS 482 Midterm 2 Fall 2012 Problem 04', 'https://docs.google.com/document/pub?id=1GJJRnzEkBpc9lraarvIFLDvbfvD5htZji3VTMIi4jbI', 4, 5, 0, 0, 0),
(2, 'UM PHYSICS 482 Midterm 2 Fall 2012 Problem 04', 'https://docs.google.com/document/pub?id=1i36rzfgw-UfyzVllsYk0DAAMIatmJLppEuMfnb0JBJ8', 4, 5, 0, 0, 0),
(3, 'UM PHYSICS 481 Midterm 1 Fall 2011 Problem 03', 'https://docs.google.com/document/pub?id=16wrvDon3w3ZmuCG53V2m5LsgLuvXUkPBlc9OuxA5D0k', 3, 4, 0, 0, 0),
(4, 'UM PHYSICS 482 Midterm 2 Fall 2012 Problem 04', 'https://docs.google.com/document/pub?id=1E7MNPd0K-_6H--kJ7ErBDuHiecQMJz5T-Jbh6TE2S6w', 4, 5, 0, 0, 0),
(5, 'UM PHYSICS 481 Midterm 1 Fall 2011 Problem 03', 'https://docs.google.com/document/pub?id=1CdgJvHjAkJij4uhZVuIUg8cUN3-66bVPtmJL7TR4wUk', 3, 4, 0, 0, 0),
(6, 'hi', 'https://docs.google.com/document/pub?id=1aT7UKpNv1KTkfFjs3dLTcAW1X_G5L4ryKz_LbwsI0vY', 2, 5, 0, 0, 0),
(7, 'hi', 'https://docs.google.com/document/pub?id=15Ok8cgBCnpQKHc8nNsCIJqJhlcysYSr-bnkAvI1z8K8', 2, 5, 0, 0, 0);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `topic`
--

INSERT INTO `topic` (`id`, `name`) VALUES
(1, 'Topic 1'),
(2, 'Midterm 2'),
(3, 'Topic 3'),
(4, 'Topic 4'),
(5, 'Chapter 12'),
(6, 'Topic 6'),
(7, 'Final Exam');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `staff` int(11) NOT NULL,
  `prefs` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `staff`, `prefs`) VALUES
(1, 'test_user', 0, 'a:11:{s:10:"page_loads";i:807;s:15:"selected_course";s:1:"1";s:20:"selected_topics_list";a:3:{i:0;s:1:"2";i:1;s:1:"5";i:2;s:1:"7";}s:13:"last_activity";i:1374076206;s:21:"omitted_problems_list";a:6:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;i:4;i:5;i:5;i:6;}s:15:"current_problem";s:1:"7";s:17:"problem_submitted";s:1:"2";s:24:"omitted_problems_list[2]";N;s:24:"omitted_problems_list[5]";N;s:24:"omitted_problems_list[7]";N;s:23:"omitted_problem_list[5]";i:8;}'),
(2, 'testprefs', 0, 'a:3:{s:15:"selected_course";i:2;s:20:"selected_topics_list";a:2:{i:0;i:1;i:1;i:2;}s:13:"last_activity";i:1373403912;}');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
