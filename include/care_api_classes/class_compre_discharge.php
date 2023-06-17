<?php
/**
*Created by mai
*Created on 09-13-2014
*/

require_once ($root_path.'include/care_api_classes/class_core.php');

class Compre_discharge extends Core{

	var $tb_compre = "seg_encounter_compre";
	var $tb_discharge_info = "seg_encounter_dischargeinfo";
	var $tb_compre_images = "seg_encounter_compre_image";
	var $user_id;
	var $sql;
	var $result;
	var $db;
	
	var $details; 

	function updateVitals(){
		global $db;
		$this->sql = "UPDATE seg_encounter_vitalsigns 
						SET temp = ".$db->qstr($this->details['temp']).
						", pulse_rate = ".$db->qstr($this->details['pulse_rate']).
						", resp_rate = ".$db->qstr($this->details['resp_rate']).
						", systole = ".$db->qstr($this->details['systole']).
						", diastole = ".$db->qstr($this->details['diastole']).
						" WHERE encounter_nr = ".$db->qstr($this->details['encounter_nr']).
						" AND vitalsign_no =".$db->qstr($this->details['vitalsign_no']);
		
		if($db->Execute($this->sql)){
			return true;
		}else{
			return false;
		}
	}

	function getRecentEnc($pid, $encounter_nr){
		global $db;

		$this->sql = "SELECT 
						  encounter_nr 
						FROM
						  care_encounter 
						WHERE DATE(encounter_date) = 
							  (SELECT 
							    DATE(encounter_date) 
							  FROM
							    care_encounter 
							  WHERE encounter_nr = ".$db->qstr($encounter_nr).") 
						  AND pid = ".$db->qstr($pid)." 
						  AND encounter_nr <> ".$db->qstr($encounter_nr)."
						ORDER BY encounter_date DESC 
						LIMIT 1 ";

		if($this->result = $db->Execute($this->sql)){
			return $this->result->FetchRow();
		}

		return false;
	}

	function insertVitals(){
		global $db;

		$this->user_id = $_SESSION['sess_temp_userid'];
		$this->sql = "INSERT INTO seg_encounter_vitalsigns (encounter_nr, date, pid, temp, 
										pulse_rate, resp_rate, systole, diastole, height_ft, height_in, weight,
										create_id, create_dt, modify_id, modify_dt, history) 
						VALUES	(".$db->qstr($this->details['encounter_nr']).", NOW(),".
									$db->qstr($this->details['pid']).",".
									$db->qstr($this->details['temp']).",".
									$db->qstr($this->details['pulse_rate']).",".
									$db->qstr($this->details['resp_rate']).",".
									$db->qstr($this->details['systole']).",".
									$db->qstr($this->details['diastole']).",".
									$db->qstr($this->details['height_ft']).",".
									$db->qstr($this->details['height_in']).",".
									$db->qstr($this->details['weight']).",".
									$db->qstr($this->user_id).", NOW(),".
									$db->qstr($this->user_id).", NOW(),
									CONCAT('Create: ', NOW(), ".$db->qstr($this->user_id)."))";
		
		if($db->Execute($this->sql)){
			return true;
		}

		return false;

	}

	function updateSomeVitals(){
		global $db;
		$this->sql = "UPDATE seg_encounter_vitalsigns 
						SET systole = ".$db->qstr($this->details['systole']).
						", diastole = ".$db->qstr($this->details['diastole']).
						", height_ft = ".$db->qstr($this->details['height_ft']).
						", height_in = ".$db->qstr($this->details['height_in']).
						", weight = ".$db->qstr($this->details['weight']).
						" WHERE encounter_nr = ".$db->qstr($this->details['encounter_nr']).
						" AND vitalsign_no =".$db->qstr($this->details['vitalsign_no']);
		
		if($db->Execute($this->sql)){
			return true;
		}else{
			return false;
		}
	}

	function updateComplaint(){
		global $db;

		$this->sql = "UPDATE care_encounter SET chief_complaint =".$db->qstr($this->details['chief_complaint']).",".
						" er_opd_diagnosis = ".$db->qstr($this->details['adm_diagnosis']).
						" WHERE encounter_nr = ".$db->qstr($this->details['encounter_nr']);

		if($db->Execute($this->sql)){
			return true;
		}else{
			return false;
		}
	}

	function addPE(){
		global $db;

		$hasPE = $this->selectCompre($this->details['encounter_nr']); //checks if there is an existing comprehensive report data

		if($hasPE){ 
			return $this->updatePE();
		}else{
			return $this->insertPE();
		}
	}

	function updatePE(){
		global $db;

		$this->sql = "UPDATE ".$this->tb_compre." SET ".
						"build = ".$db->qstr($this->details['build']).",".
						"deformity = ".$db->qstr($this->details['deformity']).",".
						"skin = ".$db->qstr($this->details['skin']).",".
						"head_and_neck = ".$db->qstr($this->details['head_and_neck']).",".
						"chest_lungs = ".$db->qstr($this->details['chest_lungs']).",".
						"lungs = ".$db->qstr($this->details['lungs']).",".
						"eye = ".$db->qstr($this->details['eyes']).",".
						"vision = ".$db->qstr($this->details['vision']).",".
						"ear = ".$db->qstr($this->details['ears']).",".
						"heart = ".$db->qstr($this->details['heart']).",".
						"abdomen = ".$db->qstr($this->details['abdomen']).",".
						"previous_hosp = ".$db->qstr($this->details['previous_hosp']).",".
						"remarks = ".$db->qstr($this->details['remarks']).",".
						"modify_id = ".$db->qstr($_SESSION['sess_temp_userid']).", ".
					   	"modify_dt = NOW() ".
					   	" WHERE encounter_nr = ".$db->qstr($this->details['encounter_nr']);
		
		if($db->Execute($this->sql)){
			return true;
		}

		return false;
	}

	function insertPE(){
		global $db;

		$this->sql = "INSERT INTO ".$this->tb_compre.
						"(encounter_nr, build, deformity, skin, head_and_neck, chest_lungs,
							lungs, eye, vision, ear, heart, abdomen, previous_hosp,
							remarks, create_id, create_dt)
						VALUES (".$db->qstr($this->details['encounter_nr']).",".
									$db->qstr($this->details['build']).",".
									$db->qstr($this->details['deformity']).",".
									$db->qstr($this->details['skin']).",".
									$db->qstr($this->details['head_and_neck']).",".
									$db->qstr($this->details['chest_lungs']).",".
									$db->qstr($this->details['lungs']).",".
									$db->qstr($this->details['eyes']).",".
									$db->qstr($this->details['vision']).",".
									$db->qstr($this->details['ears']).",".
									$db->qstr($this->details['heart']).",".
									$db->qstr($this->details['abdomen']).",".
									$db->qstr($this->details['previous_hosp']).",".
									$db->qstr($this->details['remarks']).",".
									$db->qstr($_SESSION['sess_temp_userid']).", NOW())";

		if($db->Execute($this->sql)){
			return true;
		}

		return false;
	}

	function addCompre(){
		global $db;

		$hasCompre = $this->selectCompre($this->details['encounter_nr']); //checks if there is an existing comprehensive report data

		if($hasCompre){ 
			return $this->updateCompre();
		}else{
			return $this->insertCompre();
		}
	}

	function updateCompre(){
		global $db, $HTTP_SESSION_VARS;

		$this->sql = "UPDATE ".$this->tb_compre." SET ".
					   "histo_illness = ".$db->qstr($this->details['histo_illness']).",".
					   "skin = ".$db->qstr($this->details['skin']).",".
					   "head_and_neck = ".$db->qstr($this->details['head_and_neck']).",".
					   "eye = ".$db->qstr($this->details['eye']).",".
					   "ear = ".$db->qstr($this->details['ear']).",".
					   "chest_lungs = ".$db->qstr($this->details['chest_lungs']).",".
					   "lungs = ".$db->qstr($this->details['lungs']).",".
					   "general_survey = ".$db->qstr($this->details['general_survey']).",".
					   "cvs = ".$db->qstr($this->details['cvs']).",".
					   "abdomen = ".$db->qstr($this->details['abdomen']).",".
					   "extremities = ".$db->qstr($this->details['extremities']).",".
					   "neuro = ".$db->qstr($this->details['neuro']).",".
					   "past_medical_history = ".$db->qstr($this->details['past_med_history']).",".
					   "family_history = ".$db->qstr($this->details['family_history']).",".
					   "persona_social_history = ".$db->qstr($this->details['persona_social_history']).",".
					   "immu_history = ".$db->qstr($this->details['immu_history']).",".
					   "obs_history = ".$db->qstr($this->details['obs_history']).",".
					   "modify_id = ".$db->qstr($_SESSION['sess_temp_userid']).", ".
					   "modify_dt = NOW() ".
					   " WHERE encounter_nr = ".$db->qstr($this->details['encounter_nr']);
		
		if($db->Execute($this->sql)){
			return true;
		}else{
			return false;
		}
	}

	function insertCompre(){
		global $db, $HTTP_SESSION_VARS;

		$this->sql = "INSERT INTO ".$this->tb_compre.
						" (encounter_nr, general_survey, lungs, histo_illness, skin, head_and_neck, eye, ear, chest_lungs, cvs, 
								abdomen, extremities, neuro, past_medical_history, family_history, 
								persona_social_history, immu_history, obs_history, create_id, create_dt) ".
						"VALUES (".$db->qstr($this->details['encounter_nr']).",".
								   $db->qstr($this->details['general_survey']).",".
								   $db->qstr($this->details['lungs']).",".
								   $db->qstr($this->details['histo_illness']).",".
								   $db->qstr($this->details['skin']).",".
								   $db->qstr($this->details['head_and_neck']).",".
								   $db->qstr($this->details['eye']).",".
								   $db->qstr($this->details['ear']).",".
								   $db->qstr($this->details['chest_lungs']).",".
								   $db->qstr($this->details['cvs']).",".
								   $db->qstr($this->details['abdomen']).",".
								   $db->qstr($this->details['extremities']).",".
								   $db->qstr($this->details['neuro']).",".
								   $db->qstr($this->details['past_med_history']).",".
								   $db->qstr($this->details['family_history']).",".
								   $db->qstr($this->details['persona_social_history']).",".
								   $db->qstr($this->details['immu_history']).",".
								   $db->qstr($this->details['obs_history']).",".
								   $db->qstr($_SESSION['sess_temp_userid']).", NOW())";

		if($db->Execute($this->sql)){
			return true;
		}else{
			return false;
		}
	}

	function selectCompre($encounter_nr){
		global $db;
		$this->sql = "SELECT * FROM ".$this->tb_compre.
						" WHERE encounter_nr = ".$db->qstr($encounter_nr);
		
		if($this->result = $db->Execute($this->sql)){
			if($row = $this->result->FetchRow()){
				return $row;
			}
		}

		return false;
	}

	function selectDischarge($encounter_nr){
		global $db;
		
		$this->sql = "SELECT * FROM ".$this->tb_discharge_info.
					" WHERE encounter_nr = ".$db->qstr($encounter_nr);
		
		if($this->result = $db->Execute($this->sql)){
			if($row = $this->result->FetchRow()){
				return $row;
			}
		}

		return false;
	}

	function addDischargeInfo(){
		global $db;
		
		$hasDischargeInfo = $this->selectDischarge($this->details['encounter_nr']);

		if($hasDischargeInfo){
			return $this->updateDischargeInfo();
		}else{
			return $this->insertDischargeInfo();
		}

	}

	function insertDischargeInfo(){
		global $db, $HTTP_SESSION_VARS;

		$this->sql = "INSERT INTO ".$this->tb_discharge_info.
						"(encounter_nr, medication, proc, course_ward, ".
							"no_of_infections, recommendations, notes, note, cond, create_id, create_dt)".
						" VALUES ( ".$db->qstr($this->details['encounter_nr']).",".
									$db->qstr($this->details['medication']).",".
									$db->qstr($this->details['procedure']).",".
									$db->qstr($this->details['course_ward']).",".
									$db->qstr($this->details['no_of_infections']).",".	
									$db->qstr($this->details['recommendations']).",".
									$db->qstr($this->details['notes']).",".
									$db->qstr($this->details['note']).",".
									$db->qstr($this->details['cond']).",".
									$db->qstr($_SESSION['sess_temp_userid']).", NOW())";
		
		if($db->Execute($this->sql)){
			return true;
		}else{
			return false;
		}
	}

	function updateDischargeInfo(){
		global $db, $HTTP_SESSION_VARS;

		$this->sql = "UPDATE ".$this->tb_discharge_info." SET ".
						"medication = ".$db->qstr($this->details['medication']).", ".
						"proc = ".$db->qstr($this->details['procedure']).", ".
						"course_ward = ".$db->qstr($this->details['course_ward']).", ".
						"no_of_infections = ".$db->qstr($this->details['no_of_infections']).", ".
						"recommendations = ".$db->qstr($this->details['recommendations']).", ".
						"notes = ".$db->qstr($this->details['notes']).", ".
						"note = ".$db->qstr($this->details['note']).", ".
						"cond = ".$db->qstr($this->details['cond']).", ".
						"modify_id = ".$db->qstr($_SESSION['sess_temp_userid']).", ".
						"modify_dt = NOW()".
						" WHERE encounter_nr =".$db->qstr($this->details['encounter_nr']);
				
		if($db->Execute($this->sql)){
			return true;
		}else{
			return false;
		}
	}

	function saveDiagnosis(){
		global $db, $HTTP_SESSION_VARS;
		$succesdb = true;

		foreach($this->details['icd'] as $row){
			$this->sql = "INSERT INTO seg_doctors_diagnosis (encounter_nr, icd_code, icd_description, personell_nr, create_id, create_time) ".
							"VALUES (".$db->qstr($this->details['encounter_nr']).",".$db->qstr($row['code']).", ".$db->qstr($row['description']).
								",".$db->qstr($this->details['dr_nr']).",".$db->qstr($_SESSION['sess_temp_userid']).", NOW())";
			
			if(!$db->Execute($this->sql)){
				$succesdb = false;
			}
		}

		if($succesdb){
			return true;
		}else{
			return false;
		}
	}

	function removeDiagnosis(){
		global $db;
		$this->sql = "DELETE FROM seg_doctors_diagnosis WHERE encounter_nr = ".$db->qstr($this->details['encounter_nr']).
						" AND personell_nr =".$db->qstr($this->details['dr_nr']);
						
		if($db->Execute($this->sql)){
			return true;
		}else{
			return false;
		}
	}

	function getSelectedICDDesc($sfilter = '') {
        global $db;
		$char_array = array(".","'","{","}","[","]","^","(",")","-","`",",","|");
		$sfilter = str_replace($char_array,"",$sfilter);
		$sfilter = trim($sfilter);
        
        $this->sql = "SELECT 
						  t.diagnosis_code,
						  t.description 
						FROM
						  (SELECT 
						    diagnosis_code,
						    description 
						  FROM
						    care_icd10_en 
						  UNION
						  SELECT 
						    CODE AS diagnosis_code,
						    description 
						  FROM
						    seg_case_rate_packages) t 
						WHERE (
						    REPLACE(t.description, ',', '') REGEXP '.*$sfilter.*' 
						    OR REPLACE(t.diagnosis_code, ',', '') REGEXP '.*$sfilter.*'
						  ) 
						  AND t.description <> '' 
						GROUP BY t.diagnosis_code 
						ORDER BY t.description ";

        if ($this->result = $db->Execute($this->sql)) {
            if ($this->result->RecordCount())
                return $this->result;
            else
                return FALSE;
        }
        else
            return FALSE;
    }

    function getICD(){
    	global $db;

    	$this->sql = "SELECT icd_code AS diagnosis_code, icd_description AS description FROM seg_doctors_diagnosis WHERE encounter_nr = ".$db->qstr($this->details['encounter_nr']);
    				  
    	if ($this->result = $db->Execute($this->sql)) {
            if ($this->result->RecordCount())
                return $this->result;
            else
                return FALSE;
        }else{
            return FALSE;
        }
    }

    function getDiagnosis($encounter_nr){
    	global $db;
    	
    	$this->sql = "SELECT code, description FROM seg_encounter_diagnosis WHERE encounter_nr = ".$db->qstr($encounter_nr)
    					." AND is_deleted <> 1 ORDER BY entry_no ASC";
    	
    	$this->result = $db->Execute($this->sql);
    	if($this->result){
    		if($this->result->RecordCount()){
    			return $this->result;
    		}
    	}

    	return FALSE;
    }

    function getProcedures($encounter_nr){
    	global $db;
    	
    	$this->sql = "SELECT 
					  smod.`ops_code`, smod.`description`
					FROM
					  seg_misc_ops smo 
					  LEFT JOIN seg_misc_ops_details smod 
					    ON smod.refno = smo.refno 
					WHERE smo.encounter_nr = ".$db->qstr($encounter_nr);

		$this->result = $db->Execute($this->sql);
		if($this->result){
			if($this->result->RecordCount()){
				return $this->result;
			}
		}

		return FALSE;
    }

    function insertImages(){
    	global $db;
    	
    	$this->sql = "INSERT INTO ".$this->tb_compre_images.
    					"(encounter_nr, filename, create_time, create_id) VALUES(".
    					$db->qstr($this->details['encounter_nr']).",".
    					$db->qstr($this->details['filename']).", NOW(),".
    					$db->qstr($_SESSION['sess_temp_userid']).
    					")";

		if($db->Execute($this->sql)){
			return true;
		}

		return false;

    }

    function getImages(){
    	global $db;

    	$this->sql = "SELECT filename FROM ".$this->tb_compre_images.
    					" WHERE is_deleted <> 1 AND encounter_nr = ".$db->qstr($this->details['encounter_nr']).
    					" ORDER BY create_time ASC ";

    	if($this->result = $db->Execute($this->sql)){
    		return $this->result;
    	}

    	return false;
    }

    function removeImage(){
    	global $db;

    	$this->sql = "UPDATE ".$this->tb_compre_images.
    					" SET is_deleted = 1 ".
    					" WHERE encounter_nr=".$db->qstr($this->details['encounter_nr']).
    					" AND filename = ".$db->qstr($this->details['filename']);
    	
    	if($db->Execute($this->sql)){
    		return true;
    	}else{
    		return false;
    	}
    }

    function getEncounters($pid){
    	global $db;

    	$this->sql = "SELECT 
						  DATE(ce.`encounter_date`) encounter_date,
						  ce.`encounter_nr`,
						  cte.`type`,
						  IFNULL(
						    ce.`chief_complaint`,
						    ce.`er_opd_diagnosis`
						  ) er_diagnosis,
						  ce.important_info 
						FROM
						  care_encounter ce 
						  LEFT JOIN care_type_encounter cte 
						    ON cte.type_nr = ce.`encounter_type` 
						WHERE ce.pid = ".$db->qstr($pid)." 
						ORDER BY ce.`encounter_date` DESC ";

		if($this->result = $db->Execute($this->sql)){
			return $this->result;
		}

		return false;
    }

    function getDiagnosisEncounter($encounter_nr){
    	global $db;

    	$this->sql = "SELECT 
					  IFNULL(
					    ced.`diagnosis_description`,
					    cie.description
					  ) diagnosis 
					FROM
					  care_encounter_diagnosis ced 
					  LEFT JOIN care_icd10_en cie 
					    ON cie.diagnosis_code = ced.code 
					WHERE status <> 'deleted' AND encounter_nr =  ".$db->qstr($encounter_nr);

		if($this->result = $db->Execute($this->sql)){
			if($this->result->RecordCount()){
				return $this->result;
			}
		}

		return false;
    }
}

?>