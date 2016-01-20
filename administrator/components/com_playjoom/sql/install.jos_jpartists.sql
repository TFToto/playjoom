CREATE TABLE IF NOT EXISTS `#__jpartists` (
  `id` int(255) NOT NULL auto_increment,
  `catid` int(255) NOT NULL,
  `name` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL,
  `alias` varchar(255) NOT NULL,
  `formation` date NOT NULL,
  `members` longtext NOT NULL,
  `infotxt` longtext NOT NULL,
  `params` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1000;