-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2012 年 09 月 06 日 10:41
-- 服务器版本: 5.5.16
-- PHP 版本: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `eva`
--

-- --------------------------------------------------------

--
-- 表的结构 `eva_user_accounts`
--

DROP TABLE IF EXISTS `eva_user_accounts`;
CREATE TABLE IF NOT EXISTS `eva_user_accounts` (
  `user_id` int(11) NOT NULL,
  `credits` decimal(10,2) NOT NULL DEFAULT '0.00',
  `points` decimal(10,2) NOT NULL DEFAULT '0.00',
  `discount` decimal(5,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `eva_user_fieldoptions`
--

DROP TABLE IF EXISTS `eva_user_fieldoptions`;
CREATE TABLE IF NOT EXISTS `eva_user_fieldoptions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `field_id` int(11) unsigned NOT NULL,
  `label` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `option` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `order` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `field_id` (`field_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- 表的结构 `eva_user_fields`
--

DROP TABLE IF EXISTS `eva_user_fields`;
CREATE TABLE IF NOT EXISTS `eva_user_fields` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `fieldKey` varchar(24) COLLATE utf8_unicode_ci NOT NULL,
  `label` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `required` tinyint(1) NOT NULL DEFAULT '0',
  `display` tinyint(1) unsigned NOT NULL,
  `order` smallint(3) unsigned NOT NULL DEFAULT '0',
  `defaultValue` text COLLATE utf8_unicode_ci NOT NULL,
  `config` text COLLATE utf8_unicode_ci,
  `validators` text COLLATE utf8_unicode_ci,
  `filters` text COLLATE utf8_unicode_ci,
  `style` text COLLATE utf8_unicode_ci,
  `error` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=14 ;

-- --------------------------------------------------------

--
-- 表的结构 `eva_user_fields_roles`
--

DROP TABLE IF EXISTS `eva_user_fields_roles`;
CREATE TABLE IF NOT EXISTS `eva_user_fields_roles` (
  `field_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  PRIMARY KEY (`field_id`,`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `eva_user_fieldvalues`
--

DROP TABLE IF EXISTS `eva_user_fieldvalues`;
CREATE TABLE IF NOT EXISTS `eva_user_fieldvalues` (
  `field_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `value` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`field_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `eva_user_friends`
--

DROP TABLE IF EXISTS `eva_user_friends`;
CREATE TABLE IF NOT EXISTS `eva_user_friends` (
  `from_user_id` int(10) NOT NULL,
  `to_user_id` int(10) NOT NULL,
  `relationShipStatus` enum('pending','refused','active','blocked') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'pending',
  `requestTime` datetime NOT NULL,
  `approcalTime` datetime DEFAULT NULL,
  `refusedTime` datetime DEFAULT NULL,
  `blockedTime` datetime DEFAULT NULL,
  PRIMARY KEY (`from_user_id`,`to_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `eva_user_oauths`
--

DROP TABLE IF EXISTS `eva_user_oauths`;
CREATE TABLE IF NOT EXISTS `eva_user_oauths` (
  `user_id` int(10) NOT NULL,
  `appType` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `tokenSecret` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `version` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `refreshToken` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `refreshTime` datetime DEFAULT NULL,
  `expireTime` datetime DEFAULT NULL,
  `appUserId` bigint(20) DEFAULT NULL,
  `appUserName` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `appExt` mediumtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`user_id`,`appType`,`token`,`tokenSecret`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `eva_user_options`
--

DROP TABLE IF EXISTS `eva_user_options`;
CREATE TABLE IF NOT EXISTS `eva_user_options` (
  `user_id` int(10) NOT NULL,
  `optionKey` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `optionValue` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`user_id`,`optionKey`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `eva_user_profiles`
--

DROP TABLE IF EXISTS `eva_user_profiles`;
CREATE TABLE IF NOT EXISTS `eva_user_profiles` (
  `user_id` int(10) NOT NULL,
  `site` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `photoDir` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `photoName` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fullName` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `height` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `weight` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `addressMore` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `province` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `zipcode` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `degree` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `industry` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phoneBusiness` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phoneMobile` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phoneHome` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fax` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `signature` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `longitude` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `latitude` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `location` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bio` text COLLATE utf8_unicode_ci,
  `localIm` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `internalIm` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `otherIm` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `eva_user_roles`
--

DROP TABLE IF EXISTS `eva_user_roles`;
CREATE TABLE IF NOT EXISTS `eva_user_roles` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `roleName` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `roleCache` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `eva_user_roles_users`
--

DROP TABLE IF EXISTS `eva_user_roles_users`;
CREATE TABLE IF NOT EXISTS `eva_user_roles_users` (
  `role_id` int(5) NOT NULL,
  `user_id` int(10) NOT NULL,
  PRIMARY KEY (`role_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `eva_user_users`
--

DROP TABLE IF EXISTS `eva_user_users`;
CREATE TABLE IF NOT EXISTS `eva_user_users` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `userName` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(320) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` enum('active','deleted','inactive') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'active',
  `screenName` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `salt` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `firstName` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lastName` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oldPassword` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lastPasswordChangeTime` datetime DEFAULT NULL,
  `gender` enum('male','female','other') COLLATE utf8_unicode_ci DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `timezone` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `registerTime` datetime DEFAULT NULL,
  `lastLoginTime` datetime DEFAULT NULL,
  `language` varchar(10) COLLATE utf8_unicode_ci DEFAULT 'zh_CN',
  `setting` int(10) NOT NULL DEFAULT '0',
  `inviteUserId` int(10) DEFAULT '0',
  `onlineStatus` enum('online','busy','invisible','offline') COLLATE utf8_unicode_ci DEFAULT 'offline',
  `lastFleshTime` datetime DEFAULT NULL,
  `viewCount` bigint(20) NOT NULL DEFAULT '0',
  `registerIp` varbinary(16) DEFAULT NULL,
  `lastLoginIp` varbinary(16) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=15 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
