SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema bloodstorm
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `bloodstorm` DEFAULT CHARACTER SET utf8 ;
USE `bloodstorm` ;

-- -----------------------------------------------------
-- Table `bloodstorm`.`jogo`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bloodstorm`.`jogo` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `jogador_last_turn` INT(11) NOT NULL,
  `jogador_winner` INT(11) NOT NULL,
  `data` DATE NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `bloodstorm`.`jogador`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bloodstorm`.`jogador` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(45) NOT NULL,
  `jogo_bind` INT(11) NOT NULL,
  `pontuacao` VARCHAR(45) NOT NULL,
  `first_run` TINYINT(1) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_jogador_jogo1_idx` (`jogo_bind` ASC),
  CONSTRAINT `fk_jogador_jogo1`
    FOREIGN KEY (`jogo_bind`)
    REFERENCES `bloodstorm`.`jogo` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `bloodstorm`.`jogadas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bloodstorm`.`jogadas` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `jogador_id` INT(11) NOT NULL,
  `jogo_id` INT(11) NOT NULL,
  `coordHorizontal` INT(11) NOT NULL,
  `coordVertical` INT(11) NOT NULL,
  `navio` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_jogadas_jogador_idx` (`jogador_id` ASC),
  INDEX `fk_jogadas_jogo1_idx` (`jogo_id` ASC),
  CONSTRAINT `fk_jogadas_jogador`
    FOREIGN KEY (`jogador_id`)
    REFERENCES `bloodstorm`.`jogador` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_jogadas_jogo1`
    FOREIGN KEY (`jogo_id`)
    REFERENCES `bloodstorm`.`jogo` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `bloodstorm`.`tabuleiro`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bloodstorm`.`tabuleiro` (
  `jogo_id` INT(11) NOT NULL,
  `jogador_id` INT(11) NOT NULL,
  `coordHorizontal` INT(11) NOT NULL,
  `coordVertical` INT(11) NOT NULL,
  `navio` VARCHAR(45) NULL,
  INDEX `fk_tabuleiro_jogo1_idx` (`jogo_id` ASC),
  INDEX `fk_tabuleiro_jogador1_idx` (`jogador_id` ASC),
  CONSTRAINT `fk_tabuleiro_jogo1`
    FOREIGN KEY (`jogo_id`)
    REFERENCES `bloodstorm`.`jogo` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_tabuleiro_jogador1`
    FOREIGN KEY (`jogador_id`)
    REFERENCES `bloodstorm`.`jogador` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
