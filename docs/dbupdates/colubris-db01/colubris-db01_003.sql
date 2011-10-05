SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

ALTER TABLE `report` 
	DROP FOREIGN KEY `fk_report_requirement1` ;

ALTER TABLE `budget` 
	CHANGE COLUMN `amount_currency` `amount_currency` ENUM('usd','eur','days','hours') ;

ALTER TABLE `report` 
	DROP COLUMN `requirement_id` , 
	ADD COLUMN `task_id` INT(11) NULL DEFAULT NULL  AFTER `user_id` , 
  ADD CONSTRAINT `fk_report_task1`
  FOREIGN KEY (`task_id` )
  REFERENCES `task` (`id` )
  ON DELETE NO ACTION
  ON UPDATE NO ACTION
, ADD INDEX `fk_report_task1` (`task_id` ASC) 
, DROP INDEX `fk_report_requirement1` ;

CREATE  TABLE IF NOT EXISTS `task` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `screen_id` INT(11) NULL DEFAULT NULL ,
  `name` VARCHAR(255) NULL DEFAULT NULL ,
  `type` VARCHAR(255) NULL DEFAULT NULL ,
  `descr_original` TEXT NULL DEFAULT NULL ,
  `deviation` TEXT NULL DEFAULT NULL ,
  `estimate` DECIMAL(8,2) NULL DEFAULT NULL ,
  `cur_progress` DECIMAL(8,2) NULL DEFAULT NULL ,
  `budget_id` INT(11) NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_task_screen1` (`screen_id` ASC) ,
  INDEX `fk_task_budget1` (`budget_id` ASC) ,
  CONSTRAINT `fk_task_screen1`
    FOREIGN KEY (`screen_id` )
    REFERENCES `screen` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_task_budget1`
    FOREIGN KEY (`budget_id` )
    REFERENCES `budget` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
;

CREATE  TABLE IF NOT EXISTS `screen` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(255) NULL DEFAULT NULL ,
  `ref` VARCHAR(255) NULL DEFAULT NULL ,
  `descr` TEXT NULL DEFAULT NULL ,
  `project_id` INT(11) NOT NULL ,
  `budget_id` INT(11) NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_screen_project1` (`project_id` ASC) ,
  INDEX `fk_screen_budget1` (`budget_id` ASC) ,
  CONSTRAINT `fk_screen_project1`
    FOREIGN KEY (`project_id` )
    REFERENCES `project` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_screen_budget1`
    FOREIGN KEY (`budget_id` )
    REFERENCES `budget` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
;

CREATE  TABLE IF NOT EXISTS `project` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(255) NULL DEFAULT NULL ,
  `descr` TEXT NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
;

DROP TABLE IF EXISTS `requirement` ;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

