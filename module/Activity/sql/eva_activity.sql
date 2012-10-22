SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


DROP TABLE IF EXISTS `eva_activity_atindexes`;
CREATE TABLE IF NOT EXISTS `eva_activity_atindexes` (
  `atuser_id` int(10) NOT NULL,
  `message_id` bigint(30) NOT NULL,
  `messageTime` datetime NOT NULL,
  PRIMARY KEY (`atuser_id`,`message_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `eva_activity_atusers`;
CREATE TABLE IF NOT EXISTS `eva_activity_atusers` (
  `message_id` int(30) NOT NULL,
  `user_id` int(10) NOT NULL,
  `messageType` enum('original','comment','forword') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'original',
  `author_id` int(10) NOT NULL,
  `root_user_id` int(10) NOT NULL,
  PRIMARY KEY (`message_id`,`user_id`),
  KEY `message_id` (`message_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
DROP TRIGGER IF EXISTS `atusers_insert`;
DELIMITER //
CREATE TRIGGER `atusers_insert` AFTER INSERT ON `eva_activity_atusers`
 FOR EACH ROW BEGIN
 DELETE FROM `eva_activity_atindexes` WHERE `eva_activity_atindexes`.`message_id` = NEW.`message_id` AND `eva_activity_atindexes`.`atuser_id` = NEW.`user_id`;
 INSERT INTO `eva_activity_atindexes` (`atuser_id`, `message_id`, `messageTime`) SELECT NEW.`user_id` AS `atuser_id`, `id`, `createTime` FROM `eva_activity_messages` WHERE `eva_activity_messages`.`id` = NEW.`message_id`;
 END
//
DELIMITER ;

DROP TABLE IF EXISTS `eva_activity_followers`;
CREATE TABLE IF NOT EXISTS `eva_activity_followers` (
  `user_id` int(10) NOT NULL,
  `follower_id` int(10) NOT NULL,
  `relationshipStatus` enum('single','double') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'single',
  `followTime` datetime NOT NULL,
  PRIMARY KEY (`user_id`,`follower_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
DROP TRIGGER IF EXISTS `followers_delete`;
DELIMITER //
CREATE TRIGGER `followers_delete` AFTER DELETE ON `eva_activity_followers`
 FOR EACH ROW DELETE FROM `eva_activity_indexes` WHERE `eva_activity_indexes`.`user_id` = OLD.`follower_id` AND `eva_activity_indexes`.`author_id` = OLD.`user_id`
//
DELIMITER ;
DROP TRIGGER IF EXISTS `followers_insert`;
DELIMITER //
CREATE TRIGGER `followers_insert` AFTER INSERT ON `eva_activity_followers`
 FOR EACH ROW BEGIN
 DELETE FROM `eva_activity_indexes` WHERE `eva_activity_indexes`.`user_id` = NEW.`follower_id` AND `eva_activity_indexes`.`author_id` = NEW.`user_id`;
 INSERT INTO `eva_activity_indexes` (`user_id` ,`author_id` ,`message_id` ,`messageTime`) SELECT NEW.`follower_id` AS `user_id`, NEW.`user_id` AS `author_id`, `message_id`, `messageTime` FROM `eva_activity_indexes` WHERE `eva_activity_indexes`.`user_id` = NEW.`user_id` AND `eva_activity_indexes`.`author_id` = NEW.`user_id`;
 END
//
DELIMITER ;

DROP TABLE IF EXISTS `eva_activity_indexes`;
CREATE TABLE IF NOT EXISTS `eva_activity_indexes` (
  `user_id` int(10) NOT NULL,
  `author_id` int(10) NOT NULL,
  `message_id` bigint(32) NOT NULL,
  `messageTime` datetime NOT NULL,
  PRIMARY KEY (`user_id`,`author_id`,`message_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `eva_activity_messages`;
CREATE TABLE IF NOT EXISTS `eva_activity_messages` (
  `id` bigint(32) NOT NULL AUTO_INCREMENT,
  `messageHash` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `messageType` enum('original','comment','forword') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'original',
  `content` varchar(280) COLLATE utf8_unicode_ci NOT NULL,
  `reference_id` bigint(32) NOT NULL DEFAULT '0',
  `root_id` bigint(32) NOT NULL DEFAULT '0',
  `status` enum('active','pending','deleted') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'active',
  `user_id` int(10) NOT NULL,
  `reference_user_id` int(10) NOT NULL DEFAULT '0',
  `root_user_id` int(10) NOT NULL DEFAULT '0',
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
DROP TRIGGER IF EXISTS `messages_delete`;
DELIMITER //
CREATE TRIGGER `messages_delete` AFTER DELETE ON `eva_activity_messages`
 FOR EACH ROW BEGIN 
 DELETE FROM `eva_activity_indexes` WHERE `eva_activity_indexes`.`message_id` = OLD.`id`;
 DELETE FROM `eva_activity_atindexes` WHERE `eva_activity_atindexes`.`message_id` = OLD.`id`;
 END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `messages_insert`;
DELIMITER //
CREATE TRIGGER `messages_insert` AFTER INSERT ON `eva_activity_messages`
 FOR EACH ROW BEGIN
 DELETE FROM `eva_activity_indexes` WHERE `eva_activity_indexes`.`message_id` = NEW.`id` AND (NEW.`messageType` = 'original' OR NEW.`messageType` = 'forword');
 INSERT INTO `eva_activity_indexes` (`user_id` ,`author_id` ,`message_id` ,`messageTime`) SELECT NEW.`user_id` AS `user_id`, NEW.`user_id` AS `author_id`, `id`, NEW.`createTime` AS `createTime` FROM `eva_activity_messages` WHERE `eva_activity_messages`.`id` = NEW.`id` AND (NEW.`messageType` = 'original' OR NEW.`messageType` = 'forword');
 INSERT INTO `eva_activity_indexes` (`user_id` ,`author_id` ,`message_id` ,`messageTime`) SELECT `follower_id`, NEW.`user_id` AS `author_id`, NEW.`id` AS `id`, NEW.`createTime` AS `createTime` FROM `eva_activity_followers` WHERE `eva_activity_followers`.`user_id` = NEW.`user_id` AND (NEW.`messageType` = 'original' OR NEW.`messageType` = 'forword');
 END
//
DELIMITER ;

DROP TABLE IF EXISTS `eva_activity_messages_files`;
CREATE TABLE IF NOT EXISTS `eva_activity_messages_files` (
  `message_id` bigint(32) NOT NULL,
  `file_id` int(11) NOT NULL,
  PRIMARY KEY (`message_id`,`file_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `eva_activity_messages_videos`;
CREATE TABLE IF NOT EXISTS `eva_activity_messages_videos` (
  `message_id` bigint(32) NOT NULL,
  `video_id` int(10) NOT NULL,
  PRIMARY KEY (`message_id`,`video_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `eva_activity_references`;
CREATE TABLE IF NOT EXISTS `eva_activity_references` (
  `root_user_id` int(10) NOT NULL DEFAULT '0',
  `root_message_id` bigint(32) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL,
  `message_id` bigint(32) NOT NULL,
  `reference_user_id` int(10) NOT NULL,
  `reference_message_id` bigint(32) NOT NULL,
  `messageType` enum('comment','forword') COLLATE utf8_unicode_ci NOT NULL,
  `createTime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `eva_activity_sources`;
CREATE TABLE IF NOT EXISTS `eva_activity_sources` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `sourceName` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `sourceUrl` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
