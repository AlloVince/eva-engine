ALTER TABLE `eva_blog_texts` DROP `contentHtml`;
ALTER TABLE `eva_blog_posts` DROP `postUsage`;

ALTER TABLE `eva_blog_categories` CHANGE `parentId` `parentId` INT( 10 ) NOT NULL DEFAULT '0',
CHANGE `rootId` `rootId` INT( 10 ) NOT NULL DEFAULT '0',
CHANGE `left` `left` INT( 15 ) NOT NULL DEFAULT '0',
CHANGE `right` `right` INT( 15 ) NOT NULL DEFAULT '0';

TRUNCATE TABLE `eva_blog_translations`;

ALTER TABLE `eva_blog_posts` ADD `parentId` TINYINT( 1 ) NOT NULL DEFAULT '0' AFTER `language`;

ALTER TABLE `eva_file_files_connections` CHANGE `connectType` `connectType` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

ALTER TABLE `eva_file_files_connections` ADD `orderNumber` INT( 5 ) NOT NULL DEFAULT '0';








ALTER TABLE `eva_user_users` ADD `mobile` VARCHAR( 20 ) NULL AFTER `email` ;





ALTER TABLE `eva_user_friends` CHANGE `relationShipStatus` `relationshipStatus` ENUM( 'pending', 'refused', 'active', 'blocked' ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'pending';
ALTER TABLE `eva_user_friends` CHANGE `relationshipStatus` `relationshipStatus` ENUM( 'pending', 'refused', 'approved', 'blocked' ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'pending';



ALTER TABLE `eva_user_users` CHANGE `lastFleshTime` `lastFreshTime` DATETIME NULL DEFAULT NULL;


ALTER TABLE `eva_user_codes` CHANGE `codeType` `codeType` ENUM( 'invite', 'activeAccount', 'verifyEmail', 'verifyMobile', 'resetPassword', 'other' ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `eva_oauth_accesstokens` ADD `tokenStatus` ENUM( 'active', 'expried' ) NOT NULL DEFAULT 'active' AFTER `version`;




---2012-12-18
ALTER TABLE `eva_blog_posts` CHANGE `connect_id` `connect_id` INT( 10 ) NOT NULL DEFAULT '0';
ALTER TABLE `eva_blog_posts` CHANGE `codeType` `codeType` VARCHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'markdown';
ALTER TABLE `eva_blog_posts` CHANGE `orderNumber` `orderNumber` INT( 10 ) NOT NULL DEFAULT '999';
ALTER TABLE `eva_blog_posts` CHANGE `commentType` `commentType` VARCHAR( 15 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'local';
ALTER TABLE `eva_blog_comments` CHANGE `user_id` `user_id` INT( 10 ) NOT NULL DEFAULT '0';
ALTER TABLE `eva_blog_comments` CHANGE `rootId` `rootId` INT( 10 ) NOT NULL DEFAULT '0';
ALTER TABLE `eva_blog_comments` CHANGE `parentId` `parentId` INT( 10 ) NULL DEFAULT '0';
ALTER TABLE `eva_blog_comments`
  DROP `editor_id`,
  DROP `editor_name`,
  DROP `editor_screenname`;
ALTER TABLE `eva_blog_comments` CHANGE `ip` `ip` VARBINARY( 16 ) NULL DEFAULT NULL;
ALTER TABLE `eva_blog_comments` ADD `codeType` VARCHAR( 30 ) NOT NULL DEFAULT 'markdown' AFTER `status`;
ALTER TABLE `eva_blog_comments` CHANGE `content` `content` MEDIUMTEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;







---2012-12-26
ALTER TABLE `eva_event_events` ADD `isRepeat` BOOLEAN NOT NULL DEFAULT FALSE AFTER `timezone` ,
ADD `repeatStartDate` DATE NULL AFTER `isRepeat` ,
ADD `repeatEndDate` DATE NULL AFTER `repeatStartDate` ,
ADD `frequency` ENUM( 'daily', 'weekly', 'monthly', 'yearly', 'other' ) NOT NULL DEFAULT 'daily' AFTER `repeatEndDate` ,
ADD `frequencyWeek` VARCHAR( 7 ) NOT NULL DEFAULT '0' AFTER `frequency` ,
ADD `frequencyMonth` ENUM( 'dayofmonth', 'dayofweek' ) NOT NULL DEFAULT 'dayofweek' AFTER `frequencyWeek` ,
ADD `interval` INT( 2 ) NOT NULL DEFAULT '0' AFTER `frequencyMonth`;
ALTER TABLE `eva_event_events` DROP `recurrence_id`;



-----2013-01-07
ALTER TABLE `eva_blog_posts` ADD `flag` VARCHAR( 20 ) NULL AFTER `status`;
ALTER TABLE `eva_user_users` ADD `flag` VARCHAR( 20 ) NULL AFTER `status`;
ALTER TABLE `eva_group_groups` ADD `flag` VARCHAR( 20 ) NULL AFTER `status`;
ALTER TABLE `eva_user_users` ADD `avatar_id` INT( 10 ) NOT NULL DEFAULT '0' AFTER `gender`;

DROP TABLE IF EXISTS `eva_user_avatars`;
CREATE TABLE IF NOT EXISTS `eva_user_avatars` (
  `user_id` int(10) NOT NULL,
  `file_id` int(10) NOT NULL,
  PRIMARY KEY (`user_id`,`file_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `eva_user_images_users`;
CREATE TABLE IF NOT EXISTS `eva_user_images_users` (
  `user_id` int(10) NOT NULL,
  `file_id` int(10) NOT NULL,
  `usage` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`user_id`,`file_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `eva_core_newsletters`;
CREATE TABLE IF NOT EXISTS `eva_core_newsletters` (
  `user_id` int(11) NOT NULL,
  `email` varchar(320) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-----2013-01-08
ALTER TABLE `eva_user_friends` CHANGE `from_user_id` `user_id` INT( 10 ) NOT NULL;
ALTER TABLE `eva_user_friends` CHANGE `to_user_id` `friend_id` INT( 10 ) NOT NULL;
ALTER TABLE `eva_user_friends` ADD `request_user_id` INT NOT NULL DEFAULT '0' AFTER `friend_id`;
