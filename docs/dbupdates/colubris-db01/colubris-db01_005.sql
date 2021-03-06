SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

ALTER TABLE `screen` DROP FOREIGN KEY `fk_screen_filestore_file1` ;

ALTER TABLE `screen` CHANGE COLUMN `filestore_file_id` `filestore_file_id` INT(11) NULL DEFAULT NULL  , DROP FOREIGN KEY `fk_screen_project1` ;

ALTER TABLE `screen` 
  ADD CONSTRAINT `fk_screen_project1`
  FOREIGN KEY (`project_id` )
  REFERENCES `project` (`id` )
  ON DELETE NO ACTION
  ON UPDATE NO ACTION, 
  ADD CONSTRAINT `fk_screen_filestore_file1`
  FOREIGN KEY (`filestore_file_id` )
  REFERENCES `filestore_file` (`id` )
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

