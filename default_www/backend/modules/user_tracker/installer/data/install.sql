CREATE TABLE IF NOT EXISTS `user_tracker_data` (
  `id` varchar(32) NOT NULL,
  `name` varchar(255) NOT NULL,
  `value` text,
  `added_on` datetime NOT NULL,
  KEY `idx_id_name` (`id`,`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `user_tracker_pageviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `visitor_identifier` varchar(255) NOT NULL,
  `visitor_session` varchar(255) NOT NULL,
  `url` text,
  `referrer_host` text,
  `referrer_path` text,
  `referrer_query` text,
  `status` int(11) NOT NULL,
  `added_on` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;