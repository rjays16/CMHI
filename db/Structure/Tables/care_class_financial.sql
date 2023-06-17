
DROP TABLE IF EXISTS `care_class_financial`;
CREATE TABLE `care_class_financial` (
  `class_nr` smallint(5) unsigned NOT NULL,
  `class_id` varchar(15) NOT NULL DEFAULT '0',
  `type` varchar(25) NOT NULL DEFAULT '0',
  `code` varchar(5) NOT NULL DEFAULT '',
  `name` varchar(35) NOT NULL DEFAULT '',
  `LD_var` varchar(25) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  `policy` text NOT NULL,
  `status` varchar(25) NOT NULL DEFAULT '',
  `history` text NOT NULL,
  `modify_id` varchar(35) NOT NULL DEFAULT '',
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(35) NOT NULL DEFAULT '',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`class_nr`),
  KEY `class_2` (`class_id`)
);
