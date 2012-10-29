CREATE TABLE IF NOT EXISTS `vote` (
  `vote_id` int(11) NOT NULL AUTO_INCREMENT,
  `score` smallint(6) NOT NULL,
  `user_id` int(11) NOT NULL,
  `entry_id` int(11) DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`vote_id`),
  KEY `entry_id` (`entry_id`),
  KEY `category_id` (`category_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


ALTER TABLE `vote`
  ADD CONSTRAINT `vote_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `entry` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `vote_ibfk_1` FOREIGN KEY (`entry_id`) REFERENCES `entry` (`entry_id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `vote_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE;
