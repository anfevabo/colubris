SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

ALTER TABLE `filestore_file` 
  ADD CONSTRAINT `fk_filestore_file_filestore_type1`
  FOREIGN KEY (`filestore_type_id` )
  REFERENCES `filestore_type` (`id` )
  ON DELETE NO ACTION
  ON UPDATE NO ACTION, 
  ADD CONSTRAINT `fk_filestore_file_filestore_volume1`
  FOREIGN KEY (`filestore_volume_id` )
  REFERENCES `filestore_volume` (`id` )
  ON DELETE NO ACTION
  ON UPDATE NO ACTION
, ADD INDEX `fk_filestore_file_filestore_type1` (`filestore_type_id` ASC) 
, ADD INDEX `fk_filestore_file_filestore_volume1` (`filestore_volume_id` ASC) ;

ALTER TABLE `screen` ADD COLUMN `filestore_file_id` INT(11) NOT NULL  AFTER `budget_id` , 
  ADD CONSTRAINT `fk_screen_filestore_file1`
  FOREIGN KEY (`filestore_file_id` )
  REFERENCES `filestore_file` (`id` )
  ON DELETE NO ACTION
  ON UPDATE NO ACTION
, ADD INDEX `fk_screen_filestore_file1` (`filestore_file_id` ASC) ;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

