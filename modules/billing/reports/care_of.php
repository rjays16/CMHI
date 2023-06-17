<?php
//created by Maimai 11/25/2014

require('./roots.php');
include_once($root_path . 'include/care_api_classes/reports/JasperReport.php');
require_once($root_path.'include/care_api_classes/class_company.php');
require_once($root_path.'include/care_api_classes/class_personell.php');
require_once($root_path.'include/care_api_classes/class_hospital_admin.php');
require_once($root_path.'include/inc_environment_global.php');

global $db;

$jasper = new JasperReport();
$objComp = new Company();
$objInfo = new Hospital_Admin();

$date_from = date('Y-m-d', strtotime($_GET['date_from']));
$date_to = date('Y-m-d', strtotime($_GET['date_to']));
$type = $_GET['type'];
$comp_id = $_GET['comp_id'];
$has_fb = $_GET['has_fb'];

$result = $objComp->getPatientList($type, $comp_id, $date_from, $date_to, $has_fb);

$no = 1;

$data = array("no"=>array());
if($result){
	$i=0;
	while ($rows = $result->FetchRow()){

		$data[$i] = array('no'=>$no,
							'date'=>date("m/d/y", strtotime($rows['encounter_date'])),
							'name'=>$rows['name'],
							'enc_nr'=>$rows['encounter_nr'],
							'type'=>$rows['encounter_type'],
							'comp_name'=>strtoupper($rows['comp_name']),
							'status'=>$rows['is_final'] ? "FINAL" : "");	
		$i++;
		$no++;
	}
}


if ($row = $objInfo->getAllHospitalInfo()) {
			$row['hosp_add'] = strtoupper($row['hosp_addr1']);
			$row['hosp_name']   = strtoupper($row['hosp_name']);
}else{
	$row['hosp_name'] = "Cainglet Medical Hospital Inc.";
	$row['hosp_add'] = "2081 NATIONAL HIGHWAY SALVACION, PANABO CITY";
}


$jasper->setParams(array(
	'hosp_name' => $row['hosp_name'],
	'hosp_add' => $row['hosp_add'],
	'report_range'=> "(".date("M d, Y", strtotime($_GET['date_from']))." to ".date("M d, Y", strtotime($_GET['date_to'])).")",
	'report_name'=> "List of Patients with Care Of"
));

$jasper->setData($data);

$jasper->setJrxmlFilePath('care_of.jrxml');
$jasper->run();



