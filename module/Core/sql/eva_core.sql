DROP TABLE IF EXISTS `eva_core_newsletters`;
CREATE TABLE IF NOT EXISTS `eva_core_newsletters` (
  `user_id` int(11) NOT NULL,
  `email` varchar(320) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
