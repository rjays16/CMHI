<?php
#created by Nick, 1/30/2014
require_once('roots.php');
require_once($root_path.'include/inc_jasperReporting.php');

require_once $root_path.'include/care_api_classes/class_hospital_admin.php';
require_once $root_path.'include/inc_environment_global.php';


global $db;

if ($_GET['report'] == 'daily_bills_rendered'){
	$date_span = date('M d,Y', strtotime($_GET['date']));
}else if($_GET['report'] == 'monthly_bills_rendered'){
	$tmp_date = strtotime($_GET['year'].'-'.$_GET['month'].'-01');
	$date_span = date('M',$tmp_date) . " " . date('Y',$tmp_date);
}

$objInfo = new Hospital_Admin();
if ($row1 = $objInfo->getAllHospitalInfo()) {
	$row1['hosp_agency'] = strtoupper($row1['hosp_agency']);
	$row1['hosp_name']   = strtoupper($row1['hosp_name']);
}
else {
	$row1['hosp_country'] = "Republic of the Philippines";
	$row1['hosp_agency']  = "DEPARTMENT OF HEALTH";
	$row1['hosp_name']    = "DAVAO MEDICAL CENTER";
	$row1['hosp_addr1']   = "JICA Bldg., JP Laurel Avenue, Davao City";
}

#--------------------------------------------------------------------------------------

$report_type = $_GET['report'];
$delete_type = $_GET['dtype'];
$personnel = $_GET['personnel'];

if($report_type=='daily_bills_rendered'){
	
	$date_condition = "(DATE(fb.bill_dte) = DATE(" . $db->qstr(date('Y-m-d', strtotime($_GET['date'])))  . "))";

}else /*if($report_type=='monthly_bills_rendered')*/{
	
	$startDate = strtotime($_GET['year'].'-'.$_GET['month'].'-01');
	if ($startDate === false) {
		die('Invalid month/year specified');
	}
	$endDate = strtotime('+1 month', $startDate);
	$date_condition = "(fb.bill_dte BETWEEN " . $db->qstr(date('YmdHis', $startDate)) . " AND " . $db->qstr(date('YmdHis', $endDate)) . ")";

}


$delete_condition = '(fb.is_deleted <> 1 OR fb.is_deleted IS NULL) AND';

if($personnel == 'all'){
	$personnel_condition = '';
}else{
	$personnel_condition = "fb.create_id = '".$personnel."' AND";
}

$query = "SELECT 
  fb.bill_nr,
  fb.bill_dte,
  fn_get_person_name (e.pid) `patient`,
  e.encounter_nr,
  (
    fb.total_acc_charge + fb.total_med_charge + fb.total_srv_charge + fb.total_ops_charge + fb.total_msc_charge
  ) `hb`,
  fb.total_doc_charge AS pf,
  IFNULL((SELECT SUM(ar_discount) FROM seg_billing_other_discounts WHERE refno = fb.`bill_nr`), 0) `total_discount`,
	 IFNULL(
	  (SELECT 
	    SUM(dr_claim) 
	  FROM
	    seg_billing_pf 
	  WHERE bill_nr = fb.`bill_nr` AND hcare_id = '18'),
	  0
	) + IFNULL(
	  (SELECT 
	    SUM(coverage) 
	  FROM
	    seg_billingcoverage_adjustment 
	  WHERE ref_no = fb.`bill_nr` AND hcare_id = '18'),
	  0
	) `total_coverage`,
  IFNULL(SUM(sbca.`amount`), 0) care_of,
  (IFNULL(fb.total_prevpayments, 0) - fn_get_refunded_deposit(fb.`encounter_nr`)) `previous_payment`,
  IFNULL(
    fn_billing_compute_gross_amount (fb.bill_nr),
    0
  ) `excess`,
  IFNULL(
    (SELECT 
      SUM(spr.amount_due) 
    FROM
      seg_pay_request spr 
      LEFT JOIN seg_pay sp 
        ON sp.or_no = spr.or_no 
    WHERE spr.ref_source = 'FB' 
      AND spr.service_code = fb.`bill_nr` 
      AND sp.cancel_date IS NULL),
    0
  ) `or_amount`,
  sc.`c_type` 
FROM
  seg_billing_encounter fb 
  INNER JOIN care_encounter e 
    ON e.encounter_nr = fb.encounter_nr 
  LEFT JOIN seg_billing_company_areas sbca 
    ON sbca.`encounter_nr` = fb.`encounter_nr` 
  LEFT JOIN seg_company_allotment sca 
    ON sca.`encounter_nr` = fb.`encounter_nr` 
  LEFT JOIN seg_company sc 
    ON sc.`comp_id` = sca.`comp_id` 
WHERE 
".$personnel_condition."
".$delete_condition."
".$date_condition."
GROUP BY fb.bill_nr 
ORDER BY patient ASC ";

// echo $query; exit();

$sum = array('pf'=>0, 'hb'=>0, 'discount'=>0, 'phic'=>0, 'ca'=>0, 'ce'=>0, 'ap'=>0, 'hi'=>0);

$rs = $db->Execute($query);
if($rs){
	if($rs->RecordCount()>0){
		$i = 0;
		while($row = $rs->FetchRow()){
			$AmountPayableShow = $row['previous_payment'] + $row['or_amount'];

			$hi = $row['excess']>= 0 ? ($row['excess']-$AmountPayableShow) : ($row['excess']+$AmountPayableShow); 

			$data[$i] = array('PF'=>number_format($row['pf'],2),
				              'bill_date'=>date('Y-m-d h:i A', strtotime($row['bill_dte'])),
				              'patient_name'=>$row['patient'],
				              'case_no'=>$row['encounter_nr'],
				              'HB'=>(double)$row['hb'],
				              'discount'=>(double)$row['total_discount'],
				              'phic_coverage'=>(double)$row['total_coverage'],
				              'CA' => ($row['c_type'] == 'company' ? number_format($row['care_of'],2) : "0.00"),
				              'CE' => ($row['c_type'] == 'person' ? number_format($row['care_of'],2) : "0.00"),
				              'amount Paid'=>(double)$row['or_amount'],
				              'HI'=>number_format($row['excess'] - $row['or_amount'],2)
				             );

				$sum['pf'] += $row['pf'];
				$sum['hb'] += $row['hb'];
				$sum['discount'] += $row['discount'];
				$sum['phic'] += $row['total_coverage'];
				$sum['ca'] += ($row['c_type'] == 'company' ? $row['care_of'] : 0);
				$sum['ce'] += ($row['c_type'] == 'person' ? $row['care_of'] : 0);
				$sum['ap'] += $AmountPayableShow;
				$sum['hi'] += ($hi);
			$i++;
		}
	}else{
		$data['bill_ref'][0] = "No data";
	}
}else{
	$data['bill_ref'][0] = "No data";
}

$params = array("hosp_name"=>$row1['hosp_name'],
	            "hosp_addr1"=>$row1['hosp_addr1'],
	            "date_span"=>$date_span,
	            "sum_hb"=>number_format($sum['hb'],2),
	            "sum_pf"=>number_format($sum['pf'],2),
	            "sum_discount"=>number_format($sum['discount'],2),
	            "sum_phic"=>number_format($sum['phic'],2),
	            "sum_ca"=>number_format($sum['ca'],2),
	            "sum_ce"=>number_format($sum['ce'],2),
	            "sum_amount"=>number_format($sum['ap'],2),
	            "sum_hi"=>number_format($sum['hi'],2),
	            "rep_title"=>"HOSPITAL INCOME"
);

showReport('Bills',$params,$data,$_GET['reportFormat']);
?>