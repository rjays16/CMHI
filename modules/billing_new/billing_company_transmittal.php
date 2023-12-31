<?php
//created by EJ 08/28/2014
require('./roots.php');
include_once($root_path . 'include/care_api_classes/reports/JasperReport.php');
require_once($root_path.'include/care_api_classes/class_company.php');
require_once($root_path.'include/inc_environment_global.php');

global $db;

$jasper = new JasperReport();
$objCompany = new Company();

$comp_id = $_GET['comp_id'];
$bill_nr = $_GET['bill_nr'];
$timestamp = $_GET['cutoff_time'];
$case_nrs = $_GET['case_nrs'];

$date = gmdate("F j, Y", $timestamp);
$company_name = $objCompany->getCompanyFullName($comp_id);


$signatory = $objCompany->getSignatoryData();

if($signatory){
	while ($personell = $signatory->FetchRow()){
		$noted_by = $personell['name'];
		$noted_by_position = $personell['position'];
	}
}


$prepared_by = $_SESSION['sess_temp_userid'];
$prepared_by_position = 'Billing Clerk';

if (!$bill_nr) {
	$result = $objCompany->getUnBilledEmployeesTransmittal($comp_id,$case_nrs);
}
else {
	$result = $objCompany->getBilledEmployeesTransmittal($bill_nr,$case_nrs);
	$result2 = $objCompany->selectBillingHeader($bill_nr);
}

$total_hb = 0;
$total_pf = 0;
$total_amount = 0;
$discount = 0;

if($result){
	$i=0;
	while ($rows = $result->FetchRow()){
		$data[$i] = array('date'=>date("m/d/y", strtotime($rows['discharge_date'])),
							'time'=>date("g:iA", strtotime($rows['discharge_time'])),
							'ref_no'=>$rows['encounter_nr'],
							'type'=>$rows['encounter_type'],
							'patient_name'=>utf8_encode($rows['name']),
							'hb'=>number_format($rows['HB'],2),
							'pf'=>number_format($rows['PF'],2),
							'amount'=>number_format(($rows['HB'] + $rows['PF']),2),
							'findings_diagnosis'=>utf8_encode($rows['diagnosis']));		

							$total_hb += $rows['HB'];
							$total_pf += $rows['PF'];
		$i++;
	}
}

if($result2){
	while($row = $result2->FetchRow()){
		$discount = $row['discount'];
	}
}

$jasper->setParams(array(
	'date' => $date,
	'company_name' => utf8_encode($company_name),
	'noted_by' => utf8_encode($noted_by),
	'noted_by_position' => utf8_encode($noted_by_position),
	'prepared_by' => utf8_encode($prepared_by),
	'prepared_by_position' => $prepared_by_position,
	'total_hb' => number_format($total_hb,2),
	'total_pf' => number_format($total_pf,2),
	'total_amount' => number_format(($total_hb + $total_pf),2),
	'discount' => number_format($discount,2),
	'remaining_amount' => number_format((($total_hb + $total_pf)-$discount), 2)
));

$jasper->setData($data);

$jasper->setJrxmlFilePath('company_billing.jrxml');
$jasper->run();



