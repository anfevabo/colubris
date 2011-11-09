ALTER TABLE `budget` ADD COLUMN `total_pct` INT(11) NULL DEFAULT NULL  AFTER `start_date` , ADD COLUMN `timeline_html` TEXT NULL DEFAULT NULL  AFTER `total_pct` ;

