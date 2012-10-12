SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

DROP TABLE IF EXISTS `eva_payment_logs`;
CREATE TABLE IF NOT EXISTS `eva_payment_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `secretKey` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `logStep` enum('request','response','cancel') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'request',
  `service` enum('paypal','alipay') COLLATE utf8_unicode_ci NOT NULL,
  `adapter` enum('paypalec','alipayec') COLLATE utf8_unicode_ci NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `requestTime` datetime NOT NULL,
  `responseTime` datetime DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;
