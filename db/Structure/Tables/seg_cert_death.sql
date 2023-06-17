
DROP TABLE IF EXISTS `seg_cert_death`;
CREATE TABLE `seg_cert_death` (
  `pid` varchar(12) NOT NULL,
  `registry_nr` varchar(12) DEFAULT NULL,
  `death_place_basic` varchar(100) DEFAULT NULL,
  `death_place_mun` varchar(50) DEFAULT NULL,
  `death_place_prov` varchar(50) DEFAULT NULL,
  `death_date` date DEFAULT '0000-00-00',
  `death_hour` float DEFAULT NULL,
  `death_min` float DEFAULT NULL,
  `death_sec` float DEFAULT NULL,
  `dcitizenship` text,
  `age_at_death` varchar(15) DEFAULT NULL,
  `m_age` tinyint(4) DEFAULT NULL,
  `delivery_method` varchar(30) DEFAULT NULL,
  `pregnancy_length` tinyint(4) DEFAULT NULL,
  `birth_type` tinyint(4) DEFAULT NULL,
  `birth_rank` varchar(20) DEFAULT NULL,
  `death_cause` text,
  `maternal_condition` tinyint(4) DEFAULT NULL,
  `death_manner` varchar(25) DEFAULT NULL,
  `place_occurrence` varchar(25) DEFAULT NULL,
  `attendant_type` varchar(25) DEFAULT NULL,
  `attended_from_date` date DEFAULT '0000-00-00',
  `attended_to_date` date DEFAULT '0000-00-00',
  `death_cert_attended` tinyint(1) DEFAULT NULL,
  `death_time` time DEFAULT '00:00:00',
  `attendant_name` varchar(60) DEFAULT NULL,
  `attendant_title` varchar(30) DEFAULT NULL,
  `attendant_address` varchar(100) DEFAULT NULL,
  `attendant_date_sign` date DEFAULT '0000-00-00',
  `corpse_disposal` varchar(25) DEFAULT NULL,
  `burial_permit` varchar(20) DEFAULT NULL,
  `burial_date_issued` date DEFAULT '0000-00-00',
  `transfer_permit` varchar(20) DEFAULT NULL,
  `transfer_date_issued` date DEFAULT '0000-00-00',
  `is_autopsy` tinyint(1) DEFAULT NULL,
  `cemetery_name_address` varchar(150) DEFAULT NULL,
  `informant_name` varchar(60) DEFAULT NULL,
  `informant_address` varchar(100) DEFAULT NULL,
  `informant_relation` varchar(30) DEFAULT NULL,
  `informant_date_sign` date DEFAULT '0000-00-00',
  `is_late_reg` tinyint(1) DEFAULT '0',
  `late_is_attended` tinyint(1) DEFAULT '0',
  `late_attended_by` varchar(100) DEFAULT NULL,
  `late_sign_date` date DEFAULT '0000-00-00',
  `late_sign_place` varchar(150) DEFAULT NULL,
  `late_death_cause` varchar(150) DEFAULT NULL,
  `late_affiant_name` varchar(100) DEFAULT NULL,
  `late_affiant_address` varchar(150) DEFAULT NULL,
  `late_place_death` varchar(150) DEFAULT NULL,
  `late_bcdate` date DEFAULT '0000-00-00',
  `late_reason` text,
  `affiant_com_tax_nr` varchar(15) DEFAULT NULL,
  `affiant_com_tax_date` date DEFAULT NULL,
  `affiant_com_tax_place` varchar(50) DEFAULT NULL,
  `late_officer_date_sign` date DEFAULT NULL,
  `late_officer_place_sign` varchar(60) DEFAULT NULL,
  `late_officer_name` varchar(60) DEFAULT NULL,
  `late_officer_title` varchar(50) DEFAULT NULL,
  `late_officer_address` varchar(125) DEFAULT NULL,
  `encoder_name` varchar(60) DEFAULT NULL,
  `encoder_title` varchar(30) DEFAULT NULL,
  `encoder_date_sign` date DEFAULT '0000-00-00',
  `history` text,
  `create_id` varchar(35) DEFAULT NULL,
  `create_time` datetime DEFAULT '0000-00-00 00:00:00',
  `modify_id` varchar(35) DEFAULT NULL,
  `modify_time` datetime DEFAULT '0000-00-00 00:00:00',
  `receivedby_name` varchar(60) DEFAULT NULL,
  `receivedby_title` varchar(60) DEFAULT NULL,
  `receivedby_date` date DEFAULT '0000-00-00',
  `mother_maiden_fname` varchar(50) DEFAULT NULL,
  `mother_maiden_mname` varchar(50) DEFAULT NULL,
  `mother_maiden_lname` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`pid`),
  CONSTRAINT `FK_seg_cert_death` FOREIGN KEY (`pid`) REFERENCES `care_person` (`pid`) ON UPDATE CASCADE
);
;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;