SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS `eva_activity_atindexes` (
  `atuser_id` int(10) NOT NULL,
  `message_id` bigint(30) NOT NULL,
  `messageTime` datetime NOT NULL,
  PRIMARY KEY (`atuser_id`,`message_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `eva_activity_atusers` (
  `message_id` int(30) NOT NULL,
  `user_id` int(10) NOT NULL,
  PRIMARY KEY (`message_id`,`user_id`),
  KEY `message_id` (`message_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `eva_activity_followers` (
  `user_id` int(10) NOT NULL,
  `follower_id` int(10) NOT NULL,
  `relationshipStatus` enum('single','double') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'single',
  `followTime` datetime NOT NULL,
  PRIMARY KEY (`user_id`,`follower_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `eva_activity_indexes` (
  `user_id` int(10) NOT NULL,
  `author_id` int(10) NOT NULL,
  `message_id` bigint(32) NOT NULL,
  `messageTime` datetime NOT NULL,
  PRIMARY KEY (`user_id`,`author_id`,`message_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `eva_activity_messages` (
  `id` bigint(32) NOT NULL AUTO_INCREMENT,
  `messageHash` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `messageType` enum('original','comment','forword') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'original',
  `content` varchar(280) COLLATE utf8_unicode_ci NOT NULL,
  `connect_id` bigint(32) NOT NULL DEFAULT '0',
  `status` enum('active','pending','deleted') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'active',
  `user_id` int(10) NOT NULL,
  `commentedCount` int(10) NOT NULL DEFAULT '0',
  `transferredCount` int(10) NOT NULL DEFAULT '0',
  `createTime` datetime NOT NULL,
  `sourceId` int(5) NOT NULL DEFAULT '0',
  `sourceName` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'web',
  `resourceIdString` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `atUserIdString` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `hasFile` tinyint(1) NOT NULL DEFAULT '0',
  `hasVideo` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `eva_activity_message_file` (
  `message_id` bigint(32) NOT NULL,
  `file_id` int(11) NOT NULL,
  PRIMARY KEY (`message_id`,`file_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `eva_activity_message_video` (
  `message_id` bigint(32) NOT NULL,
  `video_id` int(10) NOT NULL,
  PRIMARY KEY (`message_id`,`video_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `eva_activity_references` (
  `original_user_id` int(11) NOT NULL,
  `original_message_id` int(11) NOT NULL,
  `reference_user_id` int(11) NOT NULL,
  `reference_message_id` int(11) NOT NULL,
  `referenceType` enum('comment','forword') COLLATE utf8_unicode_ci NOT NULL,
  `referenceTime` datetime NOT NULL,
  PRIMARY KEY (`reference_user_id`,`reference_message_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `eva_activity_sources` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `sourceName` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `sourceUrl` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
