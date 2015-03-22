ALTER TABLE `#__jpaudiotracks`  ADD `add_by` INT(10) UNSIGNED NOT NULL DEFAULT '0' AFTER `add_datetime`;
ALTER TABLE `#__jpaudiotracks`  ADD `mod_by` INT(10) UNSIGNED NOT NULL DEFAULT '0' AFTER `mod_datetime`;
ALTER TABLE `#__jpaudiotracks`  ADD `metakey` TEXT NOT NULL AFTER `published`; 
ALTER TABLE `#__jpaudiotracks`  ADD `metadesc` TEXT NOT NULL AFTER `metakey`;
ALTER TABLE `#__jpaudiotracks`  ADD `access_datetime` DATETIME NOT NULL AFTER `hits`;