SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

ALTER TABLE `budget` DROP COLUMN `amount_currency` , ADD COLUMN `mandays` DECIMAL(8,2) NULL DEFAULT NULL  AFTER `amount_eur` , ADD COLUMN `deadline` DATE NULL DEFAULT NULL  AFTER `mandays` , ADD COLUMN `success_criteria` INT(11) NULL DEFAULT NULL  AFTER `deadline` , CHANGE COLUMN `amount` `amount_eur` DECIMAL(8,2) NULL DEFAULT NULL  , DROP FOREIGN KEY `fk_budget_client1` ;

ALTER TABLE `budget` 
  ADD CONSTRAINT `fk_budget_client1`
  FOREIGN KEY (`client_id` )
  REFERENCES `client` (`id` )
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

CREATE  TABLE IF NOT EXISTS `timesheet_filter` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(255) NULL DEFAULT NULL ,
  `regexp` VARCHAR(255) NULL DEFAULT NULL ,
  `client_id` INT(11) NOT NULL ,
  `user_id` INT(11) NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_timesheet_filter_client1` (`client_id` ASC) ,
  INDEX `fk_timesheet_filter_user1` (`user_id` ASC) ,
  CONSTRAINT `fk_timesheet_filter_client1`
    FOREIGN KEY (`client_id` )
    REFERENCES `client` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_timesheet_filter_user1`
    FOREIGN KEY (`user_id` )
    REFERENCES `user` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

