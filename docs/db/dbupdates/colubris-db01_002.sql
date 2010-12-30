CREATE  TABLE IF NOT EXISTS `participant` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `daily_cost` VARCHAR(255) NULL DEFAULT NULL ,
  `budget_id` INT(11) NOT NULL ,
  `user_id` INT(11) NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_participant_budget1` (`budget_id` ASC) ,
  INDEX `fk_participant_user1` (`user_id` ASC) ,
  CONSTRAINT `fk_participant_budget1`
    FOREIGN KEY (`budget_id` )
    REFERENCES `budget` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_participant_user1`
    FOREIGN KEY (`user_id` )
    REFERENCES `user` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
CHARACTER SET = utf8 , COLLATE = utf8_general_ci;
