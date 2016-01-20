CREATE TABLE IF NOT EXISTS `#__jpplaylists` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `access` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `catid` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `name` varchar(128) NOT NULL,
  `create_date` datetime NOT NULL,
  `modifier_date` datetime NOT NULL,
  `attach_artist` varchar(255) NOT NULL,
  `attach_genre` int(11) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1000;