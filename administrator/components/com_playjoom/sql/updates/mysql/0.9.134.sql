ALTER TABLE `#__jpaudiotracks` DROP `pathatweb`;
ALTER TABLE `#__jpaudiotracks` DROP `frontcover`;
ALTER TABLE `#__jpaudiotracks` DROP `backcover`;
ALTER TABLE `#__jpaudiotracks`  ADD `hits` INT(11) NOT NULL AFTER `id`;