<?php
/*
 * @package care_api
 */

require_once($root_path.'include/care_api_classes/class_core.php');

define('CHEMO',96408);
define('HEMO',90935);
//define('DEB',11000);

class Icd_Icp extends Core{

	function getInfo($code){
		global $db;

		if(empty($code)) return FALSE;

		$this->sql="SELECT * FROM seg_case_rate_packages WHERE code=".$db->qstr($code);

		if($this->res['info']=$db->Execute($this->sql)){
			if($this->res['info']->RecordCount()){
				return $this->res['info'];
			}else{return FALSE; };
		}else{return FALSE; }
	}//end function getInfo

	function removeICDCode($diagnosis_nr, $create_id){
		global $db;

		$history =$this->ConcatHistory("Deleted ".date('Y-m-d H:i:s')." ".$create_id."\n");
		$this->sql = "UPDATE seg_encounter_diagnosis SET status='deleted',history=".$history." ".
								 "\n WHERE diagnosis_nr = $diagnosis_nr";

		if($result=$db->Execute($this->sql)){
			if($result->RecordCount()){
				return TRUE;
			}else{return FALSE; };
		}else{return FALSE; }
	}//end function removeICDCode

    //added by Nick 05-30-2014
    function getInCondQuery($elems){
        $query = '';
        foreach ($elems as $elem) {
            $query .= $elem . ",";
        }
        return trim($query,',');
    }

	/**
	* Updated by Jarel
	* Updated on 03/05/2014
	* Get Package details based on diagnosis and procedure encoded
	* @param string enc
	* @return result
	*
	* Updated by Nick
	* 05/07/2014 :
	* Sum all num_sessions for rvs, for multiple special procedures
    * 05/30/2014 :
    * Added array of allowed and not allowed multiple procedures
	* @param string enc
	* @return result 
	**/
	function searchIcdIcp($enc){
		global $db;
		
        $allowed_multiple = $this->getInCondQuery(array(HEMO));
        $not_allowed_multiple = $this->getInCondQuery(array(CHEMO));

		//commented by Nick 05-07-2014
		// $this->sql = "SELECT sed.`code` AS code , 1 as num_sessions, '' as laterality, sp.*, spe.*
		// 				FROM seg_encounter_diagnosis sed
		// 				INNER JOIN seg_case_rate_packages sp
		// 					ON sp.code = sed.code
		// 				LEFT JOIN seg_case_rate_special spe
		// 					ON sp.code = spe.sp_package_id 
		// 				WHERE sed.`encounter_nr` = ".$db->qstr($enc)."
		// 				AND sed.`is_deleted` = 0
		// 			 UNION 
		// 			 SELECT smod.`ops_code` AS code, num_sessions, laterality, p.*, spe.*
		// 				FROM seg_misc_ops smo
		// 				INNER JOIN seg_misc_ops_details smod
		// 					ON smo.`refno` = smod.`refno`
		// 				INNER JOIN seg_case_rate_packages p
		// 					ON p.code = smod.ops_code
		// 				LEFT JOIN seg_case_rate_special spe
		// 					ON p.code = spe.sp_package_id
		// 				WHERE smo.`encounter_nr` = ".$db->qstr($enc)."\n
		// 			  ORDER BY (package * num_sessions) DESC";
		
		//added by Nick 05-07-2014
		$this->sql = "SELECT 
						  sed.code AS CODE,
						  1 AS num_sessions,
						  '' AS laterality,
						  sp.*,
						  spe.* 
						FROM
						  seg_encounter_diagnosis sed 
						INNER JOIN seg_case_rate_packages sp
							ON sp.code = sed.code
						  LEFT JOIN seg_case_rate_special spe 
						    ON sp.code = spe.sp_package_id 
						WHERE sed.encounter_nr = ?
						  AND sed.is_deleted = 0 
						UNION
						SELECT 
						  smod.ops_code AS CODE,
						  IF(
						    smod.ops_code IN ($not_allowed_multiple)
						    OR smod.ops_code NOT IN ($allowed_multiple)
						    AND p.description NOT LIKE '%Debridement%',
						    1,
						    SUM(num_sessions)
						  ) AS num_sessions,
						  laterality,
						  p.*,
						  spe.* 
						FROM
						  seg_misc_ops smo 
						INNER JOIN seg_misc_ops_details smod
						    ON smo.refno = smod.refno 
						INNER JOIN seg_case_rate_packages p
							ON p.code = smod.ops_code
						  LEFT JOIN seg_case_rate_special spe 
						    ON p.code = spe.sp_package_id 
						WHERE smo.encounter_nr = ? 
						GROUP BY smod.ops_code 
						ORDER BY (package * num_sessions) DESC ;";

		// if($this->res['info']=$db->Execute($this->sql)){ //commented by Nick 05-07-2014
		// $db->debug = true;
		if($this->res['info']=$db->Execute($this->sql, array($enc,$enc))){//added by Nick 05-07-2014
			if($this->res['info']->RecordCount()){
				return $this->res['info'];
					}else{return FALSE; };
				}else{return FALSE; }
		// $db->debug = false;
		}

	#updated by Nick, 4/15/2014 - order by entry_no
		function searchIcd($enc){
		global $db;

		$this->sql = "SELECT 
						  sd.code,
						  sd.description AS description,
						  sd.diagnosis_nr AS diagnosis_nr,
						  sd.type_nr AS type_nr,
						  sd.code_alt AS code_alt,
						  e.`consulting_dr_nr` AS dr,
						  e.is_confidential AS conf 
						FROM
						  seg_encounter_diagnosis AS sd 
						  INNER JOIN care_encounter AS e 
						    ON e.encounter_nr = sd.encounter_nr 
						WHERE sd.encounter_nr = ".$db->qstr($enc)."
						AND e.status NOT IN ('deleted','hidden','inactive','void')
						AND sd.is_deleted = 0 ORDER BY sd.entry_no ASC";						   

		if($this->res['info']=$db->Execute($this->sql)){
			if($this->res['info']->RecordCount()){
				return $this->res['info'];
			}else{return FALSE; };
		}else{return FALSE; }
	}



	function delICD($diagnosis_nr, $create_id){
				//$this->useCode('icd',$tabs);

				$history =$this->ConcatHistory("Deleted ".date('Y-m-d H:i:s')." ".$create_id."\n");
				$this->sql = "UPDATE seg_encounter_diagnosis SET status='deleted',history=".$history." ".
										 "\n WHERE diagnosis_nr = $diagnosis_nr";
				return $this->Transact();
		}

	function getSavedICDinfo($code,$enc){
		global $db;

	    $this->sql = "SELECT diagnosis_nr FROM seg_encounter_diagnosis WHERE is_deleted='0' AND encounter_nr=".$db->qstr($enc)." AND code=".$db->qstr($code);

	    if ($buf=$db->Execute($this->sql)){
	            if($buf->RecordCount()) {
	                return $buf->FetchRow();
	            }else { return FALSE; }
	        }else { return FALSE; }
	}

	
	/**
    * Created By Jarel
    * Created On 02/19/2014
    * Get the automatic excess amount of specific procedures
    * @param string code
    * @return string $amount
    **/
	function getOpsAdditional($code)
	{
		global $db;

		$sql = "SELECT * FROM seg_ops_auto_excess WHERE code = ".$db->qstr($code);

		if($result = $db->Execute($sql)){
			if($result->RecordCount()){
				while($row = $result->FetchRow()){
					return $row['amount'];
				}
			}else{return 0;}
		}else{return 0;}
	}	



	
}//end class icd_icp

?>