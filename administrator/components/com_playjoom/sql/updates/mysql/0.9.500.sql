ALTER TABLE  `#__jpaudiotracks` ADD  `access` INT( 11 ) NOT NULL AFTER  `hits`;
ALTER TABLE  `#__jpplaylists` ADD  `access` INT( 11 ) NOT NULL AFTER  `user_id`;
ALTER TABLE  `#__jpplaylists` ADD  `catid` INT( 11 ) NOT NULL AFTER  `access`;
ALTER TABLE  `#__jpplaylists` ADD  `attach_artist` VARCHAR( 255 ) NOT NULL AFTER  `modifier_date`;
ALTER TABLE  `#__jpplaylists` ADD  `attach_genre` INT( 11 ) NOT NULL AFTER  `attach_artist`;