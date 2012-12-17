SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

DROP TABLE IF EXISTS `eva_event_events`;
CREATE TABLE IF NOT EXISTS `eva_event_events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `recurrence_id` int(10) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `eventStatus` enum('active','finished','disputed','trashed') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'active',
  `urlName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `visibility` enum('public','private') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'public',
  `eventUsage` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'other',
  `isFullDayEvent` tinyint(1) NOT NULL DEFAULT '0',
  `eventHash` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `startDay` date NOT NULL,
  `startTime` time DEFAULT NULL,
  `startDatetimeUtc` datetime NOT NULL,
  `endDay` date NOT NULL,
  `endTime` time DEFAULT NULL,
  `endDatetimeUtc` datetime NOT NULL,
  `timezone` tinyint(2) NOT NULL DEFAULT '0',
  `longitude` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `latitude` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `location` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reminderEnable` tinyint(1) NOT NULL DEFAULT '0',
  `reminderType` enum('email','alert','sms') COLLATE utf8_unicode_ci DEFAULT NULL,
  `reminderTimeUnit` enum('minute','hour','day','week') COLLATE utf8_unicode_ci DEFAULT NULL,
  `reminderTimeValue` int(2) DEFAULT NULL,
  `registrationStart` datetime DEFAULT NULL,
  `registrationEnd` datetime DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `user_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `createTime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `eva_event_events_files`;
CREATE TABLE IF NOT EXISTS `eva_event_events_files` (
  `event_id` int(11) NOT NULL,
  `file_id` int(11) NOT NULL,
  PRIMARY KEY (`event_id`,`file_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `eva_event_events_users`;
CREATE TABLE IF NOT EXISTS `eva_event_events_users` (
  `user_id` int(10) NOT NULL,
  `event_id` int(10) NOT NULL,
  `role` enum('admin','member') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'member',
  `requestStatus` enum('pending','refused','active','blocked') COLLATE utf8_unicode_ci NOT NULL,
  `requestTime` datetime NOT NULL,
  `approvalTime` datetime DEFAULT NULL,
  `refusedTime` int(11) DEFAULT NULL,
  `blockedTime` int(11) DEFAULT NULL,
  `operator_id` int(10) NOT NULL,
  PRIMARY KEY (`user_id`,`event_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `eva_event_texts`;
CREATE TABLE IF NOT EXISTS `eva_event_texts` (
  `event_id` int(20) NOT NULL,
  `content` longtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`event_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `eva_event_events_activities`;
CREATE TABLE IF NOT EXISTS `eva_event_events_activities` (
  `event_id` int(10) NOT NULL,
  `message_id` bigint(30) NOT NULL,
  `messageTime` datetime NOT NULL,
  PRIMARY KEY (`event_id`,`message_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;