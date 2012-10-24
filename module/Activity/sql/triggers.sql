#DROP TRIGGER IF EXISTS `messages_insert`;
#DELIMITER //
#CREATE TRIGGER `messages_insert` AFTER INSERT ON `eva_activity_messages`
# FOR EACH ROW 
# BEGIN
# DELETE FROM `eva_activity_indexes` WHERE `eva_activity_indexes`.`message_id` = NEW.`id` AND (NEW.`messageType` = 'original' OR NEW.`messageType` = 'forward');
# INSERT INTO `eva_activity_indexes` (`user_id` ,`author_id` ,`message_id` ,`messageTime`) SELECT NEW.`user_id` AS `user_id`, NEW.`user_id` AS `author_id`, `id`, NEW.`createTime` AS `createTime` FROM `eva_activity_messages` WHERE `eva_activity_messages`.`id` = NEW.`id` AND (NEW.`messageType` = 'original' OR NEW.`messageType` = 'forward');
# INSERT INTO `eva_activity_indexes` (`user_id` ,`author_id` ,`message_id` ,`messageTime`) SELECT `follower_id`, NEW.`user_id` AS `author_id`, NEW.`id` AS `id`, NEW.`createTime` AS `createTime` FROM `eva_activity_followers` WHERE `eva_activity_followers`.`user_id` = NEW.`user_id` AND (NEW.`messageType` = 'original' OR NEW.`messageType` = 'forward');
# DELETE FROM `eva_activity_atindexes` WHERE `eva_activity_atindexes`.`message_id` = NEW.`id` AND (NEW.`messageType` = 'original' OR NEW.`messageType` = 'forward');
# INSERT INTO `eva_activity_atindexes` (`atuser_id`, `message_id`, `messageTime`) SELECT `user_id`, NEW.`id` AS `message_id`, NEW.`createTime` AS `createTime` FROM `eva_activity_atusers` WHERE `eva_activity_atusers`.`message_id` = NEW.`id` AND (NEW.`messageType` = 'original' OR NEW.`messageType` = 'forward');
# END
#//
#DELIMITER ;

DROP TRIGGER IF EXISTS `messages_insert`;
DELIMITER //
CREATE TRIGGER `messages_insert` AFTER INSERT ON `eva_activity_messages`
 FOR EACH ROW 
 BEGIN
 DELETE FROM `eva_activity_indexes` WHERE `eva_activity_indexes`.`message_id` = NEW.`id` AND (NEW.`messageType` = 'original' OR NEW.`messageType` = 'forward');
 INSERT INTO `eva_activity_indexes` (`user_id` ,`author_id` ,`message_id` ,`messageTime`) SELECT NEW.`user_id` AS `user_id`, NEW.`user_id` AS `author_id`, `id`, NEW.`createTime` AS `createTime` FROM `eva_activity_messages` WHERE `eva_activity_messages`.`id` = NEW.`id` AND (NEW.`messageType` = 'original' OR NEW.`messageType` = 'forward');
 INSERT INTO `eva_activity_indexes` (`user_id` ,`author_id` ,`message_id` ,`messageTime`) SELECT `follower_id`, NEW.`user_id` AS `author_id`, NEW.`id` AS `id`, NEW.`createTime` AS `createTime` FROM `eva_activity_followers` WHERE `eva_activity_followers`.`user_id` = NEW.`user_id` AND (NEW.`messageType` = 'original' OR NEW.`messageType` = 'forward');
 END
//
DELIMITER ;

DROP TRIGGER IF EXISTS `messages_delete`;
DELIMITER //
CREATE TRIGGER `messages_delete` AFTER DELETE ON `eva_activity_messages`
 FOR EACH ROW 
 BEGIN 
 DELETE FROM `eva_activity_indexes` WHERE `eva_activity_indexes`.`message_id` = OLD.`id`;
 DELETE FROM `eva_activity_atindexes` WHERE `eva_activity_atindexes`.`message_id` = OLD.`id`;
 END
//
DELIMITER ;

DROP TRIGGER IF EXISTS `followers_insert`;
DELIMITER //
CREATE TRIGGER `followers_insert` AFTER INSERT ON `eva_activity_followers`
 FOR EACH ROW 
 BEGIN
 DELETE FROM `eva_activity_indexes` WHERE `eva_activity_indexes`.`user_id` = NEW.`follower_id` AND `eva_activity_indexes`.`author_id` = NEW.`user_id`;
 INSERT INTO `eva_activity_indexes` (`user_id` ,`author_id` ,`message_id` ,`messageTime`) SELECT NEW.`follower_id` AS `user_id`, NEW.`user_id` AS `author_id`, `message_id`, `messageTime` FROM `eva_activity_indexes` WHERE `eva_activity_indexes`.`user_id` = NEW.`user_id` AND `eva_activity_indexes`.`author_id` = NEW.`user_id`;
 END
//
DELIMITER ;

DROP TRIGGER IF EXISTS `followers_delete`;
DELIMITER //
CREATE TRIGGER `followers_delete` AFTER DELETE ON `eva_activity_followers`
 FOR EACH ROW DELETE FROM `eva_activity_indexes` WHERE `eva_activity_indexes`.`user_id` = OLD.`follower_id` AND `eva_activity_indexes`.`author_id` = OLD.`user_id`;
//
DELIMITER ;


DROP TRIGGER IF EXISTS `atusers_insert`;
DELIMITER //
CREATE TRIGGER `atusers_insert` AFTER INSERT ON `eva_activity_atusers`
 FOR EACH ROW 
 BEGIN
 DELETE FROM `eva_activity_atindexes` WHERE `eva_activity_atindexes`.`message_id` = NEW.`message_id` AND `eva_activity_atindexes`.`atuser_id` = NEW.`user_id`;
 INSERT INTO `eva_activity_atindexes` (`atuser_id`, `message_id`, `messageTime`) SELECT NEW.`user_id` AS `atuser_id`, `id`, `createTime` FROM `eva_activity_messages` WHERE `eva_activity_messages`.`id` = NEW.`message_id`;
 END
//
DELIMITER ;


DROP TRIGGER IF EXISTS `references_insert`;
DELIMITER //
CREATE TRIGGER `references_insert` AFTER INSERT ON `eva_activity_references`
 FOR EACH ROW 
 BEGIN
 UPDATE `eva_activity_messages` SET `commentedCount` = `commentedCount` + 1 WHERE `eva_activity_messages`.`id` = NEW.`reference_message_id` AND NEW.`messageType` = 'comment';
 UPDATE `eva_activity_messages` SET `transferredCount` = `transferredCount` + 1 WHERE `eva_activity_messages`.`id` = NEW.`root_message_id` AND NEW.`messageType` = 'forward' AND NEW.`root_message_id` = NEW.`reference_message_id`;
 UPDATE `eva_activity_messages` SET `transferredCount` = `transferredCount` + 1 WHERE (`eva_activity_messages`.`id` = NEW.`root_message_id` OR `eva_activity_messages`.`id` = NEW.`reference_message_id`) AND NEW.`messageType` = 'forward' AND NEW.`root_message_id` != NEW.`reference_message_id`;
 END
//
DELIMITER ;

