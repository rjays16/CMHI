<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/inc_environment_global.php');
/**
* CARE2X Integrated Hospital Information System Deployment 2.1 - 2004-10-02
* GNU General Public License
* Copyright 2002,2003,2004,2005 Elpidio Latorilla
* elpidio@care2x.org, 
*
* See the file "copy_notice.txt" for the licence notice
*/
$lang_tables=array('departments.php');
define('LANG_FILE','doctors.php');

/*
switch($HTTP_SESSION_VARS['sess_user_origin'])
{
	case 'personell_admin':
		$local_user='aufnahme_user';
		$breakfile=$root_path.'modules/personell_admin/personell_register_show.php'.URL_APPEND.'&target=personell_reg&personell_nr='.$nr;
		break;
		
	case 'calendar_opt':
		define('NO_2LEVEL_CHK',1);
		$breakfile = $root_path."modules/calendar/calendar-options.php".URL_APPEND."&year=$year&month=$month&day=$day&forceback=1";
		break;
		
	default:
		$local_user='ck_op_dienstplan_user';
		if (!empty($HTTP_SESSION_VARS['sess_path_referer'])){
			$breakfile=$root_path.$HTTP_SESSION_VARS['sess_path_referer'].URL_APPEND;
		} else {		
			$breakfile = $root_path.'op-doku.php'.URL_APPEND;
		}
}

*/

define('NO_2LEVEL_CHK',1);
$breakfile = $root_path."modules/laboratory/labor.php".URL_APPEND."";

require_once($root_path.'include/inc_front_chain_lang.php');

/*
switch($retpath)
{
	case "docs": $breakfile='doctors.php'.URL_APPEND; break;
	case "op": $breakfile='op-doku.php'.URL_APPEND; break;
}


if(empty($pday)) $pday=date('j');
if(empty($pmonth)) $pmonth=date('n');
if(empty($pyear)) $pyear=date('Y');
$abtarr=array();
$abtname=array();
$datum=date('d.m.Y');

*/

/*if(!$hilitedept)
{
	if($dept_nr) $hilitedept=$dept_nr;
	else
	{
		include($root_path.'include/inc_resolve_dept_dept.php');
		if($deptOK) $hilitedept=$dept_nr;
	}
}*/
/* Load the department list with oncall doctors */

require_once($root_path.'include/care_api_classes/class_department.php');
$dept_obj=new Department;
$dept_DOC=$dept_obj->getAllActiveWithDOC();
#$dept_DOC=$dept_obj->getAllCommonMedical();

if ($_GET['target']=='labservadmin') {
	$title = 'Laboratory::Services admin';
	$fileforward='seg-lab-services-admin.php'.URL_APPEND;
}
elseif ($_GET['target']=='labservparam') {
	$title = 'Laboratory::Paramters';
	$fileforward='seg-lab-services-param.php'.URL_APPEND;
}
else {
	$title = 'Laboratory::New test request';
	$fileforward='seg-lab-test-request.php'.URL_APPEND;
}

# Start Smarty templating here
 /**
 * LOAD Smarty
 */

 # Note: it is advisable to load this after the inc_front_chain_lang.php so
 # that the smarty script can use the user configured template theme

 require_once($root_path.'gui/smarty_template/smarty_care.class.php');
 $smarty = new smarty_care('common');

# Title in toolbar
 $smarty->assign('sToolbarTitle',$title);

 # href for help button
 $smarty->assign('pbHelp',"javascript:gethelp('dept_select.php')");

 # href for close button
 $smarty->assign('breakfile',$breakfile);       

 # Window bar title
 $smarty->assign('sWindowTitle',$title);

$smarty->assign('sMascotImg','<img '.createMascot($root_path,'mascot1_r.gif','0','bottom').' align="absmiddle">');
$smarty->assign('LDPlsSelectDept',$LDPlsSelectDept);

 # Buffer department rows output
 ob_start();

$toggler=0;

while(list($x,$v)=each($dept_DOC)){

	$bold='';
	$boldx='';
	if($hilitedept==$v['nr']) 	{ echo '<tr class="hilite">'; $bold="<font color=\"red\" size=2><b>";$boldx="</b></font>"; }
	else
		if ($toggler==0)
			{ echo '<tr class="wardlistrow1">'; $toggler=1;}
				else { echo '<tr class="wardlistrow2">'; $toggler=0;}
	echo '<td>&nbsp;'.$bold;
	if(isset($$v['LD_var'])&&!empty($$v['LD_var'])) echo $$v['LD_var'];
		else echo $v['name_formal'];
	echo $boldx.'&nbsp;</td>';
	
	# Edit by AJMQ
	if (isset($_GET['asdf'])) {
		$target = 'window.opener.document';
		$nameEl = 'dept_name';
		$idEl = 'dept_nr';
		echo '<td >&nbsp;<img '.createLDImgSrc($root_path,'ok_small.gif','0','absmiddle').' alt="'.$LDShowActualPlan.'" style="cursor:pointer" ' .
			'onclick="'.$target.'.getElementById(\''.$nameEl.'\').value=\''.$v['name_formal'].'\';' . 
			$target.'.getElementById(\''.$idEl.'\').value=\''.$v['nr'].'\';' . 
			'window.close();'.
			'"></td></tr>';
	}
	else	{
		echo '<td >&nbsp; <a href="'.$fileforward.'&dept_nr='.$v['nr'].'&nr='.$nr.'&dept_name='.urlencode($v['name_formal']).'">
		<img '.createLDImgSrc($root_path,'ok_small.gif','0','absmiddle').' alt="'.$LDShowActualPlan.'" ></a> </td></tr>';
	}
	echo "\n";
}

	$sTemp = ob_get_contents();
	ob_end_clean();

# Assign the dept rows  to the frame template

$smarty->assign('sDeptRows',$sTemp);

# Edit by AJMQ
if (isset($_GET['asdf'])) {
	$smarty->assign('sBackLink','<img '.createLDImgSrc($root_path,'close2.gif','0').' alt="'.$LDCloseAlt.'" style="cursor:pointer" onclick="window.close()">');
}
else {
	$smarty->assign('sBackLink','<a href="'.$breakfile.'"><img '.createLDImgSrc($root_path,'close2.gif','0').' alt="'.$LDCloseAlt.'">');
}

$smarty->assign('sMainBlockIncludeFile','laboratory/select_dept.tpl');

 /**
 * show Template
 */
 $smarty->display('common/mainframe.tpl');
?>