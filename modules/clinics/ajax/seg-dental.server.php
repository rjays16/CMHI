<?php
require('./roots.php');
require($root_path.'include/inc_environment_global.php');
require_once($root_path.'modules/clinics/ajax/seg-dental.common.php');
require_once($root_path.'include/care_api_classes/class_dental.php');

function saveDental($data, $encounter_nr, $info, $tooth_parts){
	$objResponse = new xajaxResponse();
	$dental_obj = new Dental();

	$error = 0;

	if($dental_obj->selectMedicalHistory($encounter_nr)){
		$res = $dental_obj->updateMedicalHistory($info, $encounter_nr);
	}else{
		$res = $dental_obj->saveMedicalHistory($info, $encounter_nr);
	}

	if($res){
		if($dental_obj->removeTeeth($encounter_nr)){ //insert operations and conditions
			for($i=0; $i<count($data); $i++){
				if(!$dental_obj->insertOpsCond($data[$i])){
					$error = 1;
				}
			}

			if($dental_obj->deleteTooth($encounter_nr)){ //insert tooth parts
				for($i=0; $i<count($tooth_parts); $i++){
					if(!$dental_obj->insertDentalTooth($tooth_parts[$i])){
						$error = 1;
					}
				}
			}
		}	
	}else{
		$error = 1;
	}

	if($error){
		$objResponse->alert("Error saving operations and conditions");
	}else{
		$objResponse->alert("Successfully saved operations and conditions");
	}

	return $objResponse;
}

function getTeeth($encounter_nr){
	$objResponse = new xajaxResponse();
	$dental_obj = new Dental();

	$res = $dental_obj->selectTeeth($encounter_nr); 
	
	if($res){
		while($row = $res->FetchRow()){
			$data->tooth_no = $row['tooth_no'];
			$data->ops = $row['ops'];
			$data->con = $row['con']; 

			$objResponse->call("setTeeth", $data);
		}
	}

	$objResponse->call("getTooth");
	return $objResponse;	
}

function getToothParts($encounter_nr){
	$objResponse = new xajaxResponse();
	$dental_obj = new Dental();

	$res = $dental_obj->getDentalTooth($encounter_nr);

	if($res){
		while($row = $res->FetchRow()){
			$data->tooth_no = $row['tooth_no'];
			$data->zero = $row['tooth_0'];
			$data->one = $row['tooth_1'];
			$data->two = $row['tooth_2'];
			$data->three = $row['tooth_3'];
			$data->four = $row['tooth_4'];

			$objResponse->call("setToothParts", $data);
		}
	}

	return $objResponse;
}

$xajax->processRequest();
?>