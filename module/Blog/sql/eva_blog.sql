SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

DROP TABLE IF EXISTS `eva_blog_categories`;
CREATE TABLE IF NOT EXISTS `eva_blog_categories` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `categoryName` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `urlName` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `parentId` int(10) NOT NULL DEFAULT '0',
  `rootId` int(10) NOT NULL DEFAULT '0',
  `orderNumber` int(10) NOT NULL DEFAULT '0',
  `createTime` datetime NOT NULL,
  `count` int(10) NOT NULL DEFAULT '0',
  `left` int(15) NOT NULL DEFAULT '0',
  `right` int(15) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

DROP TABLE IF EXISTS `eva_blog_categories_posts`;
CREATE TABLE IF NOT EXISTS `eva_blog_categories_posts` (
  `category_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  PRIMARY KEY (`category_id`,`post_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `eva_blog_comments`;
CREATE TABLE IF NOT EXISTS `eva_blog_comments` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `status` enum('approved','pending','spam','deleted') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'pending',
  `codeType` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'markdown',
  `post_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL DEFAULT '0',
  `user_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `screen_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `site` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ip` varbinary(16) DEFAULT NULL,
  `content` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `createTime` datetime NOT NULL,
  `rootId` int(10) NOT NULL DEFAULT '0',
  `parentId` int(10) DEFAULT '0',
  `commentRank` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `eva_blog_posts`;
CREATE TABLE IF NOT EXISTS `eva_blog_posts` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` enum('deleted','draft','published','pending') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'published',
  `visibility` enum('public','private','password') COLLATE utf8_unicode_ci NOT NULL,
  `codeType` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'markdown',
  `language` varchar(5) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'en',
  `parentId` tinyint(1) NOT NULL DEFAULT '0',
  `connect_id` int(10) NOT NULL DEFAULT '0',
  `trackback` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `urlName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `preview` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `orderNumber` int(10) NOT NULL DEFAULT '999',
  `setting` int(10) NOT NULL DEFAULT '0',
  `createTime` datetime NOT NULL,
  `user_id` int(10) NOT NULL,
  `user_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `updateTime` datetime DEFAULT NULL,
  `editor_id` int(10) DEFAULT NULL,
  `editor_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `postPassword` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `commentStatus` enum('open','closed','authority') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'open',
  `commentType` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'local',
  `commentCount` int(10) NOT NULL DEFAULT '0',
  `viewCount` bigint(20) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=185 ;

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

DROP TABLE IF EXISTS `eva_blog_tags_posts`;
CREATE TABLE IF NOT EXISTS `eva_blog_tags_posts` (
  `tag_id` int(10) NOT NULL,
  `post_id` int(10) NOT NULL,
  PRIMARY KEY (`tag_id`,`post_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `eva_blog_textarchives`;
CREATE TABLE IF NOT EXISTS `eva_blog_textarchives` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8_unicode_ci,
  `archiveTime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `post_id` (`post_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `eva_blog_texts`;
CREATE TABLE IF NOT EXISTS `eva_blog_texts` (
  `post_id` int(20) NOT NULL,
  `metaKeywords` text COLLATE utf8_unicode_ci,
  `metaDescription` text COLLATE utf8_unicode_ci,
  `toc` text COLLATE utf8_unicode_ci,
  `content` longtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`post_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
