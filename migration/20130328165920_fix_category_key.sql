ALTER TABLE category DROP INDEX name;
ALTER TABLE `category` ADD UNIQUE (
	`name` ,
	`event`
);
