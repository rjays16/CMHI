
DROP TABLE IF EXISTS `care_type_insurance`;
CREATE TABLE `care_type_insurance` (
  `type_nr` int(11) NOT NULL,
  `type` varchar(35) NOT NULL DEFAULT '',
  `name` varchar(60) NOT NULL DEFAULT '',
  `LD_var` varchar(35) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  `status` varchar(25) NOT NULL DEFAULT '',
  `history` text NOT NULL,
  `modify_id` varchar(35) NOT NULL DEFAULT '',
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(35) NOT NULL DEFAULT '',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`type_nr`),
  KEY `type` (`type`)
);
