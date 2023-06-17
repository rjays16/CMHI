
DROP TABLE IF EXISTS `care_type_notes`;
CREATE TABLE `care_type_notes` (
  `nr` smallint(5) unsigned NOT NULL,
  `type` varchar(35) NOT NULL DEFAULT '',
  `name` varchar(35) NOT NULL DEFAULT '',
  `LD_var` varchar(35) NOT NULL DEFAULT '',
  `sort_nr` smallint(6) NOT NULL DEFAULT '0',
  `status` varchar(25) NOT NULL DEFAULT '',
  `modify_id` varchar(35) NOT NULL DEFAULT '',
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(35) NOT NULL DEFAULT '',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`nr`),
  UNIQUE KEY `type` (`type`)
);
