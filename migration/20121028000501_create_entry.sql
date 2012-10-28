CREATE TABLE `entry` (
       `entry_id` INT PRIMARY KEY AUTO_INCREMENT,
       `user_id` INT NOT NULL,
       `category_id` INT NOT NULL,
       `title` TEXT NOT NULL,
       `author` TEXT NOT NULL,
       `description` TEXT,

       FOREIGN KEY (`user_id`) REFERENCES `user`(`user_id`) ON DELETE CASCADE,
       FOREIGN KEY (`category_id`) REFERENCES `category`(`category_id`) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `revision` (
       `entry_id` INT NOT NULL,
       `revision` INT NOT NULL,
       `filename` TEXT NOT NULL,
       `original` TEXT NOT NULL,
       `timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
       PRIMARY KEY (`entry_id`, `revision`),
       FOREIGN KEY (`entry_id`) REFERENCES `entry`(`entry_id`) ON DELETE CASCADE
) ENGINE=InnoDB;
