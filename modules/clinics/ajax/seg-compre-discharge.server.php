<?php

require('./roots.php');
require($root_path.'include/inc_environment_global.php');
require_once($root_path.'modules/clinics/ajax/seg-compre-discharge.common.php');
require_once($root_path.'include/care_api_classes/class_compre_discharge.php');

function convertEncoding($string){
	return mb_convert_encoding($string,"ISO-8859-1", mb_detect_encoding($string, "UTF-8, ISO-8859-1, ISO-8859-15", true));
}

function saveCompre($details){
	$objResponse = new xajaxResponse();
	$compreObj = new Compre_discharge();

	foreach($details as $row=>$key){
		$details[$row] = convertEncoding($key);
	}

	$compreObj->details = $details;

	//update vital signs
	if($details['vitalsign_no']){
		$result1 = $compreObj->updateVitals();
	}else{
		$result1 = $compreObj->insertVitals();
	}
	//add comprehensive report
	$result2 = $compreObj->addCompre();
	//update complaint
	$result3 = $compreObj->updateComplaint();

	if($result1 && $result2 && $result3){
		$objResponse->alert('Successfully updated comprehensive report');
		$objResponse->call('refreshPage');
		$objResponse->call('scrollTop');
	}else{
		$objResponse->alert('Unable to update comprehensive report');
	}

	return $objResponse;
}

function savePE($details){
	$objResponse = new xajaxResponse();
	$compreObj = new Compre_discharge();
	$compreObj->details = $details;

	if($details['vitalsign_no']){
		$result1 = $compreObj->updateSomeVitals();
	}else{
		$result1 = $compreObj->insertVitals();
	}
	
	$result2 = $compreObj->addPE();

	if($result1 && $result2){
		$objResponse->alert('Successfully updated PE report');
		$objResponse->call('refreshPage');
		$objResponse->call('scrollTop');
	}else{
		$objResponse->alert('Unable to update PE report');
	}

	return $objResponse;
}

function saveDischrgInfo($details){
	$objResponse = new xajaxResponse();
	$discObj = new Compre_discharge();
	
	foreach($details as $row=>$key){
		if($row != 'icd'){
			$details[$row] = convertEncoding($key);
		}
	}

	$discObj->details = $details;

	//add discharge info
	$result1 = $discObj->addDischargeInfo();

	//add diagnosis
	if($discObj->removeDiagnosis()){
		$result2 = $discObj->saveDiagnosis();
	}
	
	if($result1 && $result2){
		$objResponse->alert('Successfully updated discharge information');
		$objResponse->call('refreshPage');
		$objResponse->call('scrollTop');
	}else{
		$objResponse->alert('Unable to update discharge information');
	}

	return $objResponse;
}


function searchICD($filter){
	$objResponse = new xajaxResponse();
	$discObj = new Compre_discharge();
	
	$result = $discObj->getSelectedICDDesc($filter);

	$objResponse->assign('searchResults', 'innerHTML', '');
	if($result){
		
		while($row = $result->FetchRow()){
			$div .= '<tr class="icd" onclick="addDiagnosis('."'".addslashes($row['diagnosis_code'])."', '".addslashes($row['description'])."'".'); hideSearchResults(); emptySearchBox();"><td>'.$row['diagnosis_code'].'</td><td>'.$row['description'].'</td></tr>';
		}

		$objResponse->assign('searchResults', 'style.display', 'inline');
		$objResponse->assign('searchResults', 'innerHTML', '<table width = "100%">'.$div.'</table>');
	}

	return $objResponse;
}

function getICD($details){
	$objResponse = new xajaxResponse();
	$discObj = new Compre_discharge();
	
	$discObj->details = $details;
	$result = $discObj->getICD();

	if($result){
		while($row = $result->FetchRow()){
			$objResponse->call('addDiagnosis', $row['diagnosis_code'], $row['description']);
		}
	}

	return $objResponse;
}

function getImages($details){
	$objResponse = new xajaxResponse();
	$discObj = new Compre_discharge();

	$discObj->details = $details;
	$result = $discObj->getImages();

	while($row = $result->FetchRow()){
		$objResponse->call('appendImages', $row['filename']);
	}

	return $objResponse;
}

function removeImage($details){
	$objResponse = new xajaxResponse();
	$discObj = new Compre_discharge();

	$discObj->details = $details;
	
	if($discObj->removeImage()){
		$objResponse->call('getImages');
	}else{
		$objResponse->alert("Error: Unable to delete image.");
	}

	return $objResponse;
}

$xajax->processRequest();
?>