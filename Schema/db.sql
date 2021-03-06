SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `bathalerts` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

-- -----------------------------------------------------
-- Table `bathalerts`.`Users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `bathalerts`.`Users`;
CREATE TABLE IF NOT EXISTS `bathalerts`.`Users` (
  `Email` VARCHAR(100) NOT NULL,
  `UserLat` VARCHAR(100) NOT NULL,
  `UserLong` VARCHAR(100) NOT NULL,
  `Crime` TINYINT(1) NOT NULL,
  `Planning` TINYINT(1) NOT NULL,
  `Houses` TINYINT(1) NOT NULL,
  `Flooding` TINYINT(1) NOT NULL,
  `Radius` INT NOT NULL,
  PRIMARY KEY (`Email`, `UserLat`, `UserLong`))
ENGINE = InnoDB;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;