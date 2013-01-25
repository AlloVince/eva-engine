SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

DROP TABLE IF EXISTS `eva_group_categories`;
CREATE TABLE IF NOT EXISTS `eva_group_categories` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `eva_group_categories_groups`;
CREATE TABLE IF NOT EXISTS `eva_group_categories_groups` (
  `category_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  PRIMARY KEY (`category_id`,`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `eva_group_counts`;
CREATE TABLE IF NOT EXISTS `eva_group_counts` (
  `group_id` int(20) NOT NULL,
  `memberCount` int(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `eva_group_groups`;
CREATE TABLE IF NOT EXISTS `eva_group_groups` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `groupKey` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `groupName` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `status` enum('active','pending','deleted') COLLATE utf8_unicode_ci NOT NULL,
  `summary` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `memberEnable` tinyint(1) NOT NULL DEFAULT '1',
  `memberLimit` int(5) DEFAULT NULL,
  `user_id` int(10) NOT NULL,
  `createTime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `eva_group_groups_events`;
CREATE TABLE IF NOT EXISTS `eva_group_groups_events` (
  `group_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  PRIMARY KEY (`group_id`,`event_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `eva_group_groups_files`;
CREATE TABLE IF NOT EXISTS `eva_group_groups_files` (
  `group_id` int(11) NOT NULL,
  `file_id` int(11) NOT NULL,
  PRIMARY KEY (`group_id`,`file_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `eva_group_groups_posts`;
CREATE TABLE IF NOT EXISTS `eva_group_groups_posts` (
  `group_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  PRIMARY KEY (`group_id`,`post_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `eva_group_groups_users`;
CREATE TABLE IF NOT EXISTS `eva_group_groups_users` (
  `user_id` int(10) NOT NULL,
  `group_id` int(10) NOT NULL,
  `role` enum('admin','member') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'member',
  `requestStatus` enum('pending','refused','active','blocked') COLLATE utf8_unicode_ci NOT NULL,
  `requestTime` datetime NOT NULL,
  `approvalTime` datetime DEFAULT NULL,
  `refusedTime` int(11) DEFAULT NULL,
  `blockedTime` int(11) DEFAULT NULL,
  `operator_id` int(10) NOT NULL,
  PRIMARY KEY (`user_id`,`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `eva_group_texts`;
CREATE TABLE IF NOT EXISTS `eva_group_texts` (
  `group_id` int(20) NOT NULL,
  `content` longtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `eva_group_tags`;
CREATE TABLE IF NOT EXISTS `eva_group_tags` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `tagName` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `parentId` int(10) NOT NULL DEFAULT '0',
  `rootId` int(10) NOT NULL DEFAULT '0',
  `orderNumber` int(10) NOT NULL DEFAULT '0',
  `count` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `eva_group_tags_groups`;
CREATE TABLE IF NOT EXISTS `eva_group_tags_groups` (
  `tag_id` int(10) NOT NULL,
  `group_id` int(10) NOT NULL,
  PRIMARY KEY (`tag_id`,`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
