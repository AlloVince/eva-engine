SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


DROP TABLE IF EXISTS `eva_notification_indexes`;
CREATE TABLE IF NOT EXISTS `eva_notification_indexes` (
  `user_id` int(10) NOT NULL,
  `message_id` int(10) NOT NULL,
  `readFlag` tinyint(1) NOT NULL DEFAULT '0',
  `createTime` datetime NOT NULL,
  `readTime` datetime DEFAULT NULL,
  PRIMARY KEY (`user_id`,`message_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `eva_notification_notifications`;
CREATE TABLE IF NOT EXISTS `eva_notification_notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `notificationKey` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sendNotice` tinyint(1) NOT NULL DEFAULT '0',
  `sendEmail` tinyint(1) NOT NULL DEFAULT '0',
  `sendSms` tinyint(1) NOT NULL DEFAULT '0',
  `sendPush` tinyint(1) NOT NULL DEFAULT '0',
  `allowDisableNotice` tinyint(1) NOT NULL DEFAULT '0',
  `allowDisableEmail` tinyint(1) NOT NULL DEFAULT '0',
  `allowDisableSms` tinyint(1) NOT NULL DEFAULT '0',
  `allowDisablePush` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `eva_notification_notifications_users`;
CREATE TABLE IF NOT EXISTS `eva_notification_notifications_users` (
  `notification_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `noticeType` enum('notice','email','sms','push') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'notice',
  `readFlag` tinyint(1) NOT NULL DEFAULT '0',
  `createTime` datetime NOT NULL,
  `sendTime` datetime DEFAULT NULL,
  `readTime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `eva_notification_usersettings`;
CREATE TABLE IF NOT EXISTS `eva_notification_usersettings` (
  `user_id` int(10) NOT NULL,
  `notification_id` int(10) NOT NULL,
  `disableNotice` tinyint(1) NOT NULL DEFAULT '0',
  `disableEmail` tinyint(1) NOT NULL DEFAULT '0',
  `disableSms` tinyint(1) NOT NULL DEFAULT '0',
  `disablePush` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`,`notification_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
