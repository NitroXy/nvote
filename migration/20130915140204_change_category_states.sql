ALTER TABLE `category`
	DROP COLUMN `vote_open`,
	DROP COLUMN `entry_open`,
	ADD COLUMN `status` ENUM('hidden', 'visible', 'entry_open', 'entry_closed', 'voting_open', 'voting_closed', 'results_public') DEFAULT 'hidden';

UPDATE `category` SET `status` = 'results_public';
