CREATE TABLE `simplenewsletter_members` (
  `uid` mediumint(8) unsigned NOT NULL auto_increment,
  `sub_date` int(10) unsigned NOT NULL,
  `member_sent` tinyint(1) unsigned NOT NULL default '0',
  `member_uid` mediumint(8) unsigned NOT NULL,
  `member_firstname` varchar(255) NOT NULL,
  `member_lastname` varchar(255) NOT NULL,
  `member_password` varchar(32) NOT NULL,
  `member_verified` tinyint(1) unsigned NOT NULL,
  `member_email` varchar(255) NOT NULL,
  `member_temporary` tinyint(1) unsigned NOT NULL,
  `member_user_password` varchar(32) NOT NULL,
  `member_title` varchar(50) NOT NULL,
  `member_street` varchar(255) NOT NULL,
  `member_city` varchar(255) NOT NULL,
  `member_state` varchar(100) NOT NULL,
  `member_zip` varchar(50) NOT NULL,
  `member_telephone` varchar(50) NOT NULL,
  `member_fax` varchar(50) NOT NULL,
  PRIMARY KEY  (`uid`),
  KEY `sub_date` (`sub_date`),
  KEY `member_sent` (`member_sent`),
  KEY `member_verified` (`member_verified`)
) ENGINE=InnoDB;

CREATE TABLE `simplenewsletter_news` (
  `news_id` int(10) unsigned NOT NULL auto_increment,
  `news_title` varchar(255) NOT NULL,
  `news_body` text NOT NULL,
  `news_date` int(10) unsigned NOT NULL,
  `news_html` tinyint(1) unsigned NOT NULL,
  `news_uid` mediumint(8) unsigned NOT NULL,
  `news_sent` tinyint(1) unsigned NOT NULL,
  `news_paquets` int(10) unsigned NOT NULL,
  `news_members_sent` int(10) unsigned NOT NULL,
  `news_attachment` varchar(255) NOT NULL,
  `news_mime` varchar(255) NOT NULL,
  PRIMARY KEY  (`news_id`),
  KEY `news_date` (`news_date`),
  KEY `news_title` (`news_title`),
  KEY `news_sent` (`news_sent`)
) ENGINE=InnoDB;

CREATE TABLE `simplenewsletter_sent` (
  `sent_id` int(10) unsigned NOT NULL auto_increment,
  `sent_news_id` int(10) unsigned NOT NULL,
  `sent_uid` int(8) unsigned NOT NULL,
  PRIMARY KEY  (`sent_id`),
  KEY `new_uid` (`sent_news_id`,`sent_uid`),
  KEY `sent_uid` (`sent_uid`),
  KEY `sent_news_id` (`sent_news_id`)
) ENGINE=InnoDB;
