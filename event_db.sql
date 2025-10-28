-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema event_db
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema event_db
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `event_db` DEFAULT CHARACTER SET utf8mb3 ;
USE `event_db` ;

-- -----------------------------------------------------
-- Table `event_db`.`admin`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `event_db`.`admin` (
  `admin_id` INT NOT NULL AUTO_INCREMENT,
  `admin_name` VARCHAR(255) NULL DEFAULT NULL,
  `admin_pfp` VARCHAR(255) NULL DEFAULT NULL,
  `admin_password` VARCHAR(255) NULL DEFAULT NULL,
  `admin_email` VARCHAR(255) NULL DEFAULT NULL,
  `admin_number` VARCHAR(255) NULL DEFAULT NULL,
  PRIMARY KEY (`admin_id`))
ENGINE = InnoDB
AUTO_INCREMENT = 4
DEFAULT CHARACTER SET = utf8mb3;


-- -----------------------------------------------------
-- Table `event_db`.`event`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `event_db`.`event` (
  `event_id` INT NOT NULL AUTO_INCREMENT,
  `event_poster` INT NULL DEFAULT NULL,
  `event_name` VARCHAR(45) NULL DEFAULT NULL,
  `event_details` VARCHAR(150) NULL DEFAULT NULL,
  `event_img` VARCHAR(45) NULL DEFAULT NULL,
  `event_location` VARCHAR(45) NULL DEFAULT NULL,
  `event_timestart` VARCHAR(45) NULL DEFAULT NULL,
  `event_timeend` VARCHAR(45) NULL DEFAULT NULL,
  `event_datestart` DATE NULL DEFAULT NULL,
  `event_dateend` DATE NULL DEFAULT NULL,
  PRIMARY KEY (`event_id`))
ENGINE = InnoDB
AUTO_INCREMENT = 5
DEFAULT CHARACTER SET = utf8mb3;


-- -----------------------------------------------------
-- Table `event_db`.`user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `event_db`.`user` (
  `user_id` INT NOT NULL AUTO_INCREMENT,
  `user_student_id` VARCHAR(45) NULL DEFAULT NULL,
  `user_name` VARCHAR(45) NULL DEFAULT NULL,
  `user_pfp` VARCHAR(45) NULL DEFAULT NULL,
  `user_password` VARCHAR(255) NULL DEFAULT NULL,
  `user_email` VARCHAR(45) NULL DEFAULT NULL,
  `user_year_lvl` VARCHAR(45) NULL DEFAULT NULL,
  `user_number` VARCHAR(45) NULL DEFAULT NULL,
  `friend_one` INT NOT NULL,
  `friend_two` VARCHAR(45) NOT NULL,
  `user_registration_user_id` INT NOT NULL,
  `roles` INT NOT NULL,
  PRIMARY KEY (`user_id`, `friend_one`, `friend_two`, `user_registration_user_id`, `roles`),
  INDEX `fk_user_friends_table1_idx` (`friend_one` ASC, `friend_two` ASC) VISIBLE,
  INDEX `fk_user_user_registration1_idx` (`user_registration_user_id` ASC) VISIBLE,
  INDEX `fk_user_roles1_idx` (`roles` ASC) VISIBLE)
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = utf8mb3;


-- -----------------------------------------------------
-- Table `event_db`.`event_comments`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `event_db`.`event_comments` (
  `event_com_id` INT NOT NULL AUTO_INCREMENT,
  `event_id` INT NOT NULL,
  `user_poster` INT NOT NULL,
  `event_comment` VARCHAR(150) NULL DEFAULT NULL,
  `comment_datetime` DATE NULL DEFAULT NULL,
  PRIMARY KEY (`event_com_id`, `event_id`, `user_poster`),
  INDEX `fk_Event_comments_Event_idx` (`event_id` ASC) VISIBLE,
  INDEX `fk_Event_comments_user1_idx` (`user_poster` ASC) VISIBLE,
  CONSTRAINT `fk_Event_comments_Event`
    FOREIGN KEY (`event_id`)
    REFERENCES `event_db`.`event` (`event_id`),
  CONSTRAINT `fk_Event_comments_user1`
    FOREIGN KEY (`user_poster`)
    REFERENCES `event_db`.`user` (`user_id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb3;


-- -----------------------------------------------------
-- Table `event_db`.`event_interaction`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `event_db`.`event_interaction` (
  `event_inter_id` INT NOT NULL AUTO_INCREMENT,
  `event_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  PRIMARY KEY (`event_inter_id`, `event_id`, `user_id`),
  INDEX `fk_event_interaction_event1_idx` (`event_id` ASC) VISIBLE,
  INDEX `fk_event_interaction_user1_idx` (`user_id` ASC) VISIBLE,
  CONSTRAINT `fk_event_interaction_event1`
    FOREIGN KEY (`event_id`)
    REFERENCES `event_db`.`event` (`event_id`),
  CONSTRAINT `fk_event_interaction_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `event_db`.`user` (`user_id`))
ENGINE = InnoDB
AUTO_INCREMENT = 8
DEFAULT CHARACTER SET = utf8mb3;


-- -----------------------------------------------------
-- Table `event_db`.`friends_table`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `event_db`.`friends_table` (
  `friend_one` INT NOT NULL,
  `friend_two` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`friend_one`, `friend_two`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb3;


-- -----------------------------------------------------
-- Table `event_db`.`roles`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `event_db`.`roles` (
  `roles_id` INT NOT NULL,
  `user_role` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`roles_id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb3;


-- -----------------------------------------------------
-- Table `event_db`.`super_admin`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `event_db`.`super_admin` (
  `super_id` INT NOT NULL AUTO_INCREMENT,
  `super_name` VARCHAR(45) NULL DEFAULT NULL,
  `super_email` VARCHAR(45) NULL DEFAULT NULL,
  `super_password` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`super_id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb3;


-- -----------------------------------------------------
-- Table `event_db`.`user_registration`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `event_db`.`user_registration` (
  `user_id` INT NOT NULL AUTO_INCREMENT,
  `user_student_id` VARCHAR(45) NULL DEFAULT NULL,
  `user_name` VARCHAR(45) NULL DEFAULT NULL,
  `user_pfp` BLOB NULL DEFAULT NULL,
  `user_password` VARCHAR(255) NULL DEFAULT NULL,
  `user_email` VARCHAR(45) NULL DEFAULT NULL,
  `user_year_lvl` VARCHAR(45) NULL DEFAULT NULL,
  `user_number` VARCHAR(45) NULL DEFAULT NULL,
  `approved_by` INT NULL DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  INDEX `fk_user_registration_admin1_idx` (`approved_by` ASC) VISIBLE,
  CONSTRAINT `fk_user_registration_admin1`
    FOREIGN KEY (`approved_by`)
    REFERENCES `event_db`.`admin` (`admin_id`))
ENGINE = InnoDB
AUTO_INCREMENT = 4
DEFAULT CHARACTER SET = utf8mb3;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
