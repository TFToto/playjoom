if not exists (ALTER TABLE `#__jpaudiotracks` ADD `asset_id` INT( 255 ) NOT NULL AFTER `id`);
if not exists (ALTER TABLE `#__jpaudiotracks` ADD `coverid` INT( 11 ) NOT NULL AFTER `trackfilterid`); 