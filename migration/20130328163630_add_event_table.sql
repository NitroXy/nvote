CREATE TABLE IF NOT EXISTS `event` (
  `event_id` int(11) NOT NULL AUTO_INCREMENT,
  `short_name` varchar(8) NOT NULL,
  `name` varchar(64) NOT NULL,
  PRIMARY KEY (`event_id`)
) ENGINE=InnoDB;
