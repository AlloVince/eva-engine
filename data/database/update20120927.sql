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