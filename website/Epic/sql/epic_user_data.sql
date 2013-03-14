INSERT INTO `eva_user_fieldoptions` (`id`, `field_id`, `label`, `option`, `order`) VALUES
(1, 2, 'Gastronomy and Fine Dining', 'Gastronomy and Fine Dining', 0),
(2, 2, 'Wine(oenology)', 'Wine(oenology)', 0),
(3, 2, 'Fine Spirit: Rhum, Cognac, Armagnac, Whisky, Vodka, Gin', 'Fine Spirit: Rhum, Cognac, Armagnac, Whisky, Vodka, Gin', 0),
(4, 2, 'Cocktails', 'Cocktails', 0),
(5, 2, 'Cigar', 'Cigar', 0),
(6, 2, 'Cooking', 'Cooking', 0),
(7, 2, 'Photographing food', 'Photographing food', 0),
(8, 2, 'Others', 'Others', 0),
(9, 3, 'Events(cocktail parties, wine tasting, tea party...)', 'Events(cocktail parties, wine tasting, tea party...)', 0),
(10, 3, 'Exhibitions', 'Exhibitions', 0),
(11, 3, 'Wine Trips', 'Wine Trips', 0),
(12, 3, 'Buying and collection Advices', 'Buying and collection Advices', 0),
(13, 3, 'Photo sharing', 'Photo sharing', 0),
(14, 3, 'Cooking classes', 'Cooking classes', 0),
(15, 3, 'Insider opinions sharing(locating new spots for dining, drinking.)', 'Insider opinions sharing(locating new spots for dining, drinking.)', 0),
(16, 3, 'Others', 'Others', 0),
(17, 5, 'Food, Wine and Spirit Production', 'Food, Wine and Spirit Production', 0),
(18, 5, 'Hospitality', 'Hospitality', 0),
(19, 5, 'Restaurant, bar and club', 'Restaurant, bar and club', 0),
(20, 5, 'Distribution and Trade', 'Distribution and Trade', 0),
(21, 5, 'Media and Press', 'Media and Press', 0),
(22, 5, 'Marketing and Communication', 'Marketing and Communication', 0),
(23, 5, 'Consultancy', 'Consultancy', 0),
(24, 5, 'Educational Institution', 'Educational Institution', 0),
(25, 5, 'Research and Development', 'Research and Development', 0),
(26, 5, 'Other', 'Other', 0),
(27, 6, 'Exhibition', 'exhibition', 0),
(28, 6, 'Business network events', 'business network events', 0),
(29, 6, 'Branding', 'branding', 0),
(30, 6, 'Business partners, Merge and Acquisition', 'Business partners, Merge and Acquisition', 0),
(31, 6, 'Talents search and recruiting', 'Talents search and recruiting', 0),
(32, 6, 'Consulting Services', 'Consulting Services', 0),
(33, 6, 'Financial Services', 'Financial Services', 0),
(34, 6, 'Other', 'Other', 0),
(35, 7, 'Jobs, internship Opportunities', 'Jobs, internship Opportunities', 0),
(36, 7, 'Exhibitions, Events', 'Exhibitions, Events', 0),
(37, 7, 'Distribution and International Trade', 'Distribution and International Trade', 0),
(38, 7, 'Consulting Service', 'Consulting Service', 0),
(39, 7, 'Talents search and recruiting', 'Talents search and recruiting', 0),
(40, 7, 'Consulting Services', 'Consulting Services', 0),
(41, 7, 'Financial Services', 'Financial Services', 0),
(42, 7, 'Other', 'Other', 0),
(43, 10, 'Food, Wine and Spirit Production', 'Food, Wine and Spirit Production', 0),
(44, 10, 'Hospitality', 'Hospitality', 0),
(45, 10, 'Restaurant, bar and club', 'Restaurant, bar and club', 0),
(46, 10, 'Distribution and Trade', 'Distribution and Trade', 0),
(47, 10, 'Media and Press', 'Media and Press', 0),
(48, 10, 'Marketing and Communication', 'Marketing and Communication', 0),
(49, 10, 'Consultancy', 'Consultancy', 0),
(50, 10, 'Educational Institution', 'Educational Institution', 0),
(51, 10, 'Research and Development', 'Research and Development', 0),
(52, 10, 'Other', 'Other', 0),
(53, 11, 'Jobs and Intern Opportunities', 'Jobs and Intern Opportunities', 0),
(54, 11, 'Social Network Opportunities', 'Social Network Opportunities', 0),
(55, 11, 'Others', 'Others', 0),
(56, 12, 'Chef, Sommelier, Mixologist barman', 'Chef, Sommelier, Mixologist barman', 0),
(57, 12, 'hospitality', 'hospitality', 0),
(58, 12, 'Restaurant and bar management', 'Restaurant and bar management', 0),
(59, 12, 'Distribution, Logistics, and trade', 'Distribution, Logistics, and trade', 0),
(60, 12, 'Public Communication', 'Public Communication', 0),
(61, 12, 'Marketing and Promotion', 'Marketing and Promotion', 0),
(62, 12, 'Events Planning', 'Events Planning', 0),
(63, 12, 'Consultancy', 'Consultancy', 0),
(64, 12, 'Financial Service(seed funding, venture capital. Insurance)', 'Financial Service(seed funding, venture capital. Insurance)', 0),
(65, 12, 'Design', 'Design', 0),
(72, 13, 'Mr.', 'Mr.', 0),
(73, 13, 'Miss.', 'Miss.', 0),
(74, 13, 'Mrs', 'Mrs', 0);


INSERT INTO `eva_user_fields` (`id`, `fieldName`, `fieldKey`, `fieldType`, `label`, `description`, `applyToAll`, `required`, `display`, `order`, `defaultValue`, `config`, `validators`, `filters`, `style`, `error`) VALUES
(1, 'Hobbies(Connoisseur)', 'Hobbies', 'text', 'Hobbies', '', 0, 0, 1, 0, '', NULL, NULL, NULL, NULL, NULL),
(2, 'Central Interests(Connoisseur)', 'CentralInterests', 'multiCheckbox', 'Central Interests', '', 0, 0, 1, 0, '', NULL, NULL, NULL, NULL, NULL),
(3, 'You might interested in(Connoisseur)', 'YouMightInterestedIn', 'multiCheckbox', 'You might interested in', '', 0, 0, 1, 0, '', NULL, NULL, NULL, NULL, NULL),
(4, 'Company Name(Corporate)', 'CompanyName', 'text', 'Company Name', '', 0, 0, 1, 0, '', NULL, NULL, NULL, NULL, NULL),
(5, 'Industry(Corporate)', 'IndustryIndustrie', 'select', 'Industry', '', 0, 0, 1, 0, '', NULL, NULL, NULL, NULL, NULL),
(6, 'How could we help you?(Corporate)', 'HowCouldWehelpYou', 'multiCheckbox', 'How could we help you?', '', 0, 0, 1, 0, '', NULL, NULL, NULL, NULL, NULL),
(7, 'What you are offering?(Corporate)', 'WhatYouAreOffering', 'multiCheckbox', 'What you are offering?', '', 0, 0, 1, 0, '', NULL, NULL, NULL, NULL, NULL),
(8, 'Hobbies(Professional)', 'Hobbies', 'text', 'Hobbies', '', 0, 0, 1, 0, '', NULL, NULL, NULL, NULL, NULL),
(9, 'School(Professional)', 'School', 'text', 'School', '', 0, 0, 1, 0, '', NULL, NULL, NULL, NULL, NULL),
(10, 'Industry(Professional)', 'IndustryIndustrie', 'select', 'Industry', '', 0, 0, 1, 0, '', NULL, NULL, NULL, NULL, NULL),
(11, 'How could we help you?(Professional)', 'HowCouldWehelpYou', 'multiCheckbox', 'How could we help you?', '', 0, 0, 1, 0, '', NULL, NULL, NULL, NULL, NULL),
(12, 'Your Expertise(Professional)', 'YourExpertise', 'multiCheckbox', 'Your Expertise', '', 0, 0, 1, 0, '', NULL, NULL, NULL, NULL, NULL),
(13, 'Title', 'aQR7Jv', 'radio', 'Title', '', 1, 0, 0, 0, '', NULL, NULL, NULL, NULL, NULL);



INSERT INTO `eva_user_fields_roles` (`field_id`, `role_id`) VALUES
(1, 12),
(2, 12),
(3, 12),
(4, 11),
(5, 11),
(6, 11),
(7, 11),
(8, 13),
(9, 13),
(10, 13),
(11, 13),
(12, 13);


INSERT INTO `eva_user_roles` (`id`, `roleKey`, `roleName`, `description`) VALUES
(1, 'ADMIN', 'Admin', ''),
(2, 'USER', 'User', ''),
(3, 'GUEST', 'Guest', ''),
(11, 'CORPORATE_MEMBER', 'Corporate Member', ''),
(12, 'CONNOISSEUR_MEMBER', 'Connoisseur Member', ''),
(13, 'PROFESSIONAL_MEMBER', 'Professional Member', ''),
(14, 'PAID_MEMBER', 'VIP Member', '');
;

INSERT INTO `eva_event_categories` (`id`, `categoryName`, `urlName`, `description`, `parentId`, `rootId`, `orderNumber`, `createTime`, `count`, `left`, `right`) VALUES
(1, 'Dining', 'dining', '', 0, 0, 0, '2013-02-01 06:46:51', 0, 0, 0),
(2, 'Wine', 'wine', '', 0, 0, 0, '2013-02-01 06:46:59', 0, 0, 0),
(3, 'Food', 'food', '', 0, 0, 0, '2013-02-01 06:47:09', 0, 0, 0),
(4, 'Nightlife', 'nightlife', '', 0, 0, 0, '2013-02-01 06:47:26', 0, 0, 0),
(5, 'Culture', 'culture', '', 0, 0, 0, '2013-02-01 06:47:38', 0, 0, 0),
(6, 'Cocktail', 'cocktail', '', 0, 0, 0, '2013-02-01 06:48:33', 0, 0, 0),
(7, 'Tasting', 'tasting', '', 0, 0, 0, '2013-02-01 06:48:47', 0, 0, 0),
(8, 'Cooking', 'cooking', '', 0, 0, 0, '2013-02-01 06:48:59', 0, 0, 0),
(9, 'Education', 'education', '', 0, 0, 0, '2013-02-01 06:49:23', 0, 0, 0),
(10, 'Organic & Biologic food', 'organic-biologic-food', '', 0, 0, 0, '2013-02-01 06:50:17', 0, 0, 0),
(11, 'Spirit', 'spirit', '', 0, 0, 0, '2013-02-01 06:50:45', 0, 0, 0),
(12, 'Travel', 'travel', '', 0, 0, 0, '2013-02-01 06:51:01', 0, 0, 0),
(13, 'Exhibition', 'exhibition', '', 0, 0, 0, '2013-02-01 06:51:14', 0, 0, 0),
(14, 'Other', 'other', '', 0, 0, 0, '2013-02-01 06:51:27', 0, 0, 0);



INSERT INTO `eva_group_categories` (`id`, `categoryName`, `urlName`, `description`, `parentId`, `rootId`, `orderNumber`, `createTime`, `count`, `left`, `right`) VALUES
(1, 'Gastronomy', 'gastronomy', '', 0, 0, 0, '2012-12-26 10:03:15', 0, 0, 0),
(2, 'Wine', 'wine', '', 0, 0, 0, '2012-12-26 10:03:32', 0, 0, 0),
(3, 'Spirit', 'spirit', '', 0, 0, 0, '2012-12-26 10:04:00', 0, 0, 0),
(4, 'Cocktail', 'cocktail', '', 0, 0, 0, '2012-12-26 10:04:18', 0, 0, 0),
(5, 'Hotel & Resort', 'hotel-resort', '', 0, 0, 0, '2012-12-26 10:04:43', 0, 0, 0),
(6, 'Art & Design', 'art-design', '', 0, 0, 0, '2012-12-26 10:07:30', 0, 0, 0),
(7, 'Culture', 'culture', '', 0, 0, 0, '2012-12-26 10:11:15', 0, 0, 0);



INSERT INTO `eva_notification_notifications` (
`id` ,
`notificationKey` ,
`title` ,
`sendNotice` ,
`sendEmail` ,
`sendSms` ,
`sendAppleOsPush` ,
`sendAndroidPush` ,
`sendWindowsPush` ,
`sendCustomNotice` ,
`allowDisableNotice` ,
`allowDisableEmail` ,
`allowDisableSms` ,
`allowDisableAppleOsPush` ,
`allowDisableAndroidPush` ,
`allowDisableWindowsPush` ,
`allowDisableCustomNotice`
)
VALUES (
NULL , 'ActivityAt', 'Notification user who is in activities @', '1', '1', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0'
);

