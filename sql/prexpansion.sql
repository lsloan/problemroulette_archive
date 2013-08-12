-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 12, 2013 at 02:25 PM
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

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
(7, 2, 6),
(8, 3, 8);

-- --------------------------------------------------------

--
-- Table structure for table `12m_prob_ans`
--

DROP TABLE IF EXISTS `12m_prob_ans`;
CREATE TABLE IF NOT EXISTS `12m_prob_ans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prob_id` int(11) NOT NULL,
  `ans_num` tinyint(4) NOT NULL,
  `count` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=72 ;

--
-- Dumping data for table `12m_prob_ans`
--

INSERT INTO `12m_prob_ans` (`id`, `prob_id`, `ans_num`, `count`) VALUES
(29, 1, 1, 4),
(30, 1, 2, 1),
(31, 1, 3, 1),
(32, 1, 4, 18),
(33, 1, 5, 1),
(34, 2, 1, 1),
(35, 2, 2, 1),
(36, 2, 3, 0),
(37, 2, 4, 3),
(38, 2, 5, 1),
(39, 3, 1, 0),
(40, 3, 2, 1),
(41, 3, 3, 3),
(42, 3, 4, 2),
(43, 4, 1, 1),
(44, 4, 2, 3),
(45, 4, 3, 1),
(46, 4, 4, 1),
(47, 4, 5, 0),
(48, 5, 1, 0),
(49, 5, 2, 0),
(50, 5, 3, 1),
(51, 5, 4, 1),
(52, 6, 1, 2),
(53, 6, 2, 5),
(54, 6, 3, 2),
(55, 6, 4, 3),
(56, 6, 5, 0),
(57, 7, 1, 2),
(58, 7, 2, 20),
(59, 7, 3, 1),
(60, 7, 4, 1),
(61, 7, 5, 2),
(62, 8, 1, 1),
(63, 8, 2, 4),
(64, 8, 3, 1),
(65, 8, 4, 2),
(66, 8, 5, 1),
(67, 9, 1, 1),
(68, 9, 2, 0),
(69, 9, 3, 1),
(70, 9, 4, 1),
(71, 9, 5, 1);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

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
(7, 7, 3),
(8, 8, 8),
(9, 8, 9);

-- --------------------------------------------------------

--
-- Table structure for table `class`
--

DROP TABLE IF EXISTS `class`;
CREATE TABLE IF NOT EXISTS `class` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `class`
--

INSERT INTO `class` (`id`, `name`) VALUES
(1, 'Course 1'),
(2, 'Course 2'),
(3, 'Course 3');

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
  `tot_tries` int(11) NOT NULL DEFAULT '0',
  `tot_correct` int(11) NOT NULL DEFAULT '0',
  `tot_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `problems`
--

INSERT INTO `problems` (`id`, `name`, `url`, `correct`, `ans_count`, `tot_tries`, `tot_correct`, `tot_time`) VALUES
(1, 'UM PHYSICS 482 Midterm 2 Fall 2012 Problem 04', 'https://docs.google.com/document/pub?id=1GJJRnzEkBpc9lraarvIFLDvbfvD5htZji3VTMIi4jbI', 4, 5, 25, 18, 430),
(2, 'UM PHYSICS 482 Midterm 2 Fall 2012 Problem 04', 'https://docs.google.com/document/pub?id=1i36rzfgw-UfyzVllsYk0DAAMIatmJLppEuMfnb0JBJ8', 4, 5, 6, 3, 39),
(3, 'UM PHYSICS 481 Midterm 1 Fall 2011 Problem 03', 'https://docs.google.com/document/pub?id=16wrvDon3w3ZmuCG53V2m5LsgLuvXUkPBlc9OuxA5D0k', 3, 4, 6, 3, 49),
(4, 'UM PHYSICS 482 Midterm 2 Fall 2012 Problem 04', 'https://docs.google.com/document/pub?id=1E7MNPd0K-_6H--kJ7ErBDuHiecQMJz5T-Jbh6TE2S6w', 4, 5, 6, 1, 362),
(5, 'UM PHYSICS 481 Midterm 1 Fall 2011 Problem 03', 'https://docs.google.com/document/pub?id=1CdgJvHjAkJij4uhZVuIUg8cUN3-66bVPtmJL7TR4wUk', 3, 4, 2, 1, 16),
(6, 'hi', 'https://docs.google.com/document/pub?id=1aT7UKpNv1KTkfFjs3dLTcAW1X_G5L4ryKz_LbwsI0vY', 2, 5, 12, 5, 145),
(7, 'hi', 'https://docs.google.com/document/pub?id=15Ok8cgBCnpQKHc8nNsCIJqJhlcysYSr-bnkAvI1z8K8', 2, 5, 30, 21, 382),
(8, 'Sample Problem 01', 'https://docs.google.com/document/pub?id=1bGrQwSzSFr9LYA02Ns_CxJchEdMrBFhMvEI1PoJGq7M', 3, 5, 9, 1, 60),
(9, 'Sample Problem 02', 'https://docs.google.com/document/pub?id=1Kq1TVfIG2vImUHT08CPHLgwoJmBCAUIr0C731L6pqm8', 1, 5, 4, 1, 22);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=125 ;

--
-- Dumping data for table `responses`
--

INSERT INTO `responses` (`id`, `user_id`, `prob_id`, `answer`, `start_time`, `end_time`) VALUES
(1, 0, 7, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 0, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 0, 1, 5, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 0, 1, 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, 0, 1, 3, '0000-00-00 00:00:00', '2013-07-23 19:12:40'),
(6, 0, 1, 4, '1970-01-01 00:00:00', '1970-01-01 00:00:00'),
(7, 0, 1, 4, '2013-07-23 19:17:44', '2013-07-23 19:17:50'),
(8, 0, 7, 1, '2013-07-23 19:20:15', '2013-07-23 19:20:19'),
(9, 0, 7, 5, '2013-07-23 19:26:35', '2013-07-23 19:26:39'),
(10, 0, 7, 3, '2013-07-23 19:32:25', '2013-07-23 19:32:34'),
(11, 0, 7, 1, '2013-07-23 19:38:13', '2013-07-23 19:38:44'),
(12, 0, 7, 3, '2013-07-23 19:38:58', '2013-07-23 19:39:03'),
(13, 1, 7, 1, '2013-07-23 19:46:52', '2013-07-23 19:46:57'),
(14, 1, 7, 3, '2013-07-23 20:13:24', '2013-07-23 20:13:31'),
(15, 1, 7, 5, '2013-07-23 20:14:31', '2013-07-23 20:14:35'),
(16, 1, 7, 4, '2013-07-23 20:15:48', '2013-07-23 20:15:54'),
(17, 1, 7, 3, '2013-07-23 20:17:02', '2013-07-23 20:17:08'),
(18, 1, 7, 1, '2013-07-23 20:17:57', '2013-07-23 20:18:04'),
(19, 1, 7, 5, '2013-07-23 20:19:20', '2013-07-23 20:19:30'),
(20, 1, 7, 2, '2013-07-23 20:19:42', '2013-07-23 20:19:50'),
(21, 1, 1, 4, '2013-07-23 20:21:09', '2013-07-23 20:21:19'),
(22, 1, 7, 3, '2013-07-23 20:21:31', '2013-07-23 20:21:49'),
(23, 1, 7, 3, '2013-07-23 20:30:27', '2013-07-23 20:30:32'),
(24, 1, 7, 1, '2013-07-23 20:31:13', '2013-07-23 20:31:18'),
(25, 1, 7, 5, '2013-07-23 20:32:20', '2013-07-23 20:32:25'),
(26, 1, 7, 2, '2013-07-23 20:32:37', '2013-07-23 20:32:41'),
(27, 1, 7, 3, '2013-07-23 20:34:12', '2013-07-23 20:36:06'),
(28, 1, 7, 1, '2013-07-23 20:57:15', '2013-07-23 20:57:23'),
(29, 1, 1, 1, '2013-07-23 20:57:44', '2013-07-23 20:57:49'),
(30, 1, 7, 2, '2013-07-23 20:58:05', '2013-07-23 20:58:11'),
(31, 1, 1, 1, '2013-07-23 21:23:23', '2013-07-23 21:23:28'),
(32, 1, 1, 5, '2013-07-23 21:41:05', '2013-07-23 21:41:11'),
(33, 1, 1, 3, '2013-07-23 21:42:13', '2013-07-23 21:42:18'),
(34, 1, 1, 4, '2013-07-23 21:45:55', '2013-07-23 21:46:00'),
(35, 1, 1, 4, '2013-07-23 21:46:47', '2013-07-23 21:46:59'),
(36, 1, 7, 3, '2013-07-23 21:58:29', '2013-07-23 21:58:36'),
(37, 1, 7, 1, '2013-07-24 18:08:36', '2013-07-24 18:08:43'),
(38, 1, 7, 5, '2013-07-24 18:20:16', '2013-07-24 18:20:23'),
(39, 1, 7, 4, '2013-07-24 18:21:05', '2013-07-24 18:21:14'),
(40, 1, 7, 1, '2013-07-24 18:21:54', '2013-07-24 18:22:16'),
(41, 1, 7, 2, '2013-07-24 18:32:43', '2013-07-24 18:34:01'),
(42, 1, 7, 2, '2013-07-24 18:42:18', '2013-07-24 18:42:24'),
(43, 1, 1, 4, '2013-07-24 18:42:59', '2013-07-24 18:43:04'),
(44, 1, 7, 2, '2013-07-24 18:43:49', '2013-07-24 18:43:54'),
(45, 1, 7, 2, '2013-07-24 18:45:18', '2013-07-24 18:45:28'),
(46, 1, 7, 2, '2013-07-24 18:47:11', '2013-07-24 18:47:17'),
(47, 1, 7, 2, '2013-07-24 18:49:42', '2013-07-24 18:49:47'),
(48, 1, 7, 2, '2013-07-24 18:50:01', '2013-07-24 18:50:06'),
(49, 1, 1, 4, '2013-07-24 18:52:57', '2013-07-24 18:53:03'),
(50, 1, 7, 2, '2013-07-24 18:53:53', '2013-07-24 18:53:58'),
(51, 1, 7, 2, '2013-07-24 18:54:20', '2013-07-24 18:54:25'),
(52, 1, 1, 4, '2013-07-24 18:56:30', '2013-07-24 18:56:36'),
(53, 1, 1, 4, '2013-07-24 19:00:24', '2013-07-24 19:00:29'),
(54, 1, 7, 2, '2013-07-24 19:03:14', '2013-07-24 19:03:19'),
(55, 1, 1, 4, '2013-07-24 19:06:35', '2013-07-24 19:06:39'),
(56, 1, 7, 2, '2013-07-24 19:13:19', '2013-07-24 19:13:30'),
(57, 1, 1, 4, '2013-07-24 19:13:41', '2013-07-24 19:18:23'),
(58, 1, 7, 2, '2013-07-24 19:18:55', '2013-07-24 19:19:05'),
(59, 1, 7, 2, '2013-07-24 19:19:48', '2013-07-24 19:19:53'),
(60, 1, 1, 4, '2013-07-24 19:21:37', '2013-07-24 19:21:42'),
(61, 1, 1, 4, '2013-07-24 19:22:44', '2013-07-24 19:22:47'),
(62, 1, 7, 2, '2013-07-24 19:23:35', '2013-07-24 19:23:39'),
(63, 1, 6, 2, '2013-07-24 19:25:16', '2013-07-24 19:25:22'),
(64, 1, 6, 4, '2013-07-24 19:30:29', '2013-07-24 19:30:50'),
(65, 1, 1, 1, '2013-07-24 19:40:02', '2013-07-24 19:40:10'),
(66, 1, 1, 4, '2013-07-24 19:40:45', '2013-07-24 19:40:56'),
(67, 1, 7, 2, '2013-07-24 19:41:02', '2013-07-24 19:41:08'),
(68, 1, 6, 2, '2013-07-24 19:44:19', '2013-07-24 19:44:25'),
(69, 1, 3, 3, '2013-07-24 19:44:37', '2013-07-24 19:44:53'),
(70, 1, 5, 4, '2013-07-24 19:44:58', '2013-07-24 19:45:07'),
(71, 1, 2, 4, '2013-07-24 19:45:13', '2013-07-24 19:45:18'),
(72, 1, 1, 4, '2013-07-24 20:34:17', '2013-07-24 20:34:28'),
(73, 1, 6, 2, '2013-07-24 20:36:52', '2013-07-24 20:36:58'),
(74, 1, 7, 2, '2013-07-24 20:37:04', '2013-07-24 20:37:13'),
(75, 1, 3, 3, '2013-07-24 20:37:17', '2013-07-24 20:37:23'),
(76, 1, 2, 4, '2013-07-24 20:37:27', '2013-07-24 20:37:33'),
(77, 1, 3, 3, '2013-07-24 20:39:29', '2013-07-24 20:39:34'),
(78, 1, 2, 4, '2013-07-24 20:39:38', '2013-07-24 20:39:44'),
(79, 1, 4, 1, '2013-07-26 19:57:13', '2013-07-26 19:57:19'),
(80, 1, 7, 5, '2013-07-26 19:57:24', '2013-07-26 19:57:29'),
(81, 1, 6, 4, '2013-07-26 19:57:33', '2013-07-26 19:57:44'),
(82, 1, 3, 4, '2013-07-26 19:57:48', '2013-07-26 19:57:57'),
(83, 1, 4, 2, '2013-07-26 19:58:04', '2013-07-26 19:58:10'),
(84, 1, 4, 3, '2013-07-26 19:58:15', '2013-07-26 19:58:20'),
(85, 1, 3, 4, '2013-07-26 19:58:24', '2013-07-26 19:58:30'),
(86, 1, 6, 3, '2013-07-26 19:58:34', '2013-07-26 19:58:39'),
(87, 1, 6, 1, '2013-07-26 19:58:43', '2013-07-26 19:58:48'),
(88, 1, 6, 1, '2013-07-26 19:58:43', '2013-07-26 19:58:49'),
(89, 1, 2, 5, '2013-07-26 19:58:52', '2013-07-26 19:58:57'),
(90, 1, 1, 1, '2013-07-26 19:59:01', '2013-07-26 19:59:06'),
(91, 1, 1, 2, '2013-07-26 19:59:12', '2013-07-26 19:59:17'),
(92, 1, 2, 1, '2013-07-26 19:59:22', '2013-07-26 19:59:31'),
(93, 1, 1, 4, '2013-07-26 22:05:29', '2013-07-26 22:05:35'),
(94, 1, 9, 4, '2013-07-28 23:02:04', '2013-07-28 23:02:10'),
(95, 1, 8, 2, '2013-07-28 23:02:14', '2013-07-28 23:02:21'),
(96, 1, 8, 2, '2013-07-28 23:02:14', '2013-07-28 23:02:22'),
(97, 1, 9, 5, '2013-07-28 23:02:30', '2013-07-28 23:02:35'),
(98, 1, 8, 2, '2013-07-28 23:02:41', '2013-07-28 23:02:49'),
(99, 1, 9, 3, '2013-07-28 23:02:55', '2013-07-28 23:03:02'),
(100, 1, 9, 1, '2013-07-28 23:03:23', '2013-07-28 23:03:27'),
(101, 1, 8, 2, '2013-07-28 23:03:31', '2013-07-28 23:03:37'),
(102, 1, 8, 1, '2013-07-28 23:03:43', '2013-07-28 23:03:55'),
(103, 1, 8, 5, '2013-07-28 23:04:00', '2013-07-28 23:04:05'),
(104, 1, 8, 4, '2013-07-28 23:04:08', '2013-07-28 23:04:12'),
(105, 1, 8, 4, '2013-07-28 23:04:16', '2013-07-28 23:04:20'),
(106, 1, 8, 3, '2013-07-28 23:04:23', '2013-07-28 23:04:29'),
(107, 1, 6, 3, '2013-07-29 00:37:53', '2013-07-29 00:38:48'),
(108, 1, 6, 4, '2013-07-29 00:39:12', '2013-07-29 00:39:17'),
(109, 1, 1, 4, '2013-07-29 00:39:23', '2013-07-29 00:39:28'),
(110, 1, 7, 2, '2013-07-29 00:39:45', '2013-07-29 00:39:50'),
(111, 1, 4, 2, '2013-07-29 18:18:18', '2013-07-29 18:18:29'),
(112, 1, 1, 4, '2013-07-29 18:22:47', '2013-07-29 18:22:53'),
(113, 1, 7, 2, '2013-07-29 18:22:56', '2013-07-29 18:23:00'),
(114, 1, 4, 2, '2013-07-29 18:23:13', '2013-07-29 18:28:21'),
(115, 1, 1, 4, '2013-07-29 18:36:27', '2013-07-29 18:36:32'),
(116, 1, 7, 2, '2013-07-29 18:36:36', '2013-07-29 18:36:40'),
(117, 1, 3, 2, '2013-08-03 14:22:18', '2013-08-03 14:22:25'),
(118, 1, 1, 4, '2013-08-07 15:09:00', '2013-08-07 15:09:06'),
(119, 1, 2, 2, '2013-08-07 15:32:10', '2013-08-07 15:32:18'),
(120, 1, 6, 2, '2013-08-07 15:32:23', '2013-08-07 15:32:29'),
(121, 1, 5, 3, '2013-08-07 15:35:46', '2013-08-07 15:35:53'),
(122, 1, 4, 4, '2013-08-07 15:50:56', '2013-08-07 15:51:22'),
(123, 1, 6, 2, '2013-08-08 18:53:48', '2013-08-08 18:54:01'),
(124, 6, 1, 4, '2013-08-08 19:04:46', '2013-08-08 19:04:54');

-- --------------------------------------------------------

--
-- Table structure for table `stats`
--

DROP TABLE IF EXISTS `stats`;
CREATE TABLE IF NOT EXISTS `stats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `tot_tries` int(11) NOT NULL DEFAULT '0',
  `tot_correct` int(11) NOT NULL DEFAULT '0',
  `tot_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `stats`
--

INSERT INTO `stats` (`id`, `user_id`, `tot_tries`, `tot_correct`, `tot_time`) VALUES
(1, 1, 106, 54, 1535);

-- --------------------------------------------------------

--
-- Table structure for table `topic`
--

DROP TABLE IF EXISTS `topic`;
CREATE TABLE IF NOT EXISTS `topic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

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
(7, 'Final Exam'),
(8, 'Sample Topic 01');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `staff`, `prefs`) VALUES
(1, 'test_user', 0, 'a:22:{s:10:"page_loads";i:1690;s:15:"selected_course";s:1:"1";s:20:"selected_topics_list";s:1:"7";s:13:"last_activity";i:1375121860;s:21:"omitted_problems_list";a:6:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;i:4;i:5;i:5;i:6;}s:15:"current_problem";s:1:"4";s:17:"problem_submitted";s:1:"4";s:24:"omitted_problems_list[2]";N;s:24:"omitted_problems_list[5]";N;s:24:"omitted_problems_list[7]";N;s:23:"omitted_problem_list[5]";i:8;s:13:"current_topic";s:1:"7";s:10:"start_time";i:1375890656;s:10:"solve_time";i:0;s:8:"end_time";i:1375890682;s:23:"dropdown_history_course";s:3:"all";s:24:"omitted_problems_list[8]";N;s:22:"dropdown_history_topic";s:3:"all";s:24:"omitted_problems_list[1]";N;s:24:"omitted_problems_list[3]";N;s:24:"omitted_problems_list[4]";N;s:24:"omitted_problems_list[6]";N;}'),
(2, 'testprefs', 0, 'a:3:{s:15:"selected_course";i:2;s:20:"selected_topics_list";a:2:{i:0;i:1;i:1;i:2;}s:13:"last_activity";i:1373403912;}'),
(3, 'test_user2', 0, 'a:0:{}'),
(4, 'test_user3', 0, 'a:0:{}'),
(5, 'test_user4', 0, 'a:2:{s:15:"selected_course";s:1:"1";s:13:"last_activity";i:1375987933;}'),
(6, 'test_user5', 0, 'a:14:{s:15:"selected_course";s:1:"1";s:13:"last_activity";i:1375988023;s:10:"page_loads";i:9;s:20:"selected_topics_list";a:3:{i:0;s:1:"2";i:1;s:1:"5";i:2;s:1:"7";}s:15:"current_problem";s:1:"1";s:17:"problem_submitted";s:1:"4";s:13:"current_topic";s:1:"2";s:10:"start_time";i:1375988686;s:8:"end_time";i:1375988694;s:24:"omitted_problems_list[5]";N;s:24:"omitted_problems_list[2]";N;s:24:"omitted_problems_list[7]";N;s:23:"dropdown_history_course";s:3:"all";s:22:"dropdown_history_topic";s:3:"all";}');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
