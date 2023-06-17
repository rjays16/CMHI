<?php

//created by mai 11/2/2014
require('./roots.php');
require($root_path.'include/inc_environment_global.php');

define('NO_2LEVEL_CHK',1);
$local_user='ck_pflege_user';

require_once($root_path.'include/inc_front_chain_lang.php');
require_once($root_path.'gui/smarty_template/smarty_care.class.php');
require_once($root_path.'include/care_api_classes/class_patient_queue.php');

$smarty = new smarty_care('common');
$queue_obj = new Patient_queue();

if($_GET['user_origin']){
	 switch($_GET['user_origin']){
	 	case 'billing':
	 		$breakfile=$root_path.'modules/billing/bill-main-menu.php'.URL_APPEND;
	 		break;
	 	case 'ipd':
	 		$breakfile=$root_path.'modules/ipd/seg-ipd-functions.php'.URL_APPEND;
	 		break;
	 	case 'opd':
	 		$breakfile=$root_path.'modules/opd/seg-opd-functions.php'.URL_APPEND;
	 		break;
	 	case 'er':
	 		$breakfile=$root_path.'modules/opd/seg-opd-functions.php'.URL_APPEND;
	 		break;
	 	case 'medocs':
	 		$breakfile=$root_path.'modules/medocs/seg-medocs-functions.php'.URL_APPEND;
	 		break;
	 	case 'nursing':
	 		$breakfile=$root_path.'modules/nursing/nursing.php'.URL_APPEND;
	 		break;
	 	case 'cashier':
	 		$breakfile= $root_path.'modules/cashier/seg-cashier-functions.php'.URL_APPEND;
	 		break;
	 	default:
	 		$breakfile=$root_path.$HTTP_SESSION_VARS['sess_path_referer'].URL_APPEND;
	 }
}else{
	$break_file = $root_path.'main/startframe.php'.URL_APPEND;
}

$smarty->assign('breakfile', $breakfile);
$smarty->assign('sWindowTitle',"List of Patients");
$smarty->assign('sToolbarTitle',"List of Patients");
?>

<script>
	function openInfo(encounter_nr){
		window.location.href = '<?=$root_path?>modules/registration_admission/aufnahme_daten_zeigen.php?ntid=false&lang=en&encounter_nr='+encounter_nr+'&origin=cashier&ptype=opd';
	}
</script>

<?php
$queue_dr = $queue_obj->getDr();

if($queue_dr){
	while($row_dr = $queue_dr->FetchRow()){
		$list .= '<table>';
		
		$list .= '<tr class="segPanelHeader"><td colspan="4">'.$row_dr['dr_name'].'</td></tr>';
		$count = 1;
		$queue_patient = $queue_obj->getPatients($row_dr['dr_nr']);
		if($queue_patient){
			while($row_patient = $queue_patient->FetchRow()){
				$list .= '<tr class=" segPanel" align="left" valign="top">';
				$list .= '<td width="5%">'.$count++.'</td>';
				$list .= '<td width="*">'.$row_patient['patient_name'].'</td>';
				$list .= '<td width = "20%" class="'.$row_patient['queue_status'].'">'.strtoupper($row_patient['queue_status']).'</td>';
				$list .= "<td width='5%' align='center'><img onclick='openInfo(\"".$row_patient['encounter_nr']."\");' style='cursor: pointer;' src = '".$root_path."images/cashier_edit.gif' title='details'></td>";;
				$list .='</tr>';
			}
		}
		$list .= '</table>';
	
	}
}

$smarty->assign('sList', $list);
$smarty->assign('sMainBlockIncludeFile','registration_admission/list-patient-queue.tpl');
$smarty->display('common/mainframe.tpl');
?>