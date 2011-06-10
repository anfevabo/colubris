CREATE TABLE `payment` (
`id` INT NOT NULL AUTO_INCREMENT ,
`user_id` INT NOT NULL ,
`budget_id` INT NOT NULL ,
`hourly_rate` FLOAT NOT NULL ,
PRIMARY KEY ( `id` )
) ENGINE = MYISAM ;