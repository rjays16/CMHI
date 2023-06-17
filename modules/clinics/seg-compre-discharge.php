<?
/**
*Created by mai
*Created on 09-15-2014
*/

require('./roots.php');
require($root_path.'include/inc_environment_global.php');

define('NO_2LEVEL_CHK',1);
$local_user='ck_pflege_user';

require_once($root_path.'include/inc_front_chain_lang.php');
require_once($root_path.'gui/smarty_template/smarty_care.class.php');
require_once($root_path.'include/care_api_classes/class_encounter.php');
require_once($root_path.'include/care_api_classes/class_vitalsign.php');
require_once($root_path.'include/care_api_classes/class_compre_discharge.php');
require_once($root_path.'include/care_api_classes/class_labservices_transaction.php');
require_once($root_path.'include/care_api_classes/class_radioservices_transaction.php');
require_once($root_path.'include/care_api_classes/prescription/class_prescription_writer.php');
require_once($root_path.'modules/clinics/ajax/seg-compre-discharge.common.php');
require_once($root_path.'include/care_api_classes/class_personell.php');

$smarty = new Smarty_Care('common');
$enc = new Encounter();
$vital = new SegVitalsign();
$compre_disc = new Compre_discharge();
$lab = new SegLab();
$rad = new SegRadio();
$presc = new SegPrescription();
$personell = new Personell();

$breakfile = 'javascript:window.parent.cClick();';
$smarty->assign('breakfile', $breakfile);
ob_start();
?>

<?

$encounter_compre = $_GET['encounter_nr'];
if(!$compre_disc->selectCompre($encounter_compre)){
	if($encounter_res = $compre_disc->getRecentEnc($_GET['pid'], $encounter_compre)){
		$encounter_compre = $encounter_res['encounter_nr'];
	}
}

//get patient data
$p_data = $enc->getEncounterInfo($_GET['encounter_nr']);
$encounter_type = $p_data['encounter_type'];
switch($encounter_type){
	case 1:
		$encounter_type_desc = "ER Consultation";
		break;
	case 2:
		$encounter_type_desc = "OPD Consultation";
		break;
	case 3:
		$encounter_type_desc = "ER Inpatient";
		break;
	case 4:
		$encounter_type_desc = "OPD Inpatient";
		break;
	case 12:
		$encounter_type_desc = "Well Baby";
		break;
	default:
		$encounter_type_desc = "Not Indicated";
}
$patient_name = $p_data['name_first'].' '.$p_data['name_last'];
$ward_room = $p_data['ward_name']." Room # ".$p_data['current_room_nr'];

//vital signs
$v_data = $vital->get_latest_vital_signs($_GET['pid'], $encounter_compre);
if($encounter_compre != $_GET['encounter_nr']){
	$v_data['vitalsign_no'] = 0;
}

$address = $p_data['street_name']." ".$p_data['brgy_name'].", ".$p_data['mun_name']." ".$p_data['prov_name'];
$height = $v_data['height_ft']." ft. ".$v_data['height_in']." in";

$smarty->assign('sPatientID', $p_data['pid']);
$smarty->assign('sAge', $p_data['age']);
$smarty->assign('sPtype', $encounter_type_desc);
$smarty->assign('sPatientName', $patient_name);
$smarty->assign('sWardRoom', $ward_room);
$smarty->assign('sWeight', $v_data['weight']." kg");
$smarty->assign('sGender', ($p_data['sex'] == 'f' ? 'Female' : 'Male'));
$smarty->assign('sAddress', $address);
$smarty->assign('sCivStat', $p_data['civil_status']);
$smarty->assign('sHeight', $height);

//print_r($v_data);

//comprehensive report
$c_data = $compre_disc->selectCompre($encounter_compre);

//discharge info
$d_data = $compre_disc->selectDischarge($_GET['encounter_nr']);

//list of prescriptions
$presc_data = $presc->getEncPrescription($_GET['encounter_nr']);
if($presc_data){
	while($row_head = $presc_data->FetchRow()){
		//prescription date
		$m_data .= "<table width='80%'><tr><td>Prescription Date: ".date_format(date_create($row_head['prescription_date']), "M d, Y")."</td></tr></table>";

		//list
		$m_data .= "<table width='80%' class='segList' id='order-list'>";
		$m_data .= "<thead>
						<tr id='order-list-header'>
							<td width='*'>Item Description</td>
							<td width='5%'>Quantity</td>
							<td width='20%'>Dosage</td>
							<td width='20%'>Period</td>
						</tr>
					</thead>";

		if($row_head['prescription_id']){
			$presc_info_data = $presc->getPrescription($_GET['encounter_nr'], $row_head['prescription_id']);
		}else{
			$presc_info_data = $presc->getMeds($row_head['pharma_refno']);
		}

		if($presc_info_data){
			while($row_data = $presc_info_data->FetchRow()){
				$m_data .="<tbody>
								<tr>
									<td>".$row_data['item_name']."</td>
									<td>".$row_data['quantity']."</td>
									<td>".$row_data['dosage']."</td>
									<td>".($row_data['period_count'] ? $row_data['period_count']  : "" )." ".$row_data['period_interval']."</td>
								</tr>";
			}
		}

		$m_data .="</table>";

		//instructions
		$m_data .= "<table width='80%'>
						<tr><td>Special Instructions:</td></tr>
						<tr><td><textarea style='width: 100%' class='segInput' disabled>".$row_head['instructions']."</textarea></td></tr>
					</table>";
		$m_data .="<br><br>";
	}
}

//list of lab results
$lab_results = $lab->getGroupServe($_GET['encounter_nr']);
if($lab_results){
	while($row = $lab_results->FetchRow()){
		$l_data .= "<tr>
						<td>".date_format(date_create($row['date_received']), "M d, Y h:m")."</td>
						<td>".$row['services']."</td>
						<td><button class='button' onclick='displayLabResult(\"".$row['service_code']."\", \"".$row['group_id']."\", \"".$row['refno']."\"); return false;'>
								<img class='link' src='".$root_path."/gui/img/common/default/page_white_acrobat.png'>
								Results
							</button>
						</td>
					</tr>";
	}
}

//list of radiology result
$rad_result = $rad->getRadRes($_GET['encounter_nr']);
if($rad_result){
	while($row = $rad_result->FetchRow()){
		$r_data .="<tr>
					<td>".date_format(date_create($row['date_request']), "M d, Y h:m")."</td>
					<td>".$row['services']."</td>
					<td><button class='button' onclick='displayRadResult(\"".$row['refno']."\"); return false;'>
								<img class='link' src='".$root_path."/gui/img/common/default/page_white_acrobat.png'>
								Results
							</button>
					</td>
					<td>
						<a href='javascript: void(0);'' onclick='previewRadResult(\"".$row['r_img']."\", \"".$row['patient_name']."\", \"".$row['services']."\");'> Preview Image Results</a>
					</td>
				  </tr>";
	}
}

//list of case rate diagnosis
$case_diagnosis = $compre_disc->getDiagnosis($_GET['encounter_nr']);
if($case_diagnosis){
	while($row=$case_diagnosis->FetchRow()){
		$diagnosis .="<li>".$row['code']." ".$row['description']."</li>";
	}
}

//list of case rate procedures
$case_procedure = $compre_disc->getProcedures($_GET['encounter_nr']);
if($case_procedure){
	while($row = $case_procedure->FetchRow()){
		$procedures .="<li>".$row['ops_code']." ".$row['description']."</li>";
	}
}

//history
$encounters = $compre_disc->getEncounters($_GET['pid']);

if($encounters){
	while($row = $encounters->FetchRow()){
		if($row['encounter_nr'] != $_GET['encounter_nr']){
			$history.= "<tr>";
			$history.= "<td>".date_format(date_create($row['encounter_date']),"M d, Y")."</td>";
			$history.= "<td align='center'>".$row['encounter_nr']." (".$row['type'].") "."</td>";
			$history.= "<td>";
				$prescriptions = $presc->getPresrciptionEncounter($row['encounter_nr']);
				if($prescriptions){
					$history .="<table>";
						while($row2 = $prescriptions->FetchRow()){
							$item = $presc->getPrescriptionItem($row2['id']);
							$history.="<tr>";
								while($row3 = $item->FetchRow()){
										$history .=$row3['item_name']."#".$row3['quantity']."<br>".
													$row3['dosage']."<br>".
													($row3['period_count'] ? $row3['period_count']. 
													" ".$row3['period_interval']."<br>" : "")."<br>";
								}

								$history.="<span style='color:red;'>Special Instructions: ".$row2['instructions']."</span>";
							$history .="</tr>";
						}
					$history .="</table>";
				}
			$history.= "<td>".$row['er_diagnosis']."</td>";
			$history.= "<td>";
				//get diagnosis from dashboard
				$diagnosis_ = $compre_disc->selectDischarge($row['encounter_nr']);
				$final_diag = $diagnosis_['notes'];
				
				//if diagnosis from medical record exists, overwrite final_diag var
				$diagnosis_enc = $compre_disc->getDiagnosisEncounter($row['encounter_nr']);
				if($diagnosis_enc){
					$final_diag ="<table>";
						while($row4 = $diagnosis_enc->FetchRow()){
							$final_diag.="<tr>".$row4['diagnosis']."<br><br></tr>";
						}
					$final_diag.="</table>";
				}

				$history .= $final_diag;

			$history.= "</td>";
			$history.= "<td><span style='color: red;'>".$row['important_info']."</span></td>";
			$history.=	"</tr>";
		}
	}
}

//get dr data
$dr_data = $personell->get_Personell_info($p_data['current_att_dr_nr']);
?>

<script type="text/javascript" src="<?=$root_path?>js/jsprototype/prototype.js"></script>
<link rel="stylesheet" href="<?= $root_path ?>js/jquery/themes/seg-ui/jquery.ui.all.css" type="text/css" />
<script type="text/javascript" src="<?=$root_path?>js/jquery/jquery.js"></script>
<script type="text/javascript" src="<?=$root_path?>js/jquery/ui/jquery-ui.js"></script>
<script type="text/javascript" src="<?=$root_path?>js/jquery/jquery-1.8.2.js"></script>
<script type="text/javascript" src="js/seg-compre-discharge.js"></script>
<script>var $J = jQuery.noConflict();</script>
<?
$xajax->printJavascript($root_path.'classes/xajax_0.5');
$sTemp = ob_get_contents();
ob_end_clean();
$smarty->append('JavaScript',$sTemp);

//comprehensive report
$smarty->assign('sAddImg', '<form enctype="multipart/form-data" method="POST"><input type="file" id="file" name="file" multiple="multiple"></form>');
$smarty->assign('sImages', '<div id="images-thumb"></div><input type="hidden" id="img_path" value="'.$root_path.compre_img.'">');
$smarty->assign('sChiefComplaint', '<textarea class="SegInput" name="chief_complaint" id="chief_complaint" style="width: 60%">'.$p_data['chief_complaint'].'</textarea>');
$smarty->assign('sHistoryIllness', '<textarea class="SegInput" name="histo_illness" id="histo_illness" style="width: 60%">'.$c_data['histo_illness'].'</textarea>');
$smarty->assign('sGeneralSurvey', '<textarea class="SegInput" name="general_survey" id="general_survey" style="width: 60%">'.$c_data['general_survey'].'</textarea>');
$smarty->assign('sRespRate', '<input class="SegInput" type="text" name="resp_rate" id="resp_rate" size="10" value="'.$v_data['resp_rate'].'"/>');
$smarty->assign('sHeartRate', '<input class="SegInput" type="text" name="heart_rate" id="heart_rate" size="10" value="'.$v_data['pulse_rate'].'"/>');
$smarty->assign('sBPSys', '<input class="SegInput" type="text" name="bp_sys" id="bp_sys" size="5" value="'.$v_data['systole'].'"/>');
$smarty->assign('sBPDia', '<input class="SegInput" type="text" name="bp_dia" id="bp_dia" size="5" value="'.$v_data['diastole'].'"/>');
$smarty->assign('sTemp', '<input class="SegInput" type="text" name="temp" id="temp" size="10" value="'.$v_data['temp'].'"/>');
$smarty->assign('sSkin', '<textarea class="SegInput" name="skin" id="skin" style="width: 60%;">'.$c_data['skin'].'</textarea>');
$smarty->assign('sHeadNeck', '<textarea type="text" class="SegInput" name="head_neck" id="head_neck" style="width: 60%;">'.$c_data['head_and_neck'].'</textarea>');
$smarty->assign('sEye', '<textarea class="SegInput" type="text" name="eye" id="eye" style="width: 60%;">'.$c_data['eye'].'</textarea>');
$smarty->assign('sEar', '<textarea class="SegInput" type="text" name="ear" id="ear" style="width: 60%;">'.$c_data['ear'].'</textarea>');
$smarty->assign('sChestLungs', '<textarea class="SegInput" type="text" name="chest_lungs" id="chest_lungs" style="width: 60%;">'.$c_data['chest_lungs'].'</textarea>');
$smarty->assign('sLungsC', '<textarea class="SegInput" type="text" name="lungsC" id="lungsC" style="width: 60%;">'.$c_data['lungs'].'</textarea>');
$smarty->assign('sCVS', '<textarea class="SegInput" type="text" name="cvs" id="cvs" style="width: 60%;">'.$c_data['cvs'].'</textarea>');
$smarty->assign('sAbdomen', '<textarea class="SegInput" type="text" name="abdomen" id="abdomen" style="width: 60%;">'.$c_data['abdomen'].'</textarea>');
$smarty->assign('sExtremities', '<textarea class="SegInput" type="text" name="extremities" id="extremities" style="width: 60%;">'.$c_data['extremities'].'</textarea>');
$smarty->assign('sNeuro', '<textarea class="SegInput" type="text" name="neuro" id="neuro" style="width: 60%;">'.$c_data['neuro'].'</textarea>');
$smarty->assign('sMedHist', '<textarea class="SegInput" name="med_hist" id="med_hist" style="width: 60%">'.$c_data['past_medical_history'].'</textarea>');
$smarty->assign('sFamHist', '<textarea class="SegInput" name="fam_hist" id="fam_hist" style="width: 60%">'.$c_data['family_history'].'</textarea>');
$smarty->assign('sPerSoHist', '<textarea class="SegInput" name="perso_hist" id="perso_hist" style="width: 60%">'.$c_data['persona_social_history'].'</textarea>');
$smarty->assign('sImmuHist', '<textarea class="SegInput" name="immu_hist" id="immu_hist" style="width: 60%">'.$c_data['immu_history'].'</textarea>');
$smarty->assign('sObsHist', '<textarea class="SegInput" name="obs_hist" id="obs_hist" style="width: 60%">'.$c_data['obs_history'].'</textarea>');
$smarty->assign('sDiagnosis', '<textarea class="SegInput" name="adm_diagnosis" id="adm_diagnosis" style="width: 60%">'.$p_data['er_opd_diagnosis'].'</textarea>');

$smarty->assign('sSaveBtnCmp','<button class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" onclick="saveCompre();" id="save_compre"><span class="ui-button-icon-primary ui-icon ui-icon-disk" ></span><span class="ui-button-text">
        								Save</span></button>');
$smarty->assign('sPrintBtnCmp','<button class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" id="print_compre" onclick="printReport(\'comprehensive\');"><span class="ui-button-icon-primary ui-icon ui-icon-disk"></span><span class="ui-button-text">
        								Print</span></button>');
$smarty->assign('sCancelBtnCmp','<button class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" id="cancel_compre"><span class="ui-button-icon-primary ui-icon ui-icon-disk"></span><span class="ui-button-text">
        								Cancel</span></button>');

//discharge information
$smarty->assign('sHistoryIllnessR', '<textarea class="SegInput" name="histo_illness" id="histo_illnessR" style="width: 60%" disabled>'.$c_data['histo_illness'].'</textarea>');
$smarty->assign('sRespRateR', '<input class="SegInput" type="text" name="resp_rate" id="resp_rateR" size="10" disabled value="'.$v_data['resp_rate'].'"/>');
$smarty->assign('sHeartRateR', '<input class="SegInput" type="text" name="heart_rate" id="heart_rateR" size="10" disabled value="'.$v_data['resp_rate'].'"/>');
$smarty->assign('sBPSysR', '<input class="SegInput" type="text" name="bp_sys" id="bp_sysR" size="5" disabled value="'.$v_data['systole'].'"/>');
$smarty->assign('sBPDiaR', '<input class="SegInput" type="text" name="bp_dia" id="bp_diaR" size="5" disabled value="'.$v_data['diastole'].'"/>');
$smarty->assign('sTempR', '<input class="SegInput" type="text" name="temp" id="tempR" size="10" disabled  value="'.$v_data['temp'].'"/>');

$smarty->assign('sAdmissionImpressionR', '<textarea class="SegInput" name="adm_impre" id="adm_impre" style="width: 60%" disabled>'.$p_data['er_opd_diagnosis'].'</textarea>');
$smarty->assign('sMedication', '<textarea class="SegInput" name="medication" id="medication" style="width: 60%">'.$d_data['medication'].'</textarea>');
$smarty->assign('sProcedure', '<textarea class="SegInput" name="procedure" id="procedure" style="width: 60%">'.$d_data['proc'].'</textarea>');
$smarty->assign('sCourseWard', '<textarea class="SegInput" name="course_ward" id="course_ward" style="width: 60%">'.$d_data['course_ward'].'</textarea>');

$smarty->assign('sNoInfections', '<textarea class="SegInput" name="no_infections" id="no_infections" style="width: 60%">'.$d_data['no_of_infections'].'</textarea>');
$smarty->assign('sReco', '<textarea class="SegInput" name="reco" id="reco" style="width: 60%">'.$d_data['recommendations'].'</textarea>');
$smarty->assign('sAdmitDoc', '<input class="SegInput"  type="text" name="admit_doc" id="admit_doc" style="width: 40%;" disabled value="'.$dr_data['name'].'"/>');
$smarty->assign('sNotes', '<textarea class="SegInput" name="notes" id="notes" style="width: 60%">'.$d_data['notes'].'</textarea>');
$smarty->assign('sCaseRateDiagnosis', '<div id="diagnosis" style="width: 60%; overflow-y: auto; outline: thin solid; height: 50px; background-color: #E3F1FB; outline-color: #DFECF6;"><ul>'.$diagnosis.'</ul></div>');
$smarty->assign('sCaseRateProcedures', '<div id="procedures" style="width: 60%; overflow-y: auto; outline: thin solid; height: 50px; background-color: #E3F1FB; outline-color: #DFECF6;"><ul>'.$procedures.'</ul></div>');
$smarty->assign('sSkinR', '<input class="SegInput" type="text" name="skin" id="skinR" style="width: 80%;" disabled value="'.$c_data['skin'].'"/>');
$smarty->assign('sHeadNeckR', '<input class="SegInput" type="text" name="head_neckR" id="head_neckR" style="width: 80%;" disabled value="'.$c_data['head_and_neck'].'"/>');
$smarty->assign('sEyeR', '<input class="SegInput" type="text" name="eye" id="eyeR" style="width: 80%;" disabled value="'.$c_data['eye'].'"/>');
$smarty->assign('sEarR', '<input class="SegInput" type="text" name="ear" id="earR" style="width: 80%;" disabled value="'.$c_data['ear'].'"/>');
$smarty->assign('sChestLungsR', '<input class="SegInput" type="text" name="chest_lungs" id="chest_lungsR" style="width: 80%;" disabled value="'.$c_data['chest_lungs'].'"/>');
$smarty->assign('sLungsR', '<input class="SegInput" type="text" name="lungsR" id="lungsR" style="width: 80%;" disabled value="'.$c_data['lungs'].'"/>');
$smarty->assign('sCVSR', '<input class="SegInput" type="text" name="cvs" id="cvsR" style="width: 80%;" disabled value="'.$c_data['cvs'].'"/>');
$smarty->assign('sAbdomenR', '<input class="SegInput" type="text" name="abdomenR" id="abdomenR" style="width: 80%;" disabled value="'.$c_data['abdomen'].'"/>');
$smarty->assign('sExtremitiesR', '<input class="SegInput" type="text" name="extremitiesR" id="extremities" style="width: 80%;" disabled value="'.$c_data['extremities'].'"/>');
$smarty->assign('sNeuroR', '<input class="SegInput" type="text" name="neuro" id="neuroR" style="width: 80%;" disabled value="'.$c_data['neuro'].'"/>');
$smarty->assign('sNote', '<textarea class="SegInput" name="note" id="note" style="width: 60%">'.$d_data['note'].'</textarea>');
$smarty->assign('sCond', '<textarea class="SegInput" name="cond" id="cond" style="width: 60%">'.$d_data['cond'].'</textarea>');
$smarty->assign('sGeneralSurveyR', '<textarea class="SegInput" disabled style="width: 60%">'.$c_data['general_survey'].'</textarea>');

$smarty->assign('sSaveBtnDisc','<button class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" onclick="saveDisc();" id="save_disc"><span class="ui-button-icon-primary ui-icon ui-icon-disk" ></span><span class="ui-button-text">
        								Save</span></button>');
$smarty->assign('sPrintBtnDisc','<button class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" id="print_disc" onclick="printReport(\'discharge-info\');"><span class="ui-button-icon-primary ui-icon ui-icon-disk"></span><span class="ui-button-text">
        								Print</span></button>');
$smarty->assign('sCancelBtnDisc','<button class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" id="cancel_disc"><span class="ui-button-icon-primary ui-icon ui-icon-disk"></span><span class="ui-button-text">
        								Cancel</span></button>');

//list of prescription
$smarty->assign('sListPrescription', $m_data);

//list of lab results
$smarty->assign('sListLabRes', $l_data);

//list of rad results
$smarty->assign('sListRadRes', $r_data);

//history
$smarty->assign('sTblHistory', $history);

//PE Exam
$smarty->assign('sSkinP', '<textarea class="SegInput" name="skinP" id="skinP" style="width: 60%">'.$c_data['skin'].'</textarea>');
$smarty->assign('sHeadNeckP', '<textarea class="SegInput" name="head_neckP" id="head_neckP" style="width: 60%">'.$c_data['head_and_neck'].'</textarea>');
$smarty->assign('sChestLungsP', '<textarea class="SegInput" name="chest_lungsP" id="chest_lungsP" style="width: 60%">'.$c_data['chest_lungs'].'</textarea>');
$smarty->assign('sLungsP', '<textarea class="SegInput" name="lungsP" id="lungsP" style="width: 60%">'.$c_data['lungs'].'</textarea>');
$smarty->assign('sEyeP', '<textarea class="SegInput" name="eyeP" id="eyeP" style="width: 60%">'.$c_data['eye'].'</textarea>');
$smarty->assign('sEarP', '<textarea class="SegInput" name="earP" id="earP" style="width: 60%">'.$c_data['ear'].'</textarea>');
$smarty->assign('sAbdomenP', '<textarea class="SegInput" name="abdomenP" id="abdomenP" style="width: 60%">'.$c_data['abdomen'].'</textarea>');
$smarty->assign('sHeightFt', '<input class="SegInput" type="text" name="height_ft" id="height_ft" size="1" value="'.$v_data['height_ft'].'"/>');
$smarty->assign('sHeightIn', '<input class="SegInput" type="text" name="height_in" id="height_in" size="1" value="'.$v_data['height_in'].'"/>');
$smarty->assign('sWeightKg', '<input class="SegInput" type="text" name="weight" id="weight" size="1" value="'.$v_data['weight'].'"/>');
$smarty->assign('sBuild', '<textarea class="SegInput" name="build" id="build" style="width: 60%">'.$c_data['build'].'</textarea>');
$smarty->assign('sLungs', '<input class="SegInput" type="text" name="lungs" id="lungs" style="width: 60%;" value="'.$c_data['lungs'].'"/>');
$smarty->assign('sVision', '<textarea class="SegInput" name="vision" id="vision" style="width: 60%">'.$c_data['vision'].'</textarea>');
$smarty->assign('sDeformity', '<textarea class="SegInput" name="deformity" id="deformity" style="width: 60%">'.$c_data['deformity'].'</textarea>');
$smarty->assign('sHeart', '<textarea class="SegInput" name="lungsP" id="heart" style="width: 60%">'.$c_data['heart'].'</textarea>');
$smarty->assign('sPrevHosp', '<textarea class="SegInput" name="previous_hosp" id="previous_hosp" style="width: 60%">'.$c_data['previous_hosp'].'</textarea>');
$smarty->assign('sRemarks', '<textarea class="SegInput" name="remarks" id="remarks" style="width: 60%">'.$c_data['remarks'].'</textarea>');
$smarty->assign('sBPSysP', '<input class="SegInput" type="text" name="bp_sysp" id="bp_sysp" size="5" value="'.$v_data['systole'].'"/>');
$smarty->assign('sBPDiaP', '<input class="SegInput" type="text" name="bp_diap" id="bp_diap" size="5" value="'.$v_data['diastole'].'"/>');

$smarty->assign('sSaveBtnPE','<button class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" onclick="savePE();" id="save_disc"><span class="ui-button-icon-primary ui-icon ui-icon-disk" ></span><span class="ui-button-text">
        								Save</span></button>');
$smarty->assign('sPrintBtnPE','<button class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" id="print_disc" onclick="printReport(\'PE\');"><span class="ui-button-icon-primary ui-icon ui-icon-disk"></span><span class="ui-button-text">
        								Print</span></button>');
$smarty->assign('sCancelBtnPE','<button class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" id="cancel_disc"><span class="ui-button-icon-primary ui-icon ui-icon-disk"></span><span class="ui-button-text">
        								Cancel</span></button>');

$smarty->assign('bHideTitleBar',TRUE);
$smarty->assign('bHideCopyright',TRUE);
$smarty->assign('sMainBlockIncludeFile','clinics/seg-compre-discharge.tpl');
$smarty->display('common/mainframe.tpl');
?>

<input type="hidden" id="encounter_nr" name="encounter_nr" value="<?=$_GET['encounter_nr']?>"/>
<input type="hidden" id="pid" name="pid" value="<?=$_GET['pid']?>"/>
<input type="hidden" id="vitalsign_no" value="<?=$v_data['vitalsign_no']?>"/>
<input type="hidden" id="encounter_type" value="<?=$p_data['encounter_type']?>"/>
<input type="hidden" id="gender" value="<?=$p_data['sex']?>"/>
<input type="hidden" id="dr_nr" value="<?=$p_data['attending_physician_nr']?>"/>