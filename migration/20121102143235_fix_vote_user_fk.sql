ALTER TABLE	`vote` DROP FOREIGN KEY `vote_ibfk_3`;
ALTER TABLE `vote` ADD CONSTRAINT `vote_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
