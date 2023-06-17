<?php
//created by EJ 08/28/2014
require('./roots.php');
include_once($root_path . 'include/care_api_classes/reports/JasperReport.php');
require_once($root_path.'include/care_api_classes/class_hospital_admin.php');
require_once($root_path.'include/care_api_classes/class_encounter.php');
require_once($root_path.'include/care_api_classes/class_dental.php');
require_once($root_path.'include/inc_environment_global.php');

global $db;

$encounter_nr = $_GET['encounter_nr'];

$jasper = new JasperReport();
$objDental = new Dental();
$objHosp = new Hospital_Admin();
$objEnc = new Encounter();

$pData = $objEnc->getEncounterInfo($encounter_nr);
$hospInfo = $objHosp->getAllHospitalInfo();
$medicalHisto = $objDental->selectMedicalHistory($encounter_nr);

//teeth
$selectTeeth = $objDental->selectTeeth($encounter_nr);
$teethData = array();

while($row = $selectTeeth->FetchRow()){
   $teethData[$row['tooth_no']] = array('con'=>$row['con'], 'ops'=>$row['ops']);	
}

//tooth
$toothParts = $objDental->getDentalTooth($encounter_nr);
$toothData = array();

while($row = $toothParts->FetchRow()){
   $toothData[$row['tooth_no']] = array('_0'=>$row['tooth_0'],
   										'_1'=>$row['tooth_1'],
   										'_2'=>$row['tooth_2'],
   										'_3'=>$row['tooth_3'],
   										'_4'=>$row['tooth_4']);
}

$data = array('name'=>'');

$jasper->setParams(array(
	//Hospital Info
	'hosp_name'=> $hospInfo['hosp_name'],
	'hosp_add'=> $hospInfo['hosp_addr1'],
	'report_name' => 'DENTAL RECORD',
	
	//Patient Info
	'patient_name'=>$pData['name_last'].', '.$pData['name_first'],
	'address'=> $pData['street_name']." ".$pData["brgy_name"]." ".$pData["mun_name"],
	'gender'=> ($pData['sex']=='m') ? 'Male' : 'Female',
	'contact'=>$pData['contact_no'],
	'age'=>$pData['age'],
	'date_birth'=>date_format(date_create($pData['date_birth']),'M d, Y'),

	//Physical and Medical History
	'tongue'=> $medicalHisto['tongue'] ? $medicalHisto['tongue'] : "",
	'palate'=> $medicalHisto['palate'] ? $medicalHisto['palate'] : "",
	'tonsils'=> $medicalHisto['tonsils'] ? $medicalHisto['tonsils'] : "",
	'lips'=> $medicalHisto['lips'] ? $medicalHisto['lips'] : "",
	'floor_mouth'=> $medicalHisto['floor_of_mouth'] ? $medicalHisto['floor_of_mouth'] : "",
	'cheeks'=> $medicalHisto['cheeks'] ? $medicalHisto['cheeks'] : "",
	'allergies'=> $medicalHisto['allergies'] ? $medicalHisto['allergies'] : "",
	'heart_disease'=> $medicalHisto['heart_disease'] ? $medicalHisto['heart_disease'] : "",
	'blood_dys'=> $medicalHisto['blood_dyscracia'] ? $medicalHisto['blood_dyscracia'] : "",
	'diabetes'=> $medicalHisto['diabetes'] ? $medicalHisto['diabetes'] : "",
	'kidney'=> $medicalHisto['kidney'] ? $medicalHisto['kidney'] : "",
	'liver'=> $medicalHisto['liver'] ? $medicalHisto['liver'] : "",
	'others'=> $medicalHisto['others'] ? $medicalHisto['others'] : "",
	'hygiene' => $medicalHisto['hygiene'] ? $medicalHisto['hygiene'] : "",

	//Treatment Record
	'date' => date_format(date_create($medicalHisto['create_dt']), 'M d, Y'),
	'diagnosis' => $medicalHisto['diagnosis'] ? $medicalHisto['diagnosis'] : "",
	'tooth_count' => $medicalHisto['tooth_count'] ? $medicalHisto['tooth_count'] : "",
	'service_rendered' => $medicalHisto['services'] ? $medicalHisto['services'] : "",
	'operator'=> $medicalHisto['operator'] ? $medicalHisto['operator'] : "",
	'checked_by'=> $medicalHisto['checked_by'] ? $medicalHisto['checked_by'] : "",

	//Operation and Condition
	'_18C'=>$teethData['18']['con'] ? $teethData['18']['con'] : "",
	'_17C'=>$teethData['17']['con'] ? $teethData['17']['con'] : "",
	'_16C'=>$teethData['16']['con'] ? $teethData['16']['con'] : "",
	'_15C'=>$teethData['15']['con'] ? $teethData['15']['con'] : "",
	'_14C'=>$teethData['14']['con'] ? $teethData['14']['con'] : "",
	'_13C'=>$teethData['13']['con'] ? $teethData['13']['con'] : "",
	'_12C'=>$teethData['12']['con'] ? $teethData['12']['con'] : "",
	'_11C'=>$teethData['11']['con'] ? $teethData['11']['con'] : "",
	'_21C'=>$teethData['21']['con'] ? $teethData['21']['con'] : "",
	'_22C'=>$teethData['22']['con'] ? $teethData['22']['con'] : "",
	'_23C'=>$teethData['23']['con'] ? $teethData['23']['con'] : "",
	'_24C'=>$teethData['24']['con'] ? $teethData['24']['con'] : "",
	'_25C'=>$teethData['25']['con'] ? $teethData['25']['con'] : "",
	'_26C'=>$teethData['26']['con'] ? $teethData['26']['con'] : "",
	'_27C'=>$teethData['27']['con'] ? $teethData['27']['con'] : "",
	'_28C'=>$teethData['28']['con'] ? $teethData['28']['con'] : "",
	'_48C'=>$teethData['48']['con'] ? $teethData['48']['con'] : "",
	'_47C'=>$teethData['47']['con'] ? $teethData['47']['con'] : "",
	'_46C'=>$teethData['46']['con'] ? $teethData['46']['con'] : "",
	'_45C'=>$teethData['45']['con'] ? $teethData['45']['con'] : "",
	'_44C'=>$teethData['44']['con'] ? $teethData['44']['con'] : "",
	'_43C'=>$teethData['43']['con'] ? $teethData['43']['con'] : "",
	'_42C'=>$teethData['42']['con'] ? $teethData['42']['con'] : "",
	'_41C'=>$teethData['41']['con'] ? $teethData['41']['con'] : "",
	'_31C'=>$teethData['31']['con'] ? $teethData['31']['con'] : "",
	'_32C'=>$teethData['32']['con'] ? $teethData['32']['con'] : "",
	'_33C'=>$teethData['33']['con'] ? $teethData['33']['con'] : "",
	'_34C'=>$teethData['34']['con'] ? $teethData['34']['con'] : "",
	'_35C'=>$teethData['35']['con'] ? $teethData['35']['con'] : "",
	'_36C'=>$teethData['36']['con'] ? $teethData['36']['con'] : "",
	'_37C'=>$teethData['37']['con'] ? $teethData['37']['con'] : "",
	'_38C'=>$teethData['38']['con'] ? $teethData['38']['con'] : "",

	'_18O'=>$teethData['18']['ops'] ? $teethData['18']['ops'] : "",
	'_17O'=>$teethData['17']['ops'] ? $teethData['17']['ops'] : "",
	'_16O'=>$teethData['16']['ops'] ? $teethData['16']['ops'] : "",
	'_15O'=>$teethData['15']['ops'] ? $teethData['15']['ops'] : "",
	'_14O'=>$teethData['14']['ops'] ? $teethData['14']['ops'] : "",
	'_13O'=>$teethData['13']['ops'] ? $teethData['13']['ops'] : "",
	'_12O'=>$teethData['12']['ops'] ? $teethData['12']['ops'] : "",
	'_11O'=>$teethData['11']['ops'] ? $teethData['11']['ops'] : "",
	'_21O'=>$teethData['21']['ops'] ? $teethData['21']['ops'] : "",
	'_22O'=>$teethData['22']['ops'] ? $teethData['22']['ops'] : "",
	'_23O'=>$teethData['23']['ops'] ? $teethData['23']['ops'] : "",
	'_24O'=>$teethData['24']['ops'] ? $teethData['24']['ops'] : "",
	'_25O'=>$teethData['25']['ops'] ? $teethData['25']['ops'] : "",
	'_26O'=>$teethData['26']['ops'] ? $teethData['26']['ops'] : "",
	'_27O'=>$teethData['27']['ops'] ? $teethData['27']['ops'] : "",
	'_28O'=>$teethData['28']['ops'] ? $teethData['28']['ops'] : "",
	'_48O'=>$teethData['48']['ops'] ? $teethData['48']['ops'] : "",
	'_47O'=>$teethData['47']['ops'] ? $teethData['47']['ops'] : "",
	'_46O'=>$teethData['46']['ops'] ? $teethData['46']['ops'] : "",
	'_45O'=>$teethData['45']['ops'] ? $teethData['45']['ops'] : "",
	'_44O'=>$teethData['44']['ops'] ? $teethData['44']['ops'] : "",
	'_43O'=>$teethData['43']['ops'] ? $teethData['43']['ops'] : "",
	'_42O'=>$teethData['42']['ops'] ? $teethData['42']['ops'] : "",
	'_41O'=>$teethData['41']['ops'] ? $teethData['41']['ops'] : "",
	'_31O'=>$teethData['31']['ops'] ? $teethData['31']['ops'] : "",
	'_32O'=>$teethData['32']['ops'] ? $teethData['32']['ops'] : "",
	'_33O'=>$teethData['33']['ops'] ? $teethData['33']['ops'] : "",
	'_34O'=>$teethData['34']['ops'] ? $teethData['34']['ops'] : "",
	'_35O'=>$teethData['35']['ops'] ? $teethData['35']['ops'] : "",
	'_36O'=>$teethData['36']['ops'] ? $teethData['36']['ops'] : "",
	'_37O'=>$teethData['37']['ops'] ? $teethData['37']['ops'] : "",
	'_38O'=>$teethData['38']['ops'] ? $teethData['38']['ops'] : "",

	//toot_no
	't0_18'=> $toothData['18']['_0'] ? "o" : "",
	't1_18'=> $toothData['18']['_1'] ? "o" : "",
	't2_18'=> $toothData['18']['_2'] ? "o" : "",
	't3_18'=> $toothData['18']['_3'] ? "o" : "",
	't4_18'=> $toothData['18']['_4'] ? "o" : "",

	't0_17'=> $toothData['17']['_0'] ? "o" : "",
	't1_17'=> $toothData['17']['_1'] ? "o" : "",
	't2_17'=> $toothData['17']['_2'] ? "o" : "",
	't3_17'=> $toothData['17']['_3'] ? "o" : "",
	't4_17'=> $toothData['17']['_4'] ? "o" : "",

	't0_16'=> $toothData['16']['_0'] ? "o" : "",
	't1_16'=> $toothData['16']['_1'] ? "o" : "",
	't2_16'=> $toothData['16']['_2'] ? "o" : "",
	't3_16'=> $toothData['16']['_3'] ? "o" : "",
	't4_16'=> $toothData['16']['_4'] ? "o" : "",

	't0_15'=> $toothData['15']['_0'] ? "o" : "",
	't1_15'=> $toothData['15']['_1'] ? "o" : "",
	't2_15'=> $toothData['15']['_2'] ? "o" : "",
	't3_15'=> $toothData['15']['_3'] ? "o" : "",
	't4_15'=> $toothData['15']['_4'] ? "o" : "",

	't0_14'=> $toothData['14']['_0'] ? "o" : "",
	't1_14'=> $toothData['14']['_1'] ? "o" : "",
	't2_14'=> $toothData['14']['_2'] ? "o" : "",
	't3_14'=> $toothData['14']['_3'] ? "o" : "",
	't4_14'=> $toothData['14']['_4'] ? "o" : "",

	't0_13'=> $toothData['13']['_0'] ? "o" : "",
	't1_13'=> $toothData['13']['_1'] ? "o" : "",
	't2_13'=> $toothData['13']['_2'] ? "o" : "",
	't3_13'=> $toothData['13']['_3'] ? "o" : "",
	't4_13'=> $toothData['13']['_4'] ? "o" : "",

	't0_12'=> $toothData['12']['_0'] ? "o" : "",
	't1_12'=> $toothData['12']['_1'] ? "o" : "",
	't2_12'=> $toothData['12']['_2'] ? "o" : "",
	't3_12'=> $toothData['12']['_3'] ? "o" : "",
	't4_12'=> $toothData['12']['_4'] ? "o" : "",

	't0_11'=> $toothData['11']['_0'] ? "o" : "",
	't1_11'=> $toothData['11']['_1'] ? "o" : "",
	't2_11'=> $toothData['11']['_2'] ? "o" : "",
	't3_11'=> $toothData['11']['_3'] ? "o" : "",
	't4_11'=> $toothData['11']['_4'] ? "o" : "",
	
	't0_21'=> $toothData['21']['_0'] ? "o" : "",
	't1_21'=> $toothData['21']['_1'] ? "o" : "",
	't2_21'=> $toothData['21']['_2'] ? "o" : "",
	't3_21'=> $toothData['21']['_3'] ? "o" : "",
	't4_21'=> $toothData['21']['_4'] ? "o" : "",

	't0_22'=> $toothData['22']['_0'] ? "o" : "",
	't1_22'=> $toothData['22']['_1'] ? "o" : "",
	't2_22'=> $toothData['22']['_2'] ? "o" : "",
	't3_22'=> $toothData['22']['_3'] ? "o" : "",
	't4_22'=> $toothData['22']['_4'] ? "o" : "",

	't0_23'=> $toothData['23']['_0'] ? "o" : "",
	't1_23'=> $toothData['23']['_1'] ? "o" : "",
	't2_23'=> $toothData['23']['_2'] ? "o" : "",
	't3_23'=> $toothData['23']['_3'] ? "o" : "",
	't4_23'=> $toothData['23']['_4'] ? "o" : "",

	't0_24'=> $toothData['21']['_0'] ? "o" : "",
	't1_24'=> $toothData['24']['_1'] ? "o" : "",
	't2_24'=> $toothData['24']['_2'] ? "o" : "",
	't3_24'=> $toothData['24']['_3'] ? "o" : "",
	't4_24'=> $toothData['24']['_4'] ? "o" : "",

	't0_25'=> $toothData['25']['_0'] ? "o" : "",
	't1_25'=> $toothData['25']['_1'] ? "o" : "",
	't2_25'=> $toothData['25']['_2'] ? "o" : "",
	't3_25'=> $toothData['25']['_3'] ? "o" : "",
	't4_25'=> $toothData['25']['_4'] ? "o" : "",

	't0_26'=> $toothData['26']['_0'] ? "o" : "",
	't1_26'=> $toothData['26']['_1'] ? "o" : "",
	't2_26'=> $toothData['26']['_2'] ? "o" : "",
	't3_26'=> $toothData['26']['_3'] ? "o" : "",
	't4_26'=> $toothData['26']['_4'] ? "o" : "",

	't0_27'=> $toothData['27']['_0'] ? "o" : "",
	't1_27'=> $toothData['27']['_1'] ? "o" : "",
	't2_27'=> $toothData['27']['_2'] ? "o" : "",
	't3_27'=> $toothData['27']['_3'] ? "o" : "",
	't4_27'=> $toothData['27']['_4'] ? "o" : "",

	't0_28'=> $toothData['28']['_0'] ? "o" : "",
	't1_28'=> $toothData['28']['_1'] ? "o" : "",
	't2_28'=> $toothData['28']['_2'] ? "o" : "",
	't3_28'=> $toothData['28']['_3'] ? "o" : "",
	't4_28'=> $toothData['28']['_4'] ? "o" : "",

	't0_48'=> $toothData['48']['_0'] ? "o" : "",
	't1_48'=> $toothData['48']['_1'] ? "o" : "",
	't2_48'=> $toothData['48']['_2'] ? "o" : "",
	't3_48'=> $toothData['48']['_3'] ? "o" : "",
	't4_48'=> $toothData['48']['_4'] ? "o" : "",

	't0_47'=> $toothData['47']['_0'] ? "o" : "",
	't1_47'=> $toothData['47']['_1'] ? "o" : "",
	't2_47'=> $toothData['47']['_2'] ? "o" : "",
	't3_47'=> $toothData['47']['_3'] ? "o" : "",
	't4_47'=> $toothData['47']['_4'] ? "o" : "",

	't0_46'=> $toothData['46']['_0'] ? "o" : "",
	't1_46'=> $toothData['46']['_1'] ? "o" : "",
	't2_46'=> $toothData['46']['_2'] ? "o" : "",
	't3_46'=> $toothData['46']['_3'] ? "o" : "",
	't4_46'=> $toothData['46']['_4'] ? "o" : "",

	't0_45'=> $toothData['45']['_0'] ? "o" : "",
	't1_45'=> $toothData['45']['_1'] ? "o" : "",
	't2_45'=> $toothData['45']['_2'] ? "o" : "",
	't3_45'=> $toothData['45']['_3'] ? "o" : "",
	't4_45'=> $toothData['45']['_4'] ? "o" : "",

	't0_44'=> $toothData['44']['_0'] ? "o" : "",
	't1_44'=> $toothData['44']['_1'] ? "o" : "",
	't2_44'=> $toothData['44']['_2'] ? "o" : "",
	't3_44'=> $toothData['44']['_3'] ? "o" : "",
	't4_44'=> $toothData['44']['_4'] ? "o" : "",

	't0_43'=> $toothData['43']['_0'] ? "o" : "",
	't1_43'=> $toothData['43']['_1'] ? "o" : "",
	't2_43'=> $toothData['43']['_2'] ? "o" : "",
	't3_43'=> $toothData['43']['_3'] ? "o" : "",
	't4_43'=> $toothData['43']['_4'] ? "o" : "",

	't0_42'=> $toothData['42']['_0'] ? "o" : "",
	't1_42'=> $toothData['42']['_1'] ? "o" : "",
	't2_42'=> $toothData['42']['_2'] ? "o" : "",
	't3_42'=> $toothData['42']['_3'] ? "o" : "",
	't4_42'=> $toothData['42']['_4'] ? "o" : "",

	't0_41'=> $toothData['41']['_0'] ? "o" : "",
	't1_41'=> $toothData['41']['_1'] ? "o" : "",
	't2_41'=> $toothData['41']['_2'] ? "o" : "",
	't3_41'=> $toothData['41']['_3'] ? "o" : "",
	't4_41'=> $toothData['41']['_4'] ? "o" : "",

	't0_31'=> $toothData['31']['_0'] ? "o" : "",
	't1_31'=> $toothData['31']['_1'] ? "o" : "",
	't2_31'=> $toothData['31']['_2'] ? "o" : "",
	't3_31'=> $toothData['31']['_3'] ? "o" : "",
	't4_31'=> $toothData['31']['_4'] ? "o" : "",

	't0_32'=> $toothData['32']['_0'] ? "o" : "",
	't1_32'=> $toothData['32']['_1'] ? "o" : "",
	't2_32'=> $toothData['32']['_2'] ? "o" : "",
	't3_32'=> $toothData['32']['_3'] ? "o" : "",
	't4_32'=> $toothData['32']['_4'] ? "o" : "",

	't0_33'=> $toothData['33']['_0'] ? "o" : "",
	't1_33'=> $toothData['33']['_1'] ? "o" : "",
	't2_33'=> $toothData['33']['_2'] ? "o" : "",
	't3_33'=> $toothData['33']['_3'] ? "o" : "",
	't4_33'=> $toothData['33']['_4'] ? "o" : "",

	't0_34'=> $toothData['34']['_0'] ? "o" : "",
	't1_34'=> $toothData['34']['_1'] ? "o" : "",
	't2_34'=> $toothData['34']['_2'] ? "o" : "",
	't3_34'=> $toothData['34']['_3'] ? "o" : "",
	't4_34'=> $toothData['34']['_4'] ? "o" : "",

	't0_35'=> $toothData['35']['_0'] ? "o" : "",
	't1_35'=> $toothData['35']['_1'] ? "o" : "",
	't2_35'=> $toothData['35']['_2'] ? "o" : "",
	't3_35'=> $toothData['35']['_3'] ? "o" : "",
	't4_35'=> $toothData['35']['_4'] ? "o" : "",

	't0_36'=> $toothData['36']['_0'] ? "o" : "",
	't1_36'=> $toothData['36']['_1'] ? "o" : "",
	't2_36'=> $toothData['36']['_2'] ? "o" : "",
	't3_36'=> $toothData['36']['_3'] ? "o" : "",
	't4_36'=> $toothData['36']['_4'] ? "o" : "",

	't0_37'=> $toothData['37']['_0'] ? "o" : "",
	't1_37'=> $toothData['37']['_1'] ? "o" : "",
	't2_37'=> $toothData['37']['_2'] ? "o" : "",
	't3_37'=> $toothData['37']['_3'] ? "o" : "",
	't4_37'=> $toothData['37']['_4'] ? "o" : "",

	't0_38'=> $toothData['38']['_0'] ? "o" : "",
	't1_38'=> $toothData['38']['_1'] ? "o" : "",
	't2_38'=> $toothData['38']['_2'] ? "o" : "",
	't3_38'=> $toothData['38']['_3'] ? "o" : "",
	't4_38'=> $toothData['38']['_4'] ? "o" : "",
));

$jasper->setData($data);

$jasper->setJrxmlFilePath('dental_record.jrxml');
$jasper->run();