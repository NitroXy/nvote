CREATE TABLE `category` (
       `category_id` INT PRIMARY KEY AUTO_INCREMENT,
       `name` VARCHAR(128) NOT NULL UNIQUE,
       `description` TEXT NOT NULL,
       `event` VARCHAR(12) NOT NULL
) ENGINE=InnoDB;

