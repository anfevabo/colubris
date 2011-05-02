ALTER TABLE `user` ADD COLUMN `weekly_target` INT(11) NULL DEFAULT NULL  AFTER `hash` ;
update user set weekly_target=40;
