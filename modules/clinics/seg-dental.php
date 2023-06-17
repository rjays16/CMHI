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
require_once($root_path.'include/care_api_classes/class_dental.php');
require_once($root_path.'modules/clinics/ajax/seg-dental.common.php');

$smarty = new Smarty_Care('common');
$enc = new Encounter();
$dental = new Dental();

$breakfile = 'javascript:window.parent.cClick();';
$smarty->assign('breakfile', $breakfile);
ob_start();
?>

<?
//get tooth count
$dental_res = $dental->getAlltooth();

for($i=0; $i<count($dental_res); $i++){
	$dental_data .= "<option value='".$dental_res[$i]."'>".$dental_res[$i]."</option>";
}

//get medical history
$med_hist = $dental->selectMedicalHistory($_GET['encounter_nr']);

//get patient data
$p_data = $enc->getEncounterInfo($_GET['encounter_nr']);
$address = $p_data['street_name']." ".$p_data["brgy_name"]." ".$p_data["mun_name"]." ".$p_data["prov_name"];
$gender = ($p_data['sex'] == 'm' ? 'Male' : 'Female');
$patient_name = $p_data['name_first'].' '.$p_data['name_last'];

$smarty->assign('sPatientID', $p_data['pid']);
$smarty->assign('sAge', $p_data['age']);
$smarty->assign('sGender', $gender);
$smarty->assign('sPatientName', $patient_name);
$smarty->assign('sAddress', $address);
$smarty->assign('sBirthdate', $p_data['date_birth']);
$smarty->assign('sContact', $p_data['contact_no']);

?>

<script type="text/javascript" src="<?=$root_path?>js/jsprototype/prototype.js"></script>
<link rel="stylesheet" href="<?= $root_path ?>js/jquery/themes/seg-ui/jquery.ui.all.css" type="text/css" />
<script type="text/javascript" src="<?=$root_path?>js/jquery/jquery.js"></script>
<script type="text/javascript" src="<?=$root_path?>js/jquery/ui/jquery-ui.js"></script>
<script type="text/javascript" src="<?=$root_path?>js/jquery/jquery-1.8.2.js"></script>
<script type="text/javascript" src="js/seg-dental.js"></script>
<script>var $J = jQuery.noConflict();</script>
<?
$xajax->printJavascript($root_path.'classes/xajax_0.5');
$sTemp = ob_get_contents();
ob_end_clean();
$smarty->append('JavaScript',$sTemp);

//comprehensive report
$smarty->assign('sTongue', '<input class="SegInput" type="text" name="tongue" id="tongue" style="width: 60%;" value="'.$med_hist['tongue'].'"/>');
$smarty->assign('sPalate', '<input class="SegInput" type="text" name="palate" id="palate" style="width: 60%;" value="'.$med_hist['palate'].'"/>');
$smarty->assign('sTonsils', '<input class="SegInput" type="text" name="tonsils" id="tonsils" style="width: 60%;" value="'.$med_hist['tonsils'].'"/>');
$smarty->assign('sLips', '<input class="SegInput" type="text" name="lips" id="lips" style="width: 60%;" value="'.$med_hist['lips'].'"/>');
$smarty->assign('sFloorMouth', '<input class="SegInput" type="text" name="floor_mouth" id="floor_mouth" style="width: 60%;" value="'.$med_hist['floor_of_mouth'].'"/>');
$smarty->assign('sCheeks', '<input class="SegInput" type="text" name="cheeks" id="cheeks" style="width: 60%;" value="'.$med_hist['cheeks'].'"/>');
$smarty->assign('sAllergies', '<input class="SegInput" type="text" name="allergies" id="allergies" style="width: 60%;" value="'.$med_hist['allergies'].'"/>');
$smarty->assign('sHeartDisease', '<input class="SegInput" type="text" name="heart_disease" id="heart_disease" style="width: 60%;" value="'.$med_hist['heart_disease'].'"/>');
$smarty->assign('sBloodDys', '<input class="SegInput" type="text" name="blood_dys" id="blood_dys" style="width: 60%;"value="'.$med_hist['blood_dyscracia'].'"/>');
$smarty->assign('sDiabetes', '<input class="SegInput" type="text" name="diabetes" id="diabetes" style="width: 60%;" value="'.$med_hist['diabetes'].'"/>');
$smarty->assign('sKidney', '<input class="SegInput" type="text" name="kidney" id="kidney" style="width: 60%;" value="'.$med_hist['kidney'].'"/>');
$smarty->assign('sLiver', '<input class="SegInput" type="text" name="liver" id="liver" style="width: 60%;"value="'.$med_hist['liver'].'"/>');
$smarty->assign('sOthers', '<input class="SegInput" type="text" name="others" id="others" style="width: 60%;" value="'.$med_hist['others'].'"/>');
$smarty->assign('sHygiene', '<input class="SegInput" type="text" name="hygiene" id="hygiene" style="width: 60%;" value="'.$med_hist['hygiene'].'"/>');

//Tooth
$smarty->assign('sTooth','<select class="SegInput" id="tooth_no" onchange="setVal();">'.$dental_data.'</select>');
$smarty->assign('sOps', ' Operation: <input id="ops" onblur="showSave();" type="text" class="SegInput" size="1">');
$smarty->assign('sCon', ' Condition: <input id="con" onblur="showSave();" type="text" class="SegInput" size="1">');
$smarty->assign('sTooth0', '<input type="checkbox" onchange="showSave();" id="tooth_0" class="toothCheckbox">');
$smarty->assign('sTooth1', '<input type="checkbox" onchange="showSave();" id="tooth_1" class="toothCheckbox">');
$smarty->assign('sTooth2', '<input type="checkbox" onchange="showSave();" id="tooth_2" class="toothCheckbox">');
$smarty->assign('sTooth3', '<input type="checkbox" onchange="showSave();" id="tooth_3" class="toothCheckbox">');
$smarty->assign('sTooth4', '<input type="checkbox" onchange="showSave();" id="tooth_4" class="toothCheckbox">');
$smarty->assign('sSaveTooth', '<span> <img class="SegInput" id="saveB" onclick="ops_con();"style="display: none; cursor:pointer;" title="Save" src="'.$root_path.'images/ok.gif"/></span>');

//Treatment Record
$smarty->assign('sDiagnosis', '<textarea class="SegInput" id="diagnosis" style="width: 60%">'.$med_hist['diagnosis'].'</textarea>');
$smarty->assign('sToothCount', '<input class="SegInput" type="text" name="tooth_count" id="tooth_count" style="width: 60%;" value="'.$med_hist['tooth_count'].'"/>');
$smarty->assign('sDetailsServices', '<textarea class="SegInput" id="details_services" style="width: 60%">'.$med_hist['services'].'</textarea>');
$smarty->assign('sOperator', '<input class="SegInput" type="text" name="operator" id="operator" style="width: 60%;" value="'.$med_hist['checked_by'].'"/>');
$smarty->assign('sCheckedBy', '<input class="SegInput" type="text" name="checked_by" id="checked_by" style="width: 60%;" value="'.$med_hist['operator'].'"/>');

//Buttons
$smarty->assign('sSaveBtnCmp','<button class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" onclick="saveDental();" id="save_compre"><span class="ui-button-icon-primary ui-icon ui-icon-disk" ></span><span class="ui-button-text">
        								Save</span></button>');
$smarty->assign('sPrintBtnCmp','<button class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" id="print_compre" onclick="printDental();"><span class="ui-button-icon-primary ui-icon ui-icon-disk"></span><span class="ui-button-text">
        								Print</span></button>');
$smarty->assign('sCancelBtnCmp','<button class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" id="cancel_compre"><span class="ui-button-icon-primary ui-icon ui-icon-disk"></span><span class="ui-button-text">
        								Cancel</span></button>');

$smarty->assign('bHideTitleBar',TRUE);
$smarty->assign('bHideCopyright',TRUE);
$smarty->assign('sMainBlockIncludeFile','clinics/seg-dental.tpl');
$smarty->display('common/mainframe.tpl');
?>

<input type="hidden" id="encounter_nr" name="encounter_nr" value="<?=$_GET['encounter_nr']?>"/>
<input type="hidden" id="pid" name="pid" value="<?=$_GET['pid']?>"/>