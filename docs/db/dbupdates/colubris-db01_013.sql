ALTER TABLE `task` ADD COLUMN `priority` INT(11) NULL DEFAULT NULL  AFTER `name` ;

ALTER TABLE `requirement` ADD COLUMN `estimate` DECIMAL(8,2) NULL DEFAULT NULL  AFTER `filestore_file_id` ;

