ALTER TABLE	`category` ADD COLUMN `rules` text NOT NULL DEFAULT "";
ALTER TABLE `event`
	ADD COLUMN `frontpage_text` text NOT NULL DEFAULT "",
	ADD COLUMN `general_rules` text NOT NULL DEFAULT "",
	ADD COLUMN `location` varchar(64) DEFAULT NULL;
