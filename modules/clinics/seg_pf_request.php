<?
/*created by mai 08-19-2014*/

error_reporting(E_COMPILE_ERROR | E_CORE_ERROR | E_ERROR);
require('./roots.php');
require($root_path.'include/inc_environment_global.php');

define('NO_2LEVEL_CHK',1);
$local_user='ck_pflege_user';

require_once($root_path.'include/inc_front_chain_lang.php');
require_once($root_path.'gui/smarty_template/smarty_care.class.php');
require_once($root_path.'modules/clinics/ajax/seg_pf_request.common.php');
require_once($root_path.'include/care_api_classes/billing/class_ops.php');
require_once($root_path.'include/care_api_classes/class_department.php');
require_once($root_path.'include/care_api_classes/class_oproom.php');
require_once($root_path.'include/inc_date_format_functions.php');
require_once($root_path.'include/care_api_classes/class_person.php');
require_once($root_path.'include/care_api_classes/class_personell.php');
require_once($root_path.'include/care_api_classes/class_encounter.php');
require_once($root_path.'include/care_api_classes/class_social_service.php');
require_once($root_path.'include/care_api_classes/class_ward.php');
require_once($root_path.'include/care_api_classes/class_company.php');
require_once($root_path.'include/care_api_classes/class_pf_charge.php');

$pf_obj = new Pf_charge();
$enc_obj=new Encounter;
$seg_department = new Department();
$seg_room = new OPRoom();
$seg_ops = new SegOps();
$comp_obj = new Company();
$pers_obj = new Personell;

$smarty = new Smarty_Care('common');
$smarty->assign('sToolbarTitle',"Clinics :: Professional Fee Request");
$smarty->assign('sWindowTitle',"Clinics :: Professional Fee Request");

$breakfile = 'javascript:window.parent.cClick();';
$smarty->assign('breakfile', $breakfile);
ob_start();
?>

<link rel="stylesheet" href="<?=$root_path?>modules/or/css/or_main.css" type="text/css" />
<script type="text/javascript" src="<?=$root_path?>modules/or/js/flexigrid/lib/jquery/jquery.js"></script>
<script>var J = jQuery.noConflict();</script>
<link rel="stylesheet" href="<?=$root_path?>modules/or/css/select_or_request.css" type="text/css" />
<link rel="stylesheet" type="text/css" href="<?=$root_path?>modules/or/js/flexigrid/css/flexigrid/flexigrid.css">
<script type="text/javascript" src="<?=$root_path?>js/jsprototype/prototype.js"></script>
<script type="text/javascript" src="<?=$root_path?>modules/or/js/flexigrid/flexigrid.js"></script>
<link rel="stylesheet" href="<?=$root_path?>modules/or/css/select_or_request.css" type="text/css" />
<link rel="stylesheet" type="text/css" media="all" href="<?=$root_path?>js/jscalendar/calendar-win2k-cold-1.css">
<script type="text/javascript" src="<?=$root_path?>js/jscalendar/calendar.js"></script>
<script type="text/javascript" src="<?=$root_path?>js/jscalendar/calendar-setup_3.js"></script>
<script type="text/javascript" src="<?=$root_path?>js/jscalendar/lang/calendar-en.js"></script>
<script type="text/javascript" src="<?=$root_path?>js/overlibmws/iframecontentmws.js"></script>
<script type="text/javascript" src="<?=$root_path?>js/overlibmws/overlibmws.js"></script>
<script type="text/javascript" src="<?=$root_path?>js/overlibmws/overlibmws_draggable.js"></script>
<script type="text/javascript" src="<?=$root_path?>js/overlibmws/overlibmws_filter.js"></script>
<script type="text/javascript" src="<?=$root_path?>js/overlibmws/overlibmws_overtwo.js"></script>
<script type="text/javascript" src="<?=$root_path?>js/overlibmws/overlibmws_scroll.js"></script>
<script type="text/javascript" src="<?=$root_path?>js/overlibmws/overlibmws_shadow.js"></script>
<script type="text/javascript" src="<?=$root_path?>js/overlibmws/overlibmws_modal.js"></script>
<link rel="stylesheet" type="text/css" href="<?=$root_path?>modules/or/js/jqmodal/jqModal.css">
<script type="text/javascript" src="<?=$root_path?>modules/or/js/jqmodal/jqModal.js"></script>
<script type="text/javascript" src="<?=$root_path?>modules/or/js/jqmodal/jqDnR.js"></script>
<script type="text/javascript" src="<?=$root_path?>modules/or/js/jqmodal/dimensions.js"></script>
<script type="text/javascript" src="<?=$root_path?>modules/or/js/jquery.blockUI.js"></script>
<script type="text/javascript" src="<?=$root_path?>js/NumberFormat154.js"></script>
<script type="text/javascript" src="<?=$root_path?>modules/clinics/js/pf_request_gui.js"></script>
<?
$xajax->printJavascript($root_path.'classes/xajax-0.2.5');

$sTemp = ob_get_contents();
ob_end_clean();
$smarty->append('JavaScript',$sTemp);

if(isset($_POST['submitted'])){
    $_POST['trans_source']='DOC'; 
    $_POST['trans_type']="CHARGE";
    $_POST['grant']=$_POST['grant_type'];

    if($_POST['grant_type']=="company"){
        $_POST['grant_type']="";
    }

    $saveok_cnt = 0;
    $no_items = 0;

    foreach($_POST["doc_nr"] as $i=>$item){
        $doctor_nr[] = $_POST["doc_nr"][$i];
        $doctor_fee[] = $_POST["doc_fee"][$i];
    }

    if($_POST['mode'] == 'new'){
      $refno = $pf_obj->getPfRefno(date('Y-m-d H:i:s'));
    }else{
      $refno = $_POST['refno'];
    }

    $array = array(
          'refno'=> $refno,
          'chrge_dte' => $_POST['transaction_date'],
          'encounter_nr' => $_POST['encounter_nr'],
          'pid' => $_POST['pid'],
          'is_cash' => $_POST['transaction_type'],
          'request_source'=>$_POST['area'],
          'chrg_amount'=>$doctor_fee,
          'dr_nr'=>$doctor_nr
      );

    if($_POST['mode'] == 'new'){
        $saveok = $pf_obj->savePfCharges($array);
    }else if($_POST['mode'] == 'edit'){
         $saveok = $pf_obj->updatePfCharges($array);
    }
    
    $saveok1 = TRUE;
    $_POST['refno'] = $refno;
    
    if($_POST['grant'] == 'company'){
      $saveok1=$comp_obj->saveChargetoCompanyTransaction($_POST);
    }


    if($saveok) $saveok_cnt++;
   
    if($saveok_cnt==0 && !$saveok1){
       $smarty->assign('sysErrorMessage','<strong>Error:</strong> Cannot save professional fee charges. '.$pf_obj->getErrorMsg().'\nSQL:'.$pf_obj->getLastQuery());
    }else if($saveok_cnt>0 && $saveok1){
       $smarty->assign('sysInfoMessage','Professional fee charges successfully submitted.');
    }else{
       $smarty->assign('sysErrorMessage','<strong>Error:</strong> Cannot save Professional fee charges');
    }
}

$personell_nr = $pers_obj->getPersonellNrWithLoginID();

$smarty->assign('form_start', '<form name="main_or_form" method="POST" action="'.$_SERVER['PHP_SELF'].'">');
$smarty->assign('form_end', '</form>');

$transaction_date_display = isset($_POST['transaction_date']) ? date('F d, Y h:ia', strtotime($_POST['transaction_date'])) : date('F d, Y h:ia');
$transaction_date = isset($_POST['transaction_date']) ? date('Y-m-d H:i', strtotime($_POST['transaction_date'])) : date('Y-m-d H:i');
$smarty->assign('transaction_date_display', '<div id="transaction_date_display" class="date_display">'.$transaction_date_display.'</div>');
$smarty->assign('transaction_date', '<input type="hidden" name="transaction_date" id="transaction_date" value="'.$transaction_date.'" />');
$smarty->assign('transaction_date_picker', '<img src="'.$root_path.'images/or_main_images/date_time_picker.png" id="transaction_date_picker" class="date_time_picker" />');
$smarty->assign('transaction_date_calendar_script', setup_calendar('transaction_date_display', 'transaction_date', 'transaction_date_picker'));

$pid = isset($_POST['pid']) ? $_POST['pid'] : $_GET['pid'];
$seg_person = new Person($pid);
$person_info = $seg_person->getAllInfoArray();
$middle_initial = (strnatcasecmp($person_info['name_middle'][0], $person_info['name_middle'][1]) == 0) ? ucwords(substr($person_info['name_middle'], 0, 2)) : strtoupper($person_info['name_middle'][0]);
$person_name = ucwords($person_info['name_last']) . ', ' . ucwords($person_info['name_first']) . ' ' . $middle_initial;

$smarty->assign('pid', '<input type="hidden" name="pid" id="pid" value="'.$pid.'" />');
$smarty->assign('patient_name', $person_name);

$encounter_types = array("1"=>"ER PATIENT", "2"=>'OUTPATIENT', "3"=>'INPATIENT (ER)', "4"=>'INPATIENT (OPD)', "5"=>'DIALYSIS', "6"=>'INDUSTRIAL CLINIC');
$encounter_nr = isset($_POST['encounter_nr']) ? $_POST['encounter_nr'] : $_GET['encounter_nr'];
$seg_encounter = new Encounter();
$encounter_details = $seg_encounter->getEncounterInfo($encounter_nr);
$encounter_type = $encounter_types[$encounter_details['encounter_type']];

if (($encounter_nr)||($pid)){
        $discountid = $encounter_details['discountid'];
        $discount = $encounter_details['discount'];
}

if (($encounter_details['encounter_type']==2)||($encounter_details['encounter_type']==1))
    $impression = $encounter_details['chief_complaint'];
elseif (($encounter_details['encounter_type']==3)||($encounter_details['encounter_type']==4))
    $impression = $encounter_details['er_opd_diagnosis'];
    
if (!$impression) {
    $impression = '';
    $impression = $enc_obj->getLatestImpression($pid, $encounter_nr);
}

$smarty->assign('encounter_type', $encounter_type);

$social_service = new SocialService();
if($encounter_type == 'OUTPATIENT'){
    $social_service_details = $social_service->getLatestClassificationByPid($pid);    
}else{
    $social_service_details = $social_service->getLatestClassificationByPid($encounter_nr,0);
}
$is_sc = ($social_service_details['discountid'] == 'SC') ? '1' : '0';

if ($_GET['view_from'])
    $view_from = $_GET['view_from'];

 if ($view_from=='ssview'){
     if ($_GET['discountid'] || $_POST['discountid']){
         $discountid = ($_GET['discountid']) ? $_GET['discountid'] : $_POST['discountid'];
         $infoSS = $social_service->getSSClassInfo($discountid);

         if ($infoSS['parentid'])
                $discountid = $infoSS['parentid'];
         else
                $discountid = $discountid;

         $discount = $infoSS['discount'];
     }

 }else{
      $view_from = '';
 }
        

    $infoSS2 = $social_service->getSSClassInfo($discountid);

    if (($infoSS2['parentid'])&&($infoSS2['parentid']=='D'))
        $discountid2 = $infoSS2['parentid'];
    else
        $discountid2 = $discountid;
    
$smarty->assign('view_from','<input type="hidden" name="view_from" id="view_from" value="'.$view_from.'" />');
$smarty->assign('sClassification',($discountid2) ? $discountid2:'None');
$smarty->assign('discount','<input type="hidden" name="discount" id="discount" value="'.$discount.'" /><input type="hidden" name="discountid" id="discountid" value="'.$discountid2.'" />'); 

$disabled = "";

if(strtolower($area)=='doctor'){
  $disabled = "disabled";
}

$smarty->assign('add_misc_btn', '<button class="segButton" '.$disabled.' onclick="show_popup_misc();return false;" id="add_misc_btn"><img src="'.$root_path.'gui/img/common/default/rx.png"/>Add Doctor</button>');
$smarty->assign('empty_misc_btn', '<button class="segButton" '.$disabled.' onclick="empty_misc();return false;" id="empty_misc_btn"><img src="'.$root_path.'gui/img/common/default/delete.png"/>Empty</button>');
$smarty->assign('other_charges_submit', '<input type="button" id="or_main_submit" value="" onclick="validate(); return false;"/>');
$smarty->assign('other_charges_cancel', '<a href="'.$breakfile.'" id="or_main_cancel"></a>');
$smarty->assign('sBtnDiscounts','<img name="btndiscount" id="btndiscount" onclick="validate(); return false;" style="cursor:pointer" src="'.$root_path.'images/btn_discounts2.gif" border="0">');

$smarty->assign('submitted', '<input type="hidden" value="TRUE" name="submitted" />');
$smarty->assign('encounter_nr', '<input type="hidden" name="encounter_nr" id="encounter_nr" value="'.$encounter_nr.'" />');
$mode = $_GET['mode']?$_GET['mode']:'edit'; 
$smarty->assign('mode', '<input type="hidden" id="mode" name="mode" value="'.$mode.'"/>');
$area = $_GET['area']?$_GET['area']:$_POST['area'];
$smarty->assign('area', '<input type="hidden" id="area" name="area" value="'.$area.'"/><input type="hidden" id="personell_nr" name="pers_obj" value="'.$personell_nr.'">');
$smarty->assign('impression', '<input type="hidden" id="impression" name="impression" value="'.$impression.'"/>');

if($mode=='edit') {
	$misc_refno = $_GET['refno']?$_GET['refno']:$refno;
}
$smarty->assign('reference_no', '<input type="text" class="segInput" readonly="readonly" id="refno" name="refno" value="'.$misc_refno.'"/><input id="current_dept_nr" type="hidden" name="current_dept_nr" value="'.$encounter_details['current_dept_nr'].'"/>');

$row['is_cash'] = 1;

$pf_res = $pf_obj->getPfRequestsByRefno($misc_refno);
$row = $pf_res->FetchRow();

if($mode=='edit'){
    if($row['is_cash']==1)
        $iscash = 1;
    elseif ($row['is_cash']==0)
        $iscash = 0;    
}else{
    if($encounter_details['encounter_type']==2){
        $iscash = 1;    
    }else
        $iscash = 0;  
}

$disabled="";
if($mode == 'edit' && strtolower($area) == 'doctor'){
  $disabled = "disabled";
}

$smarty->assign('sIsCash','<input class="jedInput" type="radio" name="iscash" id="iscash1" '.$disabled.' value="1" '.(($iscash!=0)?'checked="checked" ':'').' onclick="if(($('."'area')".'.value).toLowerCase() !='."'doctor'".'){ if (warnClear()) { empty_misc(); changeTransaction(this.value); return true;} else return false;} else changeTransaction(this.value);" /><label for="iscash1" class="jedInput">Cash</label>');
$smarty->assign('sIsCharge','<input class="jedInput" type="radio" name="iscash" id="iscash0" '.$disabled.' value="0" '.(($iscash==0)?'checked="checked" ':'').' onclick="if(($('."'area')".'.value).toLowerCase() !='."'doctor'".'){ if (warnClear()) { empty_misc(); changeTransaction(this.value); return true;} else return false;} else changeTransaction(this.value);" style="margin-left:10px" /><label class="jedInput" for="iscash0">Charge</label>');

//added by mai 07-19-2014
if($encounter_nr){
	$comp_info=$comp_obj->forCostCenterInfo($encounter_nr);
	if($comp_info){
            while($row=$comp_info->FetchRow()){
                $comp_name = $row['comp_name'];
                $comp_id = $row['comp_id'];
            }
    }

    $trans_amount = $comp_obj->hasChargeToCompany($encounter_nr, 'DOC', $refno);
    $smarty->assign('sCompName', $comp_name);
}

$smarty->assign('sChargeTyp','
<select id="grant_type"  name="grant_type" '.$disabled.' onchange="if(($('."'area')".'.value).toLowerCase() != '."'doctor'".'){if(warnClear()){empty_misc(); changeTransaction(0); return true; } else return false; }else{ changeTransaction(0);}">
	<option value="">PERSONAL</option>
</select>
<input type="hidden" value="'.$trans_amount.'" id="trans_amount">
<input type="hidden" id="comp_id" name="comp_id" value="'.$comp_id.'">
<input type="hidden" name="charge_comp_balance" id="charge_comp_balance">'); 
//end added by mai

$smarty->assign('transaction_type', '<input type="hidden" name="transaction_type" id="transaction_type" value="'.$iscash.'" />');
$smarty->assign('bHideTitleBar',TRUE);
$smarty->assign('bHideCopyright',TRUE);
$smarty->assign('sMainBlockIncludeFile','clinics/seg_pf_request_tray.tpl'); //Assign the or_main template to the frameset
$smarty->display('common/mainframe.tpl'); //Display the contents of the frame

function setup_calendar($display_area, $input_field, $button) {
	global $root_path;
	$calendar_script =
		'<script type="text/javascript">
			 Calendar.setup ({
				 displayArea : "'.$display_area.'",
				 inputField : "'.$input_field.'",
				 ifFormat : "%Y-%m-%d %H:%M",
				 daFormat : "%B %e, %Y %I:%M%P",
				 showsTime : true,
				 button : "'.$button.'",
				 singleClick : true,
				 step : 1
			 });
			</script>';
	return $calendar_script;
}
?>