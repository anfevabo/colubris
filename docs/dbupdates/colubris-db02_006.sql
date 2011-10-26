ALTER TABLE `participant` ADD COLUMN `role` VARCHAR(45) NULL DEFAULT NULL  AFTER `user_id` ;

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

ALTER TABLE `participant` DROP COLUMN `hourly_cost` , DROP COLUMN `daily_cost` ;

ALTER TABLE `payment` ADD COLUMN `expense` DECIMAL(8,2) NULL DEFAULT NULL  AFTER `hourly_rate` , CHANGE COLUMN `budget_id` `budget_id` INT(11) NULL DEFAULT NULL  , CHANGE COLUMN `hourly_rate` `hourly_rate` DECIMAL(8,2) NULL DEFAULT NULL  
, ADD INDEX `fk_payment_user1` (`user_id` ASC) 
, ADD INDEX `fk_payment_budget1` (`budget_id` ASC) ;

ALTER TABLE `payment` ADD COLUMN `client_pays` ENUM('Y','N') NULL DEFAULT 'N'  AFTER `expense` , ADD COLUMN `sell_rate` DECIMAL(8,2) NULL DEFAULT NULL  AFTER `client_pays` , ADD COLUMN `sell_total` DECIMAL(8,2) NULL DEFAULT NULL  AFTER `sell_rate` ;

ALTER TABLE `payment` ADD COLUMN `service_name` VARCHAR(255) NULL DEFAULT NULL  AFTER `sell_total` ;
ALTER TABLE `payment` ADD COLUMN `estimated_hours` DECIMAL(8,2) NULL DEFAULT NULL  AFTER `service_name` ;



ALTER TABLE `budget` ADD COLUMN `quote_id` INT(11) NULL DEFAULT NULL  AFTER `is_monthly` , CHANGE COLUMN `priority` `priority` VARCHAR(45) NULL DEFAULT NULL  AFTER `project_id` , 
  ADD CONSTRAINT `fk_budget_quote1`
  FOREIGN KEY (`quote_id` )
  REFERENCES `quote` (`id` )
  ON DELETE NO ACTION
  ON UPDATE NO ACTION
, ADD INDEX `fk_budget_quote1` (`quote_id` ASC) ;

ALTER TABLE `payment` CHANGE COLUMN `service_name` `service_name` TEXT NULL DEFAULT NULL  ;

CREATE  TABLE IF NOT EXISTS `quote` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `project_id` INT(11) NOT NULL ,
  `name` VARCHAR(255) NULL DEFAULT NULL ,
  `amount` DECIMAL(8,2) NULL DEFAULT NULL ,
  `issued` DATE NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_quote_project1` (`project_id` ASC) ,
  CONSTRAINT `fk_quote_project1`
    FOREIGN KEY (`project_id` )
    REFERENCES `project` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

