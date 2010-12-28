SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';


-- -----------------------------------------------------
-- Table `client`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `client` (
  `id` INT NOT NULL ,
  `name` VARCHAR(255) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `user`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `user` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(255) NULL ,
  `email` VARCHAR(255) NULL ,
  `password` VARCHAR(255) NULL ,
  `client_id` INT NULL ,
  `is_admin` ENUM('Y','N') NULL DEFAULT 'N' ,
  `is_manager` ENUM('Y','N') NULL DEFAULT 'N' ,
  `is_developer` ENUM('Y','N') NULL DEFAULT 'Y' ,
  PRIMARY KEY (`id`) ,
  CONSTRAINT `fk_user_client`
    FOREIGN KEY (`client_id` )
    REFERENCES `client` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_user_client` ON `user` (`client_id` ASC) ;


-- -----------------------------------------------------
-- Table `budget`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `budget` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(255) NULL ,
  `amount` DECIMAL(8,2) NULL ,
  `amount_currency` ENUM('usd','eur','days','hours') NULL ,
  `client_id` INT NULL ,
  PRIMARY KEY (`id`) ,
  CONSTRAINT `fk_budget_client1`
    FOREIGN KEY (`client_id` )
    REFERENCES `client` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_budget_client1` ON `budget` (`client_id` ASC) ;


-- -----------------------------------------------------
-- Table `requirement`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `requirement` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(255) NULL ,
  `est_cost` DECIMAL(8,2) NULL ,
  `est_completion` DECIMAL(8,2) NULL ,
  `budget_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  CONSTRAINT `fk_requirement_budget1`
    FOREIGN KEY (`budget_id` )
    REFERENCES `budget` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_requirement_budget1` ON `requirement` (`budget_id` ASC) ;


-- -----------------------------------------------------
-- Table `report`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `report` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `result` TEXT NULL ,
  `user_id` INT NOT NULL ,
  `requirement_id` INT NULL ,
  `budget_id` INT NULL ,
  `date` DATE NULL ,
  `amount` DECIMAL(8,2) NULL ,
  PRIMARY KEY (`id`) ,
  CONSTRAINT `fk_report_user1`
    FOREIGN KEY (`user_id` )
    REFERENCES `user` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_report_requirement1`
    FOREIGN KEY (`requirement_id` )
    REFERENCES `requirement` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_report_budget1`
    FOREIGN KEY (`budget_id` )
    REFERENCES `budget` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_report_user1` ON `report` (`user_id` ASC) ;

CREATE INDEX `fk_report_requirement1` ON `report` (`requirement_id` ASC) ;

CREATE INDEX `fk_report_budget1` ON `report` (`budget_id` ASC) ;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `user`
-- -----------------------------------------------------
SET AUTOCOMMIT=0;
INSERT INTO user (`id`, `name`, `email`, `password`, `client_id`, `is_admin`, `is_manager`, `is_developer`) VALUES (NULL, 'Admin', 'demo', 'fe01ce2a7fbac8fafaed7c982a04e229', NULL, 'Y', NULL, NULL);

COMMIT;
