-- phpMyAdmin SQL Dump
-- version 4.1.6
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 11, 2014 at 06:08 PM
-- Server version: 5.6.16
-- PHP Version: 5.5.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `online_test`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_answer`
--

CREATE TABLE IF NOT EXISTS `tbl_answer` (
  `answer_id` int(11) NOT NULL AUTO_INCREMENT,
  `fk_questions_id` int(11) NOT NULL,
  `answer_description` varchar(45) DEFAULT NULL,
  `is_correct` tinyint(1) DEFAULT '0',
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`answer_id`),
  KEY `fk_tbl_answer_tbl_questions_idx` (`fk_questions_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=53 ;

--
-- Dumping data for table `tbl_answer`
--

INSERT INTO `tbl_answer` (`answer_id`, `fk_questions_id`, `answer_description`, `is_correct`, `created_date`, `updated_date`, `is_deleted`, `is_active`) VALUES
(2, 1, '4.4', 0, '2014-10-30 05:50:08', '2014-10-30 05:50:08', 0, 1),
(3, 1, '4.9', 0, '2014-10-30 05:50:08', '2014-10-30 05:50:08', 0, 1),
(4, 1, '4.5.1', 1, '2014-10-30 05:50:08', '2014-10-30 05:50:08', 0, 1),
(5, 2, 'localPlayerDidSelectChallenge', 0, '2014-10-30 05:50:55', '2014-10-30 05:50:55', 0, 1),
(6, 2, 'challengeEventHandler', 1, '2014-10-30 05:50:55', '2014-10-30 05:50:55', 0, 1),
(7, 2, 'remotePlayerDidCompleteChallenge', 0, '2014-10-30 05:50:55', '2014-10-30 05:50:55', 0, 1),
(8, 3, 'shouldAutorotate', 0, '2014-10-30 05:51:58', '2014-10-30 05:51:58', 0, 1),
(9, 3, 'supportedInterfaceOrientations', 1, '2014-10-30 05:51:58', '2014-10-30 05:51:58', 0, 1),
(10, 3, 'shouldAutorotateToInterfaceOrientation', 0, '2014-10-30 05:51:58', '2014-10-30 05:51:58', 0, 1),
(11, 4, 'LSItemContentTypes', 1, '2014-10-30 05:54:23', '2014-10-30 05:54:23', 0, 1),
(12, 4, 'CFBundleTypeRole', 0, '2014-10-30 05:54:23', '2014-10-30 05:54:23', 0, 1),
(13, 4, 'LSHandlerRank', 0, '2014-10-30 05:54:23', '2014-10-30 05:54:23', 0, 1),
(14, 5, 'text', 0, '2014-10-30 05:55:24', '2014-10-30 05:55:24', 0, 1),
(15, 5, 'formattedText', 0, '2014-10-30 05:55:24', '2014-10-30 05:55:24', 0, 1),
(16, 5, 'styledString', 0, '2014-10-30 05:55:24', '2014-10-30 05:55:24', 0, 1),
(17, 5, 'None of above', 1, '2014-10-30 05:55:24', '2014-10-30 05:55:24', 0, 1),
(18, 6, 'MKPlacemarker', 0, '2014-10-30 05:56:53', '2014-10-30 05:56:53', 0, 1),
(19, 6, 'MKShape', 0, '2014-10-30 05:56:53', '2014-10-30 05:56:53', 0, 1),
(20, 6, 'None of the above', 1, '2014-10-30 05:56:53', '2014-10-30 05:56:53', 0, 1),
(21, 7, 'isPassLibraryExist', 0, '2014-10-30 05:58:00', '2014-10-30 05:58:00', 0, 1),
(22, 7, 'canAccessPassLibrary', 0, '2014-10-30 05:58:00', '2014-10-30 05:58:00', 0, 1),
(23, 7, 'None of the above', 1, '2014-10-30 05:58:00', '2014-10-30 05:58:00', 0, 1),
(24, 8, 'SKDownload', 1, '2014-10-30 05:59:25', '2014-10-30 05:59:25', 0, 1),
(25, 8, 'NSURLDownloader', 0, '2014-10-30 05:59:25', '2014-10-30 05:59:25', 0, 1),
(26, 8, 'All of the above', 0, '2014-10-30 05:59:25', '2014-10-30 05:59:25', 0, 1),
(27, 9, 'supportedInterfaceOrientations', 0, '2014-10-30 06:00:23', '2014-10-30 06:00:23', 0, 1),
(28, 9, 'shouldAutorotateToInterfaceOrientation', 0, '2014-10-30 06:00:23', '2014-10-30 06:00:23', 0, 1),
(29, 9, 'shouldAutorotate', 1, '2014-10-30 06:00:23', '2014-10-30 06:00:23', 0, 1),
(30, 10, 'NSObject', 0, '2014-10-30 06:01:53', '2014-10-30 06:01:53', 0, 1),
(31, 10, 'NSNetworkService', 0, '2014-10-30 06:01:53', '2014-10-30 06:01:53', 0, 1),
(32, 10, 'NSBase', 1, '2014-10-30 06:01:53', '2014-10-30 06:01:53', 0, 1),
(33, 10, 'NSProxy', 0, '2014-10-30 06:01:53', '2014-10-30 06:01:53', 0, 1),
(34, 11, 'Laser keyboard / iCloud backup', 1, '2014-10-30 06:04:03', '2014-10-30 06:04:03', 0, 1),
(35, 11, 'Apple Maps', 0, '2014-10-30 06:04:03', '2014-10-30 06:04:03', 0, 1),
(36, 11, 'FaceTime over Cellular', 0, '2014-10-30 06:04:03', '2014-10-30 06:04:03', 0, 1),
(37, 12, 'gSOAP', 1, '2014-10-30 06:05:41', '2014-10-30 06:05:41', 0, 1),
(38, 12, 'ASIHTTP framework', 1, '2014-10-30 06:05:41', '2014-10-30 06:05:41', 0, 1),
(39, 12, 'wsdl2objc framework', 1, '2014-10-30 06:05:41', '2014-10-30 06:05:41', 0, 1),
(40, 12, 'None of these', 0, '2014-10-30 06:05:41', '2014-10-30 06:05:41', 0, 1),
(41, 13, 'Beta build can be deployed only on devices', 1, '2014-10-30 06:06:33', '2014-10-30 06:06:33', 0, 1),
(42, 13, 'Beta Build can be deployed only on Simulators', 0, '2014-10-30 06:06:33', '2014-10-30 06:06:33', 0, 1),
(43, 13, 'All of above', 0, '2014-10-30 06:06:33', '2014-10-30 06:06:33', 0, 1),
(44, 14, '.NSTextAlignmentToCTTextAlignment', 1, '2014-10-30 06:11:03', '2014-10-30 06:11:03', 0, 1),
(45, 14, 'NSTextAlignmentGetCTTextAlignment', 0, '2014-10-30 06:11:03', '2014-10-30 06:11:03', 0, 1),
(46, 15, 'ARMv7', 1, '2014-10-30 06:12:19', '2014-10-30 06:12:19', 0, 1),
(47, 15, 'ARMv9', 0, '2014-10-30 06:12:19', '2014-10-30 06:12:19', 0, 1),
(48, 15, 'ARMv8', 0, '2014-10-30 06:12:19', '2014-10-30 06:12:19', 0, 1),
(49, 16, 'MBProgressHUD', 0, '2014-10-30 06:14:18', '2014-10-30 06:14:18', 0, 1),
(50, 16, 'RestKit', 1, '2014-10-30 06:14:18', '2014-10-30 06:14:18', 0, 1),
(51, 16, 'ASIHTTPRequest', 0, '2014-10-30 06:14:18', '2014-10-30 06:14:18', 0, 1),
(52, 16, 'StoreKit', 0, '2014-10-30 06:14:18', '2014-10-30 06:14:18', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_difficulty_levels`
--

CREATE TABLE IF NOT EXISTS `tbl_difficulty_levels` (
  `difficulty_levels_id` int(11) NOT NULL AUTO_INCREMENT,
  `difficulty_levels_title` varchar(45) DEFAULT NULL,
  `preference` tinyint(4) DEFAULT NULL COMMENT 'preference is using for difficulty level of question\n\nif preference is 1 then its top most lowest level\n\nif prefenrce is max value then most difficult level',
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `is_deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`difficulty_levels_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `tbl_difficulty_levels`
--

INSERT INTO `tbl_difficulty_levels` (`difficulty_levels_id`, `difficulty_levels_title`, `preference`, `created_date`, `updated_date`, `is_active`, `is_deleted`) VALUES
(1, 'Easy', 1, '2014-08-13 04:34:36', '2014-08-13 04:34:36', 1, 0),
(2, 'Moderate', 2, '2014-08-13 04:35:10', '2014-08-13 04:35:10', 1, 0),
(3, 'Difficult', 3, '2014-08-13 04:35:27', '2014-08-13 04:35:27', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_questions`
--

CREATE TABLE IF NOT EXISTS `tbl_questions` (
  `questions_id` int(11) NOT NULL AUTO_INCREMENT,
  `fk_difficulty_levels_id` int(11) NOT NULL,
  `sub_categories` varchar(45) DEFAULT NULL,
  `question_title` varchar(255) DEFAULT NULL COMMENT 'this is optional option',
  `answer_type` tinyint(2) DEFAULT NULL COMMENT '1 stands for radio button 2 stands for checkbox',
  `no_of_options` tinyint(2) DEFAULT NULL,
  `question_description` longtext,
  `correct_answers` varchar(45) DEFAULT NULL,
  `marks` float DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `is_deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`questions_id`),
  KEY `fk_tbl_questions_tbl_difficulty_levels1_idx` (`fk_difficulty_levels_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

--
-- Dumping data for table `tbl_questions`
--

INSERT INTO `tbl_questions` (`questions_id`, `fk_difficulty_levels_id`, `sub_categories`, `question_title`, `answer_type`, `no_of_options`, `question_description`, `correct_answers`, `marks`, `created_date`, `updated_date`, `is_active`, `is_deleted`) VALUES
(1, 1, '2,3', '0', 1, 3, '<p>iOS 6 SDK supports which of the following versions of Xcode?<br></p>', '3', NULL, '2014-10-30 05:50:08', '2014-10-30 05:50:08', 1, 0),
(2, 1, '2,3', '0', 1, 3, '<p>Which of the following is NOT a GKChallengeEventHandlerDelegate method?<br></p>', '2', NULL, '2014-10-30 05:50:55', '2014-10-30 05:50:55', 1, 0),
(3, 1, '2,3', '0', 1, 3, '<p>In iOS, which of the following methods are used to change the orientation of a device?<br><br></p>', '2', NULL, '2014-10-30 05:51:58', '2014-10-30 05:51:58', 1, 0),
(4, 1, '2,3', '0', 1, 3, '<p>What property in info.plist handles the association of file types in an iPhone application?<br></p>', '1', NULL, '2014-10-30 05:54:23', '2014-10-30 05:54:23', 1, 0),
(5, 2, '2,3', '0', 1, 4, '<p>Which of the following properties is preferred for setting formatted text in the iOS 6 UILabel class?<br></p>', '4', NULL, '2014-10-30 05:55:24', '2014-10-30 05:55:24', 1, 0),
(6, 2, '2,3', '0', 1, 3, '<p>Which of the following classes is used to detect iPhone location information in iOS 6?<br></p>', '3', NULL, '2014-10-30 05:56:53', '2014-10-30 05:56:53', 1, 0),
(7, 2, '2,3', '0', 1, 3, '<p>Which of the following methods of the PassKit framework is used to check if the pass library is available?<br></p>', '3', NULL, '2014-10-30 05:58:00', '2014-10-30 05:58:00', 1, 0),
(8, 2, '2,3', '0', 1, 3, 'Which class is used to handle the downloadable content associated with a product purchased from the App Store?<p><br></p>', '1', NULL, '2014-10-30 05:59:25', '2014-10-30 05:59:25', 1, 0),
(9, 3, '2,3', '0', 1, 3, '<p>In iOS, which of the following methods are used to change the orientation of a device?<br></p>', '3', NULL, '2014-10-30 06:00:23', '2014-10-30 06:00:23', 1, 0),
(10, 3, '2,3', '0', 1, 4, '<p>Which of the following is NOT a root class in the context of Objective-C?<br></p>', '3', NULL, '2014-10-30 06:01:53', '2014-10-30 06:01:53', 1, 0),
(11, 3, '2', '0', 1, 3, '<p>Which of the following features have been added to iOS 6?<br></p>', '1', NULL, '2014-10-30 06:04:03', '2014-10-30 06:04:03', 1, 0),
(12, 2, '2', '0', 2, 4, '<p>Which framework can be used to call SOAP web services in iOS applications?<br></p>', '1,2,3', NULL, '2014-10-30 06:05:41', '2014-10-30 06:05:41', 1, 0),
(13, 1, '2', '0', 1, 3, '<p>Is it possible to deploy beta build on any device?<br></p>', '1', NULL, '2014-10-30 06:06:33', '2014-10-30 06:06:33', 1, 0),
(14, 1, '2', '0', 1, 2, '<p>Which of the following functions is used to get the CTTextAlignment for the matching NSTextAlignment?<br></p>', '1', NULL, '2014-10-30 06:11:03', '2014-10-30 06:11:03', 1, 0),
(15, 1, '2', '0', 1, 3, '<p>Which of the following architectures are supported by Xcode 4.5?<br></p>', '1', NULL, '2014-10-30 06:12:19', '2014-10-30 06:12:19', 1, 0),
(16, 3, '2', '0', 1, 4, '<p>Which of the following is not an Open Source Framework/Library?<br></p>', '2', NULL, '2014-10-30 06:14:18', '2014-10-30 06:14:18', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_question_category`
--

CREATE TABLE IF NOT EXISTS `tbl_question_category` (
  `question_category_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_title` varchar(75) DEFAULT NULL,
  `parent_category_id` int(11) DEFAULT NULL COMMENT 'category id will be same for parent id',
  `test_duration` int(11) DEFAULT '0' COMMENT 'default time of test ',
  `total_marks` int(11) DEFAULT NULL,
  `no_of_questions` tinyint(4) DEFAULT NULL,
  `amount` int(11) NOT NULL,
  `difficulty_levels` varchar(100) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `is_deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`question_category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `tbl_question_category`
--

INSERT INTO `tbl_question_category` (`question_category_id`, `category_title`, `parent_category_id`, `test_duration`, `total_marks`, `no_of_questions`, `amount`, `difficulty_levels`, `created_date`, `updated_date`, `is_active`, `is_deleted`) VALUES
(1, 'Browse Tests', NULL, 0, NULL, NULL, 0, NULL, '2014-10-30 05:39:00', '2014-10-30 05:39:00', 1, 0),
(2, 'All Questions', 1, 0, NULL, NULL, 0, NULL, '2014-11-06 13:28:33', '2014-11-06 13:28:33', 1, 0),
(3, 'IOS-Test', 1, 5, 12, 6, 0, '{"1":"2","2":"2","3":"2"}', '2014-11-11 18:08:16', '2014-11-11 18:08:16', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_question_import_error`
--

CREATE TABLE IF NOT EXISTS `tbl_question_import_error` (
  `import_error_id` int(11) NOT NULL AUTO_INCREMENT,
  `question_category` text NOT NULL,
  `question_level` varchar(250) NOT NULL,
  `question_type` varchar(100) NOT NULL,
  `question` longtext NOT NULL,
  `option1` text NOT NULL,
  `option2` text NOT NULL,
  `option3` text NOT NULL,
  `option4` text NOT NULL,
  `option5` text NOT NULL,
  `answers` text NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_date` datetime NOT NULL,
  `updated_date` datetime NOT NULL,
  `fk_admin_id` int(11) NOT NULL,
  `error_string` longtext NOT NULL,
  PRIMARY KEY (`import_error_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_test_user`
--

CREATE TABLE IF NOT EXISTS `tbl_test_user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(45) DEFAULT NULL,
  `role` varchar(45) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL COMMENT 'We will have only two type of user\nAdmin\nEnd User',
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `is_deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `tbl_test_user`
--

INSERT INTO `tbl_test_user` (`user_id`, `full_name`, `role`, `email`, `password`, `created_date`, `updated_date`, `is_active`, `is_deleted`) VALUES
(1, 'Admin Demo', 'admin', 'admindemo@onlinetest.com', '$2a$08$A0r/PB1qWotnbHZwrw1Lqeu/lLvyLteyLRjGknNQu.FOaybXE/Xgu', '2014-10-29 00:00:00', '2014-11-03 06:10:58', 1, 0),
(2, 'User Demo', 'user', 'userdemo@onlinetest.com', '$2a$08$DC7xMgxcx3oYuYpfEzJF1OWRbvBWQj/iziYM5HnODFF2EFkTDMFZa', '2014-10-29 22:32:56', '2014-11-04 13:07:26', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user_activity_log`
--

CREATE TABLE IF NOT EXISTS `tbl_user_activity_log` (
  `activity_id` int(11) NOT NULL AUTO_INCREMENT,
  `activity_type` varchar(45) DEFAULT NULL,
  `activity_description` text,
  `fk_user_id` int(11) NOT NULL,
  `table_name` varchar(45) DEFAULT NULL,
  `table_id` int(11) DEFAULT NULL,
  `ipaddress` varchar(20) NOT NULL,
  `lat` double NOT NULL,
  `lng` double NOT NULL,
  `browser` varchar(50) NOT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  PRIMARY KEY (`activity_id`),
  KEY `fk_user_activity_log_tbl_user1` (`fk_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user_test`
--

CREATE TABLE IF NOT EXISTS `tbl_user_test` (
  `user_test_id` int(11) NOT NULL AUTO_INCREMENT,
  `fk_user_id` int(11) DEFAULT NULL,
  `fk_question_category_id` int(11) NOT NULL,
  `test_title` varchar(75) NOT NULL,
  `test_difficulty_levels` text NOT NULL,
  `no_of_question` tinyint(4) DEFAULT NULL,
  `test_duration` int(11) DEFAULT NULL COMMENT 'enter test duration in minutes',
  `max_marks` float NOT NULL DEFAULT '0',
  `questions_json` text NOT NULL,
  `total_questions_json` text NOT NULL,
  `questions_attempted` tinyint(4) DEFAULT '0',
  `time_spent` int(11) DEFAULT NULL COMMENT 'test time will come in minutes format\n\nTime spent by user',
  `test_status` tinyint(2) DEFAULT NULL COMMENT '0 status for in progress\n1  status for done\n2 status for hold\n3 stauts for suspend',
  `marks_obtained` float DEFAULT '0',
  `percent_get` float DEFAULT '0',
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `is_deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`user_test_id`),
  KEY `fk_tbl_user_test_tbl_question_category1_idx` (`fk_question_category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user_test_answer`
--

CREATE TABLE IF NOT EXISTS `tbl_user_test_answer` (
  `user_test_answer_id` int(11) NOT NULL AUTO_INCREMENT,
  `fk_questions_id` int(11) NOT NULL,
  `fk_user_test_id` int(11) NOT NULL,
  `selected_answers` varchar(45) DEFAULT NULL COMMENT 'ans can me more than one becaus in case of multioption question user can give more than one answer',
  `marks_obtained` float DEFAULT NULL,
  `is_skip` tinyint(1) NOT NULL DEFAULT '0',
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `is_deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`user_test_answer_id`),
  KEY `fk_tbl_user_test_answer_tbl_questions1_idx` (`fk_questions_id`),
  KEY `fk_tbl_user_test_answer_tbl_user_test1_idx` (`fk_user_test_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_answer`
--
ALTER TABLE `tbl_answer`
  ADD CONSTRAINT `fk_tbl_answer_tbl_questions` FOREIGN KEY (`fk_questions_id`) REFERENCES `tbl_questions` (`questions_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tbl_user_test`
--
ALTER TABLE `tbl_user_test`
  ADD CONSTRAINT `fk_tbl_user_test_tbl_question_category1` FOREIGN KEY (`fk_question_category_id`) REFERENCES `tbl_question_category` (`question_category_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tbl_user_test_answer`
--
ALTER TABLE `tbl_user_test_answer`
  ADD CONSTRAINT `fk_tbl_user_test_answer_tbl_questions1` FOREIGN KEY (`fk_questions_id`) REFERENCES `tbl_questions` (`questions_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_tbl_user_test_answer_tbl_user_test1` FOREIGN KEY (`fk_user_test_id`) REFERENCES `tbl_user_test` (`user_test_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
