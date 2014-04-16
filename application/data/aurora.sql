-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 30, 2012 at 11:34 PM
-- Server version: 5.5.25
-- PHP Version: 5.2.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `aurora`
--

-- --------------------------------------------------------

--
-- Table structure for table `appsettings`
--

DROP TABLE IF EXISTS `appsettings`;
CREATE TABLE `appsettings` (
  `variable` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `settingType` tinytext NOT NULL,
  KEY `variable` (`variable`,`value`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `appsettings`
--

INSERT INTO `appsettings` (`variable`, `value`, `settingType`) VALUES
('allowTags', '<h1>,<h2>,<h3>,<h4>,<h5>,<h6>,<hr>', 'Textarea'),
('enableCaptcha', '0', 'Checkbox'),
('recaptchaPrivateKey', '6Lewcs0SAAAAADCeIUYYuiHBWemBpQ5FkuI_cK7H', 'Textarea'),
('recaptchaPublicKey', '6Lewcs0SAAAAAGfBkJsG1mxf-yGFUjq9JgglSwRL', 'Textarea'),
('seoKeyWords', 'Dirextion Inc, Dxcore, Php, Development, MySQL', 'Textarea'),
('siteName', 'Aurora CMS', 'Text'),
('webMasterEmail', 'noreply@dirextion.com', 'Text'),
('remoteLicenseKey', 'SingleDomain18446aad51de8a3a596b594c3fcca5d137cf8c34', 'Textarea'),
('siteEmail', 'jsmith@dirextion.com', 'Text'),
('enableMobileSupport', '1', 'CheckBox'),
('seoDescription', 'Custom CMS', 'Textarea'),
('facebookAppId', '431812843521907', 'Text'),
('facebookAppSecret', 'd86702c59bd48f3a76bc57d923cd237e', 'Text'),
('enableFbPageLink', '1', 'CheckBox'),
('enableFbOpenGraph', '0', 'Checkbox'),
('sessionLength', '86400', 'Text'),
('showOnlineList', '1', 'Checkbox'),
('enableLogging', '1', 'Checkbox'),
('enableHomeTab', '1', 'Checkbox'),
('enableLinkLogo', '1', 'Checkbox'),
('enableDebugMode', '1', 'Checkbox'),
('enableSearch', '1', 'Checkbox');

-- --------------------------------------------------------

--
-- Table structure for table `calendar`
--

DROP TABLE IF EXISTS `calendar`;
CREATE TABLE `calendar` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `monthRangeMin` int(11) NOT NULL,
  `monthRangeMax` int(11) NOT NULL,
  `type` enum('local','google') NOT NULL DEFAULT 'local',
  `googleUserName` varchar(255) DEFAULT NULL,
  `googlePassWord` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `calendar`
--

INSERT INTO `calendar` (`id`, `name`, `monthRangeMin`, `monthRangeMax`, `type`, `googleUserName`, `googlePassWord`) VALUES
(1, 'default', -1, 12, 'local', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `calendarevents`
--

DROP TABLE IF EXISTS `calendarevents`;
CREATE TABLE `calendarevents` (
  `eventId` int(11) NOT NULL AUTO_INCREMENT,
  `calendarId` int(11) NOT NULL,
  `year` int(4) NOT NULL,
  `month` int(2) NOT NULL,
  `day` int(2) NOT NULL,
  `eventName` varchar(255) NOT NULL,
  `linkOne` varchar(255) NOT NULL,
  `linkTwo` varchar(255) NOT NULL,
  `eventContent` longtext NOT NULL,
  PRIMARY KEY (`eventId`),
  KEY `eventName` (`eventName`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `calendarevents`
--

INSERT INTO `calendarevents` (`eventId`, `calendarId`, `year`, `month`, `day`, `eventName`, `linkOne`, `linkTwo`, `eventContent`) VALUES
(1, 1, 2012, 10, 15, 'Test Event', 'http://linkone.com', 'http://linktwo', 'This is some test content for an event on Oct 15th 2012.'),
(2, 1, 2012, 10, 14, 'Test Event Two', '', '', 'event two content'),
(8, 0, 2012, 10, 17, 'The seventeenth', 'sdvwge', 'rbgwretg', 'event on the 17th'),
(9, 1, 2012, 10, 3, 'asfqerf', 'dfverv', 'adfvwerv', 'dafvwefrvw'),
(10, 1, 2012, 10, 18, 'Bday', 'sdvq', 'avqr', 'cal id = 1, eventId = 10, day = 18th, month = 10, year = 2012'),
(11, 1, 2012, 10, 29, 'New Event', 'link One', 'Link Two', 'This is some content etc');

-- --------------------------------------------------------

--
-- Table structure for table `calendarweeks`
--

DROP TABLE IF EXISTS `calendarweeks`;
CREATE TABLE `calendarweeks` (
  `weekId` int(11) NOT NULL AUTO_INCREMENT,
  `calendarId` int(11) DEFAULT NULL,
  `headingColor` varchar(7) DEFAULT NULL,
  `weekHeading` mediumtext,
  `headingLink` varchar(255) DEFAULT NULL,
  `monthId` int(11) DEFAULT NULL,
  `monthName` varchar(45) DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  PRIMARY KEY (`weekId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `installedmodules`
--

DROP TABLE IF EXISTS `installedmodules`;
CREATE TABLE `installedmodules` (
  `moduleId` int(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `nameSpace` varchar(255) NOT NULL,
  `publicName` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`moduleId`),
  UNIQUE KEY `name` (`name`,`nameSpace`),
  KEY `publicName` (`publicName`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `installedmodules`
--

INSERT INTO `installedmodules` (`moduleId`, `name`, `nameSpace`, `publicName`) VALUES
(1, 'admin', 'Admin_', 'Admin Area'),
(2, 'user', 'User_', NULL),
(3, 'pages', 'Pages_', NULL),
(4, 'media', 'Media_', 'Gallery');

-- --------------------------------------------------------

--
-- Table structure for table `lang`
--

DROP TABLE IF EXISTS `lang`;
CREATE TABLE `lang` (
  `langKey` varchar(255) NOT NULL,
  `langText` mediumtext NOT NULL,
  `locale` varchar(5) NOT NULL,
  PRIMARY KEY (`langKey`),
  KEY `locale` (`locale`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `lang`
--

INSERT INTO `lang` (`langKey`, `langText`, `locale`) VALUES
('welcomeGuest', 'Welcome back guest. We can add some more text here blah blah.', 'en_US');

-- --------------------------------------------------------

--
-- Table structure for table `log`
--

DROP TABLE IF EXISTS `log`;
CREATE TABLE `log` (
  `logId` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL DEFAULT '0',
  `fileId` int(11) NOT NULL DEFAULT '0',
  `userName` varchar(255) DEFAULT NULL,
  `timeStamp` varchar(255) NOT NULL,
  `priorityName` varchar(20) NOT NULL,
  `priority` int(1) NOT NULL,
  `message` mediumtext NOT NULL,
  PRIMARY KEY (`logId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mediaalbums`
--

DROP TABLE IF EXISTS `mediaalbums`;
CREATE TABLE `mediaalbums` (
  `albumId` int(11) NOT NULL AUTO_INCREMENT,
  `parentId` int(11) NOT NULL DEFAULT '0',
  `albumName` varchar(255) NOT NULL,
  `userId` int(11) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'guest',
  `passWord` varchar(40) DEFAULT NULL,
  `albumDesc` mediumtext,
  `serverPath` varchar(255) NOT NULL,
  `timestamp` varchar(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`albumId`),
  KEY `albumName` (`albumName`,`userId`),
  KEY `role` (`role`),
  KEY `parentId` (`parentId`),
  KEY `serverPath` (`serverPath`),
  KEY `timestamp` (`timestamp`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `mediaalbums`
--

INSERT INTO `mediaalbums` (`albumId`, `parentId`, `albumName`, `userId`, `role`, `passWord`, `albumDesc`, `serverPath`, `timestamp`) VALUES
(-3, 0, 'Slider', 1, 'guest', NULL, NULL, '', '0'),
(-2, 0, 'Media', 1, 'guest', NULL, 'This is the default Album for the Media module. This album can not be deleted as the system is dependent upon it for correct operation.', '', '0'),
(-1, 0, 'Pages', 1, 'guest', NULL, 'This is the default Album for the Pages module. This album can not be deleted as the system is dependent upon it for correct operation.', '', '0'),
(1, -2, 'Default', 1, 'guest', NULL, NULL, 'Default', '0'),
(2, -2, 'Sunrise', 1, 'guest', NULL, NULL, 'Sunrise', '0'),
(3, -2, 'TestAlbum', 1, 'guest', NULL, NULL, 'TestAlbum', '0');

-- --------------------------------------------------------

--
-- Table structure for table `mediafiles`
--

DROP TABLE IF EXISTS `mediafiles`;
CREATE TABLE `mediafiles` (
  `fileId` int(11) NOT NULL AUTO_INCREMENT,
  `albumId` int(11) DEFAULT NULL,
  `fileName` varchar(255) NOT NULL,
  `title` varchar(50) DEFAULT NULL,
  `alt` varchar(255) DEFAULT NULL,
  `description` mediumtext,
  `order` int(11) NOT NULL,
  `timestamp` varchar(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`fileId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=30 ;

--
-- Dumping data for table `mediafiles`
--

INSERT INTO `mediafiles` (`fileId`, `albumId`, `fileName`, `title`, `alt`, `description`, `order`, `timestamp`) VALUES
(1, -3, 'slider-default-one.png', NULL, NULL, '', 0, '1353951475'),
(2, -3, 'slider-default-three.png', NULL, NULL, '', 0, '1353951475'),
(3, -3, 'slider-default-two.png', NULL, NULL, '', 0, '1353951476'),
(8, 1, 'blue-wheat-grass.jpg', NULL, NULL, '', 0, '1355802796'),
(9, 1, 'brokencar.jpg', NULL, NULL, '', 0, '1355802799'),
(10, 1, 'car.jpg', NULL, NULL, '', 0, '1355802800'),
(11, 1, 'redtruck.jpg', NULL, NULL, '', 0, '1355802800'),
(12, 1, 'sun-grass-golden.jpg', NULL, NULL, '', 0, '1355802801'),
(18, 3, 'blue-wheat-grass.jpg', NULL, NULL, '', 0, '1355949688'),
(19, 3, 'brokencar.jpg', NULL, NULL, '', 0, '1355949691'),
(20, 3, 'car.jpg', NULL, NULL, '', 0, '1355949692'),
(21, 3, 'Open.png', NULL, NULL, '', 0, '1355949774'),
(22, 3, 'Pictures.png', NULL, NULL, '', 0, '1355949775'),
(23, 3, 'Public.png', NULL, NULL, '', 0, '1355949776'),
(24, 3, 'Sharepoint.png', NULL, NULL, '', 0, '1355949777'),
(25, 3, 'Sites.png', NULL, NULL, '', 0, '1355949778'),
(26, 3, 'Smart.png', NULL, NULL, '', 0, '1355949778'),
(27, 3, 'System.png', NULL, NULL, '', 0, '1355949779'),
(28, 3, 'User.png', NULL, NULL, '', 0, '1355949780'),
(29, 3, 'Utilities.png', NULL, NULL, '', 0, '1355949781');

-- --------------------------------------------------------

--
-- Table structure for table `modulesettings`
--

DROP TABLE IF EXISTS `modulesettings`;
CREATE TABLE `modulesettings` (
  `moduleName` varchar(255) NOT NULL,
  `variable` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `settingType` tinytext NOT NULL,
  PRIMARY KEY (`variable`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `modulesettings`
--

INSERT INTO `modulesettings` (`moduleName`, `variable`, `value`, `settingType`) VALUES
('testimonials', 'allowUserPostNew', '1', 'Checkbox'),
('media', 'mediaIsActive', '1', 'Checkbox'),
('home widget', 'numSubPagesToShow', '2', 'Text'),
('user', 'showEmail', '1', 'Checkbox'),
('media', 'showMostRecentFirst', '1', 'Checkbox'),
('media', 'showRecentByDate', '1', 'Checkbox'),
('media', 'showRecentCount', '4', 'Text'),
('media', 'showRecentInGallery', '1', 'Checkbox'),
('media', 'showRecentInHomeWidget', '1', 'Checkbox'),
('media', 'showRecentNumDays', '14', 'Text'),
('testimonials', 'testimonialsIsActive', '1', 'Checkbox');

-- --------------------------------------------------------

--
-- Table structure for table `pagecategories`
--

DROP TABLE IF EXISTS `pagecategories`;
CREATE TABLE `pagecategories` (
  `categoryId` int(11) NOT NULL AUTO_INCREMENT,
  `categoryName` varchar(50) NOT NULL,
  `parentId` int(11) NOT NULL DEFAULT '0',
  `visibility` enum('public','private') NOT NULL,
  PRIMARY KEY (`categoryId`),
  KEY `categoryName` (`categoryName`,`parentId`,`visibility`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `pagecomments`
--

DROP TABLE IF EXISTS `pagecomments`;
CREATE TABLE `pagecomments` (
  `commentId` int(11) NOT NULL AUTO_INCREMENT,
  `pageId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `createdDate` int(11) NOT NULL,
  `modifiedDate` int(11) NOT NULL,
  `visibility` enum('public','private') NOT NULL,
  `commentText` longtext NOT NULL,
  PRIMARY KEY (`commentId`),
  KEY `pageId` (`pageId`,`userId`,`createdDate`,`modifiedDate`,`visibility`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `pagefiles`
--

DROP TABLE IF EXISTS `pagefiles`;
CREATE TABLE `pagefiles` (
  `fileId` int(11) NOT NULL AUTO_INCREMENT,
  `fileName` varchar(255) NOT NULL,
  `pageId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `isMainImage` int(1) DEFAULT NULL,
  PRIMARY KEY (`fileId`),
  KEY `pageId` (`pageId`,`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `pagelookup`
--

DROP TABLE IF EXISTS `pagelookup`;
CREATE TABLE `pagelookup` (
  `pageId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `parentId` int(11) DEFAULT NULL,
  `categoryId` int(11) DEFAULT NULL,
  PRIMARY KEY (`pageId`),
  KEY `parentId` (`parentId`,`categoryId`),
  KEY `userId` (`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pagelookup`
--

INSERT INTO `pagelookup` (`pageId`, `userId`, `parentId`, `categoryId`) VALUES
(1, 1, NULL, NULL),
(15, 1, 1, NULL),
(19, 4, NULL, NULL),
(26, 4, NULL, NULL),
(29, 1, 1, NULL),
(34, 1, NULL, NULL),
(36, 1, 35, NULL),
(37, 1, NULL, NULL),
(38, 1, NULL, NULL),
(39, 1, NULL, NULL),
(40, 1, NULL, NULL),
(42, 1, NULL, NULL),
(43, 1, NULL, NULL),
(44, 1, NULL, NULL),
(45, 1, NULL, NULL),
(46, 1, NULL, NULL),
(47, 1, NULL, NULL),
(57, 1, NULL, NULL),
(62, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pagemenulinks`
--

DROP TABLE IF EXISTS `pagemenulinks`;
CREATE TABLE `pagemenulinks` (
  `menuId` int(11) NOT NULL,
  `linkText` varchar(50) NOT NULL,
  `uri` varchar(255) NOT NULL,
  `role` varchar(100) NOT NULL,
  `resource` varchar(255) NOT NULL,
  `order` int(11) DEFAULT NULL,
  `visibility` enum('public','private') NOT NULL,
  PRIMARY KEY (`menuId`),
  KEY `linkText` (`linkText`,`uri`,`role`,`visibility`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pagemenus`
--

DROP TABLE IF EXISTS `pagemenus`;
CREATE TABLE `pagemenus` (
  `menuId` int(11) NOT NULL AUTO_INCREMENT,
  `pageId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `visibility` enum('public','private') NOT NULL,
  PRIMARY KEY (`menuId`),
  KEY `pageId` (`pageId`,`userId`,`visibility`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
CREATE TABLE `pages` (
  `pageId` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL DEFAULT '0',
  `parentId` int(11) NOT NULL DEFAULT '0',
  `role` varchar(100) NOT NULL DEFAULT 'user',
  `pageName` varchar(50) NOT NULL,
  `pageUrl` varchar(255) NOT NULL COMMENT 'page is queried by this value',
  `visibility` enum('public','private') NOT NULL DEFAULT 'public',
  `createdDate` int(11) DEFAULT NULL,
  `publishDate` int(11) DEFAULT NULL,
  `modifiedDate` int(11) DEFAULT NULL,
  `archivedDate` int(11) DEFAULT NULL,
  `pageOrder` int(11) DEFAULT NULL,
  `pageType` varchar(255) NOT NULL DEFAULT 'page',
  `pageText` longtext NOT NULL,
  `keyWords` varchar(255) NOT NULL,
  `showSlider` tinyint(1) NOT NULL DEFAULT '0',
  `showInHomeWidget` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`pageId`),
  UNIQUE KEY `pageName` (`pageName`),
  KEY `userId` (`visibility`,`createdDate`,`modifiedDate`,`archivedDate`,`pageOrder`,`pageType`),
  KEY `parentId` (`parentId`),
  KEY `role` (`role`),
  KEY `publishDate` (`publishDate`),
  KEY `userId_2` (`userId`),
  KEY `keyWords` (`keyWords`),
  KEY `pageUrl` (`pageUrl`),
  KEY `showInHomeWidget` (`showInHomeWidget`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=77 ;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`pageId`, `userId`, `parentId`, `role`, `pageName`, `pageUrl`, `visibility`, `createdDate`, `publishDate`, `modifiedDate`, `archivedDate`, `pageOrder`, `pageType`, `pageText`, `keyWords`, `showSlider`, `showInHomeWidget`) VALUES
(1, 1, 0, 'guest', 'home', 'home', 'public', 2012, 0, 1355948850, 0, 1, 'home', '<p>\r\n  This is test text for the first testing page. Test Edit.</p>\r\n<p>\r\n Item One</p>\r\n<p>\r\n Item Two</p>\r\n<p>\r\n Item Three</p>\r\n', ' Testing, Extra,Keywords', 0, 0),
(15, 1, 1, 'guest', 'services', 'services', 'public', 2012, NULL, NULL, NULL, 2, 'page', '<p>\r\n This is the services page. This is where the services content will go.</p>\r\n<p>\r\n   Blah</p>\r\n<p>\r\n Blah</p>\r\n<p>\r\n Blah</p>\r\n<p>\r\n blah again</p>\r\n<p>\r\n   edit</p>\r\n<p>\r\n this is a edit to test saving.</p>\r\n', '', 0, 1),
(26, 1, 23, 'guest', 'food', 'food', 'public', 2012, NULL, NULL, NULL, NULL, 'page', '<p>\r\n hereisanexample</p>\r\n', '', 0, 0),
(29, 1, 1, 'guest', 'testing', 'testing', 'public', 2012, NULL, NULL, NULL, NULL, 'page', '<p>\r\n    This is some test text, also need to test why this does not render the editor when the useragent is set to iOS in my firefox addon.</p>\r\n', '', 0, 1),
(36, 1, 35, 'guest', 'child test one', 'child-test-one', 'public', 1353650400, NULL, NULL, NULL, 10, 'page', '<p>\r\n This is a child test for showing this page in a widget for the Parent Page</p>\r\n', '', 0, 0),
(43, 1, 0, 'guest', 'Gallery', 'gallery', 'public', 1355803573, NULL, NULL, NULL, 6, 'media', '<p>\r\n    Testing content for the media page</p>\r\n', '', 0, 0),
(72, 1, 0, 'guest', 'Blue', 'blue', 'public', 1355938569, NULL, NULL, NULL, 7, 'page', '<p>\r\n   fbsrtbhsrthb</p>\r\n', '', 0, 0),
(73, 1, 0, 'guest', 'Testing Pages', 'testing-pages', 'public', 1356113721, NULL, NULL, NULL, 8, 'page', '<p>\r\n dijgnsdifugnwortu gaigruwoqierugfwoauyrbg</p>\r\n', '', 0, 0),
(74, 1, 72, 'guest', 'CreateForm', 'createform', 'public', 1356622203, NULL, NULL, NULL, 9, 'page', '<p>\r\n	safdvregwq4er eargfq4w3rtq43</p>\r\n', '', 0, 0),
(75, 1, 72, 'admin', 'Testing parent', 'testing-parent', 'public', 1356622890, NULL, NULL, NULL, 10, 'contact', '<p>\r\n	sgrteg rthgwthw5ty</p>\r\n', '', 0, 0),
(76, 1, 0, 'guest', 'blahyada', 'blahyada', 'public', 1356623097, NULL, 1356623330, NULL, 11, 'page', '<p>\r\n	asgfq3rfgq fgqerfq34r fqerfq34</p>\r\n', '', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `pagetypes`
--

DROP TABLE IF EXISTS `pagetypes`;
CREATE TABLE `pagetypes` (
  `pageTypeId` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL,
  PRIMARY KEY (`pageTypeId`),
  UNIQUE KEY `type_2` (`type`),
  KEY `type` (`type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `pagetypes`
--

INSERT INTO `pagetypes` (`pageTypeId`, `type`) VALUES
(2, 'contact'),
(1, 'home'),
(3, 'media'),
(4, 'page');

-- --------------------------------------------------------

--
-- Table structure for table `paypalsettings`
--

DROP TABLE IF EXISTS `paypalsettings`;
CREATE TABLE `paypalsettings` (
  `enabled` tinyint(1) NOT NULL DEFAULT '0',
  `apiUserName` varchar(255) NOT NULL,
  `apiPassWord` varchar(255) NOT NULL,
  `apiSignature` varchar(255) NOT NULL,
  `returnUrl` varchar(255) NOT NULL,
  `cancelUrl` varchar(255) CHARACTER SET utf8 COLLATE utf8_estonian_ci NOT NULL,
  PRIMARY KEY (`enabled`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `roleId` int(11) NOT NULL AUTO_INCREMENT,
  `role` varchar(255) NOT NULL,
  `inheritsFrom` varchar(255) NOT NULL,
  `publicName` varchar(100) NOT NULL,
  PRIMARY KEY (`roleId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`roleId`, `role`, `inheritsFrom`, `publicName`) VALUES
(1, 'admin', 'jradmin', ''),
(2, 'jradmin', 'moderator', ''),
(3, 'moderator', 'user', ''),
(4, 'user', 'guest', ''),
(5, 'guest', 'none', '');

-- --------------------------------------------------------

--
-- Table structure for table `session`
--

DROP TABLE IF EXISTS `session`;
CREATE TABLE `session` (
  `id` char(32) NOT NULL DEFAULT '',
  `modified` int(11) DEFAULT NULL,
  `lifetime` int(11) DEFAULT NULL,
  `data` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `session`
--

INSERT INTO `session` (`id`, `modified`, `lifetime`, `data`) VALUES
('56b5884ef3bfa8b937996a424df0c52a', 1356543994, 86400, '.Mozilla/5.0 (Macintosh; Intel Mac OS X 10_6_8) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.101 Safari/537.11|a:1:{s:7:"storage";s:3171:"a:6:{s:12:"browser_type";s:7:"desktop";s:6:"config";a:3:{s:23:"identification_sequence";s:14:"mobile,desktop";s:7:"storage";a:1:{s:7:"adapter";s:7:"Session";}s:6:"mobile";a:1:{s:8:"features";a:1:{s:9:"classname";s:45:"Zend_Http_UserAgent_Features_Adapter_Browscap";}}}s:12:"device_class";s:27:"Zend_Http_UserAgent_Desktop";s:6:"device";s:2587:"a:6:{s:10:"_aFeatures";a:28:{s:21:"browser_compatibility";s:6:"Safari";s:14:"browser_engine";s:11:"AppleWebKit";s:12:"browser_name";s:6:"Chrome";s:13:"browser_token";s:21:"Intel Mac OS X 10_6_8";s:15:"browser_version";s:13:"23.0.1271.101";s:7:"comment";a:2:{s:4:"full";s:32:"Macintosh; Intel Mac OS X 10_6_8";s:6:"detail";a:2:{i:0;s:9:"Macintosh";i:1;s:22:" Intel Mac OS X 10_6_8";}}s:18:"compatibility_flag";s:9:"Macintosh";s:15:"device_os_token";s:9:"Macintosh";s:6:"others";a:2:{s:4:"full";s:73:"AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.101 Safari/537.11";s:6:"detail";a:3:{i:0;a:3:{i:0;s:38:"AppleWebKit/537.11 (KHTML, like Gecko)";i:1;s:11:"AppleWebKit";i:2;s:6:"537.11";}i:1;a:3:{i:0;s:20:"Chrome/23.0.1271.101";i:1;s:6:"Chrome";i:2;s:13:"23.0.1271.101";}i:2;a:3:{i:0;s:13:"Safari/537.11";i:1;s:6:"Safari";i:2;s:6:"537.11";}}}s:12:"product_name";s:7:"Mozilla";s:15:"product_version";s:3:"5.0";s:10:"user_agent";s:11:"Mozilla/5.0";s:18:"is_wireless_device";b:0;s:9:"is_mobile";b:0;s:10:"is_desktop";b:1;s:9:"is_tablet";b:0;s:6:"is_bot";b:0;s:8:"is_email";b:0;s:7:"is_text";b:0;s:25:"device_claims_web_support";b:0;s:9:"client_ip";s:9:"127.0.0.1";s:11:"php_version";s:6:"5.2.17";s:9:"server_os";s:6:"apache";s:17:"server_os_version";i:1;s:18:"server_http_accept";s:63:"text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8";s:27:"server_http_accept_language";s:14:"en-US,en;q=0.8";s:9:"server_ip";s:9:"127.0.0.1";s:11:"server_name";s:18:"dev.webinertia.net";}s:7:"_aGroup";a:2:{s:12:"product_info";a:21:{i:0;s:21:"browser_compatibility";i:1;s:14:"browser_engine";i:2;s:12:"browser_name";i:3;s:13:"browser_token";i:4;s:15:"browser_version";i:5;s:7:"comment";i:6;s:18:"compatibility_flag";i:7;s:15:"device_os_token";i:8;s:6:"others";i:9;s:12:"product_name";i:10;s:15:"product_version";i:11;s:10:"user_agent";i:12;s:18:"is_wireless_device";i:13;s:9:"is_mobile";i:14;s:10:"is_desktop";i:15;s:9:"is_tablet";i:16;s:6:"is_bot";i:17;s:8:"is_email";i:18;s:7:"is_text";i:19;s:25:"device_claims_web_support";i:20;s:9:"client_ip";}s:11:"server_info";a:7:{i:0;s:11:"php_version";i:1;s:9:"server_os";i:2;s:17:"server_os_version";i:3;s:18:"server_http_accept";i:4;s:27:"server_http_accept_language";i:5;s:9:"server_ip";i:6;s:11:"server_name";}}s:8:"_browser";s:6:"Chrome";s:15:"_browserVersion";s:13:"23.0.1271.101";s:10:"_userAgent";s:120:"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_6_8) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.101 Safari/537.11";s:7:"_images";a:6:{i:0;s:4:"jpeg";i:1;s:3:"gif";i:2;s:3:"png";i:3;s:5:"pjpeg";i:4;s:5:"x-png";i:5;s:3:"bmp";}}";s:10:"user_agent";s:120:"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_6_8) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.101 Safari/537.11";s:11:"http_accept";s:63:"text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8";}";}'),
('856acc3f0e949b7141c91d41b44e3d4b', 1356623343, 86400, '.Mozilla/5.0 (Macintosh; Intel Mac OS X 10.6; rv:17.0) Gecko/20100101 Firefox/17.0 FirePHP/0.7.1|a:1:{s:7:"storage";s:3038:"a:6:{s:12:"browser_type";s:7:"desktop";s:6:"config";a:3:{s:23:"identification_sequence";s:14:"mobile,desktop";s:7:"storage";a:1:{s:7:"adapter";s:7:"Session";}s:6:"mobile";a:1:{s:8:"features";a:1:{s:9:"classname";s:45:"Zend_Http_UserAgent_Features_Adapter_Browscap";}}}s:12:"device_class";s:27:"Zend_Http_UserAgent_Desktop";s:6:"device";s:2480:"a:6:{s:10:"_aFeatures";a:28:{s:21:"browser_compatibility";s:7:"Firefox";s:14:"browser_engine";s:5:"Gecko";s:12:"browser_name";s:7:"FirePHP";s:13:"browser_token";s:19:"Intel Mac OS X 10.6";s:15:"browser_version";s:5:"0.7.1";s:7:"comment";a:2:{s:4:"full";s:39:"Macintosh; Intel Mac OS X 10.6; rv:17.0";s:6:"detail";a:3:{i:0;s:9:"Macintosh";i:1;s:20:" Intel Mac OS X 10.6";i:2;s:8:" rv:17.0";}}s:18:"compatibility_flag";s:9:"Macintosh";s:15:"device_os_token";s:7:"rv:17.0";s:6:"others";a:2:{s:4:"full";s:41:"Gecko/20100101 Firefox/17.0 FirePHP/0.7.1";s:6:"detail";a:3:{i:0;a:3:{i:0;s:14:"Gecko/20100101";i:1;s:5:"Gecko";i:2;s:8:"20100101";}i:1;a:3:{i:0;s:12:"Firefox/17.0";i:1;s:7:"Firefox";i:2;s:4:"17.0";}i:2;a:3:{i:0;s:13:"FirePHP/0.7.1";i:1;s:7:"FirePHP";i:2;s:5:"0.7.1";}}}s:12:"product_name";s:7:"Mozilla";s:15:"product_version";s:3:"5.0";s:10:"user_agent";s:11:"Mozilla/5.0";s:18:"is_wireless_device";b:0;s:9:"is_mobile";b:0;s:10:"is_desktop";b:1;s:9:"is_tablet";b:0;s:6:"is_bot";b:0;s:8:"is_email";b:0;s:7:"is_text";b:0;s:25:"device_claims_web_support";b:0;s:9:"client_ip";s:9:"127.0.0.1";s:11:"php_version";s:5:"5.4.4";s:9:"server_os";s:6:"apache";s:17:"server_os_version";i:1;s:18:"server_http_accept";s:63:"text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8";s:27:"server_http_accept_language";s:14:"en-US,en;q=0.5";s:9:"server_ip";s:9:"127.0.0.1";s:11:"server_name";s:18:"dev.webinertia.net";}s:7:"_aGroup";a:2:{s:12:"product_info";a:21:{i:0;s:21:"browser_compatibility";i:1;s:14:"browser_engine";i:2;s:12:"browser_name";i:3;s:13:"browser_token";i:4;s:15:"browser_version";i:5;s:7:"comment";i:6;s:18:"compatibility_flag";i:7;s:15:"device_os_token";i:8;s:6:"others";i:9;s:12:"product_name";i:10;s:15:"product_version";i:11;s:10:"user_agent";i:12;s:18:"is_wireless_device";i:13;s:9:"is_mobile";i:14;s:10:"is_desktop";i:15;s:9:"is_tablet";i:16;s:6:"is_bot";i:17;s:8:"is_email";i:18;s:7:"is_text";i:19;s:25:"device_claims_web_support";i:20;s:9:"client_ip";}s:11:"server_info";a:7:{i:0;s:11:"php_version";i:1;s:9:"server_os";i:2;s:17:"server_os_version";i:3;s:18:"server_http_accept";i:4;s:27:"server_http_accept_language";i:5;s:9:"server_ip";i:6;s:11:"server_name";}}s:8:"_browser";s:7:"FirePHP";s:15:"_browserVersion";s:5:"0.7.1";s:10:"_userAgent";s:95:"Mozilla/5.0 (Macintosh; Intel Mac OS X 10.6; rv:17.0) Gecko/20100101 Firefox/17.0 FirePHP/0.7.1";s:7:"_images";a:6:{i:0;s:4:"jpeg";i:1;s:3:"gif";i:2;s:3:"png";i:3;s:5:"pjpeg";i:4;s:5:"x-png";i:5;s:3:"bmp";}}";s:10:"user_agent";s:95:"Mozilla/5.0 (Macintosh; Intel Mac OS X 10.6; rv:17.0) Gecko/20100101 Firefox/17.0 FirePHP/0.7.1";s:11:"http_accept";s:63:"text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8";}";}Zend_Auth|a:1:{s:7:"storage";O:8:"stdClass":3:{s:6:"userId";s:1:"1";s:8:"userName";s:7:"dxadmin";s:4:"role";s:7:"dxadmin";}}.Mozilla/5.0 (Macintosh; Intel Mac OS X 10.6; rv:17.0) Gecko/20100101 Firefox/17.0|a:1:{s:7:"storage";s:2925:"a:6:{s:12:"browser_type";s:7:"desktop";s:6:"config";a:3:{s:23:"identification_sequence";s:14:"mobile,desktop";s:7:"storage";a:1:{s:7:"adapter";s:7:"Session";}s:6:"mobile";a:1:{s:8:"features";a:1:{s:9:"classname";s:45:"Zend_Http_UserAgent_Features_Adapter_Browscap";}}}s:12:"device_class";s:27:"Zend_Http_UserAgent_Desktop";s:6:"device";s:2381:"a:6:{s:10:"_aFeatures";a:28:{s:21:"browser_compatibility";s:7:"Firefox";s:14:"browser_engine";s:5:"Gecko";s:12:"browser_name";s:7:"Firefox";s:13:"browser_token";s:19:"Intel Mac OS X 10.6";s:15:"browser_version";s:4:"17.0";s:7:"comment";a:2:{s:4:"full";s:39:"Macintosh; Intel Mac OS X 10.6; rv:17.0";s:6:"detail";a:3:{i:0;s:9:"Macintosh";i:1;s:20:" Intel Mac OS X 10.6";i:2;s:8:" rv:17.0";}}s:18:"compatibility_flag";s:9:"Macintosh";s:15:"device_os_token";s:7:"rv:17.0";s:6:"others";a:2:{s:4:"full";s:27:"Gecko/20100101 Firefox/17.0";s:6:"detail";a:2:{i:0;a:3:{i:0;s:14:"Gecko/20100101";i:1;s:5:"Gecko";i:2;s:8:"20100101";}i:1;a:3:{i:0;s:12:"Firefox/17.0";i:1;s:7:"Firefox";i:2;s:4:"17.0";}}}s:12:"product_name";s:7:"Mozilla";s:15:"product_version";s:3:"5.0";s:10:"user_agent";s:11:"Mozilla/5.0";s:18:"is_wireless_device";b:0;s:9:"is_mobile";b:0;s:10:"is_desktop";b:1;s:9:"is_tablet";b:0;s:6:"is_bot";b:0;s:8:"is_email";b:0;s:7:"is_text";b:0;s:25:"device_claims_web_support";b:0;s:9:"client_ip";s:9:"127.0.0.1";s:11:"php_version";s:5:"5.4.4";s:9:"server_os";s:6:"apache";s:17:"server_os_version";i:1;s:18:"server_http_accept";s:63:"text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8";s:27:"server_http_accept_language";s:14:"en-US,en;q=0.5";s:9:"server_ip";s:9:"127.0.0.1";s:11:"server_name";s:18:"dev.webinertia.net";}s:7:"_aGroup";a:2:{s:12:"product_info";a:21:{i:0;s:21:"browser_compatibility";i:1;s:14:"browser_engine";i:2;s:12:"browser_name";i:3;s:13:"browser_token";i:4;s:15:"browser_version";i:5;s:7:"comment";i:6;s:18:"compatibility_flag";i:7;s:15:"device_os_token";i:8;s:6:"others";i:9;s:12:"product_name";i:10;s:15:"product_version";i:11;s:10:"user_agent";i:12;s:18:"is_wireless_device";i:13;s:9:"is_mobile";i:14;s:10:"is_desktop";i:15;s:9:"is_tablet";i:16;s:6:"is_bot";i:17;s:8:"is_email";i:18;s:7:"is_text";i:19;s:25:"device_claims_web_support";i:20;s:9:"client_ip";}s:11:"server_info";a:7:{i:0;s:11:"php_version";i:1;s:9:"server_os";i:2;s:17:"server_os_version";i:3;s:18:"server_http_accept";i:4;s:27:"server_http_accept_language";i:5;s:9:"server_ip";i:6;s:11:"server_name";}}s:8:"_browser";s:7:"Firefox";s:15:"_browserVersion";s:4:"17.0";s:10:"_userAgent";s:81:"Mozilla/5.0 (Macintosh; Intel Mac OS X 10.6; rv:17.0) Gecko/20100101 Firefox/17.0";s:7:"_images";a:6:{i:0;s:4:"jpeg";i:1;s:3:"gif";i:2;s:3:"png";i:3;s:5:"pjpeg";i:4;s:5:"x-png";i:5;s:3:"bmp";}}";s:10:"user_agent";s:81:"Mozilla/5.0 (Macintosh; Intel Mac OS X 10.6; rv:17.0) Gecko/20100101 Firefox/17.0";s:11:"http_accept";s:63:"text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8";}";}pages|a:1:{s:6:"pageId";s:2:"76";}'),
('e22179a277eefe1fdd85cdb9bfc84f62', 1356710183, 86400, '.Mozilla/5.0 (Macintosh; Intel Mac OS X 10.6; rv:17.0) Gecko/20100101 Firefox/17.0|a:1:{s:7:"storage";s:2926:"a:6:{s:12:"browser_type";s:7:"desktop";s:6:"config";a:3:{s:23:"identification_sequence";s:14:"mobile,desktop";s:7:"storage";a:1:{s:7:"adapter";s:7:"Session";}s:6:"mobile";a:1:{s:8:"features";a:1:{s:9:"classname";s:45:"Zend_Http_UserAgent_Features_Adapter_Browscap";}}}s:12:"device_class";s:27:"Zend_Http_UserAgent_Desktop";s:6:"device";s:2382:"a:6:{s:10:"_aFeatures";a:28:{s:21:"browser_compatibility";s:7:"Firefox";s:14:"browser_engine";s:5:"Gecko";s:12:"browser_name";s:7:"Firefox";s:13:"browser_token";s:19:"Intel Mac OS X 10.6";s:15:"browser_version";s:4:"17.0";s:7:"comment";a:2:{s:4:"full";s:39:"Macintosh; Intel Mac OS X 10.6; rv:17.0";s:6:"detail";a:3:{i:0;s:9:"Macintosh";i:1;s:20:" Intel Mac OS X 10.6";i:2;s:8:" rv:17.0";}}s:18:"compatibility_flag";s:9:"Macintosh";s:15:"device_os_token";s:7:"rv:17.0";s:6:"others";a:2:{s:4:"full";s:27:"Gecko/20100101 Firefox/17.0";s:6:"detail";a:2:{i:0;a:3:{i:0;s:14:"Gecko/20100101";i:1;s:5:"Gecko";i:2;s:8:"20100101";}i:1;a:3:{i:0;s:12:"Firefox/17.0";i:1;s:7:"Firefox";i:2;s:4:"17.0";}}}s:12:"product_name";s:7:"Mozilla";s:15:"product_version";s:3:"5.0";s:10:"user_agent";s:11:"Mozilla/5.0";s:18:"is_wireless_device";b:0;s:9:"is_mobile";b:0;s:10:"is_desktop";b:1;s:9:"is_tablet";b:0;s:6:"is_bot";b:0;s:8:"is_email";b:0;s:7:"is_text";b:0;s:25:"device_claims_web_support";b:0;s:9:"client_ip";s:9:"127.0.0.1";s:11:"php_version";s:6:"5.2.17";s:9:"server_os";s:6:"apache";s:17:"server_os_version";i:1;s:18:"server_http_accept";s:63:"text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8";s:27:"server_http_accept_language";s:14:"en-US,en;q=0.5";s:9:"server_ip";s:9:"127.0.0.1";s:11:"server_name";s:18:"dev.webinertia.net";}s:7:"_aGroup";a:2:{s:12:"product_info";a:21:{i:0;s:21:"browser_compatibility";i:1;s:14:"browser_engine";i:2;s:12:"browser_name";i:3;s:13:"browser_token";i:4;s:15:"browser_version";i:5;s:7:"comment";i:6;s:18:"compatibility_flag";i:7;s:15:"device_os_token";i:8;s:6:"others";i:9;s:12:"product_name";i:10;s:15:"product_version";i:11;s:10:"user_agent";i:12;s:18:"is_wireless_device";i:13;s:9:"is_mobile";i:14;s:10:"is_desktop";i:15;s:9:"is_tablet";i:16;s:6:"is_bot";i:17;s:8:"is_email";i:18;s:7:"is_text";i:19;s:25:"device_claims_web_support";i:20;s:9:"client_ip";}s:11:"server_info";a:7:{i:0;s:11:"php_version";i:1;s:9:"server_os";i:2;s:17:"server_os_version";i:3;s:18:"server_http_accept";i:4;s:27:"server_http_accept_language";i:5;s:9:"server_ip";i:6;s:11:"server_name";}}s:8:"_browser";s:7:"Firefox";s:15:"_browserVersion";s:4:"17.0";s:10:"_userAgent";s:81:"Mozilla/5.0 (Macintosh; Intel Mac OS X 10.6; rv:17.0) Gecko/20100101 Firefox/17.0";s:7:"_images";a:6:{i:0;s:4:"jpeg";i:1;s:3:"gif";i:2;s:3:"png";i:3;s:5:"pjpeg";i:4;s:5:"x-png";i:5;s:3:"bmp";}}";s:10:"user_agent";s:81:"Mozilla/5.0 (Macintosh; Intel Mac OS X 10.6; rv:17.0) Gecko/20100101 Firefox/17.0";s:11:"http_accept";s:63:"text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8";}";}');

-- --------------------------------------------------------

--
-- Table structure for table `skins`
--

DROP TABLE IF EXISTS `skins`;
CREATE TABLE `skins` (
  `skinId` int(11) NOT NULL AUTO_INCREMENT,
  `isCurrentSkin` int(1) NOT NULL DEFAULT '0',
  `skinName` varchar(50) DEFAULT NULL,
  `includeDefault` int(1) NOT NULL DEFAULT '1',
  `skinCssPath` varchar(255) DEFAULT NULL,
  `skinCssMobiPath` varchar(255) NOT NULL,
  `skinTemplatePath` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`skinId`),
  UNIQUE KEY `skinName` (`skinName`),
  KEY `skinCssPath` (`skinCssPath`,`skinTemplatePath`),
  KEY `includeDefault` (`includeDefault`),
  KEY `isCurrentSkin` (`isCurrentSkin`),
  KEY `skinCssMobiPath` (`skinCssMobiPath`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `skins`
--

INSERT INTO `skins` (`skinId`, `isCurrentSkin`, `skinName`, `includeDefault`, `skinCssPath`, `skinCssMobiPath`, `skinTemplatePath`) VALUES
(1, 1, 'default', 0, 'skins/default/style.css', 'skins/default/style.mobi.css', NULL),
(11, 0, 'green', 1, 'skins/green/style.css', 'skins/green/style.mobi.css', NULL),
(12, 0, 'yellow', 1, 'skins/yellow/style.css', 'skins/yellow/style.mobi.css', NULL),
(13, 0, 'red', 1, 'skins/red/style.css', 'skins/red/', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `slidersettings`
--

DROP TABLE IF EXISTS `slidersettings`;
CREATE TABLE `slidersettings` (
  `name` varchar(255) NOT NULL,
  `isActive` int(1) NOT NULL DEFAULT '0' COMMENT 'used for boolean',
  `effect` varchar(255) NOT NULL DEFAULT 'fade',
  `slices` int(11) NOT NULL DEFAULT '15',
  `boxCols` int(11) NOT NULL DEFAULT '8',
  `boxRows` int(11) NOT NULL DEFAULT '4',
  `animSpeed` int(11) NOT NULL DEFAULT '500',
  `pauseTime` int(11) NOT NULL DEFAULT '3000',
  `startSlide` int(11) NOT NULL DEFAULT '1',
  `directionNav` int(1) NOT NULL DEFAULT '1' COMMENT 'used for boolean',
  `controlNav` int(1) NOT NULL DEFAULT '1' COMMENT 'used for boolean',
  `controlNavThumbs` int(1) NOT NULL DEFAULT '0' COMMENT 'used for boolean',
  `pauseOnHover` int(1) NOT NULL DEFAULT '1' COMMENT 'used for boolean',
  `manualAdvance` int(1) NOT NULL DEFAULT '0' COMMENT 'used for boolean',
  `prevText` varchar(25) NOT NULL DEFAULT 'Prev' COMMENT 'label for prev text',
  `nextText` varchar(25) NOT NULL DEFAULT 'Next' COMMENT 'label for next text',
  `randomStart` int(1) NOT NULL DEFAULT '0' COMMENT 'used for boolean',
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `slidersettings`
--

INSERT INTO `slidersettings` (`name`, `isActive`, `effect`, `slices`, `boxCols`, `boxRows`, `animSpeed`, `pauseTime`, `startSlide`, `directionNav`, `controlNav`, `controlNavThumbs`, `pauseOnHover`, `manualAdvance`, `prevText`, `nextText`, `randomStart`) VALUES
('default', 1, 'fade', 15, 8, 4, 500, 3000, 1, 1, 1, 0, 1, 0, 'Prev', 'Next', 0);

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

DROP TABLE IF EXISTS `testimonials`;
CREATE TABLE `testimonials` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guestName` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `rating` int(1) DEFAULT NULL,
  `isApproved` tinyint(1) NOT NULL DEFAULT '0',
  `createdDate` int(11) NOT NULL,
  `updatedDate` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `testimonials`
--

INSERT INTO `testimonials` (`id`, `guestName`, `content`, `rating`, `isApproved`, `createdDate`, `updatedDate`) VALUES
(1, 'Joey Smith', 'testing testimonials submission on front end', NULL, 1, 1355687289, 0),
(2, 'Joey Smith', 'This is a test testimonial to test adding them to the search index.', NULL, 0, 1355868709, 0),
(3, 'Joey Smith', 'Testing resource url being stored in the search index for use in the view for linking to resource.', NULL, 1, 1355873588, 0);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `userId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(128) NOT NULL,
  `userName` varchar(128) NOT NULL,
  `firstName` varchar(128) NOT NULL,
  `lastName` varchar(128) NOT NULL,
  `email` varchar(128) NOT NULL,
  `passWord` char(40) NOT NULL,
  `salt` char(32) NOT NULL,
  `role` varchar(100) NOT NULL DEFAULT 'user',
  `uStatus` varchar(8) NOT NULL DEFAULT 'disabled',
  `registeredDate` varchar(11) NOT NULL,
  `hash` int(10) NOT NULL,
  PRIMARY KEY (`userId`),
  KEY `email_pass` (`email`,`passWord`),
  KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userId`, `title`, `userName`, `firstName`, `lastName`, `email`, `passWord`, `salt`, `role`, `uStatus`, `registeredDate`, `hash`) VALUES
(1, '', 'dxadmin', '', '', '', 'e1da551374f0a6f136916647ab0f157cc192ea22', '', 'dxadmin', 'enabled', '', 0);
