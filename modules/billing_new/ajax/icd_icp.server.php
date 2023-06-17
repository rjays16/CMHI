<?php
	function populateDiagnosisList($encounter_nr, $sElem,$page, $frombilling=0) {
		global $db;
		$glob_obj = new GlobalConfig($GLOBAL_CONFIG);
		$glob_obj->getConfig('pagin_patient_search_max_block_rows');
		$maxRows = $GLOBAL_CONFIG['pagin_patient_search_max_block_rows'];

		$objResponse = new xajaxResponse();
		$enc_obj=new Encounter;
		$ward_obj=new Ward;
		$dept_obj=new Department;
		$pers_obj=new Personell;
		$icdIcp = new Icd_Icp();

        if ($frombilling==1) $maxRows = 20;
		$ergebnis = $icdIcp->searchIcd($encounter_nr);

		$objResponse->addScriptCall("clearList","DiagnosisList");
		if ($ergebnis) {
			$rows=$ergebnis->RecordCount();
			while($result=$ergebnis->FetchRow()) {
				$doctorinfo = $pers_obj->get_Person_name($result['dr']);
				$middleInitial = "";
				if (trim($doctorinfo['name_middle'])!=""){
					$thisMI=split(" ",$doctorinfo['name_middle']);
					foreach($thisMI as $value){
						if (!trim($value)=="")
						$middleInitial .= $value[0];
					}
					if (trim($middleInitial)!="")
					$middleInitial .= ".";
				}

				$doctor_name = $pers_obj->concatname((is_null($doctorinfo["name_last"])) ? "" : $doctorinfo["name_last"],
													 (is_null($doctorinfo["name_first"])) ? "" : $doctorinfo["name_first"], $middleInitial);
				$doctor_name = ucwords(strtolower($doctor_name));
				$doctor_name = htmlspecialchars($doctor_name);

				if ($result['conf']==1){
					$doctor_name = '<font size=1 color="red"><strong>CONFIDENTIAL</strong></font>';
				}
				$altdesc = '';
				$objResponse->addScriptCall("addDiagnosisToList","DiagnosisList",trim($result["diagnosis_nr"]),$result["code"],$result["description"],$doctor_name, 1, $altdesc, $result["type_nr"],$result["code_alt"]);
			}#end of while
		} #end of if

		if (!$rows) $objResponse->addScriptCall("addDiagnosisToList","DiagnosisList",NULL);
		if ($sElem) {
			$objResponse->addScriptCall("endAJAXSearch",$sElem);
		}

		return $objResponse;
	}

	function addCode($encounter,$encounter_type,$xdate,$code,$doc_nr,$create_id, $type, $mp,$description='') {

		$cdObj=new Medocs;
		$icdIcpObj=new Icd_Icp($code);
		$objResponse = new xajaxResponse();

		 #$objResponse->addAlert($type);

		if($mp=='icd'){
			$alert_fail = "Saving of the ICD failed!";
			$alert_invalid = "Invalid ICD Code!";
			$list = 'DiagnosisList';
		}else if($mp=='icp'){
			$alert_fail = "Saving of the ICP failed!";
			$alert_invalid = "Invalid ICP Code!";
			$list = 'ProcedureList';
		}else{
			return FALSE;
		}

	   if($rw=$icdIcpObj->getInfo($code)){
			$desc=$rw->FetchRow();

			if($mp=='icd'){
				$xcode = strtoupper($desc['code']);
			}else{
				$xcode = strtoupper($desc['code']);
			}

			$pers_obj = new Personell();

			if ($docinfo = $pers_obj->get_Person_name2($doc_nr)) {
				$doc_name = $pers_obj->concatname((is_null($docinfo["name_last"])) ? "" : $docinfo["name_last"],
												  (is_null($docinfo["name_first"])) ? "" : $docinfo["name_first"],
												  (is_null($docinfo["name_middle"])) ? "" : $docinfo["name_middle"]);
				$dept_nr = (is_null($docinfo["location_nr"])) ? 0 : $docinfo["location_nr"];
			}
			else {
				$doc_name = "";
				$dept_nr = 0;
			}

			$result=$cdObj->save_Seg_encounter_diagnoses($encounter,trim($xcode),$create_id,$desc['description'],$type);

			if($result){
				$icdInfo = $icdIcpObj->getSavedICDinfo($code,$encounter);
				$objResponse->addScriptCall("addDiagnosisToList", 'DiagnosisList', $icdInfo['diagnosis_nr'], trim($code), $desc['description'], $doc_name, 1, $type);
				if($mp=='icd'){
					$objResponse->addScriptCall("clearICDFields");
				}else{
					$objResponse->addScriptCall("clearICPFields");
				}

			}else{
				$objResponse->addAlert($alert_fail);
			}
		}else{
			$pers_obj = new Personell();

			if ($docinfo = $pers_obj->get_Person_name2($doc_nr)) {
				$doc_name = $pers_obj->concatname((is_null($docinfo["name_last"])) ? "" : $docinfo["name_last"],
												  (is_null($docinfo["name_first"])) ? "" : $docinfo["name_first"],
												  (is_null($docinfo["name_middle"])) ? "" : $docinfo["name_middle"]);
				$dept_nr = (is_null($docinfo["location_nr"])) ? 0 : $docinfo["location_nr"];
			}
			else {
				$doc_name = "";
				$dept_nr = 0;
			}

			$result=$cdObj->save_Seg_encounter_diagnoses($encounter,$code,$create_id,$description,0);
			if($result){
				$icdInfo = $icdIcpObj->getSavedICDinfo($code,$encounter);
				$objResponse->addScriptCall("addDiagnosisToList", 'DiagnosisList', $icdInfo['diagnosis_nr'], trim($code), $description, $doc_name, 1, $type);
				if($mp=='icd'){
					$objResponse->addScriptCall("clearICDFields");
				}else{
					$objResponse->addScriptCall("clearICPFields");
				}

			}else{
				$objResponse->addAlert($alert_fail);
			}
		}

		return $objResponse;
	}// End addCode Function

	function rmvCode($diagnosis_nr, $create_id){

		$cdObj=new Medocs;
		$objResponse = new xajaxResponse();

		if ($result=$cdObj->removeICDCode($diagnosis_nr, $create_id))
		//if ($result = $cdObj->delICD($diagnosis_nr, $create_id))
			$objResponse->addAlert("Data has been successfully deleted!");

		if($result){
			$objResponse->addScriptCall("removeAddedICD",$diagnosis_nr);
		}else{
			$objResponse->addAlert(print_r($cdObj->sql,TRUE));
		}
		return $objResponse;
	}

	// Added by LST -- 08.18.2009
	function saveAltDesc($enc_nr, $code, $desc, $create_id) {
		$objResponse = new xajaxResponse();

		$objmdoc = new Medocs();
		if (!$objmdoc->saveAltDesc($enc_nr, $code, $desc, $create_id)) {
			$objResponse->addAlert("ERROR: ".$objmdoc->sql);
		}

		return $objResponse;
	}

    //added by jasper 06/30/2013
    function saveAltCode($enc_nr, $code, $altcode, $create_id) {
        $objResponse = new xajaxResponse();

        $objmdoc = new Medocs();
        if (!$objmdoc->saveAltCode($enc_nr, $code, $altcode, $create_id)) {
            $objResponse->addAlert("ERROR: ".$objmdoc->sql);
        }

        return $objResponse;
    }
    //added by jasper 06/30/2013

    //added by jasper 04/24/2013
    function updateAltICD($icd10values, $encnr=0) {
        $objResponse = new xajaxResponse();

        $objmdoc = new Medocs();
        $y=0;
        for($i=0;$i<count($icd10values);$i++) {

                if (checkICDExist($encnr, $icd10values[$i]['code'])) {
                    //update seg_encounter_diagnosis
                    if(updateICDEntNr($encnr, $icd10values[$i]['code'], $icd10values[$i]['entry_no'], $icd10values[$i]['alt_code'])){
                    	$y++;
                    }
                    else{
                    	$y--;
                    }

                } else {
                    //insert seg_encounter_diagnosis
                    if(addICD($encnr, $icd10values[$i]['code'], $icd10values[$i]['diag'], $icd10values[$i]['alt_code'])){
                    	$y++;
                    }
                    else{
                    	$y--;
                    }
                }
        }
        if( $y==$i )$objResponse->alert("Sequence Updated!");
        else $objResponse->alert("Failed to update sequence!");
        return $objResponse;
    }

    function checkICDExist($encnr, $code) {
        global $db;

        $strSQL = "SELECT * FROM seg_encounter_diagnosis WHERE encounter_nr = '" . $encnr . "' AND code = '" . $code . "' AND is_deleted = 0";
        if ($result = $db->Execute($strSQL)) {
            if ($result->RecordCount()>0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    //added by jasper 04/24/2013

    //added by Francis 04/26/2013
    function updateICDEntNr($encnr, $code, $entnr, $alt_code = "") {
        global $db;
        global $HTTP_SESSION_VARS;

        $modID = $HTTP_SESSION_VARS['sess_temp_userid'];


        $strSQL = "UPDATE seg_encounter_diagnosis SET entry_no ='" .$entnr. "', " .
                   "code_alt = '" .$alt_code. "', " .
                   "modify_id = '" .$modID. "', " .
                   "modify_time = NOW() " .
                   "WHERE encounter_nr = '" . $encnr . "' AND code = '" . $code . "'";

        if ($db->Execute($strSQL)) {
            if ($db->Affected_Rows()) {
                return true;
            }
        }else{
            	return false;
        }
    }

    function addICD($encnr, $code, $diag, $alt_code = "") {
        global $db;
        global $HTTP_SESSION_VARS;

        $createID = $HTTP_SESSION_VARS['sess_temp_userid'];
        $diagnosis = str_replace("'", "\'", $diag);


        $strSQL = "INSERT INTO seg_encounter_diagnosis(encounter_nr,code,description,code_alt,is_deleted,modify_id,modify_time,create_id,create_time) ".
        		  "VALUES ('".$encnr."','".$code."','".$diagnosis."', '".$alt_code."','0','$createID',NOW(),'$createID',NOW())";
        if ($db->Execute($strSQL)) {
            if ($db->Affected_Rows()) {
                return true;
            }
        }else{
            	return false;
        }
    }
    //added by Francis 04/26/2013


/*-------------------------IPD--------------------------------------*/

function populateProcedureList($encNr,$billFrmDate,$billDate) {
	global $db;
	$objResponse = new xajaxResponse();
	$enc_obj = new Encounter;
	$srv = new SegOps;

	$opInfo = $srv->SearchCurrentOP($encNr, $billFrmDate, $billDate);
	$objResponse->addScriptCall("clearProcList");

	if ($opInfo) {
		$rows=$opInfo->RecordCount();
		while($result=$opInfo->FetchRow()) {
				$description_short = $result["description"];

		if (strlen($description_short)>50)
			$description_short = substr(trim($result["description"]),0,50)."...";

			$charge = $result["multiplier"] * $result["rvu"];

			$data->code = trim($result["code"]);
			$data->opDate = strftime("%m-%d-%Y", strtotime($result["op_date"]));
			$data->opDesc = ($result["alt_desc"] != null) ? trim($result["alt_desc"]) : trim($result["description"]);
			$data->opSDesc = trim($description_short);
			$data->opRVU = $result["rvu"];
			$data->opMultiplier = $result["multiplier"];
			$data->opProvider = $result["provider"];
			$data->opRefno = $result["refno"];
			$data->opEntry = $result["entry_no"];
			$data->opCount = $result["op_count"];
			$data->target = "ProcedureList-body";
			$data->opEncNr = $encNr;
			$data->charge = $charge;

		$objResponse->addScriptCall("addProcedureToList",$data);

		}#end of while
	}#end of if

	// $objResponse->addScriptCall("addProcedureToList",$data);

	return $objResponse;
}

function deleteProcedure($details){
	global $db;
	$objResponse = new xajaxResponse();
	$enc_obj = new Encounter;
	$srv = new SegOps;

	$enc_nr = $details['enc'];
	$bill_dt = $details['bdate'];
	$bill_frmdte = $details['fdate'];
	$op_code = $details['code'];

	$parent_encnr = getParentEncounterNr($enc_nr);

	if ($parent_encnr != '')
			$encounter = "('$parent_encnr','$enc_nr')";
	else
			$encounter = "('$enc_nr')";

	$delProc = $srv->delProcedure($encounter,$bill_dt, $bill_frmdte, $op_code);

	if($delProc){
		$objResponse->addScriptCall("rmvProcRow",$op_code);
	}else{
		$objResponse->alert("No procedure was deleted!");
	}

	return $objResponse;

}

function getParentEncounterNr($enc_nr) {
	global $db;

	$parent_encnr = '';
	$strSQL = "select parent_encounter_nr
							from care_encounter
							where encounter_nr = '$enc_nr'";
	if ($result = $db->Execute($strSQL)) {
		if ($result->RecordCount()) {
			$row = $result->FetchRow();
			$parent_encnr = $row['parent_encounter_nr'];
		}
	}
	return $parent_encnr;
}

function addProcedure($details){
	global $db;
	$objResponse = new xajaxResponse();
	$enc_obj = new Encounter;
	$srv = new SegOps;

	if (strcmp($bill_dt, "0000-00-00 00:00:00") != 0)
		$tmp_dte = $details['bDate'];
	else
		$tmp_dte = strftime("%Y-%m-%d %H:%M:%S");

	$tmp_dte = strftime("%Y-%m-%d %H:%M:%S", strtotime("-1 second", strtotime($tmp_dte)));

	$opDate = strftime("%Y-%m-%d", strtotime($details['opDate']));

	$procData = array('encNr'=>$details['encNr'],
					  'billDate'=>$tmp_dte,
					  'code'=>$details['code'],
					  'opDate'=>$opDate,
					  'user'=>$details['user'],
					  'rvu'=>$details['rvu'],
					  'multiplier'=>$details['multiplier'],
					  'charge'=>$details['charge'],
					  'laterality'=>$details['laterality'],
					  'num_sessions' => $details['sess_num'],
					  'special_dates' => $details['special_dates'],
					  'desc' => $details['desc']
				);

	$addResult = $srv->addProcedure($procData);
	$refno = $srv->getMiscOpRefNo($procData['billDate'], $procData['encNr']);

	if($addResult){
		$tmpOpDate = strftime("%m-%d-%Y", strtotime($opDate));

		$data->target = "ProcedureList-body";
		$data->code = $details['code'];
		$data->opDesc = $details['desc'];
		$data->opDate = $tmpOpDate;
		$data->opRVU = $details['rvu'];
		$data->opMultiplier = $details['multiplier'];
		$data->charge = $details['charge'];
		$data->laterality = $details['laterality'];
		$data->opRefno = $refno; // added by Nick, 3/6/2014
		$data->opCount = 1;

		$objResponse->addScriptCall("addProcedureToList",$data,true);
	}else{
		$objResponse->alert("Failed to add procedure !");
	}

	// $objResponse->alert($opDate);
	return $objResponse;

}

//added by Nick, 3/1/2014
function updateIcdCode($enc_nr, $code, $altcode, $user_id){
	$objMedocs = new Medocs;
	$objResponse = new xajaxResponse();

	$data = array($altcode,$user_id,$enc_nr,$code);

	$update_cnt = $objMedocs->updateIcdAltCode($data);

	if($update_cnt > 0 ){
		$objResponse->alert("Alt code updated!");
	}else if($update_cnt <= 0){
		$objResponse->alert("No Alt code updated!");
	}else if(!$update_cnt){
		$objResponse->alert("Failed to update Alt code!");
	}

	return $objResponse;
}

//added by Nick, 3/1/2014
function updateIcdDesc($enc_nr, $code, $desc, $user_id){
	$objMedocs = new Medocs;
	$objResponse = new xajaxResponse();

	$data = array($desc,$user_id,$enc_nr,$code);

	$update_cnt = $objMedocs->updateIcdAltDesc($data);

	if($update_cnt > 0 ){
		$objResponse->alert("Alt description updated!");
	}else if($update_cnt <= 0){
		$objResponse->alert("No Alt description updated!");
	}else if(!$update_cnt){
		$objResponse->alert("Failed to update Alt description!");
	}

	return $objResponse;
}

//added by Nick, 3/4/2014
function updateIcpDesc($refno, $code, $desc){
	$objMedocs = new Medocs;
	$objResponse = new xajaxResponse();

	$data = array($desc,$refno,$code);

	$rs = $objMedocs->updateIcpAltDesc($data);

	if($rs){
		if($rs > 0){
			$objResponse->alert('Description updated');
		}else{
			$objResponse->alert('No description updated');
		}
	}else{
		$objResponse->alert('Failed to update description');
	}

	return $objResponse;
}

//added by Nick, 4/15/2014
function updateIcdSequence($encounter_nr,$icd_list){
	$objMedocs = new Medocs;
	$objResponse = new xajaxResponse();
	$rs = $objMedocs->updateIcdSequence($encounter_nr,$icd_list);
	$objResponse->alert("Sequence Updated!");
	return $objResponse;
}

function saveFinalDiagnosis($case_nr, $icd_code, $final_diag) {

	global $db;
	$objResponse = new xajaxResponse();

		$sql = 'SELECT * FROM seg_soa_diagnosis WHERE encounter_nr = '.$db->qstr($case_nr);
		$result = $db->Execute($sql);

		$data = $result->FetchRow();
		$latest_code = $data['code'];

		$code_condition = $latest_code !== $icd_code ? "code = '".$icd_code."'," : '';

		if( $result->RecordCount() > 0 ) {

			$updateSQL = " UPDATE 
						  seg_soa_diagnosis
						SET
						 final_diagnosis = ".$db->qstr($final_diag).",
						 $code_condition
						 modify_date = NOW(),
						 modify_id = ".$db->qstr($_SESSION['sess_login_username'])."
						WHERE encounter_nr = ".$db->qstr($case_nr);

			$result = $db->Execute($updateSQL);

				if($result) {
					$objResponse->alert('Update Save');
				}else{
					$objResponse->alert('Update Fail');
				}

		} else {

			$sqlInsert = "INSERT INTO seg_soa_diagnosis(encounter_nr,code,final_diagnosis,create_id,create_date) ".
        		  "VALUES ( 
        		  '".$case_nr."',
        		  '".$icd_code."',
        		  '".$final_diag."', 
        		  '".$_SESSION['sess_login_username']."',
        		  NOW() 
        		)";
			$result = $db->Execute($sqlInsert);

				if($result) {
					$objResponse->alert('Insert Save');
				}else{
					$objResponse->alert('Insert Fail');
				}

		}

	return $objResponse;
}

/*-------------------------IPD end----------------------------------*/

require('./roots.php');
require($root_path.'include/inc_environment_global.php');
require($root_path."modules/billing_new/ajax/icd_icp.common.php");
#added by VAN 04-17-08
require_once($root_path.'include/care_api_classes/class_globalconfig.php');

require_once($root_path.'include/care_api_classes/billing/class_ops_new.php');
require_once($root_path.'include/care_api_classes/class_encounter.php');
require_once($root_path.'include/care_api_classes/class_ward.php');
require_once($root_path.'include/care_api_classes/class_department.php');
require_once($root_path.'include/care_api_classes/class_personell.php');
require($root_path.'include/care_api_classes/class_medocs.php');
require($root_path.'include/care_api_classes/class_icd10.php');
require($root_path.'include/care_api_classes/class_caserate_icd_icp.php');


$xajax->processRequests();
?>
