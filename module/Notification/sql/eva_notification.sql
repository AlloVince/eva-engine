SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

DROP TABLE IF EXISTS `eva_notification_messages`;
CREATE TABLE IF NOT EXISTS `eva_notification_messages` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `notification_id` int(5) NOT NULL,
  `notificationKey` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `args` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `createTime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

DROP TABLE IF EXISTS `eva_notification_messages_users`;
CREATE TABLE IF NOT EXISTS `eva_notification_messages_users` (
  `message_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `noticeType` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'notice',
  `notification_id` int(5) NOT NULL,
  `readFlag` tinyint(1) NOT NULL DEFAULT '0',
  `createTime` datetime NOT NULL,
  `sendTime` datetime DEFAULT NULL,
  `readTime` datetime DEFAULT NULL,
  PRIMARY KEY (`message_id`,`user_id`,`noticeType`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `eva_notification_notices`;
CREATE TABLE IF NOT EXISTS `eva_notification_notices` (
  `user_id` int(10) NOT NULL,
  `message_id` bigint(20) NOT NULL,
  `status` enum('active','deleted') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'active',
  `notification_id` int(5) NOT NULL,
  `notificationKey` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `readFlag` tinyint(1) NOT NULL DEFAULT '0',
  `createTime` datetime NOT NULL,
  `readTime` datetime DEFAULT NULL,
  `content` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`user_id`,`message_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `eva_notification_notifications`;
CREATE TABLE IF NOT EXISTS `eva_notification_notifications` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `notificationKey` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sendNotice` tinyint(1) NOT NULL DEFAULT '0',
  `sendEmail` tinyint(1) NOT NULL DEFAULT '0',
  `sendSms` tinyint(1) NOT NULL DEFAULT '0',
  `sendAppleOsPush` tinyint(1) NOT NULL DEFAULT '0',
  `sendAndroidPush` tinyint(1) NOT NULL DEFAULT '0',
  `sendWindowsPush` tinyint(1) NOT NULL DEFAULT '0',
  `sendCustomNotice` tinyint(3) NOT NULL DEFAULT '0',
  `allowDisableNotice` tinyint(1) NOT NULL DEFAULT '0',
  `allowDisableEmail` tinyint(1) NOT NULL DEFAULT '0',
  `allowDisableSms` tinyint(1) NOT NULL DEFAULT '0',
  `allowDisableAppleOsPush` tinyint(1) NOT NULL DEFAULT '0',
  `allowDisableAndroidPush` tinyint(1) NOT NULL DEFAULT '0',
  `allowDisableWindowsPush` tinyint(1) NOT NULL DEFAULT '0',
  `allowDisableCustomNotice` tinyint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

DROP TABLE IF EXISTS `eva_notification_usersettings`;
CREATE TABLE IF NOT EXISTS `eva_notification_usersettings` (
  `user_id` int(10) NOT NULL,
  `notification_id` int(5) NOT NULL,
  `disableNotice` tinyint(1) NOT NULL DEFAULT '0',
  `disableEmail` tinyint(1) NOT NULL DEFAULT '0',
  `disableSms` tinyint(1) NOT NULL DEFAULT '0',
  `disableAppleOsPush` tinyint(1) NOT NULL DEFAULT '0',
  `disableAndroidPush` tinyint(1) NOT NULL DEFAULT '0',
  `disableWindowsPush` tinyint(1) NOT NULL DEFAULT '0',
  `disableCustomNotice` tinyint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`,`notification_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

