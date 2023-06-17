<?php
require('./roots.php');
include_once($root_path . 'include/care_api_classes/reports/JasperReport.php');
require_once($root_path.'include/care_api_classes/class_lab_results.php');
require_once($root_path.'include/care_api_classes/class_hospital_admin.php');
require_once($root_path.'include/care_api_classes/class_encounter.php');
require_once($root_path.'include/care_api_classes/class_personell.php');

$jasper = new JasperReport();
$objHosp = new Hospital_Admin();
$lab_obj = new Lab_Results();
$personell_obj = new Personell();

$refno = $_GET['refno'];
$group_id = $_GET['group_id'];
$service_code = $_GET['service_code'];

$hospInfo = $objHosp->getAllHospitalInfo();
$lab_result = $lab_obj->getLabResult($refno, $group_id);
$patient = $lab_obj->get_patient_data($refno, $group_id);
$group_name = $lab_obj->getGroupName($group_id);
$form_id = $lab_obj->getGroupForm($group_id);
$jrxml = $lab_obj->getFormJrxml($form_id);
$pathologist_name =$personell_obj->getNameTitle($lab_result['pathologist_pid']);
$medtech_name =$personell_obj->getNameTitle($lab_result['med_tech_pid']); 
$performed_dr =$personell_obj->getNameTitle($lab_result['performed_by_pid']);
$ordername = mb_strtoupper($patient['name_last']).", "
				.mb_strtoupper($patient['name_first'])." "
				.mb_strtoupper($patient['name_middle']);

$result = $personell_obj->getPersonellInfo($patient['request_doctor']);
if (trim($result["name_middle"]))
$dot  = ".";

$doctor = trim($result["name_first"])." ".substr(trim($result["name_middle"]),0,1).$dot." ".trim($result["name_last"]);
$doctor = htmlspecialchars(mb_strtoupper($doctor));
$doctor = trim($doctor);
if(!empty($doctor))
$doctor = "DR. ".$doctor;

$params = array();

if (stristr($patient['age'],'years')){
	$age = substr($patient['age'],0,-5);
	$age = floor($age).'y';
}elseif (stristr($patient['age'],'year')){
	$age = substr($patient['age'],0,-4);
	$age = floor($age).'y';
}elseif (stristr($patient['age'],'months')){
	$age = substr($patient['age'],0,-6);
	$age = floor($age).'m';
}elseif (stristr($patient['age'],'month')){
	$age = substr($patient['age'],0,-5);
	$age = floor($age).'m';
}elseif (stristr($patient['age'],'days')){
	$age = substr($patient['age'],0,-4);

	if ($age>30){
		$age = $age/30;
		$label = 'm';
	}else
		$label = 'd';

	$age = floor($age).' '.$label;
}elseif (stristr($patient['age'],'day')){
	$age = substr($patient['age'],0,-3);
	$age = floor($age).'d';
}else{
	$age = floor($patient['age']).'y';
}

//lab result
if($service_code){
	$sql = "SELECT 
			  par.name,
			  res.`result_value` 
			FROM
			  seg_lab_result res 
			  LEFT JOIN seg_lab_result_params par 
			    ON par.param_id = res.`param_id` 
			WHERE res.refno = '$refno'";

	$result = $lab_obj->exec_query($sql);
	if($result){
		while($row = $result->FetchRow()){
			$params[str_replace(' ', '_', strtolower($row['name']))] = $row['result_value'];
		}
	}
}
//end lab result

$data = array('name'=>'');
$imgpath = $jasper->getLogoPath();

$params['hosp_name']=$hospInfo['hosp_name'];
$params['hosp_add']=$hospInfo['hosp_addr1'];
$params['header2']='(Affiliated with Our Lady of Mercy Diagnostic Center, Davao City)';
$params['name']=$ordername;
$params['age']=$age.' / '.strtoupper($patient['sex']);
$params['refno']=$_GET['refno'];
$params['dob']=$patient['date_birth'];
$params['pid']=$patient['pid'];
$params['diagnosis']=$patient['er_opd_diagnosis'];
$params['doctor']=$doctor;
$params['gender']=strtolower($patient['sex']) == 'f' ? 'Female' : 'Male';
$params['room']=strtoupper($patient['ward_name'] ? $patient['ward_name'].' Room '.$patient['current_room_nr'] : '');
$params['date']=$lab_result['service_date'];
$params['permormed_by_dr']=$performed_dr;
$params['pathologist']=$pathologist_name;
$params['med_tech']=$medtech_name;
$params['logo']=$imgpath;
$params['pathologist_signature']=$jasper->getPathologistSignaturePath();
$params['group']=strtoupper($group_name);
$params['remarks']=$lab_result['remarks'];
$params['med_tech_lic'] = $personell_obj->getLincenseNr($lab_result['med_tech_pid']) ? 'Lic. No. '.$personell_obj->getLincenseNr($lab_result['med_tech_pid']) : '' ;
$params['patho_lic'] = $personell_obj->getLincenseNr($lab_result['pathologist_pid']) ? 'Lic. No. '.$personell_obj->getLincenseNr($lab_result['pathologist_pid']) : '' ;
$params['permormed_by_dr_lic'] = $personell_obj->getLincenseNr($lab_result['performed_by_pid']) ? 'Lic. No. '.$personell_obj->getLincenseNr($lab_result['performed_by_pid']) : '' ;

$jasper->setParams($params);

$jasper->setData($data);
$jasper->setJrxmlFilePath($jrxml);
$jasper->run();
?>