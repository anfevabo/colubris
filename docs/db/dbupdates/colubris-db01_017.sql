SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;

ALTER TABLE `budget` DROP FOREIGN KEY `fk_budget_client1` ;

ALTER TABLE `report` DROP FOREIGN KEY `fk_report_task1` ;

ALTER TABLE `task` DROP FOREIGN KEY `fk_task_screen1` ;

ALTER TABLE `requirement` DROP FOREIGN KEY `fk_screen_filestore_file1` , DROP FOREIGN KEY `fk_screen_budget1` ;

ALTER TABLE `user` COLLATE = utf8_general_ci , CHANGE COLUMN `name` `name` VARCHAR(255) CHARACTER SET 'latin1' NULL DEFAULT NULL  , CHANGE COLUMN `email` `email` VARCHAR(255) CHARACTER SET 'latin1' NULL DEFAULT NULL  , CHANGE COLUMN `password` `password` VARCHAR(255) CHARACTER SET 'latin1' NULL DEFAULT NULL  , CHANGE COLUMN `is_admin` `is_admin` ENUM('Y','N') CHARACTER SET 'latin1' NULL DEFAULT 'N'  , CHANGE COLUMN `is_manager` `is_manager` ENUM('Y','N') CHARACTER SET 'latin1' NULL DEFAULT 'N'  , CHANGE COLUMN `is_developer` `is_developer` ENUM('Y','N') CHARACTER SET 'latin1' NULL DEFAULT 'Y'  ;

ALTER TABLE `budget` COLLATE = utf8_general_ci , DROP COLUMN `client_id` , ADD COLUMN `manhours` DECIMAL(8,2) NULL DEFAULT NULL  AFTER `mandays` , ADD COLUMN `is_monthly` ENUM('Y','N') NULL DEFAULT NULL  AFTER `accepted` , CHANGE COLUMN `project_id` `project_id` INT(11) NOT NULL  AFTER `amount_eur` , CHANGE COLUMN `name` `name` VARCHAR(255) CHARACTER SET 'latin1' NULL DEFAULT NULL  
, DROP INDEX `fk_budget_client1` ;

ALTER TABLE `client` COLLATE = utf8_general_ci , CHANGE COLUMN `name` `name` VARCHAR(255) CHARACTER SET 'latin1' NULL DEFAULT NULL  ;

ALTER TABLE `report` COLLATE = utf8_general_ci , DROP COLUMN `task_id` , ADD COLUMN `revision` VARCHAR(255) NULL DEFAULT NULL  AFTER `amount` , CHANGE COLUMN `result` `result` TEXT CHARACTER SET 'latin1' NULL DEFAULT NULL  
, DROP INDEX `fk_report_task1` ;

ALTER TABLE `participant` COLLATE = utf8_general_ci , ADD COLUMN `hourly_cost` INT(11) NULL DEFAULT NULL  AFTER `daily_cost` , CHANGE COLUMN `daily_cost` `daily_cost` INT(11) NULL DEFAULT NULL  ;

ALTER TABLE `task` COLLATE = utf8_general_ci , CHANGE COLUMN `screen_id` `requirement_id` INT(11) NULL DEFAULT NULL  , 
  ADD CONSTRAINT `fk_task_screen1`
  FOREIGN KEY (`requirement_id` )
  REFERENCES `requirement` (`id` )
  ON DELETE NO ACTION
  ON UPDATE NO ACTION
, DROP INDEX `fk_task_screen1` 
, ADD INDEX `fk_task_screen1` (`requirement_id` ASC) ;

ALTER TABLE `project` COLLATE = utf8_general_ci ;

ALTER TABLE `requirement` COLLATE = utf8_general_ci , 
  ADD CONSTRAINT `fk_screen_project1`
  FOREIGN KEY (`project_id` )
  REFERENCES `project` (`id` )
  ON DELETE NO ACTION
  ON UPDATE NO ACTION, 
  ADD CONSTRAINT `fk_requirement_budget1`
  FOREIGN KEY (`budget_id` )
  REFERENCES `budget` (`id` )
  ON DELETE NO ACTION
  ON UPDATE NO ACTION
, ADD INDEX `fk_screen_project1` (`project_id` ASC) ;

ALTER TABLE `filestore_volume` COLLATE = utf8_general_ci ;

ALTER TABLE `filestore_type` COLLATE = utf8_general_ci ;

ALTER TABLE `filestore_file` COLLATE = utf8_general_ci ;

ALTER TABLE `timesheet`
      ADD COLUMN `client_id` INT(11) NULL DEFAULT NULL  AFTER `user_id` , 
      ADD COLUMN `project_id` INT(11) NULL DEFAULT NULL  AFTER `client_id` , 
      ADD COLUMN `budget_id` INT(11) NULL DEFAULT NULL  AFTER `project_id` ,
      ADD COLUMN `requirement_id` INT(11) NULL DEFAULT NULL  AFTER `budget_id` , 
      ADD COLUMN `task_id` INT(11) NULL DEFAULT NULL  AFTER `requirement_id` , 
  ADD CONSTRAINT `fk_timesheet_report1`
  FOREIGN KEY (`report_id` )
  REFERENCES `report` (`id` )
  ON DELETE NO ACTION
  ON UPDATE NO ACTION, 
  ADD CONSTRAINT `fk_timesheet_user1`
  FOREIGN KEY (`user_id` )
  REFERENCES `user` (`id` )
  ON DELETE NO ACTION
  ON UPDATE NO ACTION, 
  ADD CONSTRAINT `fk_timesheet_client1`
  FOREIGN KEY (`client_id` )
  REFERENCES `client` (`id` )
  ON DELETE NO ACTION
  ON UPDATE NO ACTION, 
  ADD CONSTRAINT `fk_timesheet_project1`
  FOREIGN KEY (`project_id` )
  REFERENCES `project` (`id` )
  ON DELETE NO ACTION
  ON UPDATE NO ACTION, 
  ADD CONSTRAINT `fk_timesheet_budget1`
  FOREIGN KEY (`budget_id` )
  REFERENCES `budget` (`id` )
  ON DELETE NO ACTION
  ON UPDATE NO ACTION, 
  ADD CONSTRAINT `fk_timesheet_requirement1`
  FOREIGN KEY (`requirement_id` )
  REFERENCES `requirement` (`id` )
  ON DELETE NO ACTION
  ON UPDATE NO ACTION, 
  ADD CONSTRAINT `fk_timesheet_task1`
  FOREIGN KEY (`task_id` )
  REFERENCES `task` (`id` )
  ON DELETE NO ACTION
  ON UPDATE NO ACTION
, ADD INDEX `fk_timesheet_client1` (`client_id` ASC) 
, ADD INDEX `fk_timesheet_project1` (`project_id` ASC) 
, ADD INDEX `fk_timesheet_budget1` (`budget_id` ASC) 
, ADD INDEX `fk_timesheet_requirement1` (`requirement_id` ASC) 
, ADD INDEX `fk_timesheet_task1` (`task_id` ASC) ;

ALTER TABLE `filestore_image` COLLATE = utf8_general_ci ;

ALTER TABLE `timesheet_filter` COLLATE = utf8_general_ci ;

CREATE  TABLE IF NOT EXISTS `acceptance` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '	' ,
  `report_id` INT(11) NOT NULL ,
  `task_id` INT(11) NOT NULL ,
  `status` VARCHAR(20) NULL DEFAULT NULL ,
  `acceptance_date` DATE NULL DEFAULT NULL ,
  `tester_id` INT(11) NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_acceptance_report1` (`report_id` ASC) ,
  INDEX `fk_acceptance_task1` (`task_id` ASC) ,
  INDEX `fk_acceptance_user1` (`tester_id` ASC) ,
  CONSTRAINT `fk_acceptance_report1`
    FOREIGN KEY (`report_id` )
    REFERENCES `report` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_acceptance_task1`
    FOREIGN KEY (`task_id` )
    REFERENCES `task` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_acceptance_user1`
    FOREIGN KEY (`tester_id` )
    REFERENCES `user` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE  TABLE IF NOT EXISTS `bug` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `acceptance_id` INT(11) NULL DEFAULT NULL ,
  `tracker` VARCHAR(255) NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_bug_acceptance1` (`acceptance_id` ASC) ,
  CONSTRAINT `fk_bug_acceptance1`
    FOREIGN KEY (`acceptance_id` )
    REFERENCES `acceptance` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

update timesheet set date='2010-01-01' where date='0000-00-00';

ALTER TABLE `timesheet` CHANGE COLUMN `date` `date` DATETIME NULL DEFAULT NULL  ;



SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

