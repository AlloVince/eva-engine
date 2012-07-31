-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2012 年 07 月 31 日 05:35
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
-- 表的结构 `eva_file_files`
--

DROP TABLE IF EXISTS `eva_file_files`;
CREATE TABLE IF NOT EXISTS `eva_file_files` (
  `id` bigint(30) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `category_id` int(5) DEFAULT NULL,
  `isImage` tinyint(1) NOT NULL DEFAULT '0',
  `fileName` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `fileExtension` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `originalName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `serverKey` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `serverName` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `filePath` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `fileHash` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fileSize` bigint(20) DEFAULT NULL,
  `imageWidth` smallint(5) DEFAULT NULL,
  `imageHeight` smallint(5) DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `orderNumber` int(10) DEFAULT NULL,
  `user_id` int(10) DEFAULT NULL,
  `user_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `createTime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `eva_file_syncs`
--

DROP TABLE IF EXISTS `eva_file_syncs`;
CREATE TABLE IF NOT EXISTS `eva_file_syncs` (
  `id` bigint(40) NOT NULL AUTO_INCREMENT,
  `file_id` bigint(30) NOT NULL,
  `syncType` enum('fastDFS','googleCloud','amazonCloud','flickr') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'fastDFS',
  `syncServer` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `syncPath` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `syncFilename` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `syncExtra` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
