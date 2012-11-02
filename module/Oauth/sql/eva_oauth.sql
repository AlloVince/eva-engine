SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


DROP TABLE IF EXISTS `eva_oauth_accesstokens`;
CREATE TABLE IF NOT EXISTS `eva_oauth_accesstokens` (
  `adapterKey` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `tokenSecret` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `version` enum('Oauth1','Oauth2') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Oauth2',
  `refreshToken` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `refreshTime` datetime DEFAULT NULL,
  `expireTime` datetime DEFAULT NULL,
  `remoteUserId` bigint(20) DEFAULT NULL,
  `remoteUserName` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remoteExtra` mediumtext COLLATE utf8_unicode_ci,
  `user_id` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`adapterKey`,`token`,`version`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
