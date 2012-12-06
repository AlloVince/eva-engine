DROP TABLE IF EXISTS `eva_oauth_accesstokens`;
CREATE TABLE IF NOT EXISTS `eva_oauth_accesstokens` (
  `adapterKey` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `tokenSecret` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `version` enum('Oauth1','Oauth2') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Oauth2',
  `tokenStatus` enum('active','expried') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'active',
  `scope` text COLLATE utf8_unicode_ci,
  `refreshToken` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `refreshTime` datetime DEFAULT NULL,
  `expireTime` datetime DEFAULT NULL,
  `remoteUserId` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remoteUserName` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remoteExtra` mediumtext COLLATE utf8_unicode_ci,
  `user_id` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`adapterKey`,`token`,`version`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
