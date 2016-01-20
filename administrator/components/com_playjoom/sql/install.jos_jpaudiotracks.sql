CREATE TABLE IF NOT EXISTS `#__jpaudiotracks` (
  `id` int(255) NOT NULL auto_increment,
  `asset_id` int(255) NOT NULL,
  `md5` varchar(255) NOT NULL,
  `hits` int(11) NOT NULL,
  `access` int(11) NOT NULL,
  `access_datetime` DATETIME NOT NULL,
  `pathatlocal` varchar(255) NOT NULL,
  `file` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `alias` varchar(64) NOT NULL,
  `tracknumber` int(32) NOT NULL,
  `mediatype` varchar(32) NOT NULL,
  `bit_rate` int(16) NOT NULL,
  `sample_rate` int(16) NOT NULL,
  `channels` int(2) NOT NULL,
  `channelmode` varchar(32) NOT NULL,
  `filesize` int(64) NOT NULL COMMENT 'in Byte',
  `length` double NOT NULL COMMENT 'in seconds',
  `catid` int(11) NOT NULL,
  `trackfilterid` int(11) NOT NULL,
  `coverid` int(11) NOT NULL,
  `add_datetime` datetime NOT NULL,
  `add_by` INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `mod_datetime` datetime NOT NULL,
  `mod_by` INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `artist` varchar(255) NOT NULL,
  `album` varchar(255) NOT NULL,
  `year` int(4) NOT NULL,
  `description` longtext NOT NULL,
  `lyrics` longtext NOT NULL,
  `encoder` varchar(32) NOT NULL,
  `params` text NOT NULL,
  `published` tinyint(1) NOT NULL,
  `metakey` TEXT NOT NULL,
  `metadesc` TEXT NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1000 ;