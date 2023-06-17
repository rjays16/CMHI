<?php
	//Created By Maimai 10-13-2014s
	define('lab_nr', 235); //default department for walkin patients
	require_once($root_path.'include/care_api_classes/class_core.php');
	
	class Patient_queue{
		var $tb_patient_queue = "seg_patient_queue"; //table for patient queing
		var $tb_results_queue = "seg_patient_result_queue"; //table for examination results queing
		var $sql; //sql
		var $details; //data

		function addQueue(){
			global $db;

			$this->sql = "INSERT INTO ".$this->tb_patient_queue." (queue_id, encounter_nr, dr_nr, queue_status)
							SELECT uuid(), ".$db->qstr($this->details['encounter_nr']).",".
													 $db->qstr($this->details['dr_nr']).", 'pending'";
			if($db->Execute($this->sql)){
				return true;
			}

			return false;
		}

		function updateQueue(){
			global $db;
			$this->sql = "UPDATE ".$this->tb_patient_queue
							." SET dr_nr = ".$db->qstr($this->details['dr_nr'])
							." WHERE encounter_nr = ".$db->qstr($this->details['encounter_nr'])." AND queue_status <> 'done'";
			
			if($db->Execute($this->sql)){
				return true;
			} 

			return false;
		}

		function actionQueue(){
			global $db;
			
			if($this->details['dept_nr'] == lab_nr){
				$this->deletePatientQueue();
			}else{
				if($db->GetOne('SELECT encounter_nr FROM seg_patient_queue WHERE encounter_nr = '.$db->qstr($this->details['encounter_nr']))){
					$this->updateQueue();
				}else{
					$this->addQueue();
				}
			}

			return false;
		}

		function updateStatus(){
			global $db;
			$this->sql = "UPDATE ".$this->details['table_name']
							." SET queue_status = ".$db->qstr($this->details['queue_status'])
							." WHERE queue_id = ".$db->qstr($this->details['queue_id']);

			if($db->Execute($this->sql)){
				return true;
			}

			return false;
		}

		function addResultQueue(){
			global $db;
			$this->sql = "INSERT INTO ".$this->tb_results_queue." (queue_id, queue_status, area, ref_no)
							SELECT uuid(), 'pending', ".$db->qstr($this->details['area']).
													 	",".$db->qstr($this->details['ref_no']);
			if($db->Execute($this->sql)){
				return true;
			}else{
				var_dump($this->sql);
				exit;
			}
		}

		function getDr(){
			global $db;

			$this->sql = "SELECT DISTINCT 
							  (q.`dr_nr`),
							  CONCAT(
							    'DR. ',
							   	fn_get_person_lastname_first(cp.`pid`)
							  ) dr_name 
							FROM
							  seg_patient_queue q 
							  LEFT JOIN care_personell cp 
							    ON cp.`nr` = q.`dr_nr` 
							  LEFT JOIN care_encounter ce 
							    ON ce.`encounter_nr` = q.`encounter_nr` 
							WHERE DATE(ce.`encounter_date`) = DATE(NOW()) 
							ORDER BY dr_name ASC ";
							
			$this->result = $db->Execute($this->sql);
			if($this->result){
				return $this->result;
			}

			return false;
		}

		function getPatients($dr_nr){
			global $db;

			$this->sql = "SELECT 
							  q.`encounter_nr`,
							  fn_get_person_lastname_first (ce.`pid`) AS patient_name,
							  q.`queue_status` 
							FROM
							  seg_patient_queue q 
							  LEFT JOIN care_encounter ce 
							    ON ce.`encounter_nr` = q.`encounter_nr` 
							WHERE q.`dr_nr` = ".$db->qstr($dr_nr) 
							." AND DATE(ce.`encounter_date`) = DATE(NOW()) "
							." ORDER BY FIELD(
							    queue_status,
							    'active',
							    'onqueue',
							    'pending',
							    'done'
							  ), TIME(ce.`encounter_date`) ASC ";
						
			$this->result = $db->Execute($this->sql);
			if($this->result){
				return $this->result;
			}

			return false;
		}

		function checkDr(){
			global $db;

			$this->sql = "SELECT 
							  queue_id 
							FROM
							  seg_patient_queue 
							WHERE dr_nr = '0' OR dr_nr = ".$db->qstr($this->getDrNr)
							."  AND queue_id = ".$db->qstr($this->details['queue_id']);

			if($this->result = $db->Execute($this->sql)){
				if($this->result->RecordCount()){
					return $this->updateDr();
				}
			}

			return false;
		}

		function updateDr(){
			global $db;
			
			$drNr = $this->getDrNr();
			$drName = $db->qstr($this->getDrName($drNr));

			$this->sql = "UPDATE care_encounter SET current_att_dr_nr = ".$db->qstr($drNr).
								", consulting_dr = ".$drName."
									WHERE encounter_nr = ".$db->qstr($this->details['encounter_nr']);
									
			if($db->Execute($this->sql)){
				$this->details['dr_nr'] = $drNr;
				return $this->updateQueue();
			}

			return false;
		}

		function getDrNr(){
			global $db;

			return $db->GetOne("SELECT personell_nr FROM care_users WHERE login_id =".$db->qstr($_SESSION['sess_temp_userid']));
		}

		function getDrName($drNr){
			global $db;

			return $db->GetOne("SELECT fn_get_person_lastname_first(pid) FROM care_personell WHERE nr =".$db->qstr($drNr));
		}

		function deletePatientQueue(){
			global $db;
			
			$this->sql = "DELETE FROM seg_patient_queue WHERE encounter_nr = ".$db->qstr($this->details['encounter_nr']);

			if($db->Execute($this->sql)){
				return true;
			}else{
				return false;
			}
		}
	
	}
?>