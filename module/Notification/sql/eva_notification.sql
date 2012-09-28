-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2012 年 09 月 28 日 11:24
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
-- 表的结构 `eva_notification_indexes`
--

DROP TABLE IF EXISTS `eva_notification_indexes`;
CREATE TABLE IF NOT EXISTS `eva_notification_indexes` (
  `user_id` int(10) NOT NULL,
  `message_id` int(10) NOT NULL,
  `readFlag` tinyint(1) NOT NULL DEFAULT '0',
  `messageTime` datetime NOT NULL,
  PRIMARY KEY (`user_id`,`message_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `eva_notification_messages`
--

DROP TABLE IF EXISTS `eva_notification_messages`;
CREATE TABLE IF NOT EXISTS `eva_notification_messages` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `messageType` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'custom',
  `template_id` int(10) NOT NULL DEFAULT '0',
  `message_from_id` int(10) NOT NULL DEFAULT '0',
  `subject` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `createTime` datetime NOT NULL,
  `message` longtext COLLATE utf8_unicode_ci,
  `attachments` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `eva_notification_messages_users`
--

DROP TABLE IF EXISTS `eva_notification_messages_users`;
CREATE TABLE IF NOT EXISTS `eva_notification_messages_users` (
  `message_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `sendAs` enum('to','cc','bcc') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'to',
  `sendBy` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'email',
  `sendStatus` enum('waiting','sending','sent','failed') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'waiting',
  `sendTime` datetime DEFAULT NULL,
  `readFlag` tinyint(1) NOT NULL DEFAULT '0',
  `readTime` datetime DEFAULT NULL,
  PRIMARY KEY (`message_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `eva_notification_templates`
--

DROP TABLE IF EXISTS `eva_notification_templates`;
CREATE TABLE IF NOT EXISTS `eva_notification_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `templateKey` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `subject` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
