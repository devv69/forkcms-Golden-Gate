CREATE TABLE IF NOT EXISTS `user_tracker_data` (
  `id` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value` text COLLATE utf8_unicode_ci NOT NULL,
  `added_on` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `user_tracker_pageviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `visitor_identifier` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `visitor_session` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `url` text COLLATE utf8_unicode_ci NOT NULL,
  `referrer_host` text COLLATE utf8_unicode_ci,
  `referrer_path` text COLLATE utf8_unicode_ci,
  `referrer_query` text COLLATE utf8_unicode_ci,
  `status` enum('200','404') COLLATE utf8_unicode_ci NOT NULL,
  `added_on` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;