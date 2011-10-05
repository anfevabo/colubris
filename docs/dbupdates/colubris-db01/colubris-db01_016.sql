SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

ALTER TABLE `report` DROP FOREIGN KEY `fk_report_task1` ;

ALTER TABLE `budget` ADD COLUMN `manhours` DECIMAL(8,2) NULL DEFAULT NULL  AFTER `mandays` , ADD COLUMN `is_monthly` ENUM('Y','N') NULL DEFAULT NULL  AFTER `accepted` , CHANGE COLUMN `project_id` `project_id` INT(11) NOT NULL  AFTER `amount_eur` ;

ALTER TABLE `report` DROP COLUMN `task_id` , ADD COLUMN `revision` VARCHAR(255) NULL DEFAULT NULL  AFTER `amount` 
, DROP INDEX `fk_report_task1` ;

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

ALTER TABLE `participant` ADD COLUMN `hourly_cost` INT(11) NULL DEFAULT NULL  AFTER `daily_cost` , CHANGE COLUMN `daily_cost` `daily_cost` INT(11) NULL DEFAULT NULL  ;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

