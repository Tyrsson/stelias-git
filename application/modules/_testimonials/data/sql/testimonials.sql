-- phpMyAdmin SQL Dump
-- version 3.3.9.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 25, 2012 at 09:08 PM
-- Server version: 5.5.9
-- PHP Version: 5.3.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `cabin`
--

-- --------------------------------------------------------

--
-- Table structure for table `guestBook`
--

DROP TABLE IF EXISTS `testimonials`;
CREATE TABLE IF NOT EXISTS `testimonials` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guestName` varchar(255) CHARACTER SET utf8 NOT NULL,
  `content` longtext CHARACTER SET utf8 NOT NULL,
  `rating` int(1) NOT NULL,
  `isApproved` tinyint(1) NOT NULL DEFAULT '0',
  `createdDate` int(11) NOT NULL,
  `updatedDate` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `testimonials`
--

INSERT INTO `testimonials` VALUES(1, 'Joey smith', 'This is edited text, but lets add some more text to see if the second edit and populate work. Testing timestamp function', 3, 1, 1330143541, 1330492842);
INSERT INTO `testimonials` VALUES(2, 'Testing Two', 'This is a entry to test ratings', 2, 1, 1330398531, 1330398958);
INSERT INTO `testimonials` VALUES(3, 'testing tester', 'Just to add a few entries so to have more than one page', 3, 1, 1330494539, 0);
INSERT INTO `testimonials` VALUES(4, 'adding more', 'just adding more entries blah yada just another entry', 4, 1, 1330494571, 0);
INSERT INTO `testimonials` VALUES(5, 'Melissa', 'Just testing another entry', 1, 1, 1330494592, 0);
INSERT INTO `testimonials` VALUES(6, 'One more', 'here we go with one more entry blah etc', 1, 1, 1330494618, 0);
INSERT INTO `testimonials` VALUES(7, 'Testing approval', 'This should show the link in the approval list', 4, 1, 1330497789, 1330497962);
