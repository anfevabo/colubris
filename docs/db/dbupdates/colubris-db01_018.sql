ALTER TABLE `user` ADD COLUMN `weekly_hours` INT(11) NULL DEFAULT NULL  AFTER `hash` ;
update user set weekly_hours=40;
