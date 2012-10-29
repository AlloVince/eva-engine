SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

DROP TABLE IF EXISTS `eva_message_conversations`;
CREATE TABLE IF NOT EXISTS `eva_message_conversations` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sender_id` int(10) NOT NULL,
  `recipient_id` int(10) NOT NULL,
  `author_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `readFlag` tinyint(1) NOT NULL DEFAULT '0',
  `status` enum('active','pending','deleted') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'active',
  `createTime` datetime NOT NULL,
  `readTime` datetime DEFAULT NULL,
  `isBulkMessage` tinyint(1) NOT NULL DEFAULT '0',
  `message_id` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
DROP TRIGGER IF EXISTS `conversations_insert`;
DELIMITER //
CREATE TRIGGER `conversations_insert` AFTER INSERT ON `eva_message_conversations`
 FOR EACH ROW BEGIN
 DELETE FROM `eva_message_indexes` WHERE `eva_message_indexes`.`user_id` = NEW.user_id AND `eva_message_indexes`.`author_id` = NEW.author_id;
 INSERT INTO `eva_message_indexes` (`user_id` ,`author_id` ,`conversation_id` ,`messageTime`, `unreadCount`, `messageCount`)VALUES (NEW.`user_id`, NEW.`author_id`, NEW.`id`, NEW.`createTime`, (SELECT count(id) FROM `eva_message_conversations` WHERE `eva_message_conversations`.`user_id` = NEW.user_id AND `eva_message_conversations`.`author_id` = NEW.author_id AND `eva_message_conversations`.`readFlag` = 0), (SELECT count(id) FROM `eva_message_conversations` WHERE `eva_message_conversations`.`user_id` = NEW.user_id AND `eva_message_conversations`.`author_id` = NEW.author_id));
 END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `conversations_update`;
DELIMITER //
CREATE TRIGGER `conversations_update` AFTER UPDATE ON `eva_message_conversations`
 FOR EACH ROW UPDATE `eva_message_indexes` SET `unreadCount` = (SELECT count(id) FROM `eva_message_conversations` WHERE `eva_message_conversations`.`user_id` = NEW.user_id AND `eva_message_conversations`.`author_id` = NEW.author_id AND `eva_message_conversations`.`readFlag` = 0) WHERE `eva_message_indexes`.`user_id` = NEW.user_id AND `eva_message_indexes`.`author_id` = NEW.author_id AND NEW.`readFlag` = 1 AND NEW.`readFlag` != OLD.`readFlag`
//
DELIMITER ;
DROP TRIGGER IF EXISTS `conversations_delete`;
DELIMITER //
CREATE TRIGGER `conversations_delete` AFTER DELETE ON `eva_message_conversations`
 FOR EACH ROW BEGIN
 DELETE FROM `eva_message_indexes` WHERE `eva_message_indexes`.`user_id` = OLD.user_id AND `eva_message_indexes`.`author_id` = OLD.author_id AND (SELECT count(id) FROM `eva_message_conversations` WHERE `eva_message_conversations`.`user_id` = OLD.user_id AND `eva_message_conversations`.`author_id` = OLD.author_id) = 0;
 UPDATE `eva_message_indexes` SET `unreadCount` = (SELECT count(id) FROM `eva_message_conversations` WHERE `eva_message_conversations`.`user_id` = OLD.user_id AND `eva_message_conversations`.`author_id` = OLD.author_id AND `eva_message_conversations`.`readFlag` = 0), `messageCount` = (SELECT count(id) FROM `eva_message_conversations` WHERE `eva_message_conversations`.`user_id` = OLD.user_id AND `eva_message_conversations`.`author_id` = OLD.author_id) WHERE `eva_message_indexes`.`user_id` = OLD.user_id AND `eva_message_indexes`.`author_id` = OLD.author_id;
 END
//
DELIMITER ;

DROP TABLE IF EXISTS `eva_message_indexes`;
CREATE TABLE IF NOT EXISTS `eva_message_indexes` (
  `user_id` int(10) NOT NULL,
  `author_id` int(10) NOT NULL,
  `conversation_id` int(11) NOT NULL,
  `messageTime` datetime NOT NULL,
  `unreadCount` int(5) NOT NULL DEFAULT '0',
  `messageCount` int(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`,`author_id`,`conversation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `eva_message_messages`;
CREATE TABLE IF NOT EXISTS `eva_message_messages` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `conversation_id` int(10) DEFAULT NULL,
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  `readFlag` tinyint(1) NOT NULL DEFAULT '0',
  `readTime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
