-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2012 年 08 月 06 日 04:56
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
-- 表的结构 `eva_album_albums`
--

DROP TABLE IF EXISTS `eva_album_albums`;
CREATE TABLE IF NOT EXISTS `eva_album_albums` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `urlName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `albumType` enum('private','public') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'public',
  `description` text COLLATE utf8_unicode_ci,
  `theme_id` int(10) DEFAULT NULL,
  `user_id` int(10) NOT NULL,
  `user_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `orderNumber` int(10) DEFAULT NULL,
  `createTime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `eva_album_categories`
--

DROP TABLE IF EXISTS `eva_album_categories`;
CREATE TABLE IF NOT EXISTS `eva_album_categories` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `urlName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `themeType` enum('private','public') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'public',
  `description` text COLLATE utf8_unicode_ci,
  `user_id` int(10) NOT NULL,
  `user_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `orderNumber` int(10) DEFAULT NULL,
  `createTime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `eva_blog_posts`
--

DROP TABLE IF EXISTS `eva_blog_posts`;
CREATE TABLE IF NOT EXISTS `eva_blog_posts` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` enum('deleted','draft','published','pending') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'published',
  `visibility` enum('public','private','password') COLLATE utf8_unicode_ci NOT NULL,
  `codeType` enum('markdown','html','wiki','ubb','other') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'markdown',
  `language` varchar(5) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'en',
  `postUsage` enum('post','page','faq','news') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'post',
  `connect_id` int(10) DEFAULT NULL,
  `trackback` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `urlName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `preview` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `orderNumber` int(10) DEFAULT NULL,
  `setting` int(10) NOT NULL DEFAULT '0',
  `createTime` datetime NOT NULL,
  `user_id` int(10) NOT NULL,
  `user_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `updateTime` datetime DEFAULT NULL,
  `editor_id` int(10) DEFAULT NULL,
  `editor_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `postPassword` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `commentStatus` enum('open','closed','authority') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'open',
  `commentType` enum('local','disqus','youyan','duoshuo') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'local',
  `commentCount` int(10) NOT NULL DEFAULT '0',
  `viewCount` bigint(20) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

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

-- --------------------------------------------------------

--
-- 表的结构 `eva_blog_texts`
--

DROP TABLE IF EXISTS `eva_blog_texts`;
CREATE TABLE IF NOT EXISTS `eva_blog_texts` (
  `post_id` int(20) NOT NULL,
  `metaKeywords` text COLLATE utf8_unicode_ci,
  `metaDescription` text COLLATE utf8_unicode_ci,
  `toc` text COLLATE utf8_unicode_ci,
  `content` longtext COLLATE utf8_unicode_ci NOT NULL,
  `contentHtml` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`post_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `eva_blog_translations`
--

DROP TABLE IF EXISTS `eva_blog_translations`;
CREATE TABLE IF NOT EXISTS `eva_blog_translations` (
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
-- 表的结构 `eva_core_resources`
--

DROP TABLE IF EXISTS `eva_core_resources`;
CREATE TABLE IF NOT EXISTS `eva_core_resources` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `resourceName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `eva_core_roles`
--

DROP TABLE IF EXISTS `eva_core_roles`;
CREATE TABLE IF NOT EXISTS `eva_core_roles` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `roleName` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `roleCache` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `eva_core_roles_resources`
--

DROP TABLE IF EXISTS `eva_core_roles_resources`;
CREATE TABLE IF NOT EXISTS `eva_core_roles_resources` (
  `operation` enum('index','get','put','post','delete') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'get',
  `role_id` int(10) NOT NULL,
  `resource_id` int(10) NOT NULL,
  PRIMARY KEY (`operation`,`role_id`,`resource_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `eva_core_sessions`
--

DROP TABLE IF EXISTS `eva_core_sessions`;
CREATE TABLE IF NOT EXISTS `eva_core_sessions` (
  `session_id` char(32) COLLATE utf8_unicode_ci NOT NULL,
  `save_path` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `modified` int(11) DEFAULT NULL,
  `lifetime` int(11) DEFAULT NULL,
  `session_data` varchar(2000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`session_id`,`save_path`,`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `eva_dict_dicts`
--

DROP TABLE IF EXISTS `eva_dict_dicts`;
CREATE TABLE IF NOT EXISTS `eva_dict_dicts` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `word` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `wordType` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dictType` enum('jp_zh','zh_jp') COLLATE utf8_unicode_ci NOT NULL,
  `meaning` text COLLATE utf8_unicode_ci NOT NULL,
  `spelling` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `spellingAlias` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tags` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `spelling` (`spelling`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `eva_file_files`
--

DROP TABLE IF EXISTS `eva_file_files`;
CREATE TABLE IF NOT EXISTS `eva_file_files` (
  `id` bigint(30) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` enum('deleted','draft','published','pending') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'published',
  `isImage` tinyint(1) NOT NULL DEFAULT '0',
  `fileName` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `fileExtension` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `originalName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `configKey` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `fileServerKey` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fileServerName` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cdnServerKey` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cdnServerName` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
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

-- --------------------------------------------------------

--
-- 表的结构 `eva_movie_manufacturers`
--

DROP TABLE IF EXISTS `eva_movie_manufacturers`;
CREATE TABLE IF NOT EXISTS `eva_movie_manufacturers` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `nameAlias` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nameEn` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nameZh` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `homepage` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `logo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `eva_movie_moviedownloads`
--

DROP TABLE IF EXISTS `eva_movie_moviedownloads`;
CREATE TABLE IF NOT EXISTS `eva_movie_moviedownloads` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `movie_id` int(10) NOT NULL,
  `type` enum('http','torrent','emule','magnet','other') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'other',
  `url` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `eva_movie_moviepreviews`
--

DROP TABLE IF EXISTS `eva_movie_moviepreviews`;
CREATE TABLE IF NOT EXISTS `eva_movie_moviepreviews` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `movie_id` int(10) NOT NULL,
  `type` enum('pic','swf','webpage') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'pic',
  `originalUrl` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `localPath` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cdnUrl` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `eva_movie_movies`
--

DROP TABLE IF EXISTS `eva_movie_movies`;
CREATE TABLE IF NOT EXISTS `eva_movie_movies` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `titleAlias` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `urlName` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `identifier` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `imdb` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `language` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `isAdult` tinyint(1) NOT NULL DEFAULT '0',
  `isAnime` tinyint(1) NOT NULL DEFAULT '0',
  `cover` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `summary` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `directorIdString` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `directorNameString` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `actorIdString` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `actorNameString` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `authorIdString` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `authorNameString` varchar(400) COLLATE utf8_unicode_ci DEFAULT NULL,
  `manufacturer_id` int(10) DEFAULT NULL,
  `manufacturerName` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `series_id` int(10) DEFAULT NULL,
  `seriesTitle` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `genreIdString` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `genreNameString` varchar(400) COLLATE utf8_unicode_ci DEFAULT NULL,
  `movieYear` year(4) DEFAULT NULL,
  `saleDate` date DEFAULT NULL,
  `siteUrl` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `movieLength` int(5) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `urlName` (`urlName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `eva_movie_staffimages`
--

DROP TABLE IF EXISTS `eva_movie_staffimages`;
CREATE TABLE IF NOT EXISTS `eva_movie_staffimages` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `staff_id` int(10) NOT NULL,
  `isAdult` tinyint(1) NOT NULL DEFAULT '0',
  `originalUrl` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `localPath` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cdnUrl` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `eva_movie_staffprofiles`
--

DROP TABLE IF EXISTS `eva_movie_staffprofiles`;
CREATE TABLE IF NOT EXISTS `eva_movie_staffprofiles` (
  `staff_id` int(10) NOT NULL,
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
  `measurements` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `bloodType` enum('A','B','O','AB','Other') COLLATE utf8_unicode_ci DEFAULT NULL,
  `cup` varchar(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`staff_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `eva_movie_staffs`
--

DROP TABLE IF EXISTS `eva_movie_staffs`;
CREATE TABLE IF NOT EXISTS `eva_movie_staffs` (
  `id` int(10) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `nameEn` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nameZh` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nameKana` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nameKanaIndex` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  `isDirector` tinyint(1) NOT NULL DEFAULT '0',
  `isActor` tinyint(1) NOT NULL DEFAULT '0',
  `isAuthor` tinyint(1) NOT NULL DEFAULT '0',
  `avator` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gender` enum('male','female','other') COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `eva_movie_staffs_manufacturers`
--

DROP TABLE IF EXISTS `eva_movie_staffs_manufacturers`;
CREATE TABLE IF NOT EXISTS `eva_movie_staffs_manufacturers` (
  `staff_id` int(10) NOT NULL,
  `manufacturer_id` int(6) NOT NULL,
  PRIMARY KEY (`staff_id`,`manufacturer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `eva_notification_emails`
--

DROP TABLE IF EXISTS `eva_notification_emails`;
CREATE TABLE IF NOT EXISTS `eva_notification_emails` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `status` enum('waiting','sent','failed','canceled') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'waiting',
  `connectType` enum('product','user') COLLATE utf8_unicode_ci DEFAULT NULL,
  `connect_id` int(11) DEFAULT NULL,
  `mailTo` text COLLATE utf8_unicode_ci,
  `mailCc` text COLLATE utf8_unicode_ci,
  `mailBcc` text COLLATE utf8_unicode_ci,
  `mailFrom` text COLLATE utf8_unicode_ci NOT NULL,
  `subject` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sendTime` datetime NOT NULL,
  `message` longtext COLLATE utf8_unicode_ci,
  `attachments` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `eva_notification_emailtemplates`
--

DROP TABLE IF EXISTS `eva_notification_emailtemplates`;
CREATE TABLE IF NOT EXISTS `eva_notification_emailtemplates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `status` enum('active','canceled') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'active',
  `templateUsage` enum('pricealert','other') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'pricealert',
  `content` text COLLATE utf8_unicode_ci,
  `createTime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `eva_notification_emailtemplates_users`
--

DROP TABLE IF EXISTS `eva_notification_emailtemplates_users`;
CREATE TABLE IF NOT EXISTS `eva_notification_emailtemplates_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `emailtemplate_id` int(11) NOT NULL,
  `product_id` int(9) NOT NULL,
  `status` enum('waiting','sending','sent') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'waiting',
  `alertTime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `eva_notification_hashes`
--

DROP TABLE IF EXISTS `eva_notification_hashes`;
CREATE TABLE IF NOT EXISTS `eva_notification_hashes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hash` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `status` enum('active','expired','invalid') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'active',
  `operate` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `operateData` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `expired` datetime DEFAULT NULL,
  `createTime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `eva_queue_queuemessages`
--

DROP TABLE IF EXISTS `eva_queue_queuemessages`;
CREATE TABLE IF NOT EXISTS `eva_queue_queuemessages` (
  `message_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue_id` int(10) unsigned NOT NULL,
  `handle` char(32) DEFAULT NULL,
  `body` varchar(8192) NOT NULL,
  `md5` char(32) NOT NULL,
  `timeout` decimal(14,4) unsigned DEFAULT NULL,
  `created` int(10) unsigned NOT NULL,
  PRIMARY KEY (`message_id`),
  UNIQUE KEY `message_handle` (`handle`),
  KEY `message_queueid` (`queue_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `eva_queue_queues`
--

DROP TABLE IF EXISTS `eva_queue_queues`;
CREATE TABLE IF NOT EXISTS `eva_queue_queues` (
  `queue_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `queue_name` varchar(100) NOT NULL,
  `timeout` smallint(5) unsigned NOT NULL DEFAULT '30',
  PRIMARY KEY (`queue_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `eva_tweet_tweets`
--

DROP TABLE IF EXISTS `eva_tweet_tweets`;
CREATE TABLE IF NOT EXISTS `eva_tweet_tweets` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `status` enum('approved','pending','deleted') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'pending',
  `recommend` tinyint(1) NOT NULL DEFAULT '0',
  `tweetUsage` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `connect_id` int(10) DEFAULT NULL,
  `text` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `textHtml` text COLLATE utf8_unicode_ci NOT NULL,
  `textTranslation` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `createTime` datetime NOT NULL,
  `user_id` int(10) NOT NULL,
  `user_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sourceName` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sourceUrl` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `parentId` bigint(20) DEFAULT NULL,
  `reply_user_id` bigint(20) DEFAULT NULL,
  `reply_user_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `appType` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `appItemId` bigint(20) DEFAULT NULL,
  `appUserId` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `appUserName` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `appReplyId` bigint(20) DEFAULT NULL,
  `appReplyUserId` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `appReplyUserName` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `syncStatus` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `eva_tweet_tweets_users`
--

DROP TABLE IF EXISTS `eva_tweet_tweets_users`;
CREATE TABLE IF NOT EXISTS `eva_tweet_tweets_users` (
  `tweet_id` int(9) NOT NULL,
  `user_id` int(10) NOT NULL,
  `status` enum('active','deleted') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'active',
  `createTime` datetime NOT NULL,
  PRIMARY KEY (`tweet_id`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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
-- 表的结构 `eva_user_useraccounts`
--

DROP TABLE IF EXISTS `eva_user_useraccounts`;
CREATE TABLE IF NOT EXISTS `eva_user_useraccounts` (
  `user_id` int(11) NOT NULL,
  `credits` decimal(10,2) NOT NULL DEFAULT '0.00',
  `points` decimal(10,2) NOT NULL DEFAULT '0.00',
  `discount` decimal(5,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `eva_user_useroptions`
--

DROP TABLE IF EXISTS `eva_user_useroptions`;
CREATE TABLE IF NOT EXISTS `eva_user_useroptions` (
  `user_id` int(10) NOT NULL,
  `optionKey` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `optionValue` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`user_id`,`optionKey`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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
  `password` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oldPassword` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lastPasswordChangeTime` datetime DEFAULT NULL,
  `gender` enum('male','female','other') COLLATE utf8_unicode_ci DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `timezone` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `registerTime` datetime DEFAULT NULL,
  `lastLoginTime` datetime DEFAULT NULL,
  `language` varchar(10) COLLATE utf8_unicode_ci DEFAULT 'zh_CN',
  `setting` int(10) NOT NULL DEFAULT '0',
  `isInvited` tinyint(1) NOT NULL DEFAULT '0',
  `onlineStatus` enum('online','busy','offline') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'offline',
  `lastFleshTime` datetime DEFAULT NULL,
  `viewCount` bigint(20) NOT NULL DEFAULT '0',
  `registerIp` varbinary(16) DEFAULT NULL,
  `lastLoginIp` varbinary(16) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
