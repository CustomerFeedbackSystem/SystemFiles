-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 15, 2015 at 10:21 AM
-- Server version: 5.5.24-log
-- PHP Version: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `majivoice_github`
--

-- --------------------------------------------------------

--
-- Table structure for table `acc_list`
--

CREATE TABLE IF NOT EXISTS `acc_list` (
  `idacc_list` int(8) DEFAULT NULL,
  `accname` int(11) DEFAULT NULL,
  `accnum` varchar(60) DEFAULT NULL,
  `oldacc` varchar(60) DEFAULT NULL,
  `servicepiont` varchar(60) DEFAULT NULL,
  `clientcode` varchar(60) DEFAULT NULL,
  `clienttype` varchar(60) DEFAULT NULL,
  `accstatus` varchar(60) DEFAULT NULL,
  `metersrno` varchar(60) DEFAULT NULL,
  `makecod` varchar(60) DEFAULT NULL,
  `mtrmodelcod` varchar(60) DEFAULT NULL,
  `acclrno` varchar(120) DEFAULT NULL,
  `regcod` varchar(60) DEFAULT NULL,
  `regnam` varchar(60) DEFAULT NULL,
  `itineraryno` varchar(60) DEFAULT NULL,
  `accaddres` varchar(120) DEFAULT NULL,
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `audit_access`
--

CREATE TABLE IF NOT EXISTS `audit_access` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `usrid` int(11) NOT NULL,
  `usrname` varchar(40) NOT NULL,
  `timeaudit` datetime NOT NULL,
  `url` varchar(250) NOT NULL,
  `load_session` varchar(70) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `audit_docdownlds`
--

CREATE TABLE IF NOT EXISTS `audit_docdownlds` (
  `idaudit_downlods` int(11) NOT NULL AUTO_INCREMENT,
  `doc_name` varchar(250) NOT NULL,
  `doc_ext` varchar(5) NOT NULL,
  `doc_size` int(11) NOT NULL,
  `tktin_idtktin` int(11) NOT NULL DEFAULT '0',
  `createdon` datetime NOT NULL,
  `createdby` int(11) NOT NULL,
  `usersess` varchar(250) NOT NULL,
  `usrip` varchar(50) NOT NULL DEFAULT '0',
  `usrresult` char(4) DEFAULT NULL,
  PRIMARY KEY (`idaudit_downlods`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `audit_login`
--

CREATE TABLE IF NOT EXISTS `audit_login` (
  `idaudit_login` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `acname` varchar(50) DEFAULT NULL,
  `userip` varchar(20) DEFAULT NULL,
  `userbrowser` varchar(250) DEFAULT NULL,
  `urlreferer` varchar(150) DEFAULT NULL,
  `attempttime` datetime DEFAULT NULL,
  `attemptresult` varchar(4) DEFAULT NULL,
  `usersession` varchar(50) NOT NULL,
  PRIMARY KEY (`idaudit_login`),
  KEY `attemptresult` (`attemptresult`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `audit_login`
--

INSERT INTO `audit_login` (`idaudit_login`, `acname`, `userip`, `userbrowser`, `urlreferer`, `attempttime`, `attemptresult`, `usersession`) VALUES
(1, 'admin', '127.0.0.1', 'Mozilla/5.0 (Windows NT 5.1; rv:39.0) Gecko/20100101 Firefox/39.0', 'http://localhost/majivoice_github/user_login/a/index.php?resolution=1&width=1280&height=800', '2015-08-15 11:52:36', 'OK', 'nanava25csge77jja86u6b6251'),
(2, 'admin', '127.0.0.1', 'Mozilla/5.0 (Windows NT 5.1; rv:39.0) Gecko/20100101 Firefox/39.0', 'http://localhost/majivoice_github/user_login/a/index.php?resolution=1&width=1280&height=800', '2015-08-15 13:02:14', 'OK', 'nanava25csge77jja86u6b6251'),
(3, 'admin', '127.0.0.1', 'Mozilla/5.0 (Windows NT 5.1; rv:39.0) Gecko/20100101 Firefox/39.0', 'http://localhost/majivoice_github/user_login/a/index.php?resolution=1&width=1280&height=800', '2015-08-15 13:04:47', 'OK', 'nanava25csge77jja86u6b6251'),
(4, 'admin', '127.0.0.1', 'Mozilla/5.0 (Windows NT 5.1; rv:39.0) Gecko/20100101 Firefox/39.0', 'http://localhost/majivoice_github/user_login/a/index.php?resolution=1&width=1280&height=800', '2015-08-15 13:05:53', 'OK', 'nanava25csge77jja86u6b6251');

-- --------------------------------------------------------

--
-- Table structure for table `audit_userrole`
--

CREATE TABLE IF NOT EXISTS `audit_userrole` (
  `idaudit_userrole` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `iduserrole` int(8) unsigned DEFAULT NULL,
  `new_iduserrole` int(8) unsigned DEFAULT NULL,
  `transactiondate` datetime DEFAULT NULL,
  `transactionby` int(8) unsigned DEFAULT NULL,
  PRIMARY KEY (`idaudit_userrole`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `audit_usrac_log`
--

CREATE TABLE IF NOT EXISTS `audit_usrac_log` (
  `idusrac` int(8) unsigned NOT NULL,
  `usrrole_idusrrole` int(8) unsigned DEFAULT NULL,
  `usrteam_idusrteam` int(4) unsigned NOT NULL,
  `usrname` varchar(150) DEFAULT NULL,
  `usrpass` varchar(150) DEFAULT NULL,
  `utitle` varchar(10) DEFAULT NULL,
  `fname` varchar(40) DEFAULT NULL,
  `lname` varchar(40) DEFAULT NULL,
  `usrgender` char(1) NOT NULL DEFAULT '-',
  `acstatus` tinyint(1) unsigned DEFAULT NULL,
  `acstatus_work` tinyint(1) NOT NULL DEFAULT '1',
  `mobileaccess` tinyint(1) unsigned DEFAULT '0',
  `usremail` varchar(80) DEFAULT NULL,
  `usrphone` varchar(30) DEFAULT NULL,
  `usersess` varchar(50) DEFAULT NULL,
  `createdby` int(8) unsigned DEFAULT NULL,
  `createdon` datetime DEFAULT NULL,
  `modifiedby` int(8) unsigned DEFAULT '0',
  `modifiedon` datetime DEFAULT '0000-00-00 00:00:00',
  `lastaccess` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `currentsess` varchar(100) DEFAULT NULL,
  `audit_by` int(9) NOT NULL,
  `audit_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `audit_usrrole_log`
--

CREATE TABLE IF NOT EXISTS `audit_usrrole_log` (
  `idusrrole` int(8) unsigned NOT NULL,
  `sysprofiles_idsysprofiles` int(8) unsigned NOT NULL,
  `usrteamzone_idusrteamzone` int(5) unsigned NOT NULL,
  `usrrolename` varchar(100) DEFAULT NULL,
  `usrroledesc` text,
  `reportingto` int(8) NOT NULL DEFAULT '0',
  `joblevel` int(3) NOT NULL DEFAULT '0',
  `usrdpts_idusrdpts` int(3) NOT NULL DEFAULT '0',
  `usrac_idusrac` int(11) NOT NULL,
  `modifiedby` int(8) unsigned DEFAULT NULL,
  `modifiedon` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `audit_wfassetsdata`
--

CREATE TABLE IF NOT EXISTS `audit_wfassetsdata` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `idwfassetsdata` int(11) unsigned NOT NULL,
  `wfprocassetsaccess_idwfprocassetsaccess` int(11) unsigned NOT NULL,
  `wfprocassetschoice_idwfprocassetschoice_prev` int(8) NOT NULL DEFAULT '0',
  `wfprocassetschoice_idwfprocassetschoice_new` int(11) NOT NULL DEFAULT '0',
  `wfprocassets_idwfprocassets` int(8) NOT NULL,
  `wftasks_idwftasks` int(11) NOT NULL,
  `wftskupdates_idwftskupdates` int(11) NOT NULL DEFAULT '0',
  `value_choice_prev` varchar(250) DEFAULT NULL,
  `value_choice_new` varchar(250) DEFAULT NULL,
  `value_path_prev` varchar(250) DEFAULT NULL,
  `value_path_new` varchar(250) DEFAULT NULL,
  `wftaskstrac_idwftaskstrac` int(11) NOT NULL DEFAULT '0',
  `tktin_idtktin` int(11) NOT NULL DEFAULT '0',
  `createdby` int(11) unsigned DEFAULT NULL,
  `createdon` datetime DEFAULT NULL,
  `modifiedby_new` int(11) unsigned DEFAULT NULL,
  `modifiedon_new` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `value_path` (`value_path_prev`),
  KEY `wfprocassetschoice_idwfprocassetschoice` (`wfprocassetschoice_idwfprocassetschoice_prev`),
  KEY `wfprocassets_idwfprocassets` (`wfprocassets_idwfprocassets`),
  KEY `wfprocassetsaccess_idwfprocassetsaccess` (`wfprocassetsaccess_idwfprocassetsaccess`),
  KEY `wftasks_idwftasks` (`wftasks_idwftasks`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `audit_wftasksadj`
--

CREATE TABLE IF NOT EXISTS `audit_wftasksadj` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idwftasks` int(11) NOT NULL,
  `usrrole_idusrrole` int(8) NOT NULL,
  `usrac_idusrac` int(8) NOT NULL,
  `sender_idusrrole` int(8) NOT NULL,
  `sender_idusrac` int(8) NOT NULL,
  `timedone` datetime NOT NULL,
  `bywho` int(8) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `audit_wftasks_batch`
--

CREATE TABLE IF NOT EXISTS `audit_wftasks_batch` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `action` tinytext NOT NULL,
  `actionby_idusrac` int(11) NOT NULL,
  `actionby_idusrrole` int(11) NOT NULL,
  `tktin_affected` int(11) NOT NULL,
  `batchid_old` int(11) NOT NULL,
  `batchid_new` int(11) NOT NULL,
  `result` tinytext NOT NULL,
  `browser_session` varchar(30) NOT NULL,
  `action_time` datetime NOT NULL,
  `user_ip` varchar(30) DEFAULT NULL,
  `user_ip_proxy` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `disaggresults`
--

CREATE TABLE IF NOT EXISTS `disaggresults` (
  `iddisaggresults` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `reportactivities_idreportactivities` int(11) unsigned NOT NULL,
  `disagg_iddisagg` int(4) unsigned NOT NULL,
  `disagg_value` int(11) unsigned DEFAULT NULL,
  `addedon` datetime NOT NULL,
  `addedby` int(8) NOT NULL,
  PRIMARY KEY (`iddisaggresults`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `guide_chapters`
--

CREATE TABLE IF NOT EXISTS `guide_chapters` (
  `idguidehapters` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `guide_chapters_idguidehapters` int(8) unsigned NOT NULL DEFAULT '0',
  `chapter` varchar(50) DEFAULT NULL,
  `chapterintro` text,
  `chapterversion` float(2,2) DEFAULT NULL,
  `list_order` int(5) unsigned DEFAULT NULL,
  PRIMARY KEY (`idguidehapters`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `guide_content`
--

CREATE TABLE IF NOT EXISTS `guide_content` (
  `idguide_content` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `guide_chapters_idguidehapters` int(8) unsigned NOT NULL,
  `content` text,
  PRIMARY KEY (`idguide_content`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `hd`
--

CREATE TABLE IF NOT EXISTS `hd` (
  `idhelpdesk` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `hd_idhelpdesk` int(8) unsigned NOT NULL,
  `hd_feedback_idhd_feedback` int(2) unsigned NOT NULL,
  `hd_urgency_idhd_urgency` int(2) unsigned NOT NULL,
  `datesent` datetime DEFAULT NULL,
  `helpsubject` varchar(100) DEFAULT NULL,
  `helpmsg` text,
  `createdon` datetime DEFAULT NULL,
  `createdby` int(8) unsigned DEFAULT NULL,
  `respondent_userid` int(8) unsigned DEFAULT '0',
  `response_time` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`idhelpdesk`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `hd_feedback`
--

CREATE TABLE IF NOT EXISTS `hd_feedback` (
  `idhd_feedback` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `hdfeedback` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`idhd_feedback`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `hd_feedback`
--

INSERT INTO `hd_feedback` (`idhd_feedback`, `hdfeedback`) VALUES
(1, 'Email Me Back'),
(2, 'Call me back please');

-- --------------------------------------------------------

--
-- Table structure for table `hd_urgency`
--

CREATE TABLE IF NOT EXISTS `hd_urgency` (
  `idhd_urgency` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `hdurgency` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`idhd_urgency`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `hd_urgency`
--

INSERT INTO `hd_urgency` (`idhd_urgency`, `hdurgency`) VALUES
(1, 'General Comment / Question'),
(2, 'Important but not Urgent'),
(3, 'Extremely Urgent!'),
(4, 'Just a Suggestion');

-- --------------------------------------------------------

--
-- Table structure for table `link_batchtype_tktcat`
--

CREATE TABLE IF NOT EXISTS `link_batchtype_tktcat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wftasks_batchtype_idwftasks_batchtype` int(4) NOT NULL,
  `tktcategory_idtktcategory` int(4) NOT NULL,
  `createdon` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updatedon` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `link_guide_submod`
--

CREATE TABLE IF NOT EXISTS `link_guide_submod` (
  `idlink_guide_submod` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `idguide_content` int(8) unsigned NOT NULL,
  `idsyssubmodule` int(5) unsigned NOT NULL,
  PRIMARY KEY (`idlink_guide_submod`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `link_tktcategory_tktype`
--

CREATE TABLE IF NOT EXISTS `link_tktcategory_tktype` (
  `idtktcategorytype` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `tktcategory_idtktcategory` int(3) unsigned DEFAULT NULL,
  `tkttype_idtkttype` int(1) unsigned DEFAULT NULL,
  PRIMARY KEY (`idtktcategorytype`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=37 ;

--
-- Dumping data for table `link_tktcategory_tktype`
--

INSERT INTO `link_tktcategory_tktype` (`idtktcategorytype`, `tktcategory_idtktcategory`, `tkttype_idtkttype`) VALUES
(1, 1, 1),
(2, 1, 3),
(3, 2, 1),
(4, 2, 3),
(5, 3, 2),
(6, 3, 3),
(7, 4, 2),
(8, 4, 3),
(9, 5, 1),
(10, 5, 3),
(11, 6, 1),
(12, 6, 3),
(13, 7, 1),
(14, 7, 3),
(15, 8, 1),
(16, 8, 3),
(17, 9, 1),
(18, 9, 2),
(19, 9, 3),
(20, 13, 1),
(21, 13, 2),
(22, 14, 1),
(23, 15, 1),
(24, 16, 1),
(25, 17, 1),
(26, 18, 1),
(27, 19, 1),
(28, 20, 1),
(29, 21, 1),
(30, 22, 1),
(31, 23, 1),
(32, 1, 2),
(33, 25, 1),
(34, 2, 2),
(35, 2, 2),
(36, 26, 1);

-- --------------------------------------------------------

--
-- Table structure for table `link_tskcategory_wfproc`
--

CREATE TABLE IF NOT EXISTS `link_tskcategory_wfproc` (
  `idlink_tskcategory_wfproc` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `wfproc_idwfproc` int(5) unsigned NOT NULL,
  `usrteam_idusrteam` int(8) NOT NULL DEFAULT '0',
  `usrteamzone_idusrteamzone` int(8) NOT NULL DEFAULT '0',
  `tktcategory_idtktcategory` int(3) unsigned NOT NULL,
  `createdby` int(8) unsigned DEFAULT NULL,
  `createdon` datetime DEFAULT NULL,
  `modifiedby` int(8) unsigned DEFAULT '0',
  `modifiedon` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`idlink_tskcategory_wfproc`),
  UNIQUE KEY `teamcat` (`usrteam_idusrteam`,`tktcategory_idtktcategory`),
  KEY `tktcategory_idtktcategory` (`tktcategory_idtktcategory`),
  KEY `wfproc_idwfproc` (`wfproc_idwfproc`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `link_tskcategory_wfproc`
--

INSERT INTO `link_tskcategory_wfproc` (`idlink_tskcategory_wfproc`, `wfproc_idwfproc`, `usrteam_idusrteam`, `usrteamzone_idusrteamzone`, `tktcategory_idtktcategory`, `createdby`, `createdon`, `modifiedby`, `modifiedon`) VALUES
(1, 1, 1, 1, 10, 1, '2015-08-15 13:12:50', 0, '0000-00-00 00:00:00'),
(2, 2, 1, 1, 1, 1, '2015-08-15 13:19:46', 0, '0000-00-00 00:00:00'),
(3, 2, 1, 1, 2, 1, '2015-08-15 13:19:52', 0, '0000-00-00 00:00:00'),
(4, 2, 1, 1, 7, 1, '2015-08-15 13:19:59', 0, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `link_userrole_usergroup`
--

CREATE TABLE IF NOT EXISTS `link_userrole_usergroup` (
  `idlink_userac_usergroup` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `usrteam_idusrteam` int(4) NOT NULL,
  `usrrole_idusrrole` int(8) unsigned NOT NULL,
  `usrgroup_idusrgroup` int(5) unsigned NOT NULL,
  `createdby` int(8) NOT NULL,
  `createdon` datetime NOT NULL,
  PRIMARY KEY (`idlink_userac_usergroup`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `link_userteamzone_locations`
--

CREATE TABLE IF NOT EXISTS `link_userteamzone_locations` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `usrteamzone_idusrteamzone` int(5) unsigned NOT NULL,
  `loctowns_idloctowns` int(8) unsigned NOT NULL,
  `createdby` int(8) unsigned DEFAULT NULL,
  `createdon` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `usrteamzone_idusrteamzone` (`usrteamzone_idusrteamzone`),
  KEY `loctowns_idloctowns` (`loctowns_idloctowns`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `loccountry`
--

CREATE TABLE IF NOT EXISTS `loccountry` (
  `idloccountry` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `countryname` varchar(50) DEFAULT NULL,
  `lng` float(10,6) DEFAULT NULL,
  `lat` float(10,6) DEFAULT NULL,
  PRIMARY KEY (`idloccountry`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `loccountry`
--

INSERT INTO `loccountry` (`idloccountry`, `countryname`, `lng`, `lat`) VALUES
(1, 'Kenya', 37.906189, -0.023560);

-- --------------------------------------------------------

--
-- Table structure for table `loccounty`
--

CREATE TABLE IF NOT EXISTS `loccounty` (
  `idloccounty` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `locregion_idlocregion` int(3) unsigned NOT NULL,
  `loccountyname` varchar(80) DEFAULT NULL,
  `liststatus` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`idloccounty`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=76 ;

--
-- Dumping data for table `loccounty`
--

INSERT INTO `loccounty` (`idloccounty`, `locregion_idlocregion`, `loccountyname`, `liststatus`) VALUES
(1, 8, 'Kwale', 1),
(2, 8, 'Mombasa', 1),
(3, 8, 'Taita Taveta', 1),
(4, 8, 'Kilifi', 1),
(5, 8, 'Lamu', 1),
(6, 8, 'Tana River', 1),
(7, 8, 'Malindi', 0),
(8, 3, 'Makueni', 1),
(9, 3, 'Machakos', 1),
(10, 3, 'Kitui', 1),
(11, 3, 'Mwingi', 0),
(12, 3, 'Meru', 1),
(13, 3, 'Meru South', 0),
(14, 3, 'Meru North', 0),
(15, 3, 'Tharaka Nithi', 1),
(16, 3, 'Mbeere', 0),
(17, 3, 'Embu', 1),
(18, 3, 'Isiolo', 1),
(19, 3, 'Marsabit', 1),
(20, 3, 'Moyale', 0),
(21, 4, 'Garissa', 1),
(22, 4, 'Ijara', 0),
(23, 4, 'Mandera', 1),
(24, 4, 'Wajir', 1),
(25, 7, 'Kajiado', 1),
(26, 7, 'Narok', 1),
(27, 7, 'Trans Mara', 0),
(28, 7, 'Turkana', 1),
(29, 7, 'West Pokot', 1),
(30, 7, 'Elgeyo / Marakwet', 1),
(31, 7, 'Trans Nzoia', 1),
(32, 7, 'Keiyo', 0),
(33, 7, 'Uasin Gishu', 1),
(34, 7, 'Nandi', 1),
(35, 7, 'Nandi South', 0),
(36, 7, 'Kericho', 1),
(37, 7, 'Bureti', 0),
(38, 7, 'Bomet', 1),
(39, 7, 'Baringo', 1),
(40, 7, 'Koibatek', 0),
(41, 7, 'Nakuru', 1),
(42, 7, 'Samburu', 1),
(43, 7, 'Laikipia', 1),
(44, 5, 'Bondo', 0),
(45, 5, 'Nyando', 0),
(46, 5, 'Siaya', 1),
(47, 5, 'Suba', 0),
(48, 5, 'Kuria', 0),
(49, 5, 'Rachuonyo', 0),
(50, 5, 'Kisii', 1),
(51, 5, 'Gucha', 0),
(52, 5, 'Nyamira', 1),
(53, 5, ' Kisumu', 1),
(54, 5, 'Homa Bay', 1),
(55, 5, 'Migori', 1),
(56, 6, 'Busia', 1),
(57, 6, 'Bungoma', 0),
(58, 6, 'Teso', 0),
(59, 6, 'Mt. Elgon', 0),
(60, 6, 'Lugari', 0),
(61, 6, 'Kakamega', 1),
(62, 6, 'Vihiga', 1),
(63, 6, 'Butere/Mumias', 0),
(64, 2, 'Kiambu', 1),
(65, 2, 'Thika', 0),
(66, 2, 'Murang''a', 1),
(67, 2, 'Maragua', 0),
(68, 2, 'Nyandarua', 1),
(69, 2, 'Nyeri', 1),
(70, 2, 'Kirinyaga', 1),
(71, 1, 'Westlands', 0),
(72, 1, 'Kasarani', 0),
(73, 1, 'Langata', 0),
(74, 1, 'Embakasi', 0),
(75, 8, 'Nairobi', 1);

-- --------------------------------------------------------

--
-- Table structure for table `locregion`
--

CREATE TABLE IF NOT EXISTS `locregion` (
  `idlocregion` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `loccountry_idloccountry` int(3) unsigned NOT NULL,
  `locregionname` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`idlocregion`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `locregion`
--

INSERT INTO `locregion` (`idlocregion`, `loccountry_idloccountry`, `locregionname`) VALUES
(1, 1, 'Nairobi'),
(2, 1, 'Central'),
(3, 1, 'Eastern'),
(4, 1, 'North Eastern'),
(5, 1, 'Nyanza'),
(6, 1, 'Western'),
(7, 1, 'Rift Valley'),
(8, 1, 'Coast');

-- --------------------------------------------------------

--
-- Table structure for table `loctowns`
--

CREATE TABLE IF NOT EXISTS `loctowns` (
  `idloctowns` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `loccountry_idloccountry` int(3) unsigned NOT NULL,
  `locationname` varchar(50) DEFAULT NULL,
  `lng` float(10,6) DEFAULT NULL,
  `lat` float(10,6) DEFAULT NULL,
  `createdby` int(8) unsigned DEFAULT NULL,
  `createdon` datetime DEFAULT NULL,
  `validatedby` int(8) unsigned DEFAULT '0',
  `validatedon` datetime DEFAULT '0000-00-00 00:00:00',
  `is_valid` tinyint(1) unsigned DEFAULT '0',
  `is_town` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idloctowns`),
  UNIQUE KEY `locationname` (`locationname`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14055 ;

--
-- Dumping data for table `loctowns`
--

INSERT INTO `loctowns` (`idloctowns`, `loccountry_idloccountry`, `locationname`, `lng`, `lat`, `createdby`, `createdon`, `validatedby`, `validatedon`, `is_valid`, `is_town`) VALUES
(4, 1, 'Zimmerman', 36.895809, -1.212680, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(5, 1, 'Pangani', 36.839298, -1.270280, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(6, 1, 'Ngara', 36.829060, -1.274670, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(7, 1, 'Kariokor', 36.838360, -1.279460, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(8, 1, 'Savannah', 36.893631, -1.300760, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(9, 1, 'GreenFields', 36.816669, 1.283330, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(10, 1, 'South B', 36.833740, -1.310340, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(11, 1, 'Kibera', 36.783329, -1.316670, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(12, 1, 'Westlands', 36.810001, -1.270000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(13, 1, 'Langata', 36.788151, -1.327510, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(14, 1, 'Otiende', 36.779270, -1.321330, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(15, 1, 'Kangemi', 36.736778, -1.262490, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(16, 1, 'Kawagware', 36.744720, -1.285100, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(17, 1, 'BP Stage', 36.741219, -1.287760, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(18, 1, 'Maringo', 36.864281, -1.292890, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(19, 1, 'Komarock', 36.910820, -1.267210, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(20, 1, 'Lavington', 36.769218, -1.281650, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(21, 1, 'Pipeline', 36.829861, -1.313010, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(22, 1, 'South C', 36.824169, -1.312050, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(23, 1, 'Nairobi West ', 36.823170, -1.308760, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(24, 1, 'Kileleshwa', 36.794392, -1.274820, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(25, 1, 'Eastleigh', 36.838039, -1.267850, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(26, 1, 'Hurlingham', 36.791199, -1.292690, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(27, 1, 'Kilimani', 36.797421, -1.288630, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(28, 1, 'Siaya', 34.283440, 0.057380, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(29, 1, 'Kisumu', 34.756279, -0.098360, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(30, 1, 'Kendu Bay', 34.652210, -0.371690, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(31, 1, 'Bungoma', 34.563202, 0.569330, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(32, 1, 'Kakamega', 34.764431, 0.288900, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(33, 1, 'Nakuru', 36.080990, -0.283580, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(34, 1, 'Eldoret', 35.260509, 0.522830, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(35, 1, 'Kitale', 35.008789, 1.014470, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(36, 1, 'Buru Buru', 36.872345, -1.283480, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(44, 1, 'Abarot', 39.730000, -0.570000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(51, 1, 'Abarat', 39.730000, -0.570000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(52, 1, 'Abarati', 39.730000, -0.570000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(53, 1, 'Achivwa', 39.150002, -3.770000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(54, 1, 'Ada', 39.080002, -4.200000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(55, 1, 'Adda', 39.080002, -4.200000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(56, 1, 'Adu', 39.990002, -2.840000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(57, 1, 'Ahero', 34.919998, -0.180000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(58, 1, 'Ainamoi', 35.279999, -0.300000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(59, 1, 'Ainemoi', 35.279999, -0.300000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(60, 1, 'Airagwani', 36.970001, -0.120000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(61, 1, 'Aisere', 34.700001, 0.230000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(62, 1, 'Aiyam', 36.549999, 0.370000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(63, 1, 'Ajao', 39.700001, 2.950000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(64, 1, 'Akala', 34.430000, -0.070000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(65, 1, 'Akelo''s Village', 34.580002, -0.630000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(66, 1, 'Akira Ranch', 36.369999, -1.000000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(67, 1, 'Alangarua', 36.119999, -2.080000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(68, 1, 'Allus', 37.049999, 0.050000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(69, 1, 'Amakura', 34.270000, 0.570000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(70, 1, 'Ambira', 34.270000, 0.180000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(71, 1, 'Amboseli', 37.279999, -2.670000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(72, 1, 'Amimos', 34.799999, -0.180000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(73, 1, 'Amu', 40.900002, -2.280000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(74, 1, 'Amukara', 34.270000, 0.570000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(75, 1, 'Anasa', 40.310001, -2.520000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(76, 1, 'Andiwo''s Village', 34.700001, -0.520000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(77, 1, 'Andiwo?s Village', 34.700001, -0.520000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(78, 1, 'Aneko', 34.150002, -0.950000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(79, 1, 'Angara Naado', 35.980000, -1.250000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(80, 1, 'Angata Naado', 35.980000, -1.250000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(81, 1, 'Anglogitat', 35.419998, 1.950000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(82, 1, 'Angorangora', 35.400002, 2.630000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(83, 1, 'Anio', 39.790001, 3.330000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(84, 1, 'Ankish', 40.869999, -1.960000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(85, 1, 'Anyieka', 34.270000, 0.230000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(86, 1, 'Arabia', 41.500000, 3.530000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(87, 1, 'Arabuko', 39.970001, -3.250000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(88, 1, 'Archers Post', 37.680000, 0.650000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(89, 1, 'Arda Dadaja', 41.060001, 2.910000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(90, 1, 'Ardencaple Farm', 37.270000, 0.080000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(91, 1, 'Ariti', 40.299999, -2.520000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(92, 1, 'Arosen', 40.720001, -1.950000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(93, 1, 'Arroi', 37.299999, -2.030000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(94, 1, 'Arwos', 35.200001, 0.200000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(95, 1, 'Asa', 39.430000, -2.080000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(96, 1, 'Asawo', 35.000000, -0.330000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(97, 1, 'Asembo', 34.380001, -0.180000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(98, 1, 'Ashuwei', 41.320000, -1.930000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(99, 1, 'Assa', 39.430000, -2.080000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(100, 1, 'Assawa', 35.000000, -0.330000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(101, 1, 'Astra Farm', 37.099998, -1.700000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(102, 1, 'Athi', 38.070000, -2.270000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(103, 1, 'Athi New Bridge', 38.049999, -2.200000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(104, 1, 'Athi River', 36.980000, -1.450000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(105, 1, 'Athinai', 35.980000, -0.070000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(106, 1, 'Awach Tende', 34.619999, -0.480000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(107, 1, 'Awasi', 35.070000, -0.170000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(108, 1, 'Awino', 34.430000, -0.550000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(109, 1, 'Awundo''s Village', 34.580002, -0.630000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(110, 1, 'Awundo?s Village', 34.580002, -0.630000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(111, 1, 'Ayugis', 34.580002, -0.730000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(112, 1, 'Bahadale', 39.070000, -0.040000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(113, 1, 'Bahati', 36.849998, -1.280000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(114, 1, 'Bahati Settlement', 36.119999, -0.150000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(115, 1, 'Baixia', 36.330002, -0.680000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(116, 1, 'Bajumali', 41.130001, -2.080000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(117, 1, 'Bajumwali', 41.130001, -2.080000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(118, 1, 'Balguda', 39.849998, -1.470000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(119, 1, 'Bamba', 39.520000, -3.530000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(120, 1, 'Bamburi', 39.720001, -4.000000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(121, 1, 'Banda Ra Salama', 39.700001, -3.780000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(122, 1, 'Bandari', 39.529999, -3.470000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(123, 1, 'Bandari La Salaama', 39.700001, -3.780000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(124, 1, 'Bandari Ra Salama', 39.700001, -3.780000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(125, 1, 'Bandari la Salama', 39.700001, -3.780000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(126, 1, 'Banga', 39.230000, -4.100000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(127, 1, 'Bania', 39.630001, -3.920000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(128, 1, 'Banissa', 40.330002, 3.900000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(129, 1, 'Banya', 39.630001, -3.920000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(130, 1, 'Baomo', 40.130001, -1.930000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(131, 1, 'Bar Olengo', 34.200001, 0.000000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(132, 1, 'Bara Hoyo', 39.770000, -3.930000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(133, 1, 'Baragoi', 36.779999, 1.780000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(134, 1, 'Baragoli', 36.779999, 1.780000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(135, 1, 'Baragulu', 34.320000, 0.030000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(136, 1, 'Barane', 39.830002, -3.580000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(137, 1, 'Barani', 39.830002, -3.580000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(138, 1, 'Barata', 38.330002, 0.700000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(139, 1, 'Barchuma Guda', 38.220001, 1.380000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(140, 1, 'Bargoni', 40.799999, -2.050000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(141, 1, 'Baricho', 37.250000, -0.550000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(142, 1, 'Barigito', 36.869999, -0.930000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(143, 1, 'Barigoni', 40.799999, -2.050000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(144, 1, 'Baringo', 36.270000, 0.630000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(145, 1, 'Barisho', 37.250000, -0.550000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(146, 1, 'Barkowino', 34.270000, -0.120000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(147, 1, 'Baroseno', 34.279999, 0.110000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(148, 1, 'Bartabwa', 35.799999, 0.830000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(149, 1, 'Bartimaro', 36.770000, -2.300000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(150, 1, 'Bartolimo', 35.820000, 0.680000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(151, 1, 'Barwesa', 35.730000, 0.720000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(152, 1, 'Barwessa', 35.730000, 0.720000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(153, 1, 'Bata', 41.230000, -1.370000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(154, 1, 'Batani', 39.599998, -3.900000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(155, 1, 'Baure', 40.970001, -1.720000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(156, 1, 'Bavigito', 36.869999, -0.930000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(157, 1, 'Baymo', 40.130001, -1.930000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(158, 1, 'Bazo', 39.220001, -4.670000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(159, 1, 'Beacon Ranch', 36.930000, -1.400000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(160, 1, 'Beacon Ranch Limited', 36.930000, -1.400000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(161, 1, 'Belewa', 39.630001, -3.950000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(162, 1, 'Belle Vue', 36.720001, -0.230000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(163, 1, 'Benane', 38.669998, 0.500000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(164, 1, 'Bengoni', 39.430000, -3.630000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(165, 1, 'Benhard Estate', 36.770000, -1.270000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(166, 1, 'Benvar Farm', 37.020000, -1.080000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(167, 1, 'Bernhard Estate', 36.770000, -1.270000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(168, 1, 'Berti Finno', 41.439999, 3.370000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(169, 1, 'Besil', 36.779999, -2.100000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(170, 1, 'Bibitoli', 39.880001, -2.870000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(171, 1, 'Bili', 39.720001, -0.610000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(172, 1, 'Biretwo', 35.549999, 0.550000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(173, 1, 'Birithia', 36.950001, -0.520000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(174, 1, 'Bisil', 36.779999, -2.100000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(175, 1, 'Bissel', 36.779999, -2.100000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(176, 1, 'Blackscythe Farms', 35.930000, -0.080000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(177, 1, 'Blue Lodge', 37.020000, -0.080000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(178, 1, 'Bodhei', 40.730000, -1.870000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(179, 1, 'Bofu', 39.430000, -3.970000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(180, 1, 'Bohoni', 40.000000, -1.370000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(181, 1, 'Bokok', 36.180000, 0.130000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(182, 1, 'Bokoni', 40.000000, -1.370000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(183, 1, 'Bole', 39.700001, -0.550000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(184, 1, 'Boma', 37.330002, -0.670000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(185, 1, 'Boma Upande', 40.119999, -3.070000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(186, 1, 'Bomala', 34.200001, 0.300000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(187, 1, 'Bomani', 39.470001, -4.470000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(188, 1, 'Bomburia', 34.869999, -0.530000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(189, 1, 'Bomet', 35.349998, -0.780000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(190, 1, 'Bondo', 34.270000, 0.240000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(191, 1, 'Bongonoko', 39.799999, -0.770000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(192, 1, 'Bonyunyu', 34.880001, -0.520000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(193, 1, 'Bora Imani', 40.869999, -2.200000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(194, 1, 'Borana Farm', 37.299999, 0.270000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(195, 1, 'Boro', 34.230000, 0.090000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(196, 1, 'Borobini', 40.200001, -2.460000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(197, 1, 'Boron Farm', 35.599998, -0.400000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(198, 1, 'Broad Acres Farm', 36.369999, -0.870000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(199, 1, 'Broderick Falls', 34.770000, 0.620000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(200, 1, 'Bubesa', 40.169998, -2.050000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(201, 1, 'Buholo', 34.330002, 0.190000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(202, 1, 'Buji Albati', 39.820000, -2.950000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(203, 1, 'Bujwane', 34.029999, 0.120000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(204, 1, 'Bukangasi', 34.029999, 0.080000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(205, 1, 'Bukatwavi', 39.970001, -2.820000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(206, 1, 'Bukoma', 33.950001, 0.070000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(207, 1, 'Bulbul', 36.680000, -1.330000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(208, 1, 'Bulemia', 34.000000, 0.120000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(209, 1, 'Bulungo', 34.099998, 0.020000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(210, 1, 'Bulwani', 34.009998, 0.010000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(211, 1, 'Bumala', 34.200001, 0.300000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(212, 1, 'Bumburia', 34.869999, -0.530000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(213, 1, 'Bumudondo', 34.029999, 0.070000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(214, 1, 'Buna', 39.509998, 2.790000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(215, 1, 'Bunaba', 33.970001, 0.070000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(216, 1, 'Bungule', 38.669998, -3.850000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(217, 1, 'Bunyala', 33.970001, 0.100000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(218, 1, 'Bunyore', 34.619999, 0.080000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(219, 1, 'Bunyunya', 34.880001, -0.520000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(220, 1, 'Bur Mayo', 40.270000, 2.980000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(221, 1, 'Bura', 39.950001, -1.090000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(222, 1, 'Burgich', 35.080002, 1.970000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(223, 1, 'Burji', 39.070000, 3.520000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(224, 1, 'Burji Manyatta', 39.070000, 3.520000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(225, 1, 'Burti Fino', 41.439999, 3.370000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(226, 1, 'Busa', 39.070000, -4.130000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(227, 1, 'Busho', 39.070000, -3.900000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(228, 1, 'Busia', 34.099998, 0.470000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(229, 1, 'Busike', 33.990002, 0.030000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(230, 1, 'Busonga', 34.099998, 0.110000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(231, 1, 'Bute Helu', 39.849998, 2.830000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(232, 1, 'Butelu', 39.849998, 2.830000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(233, 1, 'Butere', 34.490002, 0.210000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(234, 1, 'Butsotse', 34.700001, 0.350000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(235, 1, 'Butsotso', 34.700001, 0.350000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(236, 1, 'Buttelu', 39.849998, 2.830000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(237, 1, 'Bwaga Cheti', 39.150002, -4.530000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(238, 1, 'Bwaga Macho', 39.150002, -4.530000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(239, 1, 'Bwagamoyo', 39.630001, -3.950000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(240, 1, 'By-Gum', 35.669998, -0.300000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(241, 1, 'Campi ya Bibi', 38.029999, -3.400000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(242, 1, 'Capsimotwa', 35.130001, 0.050000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(243, 1, 'Catante', 36.549999, 0.020000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(244, 1, 'Cedar Vale Farm', 37.099998, 0.130000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(245, 1, 'Ceronge', 36.970001, -0.450000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(246, 1, 'Chabia', 38.349998, -3.480000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(247, 1, 'Chala', 37.720001, -3.320000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(248, 1, 'Chamagel', 35.119999, -0.680000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(249, 1, 'Chamasiri', 34.389999, 0.740000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(250, 1, 'Chamwanamuma', 40.299999, -2.520000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(251, 1, 'Chanani', 40.049999, -1.550000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(252, 1, 'Changamatak', 35.650002, 2.630000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(253, 1, 'Changamwe', 39.630001, -4.020000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(254, 1, 'Chathoro', 40.270000, -2.500000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(255, 1, 'Chathuru', 40.270000, -2.500000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(256, 1, 'Chawia', 38.349998, -3.480000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(257, 1, 'Chebara', 35.500000, 0.880000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(258, 1, 'Cheberen', 35.830002, 0.230000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(259, 1, 'Cheberia', 35.200001, 1.320000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(260, 1, 'Chebiemit', 35.500000, 0.850000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(261, 1, 'Cheboin', 35.529999, 0.300000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(262, 1, 'Chegeini', 36.919998, -1.050000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(263, 1, 'Chehe', 37.220001, -0.400000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(264, 1, 'Chelebe', 34.549999, 0.800000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(265, 1, 'Chelelemuk', 34.360001, 0.690000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(266, 1, 'Chemagal', 35.119999, -0.680000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(267, 1, 'Chemagel', 35.119999, -0.680000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(268, 1, 'Chemaner', 35.500000, -0.780000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(269, 1, 'Chemasiri', 34.389999, 0.740000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(270, 1, 'Chematu', 38.369999, -1.580000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(271, 1, 'Chemogoch', 35.930000, 0.020000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(272, 1, 'Chemoigut', 36.200001, 0.680000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(273, 1, 'Chemosiet', 35.169998, -0.470000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(274, 1, 'Chemosit', 35.169998, -0.470000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(275, 1, 'Chemosot', 35.169998, -0.500000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(276, 1, 'Chemosusu', 35.630001, 0.080000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(277, 1, 'Chemuswa', 35.119999, 0.350000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(278, 1, 'Chemuswo', 35.119999, 0.350000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(279, 1, 'Chenani', 40.049999, -1.550000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(280, 1, 'Chengo', 39.700001, -3.850000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(281, 1, 'Chengoni', 39.700001, -3.850000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(282, 1, 'Chepareria', 35.200001, 1.320000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(283, 1, 'Chepitet', 35.570000, -0.320000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(284, 1, 'Chepkesin', 35.880001, 0.880000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(285, 1, 'Chepkorio', 35.529999, 0.380000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(286, 1, 'Chepkum', 35.619999, 0.980000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(287, 1, 'Cheptonge', 35.500000, 0.930000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(288, 1, 'Chepunyal', 35.169998, 1.500000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(289, 1, 'Cheronge', 36.970001, -0.450000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(290, 1, 'Chesakaki', 34.500000, 0.800000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(291, 1, 'Chesoi', 35.599998, 1.070000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(292, 1, 'Chevani', 40.020000, -1.420000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(293, 1, 'Chewani', 40.020000, -1.420000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(294, 1, 'Chewele', 39.970001, -1.170000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(295, 1, 'Chiananda', 37.630001, -0.420000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(296, 1, 'Chiba', 37.330002, -0.680000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(297, 1, 'Chidutani', 39.720001, -3.770000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(298, 1, 'Chieko', 36.630001, -1.150000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(299, 1, 'Chiffiri', 39.720001, -1.300000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(300, 1, 'Chifiri', 39.720001, -1.300000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(301, 1, 'Chingwede', 39.450001, -4.500000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(302, 1, 'Chiokarige', 37.930000, -0.270000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(303, 1, 'Chiromo', 36.799999, -1.270000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(304, 1, 'Chirte Hiyasa', 39.650002, -2.120000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(305, 1, 'Chirte Hiyesa', 39.650002, -2.120000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(306, 1, 'Choba', 38.049999, 2.430000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(307, 1, 'Choke', 38.380001, -3.370000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(308, 1, 'Chololo', 36.950001, 0.400000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(309, 1, 'Chomboni', 37.419998, -1.980000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(310, 1, 'Chomo', 36.880001, -0.880000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(311, 1, 'Chonye', 39.680000, -3.780000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(312, 1, 'Chonyi', 39.680000, -3.780000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(313, 1, 'Chororget', 35.580002, 0.400000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(314, 1, 'Chui', 36.270000, 0.120000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(315, 1, 'Chuka', 37.650002, -0.330000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(316, 1, 'Chundwa', 41.119999, -2.080000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(317, 1, 'Chura', 36.700001, -1.220000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(318, 1, 'Cimbria Farm', 35.900002, -0.180000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(319, 1, 'City Estate', 36.770000, -1.280000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(320, 1, 'Clema Dui', 39.529999, -1.600000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(321, 1, 'Cokereria', 36.349998, -0.430000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(322, 1, 'Colbio', 41.220001, -1.150000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(323, 1, 'Como Farm', 37.049999, -1.050000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(324, 1, 'Coryndon Farm', 36.700001, -1.230000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(325, 1, 'Cyrondon Farm', 36.700001, -1.230000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(326, 1, 'Daba', 39.450001, -1.570000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(327, 1, 'Dabel', 39.270000, 3.090000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(328, 1, 'Dabida', 38.299999, -3.420000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(329, 1, 'Dad Dogh', 40.779999, 0.770000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(330, 1, 'Dagamra', 39.930000, -3.180000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(331, 1, 'Dagoretti', 36.770000, -1.300000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(332, 1, 'Dagoretti Corner', 36.770000, -1.300000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(333, 1, 'Dakacha', 39.799999, -3.020000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(334, 1, 'Dakatcha', 39.799999, -3.020000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(335, 1, 'Dakawachu', 39.630001, -2.690000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(336, 1, 'Dalalekutok', 36.779999, -1.780000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(337, 1, 'Dambale', 39.270000, -3.700000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(338, 1, 'Dandora', 36.900002, -1.250000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(339, 1, 'Dar es Salaam', 41.560001, -1.660000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(340, 1, 'Dar es Salam', 41.560001, -1.660000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(341, 1, 'Daraja Farm', 35.950001, -0.050000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(342, 1, 'Darajani', 38.119999, -2.580000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(343, 1, 'Darasha ya Mawe', 35.680000, -0.430000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(344, 1, 'Dardesa', 40.900002, -0.930000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(345, 1, 'Dawsonville', 36.049999, -0.280000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(346, 1, 'Debani', 34.000000, 0.100000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(347, 1, 'Delgany Farm', 35.070000, 1.150000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(348, 1, 'Dembwa', 38.369999, -3.450000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(349, 1, 'Denge', 39.150002, -4.070000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(350, 1, 'Dhika', 37.080002, -1.050000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(351, 1, 'Dianga', 34.529999, -0.930000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(352, 1, 'Dida', 39.799999, -3.430000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(353, 1, 'Dida Waredi', 40.410000, -2.420000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(354, 1, 'Dif', 40.959999, 1.000000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(355, 1, 'Difatha', 37.400002, -0.550000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(356, 1, 'Digira', 40.849998, -1.100000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(357, 1, 'Digo', 39.180000, -4.230000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(358, 1, 'Dindiri', 39.799999, -3.730000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(359, 1, 'Dobel', 39.270000, 3.090000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(360, 1, 'Dodori', 41.070000, -1.900000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(361, 1, 'Dol Dol', 37.169998, 0.400000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(362, 1, 'Dololo', 39.099998, -3.850000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(363, 1, 'Don Dol', 37.169998, 0.400000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(364, 1, 'Dondori', 36.270000, -0.200000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(365, 1, 'Dondueni', 37.330002, -0.430000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(366, 1, 'Doonholm', 36.869999, -1.280000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(367, 1, 'Dowsonville', 36.049999, -0.280000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(368, 1, 'Dudi', 34.580002, -0.800000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(369, 1, 'Dueadera', 40.099998, -1.120000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(370, 1, 'Duldul', 40.730000, -1.920000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(371, 1, 'Dulukiza', 39.630001, -3.380000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(372, 1, 'Dumi', 40.130001, -2.250000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(373, 1, 'Dunan Farm', 35.419998, 0.220000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(374, 1, 'Dundani', 39.200001, -4.080000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(375, 1, 'Dundori', 36.270000, -0.200000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(376, 1, 'Dungicha', 39.619999, -3.480000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(377, 1, 'Duruma', 39.080002, -4.550000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(378, 1, 'Dwa', 38.020000, -2.400000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(379, 1, 'Dzirive', 39.169998, -4.580000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(380, 1, 'Dzitsuhe', 39.970001, -2.870000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(381, 1, 'Eburemia', 34.049999, 0.120000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(382, 1, 'Ebusonga', 34.099998, 0.110000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(383, 1, 'Egge', 40.119999, -2.250000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(384, 1, 'Eil Wak', 40.939999, 2.810000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(385, 1, 'Ekarakara', 37.470001, -0.970000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(386, 1, 'Ekaru', 36.700001, -1.200000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(387, 1, 'El Ben', 40.200001, 2.320000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(388, 1, 'El Dera', 38.849998, 0.600000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(389, 1, 'El Jera', 39.599998, -1.670000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(390, 1, 'El Karama Ranch', 36.930000, 0.220000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(391, 1, 'El Sadi', 40.060001, 1.750000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(392, 1, 'El Wak', 40.939999, 2.810000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(393, 1, 'Ela', 40.779999, -1.170000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(394, 1, 'Elaa', 40.779999, -1.170000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(395, 1, 'Elangata Wuas', 36.580002, -1.880000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(396, 1, 'Elbulbul', 36.680000, -1.330000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(397, 1, 'Elburgon', 35.820000, -0.300000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(398, 1, 'Eldama', 35.720001, 0.050000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(399, 1, 'Eldama Ravine', 35.720001, 0.050000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(400, 1, 'Elisha', 34.529999, -0.920000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(401, 1, 'Elkarama', 36.930000, 0.220000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(402, 1, 'Elmenteita', 36.150002, -0.480000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(403, 1, 'Emali', 37.470001, -2.080000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(404, 1, 'Emarangishu', 36.119999, 0.030000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(405, 1, 'Emasatsi', 34.650002, 0.150000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(406, 1, 'Embakasi', 36.919998, -1.300000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(407, 1, 'Embori Farm', 37.330002, 0.070000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(408, 1, 'Embu', 37.450001, -0.530000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(409, 1, 'Emdin', 35.250000, 0.200000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(410, 1, 'Emening', 35.880001, 0.150000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(411, 1, 'Emgoshura Farm', 36.150002, -0.250000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(412, 1, 'Emining', 35.880001, 0.150000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(413, 1, 'Emmaloba', 34.570000, 0.030000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(414, 1, 'Empakasi', 36.919998, -1.300000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(415, 1, 'Empuyiankat', 36.980000, -1.600000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(416, 1, 'Ena', 37.549999, -0.480000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(417, 1, 'Enangiperi', 35.900002, -0.680000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(418, 1, 'Endarasha', 35.779999, -0.280000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(419, 1, 'Endebess', 34.849998, 1.070000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(420, 1, 'Engoshura Farm', 36.150002, -0.250000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(421, 1, 'Enguli', 37.369999, -1.930000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(422, 1, 'Enkorika', 36.919998, -1.970000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(423, 1, 'Entasekera', 35.849998, -1.850000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(424, 1, 'Entesekera', 35.849998, -1.850000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(425, 1, 'Entontol', 36.020000, -0.880000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(426, 1, 'Enzai', 37.320000, -1.830000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(427, 1, 'Eoret Narasha', 35.919998, -1.100000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(428, 1, 'Erkarkar', 37.500000, -2.200000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(429, 1, 'Esageri', 35.820000, 0.020000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(430, 1, 'Esambu Keke', 36.720001, -1.930000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(431, 1, 'Escarpment', 36.619999, -1.020000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(432, 1, 'Fafsula', 40.020000, -1.150000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(433, 1, 'Fanjua', 40.119999, -1.670000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(434, 1, 'Faza', 41.110001, -2.060000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(435, 1, 'Figini', 38.380001, -3.380000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(436, 1, 'Finno', 41.439999, 3.370000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(437, 1, 'Fitina', 40.279999, -2.500000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(438, 1, 'Fort Hall', 37.150002, -0.720000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(439, 1, 'Fort Harrington', 39.070000, 3.520000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(440, 1, 'Fort Ternan', 35.349998, -0.200000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(441, 1, 'Frere Town', 39.700001, -4.020000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(442, 1, 'Fundi Isa', 40.099998, -2.940000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(443, 1, 'Fundisa', 40.099998, -2.900000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(444, 1, 'Fundisa Kibaoni', 40.139999, -2.940000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(445, 1, 'Funi', 39.900002, -1.000000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(446, 1, 'Funi Dela', 39.900002, -1.000000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(447, 1, 'Funzi', 39.430000, -4.570000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(448, 1, 'Furaha', 40.200001, -2.480000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(449, 1, 'Furaka', 40.200001, -2.480000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(450, 1, 'Gacarage', 36.750000, -0.930000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(451, 1, 'Gacaraigu', 37.020000, -0.700000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(452, 1, 'Gacharage', 36.970001, -0.850000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(453, 1, 'Gacharageini', 36.880001, -0.630000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(454, 1, 'Gacharaigu', 37.020000, -0.700000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(455, 1, 'Gacharu', 37.200001, -0.620000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(456, 1, 'Gachatha', 36.950001, -0.480000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(457, 1, 'Gachege', 36.799999, -0.900000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(458, 1, 'Gachichi', 36.919998, -0.570000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(459, 1, 'Gachie', 36.779999, -1.220000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(460, 1, 'Gachika', 36.770000, -0.970000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(461, 1, 'Gachirero', 37.049999, -0.550000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(462, 1, 'Gachocho', 36.980000, -0.780000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(463, 1, 'Gachoiri', 36.730000, -0.980000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(464, 1, 'Gachoka', 37.529999, -0.620000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(465, 1, 'Gachugi', 36.919998, -0.950000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(466, 1, 'Gachuku', 37.119999, -0.530000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(467, 1, 'Gacogu', 37.419998, -0.720000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(468, 1, 'Gacoiri', 36.730000, -0.980000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(469, 1, 'Gacuku', 37.119999, -0.530000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(470, 1, 'Gaichanjiro', 37.049999, -0.880000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(471, 1, 'Gaichanjiru', 37.049999, -0.880000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(472, 1, 'Gaikundo', 37.000000, -0.530000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(473, 1, 'Gaikuyu', 37.130001, -0.400000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(474, 1, 'Gaitega', 37.130001, -0.730000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(475, 1, 'Gaithece', 36.900002, -1.000000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(476, 1, 'Gaithege', 36.900002, -1.000000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(477, 1, 'Gakanga', 36.830002, -0.430000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(478, 1, 'Gakarara', 37.000000, -0.900000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(479, 1, 'Gakee', 36.799999, -0.880000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(480, 1, 'Gakira', 36.970001, -0.680000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(481, 1, 'Gakoe', 36.799999, -0.880000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(482, 1, 'Gakoi', 37.070000, -0.670000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(483, 1, 'Gakui', 36.919998, -0.930000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(484, 1, 'Gakuo', 37.330002, -0.570000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(485, 1, 'Gakurue', 37.020000, -0.630000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(486, 1, 'Gakuruwe', 37.020000, -0.630000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(487, 1, 'Gakurwe', 37.020000, -0.630000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(488, 1, 'Gakuu', 37.330002, -0.570000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(489, 1, 'Gakuyu', 37.049999, -0.650000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(490, 1, 'Gakwegore', 37.580002, -0.420000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(491, 1, 'Galafahi', 34.000000, 0.100000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(492, 1, 'Galamani', 40.009998, -1.400000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(493, 1, 'Galana Lafa Bade', 40.830002, 4.250000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(494, 1, 'Galanema', 39.700001, -3.720000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(495, 1, 'Galimani', 40.009998, -1.400000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(496, 1, 'Galole', 40.029999, -1.500000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(497, 1, 'Galu', 39.570000, -4.350000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(498, 1, 'Ganda', 40.070000, -3.220000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(499, 1, 'Gandini', 39.470001, -4.030000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(500, 1, 'Ganze', 39.680000, -3.530000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(501, 1, 'Ganzi', 39.680000, -3.530000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(502, 1, 'Ganzoni', 39.630001, -3.920000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(503, 1, 'Garashi', 39.880001, -3.120000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(504, 1, 'Garba', 37.599998, 0.380000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(505, 1, 'Garba Tula', 38.520000, 0.530000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(506, 1, 'Gariba', 37.599998, 0.380000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(507, 1, 'Garissa', 39.660000, -0.460000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(508, 1, 'Garsen', 40.119999, -2.270000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(509, 1, 'Gasi', 39.500000, -4.420000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(510, 1, 'Gatakaini', 36.779999, -0.830000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(511, 1, 'Gatakani', 36.779999, -0.830000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(512, 1, 'Gatamaiy', 36.730000, -1.000000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(513, 1, 'Gatamaiyu', 36.730000, -1.000000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(514, 1, 'Gatamayu', 36.730000, -1.000000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(515, 1, 'Gatanga', 36.970001, -0.930000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(516, 1, 'Gatangara', 36.950001, -0.720000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(517, 1, 'Gatare', 36.770000, -0.730000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(518, 1, 'Gategi', 37.419998, -0.750000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(519, 1, 'Gatei', 36.900002, -0.980000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(520, 1, 'Gateiguru', 36.880001, -0.870000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(521, 1, 'Gathage', 36.900002, -1.030000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(522, 1, 'Gathagi', 36.919998, -1.050000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(523, 1, 'Gathairu', 37.029999, -0.770000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(524, 1, 'Gathaithi', 36.919998, -0.750000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(525, 1, 'Gathalni Farm', 36.970001, -1.230000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(526, 1, 'Gathambi', 37.200001, -0.470000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(527, 1, 'Gathanga', 36.799999, -1.180000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(528, 1, 'Gathangwi', 36.950001, -0.970000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(529, 1, 'Gathanje', 36.779999, -1.080000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(530, 1, 'Gathanji', 37.020000, -0.530000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(531, 1, 'Gatharaini', 36.919998, -1.220000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(532, 1, 'Gathegia', 36.799999, -1.020000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(533, 1, 'Gathehu', 37.150002, -0.420000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(534, 1, 'Gathera', 37.029999, -0.800000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(535, 1, 'Gatheru', 37.070000, -0.730000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(536, 1, 'Gathiga', 36.750000, -1.220000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(537, 1, 'Gathigio', 37.070000, -0.800000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(538, 1, 'Gathigiriri', 37.400002, -0.680000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(539, 1, 'Gathima', 36.750000, -1.030000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(540, 1, 'Gathinga', 37.020000, -0.500000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(541, 1, 'Gathinja', 37.020000, -0.720000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(542, 1, 'Gathiriga', 36.730000, -0.970000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(543, 1, 'Gathiru', 37.029999, -0.770000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(544, 1, 'Gathiruini', 36.820000, -1.030000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(545, 1, 'Gathithina', 36.680000, -1.220000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(546, 1, 'Gathuga', 36.799999, -1.000000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(547, 1, 'Gathugu', 36.799999, -1.020000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(548, 1, 'Gathukiini', 37.049999, -0.630000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(549, 1, 'Gathumbi', 36.900002, -0.530000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(550, 1, 'Gathundia', 36.279999, -0.250000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(551, 1, 'Gathungururu', 37.020000, -0.550000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(552, 1, 'Gathuthi', 36.880001, -0.480000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(553, 1, 'Gathuthuma', 37.220001, -0.430000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(554, 1, 'Gatiabai', 36.669998, -1.230000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(555, 1, 'Gatiaini', 36.830002, -0.780000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(556, 1, 'Gatiani', 36.830002, -0.780000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(557, 1, 'Gatiguru', 36.880001, -1.070000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(558, 1, 'Gatimu', 36.650002, -1.170000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(559, 1, 'Gatina', 37.130001, -0.550000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(560, 1, 'Gatissa', 37.180000, -0.580000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(561, 1, 'Gatithi', 37.200001, -0.580000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(562, 1, 'Gatitu', 37.000000, -0.450000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(563, 1, 'Gatitu B', 36.849998, -1.030000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(564, 1, 'Gatuanibu', 36.669998, -1.200000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(565, 1, 'Gatugi One', 36.970001, -0.520000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(566, 1, 'Gatugi Two', 36.970001, -0.530000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(567, 1, 'Gatukuyu', 36.970001, -0.980000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(568, 1, 'Gatumbi', 36.970001, -0.780000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(569, 1, 'Gatumbiro', 36.869999, -0.470000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(570, 1, 'Gatumbiru', 36.869999, -0.470000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(571, 1, 'Gatundori', 37.480000, -0.480000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(572, 1, 'Gatundu', 36.919998, -0.570000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(573, 1, 'Gatunduri', 37.480000, -0.480000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(574, 1, 'Gatunga', 38.020000, -0.100000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(575, 1, 'Gatunganga', 37.049999, -0.420000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(576, 1, 'Gatunguru', 36.830002, -0.900000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(577, 1, 'Gatura', 37.029999, -0.530000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(578, 1, 'Gaturi', 36.849998, -0.450000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(579, 1, 'Gaturiri', 37.119999, -0.500000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(580, 1, 'Gatuto', 37.299999, -0.570000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(581, 1, 'Gatuya', 37.220001, -0.530000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(582, 1, 'Gatuyaini', 36.930000, -0.550000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(583, 1, 'Gatwamba', 36.700001, -1.020000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(584, 1, 'Gatwe', 37.230000, -0.430000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(585, 1, 'Gaze', 39.500000, -4.420000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(586, 1, 'Gazi', 39.500000, -4.420000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(587, 1, 'Gede', 40.020000, -3.300000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(588, 1, 'Gedi', 40.020000, -3.300000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(589, 1, 'Geitwa', 37.080002, -0.630000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(590, 1, 'Gekandu', 37.099998, -0.720000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(591, 1, 'Gekondi', 37.029999, -0.570000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(592, 1, 'Gekuuri', 37.549999, -0.450000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(593, 1, 'Gethemu', 37.520000, -0.500000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(594, 1, 'Ghalamani', 40.009998, -1.400000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(595, 1, 'Giachamwengi', 36.950001, -0.450000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(596, 1, 'Giachumi', 36.779999, -1.070000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(597, 1, 'Giagatika', 37.080002, -0.480000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(598, 1, 'Giagithu', 36.849998, -1.100000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(599, 1, 'Giaitu', 37.099998, -0.480000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0);
INSERT INTO `loctowns` (`idloctowns`, `loccountry_idloccountry`, `locationname`, `lng`, `lat`, `createdby`, `createdon`, `validatedby`, `validatedon`, `is_valid`, `is_town`) VALUES
(600, 1, 'Giakaibii', 37.150002, -0.420000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(601, 1, 'Giakibii', 37.150002, -0.420000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(602, 1, 'Giathenge', 36.980000, -0.520000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(603, 1, 'Giathieko', 36.820000, -1.080000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(604, 1, 'Giathugu One', 37.099998, -0.580000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(605, 1, 'Giathugu Two', 37.080002, -0.580000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(606, 1, 'Gichage', 35.849998, -0.330000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(607, 1, 'Gichagiini', 36.900002, -0.800000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(608, 1, 'Gicharani', 36.650002, -1.270000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(609, 1, 'Gicheha', 36.919998, -1.020000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(610, 1, 'Gichera', 37.599998, -0.450000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(611, 1, 'Gicheru', 36.549999, -1.200000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(612, 1, 'Gichiche', 37.580002, -0.430000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(613, 1, 'Gichiengo', 36.619999, -0.950000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(614, 1, 'Gichira', 37.020000, -0.470000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(615, 1, 'Gichocho', 36.820000, -1.150000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(616, 1, 'Gichongo', 36.680000, -1.270000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(617, 1, 'Gichoto', 36.650002, -1.280000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(618, 1, 'Gichugu', 37.099998, -0.820000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(619, 1, 'Gichuru', 36.669998, -1.170000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(620, 1, 'Gifyonzo', 39.250000, -4.200000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(621, 1, 'Gihigaini', 37.080002, -0.780000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(622, 1, 'Gikambura', 36.630001, -1.280000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(623, 1, 'Gikandu', 37.099998, -0.720000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(624, 1, 'Gikarmora', 37.029999, -0.820000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(625, 1, 'Gikaru', 36.700001, -1.200000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(626, 1, 'Gikigie', 36.830002, -0.730000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(627, 1, 'Gikoe', 36.930000, -0.630000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(628, 1, 'Gikoi', 36.930000, -0.630000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(629, 1, 'Gikomora', 37.029999, -0.820000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(630, 1, 'Gikondi', 37.029999, -0.570000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(631, 1, 'Gikui', 36.919998, -0.930000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(632, 1, 'Gikunguru', 37.049999, -0.780000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(633, 1, 'Gikure', 36.830002, -0.970000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(634, 1, 'Gikuu', 37.250000, -0.720000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(635, 1, 'Gikuyu', 36.669998, -1.250000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(636, 1, 'Gilgil', 36.270000, -0.220000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(637, 1, 'Giriama', 39.580002, -3.800000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(638, 1, 'Giriftu', 39.759998, 1.990000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(639, 1, 'Gitare', 37.549999, -0.400000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(640, 1, 'Gitaro', 37.070000, -0.730000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(641, 1, 'Gitaru', 36.680000, -1.230000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(642, 1, 'Gitathi-ini', 36.930000, -0.430000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(643, 1, 'Gitembe', 37.049999, -0.850000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(644, 1, 'Gitero', 36.930000, -0.450000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(645, 1, 'Githagara', 37.020000, -0.770000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(646, 1, 'Githagoya', 37.220001, -0.650000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(647, 1, 'Githakwa', 36.900002, -0.470000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(648, 1, 'Githamba', 36.820000, -1.070000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(649, 1, 'Githambo', 36.880001, -0.720000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(650, 1, 'Githanga', 37.070000, -0.620000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(651, 1, 'Githega', 36.700001, -1.070000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(652, 1, 'Githembe', 37.049999, -0.850000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(653, 1, 'Githerere', 36.869999, -0.480000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(654, 1, 'Githerioni', 36.650002, -1.020000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(655, 1, 'Githiga', 36.930000, -0.700000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(656, 1, 'Githiga A', 36.730000, -1.080000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(657, 1, 'Githiga B', 36.750000, -1.100000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(658, 1, 'Githigui', 36.919998, -1.050000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(659, 1, 'Githima', 36.349998, -0.370000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(660, 1, 'Githinga B', 36.750000, -1.080000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(661, 1, 'Githioro', 36.799999, -1.020000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(662, 1, 'Githioroni', 36.650002, -1.020000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(663, 1, 'Githirioni', 36.650002, -1.020000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(664, 1, 'Githiru', 37.020000, -0.450000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(665, 1, 'Githoito', 36.650002, -0.970000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(666, 1, 'Githugi', 36.869999, -0.520000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(667, 1, 'Githumu', 36.900002, -0.820000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(668, 1, 'Githunguri', 37.099998, -0.550000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(669, 1, 'Githunguru', 36.919998, -1.020000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(670, 1, 'Githuri', 37.200001, -0.720000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(671, 1, 'Githuva', 36.919998, -1.000000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(672, 1, 'Githuya', 36.919998, -1.000000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(673, 1, 'Gitige', 37.029999, -0.730000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(674, 1, 'Gitiha', 36.700001, -1.070000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(675, 1, 'Gitithia', 36.619999, -1.050000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(676, 1, 'Gititu', 37.029999, -0.500000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(677, 1, 'Gitombo', 36.830002, -1.050000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(678, 1, 'Gituamba', 36.230000, 0.030000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(679, 1, 'Gituandaga', 36.320000, 0.170000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(680, 1, 'Gituge', 37.020000, -0.650000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(681, 1, 'Gitugi', 36.869999, -0.520000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(682, 1, 'Gitugu', 36.930000, -0.680000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(683, 1, 'Gitui', 37.049999, -0.730000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(684, 1, 'Gitumbi', 37.270000, -0.450000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(685, 1, 'Gitura', 37.119999, -0.920000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(686, 1, 'Gituru', 36.880001, -0.820000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(687, 1, 'Gituto', 37.020000, -0.720000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(688, 1, 'Gitwamba', 36.849998, -0.900000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(689, 1, 'Gitwe', 36.820000, -0.950000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(690, 1, 'Gitweku', 36.970001, -0.700000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(691, 1, 'God''s Hill', 37.230000, -0.580000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(692, 1, 'Godoma', 39.279999, -4.400000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(693, 1, 'Godona', 39.279999, -4.400000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(694, 1, 'God?s Hill', 37.230000, -0.580000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(695, 1, 'Gogar Farm', 35.830002, -0.170000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(696, 1, 'Gogoni', 39.480000, -4.420000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(697, 1, 'Golbanti', 40.200001, -2.460000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(698, 1, 'Golbanti Mission', 40.200001, -2.460000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(699, 1, 'Golini', 39.470001, -4.150000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(700, 1, 'Golo', 39.619999, -3.930000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(701, 1, 'Gongoni', 39.480000, -4.420000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(702, 1, 'Gonja', 39.070000, -4.550000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(703, 1, 'Gorge Farm', 36.330002, -0.870000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(704, 1, 'Goroba', 35.650002, -0.630000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(705, 1, 'Gors', 34.730000, -0.570000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(706, 1, 'Goshi', 39.419998, -3.450000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(707, 1, 'Gotani', 39.529999, -3.780000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(708, 1, 'Greystone Farm', 37.169998, -1.020000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(709, 1, 'Habaswein', 39.490002, 1.010000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(710, 1, 'Habul', 40.919998, -0.520000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(711, 1, 'Hadu', 39.990002, -2.840000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(712, 1, 'Haluabagala', 39.669998, -2.850000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(713, 1, 'Haluabagalla', 39.669998, -2.850000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(714, 1, 'Handampia', 40.070000, -1.580000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(715, 1, 'Handarako', 39.349998, -1.780000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(716, 1, 'Handege', 36.869999, -1.000000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(717, 1, 'Hara Hurile', 40.270000, 4.050000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(718, 1, 'Harambee', 36.869999, -1.280000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(719, 1, 'Harefield Farm', 36.580002, -1.050000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(720, 1, 'Haridfi', 40.820000, -0.580000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(721, 1, 'Haruru', 40.180000, -1.320000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(722, 1, 'Hatha-ini', 36.900002, -0.500000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(723, 1, 'Hedaya Farm', 35.919998, -0.150000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(724, 1, 'Heho', 37.119999, -0.830000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(725, 1, 'Heni Village', 36.549999, -0.780000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(726, 1, 'Hewani', 40.189999, -2.240000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(727, 1, 'Hidio', 40.740002, -2.230000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(728, 1, 'Hidiyo', 40.740002, -2.230000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(729, 1, 'Highridge', 36.799999, -1.250000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(730, 1, 'Higlet', 40.320000, -1.070000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(731, 1, 'Hill Farm', 36.930000, -0.400000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(732, 1, 'Hindi', 40.830002, -2.180000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(733, 1, 'Hithe', 36.869999, -0.480000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(734, 1, 'Hoeys Bridge', 35.119999, 0.880000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(735, 1, 'Hola', 40.029999, -1.500000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(736, 1, 'Holmwood Farm', 36.669998, -1.150000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(737, 1, 'Homa', 34.450001, -0.520000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(738, 1, 'Homa Bay', 34.450001, -0.520000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(739, 1, 'Homa Lime Kowuor', 34.470001, -0.430000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(740, 1, 'Homa Line Owurs', 34.470001, -0.430000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(741, 1, 'Huguini', 36.849998, -0.480000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(742, 1, 'Huho-ini', 36.930000, -0.570000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(743, 1, 'Huhoini', 37.020000, -0.520000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(744, 1, 'Huhu-ini', 36.930000, -0.570000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(745, 1, 'Hunters Tree', 35.180000, 0.320000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(746, 1, 'Ianzoni', 37.320000, -1.680000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(747, 1, 'Icaci', 36.880001, -0.980000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(748, 1, 'Icaciri', 36.880001, -0.980000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(749, 1, 'Icagiciru', 36.869999, -0.450000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(750, 1, 'Ichachiri', 36.880001, -0.980000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(751, 1, 'Ichagak', 37.080002, -0.820000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(752, 1, 'Ichagaki', 37.080002, -0.820000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(753, 1, 'Ichagichiru', 36.869999, -0.450000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(754, 1, 'Ichamara', 37.080002, -0.550000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(755, 1, 'Ichichi', 36.849998, -0.700000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(756, 1, 'Idokho', 34.000000, 0.050000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(757, 1, 'Idsowe', 40.139999, -2.300000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(758, 1, 'Igikiro', 37.180000, -0.820000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(759, 1, 'Igria', 40.849998, -1.100000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(760, 1, 'Ihigaini', 36.980000, -0.680000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(761, 1, 'Ihinga', 37.000000, -0.770000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(762, 1, 'Ihithe', 36.869999, -0.480000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(763, 1, 'Ihua', 36.880001, -0.430000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(764, 1, 'Ihuririo', 36.880001, -0.550000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(765, 1, 'Ihururu', 36.880001, -0.420000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(766, 1, 'Ihurururu', 36.880001, -0.420000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(767, 1, 'Ihwagi', 37.150002, -0.450000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(768, 1, 'Iiuni', 37.330002, -1.680000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(769, 1, 'Ijara', 40.529999, -1.600000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(770, 1, 'Ikalaasa', 37.669998, -1.530000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(771, 1, 'Ikanga', 38.520000, -3.370000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(772, 1, 'Ikarakara', 37.470001, -0.970000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(773, 1, 'Ikinu', 36.799999, -1.100000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(774, 1, 'Ikonge', 35.020000, -0.530000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(775, 1, 'Ikoo', 38.169998, -1.180000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(776, 1, 'Ikuma', 36.869999, -1.000000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(777, 1, 'Ikumbi', 36.900002, -0.780000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(778, 1, 'Ikundu', 37.150002, -0.770000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(779, 1, 'Ikutha', 38.180000, -2.070000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(780, 1, 'Ilassit', 37.570000, -2.950000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(781, 1, 'Ilbisil', 36.779999, -2.100000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(782, 1, 'Ildarakwa', 36.520000, -0.070000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(783, 1, 'Ildolidol', 37.169998, 0.400000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(784, 1, 'Ilkabere', 41.049999, -0.960000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(785, 1, 'Ilpartimaro', 36.720001, -2.270000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(786, 1, 'Ilyagaleni', 36.700001, -1.750000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(787, 1, 'Imale', 38.279999, -2.150000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(788, 1, 'Iminuet', 35.480000, -0.850000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(789, 1, 'Ina', 37.549999, -0.480000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(790, 1, 'Inghi Farm', 37.279999, 0.080000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(791, 1, 'Ingile', 39.820000, -1.600000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(792, 1, 'Ingille', 39.820000, -1.600000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(793, 1, 'Inyokoni', 37.400002, -1.780000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(794, 1, 'Irangi', 39.480000, -0.350000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(795, 1, 'Iregi', 37.099998, -0.770000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(796, 1, 'Iriguini', 36.970001, -0.800000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(797, 1, 'Iruri', 37.080002, -0.350000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(798, 1, 'Ishiara', 37.779999, -0.450000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(799, 1, 'Isiolo', 37.580002, 0.350000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(800, 1, 'Island Farm', 36.029999, -0.430000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(801, 1, 'Issa', 35.820000, 0.720000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(802, 1, 'Issas', 35.820000, 0.720000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(803, 1, 'Isuvya', 37.270000, -1.550000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(804, 1, 'Itaga', 37.029999, -0.800000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(805, 1, 'Itetani', 37.380001, -1.580000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(806, 1, 'Ithaithi', 37.070000, -0.470000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(807, 1, 'Ithanji', 37.049999, -0.530000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(808, 1, 'Ithareni', 37.320000, -0.500000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(809, 1, 'Ithekahuno', 37.049999, -0.520000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(810, 1, 'Ithemboni', 37.450001, -1.650000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(811, 1, 'Ithenguri', 36.970001, -0.470000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(812, 1, 'Itheru', 37.119999, -0.800000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(813, 1, 'Ithima', 36.400002, 0.220000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(814, 1, 'Ithirameru', 36.779999, -0.950000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(815, 1, 'Ithoku', 38.570000, -1.830000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(816, 1, 'Ithumba', 38.400002, -2.200000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(817, 1, 'Itiati', 37.099998, -0.420000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(818, 1, 'Itugururu', 37.720001, -0.420000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(819, 1, 'Itundu', 37.180000, -0.420000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(820, 1, 'Ituru', 36.919998, -1.000000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(821, 1, 'Iuani', 37.520000, -1.730000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(822, 1, 'Iyego', 36.980000, -0.670000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(823, 1, 'Jaba Dimtu', 41.049999, 2.880000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(824, 1, 'Jaleny', 34.450001, -0.980000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(825, 1, 'Jaleny''s Village', 34.450001, -0.980000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(826, 1, 'Jaleny?s Village', 34.450001, -0.980000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(827, 1, 'Jambe', 39.169998, -4.600000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(828, 1, 'Jambole', 39.270000, -3.700000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(829, 1, 'Jamhuri Park', 36.770000, -1.300000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(830, 1, 'Jamji', 35.180000, -0.470000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(831, 1, 'Janoni', 38.520000, -0.570000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(832, 1, 'Jara', 39.810001, -0.710000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(833, 1, 'Jara Melka', 39.810001, -0.710000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(834, 1, 'Jarabuni', 39.730000, -3.630000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(835, 1, 'Jarabunyi', 39.730000, -3.630000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(836, 1, 'Jaribuni', 39.730000, -3.630000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(837, 1, 'Jego', 39.180000, -4.650000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(838, 1, 'Jera', 34.259998, 0.290000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(839, 1, 'Jeure', 39.779999, -3.870000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(840, 1, 'Jeuri', 39.779999, -3.870000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(841, 1, 'Jila', 39.529999, -3.420000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(842, 1, 'Jilore', 39.900002, -3.180000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(843, 1, 'Jilore Suore', 39.900002, -3.180000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(844, 1, 'Jipe', 40.880001, -2.170000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(845, 1, 'Jiweni', 39.180000, -4.250000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(846, 1, 'Jomvu', 39.619999, -3.980000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(847, 1, 'Jora', 38.650002, -3.830000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(848, 1, 'Josephs', 34.570000, -0.620000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(849, 1, 'Juga', 37.119999, -1.180000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(850, 1, 'Juja', 37.119999, -1.180000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(851, 1, 'Juja Farm', 37.119999, -1.180000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(852, 1, 'Jumba la Mtwana', 39.770000, -3.950000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(853, 1, 'Jumvu', 39.619999, -3.980000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(854, 1, 'Junda', 39.669998, -4.020000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(855, 1, 'Junja', 40.770000, -2.080000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(856, 1, 'Junju', 39.730000, -3.850000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(857, 1, 'Kaaga', 37.650002, 0.070000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(858, 1, 'Kaagogi', 36.900002, -0.430000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(859, 1, 'Kaani', 37.349998, -1.520000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(860, 1, 'Kaathene', 37.930000, 0.230000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(861, 1, 'Kabae', 37.320000, -0.520000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(862, 1, 'Kabage', 36.820000, -0.400000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(863, 1, 'Kabaldamet', 35.630001, 1.230000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(864, 1, 'Kabaloamet', 35.630001, 1.230000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(865, 1, 'Kabara', 37.320000, -0.520000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(866, 1, 'Kabare', 37.320000, -0.520000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(867, 1, 'Kabarnet', 35.750000, 0.500000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(868, 1, 'Kabartonjo', 35.799999, 0.630000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(869, 1, 'Kabartonjo Mission', 35.799999, 0.630000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(870, 1, 'Kabaru', 37.169998, -0.280000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(871, 1, 'Kabati', 37.099998, -0.950000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(872, 1, 'Kabebero', 36.869999, -0.550000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(873, 1, 'Kabete', 36.720001, -1.270000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(874, 1, 'Kabetwa', 35.630001, 1.150000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(875, 1, 'Kabiboni', 40.020000, -3.000000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(876, 1, 'Kabieni', 39.580002, -3.880000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(877, 1, 'Kabimoi', 35.779999, 0.020000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(878, 1, 'Kabisaga', 35.119999, 0.430000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(879, 1, 'Kabiyet', 35.080002, 0.400000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(880, 1, 'Kabluk', 35.650002, 0.570000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(881, 1, 'Kabochu', 36.680000, -1.180000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(882, 1, 'Kabola', 34.529999, -0.830000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(883, 1, 'Kabondo', 34.880001, -0.450000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(884, 1, 'Kabonge', 37.220001, -0.500000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(885, 1, 'Kabuku', 36.669998, -1.150000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(886, 1, 'Kabungwa', 35.150002, 0.220000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(887, 1, 'Kabuti', 37.330002, -0.430000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(888, 1, 'Kacheliba', 35.020000, 1.480000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(889, 1, 'Kadenge', 34.189999, 0.020000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(890, 1, 'Kadengi', 34.189999, 0.020000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(891, 1, 'Kadimu', 34.150002, -0.070000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(892, 1, 'Kadzeweni', 39.480000, -3.650000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(893, 1, 'Kadzinuni', 39.820000, -3.750000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(894, 1, 'Kaembekaesha', 40.099998, -3.070000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(895, 1, 'Kagaa', 36.700001, -1.030000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(896, 1, 'Kagaare', 37.570000, -0.430000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(897, 1, 'Kagaari', 37.570000, -0.430000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(898, 1, 'Kaganda', 36.950001, -0.750000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(899, 1, 'Kagarii', 37.020000, -0.570000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(900, 1, 'Kagarumo', 37.099998, -0.850000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(901, 1, 'Kagere', 36.950001, -0.500000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(902, 1, 'Kagia Farm', 36.650002, -1.170000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(903, 1, 'Kagicha', 36.900002, -0.600000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(904, 1, 'Kagio', 37.250000, -0.620000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(905, 1, 'Kagioini', 36.900002, -0.480000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(906, 1, 'Kagira', 37.029999, -0.850000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(907, 1, 'Kagondo', 36.700001, -1.270000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(908, 1, 'Kagondu', 36.869999, -0.770000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(909, 1, 'Kagongo', 36.830002, -1.130000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(910, 1, 'Kagonye', 36.919998, -0.520000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(911, 1, 'Kagumaini', 36.900002, -0.980000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(912, 1, 'Kagumo', 36.980000, -0.530000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(913, 1, 'Kagumoini', 36.980000, -0.620000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(914, 1, 'Kagundo', 36.880001, -0.450000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(915, 1, 'Kagundu', 36.880001, -0.450000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(916, 1, 'Kagundu-ini', 36.880001, -0.450000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(917, 1, 'Kagunduine', 37.049999, -0.900000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(918, 1, 'Kagunduini', 37.049999, -0.900000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(919, 1, 'Kagurumo', 37.099998, -0.850000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(920, 1, 'Kaguthi', 36.970001, -0.880000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(921, 1, 'Kagwathi', 36.919998, -0.470000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(922, 1, 'Kagwe', 36.730000, -1.000000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(923, 1, 'Kagwongo', 36.730000, -0.980000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(924, 1, 'Kahaaro', 36.919998, -0.750000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(925, 1, 'Kahaini', 37.169998, -0.850000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(926, 1, 'Kahara', 37.049999, -0.530000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(927, 1, 'Kaharati', 37.130001, -0.850000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(928, 1, 'Kaharo', 36.919998, -0.750000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(929, 1, 'Kahawa', 36.919998, -1.180000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(930, 1, 'Kahiga', 37.029999, -0.430000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(931, 1, 'Kahigaini', 36.869999, -0.400000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(932, 1, 'Kahithe', 36.980000, -0.730000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(933, 1, 'Kahuguini', 36.900002, -1.030000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(934, 1, 'Kahuhia', 37.049999, -0.720000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(935, 1, 'Kahuho', 36.680000, -1.220000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(936, 1, 'Kahunguini', 36.900002, -1.030000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(937, 1, 'Kahuro', 37.000000, -0.750000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(938, 1, 'Kahuti', 36.970001, -0.700000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(939, 1, 'Kaiboi', 35.049999, 0.420000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(940, 1, 'Kailembwa', 38.070000, -2.200000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(941, 1, 'Kaimba', 36.680000, -1.200000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(942, 1, 'Kaimu', 38.000000, -1.170000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(943, 1, 'Kairi', 36.830002, -1.080000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(944, 1, 'Kairi Ka Igamba', 36.880001, -0.920000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(945, 1, 'Kairi Mwihoti', 36.330002, -0.430000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(946, 1, 'Kairo', 36.869999, -0.600000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(947, 1, 'Kairori', 37.470001, -0.430000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(948, 1, 'Kairuri', 37.470001, -0.430000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(949, 1, 'Kairuthi', 36.880001, -0.530000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(950, 1, 'Kaitet Ranch', 35.099998, -0.770000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(951, 1, 'Kaitheri', 37.279999, -0.520000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(952, 1, 'Kaitui', 35.169998, -0.280000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(953, 1, 'Kaituiy', 35.169998, -0.280000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(954, 1, 'Kaiyaba', 37.080002, -0.470000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(955, 1, 'Kajiado', 36.779999, -1.850000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(956, 1, 'Kajinga', 36.680000, -1.200000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(957, 1, 'Kajire', 38.599998, -3.520000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(958, 1, 'Kakalia', 37.369999, -1.600000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(959, 1, 'Kakangani', 37.980000, -2.420000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(960, 1, 'Kakomani', 39.630001, -3.850000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(961, 1, 'Kakoneni', 39.869999, -3.170000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(962, 1, 'Kakuma', 34.869999, 3.720000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(963, 1, 'Kakunike', 38.349998, -0.770000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(964, 1, 'Kakunio', 38.169998, -1.920000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(965, 1, 'Kakuyuni', 40.000000, -3.220000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(966, 1, 'Kakya', 39.029999, -1.600000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(967, 1, 'Kalalu Farm', 37.169998, 0.070000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(968, 1, 'Kalaluwe', 39.740002, -2.980000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(969, 1, 'Kalamba', 37.520000, -1.870000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(970, 1, 'Kalandini', 34.369999, -0.170000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(971, 1, 'Kalasa', 37.669998, -1.530000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(972, 1, 'Kalawa', 38.020000, -1.370000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(973, 1, 'Kalawa Market', 37.700001, -1.650000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(974, 1, 'Kalema', 36.070000, -1.780000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(975, 1, 'Kalemma', 36.070000, -1.780000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(976, 1, 'Kali', 37.380001, -1.630000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(977, 1, 'Kalii', 37.770000, -2.100000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(978, 1, 'Kalimbui', 37.970001, -1.100000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(979, 1, 'Kalimoni', 37.020000, -1.100000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(980, 1, 'Kalitini', 38.250000, -0.980000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(981, 1, 'Kalivu', 38.099998, -2.020000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(982, 1, 'Kaloleni', 39.630001, -3.820000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(983, 1, 'Kalota', 40.369999, -2.520000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(984, 1, 'Kalulini', 37.980000, -2.350000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(985, 1, 'Kaluluini', 38.279999, -2.100000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(986, 1, 'Kalundu', 38.020000, -1.350000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(987, 1, 'Kamacharia', 37.000000, -0.600000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(988, 1, 'Kamae', 36.630001, -0.850000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(989, 1, 'Kamaende', 37.849998, -0.370000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(990, 1, 'Kamahia', 36.720001, -1.030000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(991, 1, 'Kamakoiwa', 34.779999, 0.780000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(992, 1, 'Kamakuywa', 34.779999, 0.780000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(993, 1, 'Kamale', 40.000000, -2.750000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(994, 1, 'Kamamut', 34.980000, 0.430000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(995, 1, 'Kamando', 36.770000, -1.100000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(996, 1, 'Kamandura', 36.630001, -1.130000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(997, 1, 'Kamara Farm', 35.680000, -0.100000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(998, 1, 'Kamara Farms', 35.680000, -0.100000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(999, 1, 'Kamarabuyon', 36.180000, 0.480000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1000, 1, 'Kamarr', 36.020000, 0.270000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1001, 1, 'Kamathuri', 37.230000, -0.570000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1002, 1, 'Kamatu', 37.099998, -0.500000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1003, 1, 'Kambaa', 36.650002, -0.980000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1004, 1, 'Kambara', 36.849998, -1.080000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1005, 1, 'Kamberua', 37.200001, -0.730000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1006, 1, 'Kambi ya Munyu', 37.919998, -2.320000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1007, 1, 'Kambu', 38.080002, -2.580000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1008, 1, 'Kamburu', 36.730000, -1.030000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1009, 1, 'Kameichiri', 37.299999, -0.650000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1010, 1, 'Kamira', 37.049999, -0.820000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1011, 1, 'Kamirithu', 36.630001, -1.120000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1012, 1, 'Kamisinga', 34.700001, 0.800000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1013, 1, 'Kamleza', 37.680000, -3.450000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1014, 1, 'Kamnunguuawa', 35.130001, 1.970000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1015, 1, 'Kamokabi', 36.930000, -0.770000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1016, 1, 'Kamondo', 36.730000, -1.100000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1017, 1, 'Kampi Ya Juu', 37.570000, 0.330000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1018, 1, 'Kampi Ya Samaki', 36.020000, 0.600000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1019, 1, 'Kampi Ya Sheikh Omar', 37.549999, 0.330000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1020, 1, 'Kampi ya Bibi', 38.029999, -3.400000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1021, 1, 'Kampi ya Kerengenzi', 40.130001, -2.920000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1022, 1, 'Kampi ya Kerenzeni', 40.130001, -2.920000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1023, 1, 'Kampi ya Njemi', 36.799999, -1.000000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1024, 1, 'Kampi ya Simba', 36.450001, 0.180000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1025, 1, 'Kampi-Ya-Chumvi', 37.630001, 0.430000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1026, 1, 'Kamucege', 36.779999, -1.030000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1027, 1, 'Kamuchege', 36.779999, -1.030000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1028, 1, 'Kamuchoni', 37.029999, -0.580000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1029, 1, 'Kamudi', 39.799999, -0.650000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1030, 1, 'Kamuga', 34.500000, -0.200000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1031, 1, 'Kamugeno', 35.119999, -0.820000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1032, 1, 'Kamuguga', 36.669998, -1.220000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1033, 1, 'Kamuiru', 37.230000, -0.480000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1034, 1, 'Kamukabi', 36.930000, -0.770000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1035, 1, 'Kamulu', 38.029999, -1.370000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1036, 1, 'Kamune', 37.020000, -0.600000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1037, 1, 'Kamunga', 37.080002, -0.870000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1038, 1, 'Kamungu', 37.080002, -0.870000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1039, 1, 'Kamunguuawa', 35.130001, 1.970000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1040, 1, 'Kamunyaka', 36.779999, -0.900000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1041, 1, 'Kamunyuini', 36.919998, -1.000000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1042, 1, 'Kamusinga', 34.700001, 0.800000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1043, 1, 'Kamuthwa', 37.650002, -1.520000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1044, 1, 'Kamuyu', 36.919998, -0.420000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1045, 1, 'Kamwangi', 36.900002, -0.950000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1046, 1, 'Kamwaura', 35.580002, -0.430000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1047, 1, 'Kamwenja', 36.919998, -0.400000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1048, 1, 'Kamweti', 37.320000, -0.330000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1049, 1, 'Kandara', 37.000000, -0.900000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1050, 1, 'Kandegenye', 37.020000, -0.750000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1051, 1, 'Kandogo', 36.869999, -1.050000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1052, 1, 'Kandolo', 37.369999, -1.970000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1053, 1, 'Kandongo', 37.279999, -0.650000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1054, 1, 'Kandongu', 37.279999, -0.650000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1055, 1, 'Kangai', 37.299999, -0.620000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1056, 1, 'Kangaita', 36.980000, -0.500000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1057, 1, 'Kangaita Farm', 37.130001, 0.070000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1058, 1, 'Kangare', 36.869999, -0.780000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1059, 1, 'Kangaru', 37.230000, -0.630000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1060, 1, 'Kangema', 36.970001, -0.680000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1061, 1, 'Kangenga', 37.099998, -0.780000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1062, 1, 'Kangeta', 37.880001, 0.280000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1063, 1, 'Kangethia', 37.549999, -0.470000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1064, 1, 'Kango', 38.119999, -0.470000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1065, 1, 'Kangocho', 37.169998, -0.520000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1066, 1, 'Kangongo', 37.330002, -0.500000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1067, 1, 'Kangoru', 37.049999, -0.530000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1068, 1, 'Kangoya', 36.820000, -1.170000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1069, 1, 'Kangundo', 37.369999, -1.350000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1070, 1, 'Kangundu', 37.349998, -1.300000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1071, 1, 'Kangunyi', 36.779999, -0.970000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1072, 1, 'Kangure', 37.130001, -0.670000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1073, 1, 'Kaningo', 38.529999, -0.820000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1074, 1, 'Kanja', 37.529999, -0.380000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1075, 1, 'Kanjai', 36.799999, -1.070000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1076, 1, 'Kanjonja', 40.130001, -1.760000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1077, 1, 'Kanjora', 36.880001, -0.400000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1078, 1, 'Kanjuki', 37.830002, -0.350000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1079, 1, 'Kanjuku', 36.880001, -0.950000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1080, 1, 'Kanunga', 36.880001, -0.830000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1081, 1, 'Kanvenyeni', 36.880001, -0.680000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1082, 1, 'Kanyama', 37.099998, -0.470000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1083, 1, 'Kanyanyeni', 36.880001, -0.680000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1084, 1, 'Kanyariri', 36.700001, -1.230000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1085, 1, 'Kanyarkwat', 34.919998, 1.270000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1086, 1, 'Kanyekine', 37.669998, -0.120000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1087, 1, 'Kanyekini', 37.669998, -0.120000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1088, 1, 'Kanyenyeini', 36.880001, -0.680000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1089, 1, 'Kanyinya', 36.849998, -0.420000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1090, 1, 'Kanyongo', 36.720001, -1.230000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1091, 1, 'Kanyongon', 36.720001, -1.230000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1092, 1, 'Kanyoni', 36.830002, -0.900000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1093, 1, 'Kanyore', 36.730000, -1.070000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1094, 1, 'Kanyuambora', 37.720001, -0.470000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1095, 1, 'Kanyuira', 36.900002, -0.650000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1096, 1, 'Kanywambora', 37.720001, -0.470000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1097, 1, 'Kaongo', 37.720001, -0.030000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1098, 1, 'Kap Sarok', 35.049999, -0.300000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1099, 1, 'Kap-Nguria', 35.119999, 1.230000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1100, 1, 'Kapchebelei', 35.570000, 0.280000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1101, 1, 'Kapchebelel', 35.570000, 0.280000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1102, 1, 'Kapchepkor', 35.799999, 0.620000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1103, 1, 'Kapchorewe', 35.529999, 0.180000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1104, 1, 'Kapedo', 36.099998, 1.170000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1105, 1, 'Kapenguria', 35.119999, 1.230000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1106, 1, 'Kapivet', 35.080002, 0.400000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1107, 1, 'Kapiyet', 35.080002, 0.400000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1108, 1, 'Kapkaim', 35.369999, -0.280000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1109, 1, 'Kapkalelwa', 35.720001, 0.370000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1110, 1, 'Kapkiam', 35.369999, -0.280000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1111, 1, 'Kapsabet', 35.099998, 0.200000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1112, 1, 'Kapsakwany', 34.720001, 0.850000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1113, 1, 'Kapsakwony', 34.720001, 0.850000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1114, 1, 'Kapsamonget', 35.220001, -0.320000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1115, 1, 'Kapsaos', 35.349998, -0.320000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1116, 1, 'Kapsaus', 35.349998, -0.320000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1117, 1, 'Kapsigak', 35.130001, 0.130000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1118, 1, 'Kapsimotwa Tea Farm', 35.130001, 0.050000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1119, 1, 'Kapsoit', 35.220001, -0.320000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1120, 1, 'Kapsowar', 35.570000, 0.980000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1121, 1, 'Kapsuser', 35.220001, -0.400000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1122, 1, 'Kapsuserr', 35.220001, -0.400000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1123, 1, 'Kaptama', 34.770000, 0.880000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1124, 1, 'Kaptarakwa', 35.529999, 0.470000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1125, 1, 'Kapteren', 35.480000, 0.570000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1126, 1, 'Kaption', 35.730000, 0.700000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1127, 1, 'Kaptumo', 35.070000, 0.070000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1128, 1, 'Kapturwo', 35.669998, 0.630000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1129, 1, 'Kapuset', 35.220001, -0.400000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1130, 1, 'Karaba', 37.099998, -0.630000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1131, 1, 'Karaba Special Camp', 37.430000, -0.730000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1132, 1, 'Karadolo', 34.130001, 0.250000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1133, 1, 'Karai', 36.500000, 0.020000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1134, 1, 'Karaine', 37.250000, -0.450000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1135, 1, 'Karamoja Farm', 34.869999, 1.020000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1136, 1, 'Karangi', 36.799999, -0.830000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1137, 1, 'Karatina', 36.770000, -1.000000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1138, 1, 'Karawa', 40.209999, -2.650000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1139, 1, 'Karembu', 36.900002, -1.030000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1140, 1, 'Karenge', 36.779999, -1.020000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1141, 1, 'Karere', 39.560001, -0.330000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1142, 1, 'Karero', 36.700001, -1.920000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1143, 1, 'Kareti', 37.020000, -0.870000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1144, 1, 'Kargi', 37.570000, 2.520000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1145, 1, 'Karia', 37.380001, -0.430000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1146, 1, 'Kariaini', 36.980000, -0.820000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1147, 1, 'Kariandus', 36.299999, -0.420000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1148, 1, 'Kariani', 36.980000, -0.820000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1149, 1, 'Kariara', 36.980000, -0.730000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1150, 1, 'Kariguini', 36.950001, -0.480000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1151, 1, 'Kariko', 36.880001, -0.570000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1152, 1, 'Kariku', 36.919998, -0.950000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1153, 1, 'Karima', 37.330002, -0.680000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1154, 1, 'Karimamwaro', 37.020000, -0.880000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1155, 1, 'Karimani', 39.450001, -3.420000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1156, 1, 'Karinga', 36.830002, -0.950000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1157, 1, 'Karingaini', 37.070000, -0.530000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1158, 1, 'Karingare', 37.500000, -0.470000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1159, 1, 'Karingari', 37.500000, -0.470000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1160, 1, 'Kariobangi', 36.880001, -1.250000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1161, 1, 'Kariobangi South', 36.880001, -1.270000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1162, 1, 'Karirau', 37.000000, -0.670000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1163, 1, 'Kariru', 37.369999, -0.470000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1164, 1, 'Karisa', 37.270000, -0.480000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1165, 1, 'Kariua', 36.970001, -0.830000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1166, 1, 'Kariura', 36.970001, -0.830000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1167, 1, 'Kariuwa', 36.970001, -0.830000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1168, 1, 'Karmale', 40.000000, -2.750000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1169, 1, 'Karogoto', 37.099998, -0.500000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1170, 1, 'Karpeddo', 36.099998, 1.170000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1171, 1, 'Karpedo', 36.099998, 1.170000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1172, 1, 'Karrira', 37.049999, -0.820000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1173, 1, 'Karua', 37.180000, -0.800000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1174, 1, 'Karuamgi', 37.450001, -0.550000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1175, 1, 'Karuari', 37.720001, -0.500000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1176, 1, 'Karugia', 37.119999, -0.900000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1177, 1, 'Karugutu', 36.830002, -0.880000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1178, 1, 'Karugya', 37.119999, -0.900000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0);
INSERT INTO `loctowns` (`idloctowns`, `loccountry_idloccountry`, `locationname`, `lng`, `lat`, `createdby`, `createdon`, `validatedby`, `validatedon`, `is_valid`, `is_town`) VALUES
(1179, 1, 'Karuiro', 37.119999, -0.620000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1180, 1, 'Karumandi', 37.349998, -0.450000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1181, 1, 'Karuna', 35.450001, 0.730000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1182, 1, 'Karundu', 37.080002, -0.570000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1183, 1, 'Karunga', 34.150002, -0.850000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1184, 1, 'Karunge', 36.980000, -0.650000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1185, 1, 'Karungu', 34.150002, -0.850000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1186, 1, 'Karungu Station', 34.150002, -0.850000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1187, 1, 'Karura', 36.770000, -1.220000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1188, 1, 'Karura Farm', 36.849998, -1.230000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1189, 1, 'Karura Kanyungu', 36.730000, -1.200000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1190, 1, 'Karuri', 36.919998, -0.970000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1191, 1, 'Karuriri', 36.919998, -0.970000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1192, 1, 'Karuris', 36.830002, -0.670000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1193, 1, 'Karuru One', 37.049999, -0.730000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1194, 1, 'Karuru Two', 37.029999, -0.730000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1195, 1, 'Karuruma', 36.830002, -0.680000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1196, 1, 'Karurumo', 36.830002, -0.680000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1197, 1, 'Karurumu', 37.650002, -0.470000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1198, 1, 'Karweti', 36.820000, -1.050000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1199, 1, 'Kasafari', 37.700001, -0.480000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1200, 1, 'Kasaini', 37.270000, -1.450000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1201, 1, 'Kasala', 38.270000, -2.130000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1202, 1, 'Kasei', 35.200001, 1.970000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1203, 1, 'Kashani', 39.700001, -3.950000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1204, 1, 'Kasidi', 39.599998, -3.880000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1205, 1, 'Kasikeu', 37.380001, -1.950000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1206, 1, 'Kasikiu', 37.380001, -1.950000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1207, 1, 'Kasilia', 38.230000, -1.920000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1208, 1, 'Kasiokoni', 38.680000, -1.730000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1209, 1, 'Kasioni', 38.020000, -0.550000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1210, 1, 'Kasisit', 35.830002, 0.630000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1211, 1, 'Kassarani', 36.919998, -1.220000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1212, 1, 'Katalembo', 37.200001, -1.520000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1213, 1, 'Katelembu', 37.200001, -1.520000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1214, 1, 'Katene', 38.430000, -1.750000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1215, 1, 'Kathaiya', 37.330002, -0.500000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1216, 1, 'Kathaiyia', 37.330002, -0.500000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1217, 1, 'Kathangarari', 37.450001, -0.400000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1218, 1, 'Kathangare', 37.450001, -0.470000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1219, 1, 'Kathangari', 37.450001, -0.470000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1220, 1, 'Kathangariri', 37.450001, -0.400000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1221, 1, 'Kathanjure', 37.599998, -0.430000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1222, 1, 'Kathari', 37.599998, -0.400000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1223, 1, 'Katheka', 37.900002, -1.200000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1224, 1, 'Kathekani', 38.150002, -2.620000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1225, 1, 'Kathenaugi', 38.150002, -0.100000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1226, 1, 'Kathenaugu', 38.150002, -0.100000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1227, 1, 'Kathera', 37.900002, -1.200000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1228, 1, 'Kathugu', 37.580002, -0.480000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1229, 1, 'Kathukeini', 37.049999, -0.630000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1230, 1, 'Kathukeni', 37.049999, -0.630000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1231, 1, 'Kathungu', 38.529999, -1.800000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1232, 1, 'Kathungure', 37.619999, -0.430000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1233, 1, 'Katothia', 37.730000, -1.230000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1234, 1, 'Katrima', 35.830002, -0.280000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1235, 1, 'Katulani', 37.630001, -1.530000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1236, 1, 'Katulye', 37.380001, -1.780000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1237, 1, 'Katumani Experimental Farm', 37.250000, -1.580000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1238, 1, 'Kau', 40.450001, -2.490000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1239, 1, 'Kaufumbani', 39.220001, -4.650000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1240, 1, 'Kaumoni', 37.549999, -1.770000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1241, 1, 'Kauro', 37.700001, 1.050000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1242, 1, 'Kavote', 37.330002, -0.430000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1243, 1, 'Kavuluni', 39.650002, -3.330000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1244, 1, 'Kavyongo', 37.980000, -2.480000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1245, 1, 'Kawanjaro', 37.549999, -0.470000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1246, 1, 'Kawelu', 38.220001, -1.770000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1247, 1, 'Kaweru', 37.099998, -0.650000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1248, 1, 'Kayafungo', 39.570000, -3.780000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1249, 1, 'Kayafungu', 39.570000, -3.780000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1250, 1, 'Kebenet', 35.080002, -0.450000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1251, 1, 'Kebeneti', 35.080002, -0.450000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1252, 1, 'Kebetwa', 35.630001, 1.150000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1253, 1, 'Kedong', 36.570000, -1.050000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1254, 1, 'Kedong Ranch', 36.480000, -1.070000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1255, 1, 'Kedowa', 35.549999, -0.230000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1256, 1, 'Keelah Farm', 35.020000, 1.080000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1257, 1, 'Kegaa', 37.570000, -0.430000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1258, 1, 'Keini East Farms', 36.570000, 0.130000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1259, 1, 'Keiria', 36.799999, -1.080000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1260, 1, 'Kendu', 34.650002, -0.370000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1261, 1, 'Kenegut', 35.220001, -0.270000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1262, 1, 'Kenplains', 37.029999, -1.520000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1263, 1, 'Kenyatta Farm', 36.930000, -1.050000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1264, 1, 'Kenze', 38.570000, -1.670000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1265, 1, 'Keplains', 37.029999, -1.520000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1266, 1, 'Kereita', 36.630001, -0.970000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1267, 1, 'Kericho', 35.279999, -0.370000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1268, 1, 'Kerichwa Kubwa', 36.779999, -1.280000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1269, 1, 'Kerie', 37.779999, -0.580000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1270, 1, 'Keringele', 37.169998, -0.970000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1271, 1, 'Keringet', 35.680000, -0.470000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1272, 1, 'Kerisa', 35.549999, -0.280000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1273, 1, 'Kerita', 36.630001, -0.980000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1274, 1, 'Keroka', 34.950001, -0.780000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1275, 1, 'Kerugoya', 37.279999, -0.500000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1276, 1, 'Keruguya', 37.279999, -0.500000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1277, 1, 'Kerundu', 37.080002, -0.570000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1278, 1, 'Keruri', 36.900002, -0.620000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1279, 1, 'Kesanguri', 39.720001, -1.620000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1280, 1, 'Ketparak', 35.169998, 0.100000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1281, 1, 'Kevanda', 38.080002, -2.370000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1282, 1, 'Kevote', 37.529999, -0.430000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1283, 1, 'Kewamoi', 35.619999, -0.400000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1284, 1, 'Khirgil', 36.270000, -0.220000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1285, 1, 'Kholera', 34.470001, 0.420000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1286, 1, 'Kholokhongo', 33.980000, 0.070000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1287, 1, 'Khorof Harar', 40.740002, 2.240000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1288, 1, 'Kiaga', 37.270000, -0.600000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1289, 1, 'Kiaguthu', 36.970001, -0.750000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1290, 1, 'Kiahiti', 37.029999, -0.820000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1291, 1, 'Kiahitie', 37.029999, -0.820000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1292, 1, 'Kiahuria', 36.720001, -1.220000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1293, 1, 'Kiaibabu', 36.680000, -1.150000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1294, 1, 'Kiairathe', 36.950001, -0.670000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1295, 1, 'Kiairia', 36.799999, -1.080000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1296, 1, 'Kiakanyinga', 37.700001, -0.270000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1297, 1, 'Kiamabara', 37.119999, -0.520000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1298, 1, 'Kiamaina', 37.220001, -0.450000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1299, 1, 'Kiamara', 37.070000, -0.700000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1300, 1, 'Kiamariga', 37.080002, -0.380000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1301, 1, 'Kiamathambo', 36.880001, -0.420000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1302, 1, 'Kiamatogo', 37.400002, -0.470000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1303, 1, 'Kiamatugu', 37.400002, -0.470000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1304, 1, 'Kiambaa', 36.750000, -1.150000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1305, 1, 'Kiambere', 37.779999, -0.700000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1306, 1, 'Kiambu', 36.830002, -1.170000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1307, 1, 'Kiambururu', 36.820000, -1.050000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1308, 1, 'Kiambuthia', 36.919998, -0.620000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1309, 1, 'Kiambuu', 36.830002, -1.170000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1310, 1, 'Kiamuchege', 37.380001, -0.670000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1311, 1, 'Kiamucheru', 37.119999, -0.420000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1312, 1, 'Kiamurathe', 37.029999, -0.580000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1313, 1, 'Kiamuringa', 37.529999, -0.570000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1314, 1, 'Kiamuthambi', 37.279999, -0.570000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1315, 1, 'Kiamutugu', 37.400002, -0.470000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1316, 1, 'Kiamuturi', 36.849998, -0.620000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1317, 1, 'Kiamuya', 36.970001, -0.570000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1318, 1, 'Kiamwangi', 36.869999, -1.020000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1319, 1, 'Kiamwathi', 36.950001, -0.450000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1320, 1, 'Kiamwenja', 37.279999, -0.530000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1321, 1, 'Kiamwenji', 37.099998, -0.680000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1322, 1, 'Kiamworia', 36.820000, -1.000000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1323, 1, 'Kiandai', 37.230000, -0.580000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1324, 1, 'Kianderi', 37.279999, -0.480000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1325, 1, 'Kiandieri', 37.279999, -0.480000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1326, 1, 'Kiandongoro', 36.830002, -0.450000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1327, 1, 'Kiandu', 36.970001, -0.480000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1328, 1, 'Kiandumu', 37.380001, -0.430000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1329, 1, 'Kiangai', 37.180000, -0.480000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1330, 1, 'Kianganda', 36.950001, -0.550000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1331, 1, 'Kiangararu', 37.130001, -0.480000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1332, 1, 'Kiangima', 36.799999, -1.070000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1333, 1, 'Kiangochi', 37.130001, -0.750000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1334, 1, 'Kiangoma', 37.070000, -0.570000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1335, 1, 'Kianguenyi', 37.279999, -0.450000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1336, 1, 'Kiangungi', 37.570000, -0.370000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1337, 1, 'Kiangunyi', 36.980000, -0.700000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1338, 1, 'Kiangwe', 40.980000, -1.940000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1339, 1, 'Kiangwenyi', 37.279999, -0.450000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1340, 1, 'Kianiai', 37.750000, 0.170000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1341, 1, 'Kianiokoma', 37.500000, -0.400000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1342, 1, 'Kianjai', 37.750000, 0.170000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1343, 1, 'Kianjege', 37.150002, -0.570000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1344, 1, 'Kianjiru', 37.200001, -0.530000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1345, 1, 'Kianjogu', 36.799999, -1.070000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1346, 1, 'Kianjuki', 37.500000, -0.450000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1347, 1, 'Kianyaga', 37.349998, -0.480000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1348, 1, 'Kianyagga', 37.349998, -0.480000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1349, 1, 'Kiarakongo', 37.349998, -0.650000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1350, 1, 'Kiaria', 36.799999, -1.080000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1351, 1, 'Kiaritha', 37.270000, -0.480000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1352, 1, 'Kiarukungu', 37.349998, -0.650000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1353, 1, 'Kiarutara', 36.820000, -0.850000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1354, 1, 'Kiarwara', 36.950001, -0.930000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1355, 1, 'Kiatineni', 37.369999, -1.550000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1356, 1, 'Kiawaithanji', 36.980000, -0.480000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1357, 1, 'Kiawambeu', 37.099998, -0.730000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1358, 1, 'Kiawambogo', 36.880001, -0.670000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1359, 1, 'Kiawamurathe', 37.029999, -0.580000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1360, 1, 'Kiawamururu', 37.029999, -0.550000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1361, 1, 'Kiawanjugu', 37.119999, -0.720000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1362, 1, 'Kiawanugu', 36.720001, -1.230000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1363, 1, 'Kiawarigi', 37.099998, -0.430000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1364, 1, 'Kibandaongo', 39.400002, -4.050000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1365, 1, 'Kibanguini', 36.970001, -1.000000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1366, 1, 'Kibanta', 35.180000, 0.320000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1367, 1, 'Kibaoni', 39.830002, -3.720000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1368, 1, 'Kibarani', 39.849998, -3.600000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1369, 1, 'Kibaya', 39.119999, -4.470000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1370, 1, 'Kibebetiet', 35.029999, 0.400000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1371, 1, 'Kibera Special Settlement Area', 36.779999, -1.320000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1372, 1, 'Kiberengi', 34.880001, 1.450000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1373, 1, 'Kibigori', 35.049999, -0.070000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1374, 1, 'Kibikoni', 36.320000, -0.830000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1375, 1, 'Kibingo', 37.250000, -0.480000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1376, 1, 'Kibingor', 35.869999, 0.500000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1377, 1, 'Kibingoti', 37.180000, -0.570000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1378, 1, 'Kibirigwi', 37.180000, -0.530000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1379, 1, 'Kibirikani', 40.820000, -2.300000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1380, 1, 'Kibiriraini', 36.680000, -1.280000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1381, 1, 'Kibogi', 37.520000, -0.420000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1382, 1, 'Kibogo', 37.430000, -0.450000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1383, 1, 'Kiboi', 36.970001, -0.750000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1384, 1, 'Kiboko', 37.650002, -2.270000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1385, 1, 'Kiboko Farm', 35.680000, -0.170000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1386, 1, 'Kiboko Farms', 35.680000, -0.170000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1387, 1, 'Kiboko Group Ranch', 37.650002, -2.270000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1388, 1, 'Kibokoni', 40.369999, -2.520000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1389, 1, 'Kibongoi', 35.169998, -0.480000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1390, 1, 'Kibos', 34.820000, -0.070000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1391, 1, 'Kibugu', 37.430000, -0.450000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1392, 1, 'Kibuguni', 39.349998, -4.500000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1393, 1, 'Kibugwa', 37.619999, -0.370000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1394, 1, 'Kiburu', 37.200001, -0.530000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1395, 1, 'Kibusu', 40.160000, -2.350000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1396, 1, 'Kibutha', 36.919998, -0.700000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1397, 1, 'Kibutio', 37.070000, -0.620000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1398, 1, 'Kibuyu', 38.580002, -1.680000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1399, 1, 'Kibwezi', 37.970001, -2.420000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1400, 1, 'Kichangalaweni', 39.180000, -4.450000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1401, 1, 'Kicio', 36.950001, -0.950000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1402, 1, 'Kidaya', 38.330002, -3.430000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1403, 1, 'Kidimu', 39.369999, -4.550000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1404, 1, 'Kidomaya', 39.169998, -4.580000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1405, 1, 'Kidono', 36.700001, -1.250000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1406, 1, 'Kiduluni', 39.720001, -3.770000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1407, 1, 'Kiduruni', 41.220001, -1.900000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1408, 1, 'Kidutani', 39.720001, -3.770000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1409, 1, 'Kidzumo', 39.580002, -4.200000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1410, 1, 'Kidzumu', 39.580002, -4.200000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1411, 1, 'Kiembekesha', 40.099998, -3.070000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1412, 1, 'Kieni', 36.669998, -0.850000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1413, 1, 'Kienyagga', 37.349998, -0.480000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1414, 1, 'Kifumbu', 38.650002, -3.820000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1415, 1, 'Kigaa', 36.849998, -1.030000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1416, 1, 'Kigala', 38.480000, -3.370000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1417, 1, 'Kiganio', 36.869999, -0.880000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1418, 1, 'Kiganjo', 36.869999, -0.880000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1419, 1, 'Kigato', 39.549999, -4.180000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1420, 1, 'Kigio', 36.950001, -0.950000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1421, 1, 'Kigogo-ini', 36.880001, -0.450000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1422, 1, 'Kigombero', 39.349998, -4.480000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1423, 1, 'Kigomberu', 39.349998, -4.480000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1424, 1, 'Kigombo', 38.450001, -3.380000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1425, 1, 'Kigongo', 36.849998, -1.030000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1426, 1, 'Kigoro', 36.880001, -0.850000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1427, 1, 'Kigumo', 37.020000, -0.680000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1428, 1, 'Kihancha', 34.619999, -1.180000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1429, 1, 'Kihara', 36.750000, -1.220000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1430, 1, 'Kihatha', 36.900002, -0.430000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1431, 1, 'Kihingo', 36.630001, -1.100000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1432, 1, 'Kihnjogu', 36.730000, -1.180000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1433, 1, 'Kihome', 36.880001, -0.520000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1434, 1, 'Kihoto', 36.900002, 0.230000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1435, 1, 'Kihoya', 36.900002, -0.650000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1436, 1, 'Kihumbuini', 36.980000, -0.930000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1437, 1, 'Kihuri', 36.869999, -0.550000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1438, 1, 'Kihuyo', 36.900002, -0.380000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1439, 1, 'Kiinu', 36.900002, -0.580000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1440, 1, 'Kiiriangoro', 37.000000, -0.800000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1441, 1, 'Kiiriongoro', 37.000000, -0.800000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1442, 1, 'Kijabe', 36.570000, -0.930000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1443, 1, 'Kijangwani', 39.830002, -3.780000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1444, 1, 'Kijegge', 37.930000, -0.270000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1445, 1, 'Kijinitini', 41.139999, -2.070000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1446, 1, 'Kijipoa', 39.799999, -3.830000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1447, 1, 'Kijipwa', 39.799999, -3.830000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1448, 1, 'Kijiwetanga', 40.080002, -3.250000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1449, 1, 'Kikambala', 39.779999, -3.900000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1450, 1, 'Kikima', 37.450001, -1.670000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1451, 1, 'Kiko Koni', 40.459999, -2.520000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1452, 1, 'Kikoko', 37.380001, -1.800000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1453, 1, 'Kikomani', 40.919998, -2.270000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1454, 1, 'Kikoneni', 39.299999, -4.450000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1455, 1, 'Kikoni', 40.869999, -2.230000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1456, 1, 'Kikumini', 37.570000, -2.050000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1457, 1, 'Kikuu', 38.320000, -1.480000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1458, 1, 'Kikuyu', 36.669998, -1.250000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1459, 1, 'Kikuyuni', 40.000000, -3.220000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1460, 1, 'Kikwezani', 39.200001, -4.670000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1461, 1, 'Kilala', 37.549999, -1.770000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1462, 1, 'Kilawa', 38.080002, -2.130000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1463, 1, 'Kileleshwan', 36.779999, -1.280000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1464, 1, 'Kilema Dui', 39.529999, -1.600000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1465, 1, 'Kilgoris', 34.880001, -1.000000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1466, 1, 'Kilibasi', 38.950001, -3.970000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1467, 1, 'Kilifi', 39.849998, -3.630000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1468, 1, 'Kiligis', 35.150002, -0.170000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1469, 1, 'Kililana', 40.919998, -2.220000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1470, 1, 'Kilima Farm', 35.849998, -0.250000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1471, 1, 'Kilima Pembe', 36.200001, 0.050000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1472, 1, 'Kilimaini', 37.400002, -0.650000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1473, 1, 'Kilimani Estate', 36.779999, -1.280000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1474, 1, 'Kilkoris', 34.880001, -1.000000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1475, 1, 'Kilole', 39.500000, -4.350000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1476, 1, 'Kilome', 37.330002, -1.800000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1477, 1, 'Kilweni', 41.020000, -2.040000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1478, 1, 'Kima', 37.250000, -1.950000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1479, 1, 'Kimaeti', 34.410000, 0.600000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1480, 1, 'Kimala', 37.700001, -3.400000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1481, 1, 'Kimana', 37.529999, -2.800000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1482, 1, 'Kimande', 36.799999, -0.820000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1483, 1, 'Kimara Maganga', 40.900002, -2.220000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1484, 1, 'Kimari Maganga', 40.900002, -2.220000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1485, 1, 'Kimathi', 36.830002, -1.080000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1486, 1, 'Kimbimbi', 37.369999, -0.620000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1487, 1, 'Kimende', 36.630001, -0.980000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1488, 1, 'Kimilili', 34.720001, 0.780000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1489, 1, 'Kiming''ini', 34.770000, 0.180000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1490, 1, 'Kiming?ini', 34.770000, 0.180000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1491, 1, 'Kiminini', 34.919998, 0.900000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1492, 1, 'Kimnai', 35.680000, 0.970000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1493, 1, 'Kimngorom', 35.820000, 0.170000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1494, 1, 'Kimondo', 37.099998, -0.550000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1495, 1, 'Kimose', 35.900002, 0.220000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1496, 1, 'Kimoset', 35.900002, 0.220000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1497, 1, 'Kimugandura Farm', 37.049999, 0.150000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1498, 1, 'Kimunye', 37.299999, -0.430000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1499, 1, 'Kimunyu', 36.950001, -0.980000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1500, 1, 'Kimuri Farm', 36.970001, 0.100000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1501, 1, 'Kimuu', 38.500000, -0.770000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1502, 1, 'Kimwani National Farm', 35.180000, 0.020000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1503, 1, 'Kimwarer', 35.630001, 0.320000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1504, 1, 'Kinagoni', 39.230000, -3.870000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1505, 1, 'Kinancha', 34.619999, -1.180000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1506, 1, 'Kinane', 39.599998, -3.770000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1507, 1, 'Kinango', 39.480000, -3.700000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1508, 1, 'Kinangoni', 39.230000, -3.870000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1509, 1, 'Kinani', 39.599998, -3.770000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1510, 1, 'Kinarane', 39.570000, -3.730000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1511, 1, 'Kinarani', 39.570000, -3.730000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1512, 1, 'Kinari', 36.619999, -0.900000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1513, 1, 'Kindu', 34.650002, -0.370000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1514, 1, 'Kindunguni', 39.630001, -4.080000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1515, 1, 'Kinna', 38.200001, 0.320000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1516, 1, 'Kinondo', 39.549999, -4.380000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1517, 1, 'Kinoo', 36.700001, -1.250000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1518, 1, 'Kinunga', 36.830002, -1.080000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1519, 1, 'Kinyach', 35.680000, 0.930000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1520, 1, 'Kinyadu', 40.150002, -2.020000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1521, 1, 'Kinyang', 36.020000, 0.970000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1522, 1, 'Kinyata Grazing Area', 37.680000, -1.330000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1523, 1, 'Kinyona', 36.830002, -0.750000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1524, 1, 'Kiongwe', 40.790001, -2.400000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1525, 1, 'Kipcherere', 35.849998, 0.580000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1526, 1, 'Kipendi', 40.119999, -1.800000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1527, 1, 'Kipevu', 39.630001, -4.030000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1528, 1, 'Kipini', 40.529999, -2.530000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1529, 1, 'Kipkabus', 35.500000, 0.300000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1530, 1, 'Kipsigak Farm', 35.130001, 0.130000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1531, 1, 'Kiptagich', 35.799999, 0.380000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1532, 1, 'Kiptere', 35.099998, -0.400000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1533, 1, 'Kipungani', 40.820000, -2.300000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1534, 1, 'Kipusi', 38.380001, -3.450000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1535, 1, 'Kiranga', 37.029999, -0.920000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1536, 1, 'Kiratina', 36.830002, -1.050000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1537, 1, 'Kirawara', 36.950001, -0.930000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1538, 1, 'Kirenga', 36.630001, -1.000000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1539, 1, 'Kireri', 37.000000, -0.800000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1540, 1, 'Kirerwa', 37.000000, -0.850000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1541, 1, 'Kirewe', 39.299999, -4.270000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1542, 1, 'Kiria', 37.299999, -0.530000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1543, 1, 'Kiriaini', 36.919998, -0.880000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1544, 1, 'Kiriangoro', 37.000000, -0.800000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1545, 1, 'Kiriani', 36.950001, -0.600000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1546, 1, 'Kirichu', 37.020000, 0.420000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1547, 1, 'Kirigi', 37.369999, -0.670000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1548, 1, 'Kirigo', 37.380001, -0.670000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1549, 1, 'Kiriko', 36.880001, -1.030000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1550, 1, 'Kirima', 36.330002, 0.080000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1551, 1, 'Kirimaine', 37.400002, -0.650000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1552, 1, 'Kirimaini', 37.080002, -0.800000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1553, 1, 'Kirimamwaro', 37.169998, -0.880000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1554, 1, 'Kirimari', 37.450001, -0.530000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1555, 1, 'Kirimatian', 36.130001, -1.880000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1556, 1, 'Kirimiri', 37.250000, -0.920000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1557, 1, 'Kirimunge', 37.270000, -0.570000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1558, 1, 'Kiriri', 36.570000, -1.200000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1559, 1, 'Kiriti', 37.049999, -0.800000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1560, 1, 'Kiritiri', 37.650002, -0.680000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1561, 1, 'Kiroe', 36.599998, -1.200000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1562, 1, 'Kirogo', 37.029999, -0.720000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1563, 1, 'Kirong''e', 37.320000, -0.530000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1564, 1, 'Kirong?e', 37.320000, -0.530000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1565, 1, 'Kiruga', 36.970001, -0.570000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1566, 1, 'Kirumbi', 39.470001, -3.750000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1567, 1, 'Kirundu', 37.070000, -0.800000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1568, 1, 'Kirungii', 36.230000, 0.180000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1569, 1, 'Kiruri', 36.849998, -0.680000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1570, 1, 'Kirurumi', 36.869999, -0.430000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1571, 1, 'Kirwara', 36.950001, -0.930000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1572, 1, 'Kirwilu', 39.820000, -3.780000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1573, 1, 'Kirwitu', 39.820000, -3.780000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1574, 1, 'Kisaoni', 39.680000, -4.030000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1575, 1, 'Kisauni', 39.680000, -4.030000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1576, 1, 'Kiserian', 36.680000, -1.430000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1577, 1, 'Kiseryan', 36.680000, -1.430000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1578, 1, 'Kisibu', 40.160000, -2.350000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1579, 1, 'Kisii', 34.770000, -0.680000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1580, 1, 'Kisima Farm', 36.720001, 0.480000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1581, 1, 'Kisimachande', 39.480000, -4.480000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1582, 1, 'Kisingatani', 41.139999, -2.070000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1583, 1, 'Kisingatini', 41.139999, -2.070000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1584, 1, 'Kisou', 38.250000, -2.100000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1585, 1, 'Kisserian', 36.680000, -1.430000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1586, 1, 'Kisuki', 36.869999, -0.520000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1587, 1, 'Kisumo', 34.750000, -0.100000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1588, 1, 'Kitaingo', 37.369999, -1.850000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1589, 1, 'Kitandi', 37.349998, -1.750000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1590, 1, 'Kitere', 34.599998, -0.830000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1591, 1, 'Kiteta', 37.529999, -1.500000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1592, 1, 'Kithangathini', 37.369999, -1.820000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1593, 1, 'Kitheine', 37.349998, -1.880000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1594, 1, 'Kithungo', 37.500000, -1.670000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1595, 1, 'Kithungu', 37.500000, -1.670000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1596, 1, 'Kithunguri', 37.480000, -0.370000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1597, 1, 'Kitoo', 38.180000, -1.900000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1598, 1, 'Kitsantse', 39.450001, -4.130000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1599, 1, 'Kitute', 38.119999, -2.130000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1600, 1, 'Kituti', 38.119999, -2.130000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1601, 1, 'Kituu', 39.250000, -4.030000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1602, 1, 'Kiu', 37.169998, -1.900000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1603, 1, 'Kiumba', 37.020000, -0.750000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1604, 1, 'Kiumbi', 36.880001, -0.420000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1605, 1, 'Kiunga', 41.500000, -1.740000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1606, 1, 'Kiunya', 37.020000, -0.950000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1607, 1, 'Kiuria', 37.320000, -0.670000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1608, 1, 'Kiuu', 37.150002, -0.600000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1609, 1, 'Kivani', 37.369999, -1.720000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1610, 1, 'Kiviogo', 39.430000, -4.500000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1611, 1, 'Kivukoni', 40.119999, -1.780000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1612, 1, 'Kivumbu', 37.430000, -1.520000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1613, 1, 'Kiwegu', 39.200001, -4.630000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1614, 1, 'Koala', 37.349998, -1.700000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1615, 1, 'Kobodo', 34.419998, -0.670000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1616, 1, 'Kodiaga', 34.450001, 0.100000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1617, 1, 'Kogoe', 34.330002, -0.350000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1618, 1, 'Koimbi', 36.980000, -0.700000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1619, 1, 'Koiparak', 34.869999, 0.120000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1620, 1, 'Koitilial', 35.619999, 0.930000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1621, 1, 'Koito', 39.119999, -3.020000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1622, 1, 'Koituiy', 35.169998, -0.280000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1623, 1, 'Kola', 37.349998, -1.700000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1624, 1, 'Kolbio', 41.220001, -1.150000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1625, 1, 'Kolloa', 35.730000, 1.180000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1626, 1, 'Kolowa', 35.730000, 1.180000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1627, 1, 'Kom', 38.029999, 1.080000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1628, 1, 'Kombo Kombo', 41.070000, -1.770000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1629, 1, 'Komboyoo', 37.980000, -2.600000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1630, 1, 'Komo Farm', 37.049999, -1.050000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1631, 1, 'Kone', 40.049999, -1.500000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1632, 1, 'Kongelai', 35.020000, 1.470000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1633, 1, 'Kongona', 40.119999, -2.250000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1634, 1, 'Kongoni Farm', 36.270000, -0.820000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1635, 1, 'Koni', 40.049999, -1.500000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1636, 1, 'Konjero se Ekonjero', 34.630001, 0.020000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1637, 1, 'Konyu', 36.869999, -0.530000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1638, 1, 'Konza', 37.119999, -1.750000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1639, 1, 'Kora', 37.000000, -0.630000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1640, 1, 'Korbesa', 38.349998, 0.250000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1641, 1, 'Korbessa', 38.349998, 0.250000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1642, 1, 'Koreni', 40.700001, -2.270000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1643, 1, 'Koridzhub', 41.509998, 3.560000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1644, 1, 'Korikabemitik', 35.599998, -0.630000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1645, 1, 'Korokora', 39.790001, -0.610000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1646, 1, 'Korongo Farm', 36.270000, -0.820000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1647, 1, 'Koru', 35.270000, -0.180000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1648, 1, 'Kosipirr', 35.070000, 2.880000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1649, 1, 'Kowa Gulecha', 39.700001, -2.680000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1650, 1, 'Koyonzo', 34.419998, 0.420000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1651, 1, 'Kubi Turkana', 38.200001, 0.570000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1652, 1, 'Kuchelebai', 35.020000, 1.480000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1653, 1, 'Kuhora Twana', 37.070000, -0.350000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1654, 1, 'Kuku', 37.750000, -2.900000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1655, 1, 'Kukurna', 35.119999, 1.970000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1656, 1, 'Kulelet', 39.150002, -2.030000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1657, 1, 'Kulesa', 40.169998, -2.180000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1658, 1, 'Kumbi', 40.000000, -1.280000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1659, 1, 'Kundi', 40.680000, -1.130000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1660, 1, 'Kungu', 38.349998, -3.420000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1661, 1, 'Kunyao', 35.049999, 1.780000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1662, 1, 'Kuruwetu', 39.820000, -3.780000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1663, 1, 'Kurwitu', 39.820000, -3.780000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1664, 1, 'Kusa', 34.830002, -0.320000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1665, 1, 'Kusitawi', 39.500000, -3.950000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1666, 1, 'Kutu', 37.320000, -0.570000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1667, 1, 'Kutus', 37.320000, -0.570000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1668, 1, 'Kuywa', 34.630001, 0.750000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1669, 1, 'Kwa Bechombo', 39.619999, -3.930000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1670, 1, 'Kwa Bwana Keri', 40.770000, -2.100000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1671, 1, 'Kwa Dadu', 39.680000, -3.250000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1672, 1, 'Kwa Jomvu', 39.599998, -4.020000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1673, 1, 'Kwa Kitau', 37.480000, -2.000000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1674, 1, 'Kwa Makuli', 37.770000, -2.070000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1675, 1, 'Kwa Mkamba', 39.130001, -4.030000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1676, 1, 'Kwa Ukungu', 38.020000, -1.320000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1677, 1, 'Kwademu', 39.599998, -3.700000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1678, 1, 'Kwakavisi', 37.720001, -2.000000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1679, 1, 'Kwale', 39.450001, -4.180000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1680, 1, 'Kwandeke', 37.529999, -2.050000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1681, 1, 'Kwangamor', 34.290001, 0.520000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1682, 1, 'Kwangamur', 34.290001, 0.520000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1683, 1, 'Kwangwari', 36.730000, -1.280000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1684, 1, 'Kwaringoi', 38.650002, -3.800000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1685, 1, 'Laboot', 34.619999, 1.020000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1686, 1, 'Lady Whitehouse', 34.750000, -0.100000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1687, 1, 'Laini', 36.750000, -1.150000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1688, 1, 'Lairagwan Farm', 37.020000, 0.130000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1689, 1, 'Laisamis', 37.799999, 1.600000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1690, 1, 'Lake View Estate', 36.080002, -0.300000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1691, 1, 'Lake View Estate Crescent', 36.080002, -0.300000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1692, 1, 'Lamu', 40.900002, -2.280000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1693, 1, 'Lamuria', 36.869999, -0.180000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1694, 1, 'Lamuria Farm', 36.869999, -0.180000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1695, 1, 'Langarwa', 36.049999, 0.470000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1696, 1, 'Langata Rongai', 36.770000, -1.400000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1697, 1, 'Lare', 37.930000, 0.330000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1698, 1, 'Lari', 36.630001, -1.020000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1699, 1, 'Lari Farm', 36.630001, -1.030000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1700, 1, 'Laset', 37.570000, -2.950000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1701, 1, 'Lasset', 37.570000, -2.950000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1702, 1, 'Laza', 40.029999, -1.500000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1703, 1, 'Lazima', 40.169998, -2.120000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1704, 1, 'Lechugu Farm', 36.930000, -0.080000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1705, 1, 'Leka', 39.830002, -1.330000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1706, 1, 'Lela', 34.599998, -0.030000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1707, 1, 'Lemek', 35.380001, -1.100000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1708, 1, 'Lenda', 40.070000, -1.570000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1709, 1, 'Lengesim', 37.220001, -2.400000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1710, 1, 'Libat', 40.919998, -0.780000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1711, 1, 'Libwezi', 37.970001, -2.420000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1712, 1, 'Likoni', 39.650002, -4.080000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1713, 1, 'Limuru', 36.650002, -1.100000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1714, 1, 'Litein', 35.180000, -0.580000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1715, 1, 'Livundoni', 39.049999, -4.480000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1716, 1, 'Livwindoni', 39.049999, -4.480000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1717, 1, 'Llewelen', 37.020000, -0.080000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1718, 1, 'Llexelen', 37.020000, -0.080000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1719, 1, 'Lodosoit', 37.570000, 1.350000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1720, 1, 'Log Logo', 37.919998, 1.980000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1721, 1, 'Logumukum', 36.080002, 0.450000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1722, 1, 'Loichangamatak', 35.650002, 2.630000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1723, 1, 'Loiminange', 36.099998, 0.530000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1724, 1, 'Lokatiang', 35.750000, 4.270000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1725, 1, 'Lokichar', 35.650002, 2.380000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1726, 1, 'Lokichiogio', 34.349998, 4.200000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1727, 1, 'Lokichoggio', 34.349998, 4.200000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1728, 1, 'Lokichokio', 34.349998, 4.200000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1729, 1, 'Lokiehoggio', 34.349998, 4.200000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1730, 1, 'Lokitaung', 35.750000, 4.270000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1731, 1, 'Lokkichoggio', 34.349998, 4.200000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1732, 1, 'Lokori', 36.020000, 1.950000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1733, 1, 'Lokwakangole', 35.900002, 3.530000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1734, 1, 'Loldaiga Farm', 37.130001, 0.280000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1735, 1, 'Loldoto Farm', 36.970001, 0.170000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1736, 1, 'Lolgorien', 34.799999, -1.230000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1737, 1, 'Lolnguswa', 36.650002, -2.150000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1738, 1, 'Lolomarik Farm', 37.279999, 0.100000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1739, 1, 'Lombala Ranch', 36.580002, 0.220000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1740, 1, 'Lomelo', 35.919998, 1.500000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1741, 1, 'Lomet Farm', 35.680000, -0.320000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1742, 1, 'Lomut', 35.570000, 1.400000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1743, 1, 'Londiani', 35.599998, -0.170000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1744, 1, 'Longonot', 36.400002, -0.830000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1745, 1, 'Longopito', 37.130001, 0.700000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1746, 1, 'Loperot', 35.849998, 2.330000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1747, 1, 'Lorngosua', 36.650002, -2.150000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1748, 1, 'Lorugumu', 35.250000, 2.880000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1749, 1, 'Loruk', 36.029999, 0.720000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1750, 1, 'Lorukumu', 35.250000, 2.880000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1751, 1, 'Lorumorr', 35.130001, 4.620000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0);
INSERT INTO `loctowns` (`idloctowns`, `loccountry_idloccountry`, `locationname`, `lng`, `lat`, `createdby`, `createdon`, `validatedby`, `validatedon`, `is_valid`, `is_town`) VALUES
(1752, 1, 'Loruth', 35.799999, 4.620000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1753, 1, 'Lorwok', 36.049999, 0.350000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1754, 1, 'Losigetti', 36.680000, -1.270000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1755, 1, 'Lossom', 35.049999, 1.980000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1756, 1, 'Lotongot', 35.630001, 1.730000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1757, 1, 'Low Ling Farm', 35.950001, -0.100000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1758, 1, 'Lower Gatara', 36.900002, -0.750000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1759, 1, 'Lower Kabete', 36.720001, -1.230000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1760, 1, 'Lower Makueni Grazing Area', 37.830002, -1.900000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1761, 1, 'Luanda', 34.060001, 0.310000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1762, 1, 'Lucy Farm', 37.220001, 0.080000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1763, 1, 'Lugare', 34.000000, 0.080000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1764, 1, 'Lugari', 34.000000, 0.080000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1765, 1, 'Lughena', 40.880001, -5.080000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1766, 1, 'Lugulu', 34.310001, 0.390000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1767, 1, 'Lukongo', 39.500000, -3.880000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1768, 1, 'Lumbwa', 35.470001, -0.200000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1769, 1, 'Lumino', 34.139999, 0.390000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1770, 1, 'Lumowa', 35.470001, -0.200000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1771, 1, 'Lunga-Lunga', 39.119999, -4.550000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1772, 1, 'Luoniek Ranch', 36.470001, 0.730000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1773, 1, 'Lushangonyi', 38.320000, -3.400000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1774, 1, 'Lusigeti', 36.599998, -1.270000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1775, 1, 'Lusigetti', 36.599998, -1.270000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1776, 1, 'Luswani', 39.549999, -3.470000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1777, 1, 'Lutsangani', 39.730000, -3.750000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1778, 1, 'Lwala', 34.369999, -0.500000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1779, 1, 'Lwanda', 34.580002, 0.030000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1780, 1, 'Lwei', 34.130001, 0.450000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1781, 1, 'Lwero', 34.130001, 0.450000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1782, 1, 'Maai Mahiu', 36.480000, -1.020000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1783, 1, 'Maang''u', 36.950001, -1.000000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1784, 1, 'Maang?u', 36.950001, -1.000000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1785, 1, 'Mabatani', 39.450001, -4.480000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1786, 1, 'Mabogi', 41.130001, -2.080000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1787, 1, 'Machakos', 37.270000, -1.520000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1788, 1, 'Machege', 35.919998, -0.080000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1789, 1, 'Mackinnon Road', 39.049999, -3.730000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1790, 1, 'Madabogo', 38.380001, -3.430000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1791, 1, 'Madera', 41.860001, 3.940000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1792, 1, 'Madiany', 34.320000, -0.280000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1793, 1, 'Mado Gashi', 39.180000, 0.730000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1794, 1, 'Mado Yaka', 38.330002, 0.700000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1795, 1, 'Maduma', 39.650002, -3.520000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1796, 1, 'Madunda', 34.299999, -0.380000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1797, 1, 'Madungoni', 39.980000, -3.180000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1798, 1, 'Madunguni', 39.980000, -3.180000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1799, 1, 'Mafigani', 39.650002, -3.920000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1800, 1, 'Magadi', 36.279999, -1.900000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1801, 1, 'Magado', 38.049999, 0.620000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1802, 1, 'Magamia Hill Farm', 36.700001, -1.050000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1803, 1, 'Magamja Hill Farm', 36.700001, -1.050000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1804, 1, 'Maganjo', 37.150002, -0.870000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1805, 1, 'Magarini', 40.070000, -3.030000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1806, 1, 'Magina', 36.619999, -0.950000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1807, 1, 'Magogoni Farm', 37.230000, -1.050000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1808, 1, 'Magongo', 39.230000, -3.850000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1809, 1, 'Maguguni', 39.200001, -4.630000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1810, 1, 'Magumba', 40.880001, -2.030000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1811, 1, 'Magumu', 36.930000, -0.950000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1812, 1, 'Magunda', 39.150002, -4.450000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1813, 1, 'Magunga', 34.150002, -0.680000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1814, 1, 'Magunguni', 39.200001, -4.630000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1815, 1, 'Magutu', 39.549999, -4.300000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1816, 1, 'Magwagwa', 35.020000, -0.480000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1817, 1, 'Mahiga', 36.900002, -0.500000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1818, 1, 'Mahigaini', 37.380001, -0.650000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1819, 1, 'Mahinga', 36.650002, -1.170000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1820, 1, 'Mahuruni', 39.130001, -4.530000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1821, 1, 'Mahuzumia', 40.330002, -2.520000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1822, 1, 'Mai Maharo', 36.619999, -0.580000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1823, 1, 'Mai Mahoro', 36.619999, -0.580000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1824, 1, 'Maiella Farm', 36.549999, -1.050000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1825, 1, 'Maikona', 37.630001, 2.930000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1826, 1, 'Maikuu', 37.980000, -2.480000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1827, 1, 'Mailla', 36.549999, -1.050000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1828, 1, 'Mailua', 36.950001, -2.330000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1829, 1, 'Maina', 36.349998, 0.070000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1830, 1, 'Mairi', 36.820000, -0.770000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1831, 1, 'Mairie', 36.820000, -0.770000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1832, 1, 'Mairii', 36.820000, -0.770000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1833, 1, 'Maiyuni', 37.549999, -1.200000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1834, 1, 'Majego', 38.419998, -3.470000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1835, 1, 'Majengo', 39.500000, -3.970000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1836, 1, 'Maji Mata', 36.049999, 0.270000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1837, 1, 'Maji Moto', 36.049999, 0.270000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1838, 1, 'Maji Ya Moto', 36.049999, 0.270000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1839, 1, 'Maji ya Chumvi', 39.380001, -3.800000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1840, 1, 'Majongoni', 41.080002, -2.140000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1841, 1, 'Majoreni', 39.279999, -4.570000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1842, 1, 'Majunguni', 41.080002, -2.140000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1843, 1, 'Makabete', 37.320000, -1.570000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1844, 1, 'Makabeti', 37.320000, -1.570000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1845, 1, 'Makadara', 36.880001, -1.280000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1846, 1, 'Makalanga', 39.119999, -4.430000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1847, 1, 'Makambuki', 36.830002, -0.800000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1848, 1, 'Makamini', 39.230000, -3.980000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1849, 1, 'Makandara', 36.970001, -1.450000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1850, 1, 'Makandara Housing Estate', 36.970001, -1.450000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1851, 1, 'Makaveti', 37.320000, -1.570000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1852, 1, 'Makendo', 37.820000, -2.280000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1853, 1, 'Makenge', 37.520000, -0.450000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1854, 1, 'Makere', 40.009998, -1.350000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1855, 1, 'Makere ya Gwano', 40.130001, -1.870000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1856, 1, 'Makhoma', 34.000000, 0.080000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1857, 1, 'Makindi', 37.099998, -0.970000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1858, 1, 'Makindu', 37.820000, -2.280000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1859, 1, 'Makinya', 37.700001, -1.650000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1860, 1, 'Makinyambu', 38.299999, -3.320000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1861, 1, 'Makobeni', 39.630001, -3.870000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1862, 1, 'Makomboki', 36.830002, -0.800000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1863, 1, 'Makondeni', 39.580002, -4.150000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1864, 1, 'Makongani', 39.599998, -3.430000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1865, 1, 'Makowe', 40.860001, -2.230000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1866, 1, 'Maktau', 38.130001, -3.400000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1867, 1, 'Makueni', 37.619999, -1.800000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1868, 1, 'Makueni Boma', 37.619999, -1.800000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1869, 1, 'Makumbe', 40.880001, -2.030000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1870, 1, 'Makumbwani', 39.580002, -3.780000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1871, 1, 'Makutano', 35.099998, 1.270000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1872, 1, 'Makuyu', 37.180000, -0.900000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1873, 1, 'Makwa', 36.970001, -0.970000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1874, 1, 'Makwara', 39.500000, -3.650000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1875, 1, 'Makwau', 36.970001, -0.970000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1876, 1, 'Malaba', 34.270000, 0.630000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1877, 1, 'Malakisi', 34.419998, 0.680000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1878, 1, 'Malamba', 39.570000, -3.850000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1879, 1, 'Malandoni', 40.849998, -2.280000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1880, 1, 'Malanga', 34.290001, 0.420000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1881, 1, 'Maledi', 39.130001, -4.450000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1882, 1, 'Malibani', 37.580002, -1.800000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1883, 1, 'Malibati', 40.000000, -1.300000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1884, 1, 'Malikisi', 34.419998, 0.670000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1885, 1, 'Malima', 37.669998, -0.270000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1886, 1, 'Malindi', 40.119999, -3.220000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1887, 1, 'Malka Jara', 39.810001, -0.710000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1888, 1, 'Malka Mari', 40.779999, 4.270000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1889, 1, 'Malka Murri', 40.779999, 4.270000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1890, 1, 'Mambore', 41.430000, -1.810000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1891, 1, 'Mambosasa', 40.529999, -2.380000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1892, 1, 'Mambrui', 40.150002, -3.120000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1893, 1, 'Manamare', 39.529999, -3.800000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1894, 1, 'Mandeni', 40.529999, -2.430000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1895, 1, 'Mandera', 41.860001, 3.940000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1896, 1, 'Mandongoi', 38.580002, -0.520000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1897, 1, 'Manera', 36.419998, -0.680000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1898, 1, 'Mangai', 41.169998, -1.750000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1899, 1, 'Mangelete', 38.130001, -2.700000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1900, 1, 'Mangu', 36.950001, -1.000000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1901, 1, 'Mangu Farm', 37.020000, -1.050000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1902, 1, 'Manoni', 38.080002, -2.380000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1903, 1, 'Manooni', 38.080002, -2.380000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1904, 1, 'Manyata', 37.150002, -0.870000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1905, 1, 'Manyatta', 37.150002, -0.870000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1906, 1, 'Manyeso', 39.779999, -2.990000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1907, 1, 'Maongo', 40.330002, -2.520000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1908, 1, 'Mapenya', 40.700001, -2.350000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1909, 1, 'Mapepie', 37.279999, -1.780000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1910, 1, 'Mapfanga', 39.130001, -4.170000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1911, 1, 'Mapotea', 39.450001, -3.380000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1912, 1, 'Maraagwa', 35.020000, -0.480000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1913, 1, 'Marach', 34.320000, 0.330000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1914, 1, 'Marafa', 39.950001, -3.030000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1915, 1, 'Maragi One', 37.119999, -0.730000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1916, 1, 'Maragi Two', 37.130001, -0.730000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1917, 1, 'Maragoli', 34.720001, 0.100000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1918, 1, 'Maragua', 37.130001, -0.800000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1919, 1, 'Maragua Station', 37.130001, -0.800000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1920, 1, 'Maragwa', 37.130001, -0.800000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1921, 1, 'Marakwet', 35.570000, 0.980000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1922, 1, 'Maralal', 36.700001, 1.100000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1923, 1, 'Maranjau', 37.220001, -0.820000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1924, 1, 'Maranjua', 37.220001, -0.820000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1925, 1, 'Maranu', 37.700001, -3.380000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1926, 1, 'Mararani', 41.310001, -1.710000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1927, 1, 'Mararo', 36.720001, -1.230000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1928, 1, 'Mararui', 41.299999, -1.930000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1929, 1, 'Marashoni', 35.820000, -0.370000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1930, 1, 'Mareira', 36.930000, -0.800000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1931, 1, 'Marereni', 40.150002, -2.870000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1932, 1, 'Mariaini', 37.080002, -0.830000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1933, 1, 'Mariakani', 39.470001, -3.870000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1934, 1, 'Mariani', 37.700001, -0.320000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1935, 1, 'Marigat', 35.980000, 0.470000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1936, 1, 'Marigiza', 39.470001, -4.450000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1937, 1, 'Mariira', 36.930000, -0.800000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1938, 1, 'Marikebuni', 40.119999, -3.080000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1939, 1, 'Marima', 37.669998, -0.270000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1940, 1, 'Marinde', 34.520000, -0.650000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1941, 1, 'Marira', 36.930000, -0.800000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1942, 1, 'Mariwenyi', 38.520000, -3.430000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1943, 1, 'Mariwinyi', 38.520000, -3.430000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1944, 1, 'Marmanet', 36.369999, 0.170000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1945, 1, 'Marmar', 36.750000, 0.780000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1946, 1, 'Marmar Ranching and Trading Company', 36.750000, 0.780000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1947, 1, 'Marongo', 39.200001, -4.620000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1948, 1, 'Maroni', 40.099998, -1.850000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1949, 1, 'Marsabit', 37.980000, 2.330000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1950, 1, 'Marua', 37.029999, -0.450000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1951, 1, 'Marucha', 34.430000, -0.570000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1952, 1, 'Marula Valley Farm', 36.650002, 0.300000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1953, 1, 'Marumi', 36.980000, -0.800000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1954, 1, 'Marurumo', 37.430000, -0.720000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1955, 1, 'Maruvesa', 39.270000, -3.670000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1956, 1, 'Maruvessa', 39.270000, -3.670000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1957, 1, 'Maryland Estate', 36.230000, 0.030000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1958, 1, 'Maryvale Farm', 36.950001, -1.220000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1959, 1, 'Masabubu', 40.020000, -1.210000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1960, 1, 'Masaita', 35.580002, -0.130000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1961, 1, 'Masaku', 37.270000, -1.520000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1962, 1, 'Masalani', 38.119999, -2.320000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1963, 1, 'Masandare', 35.599998, -0.970000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1964, 1, 'Masanga', 40.680000, -2.250000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1965, 1, 'Masati', 34.650002, 0.150000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1966, 1, 'Maseno', 34.599998, -0.020000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1967, 1, 'Mashamba', 37.520000, -0.750000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1968, 1, 'Mashanda', 38.580002, -3.500000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1969, 1, 'Mashimdwani', 40.880001, -2.200000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1970, 1, 'Mashundwani', 40.880001, -2.200000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1971, 1, 'Mashuru', 37.119999, -2.050000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1972, 1, 'Masii', 37.430000, -1.470000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1973, 1, 'Masimbani', 38.130001, -2.370000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1974, 1, 'Masongaleni', 38.049999, -2.480000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1975, 1, 'Masongoleni', 38.049999, -2.480000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1976, 1, 'Massalani', 40.130001, -1.710000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1977, 1, 'Mata', 37.750000, -3.050000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1978, 1, 'Mataara', 36.799999, -0.870000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1979, 1, 'Matadoni', 40.849998, -2.280000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1980, 1, 'Matakari', 37.450001, -0.530000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1981, 1, 'Matandara', 37.369999, -0.630000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1982, 1, 'Matapani', 40.490002, -2.470000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1983, 1, 'Matathia', 36.680000, -1.400000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1984, 1, 'Mataya', 34.169998, 0.360000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1985, 1, 'Matayo', 34.169998, 0.360000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1986, 1, 'Matayos', 34.169998, 0.360000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1987, 1, 'Mateka', 34.500000, 0.540000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1988, 1, 'Matha', 37.570000, -2.000000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1989, 1, 'Matha-geni', 36.849998, -0.770000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1990, 1, 'Mathaguta', 37.369999, -0.630000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1991, 1, 'Mathandara', 37.369999, -0.630000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1992, 1, 'Mathangauta', 37.369999, -0.630000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1993, 1, 'Mathangiini', 36.849998, -0.770000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1994, 1, 'Mathanjeini', 36.849998, -0.770000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1995, 1, 'Mathareini', 36.970001, -0.820000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1996, 1, 'Mathari', 36.919998, -0.420000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1997, 1, 'Mathariti', 36.900002, -0.700000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1998, 1, 'Mathiga', 37.430000, -0.600000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(1999, 1, 'Mathigaine', 37.380001, -0.650000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2000, 1, 'Mathiyas', 37.180000, -0.450000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2001, 1, 'Matironi', 41.299999, -1.950000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2002, 1, 'Matolani', 39.470001, -3.670000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2003, 1, 'Matondoni', 40.849998, -2.280000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2004, 1, 'Matsangoni', 39.930000, -3.400000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2005, 1, 'Matsavini', 39.470001, -3.680000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2006, 1, 'Matuga', 39.570000, -4.170000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2007, 1, 'Matuguta', 36.730000, -1.080000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2008, 1, 'Matumbi', 39.380001, -3.970000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2009, 1, 'Mau Narok', 36.000000, -0.600000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2010, 1, 'Maua', 37.930000, 0.230000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2011, 1, 'Mavindu', 37.419998, -1.620000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2012, 1, 'Mavuria', 37.650002, -0.780000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2013, 1, 'Maweu', 39.470001, -3.930000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2014, 1, 'Mbagathi', 36.770000, -1.380000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2015, 1, 'Mbajumali', 41.130001, -2.080000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2016, 1, 'Mbalambala', 39.070000, -0.040000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2017, 1, 'Mbale', 38.380001, -3.400000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2018, 1, 'Mbaoni', 40.020000, -3.150000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2019, 1, 'Mbare ya Mwehia', 36.950001, -0.970000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2020, 1, 'Mbari', 36.950001, -0.970000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2021, 1, 'Mbari Ya Njiru', 36.700001, -1.280000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2022, 1, 'Mbari ya Mwehia', 36.950001, -0.970000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2023, 1, 'Mbari-ya-Igi', 36.849998, -1.070000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2024, 1, 'Mbari-ya-hiti', 37.029999, -0.730000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2025, 1, 'Mbauini', 36.619999, -0.970000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2026, 1, 'Mbauro', 38.369999, -3.420000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2027, 1, 'Mbegani', 39.180000, -4.350000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2028, 1, 'Mbevoni', 38.380001, -1.900000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2029, 1, 'Mbilini Group Ranch', 37.430000, -2.200000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2030, 1, 'Mbilini Ranch', 37.430000, -2.200000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2031, 1, 'Mbiri', 37.400002, -0.480000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2032, 1, 'Mbirikani', 37.529999, -2.530000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2033, 1, 'Mbita', 34.200001, -0.420000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2034, 1, 'Mbogoro', 36.849998, -0.950000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2035, 1, 'Mbonea', 40.099998, -2.940000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2036, 1, 'Mbongo', 39.450001, -3.670000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2037, 1, 'Mbooni', 37.450001, -1.670000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2038, 1, 'Mbooni Mission', 37.470001, -1.620000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2039, 1, 'Mbotela', 36.869999, -1.300000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2040, 1, 'Mbugua', 37.080002, -0.780000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2041, 1, 'Mbuinzau', 37.900002, -2.370000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2042, 1, 'Mbuji', 39.099998, -4.450000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2043, 1, 'Mbumbuni', 37.549999, -1.620000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2044, 1, 'Mbunboni', 39.630001, -3.850000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2045, 1, 'Mbungoni', 39.630001, -3.850000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2046, 1, 'Mbuvu', 38.430000, -0.750000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2047, 1, 'Mbuyuni', 39.529999, -3.950000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2048, 1, 'Mbuzia', 38.119999, -1.280000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2049, 1, 'Mbwara Maganga', 40.900002, -2.220000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2050, 1, 'Mbweka', 34.150002, 0.450000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2051, 1, 'Mbwekas', 34.150002, 0.450000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2052, 1, 'Mbwinzau', 37.900002, -2.370000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2053, 1, 'Mdundonyi', 38.320000, -3.380000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2054, 1, 'Mdzimure', 39.480000, -3.730000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2055, 1, 'Meari', 34.270000, -0.350000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2056, 1, 'Mecun Farm', 35.330002, 0.370000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2057, 1, 'Meda', 39.970001, -3.320000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2058, 1, 'Megun Farm', 35.330002, 0.370000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2059, 1, 'Mehuru', 34.119999, -1.020000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2060, 1, 'Melawa', 36.430000, -0.620000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2061, 1, 'Melewa', 36.430000, -0.620000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2062, 1, 'Melka Meri', 40.779999, 4.270000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2063, 1, 'Melwa Ranch', 36.430000, 0.280000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2064, 1, 'Memerush', 37.549999, -2.370000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2065, 1, 'Menengai', 36.700001, -1.130000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2066, 1, 'Mere', 40.029999, -3.220000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2067, 1, 'Meri', 39.939999, 0.470000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2068, 1, 'Merti', 38.669998, 1.070000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2069, 1, 'Meru', 37.650002, 0.050000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2070, 1, 'Merueshi', 37.549999, -2.370000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2071, 1, 'Merueshi Ranch', 37.529999, -2.250000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2072, 1, 'Metka', 35.619999, 0.170000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2073, 1, 'Metkei', 35.619999, 0.170000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2074, 1, 'Meu', 37.570000, -1.520000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2075, 1, 'Mevoni', 38.380001, -1.900000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2076, 1, 'Mgamboni', 39.400002, -3.580000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2077, 1, 'Mgambonyi', 38.369999, -3.330000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2078, 1, 'Mgange', 38.320000, -3.400000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2079, 1, 'Mgangi', 38.320000, -3.380000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2080, 1, 'Mgengi', 38.320000, -3.400000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2081, 1, 'Mguya', 39.200001, -4.670000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2082, 1, 'Mianga', 34.400002, 0.560000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2083, 1, 'Michatha Farm', 35.820000, -0.270000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2084, 1, 'Mida', 39.970001, -3.320000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2085, 1, 'Midoina', 39.320000, -3.550000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2086, 1, 'Mienzeni', 39.480000, -4.020000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2087, 1, 'Migoko', 34.320000, -0.370000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2088, 1, 'Migori', 34.470001, -1.070000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2089, 1, 'Miguta', 36.830002, -1.070000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2090, 1, 'Miharati', 36.480000, -0.400000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2091, 1, 'Mihuti', 36.970001, -0.650000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2092, 1, 'Miirini', 37.080002, -0.570000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2093, 1, 'Mikinduni', 40.049999, -1.530000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2094, 1, 'Mikinduri', 37.830002, 0.120000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2095, 1, 'Mikuani', 39.169998, -4.370000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2096, 1, 'Milalani', 39.450001, -4.470000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2097, 1, 'Mile 46', 36.580002, -1.880000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2098, 1, 'Milimani', 40.840000, -1.780000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2099, 1, 'Milimani East', 40.830002, -1.770000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2100, 1, 'Milimani West', 40.820000, -1.780000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2101, 1, 'Milinga', 40.970001, -2.280000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2102, 1, 'Minjila', 40.130001, -2.320000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2103, 1, 'Mirarani', 39.650002, -3.980000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2104, 1, 'Mirieri', 34.430000, -0.070000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2105, 1, 'Mirihini', 39.570000, -4.000000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2106, 1, 'Miritini', 39.570000, -4.000000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2107, 1, 'Mirogi', 34.400002, -0.720000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2108, 1, 'Mirundu', 37.029999, -0.550000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2109, 1, 'Misageni', 39.630001, -3.920000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2110, 1, 'Misaroni', 39.099998, -4.070000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2111, 1, 'Mitchell Park', 36.770000, -1.300000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2112, 1, 'Mitembuka', 38.549999, -1.670000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2113, 1, 'Miti ya Hunter', 35.180000, 0.320000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2114, 1, 'Mitsajeni', 39.630001, -3.920000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2115, 1, 'Mitsolokanani', 39.630001, -3.970000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2116, 1, 'Mitsolokani', 39.630001, -3.970000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2117, 1, 'Mitubiri Ranch', 37.150002, -0.980000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2118, 1, 'Mitungugu', 37.779999, -0.100000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2119, 1, 'Mitunguu', 37.779999, -0.100000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2120, 1, 'Miu', 37.570000, -1.520000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2121, 1, 'Miyare', 34.270000, -0.350000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2122, 1, 'Mizijini', 40.000000, -2.970000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2123, 1, 'Mjini', 37.150002, -0.720000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2124, 1, 'Mkambini', 39.080002, -4.550000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2125, 1, 'Mkapuwanzee', 38.549999, -0.680000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2126, 1, 'Mkokoni', 41.299999, -1.970000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2127, 1, 'Mkomaniboi', 40.099998, -1.620000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2128, 1, 'Mkomba', 39.270000, -4.300000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2129, 1, 'Mkondo wa Simiti', 40.130001, -2.980000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2130, 1, 'Mkongani', 39.799999, -3.680000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2131, 1, 'Mkonumbi', 40.700001, -2.310000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2132, 1, 'Mkonumbia', 40.700001, -2.310000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2133, 1, 'Mkowe', 40.860001, -2.230000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2134, 1, 'Mkuki', 35.680000, -0.470000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2135, 1, 'Mkuluni', 39.419998, -3.730000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2136, 1, 'Mkunguni', 39.619999, -4.070000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2137, 1, 'Mkunumbi', 40.700001, -2.310000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2138, 1, 'Mkwiro', 39.400002, -4.670000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2139, 1, 'Mlegwa', 38.320000, -3.480000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2140, 1, 'Mlimani', 39.549999, -3.350000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2141, 1, 'Mnarani', 39.849998, -3.630000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2142, 1, 'Mnazi Moja', 39.480000, -3.680000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2143, 1, 'Mnazini', 40.150002, -1.980000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2144, 1, 'Mnazinia', 40.150002, -1.980000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2145, 1, 'Mndrani', 39.849998, -3.630000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2146, 1, 'Mnyenzeni', 39.480000, -3.780000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2147, 1, 'Mogoiri', 37.020000, -0.750000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2148, 1, 'Mogotio', 35.970001, -0.020000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2149, 1, 'Mogwooni', 36.980000, 0.180000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2150, 1, 'Mohanda Arunde', 34.500000, 0.110000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2151, 1, 'Mohoru', 34.119999, -1.020000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2152, 1, 'Moi', 36.779999, -1.320000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2153, 1, 'Moi''s Bridge', 35.119999, 0.880000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2154, 1, 'Moiben', 35.380001, 0.820000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2155, 1, 'Moi?s Bridge', 35.119999, 0.880000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2156, 1, 'Mokowe', 40.860001, -2.230000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2157, 1, 'Molo', 35.730000, -0.250000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2158, 1, 'Momba Sasa', 40.529999, -2.380000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2159, 1, 'Mombasa', 39.669998, -4.050000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2160, 1, 'Mombassa', 39.669998, -4.050000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2161, 1, 'Monte Carlo Ranch', 36.930000, -0.330000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2162, 1, 'Morengai Farm', 37.130001, 0.050000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2163, 1, 'Morkwijit', 35.080002, 1.200000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2164, 1, 'Morukorg', 35.080002, 1.970000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2165, 1, 'Mosiro', 36.099998, -1.480000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2166, 1, 'Moya', 39.619999, -3.930000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2167, 1, 'Moyale', 39.070000, 3.520000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2168, 1, 'Moyben', 35.380001, 0.820000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2169, 1, 'Moyo Drift Farms', 36.869999, -0.270000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2170, 1, 'Mpala Farm', 36.880001, 0.330000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2171, 1, 'Mrangi', 38.450001, -3.300000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2172, 1, 'Mrefu Farm', 37.020000, -1.120000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2173, 1, 'Mreru', 36.480000, 0.070000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2174, 1, 'Mrugua', 38.299999, -3.430000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2175, 1, 'Msabaha', 40.049999, -3.270000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2176, 1, 'Msambweni', 39.480000, -4.470000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2177, 1, 'Msanga', 40.680000, -2.250000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2178, 1, 'Msangatifu', 39.470001, -3.920000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2179, 1, 'Msau', 38.400002, -3.420000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2180, 1, 'Msomarini', 40.779999, -2.100000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2181, 1, 'Msuakini', 40.709999, -2.430000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2182, 1, 'Msumarini', 40.779999, -2.100000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2183, 1, 'Mtaa', 39.330002, -4.000000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2184, 1, 'Mtembur', 35.049999, 1.350000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2185, 1, 'Mteza', 39.099998, -4.430000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2186, 1, 'Mtito Andei', 38.169998, -2.680000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2187, 1, 'Mto Panga', 39.700001, -4.000000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2188, 1, 'Mtongwe', 39.630001, -4.070000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2189, 1, 'Mtoni Farm', 35.900002, -0.080000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2190, 1, 'Mtsangatifu', 39.470001, -3.920000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2191, 1, 'Mtulu', 39.450001, -3.670000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2192, 1, 'Mtungi', 38.380001, -3.470000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2193, 1, 'Mubuko', 38.130001, -1.930000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2194, 1, 'Mucakuthi', 37.320000, -0.570000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2195, 1, 'Mucharage', 36.849998, -0.570000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2196, 1, 'Muchatha', 36.779999, -1.200000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2197, 1, 'Muchiene', 37.529999, 0.100000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2198, 1, 'Muchumo', 37.599998, -0.480000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2199, 1, 'Muddo Gashi', 39.180000, 0.730000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2200, 1, 'Mudomo', 39.230000, -3.900000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2201, 1, 'Mugaga', 36.650002, -1.180000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2202, 1, 'Mugeka', 37.099998, -0.650000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2203, 1, 'Mugerin', 36.029999, 0.100000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2204, 1, 'Mugie Limited', 36.599998, 0.720000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2205, 1, 'Mugoiri', 37.020000, -0.750000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2206, 1, 'Mugome', 39.180000, -4.400000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2207, 1, 'Mugomoini', 36.980000, -0.950000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2208, 1, 'Mugueni', 36.869999, -1.070000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2209, 1, 'Muguga', 36.650002, -1.180000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2210, 1, 'Mugumoini', 37.369999, -0.630000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2211, 1, 'Mugumoni', 37.369999, -0.630000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2212, 1, 'Mugurin', 36.029999, 0.100000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2213, 1, 'Mugus', 35.380001, 1.850000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2214, 1, 'Muguss', 35.380001, 1.850000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2215, 1, 'Muhaka Mbavu', 39.500000, -4.330000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2216, 1, 'Muhanda', 34.500000, 0.110000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2217, 1, 'Muhoro', 34.119999, -1.020000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2218, 1, 'Muhoroni', 35.200001, -0.150000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2219, 1, 'Muhutetu', 36.369999, 0.250000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2220, 1, 'Muiga', 36.900002, -0.320000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2221, 1, 'Muirungi', 36.880001, -0.600000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2222, 1, 'Mukaa', 37.320000, -1.850000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2223, 1, 'Mukangu', 36.930000, -0.850000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2224, 1, 'Mukawa', 36.669998, 1.200000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2225, 1, 'Mukinduri', 37.270000, -0.530000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2226, 1, 'Mukore', 37.099998, -0.530000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2227, 1, 'Mukui', 37.070000, -0.600000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2228, 1, 'Mukuki', 35.680000, -0.470000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2229, 1, 'Mukunguni', 40.029999, -3.130000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2230, 1, 'Mukuria', 36.930000, -0.820000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2231, 1, 'Mukurue', 36.950001, -0.900000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2232, 1, 'Mukuruwe', 36.950001, -0.900000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2233, 1, 'Mukurwe', 36.950001, -0.900000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2234, 1, 'Mukus', 35.380001, 1.850000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2235, 1, 'Mukutan', 36.270000, 0.630000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2236, 1, 'Mukutano', 37.529999, -2.530000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2237, 1, 'Mukuyu', 37.180000, -0.900000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2238, 1, 'Mulala', 37.480000, -2.020000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2239, 1, 'Mulemwa', 38.650002, -3.830000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2240, 1, 'Muliloni', 39.080002, -4.130000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2241, 1, 'Mulunguni', 40.000000, -2.800000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2242, 1, 'Mumbethana', 37.650002, -1.200000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2243, 1, 'Mumbo', 34.330002, -0.350000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2244, 1, 'Mumias', 34.490002, 0.340000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2245, 1, 'Munani', 38.080002, -2.380000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2246, 1, 'Munge', 39.450001, -4.520000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2247, 1, 'Munguvini', 40.160000, -2.020000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2248, 1, 'Mununca', 36.980000, -0.970000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2249, 1, 'Mununga', 37.020000, -0.700000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2250, 1, 'Munyange One', 36.880001, -0.500000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2251, 1, 'Munyange Two', 36.869999, -0.500000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2252, 1, 'Murabara', 37.369999, -0.670000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2253, 1, 'Muramati', 37.080002, 0.120000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2254, 1, 'Murang''a', 37.150002, -0.720000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2255, 1, 'Murang?a', 37.150002, -0.720000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2256, 1, 'Murarandia One', 36.930000, -0.730000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2257, 1, 'Murarandia Two', 36.900002, -0.730000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2258, 1, 'Mureko', 34.480000, 0.230000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2259, 1, 'Murengeti', 36.630001, -1.070000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2260, 1, 'Mureru Farm', 37.029999, -0.120000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2261, 1, 'Muriling', 35.930000, 2.450000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2262, 1, 'Murinduko', 37.450001, -0.570000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2263, 1, 'Muringari', 37.730000, -0.480000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2264, 1, 'Muritu', 39.770000, -2.980000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2265, 1, 'Murka', 37.930000, -3.370000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2266, 1, 'Murua Korg', 35.080002, 1.970000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2267, 1, 'Murubara', 37.369999, -0.670000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2268, 1, 'Muruguru', 37.029999, -0.470000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2269, 1, 'Murundu', 37.029999, -0.550000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2270, 1, 'Mururiini', 37.180000, -0.580000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2271, 1, 'Mururuwe', 36.950001, -1.020000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2272, 1, 'Musa Nyandisi', 34.830002, -0.720000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2273, 1, 'Musanda', 34.450001, 0.220000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2274, 1, 'Musereita', 35.049999, -0.680000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2275, 1, 'Mushamba', 37.500000, -0.770000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2276, 1, 'Musikio', 38.150002, -1.630000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2277, 1, 'Musoma', 34.169998, 0.380000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2278, 1, 'Mustuni Farm', 35.549999, -0.300000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2279, 1, 'Mutamayo Limited', 35.779999, -0.250000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2280, 1, 'Mutaro Ranch', 36.599998, 0.030000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2281, 1, 'Mutathiini', 37.070000, -0.480000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2282, 1, 'Mutei', 36.700001, -1.280000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2283, 1, 'Mutet', 34.750000, -0.030000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2284, 1, 'Mutha', 38.430000, -1.800000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2285, 1, 'Muthaiga', 36.830002, -1.250000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2286, 1, 'Muthara', 37.799999, 0.220000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2287, 1, 'Muthinga', 37.020000, -0.500000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2288, 1, 'Muthithi', 37.029999, -0.820000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2289, 1, 'Muthue', 38.270000, -1.930000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2290, 1, 'Muthurua', 36.750000, -1.170000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2291, 1, 'Muthurua Farm', 36.700001, -1.170000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2292, 1, 'Muthurwa', 36.750000, -1.170000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2293, 1, 'Muthuthiini', 37.049999, -0.600000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2294, 1, 'Muthuthini', 37.049999, -0.600000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2295, 1, 'Mutito', 37.000000, -0.580000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2296, 1, 'Mutitu', 38.180000, -1.230000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2297, 1, 'Mutitu Andei', 38.169998, -2.680000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2298, 1, 'Mutomo', 38.200001, -1.850000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2299, 1, 'Mutonguni', 37.970001, -1.180000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2300, 1, 'Mutuini', 36.700001, -1.300000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2301, 1, 'Mutuli', 39.500000, -3.550000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2302, 1, 'Mutulia', 38.450001, -0.470000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2303, 1, 'Mutune', 38.020000, -1.320000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2304, 1, 'Mutuni', 38.020000, -1.320000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2305, 1, 'Muuathi', 36.779999, -1.130000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2306, 1, 'Muweri', 34.200001, 0.100000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2307, 1, 'Muyaka', 36.720001, -1.180000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2308, 1, 'Mvindeni', 41.330002, -1.920000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2309, 1, 'Mvita', 39.669998, -4.050000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2310, 1, 'Mvuleni', 39.480000, -4.330000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2311, 1, 'Mwabani', 39.470001, -3.720000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2312, 1, 'Mwabaya Nyundo', 39.599998, -3.820000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2313, 1, 'Mwabila', 39.480000, -4.280000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2314, 1, 'Mwachawaza', 38.380001, -3.430000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2315, 1, 'Mwachirunge', 39.669998, -3.950000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2316, 1, 'Mwachirunge Bomu', 39.650002, -3.930000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2317, 1, 'Mwachirunje ya Pwani', 39.650002, -3.950000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2318, 1, 'Mwaembe', 39.450001, -4.500000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2319, 1, 'Mwaga', 39.700001, -3.230000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2320, 1, 'Mwaketutu', 38.369999, -3.480000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2321, 1, 'Mwakinyungu', 38.419998, -3.420000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2322, 1, 'Mwakirunge', 39.669998, -3.950000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2323, 1, 'Mwakirunge Kuu', 39.650002, -3.930000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0);
INSERT INTO `loctowns` (`idloctowns`, `loccountry_idloccountry`, `locationname`, `lng`, `lat`, `createdby`, `createdon`, `validatedby`, `validatedon`, `is_valid`, `is_town`) VALUES
(2324, 1, 'Mwakirunge ya Pwani', 39.650002, -3.950000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2325, 1, 'Mwakitau', 38.130001, -3.400000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2326, 1, 'Mwakunde', 39.619999, -3.920000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2327, 1, 'Mwakwala', 39.500000, -3.650000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2328, 1, 'Mwalili', 39.680000, -3.520000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2329, 1, 'Mwalilo', 39.619999, -3.920000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2330, 1, 'Mwalio', 39.619999, -3.920000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2331, 1, 'Mwaluvanga', 39.349998, -4.420000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2332, 1, 'Mwambani', 38.020000, -1.630000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2333, 1, 'Mwambathana', 37.650002, -1.200000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2334, 1, 'Mwambiri', 39.799999, -3.100000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2335, 1, 'Mwamkuchi', 39.150002, -4.380000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2336, 1, 'Mwana Mwinga', 39.549999, -3.750000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2337, 1, 'Mwanachini', 39.180000, -4.420000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2338, 1, 'Mwanamare', 39.529999, -3.800000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2339, 1, 'Mwanathamba', 40.200001, -2.430000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2340, 1, 'Mwanathumba', 40.200001, -2.430000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2341, 1, 'Mwanda', 38.270000, -3.420000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2342, 1, 'Mwandimu', 39.020000, -4.170000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2343, 1, 'Mwandoni', 39.630001, -3.980000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2344, 1, 'Mwangareme', 37.650002, -0.270000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2345, 1, 'Mwangarimwe', 37.650002, -0.270000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2346, 1, 'Mwangini', 38.119999, -2.130000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2347, 1, 'Mwangoni', 39.200001, -4.050000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2348, 1, 'Mwangulu', 39.130001, -4.420000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2349, 1, 'Mwanyora', 39.099998, -4.570000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2350, 1, 'Mwarakaya', 39.700001, -3.800000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2351, 1, 'Mwarongo', 39.200001, -4.620000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2352, 1, 'Mwarungu', 38.349998, -3.370000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2353, 1, 'Mwatate', 38.380001, -3.500000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2354, 1, 'Mwathaini', 37.369999, -0.650000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2355, 1, 'Mwazare', 39.330002, -4.450000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2356, 1, 'Mwazi', 41.220001, -1.900000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2357, 1, 'Mwe', 37.980000, -1.550000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2358, 1, 'Mweiga', 36.900002, -0.320000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2359, 1, 'Mweri', 36.680000, -0.980000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2360, 1, 'Mweru', 37.080002, -0.670000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2361, 1, 'Mwewe', 38.029999, -1.550000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2362, 1, 'Mweza', 39.400002, -3.680000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2363, 1, 'Mwimuto', 36.869999, -0.930000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2364, 1, 'Mwingi', 38.070000, -0.930000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2365, 1, 'Mwiyogo', 36.869999, -0.270000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2366, 1, 'Mwogahendi', 40.020000, -1.430000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2367, 1, 'Myabogi', 41.130001, -2.080000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2368, 1, 'Myanga', 34.400002, 0.560000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2369, 1, 'Naboro Farm', 36.630001, 0.220000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2370, 1, 'Naiberi Farm', 35.369999, 0.480000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2371, 1, 'Nairagie Ngare', 36.169998, -1.050000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2372, 1, 'Nairoba', 36.820000, -1.280000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2373, 1, 'Nairobi', 36.820000, -1.280000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2374, 1, 'Nairobi Hill', 36.820000, -1.300000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2375, 1, 'Nairobi Industrial Area', 36.830002, -1.300000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2376, 1, 'Nairobi South', 36.830002, -1.320000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2377, 1, 'Nakatur', 35.180000, 4.630000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2378, 1, 'Nakinglas', 34.970001, 2.200000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2379, 1, 'Nakwajit', 35.080002, 1.200000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2380, 1, 'Namabusi', 33.980000, 0.070000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2381, 1, 'Namanga', 36.779999, -2.550000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2382, 1, 'Nambale', 34.250000, 0.450000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2383, 1, 'Nambare', 34.250000, 0.450000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2384, 1, 'Namiaso', 34.080002, 0.320000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2385, 1, 'Namyoso', 34.080002, 0.320000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2386, 1, 'Nandi', 35.099998, 0.200000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2387, 1, 'Nandi Hills', 35.180000, 0.120000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2388, 1, 'Nanga', 34.730000, -0.150000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2389, 1, 'Nangina', 34.099998, 0.280000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2390, 1, 'Nango', 34.730000, -0.150000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2391, 1, 'Nani', 39.880001, -0.850000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2392, 1, 'Nanig', 39.880001, -0.850000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2393, 1, 'Nanigi East', 39.880001, -0.850000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2394, 1, 'Nanigi West', 39.849998, -0.860000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2395, 1, 'Nanyuki', 37.070000, 0.020000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2396, 1, 'Naro Moru', 37.020000, -0.170000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2397, 1, 'Narok', 35.869999, -1.080000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2398, 1, 'Narok Ranch', 36.580002, 0.380000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2399, 1, 'Narosura', 35.869999, -1.530000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2400, 1, 'Narosurra Farm', 35.779999, 0.050000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2401, 1, 'Nasibu', 39.680000, -3.480000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2402, 1, 'Naso Ranch', 36.970001, 0.430000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2403, 1, 'Nchimwani', 38.029999, -1.600000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2404, 1, 'Ndakalu', 34.450001, 0.730000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2405, 1, 'Ndakaru', 34.450001, 0.730000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2406, 1, 'Ndakulu', 34.450001, 0.730000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2407, 1, 'Ndambwe', 40.720001, -2.320000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2408, 1, 'Ndamichoni', 35.180000, -0.800000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2409, 1, 'Ndanai', 35.099998, -0.830000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2410, 1, 'Ndaragwa', 36.520000, -0.070000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2411, 1, 'Ndaroini', 37.150002, -0.500000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2412, 1, 'Ndarugu Farm', 37.080002, -1.080000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2413, 1, 'Ndathi', 37.119999, -0.300000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2414, 1, 'Ndau', 35.950001, 0.550000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2415, 1, 'Ndavaya', 39.169998, -4.250000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2416, 1, 'Ndavayas', 39.169998, -4.250000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2417, 1, 'Ndenderu', 36.750000, -1.180000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2418, 1, 'Ndere', 34.279999, 0.110000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2419, 1, 'Nderi', 36.650002, -1.220000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2420, 1, 'Nderitu Farm', 36.950001, -0.330000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2421, 1, 'Nderu', 36.599998, -1.150000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2422, 1, 'Ndhambwe', 40.720001, -2.320000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2423, 1, 'Ndhiwa', 34.369999, -0.730000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2424, 1, 'Ndiaini One', 36.980000, -0.570000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2425, 1, 'Ndiaini Two', 37.000000, -0.570000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2426, 1, 'Ndiara', 36.500000, -0.300000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2427, 1, 'Ndile', 38.480000, -3.330000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2428, 1, 'Ndiloi Farm', 36.230000, 0.070000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2429, 1, 'Ndimaini', 37.130001, -0.520000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2430, 1, 'Ndindiruku', 37.430000, -0.720000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2431, 1, 'Ndioni', 36.599998, -1.100000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2432, 1, 'Ndiuini', 36.580002, -1.100000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2433, 1, 'Ndome', 38.480000, -3.300000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2434, 1, 'Ndonga', 37.070000, -0.870000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2435, 1, 'Ndooa', 38.180000, -1.230000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2436, 1, 'Ndulele', 36.080002, -1.100000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2437, 1, 'Ndulelei', 36.080002, -1.100000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2438, 1, 'Nduma', 37.049999, -0.570000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2439, 1, 'Ndumberi', 36.799999, -1.150000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2440, 1, 'Ndundu-ini', 37.330002, -0.430000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2441, 1, 'Ndunyu', 37.029999, -0.820000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2442, 1, 'Ndunyu Chege', 36.919998, -0.870000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2443, 1, 'Ndurarua', 36.700001, -1.230000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2444, 1, 'Nduru', 40.279999, -2.500000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2445, 1, 'Ndurumo', 36.430000, 0.380000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2446, 1, 'Ndurutu', 37.020000, -0.430000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2447, 1, 'Nembu', 36.880001, -1.050000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2448, 1, 'Nembure', 37.520000, -0.470000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2449, 1, 'Ngababa', 36.720001, -1.220000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2450, 1, 'Ngama Ranch', 37.299999, -2.150000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2451, 1, 'Ngambinyi', 38.330002, -3.420000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2452, 1, 'Ngandani', 38.020000, -2.370000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2453, 1, 'Ngandu', 37.099998, -0.450000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2454, 1, 'Ngandure', 37.599998, -0.700000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2455, 1, 'Nganja', 39.470001, -4.470000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2456, 1, 'Nganukonharengak', 35.619999, 2.350000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2457, 1, 'Ngao', 40.209999, -2.420000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2458, 1, 'Ngarariga', 36.619999, -1.080000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2459, 1, 'Ngare Ndare Farm', 37.349998, 0.230000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2460, 1, 'Ngaru', 36.869999, -0.600000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2461, 1, 'Ngata Farm', 35.980000, -0.270000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2462, 1, 'Ngathune', 38.279999, -0.230000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2463, 1, 'Ngau Mission', 40.209999, -2.420000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2464, 1, 'Ngecha', 36.669998, -1.170000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2465, 1, 'Ngema Farm', 36.529999, -1.080000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2466, 1, 'Ngemwa', 36.820000, -1.120000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2467, 1, 'Ngenwa', 36.820000, -1.120000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2468, 1, 'Ngeranyi', 38.330002, -3.420000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2469, 1, 'Ngerenyi', 38.330002, -3.420000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2470, 1, 'Ngesedai Farm', 35.950001, -0.120000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2471, 1, 'Ngethu', 36.900002, -0.920000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2472, 1, 'Ngewa', 36.869999, -1.080000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2473, 1, 'Nghonji', 37.730000, -3.450000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2474, 1, 'Ngia', 34.369999, 0.040000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2475, 1, 'Nginda', 40.669998, -2.070000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2476, 1, 'Ngindo', 39.630001, -3.930000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2477, 1, 'Nginduri', 36.869999, -1.070000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2478, 1, 'Nginyang', 36.020000, 0.970000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2479, 1, 'Ngiri', 34.150002, -0.900000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2480, 1, 'Ngiriambu', 37.380001, -0.480000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2481, 1, 'Ngiva', 34.369999, 0.040000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2482, 1, 'Ngiya', 34.369999, 0.040000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2483, 1, 'Ngombani', 39.119999, -4.380000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2484, 1, 'Ngombeni', 39.619999, -4.130000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2485, 1, 'Ngomeni', 38.400002, -0.650000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2486, 1, 'Ngomheni', 39.619999, -4.130000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2487, 1, 'Ngong', 36.650002, -1.370000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2488, 1, 'Ngorare Ranch', 36.669998, 0.220000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2489, 1, 'Ngorigaishi', 36.919998, -1.970000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2490, 1, 'Ngoriundito', 36.630001, -1.200000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2491, 1, 'Ngosini East', 37.750000, -1.800000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2492, 1, 'Ngosini West', 37.700001, -1.830000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2493, 1, 'Ngosini-East Detention Camp', 37.750000, -1.800000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2494, 1, 'Ngosini-West Detention Camp', 37.700001, -1.830000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2495, 1, 'Ngovie', 37.450001, -0.420000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2496, 1, 'Ngovio', 37.450001, -0.420000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2497, 1, 'Nguka', 37.320000, -0.670000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2498, 1, 'Nguna', 36.930000, -0.950000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2499, 1, 'Ngunduri', 36.869999, -1.070000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2500, 1, 'Ngunguru', 37.080002, -0.520000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2501, 1, 'Nguni', 37.950001, -1.520000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2502, 1, 'Ngure', 36.680000, -1.230000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2503, 1, 'Nguruweni', 39.419998, -4.470000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2504, 1, 'Ngusishi Farm', 37.299999, 0.080000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2505, 1, 'Ngutuni', 38.630001, -3.420000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2506, 1, 'Nguu', 37.599998, -2.130000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2507, 1, 'Nguu Ranching Cooperative Society', 37.599998, -2.130000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2508, 1, 'Nguuni', 38.369999, -1.950000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2509, 1, 'Nguviu', 37.450001, -0.420000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2510, 1, 'Ngwaru', 39.349998, -4.450000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2511, 1, 'Ngwataniro Farm', 35.750000, -0.320000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2512, 1, 'Ngwatawiro', 35.750000, -0.320000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2513, 1, 'Ngwate', 38.250000, -2.070000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2514, 1, 'Ngwena', 40.029999, -1.450000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2515, 1, 'Niandarawa', 36.619999, -0.550000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2516, 1, 'Nitimaini', 37.130001, -0.430000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2517, 1, 'Njabini', 36.669998, -0.720000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2518, 1, 'Njega', 37.320000, -0.620000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2519, 1, 'Njegas', 37.279999, -0.570000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2520, 1, 'Njege', 37.070000, -0.570000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2521, 1, 'Njele', 39.470001, -4.320000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2522, 1, 'Njigari', 36.849998, -0.500000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2523, 1, 'Njiku', 36.730000, -1.170000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2524, 1, 'Njora', 37.049999, -0.820000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2525, 1, 'Nkama Group Ranch', 37.299999, -2.150000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2526, 1, 'Nkidongi', 36.570000, -1.050000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2527, 1, 'Nkunumbi', 40.700001, -2.310000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2528, 1, 'Nnundu', 36.799999, -0.970000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2529, 1, 'Noiwe', 35.930000, 0.070000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2530, 1, 'Noolpopong', 36.049999, -1.170000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2531, 1, 'Norpopong', 36.049999, -1.170000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2532, 1, 'North Horr', 37.070000, 3.320000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2533, 1, 'Nosuguro', 36.130001, 0.580000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2534, 1, 'Nrundu', 36.799999, -0.970000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2535, 1, 'Nthwani', 38.029999, -1.600000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2536, 1, 'Ntuka', 35.900002, -1.330000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2537, 1, 'Ntulelei', 36.080002, -1.100000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2538, 1, 'Nundu', 36.799999, -0.970000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2539, 1, 'Nunguni', 37.369999, -1.800000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2540, 1, 'Nunjoro Farm', 36.470001, -0.630000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2541, 1, 'Nyabasi', 34.580002, -1.300000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2542, 1, 'Nyabassi', 34.580002, -1.300000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2543, 1, 'Nyabera', 34.730000, -0.600000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2544, 1, 'Nyabera''s', 34.730000, -0.600000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2545, 1, 'Nyabera?s', 34.730000, -0.600000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2546, 1, 'Nyadorera', 34.099998, 0.120000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2547, 1, 'Nyaga', 36.849998, -1.080000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2548, 1, 'Nyagachugu', 37.020000, -0.800000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2549, 1, 'Nyagatugu', 36.869999, -0.650000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2550, 1, 'Nyahururu', 36.369999, 0.030000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2551, 1, 'Nyahururu Falls', 36.369999, 0.030000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2552, 1, 'Nyakahuho', 37.020000, -0.580000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2553, 1, 'Nyakahura', 37.099998, -0.700000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2554, 1, 'Nyalani', 39.119999, -4.100000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2555, 1, 'Nyali', 37.180000, -0.850000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2556, 1, 'Nyalilpuch', 36.150002, 0.230000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2557, 1, 'Nyamakuyu', 36.880001, -0.400000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2558, 1, 'Nyambogi', 41.130001, -2.080000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2559, 1, 'Nyamindi', 37.419998, -0.670000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2560, 1, 'Nyamuga', 34.549999, -0.730000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2561, 1, 'Nyamware', 34.799999, -0.180000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2562, 1, 'Nyandarua', 36.619999, -0.550000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2563, 1, 'Nyanduma', 36.730000, -0.970000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2564, 1, 'Nyang''oma', 34.349998, -0.020000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2565, 1, 'Nyangi Farm', 37.020000, -0.300000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2566, 1, 'Nyangiti', 37.049999, -0.680000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2567, 1, 'Nyangore', 40.349998, -2.330000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2568, 1, 'Nyangoro', 40.349998, -2.330000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2569, 1, 'Nyangoso', 34.849998, -0.930000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2570, 1, 'Nyangusu', 34.849998, -0.930000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2571, 1, 'Nyangwani', 40.020000, -1.250000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2572, 1, 'Nyangweso', 34.459999, 0.080000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2573, 1, 'Nyang?oma', 34.349998, -0.020000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2574, 1, 'Nyaru', 35.580002, 0.330000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2575, 1, 'Nyati', 37.180000, -0.850000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2576, 1, 'Nyawita', 34.419998, -0.680000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2577, 1, 'Nyenga', 34.099998, -0.720000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2578, 1, 'Nyeri', 36.950001, -0.420000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2579, 1, 'Nyeru', 35.580002, 0.330000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2580, 1, 'Nyika', 39.220001, -3.880000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2581, 1, 'Nyiri', 36.950001, -0.420000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2582, 1, 'Nyiro', 36.299999, 0.580000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2583, 1, 'Nyiroko Farm', 36.369999, -0.400000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2584, 1, 'Nyota', 35.700001, -0.350000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2585, 1, 'Nyowita', 34.400002, -0.680000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2586, 1, 'Nyundo', 39.599998, -3.820000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2587, 1, 'Oasis Farm', 35.299999, 0.470000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2588, 1, 'Obila', 34.450001, -0.970000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2589, 1, 'Obila''s Village', 34.450001, -0.970000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2590, 1, 'Obila?s Village', 34.450001, -0.970000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2591, 1, 'Obo', 40.000000, -1.350000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2592, 1, 'Oda', 40.209999, -2.480000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2593, 1, 'Odd', 40.209999, -2.480000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2594, 1, 'Odok', 34.619999, -0.920000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2595, 1, 'Odoyo', 34.430000, -0.570000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2596, 1, 'Ofafa', 36.869999, -1.280000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2597, 1, 'Ogama', 34.450001, -0.630000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2598, 1, 'Ogembo', 34.720001, -0.800000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2599, 1, 'Ogomo', 34.450001, -0.630000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2600, 1, 'Ojuok', 34.549999, -0.770000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2601, 1, 'Okia', 37.500000, -1.770000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2602, 1, 'Okoth', 34.570000, -0.930000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2603, 1, 'Okumu', 34.549999, -0.920000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2604, 1, 'Ol Ari Nyiro Ranch', 36.320000, 0.500000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2605, 1, 'Ol Choro Orogwa Ranch', 35.200001, -1.100000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2606, 1, 'Ol Kejuado', 36.779999, -1.850000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2607, 1, 'Ol Lalunga', 35.669998, -1.000000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2608, 1, 'Ol Latunga', 35.669998, -1.000000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2609, 1, 'Ol Mesuti', 35.770000, -1.900000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2610, 1, 'Ol Moirogi', 36.400002, -0.580000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2611, 1, 'Ol Mukutan', 36.270000, 0.630000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2612, 1, 'Ol Olong''a', 35.669998, -1.000000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2613, 1, 'Ol Olong?a', 35.669998, -1.000000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2614, 1, 'Ol Ombokishi', 36.049999, -1.020000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2615, 1, 'Ol Omborishi', 36.049999, -1.020000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2616, 1, 'Olaragwai', 36.450001, -0.650000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2617, 1, 'Olarinyiro', 36.320000, 0.500000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2618, 1, 'Olchorro Orogwa Ranch', 35.200001, -1.100000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2619, 1, 'Old Mombasa', 39.669998, -4.050000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2620, 1, 'Old Town', 39.669998, -4.050000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2621, 1, 'Olderet', 35.279999, 0.520000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2622, 1, 'Oldoinyo Lemboro', 36.779999, -0.470000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2623, 1, 'Oldonyo Farm', 37.279999, 0.100000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2624, 1, 'Ole Seni', 36.430000, -2.230000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2625, 1, 'Olechugu Farm', 36.930000, -0.080000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2626, 1, 'Olechugua Farm', 36.930000, -0.080000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2627, 1, 'Olengarua', 36.119999, -2.080000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2628, 1, 'Oleobar Farm', 36.279999, -0.300000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2629, 1, 'Oleolondo Farm', 36.299999, -0.170000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2630, 1, 'Oleondo', 36.299999, -0.170000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2631, 1, 'Olioserri', 36.430000, -2.230000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2632, 1, 'Oljogi', 36.950001, 0.350000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2633, 1, 'Oljoro Orok', 36.369999, -0.070000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2634, 1, 'Olkeramatian', 36.130001, -1.880000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2635, 1, 'Olkeramatiani', 36.130001, -1.880000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2636, 1, 'Olkiramatian', 36.130001, -1.880000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2637, 1, 'Ollerai Farm', 36.419998, -0.800000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2638, 1, 'Olmaisor', 36.619999, 0.450000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2639, 1, 'Olmesutie', 35.770000, -1.900000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2640, 1, 'Olmesutye', 35.770000, -1.900000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2641, 1, 'Olmogogo', 36.430000, -0.570000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2642, 1, 'Olmorogi', 36.400002, -0.580000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2643, 1, 'Olngarua', 36.299999, 0.280000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2644, 1, 'Oloiserri', 36.430000, -2.230000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2645, 1, 'Oloiyangalani', 36.700001, -1.750000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2646, 1, 'Olokurto', 35.849998, -0.630000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2647, 1, 'Ololaro', 36.470001, 0.120000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2648, 1, 'Ololerai', 36.299999, -0.780000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2649, 1, 'Ololerai Farm', 36.299999, -0.780000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2650, 1, 'Olololong''a', 35.669998, -1.000000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2651, 1, 'Olololong?a', 35.669998, -1.000000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2652, 1, 'Oloyaingalani', 36.700001, -1.750000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2653, 1, 'Olpejeta', 36.820000, 0.080000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2654, 1, 'Olpinguin', 36.630001, 0.630000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2655, 1, 'Oltafeta Farm', 36.820000, -0.050000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2656, 1, 'Olteret', 35.279999, 0.520000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2657, 1, 'Oltulili', 37.169998, 0.020000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2658, 1, 'Omari', 38.320000, -3.480000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2659, 1, 'Ondiek''s', 34.299999, -0.750000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2660, 1, 'Ondiek?s', 34.299999, -0.750000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2661, 1, 'Ontulili', 37.169998, 0.020000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2662, 1, 'Opiyo', 34.570000, -0.830000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2663, 1, 'Oraimutia Farm', 36.320000, -0.120000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2664, 1, 'Orbu', 39.779999, -0.660000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2665, 1, 'Oropoi', 34.230000, 3.770000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2666, 1, 'Orwa', 35.470001, 1.670000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2667, 1, 'Oseni', 41.310001, -1.960000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2668, 1, 'Oserian', 36.279999, -0.830000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2669, 1, 'Osirua', 36.419998, -0.800000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2670, 1, 'Othaya', 36.950001, -0.570000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2671, 1, 'Otinga', 34.580002, -0.870000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2672, 1, 'Ouma''s', 34.279999, -0.900000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2673, 1, 'Ouma?s', 34.279999, -0.900000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2674, 1, 'Outer Ring Road', 36.880001, -1.270000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2675, 1, 'Oyani Farm Institute', 34.520000, -0.980000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2676, 1, 'Oyugis', 34.720001, -0.500000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2677, 1, 'Ozi', 40.459999, -2.520000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2678, 1, 'Pandanguo', 40.500000, -2.220000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2679, 1, 'Pangayambo', 39.549999, -3.800000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2680, 1, 'Pasa', 41.110001, -2.060000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2681, 1, 'Pate', 41.000000, -2.140000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2682, 1, 'Patta', 41.000000, -2.140000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2683, 1, 'Paulanne Farm', 36.419998, -0.280000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2684, 1, 'Paulo', 34.599998, -0.770000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2685, 1, 'Paza', 41.110001, -2.060000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2686, 1, 'Pemba', 39.430000, -3.950000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2687, 1, 'Pemwai', 35.770000, 0.570000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2688, 1, 'Penda Kula', 39.930000, -3.380000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2689, 1, 'Pendeza', 39.630001, -3.950000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2690, 1, 'Pep Gadera', 34.320000, -0.250000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2691, 1, 'Pepper Corn', 36.470001, -0.700000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2692, 1, 'Peppercorn Farm', 36.470001, -0.700000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2693, 1, 'Pesi', 36.580002, 0.270000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2694, 1, 'Pingilikani', 39.779999, -3.780000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2695, 1, 'Piribayat', 36.250000, 0.020000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2696, 1, 'Poi', 35.820000, 0.770000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2697, 1, 'Poka', 37.450001, -2.130000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2698, 1, 'Poka Group Ranch', 37.450001, -2.150000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2699, 1, 'Pondo', 36.430000, 0.100000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2700, 1, 'Pongwe', 39.349998, -4.550000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2701, 1, 'Port Bunyala', 33.970001, 0.100000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2702, 1, 'Port Florence', 34.750000, -0.100000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2703, 1, 'Port Harrington', 39.070000, 3.520000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2704, 1, 'Port Southby', 34.150002, -0.200000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2705, 1, 'Port Victoria', 33.970001, 0.100000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2706, 1, 'Poulton', 35.619999, -0.350000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2707, 1, 'Pumuani', 36.849998, -1.280000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2708, 1, 'Pumwani', 36.849998, -1.280000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2709, 1, 'Pundo', 34.320000, -0.780000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2710, 1, 'Pundo''s Village', 34.320000, -0.780000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2711, 1, 'Pundo?s Village', 34.320000, -0.780000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2712, 1, 'Quf Tika', 39.619999, 3.500000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2713, 1, 'Rabour', 34.799999, -0.150000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2714, 1, 'Rabur', 34.799999, -0.150000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2715, 1, 'Rabwor', 34.799999, -0.150000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2716, 1, 'Ragati', 37.150002, -0.380000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2717, 1, 'Raguta', 37.630001, -0.300000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2718, 1, 'Raiyani', 36.869999, -1.080000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2719, 1, 'Ramada', 40.029999, -2.900000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2720, 1, 'Rambara', 34.700001, -0.070000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2721, 1, 'Ramisi', 39.380001, -4.530000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2722, 1, 'Ramogi', 34.049999, 0.000000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2723, 1, 'Ramu', 41.220001, 3.920000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2724, 1, 'Ramula', 34.520000, 0.000000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2725, 1, 'Randani', 38.570000, -1.670000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2726, 1, 'Rangala', 34.330002, 0.150000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2727, 1, 'Rasini', 41.110001, -2.060000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2728, 1, 'Ratai', 37.169998, -0.400000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2729, 1, 'Raywell Farm', 36.250000, -0.120000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2730, 1, 'Renguti', 36.619999, -1.230000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2731, 1, 'Rhoka', 39.990002, -1.280000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2732, 1, 'Ria Kalui', 39.230000, -3.950000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2733, 1, 'Riabai', 36.830002, -1.170000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2734, 1, 'Riakiania', 37.220001, -0.520000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2735, 1, 'Riamukuruwe', 36.970001, -0.450000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2736, 1, 'Riamukurwe', 36.970001, -0.450000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2737, 1, 'Riana', 34.669998, -0.650000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2738, 1, 'Ribe', 39.630001, -3.880000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2739, 1, 'Ribe Mission', 39.630001, -3.880000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2740, 1, 'Ridge', 36.349998, -0.430000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2741, 1, 'Rigmio', 41.020000, -1.150000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2742, 1, 'Riitho', 36.849998, -0.970000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2743, 1, 'Riondonga', 34.849998, -0.770000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2744, 1, 'Rironi', 36.630001, -1.150000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2745, 1, 'Riruta', 36.730000, -1.280000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2746, 1, 'Rishau', 36.450001, 0.030000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2747, 1, 'Ritho', 36.849998, -0.970000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2748, 1, 'Rititi', 37.080002, -0.450000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2749, 1, 'Riuki', 36.849998, -1.100000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2750, 1, 'Rocho', 36.980000, -0.900000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2751, 1, 'Roka', 39.200001, -1.600000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2752, 1, 'Rokas', 39.990002, -1.280000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2753, 1, 'Rombala', 36.570000, 0.220000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2754, 1, 'Rombo', 37.700001, -3.050000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2755, 1, 'Rongai', 35.849998, -0.170000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2756, 1, 'Ruaganga', 37.119999, -0.870000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2757, 1, 'Ruaka', 36.779999, -1.200000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2758, 1, 'Ruambiti', 37.349998, -0.530000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2759, 1, 'Ruanganga', 37.119999, -0.870000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2760, 1, 'Ruangondu', 37.299999, -0.480000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2761, 1, 'Ruare', 36.599998, -0.080000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2762, 1, 'Rubu', 41.380001, -1.880000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2763, 1, 'Ruchu', 36.980000, -0.900000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2764, 1, 'Rudacha', 33.980000, 0.120000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2765, 1, 'Rugumu', 37.450001, -0.400000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2766, 1, 'Ruiru', 37.119999, -0.700000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2767, 1, 'Rukala', 34.000000, 0.070000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2768, 1, 'Rukanga', 37.119999, -0.720000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2769, 1, 'Rukenya', 37.330002, -0.530000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2770, 1, 'Rukira', 36.930000, -0.530000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2771, 1, 'Ruku', 36.700001, -1.200000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2772, 1, 'Rukubi', 36.700001, -1.230000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2773, 1, 'Rukurire', 37.549999, -0.380000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2774, 1, 'Rukuriri', 37.549999, -0.380000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2775, 1, 'Rumuruti', 36.500000, 0.320000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2776, 1, 'Rungiri', 36.680000, -1.230000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2777, 1, 'Rurii', 37.180000, -0.700000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2778, 1, 'Ruring''u', 36.970001, -0.430000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2779, 1, 'Ruring?u', 36.970001, -0.430000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2780, 1, 'Ruruguti', 36.880001, -0.570000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2781, 1, 'Ruruma', 39.599998, -3.900000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2782, 1, 'Rusinga', 36.180000, 0.020000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2783, 1, 'Rutara', 36.250000, -0.270000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2784, 1, 'Ruthagati', 37.070000, -0.430000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2785, 1, 'Rutura', 36.880001, -0.420000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2786, 1, 'Rwamburi Kiugu', 36.580002, -1.120000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2787, 1, 'Rwathia', 36.919998, -0.670000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2788, 1, 'Rwegetha', 36.919998, -0.920000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2789, 1, 'Saadani', 41.130001, -1.950000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2790, 1, 'Saba Farm', 36.299999, -0.280000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2791, 1, 'Sabule', 40.200001, 0.310000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2792, 1, 'Sadani', 39.320000, -4.430000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2793, 1, 'Safarisi', 40.799999, -2.200000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2794, 1, 'Sagala', 38.570000, -3.520000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2795, 1, 'Sagalla', 38.570000, -3.520000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2796, 1, 'Sagana', 37.200001, -0.670000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2797, 1, 'Saidibabo', 40.330002, -2.520000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2798, 1, 'Saikago', 37.630001, -0.580000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2799, 1, 'Sailoni', 40.180000, -2.150000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2800, 1, 'Saint Marys', 37.130001, -0.720000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2801, 1, 'Saka', 39.349998, -0.140000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2802, 1, 'Salabani', 36.049999, 0.550000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2803, 1, 'Salengai', 37.169998, -2.180000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2804, 1, 'Samaki Farm', 36.779999, -0.330000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2805, 1, 'Samara', 37.070000, -0.830000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2806, 1, 'Samburu', 39.279999, -3.770000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2807, 1, 'Samburu Ranch', 36.680000, 0.830000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2808, 1, 'Samicha', 40.320000, -2.520000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2809, 1, 'Samikaro', 40.279999, -2.520000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2810, 1, 'Samsons Corner', 37.369999, -0.550000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2811, 1, 'Samsons Kona', 37.369999, -0.550000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2812, 1, 'Samuli', 37.430000, -2.120000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2813, 1, 'Sandai', 36.080002, 0.380000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2814, 1, 'Sang''alo', 35.049999, 0.350000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2815, 1, 'Sangoleaku', 40.869999, -0.550000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2816, 1, 'Sang?alo', 35.049999, 0.350000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2817, 1, 'Saranga', 34.220001, 0.180000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2818, 1, 'Saranja', 34.220001, 0.180000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2819, 1, 'Sarbuye', 39.900002, -2.180000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2820, 1, 'Sare', 34.529999, -0.900000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2821, 1, 'Saricho', 39.080002, 1.080000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2822, 1, 'Sarim', 40.970001, -0.380000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2823, 1, 'Sarora', 35.000000, 0.470000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2824, 1, 'Sarunza', 34.220001, 0.180000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2825, 1, 'Sasumua', 35.669998, -0.300000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2826, 1, 'Sawasawa', 39.480000, -4.470000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2827, 1, 'Sebit', 35.330002, 1.400000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2828, 1, 'Secho', 38.330002, -3.500000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2829, 1, 'Sega', 34.230000, 0.260000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2830, 1, 'Segara', 36.849998, 0.200000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2831, 1, 'Segera', 36.779999, 0.370000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2832, 1, 'Segera Ranch', 36.849998, 0.200000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2833, 1, 'Selaloni', 39.279999, -3.620000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2834, 1, 'Selengai', 37.169998, -2.180000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2835, 1, 'Semandaro', 40.880001, -2.180000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2836, 1, 'Sena', 34.070000, -0.470000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2837, 1, 'Sendeni', 41.369999, -1.880000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2838, 1, 'Serengugt Farm', 36.279999, -0.070000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2839, 1, 'Seretunin', 35.779999, 0.550000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2840, 1, 'Sericho', 39.080002, 1.080000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2841, 1, 'Serkonghun', 35.770000, 0.600000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2842, 1, 'Serkongun', 35.770000, 0.600000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2843, 1, 'Sesia', 35.820000, 0.500000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2844, 1, 'Shabaltaragwa', 35.799999, -0.550000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2845, 1, 'Shakadula', 39.869999, -3.070000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2846, 1, 'Shakadulo', 39.869999, -3.070000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2847, 1, 'Shakani', 41.560001, -1.660000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2848, 1, 'Shambini', 39.169998, -4.050000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2849, 1, 'Shambweni', 39.619999, -3.400000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2850, 1, 'Shamenei', 36.279999, 0.050000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2851, 1, 'Shamenek', 36.270000, 0.130000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2852, 1, 'Shanga', 41.070000, -2.130000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2853, 1, 'Shanzu', 39.750000, -3.970000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2854, 1, 'Shauri Moya', 40.029999, -3.020000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2855, 1, 'Shauri Moyo', 36.849998, -1.280000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2856, 1, 'Shawa Farm', 35.830002, -0.250000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2857, 1, 'Shela', 40.919998, -2.300000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2858, 1, 'Shelemba', 38.470001, -3.430000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2859, 1, 'Shella', 40.919998, -2.300000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2860, 1, 'Shigaro', 38.369999, -3.400000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2861, 1, 'Shikusa', 34.820000, 0.320000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2862, 1, 'Shimambaya', 41.369999, -1.880000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2863, 1, 'Shimanzi', 39.630001, -4.050000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2864, 1, 'Shimo la Tewa', 39.730000, -3.950000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2865, 1, 'Shimoni', 35.720001, 0.050000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2866, 1, 'Shirazi', 39.419998, -4.530000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2867, 1, 'Shirotsa', 34.570000, 0.230000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2868, 1, 'Shombole', 36.119999, -2.080000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2869, 1, 'Showa Farm', 35.830002, -0.250000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2870, 1, 'Sh?manzi', 39.630001, -4.050000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2871, 1, 'Siakago', 37.630001, -0.580000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2872, 1, 'Siamufua', 34.070000, 0.120000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2873, 1, 'Sibillo', 35.919998, 0.680000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2874, 1, 'Sibilo', 35.919998, 0.680000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2875, 1, 'Sichei', 34.630001, 0.720000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2876, 1, 'Sicheyi', 34.630001, 0.720000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2877, 1, 'Sidindi', 34.389999, 0.150000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2878, 1, 'Sigalagala', 34.750000, 0.200000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2879, 1, 'Sigio', 36.599998, -1.200000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2880, 1, 'Sigomere', 34.360001, 0.200000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2881, 1, 'Sigoor Camp', 35.470001, 1.480000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2882, 1, 'Sigor', 35.470001, 1.480000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2883, 1, 'Sikoma', 34.200001, 0.420000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2884, 1, 'Silaloni', 39.279999, -3.620000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2885, 1, 'Silanga Farm', 36.369999, -0.230000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2886, 1, 'Silibwet Farm', 36.250000, -0.080000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2887, 1, 'Simambaya', 41.369999, -1.880000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2888, 1, 'Simandaro', 40.880001, -2.180000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2889, 1, 'Simba', 37.599998, -2.170000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2890, 1, 'Simba Ranch', 37.750000, -2.080000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2891, 1, 'Simbara Farm', 36.470001, -0.130000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2892, 1, 'Simisi', 38.330002, -2.000000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2893, 1, 'Simkara', 40.279999, -2.520000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2894, 1, 'Sindo', 34.169998, -0.530000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2895, 1, 'Singore', 35.520000, 0.720000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2896, 1, 'Sinoia', 40.099998, -3.170000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0);
INSERT INTO `loctowns` (`idloctowns`, `loccountry_idloccountry`, `locationname`, `lng`, `lat`, `createdby`, `createdon`, `validatedby`, `validatedon`, `is_valid`, `is_town`) VALUES
(2897, 1, 'Sintakara', 36.150002, -1.000000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2898, 1, 'Sio', 34.020000, 0.220000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2899, 1, 'Sio Port', 34.020000, 0.220000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2900, 1, 'Siongiroi', 35.220001, -0.920000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2901, 1, 'Siranga', 34.220001, 0.180000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2902, 1, 'Siriba', 34.599998, 0.000000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2903, 1, 'Sirisia', 34.000000, 0.120000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2904, 1, 'Sirkoma', 34.200001, 0.420000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2905, 1, 'Sirrima', 36.770000, 0.020000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2906, 1, 'Sisenye', 33.980000, 0.150000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2907, 1, 'Sitaman', 35.250000, 1.070000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2908, 1, 'Sitian', 35.529999, -0.230000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2909, 1, 'Six Hills Farm', 36.480000, -0.100000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2910, 1, 'Siyu', 41.060001, -2.100000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2911, 1, 'Skwata', 38.419998, -1.880000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2912, 1, 'Soisian', 36.820000, 0.620000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2913, 1, 'Soit Nyiro', 36.880001, 0.450000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2914, 1, 'Sokoke', 39.820000, -3.530000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2915, 1, 'Sokoksa', 39.730000, 3.630000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2916, 1, 'Sokoxa', 39.730000, 3.630000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2917, 1, 'Solai', 36.029999, 0.030000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2918, 1, 'Solio Ranch', 36.970001, -0.270000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2919, 1, 'Sololo', 38.650002, 3.550000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2920, 1, 'Sondu', 35.020000, -0.380000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2921, 1, 'Songeni', 37.480000, -1.570000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2922, 1, 'Sosian', 36.669998, 0.480000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2923, 1, 'Sosiot', 35.169998, -0.370000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2924, 1, 'Sotik', 35.349998, -0.780000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2925, 1, 'Sotik Post', 35.349998, -0.780000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2926, 1, 'South Horr', 36.919998, 2.100000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2927, 1, 'South Kinangop', 36.650002, -0.720000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2928, 1, 'Soy', 35.150002, 0.670000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2929, 1, 'Spring Valley', 36.779999, -1.250000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2930, 1, 'Subego', 36.619999, 0.000000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2931, 1, 'Subuku', 36.369999, 0.150000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2932, 1, 'Suguroi', 36.730000, 0.270000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2933, 1, 'Sukari Ranch', 37.099998, -1.250000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2934, 1, 'Sultan Hamud', 37.369999, -2.020000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2935, 1, 'Sumai', 39.320000, -1.530000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2936, 1, 'Sumat', 40.070000, -1.130000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2937, 1, 'Suna', 34.430000, -1.080000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2938, 1, 'Sungululu', 38.369999, -3.380000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2939, 1, 'Sunny Brook', 35.570000, -0.120000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2940, 1, 'Sweet Waters', 36.919998, 0.050000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2941, 1, 'Syolima', 38.080002, -1.430000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2942, 1, 'Syumbungu', 38.500000, -0.900000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2943, 1, 'Takaba', 40.220001, 3.360000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2944, 1, 'Takaungu', 39.849998, -3.680000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2945, 1, 'Takwa Milinga', 40.970001, -2.280000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2946, 1, 'Talai', 35.799999, 0.580000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2947, 1, 'Talau', 35.119999, 1.200000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2948, 1, 'Talio', 38.580002, -3.530000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2949, 1, 'Taloa', 35.549999, -0.350000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2950, 1, 'Tambach', 35.520000, 0.600000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2951, 1, 'Tambaya', 37.020000, -0.530000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2952, 1, 'Tana Ranch', 37.369999, -0.850000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2953, 1, 'Tandala', 36.500000, 0.130000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2954, 1, 'Tandare', 36.279999, 0.380000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2955, 1, 'Tangulbei', 36.279999, 0.800000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2956, 1, 'Tangulbwe', 36.279999, 0.800000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2957, 1, 'Tarbaj', 40.139999, 2.210000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2958, 1, 'Taru', 39.150002, -3.730000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2959, 1, 'Taveta', 37.680000, -3.400000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2960, 1, 'Tawa', 37.470001, -1.550000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2961, 1, 'Tay Vallich Farm', 36.299999, -0.100000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2962, 1, 'Tegessi Farm', 36.950001, -0.050000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2963, 1, 'Teragua', 35.650002, -0.420000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2964, 1, 'Teso', 39.669998, -1.300000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2965, 1, 'Teso Ramu', 39.630001, 3.530000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2966, 1, 'Tezo', 39.849998, -3.530000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2967, 1, 'Thaara', 37.200001, -0.800000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2968, 1, 'Thagare', 37.000000, -0.830000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2969, 1, 'Thaina', 36.820000, -0.420000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2970, 1, 'Thaita', 37.270000, -0.450000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2971, 1, 'Thakwa', 36.770000, -1.070000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2972, 1, 'Thamara', 37.070000, -0.830000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2973, 1, 'Thangata', 38.320000, -0.200000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2974, 1, 'Thangathe', 37.130001, -0.570000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2975, 1, 'Thange', 38.020000, -2.500000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2976, 1, 'Thangi', 38.049999, -2.470000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2977, 1, 'Thanju', 37.230000, -0.680000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2978, 1, 'Thanu', 37.119999, -0.630000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2979, 1, 'Thara', 37.200001, -0.800000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2980, 1, 'Tharaka', 38.020000, -0.300000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2981, 1, 'Tharaka Kijegge', 38.020000, -0.300000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2982, 1, 'Thembigwa', 36.770000, -1.180000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2983, 1, 'Thengeini', 37.150002, -0.520000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2984, 1, 'Theri', 36.930000, -0.720000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2985, 1, 'Thiba', 37.349998, -0.680000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2986, 1, 'Thigio', 36.599998, -1.200000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2987, 1, 'Thiguku', 37.230000, -0.550000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2988, 1, 'Thika', 37.080002, -1.050000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2989, 1, 'Thika Rapids Farm', 37.220001, -1.020000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2990, 1, 'Thika Valley', 36.820000, -0.800000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2991, 1, 'Thimbigua', 36.770000, -1.180000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2992, 1, 'Thogoto', 36.669998, -1.270000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2993, 1, 'Thompsons Falls', 36.369999, 0.030000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2994, 1, 'Thomson Estate', 36.770000, -1.280000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2995, 1, 'Thomsons Falls', 36.369999, 0.030000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2996, 1, 'Thorason''s Falls', 36.369999, 0.030000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2997, 1, 'Thorason?s Falls', 36.369999, 0.030000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2998, 1, 'Thuita', 36.980000, -0.920000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(2999, 1, 'Thuitia', 36.880001, -1.070000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3000, 1, 'Thunguma', 36.980000, -0.430000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3001, 1, 'Thunguri', 36.919998, -0.550000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3002, 1, 'Thuthuriki', 36.770000, -0.070000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3003, 1, 'Thuva', 39.700001, -3.580000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3004, 1, 'Tiekunu', 36.599998, -1.120000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3005, 1, 'Tigoni', 36.669998, -1.150000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3006, 1, 'Timau', 37.230000, 0.080000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3007, 1, 'Timbila', 37.700001, -3.370000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3008, 1, 'Timboiwo', 35.799999, 0.420000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3009, 1, 'Timboywo', 35.799999, 0.420000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3010, 1, 'Tindini', 39.470001, -3.650000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3011, 1, 'Tinet Farm', 35.570000, -0.420000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3012, 1, 'Tinganga', 36.820000, -1.130000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3013, 1, 'Tingga Farm', 36.419998, -0.270000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3014, 1, 'Tinggai Farm', 36.419998, -0.270000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3015, 1, 'Tiribe', 39.250000, -4.320000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3016, 1, 'Tirimionin', 35.820000, 0.670000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3017, 1, 'Tiriondonin', 35.770000, 0.630000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3018, 1, 'Titila', 39.220001, -1.530000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3019, 1, 'Tiva', 37.880001, -1.430000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3020, 1, 'Tiwi', 39.580002, -4.230000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3021, 1, 'Tokitok', 35.720001, -0.550000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3022, 1, 'Topiwalop Farm', 34.980000, 1.050000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3023, 1, 'Toritu', 36.799999, -1.170000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3024, 1, 'Tower', 34.330002, 0.150000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3025, 1, 'Toweri', 34.330002, 0.150000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3026, 1, 'Tsangalaweni', 39.700001, -3.520000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3027, 1, 'Tsangatsini', 39.450001, -3.720000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3028, 1, 'Tsavo', 38.470001, -2.980000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3029, 1, 'Tsavo Station', 38.470001, -2.980000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3030, 1, 'Tsunguni', 39.669998, -3.820000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3031, 1, 'Tukeli', 40.869999, -1.070000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3032, 1, 'Tula', 39.700001, -0.830000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3033, 1, 'Tulia', 37.980000, -1.180000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3034, 1, 'Tulu', 40.099998, -2.270000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3035, 1, 'Tumaini Farm', 36.279999, -0.270000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3036, 1, 'Tumeiyo', 35.599998, 0.280000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3037, 1, 'Tumeya', 35.599998, 0.280000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3038, 1, 'Tungu', 37.630001, -0.300000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3039, 1, 'Tuni', 39.900002, -1.000000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3040, 1, 'Tura', 37.029999, 0.530000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3041, 1, 'Turasha', 36.450001, -0.470000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3042, 1, 'Turbo', 35.049999, 0.630000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3043, 1, 'Turi', 35.770000, -0.280000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3044, 1, 'Tusha', 36.820000, -0.470000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3045, 1, 'Tuso', 36.830002, -0.670000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3046, 1, 'Twaandu', 37.770000, -2.150000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3047, 1, 'Twelve Springs Farm', 36.299999, -0.130000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3048, 1, 'Twiga Farm', 37.029999, -1.050000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3049, 1, 'Twin Stream', 35.720001, -0.200000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3050, 1, 'Twin Streams Farm', 35.720001, -0.200000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3051, 1, 'Two Rivers', 36.520000, 0.350000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3052, 1, 'Ufumbani', 39.220001, -4.650000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3053, 1, 'Uholo', 34.330002, 0.190000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3054, 1, 'Uhuru', 36.869999, -1.280000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3055, 1, 'Ukunda', 39.570000, -4.280000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3056, 1, 'Ukundu', 39.570000, -4.280000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3057, 1, 'Ukwala', 34.189999, 0.190000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3058, 1, 'Ulro', 34.279999, -0.850000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3059, 1, 'Ulu', 37.150002, -1.820000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3060, 1, 'Umbui', 36.930000, -0.620000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3061, 1, 'Umingu', 38.369999, -3.330000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3062, 1, 'Unjiru', 37.119999, -0.450000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3063, 1, 'Upete', 37.349998, -1.850000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3064, 1, 'Upper Gatara', 36.880001, -0.730000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3065, 1, 'Upper Gilgil', 36.270000, -0.220000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3066, 1, 'Upper Hill', 36.779999, -1.280000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3067, 1, 'Upper Parklands Estate', 36.799999, -1.250000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3068, 1, 'Uranga', 34.150002, 0.120000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3069, 1, 'Uremia', 34.049999, 0.120000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3070, 1, 'Uruku', 36.580002, 0.030000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3071, 1, 'Urumathi', 36.830002, -1.050000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3072, 1, 'Ushingo', 39.669998, -2.770000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3073, 1, 'Usueni', 38.200001, -0.150000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3074, 1, 'Utange', 39.720001, -3.970000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3075, 1, 'Utangwa', 37.450001, -1.700000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3076, 1, 'Uthiru', 36.720001, -1.270000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3077, 1, 'Uthithi', 38.000000, -2.530000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3078, 1, 'Uvete', 37.349998, -1.950000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3079, 1, 'Uwai', 34.169998, 0.240000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3080, 1, 'Uyombo', 39.950001, -3.380000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3081, 1, 'Vidungeni', 39.450001, -4.470000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3082, 1, 'Vifanjoni', 39.630001, -3.950000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3083, 1, 'Vikinduni', 39.150002, -4.250000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3084, 1, 'Villa Franca Dairy Farm', 36.869999, -1.320000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3085, 1, 'Vinagoni', 39.529999, -3.700000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3086, 1, 'Vingujini', 39.480000, -4.470000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3087, 1, 'Vinuni', 39.529999, -4.180000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3088, 1, 'Vinunyi', 39.529999, -4.180000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3089, 1, 'Vipingo', 39.799999, -3.820000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3090, 1, 'Vipingoni', 39.799999, -3.820000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3091, 1, 'Viragoni', 39.529999, -3.700000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3092, 1, 'Vitengeni', 39.720001, -3.370000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3093, 1, 'Vitsangalaweni', 39.180000, -4.450000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3094, 1, 'Vivini', 39.349998, -4.470000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3095, 1, 'Vivwini', 39.349998, -4.470000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3096, 1, 'Voi', 38.570000, -3.380000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3097, 1, 'Voo', 38.330002, -1.670000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3098, 1, 'Vuga', 39.500000, -4.180000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3099, 1, 'Vulueni', 37.419998, -1.980000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3100, 1, 'Wabuga', 36.720001, -1.230000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3101, 1, 'Wacha Koni', 40.020000, -1.430000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3102, 1, 'Wachakone', 40.020000, -1.430000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3103, 1, 'Wagu', 39.580002, -1.530000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3104, 1, 'Waguthu', 36.779999, -1.180000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3105, 1, 'Waita', 38.099998, -0.780000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3106, 1, 'Waithaka', 36.720001, -1.280000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3107, 1, 'Wal Mura', 39.580002, 3.480000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3108, 1, 'Walanga', 34.419998, 0.830000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3109, 1, 'Waldena', 39.029999, -1.600000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3110, 1, 'Walu', 40.130001, -1.700000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3111, 1, 'Wama', 40.189999, -2.180000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3112, 1, 'Wamachatha', 37.020000, -0.570000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3113, 1, 'Wamagana', 36.930000, -0.470000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3114, 1, 'Wamba', 37.320000, 0.980000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3115, 1, 'Wamitaa', 36.919998, -1.050000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3116, 1, 'Wamono', 34.430000, 0.790000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3117, 1, 'Wamumu', 37.320000, -0.750000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3118, 1, 'Wamutitu', 37.029999, -0.550000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3119, 1, 'Wamwangi', 36.919998, -1.000000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3120, 1, 'Wandimi', 36.750000, -1.000000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3121, 1, 'Wandore', 35.529999, -0.220000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3122, 1, 'Wandumbi', 36.880001, -0.470000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3123, 1, 'Wangai', 37.070000, -0.900000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3124, 1, 'Wange', 40.919998, -2.000000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3125, 1, 'Wangi', 40.919998, -2.000000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3126, 1, 'Wangoma', 34.450001, 0.770000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3127, 1, 'Wanjengi', 37.000000, -0.720000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3128, 1, 'Wanjereri', 36.849998, -0.650000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3129, 1, 'Wanjii', 37.169998, -0.750000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3130, 1, 'Wanyaga', 36.799999, -0.800000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3131, 1, 'Wanyagga', 36.549999, -1.230000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3132, 1, 'War Dirsame', 40.939999, 0.780000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3133, 1, 'Waratho', 36.779999, -1.120000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3134, 1, 'Waresa', 40.000000, -2.770000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3135, 1, 'Waressa', 40.000000, -2.770000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3136, 1, 'Wariva', 34.570000, 1.030000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3137, 1, 'Wasin', 39.369999, -4.670000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3138, 1, 'Wasini', 39.369999, -4.670000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3139, 1, 'Wasis', 34.730000, -0.550000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3140, 1, 'Watamu', 40.020000, -3.350000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3141, 1, 'Waterer', 35.650002, -0.380000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3142, 1, 'Wattle Blossom Farm', 36.980000, -1.430000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3143, 1, 'Wautu', 37.400002, -1.870000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3144, 1, 'Wayu', 39.580002, -1.530000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3145, 1, 'Webuye', 34.770000, 0.620000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3146, 1, 'Welaluit', 40.900002, -0.930000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3147, 1, 'Welaluit Dardesa', 40.900002, -0.930000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3148, 1, 'Wema', 40.189999, -2.180000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3149, 1, 'Wempa Farm', 37.169998, -0.970000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3150, 1, 'Wenje', 40.110001, -1.790000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3151, 1, 'Weno', 34.430000, -0.570000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3152, 1, 'Weru', 37.720001, -0.370000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3153, 1, 'Weruga', 38.330002, -3.380000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3154, 1, 'Westerland', 35.630001, -0.350000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3155, 1, 'Wesu', 38.349998, -3.400000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3156, 1, 'Wetima One', 36.980000, -0.550000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3157, 1, 'Wetima Two', 36.970001, -0.550000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3158, 1, 'Winam', 34.750000, -0.100000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3159, 1, 'Witu', 40.450001, -2.390000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3160, 1, 'Wmingo', 38.330002, -3.370000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3161, 1, 'Woldena', 39.029999, -1.600000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3162, 1, 'Wongonyi', 38.430000, -3.320000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3163, 1, 'Woodley Estate', 36.770000, -1.300000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3164, 1, 'Wote', 37.630001, -1.780000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3165, 1, 'Wumari', 38.320000, -3.480000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3166, 1, 'Wundanyi', 38.369999, -3.400000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3167, 1, 'Yaka', 39.380001, -1.550000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3168, 1, 'Yala', 34.529999, 0.100000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3169, 1, 'Yala Station', 34.529999, 0.100000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3170, 1, 'Yale', 38.320000, -3.400000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3171, 1, 'Yamogo', 36.770000, -1.200000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3172, 1, 'Yamugwe', 37.020000, -0.770000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3173, 1, 'Yatta Ranch', 37.430000, -1.170000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3174, 1, 'Yatya', 35.919998, 0.780000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3175, 1, 'Yedi', 39.880001, -1.030000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3176, 1, 'Zacharia', 34.830002, -0.630000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3177, 1, 'Zaikone', 37.320000, -1.470000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3178, 1, 'Zaikone Village', 37.320000, -1.470000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3179, 1, 'Zaina', 36.820000, -0.420000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3180, 1, 'Zare', 38.320000, -3.500000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3181, 1, 'Zawadi', 36.849998, 0.150000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3182, 1, 'Ziwani', 37.779999, -3.380000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3183, 1, 'Zombe', 38.230000, -1.450000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3184, 1, 'Zombi', 38.230000, -1.450000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3213, 1, 'Kasarani', 36.905731, -1.227840, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3214, 1, 'Kahawa West', 36.903042, -1.187230, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3215, 1, 'Gigiri', 36.806179, -1.243020, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3216, 1, 'Peponi', 36.792469, -1.244130, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3217, 1, 'Githurai', 36.913792, -1.206420, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3218, 1, 'All Sops', 36.872108, -1.248730, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3219, 1, 'Survey', 36.858662, -1.256710, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 1),
(3220, 1, 'Lucky Summer', 36.899010, -1.238600, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3221, 1, 'Ngumba', 36.885559, -1.233010, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3222, 1, 'Messo', 36.884109, -1.234890, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3223, 1, 'Ruai', 37.007820, -1.253290, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3224, 1, 'Mihango', 36.975601, -1.292570, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3225, 1, 'Kayole', 36.913792, -1.276160, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3227, 1, 'Umoja', 36.905731, -1.274360, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3229, 1, 'Kitisuru', 36.798111, -1.221810, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3231, 1, 'Loresho', 36.744030, -1.269160, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3233, 1, 'Wilson Airport', 36.812271, -1.331110, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3235, 1, 'Kyuna', 36.776169, -1.250660, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3236, 1, 'Huruma', 36.872112, -1.256490, NULL, NULL, 1, '0000-00-00 00:00:00', 1, 0),
(3237, 1, 'Parklands, Nairobi', 36.818054, -1.260000, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 0),
(3238, 1, 'Nairobi City', 36.818619, -1.283416, 1, NULL, 0, '0000-00-00 00:00:00', 1, 1),
(3241, 1, 'PARKLANDS', 36.818054, -1.260000, 53, '2013-06-12 09:09:18', 0, '0000-00-00 00:00:00', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `loctowns_alias`
--

CREATE TABLE IF NOT EXISTS `loctowns_alias` (
  `idloctowns_alias` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `loctowns_idloctowns` int(8) unsigned NOT NULL,
  `loctown_alias` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`idloctowns_alias`),
  UNIQUE KEY `loctown_alias` (`loctown_alias`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

--
-- Dumping data for table `loctowns_alias`
--

INSERT INTO `loctowns_alias` (`idloctowns_alias`, `loctowns_idloctowns`, `loctown_alias`) VALUES
(1, 929, 'Kahawa Sukari'),
(2, 3216, 'Peponi Road'),
(3, 3217, 'Githu'),
(4, 3217, '44'),
(5, 3217, 'Route 44'),
(6, 3217, 'Root 44'),
(7, 25, 'isich'),
(8, 25, 'eishich'),
(9, 3218, 'olsops'),
(10, 3218, 'alsops'),
(11, 3218, 'allsops'),
(12, 3220, 'Lakisama'),
(13, 3220, 'laki sama'),
(14, 3220, 'laki summer'),
(15, 3220, 'lakisummer'),
(16, 3220, 'luckysama'),
(17, 3221, 'Gumba'),
(18, 3222, 'Meso');

-- --------------------------------------------------------

--
-- Table structure for table `log_escalations`
--

CREATE TABLE IF NOT EXISTS `log_escalations` (
  `idlog_escalations` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `wfescalations_idwfescalations` int(11) NOT NULL,
  `tktid` int(11) unsigned NOT NULL,
  `wftaskid` int(11) unsigned NOT NULL,
  `escalatedtime` datetime NOT NULL,
  PRIMARY KEY (`idlog_escalations`),
  KEY `wfescalations_idwfescalations` (`wfescalations_idwfescalations`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `log_escalations_wfteamesc`
--

CREATE TABLE IF NOT EXISTS `log_escalations_wfteamesc` (
  `idlog_escalations_wfteamesc` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `idwfteamesc` int(11) unsigned zerofill NOT NULL,
  `tktid` int(11) unsigned zerofill NOT NULL,
  `wftaskid` int(11) unsigned zerofill NOT NULL,
  `escalatedtime` datetime NOT NULL,
  PRIMARY KEY (`idlog_escalations_wfteamesc`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `log_notifications_email`
--

CREATE TABLE IF NOT EXISTS `log_notifications_email` (
  `idlog_notifications_email` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tbl_name` varchar(60) DEFAULT NULL,
  `tbl_fk_id` int(11) unsigned DEFAULT NULL,
  `emailadd` varchar(200) DEFAULT NULL,
  `emailmsg` text,
  `notificationtime` datetime DEFAULT NULL,
  `timesent` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`idlog_notifications_email`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `log_notifications_sms`
--

CREATE TABLE IF NOT EXISTS `log_notifications_sms` (
  `idlog_notifications_sms` bigint(20) NOT NULL AUTO_INCREMENT,
  `tbl_name` varchar(60) NOT NULL,
  `tbl_fk_id` int(11) unsigned NOT NULL,
  `smsmsg` varchar(250) DEFAULT NULL,
  `recnumber` varchar(20) DEFAULT NULL,
  `notificationtime` datetime NOT NULL,
  `timesent` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`idlog_notifications_sms`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `maintenancelog`
--

CREATE TABLE IF NOT EXISTS `maintenancelog` (
  `idmaintenancelog` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `assetstatus_idassetstatus` int(3) unsigned NOT NULL,
  `assetlist_idassetlist` int(11) unsigned NOT NULL,
  `checkdate` datetime DEFAULT NULL,
  `enteredby` int(8) unsigned DEFAULT NULL,
  `enteredon` datetime DEFAULT NULL,
  `checknotes` text,
  `checkstatus` varchar(4) NOT NULL,
  PRIMARY KEY (`idmaintenancelog`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `markers`
--

CREATE TABLE IF NOT EXISTS `markers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `address` varchar(80) NOT NULL,
  `lat` float(10,6) NOT NULL,
  `lng` float(10,6) NOT NULL,
  `type` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdata_emailsout`
--

CREATE TABLE IF NOT EXISTS `mdata_emailsout` (
  `idemailout` int(11) NOT NULL AUTO_INCREMENT,
  `email_to` varchar(60) NOT NULL,
  `email_subject` varchar(250) NOT NULL,
  `email_message` text NOT NULL,
  `email_headers` varchar(250) NOT NULL,
  `createdon` datetime NOT NULL,
  `processedon` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`idemailout`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdata_in_sms`
--

CREATE TABLE IF NOT EXISTS `mdata_in_sms` (
  `idmdata_in_sms` bigint(20) NOT NULL DEFAULT '0',
  `srcnumber` bigint(12) DEFAULT NULL,
  `msgtext` varchar(160) COLLATE utf8_unicode_ci DEFAULT NULL,
  `timein` datetime DEFAULT NULL,
  `processed` tinyint(1) unsigned DEFAULT '0',
  `processedtime` datetime DEFAULT NULL,
  `uniqueid` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `request_usrteamid` int(4) NOT NULL DEFAULT '0',
  `idmdata_in_smsPK` bigint(20) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`idmdata_in_smsPK`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdata_in_ussdbilling`
--

CREATE TABLE IF NOT EXISTS `mdata_in_ussdbilling` (
  `idmdata_in_ussdbilling` bigint(20) NOT NULL AUTO_INCREMENT,
  `srcnumber` bigint(12) DEFAULT NULL,
  `request_cat` varchar(50) DEFAULT NULL,
  `request_subcat` varchar(50) DEFAULT NULL,
  `wcompany` varchar(50) DEFAULT NULL,
  `accountnum` varchar(50) DEFAULT NULL,
  `timein` datetime DEFAULT NULL,
  `processed` tinyint(1) unsigned DEFAULT '0',
  `processedtime` datetime DEFAULT NULL,
  `uniqueid` bigint(20) DEFAULT NULL,
  `request_usrteamid` int(4) unsigned DEFAULT '0',
  `request_channel` int(2) unsigned DEFAULT '0',
  PRIMARY KEY (`idmdata_in_ussdbilling`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdata_in_ussdkiosk`
--

CREATE TABLE IF NOT EXISTS `mdata_in_ussdkiosk` (
  `idmdata_in_ussdkiosk` bigint(20) NOT NULL DEFAULT '0',
  `srcnumber` bigint(12) DEFAULT NULL,
  `request_cat` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `request_subcat` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `kiosknumber` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `msgtext` varchar(160) COLLATE utf8_unicode_ci DEFAULT NULL,
  `timein` datetime DEFAULT NULL,
  `processed` tinyint(1) unsigned DEFAULT '0',
  `processedtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `uniqueid` bigint(20) DEFAULT NULL,
  `request_usrteamid` int(4) NOT NULL DEFAULT '0',
  `request_channel` int(2) DEFAULT '0',
  `idmdata_in_ussdkioskPK` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`idmdata_in_ussdkioskPK`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdata_in_ussdprivate`
--

CREATE TABLE IF NOT EXISTS `mdata_in_ussdprivate` (
  `idmdata_in_ussdprivate` bigint(20) NOT NULL DEFAULT '0',
  `srcnumber` bigint(12) DEFAULT NULL,
  `request_cat` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `request_subcat` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `wcompany` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `accountnum` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `physicaladdr` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `msgtext` varchar(160) COLLATE utf8_unicode_ci DEFAULT NULL,
  `srcgender` varchar(6) COLLATE utf8_unicode_ci DEFAULT NULL,
  `srcticket` char(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `timein` datetime DEFAULT NULL,
  `processed` tinyint(1) unsigned DEFAULT '0',
  `processedtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `uniqueid` bigint(20) DEFAULT NULL,
  `request_usrteamid` int(4) NOT NULL DEFAULT '0',
  `request_channel` int(2) DEFAULT '0',
  `idmdata_in_ussdprivatePK` bigint(20) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`idmdata_in_ussdprivatePK`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdata_in_ussdpublic`
--

CREATE TABLE IF NOT EXISTS `mdata_in_ussdpublic` (
  `idmdata_in_ussdpublic` bigint(20) NOT NULL DEFAULT '0',
  `srcnumber` bigint(12) DEFAULT NULL,
  `request_cat` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `request_subcat` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `loctown` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `loclandmark` varchar(160) COLLATE utf8_unicode_ci DEFAULT NULL,
  `msgtext` varchar(160) COLLATE utf8_unicode_ci DEFAULT NULL,
  `timein` datetime DEFAULT NULL,
  `processed` tinyint(1) unsigned DEFAULT '0',
  `processedtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `uniqueid` bigint(20) DEFAULT NULL,
  `request_usrteamid` int(4) NOT NULL DEFAULT '0',
  `request_channel` int(2) DEFAULT '0',
  `idmdata_in_ussdpublicPK` bigint(20) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`idmdata_in_ussdpublicPK`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdata_out_errlog`
--

CREATE TABLE IF NOT EXISTS `mdata_out_errlog` (
  `idmdata_out_errlog` bigint(20) NOT NULL AUTO_INCREMENT,
  `mdata_out_smsid` bigint(20) DEFAULT NULL,
  `curl_stut` varchar(200) DEFAULT NULL,
  `err_date` datetime DEFAULT NULL,
  PRIMARY KEY (`idmdata_out_errlog`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdata_out_sms`
--

CREATE TABLE IF NOT EXISTS `mdata_out_sms` (
  `idmdata_out_sms` bigint(20) NOT NULL DEFAULT '0',
  `destnumber` bigint(12) DEFAULT NULL,
  `msgtext` varchar(160) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cron_status` int(2) DEFAULT '0',
  `cron_count` int(2) DEFAULT '0',
  `process_status` float(2,0) DEFAULT '0',
  `processedtime` datetime DEFAULT NULL,
  `delivery_status` float(2,0) DEFAULT NULL,
  `deliverytime` datetime DEFAULT NULL,
  `messageid` bigint(20) DEFAULT NULL,
  `idmdata_out_smsPK` bigint(20) NOT NULL AUTO_INCREMENT,
  `data_source` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data_source_id` int(11) NOT NULL DEFAULT '0',
  `data_source_timein` datetime DEFAULT NULL,
  `time_inserted` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idmdata_out_smsPK`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdata_out_smsalertslog`
--

CREATE TABLE IF NOT EXISTS `mdata_out_smsalertslog` (
  `idmdata_out_smsalertslog` bigint(20) NOT NULL AUTO_INCREMENT,
  `usrrole_idusrrole` int(8) unsigned NOT NULL,
  `mdata_out_smsalerttype_idmdata_out_smsalerttype` int(2) NOT NULL DEFAULT '0',
  `msgsenton` date DEFAULT NULL,
  PRIMARY KEY (`idmdata_out_smsalertslog`),
  KEY `mdata_out_smsalertslog_FKIndex1` (`usrrole_idusrrole`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdata_out_smsalerttype`
--

CREATE TABLE IF NOT EXISTS `mdata_out_smsalerttype` (
  `idmdata_out_smsalerttype` int(2) NOT NULL AUTO_INCREMENT,
  `alertdescription` varchar(30) NOT NULL,
  PRIMARY KEY (`idmdata_out_smsalerttype`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `mdata_out_smsalerttype`
--

INSERT INTO `mdata_out_smsalerttype` (`idmdata_out_smsalerttype`, `alertdescription`) VALUES
(1, 'Individual Task Aler'),
(2, 'Cordinator Team Alert'),
(3, 'RM Team Alert');

-- --------------------------------------------------------

--
-- Table structure for table `mdata_source`
--

CREATE TABLE IF NOT EXISTS `mdata_source` (
  `idmdata_source` int(2) NOT NULL AUTO_INCREMENT,
  `mdata_sourcename` varchar(40) NOT NULL,
  `table_name` varchar(40) NOT NULL,
  `field_name` varchar(40) NOT NULL,
  PRIMARY KEY (`idmdata_source`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdata_usrmobile_exception`
--

CREATE TABLE IF NOT EXISTS `mdata_usrmobile_exception` (
  `idmdata_usrmobile_exception` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `srcnumber` bigint(12) DEFAULT NULL,
  PRIMARY KEY (`idmdata_usrmobile_exception`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mdata_usrteam_exception`
--

CREATE TABLE IF NOT EXISTS `mdata_usrteam_exception` (
  `idmdata_usrteam_exception` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `usrteam_exception` int(4) unsigned DEFAULT NULL,
  PRIMARY KEY (`idmdata_usrteam_exception`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `mdata_usrteam_exception`
--

INSERT INTO `mdata_usrteam_exception` (`idmdata_usrteam_exception`, `usrteam_exception`) VALUES
(1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE IF NOT EXISTS `notes` (
  `idnotes` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `notestatus_idnotestatus` int(2) unsigned NOT NULL,
  `statusnotes` text,
  `enteredby` int(8) unsigned DEFAULT NULL,
  `enteredon` datetime DEFAULT NULL,
  `createdby` int(8) unsigned DEFAULT NULL,
  `createdon` datetime DEFAULT NULL,
  `modifiedby` int(8) unsigned DEFAULT NULL,
  `modifiedon` datetime DEFAULT NULL,
  `tbl_fk_id` int(11) unsigned DEFAULT NULL,
  `tbl_name` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`idnotes`),
  KEY `tbl_name` (`tbl_name`),
  KEY `tbl_fk_id` (`tbl_fk_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `notestatus`
--

CREATE TABLE IF NOT EXISTS `notestatus` (
  `idnotestatus` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `nstatus` varchar(50) DEFAULT NULL,
  `tbl_name` varchar(60) DEFAULT NULL,
  `statuscolor` varchar(30) NOT NULL,
  PRIMARY KEY (`idnotestatus`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `notestatus`
--

INSERT INTO `notestatus` (`idnotestatus`, `nstatus`, `tbl_name`, `statuscolor`) VALUES
(1, 'New awaiting Review', 'wpheader', ''),
(2, 'Reviewed & Approved', 'wpheader', ''),
(3, 'New awaiting Review', 'reportactivities', ''),
(4, 'Reviewed & Accepted', 'reportactivities', ''),
(5, 'Changes Required', 'wpheader', '');

-- --------------------------------------------------------

--
-- Table structure for table `orgesc`
--

CREATE TABLE IF NOT EXISTS `orgesc` (
  `idorgesc` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `idtktesclevel` int(2) unsigned NOT NULL,
  `idusrteam_from` int(8) unsigned DEFAULT NULL,
  `idusrteam_to` int(8) unsigned DEFAULT NULL,
  PRIMARY KEY (`idorgesc`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `orgesc`
--

INSERT INTO `orgesc` (`idorgesc`, `idtktesclevel`, `idusrteam_from`, `idusrteam_to`) VALUES
(1, 1, 2, 6),
(2, 2, 6, 7),
(3, 3, 6, 1);

-- --------------------------------------------------------

--
-- Table structure for table `request`
--

CREATE TABLE IF NOT EXISTS `request` (
  `Number` char(20) DEFAULT NULL,
  `idtrans_sms` bigint(20) NOT NULL DEFAULT '0',
  `shortcode` char(5) DEFAULT NULL,
  `sms_index` bigint(20) NOT NULL AUTO_INCREMENT,
  `Message` char(160) NOT NULL,
  `sender_ref` varchar(20) DEFAULT NULL,
  `clicked` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`sms_index`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sequence`
--

CREATE TABLE IF NOT EXISTS `sequence` (
  `SEQ_NAME` varchar(50) NOT NULL,
  `SEQ_COUNT` decimal(38,0) DEFAULT NULL,
  PRIMARY KEY (`SEQ_NAME`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sms`
--

CREATE TABLE IF NOT EXISTS `sms` (
  `Number` char(20) DEFAULT NULL,
  `Message` char(255) DEFAULT NULL,
  `date_time` datetime DEFAULT NULL,
  `clicked` int(11) DEFAULT '0',
  `sms_index` bigint(20) NOT NULL AUTO_INCREMENT,
  `shortcode` char(5) DEFAULT '6254',
  PRIMARY KEY (`sms_index`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `smsout`
--

CREATE TABLE IF NOT EXISTS `smsout` (
  `Number` char(20) DEFAULT NULL,
  `shortcode` char(5) DEFAULT NULL,
  `sms_index` bigint(20) NOT NULL AUTO_INCREMENT,
  `datesent` datetime DEFAULT NULL,
  `Message` char(160) NOT NULL,
  `response` varchar(255) DEFAULT NULL,
  `sender_ref` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`sms_index`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sync_inserts_down`
--

CREATE TABLE IF NOT EXISTS `sync_inserts_down` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `table_name` varchar(50) NOT NULL,
  `field_namePK` varchar(50) NOT NULL,
  `field_names` mediumtext NOT NULL,
  `lastid_local` bigint(20) NOT NULL,
  `lastid_remote` bigint(20) NOT NULL,
  `last_syncsession` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ts_iduserteam` int(5) NOT NULL DEFAULT '0',
  `setting_status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `table_name` (`table_name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sync_inserts_up`
--

CREATE TABLE IF NOT EXISTS `sync_inserts_up` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `table_name` varchar(50) NOT NULL,
  `field_namePK` varchar(50) NOT NULL,
  `field_names` mediumtext NOT NULL,
  `lastid_local` bigint(20) NOT NULL,
  `lastid_remote` bigint(20) NOT NULL,
  `last_syncsession` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ts_iduserteam` int(5) NOT NULL DEFAULT '0',
  `setting_status` tinyint(1) NOT NULL DEFAULT '0',
  `scripttimestart` time NOT NULL DEFAULT '00:00:00',
  `scripttimestop` time NOT NULL DEFAULT '00:00:00',
  PRIMARY KEY (`id`),
  KEY `table_name` (`table_name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sysdefaultadminaccess`
--

CREATE TABLE IF NOT EXISTS `sysdefaultadminaccess` (
  `idsysdefaultadmin` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `syssubmodule_idsyssubmodule` int(5) unsigned NOT NULL,
  `usrteamtype_idusrteamtype` int(2) unsigned NOT NULL,
  PRIMARY KEY (`idsysdefaultadmin`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=91 ;

--
-- Dumping data for table `sysdefaultadminaccess`
--

INSERT INTO `sysdefaultadminaccess` (`idsysdefaultadmin`, `syssubmodule_idsyssubmodule`, `usrteamtype_idusrteamtype`) VALUES
(90, 23, 3),
(89, 22, 3),
(88, 19, 3),
(87, 17, 3),
(86, 15, 3),
(85, 14, 3),
(84, 7, 3),
(83, 6, 3),
(82, 3, 3),
(81, 2, 3),
(80, 1, 3),
(12, 1, 4),
(13, 2, 4),
(14, 3, 4),
(15, 6, 4),
(16, 7, 4),
(17, 14, 4),
(18, 15, 4),
(19, 17, 4),
(20, 19, 4),
(21, 22, 4),
(22, 23, 4),
(23, 1, 5),
(24, 2, 5),
(25, 3, 5),
(26, 6, 5),
(27, 7, 5),
(28, 14, 5),
(29, 15, 5),
(30, 17, 5),
(31, 19, 5),
(32, 22, 5),
(33, 23, 5),
(34, 1, 6),
(35, 2, 6),
(36, 3, 6),
(37, 6, 6),
(38, 7, 6),
(39, 14, 6),
(40, 15, 6),
(41, 17, 6),
(42, 19, 6),
(43, 22, 6),
(44, 23, 6),
(45, 1, 2),
(46, 22, 2),
(47, 19, 2),
(48, 17, 2),
(49, 15, 2),
(50, 14, 2),
(51, 7, 2),
(52, 6, 2),
(53, 3, 2),
(54, 2, 2),
(55, 23, 2),
(56, 1, 1),
(57, 22, 1),
(58, 19, 1),
(59, 17, 1),
(60, 15, 1),
(61, 14, 1),
(62, 7, 1),
(63, 6, 1),
(64, 3, 1),
(65, 2, 1),
(66, 23, 1),
(67, 8, 7),
(68, 11, 7),
(69, 12, 7),
(70, 13, 7),
(71, 14, 7),
(72, 1, 8),
(73, 11, 8),
(74, 12, 8),
(75, 13, 8),
(76, 17, 8),
(77, 18, 8),
(78, 14, 8),
(79, 15, 8);

-- --------------------------------------------------------

--
-- Table structure for table `sysdefaultprofiles`
--

CREATE TABLE IF NOT EXISTS `sysdefaultprofiles` (
  `idsysdefaultprofile` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `usrteamtype_idusrteamtype` int(2) unsigned NOT NULL,
  `sysdefaultprofile` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`idsysdefaultprofile`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `sysdefaultprofiles`
--

INSERT INTO `sysdefaultprofiles` (`idsysdefaultprofile`, `usrteamtype_idusrteamtype`, `sysdefaultprofile`) VALUES
(1, 1, 'System Administrator'),
(2, 2, 'System Administrator'),
(3, 3, 'System Administrator'),
(4, 4, 'System Administrator'),
(5, 5, 'System Administrator'),
(6, 6, 'System Administrator'),
(7, 7, 'System Administrator'),
(8, 8, 'System Administrator');

-- --------------------------------------------------------

--
-- Table structure for table `sysdefaultroles`
--

CREATE TABLE IF NOT EXISTS `sysdefaultroles` (
  `idsysdefaultroles` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `sysdefaultprofiles_idsysdefaultprofile` int(5) NOT NULL,
  `usrteamtype_idusrteamtype` int(2) unsigned NOT NULL,
  `sysdefaultrole` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`idsysdefaultroles`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `sysdefaultroles`
--

INSERT INTO `sysdefaultroles` (`idsysdefaultroles`, `sysdefaultprofiles_idsysdefaultprofile`, `usrteamtype_idusrteamtype`, `sysdefaultrole`) VALUES
(1, 1, 1, 'Admin'),
(2, 2, 2, 'Admin'),
(3, 3, 3, 'Admin'),
(4, 4, 4, 'Admin'),
(5, 5, 5, 'Admin'),
(6, 6, 6, 'Admin'),
(7, 7, 7, 'Admin'),
(8, 8, 8, 'Admin');

-- --------------------------------------------------------

--
-- Table structure for table `sysdefaultteamaccess`
--

CREATE TABLE IF NOT EXISTS `sysdefaultteamaccess` (
  `idsysdefaultteam` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `usrteamtype_idusrteamtype` int(2) unsigned NOT NULL,
  `sysmodule_idsysmodule` int(5) unsigned NOT NULL,
  PRIMARY KEY (`idsysdefaultteam`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=52 ;

--
-- Dumping data for table `sysdefaultteamaccess`
--

INSERT INTO `sysdefaultteamaccess` (`idsysdefaultteam`, `usrteamtype_idusrteamtype`, `sysmodule_idsysmodule`) VALUES
(1, 3, 1),
(2, 3, 2),
(3, 3, 3),
(4, 3, 4),
(5, 3, 5),
(6, 3, 8),
(7, 3, 10),
(8, 4, 1),
(9, 4, 2),
(10, 4, 3),
(11, 4, 4),
(12, 4, 5),
(13, 4, 8),
(14, 4, 10),
(15, 5, 1),
(16, 5, 2),
(17, 5, 3),
(18, 5, 4),
(19, 5, 5),
(20, 5, 8),
(21, 5, 10),
(22, 6, 1),
(23, 6, 2),
(24, 6, 3),
(25, 6, 4),
(26, 6, 5),
(27, 6, 8),
(28, 6, 10),
(29, 7, 1),
(30, 7, 2),
(31, 7, 3),
(32, 7, 4),
(33, 7, 5),
(34, 7, 8),
(35, 7, 10),
(36, 2, 1),
(37, 2, 2),
(38, 2, 3),
(39, 2, 4),
(40, 2, 5),
(41, 2, 8),
(42, 2, 10),
(43, 8, 1),
(44, 8, 2),
(45, 8, 3),
(46, 8, 6),
(47, 8, 10),
(48, 8, 11),
(49, 8, 13),
(50, 8, 17),
(51, 8, 18);

-- --------------------------------------------------------

--
-- Table structure for table `sysmodule`
--

CREATE TABLE IF NOT EXISTS `sysmodule` (
  `idsysmodule` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `sysmodulepos_id` int(2) NOT NULL DEFAULT '0',
  `modulename` varchar(30) DEFAULT NULL,
  `listorder` float(3,1) unsigned DEFAULT NULL,
  `module_desc` text,
  `sys_status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`idsysmodule`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=30 ;

--
-- Dumping data for table `sysmodule`
--

INSERT INTO `sysmodule` (`idsysmodule`, `sysmodulepos_id`, `modulename`, `listorder`, `module_desc`, `sys_status`) VALUES
(1, 1, 'My Dashboard', 1.0, 'A Dashboard acts as a welcome page for all system users with relevant shortcuts and graphs', 1),
(2, 1, 'My Tasks', 2.0, 'Task Management enables a user to view new tasks received and submitted tasks to his/her colleagues', 1),
(3, 1, 'My Team', 3.0, 'Users at the Supervisory level can see their team members and overall work load', 1),
(4, 1, 'Tickets', 4.0, 'View all customer tickets received and follow the status of each ticket', 1),
(5, 1, 'Report Center', 5.0, 'Create Reports for the Water Service Company', 1),
(8, 1, 'System Admin', 8.0, 'System Administration for the Water Company to manage users and Workflow', 1),
(9, 1, 'System Admin (Overall)', 9.0, 'Overall System Management', 1),
(10, 1, 'SMS Manager', 10.0, 'Send Bulk SMSes to Customers\r\n', 0),
(11, 2, 'My_Account', 11.0, 'Allows users to view and/or change their Account Information and Account Passwords', 1),
(12, 2, 'Users_Guide', 12.0, 'Allows users to view read tutorials as will as some quick videos', 0),
(13, 2, 'Contact_Support', 13.0, 'Allows users to submit an online Help Form to MajiVoice / WASREB support team', 1),
(23, 1, 'Escalations', 2.1, 'Escalations for work not done from my team', 0),
(24, 1, 'Notifications', 2.2, 'FYI notices of work', 0),
(25, 1, 'My Team Tasks', 4.1, 'Managing Tasks belonging to your team', 0);

-- --------------------------------------------------------

--
-- Table structure for table `sysmodulepos`
--

CREATE TABLE IF NOT EXISTS `sysmodulepos` (
  `id` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `moduleposition` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `sysmodulepos`
--

INSERT INTO `sysmodulepos` (`id`, `moduleposition`) VALUES
(1, 'Top Tabs'),
(2, 'Header Button');

-- --------------------------------------------------------

--
-- Table structure for table `sysmosubmodpglink`
--

CREATE TABLE IF NOT EXISTS `sysmosubmodpglink` (
  `idsysmosubmodpglink` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `syssubmodule_idsyssubmodule` int(5) unsigned NOT NULL,
  `pglink` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`idsysmosubmodpglink`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `sysmosubmodpglink`
--

INSERT INTO `sysmosubmodpglink` (`idsysmosubmodpglink`, `syssubmodule_idsyssubmodule`, `pglink`) VALUES
(1, 5, 'mod_myteam.php'),
(2, 2, 'mod_tskin.php'),
(3, 3, 'mod_tskout.php');

-- --------------------------------------------------------

--
-- Table structure for table `sysprofiles`
--

CREATE TABLE IF NOT EXISTS `sysprofiles` (
  `idsysprofiles` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `usrteamzone_idusrteamzone` int(5) unsigned NOT NULL,
  `sysprofile` varchar(30) DEFAULT NULL,
  `createdby` int(8) NOT NULL,
  `createdon` datetime NOT NULL,
  PRIMARY KEY (`idsysprofiles`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `sysprofiles`
--

INSERT INTO `sysprofiles` (`idsysprofiles`, `usrteamzone_idusrteamzone`, `sysprofile`, `createdby`, `createdon`) VALUES
(1, 1, 'Super Admin', 1, '2012-04-25 00:36:22'),
(2, 1, 'Customer Care Officers', 1, '2015-08-15 13:07:44');

-- --------------------------------------------------------

--
-- Table structure for table `syssubmodule`
--

CREATE TABLE IF NOT EXISTS `syssubmodule` (
  `idsyssubmodule` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `sysmodule_idsysmodule` int(5) unsigned NOT NULL,
  `submodule` varchar(150) DEFAULT NULL,
  `listorder` int(5) unsigned DEFAULT NULL,
  `is_default` tinyint(1) unsigned DEFAULT '0',
  `submod_status` tinyint(1) NOT NULL DEFAULT '1',
  `submod_type` char(10) NOT NULL DEFAULT 'LINK',
  PRIMARY KEY (`idsyssubmodule`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=89 ;

--
-- Dumping data for table `syssubmodule`
--

INSERT INTO `syssubmodule` (`idsyssubmodule`, `sysmodule_idsysmodule`, `submodule`, `listorder`, `is_default`, `submod_status`, `submod_type`) VALUES
(1, 1, 'My Dashboard', 1, 1, 1, 'LINK'),
(2, 2, 'My Tasks IN-Tray', 1, 1, 1, 'LINK'),
(3, 2, 'My Tasks OUT', 2, 0, 1, 'LINK'),
(4, 4, 'Map View', 5, 0, 1, 'LINK'),
(5, 3, 'List Team', 1, 1, 1, 'LINK'),
(6, 4, 'Find Ticket', 2, 0, 1, 'LINK'),
(7, 5, 'Incidence / Occurence', 1, 1, 0, 'LINK'),
(8, 5, 'Team Reports', 2, 0, 0, 'LINK'),
(14, 8, 'User Accounts', 5, 0, 1, 'LINK'),
(15, 8, 'User Access Profiles', 3, 1, 1, 'LINK'),
(16, 9, 'Organization Modules', 3, 0, 1, 'LINK'),
(17, 8, 'Workflows Configuration', 6, 0, 1, 'LINK'),
(18, 9, 'Organizations & Zones', 1, 1, 1, 'LINK'),
(19, 8, 'User Roles & Groups', 4, 0, 1, 'LINK'),
(20, 9, 'Global User Management', 7, 0, 1, 'LINK'),
(21, 9, 'System Parameters', 9, 0, 0, 'LINK'),
(22, 10, 'Compose & Send SMS', 2, 0, 1, 'LINK'),
(23, 10, 'SMS Stats', 1, 1, 1, 'LINK'),
(24, 4, 'New Tickets', 1, 1, 1, 'LINK'),
(25, 4, 'Find Ticket', 3, 0, 0, 'LINK'),
(26, 2, 'Notifications', 3, 0, 0, 'LINK'),
(27, 10, 'Buy SMS', 4, 0, 0, 'LINK'),
(28, 5, 'Overview', 1, 1, 1, 'LINK'),
(29, 5, 'Received Tickets', 2, 0, 1, 'LINK'),
(30, 8, 'Ticket Routing', 10, 0, 0, 'LINK'),
(31, 9, 'Service Areas (Overall)', NULL, 0, 1, 'LINK'),
(32, 11, 'My Account Details', 1, 1, 1, 'LINK'),
(33, 11, 'My Password', 2, 0, 1, 'LINK'),
(34, 12, 'Read Online', 1, 1, 1, 'LINK'),
(35, 12, 'Short Videos', 2, 0, 1, 'LINK'),
(36, 13, 'Get Help', 1, 1, 1, 'LINK'),
(37, 5, 'Field Team Performance', 3, 0, 0, 'LINK'),
(38, 5, 'Trends & Analysis', 4, 0, 0, 'LINK'),
(43, 8, 'System Parameters', 10, 0, 1, 'LINK'),
(39, 9, 'Org. Escalation Rules', 8, 0, 1, 'LINK'),
(40, 9, 'Organization Groups', 2, 0, 0, 'LINK'),
(51, 18, 'New Asset', 2, 0, 1, 'LINK'),
(54, 20, 'Escalated IN Tickets', 1, 1, 1, 'LINK'),
(55, 20, 'Map View', 2, 0, 1, 'LINK'),
(56, 21, 'View WorkPlans', NULL, 0, 1, 'LINK'),
(57, 22, 'View Event Reports', NULL, 0, 1, 'LINK'),
(58, 4, 'Escalated OUT Tickets', 3, 0, 0, 'LINK'),
(59, 23, 'Escalated Tasks', 1, 1, 1, 'LINK'),
(60, 24, 'Received', 1, 1, 1, 'LINK'),
(61, 25, 'Team Tasks', 1, 1, 1, 'LINK'),
(63, 8, 'Task Reorder/Reassign', 10, 0, 0, 'LINK'),
(64, 27, 'Task Batching', 1, 1, 1, 'COMPOSITE'),
(65, 28, 'Voucher Adjustment', 1, 1, 0, 'COMPOSITE'),
(66, 5, 'Pending Tickets', 3, 0, 1, 'LINK'),
(67, 5, 'Closed Tickets', 4, 0, 1, 'LINK'),
(68, 29, 'CEG Escalation', 1, 1, 1, 'LINK'),
(69, 28, 'OMMIS Forms', 2, 1, 1, 'FORM'),
(70, 28, 'Complaint Audit Form', 2, 1, 1, 'FORM'),
(88, 28, 'Contracting Form', 3, 1, 1, 'FORM'),
(87, 10, 'SMS Bundles', 3, 0, 1, 'LINK');

-- --------------------------------------------------------

--
-- Table structure for table `systeamaccess`
--

CREATE TABLE IF NOT EXISTS `systeamaccess` (
  `idsysteamaccess` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `usrteam_idusrteam` int(4) unsigned NOT NULL,
  `sysmodule_idsysmodule` int(5) unsigned NOT NULL,
  `createdby` int(8) unsigned DEFAULT NULL,
  `createdon` datetime DEFAULT NULL,
  `modifiedby` int(8) unsigned DEFAULT NULL,
  `modifiedon` datetime DEFAULT NULL,
  `permview` tinyint(1) unsigned DEFAULT NULL,
  `perminsert` tinyint(1) unsigned DEFAULT NULL,
  `permupdate` tinyint(1) unsigned DEFAULT NULL,
  `permdelete` tinyint(1) unsigned DEFAULT NULL,
  PRIMARY KEY (`idsysteamaccess`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=74 ;

--
-- Dumping data for table `systeamaccess`
--

INSERT INTO `systeamaccess` (`idsysteamaccess`, `usrteam_idusrteam`, `sysmodule_idsysmodule`, `createdby`, `createdon`, `modifiedby`, `modifiedon`, `permview`, `perminsert`, `permupdate`, `permdelete`) VALUES
(1, 1, 1, 1, '2012-04-24 20:28:02', NULL, NULL, 1, 1, 1, 1),
(2, 1, 2, 1, '2012-04-24 20:28:00', NULL, NULL, 1, 1, 1, 1),
(3, 1, 3, 1, '2012-04-24 20:28:01', NULL, NULL, 1, 1, 1, 1),
(4, 1, 4, 1, '2012-04-24 20:27:46', NULL, NULL, 1, 1, 1, 1),
(5, 1, 5, 1, '2012-04-25 00:59:14', NULL, NULL, NULL, NULL, NULL, NULL),
(6, 1, 8, 1, '2012-04-25 00:59:14', NULL, NULL, NULL, NULL, NULL, NULL),
(7, 1, 9, 1, '2012-04-25 00:59:14', NULL, NULL, NULL, NULL, NULL, NULL),
(8, 1, 11, 1, '2012-04-25 00:59:14', NULL, NULL, NULL, NULL, NULL, NULL),
(9, 1, 12, 1, '2012-04-25 00:59:14', NULL, NULL, NULL, NULL, NULL, NULL),
(10, 1, 13, 1, '2012-04-25 00:59:14', NULL, NULL, NULL, NULL, NULL, NULL),
(11, 1, 25, 1, '2012-04-25 00:59:14', NULL, NULL, NULL, NULL, NULL, NULL),
(73, 1, 29, 1, '2012-07-08 09:57:26', NULL, NULL, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `systemprofileaccess`
--

CREATE TABLE IF NOT EXISTS `systemprofileaccess` (
  `idsystemprofileaccess` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `syssubmodule_idsyssubmodule` int(5) unsigned NOT NULL,
  `sysprofiles_idsysprofiles` int(8) unsigned NOT NULL,
  `permview` tinyint(1) unsigned DEFAULT '0',
  `perminsert` tinyint(1) unsigned DEFAULT '0',
  `permupdate` tinyint(1) unsigned DEFAULT '0',
  `permdelete` tinyint(1) unsigned DEFAULT '0',
  `mobile_access` int(1) NOT NULL DEFAULT '0',
  `global_access` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idsystemprofileaccess`),
  KEY `syssubmodule_idsyssubmodule` (`syssubmodule_idsyssubmodule`),
  KEY `sysprofiles_idsysprofiles` (`sysprofiles_idsysprofiles`),
  KEY `permview` (`permview`),
  KEY `perminsert` (`perminsert`),
  KEY `permupdate` (`permupdate`),
  KEY `permdelete` (`permdelete`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=73 ;

--
-- Dumping data for table `systemprofileaccess`
--

INSERT INTO `systemprofileaccess` (`idsystemprofileaccess`, `syssubmodule_idsyssubmodule`, `sysprofiles_idsysprofiles`, `permview`, `perminsert`, `permupdate`, `permdelete`, `mobile_access`, `global_access`) VALUES
(1, 1, 1, 1, 1, 1, 1, 0, 0),
(2, 2, 1, 1, 1, 1, 1, 0, 0),
(3, 3, 1, 1, 1, 1, 1, 0, 0),
(4, 4, 1, 1, 1, 1, 1, 0, 0),
(5, 5, 1, 1, 1, 1, 1, 0, 0),
(6, 6, 1, 1, 1, 1, 1, 0, 0),
(7, 7, 1, 1, 1, 1, 1, 0, 0),
(8, 8, 1, 1, 1, 1, 1, 0, 0),
(9, 14, 1, 1, 1, 1, 1, 0, 0),
(10, 15, 1, 1, 1, 1, 1, 0, 0),
(11, 16, 1, 1, 1, 1, 1, 0, 0),
(12, 17, 1, 1, 1, 1, 1, 0, 0),
(13, 18, 1, 1, 1, 1, 1, 0, 0),
(14, 19, 1, 1, 1, 1, 1, 0, 1),
(15, 20, 1, 1, 1, 1, 1, 1, 0),
(16, 21, 1, 1, 1, 1, 1, 1, 0),
(17, 22, 1, 1, 1, 1, 1, 1, 1),
(18, 23, 1, 1, 1, 1, 1, 0, 1),
(19, 24, 1, 1, 1, 1, 1, 0, 1),
(20, 25, 1, 1, 1, 1, 1, 0, 1),
(21, 26, 1, 1, 1, 1, 1, 0, 1),
(22, 28, 1, 1, 1, 1, 1, 0, 0),
(23, 29, 1, 1, 1, 1, 1, 0, 0),
(24, 30, 1, 1, 1, 1, 1, 0, 0),
(25, 31, 1, 1, 1, 1, 1, 1, 0),
(26, 32, 1, 1, 1, 1, 1, 1, 0),
(27, 33, 1, 1, 1, 1, 1, 1, 0),
(28, 34, 1, 1, 1, 1, 1, 1, 0),
(29, 35, 1, 1, 1, 1, 1, 1, 0),
(30, 36, 1, 1, 1, 1, 1, 1, 0),
(31, 37, 1, 1, 1, 1, 1, 1, 0),
(32, 38, 1, 1, 1, 1, 1, 1, 0),
(33, 39, 1, 1, 1, 1, 1, 1, 0),
(34, 40, 1, 1, 1, 1, 1, 1, 0),
(35, 43, 1, 1, 1, 1, 1, 1, 0),
(36, 1, 2, 1, 1, 1, 1, 0, 0),
(37, 2, 2, 1, 1, 1, 1, 0, 0),
(38, 3, 2, 1, 1, 1, 1, 0, 0),
(39, 4, 2, 1, 1, 1, 1, 0, 0),
(40, 5, 2, 1, 1, 1, 1, 0, 0),
(41, 6, 2, 1, 1, 1, 1, 0, 0),
(42, 7, 2, 1, 1, 1, 1, 0, 0),
(43, 8, 2, 1, 1, 1, 1, 0, 0),
(71, 66, 2, 1, 1, 1, 1, 0, 0),
(72, 67, 2, 1, 1, 1, 1, 0, 0),
(51, 21, 2, 1, 1, 1, 1, 1, 0),
(52, 22, 2, 1, 1, 1, 1, 1, 1),
(53, 23, 2, 1, 1, 1, 1, 0, 1),
(54, 24, 2, 1, 1, 1, 1, 0, 1),
(55, 25, 2, 1, 1, 1, 1, 0, 1),
(56, 26, 2, 1, 1, 1, 1, 0, 1),
(57, 28, 2, 1, 1, 1, 1, 0, 0),
(58, 29, 2, 1, 1, 1, 1, 0, 0),
(59, 30, 2, 1, 1, 1, 1, 0, 0),
(61, 32, 2, 1, 1, 1, 1, 1, 0),
(62, 33, 2, 1, 1, 1, 1, 1, 0),
(63, 34, 2, 1, 1, 1, 1, 1, 0),
(64, 35, 2, 1, 1, 1, 1, 1, 0),
(65, 36, 2, 1, 1, 1, 1, 1, 0),
(66, 37, 2, 1, 1, 1, 1, 1, 0),
(67, 38, 2, 1, 1, 1, 1, 1, 0),
(69, 40, 2, 1, 1, 1, 1, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tips`
--

CREATE TABLE IF NOT EXISTS `tips` (
  `idtips` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `tip` varchar(250) DEFAULT NULL,
  `createdon` datetime DEFAULT NULL,
  `createdby` int(8) unsigned DEFAULT NULL,
  PRIMARY KEY (`idtips`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `tips`
--

INSERT INTO `tips` (`idtips`, `tip`, `createdon`, `createdby`) VALUES
(1, 'You can reach the support team any time by clicking on the Contact Support button at the top right section', NULL, NULL),
(3, 'Click on ''My Account'' button at the top right section to change you password!', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tktactivity`
--

CREATE TABLE IF NOT EXISTS `tktactivity` (
  `idtktactivity` bigint(20) NOT NULL AUTO_INCREMENT,
  `idtktesclevel` int(2) unsigned NOT NULL,
  `idtktin` bigint(20) NOT NULL,
  `idtktactivitytype` int(3) unsigned NOT NULL,
  `activity_date` datetime DEFAULT NULL,
  `activity_details` text,
  `entered_by` int(2) unsigned DEFAULT NULL,
  `entered_by_role` int(8) NOT NULL,
  `addedby` int(8) unsigned DEFAULT NULL,
  `addedon` datetime DEFAULT NULL,
  `modifiedby` int(8) unsigned DEFAULT NULL,
  `modifiedon` datetime DEFAULT NULL,
  PRIMARY KEY (`idtktactivity`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tktactivityclass`
--

CREATE TABLE IF NOT EXISTS `tktactivityclass` (
  `idtktactivityclass` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `tktactivityclass` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`idtktactivityclass`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `tktactivityclass`
--

INSERT INTO `tktactivityclass` (`idtktactivityclass`, `tktactivityclass`) VALUES
(1, 'WorkPlan Activities'),
(2, 'Ticket Followup Activities'),
(3, 'Work Plan Statuses');

-- --------------------------------------------------------

--
-- Table structure for table `tktactivityowner`
--

CREATE TABLE IF NOT EXISTS `tktactivityowner` (
  `idtktactivityowner` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orgesc_idorgesc` int(2) NOT NULL,
  `idtktin` bigint(20) NOT NULL,
  `idusrac` int(8) unsigned NOT NULL,
  `addedon` datetime DEFAULT NULL,
  `addedby` int(9) DEFAULT NULL,
  PRIMARY KEY (`idtktactivityowner`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tktactivitytype`
--

CREATE TABLE IF NOT EXISTS `tktactivitytype` (
  `idtktactivitytype` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `tktactivityclass_idtktactivityclass` int(3) NOT NULL DEFAULT '0',
  `activitytype` varchar(50) DEFAULT NULL,
  `activitytypedesc` text,
  `is_disagg` tinyint(1) NOT NULL DEFAULT '0',
  `is_attach` tinyint(1) NOT NULL DEFAULT '0',
  `is_attendance` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idtktactivitytype`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tktcategory`
--

CREATE TABLE IF NOT EXISTS `tktcategory` (
  `idtktcategory` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `tktcategory_idtktcategory` int(3) unsigned NOT NULL DEFAULT '0',
  `tktcategoryname` varchar(30) DEFAULT NULL,
  `tktcategoryname_swa` varchar(30) DEFAULT NULL,
  `tktcategorydesc` text,
  `tat` int(11) unsigned DEFAULT NULL,
  `createdby` int(8) unsigned DEFAULT NULL,
  `createdon` datetime DEFAULT NULL,
  `modifiedby` int(8) unsigned DEFAULT NULL,
  `modifiedon` datetime DEFAULT NULL,
  `gmap_bubble` varchar(20) NOT NULL,
  `category_pref` varchar(6) DEFAULT NULL,
  `internal_task_cat` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idtktcategory`),
  KEY `tktcategoryname` (`tktcategoryname`),
  KEY `category_pref` (`category_pref`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=28 ;

--
-- Dumping data for table `tktcategory`
--

INSERT INTO `tktcategory` (`idtktcategory`, `tktcategory_idtktcategory`, `tktcategoryname`, `tktcategoryname_swa`, `tktcategorydesc`, `tat`, `createdby`, `createdon`, `modifiedby`, `modifiedon`, `gmap_bubble`, `category_pref`, `internal_task_cat`) VALUES
(1, 0, 'No Water', 'Hakuna Maji', 'Lack of Water in the Taps', 172800, 1, '2012-03-24 06:54:15', NULL, NULL, 'bubble_1', 'SF', 0),
(2, 0, 'Water Quality', 'Ubora wa maji', 'Includes Dirty Water, Turbidity Issues, Any Smells etc', 259200, 1, '2012-03-24 06:57:51', NULL, NULL, 'bubble_2', 'WQ', 0),
(3, 0, 'Water Leak', 'Mfereji uliopasuka', NULL, 172800, 1, '2012-03-24 06:58:26', NULL, NULL, 'bubble_3', 'LE', 0),
(4, 0, 'Sewer Blockage', 'Maji taka', NULL, 172800, 1, '2012-03-24 06:58:39', NULL, NULL, 'bubble_4', 'SE', 0),
(5, 0, 'Billing', 'Malipo', NULL, 2592000, 1, '2012-03-24 06:59:29', NULL, NULL, 'bubble_5', 'BL', 0),
(6, 0, 'Vandalism/Theft', 'Uharibifu au wizi', NULL, 259200, 1, '2012-03-24 07:00:00', NULL, NULL, 'bubble_6', 'VN', 0),
(7, 0, 'Meter Problems', 'Laini ya Mita', NULL, 259200, 1, '2012-03-24 07:00:21', NULL, NULL, 'bubble_7', 'MC', 0),
(8, 0, 'Corruption', 'Ufisadi', NULL, 259200, 1, '2012-03-24 07:00:32', NULL, NULL, 'bubble_9', 'CR', 0),
(9, 0, 'Customer Care', 'Huduma ya wateja', NULL, 259200, 1, '2012-03-24 07:00:56', NULL, NULL, 'bubble_10', 'CC', 0),
(10, 0, 'General', NULL, NULL, 259200, 1, '2012-03-24 07:01:06', NULL, NULL, 'bubble_10', 'GE', 0),
(11, 7, 'Compliments', 'Pongezi', NULL, 259200, 1, '2012-03-27 12:42:09', NULL, NULL, 'bubble_10', 'CO', 0),
(12, 7, 'Satisfied?', 'Kuridhika?', NULL, 259200, 1, '2012-03-29 17:47:51', NULL, NULL, 'bubble_10', 'SA', 0),
(13, 0, 'Major Burst', NULL, NULL, 259200, 1, '2013-04-24 13:43:45', NULL, NULL, 'bubble_10', 'MB', 0),
(14, 0, 'New Connection', NULL, NULL, 259200, 1, NULL, NULL, NULL, 'bubble_10', 'NC', 0),
(15, 0, 'Account Termination', NULL, NULL, 259200, 1, NULL, NULL, NULL, 'bubble_10', 'AT', 0),
(16, 0, 'Stolen Meter', NULL, NULL, 259200, 1, NULL, NULL, NULL, 'bubble_10', 'SM', 0),
(17, 0, 'Illegal Connection', NULL, NULL, 259200, 1, NULL, NULL, NULL, 'bubble_10', 'IL', 0),
(18, 0, 'Meter Reading', NULL, NULL, 864000, 1, NULL, NULL, NULL, 'bubble_10', 'MR', 0),
(19, 0, 'Faulty Meters', NULL, NULL, 259200, 1, NULL, NULL, NULL, 'bubble_10', 'FM', 0),
(20, 0, 'Parallel Accounts', NULL, NULL, 259200, 1, NULL, NULL, NULL, 'bubble_10', 'PA', 0),
(21, 0, 'Deposit Refunds', NULL, NULL, 259200, 1, NULL, NULL, NULL, 'bubble_10', 'DR', 0),
(22, 0, 'Non Reflected Payments', NULL, NULL, 259200, 1, NULL, NULL, NULL, 'bubble_10', 'RP', 0),
(23, 0, 'Reconnections/Disconnections', NULL, NULL, 259200, 1, NULL, NULL, NULL, 'bubble_10', 'RD', 0),
(24, 0, 'ICT_Support', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'bubble_10', 'IT', 0),
(25, 0, 'Incorrect Account Details', NULL, NULL, 259200, 1, NULL, NULL, NULL, 'bubble_10', 'AD', 0),
(26, 0, 'Contracting', NULL, NULL, 129600, 1, NULL, NULL, NULL, 'bubble_10', 'CT', 0),
(27, 0, 'New Customer Contracting', 'Contracting', 'Contracting Workflow', 2592000, 1, '2015-03-03 00:00:00', NULL, NULL, '10', 'CTR', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tktcategory_sub`
--

CREATE TABLE IF NOT EXISTS `tktcategory_sub` (
  `idtktcategory_sub` int(3) NOT NULL AUTO_INCREMENT,
  `tktcategory_idtktcategory` int(3) NOT NULL,
  `tktcategory_sublbl` varchar(100) NOT NULL,
  `tktcategory_sub_values` char(10) NOT NULL,
  `tktcategory_sub_source` char(10) NOT NULL,
  PRIMARY KEY (`idtktcategory_sub`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=43 ;

--
-- Dumping data for table `tktcategory_sub`
--

INSERT INTO `tktcategory_sub` (`idtktcategory_sub`, `tktcategory_idtktcategory`, `tktcategory_sublbl`, `tktcategory_sub_values`, `tktcategory_sub_source`) VALUES
(1, 5, 'Excess Usage', 'ZO011', 'CMS'),
(2, 5, 'Reading Error', 'ZO012', 'CMS'),
(3, 5, 'Calculation Errors', 'ZO013', 'CMS'),
(4, 5, 'Miscellaneous Charges Error', 'ZO015', 'CMS'),
(5, 5, 'Other Bill', 'ZO019', 'CMS'),
(6, 5, 'Incorrect Direct Debit Address', 'ZO021', 'CMS'),
(7, 5, 'Incorrect Invoice Addressee Name', 'ZO022', 'CMS'),
(8, 5, 'Other Collection Errors', 'ZO029', 'CMS'),
(9, 5, 'Incorrect Service Contract Data', 'ZO031', 'CMS'),
(10, 5, 'Other Contracting Errors', 'ZO039', 'CMS'),
(11, 5, 'Metering Equipment', 'ZO041', 'CMS'),
(12, 5, 'Control Equipment', 'ZO042', 'CMS'),
(13, 5, 'Adjustment Error', 'ZO043', 'CMS'),
(14, 5, 'Damaged Meter', 'ZO045', 'CMS'),
(15, 5, 'Invoice Dispatch', 'ZO046', 'CMS'),
(16, 5, 'Due To Defects In Distribution Network', 'ZO047', 'CMS'),
(17, 5, 'Overestimated', 'ZO048', 'CMS'),
(18, 5, 'Misposts', 'ZO049', 'CMS'),
(19, 5, 'Error On Reconnection & Deposit Charges', 'ZO050', 'CMS'),
(20, 5, 'Underestimated', 'ZO051', 'CMS'),
(21, 5, 'Other Types Of Complaint', 'ZO999', 'CMS'),
(22, 18, 'Excess Usage', 'ZO011', 'CMS'),
(23, 18, 'Reading Error', 'ZO012', 'CMS'),
(24, 18, 'Calculation Errors', 'ZO013', 'CMS'),
(25, 18, 'Miscellaneous Charges Error', 'ZO015', 'CMS'),
(26, 18, 'Other Bill', 'ZO019', 'CMS'),
(27, 18, 'Incorrect Direct Debit Address', 'ZO021', 'CMS'),
(28, 18, 'Incorrect Invoice Addressee Name', 'ZO022', 'CMS'),
(29, 18, 'Other Collection Errors', 'ZO029', 'CMS'),
(30, 18, 'Incorrect Service Contract Data', 'ZO031', 'CMS'),
(31, 18, 'Other Contracting Errors', 'ZO039', 'CMS'),
(32, 18, 'Metering Equipment', 'ZO041', 'CMS'),
(33, 18, 'Control Equipment', 'ZO042', 'CMS'),
(34, 18, 'Adjustment Error', 'ZO043', 'CMS'),
(35, 18, 'Damaged Meter', 'ZO045', 'CMS'),
(36, 18, 'Invoice Dispatch', 'ZO046', 'CMS'),
(37, 18, 'Due To Defects In Distribution Network', 'ZO047', 'CMS'),
(38, 18, 'Overestimated', 'ZO048', 'CMS'),
(39, 18, 'Misposts', 'ZO049', 'CMS'),
(40, 18, 'Error On Reconnection & Deposit Charges', 'ZO050', 'CMS'),
(41, 18, 'Underestimated', 'ZO051', 'CMS'),
(42, 18, 'Other Types Of Complaint', 'ZO999', 'CMS');

-- --------------------------------------------------------

--
-- Table structure for table `tktchannel`
--

CREATE TABLE IF NOT EXISTS `tktchannel` (
  `idtktchannel` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `tktchannelname` varchar(40) DEFAULT NULL,
  `tktchanneldesc` text,
  `portalaccessibility` tinyint(1) unsigned DEFAULT NULL,
  `button_path` varchar(250) DEFAULT NULL,
  `icon_path` varchar(250) DEFAULT NULL,
  `cms_value` char(5) DEFAULT NULL,
  PRIMARY KEY (`idtktchannel`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `tktchannel`
--

INSERT INTO `tktchannel` (`idtktchannel`, `tktchannelname`, `tktchanneldesc`, `portalaccessibility`, `button_path`, `icon_path`, `cms_value`) VALUES
(1, 'USSD', 'Unstructured Supplementary Service Data', 0, NULL, NULL, 'ZP001'),
(2, 'SMS', 'Short Message Service', 0, NULL, NULL, 'ZP001'),
(3, 'Mobile Web', NULL, 0, NULL, NULL, 'ZP001'),
(4, 'Online Portal', NULL, 0, NULL, NULL, 'ZP006'),
(5, 'Over the Counter', 'All enquiries/reports/complaints made on the counter', 1, NULL, NULL, 'ZP001'),
(6, 'Telephone Call', 'All enquiries/reports/complaints received via phone', 1, NULL, NULL, 'ZP002'),
(7, 'Letters', 'All enquiries/Reports/complaints submitted via physical letters/notes etc', 1, NULL, NULL, 'ZP003'),
(8, 'eMail', 'All enquiries/Reports/complaints submitted via email etc', 1, NULL, NULL, 'ZP006'),
(9, 'Social Network Site', 'All enquiries/Reports/complaints submitted via Social Networking Sites such as FaceBook, Twitter etc', 0, NULL, NULL, 'ZP006');

-- --------------------------------------------------------

--
-- Table structure for table `tktesclevels`
--

CREATE TABLE IF NOT EXISTS `tktesclevels` (
  `idtktesclevel` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `esclevel` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`idtktesclevel`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `tktesclevels`
--

INSERT INTO `tktesclevels` (`idtktesclevel`, `esclevel`) VALUES
(1, 'Escalation Level 1'),
(2, 'Escalation Level 2'),
(3, 'Escalation Level 3');

-- --------------------------------------------------------

--
-- Table structure for table `tktfeedback`
--

CREATE TABLE IF NOT EXISTS `tktfeedback` (
  `idtktfeedback` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `wftskflow_idwftskflow` int(11) unsigned NOT NULL,
  `feedbacksms` varchar(100) DEFAULT NULL,
  `feedbackemail` text,
  `actionstatus` tinyint(1) unsigned DEFAULT NULL,
  `createdby` int(5) unsigned DEFAULT NULL,
  `createdon` datetime DEFAULT NULL,
  `modifiedby` int(5) unsigned DEFAULT NULL,
  `modifiedon` datetime DEFAULT NULL,
  PRIMARY KEY (`idtktfeedback`),
  KEY `wftskflow_idwftskflow` (`wftskflow_idwftskflow`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tktfeedbacklog`
--

CREATE TABLE IF NOT EXISTS `tktfeedbacklog` (
  `idtktfeedbacklog` bigint(20) NOT NULL AUTO_INCREMENT,
  `wftskflow_idwftskflow` int(11) unsigned NOT NULL,
  `wftasks_idwftasks` bigint(20) NOT NULL,
  `msgsms` varchar(160) DEFAULT NULL,
  `msgemail` text,
  `mobilephone` varchar(15) DEFAULT NULL,
  `emailadd` varchar(150) DEFAULT NULL,
  `createdon` datetime DEFAULT NULL,
  PRIMARY KEY (`idtktfeedbacklog`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tktfollowup`
--

CREATE TABLE IF NOT EXISTS `tktfollowup` (
  `idtktfollowup` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `reports_tktin_public_tktstatus_idtktstatus` int(2) unsigned NOT NULL,
  `reports_tktin_public_idtktin` bigint(20) NOT NULL,
  `usrac_idusrac` int(8) unsigned NOT NULL,
  `usrrole_idusrrole` int(8) unsigned NOT NULL,
  `tktfollowupstat_idtktfollowupstat` int(2) unsigned NOT NULL,
  `followupcomment` text,
  `createdby` int(8) unsigned DEFAULT NULL,
  `createdon` datetime DEFAULT NULL,
  PRIMARY KEY (`idtktfollowup`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tktfollowupstat`
--

CREATE TABLE IF NOT EXISTS `tktfollowupstat` (
  `idtktfollowupstat` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `followupstat` varchar(30) DEFAULT NULL,
  `descrip` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`idtktfollowupstat`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tktgroup`
--

CREATE TABLE IF NOT EXISTS `tktgroup` (
  `idtktgroup` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tktstatus_idtktstatus` int(2) unsigned NOT NULL,
  `timereportedfirst` datetime DEFAULT NULL,
  `timereportedlast` datetime DEFAULT NULL,
  `tktcounter` int(5) unsigned DEFAULT NULL,
  `timeclosed` datetime DEFAULT NULL,
  `tracknumber` int(8) NOT NULL,
  `tracknumber_tktid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idtktgroup`),
  UNIQUE KEY `tracknumber` (`tracknumber`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tktin`
--

CREATE TABLE IF NOT EXISTS `tktin` (
  `idtktin` bigint(20) NOT NULL DEFAULT '0',
  `tktlang_idtktlang` int(2) unsigned NOT NULL,
  `usrteamzone_idusrteamzone` int(5) unsigned NOT NULL,
  `usrteam_idusrteam` int(4) unsigned NOT NULL,
  `tktgroup_idtktgroup` int(11) unsigned NOT NULL DEFAULT '0',
  `tktchannel_idtktchannel` int(2) unsigned NOT NULL,
  `tktstatus_idtktstatus` int(2) unsigned NOT NULL,
  `tktcategory_idtktcategory` int(3) unsigned NOT NULL,
  `tkttype_idtkttype` int(1) unsigned NOT NULL,
  `sendername` varchar(50) DEFAULT NULL,
  `sendergender` char(1) DEFAULT NULL,
  `senderemail` varchar(200) DEFAULT NULL,
  `senderphone` varchar(20) DEFAULT NULL,
  `refnumber` varchar(20) NOT NULL,
  `tktdesc` text,
  `timereported` datetime NOT NULL,
  `timedeadline` datetime DEFAULT NULL,
  `timeclosed` datetime DEFAULT NULL,
  `city_town` varchar(250) DEFAULT NULL,
  `loctowns_idloctowns` int(8) unsigned NOT NULL DEFAULT '0',
  `road_street` varchar(60) DEFAULT NULL,
  `building_estate` varchar(60) DEFAULT NULL,
  `unitno` varchar(10) DEFAULT NULL,
  `waterac` varchar(15) DEFAULT NULL,
  `kioskno` varchar(10) DEFAULT NULL,
  `usrsession` varchar(50) DEFAULT NULL,
  `createdby` int(8) unsigned DEFAULT '2',
  `createdon` datetime DEFAULT NULL,
  `modifiedby` int(8) NOT NULL DEFAULT '0',
  `modifiedon` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `wfteamesc_idwfteamesc` int(4) NOT NULL DEFAULT '0',
  `tktpub_count` int(11) NOT NULL DEFAULT '1',
  `orgesc_idorgesc` int(11) NOT NULL DEFAULT '0',
  `landmark` varchar(250) DEFAULT NULL,
  `is_validated` tinyint(1) NOT NULL DEFAULT '1',
  `refnumber_prev` varchar(20) DEFAULT NULL,
  `wftaskstrac_idwftaskstrac` int(11) DEFAULT NULL,
  `idtktinPK` int(11) NOT NULL AUTO_INCREMENT,
  `batch_number` int(11) NOT NULL DEFAULT '0',
  `wftasks_batch_idwftasks_batch` int(5) NOT NULL DEFAULT '0',
  `voucher_number` int(3) NOT NULL DEFAULT '0',
  `cms_complaint_no` bigint(20) DEFAULT NULL,
  `tktcategory_sub_idtktcategory_sub` int(3) DEFAULT NULL,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `internal_task` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idtktinPK`),
  UNIQUE KEY `refnumber` (`refnumber`),
  KEY `usrteam_idusrteam` (`usrteam_idusrteam`),
  KEY `tktgroup_idtktgroup` (`tktgroup_idtktgroup`),
  KEY `tktchannel_idtktchannel` (`tktchannel_idtktchannel`),
  KEY `tktstatus_idtktstatus` (`tktstatus_idtktstatus`),
  KEY `tktcategory_idtktcategory` (`tktcategory_idtktcategory`),
  KEY `tkttype_idtkttype` (`tkttype_idtkttype`),
  KEY `loctowns_idloctowns` (`loctowns_idloctowns`),
  KEY `idtktin` (`idtktin`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=250 ;

--
-- Dumping data for table `tktin`
--

INSERT INTO `tktin` (`idtktin`, `tktlang_idtktlang`, `usrteamzone_idusrteamzone`, `usrteam_idusrteam`, `tktgroup_idtktgroup`, `tktchannel_idtktchannel`, `tktstatus_idtktstatus`, `tktcategory_idtktcategory`, `tkttype_idtkttype`, `sendername`, `sendergender`, `senderemail`, `senderphone`, `refnumber`, `tktdesc`, `timereported`, `timedeadline`, `timeclosed`, `city_town`, `loctowns_idloctowns`, `road_street`, `building_estate`, `unitno`, `waterac`, `kioskno`, `usrsession`, `createdby`, `createdon`, `modifiedby`, `modifiedon`, `wfteamesc_idwfteamesc`, `tktpub_count`, `orgesc_idorgesc`, `landmark`, `is_validated`, `refnumber_prev`, `wftaskstrac_idwftaskstrac`, `idtktinPK`, `batch_number`, `wftasks_batch_idwftasks_batch`, `voucher_number`, `cms_complaint_no`, `tktcategory_sub_idtktcategory_sub`, `last_updated`, `internal_task`) VALUES
(0, 1, 11, 2, 0, 5, 2, 3, 1, 'chepkurui', '-', '', '254725481036', '4BL1408051', 'High Bill in the month of August', '2014-08-05 14:44:00', '2014-09-04 14:44:00', '2014-08-13 15:32:34', 'kaptembwa', 14046, '', 'chebarkamae bld', '', '', '', 's2njlr5q13rv6h6onm12ucpl11', 777, '2014-08-05 14:44:42', 865, '2014-08-13 15:32:34', 0, 1, 0, '', 1, '', 1, 1, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 5, 1, 'Esther', '-', '', '254722223344', '4BL1408052', 'I consumed 30 units last month. Wrong reading this month', '2014-08-05 14:55:00', '2014-09-04 14:55:00', '0000-00-00 00:00:00', 'al shabaab', 13952, '', '', '', '1234567', '', 'hqdo8g0v8tog114jl662tm3d27', 777, '2014-08-05 14:55:55', 777, '2014-08-05 14:55:55', 0, 1, 0, '', 1, '', 2, 2, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 4, 5, 1, 'Nathan', '-', '', '254711894567', '4BL1408053', 'I have not recieved a bill in 12 months', '2014-08-05 14:57:00', '2014-09-04 14:57:00', '2014-09-12 14:36:22', 'Nakuru', 33, '', '', '', '1111111', '', 'hqdo8g0v8tog114jl662tm3d27', 777, '2014-08-05 14:57:34', 777, '2014-08-05 14:57:34', 0, 1, 0, '', 1, '', 3, 3, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 4, 5, 1, 'Moses', '-', '', '254722337788', '4BL1408054', 'My bill was too high last month. I complained and nothing happened', '2014-08-05 14:59:00', '2014-09-04 14:59:00', '2014-09-12 14:33:49', 'Milimani', 2098, '', '', '', '2233445', '', '4peegiha6egr7d6b83b7dll9p4', 777, '2014-08-05 15:01:01', 771, '2014-09-12 14:33:49', 0, 1, 0, '', 1, '', 4, 4, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 5, 3, 2, 'Daniel', '-', '', '254722342084', '4LE1408061', 'leakage water', '2014-08-06 09:08:00', '2014-08-07 09:08:00', '2014-09-12 14:45:00', 'Nakuru', 33, '', '', '', '', '', '4peegiha6egr7d6b83b7dll9p4', 778, '2014-08-06 09:09:47', 781, '2014-09-12 14:45:00', 0, 1, 0, '', 1, '', 5, 5, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 5, 1, 'Max L H', '-', '', '254729988173', '4BL140806HQ1', 'I have been overbilled', '2014-08-06 09:26:00', '2014-09-05 09:26:00', '2014-08-06 09:26:38', 'Nakuru', 33, '', '', '', '1231231', '', 'v8k2v57o3k3iak22dfoh1v2nb1', 778, '2014-08-06 09:26:38', 791, '2014-08-06 15:09:30', 0, 1, 0, 'Nakuru water', 1, '', 6, 6, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 5, 1, 'J Kimotho', '-', '', '254721713555', '4BL140806HQ2', 'I have an overbill', '2014-08-06 09:29:00', '2014-09-05 09:29:00', '2014-08-06 09:29:23', 'Kilimani', 27, '', '', '', '121121211', '', 'v8k2v57o3k3iak22dfoh1v2nb1', 778, '2014-08-06 09:29:23', 791, '2014-08-06 15:12:51', 0, 1, 0, 'Nakuru area in Kimilamni', 1, '', 7, 7, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 5, 1, 'Dickson M', '-', '', '254725481036', '4BL140806HQ3', 'August and July Bills Hyped', '2014-08-06 09:30:00', '2014-09-05 09:30:00', '2014-08-06 09:31:09', 'Nakuru Town', 13953, '', '', '', '3124123123', '', '3tqe1kvvsunhuf3i1ed0bhqid2', 778, '2014-08-06 09:31:09', 767, '2014-08-06 11:57:51', 0, 1, 0, 'Near Royal Courts', 1, '', 8, 8, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 4, 5, 1, 'Nathan', '-', '', '254721210767', '4BL140806HQ4', 'I have been overcharged Why is my bill high', '2014-08-06 10:42:00', '2014-09-05 10:42:00', '2014-09-12 14:52:03', 'Flamingo Phase 2', 13954, '', 'Phase 2', '10', '43401016', '', '4peegiha6egr7d6b83b7dll9p4', 767, '2014-08-06 10:44:50', 781, '2014-09-12 14:52:03', 0, 1, 0, '', 1, '', 9, 9, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 0, 2, 0, 3, 1, 5, 1, 'Esther', NULL, NULL, '254720315425', '21408061', 'Meter is moving fast', '2014-08-06 10:59:03', '2014-09-05 10:59:03', '0000-00-00 00:00:00', 'Mwariki Flats Apartment 2', 33, NULL, NULL, NULL, '777777', NULL, NULL, 0, '2014-08-06 10:59:03', 0, '0000-00-00 00:00:00', 0, 1, 0, NULL, 1, NULL, NULL, 10, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 5, 2, 5, 1, 'wanjohi', '-', '', '254720853561', '4BL140806HQ5', 'high blling', '2014-08-06 12:19:00', '2014-09-05 12:19:00', '2014-08-06 12:23:02', 'Bondeni office', 13955, '', '', '', '05700753', '', 'bvrsiur6iubm00l2feti6krb31', 777, '2014-08-06 12:23:02', 787, '2014-08-06 15:10:34', 0, 1, 0, '', 1, '', 11, 11, 7, 25, 7, NULL, NULL, '2015-05-29 05:25:45', 0),
(0, 1, 11, 2, 0, 6, 2, 3, 2, 'Mary', '-', 'maryjebichii@yahoo.com', '254722387725', '4LE140806HQ2', 'water leakage in manyani estate.', '2014-08-06 12:26:00', '2014-08-07 12:26:00', '2014-08-06 12:38:36', 'TM''S GROUND', 13957, 'kalewa road', 'manyani estate', '2', '06300600', '', '3kqdrs6j8g66hadsuqphfst963', 780, '2014-08-06 12:38:36', 778, '2014-08-29 11:01:47', 0, 1, 0, 'Next to Nakuru primary school', 1, '', 12, 12, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 5, 2, 5, 1, 'Beatrice', '-', '', '254726596005', '4BL140806HQ6', 'High bill inJuly 2014', '2014-08-06 12:41:00', '2014-09-05 12:41:00', '2014-08-06 12:44:15', 'Rhonda', 13959, '', '', '', '47502000', '', 'u93l28ljh5jcpkh8cjq2fjd4o3', 778, '2014-08-06 12:44:15', 778, '2014-08-06 12:44:15', 0, 1, 0, '', 1, '', 13, 13, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 5, 1, 'TOM', '-', '', '254722687935', '4BL140806HQ7', 'BIL HYPED IN JULY', '2014-08-06 12:40:00', '2014-09-05 12:40:00', '2014-08-06 12:48:19', 'Milimani East', 2099, 'MARAGOLI LANE', 'MILIMANI APARTMENTS', '1', '03501601', '', 'qdkb544ere7t4i6l7b28olm6q2', 791, '2014-08-06 12:48:19', 791, '2014-08-06 12:48:19', 0, 1, 0, 'NEXT TO MILIMANI PRIMARY', 1, '', 14, 14, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 5, 2, 5, 1, 'OKUL', '-', '', '254721753086', '4BL140806HQ8', 'Bill hyped in may', '2014-08-06 12:52:00', '2014-09-05 12:52:00', '2014-08-06 12:59:10', 'CENTRAL ZONE', 13961, 'KANU STREET', 'YORK STAR', '1', '0010060', '', '8k2d7013s60mbglq0lne389p82', 780, '2014-08-06 12:59:10', 784, '2014-08-06 15:12:41', 0, 1, 0, 'NEXT TO LANGA LANGA CLINIC', 1, '', 15, 15, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 5, 2, 3, 2, 'charles mathenge', '-', '', '254727573158', '4LE140806HQ3', 'water leakage', '2014-08-06 12:58:00', '2014-08-07 12:58:00', '2014-08-06 13:06:59', 'makao centre', 13962, '', '', '', '', '', '3kqdrs6j8g66hadsuqphfst963', 767, '2014-08-06 13:06:59', 778, '2014-08-29 11:12:43', 0, 1, 0, '', 1, '', 16, 16, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 5, 1, 'wangare', '-', '', '254722922075', '4BL140806HQ9', 'high bill', '2014-08-06 13:07:00', '2014-09-05 13:07:00', '2014-08-06 13:10:27', 'dog section', 13963, '', '', '', '45900880', '', '355tqoojhr4jkr0s8bfnqu5gu3', 792, '2014-08-06 13:10:27', 784, '2014-08-06 16:45:08', 0, 1, 0, '', 1, '', 17, 17, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 5, 1, 'Zonal Manager - Eastern', '-', '', '254723454772', '4BL140806HQ10', 'Please high bill', '2014-08-06 13:42:00', '2014-09-05 13:42:00', '2014-08-06 13:42:35', 'Milimani', 2098, '', '', '', '23423445454', '', 'bvrsiur6iubm00l2feti6krb31', 778, '2014-08-06 13:42:35', 787, '2014-08-06 14:59:30', 0, 1, 0, '', 1, '', 18, 18, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 5, 1, 'Mathenge', '-', '', '254727573158', '4BL140806HQ11', 'My bill is high', '2014-08-06 14:42:00', '2014-09-05 14:42:00', '2014-08-06 14:42:53', 'Nakuru Town', 13953, '', '', '', '53053040', '', 'v8k2v57o3k3iak22dfoh1v2nb1', 777, '2014-08-06 14:42:53', 791, '2014-08-06 15:17:18', 0, 1, 0, '', 1, '', 19, 19, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 4, 5, 1, 'Esther', '-', '', '254720315425', '4BL140806HQ12', 'My bill is high', '2014-08-06 15:54:00', '2014-09-05 15:54:00', '2014-08-07 13:22:51', 'Majengo', 1835, '', '', '', '1111111', '', 'm36td5d215n8f5ce9gmb45ehr1', 791, '2014-08-06 15:54:41', 797, '2014-08-07 13:22:51', 0, 1, 0, '', 1, '', 20, 20, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 5, 1, 'sungura', '-', '', '254720315425', '4BL140806HQ13', 'meter faulty', '2014-08-06 16:09:00', '2014-09-05 16:09:00', '2014-08-06 16:10:15', 'meter', 4549, 'kenyatta avenue', '', '1', '00100020', '', 'qdkb544ere7t4i6l7b28olm6q2', 791, '2014-08-06 16:10:15', 780, '2014-08-06 16:42:08', 0, 1, 0, '', 1, '', 21, 21, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 5, 1, 'sammy', '-', '', '254722922075', '4BL140806HQ14', 'high bill', '2014-08-06 16:18:00', '2014-09-05 16:18:00', '2014-08-06 16:19:49', 'mama ngina', 6146, '', '', '', '45900880', '', '3tqe1kvvsunhuf3i1ed0bhqid2', 792, '2014-08-06 16:19:49', 767, '2014-08-06 16:21:25', 0, 1, 0, '', 1, '', 22, 22, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 5, 1, 'mathenge', '-', '', '254727573158', '4BL140806HQ15', 'high bill', '2014-08-06 16:22:00', '2014-09-05 16:22:00', '2014-08-06 16:24:19', 'makao centre', 13962, '', '', '', '53053040', '', '355tqoojhr4jkr0s8bfnqu5gu3', 784, '2014-08-06 16:24:19', 784, '2014-08-06 16:24:19', 0, 1, 0, '', 1, '', 23, 23, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 5, 1, 'waithera', '-', '', '254722922075', '4BL140806HQ16', 'weave my sewer', '2014-08-06 16:35:00', '2014-09-05 16:35:00', '2014-08-06 16:35:56', 'manyani', 13966, '', '', '', '06300521', '', '3tqe1kvvsunhuf3i1ed0bhqid2', 792, '2014-08-06 16:35:56', 767, '2014-08-06 16:39:42', 0, 1, 0, '', 1, '', 24, 24, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 5, 1, 'Evans', '-', '', '254721252846', '4BL140807HQ1', 'my bill is high', '2014-08-07 10:34:00', '2014-09-06 10:34:00', '2014-08-07 10:39:04', 'CBD', 3498, 'Government Road', 'Nawassco Plaza', 'HQ', '12345678', '', 'hqdo8g0v8tog114jl662tm3d27', 777, '2014-08-07 10:39:04', 777, '2014-08-07 10:39:04', 0, 1, 0, '', 1, '', 25, 25, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 5, 1, 'caleb muibu', 'M', '', '254710727151', '4BL140807HQ2', 'no water for the last two weeks', '2014-08-07 12:18:00', '2014-08-08 12:18:00', '2014-08-07 12:29:00', 'langa langa', 13958, 'jua lako', 'Ck building', 'Door no3', '07415390', '', 'sgoq7k4hss2ghbfqfi62iqcdf0', 805, '2014-08-07 12:29:00', 798, '2014-08-07 15:24:21', 0, 1, 0, '', 1, '', 26, 26, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 5, 1, 'Jane Namarome', '-', '', '254710727151', '4BL140807HQ3', 'My meter has never been read since i opened my account', '2014-08-07 12:31:00', '2014-08-08 12:31:00', '2014-08-07 12:32:10', 'langalanga', 13956, 'KANU STREET', 'CK', '17', '07415390', '', '7os1pjs4u5820t81oolqt52bh0', 804, '2014-08-07 12:32:10', 805, '2014-08-07 13:04:24', 0, 1, 0, '16314-20100', 1, '', 27, 27, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 3, 2, 'Peter Kimenju mwangi', '-', '', '254721359118', '4LE140807HQ1', 'please repair', '2014-08-07 12:31:00', '2014-08-08 12:31:00', '2014-08-07 12:33:31', 'MOUNTAIN VIEW', 3305, 'murunyu road', 'engashura', '', '', '', '758bm5uqhi3u3e8716a7suhvu5', 799, '2014-08-07 12:33:31', 799, '2014-08-07 12:33:31', 0, 1, 0, 'Opposite White House along Nakuru Solai road', 1, '', 28, 28, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 3, 2, 'xyz', 'M', 'andatiezekiel@gmail.com', '254710920745', '4LE140807HQ2', 'leakage at forest rd', '2014-08-07 12:18:00', '2014-08-08 12:18:00', '2014-08-07 12:33:50', 'forest rd juction', 13967, 'forest rd', 'milimani', 'plot 15', '', '', '3kqdrs6j8g66hadsuqphfst963', 797, '2014-08-07 12:33:50', 778, '2014-08-29 10:43:15', 0, 1, 0, '4046 Nakuru', 1, '', 29, 29, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 3, 2, 'Jane Namarome', '-', '', '254710727151', '4LE140807HQ3', 'There is a burst near stima line.', '2014-08-07 12:38:00', '2014-08-08 12:38:00', '2014-08-07 12:38:09', 'Mwariki', 13968, '', '', '', '', '', '758bm5uqhi3u3e8716a7suhvu5', 804, '2014-08-07 12:38:09', 799, '2014-08-07 12:53:44', 0, 1, 0, '', 1, '', 30, 30, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 5, 5, 5, 1, 'eunice Awuor', '-', '', '254724827364', '4BL140807HQ4', 'high water bill with wrong meter readings', '2014-08-07 12:37:00', '2014-08-08 12:37:00', '2014-09-12 15:12:08', 'ojuka estate', 13969, '', '', '', '03405270', '', '3tqe1kvvsunhuf3i1ed0bhqid2', 777, '2014-08-07 12:41:15', 777, '2014-08-07 12:41:15', 0, 1, 0, '', 1, '', 31, 31, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 4, 5, 1, 'Nathan', '-', '', '254728536865', '4BL140807HQ5', 'High bill', '2014-08-07 12:41:00', '2014-08-08 12:41:00', '2014-08-07 13:30:14', 'Majengo', 1835, '', '', '', '1235556', '', 'mkk022raq46qs5j561s2dobtk4', 778, '2014-08-07 12:41:40', 804, '2014-08-07 13:30:14', 0, 1, 0, '', 1, '', 32, 32, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 5, 3, 2, 'Peter Kimenju', '-', '', '254721359118', '4LE140807HQ4', 'water burst', '2014-08-07 12:43:00', '2014-08-08 12:43:00', '2014-09-12 14:58:11', 'All nation', 13970, 'Engashura road', 'route2', '1', '', '', '7os1pjs4u5820t81oolqt52bh0', 805, '2014-08-07 12:43:56', 805, '2014-08-07 12:43:56', 0, 1, 0, '', 1, '', 33, 33, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 5, 2, 5, 1, 'mark', '-', '', '254710330450', '4BL140807HQ6', 'high bbill', '2014-08-07 12:44:00', '2014-08-08 12:44:00', '2014-08-07 12:44:52', 'Nakuru Town', 13953, '', '', '', '1023556778', '', '3tqe1kvvsunhuf3i1ed0bhqid2', 777, '2014-08-07 12:44:52', 777, '2014-08-07 12:44:52', 0, 1, 0, '', 1, '', 34, 34, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 5, 5, 1, 'Zaituni', 'F', 'zaitunirehema@yahoo.com', '254727432652', '4BL140807HQ7', 'exagerated bill', '2014-08-07 12:36:00', '2014-08-08 12:36:00', '2014-08-07 15:31:21', 'kenlands plot no. 18', 13971, 'kawango close', 'kenlands', '18', '54021130', '', '7os1pjs4u5820t81oolqt52bh0', 799, '2014-08-07 12:46:37', 805, '2014-08-07 15:31:21', 0, 1, 0, 'opposite SDA church', 1, '2', 35, 35, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 5, 1, 'Susan Anguku', 'F', '', '254725403142', '4BL140807HQ8', 'wrong meter reading', '2014-08-07 12:44:00', '2014-08-08 12:44:00', '2014-08-07 12:52:10', 'center', 13973, 'kalewa', 'shauri yako', '119/7', '47401550', '', '7os1pjs4u5820t81oolqt52bh0', 805, '2014-08-07 12:52:10', 805, '2014-08-07 12:52:10', 0, 1, 0, '', 1, '', 36, 36, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 4, 5, 1, 'Dr.Patel', '-', '', '254704261104', '4BL140807HQ9', 'High bill', '2014-08-07 12:49:00', '2014-08-08 12:49:00', '2014-08-07 13:23:47', 'Crater climb', 13972, 'Kawango', 'Milimani', 'plot 1', '40352201', '', '7os1pjs4u5820t81oolqt52bh0', 797, '2014-08-07 12:52:35', 805, '2014-08-07 13:23:47', 0, 1, 0, '16314', 1, '', 37, 37, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 5, 1, 'Daniel', '-', '', '254728536865', '4BL140807HQ10', 'My bill is high', '2014-08-07 12:52:00', '2014-08-08 12:52:00', '2014-08-07 12:52:51', 'Majengo', 1835, '', '', '', '1234567', '', '3tqe1kvvsunhuf3i1ed0bhqid2', 778, '2014-08-07 12:52:51', 767, '2014-08-07 13:04:40', 0, 1, 0, '', 1, '', 38, 38, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 4, 3, 2, 'Mark', '-', '', '254721713555', '4LE140807HQ5', 'huge burst', '2014-08-07 13:04:00', '2014-08-08 13:04:00', '2014-08-07 13:11:34', 'Near Nakumatt Lifestyle', 4065, '', '', '', '', '', '3tqe1kvvsunhuf3i1ed0bhqid2', 778, '2014-08-07 13:04:27', 767, '2014-08-07 13:11:34', 0, 1, 0, '', 1, '', 39, 39, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 4, 5, 1, 'John', '-', '', '254721713555', '4BL140807HQ11', 'Huge sewer bill', '2014-08-07 13:05:00', '2014-08-08 13:05:00', '2014-08-07 15:50:02', 'Nakuru Town', 13953, '', '', '', '12422555', '', '758bm5uqhi3u3e8716a7suhvu5', 778, '2014-08-07 13:06:04', 799, '2014-08-07 15:50:02', 0, 1, 0, '', 1, '', 40, 40, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 5, 1, 'Frank', '-', '', '254728536865', '4BL140807HQ12', 'My bill is high', '2014-08-07 13:20:00', '2014-08-08 13:20:00', '2014-08-07 13:21:07', 'CBD', 3498, '', '', '', '12345678', '', '758bm5uqhi3u3e8716a7suhvu5', 767, '2014-08-07 13:21:07', 807, '2014-08-07 13:30:09', 0, 1, 0, '', 1, '', 41, 41, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 5, 1, 'yyyyyyyyy', '-', '', '254727432652', '4BL140807HQ13', 'billed for sewerage services yet the plot is using on-site disposal system', '2014-08-07 14:17:00', '2014-08-08 14:17:00', '2014-08-07 14:18:09', 'nakuru workers estate', 13975, '', '', '', 'xxxxxxxxxxxx', '', 'r2pu49ajtgtb7li1sptnjulb16', 793, '2014-08-07 14:18:09', 793, '2014-08-07 14:18:09', 0, 1, 0, 'opposite police post', 1, '', 42, 42, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 0, 2, 0, 3, 1, 5, 1, 'Susan', NULL, NULL, '254725403142', '21408071', 'My bill for June was too high', '2014-08-07 14:39:57', '2014-08-08 14:39:57', '0000-00-00 00:00:00', 'Abc flats', 33, NULL, NULL, NULL, '1234567', NULL, NULL, 0, '2014-08-07 14:39:57', 0, '0000-00-00 00:00:00', 0, 1, 0, NULL, 1, NULL, NULL, 43, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 5, 4, 5, 1, 'Jane Namarome', '-', '', '254710727151', '4BL140807HQ14', 'High bill in the month of July.', '2014-08-07 15:03:00', '2014-08-08 15:03:00', '2014-08-07 15:21:43', 'langalanga', 13956, 'Kanu Street', 'CK', '', '07415390', '', '7os1pjs4u5820t81oolqt52bh0', 804, '2014-08-07 15:03:55', 805, '2014-08-07 15:21:43', 0, 1, 0, '16314-20100', 1, '', 44, 44, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 4, 5, 1, 'Susan Anguku', '-', '', '254725403142', '4BL140807HQ15', 'High bill', '2014-08-07 15:06:00', '2014-08-08 15:06:00', '2014-08-07 15:32:55', 'bondeni', 6308, 'Kalewa', 'Ck', '1', '47401550', '', 'sgoq7k4hss2ghbfqfi62iqcdf0', 805, '2014-08-07 15:06:51', 798, '2014-08-07 15:32:55', 0, 1, 0, '', 1, '', 45, 45, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 3, 2, 'John kemoi', '-', '', '254721252846', '4LE140807HQ6', 'leakage at show ground', '2014-08-07 15:11:00', '2014-08-08 15:11:00', '2014-08-07 15:17:09', 'show ground rd', 13976, '', '', '', '', '', 'sgoq7k4hss2ghbfqfi62iqcdf0', 798, '2014-08-07 15:17:09', 798, '2014-08-07 15:17:09', 0, 1, 0, '16314 nakuru', 1, '', 46, 46, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 3, 2, 'Dan kameme', 'M', '', '254722359118', '4LE140807HQ7', 'please attend a leakage outside waterbuck', '2014-08-07 15:10:00', '2014-08-08 15:10:00', '2014-08-07 15:24:47', 'At the entrance', 13978, 'government road', 'kawere', '00145', '015000320', '', 'r2pu49ajtgtb7li1sptnjulb16', 794, '2014-08-07 15:24:47', 794, '2014-08-07 15:24:47', 0, 1, 0, '20-100 12733', 1, '', 47, 47, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 3, 2, 'Zaituni', '-', '', '254727432652', '4LE140807HQ8', 'Please repair the leakage urgently', '2014-08-07 15:28:00', '2014-08-08 15:28:00', '2014-08-07 15:28:37', 'Pivot Hotel', 13977, 'Baringo street', 'Kenlands', '', '', '', '758bm5uqhi3u3e8716a7suhvu5', 799, '2014-08-07 15:28:37', 799, '2014-08-07 15:28:37', 0, 1, 0, 'Opposite manyatta roundabout', 1, '', 48, 48, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 4, 3, 2, 'Dickson', '-', '', '254721713555', '4LE140807HQ9', 'There is a burst pipe around Gate House outside Equity Bank', '2014-08-07 15:31:00', '2014-08-08 15:31:00', '2014-08-07 15:37:58', 'Nakuru', 33, '', '', '', '', '', 'mkk022raq46qs5j561s2dobtk4', 777, '2014-08-07 15:31:11', 804, '2014-08-07 15:37:58', 0, 1, 0, '', 1, '', 49, 49, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 5, 2, 3, 2, 'Beatrice Anguku', 'F', '', '254721766435', '4LE140807HQ10', 'water main burst', '2014-08-07 15:32:00', '2014-08-08 15:32:00', '2014-08-07 15:38:20', 'Krep Nakuru', 13979, 'Mburu Gichua', 'Gituamba', '23', '07605090', '', '758bm5uqhi3u3e8716a7suhvu5', 805, '2014-08-07 15:38:20', 799, '2014-08-07 15:44:59', 0, 1, 0, '', 1, '', 50, 50, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 5, 3, 2, 'Dickson', '-', '', '254725481036', '4LE140807HQ11', 'There is a water leakage outside shade hotel', '2014-08-07 15:39:00', '2014-08-08 15:39:00', '2014-08-07 15:44:54', 'Savannah', 8, '', '', '', '', '', 'sgoq7k4hss2ghbfqfi62iqcdf0', 777, '2014-08-07 15:39:57', 798, '2014-08-07 15:44:54', 0, 1, 0, '', 1, '', 51, 51, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 5, 5, 1, 'Evans', '-', '', '254728536865', '4BL140807HQ16', 'I have not received my bill in 3 months', '2014-08-07 15:47:00', '2014-08-08 15:47:00', '2014-08-07 15:52:34', 'Majengo', 1835, '', '', '', '122334556', '', 'mkk022raq46qs5j561s2dobtk4', 767, '2014-08-07 15:47:35', 804, '2014-08-07 15:52:34', 0, 1, 0, '', 1, '', 52, 52, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 5, 2, 5, 1, 'Lucy Macharia', '-', '', '254711170418', '4BL140808HQ1', 'My bill is high.', '2014-08-08 10:06:00', '2014-08-09 10:06:00', '2014-08-08 10:14:54', 'london', 13981, 'Oginga Odinga Road', 'Moi Flats', '4', '02609720', '', '3tqe1kvvsunhuf3i1ed0bhqid2', 767, '2014-08-08 10:14:54', 767, '2014-08-08 10:14:54', 0, 1, 0, '', 1, '', 53, 53, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 5, 4, 5, 1, 'Mark Too', '-', '', '254721713555', '4BL140808HQ2', 'High bill. Previous was only ksh 300', '2014-08-08 10:48:00', '2014-08-09 10:48:00', '2014-08-08 13:02:03', 'CBD Nakuru Town', 13982, 'Raila Ondinga st', '', '', '23457890', '', 'v9ne3k012poi47bu3n6s986ev0', 806, '2014-08-08 10:48:54', 823, '2014-08-08 13:02:03', 0, 1, 0, '', 1, '', 54, 54, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 4, 3, 2, 'Kamau wa Makara', '-', '', '254721713555', '4LE140808HQ1', 'Huge burst near State house lodge', '2014-08-08 10:50:00', '2014-08-09 10:50:00', '2014-08-08 12:58:21', 'State house', 13983, '', '', '', '', '', '0rlknsg383tfc55ib37bg04tr1', 806, '2014-08-08 10:50:55', 811, '2014-08-08 12:58:21', 0, 1, 0, '', 1, '', 55, 55, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 5, 2, 5, 1, 'Peter', 'M', '', '254720593450', '4BL140808HQ3', 'high bill', '2014-08-08 11:44:00', '2014-08-09 11:44:00', '2014-08-08 11:54:56', 'TOWN CENTRE', 4539, 'George morara rd.', 'Next to Ave. Suites', '', '42427443', '', '1m6vqi47bsc2u9i1h68apv85r2', 814, '2014-08-08 11:54:56', 815, '2014-08-08 12:40:11', 0, 1, 0, 'P.o. Box 16581-20100\r\nNakuru', 1, '', 56, 56, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 5, 2, 5, 1, 'modo', '-', '', '254725728405', '4BL140808HQ4', 'no water available forrntwo days', '2014-08-08 11:44:00', '2014-08-09 11:44:00', '2014-08-08 11:59:02', 'KITI AREA', 13984, 'F.MUHORO RD', 'TEACHERS', '', '390255657', '', 'f6lse9e56j16mmghu8h66gcij5', 809, '2014-08-08 11:59:02', 817, '2014-08-08 15:19:21', 0, 1, 0, '', 1, '', 57, 57, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 5, 2, 5, 1, 'zipporah mwangi', 'F', '', '254721461379', '4BL140808HQ5', 'water leak', '2014-08-08 11:44:00', '2014-08-09 11:44:00', '2014-08-08 11:59:07', 'embu street', 13985, 'kanu street', 'free hold', '', '45102500', '', '1m6vqi47bsc2u9i1h68apv85r2', 815, '2014-08-08 11:59:07', 815, '2014-08-08 11:59:07', 0, 1, 0, '16314', 1, '', 58, 58, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 5, 2, 5, 1, 'andrew kariuki', '-', '', '254710452752', '4BL140808HQ6', 'high bill', '2014-08-08 11:47:00', '2014-08-09 11:47:00', '2014-08-08 12:00:36', 'moi estate', 4304, '', '', '', '07408000', '', 'vg2406u68ii5egrhjuiboecdq3', 817, '2014-08-08 12:00:36', 817, '2014-08-08 12:00:36', 0, 1, 0, '16314', 1, '', 59, 59, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 4, 5, 1, 'elizabeth', '-', '', '254720776859', '4BL140808HQ7', 'my july bill so high', '2014-08-08 11:55:00', '2014-08-09 11:55:00', '2014-08-08 12:59:16', 'Kasarani', 3213, '', '', '', '45104500', '', '1m6vqi47bsc2u9i1h68apv85r2', 818, '2014-08-08 12:01:00', 815, '2014-08-08 12:59:16', 0, 1, 0, '', 1, '', 60, 60, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 5, 1, 'ayub macharia', 'M', '', '254721327091', '4BL140808HQ8', 'my bill is too high', '2014-08-08 11:44:00', '2014-08-09 11:44:00', '2014-08-08 12:03:38', 'pembee', 13986, 'eldoret road', 'nyati house', '', '07401951', '', 'vg2406u68ii5egrhjuiboecdq3', 823, '2014-08-08 12:03:38', 817, '2014-08-08 12:34:35', 0, 1, 0, '', 1, '', 61, 61, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 5, 2, 3, 2, 'Joseph', 'M', '', '254716398253', '4LE140808HQ2', 'Leakage at moi flats', '2014-08-08 12:04:00', '2014-08-09 12:04:00', '2014-08-08 12:09:09', 'Opp. Mt Kenya Hotels', 13987, 'Oginga Odinga rd', 'Moi flats', '', '01500370', '', 'fdbracc4i8cvb0pb4gksg47i87', 814, '2014-08-08 12:09:09', 814, '2014-08-08 12:09:09', 0, 1, 0, '16314-20100 ', 1, '', 62, 62, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 5, 2, 3, 2, 'alshabab', '-', '', '254722369613', '4LE140808HQ3', 'huge burst', '2014-08-08 12:04:00', '2014-08-09 12:04:00', '2014-08-08 12:09:15', 'freehold', 13988, '', '', '', '', '', 'evoh6cjsqdeqs909bg7oup9fc5', 818, '2014-08-08 12:09:15', 818, '2014-08-08 12:09:15', 0, 1, 0, '', 1, '', 63, 63, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 5, 1, 'Evans', '-', '', '254728536865', '4BL140808HQ9', 'I have not received a bill in the last 1 year. WHY', '2014-08-08 12:11:00', '2014-08-09 12:11:00', '2014-08-08 12:11:43', 'CBD', 3498, '', '', '', '1234567', '', 'fdbracc4i8cvb0pb4gksg47i87', 777, '2014-08-08 12:11:43', 814, '2014-08-08 12:47:48', 0, 1, 0, '', 1, '', 64, 64, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 5, 5, 1, 'osodo', '-', '', '254722456011', '4BL140808HQ10', 'water burst inside my plot', '2014-08-08 12:05:00', '2014-08-09 12:05:00', '2014-08-08 15:20:20', 'near nakuru primary school', 13989, 'kalewa rd', 'manyani', '', '3901125', '', 'evoh6cjsqdeqs909bg7oup9fc5', 809, '2014-08-08 12:12:02', 818, '2014-08-08 15:20:20', 0, 1, 0, '', 1, '', 65, 65, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 5, 4, 3, 2, 'christine muthoni', '-', '', '254711170418', '4LE140808HQ4', 'water leak', '2014-08-08 12:12:00', '2014-08-09 12:12:00', '2014-09-09 11:12:42', 'london', 13981, '', '', '', '03003550', '', 'lk6dgp6on26hvggbc46nlek395', 815, '2014-08-08 12:13:02', 778, '2014-09-09 11:12:42', 0, 1, 0, '', 1, '', 66, 66, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 4, 5, 1, 'wyclif imakobi', '-', '', '254707224826', '4BL140808HQ11', 'wrong reading', '2014-08-08 12:13:00', '2014-08-09 12:13:00', '2014-08-08 15:23:56', 'TOWN CENTRE', 4539, '', '', '', '34567890', '', 'f6lse9e56j16mmghu8h66gcij5', 823, '2014-08-08 12:15:46', 817, '2014-08-08 15:23:56', 0, 1, 0, '', 1, '', 67, 67, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 3, 2, 'john', '-', '', '254723248730', '4LE140808HQ5', 'huge burst', '2014-08-08 12:07:00', '2014-08-09 12:07:00', '2014-08-08 12:16:23', 'moi road', 13990, '', '', '', '', '', 'ah0rkhokbi9hm68fsg0ldgv4p0', 817, '2014-08-08 12:16:23', 823, '2014-08-08 15:27:44', 0, 1, 0, '', 1, '', 68, 68, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 4, 3, 2, 'Mukorino', '-', '', '254721713555', '4LE140808HQ6', 'High bill', '2014-08-08 12:32:00', '2014-08-09 12:32:00', '2014-08-08 15:15:32', 'CBD Nairobi', 13991, '', '', '', '', '', 'evoh6cjsqdeqs909bg7oup9fc5', 806, '2014-08-08 12:32:53', 818, '2014-08-08 15:15:32', 0, 1, 0, '', 1, '', 69, 69, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 4, 5, 1, 'Mater', '-', '', '254728536265', '4BL140808HQ12', 'My bill is so high. I only paid 100 shillings last month', '2014-08-08 12:34:00', '2014-08-09 12:34:00', '2014-08-08 12:58:27', 'Majengo', 1835, '', '', '', '1234567', '', 'vg2406u68ii5egrhjuiboecdq3', 767, '2014-08-08 12:34:20', 817, '2014-08-08 12:58:27', 0, 1, 0, '', 1, '', 70, 70, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 4, 3, 2, 'Adrian', '-', '', '254728536865', '4LE140808HQ7', 'Water leak near Nakumatt.', '2014-08-08 12:38:00', '2014-08-09 12:38:00', '2014-08-08 12:56:40', 'CBD', 3498, '', '', '', '', '', '3tqe1kvvsunhuf3i1ed0bhqid2', 777, '2014-08-08 12:38:58', 767, '2014-08-08 12:56:40', 0, 1, 0, '', 1, '', 71, 71, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 4, 5, 1, 'Amarosa', '-', '', '254728536865', '4BL140808HQ13', 'My readings for this month are wrong.', '2014-08-08 12:45:00', '2014-08-09 12:45:00', '2014-08-08 12:57:28', 'Milimani', 2098, '', '', '', '123456789', '', 'evoh6cjsqdeqs909bg7oup9fc5', 767, '2014-08-08 12:46:13', 818, '2014-08-08 12:57:28', 0, 1, 0, '', 1, '', 72, 72, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 5, 1, 'Halima', '-', '', '254728536865', '4BL140808HQ14', 'My bill is high', '2014-08-08 13:08:00', '2014-08-09 13:08:00', '2014-08-08 13:08:30', 'CDB', 13992, '', '', '', '15874676', '', 'evoh6cjsqdeqs909bg7oup9fc5', 767, '2014-08-08 13:08:30', 818, '2014-08-08 15:09:38', 0, 1, 0, '', 1, '', 73, 73, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 5, 2, 5, 1, 'mary njambi', '-', '', '254716398253', '4BL140808HQ15', 'wrong reading in march', '2014-08-08 14:34:00', '2014-08-09 14:34:00', '2014-08-08 14:36:05', 'phase 2', 13994, '', '', '', '03503450', '', '1m6vqi47bsc2u9i1h68apv85r2', 815, '2014-08-08 14:36:05', 815, '2014-08-08 14:36:05', 0, 1, 0, '', 1, '', 74, 74, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 5, 5, 3, 2, 'Hadson', '-', '', '254722959978', '4LE140808HQ8', 'There is sewer leakage', '2014-08-08 14:32:00', '2014-08-09 14:32:00', '2014-08-08 15:23:57', 'cbd kirinyaga rd', 4641, 'Bunyala rd', '', 'shabaab', '', '', 'qq80apl803gllijq5r2cf78nm6', 814, '2014-08-08 14:36:14', 811, '2014-08-08 15:23:57', 0, 1, 0, '13614-20100\r\nnakuru', 1, '', 75, 75, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 3, 2, 'wannyoro', '-', '', '254725728405', '4LE140808HQ9', 'water being stolen through a burst pipe', '2014-08-08 14:39:00', '2014-08-09 14:39:00', '2014-08-08 14:41:52', 'along kabatini road', 13995, '', '', '', '', '', '3kqdrs6j8g66hadsuqphfst963', 811, '2014-08-08 14:41:52', 778, '2014-08-29 11:54:49', 0, 1, 0, '', 1, '', 76, 76, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 5, 2, 3, 2, 'moha', '-', '', '254788622200', '4LE140808HQ10', 'sewer leakage', '2014-08-08 14:39:00', '2014-08-09 14:39:00', '2014-08-08 14:43:20', 'CBD', 3498, '', '', '', '', '', 'ah0rkhokbi9hm68fsg0ldgv4p0', 818, '2014-08-08 14:43:20', 823, '2014-08-08 15:20:06', 0, 1, 0, '', 1, '', 77, 77, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 5, 2, 3, 2, 'john otieno', '-', '', '254723248730', '4LE140808HQ11', 'leakage near the sewer line', '2014-08-08 14:42:00', '2014-08-09 14:42:00', '2014-08-08 14:43:39', 'bondeni', 6308, '', '', '', '', '', '1m6vqi47bsc2u9i1h68apv85r2', 815, '2014-08-08 14:43:39', 815, '2014-08-08 14:43:39', 0, 1, 0, '', 1, '', 78, 78, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 5, 2, 5, 1, 'Joseph', '-', '', '254722348584', '4BL140808HQ16', 'High bill in the month of August', '2014-08-08 14:45:00', '2014-08-09 14:45:00', '2014-08-08 14:45:53', 'CBD', 3498, 'Kalewa rd', 'Okilgei bld', '', '42427440', '', 'revv6146blqadtdu2kqo682a16', 814, '2014-08-08 14:45:53', 814, '2014-08-08 14:45:53', 0, 1, 0, '', 1, '', 79, 79, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 5, 1, 'robert mwangi', '-', '', '254721327091', '4BL140808HQ17', 'not received bill', '2014-08-08 14:48:00', '2014-08-09 14:48:00', '2014-08-08 14:48:54', 'Rhonda', 13959, '', '', '', '07401951', '', 'revv6146blqadtdu2kqo682a16', 823, '2014-08-08 14:48:54', 814, '2014-08-08 15:09:33', 0, 1, 0, '', 1, '', 80, 80, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 3, 2, 'kitur', '-', '', '254725728405', '4LE140808HQ12', 'power failure at mwariki station', '2014-08-08 14:48:00', '2014-08-09 14:48:00', '2014-08-08 14:49:13', 'mwariki estate', 13998, '', '', '', '', '', 'qq80apl803gllijq5r2cf78nm6', 811, '2014-08-08 14:49:13', 811, '2014-08-08 14:49:13', 0, 1, 0, '', 1, '', 81, 81, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 5, 2, 3, 2, 'peter', '-', '', '254722959978', '4LE140808HQ13', 'water leakage', '2014-08-08 15:13:00', '2014-08-09 15:13:00', '2014-08-08 15:14:16', 'CBD dubois rd', 4640, 'Bunyala', '', '', '', '', 'revv6146blqadtdu2kqo682a16', 814, '2014-08-08 15:14:16', 814, '2014-08-08 15:14:16', 0, 1, 0, '', 1, '', 82, 82, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 5, 5, 1, 'Leornard', 'M', '', '254722747473', '4BL140808HQ18', 'High bill', '2014-08-08 15:15:00', '2014-08-09 15:15:00', '2014-08-08 15:27:46', 'Frere Town', 441, '', '', '', '42427441', '', '1m6vqi47bsc2u9i1h68apv85r2', 814, '2014-08-08 15:18:32', 815, '2014-08-08 15:27:46', 0, 1, 0, '', 1, '', 83, 83, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 3, 3, 'tanui', 'M', '', '254720462427', '4LE140808HQ14', 'water leakage from the tap', '2014-08-08 15:25:00', '2014-08-09 15:25:00', '2014-08-08 15:29:54', 'murogi', 13999, '', '', '', '', '', 'qq80apl803gllijq5r2cf78nm6', 809, '2014-08-08 15:29:54', 809, '2014-08-08 15:29:54', 0, 1, 0, '', 1, '', 84, 84, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 0, 2, 0, 3, 1, 5, 1, 'Teresa', NULL, NULL, '254728536865', '21408081', 'my bill is high', '2014-08-08 15:39:34', '2014-08-09 15:39:34', '0000-00-00 00:00:00', 'abc flats', 33, NULL, NULL, NULL, '12345689', NULL, NULL, 0, '2014-08-08 15:39:34', 0, '0000-00-00 00:00:00', 0, 1, 0, NULL, 1, NULL, NULL, 85, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 5, 5, 5, 1, 'James Njihia', '-', '', '254703289327', '4BL140811HQ1', 'Wrong meter reading.', '2014-08-11 11:08:00', '2014-08-12 11:08:00', '2014-08-11 15:40:01', 'CBD', 3498, 'Kenyatta Avenue', 'Sokoni Plaza', '10', '00100240', '', 'v5vm8jjrkpdtcblg6gaf33t4p6', 767, '2014-08-11 11:17:30', 826, '2014-08-11 15:40:01', 0, 1, 0, '', 1, '', 86, 86, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 5, 5, 5, 1, 'Mark', '-', '', '254721713555', '4BL140811HQ2', 'My readings are not correct', '2014-08-11 11:34:00', '2014-08-12 11:34:00', '2014-08-11 15:42:37', 'city centre nakumatt life style', 7388, '', '', '', '890890890', '', '1kpsnmb22d8k693e2u212rndk7', 806, '2014-08-11 11:34:16', 790, '2014-08-11 15:42:37', 0, 1, 0, '', 1, '', 87, 87, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 5, 2, 3, 2, 'Kamau wa Ngugi', '-', '', '254721713555', '4LE140811HQ1', 'Huge burst at the bus stop', '2014-08-11 11:35:00', '2014-08-12 11:35:00', '2014-08-11 11:36:08', 'Kenyatta Avenue', 6307, '', '', '', '', '', '3tqe1kvvsunhuf3i1ed0bhqid2', 806, '2014-08-11 11:36:08', 767, '2014-08-11 12:08:45', 0, 1, 0, '', 1, '', 88, 88, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 5, 1, 'kimani mwangi2', '-', '', '254720750397', '4BL140811HQ3', 'wrong meter reading', '2014-08-11 12:41:00', '2014-08-12 12:41:00', '2014-08-11 12:49:22', 'CBD', 3498, '', '', '', '03601950', '', '1kpsnmb22d8k693e2u212rndk7', 825, '2014-08-11 12:49:22', 790, '2014-08-11 15:27:49', 0, 1, 0, '', 1, '', 89, 89, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 4, 3, 2, 'Esther', '-', '', '254728536865', '4LE140811HQ2', 'Major burst by government road', '2014-08-11 12:52:00', '2014-08-12 12:52:00', '2014-08-11 15:01:14', 'Nakuru', 33, 'government road', '', '', '', '', 'c1ejceteq3gad5f1u77i3vdou5', 767, '2014-08-11 12:53:10', 835, '2014-08-11 15:01:14', 0, 1, 0, 'next to nawasco offices', 1, '', 90, 90, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 5, 1, 'ben ngotho', 'M', '', '254726132984', '4BL140811HQ4', 'burst pipe along kenyatta avenue', '2014-08-11 12:40:00', '2014-08-12 12:40:00', '2014-08-11 12:53:59', 'CBD', 3498, '', 'afc  building', '123', '03542280', '', 'v5vm8jjrkpdtcblg6gaf33t4p6', 827, '2014-08-11 12:53:59', 826, '2014-08-11 15:29:45', 0, 1, 0, '12354', 1, '', 91, 91, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 5, 1, 'John Kamau', '-', '', '254703289327', '4BL140811HQ5', 'My bill is high', '2014-08-11 12:54:00', '2014-08-12 12:54:00', '2014-08-11 12:54:26', 'Central Business District', 14000, 'Goverment road', 'Mssters plaza', '213', '42600625', '', 'b9kqbb8calicir1b0254lm6vh3', 835, '2014-08-11 12:54:26', 790, '2014-08-11 13:20:48', 0, 1, 0, '16523-20100', 1, '', 92, 92, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 5, 2, 5, 1, 'jane ochieng', '-', '', '254727883851', '4BL140811HQ6', 'high bill', '2014-08-11 12:52:00', '2014-08-12 12:52:00', '2014-08-11 12:56:12', 'cbd', 3498, 'gusii road', '', '', '03411680', '', 'b9kqbb8calicir1b0254lm6vh3', 790, '2014-08-11 12:56:12', 790, '2014-08-11 12:56:12', 0, 1, 0, '', 1, '', 93, 93, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 5, 5, 3, 2, 'JOSKA', '-', '', '254723646477', '4LE140811HQ3', 'burst at kePnyatta avenue', '2014-08-11 12:40:00', '2014-08-12 12:40:00', '2014-08-11 15:42:14', 'CBD', 3498, 'KIJABE ROAD', 'CBD', '', '00100061', '', '25d38pbub5gpp5jdf4nbk04m13', 826, '2014-08-11 12:56:22', 827, '2014-08-11 15:42:14', 0, 1, 0, '', 1, '', 94, 94, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 3, 2, 'john', '-', '', '254728289108', '4LE140811HQ4', 'huge burst', '2014-08-11 12:58:00', '2014-08-12 12:58:00', '2014-08-11 13:00:58', 'CBD', 3498, '', '', '', '', '', 'b7o1e9ks2c2m42evk5m94ul0l5', 790, '2014-08-11 13:00:58', 825, '2014-08-11 13:52:23', 0, 1, 0, '', 1, '', 95, 95, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 5, 4, 3, 2, 'loise moraa', '-', '', '254723646477', '4LE140811HQ5', 'water leak', '2014-08-11 13:00:00', '2014-08-12 13:00:00', '2014-08-11 15:33:37', 'water leakage', 4401, '', '', '', '', '', 'v5vm8jjrkpdtcblg6gaf33t4p6', 825, '2014-08-11 13:01:55', 826, '2014-08-11 15:33:37', 0, 1, 0, '', 1, '', 96, 96, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 4, 3, 2, 'Evans', '-', '', '254728536865', '4LE140811HQ6', 'Burst outside government road', '2014-08-11 13:12:00', '2014-08-12 13:12:00', '2014-08-11 15:33:44', 'CBD', 3498, '', '', '', '', '', '25d38pbub5gpp5jdf4nbk04m13', 767, '2014-08-11 13:12:18', 827, '2014-08-11 15:33:44', 0, 1, 0, 'Next to nawasco offices', 1, '', 97, 97, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 5, 2, 5, 1, 'omweno james', 'M', '', '254723888800', '4BL140811HQ7', 'high bill', '2014-08-11 13:38:00', '2014-08-12 13:38:00', '2014-08-11 13:48:08', 'ngala', 14001, 'oginga', 'ngala', '', '06503111', '', '25d38pbub5gpp5jdf4nbk04m13', 790, '2014-08-11 13:48:08', 827, '2014-08-11 15:27:16', 0, 1, 0, '', 1, '', 98, 98, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 5, 1, 'james', '-', '', '254703289327', '4BL140811HQ8', 'There is a huge burst along Nakuru-Eldoret road', '2014-08-11 14:44:00', '2014-08-12 14:44:00', '2014-08-11 14:45:08', 'Nakuru', 33, 'kibande', 'Soilo Bld', '211', '12345678', '', 'c1ejceteq3gad5f1u77i3vdou5', 835, '2014-08-11 14:45:08', 835, '2014-08-11 14:45:08', 0, 1, 0, '45668-20100', 1, '', 99, 99, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 5, 5, 3, 2, 'jane', '-', '', '254703289327', '4LE140811HQ7', 'There is a burst at Kaptembwa trading centre', '2014-08-11 14:47:00', '2014-08-12 14:47:00', '2014-08-11 15:36:47', 'Nakuru', 33, 'Moi avenue', 'Sokoni plaza', '4587', '45678965', '', 'k09slounutkoq1l1tr60886qp2', 835, '2014-08-11 14:47:44', 825, '2014-08-11 15:36:47', 0, 1, 0, '7894', 1, '', 100, 100, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 5, 2, 5, 1, 'joseph odhiambo', '-', '', '254723646477', '4BL140811HQ9', 'my meter is never read', '2014-08-11 14:50:00', '2014-08-12 14:50:00', '2014-08-11 14:51:36', 'sec58', 14002, '', '', '', '45800685', '', '1kpsnmb22d8k693e2u212rndk7', 790, '2014-08-11 14:51:36', 790, '2014-08-11 14:51:36', 0, 1, 0, 'dog section', 1, '', 101, 101, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 4, 5, 1, 'daniel otiende', '-', '', '254720750397', '4BL140811HQ10', 'high bill', '2014-08-11 14:51:00', '2014-08-12 14:51:00', '2014-08-11 15:33:55', 'High bill', 13551, '', '', '', '06302300', '', '1kpsnmb22d8k693e2u212rndk7', 825, '2014-08-11 14:52:30', 790, '2014-08-11 15:33:55', 0, 1, 0, '', 1, '', 102, 102, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 3, 2, 'nyanaro', 'M', '', '254721855110', '4LE140811HQ8', 'burst taking long', '2014-08-11 14:41:00', '2014-08-12 14:41:00', '2014-08-11 14:57:52', 'wareng road', 14003, 'mlimani', 'blue bay', '2', '03548702', '', 'iu75lhiu0bmf76ibkmq1v1kr42', 827, '2014-08-11 14:57:52', 827, '2014-08-11 14:57:52', 0, 1, 0, '', 1, '', 103, 103, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 5, 2, 3, 2, 'Daniel Otiende', '-', '', '254720750397', '4LE140811HQ9', 'water burst', '2014-08-11 15:01:00', '2014-08-12 15:01:00', '2014-08-11 15:01:48', 'section 58', 14004, '', '', '', '', '', 'v5vm8jjrkpdtcblg6gaf33t4p6', 825, '2014-08-11 15:01:48', 826, '2014-08-11 15:19:51', 0, 1, 0, '', 1, '', 104, 104, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 5, 4, 5, 1, 'JOSEPH MILGO', '-', '', '254723646477', '4BL140811HQ11', 'overcharge in July bill', '2014-08-11 15:01:00', '2014-08-12 15:01:00', '2014-08-11 15:32:50', 'Railways', 7977, 'HOSPITAL ROAD', 'Railways', '10', '02700350', '', 'c1ejceteq3gad5f1u77i3vdou5', 826, '2014-08-11 15:02:15', 835, '2014-08-11 15:32:50', 0, 1, 0, '', 1, '', 105, 105, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 5, 5, 1, 'Kipchumba', '-', '', '254728536865', '4BL140811HQ12', 'I have not received a bill for 2 months', '2014-08-11 15:02:00', '2014-08-12 15:02:00', '2014-08-11 15:42:06', 'Majengo', 1835, '', '', '', '5487369', '', 'c1ejceteq3gad5f1u77i3vdou5', 767, '2014-08-11 15:02:37', 835, '2014-08-11 15:42:06', 0, 1, 0, '', 1, '', 106, 106, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 4, 5, 1, 'ahmed  hussein', '-', '', '254723390134', '4BL140811HQ13', 'billed on estimates', '2014-08-11 15:05:00', '2014-08-12 15:05:00', '2014-08-11 15:44:15', 'ngala', 14001, '', '', '', '06503111', '', 'k09slounutkoq1l1tr60886qp2', 790, '2014-08-11 15:06:20', 825, '2014-08-11 15:44:15', 0, 1, 0, '', 1, '', 107, 107, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 3, 2, 'Gerald Masabi', '-', '', '254728536865', '4LE140811HQ10', 'Major bust by nakumatt.', '2014-08-11 15:06:00', '2014-08-12 15:06:00', '2014-08-11 15:06:24', 'Nakumatt', 14005, '', '', '', '', '', '16ulfl30lsmqhiha6hok9plgc4', 767, '2014-08-11 15:06:24', 844, '2014-08-26 19:46:58', 0, 1, 0, '', 1, '', 108, 108, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 5, 1, 'Grace', '-', '', '254703289327', '4BL140811HQ14', 'high bill', '2014-08-11 15:08:00', '2014-08-12 15:08:00', '2014-08-11 15:08:43', 'Nakuru Town', 13953, 'Town center', 'Nakuru', '', '45698745', '', '1kpsnmb22d8k693e2u212rndk7', 835, '2014-08-11 15:08:43', 790, '2014-08-11 15:22:00', 0, 1, 0, '123654', 1, '', 109, 109, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 5, 1, 'james kimotho', '-', '', '254720135425', '4BL140811HQ15', 'high bill', '2014-08-11 15:05:00', '2014-08-12 15:05:00', '2014-08-11 15:11:39', 'CBD', 3498, '', '', '', '049000460', '', 'c1ejceteq3gad5f1u77i3vdou5', 825, '2014-08-11 15:11:39', 835, '2014-08-11 15:26:40', 0, 1, 0, '', 1, '', 110, 110, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 4, 5, 1, '0guk', '-', '', '254720750397', '4BL140811HQ16', 'wrong readings', '2014-08-11 15:10:00', '2014-08-12 15:10:00', '2014-08-11 15:33:23', 'wrong readingwrong', 14006, 'ravine road', 'unga estate', '3', '33456895', '', 'k09slounutkoq1l1tr60886qp2', 827, '2014-08-11 15:11:54', 825, '2014-08-11 15:33:23', 0, 1, 0, '', 1, '', 111, 111, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 3, 2, 'DANIEL OTIENO', '-', '', '254710726303', '4LE140811HQ11', 'there is a sewer blockage', '2014-08-11 15:02:00', '2014-08-12 15:02:00', '2014-08-11 15:12:20', 'INDUSTRIAL AREA', 3543, 'BIASHARA STREET', 'Next to ndumu house', 'shabab', '', '', 'k09slounutkoq1l1tr60886qp2', 826, '2014-08-11 15:12:20', 825, '2014-08-11 15:26:41', 0, 1, 0, '', 1, '', 112, 112, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 5, 2, 5, 1, 'patel norru', '-', '', '254703289327', '4BL140811HQ17', 'bill ever estimated', '2014-08-11 15:12:00', '2014-08-12 15:12:00', '2014-08-11 15:12:41', 'sec 58', 14007, '', '', '', '05803400', '', 'c1ejceteq3gad5f1u77i3vdou5', 790, '2014-08-11 15:12:41', 835, '2014-08-11 15:20:07', 0, 1, 0, '', 1, '', 113, 113, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 0, 2, 0, 3, 1, 5, 1, 'Nathan', NULL, NULL, '254723646477', '41408111', 'I have not received a bill in 4 months', '2014-08-11 15:53:11', '2014-08-12 15:53:11', '0000-00-00 00:00:00', 'abc flats 123', 33, NULL, NULL, NULL, '123456788', NULL, NULL, 0, '2014-08-11 15:53:11', 0, '0000-00-00 00:00:00', 0, 1, 0, NULL, 1, NULL, NULL, 114, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 5, 1, 'Ramesh', '-', '', '254711124578', '4BL140812HQ1', 'HIGH CHARGES', '2014-08-12 10:40:00', '2014-08-13 10:40:00', '2014-08-12 10:48:43', 'Blanket', 14008, '', 'Nagaria Estate', '2', '48000002', '', '2vm65u92mo30bus8g1fn81t2i2', 767, '2014-08-12 10:48:43', 833, '2014-08-12 13:12:31', 0, 1, 0, '', 1, '', 115, 115, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 5, 5, 1, 'Mike', '-', '', '254721713555', '4BL140812HQ2', 'My bill is high', '2014-08-12 11:19:00', '2014-08-13 11:19:00', '2014-08-12 15:30:39', 'CBD', 3498, '', '', '', '2144552626', '', 'f77h7fer2dpltvm8vgvu6inhr5', 806, '2014-08-12 11:19:55', 847, '2014-08-12 15:30:39', 0, 1, 0, '', 1, '', 116, 116, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 5, 2, 3, 2, 'Johana', '-', '', '254721713555', '4LE140812HQ1', 'Burst pipe near the west side mall', '2014-08-12 11:21:00', '2014-08-13 11:21:00', '2014-08-12 11:21:27', 'West side Mall', 14009, '', '', '', '', '', '3tqe1kvvsunhuf3i1ed0bhqid2', 806, '2014-08-12 11:21:27', 767, '2014-08-12 11:47:34', 0, 1, 0, '', 1, '', 117, 117, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 5, 2, 3, 2, 'Jeremiah Chege', 'M', '', '254722222562', '4LE140812HQ2', 'Theres a pipe leakage in Rhonda near the Restoration church', '2014-08-12 12:15:00', '2014-08-13 12:15:00', '2014-08-12 12:24:59', 'Rhonda', 13959, 'Rhonda', 'Rhonda Estate', '', '', '', 'dh074n7hncs43o1h9ispp8a3t7', 844, '2014-08-12 12:24:59', 844, '2014-08-12 12:24:59', 0, 1, 0, 'Near Church of Restoration', 1, '', 118, 118, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 3, 2, 'Paul', '-', '', '254728536865', '4LE140812HQ3', 'Spotted a water leak on kenyatta avenue.', '2014-08-12 12:26:00', '2014-08-13 12:26:00', '2014-08-12 12:27:58', 'CBD', 3498, 'Kenyattaa', '', '', '', '', '3tqe1kvvsunhuf3i1ed0bhqid2', 767, '2014-08-12 12:27:58', 767, '2014-08-12 12:27:58', 0, 1, 0, 'Next to Equity Bank', 1, '', 119, 119, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 3, 2, 'ben cheruiyot', 'M', '', '254721991072', '4LE140812HQ4', 'water leakage at barut', '2014-08-12 12:15:00', '2014-08-13 12:15:00', '2014-08-12 12:29:03', 'barutp', 14011, 'moiroad', 'mwariki', '3', '', '', 'lhtafac8kakj6vi7othrhvsv53', 838, '2014-08-12 12:29:03', 834, '2014-08-12 13:24:21', 0, 1, 0, '', 1, '', 120, 120, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 3, 2, 'john were', '-', '', '254721855543', '4LE140812HQ5', 'leakage at bondeni main pipe', '2014-08-12 12:15:00', '2014-08-13 12:15:00', '2014-08-12 12:29:17', 'langa', 14012, 'kanu', 'langalanga', '02', '', '', '2vm65u92mo30bus8g1fn81t2i2', 833, '2014-08-12 12:29:17', 833, '2014-08-12 12:29:17', 0, 1, 0, '', 1, '', 121, 121, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 3, 2, 'MUTHEE', '-', '', '254725491862', '4LE140812HQ6', 'Burst main pipe near KUNSTE HOTEL', '2014-08-12 12:28:00', '2014-08-13 12:28:00', '2014-08-12 12:29:30', 'NAIROBI- NAKURU HIGHWAY', 14010, '', '', '', '', '', 'f77h7fer2dpltvm8vgvu6inhr5', 847, '2014-08-12 12:29:30', 847, '2014-08-12 15:13:53', 0, 1, 0, 'SHELL PETROL STN', 1, '', 122, 122, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 3, 2, 'sylvia', 'F', '', '254721523436', '4LE140812HQ7', 'leakage along moi avenue', '2014-08-12 12:17:00', '2014-08-13 12:17:00', '2014-08-12 12:30:57', 'goverment road', 14013, 'govt road', 'bontana hotel', '1', '', '', '0bjhi31grdsg98jg12kl7pdmb0', 846, '2014-08-12 12:30:57', 846, '2014-08-12 12:30:57', 0, 1, 0, 'bontana', 1, '', 123, 123, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0);
INSERT INTO `tktin` (`idtktin`, `tktlang_idtktlang`, `usrteamzone_idusrteamzone`, `usrteam_idusrteam`, `tktgroup_idtktgroup`, `tktchannel_idtktchannel`, `tktstatus_idtktstatus`, `tktcategory_idtktcategory`, `tkttype_idtkttype`, `sendername`, `sendergender`, `senderemail`, `senderphone`, `refnumber`, `tktdesc`, `timereported`, `timedeadline`, `timeclosed`, `city_town`, `loctowns_idloctowns`, `road_street`, `building_estate`, `unitno`, `waterac`, `kioskno`, `usrsession`, `createdby`, `createdon`, `modifiedby`, `modifiedon`, `wfteamesc_idwfteamesc`, `tktpub_count`, `orgesc_idorgesc`, `landmark`, `is_validated`, `refnumber_prev`, `wftaskstrac_idwftaskstrac`, `idtktinPK`, `batch_number`, `wftasks_batch_idwftasks_batch`, `voucher_number`, `cms_complaint_no`, `tktcategory_sub_idtktcategory_sub`, `last_updated`, `internal_task`) VALUES
(0, 1, 11, 2, 0, 6, 2, 5, 1, 'esther wangari', '-', '', '254722415254', '4BL140812HQ3', 'high bill', '2014-08-12 12:35:00', '2014-08-13 12:35:00', '2014-08-12 12:35:49', 'lanet hill', 14014, '', 'freearea', '', '47802440', '', 'lhtafac8kakj6vi7othrhvsv53', 843, '2014-08-12 12:35:49', 834, '2014-08-12 13:22:17', 0, 1, 0, '16314', 1, '', 124, 124, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 3, 2, 'mungara', '-', '', '254720244134', '4LE140812HQ8', 'burst at prison line', '2014-08-12 12:15:00', '2014-08-13 12:15:00', '2014-08-12 12:36:47', 'milimani primary school', 14015, 'prison road', 'london', '5', '', '', 'lhtafac8kakj6vi7othrhvsv53', 837, '2014-08-12 12:36:47', 834, '2014-08-12 13:27:53', 0, 1, 0, 'sk estate', 1, '', 125, 125, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 5, 2, 3, 2, 'john', '-', '', '254725373432', '4LE140812HQ9', 'leakage at london prison road', '2014-08-12 12:28:00', '2014-08-13 12:28:00', '2014-08-12 12:38:05', 'london prison road', 14016, '', '', '', '', '', 'p6esor4gi69ab0tivmjp0t7hm2', 839, '2014-08-12 12:38:05', 837, '2014-08-12 15:14:58', 0, 1, 0, '', 1, '', 126, 126, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 3, 2, 'kezia ongondi', 'F', '', '254724983054', '4LE140812HQ10', 'burst', '2014-08-12 12:38:00', '2014-08-13 12:38:00', '2014-08-12 12:42:22', 'blankets', 14018, 'blanket', 'nagaria', '', '', '', 'lhtafac8kakj6vi7othrhvsv53', 843, '2014-08-12 12:42:22', 834, '2014-08-12 13:05:52', 0, 1, 0, '16314', 1, '', 127, 127, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 5, 1, 'DANCAN', 'M', '', '254721408217', '4BL140812HQ4', 'HIGH BILL', '2014-08-12 12:41:00', '2014-08-13 12:41:00', '2014-08-12 12:47:07', 'ENGACHURA', 14019, '', '', '', '53000180', '', 'qn2vu8d6ig5eqgmtim481vu2c2', 847, '2014-08-12 12:47:07', 843, '2014-08-12 13:14:49', 0, 1, 0, 'NDEFFO PRY SCH', 1, '', 128, 128, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 3, 2, 'mwangi', '-', '', '254720538858', '4LE140812HQ11', 'leakage in shabab near mosque', '2014-08-12 12:42:00', '2014-08-13 12:42:00', '2014-08-12 12:47:49', 'shabab near mosque', 14021, '', '', '', '', '', 'rle4tsqtrchcv9u518r20i8kl6', 839, '2014-08-12 12:47:49', 839, '2014-08-12 12:47:49', 0, 1, 0, '', 1, '', 129, 129, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 5, 2, 5, 1, 'samuel bett', '-', '', '254721855543', '4BL140812HQ5', 'high bill', '2014-08-12 12:48:00', '2014-08-13 12:48:00', '2014-08-12 12:49:46', 'barut', 14020, 'barut', 'barut', '2693', '47535700', '', '0bjhi31grdsg98jg12kl7pdmb0', 833, '2014-08-12 12:49:46', 846, '2014-08-12 13:14:54', 0, 1, 0, '', 1, '', 130, 130, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 3, 2, 'susan', 'F', '', '254721523434', '4LE140812HQ12', 'leakage at menengai high sch', '2014-08-12 12:45:00', '2014-08-13 12:45:00', '2014-08-12 12:50:55', 'Menengai', 2065, 'oginga odinga', '', '2', '', '', 'a0t73f801l5h97asidkdd6cak2', 846, '2014-08-12 12:50:55', 838, '2014-08-12 13:12:41', 0, 1, 0, '', 1, '', 131, 131, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 5, 1, 'maggy', 'F', '', '254724755073', '4BL140812HQ6', 'high bill', '2014-08-12 12:49:00', '2014-08-13 12:49:00', '2014-08-12 12:51:46', 'freearea', 14017, 'praire', 'praire', '', '47811570', '', 'f77h7fer2dpltvm8vgvu6inhr5', 843, '2014-08-12 12:51:46', 847, '2014-08-12 13:13:34', 0, 1, 0, '16314', 1, '', 132, 132, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 5, 1, 'john', 'M', '', '254722599647', '4BL140812HQ7', 'fault meter', '2014-08-12 12:41:00', '2014-08-13 12:41:00', '2014-08-12 12:53:48', 'index invisible', 14023, 'kenyatta avenue', 'pinkam house', '1', '05600240', '', 'lhtafac8kakj6vi7othrhvsv53', 837, '2014-08-12 12:53:48', 837, '2014-08-12 12:53:48', 0, 1, 0, 'roundabout', 1, '', 133, 133, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 3, 2, 'Mimi Rashid', '-', '', '254721991072', '4LE140812HQ13', 'Leak on Nairobi Road', '2014-08-12 12:54:00', '2014-08-13 12:54:00', '2014-08-12 12:55:27', 'Nairobi Road', 14024, '', '', '', '', '', 'dh074n7hncs43o1h9ispp8a3t7', 844, '2014-08-12 12:55:27', 844, '2014-08-12 13:03:49', 0, 1, 0, 'Near State House', 1, '', 134, 134, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 7, 2, 3, 2, 'MAKORI', 'M', '', '254734618772', '4LE140812HQ14', 'KIONDO BURST', '2014-08-12 12:49:00', '2014-08-13 12:49:00', '2014-08-12 12:55:45', 'NEAR STEM', 14025, '', '', '', '', '', 'f77h7fer2dpltvm8vgvu6inhr5', 847, '2014-08-12 12:55:45', 847, '2014-08-12 12:55:45', 0, 1, 0, '', 1, '', 135, 135, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 3, 2, 'joseph', '-', '', '254721350285', '4LE140812HQ15', 'leakage', '2014-08-12 12:53:00', '2014-08-13 12:53:00', '2014-08-12 12:56:02', 'shabab near mosque', 14021, '', '', '', '', '', 'rle4tsqtrchcv9u518r20i8kl6', 839, '2014-08-12 12:56:02', 839, '2014-08-12 12:56:02', 0, 1, 0, '', 1, '', 136, 136, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 5, 1, 'Ken', '-', '', '254721855543', '4BL140812HQ8', 'high bill', '2014-08-12 13:02:00', '2014-08-13 13:02:00', '2014-08-12 13:02:57', 'shabab', 14026, '', '', '', '6778899908', '', 'rle4tsqtrchcv9u518r20i8kl6', 833, '2014-08-12 13:02:57', 839, '2014-08-12 13:12:45', 0, 1, 0, '', 1, '', 137, 137, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 5, 5, 1, 'Mary', '-', '', '254721713555', '4BL140812HQ9', 'high bill', '2014-08-12 13:06:00', '2014-08-13 13:06:00', '2014-08-12 15:24:49', 'CBD', 3498, '', '', '', '23445566', '', 'f8nnhb43de0hjib607bm4k0pk6', 806, '2014-08-12 13:06:51', 843, '2014-08-12 15:24:49', 0, 1, 0, '', 1, '', 138, 138, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 4, 3, 2, 'Evans', '-', '', '254728536865', '4LE140812HQ16', 'Major burst by government road', '2014-08-12 13:14:00', '2014-08-13 13:14:00', '2014-08-28 12:42:53', 'CBD', 3498, 'Government Road', '', '', '123545668', '', 'k8makqii2uac5avb86pb4rdc47', 767, '2014-08-12 13:14:14', 778, '2014-08-28 12:42:53', 0, 1, 0, '', 1, '', 139, 139, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 3, 2, 'jane g', '-', '', '254722222562', '4LE140812HQ17', 'leakage at Barut', '2014-08-12 13:15:00', '2014-08-13 13:15:00', '2014-08-12 13:15:50', 'barut', 14020, '', '', '', '', '', 'quadrphobvdb7vg28d653i18d2', 844, '2014-08-12 13:15:50', 833, '2014-08-12 15:28:56', 0, 1, 0, 'barut near the chief\\''s office', 1, '', 140, 140, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 3, 2, 'MAKORI', '-', '', '254721694658', '4LE140812HQ18', 'WATER LEAKAGE', '2014-08-12 13:29:00', '2014-08-13 13:29:00', '2014-08-12 13:33:03', 'kawangware near heshima supermarket', 5505, '', '', '', '', '', 'ijdssroadcpgsifbp8a22lakf1', 847, '2014-08-12 13:33:03', 839, '2014-08-12 16:09:52', 0, 1, 0, 'HESHIMAT', 1, '', 141, 141, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 3, 2, 'MIAKOR', 'M', '', '254735104751', '4LE140812HQ19', 'NQ WATER', '2014-08-12 14:08:00', '2014-08-13 14:08:00', '2014-08-12 14:10:50', 'Nakuru', 33, '', '', '', '', '', 'ijdssroadcpgsifbp8a22lakf1', 847, '2014-08-12 14:10:50', 839, '2014-08-12 15:32:46', 0, 1, 0, 'TOWN', 1, '', 142, 142, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 4, 5, 1, 'OTIENDE', '-', '', '254721523434', '4BL140812HQ10', 'HIGH BILL AND NO WATER', '2014-08-12 14:31:00', '2014-08-13 14:31:00', '2014-08-12 15:47:31', 'kenlands plot no. 18', 13971, '', '', '', '06704117', '', '0bjhi31grdsg98jg12kl7pdmb0', 847, '2014-08-12 14:31:50', 846, '2014-08-12 15:47:31', 0, 1, 0, '', 1, '', 143, 143, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 5, 1, 'wahu jecinta', 'F', '', '254722937454', '4BL140812HQ11', 'wrong reading', '2014-08-12 14:30:00', '2014-08-13 14:30:00', '2014-08-12 14:36:19', 'freearea', 14017, 'lanet centre', 'freearea', '', '47700131', '', 'f8nnhb43de0hjib607bm4k0pk6', 842, '2014-08-12 14:36:19', 843, '2014-08-12 15:21:35', 0, 1, 0, '16314', 1, '', 144, 144, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 5, 1, 'patrick muiruri', '-', '', '254721991072', '4BL140812HQ12', 'wrong readings', '2014-08-12 14:36:00', '2014-08-13 14:36:00', '2014-08-12 14:37:52', 'lake view', 7254, 'eldoret rd', 'lake view', '12', '07301750', '', 'f8nnhb43de0hjib607bm4k0pk6', 843, '2014-08-12 14:37:52', 843, '2014-08-12 14:37:52', 0, 1, 0, 'game park', 1, '', 145, 145, 0, 0, 0, NULL, NULL, '2015-05-29 05:23:26', 0),
(0, 1, 11, 2, 0, 6, 2, 3, 2, 'KHAGRAM', '-', '', '254734618772', '4LE140812HQ20', 'SEWER BURST', '2014-08-12 14:38:00', '2014-08-13 14:38:00', '2014-08-12 14:39:31', 'shabab', 14026, '', '', '', '', '', 'f77h7fer2dpltvm8vgvu6inhr5', 847, '2014-08-12 14:39:31', 847, '2014-08-12 14:39:31', 0, 1, 0, 'NEAR TANNERS', 1, '', 146, 146, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 3, 2, 'grace', '-', '', '254723808539', '4LE140812HQ21', 'burst at githima', '2014-08-12 14:31:00', '2014-08-13 14:31:00', '2014-08-12 14:40:17', 'Githima', 659, 'eveready', 'githima', '', '', '', '3kqdrs6j8g66hadsuqphfst963', 833, '2014-08-12 14:40:17', 778, '2014-08-29 10:28:55', 0, 1, 0, '', 1, '', 147, 147, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 3, 2, 'solomon', '-', '', '254722599647', '4LE140812HQ22', 'illegal connection', '2014-08-12 14:41:00', '2014-08-13 14:41:00', '2014-08-12 14:43:01', 'ngala flats', 14028, 'stadium road', 'stadium', '5', '', '', 'p6esor4gi69ab0tivmjp0t7hm2', 837, '2014-08-12 14:43:01', 837, '2014-08-12 14:43:01', 0, 1, 0, 'studium', 1, '', 148, 148, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 4, 3, 2, 'julius nderitu', '-', '', '254720849914', '4LE140812HQ23', 'burst', '2014-08-12 14:37:00', '2014-08-13 14:37:00', '2014-08-12 15:38:54', 'heshima supermaket', 3942, 'heshima', 'heshima', '', '', '', 'ijdssroadcpgsifbp8a22lakf1', 842, '2014-08-12 14:44:15', 839, '2014-08-12 15:38:54', 0, 1, 0, '16314', 1, '', 149, 149, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 3, 2, 'muthee', '-', '', '254725491862', '4LE140812HQ24', 'burst pipe in kiamunyeki alot of water going to waste', '2014-08-12 14:46:00', '2014-08-13 14:46:00', '2014-08-12 14:47:34', 'kiamunyeki', 14030, 'pcea rd', 'kiamunyeki', '', '', '', 'f8nnhb43de0hjib607bm4k0pk6', 843, '2014-08-12 14:47:34', 843, '2014-08-12 14:47:34', 0, 1, 0, '10555', 1, '', 150, 150, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 5, 1, 'james kamau', '-', '', '254721350285', '4BL140812HQ13', 'wrong meter reding', '2014-08-12 14:48:00', '2014-08-13 14:48:00', '2014-08-12 14:49:31', 'shabab', 14026, '', '', '', '07410080', '', 'ijdssroadcpgsifbp8a22lakf1', 839, '2014-08-12 14:49:31', 839, '2014-08-12 14:49:31', 0, 1, 0, '', 1, '', 151, 151, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 3, 2, 'esther wangari', 'F', '', '254711226648', '4LE140812HQ25', 'leakage before the meter', '2014-08-12 14:44:00', '2014-08-13 14:44:00', '2014-08-12 14:50:33', 'opp teachers college', 11435, 'teachers', 'teachers', '', '39000320', '', 'ijdssroadcpgsifbp8a22lakf1', 842, '2014-08-12 14:50:33', 839, '2014-08-12 15:24:21', 0, 1, 0, '16314', 1, '', 152, 152, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 4, 5, 1, 'KIMANI', '-', '', '254721991072', '4BL140812HQ14', 'WRONG READINGS', '2014-08-12 14:50:00', '2014-08-13 14:50:00', '2014-08-12 15:27:00', 'makao centre', 13962, '', '', '', '54000007', '', 'f8nnhb43de0hjib607bm4k0pk6', 847, '2014-08-12 14:51:31', 843, '2014-08-12 15:27:00', 0, 1, 0, '', 1, '', 153, 153, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 4, 5, 1, 'simon oluoch', 'M', '', '254716389685', '4BL140812HQ15', 'high bill', '2014-08-12 14:43:00', '2014-08-13 14:43:00', '2014-08-12 15:24:27', 'bontana hotel', 14031, 'government road', 'bontana', '8', '09100018', '', 'f77h7fer2dpltvm8vgvu6inhr5', 837, '2014-08-12 14:54:37', 847, '2014-08-12 15:24:27', 0, 1, 0, 'bontana', 1, '', 154, 154, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 5, 3, 3, 'aifonce were', '-', '', '254723808539', '4LE140812HQ26', 'alphonce were', '2014-08-12 14:58:00', '2014-08-13 14:58:00', '2014-08-12 15:36:22', 'kanu sreet', 14032, 'kanu street', 'yorkstar', '44', '', '24', 'ijdssroadcpgsifbp8a22lakf1', 843, '2014-08-12 14:58:47', 839, '2014-08-12 15:36:22', 0, 1, 0, 'church pag', 1, '', 155, 155, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 5, 1, 'LUCY', '-', '', '254722937454', '4BL140812HQ16', 'NOT RECEIVING BILLS CHANGE MY ADDRESS', '2014-08-12 15:00:00', '2014-08-13 15:00:00', '2014-08-12 15:01:47', 'WORKERS', 14033, '', '', '', '38059600', '', 'f77h7fer2dpltvm8vgvu6inhr5', 847, '2014-08-12 15:01:47', 847, '2014-08-12 15:01:47', 0, 1, 0, '', 1, '', 156, 156, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 5, 1, 'esther', '-', '', '254711853952', '4BL140812HQ17', 'high bill due to gate locked', '2014-08-12 15:01:00', '2014-08-13 15:01:00', '2014-08-12 15:02:55', 'langalanga', 13956, 'langalanga', 'langalanga', '', '07110610', '', 'f77h7fer2dpltvm8vgvu6inhr5', 852, '2014-08-12 15:02:55', 847, '2014-08-12 15:34:37', 0, 1, 0, '16314', 1, '', 157, 157, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 4, 3, 2, 'sylvia', '-', '', '254721523436', '4LE140812HQ27', 'sewer blockage', '2014-08-12 15:03:00', '2014-08-13 15:03:00', '2014-08-12 15:38:55', 'race track', 14034, '', 'zakayos', '', '', '', 'qn2vu8d6ig5eqgmtim481vu2c2', 850, '2014-08-12 15:04:35', 852, '2014-08-12 15:38:55', 0, 1, 0, '', 1, '', 158, 158, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 3, 2, 'godfrey', 'M', '', '254728591717', '4LE140812HQ28', 'burst', '2014-08-12 15:03:00', '2014-08-13 15:03:00', '2014-08-12 15:08:41', 'solai road', 4183, 'solai road', 'solai road', '', '', '', 'ijdssroadcpgsifbp8a22lakf1', 852, '2014-08-12 15:08:41', 839, '2014-08-12 15:17:48', 0, 1, 0, '16314', 1, '', 159, 159, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 3, 2, 'mtai', '-', '', '254720244134', '4LE140812HQ29', 'leakage', '2014-08-12 15:08:00', '2014-08-13 15:08:00', '2014-08-12 15:10:09', 'dv estate', 14036, 'kabarak road', 'kobil petrol station', '3', '', '', 'p6esor4gi69ab0tivmjp0t7hm2', 837, '2014-08-12 15:10:09', 837, '2014-08-12 15:10:09', 0, 1, 0, 'riva car wash', 1, '', 160, 160, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 5, 5, 1, 'jane', '-', '', '254721713555', '4BL140812HQ18', 'high bill', '2014-08-12 15:13:00', '2014-08-13 15:13:00', '2014-08-12 15:44:12', 'CBD', 3498, '', '', '', '2134588900', '', '0bjhi31grdsg98jg12kl7pdmb0', 833, '2014-08-12 15:13:37', 846, '2014-08-12 15:44:12', 0, 1, 0, '', 1, '', 161, 161, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 4, 5, 1, 'Justin', '-', '', '254728536865', '4BL140812HQ19', 'I have not received a bill in 5 months', '2014-08-12 15:19:00', '2014-08-13 15:19:00', '2014-09-25 05:36:58', 'Lanet', 14037, '', '', '', '23804123', '', '2d6vu0r3f7i06fu0ieb5sj5442', 767, '2014-08-12 15:19:35', 778, '2014-09-25 05:36:58', 0, 1, 0, '', 1, '', 162, 162, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 4, 3, 2, 'Daniel', '-', '', '254728536865', '4LE140812HQ30', 'Major burst near nakumatt', '2014-08-12 15:21:00', '2014-08-13 15:21:00', '2014-08-12 15:38:41', 'CBD', 3498, '', 'Nakumatt', '', '', '', 'p6esor4gi69ab0tivmjp0t7hm2', 767, '2014-08-12 15:21:24', 837, '2014-08-12 15:38:41', 0, 1, 0, '', 1, '', 163, 163, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 5, 5, 1, 'kiruhu john', 'F', '', '254721230009', '4BL140812HQ20', 'meter notread', '2014-08-12 15:17:00', '2014-08-13 15:17:00', '2014-08-12 15:43:37', 'sha', 14038, 'shabab', 'shabab', '32', '38000410', '', 'qn2vu8d6ig5eqgmtim481vu2c2', 833, '2014-08-12 15:24:44', 852, '2014-08-12 15:43:37', 0, 1, 0, '', 1, '', 164, 164, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 5, 3, 2, 'Janet', '-', '', '254728536865', '4LE140812HQ31', 'Our neighbourhood is not in the sewer network. Please add us', '2014-08-12 15:43:00', '2014-08-13 15:43:00', '2014-08-12 15:47:38', 'Lanet', 14037, '', '', '', '', '', 'p6esor4gi69ab0tivmjp0t7hm2', 767, '2014-08-12 15:43:30', 837, '2014-08-12 15:47:38', 0, 1, 0, '', 1, '', 165, 165, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 5, 1, 'MBURU', 'M', '', '254727888813', '4BL140812HQ21', 'HI BILL', '2014-08-12 15:48:00', '2014-08-13 15:48:00', '2014-08-12 15:53:51', 'KANU', 14039, 'KANU', 'NAI', 'F', '07508790', '', 'quadrphobvdb7vg28d653i18d2', 833, '2014-08-12 15:53:51', 833, '2014-08-12 15:53:51', 0, 1, 0, '98908', 1, '', 166, 166, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 5, 1, 'MBURU', '-', '', '254727888813', '4BL140812HQ22', 'HI BILL', '2014-08-12 15:54:00', '2014-08-13 15:54:00', '2014-08-12 15:55:10', 'KANU', 14039, 'KANU', 'NAI', 'F', '07508790', '', 'quadrphobvdb7vg28d653i18d2', 833, '2014-08-12 15:55:10', 833, '2014-08-12 15:55:10', 0, 1, 0, '98908', 1, '', 167, 167, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 0, 2, 0, 3, 1, 5, 1, 'John', NULL, NULL, '254720314027', '41408121', 'i have not received a bill in 3 months', '2014-08-12 15:55:16', '2014-08-13 15:55:16', '0000-00-00 00:00:00', 'abc flats', 33, NULL, NULL, NULL, '1234567', NULL, NULL, 0, '2014-08-12 15:55:16', 0, '0000-00-00 00:00:00', 0, 1, 0, NULL, 1, NULL, NULL, 168, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 5, 1, 'james', '-', '', '254703289327', '4BL140813HQ1', 'high bill', '2014-08-13 08:59:00', '2014-08-14 08:59:00', '2014-08-13 08:59:37', 'Nakuru', 33, '', '', '', '1234678', '', 'nscfqioa3orr6gdo311oinpsq4', 835, '2014-08-13 08:59:37', 835, '2014-08-13 08:59:37', 0, 1, 0, '21355', 1, '', 169, 169, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 5, 3, 2, 'Titus', '-', '', '254728536865', '4LE140813HQ1', 'Water leak in Shabaab estate', '2014-08-13 10:21:00', '2014-08-14 10:21:00', '2014-08-13 11:59:04', 'Shabaab', 14040, '', 'Shabaab Estate', '10', '', '', '3tqe1kvvsunhuf3i1ed0bhqid2', 778, '2014-08-13 10:21:53', 767, '2014-08-13 11:59:04', 0, 1, 0, '', 1, '', 170, 170, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 4, 5, 1, 'Michelle', '-', '', '254728536865', '4BL140813HQ2', 'I consumed 30 units in July. I have received a bill for Aug stating I consumed 100units. HOW', '2014-08-13 10:25:00', '2014-08-14 10:25:00', '2014-08-13 11:57:00', 'section 58', 14004, '', 'Mountain View Apartments', '49', '1728349', '', '3tqe1kvvsunhuf3i1ed0bhqid2', 778, '2014-08-13 10:25:51', 767, '2014-08-13 11:57:00', 0, 1, 0, '', 1, '', 171, 171, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 5, 1, 'Timothy', '-', '', '254728536865', '4BL140813HQ3', 'I have not received a bill in 3 months. Your disconnection team came to disconnect my meter.', '2014-08-13 10:28:00', '2014-08-14 10:28:00', '2014-08-13 10:28:13', 'Milimani', 2098, '', 'Milimani Villas', '3', '3840521', '', 'omeauc9f1ah1a22tn1frl5e8q0', 778, '2014-08-13 10:28:13', 864, '2014-08-13 13:14:04', 0, 1, 0, '', 1, '', 172, 172, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 5, 5, 1, 'Gilbert', '-', '', '254728536865', '4BL140813HQ4', 'I have not received water this month.', '2014-08-13 10:30:00', '2014-08-14 10:30:00', '2014-08-13 15:06:05', 'Milimani', 2098, '', 'Shamani Apartments', '10', '3929234', '', 'b93oaa3hr6el5epu6mh468g4r4', 778, '2014-08-13 10:30:17', 853, '2014-08-13 15:06:05', 0, 1, 0, '', 1, '', 173, 173, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 5, 1, 'Loise Mutua', '-', '', '254722735711', '4BL140813HQ5', 'Wrong reading.', '2014-08-13 10:43:00', '2014-08-14 10:43:00', '2014-08-13 10:54:01', 'Freehold', 13988, 'Kanu Street', 'Freehold Estate', '5', '09201180', '', '3tqe1kvvsunhuf3i1ed0bhqid2', 767, '2014-08-13 10:54:01', 767, '2014-08-13 10:54:01', 0, 1, 0, '', 1, '', 174, 174, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 5, 5, 1, 'mr. mwaura', '-', '', '254716252166', '4BL140813HQ6', 'my mpesa payments are not updated.', '2014-08-13 12:18:00', '2014-08-14 12:18:00', '2014-08-13 15:09:41', 'next to tuskys supermarket', 14041, 'kenyatta avenue', 'town', '', '01000010', '', '20k9b51ltdpeq6hfu3n0hi9ht1', 820, '2014-08-13 12:28:29', 857, '2014-08-13 15:09:41', 0, 1, 0, '', 1, '', 175, 175, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 5, 1, 'Justin', '-', '', '254728536865', '4BL140813HQ7', 'My bill for July was very high', '2014-08-13 12:28:00', '2014-08-14 12:28:00', '2014-08-13 12:28:42', 'Kanu Street', 14042, 'Kanu street', 'Abc Flats', '3', '1234567', '', '3tqe1kvvsunhuf3i1ed0bhqid2', 767, '2014-08-13 12:28:42', 767, '2014-08-13 12:28:42', 0, 1, 0, '', 1, '', 176, 176, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 5, 1, 'jane wanjiku', '-', '', '254721552339', '4BL140813HQ8', 'wrong reading in july 2014', '2014-08-13 12:18:00', '2014-08-14 12:18:00', '2014-08-13 12:28:51', 'freehold', 13988, 'freehold estate', 'freehold', '3', '09225180', '', 'u2ud64tjrqhmgbnle73fo6c0b2', 822, '2014-08-13 12:28:51', 822, '2014-08-13 12:28:51', 0, 1, 0, 'embu street', 1, '', 177, 177, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 4, 5, 1, 'PENINAH MWANGI', 'F', '', '254711106066', '4BL140813HQ9', 'high bill in AUGUST 2014', '2014-08-13 12:18:00', '2014-08-14 12:18:00', '2014-08-13 15:02:22', 'Kiratina', 1536, 'NAIROBI HIGHWAY', 'MAMBO ESTATE', '72', '03804900', '', 'elgaa2p8l8s470dip436fe6171', 864, '2014-08-13 12:28:57', 858, '2014-08-13 15:02:22', 0, 1, 0, 'HYRAX MUSEUM', 1, '', 178, 178, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 5, 1, 'francis n', 'M', '', '254726050508', '4BL140813HQ10', 'high billing', '2014-08-13 12:18:00', '2014-08-14 12:18:00', '2014-08-13 12:29:03', 'Kenyatta', 8418, 'kenyatta', 'mountain view', '5', '02454321', '', 'elgaa2p8l8s470dip436fe6171', 853, '2014-08-13 12:29:03', 858, '2014-08-13 15:00:18', 0, 1, 0, '', 1, '', 179, 179, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 5, 1, 'njoroge Nderito', '-', '', '254720849914', '4BL140813HQ11', 'high bill', '2014-08-13 12:29:00', '2014-08-14 12:29:00', '2014-08-13 12:29:44', 'Nakatur', 2377, 'Kijabe RD', 'JAMS', 'BLK 2', '38020360', '', 'kbnlbn7k1cnsilrdmvcdtt9670', 831, '2014-08-13 12:29:44', 831, '2014-08-13 12:29:44', 0, 1, 0, '', 1, '', 180, 180, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 5, 2, 5, 1, 'salgia', '-', '', '254722937454', '4BL140813HQ12', 'high bill', '2014-08-13 12:29:00', '2014-08-14 12:29:00', '2014-08-13 12:30:30', 'section 58', 14004, '', '', '2', '05816550', '', 'elgaa2p8l8s470dip436fe6171', 858, '2014-08-13 12:30:30', 858, '2014-08-13 12:30:30', 0, 1, 0, 'near elite primary', 1, '', 181, 181, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 5, 4, 3, 2, 'George Otieno', 'M', '', '254725794232', '4LE140813HQ2', 'There is a water burst at Naka Estate near Excel Academy', '2014-08-13 12:29:00', '2014-08-14 12:29:00', '2014-08-13 15:01:48', 'Naka Estate', 14043, 'Oginga Odinga', 'Mambo Flat', '', '', '', 'b93oaa3hr6el5epu6mh468g4r4', 864, '2014-08-13 12:35:21', 853, '2014-08-13 15:01:48', 0, 1, 0, 'Excel Academy', 1, '', 182, 182, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 5, 1, 'patrick', '-', '', '254720849914', '4BL140813HQ13', 'no water', '2014-08-13 12:36:00', '2014-08-14 12:36:00', '2014-08-13 12:37:59', 'Eldoret road', 14044, '', '', '', '048000812', '', '20k9b51ltdpeq6hfu3n0hi9ht1', 858, '2014-08-13 12:37:59', 857, '2014-08-13 15:02:26', 0, 1, 0, '', 1, '', 183, 183, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 3, 2, 'titus', '-', '', '254721571845', '4LE140813HQ3', 'burst at maindi choma lake view', '2014-08-13 12:30:00', '2014-08-14 12:30:00', '2014-08-13 12:38:13', 'maindi choma', 14045, 'eldoret', 'lakeview', '', 't', '', 'b93oaa3hr6el5epu6mh468g4r4', 853, '2014-08-13 12:38:13', 853, '2014-08-13 12:38:13', 0, 1, 0, '', 1, '', 184, 184, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 5, 1, 'james okoth', '-', '', '254735845084', '4BL140813HQ14', 'leakage for the last one week', '2014-08-13 12:30:00', '2014-08-14 12:30:00', '2014-08-13 12:38:38', 'Kanu Street', 14042, 'kanu street', 'mwariki', '2', '07400012', '', 'b93oaa3hr6el5epu6mh468g4r4', 822, '2014-08-13 12:38:38', 853, '2014-08-13 14:59:16', 0, 1, 0, 'end of kanu street', 1, '', 185, 185, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 5, 1, 'Evans', '-', '', '254728536865', '4BL140813HQ15', 'High bill', '2014-08-13 12:44:00', '2014-08-14 12:44:00', '2014-08-13 12:45:50', 'Shabaab', 14040, '', '', '', '12345678', '', '3tqe1kvvsunhuf3i1ed0bhqid2', 767, '2014-08-13 12:45:50', 767, '2014-08-13 12:45:50', 0, 1, 0, '', 1, '', 186, 186, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 5, 1, 'salgia', '-', '', '254725736335', '4BL140813HQ16', 'i have not received my water bills for the last 3 months', '2014-08-13 12:39:00', '2014-08-14 12:39:00', '2014-08-13 12:47:53', 'Shabaab', 14040, '', '', '', '02432674', '', 'kbnlbn7k1cnsilrdmvcdtt9670', 853, '2014-08-13 12:47:53', 854, '2014-08-13 15:17:18', 0, 1, 0, '', 1, '', 187, 187, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 3, 2, 'John Otieno', '-', '', '254726050508', '4LE140813HQ4', 'Heavy leakage', '2014-08-13 12:47:00', '2014-08-14 12:47:00', '2014-08-13 12:48:09', 'Eastleigh Old race course', 4181, 'kanu street', 'zakayos', 'blk 7', '', '', 'eenr8c85e06qj1fg3i9pg3ccc0', 831, '2014-08-13 12:48:09', 864, '2014-08-13 15:11:08', 0, 1, 0, 'Round About', 1, '', 188, 188, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 5, 2, 5, 3, 'Naroka Greeners SHG', '-', '', '254711106066', '4BL140813HQ17', 'Received July 2014 bill yet there was no water', '2014-08-13 12:48:00', '2014-08-14 12:48:00', '2014-08-13 12:50:26', 'Rhonda', 13959, 'Mariakani', 'Rhonda Estate', '5', '47602030', '5', 'u2ud64tjrqhmgbnle73fo6c0b2', 864, '2014-08-13 12:50:26', 822, '2014-08-13 13:15:36', 0, 1, 0, 'Nakuru Tanners', 1, '', 189, 189, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 3, 2, 'chepkurui', '-', '', '254722735711', '4LE140813HQ5', 'burst', '2014-08-13 12:53:00', '2014-08-14 12:53:00', '2014-08-13 12:53:59', 'kaptembwa', 14046, '', 'chebarkamae bld', '', '', '', 'b93oaa3hr6el5epu6mh468g4r4', 820, '2014-08-13 12:53:59', 853, '2014-08-13 13:10:45', 0, 1, 0, '', 1, '', 190, 190, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 5, 1, 'kamayu', '-', '', '254722937454', '4BL140813HQ18', 'high bill', '2014-08-13 13:03:00', '2014-08-14 13:03:00', '2014-08-13 13:03:29', 'nakuru', 33, '', '', '', '54461001116', '', 'elgaa2p8l8s470dip436fe6171', 862, '2014-08-13 13:03:29', 862, '2014-08-13 13:03:29', 0, 1, 0, '', 1, '', 191, 191, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 5, 1, 'Kamay', '-', '', '254721713555', '4BL140813HQ19', 'high bill', '2014-08-13 13:06:00', '2014-08-14 13:06:00', '2014-08-13 13:06:54', 'Nakuru', 33, '', '', '', '1312425662643', '', 'b93oaa3hr6el5epu6mh468g4r4', 806, '2014-08-13 13:06:54', 853, '2014-08-13 15:11:04', 0, 1, 0, '', 1, '', 192, 192, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 5, 1, 'Joyce', '-', '', '254721713555', '4BL140813HQ20', 'my readings are wrong', '2014-08-13 13:09:00', '2014-08-14 13:09:00', '2014-08-13 13:09:32', 'alshab', 14047, '', '', '', '12424555225', '', 'elgaa2p8l8s470dip436fe6171', 806, '2014-08-13 13:09:32', 858, '2014-08-13 14:55:02', 0, 1, 0, '', 1, '', 193, 193, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 5, 2, 3, 2, 'n OTIENOJoh', '-', '', '254726050508', '4LE140813HQ6', 'High Leakage', '2014-08-13 13:12:00', '2014-08-14 13:12:00', '2014-08-13 13:13:14', 'freehold', 13988, 'Kanu Street', 'Zakayos', '', '', '', 'kbnlbn7k1cnsilrdmvcdtt9670', 831, '2014-08-13 13:13:14', 831, '2014-08-13 13:13:14', 0, 1, 0, 'round about', 1, '', 194, 194, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 3, 2, 'john', '-', '', '254722794688', '4LE140813HQ7', 'burst near mawanga', '2014-08-13 14:22:00', '2014-08-14 14:22:00', '2014-08-13 14:26:03', 'near mawanga', 14049, '', '', '', '', '', 'elgaa2p8l8s470dip436fe6171', 858, '2014-08-13 14:26:03', 858, '2014-08-13 14:26:03', 0, 1, 0, '', 1, '', 195, 195, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 5, 1, 'john', '-', '', '254729853489', '4BL140813HQ21', 'wrong meter reading', '2014-08-13 14:25:00', '2014-08-14 14:25:00', '2014-08-13 14:27:48', 'kaptembwo', 14048, '', '', '5', '074324578', '', 'u2ud64tjrqhmgbnle73fo6c0b2', 865, '2014-08-13 14:27:48', 866, '2014-08-13 15:46:11', 0, 1, 0, '', 1, '', 196, 196, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 5, 2, 5, 1, 'josphine c.kibirir', 'F', '', '254722680035', '4BL140813HQ22', 'wrong reading in august2014', '2014-08-13 14:25:00', '2014-08-14 14:25:00', '2014-08-13 14:34:03', 'shabab', 14026, 'kenlands', 'quickmart', '', '06922023', '', '20k9b51ltdpeq6hfu3n0hi9ht1', 857, '2014-08-13 14:34:03', 857, '2014-08-13 14:34:03', 0, 1, 0, '', 1, '', 197, 197, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 5, 5, 1, 'damaris', '-', '', '254716252166', '4BL140813HQ23', 'over billing', '2014-08-13 14:33:00', '2014-08-14 14:33:00', '2014-08-13 15:02:49', 'kanyi', 14050, '', '', '', '07658743', '', 'eenr8c85e06qj1fg3i9pg3ccc0', 865, '2014-08-13 14:34:03', 864, '2014-08-13 15:02:49', 0, 1, 0, '', 1, '', 198, 198, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 5, 5, 1, 'mary', '-', '', '254724824560', '4BL140813HQ24', 'estimated bill', '2014-08-13 14:34:00', '2014-08-14 14:34:00', '2014-08-13 15:03:52', 'solai road', 4183, '', '', '', '45400076', '', 'elgaa2p8l8s470dip436fe6171', 858, '2014-08-13 14:34:18', 858, '2014-08-13 15:03:52', 0, 1, 0, '', 1, '', 199, 199, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 3, 2, 'Andrew Kulecho', '-', '', '254726050508', '4LE140813HQ8', 'High leakage', '2014-08-13 14:34:00', '2014-08-14 14:34:00', '2014-08-13 14:35:03', 'Within blk 2 Compound', 14051, 'Stadium Rd', 'Muhamudi Kahero', 'Blk 2', '', '', 'kbnlbn7k1cnsilrdmvcdtt9670', 831, '2014-08-13 14:35:03', 831, '2014-08-13 14:35:03', 0, 1, 0, 'Junction to estate', 1, '', 200, 200, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 5, 1, 'Eunice', '-', '', '254725794232', '4BL140813HQ25', 'My water is disconnected and i had cleared my bill', '2014-08-13 14:37:00', '2014-08-14 14:37:00', '2014-08-13 14:38:31', 'Kisulisuli', 14052, 'kimalel road', 'Kanyi Estate', '37/D.3', '06502942', '', '20k9b51ltdpeq6hfu3n0hi9ht1', 864, '2014-08-13 14:38:31', 857, '2014-08-13 15:20:47', 0, 1, 0, 'Kisulisuli Primary School', 1, '', 201, 201, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 4, 5, 1, 'george hrty', '-', '', '254722937454', '4BL140813HQ26', 'high billing in july 2014', '2014-08-13 14:37:00', '2014-08-14 14:37:00', '2014-08-13 15:21:04', 'kanu sreet', 14032, 'kanu street', 'jams hotel', '5', '08102025', '', 'u2ud64tjrqhmgbnle73fo6c0b2', 866, '2014-08-13 14:38:36', 866, '2014-08-13 15:21:04', 0, 1, 0, 'freehold', 1, '', 202, 202, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 3, 2, 'muiruri', '-', '', '254725794232', '4LE140813HQ9', 'leakage at githima', '2014-08-13 14:40:00', '2014-08-14 14:40:00', '2014-08-13 14:40:56', 'githima', 659, '', '', '', '', '', 'b93oaa3hr6el5epu6mh468g4r4', 865, '2014-08-13 14:40:56', 865, '2014-08-13 14:40:56', 0, 1, 0, '', 1, '', 203, 203, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 4, 3, 2, 'barbra chelio', '-', '', '254707890790', '4LE140813HQ10', 'water leaking from my kitchen', '2014-08-13 14:34:00', '2014-08-14 14:34:00', '2014-08-13 15:06:24', 'ngashura', 14053, '', '', '', '2266692', '', 'kbnlbn7k1cnsilrdmvcdtt9670', 857, '2014-08-13 14:45:51', 854, '2014-08-13 15:06:24', 0, 1, 0, '', 1, '', 204, 204, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 4, 3, 2, 'kimani', '-', '', '254711106066', '4LE140813HQ11', 'burst pipe at manyani near cementry', '2014-08-13 14:46:00', '2014-08-14 14:46:00', '2014-08-13 15:14:54', 'manyani east', 4619, 'kalewa road', 'manyani', '70', '', '', 'u2ud64tjrqhmgbnle73fo6c0b2', 864, '2014-08-13 14:47:16', 866, '2014-08-13 15:14:54', 0, 1, 0, 'cementry', 1, '', 205, 205, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 5, 1, 'gilbert mutai', '-', '', '254722794688', '4BL140813HQ27', 'high biil', '2014-08-13 14:47:00', '2014-08-14 14:47:00', '2014-08-13 14:48:10', 'Rhonda', 13959, '', '', '', '48002030', '', 'kbnlbn7k1cnsilrdmvcdtt9670', 831, '2014-08-13 14:48:10', 831, '2014-08-13 14:48:10', 0, 1, 0, 'Quarry', 1, '', 206, 206, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 5, 5, 1, 'Oscar', '-', '', '254728536865', '4BL140813HQ28', 'My bill was high this month', '2014-08-13 15:03:00', '2014-08-14 15:03:00', '2014-08-13 15:19:04', 'section 58', 14004, '', '', '', '4579645', '', 'u2ud64tjrqhmgbnle73fo6c0b2', 767, '2014-08-13 15:03:17', 866, '2014-08-13 15:19:04', 0, 1, 0, '', 1, '', 207, 207, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 3, 2, 'Evans', '-', '', '254728536865', '4LE140813HQ12', 'Water burst outside Nakumatt', '2014-08-13 15:13:00', '2014-08-14 15:13:00', '2014-08-13 15:13:52', 'CBD', 3498, '', 'Nakumatt', '', '', '', '3tqe1kvvsunhuf3i1ed0bhqid2', 767, '2014-08-13 15:13:52', 767, '2014-08-13 15:13:52', 0, 1, 0, '', 1, '', 208, 208, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 0, 2, 0, 3, 1, 5, 1, 'Wanyonyi', NULL, NULL, '254787359271', '41408131', 'My bill is high', '2014-08-13 15:26:08', '2014-08-14 15:26:08', '0000-00-00 00:00:00', 'ABC Flats', 33, NULL, NULL, NULL, '1234567', NULL, NULL, 0, '2014-08-13 15:26:08', 0, '0000-00-00 00:00:00', 0, 1, 0, NULL, 1, NULL, NULL, 209, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 5, 1, 'afsafwrewrewrew', '-', '', '254723424324', '4BL140828HQ1', 'this and that', '2014-08-28 09:20:00', '2014-08-29 09:20:00', '2014-08-28 09:20:28', 'Ajao', 63, '', '', '', '12312321312', '', 'k8makqii2uac5avb86pb4rdc47', 778, '2014-08-28 09:20:28', 778, '2014-08-28 09:20:28', 0, 1, 0, '', 1, '', 210, 210, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 5, 1, 'Daniel Kamiri', '-', '', '254722342084', '4BL140924HQ1', 'popopo', '2014-09-24 13:15:00', '2014-09-25 13:15:00', '2014-09-24 13:15:14', '4th Parklands Avenue,A1', 3289, 'Kahawa Rd', 'Sukari Building', '1234', '987654321', '', '2d6vu0r3f7i06fu0ieb5sj5442', 848, '2014-09-24 13:15:14', 848, '2014-09-24 13:15:14', 0, 1, 0, 'Karibu na Total', 1, '', 211, 211, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 1, 5, 1, 'dan', '-', 'dankamiri@gmail.com', '254734454344', '4BL140924HQ2', 'atatatatata', '2014-09-24 13:33:00', '2014-09-25 13:33:00', '0000-00-00 00:00:00', 'Jamhuri Park', 829, 'this rd', 'this bldg', 'this unit', '87654321', 'poil', '2d6vu0r3f7i06fu0ieb5sj5442', 848, '2014-09-24 13:35:10', 0, '0000-00-00 00:00:00', 0, 1, 0, 'next to abc ', 1, '', 212, 212, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 5, 1, 'dan', '-', '', '254734454344', '4BL140924HQ3', 'atatatatata', '2014-09-24 13:44:00', '2014-09-25 13:44:00', '2014-09-24 13:44:16', 'Jamhuri Park', 829, 'this rd', 'this bldg', 'this unit', '87654321', 'poil', '2d6vu0r3f7i06fu0ieb5sj5442', 848, '2014-09-24 13:44:16', 848, '2014-09-24 13:44:16', 0, 1, 0, 'next to abc ', 1, '', 213, 213, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 3, 2, 'Juma', '-', '', '254713121212', '4LE140924HQ1', 'hakuna maji', '2014-09-24 17:45:00', '2014-09-25 17:45:00', '2014-09-24 17:46:14', 'at the parking of sadolin paints premises', 3635, '', '', '', '', '', '2d6vu0r3f7i06fu0ieb5sj5442', 838, '2014-09-24 17:46:14', 838, '2014-09-24 17:46:14', 0, 1, 0, '', 1, '', 214, 214, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 5, 1, 'asdfadf', '-', '', '254722323432', '4BL141003HQ1', 'asdfafasdf', '2014-10-03 13:36:00', '2014-11-02 13:36:00', '2014-10-03 13:44:46', 'Water Services Regulatory Board', 3, '', '', '', 'we23423232323', '', '2031tbcehv2504eotijn57his7', 778, '2014-10-03 13:44:46', 778, '2014-10-03 13:44:46', 0, 1, 0, '', 1, '', 215, 215, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 1, 2, 0, 1, 2, 2, 1, 'dd', 'm', 'dd@dd.com', '078833333333', 'trw', 'this is a test', '2014-10-15 00:00:00', '2014-10-15 00:00:00', '0000-00-00 00:00:00', 'parkie', 1, 'this it ist', 'asdfds', NULL, NULL, NULL, NULL, 2, NULL, 0, '0000-00-00 00:00:00', 0, 1, 0, NULL, 1, NULL, NULL, 216, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 5, 1, 'Dan Me', '-', '', '254722332233', '4BL141112HQ1', 'this is a test', '2014-11-12 07:12:00', '2014-11-13 07:12:00', '2014-11-12 07:13:20', 'Water Services Regulatory Board', 3, '', '', '', '123412', '', 'cqr04sv0j9iqmh3ednllpa6s61', 858, '2014-11-12 07:13:20', 858, '2014-11-12 07:13:20', 0, 1, 0, '', 1, '', 216, 217, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 5, 1, 'Joseph', '-', '', '254777777777', '4BL141112HQ2', 'asdf adsf ds', '2014-11-12 07:13:00', '2014-11-13 07:13:00', '2014-11-12 07:14:24', 'Kamwangi', 1045, '', '', '', '1231', '', 'cqr04sv0j9iqmh3ednllpa6s61', 858, '2014-11-12 07:14:24', 858, '2014-11-12 07:14:24', 0, 1, 0, '', 1, '', 217, 218, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 5, 1, 'Joseph', '-', '', '254777777777', '4BL141112HQ3', 'asdf adsf ds', '2014-11-12 07:15:00', '2014-11-13 07:15:00', '2014-11-12 07:15:35', 'Kamwangi', 1045, '', '', '', '1231', '', 'cqr04sv0j9iqmh3ednllpa6s61', 858, '2014-11-12 07:15:35', 858, '2014-11-12 07:15:35', 0, 1, 0, '', 1, '', 218, 219, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 5, 1, 'Joseph', '-', '', '254777777777', '4BL141112HQ4', 'asdf adsf ds', '2014-11-12 07:15:00', '2014-11-13 07:15:00', '2014-11-12 07:19:53', 'Kamwangi', 1045, '', '', '', '1231', '', 'cqr04sv0j9iqmh3ednllpa6s61', 858, '2014-11-12 07:19:53', 858, '2014-11-12 07:19:53', 0, 1, 0, '', 1, '', 219, 220, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 5, 1, 'Joseph', '-', '', '254777777777', '4BL141112HQ5', 'asdf adsf ds', '2014-11-12 07:15:00', '2014-11-13 07:15:00', '2014-11-12 07:20:03', 'Kamwangi', 1045, '', '', '', '1231', '', 'cqr04sv0j9iqmh3ednllpa6s61', 858, '2014-11-12 07:20:03', 858, '2014-11-12 07:20:03', 0, 1, 0, '', 1, '', 220, 221, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 11, 2, 0, 6, 2, 5, 1, 'test', '-', '', '254722222222', '4BL141114HQ1', 'adsf asf dfs', '2014-11-14 09:31:00', '2014-11-15 09:31:00', '2014-11-14 09:31:50', 'World Bank Offices, Kenya', 1, '', '', '', '092263', '', 'q38bgu1pfk2vtfajafq81mhed6', 778, '2014-11-14 09:31:50', 778, '2014-11-14 09:31:50', 0, 1, 0, '', 1, '', 221, 222, 0, 0, 0, NULL, NULL, '2015-03-11 13:12:49', 0),
(0, 1, 2, 2, 0, 6, 2, 1, 1, 'Didi Mclauren', '-', '', '254711221122', '2SF150311HQ1', 'This is a test for Transfer of task', '2015-03-11 16:21:00', '2015-03-13 16:21:00', '2015-03-11 16:21:30', 'Upper Parklands Estate', 3067, '', '', '', '22323', '', '8cuo2fnel6si2lguv2rvgjjp45', 3, '2015-03-11 16:21:30', 3, '2015-03-11 16:21:30', 0, 1, 0, '', 1, '', 222, 223, 0, 0, 0, NULL, NULL, '2015-03-12 15:37:28', 0),
(0, 1, 2, 2, 0, 6, 2, 1, 1, 'KK LL', '-', '', '254711111111', '2SF150311HQ2', 'This is test 2', '2015-03-11 16:21:00', '2015-03-13 16:21:00', '2015-03-11 16:22:03', 'Jamhuri Park', 829, '', '', '', '2342332', '', '8cuo2fnel6si2lguv2rvgjjp45', 3, '2015-03-11 16:22:03', 3, '2015-03-11 16:22:03', 0, 1, 0, '', 1, '', 223, 224, 0, 0, 0, NULL, NULL, '2015-03-11 13:22:03', 0),
(0, 1, 2, 2, 0, 6, 1, 1, 1, 'Joki', '-', '', '254722222222', '2SF150318HQ1', 'This is a test', '2015-03-18 07:25:00', '2015-03-20 07:25:00', '0000-00-00 00:00:00', 'parklands', 3241, '', '', '', '23423', '', 'ok75dp79bb82qj0s5c9gbh9fv5', 249, '2015-03-18 07:26:56', 0, '0000-00-00 00:00:00', 0, 1, 0, '', 1, '', 224, 225, 0, 0, 0, NULL, NULL, '2015-03-18 04:26:56', 0),
(0, 1, 2, 2, 0, 6, 1, 1, 1, 'asdfasdfads', '-', '', '254723234323', '2SF150318HQ2', 'adsf asf as', '2015-03-18 07:51:00', '2015-03-20 07:51:00', '0000-00-00 00:00:00', 'Archers Post', 88, '', '', '', 'werewr', '', 'ok75dp79bb82qj0s5c9gbh9fv5', 249, '2015-03-18 07:53:33', 0, '0000-00-00 00:00:00', 0, 1, 0, '', 1, '', 225, 226, 0, 0, 0, NULL, NULL, '2015-03-18 04:53:33', 0),
(0, 1, 2, 2, 0, 6, 1, 1, 1, 'dan', '-', '', '254711111111', '2SF150318HQ3', 'asf asf as', '2015-03-18 08:25:00', '2015-03-20 08:25:00', '0000-00-00 00:00:00', 'GreenFields', 9, '', '', '', '11111', '', 'ok75dp79bb82qj0s5c9gbh9fv5', 249, '2015-03-18 08:26:19', 0, '0000-00-00 00:00:00', 0, 1, 0, '', 1, '', 226, 227, 0, 0, 0, NULL, NULL, '2015-03-18 05:26:19', 0),
(0, 1, 2, 2, 0, 6, 1, 1, 1, 'Kamau', '-', '', '254711111111', '2SF150318HQ4', 'adsf asdf ads f', '2015-03-18 08:41:00', '2015-03-20 08:41:00', '0000-00-00 00:00:00', 'Jamhuri Park', 829, '', '', '', '1231231', '', 'ok75dp79bb82qj0s5c9gbh9fv5', 249, '2015-03-18 08:42:56', 0, '0000-00-00 00:00:00', 0, 1, 0, '', 1, '', 227, 228, 0, 0, 0, NULL, NULL, '2015-03-18 05:42:56', 0),
(0, 1, 2, 2, 0, 6, 1, 1, 1, 'Joska', '-', '', '254713890854', '2SF150318HQ5', 'This is another Test', '2015-03-18 08:50:00', '2015-03-20 08:50:00', '0000-00-00 00:00:00', 'Awach Tende', 106, '', '', '', '121212', '', 'ok75dp79bb82qj0s5c9gbh9fv5', 249, '2015-03-18 08:52:10', 0, '0000-00-00 00:00:00', 0, 1, 0, '', 1, '', 228, 229, 0, 0, 0, NULL, NULL, '2015-03-18 05:52:10', 0),
(0, 1, 4, 2, 0, 6, 1, 5, 1, 'Dan KM', '-', '', '254722344213', '2BL150324CE1', 'Overbilled 2015 Jan', '2015-03-24 19:56:00', '2015-04-23 19:56:00', '0000-00-00 00:00:00', 'Parklands', 3241, '', '', '', '1231231', '', '5dl6kos8c9tboegp90ln01too6', 205, '2015-03-24 19:56:38', 0, '0000-00-00 00:00:00', 0, 1, 0, '', 1, '', 229, 230, 0, 0, 0, NULL, NULL, '2015-03-24 16:56:38', 0),
(0, 1, 4, 2, 0, 6, 1, 5, 1, 'John K', '-', '', '254777777777', '2BL150324CE2', 'high bill', '2015-03-24 21:48:00', '2015-04-23 21:48:00', '0000-00-00 00:00:00', 'Juja Road', 4020, '', '', '', '199999999', '', '5dl6kos8c9tboegp90ln01too6', 205, '2015-03-24 21:48:46', 0, '0000-00-00 00:00:00', 0, 1, 0, '', 1, '', 230, 231, 0, 0, 0, NULL, NULL, '2015-04-28 10:22:27', 0),
(0, 1, 4, 2, 0, 6, 1, 5, 1, 'Dan', '-', '', '254790283211', '2BL150325CE1', 'hi bill', '2015-03-25 10:08:00', '2015-04-24 10:08:00', '0000-00-00 00:00:00', 'parklands', 3241, '', '', '', '999893323122', '', 'ug1pl1e6l42c667buak0soag27', 205, '2015-03-25 10:20:30', 0, '0000-00-00 00:00:00', 0, 1, 0, '', 1, '', 231, 232, 4, 24, 4, NULL, NULL, '2015-04-29 08:44:18', 0),
(0, 1, 4, 2, 0, 6, 1, 5, 1, 'Joh', '-', '', '254789091832', '2BL150421CE1', 'Hi bill Feb', '2015-04-21 23:05:00', '2015-05-21 23:05:00', '0000-00-00 00:00:00', 'Pangani', 5, '', '', '', '111999000', '', '055c2907leicimketsdl8729n7', 205, '2015-04-21 23:06:10', 0, '0000-00-00 00:00:00', 0, 1, 0, '', 1, '', 232, 233, 3, 25, 3, NULL, NULL, '2015-04-29 08:44:11', 0),
(0, 1, 4, 2, 0, 6, 1, 5, 1, 'Jemin', '-', '', '254771009111', '2BL150421CE2', 'ver hi bl', '2015-04-21 23:06:00', '2015-05-21 23:06:00', '0000-00-00 00:00:00', 'Otiende', 14, '', '', '', '9990099900', '', '055c2907leicimketsdl8729n7', 205, '2015-04-21 23:06:55', 0, '0000-00-00 00:00:00', 0, 1, 0, '', 1, '', 233, 234, 0, 0, 0, NULL, NULL, '2015-04-28 10:22:27', 0),
(0, 1, 4, 2, 0, 6, 1, 5, 1, 'dddd', '-', '', '254709900003', '2BL150422CE1', 'This is a test', '2015-04-22 05:55:00', '2015-05-22 05:55:00', '0000-00-00 00:00:00', 'GreenFields', 9, '', '', '', '144411233', '', '055c2907leicimketsdl8729n7', 205, '2015-04-22 05:55:49', 0, '0000-00-00 00:00:00', 0, 1, 0, '', 1, '', 234, 235, 5, 24, 5, NULL, NULL, '2015-04-29 08:44:28', 0),
(0, 1, 4, 2, 0, 6, 1, 5, 1, 'Test User', '-', '', '254713312433', '2BL150422CE2', 'Test Ticket', '2015-04-22 08:42:00', '2015-05-22 08:42:00', '0000-00-00 00:00:00', 'Jamhuri Park', 829, '', '', '', '13312233443', '', '055c2907leicimketsdl8729n7', 3, '2015-04-22 08:43:26', 0, '0000-00-00 00:00:00', 0, 1, 0, '', 1, '', 235, 236, 0, 0, 0, NULL, NULL, '2015-04-22 05:43:26', 0),
(0, 1, 4, 2, 0, 6, 1, 5, 1, 'John Kamau', '-', '', '254713323224', '2BL150422CE3', 'Test Ticket', '2015-04-22 08:43:00', '2015-05-22 08:43:00', '0000-00-00 00:00:00', 'Pangani', 5, '', '', '', '211121212', '', '055c2907leicimketsdl8729n7', 3, '2015-04-22 08:44:02', 0, '0000-00-00 00:00:00', 0, 1, 0, '', 1, '', 236, 237, 0, 0, 0, NULL, NULL, '2015-04-28 10:22:27', 0),
(0, 1, 5, 2, 0, 6, 1, 5, 1, 'John', '-', '', '254714000999', '2BL150422NE1', 'Test Ticket', '2015-04-22 08:44:00', '2015-05-22 08:44:00', '0000-00-00 00:00:00', 'GreenFields', 9, '', '', '', '14423233', '', '055c2907leicimketsdl8729n7', 3, '2015-04-22 08:44:39', 0, '0000-00-00 00:00:00', 0, 1, 0, '', 1, '', 237, 238, 0, 0, 0, NULL, NULL, '2015-04-28 10:22:27', 0),
(0, 1, 5, 2, 0, 6, 1, 5, 1, 'Johhh', '-', '', '254710009933', '2BL150422NE2', 'This is a test', '2015-04-22 08:44:00', '2015-05-22 08:44:00', '0000-00-00 00:00:00', 'Pangani', 5, '', '', '', '23523432', '', '055c2907leicimketsdl8729n7', 3, '2015-04-22 08:45:15', 0, '0000-00-00 00:00:00', 0, 1, 0, '', 1, '', 238, 239, 0, 0, 0, NULL, NULL, '2015-04-28 10:22:27', 0),
(0, 1, 6, 2, 0, 6, 1, 5, 1, 'Opoti', '-', '', '254713333131', '2BL150422NR1', 'Test Tiket', '2015-04-22 08:45:00', '2015-05-22 08:45:00', '0000-00-00 00:00:00', 'Jamhuri Park', 829, '', '', '', '341122121', '', '055c2907leicimketsdl8729n7', 3, '2015-04-22 08:46:05', 0, '0000-00-00 00:00:00', 0, 1, 0, '', 1, '', 239, 240, 0, 0, 0, NULL, NULL, '2015-04-28 10:22:27', 0),
(0, 1, 6, 2, 0, 6, 1, 5, 1, 'Dann', '-', '', '254799000111', '2BL150422NR2', 'This is atest', '2015-04-22 08:53:00', '2015-05-22 08:53:00', '0000-00-00 00:00:00', 'South B', 10, '', '', '', '134423233', '', '055c2907leicimketsdl8729n7', 3, '2015-04-22 08:53:42', 0, '0000-00-00 00:00:00', 0, 1, 0, '', 1, '', 240, 241, 0, 0, 0, NULL, NULL, '2015-04-28 10:22:27', 0),
(0, 1, 6, 2, 0, 6, 1, 5, 1, 'John', '-', '', '254799911123', '2BL150422NR3', 'Test Ticket', '2015-04-22 08:53:00', '2015-05-22 08:53:00', '0000-00-00 00:00:00', 'Boma Upande', 185, '', '', '', '42232323', '', '055c2907leicimketsdl8729n7', 3, '2015-04-22 08:54:16', 0, '0000-00-00 00:00:00', 0, 1, 0, '', 1, '', 241, 242, 0, 0, 0, NULL, NULL, '2015-04-28 10:22:27', 0),
(0, 1, 7, 2, 0, 6, 1, 5, 1, 'Paul', '-', '', '254713311231', '2BL150422WE1', 'Test ticket', '2015-04-22 08:54:00', '2015-05-22 08:54:00', '0000-00-00 00:00:00', 'LAVINGITON WEST NEAR POLICE POST', 3387, '', '', '', '1221212', '', '055c2907leicimketsdl8729n7', 3, '2015-04-22 08:54:54', 0, '0000-00-00 00:00:00', 0, 1, 0, '', 1, '', 242, 243, 0, 0, 0, NULL, NULL, '2015-04-22 05:54:54', 0),
(0, 1, 8, 2, 0, 6, 1, 5, 1, 'Joh', '-', '', '254712125553', '2BL150422SR1', 'this is a test', '2015-04-22 08:54:00', '2015-05-22 08:54:00', '0000-00-00 00:00:00', 'Kileleshwa', 24, '', '', '', '123123123', '', '055c2907leicimketsdl8729n7', 3, '2015-04-22 08:56:03', 0, '0000-00-00 00:00:00', 0, 1, 0, '', 1, '', 243, 244, 0, 0, 0, NULL, NULL, '2015-04-28 10:22:27', 0),
(0, 1, 8, 2, 0, 6, 1, 5, 1, 'Juliet', '-', '', '254723321111', '2BL150422SR2', 'test ticket', '2015-04-22 08:56:00', '2015-05-22 08:56:00', '0000-00-00 00:00:00', 'GreenFields', 9, '', '', '', '12121212', '', '055c2907leicimketsdl8729n7', 3, '2015-04-22 08:56:50', 0, '0000-00-00 00:00:00', 0, 1, 0, '', 1, '', 244, 245, 0, 0, 0, NULL, NULL, '2015-04-28 10:22:27', 0),
(0, 1, 9, 2, 0, 6, 1, 5, 1, 'James', '-', '', '254713312221', '2BL150422ES1', 'this is a test', '2015-04-22 08:56:00', '2015-05-22 08:56:00', '0000-00-00 00:00:00', 'BP Stage', 17, '', '', '', '222323235', '', '055c2907leicimketsdl8729n7', 3, '2015-04-22 08:57:28', 0, '0000-00-00 00:00:00', 0, 1, 0, '', 1, '', 245, 246, 0, 0, 0, NULL, NULL, '2015-04-22 05:57:28', 0),
(0, 1, 9, 2, 0, 6, 1, 5, 1, 'Kamau', '-', '', '254791211343', '2BL150422ES2', 'Test ticket', '2015-04-22 08:58:00', '2015-05-22 08:58:00', '0000-00-00 00:00:00', 'Kariokor', 7, '', '', '', '111121122', '', '055c2907leicimketsdl8729n7', 3, '2015-04-22 08:58:23', 0, '0000-00-00 00:00:00', 0, 1, 0, '', 1, '', 246, 247, 0, 0, 0, NULL, NULL, '2015-04-28 10:22:27', 0),
(0, 1, 4, 2, 0, 6, 1, 5, 1, 'DDD', '-', '', '254989232323', '2BL150427CE1', 'test test', '2015-04-27 16:24:00', '2015-05-27 16:24:00', '0000-00-00 00:00:00', 'parliament rd round about', 4911, '', '', '', '11114343434', '', 'l9ljmm9njhthttsej3dm7d2st7', 3, '2015-04-27 16:24:22', 0, '0000-00-00 00:00:00', 0, 1, 0, '', 1, '', 247, 248, 0, 0, 0, NULL, NULL, '2015-04-28 10:22:27', 0);
INSERT INTO `tktin` (`idtktin`, `tktlang_idtktlang`, `usrteamzone_idusrteamzone`, `usrteam_idusrteam`, `tktgroup_idtktgroup`, `tktchannel_idtktchannel`, `tktstatus_idtktstatus`, `tktcategory_idtktcategory`, `tkttype_idtkttype`, `sendername`, `sendergender`, `senderemail`, `senderphone`, `refnumber`, `tktdesc`, `timereported`, `timedeadline`, `timeclosed`, `city_town`, `loctowns_idloctowns`, `road_street`, `building_estate`, `unitno`, `waterac`, `kioskno`, `usrsession`, `createdby`, `createdon`, `modifiedby`, `modifiedon`, `wfteamesc_idwfteamesc`, `tktpub_count`, `orgesc_idorgesc`, `landmark`, `is_validated`, `refnumber_prev`, `wftaskstrac_idwftaskstrac`, `idtktinPK`, `batch_number`, `wftasks_batch_idwftasks_batch`, `voucher_number`, `cms_complaint_no`, `tktcategory_sub_idtktcategory_sub`, `last_updated`, `internal_task`) VALUES
(0, 1, 4, 2, 0, 6, 1, 5, 1, 'dd', '-', '', '254877744534', '2BL150521CE1', 'asdfasf as', '2015-05-21 12:07:00', '2015-06-20 12:07:00', '0000-00-00 00:00:00', 'adfas', 14054, '', '', '', '1232323', '', 'l3ggs45otplekr8omm2lj0tol3', 205, '2015-05-21 12:08:25', 0, '0000-00-00 00:00:00', 0, 1, 0, '', 1, '', 248, 249, 6, 25, 6, NULL, NULL, '2015-05-29 05:25:31', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tktin_progress`
--

CREATE TABLE IF NOT EXISTS `tktin_progress` (
  `idtktin_progress` bigint(20) NOT NULL DEFAULT '0',
  `tktin_idtktin` bigint(20) NOT NULL,
  `tktstatus_idtktstatus` int(2) NOT NULL DEFAULT '0',
  `usrteam_idusrteam` int(4) unsigned NOT NULL,
  `department` varchar(40) DEFAULT NULL,
  `lastmsg` text,
  `lastactiondate` datetime NOT NULL,
  `lastcronupdate` int(11) NOT NULL,
  `idtktin_progressPK` bigint(20) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`idtktin_progressPK`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tktin_public`
--

CREATE TABLE IF NOT EXISTS `tktin_public` (
  `idtktin` bigint(20) NOT NULL DEFAULT '0',
  `tktin_tktstatus_idtktstatus` int(2) unsigned NOT NULL,
  `tktin_idtktin` bigint(20) NOT NULL,
  `sendername` varchar(50) DEFAULT NULL,
  `sendergender` char(1) DEFAULT NULL,
  `senderemail` varchar(200) DEFAULT NULL,
  `senderphone` varchar(20) DEFAULT NULL,
  `refnumber` char(10) NOT NULL,
  `tktdesc` text,
  `timereported` datetime NOT NULL,
  `timedeadline` datetime DEFAULT NULL,
  `timeclosed` datetime DEFAULT NULL,
  `city_town` varchar(250) DEFAULT NULL,
  `createdon` datetime DEFAULT NULL,
  `idtktinPK` bigint(20) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`idtktinPK`),
  KEY `refnumber` (`refnumber`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tktlang`
--

CREATE TABLE IF NOT EXISTS `tktlang` (
  `idtktlang` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `tktlangname` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`idtktlang`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `tktlang`
--

INSERT INTO `tktlang` (`idtktlang`, `tktlangname`) VALUES
(1, 'English'),
(2, 'Swahili');

-- --------------------------------------------------------

--
-- Table structure for table `tktmsglogs_dashboard`
--

CREATE TABLE IF NOT EXISTS `tktmsglogs_dashboard` (
  `idtktmsglogs_dashboard` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tktmsgs_idtktmsgs` int(5) unsigned NOT NULL,
  `msgto_roleid` int(5) unsigned DEFAULT NULL,
  `msgto_subject` varchar(150) DEFAULT NULL,
  `msgto_body` text,
  `createdon` datetime DEFAULT NULL,
  `readon` datetime DEFAULT NULL,
  PRIMARY KEY (`idtktmsglogs_dashboard`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tktmsgs`
--

CREATE TABLE IF NOT EXISTS `tktmsgs` (
  `idtktmsgs` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `wfnotification_idwfnotification` int(8) unsigned NOT NULL DEFAULT '0',
  `tktstatus_idtktstatus` int(2) unsigned NOT NULL DEFAULT '0',
  `usrteam_idusrteam` int(4) unsigned NOT NULL,
  `tktmsgsubject` varchar(40) DEFAULT NULL,
  `tktmsg_sms` varchar(100) DEFAULT NULL,
  `tktmsg_email` text,
  `tktmsg_dashboard` varchar(250) DEFAULT NULL,
  `createdby` int(8) unsigned DEFAULT NULL,
  `createdon` datetime DEFAULT NULL,
  `modifiedby` int(8) unsigned DEFAULT '0',
  `modifiedon` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`idtktmsgs`),
  KEY `wfnotification_idwfnotification` (`wfnotification_idwfnotification`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tktmsgslog_emails`
--

CREATE TABLE IF NOT EXISTS `tktmsgslog_emails` (
  `idtktmsgslog` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tktmsgs_idtktmsgs` int(5) unsigned NOT NULL,
  `emailto` varchar(150) DEFAULT NULL,
  `emailsubject` varchar(200) DEFAULT NULL,
  `emailbody` text,
  `createdon` datetime DEFAULT NULL,
  `senton` datetime DEFAULT NULL,
  PRIMARY KEY (`idtktmsgslog`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tktstatus`
--

CREATE TABLE IF NOT EXISTS `tktstatus` (
  `idtktstatus` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `tktstatusname` varchar(30) DEFAULT NULL,
  `tktstatusdesc` text,
  `tktstatustopublic_en` varchar(85) DEFAULT NULL,
  `tktstatustopublic_swa` varchar(85) DEFAULT NULL,
  `status_color` varchar(10) DEFAULT NULL,
  `cms_value` char(5) DEFAULT NULL,
  PRIMARY KEY (`idtktstatus`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `tktstatus`
--

INSERT INTO `tktstatus` (`idtktstatus`, `tktstatusname`, `tktstatusdesc`, `tktstatustopublic_en`, `tktstatustopublic_swa`, `status_color`, `cms_value`) VALUES
(1, 'New', 'When the Task is New (When task has just been created)', 'We shall look into the issue and update you.', 'Tutaliangalia hilo swala na tutakujulisha ipasavyo ', '#356aa0', 'ZQ003'),
(2, 'In Progress', 'The matter is currently being worked on by our Team', 'Update - Our Team is currently working on the issue', 'Wafanyikazi wetu sasa wanalitatua swala lile ', '#C79810', 'ZQ004'),
(3, 'On Hold', 'The Ticket cannot be submitted because of lack of required information or lack of clarity', 'We shall call you for more details', 'Tutakupitigia simu hivi karibuni kwa maelezo zaidi', '#CCCCCC', 'ZQ005'),
(4, 'Closed', 'The Task has been completed and case closed', 'The issue has been dealt with.', 'Swala hili limetatuliwa.', '#009900', 'ZQ001'),
(5, 'Invalidated', 'This Ticket is not Valid', 'Sorry - The issue you submitted was invalid.', 'Samahani - Swala ulilotuma halijakamilika', '#ff9900', 'ZQ002');

-- --------------------------------------------------------

--
-- Table structure for table `tktstatus_log`
--

CREATE TABLE IF NOT EXISTS `tktstatus_log` (
  `idtktstatus_log` int(11) NOT NULL AUTO_INCREMENT,
  `idtktinPK` int(11) NOT NULL DEFAULT '0',
  `usrteam_idusrteam` int(5) NOT NULL,
  `tktstatus_idtktstatus` int(3) NOT NULL,
  `timeclosed` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_updated` datetime NOT NULL,
  `lastcronupdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `idtktstatus_logPK` int(11) NOT NULL,
  PRIMARY KEY (`idtktstatus_log`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `tktstatus_log`
--

INSERT INTO `tktstatus_log` (`idtktstatus_log`, `idtktinPK`, `usrteam_idusrteam`, `tktstatus_idtktstatus`, `timeclosed`, `last_updated`, `lastcronupdate`, `idtktstatus_logPK`) VALUES
(1, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tkttype`
--

CREATE TABLE IF NOT EXISTS `tkttype` (
  `idtkttype` int(1) unsigned NOT NULL AUTO_INCREMENT,
  `tkttypename` varchar(10) DEFAULT NULL,
  `tkttypedesc` text,
  PRIMARY KEY (`idtkttype`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `tkttype`
--

INSERT INTO `tkttype` (`idtkttype`, `tkttypename`, `tkttypedesc`) VALUES
(1, 'Private', 'Personal Tickets to do with a Customers House or Office'),
(2, 'Public', 'Reports to do with Public Place eg; Burst Main Pipes'),
(3, 'Kiosk', 'To cater for complaints from the informal sectors where water distribution is done via selling points known as Kiosks');

-- --------------------------------------------------------

--
-- Table structure for table `trans_sms`
--

CREATE TABLE IF NOT EXISTS `trans_sms` (
  `idtrans_sms` bigint(20) NOT NULL AUTO_INCREMENT,
  `tktstatus_idtktstatus` int(2) unsigned NOT NULL,
  `idtktin` bigint(20) NOT NULL,
  `idwftasks` bigint(20) NOT NULL,
  `fbmsg` varchar(160) DEFAULT NULL,
  `admsg` varchar(60) DEFAULT NULL,
  `finmsg` varchar(160) DEFAULT NULL,
  `createdon` datetime DEFAULT NULL,
  `createdby` int(8) unsigned DEFAULT NULL,
  `processedon` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`idtrans_sms`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `userteamzone_alias`
--

CREATE TABLE IF NOT EXISTS `userteamzone_alias` (
  `iduserteamzone_alias` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `usrteamzone_idusrteamzone` int(5) unsigned NOT NULL,
  `userteamzone_aliasname` varchar(150) DEFAULT NULL,
  `createdby` int(8) NOT NULL,
  `createdon` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `validatedby` int(8) NOT NULL DEFAULT '0',
  `validatedon` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`iduserteamzone_alias`),
  UNIQUE KEY `userteamzone_aliasname` (`userteamzone_aliasname`),
  UNIQUE KEY `userteamzone_aliasname_2` (`userteamzone_aliasname`),
  KEY `usrteamzone_idusrteamzone` (`usrteamzone_idusrteamzone`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `usrac`
--

CREATE TABLE IF NOT EXISTS `usrac` (
  `idusrac` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `usrrole_idusrrole` int(8) unsigned DEFAULT NULL,
  `usrteam_idusrteam` int(4) unsigned NOT NULL,
  `usrname` varchar(150) DEFAULT NULL,
  `usrpass` varchar(150) DEFAULT NULL,
  `utitle` varchar(10) DEFAULT NULL,
  `fname` varchar(40) DEFAULT NULL,
  `lname` varchar(40) DEFAULT NULL,
  `usrgender` char(1) NOT NULL DEFAULT '-',
  `acstatus` tinyint(1) unsigned DEFAULT NULL,
  `acstatus_work` tinyint(1) NOT NULL DEFAULT '1',
  `mobileaccess` tinyint(1) unsigned DEFAULT '0',
  `usremail` varchar(80) DEFAULT NULL,
  `usrphone` varchar(30) DEFAULT NULL,
  `usersess` varchar(50) DEFAULT NULL,
  `createdby` int(8) unsigned DEFAULT NULL,
  `createdon` datetime DEFAULT NULL,
  `modifiedby` int(8) unsigned DEFAULT '0',
  `modifiedon` datetime DEFAULT '0000-00-00 00:00:00',
  `lastaccess` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `currentsess` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`idusrac`),
  UNIQUE KEY `unique_role` (`usrrole_idusrrole`),
  KEY `usrrole_idusrrole` (`usrrole_idusrrole`),
  KEY `usrteam_idusrteam` (`usrteam_idusrteam`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `usrac`
--

INSERT INTO `usrac` (`idusrac`, `usrrole_idusrrole`, `usrteam_idusrteam`, `usrname`, `usrpass`, `utitle`, `fname`, `lname`, `usrgender`, `acstatus`, `acstatus_work`, `mobileaccess`, `usremail`, `usrphone`, `usersess`, `createdby`, `createdon`, `modifiedby`, `modifiedon`, `lastaccess`, `currentsess`) VALUES
(1, 1, 1, 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', '', 'Super', 'Admin', '-', 1, 1, 0, 'admin@email.com', '', NULL, 1, '2015-03-23 21:13:15', 1, '2015-05-17 10:58:33', '2015-08-15 13:05:53', 'nanava25csge77jja86u6b6251'),
(2, 2, 0, 'Customer', '923uann239u429jijf092304j3209j09f8092j093', NULL, NULL, NULL, '-', 1, 1, 0, NULL, NULL, NULL, NULL, NULL, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(3, 3, 1, 'ccofficer1', 'dabf5541fc210ff051fbcd29c22f3480bd2f0f9f', '', 'Jane', 'Doe', '-', 1, 1, 0, 'jdoe@email.com', '2547', NULL, 1, '2015-08-15 13:11:57', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(4, 0, 1, 'ccareagent', '43b11e265043dd131a15f0fce85a1d645d52503f', '', 'Jane', 'Doe 2', '-', 1, 1, 0, 'jdoe2@email.com', '2547', NULL, 1, '2015-08-15 13:18:20', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `usraccesscard`
--

CREATE TABLE IF NOT EXISTS `usraccesscard` (
  `idusraccesscard` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `usrteam_idusrteam` int(4) unsigned NOT NULL,
  `datecreated` datetime DEFAULT NULL,
  `validtill` date DEFAULT NULL,
  `cardstatus` tinyint(1) unsigned DEFAULT NULL,
  `createdby` int(8) unsigned DEFAULT NULL,
  `createdon` datetime DEFAULT NULL,
  `modifiedby` int(8) unsigned DEFAULT '0',
  `modifiedon` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`idusraccesscard`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `usraudit`
--

CREATE TABLE IF NOT EXISTS `usraudit` (
  `idusraudit` bigint(20) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`idusraudit`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `usrdpts`
--

CREATE TABLE IF NOT EXISTS `usrdpts` (
  `idusrdpts` int(11) NOT NULL AUTO_INCREMENT,
  `usrdptname` varchar(40) NOT NULL,
  `dptdesc` varchar(250) DEFAULT NULL,
  `usrteam_idusrteam` int(4) NOT NULL,
  PRIMARY KEY (`idusrdpts`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `usrdpts`
--

INSERT INTO `usrdpts` (`idusrdpts`, `usrdptname`, `dptdesc`, `usrteam_idusrteam`) VALUES
(1, 'ICT Department', NULL, 1),
(2, 'Senior Management', NULL, 1),
(3, 'Customer Care', NULL, 1),
(4, 'Security / Legal', NULL, 1),
(5, 'Finance', NULL, 1),
(6, 'HR & Admin', NULL, 1),
(7, 'Procurement', '-', 1),
(8, 'Transport', 'Transport', 1),
(9, 'Manager / Regional Manager', NULL, 1),
(10, 'Technical', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `usrgroup`
--

CREATE TABLE IF NOT EXISTS `usrgroup` (
  `idusrgroup` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `usrteam_idusrteam` int(4) NOT NULL,
  `usrgroupname` varchar(100) DEFAULT NULL,
  `usrgroupdesc` text,
  `createdby` int(8) unsigned DEFAULT NULL,
  `createdon` datetime DEFAULT NULL,
  `modifiedby` int(8) unsigned DEFAULT '0',
  `modifiedon` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`idusrgroup`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `usrjoblvl`
--

CREATE TABLE IF NOT EXISTS `usrjoblvl` (
  `idjoblvl` int(5) NOT NULL,
  `joblvl_lbl` varchar(50) NOT NULL,
  `joblvldesc` varchar(250) NOT NULL,
  `usrteam_idusrteam` int(1) NOT NULL DEFAULT '1',
  UNIQUE KEY `joblvl` (`idjoblvl`,`usrteam_idusrteam`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `usrjoblvl`
--

INSERT INTO `usrjoblvl` (`idjoblvl`, `joblvl_lbl`, `joblvldesc`, `usrteam_idusrteam`) VALUES
(1, 'Default', 'Default Job Level', 1);

-- --------------------------------------------------------

--
-- Table structure for table `usrloginattempts`
--

CREATE TABLE IF NOT EXISTS `usrloginattempts` (
  `idusrloginattempts` bigint(20) NOT NULL AUTO_INCREMENT,
  `usersess` varchar(50) DEFAULT NULL,
  `userip` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`idusrloginattempts`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `usrpwdreminder`
--

CREATE TABLE IF NOT EXISTS `usrpwdreminder` (
  `idusrpwdreminder` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userid` varchar(50) DEFAULT NULL,
  `useremail` varchar(150) DEFAULT NULL,
  `token1` varchar(150) DEFAULT NULL,
  `token2` varchar(150) DEFAULT NULL,
  `token3` varchar(150) DEFAULT NULL,
  `visited_on` datetime DEFAULT '0000-00-00 00:00:00',
  `visited_ip` varchar(50) DEFAULT NULL,
  `visited_uri` varchar(150) DEFAULT NULL,
  `password_reset` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`idusrpwdreminder`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `usrrole`
--

CREATE TABLE IF NOT EXISTS `usrrole` (
  `idusrrole` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `sysprofiles_idsysprofiles` int(8) unsigned NOT NULL,
  `usrteamzone_idusrteamzone` int(5) unsigned NOT NULL,
  `usrrolename` varchar(100) DEFAULT NULL,
  `usrroledesc` text,
  `reportingto` int(8) NOT NULL DEFAULT '0',
  `joblevel` int(3) NOT NULL DEFAULT '0',
  `usrdpts_idusrdpts` int(3) NOT NULL DEFAULT '0',
  `createdby` int(8) unsigned DEFAULT NULL,
  `createdon` datetime DEFAULT NULL,
  `modifiedby` int(8) unsigned DEFAULT NULL,
  `modifiedon` datetime DEFAULT NULL,
  PRIMARY KEY (`idusrrole`),
  UNIQUE KEY `usrteamzone_idusrteamzone` (`usrteamzone_idusrteamzone`,`usrrolename`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `usrrole`
--

INSERT INTO `usrrole` (`idusrrole`, `sysprofiles_idsysprofiles`, `usrteamzone_idusrteamzone`, `usrrolename`, `usrroledesc`, `reportingto`, `joblevel`, `usrdpts_idusrdpts`, `createdby`, `createdon`, `modifiedby`, `modifiedon`) VALUES
(1, 1, 1, 'Super Admin', 'Super Admin has overall control of the Platform', 0, 0, 0, 1, '2012-03-25 20:09:15', 1, '2012-05-17 15:34:27'),
(2, 0, 0, 'Customer ', '-', 0, 0, 0, 1, '2012-04-25 01:00:15', NULL, NULL),
(3, 2, 1, 'Customer Care Officer 1', 'This is a test user role', 0, 1, 3, 1, '2015-08-15 13:09:56', 1, '2015-08-15 13:10:08'),
(4, 2, 1, 'Customer Care Agent 1', '', 1, 1, 3, 1, '2015-08-15 13:17:40', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `usrteam`
--

CREATE TABLE IF NOT EXISTS `usrteam` (
  `idusrteam` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `usrteamgroup_idusrteamgroup` int(3) NOT NULL DEFAULT '0',
  `usrteamtype_idusrteamtype` int(2) unsigned NOT NULL DEFAULT '1',
  `reportto_idusrteam` int(4) NOT NULL DEFAULT '0',
  `usrteamname` varchar(100) DEFAULT NULL,
  `usrteamshortname` varchar(20) NOT NULL,
  `mainlogo_path` varchar(250) DEFAULT NULL,
  `smalllogo_path` varchar(250) DEFAULT NULL,
  `introtxt` text,
  `createdby` int(8) unsigned DEFAULT NULL,
  `createdon` datetime DEFAULT NULL,
  `modifiedby` int(8) unsigned DEFAULT '0',
  `modifiedon` datetime DEFAULT '0000-00-00 00:00:00',
  `currentusersess` varchar(50) DEFAULT NULL,
  `table_billing` varchar(20) DEFAULT NULL,
  `esctimeframe` int(11) NOT NULL DEFAULT '0',
  `escorg1` int(5) NOT NULL DEFAULT '0',
  `escorg2` int(5) NOT NULL DEFAULT '0',
  `escorg3` int(5) NOT NULL DEFAULT '0',
  `acstatus` tinyint(1) NOT NULL,
  PRIMARY KEY (`idusrteam`),
  UNIQUE KEY `usrteamshortname` (`usrteamshortname`),
  UNIQUE KEY `usrteamname` (`usrteamname`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `usrteam`
--

INSERT INTO `usrteam` (`idusrteam`, `usrteamgroup_idusrteamgroup`, `usrteamtype_idusrteamtype`, `reportto_idusrteam`, `usrteamname`, `usrteamshortname`, `mainlogo_path`, `smalllogo_path`, `introtxt`, `createdby`, `createdon`, `modifiedby`, `modifiedon`, `currentusersess`, `table_billing`, `esctimeframe`, `escorg1`, `escorg2`, `escorg3`, `acstatus`) VALUES
(1, 1, 1, 0, 'Demo Company', 'DC', NULL, NULL, NULL, NULL, NULL, 0, '0000-00-00 00:00:00', NULL, NULL, 0, 0, 0, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `usrteamgroup`
--

CREATE TABLE IF NOT EXISTS `usrteamgroup` (
  `idusrteamgroup` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `usrteamgroupname` varchar(20) DEFAULT NULL,
  `groupdesc` varchar(250) DEFAULT NULL,
  `createdby` int(8) unsigned DEFAULT NULL,
  `createdon` datetime DEFAULT NULL,
  PRIMARY KEY (`idusrteamgroup`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `usrteamgroup`
--

INSERT INTO `usrteamgroup` (`idusrteamgroup`, `usrteamgroupname`, `groupdesc`, `createdby`, `createdon`) VALUES
(1, 'Default', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `usrteamtype`
--

CREATE TABLE IF NOT EXISTS `usrteamtype` (
  `idusrteamtype` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `usrteamtypename` varchar(50) DEFAULT NULL,
  `usrteamtypedesc` text,
  PRIMARY KEY (`idusrteamtype`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `usrteamtype`
--

INSERT INTO `usrteamtype` (`idusrteamtype`, `usrteamtypename`, `usrteamtypedesc`) VALUES
(1, 'Default', 'This usually could stand for the company type');

-- --------------------------------------------------------

--
-- Table structure for table `usrteamzone`
--

CREATE TABLE IF NOT EXISTS `usrteamzone` (
  `idusrteamzone` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `usrteam_idusrteam` int(4) unsigned NOT NULL,
  `userteamzonename` varchar(100) DEFAULT NULL,
  `loccountry_idloccountry` int(3) unsigned NOT NULL,
  `locregion_idlocregion` int(3) NOT NULL,
  `loccounty_idloccounty` int(3) NOT NULL,
  `loctowns_idloctowns` int(8) unsigned NOT NULL,
  `teamzonephone` varchar(30) DEFAULT NULL,
  `teamzoneemail` varchar(80) DEFAULT NULL,
  `physicaladdress` varchar(250) DEFAULT NULL,
  `postaladdress` varchar(50) DEFAULT NULL,
  `lat` float(10,6) DEFAULT NULL,
  `lng` float(10,6) DEFAULT NULL,
  `createdby` int(8) unsigned DEFAULT NULL,
  `createdon` datetime DEFAULT NULL,
  `modifiedby` int(8) unsigned DEFAULT NULL,
  `modifiedon` datetime DEFAULT NULL,
  `gmapflag_type` varchar(50) DEFAULT NULL,
  `region_pref` varchar(3) DEFAULT NULL,
  `region_code` int(4) DEFAULT NULL,
  `color_bg` varchar(7) DEFAULT NULL,
  `color_font` varchar(7) DEFAULT NULL,
  PRIMARY KEY (`idusrteamzone`),
  KEY `userteamzonename` (`userteamzonename`),
  KEY `region_pref` (`region_pref`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `usrteamzone`
--

INSERT INTO `usrteamzone` (`idusrteamzone`, `usrteam_idusrteam`, `userteamzonename`, `loccountry_idloccountry`, `locregion_idlocregion`, `loccounty_idloccounty`, `loctowns_idloctowns`, `teamzonephone`, `teamzoneemail`, `physicaladdress`, `postaladdress`, `lat`, `lng`, `createdby`, `createdon`, `modifiedby`, `modifiedon`, `gmapflag_type`, `region_pref`, `region_code`, `color_bg`, `color_font`) VALUES
(1, 1, 'HQ', 1, 12, 75, 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `usrtzoneunit`
--

CREATE TABLE IF NOT EXISTS `usrtzoneunit` (
  `idusrtzoneunit` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `usrtzoneunittype_idusrtzoneunittype` int(10) unsigned NOT NULL,
  `usrteamzone_idusrteamzone` int(5) unsigned NOT NULL,
  `tzunitno` varchar(20) DEFAULT NULL,
  `tzunitname` varchar(20) DEFAULT NULL,
  `tzunitphone` varchar(30) DEFAULT NULL,
  `tzunitemail` varchar(80) DEFAULT NULL,
  `tzphysicaladdr` varchar(250) DEFAULT NULL,
  `tzpostaladdr` varchar(50) DEFAULT NULL,
  `lat` float(10,6) DEFAULT NULL,
  `lng` float(10,6) DEFAULT NULL,
  `createdby` int(8) unsigned DEFAULT NULL,
  `createdon` datetime DEFAULT NULL,
  `modifiedby` int(8) unsigned DEFAULT NULL,
  `modifiedon` datetime DEFAULT NULL,
  PRIMARY KEY (`idusrtzoneunit`),
  UNIQUE KEY `tzunitno` (`tzunitno`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `usrtzoneunit`
--

INSERT INTO `usrtzoneunit` (`idusrtzoneunit`, `usrtzoneunittype_idusrtzoneunittype`, `usrteamzone_idusrteamzone`, `tzunitno`, `tzunitname`, `tzunitphone`, `tzunitemail`, `tzphysicaladdr`, `tzpostaladdr`, `lat`, `lng`, `createdby`, `createdon`, `modifiedby`, `modifiedon`) VALUES
(1, 1, 2, 'A005', 'Wamuthonis', '254725481036', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `usrtzoneunitmsgs`
--

CREATE TABLE IF NOT EXISTS `usrtzoneunitmsgs` (
  `idusrtzoneunitmsgs` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `usrtzoneunit_idusrtzoneunit` int(10) unsigned NOT NULL,
  `usrtzoneunitsubs_idusrtzoneunitsubs` int(10) unsigned NOT NULL,
  `msgbroadcast` varchar(100) DEFAULT NULL,
  `mgscount` int(10) unsigned DEFAULT NULL,
  `timereceived` datetime DEFAULT NULL,
  `timeprocessed` datetime DEFAULT NULL,
  `x` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`idusrtzoneunitmsgs`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `usrtzoneunitsubs`
--

CREATE TABLE IF NOT EXISTS `usrtzoneunitsubs` (
  `idusrtzoneunitsubs` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `usrtzoneunit_idusrtzoneunit` int(10) unsigned NOT NULL,
  `subsphoneno` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`idusrtzoneunitsubs`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `usrtzoneunittype`
--

CREATE TABLE IF NOT EXISTS `usrtzoneunittype` (
  `idusrtzoneunittype` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tzunittypename` varchar(50) DEFAULT NULL,
  `tzunittypedesc` text,
  PRIMARY KEY (`idusrtzoneunittype`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `usrtzoneunittype`
--

INSERT INTO `usrtzoneunittype` (`idusrtzoneunittype`, `tzunittypename`, `tzunittypedesc`) VALUES
(1, 'Kiosk', 'Water disrtibution point in the informal settlement');

-- --------------------------------------------------------

--
-- Table structure for table `wfactors`
--

CREATE TABLE IF NOT EXISTS `wfactors` (
  `idwfactors` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `usrrole_idusrrole` int(8) unsigned NOT NULL DEFAULT '0',
  `usrgroup_idusrgroup` int(5) NOT NULL DEFAULT '0',
  `wftskflow_idwftskflow` int(11) unsigned NOT NULL,
  `usrteamzone_idusrteamzone` int(5) NOT NULL,
  `allow_tskflow_jump` tinyint(4) NOT NULL DEFAULT '0',
  `createdby` int(8) unsigned DEFAULT NULL,
  `createdon` datetime DEFAULT NULL,
  `modifiedby` int(8) unsigned DEFAULT NULL,
  `modifiedon` datetime DEFAULT NULL,
  PRIMARY KEY (`idwfactors`),
  UNIQUE KEY `role_workflowstep` (`usrrole_idusrrole`,`wftskflow_idwftskflow`,`usrteamzone_idusrteamzone`),
  KEY `usrrole_idusrrole` (`usrrole_idusrrole`),
  KEY `usrgroup_idusrgroup` (`usrgroup_idusrgroup`),
  KEY `wftskflow_idwftskflow` (`wftskflow_idwftskflow`),
  KEY `usrteamzone_idusrteamzone` (`usrteamzone_idusrteamzone`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `wfactors`
--

INSERT INTO `wfactors` (`idwfactors`, `usrrole_idusrrole`, `usrgroup_idusrgroup`, `wftskflow_idwftskflow`, `usrteamzone_idusrteamzone`, `allow_tskflow_jump`, `createdby`, `createdon`, `modifiedby`, `modifiedon`) VALUES
(1, 3, 0, 3, 1, 0, 1, '2015-08-15 13:13:56', NULL, NULL),
(2, 3, 0, 4, 1, 0, 1, '2015-08-15 13:18:54', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `wfactorsgroup`
--

CREATE TABLE IF NOT EXISTS `wfactorsgroup` (
  `idwfactorsgroup` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `wfactorsgroupname_idwfactorsgroupname` int(8) unsigned NOT NULL,
  `usrrole_idusrrole` int(8) unsigned NOT NULL,
  `wftskflow_idwftskflow` int(11) unsigned NOT NULL,
  `createdby` int(8) unsigned DEFAULT NULL,
  `createdon` datetime DEFAULT NULL,
  `modifiedby` int(8) unsigned DEFAULT NULL,
  `modifiedon` datetime DEFAULT NULL,
  PRIMARY KEY (`idwfactorsgroup`),
  UNIQUE KEY `usrrole_idusrrole` (`usrrole_idusrrole`,`wftskflow_idwftskflow`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `wfactorsgroupname`
--

CREATE TABLE IF NOT EXISTS `wfactorsgroupname` (
  `idwfactorsgroupname` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `wftskflow_idwftskflow` int(11) unsigned NOT NULL,
  `groupname` varchar(20) DEFAULT NULL,
  `createdon` datetime DEFAULT NULL,
  `createdby` int(8) unsigned DEFAULT NULL,
  `modifiedon` datetime DEFAULT NULL,
  `modifiedby` int(8) unsigned DEFAULT NULL,
  PRIMARY KEY (`idwfactorsgroupname`),
  UNIQUE KEY `wftskflow_idwftskflow` (`wftskflow_idwftskflow`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `wfassetsdata`
--

CREATE TABLE IF NOT EXISTS `wfassetsdata` (
  `idwfassetsdata` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `wfprocassetsaccess_idwfprocassetsaccess` int(11) unsigned NOT NULL,
  `wfprocassetschoice_idwfprocassetschoice` int(8) NOT NULL DEFAULT '0',
  `wfprocassets_idwfprocassets` int(8) NOT NULL,
  `wftasks_idwftasks` int(11) NOT NULL,
  `wftskupdates_idwftskupdates` int(11) NOT NULL DEFAULT '0',
  `value_choice` varchar(250) DEFAULT NULL,
  `value_path` varchar(250) DEFAULT NULL,
  `wftaskstrac_idwftaskstrac` int(11) NOT NULL DEFAULT '0',
  `tktin_idtktin` int(11) NOT NULL DEFAULT '0',
  `createdby` int(11) unsigned DEFAULT NULL,
  `createdon` datetime DEFAULT NULL,
  `modifiedby` int(11) unsigned DEFAULT NULL,
  `modifiedon` datetime DEFAULT NULL,
  PRIMARY KEY (`idwfassetsdata`),
  UNIQUE KEY `wfprocassetsaccess_idwfprocass_2` (`wfprocassetsaccess_idwfprocassetsaccess`,`wfprocassets_idwfprocassets`,`wftasks_idwftasks`,`tktin_idtktin`),
  KEY `wfassetsdata_index15002` (`value_choice`),
  KEY `value_path` (`value_path`),
  KEY `wfprocassetschoice_idwfprocassetschoice` (`wfprocassetschoice_idwfprocassetschoice`),
  KEY `wfprocassets_idwfprocassets` (`wfprocassets_idwfprocassets`),
  KEY `wfprocassetsaccess_idwfprocassetsaccess` (`wfprocassetsaccess_idwfprocassetsaccess`),
  KEY `wftasks_idwftasks` (`wftasks_idwftasks`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `wfdependency`
--

CREATE TABLE IF NOT EXISTS `wfdependency` (
  `idwfdependency` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `wftskstatus_idwftskstatus` int(2) unsigned NOT NULL,
  `wfdependencyname` varchar(30) DEFAULT NULL,
  `dependencydesc` text,
  `createdby` int(8) unsigned DEFAULT NULL,
  `createdon` datetime DEFAULT NULL,
  `modifiedby` int(8) unsigned DEFAULT NULL,
  `modifiedon` datetime DEFAULT NULL,
  PRIMARY KEY (`idwfdependency`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `wfdependencylogs`
--

CREATE TABLE IF NOT EXISTS `wfdependencylogs` (
  `idwfdependencylogs` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `wftasks_idwftasks` bigint(20) NOT NULL,
  `wfdependency_idwfdependency` int(8) unsigned NOT NULL,
  `createdby` int(8) unsigned DEFAULT NULL,
  `createdon` datetime DEFAULT NULL,
  `modifiedby` int(8) unsigned DEFAULT NULL,
  `modifiedon` datetime DEFAULT NULL,
  PRIMARY KEY (`idwfdependencylogs`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `wfescalation`
--

CREATE TABLE IF NOT EXISTS `wfescalation` (
  `idwfescalations` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `usrrole_idusrrole` int(8) unsigned NOT NULL,
  `wftskflow_idwftskflow` int(11) unsigned NOT NULL,
  `leadtime` int(11) unsigned DEFAULT NULL,
  `createdby` int(8) unsigned DEFAULT NULL,
  `createdon` datetime DEFAULT NULL,
  `modifiedby` int(8) unsigned DEFAULT NULL,
  `modifiedon` datetime DEFAULT NULL,
  `limit_to_zone` tinyint(1) NOT NULL DEFAULT '0',
  `time_to_deadline` int(2) NOT NULL DEFAULT '1',
  PRIMARY KEY (`idwfescalations`),
  KEY `usrrole_idusrrole` (`usrrole_idusrrole`),
  KEY `wftskflow_idwftskflow` (`wftskflow_idwftskflow`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `wfescalation_tatlist`
--

CREATE TABLE IF NOT EXISTS `wfescalation_tatlist` (
  `idtatlist` int(2) NOT NULL AUTO_INCREMENT,
  `tat_lbl` varchar(100) NOT NULL,
  `tat_desc` varchar(250) NOT NULL,
  PRIMARY KEY (`idtatlist`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `wfescalation_tatlist`
--

INSERT INTO `wfescalation_tatlist` (`idtatlist`, `tat_lbl`, `tat_desc`) VALUES
(1, 'Time to Deadline TAT (This Step)', 'Trigger the escalation before the turn around time within this Step which is just part of a bigger workflow'),
(2, 'Time to Deadline TAT (Workflow)', 'Trigger the escalation before the turn around time defined at the whole workflow level / workflow details'),
(3, 'Time After Deadline TAT (This Step)', 'Trigger the escalation after the turn around time defined in this Step which is just part of a bigger workflow'),
(4, 'Time After Deadline TAT (Workflow)', 'Trigger the escalation after the turn around time defined in the whole workflow level / workflow details');

-- --------------------------------------------------------

--
-- Table structure for table `wfnotification`
--

CREATE TABLE IF NOT EXISTS `wfnotification` (
  `idwfnotification` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `tktstatus_idtktstatus` int(2) unsigned NOT NULL,
  `usrrole_idusrrole` int(8) unsigned NOT NULL,
  `wftskflow_idwftskflow` int(11) unsigned NOT NULL,
  `notify_system` tinyint(1) unsigned DEFAULT NULL,
  `notify_email` tinyint(1) unsigned DEFAULT NULL,
  `notify_sms` tinyint(1) unsigned DEFAULT NULL,
  `createdby` int(8) unsigned DEFAULT NULL,
  `createdon` datetime DEFAULT NULL,
  `modifiedby` int(10) unsigned DEFAULT NULL,
  `modifiedon` datetime DEFAULT NULL,
  `limit_to_zone` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idwfnotification`),
  KEY `wftskflow_idwftskflow` (`wftskflow_idwftskflow`),
  KEY `usrrole_idusrrole` (`usrrole_idusrrole`),
  KEY `tktstatus_idtktstatus` (`tktstatus_idtktstatus`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `wfproc`
--

CREATE TABLE IF NOT EXISTS `wfproc` (
  `idwfproc` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `usrteam_idusrteam` int(11) NOT NULL,
  `usrteamzone_idusrteamzone` int(5) unsigned NOT NULL,
  `wfprocname` varchar(50) DEFAULT NULL,
  `wfproctat` int(11) unsigned DEFAULT NULL,
  `wfprocdesc` text,
  `mobileaccess` tinyint(1) unsigned DEFAULT '0',
  `wfstatus` tinyint(1) NOT NULL DEFAULT '0',
  `createdby` int(8) unsigned DEFAULT NULL,
  `createdon` datetime DEFAULT NULL,
  `modifiedby` int(8) unsigned DEFAULT '0',
  `modifiedon` datetime DEFAULT '0000-00-00 00:00:00',
  `wftype_idwftype` int(2) NOT NULL DEFAULT '1',
  PRIMARY KEY (`idwfproc`),
  KEY `usrteam_idusrteam` (`usrteam_idusrteam`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `wfproc`
--

INSERT INTO `wfproc` (`idwfproc`, `usrteam_idusrteam`, `usrteamzone_idusrteamzone`, `wfprocname`, `wfproctat`, `wfprocdesc`, `mobileaccess`, `wfstatus`, `createdby`, `createdon`, `modifiedby`, `modifiedon`, `wftype_idwftype`) VALUES
(1, 1, 2, 'ALL_SMS_TICKETS', 86400, 'Ticket is Received from SMS', 1, 1, 3, '2013-05-25 20:48:51', 4, '2013-09-04 21:02:04', 1),
(2, 1, 1, 'General Workflow', 604800, 'Ticket is Received from Customer either via Mobile, Over the Counter or Telephone Call', 1, 1, 1, '2015-08-15 13:13:20', 1, '2015-08-15 13:19:33', 1);

-- --------------------------------------------------------

--
-- Table structure for table `wfprocassets`
--

CREATE TABLE IF NOT EXISTS `wfprocassets` (
  `idwfprocassets` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `wfproc_idwfproc` int(5) unsigned NOT NULL,
  `wfprocforms_idwfprocforms` int(6) NOT NULL DEFAULT '0',
  `wfprocdtype_idwfprocdtype` int(2) unsigned NOT NULL,
  `wfprocassetsgroup_idwfprocassetsgroup` int(5) NOT NULL DEFAULT '0',
  `assetname` varchar(20) NOT NULL,
  `ordering` float(5,2) NOT NULL DEFAULT '0.00',
  `item_position` tinytext NOT NULL,
  `is_calc` tinyint(1) NOT NULL DEFAULT '0',
  `createdon` datetime NOT NULL,
  `createdby` int(11) NOT NULL,
  PRIMARY KEY (`idwfprocassets`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `wfprocassetsaccess`
--

CREATE TABLE IF NOT EXISTS `wfprocassetsaccess` (
  `idwfprocassetsaccess` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `wfprocforms_idwfprocforms` int(11) NOT NULL DEFAULT '0',
  `wfprocassets_idwfprocassets` int(11) unsigned NOT NULL,
  `perm_read` tinyint(1) unsigned DEFAULT '0',
  `perm_write` tinyint(1) unsigned DEFAULT '0',
  `perm_required` tinyint(1) unsigned DEFAULT '0',
  `wftskflow_idwftskflow` int(11) unsigned NOT NULL,
  `createdby` int(11) unsigned DEFAULT NULL,
  `sysprofiles_idsysprofiles` int(11) NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `modifiedby` int(11) unsigned DEFAULT '0',
  `modifiedon` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`idwfprocassetsaccess`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `wfprocassetschoice`
--

CREATE TABLE IF NOT EXISTS `wfprocassetschoice` (
  `idwfprocassetschoice` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `wfprocassets_idwfprocassets` int(11) unsigned NOT NULL,
  `assetchoice` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`idwfprocassetschoice`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `wfprocassetsgroup`
--

CREATE TABLE IF NOT EXISTS `wfprocassetsgroup` (
  `idwfprocassetsgroup` int(5) NOT NULL AUTO_INCREMENT,
  `wfprocassetsgrouplbl` varchar(30) NOT NULL,
  `wfprocforms_idwfprocforms` int(6) NOT NULL,
  `createdon` datetime NOT NULL,
  `createdby` int(11) NOT NULL,
  `modifiedon` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modifiedby` int(11) NOT NULL DEFAULT '0',
  `userteam_owner` int(5) NOT NULL,
  PRIMARY KEY (`idwfprocassetsgroup`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `wfprocassets_calcs`
--

CREATE TABLE IF NOT EXISTS `wfprocassets_calcs` (
  `idwfassetscalcs` int(11) NOT NULL AUTO_INCREMENT,
  `wfassets_var1` int(6) NOT NULL,
  `calc` tinytext NOT NULL,
  `wfassets_var2` int(6) NOT NULL,
  `wfassets_results` int(11) NOT NULL,
  `createdon` datetime NOT NULL,
  `createdby` int(8) NOT NULL,
  PRIMARY KEY (`idwfassetscalcs`),
  UNIQUE KEY `wfassetsdata_results` (`wfassets_results`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `wfprocdtype`
--

CREATE TABLE IF NOT EXISTS `wfprocdtype` (
  `idwfprocdtype` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `procdtype` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`idwfprocdtype`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `wfprocdtype`
--

INSERT INTO `wfprocdtype` (`idwfprocdtype`, `procdtype`) VALUES
(1, 'Text Box'),
(2, 'Menu List'),
(3, 'File Attachment'),
(4, 'CheckBox'),
(5, 'YES / NO'),
(6, 'Date ONLY Picker'),
(7, 'Date/Time Picker'),
(8, 'Text Box (No. Only)'),
(9, 'Approvals'),
(10, 'Text Area');

-- --------------------------------------------------------

--
-- Table structure for table `wfprocdtype_approvals`
--

CREATE TABLE IF NOT EXISTS `wfprocdtype_approvals` (
  `idwfprocdtype_approvals` int(2) NOT NULL AUTO_INCREMENT,
  `wfprocdtype_approvalslbl` varchar(40) NOT NULL,
  PRIMARY KEY (`idwfprocdtype_approvals`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `wfprocdtype_approvals`
--

INSERT INTO `wfprocdtype_approvals` (`idwfprocdtype_approvals`, `wfprocdtype_approvalslbl`) VALUES
(1, 'APPROVED'),
(2, 'APPROVED W/A'),
(3, 'DECLINED');

-- --------------------------------------------------------

--
-- Table structure for table `wfprocforms`
--

CREATE TABLE IF NOT EXISTS `wfprocforms` (
  `idwfprocforms` int(11) NOT NULL AUTO_INCREMENT,
  `syssubmodule_idsyssubmodule` int(4) NOT NULL,
  `form_description` varchar(250) NOT NULL,
  `form_status` tinyint(1) NOT NULL DEFAULT '0',
  `usrteam_idusrteam` int(3) NOT NULL DEFAULT '0',
  `createdon` datetime NOT NULL,
  `createdby` int(11) NOT NULL,
  PRIMARY KEY (`idwfprocforms`),
  UNIQUE KEY `form_name` (`form_description`),
  UNIQUE KEY `syssubmodule_idsyssubmodule` (`syssubmodule_idsyssubmodule`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `wfprocforms_cats`
--

CREATE TABLE IF NOT EXISTS `wfprocforms_cats` (
  `idwfprocforms_cats` int(11) NOT NULL AUTO_INCREMENT,
  `wfprocforms_idwfprocforms` int(5) NOT NULL,
  `tktcategory_idtktcategory` int(3) NOT NULL,
  `createdon` datetime NOT NULL,
  `createdby` int(11) NOT NULL,
  `updatedon` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updatedby` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idwfprocforms_cats`),
  UNIQUE KEY `wfprocforms_idwfprocforms_2` (`wfprocforms_idwfprocforms`,`tktcategory_idtktcategory`),
  KEY `wfprocforms_idwfprocforms` (`wfprocforms_idwfprocforms`,`tktcategory_idtktcategory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `wfsymbol`
--

CREATE TABLE IF NOT EXISTS `wfsymbol` (
  `idwfsymbol` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `wfsymbolname` varchar(40) DEFAULT NULL,
  `wfsymbol_imgpath` varchar(150) DEFAULT NULL,
  `wfsymboldesc` text,
  `list_status` tinyint(1) NOT NULL,
  PRIMARY KEY (`idwfsymbol`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `wfsymbol`
--

INSERT INTO `wfsymbol` (`idwfsymbol`, `wfsymbolname`, `wfsymbol_imgpath`, `wfsymboldesc`, `list_status`) VALUES
(1, 'Start', 'icon_start.gif', 'The process begins here.Defines the time when this process can start', 1),
(2, 'Task / Activity', 'icon_task.gif', 'Assign Users to work on various tasks\r\n', 1),
(3, 'Event (Message)', 'icon_event_msg.gif', 'Set the Message Alerts by eMail, SMS or System Dashboard', 0),
(4, 'Event (Escalation)', 'icon_escalation.gif', 'Settings when a Task is Overdue', 0),
(5, 'Gateway (Exclusive)', 'icon_gateway_xor.gif', 'Decision Point - Chose only one path', 1),
(6, 'Gateway (Parallel)', 'icon_gateway_para.gif', 'Tasks done concurrently - split workflow path', 1),
(7, 'Gateway (Complex)', 'icon_gateway_complex.gif', 'Multiple factors determining how the task will flow', 0),
(8, 'Gateway (Inclusive)', 'icon_gateway_incl.gif', 'Follow one or more paths as long as the necessary conditions have been fulfilled', 0),
(9, 'Gateway (Event-Based)', 'icon_gateway_event.gif', 'An event will trigger the direction the workflow will take', 0),
(10, 'End', 'icon_end.gif', 'Mark the end of the process\r\n', 1);

-- --------------------------------------------------------

--
-- Table structure for table `wftasks`
--

CREATE TABLE IF NOT EXISTS `wftasks` (
  `idwftasks` int(11) NOT NULL AUTO_INCREMENT,
  `wftaskstrac_idwftaskstrac` int(11) NOT NULL,
  `usrrole_idusrrole` int(8) unsigned NOT NULL,
  `wftasks_idwftasks` int(11) NOT NULL DEFAULT '0',
  `wftskflow_idwftskflow` int(11) unsigned NOT NULL,
  `tktin_idtktin` int(11) NOT NULL,
  `usrac_idusrac` int(8) unsigned NOT NULL,
  `wftskstatustypes_idwftskstatustypes` int(2) unsigned NOT NULL DEFAULT '0',
  `wftskstatusglobal_idwftskstatusglobal` int(2) NOT NULL DEFAULT '0',
  `tasksubject` varchar(200) DEFAULT NULL,
  `taskdesc` text,
  `timeinactual` datetime DEFAULT NULL,
  `timeoveralldeadline` datetime DEFAULT NULL,
  `timetatstart` datetime DEFAULT NULL,
  `timedeadline` datetime DEFAULT NULL,
  `timeactiontaken` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `sender_idusrrole` int(8) NOT NULL,
  `sender_idusrac` int(8) NOT NULL,
  `createdon` datetime NOT NULL,
  `createdby` int(8) NOT NULL DEFAULT '0',
  `wfactorsgroup_idwfactorsgroup` int(8) NOT NULL DEFAULT '0',
  `wftasks_batch_idwftasks_batch` int(8) DEFAULT NULL,
  `batch_number` int(11) NOT NULL DEFAULT '0',
  `actedon_idusrrole` int(8) NOT NULL DEFAULT '0',
  `actedon_idusrac` int(8) NOT NULL DEFAULT '0',
  `wftasks_co_idwftasks_co` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idwftasks`),
  KEY `usrrole_idusrrole` (`usrrole_idusrrole`),
  KEY `wftasks_idwftasks` (`wftasks_idwftasks`),
  KEY `wftskflow_idwftskflow` (`wftskflow_idwftskflow`),
  KEY `tktin_idtktin` (`tktin_idtktin`),
  KEY `usrac_idusrac` (`usrac_idusrac`),
  KEY `wftskstatus_idwftskstatus` (`wftskstatustypes_idwftskstatustypes`),
  KEY `wftskstatusglobal_idwftskstatusglobal` (`wftskstatusglobal_idwftskstatusglobal`),
  KEY `wftaskstrac_idwftaskstrac` (`wftaskstrac_idwftaskstrac`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `wftasksdeleg`
--

CREATE TABLE IF NOT EXISTS `wftasksdeleg` (
  `idwftasksdeleg` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `wftasksdeleg_meta_idwftasksdeleg_meta` int(8) unsigned NOT NULL,
  `wftskflow_idwftskflow` int(11) unsigned NOT NULL,
  `time_transaction` datetime DEFAULT NULL,
  `createdby` int(8) unsigned DEFAULT NULL,
  `createdon` datetime DEFAULT NULL,
  `modifiedby` int(8) unsigned DEFAULT '0',
  `modifiedon` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`idwftasksdeleg`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `wftasksdeleg_key`
--

CREATE TABLE IF NOT EXISTS `wftasksdeleg_key` (
  `idwftasksdeleg_key` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `time_requested` datetime DEFAULT NULL,
  `requested_by_idusrac` int(8) unsigned DEFAULT NULL,
  `requested_by_idrole` int(8) NOT NULL,
  `authkey` varchar(8) DEFAULT NULL,
  `use_status` tinyint(1) unsigned DEFAULT '0',
  `time_user` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `used_by` int(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`idwftasksdeleg_key`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `wftasksdeleg_meta`
--

CREATE TABLE IF NOT EXISTS `wftasksdeleg_meta` (
  `idwftasksdeleg_meta` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `idusrrole_from` int(8) unsigned DEFAULT NULL,
  `idusrrole_to` int(8) unsigned DEFAULT NULL,
  `time_request` datetime DEFAULT NULL,
  `authenticate_key` varchar(8) DEFAULT NULL,
  `msg_request` text,
  `deleg_status` tinyint(1) unsigned DEFAULT NULL,
  `time_recall` datetime DEFAULT NULL,
  `msg_recall` text,
  `recall_by_idusrac` int(8) unsigned DEFAULT NULL,
  `recall_by_idrole` int(8) unsigned DEFAULT NULL,
  `createdby` int(8) unsigned DEFAULT NULL,
  `createdon` datetime DEFAULT NULL,
  `modifiedby` int(8) unsigned DEFAULT '0',
  `modifiedon` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`idwftasksdeleg_meta`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `wftaskstrac`
--

CREATE TABLE IF NOT EXISTS `wftaskstrac` (
  `idwftaskstrac` bigint(20) NOT NULL AUTO_INCREMENT,
  `sendernumber` bigint(12) DEFAULT NULL,
  `createdon` datetime NOT NULL,
  `createdby` int(8) NOT NULL,
  PRIMARY KEY (`idwftaskstrac`),
  KEY `sendernumber` (`sendernumber`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `wftaskstrac_approvals`
--

CREATE TABLE IF NOT EXISTS `wftaskstrac_approvals` (
  `idwftasktrac_approvals` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `wftasks_idwftasks` bigint(20) NOT NULL,
  `wftasks_batch_idwftasks_batch` int(11) unsigned NOT NULL,
  `wftskflow_listorder` float(4,2) NOT NULL,
  `value_choice` int(2) unsigned DEFAULT NULL,
  `createdon` datetime DEFAULT NULL,
  `createdby` int(8) unsigned DEFAULT NULL,
  PRIMARY KEY (`idwftasktrac_approvals`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `wftaskstrac_batch`
--

CREATE TABLE IF NOT EXISTS `wftaskstrac_batch` (
  `idwftaskstrac_batch` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `wftasks_batch_idwftasks_batch` int(11) unsigned NOT NULL,
  `from_idusrrole` int(8) unsigned DEFAULT NULL,
  `from_idusrac` int(8) unsigned DEFAULT NULL,
  `to_idusrrole` int(8) unsigned DEFAULT NULL,
  `to_idusrac` int(8) unsigned DEFAULT NULL,
  `batchcomment` text,
  `time_in` datetime DEFAULT NULL,
  `time_out` datetime DEFAULT NULL,
  `wftskstatusglobal_idwftskstatusglobal` int(2) unsigned DEFAULT NULL,
  PRIMARY KEY (`idwftaskstrac_batch`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `wftasks_batch`
--

CREATE TABLE IF NOT EXISTS `wftasks_batch` (
  `idwftasks_batch` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `usrteamzone_idusrteamzone` int(5) unsigned NOT NULL,
  `wftasks_batchtype_idwftasks_batchtype` int(2) unsigned NOT NULL,
  `batch_no` int(4) unsigned zerofill NOT NULL,
  `batch_year` int(4) NOT NULL,
  `batch_month` int(2) unsigned zerofill NOT NULL,
  `batch_no_verbose` varchar(25) NOT NULL,
  `countbatch` int(3) unsigned DEFAULT NULL,
  `createdby` int(8) unsigned DEFAULT NULL,
  `createdon` datetime DEFAULT NULL,
  PRIMARY KEY (`idwftasks_batch`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `wftasks_batchtype`
--

CREATE TABLE IF NOT EXISTS `wftasks_batchtype` (
  `idwftasks_batchtype` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `batchtypelbl` varchar(5) DEFAULT NULL,
  `batchtypedesc` varchar(250) DEFAULT NULL,
  `max_size` int(3) NOT NULL,
  PRIMARY KEY (`idwftasks_batchtype`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `wftasks_batchtype`
--

INSERT INTO `wftasks_batchtype` (`idwftasks_batchtype`, `batchtypelbl`, `batchtypedesc`, `max_size`) VALUES
(1, 'ADJ', 'Adjustments', 30),
(2, 'AT', 'Account Termination', 30);

-- --------------------------------------------------------

--
-- Table structure for table `wftasks_co`
--

CREATE TABLE IF NOT EXISTS `wftasks_co` (
  `idwftasks_co` int(11) NOT NULL AUTO_INCREMENT,
  `idusrrole_acting` int(11) NOT NULL,
  `idusrac_acting` int(11) NOT NULL DEFAULT '0',
  `idusrrole_owner` int(11) NOT NULL,
  `idusrac_owner` int(11) NOT NULL DEFAULT '0',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL,
  `disabled_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `disabled_by` int(11) NOT NULL DEFAULT '0',
  `co_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0=not validated, 1=active, 2=disabled',
  `validation_code` int(5) DEFAULT NULL,
  `validation_code_created` datetime NOT NULL,
  `validation_code_sent` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `validation_code_valattempt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `validation_code_validated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`idwftasks_co`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `wftasks_exceptions`
--

CREATE TABLE IF NOT EXISTS `wftasks_exceptions` (
  `idwftasks_exceptions` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `wftasks_idwftasks` bigint(20) NOT NULL,
  `wftskflow_idwftskflow` int(11) unsigned NOT NULL,
  `idusrrole_from` int(8) NOT NULL,
  `idusrac_from` int(8) NOT NULL,
  `idusrrole_to` int(8) NOT NULL,
  `idusrac_to` int(8) NOT NULL,
  `wfprocassetsaccess_idwfprocassetsaccess` int(5) NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `createdby` int(8) unsigned DEFAULT NULL,
  PRIMARY KEY (`idwftasks_exceptions`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `wftasks_transfers`
--

CREATE TABLE IF NOT EXISTS `wftasks_transfers` (
  `idwftask_transfers` int(11) NOT NULL AUTO_INCREMENT,
  `wftasks_idwftasks` int(11) NOT NULL,
  `usrroleid_from` int(11) NOT NULL,
  `usracid_from` int(11) NOT NULL DEFAULT '0',
  `usrroleid_to` int(11) NOT NULL DEFAULT '0',
  `usracid_to` int(11) NOT NULL DEFAULT '0',
  `createdby_idusrac` int(11) NOT NULL,
  `createdon` datetime NOT NULL,
  `transfer_batch` int(11) NOT NULL,
  PRIMARY KEY (`idwftask_transfers`),
  UNIQUE KEY `wftasks_idwftasks` (`wftasks_idwftasks`,`usrroleid_from`,`usrroleid_to`,`transfer_batch`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `wftasks_transfers_batch`
--

CREATE TABLE IF NOT EXISTS `wftasks_transfers_batch` (
  `idtransferbatch` int(11) NOT NULL AUTO_INCREMENT,
  `createdby_iduser` int(11) NOT NULL,
  `createdon` datetime NOT NULL,
  PRIMARY KEY (`idtransferbatch`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `wftasks_transfers_usrto`
--

CREATE TABLE IF NOT EXISTS `wftasks_transfers_usrto` (
  `idwftasks_transfers_usrto` int(8) NOT NULL AUTO_INCREMENT,
  `wftasks_transfers_batch_idtransferbatch` int(7) NOT NULL,
  `usrroleid_to` int(8) NOT NULL,
  `usracid_to` int(8) NOT NULL,
  `createdon` datetime NOT NULL,
  `createdby` int(7) NOT NULL,
  PRIMARY KEY (`idwftasks_transfers_usrto`),
  UNIQUE KEY `wftasks_transfers_batch_idtransferbatch` (`wftasks_transfers_batch_idtransferbatch`,`usrroleid_to`,`usracid_to`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `wfteamesc`
--

CREATE TABLE IF NOT EXISTS `wfteamesc` (
  `idwfteamesc` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `wfteamesc_idwfteamesc` int(4) unsigned NOT NULL DEFAULT '0',
  `listorder` float(2,2) DEFAULT NULL,
  `teamgroupfrom` int(5) unsigned DEFAULT NULL,
  `teamgroupto` int(5) unsigned DEFAULT NULL,
  `tatoverdue` int(11) unsigned DEFAULT NULL,
  `escfreq` int(11) unsigned DEFAULT NULL,
  `createdby` int(5) unsigned DEFAULT NULL,
  `createdon` datetime DEFAULT NULL,
  PRIMARY KEY (`idwfteamesc`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `wftskflow`
--

CREATE TABLE IF NOT EXISTS `wftskflow` (
  `idwftskflow` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `wfsymbol_idwfsymbol` int(3) unsigned NOT NULL,
  `wfproc_idwfproc` int(5) unsigned NOT NULL,
  `listorder` float(4,2) DEFAULT NULL,
  `wftskflowname` varchar(40) DEFAULT NULL,
  `wftskflowdesc` text,
  `wftsktat` int(11) unsigned DEFAULT '0',
  `expubholidays` tinyint(1) unsigned DEFAULT NULL,
  `createdby` int(8) unsigned DEFAULT NULL,
  `createdon` datetime DEFAULT NULL,
  `modifiedby` int(8) unsigned DEFAULT NULL,
  `modifiedon` datetime DEFAULT NULL,
  `h_pos` char(2) DEFAULT '0',
  `limit_to_zone` tinyint(1) unsigned DEFAULT '0',
  `limit_to_dpt` tinyint(1) NOT NULL DEFAULT '0',
  `group_task_share` tinyint(1) NOT NULL DEFAULT '0',
  `is_milestone` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idwftskflow`),
  UNIQUE KEY `wftskflow_index15010` (`wfproc_idwfproc`,`listorder`,`h_pos`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `wftskflow`
--

INSERT INTO `wftskflow` (`idwftskflow`, `wfsymbol_idwfsymbol`, `wfproc_idwfproc`, `listorder`, `wftskflowname`, `wftskflowdesc`, `wftsktat`, `expubholidays`, `createdby`, `createdon`, `modifiedby`, `modifiedon`, `h_pos`, `limit_to_zone`, `limit_to_dpt`, `group_task_share`, `is_milestone`) VALUES
(1, 1, 1, 0.00, 'Ticket IN', 'Ticket is Received from Customer either via Mobile, Over the Counter or Telephone Call', 3600, 0, 3, '2013-05-25 20:48:51', 3, '2013-05-25 21:30:14', '0', 0, 0, 0, 0),
(2, 1, 2, 0.00, 'Ticket IN', 'Ticket is Received from Customer either via Mobile, Over the Counter or Telephone Call', 0, NULL, 1, '2015-08-15 13:13:20', NULL, NULL, '0', 0, 0, 0, 0),
(3, 2, 2, 3.00, 'Record Complaint', 'Create complaint in the system', 3600, NULL, 1, '2015-08-15 13:13:46', NULL, NULL, '0', 0, 0, 0, 0),
(4, 2, 2, 6.00, 'Review Complaint', 'Review the complaint and Close', 28800, NULL, 1, '2015-08-15 13:18:49', NULL, NULL, '0', 0, 0, 0, 0),
(5, 10, 2, 9.00, 'End', 'Close', 3600, NULL, 1, '2015-08-15 13:19:16', NULL, NULL, '0', 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `wftskflow_gateways`
--

CREATE TABLE IF NOT EXISTS `wftskflow_gateways` (
  `idwftskflow_gateways` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `wftskflow_idwftskflow` int(11) unsigned NOT NULL,
  `wfsymbol_idwfsymbol` int(3) unsigned NOT NULL,
  `gateway_vars` varchar(50) DEFAULT NULL,
  `gateway_type` char(5) NOT NULL,
  `gateway_splitpoint` int(7) NOT NULL,
  `h_pos` char(2) NOT NULL,
  `createdon` datetime DEFAULT NULL,
  `createdby` int(11) unsigned DEFAULT NULL,
  `modifiedon` datetime DEFAULT '0000-00-00 00:00:00',
  `modifiedby` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`idwftskflow_gateways`),
  KEY `h_pos` (`gateway_splitpoint`),
  KEY `gateway_type` (`gateway_type`),
  KEY `gateway_type_2` (`gateway_type`),
  KEY `gateway_splitpoint` (`gateway_splitpoint`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `wftskflow_gateways`
--

INSERT INTO `wftskflow_gateways` (`idwftskflow_gateways`, `wftskflow_idwftskflow`, `wfsymbol_idwfsymbol`, `gateway_vars`, `gateway_type`, `gateway_splitpoint`, `h_pos`, `createdon`, `createdby`, `modifiedon`, `modifiedby`) VALUES
(1, 0, 5, 'Path1', 'SPLIT', 288, '-1', '2015-03-18 13:21:24', 3, '0000-00-00 00:00:00', NULL),
(2, 0, 5, 'Path2', 'SPLIT', 288, '1', '2015-03-18 13:21:24', 3, '0000-00-00 00:00:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `wftskholiday`
--

CREATE TABLE IF NOT EXISTS `wftskholiday` (
  `idwftskholiday` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `wftskholidayname` varchar(40) DEFAULT NULL,
  `wftskholidaydesc` text,
  `wftskholidaydate` datetime DEFAULT NULL,
  PRIMARY KEY (`idwftskholiday`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `wftskholiday`
--

INSERT INTO `wftskholiday` (`idwftskholiday`, `wftskholidayname`, `wftskholidaydesc`, `wftskholidaydate`) VALUES
(1, 'Labour Day', NULL, '2012-05-01 16:59:06'),
(2, 'Madaraka Day', NULL, '2012-06-01 16:59:52'),
(3, 'Idd Ul Fitr ', NULL, '2012-08-20 17:00:42'),
(4, 'Mashujaa Day', NULL, '2012-10-20 17:01:01'),
(5, 'Jamuhuri Day', NULL, '2012-12-12 17:01:29'),
(6, 'Christmas Day', NULL, '2012-12-25 17:01:44'),
(7, 'Boxing Day', NULL, '2012-12-26 17:01:54');

-- --------------------------------------------------------

--
-- Table structure for table `wftskholiddayproc`
--

CREATE TABLE IF NOT EXISTS `wftskholiddayproc` (
  `idwftskholiddayproc` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `wftskholiday_idwftskholiday` int(8) unsigned NOT NULL,
  `wfproc_idwfproc` int(5) unsigned NOT NULL,
  `createdby` int(11) unsigned DEFAULT NULL,
  `createdon` datetime DEFAULT NULL,
  `modifiedby` int(11) unsigned DEFAULT NULL,
  `modifiedon` datetime DEFAULT NULL,
  PRIMARY KEY (`idwftskholiddayproc`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `wftskinvalid`
--

CREATE TABLE IF NOT EXISTS `wftskinvalid` (
  `idwftskinvalid` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `wftskinvalidlist_idwftskinvalidlist` int(5) unsigned NOT NULL,
  `wftasks_idwftasks` bigint(20) NOT NULL,
  `createdby` int(8) unsigned DEFAULT NULL,
  `createdon` datetime DEFAULT NULL,
  PRIMARY KEY (`idwftskinvalid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `wftskinvalidlist`
--

CREATE TABLE IF NOT EXISTS `wftskinvalidlist` (
  `idwftskinvalidlist` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `wfttaskinvalidlistlbl` varchar(60) DEFAULT NULL,
  `invalidsmsmsg` varchar(100) DEFAULT NULL,
  `createdby` int(8) unsigned DEFAULT NULL,
  `createdon` datetime DEFAULT NULL,
  PRIMARY KEY (`idwftskinvalidlist`),
  UNIQUE KEY `wftskinvalidlist_index12274` (`wfttaskinvalidlistlbl`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `wftskinvalidlist`
--

INSERT INTO `wftskinvalidlist` (`idwftskinvalidlist`, `wfttaskinvalidlistlbl`, `invalidsmsmsg`, `createdby`, `createdon`) VALUES
(1, 'Mobile Unreachable', NULL, 1, '2012-05-15 10:44:22'),
(2, 'Incomplete Details', NULL, 3, '2012-05-18 20:52:34'),
(3, 'Wrong Number', NULL, 5, '2012-05-25 22:11:34'),
(4, 'Account Not Found', NULL, 22, '2012-07-09 10:39:37'),
(5, 'MajiVoice Tests', NULL, 4, '2013-06-21 12:11:34');

-- --------------------------------------------------------

--
-- Table structure for table `wftskstatus`
--

CREATE TABLE IF NOT EXISTS `wftskstatus` (
  `idwftskstatus` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `wftskstatustypes_idwftskstatustypes` int(2) unsigned NOT NULL,
  `wftskflow_idwftskflow` int(11) unsigned NOT NULL,
  `wftskstatusname` varchar(30) DEFAULT NULL,
  `createdby` int(8) unsigned DEFAULT NULL,
  `createdon` datetime DEFAULT NULL,
  `modifiedby` int(8) unsigned DEFAULT NULL,
  `modifiedon` datetime DEFAULT NULL,
  PRIMARY KEY (`idwftskstatus`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `wftskstatus`
--

INSERT INTO `wftskstatus` (`idwftskstatus`, `wftskstatustypes_idwftskstatustypes`, `wftskflow_idwftskflow`, `wftskstatusname`, `createdby`, `createdon`, `modifiedby`, `modifiedon`) VALUES
(1, 2, 3, NULL, 1, '2015-08-15 13:14:00', NULL, NULL),
(2, 6, 3, NULL, 1, '2015-08-15 13:14:01', NULL, NULL),
(3, 1, 3, NULL, 1, '2015-08-15 13:14:03', NULL, NULL),
(4, 4, 3, NULL, 1, '2015-08-15 13:14:05', NULL, NULL),
(5, 2, 4, NULL, 1, '2015-08-15 13:18:57', NULL, NULL),
(6, 6, 4, NULL, 1, '2015-08-15 13:18:59', NULL, NULL),
(7, 1, 4, NULL, 1, '2015-08-15 13:19:00', NULL, NULL),
(8, 4, 4, NULL, 1, '2015-08-15 13:19:01', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `wftskstatusglobal`
--

CREATE TABLE IF NOT EXISTS `wftskstatusglobal` (
  `idwftskstatusglobal` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `statusglobal` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`idwftskstatusglobal`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `wftskstatusglobal`
--

INSERT INTO `wftskstatusglobal` (`idwftskstatusglobal`, `statusglobal`) VALUES
(1, 'New'),
(2, 'In Progress'),
(3, 'Out');

-- --------------------------------------------------------

--
-- Table structure for table `wftskstatustypes`
--

CREATE TABLE IF NOT EXISTS `wftskstatustypes` (
  `idwftskstatustypes` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `wftskstatustype` varchar(40) DEFAULT NULL,
  `wftskstatustypedesc` varchar(250) DEFAULT NULL,
  `wftskstatuslbl` varchar(40) NOT NULL,
  `listorder` int(2) NOT NULL,
  `is_visible` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`idwftskstatustypes`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `wftskstatustypes`
--

INSERT INTO `wftskstatustypes` (`idwftskstatustypes`, `wftskstatustype`, `wftskstatustypedesc`, `wftskstatuslbl`, `listorder`, `is_visible`) VALUES
(1, 'Close Ticket / Work', 'The matter has now been resolved. Thank you for reporting', 'Closed Ticket / Work', 3, 1),
(2, 'Done > Pass It On', 'Forwarding work done to supervisor or peer', 'Passed On', 1, 1),
(3, 'Transfer Task', 'Transfering work from one person to another', 'Transferred Task', 4, 0),
(4, 'Invalidate Task', 'Actor to close the ticket prematurely because it is invalid', 'Invalidated Task', 5, 1),
(5, 'Request for Transfer', '', 'Transfer Request', 6, 0),
(6, 'Progress Update', 'Updating the task with details of work done so far', 'In Progress', 2, 1),
(7, 'Escalate to WSB', 'Ability for actors can raise the matter with the Board', 'Escalated to WSB', 7, 1),
(8, 'Assign Task', 'Issuing work from top down', '', 2, 0),
(9, 'Return to Sender', 'Return tasks to sender when erroneously sent', 'Returned to Sender', 9, 0),
(10, 'Retract / Recall Task', 'Recall a Task', 'Retracted / Recalled Task', 10, 0),
(11, 'Allow Tasks Batching', '', 'Task Batching', 13, 0),
(12, 'Allow New Batch Creation', 'Allow creation of new Batch', 'Allow New Batch Creation', 14, 0);

-- --------------------------------------------------------

--
-- Table structure for table `wftskupdates`
--

CREATE TABLE IF NOT EXISTS `wftskupdates` (
  `idwftskupdates` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `wftaskstrac_idwftaskstrac` bigint(20) NOT NULL,
  `usrrole_idusrrole` int(11) NOT NULL,
  `usrac_idusrac` int(11) NOT NULL,
  `wftskstatusglobal_idwftskstatusglobal` int(2) NOT NULL DEFAULT '0',
  `wftskstatustypes_idwftskstatustypes` int(2) NOT NULL DEFAULT '0',
  `wftasks_idwftasks` bigint(20) NOT NULL,
  `wftskupdate` text NOT NULL,
  `createdby` int(8) unsigned DEFAULT NULL,
  `createdon` datetime DEFAULT NULL,
  `wftskupdates_class_idwftskupdates_class` int(2) NOT NULL DEFAULT '0',
  `wftasks_co_idwftasks_co` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idwftskupdates`),
  KEY `wftskstatus_idwftskstatus` (`wftskstatusglobal_idwftskstatusglobal`),
  KEY `wftskstatustypes_idwftskstatustypes` (`wftskstatustypes_idwftskstatustypes`),
  KEY `wftasks_idwftasks` (`wftasks_idwftasks`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=840 ;

--
-- Dumping data for table `wftskupdates`
--

INSERT INTO `wftskupdates` (`idwftskupdates`, `wftaskstrac_idwftaskstrac`, `usrrole_idusrrole`, `usrac_idusrac`, `wftskstatusglobal_idwftskstatusglobal`, `wftskstatustypes_idwftskstatustypes`, `wftasks_idwftasks`, `wftskupdate`, `createdby`, `createdon`, `wftskupdates_class_idwftskupdates_class`, `wftasks_co_idwftasks_co`) VALUES
(1, 1, 899, 777, 2, 2, 1, 'High bill in the month of August', 777, '2014-08-05 14:44:42', 0, 0),
(2, 1, 878, 771, 2, 6, 2, 'i am working on this but we are still waiting for pipes from the inventory', 771, '2014-08-05 14:55:15', 0, 0),
(3, 2, 899, 777, 2, 2, 3, 'Please deal', 777, '2014-08-05 14:55:55', 0, 0),
(4, 1, 878, 771, 3, 1, 2, 'this has complaint has been successfull resolved', 771, '2014-08-05 14:57:06', 0, 0),
(5, 3, 899, 777, 2, 2, 5, 'P_lease check with billing department', 777, '2014-08-05 14:57:34', 0, 0),
(6, 4, 899, 777, 2, 2, 7, 'Please check with billing department.', 777, '2014-08-05 15:01:01', 0, 0),
(7, 5, 900, 778, 2, 2, 9, 'Please Act', 778, '2014-08-06 09:09:47', 0, 0),
(8, 6, 900, 778, 2, 2, 11, 'Please forward for action', 778, '2014-08-06 09:26:38', 0, 0),
(9, 7, 900, 778, 2, 2, 13, 'Please forwar dofr action', 778, '2014-08-06 09:29:23', 0, 0),
(10, 8, 900, 778, 2, 2, 15, 'Please have a look', 778, '2014-08-06 09:31:09', 0, 0),
(11, 9, 874, 767, 2, 2, 17, 'Please deal', 767, '2014-08-06 10:44:50', 0, 0),
(12, 8, 874, 767, 2, 2, 16, 'Please deal', 767, '2014-08-06 11:57:51', 0, 0),
(13, 7, 874, 767, 2, 6, 14, 'Do not have transport to visit the site.', 767, '2014-08-06 11:59:18', 0, 0),
(14, 7, 874, 767, 2, 6, 14, 'Do not have transport to visit the site.', 767, '2014-08-06 11:59:19', 0, 0),
(15, 11, 899, 777, 2, 2, 21, 'investigate on the meter readings', 777, '2014-08-06 12:23:02', 0, 0),
(16, 12, 902, 780, 2, 2, 23, 'please deal', 780, '2014-08-06 12:38:36', 0, 0),
(17, 13, 900, 778, 2, 2, 25, 'check on the meter reading for he month of July14', 778, '2014-08-06 12:44:15', 0, 0),
(18, 14, 912, 791, 2, 2, 27, 'PLEASE DEAL', 791, '2014-08-06 12:48:19', 0, 0),
(19, 15, 902, 780, 2, 2, 29, 'PLIZ FOR YOUR ACTION', 780, '2014-08-06 12:59:10', 0, 0),
(20, 16, 874, 767, 2, 2, 31, 'please deal', 767, '2014-08-06 13:06:59', 0, 0),
(21, 17, 915, 792, 2, 2, 33, 'please deal', 792, '2014-08-06 13:10:27', 0, 0),
(22, 6, 874, 767, 2, 2, 12, 'Please deal', 767, '2014-08-06 13:19:28', 0, 0),
(23, 18, 900, 778, 2, 2, 36, 'Please assist. The customer ac is ok', 778, '2014-08-06 13:42:35', 0, 0),
(24, 19, 899, 777, 2, 2, 38, 'Deal with this', 777, '2014-08-06 14:42:53', 0, 0),
(25, 7, 874, 767, 2, 2, 14, 'Please deal', 767, '2014-08-06 14:50:30', 0, 0),
(26, 19, 908, 787, 2, 2, 39, 'please deal', 787, '2014-08-06 14:55:10', 0, 0),
(27, 7, 912, 791, 2, 2, 40, 'For your action', 791, '2014-08-06 14:56:58', 0, 0),
(28, 18, 908, 787, 2, 2, 37, 'please deal', 787, '2014-08-06 14:59:30', 0, 0),
(29, 19, 906, 784, 2, 6, 41, 'I have already requested for transport but not yet provided. please ask finance', 784, '2014-08-06 15:00:02', 0, 0),
(30, 19, 906, 784, 2, 2, 41, 'forward to zonal manager', 784, '2014-08-06 15:01:38', 0, 0),
(31, 15, 908, 787, 2, 2, 30, 'please deal', 787, '2014-08-06 15:04:36', 0, 0),
(32, 19, 912, 791, 2, 6, 44, 'GATE LOCKED', 791, '2014-08-06 15:04:55', 0, 0),
(33, 17, 908, 787, 2, 2, 34, 'please deal', 787, '2014-08-06 15:05:56', 0, 0),
(34, 7, 912, 791, 2, 6, 42, 'SHORTAGE OF PROTECTIVE CLOTHING', 791, '2014-08-06 15:07:11', 0, 0),
(35, 16, 908, 787, 2, 2, 32, 'please deal', 787, '2014-08-06 15:07:31', 0, 0),
(36, 6, 912, 791, 2, 6, 35, 'LACK OF TRANSPORTATION', 791, '2014-08-06 15:09:30', 0, 0),
(37, 11, 908, 787, 2, 2, 22, 'please deal', 787, '2014-08-06 15:10:34', 0, 0),
(38, 15, 906, 784, 2, 2, 45, 'correct  readings are 1721', 784, '2014-08-06 15:12:41', 0, 0),
(39, 7, 912, 791, 2, 6, 42, 'PROCUREMENT OF EQUIPMENT STILL UNDERWAY', 791, '2014-08-06 15:12:51', 0, 0),
(40, 12, 908, 787, 2, 2, 24, 'please deal', 787, '2014-08-06 15:13:48', 0, 0),
(41, 9, 908, 787, 2, 2, 18, 'please deal', 787, '2014-08-06 15:16:05', 0, 0),
(42, 19, 912, 791, 2, 2, 44, 'PLEASE DEAL', 791, '2014-08-06 15:17:18', 0, 0),
(43, 5, 908, 787, 2, 2, 10, 'please deal', 787, '2014-08-06 15:17:42', 0, 0),
(44, 17, 906, 784, 2, 6, 46, 'gate locked', 784, '2014-08-06 15:22:32', 0, 0),
(45, 20, 912, 791, 2, 2, 54, 'Please deal', 791, '2014-08-06 15:54:41', 0, 0),
(46, 21, 912, 791, 2, 2, 56, 'check meter', 791, '2014-08-06 16:10:15', 0, 0),
(47, 22, 915, 792, 2, 2, 58, 'please deal', 792, '2014-08-06 16:19:49', 0, 0),
(48, 22, 874, 767, 2, 2, 59, 'Please deal', 767, '2014-08-06 16:21:25', 0, 0),
(49, 23, 906, 784, 2, 2, 61, 'gate locked', 784, '2014-08-06 16:24:19', 0, 0),
(50, 24, 915, 792, 2, 2, 63, 'please deal', 792, '2014-08-06 16:35:56', 0, 0),
(51, 20, 899, 777, 2, 3, 55, 'please deal', 777, '2014-08-06 16:38:18', 0, 0),
(52, 21, 899, 777, 2, 3, 57, 'please deal', 777, '2014-08-06 16:38:18', 0, 0),
(53, 24, 874, 767, 2, 2, 64, 'Please deal', 767, '2014-08-06 16:39:42', 0, 0),
(54, 21, 902, 780, 2, 6, 57, 'procurement of the required equipment is still underway', 780, '2014-08-06 16:40:45', 0, 0),
(55, 21, 902, 780, 2, 2, 57, 'please deal', 780, '2014-08-06 16:42:08', 0, 0),
(56, 17, 906, 784, 2, 2, 46, 'to be done immediately', 784, '2014-08-06 16:45:08', 0, 0),
(57, 20, 902, 780, 2, 2, 55, 'tttttttt', 780, '2014-08-06 16:47:10', 0, 0),
(58, 25, 899, 777, 2, 2, 69, 'Please deal', 777, '2014-08-07 10:39:04', 0, 0),
(59, 20, 899, 777, 2, 2, 68, 'Please Deal', 777, '2014-08-07 11:50:53', 0, 0),
(60, 26, 929, 805, 2, 2, 72, 'please deal', 805, '2014-08-07 12:29:00', 0, 0),
(61, 27, 928, 804, 2, 2, 74, 'Meter has never been read since the account was opened', 804, '2014-08-07 12:32:10', 0, 0),
(62, 28, 922, 799, 2, 2, 76, 'Please repair the meter connection', 799, '2014-08-07 12:33:31', 0, 0),
(63, 29, 920, 797, 2, 2, 78, 'please deal', 797, '2014-08-07 12:33:50', 0, 0),
(64, 30, 928, 804, 2, 2, 80, 'Burst near Stima Line', 804, '2014-08-07 12:38:09', 0, 0),
(65, 31, 899, 777, 2, 2, 82, 'please confirm meter readings', 777, '2014-08-07 12:41:15', 0, 0),
(66, 32, 900, 778, 2, 2, 84, 'Please deal', 778, '2014-08-07 12:41:40', 0, 0),
(67, 33, 929, 805, 2, 2, 86, 'please deal', 805, '2014-08-07 12:43:56', 0, 0),
(68, 34, 899, 777, 2, 2, 88, 'please handle', 777, '2014-08-07 12:44:52', 0, 0),
(69, 35, 922, 799, 2, 2, 90, 'please bill according to meter readings', 799, '2014-08-07 12:46:37', 0, 0),
(70, 35, 916, 793, 2, 2, 91, 'visit site and confirm meter readings', 793, '2014-08-07 12:52:02', 0, 0),
(71, 36, 929, 805, 2, 2, 93, 'confirm readings', 805, '2014-08-07 12:52:10', 0, 0),
(72, 37, 920, 797, 2, 2, 95, 'PLSE DEAL', 797, '2014-08-07 12:52:35', 0, 0),
(73, 38, 900, 778, 2, 2, 97, 'Please deal', 778, '2014-08-07 12:52:51', 0, 0),
(74, 30, 922, 799, 2, 2, 81, 'please repair urgently', 799, '2014-08-07 12:53:44', 0, 0),
(75, 29, 916, 793, 2, 6, 79, 'transport assistant please provide transport to facilitate site visit', 793, '2014-08-07 12:54:41', 0, 0),
(76, 26, 916, 793, 2, 2, 73, 'enhance water rationing program and ensure adequate water availability during scheduled periods of supply', 793, '2014-08-07 13:02:29', 0, 0),
(77, 27, 929, 805, 2, 2, 75, 'visit and provide the readings', 805, '2014-08-07 13:04:24', 0, 0),
(78, 39, 900, 778, 2, 2, 102, 'please deal', 778, '2014-08-07 13:04:27', 0, 0),
(79, 38, 874, 767, 2, 2, 98, 'Please send your team to site', 767, '2014-08-07 13:04:40', 0, 0),
(80, 20, 920, 797, 2, 2, 71, 'PLEASE SEND YOUR TEAM ON SITE', 797, '2014-08-07 13:05:17', 0, 0),
(81, 37, 928, 804, 2, 2, 96, 'please deal', 804, '2014-08-07 13:05:29', 0, 0),
(82, 40, 900, 778, 2, 2, 107, 'Please deal', 778, '2014-08-07 13:06:04', 0, 0),
(83, 29, 916, 793, 2, 2, 79, 'arrange and attend to the leakage using KAZ 226R today at 2pm Contact the driver', 793, '2014-08-07 13:06:54', 0, 0),
(84, 39, 874, 767, 2, 6, 103, 'We do not have protective clothing in the store. Please advice on requistion from store', 767, '2014-08-07 13:08:05', 0, 0),
(85, 39, 874, 767, 3, 1, 103, 'Complaint has been resolved.', 767, '2014-08-07 13:11:34', 0, 0),
(86, 35, 929, 805, 2, 6, 92, 'Gate locked never available', 805, '2014-08-07 13:13:55', 0, 0),
(87, 35, 929, 805, 2, 6, 92, 'gate locked no access', 805, '2014-08-07 13:15:59', 0, 0),
(88, 20, 920, 797, 2, 6, 105, 'our car has broken down we will go to the site as soon as it is fixed tomorrow', 797, '2014-08-07 13:20:51', 0, 0),
(89, 41, 874, 767, 2, 2, 110, 'Please deal. Customer resides in informal settlements', 767, '2014-08-07 13:21:07', 0, 0),
(90, 32, 928, 804, 2, 6, 85, 'waiting for the meter reader to get the meter reading.', 804, '2014-08-07 13:21:26', 0, 0),
(91, 37, 929, 805, 2, 6, 106, 'readings confirmed to be actual consumption', 805, '2014-08-07 13:22:11', 0, 0),
(92, 20, 920, 797, 3, 1, 105, 'work is done', 797, '2014-08-07 13:22:51', 0, 0),
(93, 37, 929, 805, 3, 1, 106, 'correct bill', 805, '2014-08-07 13:23:47', 0, 0),
(94, 32, 928, 804, 2, 6, 85, 'Am trying to reach the customer but at the moment he is not available.', 804, '2014-08-07 13:26:11', 0, 0),
(95, 41, 930, 807, 2, 2, 111, 'pls rectify the readings', 807, '2014-08-07 13:30:09', 0, 0),
(96, 32, 928, 804, 3, 1, 85, 'The matter has been resolved', 804, '2014-08-07 13:30:14', 0, 0),
(97, 42, 916, 793, 2, 2, 113, 'visit site and confirm disposal system in use', 793, '2014-08-07 14:18:09', 0, 0),
(98, 44, 928, 804, 2, 2, 116, 'Please assign the area meter reader to confirm the current meter readings.', 804, '2014-08-07 15:03:55', 0, 0),
(99, 45, 929, 805, 2, 2, 118, 'confirm meter readings', 805, '2014-08-07 15:06:51', 0, 0),
(100, 44, 929, 805, 2, 6, 117, 'gate locked cant access', 805, '2014-08-07 15:14:43', 0, 0),
(101, 45, 928, 804, 2, 2, 119, 'Please assign the area meter reader to confirm the current meter reading.', 804, '2014-08-07 15:16:13', 0, 0),
(102, 46, 921, 798, 2, 2, 121, 'attend urgently', 798, '2014-08-07 15:17:09', 0, 0),
(103, 44, 929, 805, 3, 1, 117, 'customer to bring readings to the Hq for correct billing', 805, '2014-08-07 15:21:43', 0, 0),
(104, 26, 921, 798, 2, 2, 100, 'please deal', 798, '2014-08-07 15:24:21', 0, 0),
(105, 47, 917, 794, 2, 2, 124, 'please deal urgently', 794, '2014-08-07 15:24:47', 0, 0),
(106, 45, 929, 805, 2, 2, 120, 'please deal', 805, '2014-08-07 15:26:37', 0, 0),
(107, 48, 922, 799, 2, 2, 127, 'urgently repair the pipe burst', 799, '2014-08-07 15:28:37', 0, 0),
(108, 45, 921, 798, 2, 6, 126, 'work still in progress', 798, '2014-08-07 15:29:19', 0, 0),
(109, 49, 899, 777, 2, 2, 129, 'Please send this to the technical team for investigation.', 777, '2014-08-07 15:31:11', 0, 0),
(110, 35, 929, 805, 3, 4, 92, 'task closed', 805, '2014-08-07 15:31:21', 0, 0),
(111, 45, 921, 798, 3, 1, 126, 'work completed', 798, '2014-08-07 15:32:55', 0, 0),
(112, 49, 928, 804, 2, 6, 130, 'Currently there is no means of transport but i have been told by the Transport Manager to wait for the next 4 hours.', 804, '2014-08-07 15:36:02', 0, 0),
(113, 49, 928, 804, 3, 1, 130, 'The complaint has been resolved.', 804, '2014-08-07 15:37:58', 0, 0),
(114, 50, 929, 805, 2, 2, 131, 'please deal', 805, '2014-08-07 15:38:20', 0, 0),
(115, 51, 899, 777, 2, 2, 133, 'Please send the technical team to investigate', 777, '2014-08-07 15:39:57', 0, 0),
(116, 51, 921, 798, 3, 4, 134, 'reading was verified to be correct as per the bill', 798, '2014-08-07 15:44:54', 0, 0),
(117, 50, 922, 799, 2, 6, 132, 'follow up with the transport manager', 799, '2014-08-07 15:44:59', 0, 0),
(118, 52, 874, 767, 2, 2, 135, 'Please deal', 767, '2014-08-07 15:47:35', 0, 0),
(119, 40, 922, 799, 3, 1, 108, 'Bill adjusted', 799, '2014-08-07 15:50:02', 0, 0),
(120, 52, 928, 804, 3, 4, 136, 'There is no complain to resolve because the customers account details are okay and he has confirmed the same.', 804, '2014-08-07 15:52:34', 0, 0),
(121, 38, 899, 777, 2, 3, 104, 'please work on this asap', 777, '2014-08-07 15:56:28', 0, 0),
(122, 53, 874, 767, 2, 2, 137, 'The bill is high please start adjustment process.', 767, '2014-08-08 10:14:54', 0, 0),
(123, 54, 931, 806, 2, 2, 139, 'Please correct reading', 806, '2014-08-08 10:48:54', 0, 0),
(124, 55, 931, 806, 2, 2, 141, 'Please check the burst', 806, '2014-08-08 10:50:55', 0, 0),
(125, 55, 874, 767, 2, 2, 142, 'Please attend to the leakage next to state house.', 767, '2014-08-08 11:09:09', 0, 0),
(126, 54, 874, 767, 2, 6, 140, 'Could not access the premises. Gate was locked.', 767, '2014-08-08 11:12:58', 0, 0),
(127, 54, 874, 767, 2, 2, 140, 'Correct readings has been availed. Please adjust', 767, '2014-08-08 11:15:15', 0, 0),
(128, 56, 938, 814, 2, 2, 145, 'Adjust bill', 814, '2014-08-08 11:54:56', 0, 0),
(129, 57, 933, 809, 2, 2, 147, 'NO WATER AVAILABLE FOR TWO DAYS', 809, '2014-08-08 11:59:02', 0, 0),
(130, 58, 926, 815, 2, 2, 149, 'repair leakage', 815, '2014-08-08 11:59:07', 0, 0),
(131, 59, 939, 817, 2, 2, 151, 'leakage', 817, '2014-08-08 12:00:36', 0, 0),
(132, 60, 942, 818, 2, 2, 153, 'harsh dog', 818, '2014-08-08 12:01:00', 0, 0),
(133, 61, 949, 823, 2, 2, 155, 'check the readings if they are okey.', 823, '2014-08-08 12:03:38', 0, 0),
(134, 62, 938, 814, 2, 2, 157, 'Attend immediately', 814, '2014-08-08 12:09:09', 0, 0),
(135, 63, 942, 818, 2, 2, 159, 'please deal urgently', 818, '2014-08-08 12:09:15', 0, 0),
(136, 64, 899, 777, 2, 2, 161, 'Please deal. This customer sounds angry', 777, '2014-08-08 12:11:43', 0, 0),
(137, 65, 933, 809, 2, 2, 163, 'water burst inside a plot', 809, '2014-08-08 12:12:02', 0, 0),
(138, 66, 926, 815, 2, 2, 165, 'repair the leak', 815, '2014-08-08 12:13:02', 0, 0),
(139, 67, 949, 823, 2, 2, 167, 'confirm readings', 823, '2014-08-08 12:15:46', 0, 0),
(140, 68, 939, 817, 2, 2, 169, 'take action', 817, '2014-08-08 12:16:23', 0, 0),
(141, 64, 874, 767, 2, 2, 162, 'Please check with billing office why bills have not been sent to this customer.', 767, '2014-08-08 12:32:24', 0, 0),
(142, 69, 931, 806, 2, 2, 172, 'Please check', 806, '2014-08-08 12:32:53', 0, 0),
(143, 70, 874, 767, 2, 2, 174, 'Please deal', 767, '2014-08-08 12:34:20', 0, 0),
(144, 61, 939, 817, 2, 2, 156, 'peruse', 817, '2014-08-08 12:34:35', 0, 0),
(145, 54, 938, 814, 2, 2, 144, 'Please adjust the bill according to the readings', 814, '2014-08-08 12:35:15', 0, 0),
(146, 68, 935, 811, 2, 2, 170, 'no water for two days', 811, '2014-08-08 12:36:19', 0, 0),
(147, 71, 899, 777, 2, 2, 179, 'Plezse send your team to the site', 777, '2014-08-08 12:38:58', 0, 0),
(148, 70, 949, 823, 2, 2, 175, 'the readings are okey', 823, '2014-08-08 12:39:03', 0, 0),
(149, 56, 926, 815, 2, 2, 146, 'work on it', 815, '2014-08-08 12:40:11', 0, 0),
(150, 64, 942, 818, 2, 2, 171, 'billing system is down', 818, '2014-08-08 12:42:44', 0, 0),
(151, 71, 874, 767, 2, 6, 180, 'No transport available to send my team to site. I will have to wait two days.', 767, '2014-08-08 12:43:39', 0, 0),
(152, 65, 935, 811, 2, 2, 164, 'check burst', 811, '2014-08-08 12:45:01', 0, 0),
(153, 64, 938, 814, 2, 6, 183, 'The billing system is down. please wait for one more  day', 814, '2014-08-08 12:45:27', 0, 0),
(154, 54, 949, 823, 2, 6, 177, 'the bill is being worked on by mr kamau', 823, '2014-08-08 12:46:13', 0, 0),
(155, 72, 874, 767, 2, 2, 185, 'Please send your meter reader to site to check on the problem', 767, '2014-08-08 12:46:13', 0, 0),
(156, 65, 942, 818, 2, 6, 184, 'sent the plumber already', 818, '2014-08-08 12:47:09', 0, 0),
(157, 60, 926, 815, 2, 6, 154, 'it will be done today', 815, '2014-08-08 12:47:47', 0, 0),
(158, 64, 938, 814, 2, 2, 183, 'Billing system is down. please wait for one more day.', 814, '2014-08-08 12:47:48', 0, 0),
(159, 70, 939, 817, 2, 6, 181, 'bill okey', 817, '2014-08-08 12:48:43', 0, 0),
(160, 72, 942, 818, 2, 6, 186, 'gatelocked', 818, '2014-08-08 12:50:07', 0, 0),
(161, 69, 926, 815, 2, 6, 173, 'men at work', 815, '2014-08-08 12:51:02', 0, 0),
(162, 71, 874, 767, 3, 1, 180, 'My team has resolved the matter. Leakage has been fixed', 767, '2014-08-08 12:56:40', 0, 0),
(163, 72, 942, 818, 3, 1, 186, 'the meter has been read and rectified', 818, '2014-08-08 12:57:28', 0, 0),
(164, 55, 935, 811, 3, 1, 143, 'my team have repaired the broken pipe', 811, '2014-08-08 12:58:21', 0, 0),
(165, 70, 939, 817, 3, 1, 181, 'my team  has finished the work', 817, '2014-08-08 12:58:27', 0, 0),
(166, 60, 926, 815, 3, 1, 154, 'plumbers are already on the ground', 815, '2014-08-08 12:59:16', 0, 0),
(167, 54, 949, 823, 3, 1, 177, 'the bill adjusted', 823, '2014-08-08 13:02:03', 0, 0),
(168, 73, 874, 767, 2, 2, 188, 'Please deal', 767, '2014-08-08 13:08:30', 0, 0),
(169, 74, 926, 815, 2, 2, 190, 'please adjust the bill', 815, '2014-08-08 14:36:05', 0, 0),
(170, 75, 938, 814, 2, 2, 192, 'Kindly attend to the leakage', 814, '2014-08-08 14:36:14', 0, 0),
(171, 76, 935, 811, 2, 2, 194, 'check on water being stolen along kabatini road and repair the burst', 811, '2014-08-08 14:41:52', 0, 0),
(172, 77, 942, 818, 2, 2, 196, 'please attend to the leakage', 818, '2014-08-08 14:43:20', 0, 0),
(173, 78, 926, 815, 2, 2, 198, 'am sending an artisan immeadietly', 815, '2014-08-08 14:43:39', 0, 0),
(174, 79, 938, 814, 2, 2, 200, 'Kindly confirm readings', 814, '2014-08-08 14:45:53', 0, 0),
(175, 80, 949, 823, 2, 2, 202, 'customer didnt get a bill  please assist', 823, '2014-08-08 14:48:54', 0, 0),
(176, 81, 935, 811, 2, 2, 204, 'check on reported power failure at mwariki', 811, '2014-08-08 14:49:13', 0, 0),
(177, 80, 938, 814, 2, 2, 203, 'Kindly correct the bill', 814, '2014-08-08 15:09:33', 0, 0),
(178, 73, 942, 818, 2, 2, 189, 'pls deal', 818, '2014-08-08 15:09:38', 0, 0),
(179, 75, 935, 811, 2, 6, 193, 'vehicle broken down', 811, '2014-08-08 15:11:16', 0, 0),
(180, 69, 926, 815, 2, 2, 173, 'BILL IS OK', 815, '2014-08-08 15:11:24', 0, 0),
(181, 77, 949, 823, 2, 6, 197, 'not completed due to lack of material', 823, '2014-08-08 15:12:39', 0, 0),
(182, 67, 935, 811, 2, 2, 168, 'please deal', 811, '2014-08-08 15:12:43', 0, 0),
(183, 65, 942, 818, 2, 6, 184, 'will install the meters immediately', 818, '2014-08-08 15:13:28', 0, 0),
(184, 57, 935, 811, 2, 2, 148, 'attend', 811, '2014-08-08 15:13:37', 0, 0),
(185, 82, 938, 814, 2, 2, 211, 'Kindly attend to the leakage', 814, '2014-08-08 15:14:16', 0, 0),
(186, 69, 942, 818, 3, 1, 208, 'meters installed', 818, '2014-08-08 15:15:32', 0, 0),
(187, 83, 938, 814, 2, 2, 213, 'Kindly adjust', 814, '2014-08-08 15:18:32', 0, 0),
(188, 77, 949, 823, 2, 6, 197, 'provide the material', 823, '2014-08-08 15:18:38', 0, 0),
(189, 57, 939, 817, 2, 2, 210, 'report progress', 817, '2014-08-08 15:19:21', 0, 0),
(190, 77, 949, 823, 2, 6, 197, 'leakage attended', 823, '2014-08-08 15:20:06', 0, 0),
(191, 65, 942, 818, 3, 4, 184, 'already disconnected', 818, '2014-08-08 15:20:20', 0, 0),
(192, 83, 926, 815, 2, 6, 214, 'THE WORK WILL BE DONE TOMMOROW', 815, '2014-08-08 15:23:22', 0, 0),
(193, 67, 939, 817, 3, 1, 209, 'dealt with', 817, '2014-08-08 15:23:56', 0, 0),
(194, 75, 935, 811, 3, 4, 193, 'still waiting for access to the customers premises', 811, '2014-08-08 15:23:57', 0, 0),
(195, 68, 949, 823, 2, 6, 178, 'please attend', 823, '2014-08-08 15:27:44', 0, 0),
(196, 83, 926, 815, 3, 4, 214, 'GOOD WORK', 815, '2014-08-08 15:27:46', 0, 0),
(197, 84, 933, 809, 2, 2, 216, 'repair water leakage from a reported tap', 809, '2014-08-08 15:29:54', 0, 0),
(198, 86, 874, 767, 2, 2, 219, 'Please send meter reader to confirm the readings', 767, '2014-08-11 11:17:30', 0, 0),
(199, 87, 931, 806, 2, 2, 221, 'Please check readings', 806, '2014-08-11 11:34:16', 0, 0),
(200, 88, 931, 806, 2, 2, 223, 'Please allocate a plumber to attend', 806, '2014-08-11 11:36:08', 0, 0),
(201, 88, 874, 767, 2, 2, 224, 'Please go to the location stated and fix the problem', 767, '2014-08-11 12:08:45', 0, 0),
(202, 87, 874, 767, 2, 6, 222, 'Tried to access the premises but gate locked.', 767, '2014-08-11 12:11:31', 0, 0),
(203, 87, 874, 767, 2, 2, 222, 'Readings are 40 units. Please post', 767, '2014-08-11 12:13:33', 0, 0),
(204, 89, 950, 825, 2, 2, 227, 'wrong meter reading', 825, '2014-08-11 12:49:22', 0, 0),
(205, 90, 874, 767, 2, 2, 229, 'Please deal', 767, '2014-08-11 12:53:10', 0, 0),
(206, 91, 953, 827, 2, 2, 231, 'please deal', 827, '2014-08-11 12:53:59', 0, 0),
(207, 92, 962, 835, 2, 2, 233, 'Please check', 835, '2014-08-11 12:54:26', 0, 0),
(208, 93, 911, 790, 2, 2, 235, 'confirm correct readings', 790, '2014-08-11 12:56:12', 0, 0),
(209, 94, 951, 826, 2, 2, 237, 'Please deal', 826, '2014-08-11 12:56:22', 0, 0),
(210, 95, 911, 790, 2, 2, 239, 'please check', 790, '2014-08-11 13:00:58', 0, 0),
(211, 96, 950, 825, 2, 2, 241, 'water leak', 825, '2014-08-11 13:01:55', 0, 0),
(212, 97, 874, 767, 2, 2, 243, 'Please send your team', 767, '2014-08-11 13:12:18', 0, 0),
(213, 97, 950, 825, 2, 2, 244, 'lrneakage attended to', 825, '2014-08-11 13:20:35', 0, 0),
(214, 92, 911, 790, 2, 2, 234, 'service the meter', 790, '2014-08-11 13:20:48', 0, 0),
(215, 95, 962, 835, 2, 2, 240, 'Please avail the required equipment.', 835, '2014-08-11 13:21:07', 0, 0),
(216, 96, 953, 827, 2, 2, 242, 'need elbow', 827, '2014-08-11 13:21:34', 0, 0),
(217, 91, 951, 826, 2, 2, 232, 'kindly visit the place and act', 826, '2014-08-11 13:25:07', 0, 0),
(218, 98, 911, 790, 2, 2, 250, 'replace the meter', 790, '2014-08-11 13:48:08', 0, 0),
(219, 95, 950, 825, 2, 6, 247, 'at procurement stage', 825, '2014-08-11 13:52:23', 0, 0),
(220, 99, 962, 835, 2, 2, 252, 'Please deal', 835, '2014-08-11 14:45:08', 0, 0),
(221, 100, 962, 835, 2, 2, 254, 'Please attend', 835, '2014-08-11 14:47:44', 0, 0),
(222, 101, 911, 790, 2, 2, 256, 'arrange to capture readings', 790, '2014-08-11 14:51:36', 0, 0),
(223, 102, 950, 825, 2, 2, 258, 'meter reader to confirm', 825, '2014-08-11 14:52:30', 0, 0),
(224, 90, 962, 835, 2, 6, 230, 'Transport to the site failed.', 835, '2014-08-11 14:57:46', 0, 0),
(225, 103, 953, 827, 2, 2, 260, 'burst taking long', 827, '2014-08-11 14:57:52', 0, 0),
(226, 90, 962, 835, 3, 1, 230, 'The burst has been repaired.', 835, '2014-08-11 15:01:14', 0, 0),
(227, 104, 950, 825, 2, 2, 262, 'Send plumber to attend to it.', 825, '2014-08-11 15:01:48', 0, 0),
(228, 105, 951, 826, 2, 2, 264, 'Go and confirm the readings', 826, '2014-08-11 15:02:15', 0, 0),
(229, 106, 874, 767, 2, 2, 266, 'Please check with billing office why bills are not sent. Is the database not updated with customers new address', 767, '2014-08-11 15:02:37', 0, 0),
(230, 107, 911, 790, 2, 2, 268, 'capture readings', 790, '2014-08-11 15:06:20', 0, 0),
(231, 108, 874, 767, 2, 2, 270, 'Please broadcast SMS to customers about burst in the area.', 767, '2014-08-11 15:06:24', 0, 0),
(232, 109, 962, 835, 2, 2, 272, 'Please check the readings', 835, '2014-08-11 15:08:43', 0, 0),
(233, 110, 950, 825, 2, 2, 274, 'Confirm Reading', 825, '2014-08-11 15:11:39', 0, 0),
(234, 111, 953, 827, 2, 2, 276, 'wrong reading', 827, '2014-08-11 15:11:54', 0, 0),
(235, 112, 951, 826, 2, 2, 278, 'kindly deal', 826, '2014-08-11 15:12:20', 0, 0),
(236, 113, 911, 790, 2, 2, 280, 'rebillplease', 790, '2014-08-11 15:12:41', 0, 0),
(237, 104, 951, 826, 2, 2, 263, 'go attend to the burst', 826, '2014-08-11 15:19:51', 0, 0),
(238, 113, 962, 835, 2, 2, 281, 'Please take action.', 835, '2014-08-11 15:20:07', 0, 0),
(239, 107, 950, 825, 2, 6, 269, 'It is under adjustment', 825, '2014-08-11 15:21:38', 0, 0),
(240, 91, 953, 827, 2, 2, 249, 'work succesfully done', 827, '2014-08-11 15:21:42', 0, 0),
(241, 109, 911, 790, 2, 2, 273, 'confirm the readings urgently', 790, '2014-08-11 15:22:00', 0, 0),
(242, 110, 962, 835, 2, 6, 275, 'Will check the readings once the customer is available.', 835, '2014-08-11 15:26:40', 0, 0),
(243, 112, 950, 825, 2, 6, 279, 'Provide transport', 825, '2014-08-11 15:26:41', 0, 0),
(244, 98, 953, 827, 2, 6, 251, 'no meters in store', 827, '2014-08-11 15:27:16', 0, 0),
(245, 89, 911, 790, 2, 6, 228, 'gate locked', 790, '2014-08-11 15:27:49', 0, 0),
(246, 91, 951, 826, 2, 6, 284, 'work still on going', 826, '2014-08-11 15:29:45', 0, 0),
(247, 105, 962, 835, 3, 1, 265, 'The readings are 0456 units.', 835, '2014-08-11 15:32:50', 0, 0),
(248, 111, 950, 825, 3, 1, 277, 'Work done', 825, '2014-08-11 15:33:23', 0, 0),
(249, 96, 951, 826, 3, 1, 248, 'leakage repaired', 826, '2014-08-11 15:33:37', 0, 0),
(250, 97, 953, 827, 3, 1, 245, 'complaint resolved', 827, '2014-08-11 15:33:44', 0, 0),
(251, 102, 911, 790, 3, 1, 259, 'readings have been confirmed', 790, '2014-08-11 15:33:55', 0, 0),
(252, 100, 950, 825, 3, 4, 255, 'the customer has no account', 825, '2014-08-11 15:36:47', 0, 0),
(253, 86, 951, 826, 3, 4, 220, 'customer did not clear last month bill.', 826, '2014-08-11 15:40:01', 0, 0),
(254, 106, 962, 835, 3, 4, 267, 'The customer did not provide the correct address.', 835, '2014-08-11 15:42:06', 0, 0),
(255, 94, 953, 827, 3, 4, 238, 'burst already dealt with', 827, '2014-08-11 15:42:14', 0, 0),
(256, 87, 911, 790, 3, 4, 226, 'waiting for two months to adjust', 790, '2014-08-11 15:42:37', 0, 0),
(257, 107, 950, 825, 3, 1, 269, 'The bill has no mistakes  please pay to avoid disconnection', 825, '2014-08-11 15:44:15', 0, 0),
(259, 115, 874, 767, 2, 2, 287, 'Take necessary action', 767, '2014-08-12 10:48:43', 0, 0),
(260, 116, 931, 806, 2, 2, 289, 'Please adjust', 806, '2014-08-12 11:19:55', 0, 0),
(261, 117, 931, 806, 2, 2, 291, 'Please attend to this burst', 806, '2014-08-12 11:21:27', 0, 0),
(262, 117, 874, 767, 2, 2, 292, 'Please send your team to site', 767, '2014-08-12 11:47:34', 0, 0),
(263, 116, 874, 767, 2, 6, 290, 'We have tried to call the customer to no avail. Will keep trying until he responds.', 767, '2014-08-12 11:50:29', 0, 0),
(264, 116, 874, 767, 2, 2, 290, 'Visited the site. Readings are 55 units. Please adjust bill', 767, '2014-08-12 11:54:22', 0, 0),
(265, 118, 977, 844, 2, 2, 295, 'Take necessary action immediately', 844, '2014-08-12 12:24:59', 0, 0),
(266, 119, 874, 767, 2, 2, 297, 'Please deal', 767, '2014-08-12 12:27:58', 0, 0),
(267, 120, 964, 838, 2, 2, 299, 'please deal', 838, '2014-08-12 12:29:03', 0, 0),
(268, 121, 961, 833, 2, 2, 301, 'please deal', 833, '2014-08-12 12:29:17', 0, 0),
(269, 122, 972, 847, 2, 2, 303, 'PLEASE DEAL', 847, '2014-08-12 12:29:30', 0, 0),
(270, 123, 971, 846, 2, 2, 305, 'pls attend immediately', 846, '2014-08-12 12:30:57', 0, 0),
(271, 124, 969, 843, 2, 2, 307, 'take necessary action', 843, '2014-08-12 12:35:49', 0, 0),
(272, 125, 963, 837, 2, 2, 309, 'repair bust pipe', 837, '2014-08-12 12:36:47', 0, 0),
(273, 126, 965, 839, 2, 2, 311, 'attend to this leakage', 839, '2014-08-12 12:38:05', 0, 0),
(274, 127, 969, 843, 2, 2, 313, 'please deal', 843, '2014-08-12 12:42:22', 0, 0),
(275, 128, 972, 847, 2, 2, 315, 'CONFIRM READINGS', 847, '2014-08-12 12:47:07', 0, 0),
(276, 129, 965, 839, 2, 2, 317, 'take action', 839, '2014-08-12 12:47:49', 0, 0),
(277, 130, 961, 833, 2, 2, 319, 'please deal', 833, '2014-08-12 12:49:46', 0, 0),
(278, 131, 971, 846, 2, 2, 321, 'please attend', 846, '2014-08-12 12:50:55', 0, 0),
(279, 132, 969, 843, 2, 2, 323, 'adjust', 843, '2014-08-12 12:51:46', 0, 0),
(280, 133, 963, 837, 2, 2, 325, 'meter servicing', 837, '2014-08-12 12:53:48', 0, 0),
(281, 134, 977, 844, 2, 2, 327, 'Kindly take action immediately', 844, '2014-08-12 12:55:27', 0, 0),
(282, 135, 972, 847, 2, 2, 329, 'DEAL', 847, '2014-08-12 12:55:45', 0, 0),
(283, 136, 965, 839, 2, 2, 331, 'take action', 839, '2014-08-12 12:56:02', 0, 0),
(284, 137, 961, 833, 2, 2, 333, 'Please deal', 833, '2014-08-12 13:02:57', 0, 0),
(285, 134, 977, 844, 2, 2, 328, 'kindly take action', 844, '2014-08-12 13:03:49', 0, 0),
(286, 127, 955, 834, 2, 2, 314, 'Please deal', 834, '2014-08-12 13:05:52', 0, 0),
(287, 138, 931, 806, 2, 2, 337, 'please deal', 806, '2014-08-12 13:06:51', 0, 0),
(288, 138, 874, 767, 2, 2, 338, 'Please send team to confirm readings', 767, '2014-08-12 13:11:55', 0, 0),
(289, 115, 961, 833, 2, 2, 288, 'please send team to confirm readings', 833, '2014-08-12 13:12:31', 0, 0),
(290, 131, 964, 838, 2, 2, 322, 'kindly send team to repair the leakage', 838, '2014-08-12 13:12:41', 0, 0),
(291, 137, 965, 839, 2, 2, 334, 'confirm readings', 839, '2014-08-12 13:12:45', 0, 0),
(292, 132, 972, 847, 2, 2, 324, 'PLEASE DEAL', 847, '2014-08-12 13:13:34', 0, 0),
(293, 139, 874, 767, 2, 2, 344, 'Please approve broadcast SMS to customers on the burst', 767, '2014-08-12 13:14:14', 0, 0),
(294, 128, 969, 843, 2, 2, 316, 'please send the team to work on it', 843, '2014-08-12 13:14:49', 0, 0),
(295, 130, 971, 846, 2, 2, 320, 'pls send team to confirm readings', 846, '2014-08-12 13:14:54', 0, 0),
(296, 140, 977, 844, 2, 2, 348, 'take action', 844, '2014-08-12 13:15:50', 0, 0),
(297, 122, 955, 834, 2, 2, 304, 'adjust bill', 834, '2014-08-12 13:18:18', 0, 0),
(298, 124, 955, 834, 2, 2, 308, 'adjust bill', 834, '2014-08-12 13:22:17', 0, 0),
(299, 120, 955, 834, 2, 2, 300, 'attend to leakage', 834, '2014-08-12 13:24:21', 0, 0),
(300, 125, 955, 834, 2, 2, 310, 'take action', 834, '2014-08-12 13:27:53', 0, 0),
(301, 141, 972, 847, 2, 2, 354, 'TAKE ACTION', 847, '2014-08-12 13:33:03', 0, 0),
(302, 126, 955, 834, 2, 2, 312, 'we lack materials', 834, '2014-08-12 13:33:25', 0, 0),
(303, 140, 961, 833, 2, 6, 349, 'work in progress', 833, '2014-08-12 13:59:40', 0, 0),
(304, 142, 972, 847, 2, 2, 357, 'ACT', 847, '2014-08-12 14:10:50', 0, 0),
(305, 143, 972, 847, 2, 2, 359, 'TAKE ACTION', 847, '2014-08-12 14:31:50', 0, 0),
(306, 144, 959, 842, 2, 2, 361, 'confirm the readings', 842, '2014-08-12 14:36:19', 0, 0),
(307, 145, 969, 843, 2, 2, 363, 'kindly confirm the reading inthe of july', 843, '2014-08-12 14:37:52', 0, 0),
(308, 146, 972, 847, 2, 2, 365, 'TAKE ACTION', 847, '2014-08-12 14:39:31', 0, 0),
(309, 147, 961, 833, 2, 2, 367, 'please deal', 833, '2014-08-12 14:40:17', 0, 0),
(310, 148, 963, 837, 2, 2, 369, 'repair leakage', 837, '2014-08-12 14:43:01', 0, 0),
(311, 149, 959, 842, 2, 2, 371, 'send your team to repair the leakage', 842, '2014-08-12 14:44:15', 0, 0),
(312, 150, 969, 843, 2, 2, 373, 'kindly repair the leakage in kiamunyeki', 843, '2014-08-12 14:47:34', 0, 0),
(313, 151, 965, 839, 2, 2, 375, 'adjust my bill', 839, '2014-08-12 14:49:31', 0, 0),
(314, 152, 959, 842, 2, 2, 377, 'deal on it', 842, '2014-08-12 14:50:33', 0, 0),
(315, 153, 972, 847, 2, 2, 379, 'CONFIRM READINGS', 847, '2014-08-12 14:51:31', 0, 0),
(316, 154, 963, 837, 2, 2, 381, 'correct the bill', 837, '2014-08-12 14:54:37', 0, 0),
(317, 155, 969, 843, 2, 2, 383, 'kindly attend the leakage', 843, '2014-08-12 14:58:47', 0, 0),
(318, 156, 972, 847, 2, 2, 385, 'CHANGE ADRESS', 847, '2014-08-12 15:01:47', 0, 0),
(319, 157, 970, 852, 2, 2, 387, 'adjust', 852, '2014-08-12 15:02:55', 0, 0),
(320, 158, 913, 850, 2, 2, 389, 'please attend the burst', 850, '2014-08-12 15:04:35', 0, 0),
(321, 159, 970, 852, 2, 2, 391, 'please act on it', 852, '2014-08-12 15:08:41', 0, 0),
(322, 160, 963, 837, 2, 2, 393, 'repair the leaking pipe', 837, '2014-08-12 15:10:09', 0, 0),
(323, 161, 961, 833, 2, 2, 395, 'please deal', 833, '2014-08-12 15:13:37', 0, 0),
(324, 122, 972, 847, 2, 2, 350, 'ATTEND URGENTLY', 847, '2014-08-12 15:13:53', 0, 0),
(325, 126, 963, 837, 2, 2, 356, 'lack of materials', 837, '2014-08-12 15:14:58', 0, 0),
(326, 159, 965, 839, 2, 2, 392, 'please attend to the leakage', 839, '2014-08-12 15:17:48', 0, 0),
(327, 161, 874, 767, 2, 2, 396, 'Contact the customer for more details on the payment history of this account', 767, '2014-08-12 15:18:01', 0, 0),
(328, 158, 969, 843, 2, 2, 390, 'kindly send the team', 843, '2014-08-12 15:18:28', 0, 0),
(329, 162, 874, 767, 2, 2, 402, 'This account in your zone. Please deal', 767, '2014-08-12 15:19:35', 0, 0),
(330, 155, 965, 839, 2, 2, 384, 'please repair the leakage', 839, '2014-08-12 15:20:35', 0, 0),
(331, 163, 874, 767, 2, 2, 405, 'Please send your team to site', 767, '2014-08-12 15:21:24', 0, 0),
(332, 144, 969, 843, 2, 6, 362, 'waiting for transport', 843, '2014-08-12 15:21:35', 0, 0),
(333, 152, 965, 839, 2, 2, 378, 'take necessary measures', 839, '2014-08-12 15:24:21', 0, 0),
(334, 154, 972, 847, 3, 1, 382, 'BILL HAS BEEN ADJUSTED', 847, '2014-08-12 15:24:27', 0, 0),
(335, 164, 961, 833, 2, 2, 408, 'please take correct readings', 833, '2014-08-12 15:24:44', 0, 0),
(336, 138, 969, 843, 3, 4, 339, 'faulty meter', 843, '2014-08-12 15:24:49', 0, 0),
(337, 158, 961, 833, 2, 2, 401, 'please deal', 833, '2014-08-12 15:26:59', 0, 0),
(338, 153, 969, 843, 3, 1, 380, '2015', 843, '2014-08-12 15:27:00', 0, 0),
(339, 163, 963, 837, 2, 6, 406, 'technical team working on the ground', 837, '2014-08-12 15:28:45', 0, 0),
(340, 140, 961, 833, 2, 6, 349, 'no meters in the store', 833, '2014-08-12 15:28:56', 0, 0),
(341, 142, 965, 839, 2, 6, 358, 'delayed transport', 839, '2014-08-12 15:29:43', 0, 0),
(342, 155, 971, 846, 2, 2, 404, 'The leak has been attended to', 846, '2014-08-12 15:29:54', 0, 0),
(343, 116, 972, 847, 3, 4, 294, 'HAVE THE METER TESTED', 847, '2014-08-12 15:30:39', 0, 0),
(344, 142, 965, 839, 2, 2, 358, 'meter correct readings are 34', 839, '2014-08-12 15:32:46', 0, 0),
(345, 157, 972, 847, 2, 6, 388, 'TRYING TO REACH THE CUSTOMER', 847, '2014-08-12 15:34:37', 0, 0),
(346, 162, 971, 846, 2, 6, 403, 'please let the customer provide us with his/her contacts', 846, '2014-08-12 15:34:43', 0, 0),
(347, 155, 965, 839, 3, 4, 411, 'custmer message not clear', 839, '2014-08-12 15:36:22', 0, 0),
(348, 162, 971, 846, 2, 2, 403, 'Details have been provided', 846, '2014-08-12 15:38:23', 0, 0),
(349, 163, 963, 837, 3, 1, 406, 'work done', 837, '2014-08-12 15:38:41', 0, 0),
(350, 149, 965, 839, 3, 1, 372, 'burst repaired', 839, '2014-08-12 15:38:54', 0, 0),
(351, 158, 970, 852, 3, 1, 410, 'the work has been done', 852, '2014-08-12 15:38:55', 0, 0),
(352, 161, 971, 846, 2, 6, 400, 'customer is not available', 846, '2014-08-12 15:42:56', 0, 0),
(353, 165, 874, 767, 2, 2, 414, 'Please send your team to site', 767, '2014-08-12 15:43:30', 0, 0),
(354, 164, 970, 852, 3, 4, 409, 'account was wrongly routed', 852, '2014-08-12 15:43:37', 0, 0),
(355, 161, 971, 846, 3, 4, 400, 'Detail confirmed', 846, '2014-08-12 15:44:12', 0, 0),
(356, 143, 971, 846, 3, 1, 360, 'The customer received water but had not paid last months bill', 846, '2014-08-12 15:47:31', 0, 0),
(357, 165, 963, 837, 3, 4, 415, 'acc not found in the system', 837, '2014-08-12 15:47:38', 0, 0),
(358, 141, 965, 839, 2, 6, 355, 'trasport available', 839, '2014-08-12 15:52:04', 0, 0),
(359, 166, 961, 833, 2, 2, 416, 'KINDLY DEAL', 833, '2014-08-12 15:53:51', 0, 0),
(360, 167, 961, 833, 2, 2, 418, 'KINDLY DEAL', 833, '2014-08-12 15:55:10', 0, 0),
(361, 141, 965, 839, 2, 2, 355, 'done', 839, '2014-08-12 16:09:52', 0, 0),
(362, 108, 962, 835, 2, 2, 271, 'There is no free sms', 835, '2014-08-13 08:56:27', 0, 0),
(363, 169, 962, 835, 2, 2, 423, 'please deal', 835, '2014-08-13 08:59:37', 0, 0),
(364, 170, 900, 778, 2, 2, 425, 'Please deal', 778, '2014-08-13 10:21:53', 0, 0),
(365, 171, 900, 778, 2, 2, 427, 'Please deal', 778, '2014-08-13 10:25:51', 0, 0),
(366, 172, 900, 778, 2, 2, 429, 'Please deal', 778, '2014-08-13 10:28:13', 0, 0),
(367, 173, 900, 778, 2, 2, 431, 'Please deal', 778, '2014-08-13 10:30:17', 0, 0),
(368, 174, 874, 767, 2, 2, 433, 'Confirm the readings for  July 2014', 767, '2014-08-13 10:54:01', 0, 0),
(369, 173, 874, 767, 2, 2, 432, 'Please send your foreman to site to confirm situation', 767, '2014-08-13 11:51:17', 0, 0),
(370, 172, 874, 767, 2, 6, 430, 'Trying to call the customer to access the home. Unreachable. Will keep trying', 767, '2014-08-13 11:54:14', 0, 0),
(371, 171, 874, 767, 3, 1, 428, 'Complaint has been resolved. Checked meter units are ok.', 767, '2014-08-13 11:57:00', 0, 0),
(372, 170, 874, 767, 3, 4, 426, 'Sent plumber to confirm. Leakage from the toilet.', 767, '2014-08-13 11:59:04', 0, 0),
(373, 175, 894, 820, 2, 2, 436, 'please update in the system.', 820, '2014-08-13 12:28:29', 0, 0),
(374, 176, 874, 767, 2, 2, 438, 'Please send your team to confirm readings', 767, '2014-08-13 12:28:42', 0, 0),
(375, 177, 948, 822, 2, 2, 440, 'confirm the readings', 822, '2014-08-13 12:28:51', 0, 0),
(376, 178, 992, 864, 2, 2, 442, 'PLEASE CONFIRM THE READING FOR AUGUST 2014', 864, '2014-08-13 12:28:57', 0, 0),
(377, 179, 979, 853, 2, 2, 444, 'confirm readings', 853, '2014-08-13 12:29:03', 0, 0),
(378, 180, 960, 831, 2, 2, 446, 'CHECK THE BILL', 831, '2014-08-13 12:29:44', 0, 0),
(379, 181, 990, 858, 2, 2, 448, 'confirm readings', 858, '2014-08-13 12:30:30', 0, 0),
(380, 182, 992, 864, 2, 2, 450, 'Kindly confirm and take the necessary action', 864, '2014-08-13 12:35:21', 0, 0),
(381, 183, 990, 858, 2, 2, 452, 'plumber to confirm', 858, '2014-08-13 12:37:59', 0, 0),
(382, 184, 979, 853, 2, 2, 454, 'please act', 853, '2014-08-13 12:38:13', 0, 0),
(383, 185, 948, 822, 2, 2, 456, 'please investigate the leakage', 822, '2014-08-13 12:38:38', 0, 0),
(384, 186, 874, 767, 2, 2, 458, 'Please deal', 767, '2014-08-13 12:45:50', 0, 0),
(385, 187, 979, 853, 2, 2, 460, 'plse check', 853, '2014-08-13 12:47:53', 0, 0),
(386, 188, 960, 831, 2, 2, 462, 'Check and repair leakage', 831, '2014-08-13 12:48:09', 0, 0),
(387, 189, 992, 864, 2, 2, 464, 'Please confirm', 864, '2014-08-13 12:50:26', 0, 0),
(388, 190, 894, 820, 2, 2, 466, 'kindly send your plumber', 820, '2014-08-13 12:53:59', 0, 0),
(389, 191, 989, 862, 2, 2, 468, 'deal', 862, '2014-08-13 13:03:29', 0, 0),
(390, 172, 874, 767, 2, 2, 430, 'Got hold of customer. Access premises. Adjust bill reading is wrong', 767, '2014-08-13 13:05:13', 0, 0),
(391, 192, 931, 806, 2, 2, 471, 'deal with this', 806, '2014-08-13 13:06:54', 0, 0),
(392, 193, 931, 806, 2, 2, 473, 'Please confirm readings', 806, '2014-08-13 13:09:32', 0, 0),
(393, 187, 894, 820, 2, 2, 461, 'kindly get the correct the right adress from the customer', 820, '2014-08-13 13:10:41', 0, 0),
(394, 190, 979, 853, 2, 2, 467, 'Please acquire material and repair.', 853, '2014-08-13 13:10:45', 0, 0),
(395, 192, 989, 862, 2, 2, 472, 'please adjust', 862, '2014-08-13 13:12:12', 0, 0),
(396, 194, 960, 831, 2, 2, 478, 'check  repair leakage', 831, '2014-08-13 13:13:14', 0, 0),
(397, 172, 992, 864, 2, 2, 470, 'PLEASE ADJUST BILL', 864, '2014-08-13 13:14:04', 0, 0),
(398, 189, 948, 822, 2, 2, 465, 'confired', 822, '2014-08-13 13:15:36', 0, 0),
(399, 195, 990, 858, 2, 2, 482, 'plumber to attend', 858, '2014-08-13 14:26:03', 0, 0),
(400, 196, 983, 865, 2, 2, 484, 'plse confirm the readings', 865, '2014-08-13 14:27:48', 0, 0),
(401, 197, 986, 857, 2, 2, 486, 'kindly verify the readings', 857, '2014-08-13 14:34:03', 0, 0),
(402, 198, 983, 865, 2, 2, 488, 'please check for adjustment', 865, '2014-08-13 14:34:03', 0, 0),
(403, 199, 990, 858, 2, 2, 490, 'please adjust', 858, '2014-08-13 14:34:18', 0, 0),
(404, 200, 960, 831, 2, 2, 492, 'Confirm  repair', 831, '2014-08-13 14:35:03', 0, 0),
(405, 201, 992, 864, 2, 2, 494, 'Kindly confirm the same', 864, '2014-08-13 14:38:31', 0, 0),
(406, 202, 978, 866, 2, 2, 496, 'confirm readings', 866, '2014-08-13 14:38:36', 0, 0),
(407, 203, 983, 865, 2, 2, 498, 'please repair', 865, '2014-08-13 14:40:56', 0, 0),
(408, 204, 986, 857, 2, 2, 500, 'see to that problem of leakage in shabab', 857, '2014-08-13 14:45:51', 0, 0),
(409, 205, 992, 864, 2, 2, 502, 'confirm and repair the burst', 864, '2014-08-13 14:47:16', 0, 0),
(410, 206, 960, 831, 2, 2, 504, 'check bill  correct', 831, '2014-08-13 14:48:10', 0, 0),
(411, 193, 990, 858, 2, 2, 474, 'deal', 858, '2014-08-13 14:55:02', 0, 0),
(412, 205, 979, 853, 2, 2, 503, 'please deal', 853, '2014-08-13 14:55:20', 0, 0),
(413, 188, 986, 857, 2, 2, 463, 'kindly check into the issue', 857, '2014-08-13 14:57:58', 0, 0),
(414, 202, 992, 864, 2, 2, 497, 'bILL HAS BEEN CORRECTED', 864, '2014-08-13 14:58:15', 0, 0),
(415, 187, 990, 858, 2, 2, 475, 'deal', 858, '2014-08-13 14:58:58', 0, 0),
(416, 185, 979, 853, 2, 6, 457, 'Procurement of the necessary  material is ongoing', 853, '2014-08-13 14:59:16', 0, 0),
(417, 179, 990, 858, 2, 6, 445, 'readings confirmed', 858, '2014-08-13 15:00:18', 0, 0),
(418, 188, 992, 864, 2, 6, 508, 'Repair has been done', 864, '2014-08-13 15:00:50', 0, 0),
(419, 205, 980, 854, 2, 2, 507, 'burst repaired', 854, '2014-08-13 15:01:00', 0, 0),
(420, 182, 979, 853, 3, 1, 451, 'Billing has been adjusted', 853, '2014-08-13 15:01:48', 0, 0),
(421, 178, 990, 858, 3, 1, 443, 'readings ok', 858, '2014-08-13 15:02:22', 0, 0),
(422, 183, 986, 857, 2, 6, 453, 'system are down.system under going maintainance', 857, '2014-08-13 15:02:26', 0, 0),
(423, 198, 992, 864, 3, 4, 489, 'The same case has been handled', 864, '2014-08-13 15:02:49', 0, 0),
(424, 207, 874, 767, 2, 2, 512, 'Please deal', 767, '2014-08-13 15:03:17', 0, 0),
(425, 204, 980, 854, 2, 6, 501, 'the store has no materials waiting for matrerials', 854, '2014-08-13 15:03:20', 0, 0),
(426, 199, 990, 858, 3, 4, 491, 'bill okay', 858, '2014-08-13 15:03:52', 0, 0),
(427, 173, 979, 853, 3, 4, 435, 'To control the overflow.', 853, '2014-08-13 15:06:05', 0, 0),
(428, 204, 980, 854, 3, 1, 501, 'burst repaired', 854, '2014-08-13 15:06:24', 0, 0),
(429, 187, 978, 866, 2, 2, 510, 'confirm the adress from the ground', 866, '2014-08-13 15:08:46', 0, 0),
(430, 175, 986, 857, 3, 4, 437, 'account number not correct', 857, '2014-08-13 15:09:41', 0, 0),
(431, 192, 979, 853, 2, 2, 477, 'please deal', 853, '2014-08-13 15:11:04', 0, 0),
(432, 188, 992, 864, 2, 2, 508, 'check this', 864, '2014-08-13 15:11:08', 0, 0),
(433, 205, 978, 866, 2, 6, 511, 'the store has no materials', 866, '2014-08-13 15:12:48', 0, 0),
(434, 208, 874, 767, 2, 2, 517, 'Please send your team to site', 767, '2014-08-13 15:13:52', 0, 0),
(435, 205, 978, 866, 3, 1, 511, 'burst repaired', 866, '2014-08-13 15:14:54', 0, 0),
(436, 187, 980, 854, 2, 2, 514, 'Confirm latest bill', 854, '2014-08-13 15:17:18', 0, 0),
(437, 207, 978, 866, 3, 4, 513, 'the account is wrongly routed', 866, '2014-08-13 15:19:04', 0, 0),
(438, 201, 986, 857, 2, 2, 495, 'pls do the necessary', 857, '2014-08-13 15:20:47', 0, 0),
(439, 202, 978, 866, 3, 1, 509, 'dealt with', 866, '2014-08-13 15:21:04', 0, 0),
(440, 190, 983, 865, 2, 2, 476, 'Repaired the burst. Inform customer on feedback', 865, '2014-08-13 15:32:34', 0, 0),
(441, 196, 986, 857, 2, 2, 485, 'urgently confirm rdgs', 857, '2014-08-13 15:43:12', 0, 0),
(442, 196, 978, 866, 2, 2, 523, 'confirmed', 866, '2014-08-13 15:46:11', 0, 0),
(452, 108, 977, 844, 2, 2, 422, 'This is a test', 844, '2014-08-26 19:46:58', 0, 0),
(453, 139, 900, 778, 2, 6, 345, 'Am on it', 778, '2014-08-26 19:49:58', 0, 0),
(454, 210, 900, 778, 2, 2, 526, 'sdfasfsdf', 778, '2014-08-28 09:20:28', 0, 0),
(455, 139, 900, 778, 3, 1, 345, 'Done', 778, '2014-08-28 12:42:53', 0, 0),
(456, 147, 900, 778, 2, 6, 368, 'Work is still in progress', 778, '2014-08-28 13:44:27', 0, 0),
(457, 29, 902, 780, 2, 6, 109, 'looking at it', 780, '2014-08-28 14:22:03', 0, 0),
(458, 29, 902, 780, 2, 6, 109, 'for a second time am looking bana chief', 780, '2014-08-28 14:22:18', 0, 0),
(459, 147, 900, 778, 2, 9, 368, 'please recheck', 778, '2014-08-29 10:28:55', 0, 8),
(460, 29, 900, 778, 2, 2, 109, 'This is a lovely thing', 778, '2014-08-29 10:43:15', 0, 11),
(461, 12, 900, 778, 2, 2, 50, 'done', 778, '2014-08-29 11:01:47', 0, 12),
(462, 16, 900, 778, 2, 6, 47, 'so far soo gooooodd', 778, '2014-08-29 11:12:43', 0, 12),
(463, 76, 900, 778, 2, 6, 195, 'Mabo bado', 778, '2014-08-29 11:32:14', 0, 14),
(464, 76, 900, 778, 2, 2, 195, 'this is it', 778, '2014-08-29 11:54:49', 0, 14),
(465, 66, 900, 778, 3, 1, 166, 'done', 778, '2014-09-09 11:12:42', 0, 14),
(466, 4, 878, 771, 3, 1, 8, 'Done Kapish', 771, '2014-09-12 14:33:49', 0, 0),
(468, 3, 878, 771, 3, 1, 6, 'Fixed Bug Kabis', 771, '2014-09-12 14:36:22', 0, 0),
(469, 5, 903, 781, 3, 4, 53, 'this is a test', 781, '2014-09-12 14:45:00', 0, 0),
(470, 9, 903, 781, 3, 4, 51, 'error', 781, '2014-09-12 14:52:03', 0, 0),
(471, 27, 923, 800, 3, 4, 101, 'asdfasdf dsf', 800, '2014-09-12 14:52:59', 0, 0),
(472, 33, 925, 802, 3, 4, 87, 'asdfadsf', 802, '2014-09-12 14:58:11', 0, 0),
(473, 31, 925, 802, 3, 4, 83, 'Done use', 802, '2014-09-12 15:12:08', 0, 0),
(474, 2, 881, 774, 2, 6, 4, 'haiya', 774, '2014-09-17 05:24:26', 0, 0),
(475, 2, 881, 774, 2, 6, 4, 'popopopo', 774, '2014-09-17 05:34:02', 0, 0),
(476, 2, 881, 774, 2, 6, 4, 'asfasdf', 774, '2014-09-17 05:34:35', 0, 0),
(477, 2, 881, 774, 2, 6, 4, 'asdf', 774, '2014-09-17 05:36:01', 0, 0),
(478, 2, 881, 774, 2, 6, 4, 'asdadsf', 774, '2014-09-17 05:37:09', 0, 0),
(479, 2, 881, 774, 2, 6, 4, 'asdfads adsf', 774, '2014-09-17 05:43:07', 0, 0),
(480, 2, 881, 774, 2, 6, 4, 'adsf adsf dsf ds', 774, '2014-09-17 05:44:36', 0, 0),
(481, 2, 881, 774, 2, 6, 4, 'adsf adsf dsf ds', 774, '2014-09-17 05:45:07', 0, 0),
(482, 2, 881, 774, 2, 6, 4, 'adsf adsf dsf ds', 774, '2014-09-17 05:50:56', 0, 0),
(483, 2, 881, 774, 2, 6, 4, 'adsf adsf dsf ds', 774, '2014-09-17 05:59:10', 0, 0),
(484, 2, 881, 774, 2, 6, 4, 'haiiiya', 774, '2014-09-17 06:38:18', 0, 0),
(485, 2, 881, 774, 2, 6, 4, 'asdfsf', 774, '2014-09-17 06:44:50', 0, 0),
(486, 2, 881, 774, 2, 6, 4, 'asdfasdf', 774, '2014-09-17 06:46:03', 0, 0),
(487, 2, 881, 774, 2, 2, 4, 'this is OK', 774, '2014-09-17 08:56:25', 0, 0),
(488, 2, 881, 774, 2, 2, 4, 'this is OK', 774, '2014-09-17 08:59:56', 0, 0),
(489, 279, 931, 806, 2, 2, 700, 'Please look at this', 806, '2014-09-17 12:27:07', 0, 0),
(490, 162, 895, 848, 2, 2, 413, 'Haiya', 848, '2014-09-24 09:42:52', 0, 0),
(491, 211, 895, 848, 2, 2, 536, 'Fanya hii tafadhali', 848, '2014-09-24 13:15:14', 0, 0),
(493, 213, 895, 848, 2, 2, 540, 'this is it', 848, '2014-09-24 13:44:16', 0, 0),
(494, 169, 895, 848, 2, 2, 424, 'Go ahead please', 848, '2014-09-24 17:34:09', 0, 0),
(495, 162, 964, 838, 2, 2, 535, 'Angalia hiyo test task', 838, '2014-09-24 17:37:44', 0, 0),
(496, 214, 964, 838, 2, 2, 544, 'chek dat', 838, '2014-09-24 17:46:14', 0, 0),
(497, 162, 900, 778, 2, 6, 543, 'This is a test progress update', 778, '2014-09-25 04:21:02', 0, 0),
(498, 162, 900, 778, 2, 6, 543, 'Do this bana', 778, '2014-09-25 04:49:56', 0, 0),
(499, 162, 900, 778, 2, 6, 543, 'come we talk', 778, '2014-09-25 04:50:29', 0, 0),
(500, 162, 900, 778, 2, 6, 543, 'This is a good start<br>[SMS:NAWASCO [4BL140812HQ19-Billing]:We need you to come to our offices for this matter to be dealt with ]', 778, '2014-09-25 04:55:18', 0, 0),
(501, 162, 900, 778, 2, 6, 543, 'this is a test<br>[[[NAWASCO [4BL140812HQ19-Billing]:THis is a test of the sms format on the task history]]]', 778, '2014-09-25 05:03:49', 0, 0),
(503, 162, 900, 778, 3, 1, 543, 'This matter is a wrap<br>[[[NAWASCO [4BL140812HQ19-Billing]:Dear Customer, the matter has been resolved. Debited 900 . Call us FREE 0800720036]]]', 778, '2014-09-25 05:36:58', 0, 0),
(504, 215, 900, 778, 2, 2, 546, 'tesdsd', 778, '2014-10-03 13:44:46', 0, 0),
(505, 145, 964, 838, 2, 2, 364, 'this is a test', 838, '2014-10-30 12:41:37', 0, 0),
(506, 145, 990, 858, 2, 6, 548, 'That is the approved credit', 858, '2014-10-30 12:44:37', 0, 0),
(507, 145, 990, 858, 2, 6, 548, 'opopop', 858, '2014-10-30 13:06:54', 0, 0),
(508, 145, 990, 858, 2, 6, 548, 'test', 858, '2014-10-30 13:08:30', 0, 0),
(509, 196, 990, 858, 2, 6, 524, 'just a test', 858, '2014-10-30 15:06:01', 1, 0),
(510, 145, 990, 858, 2, 6, 548, 'hakuna garii plwana', 858, '2014-10-30 15:06:43', 2, 0),
(511, 201, 990, 858, 2, 6, 520, 'ooiujju', 858, '2014-10-31 12:24:40', 1, 0),
(512, 201, 990, 858, 2, 6, 520, 'hjkkhhik', 858, '2014-10-31 12:25:22', 1, 0),
(513, 177, 990, 858, 2, 6, 441, 'Confirming with the files', 858, '2014-10-31 12:43:53', 1, 0),
(514, 174, 990, 858, 2, 6, 434, 'poopopopop', 858, '2014-10-31 18:47:17', 1, 0),
(515, 145, 990, 858, 2, 6, 548, 'this is it', 858, '2014-11-01 10:04:06', 1, 0),
(516, 145, 990, 858, 2, 6, 548, 'adfasdf', 858, '2014-11-01 12:19:00', 1, 0),
(517, 145, 990, 858, 2, 6, 548, 'adfasdf', 858, '2014-11-01 12:21:04', 1, 0),
(518, 145, 990, 858, 2, 6, 548, 'adfasdf', 858, '2014-11-01 12:21:37', 1, 0),
(519, 145, 990, 858, 2, 6, 548, 'asdfasdfa', 858, '2014-11-01 12:22:09', 1, 0),
(520, 145, 990, 858, 2, 6, 548, 'asdfasdfa', 858, '2014-11-01 12:25:42', 1, 0),
(521, 145, 990, 858, 2, 6, 548, 'adfadsf a', 858, '2014-11-01 12:34:47', 1, 0),
(522, 145, 990, 858, 2, 6, 548, 'adf adsf asf', 858, '2014-11-01 12:36:30', 1, 0),
(523, 145, 990, 858, 2, 6, 548, 'adsf adsf', 858, '2014-11-01 12:38:09', 1, 0),
(524, 145, 990, 858, 2, 6, 548, 'adsf adsf', 858, '2014-11-01 12:38:14', 1, 0),
(525, 145, 990, 858, 2, 6, 548, 'adsf a', 858, '2014-11-01 12:38:57', 1, 0),
(526, 145, 990, 858, 2, 6, 548, 'asf asf', 858, '2014-11-01 12:39:52', 1, 0),
(527, 145, 990, 858, 2, 6, 548, 'asf asf', 858, '2014-11-01 12:42:02', 1, 0),
(528, 145, 990, 858, 2, 6, 548, 'asdfasf asdf', 858, '2014-11-01 12:44:02', 1, 0),
(529, 145, 990, 858, 2, 6, 548, 'asdfadsf', 858, '2014-11-01 12:46:22', 1, 0),
(530, 145, 990, 858, 2, 6, 548, 'asf', 858, '2014-11-01 12:47:16', 1, 0),
(531, 145, 990, 858, 2, 6, 548, 'asdfa sf', 858, '2014-11-01 12:48:50', 1, 0),
(532, 145, 990, 858, 2, 6, 548, 'iuiu', 858, '2014-11-01 12:51:01', 1, 0),
(533, 145, 990, 858, 2, 6, 548, 'iuiu', 858, '2014-11-01 12:53:12', 1, 0),
(534, 145, 990, 858, 2, 6, 548, 'asfasdf sdadsf', 858, '2014-11-01 12:56:45', 1, 0),
(535, 145, 990, 858, 2, 6, 548, 'ads asdf', 858, '2014-11-01 13:21:15', 1, 0),
(536, 145, 990, 858, 2, 6, 548, 'ads asdf', 858, '2014-11-01 13:21:18', 1, 0),
(537, 145, 990, 858, 2, 6, 548, 'adfasdfa', 858, '2014-11-01 13:23:02', 1, 0),
(538, 145, 990, 858, 2, 6, 548, 'adfasdfa', 858, '2014-11-01 13:25:07', 1, 0),
(539, 145, 990, 858, 2, 6, 548, 'adfasdfa', 858, '2014-11-01 13:25:30', 1, 0),
(540, 145, 990, 858, 2, 6, 548, 'adfasdfa', 858, '2014-11-01 13:28:20', 1, 0),
(541, 145, 990, 858, 2, 6, 548, 'adfasdfa', 858, '2014-11-01 13:29:14', 1, 0),
(542, 145, 990, 858, 2, 6, 548, 'adfasdfa', 858, '2014-11-01 13:29:22', 1, 0),
(543, 145, 990, 858, 2, 6, 548, 'adfasdfa', 858, '2014-11-01 13:29:46', 1, 0),
(544, 145, 990, 858, 2, 6, 548, 'adfasdfa', 858, '2014-11-01 13:29:53', 1, 0),
(545, 145, 990, 858, 2, 6, 548, 'adfasdfa', 858, '2014-11-01 13:32:14', 1, 0),
(546, 145, 990, 858, 2, 6, 548, 'adfasdfa', 858, '2014-11-01 13:32:37', 1, 0),
(547, 145, 990, 858, 2, 6, 548, 'adfasdfa', 858, '2014-11-01 13:32:45', 1, 0),
(548, 145, 990, 858, 2, 6, 548, 'adfasdfa', 858, '2014-11-01 13:33:57', 1, 0),
(549, 145, 990, 858, 2, 6, 548, 'adfasdfa', 858, '2014-11-01 13:35:34', 1, 0),
(550, 145, 990, 858, 2, 6, 548, 'adfasdfa', 858, '2014-11-01 13:36:10', 1, 0),
(551, 145, 990, 858, 2, 6, 548, 'adfasdfa', 858, '2014-11-01 13:37:13', 1, 0),
(552, 145, 990, 858, 2, 6, 548, 'adfasdfa', 858, '2014-11-01 19:50:56', 1, 0),
(553, 145, 990, 858, 2, 6, 548, 'adfasdfa', 858, '2014-11-01 19:54:28', 1, 0),
(554, 145, 990, 858, 2, 6, 548, 'adfasdfa', 858, '2014-11-01 19:55:04', 1, 0),
(555, 145, 990, 858, 2, 6, 548, 'adfasdfa', 858, '2014-11-01 19:55:06', 1, 0),
(556, 145, 990, 858, 2, 6, 548, 'adfasdfa', 858, '2014-11-01 19:55:16', 1, 0),
(557, 145, 990, 858, 2, 6, 548, 'adfasdfa', 858, '2014-11-01 19:55:17', 1, 0),
(558, 145, 990, 858, 2, 6, 548, 'adsfadsf', 858, '2014-11-01 19:55:30', 1, 0);
INSERT INTO `wftskupdates` (`idwftskupdates`, `wftaskstrac_idwftaskstrac`, `usrrole_idusrrole`, `usrac_idusrac`, `wftskstatusglobal_idwftskstatusglobal`, `wftskstatustypes_idwftskstatustypes`, `wftasks_idwftasks`, `wftskupdate`, `createdby`, `createdon`, `wftskupdates_class_idwftskupdates_class`, `wftasks_co_idwftasks_co`) VALUES
(559, 145, 990, 858, 2, 6, 548, 'adsfadsf', 858, '2014-11-01 19:56:00', 1, 0),
(560, 145, 990, 858, 2, 6, 548, 'adsfadsf', 858, '2014-11-01 19:56:01', 1, 0),
(561, 145, 990, 858, 2, 6, 548, 'adsfadsf', 858, '2014-11-01 19:56:10', 1, 0),
(562, 145, 990, 858, 2, 6, 548, 'adsfadsf', 858, '2014-11-01 19:56:21', 1, 0),
(563, 145, 990, 858, 2, 6, 548, 'asdfas asdfa', 858, '2014-11-01 19:57:17', 1, 0),
(567, 145, 990, 858, 2, 6, 548, 'adsf asdf adsfads', 858, '2014-11-02 08:27:51', 1, 0),
(568, 145, 990, 858, 2, 6, 548, 'asdf adsf ads f', 858, '2014-11-02 08:29:24', 1, 0),
(569, 145, 990, 858, 2, 6, 548, 'hahahhahahahahahahahahaa', 858, '2014-11-02 08:29:37', 1, 0),
(570, 145, 990, 858, 2, 6, 548, 'adsf adsf ads', 858, '2014-11-02 08:36:02', 1, 0),
(571, 145, 990, 858, 2, 6, 548, 'asdfa sdf', 858, '2014-11-02 08:37:28', 1, 0),
(572, 145, 990, 858, 2, 6, 548, 'adsfasf', 858, '2014-11-02 08:44:00', 1, 0),
(573, 145, 990, 858, 2, 6, 548, 'adsfasf', 858, '2014-11-02 08:44:48', 1, 0),
(574, 145, 990, 858, 2, 6, 548, 'adsfasf', 858, '2014-11-02 08:46:23', 1, 0),
(575, 145, 990, 858, 2, 6, 548, 'adsfasf', 858, '2014-11-02 08:47:08', 1, 0),
(576, 145, 990, 858, 2, 6, 548, 'adsfasf', 858, '2014-11-02 08:47:10', 1, 0),
(577, 145, 990, 858, 2, 6, 548, 'adsfasf', 858, '2014-11-02 08:48:35', 1, 0),
(578, 145, 990, 858, 2, 6, 548, 'ad asdf asdf adsf', 858, '2014-11-02 08:49:05', 1, 0),
(579, 145, 990, 858, 2, 6, 548, 'asdf asdf asdf asdfas', 858, '2014-11-02 16:21:58', 1, 0),
(580, 145, 990, 858, 2, 6, 548, 'a fasdf adsf asdf ad  ad', 858, '2014-11-03 11:54:19', 1, 0),
(581, 174, 990, 858, 2, 6, 434, 'a fs adf a sd', 858, '2014-11-03 11:54:49', 1, 0),
(582, 174, 990, 858, 2, 6, 434, 'afadsf adsf', 858, '2014-11-03 12:03:01', 1, 0),
(583, 190, 990, 858, 2, 6, 522, 'Still working on it. Waiting to cure', 858, '2014-11-05 14:41:30', 1, 0),
(584, 145, 990, 858, 2, 2, 548, 'asdf adsf', 858, '2014-11-11 10:14:26', 0, 0),
(585, 196, 990, 858, 2, 2, 524, 'asdf dsaf', 858, '2014-11-11 10:17:16', 0, 0),
(586, 190, 990, 858, 2, 2, 522, 'adsfasdf', 858, '2014-11-11 10:20:49', 0, 0),
(587, 201, 990, 858, 2, 2, 520, 'adf adf', 858, '2014-11-11 10:26:24', 0, 0),
(588, 216, 990, 858, 2, 2, 553, 'Please deal', 858, '2014-11-12 07:13:20', 0, 0),
(589, 217, 990, 858, 2, 2, 555, 'this is a test', 858, '2014-11-12 07:14:24', 0, 0),
(590, 218, 990, 858, 2, 2, 557, 'this is a test', 858, '2014-11-12 07:15:35', 0, 0),
(591, 219, 990, 858, 2, 2, 559, 'this is a test', 858, '2014-11-12 07:19:53', 0, 0),
(592, 220, 990, 858, 2, 2, 561, 'this is a test', 858, '2014-11-12 07:20:03', 0, 0),
(593, 121, 955, 834, 2, 2, 302, 'Visited You need to fix a 6 inc. Talked to stores. Pick items and deal immediately', 834, '2014-11-12 12:58:54', 0, 0),
(594, 148, 955, 834, 2, 6, 370, 'Tunnangoja gari toka 9 hadi sasa.', 834, '2014-11-12 13:01:31', 2, 0),
(595, 48, 918, 795, 2, 2, 128, 'provide the materials as per the stores request form on your desk. need to fix by cob', 795, '2014-11-13 12:14:31', 0, 0),
(596, 46, 918, 795, 2, 6, 122, 'Dug the site... now fixing new pipe', 795, '2014-11-13 12:17:59', 1, 0),
(597, 221, 900, 778, 2, 2, 565, 'adsfasdfasf', 778, '2014-11-14 09:31:50', 0, 0),
(598, 84, 936, 812, 2, 2, 217, 'This is a test', 812, '2015-01-22 13:45:02', 0, 0),
(599, 8, 3, 3, 2, 3, 20, 'This is a test', 3, '2015-03-11 13:01:40', 0, 0),
(600, 11, 3, 3, 2, 3, 48, 'tteettee', 3, '2015-03-11 13:05:09', 0, 0),
(601, 222, 3, 3, 2, 2, 570, 'This is a test', 3, '2015-03-11 16:21:30', 0, 0),
(602, 223, 3, 3, 2, 2, 572, 'This is test 2', 3, '2015-03-11 16:22:03', 0, 0),
(603, 222, 3, 3, 2, 3, 571, 'Please deal expedite', 3, '2015-03-11 18:46:40', 0, 0),
(604, 223, 3, 3, 2, 3, 573, '[ TRANSFERRED IN ] Deal haraka please', 3, '2015-03-11 19:08:41', 0, 0),
(605, 222, 3, 3, 2, 6, 574, 'This is a test', 3, '2015-03-11 19:15:29', 0, 0),
(606, 222, 3, 3, 2, 6, 574, 'asfasfasdf', 3, '2015-03-11 19:17:46', 0, 0),
(607, 222, 3, 3, 2, 6, 574, 'asdfasdfads', 3, '2015-03-11 19:19:45', 0, 0),
(608, 222, 3, 3, 2, 6, 574, 'popopopo', 3, '2015-03-11 19:21:56', 0, 0),
(609, 222, 3, 3, 2, 6, 574, 'test test test hoooray', 3, '2015-03-11 19:27:20', 0, 0),
(610, 222, 3, 3, 2, 6, 574, 'yup yup yup', 3, '2015-03-11 19:28:39', 0, 0),
(611, 222, 3, 3, 2, 6, 574, 'abce acen cads', 3, '2015-03-11 19:30:19', 0, 0),
(612, 222, 3, 3, 2, 6, 574, 'yup this is a test', 3, '2015-03-11 19:32:30', 0, 0),
(613, 222, 3, 3, 2, 6, 574, 'Replace the image', 3, '2015-03-12 07:15:05', 0, 0),
(614, 222, 3, 3, 2, 6, 574, 'changed the document', 3, '2015-03-12 07:17:23', 0, 0),
(615, 222, 3, 3, 2, 6, 574, 'this is another upload test', 3, '2015-03-12 07:18:48', 0, 0),
(616, 222, 3, 3, 2, 6, 574, 'adfasd', 3, '2015-03-12 12:24:14', 0, 0),
(617, 222, 3, 3, 2, 6, 574, 'adfa df as', 3, '2015-03-12 12:26:50', 0, 0),
(618, 222, 3, 3, 2, 6, 574, 'adsfadf', 3, '2015-03-12 12:27:38', 0, 0),
(619, 222, 3, 3, 2, 6, 574, 'fa f adfadsfad fa', 3, '2015-03-12 12:29:02', 0, 0),
(620, 222, 3, 3, 2, 6, 574, 'adfs asf asfas', 3, '2015-03-12 12:29:42', 0, 0),
(621, 222, 3, 3, 2, 6, 574, 'adf asf asf ads', 3, '2015-03-12 12:31:59', 0, 0),
(622, 222, 3, 3, 2, 6, 574, 'af asf af', 3, '2015-03-12 12:32:37', 0, 0),
(623, 222, 3, 3, 2, 6, 574, 'af asf af', 3, '2015-03-12 12:33:50', 0, 0),
(624, 222, 3, 3, 2, 6, 574, 'af asf af', 3, '2015-03-12 12:34:54', 0, 0),
(625, 222, 3, 3, 2, 6, 574, 'asdfasdf as', 3, '2015-03-12 12:35:11', 0, 0),
(626, 222, 3, 3, 2, 6, 574, 'asdf asdf adsf', 3, '2015-03-12 12:35:53', 0, 0),
(627, 222, 3, 3, 2, 6, 574, 'asdf asdf adsf', 3, '2015-03-12 12:36:24', 0, 0),
(628, 222, 3, 3, 2, 6, 574, 'a sfads fsaf', 3, '2015-03-12 12:37:00', 0, 0),
(629, 222, 3, 3, 2, 6, 574, 'afasdf asfsdf', 3, '2015-03-12 12:41:15', 0, 0),
(630, 222, 3, 3, 2, 6, 574, 'af adsf a', 3, '2015-03-12 12:42:41', 0, 0),
(631, 222, 3, 3, 2, 6, 574, 'pppp', 3, '2015-03-12 12:45:07', 0, 0),
(632, 223, 777, 699, 2, 6, 575, 'dssdds', 699, '2015-03-12 12:46:13', 0, 0),
(633, 223, 777, 699, 2, 6, 575, 'dssdds', 699, '2015-03-12 12:47:19', 0, 0),
(634, 223, 777, 699, 2, 6, 575, 'afasdf', 699, '2015-03-12 12:47:45', 0, 0),
(635, 223, 777, 699, 2, 6, 575, 'sg sdgds', 699, '2015-03-12 13:14:50', 0, 0),
(636, 223, 777, 699, 2, 6, 575, 'adf asdfs', 699, '2015-03-12 13:15:56', 0, 0),
(637, 223, 777, 699, 2, 6, 575, 'asf asf', 699, '2015-03-12 13:16:43', 0, 0),
(638, 223, 777, 699, 2, 6, 575, 'adf asdfadsf dasf', 699, '2015-03-12 13:20:05', 0, 0),
(639, 223, 777, 699, 2, 6, 575, 'adf asdfadsf dasf', 699, '2015-03-12 13:23:14', 0, 0),
(640, 223, 777, 699, 2, 6, 575, 'asdf asdfadsf', 699, '2015-03-12 13:25:49', 0, 0),
(641, 223, 777, 699, 2, 6, 575, 'sdf adsf adsfd', 699, '2015-03-12 13:26:54', 0, 0),
(642, 223, 777, 699, 2, 6, 575, 'sdf adsf adsfd', 699, '2015-03-12 13:29:01', 0, 0),
(643, 223, 777, 699, 2, 6, 575, 'afdasdf adfadf', 699, '2015-03-12 13:54:02', 0, 0),
(644, 223, 777, 699, 2, 6, 575, 'adfaf adfa f', 699, '2015-03-12 13:54:21', 0, 0),
(645, 223, 777, 699, 2, 6, 575, 'tetete tetete', 699, '2015-03-12 13:59:01', 0, 0),
(646, 223, 777, 699, 2, 6, 575, 'test test', 699, '2015-03-12 14:44:47', 0, 0),
(647, 223, 777, 699, 2, 6, 575, 'teeeee', 699, '2015-03-12 14:47:45', 0, 0),
(648, 223, 777, 699, 2, 6, 575, 'changed document', 699, '2015-03-12 14:48:33', 0, 0),
(649, 223, 777, 699, 2, 6, 575, 'changed doc', 699, '2015-03-12 14:49:00', 0, 0),
(650, 223, 777, 699, 2, 6, 575, 'aaaa', 699, '2015-03-12 14:50:00', 0, 0),
(651, 223, 777, 699, 2, 6, 575, 'aaaa', 699, '2015-03-12 14:50:41', 0, 0),
(652, 223, 777, 699, 2, 6, 575, 'aaaa', 699, '2015-03-12 14:50:54', 0, 0),
(653, 223, 777, 699, 2, 6, 575, 'adsf adsf', 699, '2015-03-12 14:51:08', 0, 0),
(654, 223, 777, 699, 2, 6, 575, 'haiya and now', 699, '2015-03-12 16:11:18', 0, 0),
(655, 223, 777, 699, 2, 6, 575, 'adsf adsfa', 699, '2015-03-12 16:12:33', 0, 0),
(656, 223, 777, 699, 2, 6, 575, 'sgsg s', 699, '2015-03-12 16:13:05', 0, 0),
(657, 223, 777, 699, 2, 6, 575, 'sgsg s', 699, '2015-03-12 16:14:17', 0, 0),
(658, 223, 777, 699, 2, 6, 575, 'asfa dsf ds', 699, '2015-03-12 16:18:28', 0, 0),
(659, 223, 777, 699, 2, 6, 575, 'ad fad a', 699, '2015-03-12 16:20:44', 0, 0),
(660, 223, 777, 699, 2, 6, 575, 'asdfa', 699, '2015-03-12 16:22:16', 0, 0),
(661, 223, 777, 699, 2, 6, 575, 'asdfasf ads', 699, '2015-03-12 16:22:28', 0, 0),
(662, 223, 777, 699, 2, 6, 575, 'adsf ads', 699, '2015-03-12 16:23:28', 0, 0),
(663, 223, 777, 699, 2, 6, 575, 'asdfasd', 699, '2015-03-12 16:23:43', 0, 0),
(664, 223, 777, 699, 2, 6, 575, 'sgsd', 699, '2015-03-12 16:26:37', 0, 0),
(665, 223, 777, 699, 2, 6, 575, 'adfa fa', 699, '2015-03-12 16:26:46', 0, 0),
(666, 223, 777, 699, 2, 6, 575, 'httyty', 699, '2015-03-12 16:29:56', 0, 0),
(667, 223, 777, 699, 2, 6, 575, 'adfa dsf', 699, '2015-03-12 16:36:03', 0, 0),
(668, 223, 777, 699, 2, 6, 575, 'adfa sdf', 699, '2015-03-12 16:37:54', 0, 0),
(669, 223, 777, 699, 2, 6, 575, 'asdfas df', 699, '2015-03-12 16:38:13', 0, 0),
(670, 223, 777, 699, 2, 6, 575, 'af adsf', 699, '2015-03-12 16:39:34', 0, 0),
(671, 223, 777, 699, 2, 6, 575, 'adfa', 699, '2015-03-12 16:40:08', 0, 0),
(672, 223, 777, 699, 2, 6, 575, '_POSTitem_.fet_validwfprocassetsaccess.', 699, '2015-03-12 16:40:38', 0, 0),
(673, 223, 777, 699, 2, 6, 575, '_POSTitem_.fet_validwfprocassetsaccess.', 699, '2015-03-12 16:40:52', 0, 0),
(674, 223, 777, 699, 2, 6, 575, 'this is a test', 699, '2015-03-12 16:42:23', 0, 0),
(675, 223, 777, 699, 2, 6, 575, 'This is a test', 699, '2015-03-12 16:43:22', 0, 0),
(676, 223, 777, 699, 2, 6, 575, 'test', 699, '2015-03-12 16:44:26', 0, 0),
(677, 223, 777, 699, 2, 6, 575, 'sawa sawa', 699, '2015-03-12 16:47:27', 0, 0),
(678, 223, 777, 699, 2, 6, 575, 'a afad', 699, '2015-03-12 16:53:43', 0, 0),
(679, 223, 777, 699, 2, 6, 575, 'Both checkbox and doc', 699, '2015-03-12 16:55:13', 0, 0),
(680, 223, 777, 699, 2, 6, 575, 'No Checkbox change Doc', 699, '2015-03-12 16:55:49', 0, 0),
(688, 223, 777, 699, 2, 6, 575, 'dsfdsfa', 699, '2015-03-12 17:17:47', 0, 0),
(689, 223, 777, 699, 2, 6, 575, 'testesetestswete', 699, '2015-03-12 17:21:17', 0, 0),
(690, 223, 777, 699, 2, 6, 575, 'adf adsf adsf', 699, '2015-03-12 17:21:38', 0, 0),
(695, 223, 777, 699, 2, 6, 575, 'adf', 699, '2015-03-12 17:27:29', 0, 0),
(696, 223, 777, 699, 2, 6, 575, 'adsf adsf', 699, '2015-03-12 17:45:46', 0, 0),
(697, 222, 3, 3, 2, 2, 574, 'experimenting', 3, '2015-03-12 17:53:46', 0, 0),
(698, 222, 777, 699, 2, 2, 576, 'Paid is CheckedrnDocument changed to LetterHead', 699, '2015-03-12 17:54:50', 0, 0),
(699, 222, 275, 249, 2, 6, 577, 'nwc18844', 249, '2015-03-12 18:52:08', 0, 0),
(700, 222, 275, 249, 2, 6, 577, 'nwc18844', 249, '2015-03-12 18:52:20', 0, 0),
(701, 222, 275, 249, 2, 6, 577, 'check', 249, '2015-03-13 11:27:29', 0, 0),
(702, 222, 275, 249, 2, 6, 577, 'see error', 249, '2015-03-13 11:27:58', 0, 0),
(703, 222, 275, 249, 2, 6, 577, 'Error must have gone', 249, '2015-03-13 11:28:54', 0, 0),
(704, 222, 275, 249, 2, 6, 577, 'hIiya Checkbox bado ina bug', 249, '2015-03-13 11:29:41', 0, 0),
(705, 222, 275, 249, 2, 6, 577, 'ACtivated checkbox', 249, '2015-03-13 11:30:08', 0, 0),
(706, 222, 275, 249, 2, 6, 577, '2SF150311HQ1', 249, '2015-03-13 15:33:47', 0, 0),
(707, 222, 275, 249, 2, 6, 577, 'check it out men', 249, '2015-03-13 16:01:17', 0, 0),
(708, 222, 275, 249, 2, 6, 577, 'tetete', 249, '2015-03-13 16:18:39', 0, 0),
(709, 222, 275, 249, 2, 6, 577, 'adf adsf', 249, '2015-03-13 16:28:36', 0, 0),
(710, 222, 275, 249, 2, 6, 577, 'adf adsf a', 249, '2015-03-13 16:37:22', 0, 0),
(711, 222, 275, 249, 2, 6, 577, 'asfsaf ds', 249, '2015-03-13 16:37:42', 0, 0),
(712, 222, 275, 249, 2, 6, 577, 'sdfs', 249, '2015-03-13 16:39:39', 0, 0),
(713, 222, 275, 249, 2, 6, 577, 'update nayo/3', 249, '2015-03-16 12:17:56', 0, 0),
(714, 222, 275, 249, 2, 6, 577, 'adsfasdf', 249, '2015-03-16 12:19:04', 0, 0),
(715, 222, 275, 249, 2, 6, 577, 'adsfasdf', 249, '2015-03-16 12:19:50', 0, 0),
(716, 222, 275, 249, 2, 6, 577, 'lololololo', 249, '2015-03-16 12:41:24', 0, 0),
(717, 222, 275, 249, 2, 6, 577, 'adf asdf adsf as', 249, '2015-03-16 13:06:00', 0, 0),
(718, 222, 275, 249, 2, 6, 577, 'asdf adsf', 249, '2015-03-16 13:10:14', 0, 0),
(719, 222, 275, 249, 2, 6, 577, 'asdf asd', 249, '2015-03-16 13:19:49', 0, 0),
(720, 222, 275, 249, 2, 6, 577, 'ads adsfad f', 249, '2015-03-16 13:21:17', 0, 0),
(721, 222, 275, 249, 2, 6, 577, 'no audit log', 249, '2015-03-16 15:12:57', 0, 0),
(722, 222, 275, 249, 2, 6, 577, 'adfasdf', 249, '2015-03-16 15:13:18', 0, 0),
(723, 222, 275, 249, 2, 6, 577, 'haiyaiyai', 249, '2015-03-16 15:42:28', 0, 0),
(724, 222, 275, 249, 2, 6, 577, 'asdfas fa dfasdf', 249, '2015-03-16 15:44:10', 0, 0),
(725, 222, 275, 249, 2, 6, 577, 'popopopo', 249, '2015-03-16 15:53:58', 0, 0),
(726, 222, 275, 249, 2, 6, 577, 'polllppllppp', 249, '2015-03-16 16:12:13', 0, 0),
(727, 222, 275, 249, 2, 6, 577, 'wtrwt', 249, '2015-03-16 16:16:02', 0, 0),
(728, 222, 275, 249, 2, 6, 577, 'etretre', 249, '2015-03-16 16:20:55', 0, 0),
(729, 222, 275, 249, 2, 6, 577, 'aafdfafds', 249, '2015-03-16 16:21:38', 0, 0),
(732, 222, 275, 249, 2, 6, 577, 'adsafsdf', 249, '2015-03-16 16:24:31', 0, 0),
(733, 222, 275, 249, 2, 6, 577, 'efefe', 249, '2015-03-16 16:52:54', 0, 0),
(734, 222, 275, 249, 2, 6, 577, 'wrew', 249, '2015-03-16 18:34:02', 0, 0),
(735, 222, 275, 249, 2, 6, 577, 'afd', 249, '2015-03-16 18:34:25', 0, 0),
(736, 224, 275, 249, 2, 2, 578, 'testing', 249, '2015-03-18 07:26:56', 0, 0),
(737, 225, 275, 249, 2, 2, 580, 'adf asdf', 249, '2015-03-18 07:53:33', 0, 0),
(738, 226, 275, 249, 2, 2, 582, 'adf asdf', 249, '2015-03-18 08:26:19', 0, 0),
(739, 227, 275, 249, 2, 2, 584, 'Haiya', 249, '2015-03-18 08:42:56', 0, 0),
(740, 228, 275, 249, 2, 2, 586, 'Haiya', 249, '2015-03-18 08:52:10', 0, 0),
(741, 222, 275, 249, 2, 6, 577, 'a fasdf', 249, '2015-03-18 11:42:34', 0, 0),
(742, 222, 275, 249, 2, 6, 577, 'sfasf', 249, '2015-03-19 16:54:47', 0, 0),
(743, 80, 275, 249, 2, 6, 206, 'aaf ads', 249, '2015-03-19 16:56:59', 0, 0),
(744, 222, 275, 249, 2, 6, 577, 'sgdsfg f', 249, '2015-03-19 16:57:42', 0, 0),
(745, 229, 228, 205, 2, 2, 588, 'Please forward for action', 205, '2015-03-24 19:56:38', 0, 0),
(747, 229, 240, 216, 2, 2, 589, 'this is ok kindly review for approval', 216, '2015-03-24 20:01:14', 0, 0),
(748, 229, 233, 210, 2, 9, 591, 'Please add this to a relevant batch', 210, '2015-03-24 20:04:21', 0, 0),
(749, 229, 240, 216, 2, 2, 592, 'Batch Number Added. Please forward', 216, '2015-03-24 20:07:18', 0, 0),
(750, 230, 228, 205, 2, 2, 594, 'bill for feb too high', 205, '2015-03-24 21:48:46', 0, 0),
(751, 231, 228, 205, 2, 2, 596, 'CrCS please deal', 205, '2015-03-25 10:20:30', 0, 0),
(753, 230, 240, 216, 2, 2, 595, 'plese review', 216, '2015-03-25 11:38:01', 0, 0),
(754, 231, 240, 216, 2, 6, 597, 'THis is an update', 216, '2015-04-21 15:13:49', 0, 0),
(755, 231, 240, 216, 2, 6, 597, 'this is an update', 216, '2015-04-21 15:14:11', 0, 0),
(756, 231, 240, 216, 2, 6, 597, 'This is a test', 216, '2015-04-21 15:15:05', 0, 0),
(757, 231, 240, 216, 2, 6, 597, 'This is an update', 216, '2015-04-21 15:16:23', 0, 0),
(760, 231, 240, 216, 2, 6, 597, 'testestes', 216, '2015-04-21 20:52:51', 0, 0),
(761, 231, 240, 216, 2, 6, 597, 'tytytytyt', 216, '2015-04-21 21:46:37', 0, 0),
(762, 231, 240, 216, 2, 6, 597, 'ooooo', 216, '2015-04-21 21:48:19', 0, 0),
(763, 231, 240, 216, 2, 6, 597, 'ppoopp', 216, '2015-04-21 21:50:44', 0, 0),
(764, 232, 228, 205, 2, 2, 600, 'Hi bill deal', 205, '2015-04-21 23:06:10', 0, 0),
(765, 233, 228, 205, 2, 2, 602, 'deal pls', 205, '2015-04-21 23:06:55', 0, 0),
(766, 234, 228, 205, 2, 2, 604, 'test', 205, '2015-04-22 05:55:49', 0, 0),
(767, 233, 240, 216, 2, 6, 603, 'Recovery in progress', 216, '2015-04-22 06:13:03', 0, 0),
(768, 233, 240, 216, 2, 2, 603, 'Please deal', 216, '2015-04-22 06:17:46', 0, 0),
(770, 232, 240, 216, 2, 6, 601, 'Am working on it... seems to hi', 216, '2015-04-22 07:36:12', 0, 0),
(771, 232, 240, 216, 2, 6, 601, 'adsfadsfadsfads', 216, '2015-04-22 07:36:33', 0, 0),
(772, 235, 3, 3, 2, 2, 608, 'Test Ticket for Batching', 3, '2015-04-22 08:43:26', 0, 0),
(773, 236, 3, 3, 2, 2, 610, 'Test task for batching', 3, '2015-04-22 08:44:02', 0, 0),
(774, 237, 3, 3, 2, 2, 612, 'Test Task', 3, '2015-04-22 08:44:39', 0, 0),
(775, 238, 3, 3, 2, 2, 614, 'This is a batch test', 3, '2015-04-22 08:45:15', 0, 0),
(776, 239, 3, 3, 2, 2, 616, 'Test Batching Task', 3, '2015-04-22 08:46:05', 0, 0),
(777, 240, 3, 3, 2, 2, 618, 'Test Task Batching', 3, '2015-04-22 08:53:42', 0, 0),
(778, 241, 3, 3, 2, 2, 620, 'Batch this task pls', 3, '2015-04-22 08:54:16', 0, 0),
(779, 242, 3, 3, 2, 2, 622, 'Please batch it', 3, '2015-04-22 08:54:54', 0, 0),
(780, 243, 3, 3, 2, 2, 624, 'bath it pls', 3, '2015-04-22 08:56:03', 0, 0),
(781, 244, 3, 3, 2, 2, 626, 'Please batch and calc', 3, '2015-04-22 08:56:50', 0, 0),
(782, 237, 492, 335, 2, 2, 613, 'kindly check', 335, '2015-04-22 08:57:13', 0, 0),
(783, 245, 3, 3, 2, 2, 629, 'batch and calc', 3, '2015-04-22 08:57:28', 0, 0),
(784, 246, 3, 3, 2, 2, 631, 'Batch please and calculate', 3, '2015-04-22 08:58:23', 0, 0),
(785, 238, 806, 690, 2, 2, 615, 'plse batch', 690, '2015-04-22 09:02:08', 0, 0),
(786, 236, 1011, 104, 2, 2, 611, 'done', 104, '2015-04-22 09:03:16', 0, 0),
(787, 237, 806, 690, 2, 2, 628, 'done', 690, '2015-04-22 09:05:08', 0, 0),
(788, 238, 492, 335, 2, 2, 633, 'done', 335, '2015-04-22 09:07:13', 0, 0),
(789, 241, 516, 595, 2, 2, 621, 'proposed credit of 10000', 595, '2015-04-22 09:08:03', 0, 0),
(790, 243, 339, 185, 2, 2, 625, 'Done', 185, '2015-04-22 09:11:02', 0, 0),
(791, 238, 806, 690, 2, 2, 636, 'changed', 690, '2015-04-22 09:12:30', 0, 0),
(793, 239, 516, 595, 2, 2, 617, 'proposed credit of 550000', 595, '2015-04-22 09:14:26', 0, 0),
(794, 234, 240, 216, 2, 6, 605, 'this is it', 216, '2015-04-22 09:21:58', 0, 0),
(795, 234, 240, 216, 2, 6, 605, 'test', 216, '2015-04-22 09:22:10', 0, 0),
(796, 234, 240, 216, 2, 6, 605, 'batch moved', 216, '2015-04-26 17:46:34', 0, 0),
(797, 232, 240, 216, 2, 6, 601, 'ooppop', 216, '2015-04-26 17:49:31', 0, 0),
(798, 232, 240, 216, 2, 6, 601, 'moved batch', 216, '2015-04-26 17:58:23', 0, 0),
(799, 234, 240, 216, 2, 6, 605, 'test movement', 216, '2015-04-26 18:21:36', 0, 0),
(800, 234, 240, 216, 2, 6, 605, 'moved to 008', 216, '2015-04-26 18:39:08', 0, 0),
(801, 232, 240, 216, 2, 6, 601, 'ppppp', 216, '2015-04-26 19:38:31', 0, 0),
(802, 234, 240, 216, 2, 6, 605, 'OK', 216, '2015-04-26 20:07:23', 0, 0),
(803, 234, 240, 216, 2, 6, 605, 'this is another test', 216, '2015-04-27 06:53:36', 0, 0),
(804, 234, 240, 216, 2, 6, 605, 'move to anothe rbatch', 216, '2015-04-27 06:53:59', 0, 0),
(805, 234, 240, 216, 2, 6, 605, 'update the batch', 216, '2015-04-27 06:57:48', 0, 0),
(806, 234, 240, 216, 2, 6, 605, 'this is an update', 216, '2015-04-27 07:14:26', 0, 0),
(807, 234, 240, 216, 2, 6, 605, 'Tets eststs', 216, '2015-04-27 07:16:40', 0, 0),
(809, 232, 240, 216, 2, 6, 601, 'moved it', 216, '2015-04-27 11:21:24', 0, 0),
(810, 234, 240, 216, 2, 6, 605, 'moved batch', 216, '2015-04-27 11:26:19', 0, 0),
(811, 231, 240, 216, 2, 6, 597, 'move it', 216, '2015-04-27 11:31:35', 0, 0),
(812, 234, 240, 216, 2, 6, 605, 'pass it back', 216, '2015-04-27 11:32:15', 0, 0),
(813, 234, 240, 216, 2, 6, 605, 'move it tii', 216, '2015-04-27 11:38:12', 0, 0),
(814, 232, 240, 216, 2, 2, 601, 'Dan please look at this new batch', 216, '2015-04-27 11:49:18', 0, 0),
(815, 234, 240, 216, 2, 6, 605, 'moved batch', 216, '2015-04-27 11:57:21', 0, 0),
(816, 231, 240, 216, 2, 6, 597, 'Done twice', 216, '2015-04-27 11:57:35', 0, 0),
(817, 234, 240, 216, 2, 2, 605, 'shika hyi', 216, '2015-04-27 12:52:52', 0, 0),
(818, 247, 3, 3, 2, 2, 644, 'testt', 3, '2015-04-27 16:24:22', 0, 0),
(819, 247, 803, 184, 2, 2, 645, 'please deal', 184, '2015-04-27 16:25:54', 0, 0),
(820, 247, 240, 216, 2, 2, 646, 'Please deal', 216, '2015-04-28 11:10:23', 0, 0),
(821, 247, 3, 3, 2, 6, 647, 'adfadsf', 3, '2015-04-28 11:33:52', 0, 0),
(822, 234, 3, 3, 2, 2, 643, 'missing batc', 3, '2015-04-28 13:16:21', 0, 0),
(823, 232, 3, 3, 2, 2, 642, 'pleae batch', 3, '2015-04-28 13:16:38', 0, 0),
(824, 145, 3, 3, 2, 2, 549, 'batch pls', 3, '2015-04-28 13:18:17', 0, 0),
(825, 11, 3, 3, 2, 2, 569, 'batch pls', 3, '2015-04-28 13:18:38', 0, 0),
(827, 11, 240, 216, 2, 2, 651, 'testssd', 216, '2015-04-28 14:22:49', 0, 0),
(829, 145, 240, 216, 2, 2, 650, 'This is a test..it should be 0002', 216, '2015-05-20 11:59:09', 0, 0),
(834, 11, 240, 216, 2, 2, 653, '001 is the bach', 216, '2015-05-20 12:12:04', 0, 0),
(835, 234, 240, 216, 2, 2, 648, 'This is batch 005', 216, '2015-05-20 12:12:28', 0, 0),
(836, 234, 3, 3, 2, 2, 661, 'this is 005', 3, '2015-05-20 12:13:03', 0, 0),
(837, 11, 3, 3, 2, 2, 660, 'I have changed this to Batch HQ/2015/05/0003', 3, '2015-05-20 12:13:41', 0, 0),
(838, 248, 228, 205, 2, 2, 664, 'fas dfas fs adsf  a', 205, '2015-05-21 12:08:25', 0, 0),
(839, 145, 3, 3, 2, 2, 655, 'adsf adsf as', 3, '2015-06-19 09:41:47', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `wftskupdates_class`
--

CREATE TABLE IF NOT EXISTS `wftskupdates_class` (
  `idwftskupdates_class` int(2) NOT NULL AUTO_INCREMENT,
  `wftskupdates_classlbl` varchar(35) NOT NULL,
  `wftskupdates_classdesc` varchar(250) NOT NULL,
  `sysprofiles_ idsysprofiles` int(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idwftskupdates_class`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `wftskupdates_class`
--

INSERT INTO `wftskupdates_class` (`idwftskupdates_class`, `wftskupdates_classlbl`, `wftskupdates_classdesc`, `sysprofiles_ idsysprofiles`) VALUES
(1, 'Work in Progress', 'Work is ongoing on the issue', 0),
(2, 'Delayed Transport', 'Delay because there is no transport ', 0),
(3, 'Lack of Protective Clothing', 'Lack of gloves or protective clothing', 0),
(4, 'No Meters', 'Meters are over in the stores', 0),
(5, 'Unavailable Equipment', 'Critical equipment is not available', 0),
(6, 'Customer Unreachable', 'Customer cannot be reached', 0),
(7, 'Forwarded to HQ', 'Work at HQ Awaiting Action', 0);

-- --------------------------------------------------------

--
-- Table structure for table `wftype`
--

CREATE TABLE IF NOT EXISTS `wftype` (
  `idwftype` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `wftypelbl` varchar(50) DEFAULT NULL,
  `wftypedesc` varchar(250) DEFAULT NULL,
  `recstatus` tinyint(1) NOT NULL,
  PRIMARY KEY (`idwftype`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `wftype`
--

INSERT INTO `wftype` (`idwftype`, `wftypelbl`, `wftypedesc`, `recstatus`) VALUES
(1, 'Tickets', 'For all Tickets including Mobile and Manually Keyed In', 1),
(2, 'Other ', 'Workflows that have nothing to do with tickets', 1);

-- --------------------------------------------------------

--
-- Table structure for table `wfworkingdays`
--

CREATE TABLE IF NOT EXISTS `wfworkingdays` (
  `idwfworkingdays` int(1) unsigned NOT NULL AUTO_INCREMENT,
  `workingdays` varchar(15) DEFAULT NULL,
  `workingdaysdesc` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`idwfworkingdays`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `wfworkingdays`
--

INSERT INTO `wfworkingdays` (`idwfworkingdays`, `workingdays`, `workingdaysdesc`) VALUES
(1, 'Monday - Friday', ''),
(2, 'Saturdays', 'To disable, enter 00:00 on both From and To'),
(3, 'Sundays', 'To disable, enter 00:00 on both From and To');

-- --------------------------------------------------------

--
-- Table structure for table `wfworkinghrs`
--

CREATE TABLE IF NOT EXISTS `wfworkinghrs` (
  `idwfworkinghrs` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `wftskflow_idwftskflow` int(11) unsigned NOT NULL,
  `wfworkingdays_idwfworkingdays` int(1) unsigned NOT NULL,
  `time_earliest` time DEFAULT '00:00:00',
  `time_latest` time DEFAULT '00:00:00',
  `notapplicable` tinyint(1) unsigned DEFAULT '0',
  PRIMARY KEY (`idwfworkinghrs`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1139 ;

--
-- Dumping data for table `wfworkinghrs`
--

INSERT INTO `wfworkinghrs` (`idwfworkinghrs`, `wftskflow_idwftskflow`, `wfworkingdays_idwfworkingdays`, `time_earliest`, `time_latest`, `notapplicable`) VALUES
(1, 1, 1, '08:00:00', '16:30:00', 0),
(2, 1, 2, '09:00:00', '12:30:00', 0),
(3, 1, 3, '09:00:00', '12:30:00', 0),
(289, 17, 3, '09:00:00', '12:30:00', 0),
(288, 17, 2, '09:00:00', '12:30:00', 0),
(287, 17, 1, '08:00:00', '16:30:00', 0),
(286, 16, 3, '09:00:00', '12:30:00', 0),
(285, 16, 2, '09:00:00', '12:30:00', 0),
(284, 16, 1, '08:00:00', '16:30:00', 0),
(334, 33, 3, '09:00:00', '12:30:00', 0),
(333, 33, 2, '09:00:00', '12:30:00', 0),
(332, 33, 1, '08:00:00', '16:30:00', 0),
(331, 32, 3, '00:00:00', '00:00:00', 0),
(330, 32, 2, '00:00:00', '00:00:00', 0),
(329, 32, 1, '08:00:00', '17:00:00', 0),
(328, 31, 3, '00:00:00', '00:00:00', 0),
(327, 31, 2, '00:00:00', '00:00:00', 0),
(326, 31, 1, '08:00:00', '17:00:00', 0),
(325, 30, 3, '00:00:00', '00:00:00', 0),
(324, 30, 2, '00:00:00', '00:00:00', 0),
(323, 30, 1, '08:00:00', '17:00:00', 0),
(322, 29, 3, '00:00:00', '00:00:00', 0),
(321, 29, 2, '00:00:00', '00:00:00', 0),
(320, 29, 1, '08:00:00', '17:00:00', 0),
(319, 28, 3, '09:00:00', '12:30:00', 0),
(318, 28, 2, '09:00:00', '12:30:00', 0),
(317, 28, 1, '08:00:00', '16:30:00', 0),
(316, 27, 3, '09:00:00', '12:30:00', 0),
(315, 27, 2, '09:00:00', '12:30:00', 0),
(314, 27, 1, '08:00:00', '16:30:00', 0),
(313, 26, 3, '09:00:00', '12:30:00', 0),
(312, 26, 2, '09:00:00', '12:30:00', 0),
(311, 26, 1, '08:00:00', '16:30:00', 0),
(310, 25, 3, '09:00:00', '12:30:00', 0),
(309, 25, 2, '09:00:00', '12:30:00', 0),
(308, 25, 1, '08:00:00', '16:30:00', 0),
(307, 23, 3, '09:00:00', '12:30:00', 0),
(306, 23, 2, '09:00:00', '12:30:00', 0),
(305, 23, 1, '08:00:00', '16:30:00', 0),
(304, 22, 3, '09:00:00', '12:30:00', 0),
(303, 22, 2, '09:00:00', '12:30:00', 0),
(302, 22, 1, '08:00:00', '16:30:00', 0),
(301, 21, 3, '09:00:00', '12:30:00', 0),
(300, 21, 2, '09:00:00', '12:30:00', 0),
(299, 21, 1, '08:00:00', '16:30:00', 0),
(298, 20, 3, '09:00:00', '12:30:00', 0),
(297, 20, 2, '09:00:00', '12:30:00', 0),
(296, 20, 1, '08:00:00', '16:30:00', 0),
(295, 19, 3, '09:00:00', '12:30:00', 0),
(294, 19, 2, '09:00:00', '12:30:00', 0),
(293, 19, 1, '08:00:00', '16:30:00', 0),
(292, 18, 3, '09:00:00', '12:30:00', 0),
(291, 18, 2, '09:00:00', '12:30:00', 0),
(290, 18, 1, '08:00:00', '16:30:00', 0),
(283, 15, 3, '09:00:00', '12:30:00', 0),
(282, 15, 2, '09:00:00', '12:30:00', 0),
(281, 15, 1, '08:00:00', '16:30:00', 0),
(553, 97, 3, '00:00:00', '00:00:00', 0),
(552, 97, 2, '00:00:00', '00:00:00', 0),
(551, 97, 1, '08:00:00', '17:00:00', 0),
(550, 96, 3, '00:00:00', '00:00:00', 0),
(549, 96, 2, '09:00:00', '12:30:00', 0),
(548, 96, 1, '08:00:00', '16:30:00', 0),
(547, 95, 3, '09:00:00', '12:30:00', 0),
(546, 95, 2, '09:00:00', '12:30:00', 0),
(545, 95, 1, '08:00:00', '16:30:00', 0),
(544, 94, 3, '00:00:00', '00:00:00', 0),
(543, 94, 2, '00:00:00', '00:00:00', 0),
(542, 94, 1, '08:00:00', '16:30:00', 0),
(541, 93, 3, '08:00:00', '17:00:00', 0),
(540, 93, 2, '08:00:00', '17:00:00', 0),
(539, 93, 1, '08:00:00', '17:00:00', 0),
(478, 33, 3, '09:00:00', '12:30:00', 0),
(477, 33, 2, '09:00:00', '12:30:00', 0),
(476, 33, 1, '08:00:00', '16:30:00', 0),
(538, 92, 3, '09:00:00', '12:30:00', 0),
(537, 92, 2, '09:00:00', '12:30:00', 0),
(536, 92, 1, '08:00:00', '16:30:00', 0),
(535, 91, 3, '00:00:00', '00:00:00', 0),
(534, 91, 2, '00:00:00', '00:00:00', 0),
(533, 91, 1, '08:00:00', '17:00:00', 0),
(532, 90, 3, '00:00:00', '00:00:00', 0),
(531, 90, 2, '00:00:00', '00:00:00', 0),
(530, 90, 1, '08:00:00', '17:00:00', 0),
(529, 89, 3, '00:00:00', '00:00:00', 0),
(528, 89, 2, '09:00:00', '12:30:00', 0),
(527, 89, 1, '08:00:00', '17:00:00', 0),
(526, 88, 3, '00:00:00', '00:00:00', 0),
(525, 88, 2, '09:00:00', '12:30:00', 0),
(524, 88, 1, '08:00:00', '17:00:00', 0),
(523, 87, 3, '00:00:00', '00:00:00', 0),
(522, 87, 2, '09:00:00', '12:30:00', 0),
(521, 87, 1, '08:00:00', '17:00:00', 0),
(520, 86, 3, '00:00:00', '00:00:00', 0),
(519, 86, 2, '09:00:00', '12:30:00', 0),
(518, 86, 1, '08:00:00', '17:00:00', 0),
(517, 85, 3, '00:00:00', '00:00:00', 0),
(516, 85, 2, '00:00:00', '00:00:00', 0),
(515, 85, 1, '08:00:00', '17:00:00', 0),
(514, 84, 3, '00:00:00', '00:00:00', 0),
(513, 84, 2, '00:00:00', '00:00:00', 0),
(512, 84, 1, '08:00:00', '17:00:00', 0),
(511, 83, 3, '00:00:00', '00:00:00', 0),
(510, 83, 2, '00:00:00', '00:00:00', 0),
(509, 83, 1, '08:00:00', '16:30:00', 0),
(508, 82, 3, '09:00:00', '12:30:00', 0),
(507, 82, 2, '09:00:00', '12:30:00', 0),
(506, 82, 1, '08:00:00', '16:30:00', 0),
(505, 81, 3, '09:00:00', '12:30:00', 0),
(504, 81, 2, '09:00:00', '12:30:00', 0),
(503, 81, 1, '08:00:00', '16:30:00', 0),
(502, 80, 3, '00:00:00', '00:00:00', 0),
(501, 80, 2, '09:00:00', '12:30:00', 0),
(500, 80, 1, '08:00:00', '17:00:00', 0),
(499, 79, 3, '00:00:00', '00:00:00', 0),
(498, 79, 2, '09:00:00', '12:30:00', 0),
(497, 79, 1, '08:00:00', '17:00:00', 0),
(496, 78, 3, '00:00:00', '00:00:00', 0),
(495, 78, 2, '09:00:00', '12:30:00', 0),
(494, 78, 1, '08:00:00', '16:30:00', 0),
(493, 77, 3, '09:00:00', '12:30:00', 0),
(492, 77, 2, '09:00:00', '12:30:00', 0),
(491, 77, 1, '08:00:00', '17:00:00', 0),
(490, 76, 3, '09:00:00', '12:30:00', 0),
(489, 76, 2, '09:00:00', '12:30:00', 0),
(488, 76, 1, '08:00:00', '17:00:00', 0),
(487, 75, 3, '00:00:00', '00:00:00', 0),
(486, 75, 2, '00:00:00', '00:00:00', 0),
(485, 75, 1, '08:00:00', '16:30:00', 0),
(484, 73, 3, '09:00:00', '12:30:00', 0),
(483, 73, 2, '09:00:00', '12:30:00', 0),
(482, 73, 1, '08:00:00', '16:30:00', 0),
(481, 73, 3, '09:00:00', '12:30:00', 0),
(480, 73, 2, '09:00:00', '12:30:00', 0),
(479, 73, 1, '08:00:00', '16:30:00', 0),
(475, 74, 3, '09:00:00', '12:30:00', 0),
(474, 74, 2, '09:00:00', '12:30:00', 0),
(473, 74, 1, '08:00:00', '16:30:00', 0),
(472, 33, 3, '09:00:00', '12:30:00', 0),
(471, 33, 2, '09:00:00', '12:30:00', 0),
(470, 33, 1, '08:00:00', '16:30:00', 0),
(469, 33, 3, '09:00:00', '12:30:00', 0),
(468, 33, 2, '09:00:00', '12:30:00', 0),
(467, 33, 1, '08:00:00', '16:30:00', 0),
(466, 33, 3, '09:00:00', '12:30:00', 0),
(465, 33, 2, '09:00:00', '12:30:00', 0),
(464, 33, 1, '08:00:00', '16:30:00', 0),
(463, 33, 3, '09:00:00', '12:30:00', 0),
(462, 33, 2, '09:00:00', '12:30:00', 0),
(461, 33, 1, '08:00:00', '16:30:00', 0),
(428, 65, 1, '08:00:00', '16:30:00', 0),
(429, 65, 2, '09:00:00', '12:30:00', 0),
(430, 65, 3, '09:00:00', '12:30:00', 0),
(460, 33, 3, '09:00:00', '12:30:00', 0),
(459, 33, 2, '09:00:00', '12:30:00', 0),
(458, 33, 1, '08:00:00', '16:30:00', 0),
(434, 67, 1, '08:00:00', '17:00:00', 0),
(435, 67, 2, '00:00:00', '00:00:00', 0),
(436, 67, 3, '00:00:00', '00:00:00', 0),
(437, 68, 1, '08:00:00', '16:30:00', 0),
(438, 68, 2, '00:00:00', '00:00:00', 0),
(439, 68, 3, '00:00:00', '00:00:00', 0),
(440, 69, 1, '08:00:00', '17:00:00', 0),
(441, 69, 2, '00:00:00', '00:00:00', 0),
(442, 69, 3, '00:00:00', '00:00:00', 0),
(443, 70, 1, '08:00:00', '16:30:00', 0),
(444, 70, 2, '09:00:00', '12:30:00', 0),
(445, 70, 3, '09:00:00', '12:30:00', 0),
(446, 71, 1, '08:00:00', '16:30:00', 0),
(447, 71, 2, '00:00:00', '00:00:00', 0),
(448, 71, 3, '00:00:00', '00:00:00', 0),
(457, 33, 3, '09:00:00', '12:30:00', 0),
(456, 33, 2, '09:00:00', '12:30:00', 0),
(455, 33, 1, '08:00:00', '16:30:00', 0),
(452, 73, 1, '08:00:00', '16:30:00', 0),
(453, 73, 2, '09:00:00', '12:30:00', 0),
(454, 73, 3, '09:00:00', '12:30:00', 0),
(554, 98, 1, '08:00:00', '17:00:00', 0),
(555, 98, 2, '00:00:00', '00:00:00', 0),
(556, 98, 3, '00:00:00', '00:00:00', 0),
(557, 99, 1, '08:00:00', '16:30:00', 0),
(558, 99, 2, '00:00:00', '00:00:00', 0),
(559, 99, 3, '00:00:00', '00:00:00', 0),
(560, 100, 1, '08:00:00', '16:30:00', 0),
(561, 100, 2, '00:00:00', '00:00:00', 0),
(562, 100, 3, '00:00:00', '00:00:00', 0),
(563, 101, 1, '08:00:00', '16:30:00', 0),
(564, 101, 2, '00:00:00', '00:00:00', 0),
(565, 101, 3, '00:00:00', '00:00:00', 0),
(566, 102, 1, '08:00:00', '16:30:00', 0),
(567, 102, 2, '00:00:00', '00:00:00', 0),
(568, 102, 3, '00:00:00', '00:00:00', 0),
(569, 103, 1, '08:00:00', '16:30:00', 0),
(570, 103, 2, '09:00:00', '12:30:00', 0),
(571, 103, 3, '09:00:00', '12:30:00', 0),
(572, 104, 1, '08:00:00', '16:30:00', 0),
(573, 104, 2, '09:00:00', '12:30:00', 0),
(574, 104, 3, '09:00:00', '12:30:00', 0),
(575, 105, 1, '08:00:00', '17:00:00', 0),
(576, 105, 2, '00:00:00', '00:00:00', 0),
(577, 105, 3, '00:00:00', '00:00:00', 0),
(578, 106, 1, '08:00:00', '17:00:00', 0),
(579, 106, 2, '00:00:00', '00:00:00', 0),
(580, 106, 3, '00:00:00', '00:00:00', 0),
(581, 107, 1, '08:00:00', '16:30:00', 0),
(582, 107, 2, '09:00:00', '12:30:00', 0),
(583, 107, 3, '09:00:00', '12:30:00', 0),
(584, 108, 1, '08:00:00', '17:00:00', 0),
(585, 108, 2, '00:00:00', '00:00:00', 0),
(586, 108, 3, '00:00:00', '00:00:00', 0),
(587, 109, 1, '08:00:00', '17:00:00', 0),
(588, 109, 2, '00:00:00', '00:00:00', 0),
(589, 109, 3, '00:00:00', '00:00:00', 0),
(590, 110, 1, '08:00:00', '16:30:00', 0),
(591, 110, 2, '00:00:00', '00:00:00', 0),
(592, 110, 3, '00:00:00', '00:00:00', 0),
(593, 111, 1, '08:00:00', '16:30:00', 0),
(594, 111, 2, '00:00:00', '00:00:00', 0),
(595, 111, 3, '00:00:00', '00:00:00', 0),
(596, 112, 1, '08:00:00', '16:30:00', 0),
(597, 112, 2, '00:00:00', '00:00:00', 0),
(598, 112, 3, '00:00:00', '00:00:00', 0),
(599, 113, 1, '08:00:00', '16:30:00', 0),
(600, 113, 2, '09:00:00', '12:30:00', 0),
(601, 113, 3, '09:00:00', '12:30:00', 0),
(602, 114, 1, '08:00:00', '16:30:00', 0),
(603, 114, 2, '00:00:00', '00:00:00', 0),
(604, 114, 3, '00:00:00', '00:00:00', 0),
(605, 115, 1, '08:00:00', '16:30:00', 0),
(606, 115, 2, '00:00:00', '00:00:00', 0),
(607, 115, 3, '00:00:00', '00:00:00', 0),
(608, 116, 1, '08:00:00', '17:00:00', 0),
(609, 116, 2, '00:00:00', '00:00:00', 0),
(610, 116, 3, '00:00:00', '00:00:00', 0),
(611, 117, 1, '08:00:00', '17:00:00', 0),
(612, 117, 2, '00:00:00', '00:00:00', 0),
(613, 117, 3, '00:00:00', '00:00:00', 0),
(614, 118, 1, '08:00:00', '17:00:00', 0),
(615, 118, 2, '00:00:00', '00:00:00', 0),
(616, 118, 3, '00:00:00', '00:00:00', 0),
(617, 119, 1, '08:00:00', '16:30:00', 0),
(618, 119, 2, '09:00:00', '12:30:00', 0),
(619, 119, 3, '09:00:00', '12:30:00', 0),
(620, 120, 1, '08:00:00', '16:30:00', 0),
(621, 120, 2, '00:00:00', '00:00:00', 0),
(622, 120, 3, '00:00:00', '00:00:00', 0),
(623, 121, 1, '08:00:00', '16:30:00', 0),
(624, 121, 2, '00:00:00', '00:00:00', 0),
(625, 121, 3, '00:00:00', '00:00:00', 0),
(626, 122, 1, '08:00:00', '16:30:00', 0),
(627, 122, 2, '00:00:00', '00:00:00', 0),
(628, 122, 3, '00:00:00', '00:00:00', 0),
(629, 123, 1, '08:00:00', '16:30:00', 0),
(630, 123, 2, '00:00:00', '00:00:00', 0),
(631, 123, 3, '00:00:00', '00:00:00', 0),
(632, 124, 1, '08:00:00', '16:30:00', 0),
(633, 124, 2, '00:00:00', '00:00:00', 0),
(634, 124, 3, '00:00:00', '00:00:00', 0),
(635, 125, 1, '08:00:00', '16:30:00', 0),
(636, 125, 2, '09:00:00', '12:30:00', 0),
(637, 125, 3, '09:00:00', '12:30:00', 0),
(638, 126, 1, '08:00:00', '17:00:00', 0),
(639, 126, 2, '00:00:00', '00:00:00', 0),
(640, 126, 3, '00:00:00', '00:00:00', 0),
(641, 127, 1, '08:00:00', '16:30:00', 0),
(642, 127, 2, '00:00:00', '00:00:00', 0),
(643, 127, 3, '00:00:00', '00:00:00', 0),
(644, 128, 1, '08:00:00', '17:00:00', 0),
(645, 128, 2, '00:00:00', '00:00:00', 0),
(646, 128, 3, '00:00:00', '00:00:00', 0),
(647, 129, 1, '08:00:00', '16:30:00', 0),
(648, 129, 2, '00:00:00', '00:00:00', 0),
(649, 129, 3, '00:00:00', '00:00:00', 0),
(650, 130, 1, '08:00:00', '16:30:00', 0),
(651, 130, 2, '09:00:00', '12:30:00', 0),
(652, 130, 3, '09:00:00', '12:30:00', 0),
(653, 131, 1, '08:00:00', '16:30:00', 0),
(654, 131, 2, '09:00:00', '12:30:00', 0),
(655, 131, 3, '09:00:00', '12:30:00', 0),
(656, 132, 1, '08:00:00', '16:30:00', 0),
(657, 132, 2, '00:00:00', '00:00:00', 0),
(658, 132, 3, '00:00:00', '00:00:00', 0),
(659, 133, 1, '08:00:00', '16:30:00', 0),
(660, 133, 2, '00:00:00', '00:00:00', 0),
(661, 133, 3, '00:00:00', '00:00:00', 0),
(662, 134, 1, '08:00:00', '16:30:00', 0),
(663, 134, 2, '00:00:00', '00:00:00', 0),
(664, 134, 3, '00:00:00', '00:00:00', 0),
(665, 135, 1, '08:00:00', '16:30:00', 0),
(666, 135, 2, '00:00:00', '00:00:00', 0),
(667, 135, 3, '00:00:00', '00:00:00', 0),
(668, 136, 1, '08:00:00', '16:30:00', 0),
(669, 136, 2, '09:00:00', '12:30:00', 0),
(670, 136, 3, '09:00:00', '12:30:00', 0),
(671, 137, 1, '08:00:00', '16:30:00', 0),
(672, 137, 2, '09:00:00', '12:30:00', 0),
(673, 137, 3, '09:00:00', '12:30:00', 0),
(674, 138, 1, '08:00:00', '17:00:00', 0),
(675, 138, 2, '00:00:00', '00:00:00', 0),
(676, 138, 3, '00:00:00', '00:00:00', 0),
(677, 139, 1, '08:00:00', '17:00:00', 0),
(678, 139, 2, '00:00:00', '00:00:00', 0),
(679, 139, 3, '00:00:00', '00:00:00', 0),
(680, 140, 1, '08:00:00', '16:30:00', 0),
(681, 140, 2, '09:00:00', '12:30:00', 0),
(682, 140, 3, '09:00:00', '12:30:00', 0),
(683, 141, 1, '08:00:00', '17:00:00', 0),
(684, 141, 2, '00:00:00', '00:00:00', 0),
(685, 141, 3, '00:00:00', '00:00:00', 0),
(686, 142, 1, '08:00:00', '16:30:00', 0),
(687, 142, 2, '09:00:00', '12:30:00', 0),
(688, 142, 3, '09:00:00', '12:30:00', 0),
(689, 143, 1, '08:00:00', '17:00:00', 0),
(690, 143, 2, '00:00:00', '00:00:00', 0),
(691, 143, 3, '00:00:00', '00:00:00', 0),
(692, 144, 1, '08:00:00', '16:30:00', 0),
(693, 144, 2, '00:00:00', '00:00:00', 0),
(694, 144, 3, '00:00:00', '00:00:00', 0),
(695, 145, 1, '08:00:00', '16:30:00', 0),
(696, 145, 2, '00:00:00', '00:00:00', 0),
(697, 145, 3, '00:00:00', '00:00:00', 0),
(698, 146, 1, '08:00:00', '17:00:00', 0),
(699, 146, 2, '00:00:00', '00:00:00', 0),
(700, 146, 3, '00:00:00', '00:00:00', 0),
(701, 147, 1, '08:00:00', '16:30:00', 0),
(702, 147, 2, '00:00:00', '00:00:00', 0),
(703, 147, 3, '00:00:00', '00:00:00', 0),
(704, 148, 1, '08:00:00', '17:00:00', 0),
(705, 148, 2, '00:00:00', '00:00:00', 0),
(706, 148, 3, '00:00:00', '00:00:00', 0),
(707, 149, 1, '08:00:00', '17:00:00', 0),
(708, 149, 2, '00:00:00', '00:00:00', 0),
(709, 149, 3, '00:00:00', '00:00:00', 0),
(710, 150, 1, '08:00:00', '17:00:00', 0),
(711, 150, 2, '00:00:00', '00:00:00', 0),
(712, 150, 3, '00:00:00', '00:00:00', 0),
(713, 151, 1, '08:00:00', '17:00:00', 0),
(714, 151, 2, '00:00:00', '00:00:00', 0),
(715, 151, 3, '00:00:00', '00:00:00', 0),
(716, 152, 1, '08:00:00', '17:00:00', 0),
(717, 152, 2, '00:00:00', '00:00:00', 0),
(718, 152, 3, '00:00:00', '00:00:00', 0),
(719, 153, 1, '08:00:00', '17:00:00', 0),
(720, 153, 2, '00:00:00', '00:00:00', 0),
(721, 153, 3, '00:00:00', '00:00:00', 0),
(722, 154, 1, '08:00:00', '16:30:00', 0),
(723, 154, 2, '00:00:00', '00:00:00', 0),
(724, 154, 3, '00:00:00', '00:00:00', 0),
(725, 155, 1, '08:00:00', '16:30:00', 0),
(726, 155, 2, '00:00:00', '00:00:00', 0),
(727, 155, 3, '00:00:00', '00:00:00', 0),
(728, 156, 1, '08:00:00', '16:30:00', 0),
(729, 156, 2, '00:00:00', '00:00:00', 0),
(730, 156, 3, '00:00:00', '00:00:00', 0),
(731, 157, 1, '08:00:00', '16:30:00', 0),
(732, 157, 2, '00:00:00', '00:00:00', 0),
(733, 157, 3, '00:00:00', '00:00:00', 0),
(734, 158, 1, '08:00:00', '16:30:00', 0),
(735, 158, 2, '00:00:00', '00:00:00', 0),
(736, 158, 3, '00:00:00', '00:00:00', 0),
(737, 159, 1, '08:00:00', '17:00:00', 0),
(738, 159, 2, '00:00:00', '00:00:00', 0),
(739, 159, 3, '00:00:00', '00:00:00', 0),
(740, 160, 1, '08:00:00', '16:30:00', 0),
(741, 160, 2, '00:00:00', '00:00:00', 0),
(742, 160, 3, '00:00:00', '00:00:00', 0),
(743, 161, 1, '08:00:00', '16:30:00', 0),
(744, 161, 2, '00:00:00', '00:00:00', 0),
(745, 161, 3, '00:00:00', '00:00:00', 0),
(746, 162, 1, '08:00:00', '17:00:00', 0),
(747, 162, 2, '08:00:00', '13:00:00', 0),
(748, 162, 3, '00:00:00', '00:00:00', 0),
(749, 163, 1, '08:00:00', '17:00:00', 0),
(750, 163, 2, '08:00:00', '13:00:00', 0),
(751, 163, 3, '00:00:00', '00:00:00', 0),
(752, 164, 1, '08:00:00', '16:30:00', 0),
(753, 164, 2, '00:00:00', '00:00:00', 0),
(754, 164, 3, '00:00:00', '00:00:00', 0),
(755, 165, 1, '08:00:00', '17:00:00', 0),
(756, 165, 2, '00:00:00', '00:00:00', 0),
(757, 165, 3, '00:00:00', '00:00:00', 0),
(758, 166, 1, '08:00:00', '17:00:00', 0),
(759, 166, 2, '00:00:00', '00:00:00', 0),
(760, 166, 3, '00:00:00', '00:00:00', 0),
(761, 167, 1, '08:00:00', '17:00:00', 0),
(762, 167, 2, '00:00:00', '00:00:00', 0),
(763, 167, 3, '00:00:00', '00:00:00', 0),
(764, 168, 1, '08:00:00', '17:00:00', 0),
(765, 168, 2, '00:00:00', '00:00:00', 0),
(766, 168, 3, '00:00:00', '00:00:00', 0),
(767, 169, 1, '08:00:00', '17:00:00', 0),
(768, 169, 2, '00:00:00', '00:00:00', 0),
(769, 169, 3, '00:00:00', '00:00:00', 0),
(770, 170, 1, '08:00:00', '17:00:00', 0),
(771, 170, 2, '00:00:00', '00:00:00', 0),
(772, 170, 3, '00:00:00', '00:00:00', 0),
(773, 171, 1, '08:00:00', '17:00:00', 0),
(774, 171, 2, '00:00:00', '00:00:00', 0),
(775, 171, 3, '00:00:00', '00:00:00', 0),
(776, 172, 1, '08:00:00', '16:30:00', 0),
(777, 172, 2, '00:00:00', '00:00:00', 0),
(778, 172, 3, '00:00:00', '00:00:00', 0),
(779, 173, 1, '08:00:00', '16:30:00', 0),
(780, 173, 2, '00:00:00', '00:00:00', 0),
(781, 173, 3, '00:00:00', '00:00:00', 0),
(782, 174, 1, '08:00:00', '16:30:00', 0),
(783, 174, 2, '00:00:00', '00:00:00', 0),
(784, 174, 3, '00:00:00', '00:00:00', 0),
(785, 175, 1, '08:00:00', '17:00:00', 0),
(786, 175, 2, '00:00:00', '00:00:00', 0),
(787, 175, 3, '00:00:00', '00:00:00', 0),
(788, 176, 1, '08:00:00', '17:00:00', 0),
(789, 176, 2, '00:00:00', '00:00:00', 0),
(790, 176, 3, '00:00:00', '00:00:00', 0),
(791, 177, 1, '08:00:00', '16:30:00', 0),
(792, 177, 2, '09:00:00', '12:30:00', 0),
(793, 177, 3, '09:00:00', '12:30:00', 0),
(794, 178, 1, '08:00:00', '17:00:00', 0),
(795, 178, 2, '00:00:00', '00:00:00', 0),
(796, 178, 3, '00:00:00', '00:00:00', 0),
(797, 179, 1, '08:00:00', '16:30:00', 0),
(798, 179, 2, '00:00:00', '00:00:00', 0),
(799, 179, 3, '00:00:00', '00:00:00', 0),
(800, 180, 1, '08:00:00', '16:30:00', 0),
(801, 180, 2, '00:00:00', '00:00:00', 0),
(802, 180, 3, '00:00:00', '00:00:00', 0),
(803, 181, 1, '08:00:00', '17:00:00', 0),
(804, 181, 2, '00:00:00', '00:00:00', 0),
(805, 181, 3, '00:00:00', '00:00:00', 0),
(806, 182, 1, '08:00:00', '16:30:00', 0),
(807, 182, 2, '00:00:00', '00:00:00', 0),
(808, 182, 3, '00:00:00', '00:00:00', 0),
(809, 183, 1, '08:00:00', '16:30:00', 0),
(810, 183, 2, '00:00:00', '00:00:00', 0),
(811, 183, 3, '00:00:00', '00:00:00', 0),
(812, 184, 1, '08:00:00', '16:30:00', 0),
(813, 184, 2, '00:00:00', '00:00:00', 0),
(814, 184, 3, '00:00:00', '00:00:00', 0),
(815, 185, 1, '08:00:00', '16:30:00', 0),
(816, 185, 2, '00:00:00', '00:00:00', 0),
(817, 185, 3, '00:00:00', '00:00:00', 0),
(818, 186, 1, '08:00:00', '17:00:00', 0),
(819, 186, 2, '00:00:00', '00:00:00', 0),
(820, 186, 3, '00:00:00', '00:00:00', 0),
(821, 187, 1, '08:00:00', '16:30:00', 0),
(822, 187, 2, '09:00:00', '12:30:00', 0),
(823, 187, 3, '09:00:00', '12:30:00', 0),
(824, 188, 1, '08:00:00', '17:00:00', 0),
(825, 188, 2, '00:00:00', '00:00:00', 0),
(826, 188, 3, '00:00:00', '00:00:00', 0),
(827, 189, 1, '08:00:00', '17:00:00', 0),
(828, 189, 2, '00:00:00', '00:00:00', 0),
(829, 189, 3, '00:00:00', '00:00:00', 0),
(830, 190, 1, '08:00:00', '16:30:00', 0),
(831, 190, 2, '09:00:00', '12:30:00', 0),
(832, 190, 3, '09:00:00', '12:30:00', 0),
(833, 191, 1, '08:00:00', '16:30:00', 0),
(834, 191, 2, '09:00:00', '12:30:00', 0),
(835, 191, 3, '09:00:00', '12:30:00', 0),
(836, 192, 1, '08:00:00', '17:00:00', 0),
(837, 192, 2, '00:00:00', '00:00:00', 0),
(838, 192, 3, '00:00:00', '00:00:00', 0),
(839, 193, 1, '08:00:00', '17:00:00', 0),
(840, 193, 2, '00:00:00', '00:00:00', 0),
(841, 193, 3, '00:00:00', '00:00:00', 0),
(842, 194, 1, '08:00:00', '17:00:00', 0),
(843, 194, 2, '00:00:00', '00:00:00', 0),
(844, 194, 3, '00:00:00', '00:00:00', 0),
(845, 195, 1, '08:00:00', '17:00:00', 0),
(846, 195, 2, '00:00:00', '13:00:00', 0),
(847, 195, 3, '00:00:00', '00:00:00', 0),
(848, 196, 1, '08:00:00', '17:00:00', 0),
(849, 196, 2, '08:00:00', '13:00:00', 0),
(850, 196, 3, '00:00:00', '00:00:00', 0),
(851, 197, 1, '08:00:00', '17:00:00', 0),
(852, 197, 2, '08:00:00', '13:00:00', 0),
(853, 197, 3, '00:00:00', '00:00:00', 0),
(854, 198, 1, '08:00:00', '17:00:00', 0),
(855, 198, 2, '08:00:00', '13:00:00', 0),
(856, 198, 3, '00:00:00', '00:00:00', 0),
(857, 199, 1, '08:00:00', '17:00:00', 0),
(858, 199, 2, '00:00:00', '13:00:00', 0),
(859, 199, 3, '00:00:00', '00:00:00', 0),
(860, 200, 1, '08:00:00', '16:30:00', 0),
(861, 200, 2, '09:00:00', '12:30:00', 0),
(862, 200, 3, '09:00:00', '12:30:00', 0),
(863, 201, 1, '08:00:00', '16:30:00', 0),
(864, 201, 2, '09:00:00', '12:30:00', 0),
(865, 201, 3, '09:00:00', '12:30:00', 0),
(866, 202, 1, '08:00:00', '16:30:00', 0),
(867, 202, 2, '09:00:00', '12:30:00', 0),
(868, 202, 3, '09:00:00', '12:30:00', 0),
(869, 203, 1, '08:00:00', '16:30:00', 0),
(870, 203, 2, '09:00:00', '12:30:00', 0),
(871, 203, 3, '09:00:00', '12:30:00', 0),
(872, 204, 1, '08:00:00', '16:30:00', 0),
(873, 204, 2, '09:00:00', '12:30:00', 0),
(874, 204, 3, '00:00:00', '00:00:00', 0),
(875, 205, 1, '08:00:00', '16:30:00', 0),
(876, 205, 2, '09:00:00', '12:30:00', 0),
(877, 205, 3, '09:00:00', '12:30:00', 0),
(878, 206, 1, '08:00:00', '16:30:00', 0),
(879, 206, 2, '09:00:00', '12:30:00', 0),
(880, 206, 3, '09:00:00', '12:30:00', 0),
(881, 207, 1, '08:00:00', '16:30:00', 0),
(882, 207, 2, '09:00:00', '12:30:00', 0),
(883, 207, 3, '09:00:00', '12:30:00', 0),
(884, 208, 1, '08:00:00', '16:30:00', 0),
(885, 208, 2, '09:00:00', '12:30:00', 0),
(886, 208, 3, '09:00:00', '12:30:00', 0),
(887, 209, 1, '08:00:00', '16:30:00', 0),
(888, 209, 2, '09:00:00', '12:30:00', 0),
(889, 209, 3, '09:00:00', '12:30:00', 0),
(890, 210, 1, '08:00:00', '16:30:00', 0),
(891, 210, 2, '09:00:00', '12:30:00', 0),
(892, 210, 3, '09:00:00', '12:30:00', 0),
(893, 211, 1, '08:00:00', '16:30:00', 0),
(894, 211, 2, '00:00:00', '00:00:00', 0),
(895, 211, 3, '00:00:00', '00:00:00', 0),
(896, 212, 1, '08:00:00', '16:30:00', 0),
(897, 212, 2, '00:00:00', '00:00:00', 0),
(898, 212, 3, '00:00:00', '00:00:00', 0),
(899, 213, 1, '08:00:00', '16:30:00', 0),
(900, 213, 2, '00:00:00', '00:00:00', 0),
(901, 213, 3, '00:00:00', '00:00:00', 0),
(902, 214, 1, '08:00:00', '16:30:00', 0),
(903, 214, 2, '09:00:00', '12:30:00', 0),
(904, 214, 3, '09:00:00', '12:30:00', 0),
(905, 215, 1, '08:00:00', '16:30:00', 0),
(906, 215, 2, '09:00:00', '12:30:00', 0),
(907, 215, 3, '09:00:00', '12:30:00', 0),
(908, 216, 1, '08:00:00', '16:30:00', 0),
(909, 216, 2, '09:00:00', '12:30:00', 0),
(910, 216, 3, '09:00:00', '12:30:00', 0),
(911, 218, 1, '08:00:00', '16:30:00', 0),
(912, 218, 2, '09:00:00', '12:30:00', 0),
(913, 218, 3, '09:00:00', '12:30:00', 0),
(914, 219, 1, '08:00:00', '16:30:00', 0),
(915, 219, 2, '09:00:00', '12:30:00', 0),
(916, 219, 3, '09:00:00', '12:30:00', 0),
(917, 220, 1, '08:00:00', '16:30:00', 0),
(918, 220, 2, '09:00:00', '12:30:00', 0),
(919, 220, 3, '09:00:00', '12:30:00', 0),
(920, 221, 1, '08:00:00', '17:00:00', 0),
(921, 221, 2, '00:00:00', '00:00:00', 0),
(922, 221, 3, '00:00:00', '00:00:00', 0),
(923, 222, 1, '08:00:00', '17:00:00', 0),
(924, 222, 2, '08:00:00', '13:00:00', 0),
(925, 222, 3, '00:00:00', '00:00:00', 0),
(926, 223, 1, '08:00:00', '17:00:00', 0),
(927, 223, 2, '08:00:00', '13:00:00', 0),
(928, 223, 3, '00:00:00', '00:00:00', 0),
(929, 224, 1, '08:00:00', '17:00:00', 0),
(930, 224, 2, '08:00:00', '13:00:00', 0),
(931, 224, 3, '00:00:00', '00:00:00', 0),
(932, 225, 1, '08:00:00', '17:00:00', 0),
(933, 225, 2, '00:00:00', '00:00:00', 0),
(934, 225, 3, '00:00:00', '00:00:00', 0),
(935, 226, 1, '08:00:00', '17:00:00', 0),
(936, 226, 2, '00:00:00', '00:00:00', 0),
(937, 226, 3, '00:00:00', '00:00:00', 0),
(938, 227, 1, '08:00:00', '17:00:00', 0),
(939, 227, 2, '00:00:00', '00:00:00', 0),
(940, 227, 3, '00:00:00', '00:00:00', 0),
(941, 228, 1, '08:00:00', '17:00:00', 0),
(942, 228, 2, '00:00:00', '00:00:00', 0),
(943, 228, 3, '00:00:00', '00:00:00', 0),
(944, 229, 1, '08:00:00', '17:00:00', 0),
(945, 229, 2, '00:00:00', '00:00:00', 0),
(946, 229, 3, '00:00:00', '00:00:00', 0),
(947, 230, 1, '08:00:00', '16:30:00', 0),
(948, 230, 2, '00:00:00', '00:00:00', 0),
(949, 230, 3, '00:00:00', '00:00:00', 0),
(950, 231, 1, '08:00:00', '16:30:00', 0),
(951, 231, 2, '09:00:00', '12:30:00', 0),
(952, 231, 3, '09:00:00', '12:30:00', 0),
(953, 232, 1, '08:00:00', '16:30:00', 0),
(954, 232, 2, '09:00:00', '12:30:00', 0),
(955, 232, 3, '09:00:00', '12:30:00', 0),
(956, 233, 1, '08:00:00', '16:30:00', 0),
(957, 233, 2, '09:00:00', '12:30:00', 0),
(958, 233, 3, '09:00:00', '12:30:00', 0),
(959, 234, 1, '08:00:00', '16:30:00', 0),
(960, 234, 2, '09:00:00', '12:30:00', 0),
(961, 234, 3, '09:00:00', '12:30:00', 0),
(962, 235, 1, '08:00:00', '16:30:00', 0),
(963, 235, 2, '09:00:00', '12:30:00', 0),
(964, 235, 3, '09:00:00', '12:30:00', 0),
(965, 236, 1, '08:00:00', '16:30:00', 0),
(966, 236, 2, '09:00:00', '12:30:00', 0),
(967, 236, 3, '09:00:00', '12:30:00', 0),
(968, 237, 1, '08:00:00', '17:00:00', 0),
(969, 237, 2, '00:00:00', '00:00:00', 0),
(970, 237, 3, '00:00:00', '00:00:00', 0),
(971, 238, 1, '08:00:00', '16:30:00', 0),
(972, 238, 2, '00:00:00', '00:00:00', 0),
(973, 238, 3, '00:00:00', '00:00:00', 0),
(974, 239, 1, '08:00:00', '17:00:00', 0),
(975, 239, 2, '00:00:00', '00:00:00', 0),
(976, 239, 3, '00:00:00', '00:00:00', 0),
(977, 240, 1, '08:00:00', '17:00:00', 0),
(978, 240, 2, '00:00:00', '00:00:00', 0),
(979, 240, 3, '00:00:00', '00:00:00', 0),
(980, 241, 1, '08:00:00', '16:30:00', 0),
(981, 241, 2, '09:00:00', '12:30:00', 0),
(982, 241, 3, '00:00:00', '00:00:00', 0),
(983, 242, 1, '08:00:00', '16:30:00', 0),
(984, 242, 2, '09:00:00', '12:30:00', 0),
(985, 242, 3, '09:00:00', '12:30:00', 0),
(986, 243, 1, '08:00:00', '16:30:00', 0),
(987, 243, 2, '00:00:00', '00:00:00', 0),
(988, 243, 3, '00:00:00', '00:00:00', 0),
(989, 244, 1, '08:00:00', '16:30:00', 0),
(990, 244, 2, '00:00:00', '00:00:00', 0),
(991, 244, 3, '00:00:00', '00:00:00', 0),
(992, 245, 1, '08:00:00', '16:30:00', 0),
(993, 245, 2, '00:00:00', '00:00:00', 0),
(994, 245, 3, '00:00:00', '00:00:00', 0),
(995, 246, 1, '08:00:00', '16:30:00', 0),
(996, 246, 2, '09:00:00', '12:30:00', 0),
(997, 246, 3, '09:00:00', '12:30:00', 0),
(998, 247, 1, '08:00:00', '16:30:00', 0),
(999, 247, 2, '00:00:00', '00:00:00', 0),
(1000, 247, 3, '00:00:00', '00:00:00', 0),
(1001, 248, 1, '08:00:00', '16:30:00', 0),
(1002, 248, 2, '00:00:00', '00:00:00', 0),
(1003, 248, 3, '00:00:00', '00:00:00', 0),
(1004, 249, 1, '08:00:00', '16:30:00', 0),
(1005, 249, 2, '00:00:00', '00:00:00', 0),
(1006, 249, 3, '00:00:00', '00:00:00', 0),
(1007, 250, 1, '08:00:00', '16:30:00', 0),
(1008, 250, 2, '00:00:00', '00:00:00', 0),
(1009, 250, 3, '00:00:00', '00:00:00', 0),
(1010, 251, 1, '08:00:00', '16:30:00', 0),
(1011, 251, 2, '00:00:00', '00:00:00', 0),
(1012, 251, 3, '00:00:00', '00:00:00', 0),
(1013, 252, 1, '08:00:00', '16:30:00', 0),
(1014, 252, 2, '00:00:00', '00:00:00', 0),
(1015, 252, 3, '00:00:00', '00:00:00', 0),
(1016, 253, 1, '08:00:00', '16:30:00', 0),
(1017, 253, 2, '00:00:00', '00:00:00', 0),
(1018, 253, 3, '00:00:00', '00:00:00', 0),
(1019, 254, 1, '08:00:00', '16:30:00', 0),
(1020, 254, 2, '00:00:00', '00:00:00', 0),
(1021, 254, 3, '00:00:00', '00:00:00', 0),
(1022, 255, 1, '08:00:00', '16:30:00', 0),
(1023, 255, 2, '00:00:00', '00:00:00', 0),
(1024, 255, 3, '00:00:00', '00:00:00', 0),
(1025, 256, 1, '08:00:00', '16:30:00', 0),
(1026, 256, 2, '00:00:00', '00:00:00', 0),
(1027, 256, 3, '00:00:00', '00:00:00', 0),
(1028, 257, 1, '08:00:00', '16:30:00', 0),
(1029, 257, 2, '00:00:00', '00:00:00', 0),
(1030, 257, 3, '00:00:00', '00:00:00', 0),
(1031, 258, 1, '08:00:00', '16:30:00', 0),
(1032, 258, 2, '00:00:00', '00:00:00', 0),
(1033, 258, 3, '00:00:00', '00:00:00', 0),
(1034, 259, 1, '08:00:00', '16:30:00', 0),
(1035, 259, 2, '00:00:00', '00:00:00', 0),
(1036, 259, 3, '00:00:00', '00:00:00', 0),
(1037, 260, 1, '08:00:00', '16:30:00', 0),
(1038, 260, 2, '00:00:00', '00:00:00', 0),
(1039, 260, 3, '00:00:00', '00:00:00', 0),
(1040, 261, 1, '08:00:00', '16:30:00', 0),
(1041, 261, 2, '00:00:00', '00:00:00', 0),
(1042, 261, 3, '00:00:00', '00:00:00', 0),
(1043, 262, 1, '08:00:00', '16:30:00', 0),
(1044, 262, 2, '00:00:00', '00:00:00', 0),
(1045, 262, 3, '00:00:00', '00:00:00', 0),
(1046, 263, 1, '08:00:00', '16:30:00', 0),
(1047, 263, 2, '00:00:00', '00:00:00', 0),
(1048, 263, 3, '00:00:00', '00:00:00', 0),
(1049, 264, 1, '08:00:00', '16:30:00', 0),
(1050, 264, 2, '09:00:00', '12:30:00', 0),
(1051, 264, 3, '09:00:00', '12:30:00', 0),
(1052, 265, 1, '08:00:00', '16:30:00', 0),
(1053, 265, 2, '09:00:00', '12:30:00', 0),
(1054, 265, 3, '09:00:00', '12:30:00', 0),
(1055, 266, 1, '08:00:00', '16:30:00', 0),
(1056, 266, 2, '09:00:00', '12:30:00', 0),
(1057, 266, 3, '09:00:00', '12:30:00', 0),
(1058, 267, 1, '08:00:00', '16:30:00', 0),
(1059, 267, 2, '09:00:00', '12:30:00', 0),
(1060, 267, 3, '09:00:00', '12:30:00', 0),
(1061, 268, 1, '08:00:00', '16:30:00', 0),
(1062, 268, 2, '09:00:00', '12:30:00', 0),
(1063, 268, 3, '09:00:00', '12:30:00', 0),
(1064, 269, 1, '08:00:00', '16:30:00', 0),
(1065, 269, 2, '09:00:00', '12:30:00', 0),
(1066, 269, 3, '09:00:00', '12:30:00', 0),
(1067, 270, 1, '08:00:00', '16:30:00', 0),
(1068, 270, 2, '09:00:00', '12:30:00', 0),
(1069, 270, 3, '09:00:00', '12:30:00', 0),
(1070, 271, 1, '08:00:00', '16:30:00', 0),
(1071, 271, 2, '09:00:00', '12:30:00', 0),
(1072, 271, 3, '09:00:00', '12:30:00', 0),
(1073, 272, 1, '08:00:00', '16:30:00', 0),
(1074, 272, 2, '09:00:00', '12:30:00', 0),
(1075, 272, 3, '09:00:00', '12:30:00', 0),
(1076, 273, 1, '08:00:00', '16:30:00', 0),
(1077, 273, 2, '09:00:00', '12:30:00', 0),
(1078, 273, 3, '09:00:00', '12:30:00', 0),
(1079, 274, 1, '08:00:00', '16:30:00', 0),
(1080, 274, 2, '09:00:00', '12:30:00', 0),
(1081, 274, 3, '09:00:00', '12:30:00', 0),
(1082, 275, 1, '08:00:00', '16:30:00', 0),
(1083, 275, 2, '09:00:00', '12:30:00', 0),
(1084, 275, 3, '09:00:00', '12:30:00', 0),
(1085, 276, 1, '08:00:00', '16:30:00', 0),
(1086, 276, 2, '09:00:00', '12:30:00', 0),
(1087, 276, 3, '09:00:00', '12:30:00', 0),
(1088, 277, 1, '08:00:00', '16:30:00', 0),
(1089, 277, 2, '09:00:00', '12:30:00', 0),
(1090, 277, 3, '09:00:00', '12:30:00', 0),
(1091, 278, 1, '08:00:00', '16:30:00', 0),
(1092, 278, 2, '09:00:00', '12:30:00', 0),
(1093, 278, 3, '09:00:00', '12:30:00', 0),
(1094, 279, 1, '08:00:00', '16:30:00', 0),
(1095, 279, 2, '09:00:00', '12:30:00', 0),
(1096, 279, 3, '09:00:00', '12:30:00', 0),
(1097, 280, 1, '08:00:00', '16:30:00', 0),
(1098, 280, 2, '09:00:00', '12:30:00', 0),
(1099, 280, 3, '09:00:00', '12:30:00', 0),
(1100, 281, 1, '08:00:00', '16:30:00', 0),
(1101, 281, 2, '09:00:00', '12:30:00', 0),
(1102, 281, 3, '09:00:00', '12:30:00', 0),
(1103, 282, 1, '08:00:00', '16:30:00', 0),
(1104, 282, 2, '09:00:00', '12:30:00', 0),
(1105, 282, 3, '09:00:00', '12:30:00', 0),
(1106, 283, 1, '08:00:00', '16:30:00', 0),
(1107, 283, 2, '09:00:00', '12:30:00', 0),
(1108, 283, 3, '09:00:00', '12:30:00', 0),
(1109, 284, 1, '08:00:00', '16:30:00', 0),
(1110, 284, 2, '09:00:00', '12:30:00', 0),
(1111, 284, 3, '09:00:00', '12:30:00', 0),
(1112, 285, 1, '08:00:00', '16:30:00', 0),
(1113, 285, 2, '09:00:00', '12:30:00', 0),
(1114, 285, 3, '09:00:00', '12:30:00', 0),
(1115, 286, 1, '08:00:00', '16:30:00', 0),
(1116, 286, 2, '09:00:00', '12:30:00', 0),
(1117, 286, 3, '09:00:00', '12:30:00', 0),
(1118, 287, 1, '08:00:00', '16:30:00', 0),
(1119, 287, 2, '09:00:00', '12:30:00', 0),
(1120, 287, 3, '09:00:00', '12:30:00', 0),
(1121, 288, 1, '08:00:00', '16:30:00', 0),
(1122, 288, 2, '09:00:00', '12:30:00', 0),
(1123, 288, 3, '09:00:00', '12:30:00', 0),
(1124, 289, 1, '08:00:00', '16:30:00', 0),
(1125, 289, 2, '09:00:00', '12:30:00', 0),
(1126, 289, 3, '09:00:00', '12:30:00', 0),
(1127, 2, 1, '08:00:00', '16:30:00', 0),
(1128, 2, 2, '09:00:00', '12:30:00', 0),
(1129, 2, 3, '08:00:00', '12:30:00', 0),
(1130, 3, 1, '08:00:00', '16:30:00', 0),
(1131, 3, 2, '09:00:00', '12:30:00', 0),
(1132, 3, 3, '08:00:00', '12:30:00', 0),
(1133, 4, 1, '08:00:00', '16:30:00', 0),
(1134, 4, 2, '09:00:00', '12:30:00', 0),
(1135, 4, 3, '08:00:00', '12:30:00', 0),
(1136, 5, 1, '08:00:00', '16:30:00', 0),
(1137, 5, 2, '09:00:00', '12:30:00', 0),
(1138, 5, 3, '08:00:00', '12:30:00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `wfworkinghrsdefault`
--

CREATE TABLE IF NOT EXISTS `wfworkinghrsdefault` (
  `idwfworkinghrsdefault` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `usrteamzone_idusrteamzone` int(5) unsigned NOT NULL,
  `wfworkingdays_idwfworkingdays` int(1) unsigned NOT NULL,
  `time_earliest` time DEFAULT NULL,
  `time_latest` time DEFAULT NULL,
  PRIMARY KEY (`idwfworkinghrsdefault`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `wfworkinghrsdefault`
--

INSERT INTO `wfworkinghrsdefault` (`idwfworkinghrsdefault`, `usrteamzone_idusrteamzone`, `wfworkingdays_idwfworkingdays`, `time_earliest`, `time_latest`) VALUES
(1, 1, 1, '08:00:00', '16:30:00'),
(2, 1, 2, '09:00:00', '12:30:00'),
(3, 1, 3, '08:00:00', '12:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `wpdetails`
--

CREATE TABLE IF NOT EXISTS `wpdetails` (
  `idwpdetails` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `wpquarters_idwpquarters` int(2) unsigned NOT NULL,
  `tktactivitytype_idtktactivitytype` bigint(20) NOT NULL,
  `wpheader_idwpheader` int(11) unsigned NOT NULL,
  `value_number` int(11) unsigned DEFAULT NULL,
  `value_budget` float(10,2) DEFAULT NULL,
  `createdby` int(8) NOT NULL,
  `createdon` datetime NOT NULL,
  `modifiedby` int(8) NOT NULL DEFAULT '0',
  `modifiedon` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`idwpdetails`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `wpquarters`
--

CREATE TABLE IF NOT EXISTS `wpquarters` (
  `idwpquarters` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `wpquarter` varchar(10) DEFAULT NULL,
  `list_order` int(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idwpquarters`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `wpquarters`
--

INSERT INTO `wpquarters` (`idwpquarters`, `wpquarter`, `list_order`) VALUES
(1, 'Quarter 1', 0),
(2, 'Quarter 2', 0),
(3, 'Quarter 3', 0),
(4, 'Quarter 4', 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
