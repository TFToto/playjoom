CREATE TABLE IF NOT EXISTS `#__jpalbums` (
  `id` int(255) NOT NULL auto_increment,
  `title` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL,
  `artist` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `album_release` date NOT NULL,
  `label` varchar(255) NOT NULL,
  `production` varchar(255) NOT NULL,
  `infotxt` longtext NOT NULL,
  `catid` int(255) NOT NULL,
  `params` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1000;