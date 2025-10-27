-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema event_sch
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema event_sch
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `event_sch` DEFAULT CHARACTER SET utf8 ;
USE `event_sch` ;

-- -----------------------------------------------------
-- Table `event_sch`.`admin`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `event_sch`.`admin` (
  `admin_id` INT NOT NULL AUTO_INCREMENT,
  `admin_name` VARCHAR(45) NOT NULL,
  `admin_pfp` BLOB NULL,
  `admin_password` VARCHAR(45) NULL,
  `admin_email` VARCHAR(45) NULL,
  `admin_number` VARCHAR(45) NULL,
  PRIMARY KEY (`admin_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `event_sch`.`event`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `event_sch`.`event` (
  `event_id` INT NOT NULL AUTO_INCREMENT,
  `event_name` VARCHAR(45) NULL,
  `event_details` VARCHAR(150) NULL,
  `event_img` BLOB NULL,
  `event_location` VARCHAR(45) NULL,
  `event_date` DATE NULL,
  `event_poster` INT NOT NULL,
  PRIMARY KEY (`event_id`, `event_poster`),
  INDEX `fk_event_admin1_idx` (`event_poster` ASC) VISIBLE,
  CONSTRAINT `fk_event_admin1`
    FOREIGN KEY (`event_poster`)
    REFERENCES `event_sch`.`admin` (`admin_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `event_sch`.`friends_table`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `event_sch`.`friends_table` (
  `friend_one` INT NOT NULL,
  `friend_two` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`friend_one`, `friend_two`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `event_sch`.`user_registration`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `event_sch`.`user_registration` (
  `user_id` INT NOT NULL AUTO_INCREMENT,
  `user_student_id` VARCHAR(45) NULL,
  `user_name` VARCHAR(45) NOT NULL,
  `user_pfp` BLOB NULL,
  `user_password` VARCHAR(45) NULL,
  `user_email` VARCHAR(45) NULL,
  `user_year_lvl` VARCHAR(45) NULL,
  `user_number` VARCHAR(45) NULL,
  `approved_by` INT NOT NULL,
  PRIMARY KEY (`user_id`),
  INDEX `fk_user_registration_admin1_idx` (`approved_by` ASC) VISIBLE,
  CONSTRAINT `fk_user_registration_admin1`
    FOREIGN KEY (`approved_by`)
    REFERENCES `event_sch`.`admin` (`admin_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `event_sch`.`user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `event_sch`.`user` (
  `user_id` INT NOT NULL AUTO_INCREMENT,
  `user_student_id` VARCHAR(45) NULL,
  `user_name` VARCHAR(45) NOT NULL,
  `user_pfp` BLOB NULL,
  `user_password` VARCHAR(45) NULL,
  `user_email` VARCHAR(45) NULL,
  `user_year_lvl` VARCHAR(45) NULL,
  `user_number` VARCHAR(45) NULL,
  `friend_one` INT NOT NULL,
  `friend_two` VARCHAR(45) NOT NULL,
  `user_registration_user_id` INT NOT NULL,
  PRIMARY KEY (`user_id`, `friend_one`, `friend_two`, `user_registration_user_id`),
  INDEX `fk_user_friends_table1_idx` (`friend_one` ASC, `friend_two` ASC) VISIBLE,
  INDEX `fk_user_user_registration1_idx` (`user_registration_user_id` ASC) VISIBLE,
  CONSTRAINT `fk_user_friends_table1`
    FOREIGN KEY (`friend_one` , `friend_two`)
    REFERENCES `event_sch`.`friends_table` (`friend_one` , `friend_two`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_user_registration1`
    FOREIGN KEY (`user_registration_user_id`)
    REFERENCES `event_sch`.`user_registration` (`user_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `event_sch`.`event_comments`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `event_sch`.`event_comments` (
  `event_com_id` INT NOT NULL AUTO_INCREMENT,
  `event_id` INT NOT NULL,
  `user_poster` INT NOT NULL,
  `evemt_comment` VARCHAR(150) NULL,
  `comment_datetime` DATE NULL,
  PRIMARY KEY (`event_com_id`, `event_id`, `user_poster`),
  INDEX `fk_Event_comments_Event_idx` (`event_id` ASC) VISIBLE,
  INDEX `fk_Event_comments_user1_idx` (`user_poster` ASC) VISIBLE,
  CONSTRAINT `fk_Event_comments_Event`
    FOREIGN KEY (`event_id`)
    REFERENCES `event_sch`.`event` (`event_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Event_comments_user1`
    FOREIGN KEY (`user_poster`)
    REFERENCES `event_sch`.`user` (`user_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `event_sch`.`event_interaction`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `event_sch`.`event_interaction` (
  `event_inter_id` INT NOT NULL AUTO_INCREMENT,
  `event_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  PRIMARY KEY (`event_inter_id`, `event_id`, `user_id`),
  INDEX `fk_event_interaction_event1_idx` (`event_id` ASC) VISIBLE,
  INDEX `fk_event_interaction_user1_idx` (`user_id` ASC) VISIBLE,
  CONSTRAINT `fk_event_interaction_event1`
    FOREIGN KEY (`event_id`)
    REFERENCES `event_sch`.`event` (`event_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_event_interaction_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `event_sch`.`user` (`user_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
