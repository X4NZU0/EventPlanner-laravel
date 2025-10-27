SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

CREATE SCHEMA IF NOT EXISTS `event_db` DEFAULT CHARACTER SET utf8;
USE `event_db`;

-- -----------------------------------------------------
-- Table `event_db`.`event`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `event_db`.`event` (
  `event_id` INT NOT NULL AUTO_INCREMENT,
  `event_name` VARCHAR(45) NULL,
  `event_details` VARCHAR(150) NULL,
  `event_img` BLOB NULL,
  `event_location` VARCHAR(45) NULL,
  `event_date` DATE NULL,
  PRIMARY KEY (`event_id`)
) ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `event_db`.`friends_table`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `event_db`.`friends_table` (
  `friend_one` INT NOT NULL,
  `friend_two` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`friend_one`, `friend_two`)
) ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `event_db`.`user_registration`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `event_db`.`user_registration` (
  `user_id` INT NOT NULL AUTO_INCREMENT,
  `user_student_id` VARCHAR(45) NULL,
  `user_name` VARCHAR(45) NOT NULL,
  `user_pfp` BLOB NULL,
  `user_password` VARCHAR(255) NOT NULL,
  `user_email` VARCHAR(100) UNIQUE,
  `user_year_lvl` VARCHAR(45) NULL,
  `user_number` VARCHAR(45) NULL,
  `approved_by` VARCHAR(100) NULL, -- ✅ changed to nullable, not foreign key
  PRIMARY KEY (`user_id`)
) ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `event_db`.`user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `event_db`.`user` (
  `user_id` INT NOT NULL AUTO_INCREMENT,
  `user_student_id` VARCHAR(45) NULL,
  `user_name` VARCHAR(45) NOT NULL,
  `user_pfp` BLOB NULL,
  `user_password` VARCHAR(255) NULL,
  `user_email` VARCHAR(100) NULL,
  `user_year_lvl` VARCHAR(45) NULL,
  `user_number` VARCHAR(45) NULL,
  `friend_one` INT NULL,
  `friend_two` VARCHAR(45) NULL,
  `user_registration_user_id` INT NULL,
  PRIMARY KEY (`user_id`),
  INDEX `fk_user_user_registration1_idx` (`user_registration_user_id` ASC) VISIBLE,
  CONSTRAINT `fk_user_user_registration1`
    FOREIGN KEY (`user_registration_user_id`)
    REFERENCES `event_db`.`user_registration` (`user_id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE
) ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `event_db`.`event_comments`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `event_db`.`event_comments` (
  `event_com_id` INT NOT NULL AUTO_INCREMENT,
  `event_id` INT NOT NULL,
  `user_poster` INT NOT NULL,
  `event_comment` VARCHAR(150) NULL,
  `comment_datetime` DATETIME NULL,
  PRIMARY KEY (`event_com_id`),
  INDEX `fk_Event_comments_Event_idx` (`event_id` ASC) VISIBLE,
  INDEX `fk_Event_comments_user1_idx` (`user_poster` ASC) VISIBLE,
  CONSTRAINT `fk_Event_comments_Event`
    FOREIGN KEY (`event_id`)
    REFERENCES `event_db`.`event` (`event_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Event_comments_user1`
    FOREIGN KEY (`user_poster`)
    REFERENCES `event_db`.`user` (`user_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `event_db`.`event_interaction`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `event_db`.`event_interaction` (
  `event_inter_id` INT NOT NULL AUTO_INCREMENT,
  `event_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  PRIMARY KEY (`event_inter_id`),
  INDEX `fk_event_interaction_event1_idx` (`event_id` ASC) VISIBLE,
  INDEX `fk_event_interaction_user1_idx` (`user_id` ASC) VISIBLE,
  CONSTRAINT `fk_event_interaction_event1`
    FOREIGN KEY (`event_id`)
    REFERENCES `event_db`.`event` (`event_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_event_interaction_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `event_db`.`user` (`user_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE = InnoDB;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
