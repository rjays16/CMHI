<?php
require('./roots.php');
include_once($root_path . 'include/care_api_classes/reports/JasperReport.php');
require_once($root_path.'include/care_api_classes/class_seg_lab_services_transaction.php');
require_once($root_path.'include/inc_environment_global.php');

$from = $_GET['fromdate'];
$to = $_GET['todate'];
$date_range = date("M d, Y", strtotime($from))." to ".date("M d, Y", strtotime($to));

$jasper = new JasperReport();
$objHosp = new Hospital_Admin();
$labObj = new SegLab();

$hospInfo = $objHosp->getAllHospitalInfo();
$lab_res = $labObj->phicReport($from, $to);

$data[0]['patient'] = "No results found..";

if($lab_res){
	$i=0;
	while($row = $lab_res->FetchRow()){
		$age = explode("and", $row['age']);

		$data[$i] = array(
				"patient"=>utf8_encode($row['patient']),
				"age"=>$age[0],
				"room"=>utf8_encode($row['room']),
				"admission"=>date("M d, Y g:iA", strtotime($row['encounter_date'])),
				"att_dr"=>utf8_encode($row['attending_dr']),
				"services"=>utf8_encode($row['services']),
				"diagnosis"=>utf8_encode($row['diagnosis']),
				"phic"=>$row['insurance_nr']
			);
		$i++;
	}
}

$jasper->setParams(array(
	'hosp_name'=>$hospInfo['hosp_name'],
	'hosp_add'=>$hospInfo['hosp_addr1'],
	'date_range'=>$date_range
));

$jasper->setData($data);

$jasper->setJrxmlFilePath('phicLabReport.jrxml');
$jasper->run();