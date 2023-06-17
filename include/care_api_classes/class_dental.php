<?php
/*created by mai 12-18-2014*/
/**
* @package care_api
*/

require_once($root_path.'include/care_api_classes/class_core.php');

class Dental extends Core{

	var $tb_tooth = "seg_tooth"; //table list of tooth
	var $tb_dental = "seg_dental"; //table toot operation and condition
	var $tb_dental_medical_hist = "seg_dental_med_hist"; //table for medical history
	var $tb_dental_tooth = "seg_dental_tooth"; //table for dental tooth parts

	var $result;
	var $sql;

	function getAlltooth(){
		global $db;

		$this->sql = "SELECT tooth_no FROM ".$this->tb_tooth;

		if($this->result = $db->Execute($this->sql)){
			if($this->result->RecordCount()) {
				while($row = $this->result->FetchRow()){
					$data[] = $row['tooth_no'];
				}
			}
			return $data;
		}else{
			return false;
		}
	}

	function insertOpsCond($data){
		global $db;

		$this->sql = "INSERT INTO ".$this->tb_dental. 
						"(uuid, encounter_nr, tooth_no, ops, con)
						SELECT UUID(), ".
								$db->qstr($data["encounter_nr"]).",".
								$db->qstr($data["tooth_no"]).",".
								$db->qstr($data["ops"]).",".
								$db->qstr($data["con"])."";

		if($db->Execute($this->sql)){
			return true;
		}

		return true;
	}

	function removeTeeth($encounter_nr){
		global $db;

		$this->sql = "DELETE FROM ".$this->tb_dental." WHERE encounter_nr=".$db->qstr($encounter_nr);

		if($db->Execute($this->sql)){
			return true;
		}

		return false;
	}

	function selectTeeth($encounter_nr){
		global $db;

		$this->sql = "SELECT tooth_no, ops, con FROM ".$this->tb_dental.
						" WHERE encounter_nr = ".$db->qstr($encounter_nr);
						
		if($this->result = $db->Execute($this->sql)){
			return $this->result;
		}
	}

	function selectMedicalHistory($encounter_nr){
		global $db;

		$this->sql = "SELECT * FROM ".$this->tb_dental_medical_hist." WHERE encounter_nr = ".$db->qstr($encounter_nr);
		
		if($this->result = $db->Execute($this->sql)){
			return $this->result->FetchRow();
		}

		return false;
	}

	function saveMedicalHistory($data, $encounter_nr){
		global $db;

		$this->sql = "INSERT INTO ".$this->tb_dental_medical_hist.
						" (tongue, palate, tonsils, lips,
									floor_of_mouth, allergies, heart_disease,
									blood_dyscracia, diabetes, kidney, liver, others,
									hygiene, tooth_count, services, operator, checked_by,
									diagnosis, create_id, create_dt, modify_id, modify_dt, encounter_nr, cheeks)
							VALUES (".$db->qstr($data['tongue']).",".
										$db->qstr($data['palate']).",".
										$db->qstr($data['tonsils']).",".
										$db->qstr($data['lips']).",".
										$db->qstr($data['floor_of_mouth']).",".
										$db->qstr($data['allergies']).",".
										$db->qstr($data['heart_disease']).",".
										$db->qstr($data['blood_dyscracia']).",".
										$db->qstr($data['diabetes']).",".
										$db->qstr($data['kidney']).",".
										$db->qstr($data['liver']).",".
										$db->qstr($data['others']).",".
										$db->qstr($data['hygiene']).",".
										$db->qstr($data['tooth_count']).",".
										$db->qstr($data['services']).",".
										$db->qstr($data['operator']).",".
										$db->qstr($data['checked_by']).",".
										$db->qstr($data['diagnosis']).",".
										$db->qstr($_SESSION['sess_temp_userid']).", NOW(),".
										$db->qstr($_SESSION['sess_temp_userid']).", NOW(),".$db->qstr($encounter_nr).",".
										$db->qstr($data['cheeks']).")";
										
		if($db->Execute($this->sql)){
			return true;
		}

		return false;
	}

	function updateMedicalHistory($data, $encounter_nr){
		global $db;

		$this->sql = "UPDATE ".$this->tb_dental_medical_hist.
						" SET tongue = ".$db->qstr($data['tongue']).", palate = ".
										$db->qstr($data['palate']).", tonsils = ".
										$db->qstr($data['tonsils']).", lips = ".
										$db->qstr($data['lips']).", floor_of_mouth = ".
										$db->qstr($data['floor_of_mouth']).", cheeks = ".
										$db->qstr($data['cheeks']).", allergies = ".
										$db->qstr($data['allergies']).", heart_disease = ".
										$db->qstr($data['heart_disease']).", blood_dyscracia = ".
										$db->qstr($data['blood_dyscracia']).", diabetes = ".
										$db->qstr($data['diabetes']).", kidney = ".
										$db->qstr($data['kidney']).", liver = ".
										$db->qstr($data['liver']).", others = ".
										$db->qstr($data['others']).", hygiene = ".
										$db->qstr($data['hygiene']).", tooth_count = ".
										$db->qstr($data['tooth_count']).", services = ".
										$db->qstr($data['services']).", operator = ".
										$db->qstr($data['operator']).", checked_by = ".
										$db->qstr($data['checked_by']).", diagnosis = ".
										$db->qstr($data['diagnosis']).", modify_id = ".
										$db->qstr($_SESSION['sess_temp_userid']).", modify_dt = NOW() ".
											" WHERE encounter_nr = ".$db->qstr($encounter_nr);
											
			if($db->Execute($this->sql)){
				return true;
			}

			return false;
	}

	function insertDentalTooth($data){
		global $db;

		$this->sql = "INSERT INTO ".$this->tb_dental_tooth.
							"(encounter_nr, tooth_0, tooth_1, tooth_2, tooth_3, tooth_4, tooth_no)
							VALUES (".$db->qstr($data['encounter_nr']).",".
										$db->qstr($data['zero']).",".
										$db->qstr($data['one']).",".
										$db->qstr($data['two']).",".
										$db->qstr($data['three']).",".
										$db->qstr($data['four']).",".
										$db->qstr($data['tooth_no']).")";

		if($db->Execute($this->sql)){
			return true;
		}

		return false;
	}

	function getDentalTooth($encounter_nr){
		global $db;

		$this->sql = "SELECT tooth_no, tooth_0, tooth_1, tooth_2, tooth_3, tooth_4 FROM ".$this->tb_dental_tooth." WHERE encounter_nr = ".$db->qstr($encounter_nr);

		if($this->result = $db->Execute($this->sql)){
			return $this->result;
		}

		return false;
	}

	function deleteTooth($encounter_nr){
		global $db;

		$this->sql = "DELETE FROM ".$this->tb_dental_tooth." WHERE encounter_nr = ".$db->qstr($encounter_nr);

		if($db->Execute($this->sql)){
			return true;
		}

		return false;
	}
}

?>