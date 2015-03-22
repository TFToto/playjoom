CREATE TABLE IF NOT EXISTS `#__jpaudiotracks_rating` (
  `track_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating_sum` int(10) NOT NULL,
  `rating_count` int(10) NOT NULL,
  `lastip` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1000;
