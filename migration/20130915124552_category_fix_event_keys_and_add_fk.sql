ALTER TABLE `category` ADD COLUMN `event_id` int(11) NOT NULL;

UPDATE `category`, `event` SET `category`.`event_id` = `event`.`event_id` WHERE `category`.`event` = `event`.`short_name`;

ALTER TABLE `category`
	DROP INDEX `name`,
	DROP COLUMN `event`,
	ADD UNIQUE KEY `name` (`name`, `event_id`),
	ADD KEY `event_id` (`event_id`),
	ADD CONSTRAINT FOREIGN KEY `category_event_fk` (`event_id`) REFERENCES `event` (`event_id`) ON DELETE RESTRICT;

