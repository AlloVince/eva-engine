-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2012 年 05 月 07 日 08:27
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
-- 表的结构 `eva_blog_categories`
--

DROP TABLE IF EXISTS `eva_blog_categories`;
CREATE TABLE IF NOT EXISTS `eva_blog_categories` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `categoryName` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `urlName` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `parentId` int(10) DEFAULT NULL,
  `rootId` int(10) DEFAULT NULL,
  `orderNumber` int(10) DEFAULT NULL,
  `createTime` datetime NOT NULL,
  `count` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `eva_blog_categories_posts`
--

DROP TABLE IF EXISTS `eva_blog_categories_posts`;
CREATE TABLE IF NOT EXISTS `eva_blog_categories_posts` (
  `category_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  PRIMARY KEY (`category_id`,`post_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `eva_blog_comments`
--

DROP TABLE IF EXISTS `eva_blog_comments`;
CREATE TABLE IF NOT EXISTS `eva_blog_comments` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `status` enum('approved','pending','badword','spam','deleted') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'pending',
  `commentUsage` enum('post','product','game','movie','music','book','album','image','ticket') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'post',
  `connect_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `user_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `screen_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `site` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ip` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `content` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `contentHtml` text COLLATE utf8_unicode_ci,
  `createTime` datetime NOT NULL,
  `editor_id` int(10) DEFAULT NULL,
  `editor_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `editor_screenname` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rootId` int(10) DEFAULT NULL,
  `parentId` int(10) DEFAULT NULL,
  `commentRank` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1244 ;

-- --------------------------------------------------------

--
-- 表的结构 `eva_blog_postlanguageextras`
--

DROP TABLE IF EXISTS `eva_blog_postlanguageextras`;
CREATE TABLE IF NOT EXISTS `eva_blog_postlanguageextras` (
  `post_id` int(11) NOT NULL,
  `language` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `preview` text COLLATE utf8_unicode_ci,
  `content` longtext COLLATE utf8_unicode_ci,
  `contentHtml` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`post_id`,`language`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `eva_blog_posts`
--

DROP TABLE IF EXISTS `eva_blog_posts`;
CREATE TABLE IF NOT EXISTS `eva_blog_posts` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` enum('deleted','draft','published','pending','badword') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'published',
  `visibility` enum('public','private','password') COLLATE utf8_unicode_ci NOT NULL,
  `displayEmail` tinyint(1) NOT NULL DEFAULT '0',
  `displayProfile` tinyint(1) NOT NULL DEFAULT '0',
  `codeType` enum('wiki','html','ubb') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'wiki',
  `language` varchar(5) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'en',
  `postUsage` enum('post','page','faq','news') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'post',
  `connect_id` int(10) DEFAULT NULL,
  `trackback` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `urlName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `preview` text COLLATE utf8_unicode_ci,
  `toc` text COLLATE utf8_unicode_ci,
  `content` longtext COLLATE utf8_unicode_ci NOT NULL,
  `contentHtml` longtext COLLATE utf8_unicode_ci,
  `orderNumber` int(10) DEFAULT NULL,
  `metaKeywords` tinytext COLLATE utf8_unicode_ci,
  `metaDescription` tinytext COLLATE utf8_unicode_ci,
  `createTime` datetime NOT NULL,
  `user_id` int(10) NOT NULL,
  `user_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `site` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `updateTime` datetime DEFAULT NULL,
  `editor_id` int(10) DEFAULT NULL,
  `editor_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `commentAble` tinyint(1) NOT NULL DEFAULT '1',
  `commentCount` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=138 ;

-- --------------------------------------------------------

--
-- 表的结构 `eva_blog_tags`
--

DROP TABLE IF EXISTS `eva_blog_tags`;
CREATE TABLE IF NOT EXISTS `eva_blog_tags` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `tagName` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `linkTo` enum('book','tag','post','page') COLLATE utf8_unicode_ci DEFAULT NULL,
  `parentId` int(10) DEFAULT NULL,
  `rootId` int(10) DEFAULT NULL,
  `orderNumber` int(10) DEFAULT NULL,
  `count` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=139 ;

-- --------------------------------------------------------

--
-- 表的结构 `eva_blog_tags_posts`
--

DROP TABLE IF EXISTS `eva_blog_tags_posts`;
CREATE TABLE IF NOT EXISTS `eva_blog_tags_posts` (
  `tag_id` int(10) NOT NULL,
  `post_id` int(10) NOT NULL,
  PRIMARY KEY (`tag_id`,`post_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
