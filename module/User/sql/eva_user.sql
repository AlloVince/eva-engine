SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


DROP TABLE IF EXISTS `eva_user_accounts`;
CREATE TABLE IF NOT EXISTS `eva_user_accounts` (
  `user_id` int(11) NOT NULL,
  `credits` decimal(10,2) NOT NULL DEFAULT '0.00',
  `points` decimal(10,2) NOT NULL DEFAULT '0.00',
  `discount` decimal(5,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `eva_user_accounts` (`user_id`, `credits`, `points`, `discount`) VALUES
(2, 0.00, 0.00, 0.00);

DROP TABLE IF EXISTS `eva_user_fieldoptions`;
CREATE TABLE IF NOT EXISTS `eva_user_fieldoptions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `field_id` int(11) unsigned NOT NULL,
  `label` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `option` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `order` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `field_id` (`field_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `eva_user_fields`;
CREATE TABLE IF NOT EXISTS `eva_user_fields` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `fieldName` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `fieldKey` varchar(24) COLLATE utf8_unicode_ci NOT NULL,
  `fieldType` enum('text','radio','select','multiCheckbox','number','email','textarea','url') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'text',
  `label` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `applyToAll` tinyint(1) NOT NULL DEFAULT '0',
  `required` tinyint(1) NOT NULL DEFAULT '0',
  `display` tinyint(1) unsigned NOT NULL,
  `order` smallint(3) unsigned NOT NULL DEFAULT '0',
  `defaultValue` text COLLATE utf8_unicode_ci NOT NULL,
  `config` text COLLATE utf8_unicode_ci,
  `validators` text COLLATE utf8_unicode_ci,
  `filters` text COLLATE utf8_unicode_ci,
  `style` text COLLATE utf8_unicode_ci,
  `error` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `eva_user_fields_roles`;
CREATE TABLE IF NOT EXISTS `eva_user_fields_roles` (
  `field_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  PRIMARY KEY (`field_id`,`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `eva_user_fieldvalues`;
CREATE TABLE IF NOT EXISTS `eva_user_fieldvalues` (
  `field_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `value` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`field_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `eva_user_friends`;
CREATE TABLE IF NOT EXISTS `eva_user_friends` (
  `from_user_id` int(10) NOT NULL,
  `to_user_id` int(10) NOT NULL,
  `relationShipStatus` enum('pending','refused','active','blocked') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'pending',
  `requestTime` datetime NOT NULL,
  `approcalTime` datetime DEFAULT NULL,
  `refusedTime` datetime DEFAULT NULL,
  `blockedTime` datetime DEFAULT NULL,
  PRIMARY KEY (`from_user_id`,`to_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `eva_user_oauths`;
CREATE TABLE IF NOT EXISTS `eva_user_oauths` (
  `user_id` int(10) NOT NULL,
  `appType` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `tokenSecret` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `version` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `refreshToken` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `refreshTime` datetime DEFAULT NULL,
  `expireTime` datetime DEFAULT NULL,
  `appUserId` bigint(20) DEFAULT NULL,
  `appUserName` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `appExt` mediumtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`user_id`,`appType`,`token`,`tokenSecret`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `eva_user_oauths` (`user_id`, `appType`, `token`, `tokenSecret`, `version`, `refreshToken`, `refreshTime`, `expireTime`, `appUserId`, `appUserName`, `appExt`) VALUES
(1, '360safe', '1012028785a37944533bff5b145db9275b5c57082855a8b18', '', '2', '101202878d5fa4f0e50bd590636848b86c56e528ffe59c8a6', '2012-04-21 14:33:34', '2012-04-21 15:33:33', 101202878, 'AlloVince', 'a:5:{s:2:"id";s:9:"101202878";s:4:"name";s:9:"AlloVince";s:6:"avatar";s:101:"http://u1.qhimg.com/qhimg/quc/48_48/2d/02/16/2d0216q136.50d8d4.jpg?f=4d0c752bbe0a9dae988c0c2d90812fb3";s:3:"sex";s:3:"男";s:4:"area";s:16:"甘肃省 兰州";}'),
(1, 'baidu', '3.deea40651b7440c9805de8c2fbb4c3e8.2592000.1329294048.3590640020-188401', '', '2', '4.fcd4f006157b46c8f9a19148df307727.315360000.1642062048.3590640020-188401', '2012-01-16 08:54:55', '2012-02-15 08:54:55', 3590640020, 'AlloVince', 'a:13:{s:6:"userid";s:10:"3590640020";s:8:"username";s:9:"AlloVince";s:8:"realname";s:0:"";s:8:"portrait";s:26:"9fd6416c6c6f56696e63654b00";s:8:"birthday";s:10:"1984-08-17";s:8:"marriage";s:6:"恋爱";s:3:"sex";s:3:"男";s:5:"blood";s:1:"B";s:6:"figure";s:6:"苗条";s:13:"constellation";s:6:"天秤";s:9:"education";s:13:"大学/专科";s:5:"trade";s:22:"计算机/电子产品";s:3:"job";s:6:"未知";}'),
(1, 'baiduapp', '3.cabc053aaea117371c859cbc4d66b815.2592000.1327822287.3590640020-149812', '', '2', '4.1d44446cfaed4f8ab53b753ece611c8a.315360000.1640590287.3590640020-149812', '2011-12-30 07:59:57', '2012-01-29 07:59:57', 3590640020, 'AlloVince', 'a:13:{s:6:"userid";s:10:"3590640020";s:8:"username";s:9:"AlloVince";s:8:"realname";s:0:"";s:8:"portrait";s:26:"9fd6416c6c6f56696e63654b00";s:8:"birthday";s:10:"1984-08-17";s:8:"marriage";s:6:"恋爱";s:3:"sex";s:3:"男";s:5:"blood";s:1:"B";s:6:"figure";s:6:"苗条";s:13:"constellation";s:6:"天秤";s:9:"education";s:13:"大学/专科";s:5:"trade";s:22:"计算机/电子产品";s:3:"job";s:6:"未知";}'),
(1, 'douban', '297f806418e9ed257c7e812c4abdc76f', '6e1ed5d4e6583341', '1', NULL, NULL, NULL, 1291360, NULL, NULL),
(1, 'kaixin', '206377_100020330_206377_1332390050_962f453e1a6ff0e4bf3a09028e3a0d56', '', '2', NULL, '2012-03-22 04:21:48', NULL, 206377, '徐谦', 'a:4:{s:3:"uid";s:6:"206377";s:4:"name";s:6:"徐谦";s:6:"gender";s:1:"0";s:6:"logo50";s:40:"http://img.kaixin001.com.cn/i/50_0_0.gif";}'),
(1, 'qplus', 'AC9AAE6BFF4C5E062896CD36359D4984', '', '2', NULL, NULL, NULL, NULL, NULL, 'a:2:{s:11:"appUserName";s:3:"A@V";s:6:"avatar";s:234:"http://q2.qlogo.cn/headimg_dl?bs=qq&amp;dst_uin=ce95d306356efaf056826d4987e343ea&amp;src_uin=ce95d306356efaf056826d4987e343ea&amp;fid=ce95d306356efaf056826d4987e343ea&amp;spec=40&amp;url_enc=1&amp;referer=bu_interface&amp;term_type=PC";}'),
(1, 'qq', 'DEA3FEA777883566CDB33396AF4D7235', '52528B53D760A57D3954AD219AF307FE', '2', NULL, '2012-01-06 10:13:26', '2012-04-05 10:13:26', NULL, NULL, NULL),
(1, 'qzoneapp', '61316A27F2DE53EF484FA6EE5A919425', '', '2', NULL, NULL, NULL, NULL, 'A@V', 'a:12:{s:3:"ret";i:0;s:7:"is_lost";i:0;s:8:"nickname";s:3:"A@V";s:6:"gender";s:3:"男";s:7:"country";s:6:"中国";s:8:"province";s:6:"甘肃";s:4:"city";s:9:"兰州市";s:9:"figureurl";s:103:"http://thirdapp3.qlogo.cn/qzopenapp/d8219673598dbd6f7925a9aca802050c218ec4240f305ed038a7a0b498bd1cc7/50";s:13:"is_yellow_vip";i:0;s:18:"is_yellow_year_vip";i:0;s:16:"yellow_vip_level";i:0;s:18:"is_yellow_high_vip";i:0;}'),
(1, 'renren', '169260|6.d6fc0b29520a69af774bdf04bdff6183.2592000.1324530000-241264504', '', '2', '169260|7.d2730a736bcdf5e03634575f79e27aa0.5184000.1327122000-241264504', '2011-11-22 04:43:45', '2011-12-22 05:15:56', 241264504, '徐谦', 'a:4:{i:0;a:2:{s:4:"type";s:6:"avatar";s:3:"url";s:68:"http://hd44.xiaonei.com/photos/hd44/20080711/21/02/head_5071g107.jpg";}i:1;a:2:{s:4:"type";s:4:"tiny";s:3:"url";s:68:"http://hd44.xiaonei.com/photos/hd44/20080711/21/02/tiny_5071g107.jpg";}i:2;a:2:{s:4:"type";s:4:"main";s:3:"url";s:68:"http://hd44.xiaonei.com/photos/hd44/20080711/21/02/main_5071g107.jpg";}i:3;a:2:{s:4:"type";s:5:"large";s:3:"url";s:69:"http://hd44.xiaonei.com/photos/hd44/20080711/21/02/large_5282i107.jpg";}}'),
(1, 'renrenapp', '2.755b3ca7796f272f1a59aed9fd9f201b.3600.1336719600-241264504', '', '2', NULL, '2012-05-11 05:58:06', NULL, 241264504, '徐谦', 'a:8:{s:3:"uid";i:241264504;s:7:"tinyurl";s:68:"http://hd44.xiaonei.com/photos/hd44/20080711/21/02/tiny_5071g107.jpg";s:3:"vip";i:1;s:3:"sex";i:1;s:4:"name";s:6:"徐谦";s:4:"star";i:1;s:7:"headurl";s:68:"http://hd44.xiaonei.com/photos/hd44/20080711/21/02/head_5071g107.jpg";s:5:"zidou";i:0;}'),
(1, 'taobao', '6201313f535a47890d5d1ZZfe30411782e801f999ec681513425379', '', '2', '6201f132e2760de731ce1ZZ105e8338957bb9a282d053a613425379', '2012-06-05 13:36:03', '2012-06-06 13:36:03', 13425379, 'allovince', 'a:13:{s:14:"alipay_account";s:20:"allo.vince@gmail.com";s:11:"alipay_bind";s:4:"bind";s:9:"alipay_no";s:20:"20880020157286870156";s:8:"birthday";s:19:"1984-08-17 00:00:00";s:19:"consumer_protection";b:0;s:5:"email";s:20:"allo.vince@gmail.com";s:8:"location";a:3:{s:4:"city";s:6:"兰州";s:5:"state";s:6:"甘肃";s:3:"zip";s:6:"730000";}s:4:"nick";s:9:"allovince";s:3:"sex";s:1:"m";s:6:"status";s:6:"normal";s:4:"type";s:1:"C";s:3:"uid";s:32:"4fcb708edbde83cd018e1539cbe3efe7";s:7:"user_id";i:13425379;}'),
(1, 'weibo', '2.00zno_nB0eBxa6ca3145afc7LS4ypB', '', '2', NULL, '2012-05-15 13:11:15', '2012-05-22 13:11:14', 1644896827, 'Allo', 'a:31:{s:2:"id";i:1644896827;s:5:"idstr";s:10:"1644896827";s:11:"screen_name";s:4:"Allo";s:4:"name";s:4:"Allo";s:8:"province";s:2:"62";s:4:"city";s:1:"1";s:8:"location";s:13:"甘肃 兰州";s:11:"description";s:20:"信我者，得AV。";s:3:"url";s:16:"http://avnpc.com";s:17:"profile_image_url";s:48:"http://tp4.sinaimg.cn/1644896827/50/1265339753/1";s:11:"profile_url";s:5:"avnpc";s:6:"domain";s:5:"avnpc";s:6:"weihao";s:0:"";s:6:"gender";s:1:"m";s:15:"followers_count";i:1219;s:13:"friends_count";i:29;s:14:"statuses_count";i:166;s:16:"favourites_count";i:1;s:10:"created_at";s:30:"Tue Jan 05 23:45:26 +0800 2010";s:9:"following";b:0;s:17:"allow_all_act_msg";b:0;s:11:"geo_enabled";b:1;s:8:"verified";b:0;s:13:"verified_type";i:-1;s:17:"allow_all_comment";b:0;s:12:"avatar_large";s:49:"http://tp4.sinaimg.cn/1644896827/180/1265339753/1";s:15:"verified_reason";s:0:"";s:9:"follow_me";b:0;s:13:"online_status";i:1;s:18:"bi_followers_count";i:20;s:4:"lang";s:5:"zh-cn";}'),
(1, 'weiboapp', '2.00zno_nBlxn6sD76be78c02d0d51Yo', '', '2', NULL, '2012-05-09 14:47:50', NULL, 1644896827, 'Allo', 'a:32:{s:2:"id";i:1644896827;s:5:"idstr";s:10:"1644896827";s:11:"screen_name";s:4:"Allo";s:4:"name";s:4:"Allo";s:8:"province";s:2:"62";s:4:"city";s:1:"1";s:8:"location";s:13:"甘肃 兰州";s:11:"description";s:20:"信我者，得AV。";s:3:"url";s:16:"http://avnpc.com";s:17:"profile_image_url";s:48:"http://tp4.sinaimg.cn/1644896827/50/1265339753/1";s:11:"profile_url";s:5:"avnpc";s:6:"domain";s:5:"avnpc";s:6:"weihao";s:0:"";s:6:"gender";s:1:"m";s:15:"followers_count";i:1097;s:13:"friends_count";i:27;s:14:"statuses_count";i:159;s:16:"favourites_count";i:1;s:10:"created_at";s:30:"Tue Jan 05 23:45:26 +0800 2010";s:9:"following";b:0;s:17:"allow_all_act_msg";b:0;s:11:"geo_enabled";b:1;s:8:"verified";b:0;s:13:"verified_type";i:-1;s:17:"allow_all_comment";b:0;s:12:"avatar_large";s:49:"http://tp4.sinaimg.cn/1644896827/180/1265339753/1";s:15:"verified_reason";s:0:"";s:9:"follow_me";b:0;s:13:"online_status";i:1;s:18:"bi_followers_count";i:20;s:4:"lang";s:5:"zh-cn";s:5:"token";s:32:"2.00zno_nBlxn6sD76be78c02d0d51Yo";}'),
(1, '360safe', '1334021554e9953f45c36d33d2a41966675b7ee98767f37c5', '', '2', '13340215589ffd5aca196ee70a8bca76dde51904b8871f75a', '2012-01-06 10:20:49', '2012-01-06 11:20:49', 133402155, '360U133402155', 'a:5:{s:2:"id";s:9:"133402155";s:4:"name";s:13:"360U133402155";s:6:"avatar";s:104:"http://u.qhimg.com/qhimg/quc/48_48/19/01/44/190144aq118a4e.451ab3.jpg?f=4d0c752bbe0a9dae988c0c2d90812fb3";s:3:"sex";s:6:"未知";s:4:"area";s:0:"";}'),
(2, '360safe', '1012028785a37944533bff5b145db9275b5c57082855a8b18', '', '2', '101202878d5fa4f0e50bd590636848b86c56e528ffe59c8a6', '2012-04-21 14:33:34', '2012-04-21 15:33:33', 101202878, 'AlloVince', 'a:5:{s:2:"id";s:9:"101202878";s:4:"name";s:9:"AlloVince";s:6:"avatar";s:101:"http://u1.qhimg.com/qhimg/quc/48_48/2d/02/16/2d0216q136.50d8d4.jpg?f=4d0c752bbe0a9dae988c0c2d90812fb3";s:3:"sex";s:3:"男";s:4:"area";s:16:"甘肃省 兰州";}');

DROP TABLE IF EXISTS `eva_user_options`;
CREATE TABLE IF NOT EXISTS `eva_user_options` (
  `user_id` int(10) NOT NULL,
  `optionKey` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `optionValue` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`user_id`,`optionKey`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `eva_user_profiles`;
CREATE TABLE IF NOT EXISTS `eva_user_profiles` (
  `user_id` int(10) NOT NULL,
  `site` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `photoDir` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `photoName` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fullName` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `height` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `weight` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `addressMore` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `province` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `zipcode` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `degree` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `industry` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phoneBusiness` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phoneMobile` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phoneHome` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fax` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `signature` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `longitude` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `latitude` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `location` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bio` text COLLATE utf8_unicode_ci,
  `localIm` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `internalIm` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `otherIm` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `eva_user_profiles` (`user_id`, `site`, `photoDir`, `photoName`, `fullName`, `birthday`, `height`, `weight`, `country`, `address`, `addressMore`, `city`, `province`, `state`, `zipcode`, `degree`, `industry`, `phoneBusiness`, `phoneMobile`, `phoneHome`, `fax`, `signature`, `longitude`, `latitude`, `location`, `bio`, `localIm`, `internalIm`, `otherIm`) VALUES
(2, '', '', '', '', '0000-00-00', '', '', '001', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');

DROP TABLE IF EXISTS `eva_user_roles`;
CREATE TABLE IF NOT EXISTS `eva_user_roles` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `roleName` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

INSERT INTO `eva_user_roles` (`id`, `roleName`, `description`) VALUES
(1, 'Corporate Member', ''),
(2, 'Connoisseur Member', '123'),
(3, 'Professional Member', ''),
(4, 'Admin', '');

DROP TABLE IF EXISTS `eva_user_roles_users`;
CREATE TABLE IF NOT EXISTS `eva_user_roles_users` (
  `role_id` int(5) NOT NULL,
  `user_id` int(10) NOT NULL,
  `status` enum('active','pending','expired') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'pending',
  `pendingTime` datetime DEFAULT NULL,
  `activeTime` datetime DEFAULT NULL,
  `expiredTime` datetime DEFAULT NULL,
  PRIMARY KEY (`role_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `eva_user_roles_users` (`role_id`, `user_id`, `status`, `pendingTime`, `activeTime`, `expiredTime`) VALUES
(1, 2, 'pending', NULL, NULL, NULL),
(2, 2, 'pending', NULL, NULL, NULL);

DROP TABLE IF EXISTS `eva_user_tags`;
CREATE TABLE IF NOT EXISTS `eva_user_tags` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `tagName` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `language` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'en',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `eva_user_tags_users`;
CREATE TABLE IF NOT EXISTS `eva_user_tags_users` (
  `user_id` int(10) NOT NULL,
  `tag_id` int(10) NOT NULL,
  `createTime` datetime NOT NULL,
  `orderNumber` int(5) NOT NULL,
  PRIMARY KEY (`user_id`,`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `eva_user_users`;
CREATE TABLE IF NOT EXISTS `eva_user_users` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `userName` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(320) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` enum('active','deleted','inactive') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'active',
  `screenName` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `salt` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `firstName` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lastName` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oldPassword` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lastPasswordChangeTime` datetime DEFAULT NULL,
  `gender` enum('male','female','other') COLLATE utf8_unicode_ci DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `timezone` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `registerTime` datetime DEFAULT NULL,
  `lastLoginTime` datetime DEFAULT NULL,
  `language` varchar(10) COLLATE utf8_unicode_ci DEFAULT 'en',
  `setting` int(10) NOT NULL DEFAULT '0',
  `inviteUserId` int(10) DEFAULT '0',
  `onlineStatus` enum('online','busy','invisible','offline') COLLATE utf8_unicode_ci DEFAULT 'offline',
  `lastFleshTime` datetime DEFAULT NULL,
  `viewCount` bigint(20) NOT NULL DEFAULT '0',
  `registerIp` varbinary(16) DEFAULT NULL,
  `lastLoginIp` varbinary(16) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

INSERT INTO `eva_user_users` (`id`, `userName`, `email`, `status`, `screenName`, `salt`, `firstName`, `lastName`, `password`, `oldPassword`, `lastPasswordChangeTime`, `gender`, `avatar`, `timezone`, `registerTime`, `lastLoginTime`, `language`, `setting`, `inviteUserId`, `onlineStatus`, `lastFleshTime`, `viewCount`, `registerIp`, `lastLoginIp`) VALUES
(2, 'AlloVince', 'allo.vince@gmail.com', 'active', '', 'fIJ3Qj7b2EFzaqYnAb', '', '', '', '', NULL, 'male', '', 'Etc/Unknown', NULL, NULL, 'zh', 0, 0, NULL, NULL, 0, NULL, NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
