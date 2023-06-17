<?php
/*created by mai 08-19-2014*/
require('./roots.php');
require($root_path.'include/inc_environment_global.php');
require($root_path.'modules/clinics/ajax/seg_pf_request.common.php');
require_once($root_path.'include/care_api_classes/class_company.php');
require_once($root_path.'include/care_api_classes/class_personell.php');
require_once($root_path.'include/care_api_classes/class_pf_charge.php');

	function getChargeCompanyBalance($encounter_nr, $trans_source, $refno){ 
			$company = new Company();
			$objResponse = new xajaxResponse();

			if($refno){
				$balance=$company->getchargeBalance($encounter_nr, $trans_source, $refno);
			}else{
				$balance=$company->getchargeBalance($encounter_nr, $trans_source);
			}
			
			if($balance){
				$objResponse->assign('charge_comp_balance', 'value', $balance);
				$objResponse->addScriptCall('chargeToCompany');
			}else{
				$objResponse->assign('charge_comp_balance', 'value', 0);
			}

			return $objResponse;

	}

	function getDoctors($dept_nr='', $dr_name =''){
		$objResponse = new xajaxResponse();
		$pers_obj=new Personell;

		$objResponse->addScriptCall("clearList");

		if(!$dr_name){
			$rs=$pers_obj->getDoctorsOfDept($dept_nr);
		}else{
			$rs=$pers_obj->getDoctorWithDept($dept_nr, $dr_name);	
		} 
		
		if ($rs) {
			while ($result=$rs->FetchRow()) {
				if (trim($result["name_middle"]))
					$dot  = ".";

				$doctor_name = trim($result["name_last"]).", ".trim($result["name_first"])." ".substr(trim($result["name_middle"]),0,1).$dot;

				$doctor_name = ucwords(strtolower($doctor_name)).", MD";
				$doctor_name = htmlspecialchars($doctor_name);
				
				$objResponse->addScriptCall("listDoctor", $result["personell_nr"], $doctor_name);
			}
		}
		else {
			$objResponse->addScriptCall("listDoctor",0,0);
		}
		
		return $objResponse;
	}

	function trimDocName($name_last, $name_first, $name_middle){
		if (trim($name_middle))
		$dot  = ".";
			
		$doctor_name = trim($name_last).", ".trim($name_first)." ".substr(trim($name_middle),0,1).$dot;

		$doctor_name = ucwords(strtolower($doctor_name)).", MD";
		$doctor_name = htmlspecialchars($doctor_name);

		return $doctor_name;
	}

	function addDoctor($nr){
		$objResponse = new xajaxResponse();
		$pers_obj = new Personell;

		$result = $pers_obj->getPersonellInfo($nr);

		if($result){
				if (trim($result["name_middle"]))
					$dot  = ".";
			
				$doctor_name = trim($result["name_last"]).", ".trim($result["name_first"])." ".substr(trim($result["name_middle"]),0,1).$dot;

				$doctor_name = ucwords(strtolower($doctor_name)).", MD";
				$doctor_name = htmlspecialchars($doctor_name);
				
				$objResponse->addScriptCall("prepareAdd", $result["short_id"], $doctor_name);
		}else{
			$objResponse->addAlert("Unable to add doctor");
		}

		return $objResponse;
	}

	function getPf($refno){
		$objResponse = new xajaxResponse();
		$pf_obj = new Pf_charge();

		if($result = $pf_obj->getPfByRefno($refno)){
			while($pf = $result->FetchRow()){
				$details[] = array('doctor_nr'=>$pf["dr_nr"],
								'fee'=>$pf["chrg_amount"],
								'request_flag'=>(is_null($pf["request_flag"]) ? 'notpaid' : 'paid'),
								'dr_name'=>trimDocName($pf["name_last"], $pf["name_first"], $pf["name_middle"]));
			}

			$objResponse->addScriptCall("addPfFromRef", $details);
			$objResponse->addScriptCall("update_total_misc");
		}else{
			$objResponse->addAlert("Unable to fetch previous data");
		}

		return $objResponse;
	}

	$xajax->processRequests();
?>