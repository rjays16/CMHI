INSERT INTO `cainglet0810`.`care_config_global` (`type`, `value`, `modify_time`) 
VALUES
  (
    'new_report_jrxml',
    'csfp1,PHIC_CF1,ACR_2,eclaims_status_report',
    '2018-06-19 17:35:22'
  )

  ALTER TABLE `cainglet0810`.`seg_encounter_insurance_memberinfo` ADD COLUMN `patient_pin` VARCHAR(25) DEFAULT '000000000000' NULL AFTER `parent_pid`

  #NOTE: CHANGE! the database name above for live and other database uses...