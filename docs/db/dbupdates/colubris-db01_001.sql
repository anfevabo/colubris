SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

ALTER TABLE `user` CHARACTER SET = utf8 , COLLATE = utf8_general_ci ;

ALTER TABLE `budget` CHARACTER SET = utf8 , COLLATE = utf8_general_ci ;

ALTER TABLE `client` CHARACTER SET = utf8 , COLLATE = utf8_general_ci , CHANGE COLUMN `id` `id` INT(11) NOT NULL AUTO_INCREMENT  ;

ALTER TABLE `requirement` CHARACTER SET = utf8 , COLLATE = utf8_general_ci ;

ALTER TABLE `report` CHARACTER SET = utf8 , COLLATE = utf8_general_ci ;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
