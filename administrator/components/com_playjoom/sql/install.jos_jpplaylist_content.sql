CREATE TABLE IF NOT EXISTS `#__jpplaylist_content` (
  `id` int(11) NOT NULL auto_increment,
  `track_id` int(11) NOT NULL,
  `lft` int(11) NOT NULL,
  `rgt` int(11) NOT NULL,
  `list_id` int(11) NOT NULL,
  `user_id` int(255) NOT NULL,
  `add_date` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1000;