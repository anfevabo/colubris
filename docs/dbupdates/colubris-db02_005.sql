ALTER TABLE `client` 
    ADD COLUMN `email` VARCHAR(255) NULL DEFAULT NULL  AFTER `name` , 
    ADD COLUMN `smbo_id` INT(11) NULL DEFAULT NULL  AFTER `email` , 
    ADD COLUMN `is_archive` ENUM('Y','N') NULL DEFAULT 'N'  AFTER `smbo_id` ;

ALTER TABLE `client` 
    ADD COLUMN `total_sales` DECIMAL(8,2) NULL DEFAULT NULL  AFTER `is_archive` , 
    ADD COLUMN `day_credit` INT(11) NULL DEFAULT NULL  AFTER `total_sales` ,
    ADD COLUMN `mailed_dts` DATETIME NULL DEFAULT NULL  AFTER `day_credit` , 
    ADD COLUMN `printed_dts` DATETIME NULL DEFAULT NULL  AFTER `mailed_dts` , 
    ADD COLUMN `ebalance` DECIMAL(8,2) NULL DEFAULT NULL  AFTER `printed_dts` ;

ALTER TABLE `budget` 
    ADD COLUMN `priority` VARCHAR(45) NULL DEFAULT NULL  AFTER `is_monthly` , 
    ADD COLUMN `state` VARCHAR(45) NULL DEFAULT NULL  AFTER `priority` , 
    ADD COLUMN `is_moreinfo_needed` ENUM('Y','N') NULL DEFAULT NULL  AFTER `state` , 
    ADD COLUMN `is_delaying` ENUM('Y','N') NULL DEFAULT NULL  AFTER `is_moreinfo_needed` , 
    ADD COLUMN `is_overtime` ENUM('Y','N') NULL DEFAULT NULL  AFTER `is_delaying` , 
    ADD COLUMN `expenses` DECIMAL(8,2) NULL DEFAULT NULL  AFTER `is_overtime` , 
    ADD COLUMN `expenses_descr` TEXT NULL DEFAULT NULL  AFTER `expenses` , 
    ADD COLUMN `currency` VARCHAR(45) NULL DEFAULT NULL  AFTER `expenses_descr` , 
    CHANGE COLUMN `amount_eur` `amount` DECIMAL(8,2) NULL DEFAULT NULL  ;

