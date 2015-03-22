CREATE TABLE IF NOT EXISTS `#__jpcoverblobs` (
  `id` int(255) NOT NULL auto_increment,
  `artist` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL,
  `album` varchar(255) NOT NULL,
  `md5` varchar(255) NOT NULL,
  `width` int(64) NOT NULL default '0',
  `height` int(64) NOT NULL default '0',
  `mime` varchar(128) NOT NULL,
  `params` varchar(255) NOT NULL,
  `data` longblob NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1000;