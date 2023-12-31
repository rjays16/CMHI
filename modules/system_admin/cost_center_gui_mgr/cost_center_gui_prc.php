<?php
error_reporting(E_COMPILE_ERROR | E_CORE_ERROR | E_ERROR);  //set the error level reporting
require('./roots.php'); //traverse the root= directory
define('NO_2LEVEL_CHK',1);
define('LANG_FILE','products.php');
$local_user='ck_op_pflegelogbuch_user'; //I don't get this, but it has something to do with page authorization access
require($root_path.'include/inc_environment_global.php');
require_once($root_path.'include/inc_front_chain_lang.php');
require_once($root_path.'gui/smarty_template/smarty_care.class.php'); //load the extended smarty template
require_once($root_path . 'modules/system_admin/ajax/cost-center-gui-mgr.common.php');
require_once($root_path.'include/care_api_classes/class_gui_cost_center_mgr.php'); //load the CostCenterGuiMgr class
$target = $_GET['target'];

$smarty = new Smarty_Care('common');
$smarty->assign('sToolbarTitle',"Cost Center Service Price :: Manager"); //Assign a toolbar title
$page=0;
$smarty->assign('sOnLoadJs','onLoad="startAJAXSearch(\''.$page.'\'); "');

ob_start();
?>

<link rel="stylesheet" href="<?= $root_path ?>modules/system_admin/cost_center_gui_mgr/cost_center_mgr.css" type="text/css" />
<script type="text/javascript" src="<?= $root_path ?>js/jquery/jquery.js"></script>
<script>var J = jQuery.noConflict();</script>
<script type="text/javascript" src="<?= $root_path ?>modules/or/js/jquery.tabs/jquery.tabs.pack.js"></script>
<link rel="stylesheet" type="text/css" href="<?= $root_path ?>modules/or/js/jquery.tabs/jquery.tabs.css" />
<script type="text/javascript" src="<?= $root_path ?>js/overlibmws/iframecontentmws.js"></script>
<script type="text/javascript" src="<?= $root_path ?>js/overlibmws/overlibmws.js"></script>
<script type="text/javascript" src="<?= $root_path ?>js/overlibmws/overlibmws_draggable.js"></script>
<script type="text/javascript" src="<?= $root_path ?>js/overlibmws/overlibmws_filter.js"></script>
<script type="text/javascript" src="<?= $root_path ?>js/overlibmws/overlibmws_overtwo.js"></script>
<script type="text/javascript" src="<?= $root_path ?>js/overlibmws/overlibmws_scroll.js"></script>
<script type="text/javascript" src="<?= $root_path ?>js/overlibmws/overlibmws_shadow.js"></script>
<script type="text/javascript" src="<?= $root_path ?>js/overlibmws/overlibmws_modal.js"></script>
<script type="text/javascript" src="<?= $root_path ?>js/jsprototype/prototype.js"></script>
<script type="text/javascript" src="<?= $root_path ?>js/jquery/ui/jquery.ui.core.js"></script>
<script type="text/javascript" src="<?= $root_path ?>js/jquery/ui/jquery.ui.widget.js"></script>
<script type="text/javascript" src="<?= $root_path ?>js/jquery/ui/jquery.ui.mouse.js"></script>
<script type="text/javascript" src="<?= $root_path ?>js/jquery/ui/jquery.ui.sortable.js"></script>
<script type="text/javascript" src="<?= $root_path ?>modules/system_admin/js/gui-mgr-functions.js"></script>

<style type="text/css">
	#sortable1 { list-style-type: none; margin: 0; padding: 0; float: left; margin-right: 10px; }
	##sortable1 li { margin: 0 5px 5px 5px; padding: 5px; font-size: 1.2em; width: 120px; }
</style>


<script type="text/javascript">


J().ready(function() {
	J('#new_package').tabs();
});
</script>

<?php


$xajax->printJavascript($root_path.'classes/xajax_0.5');
$javascript = ob_get_contents();
ob_end_clean();
$smarty->append('JavaScript',$javascript);


$breakfile=$root_path."main/spediens.php".URL_APPEND;
if (isset($_POST['is_submitted']))
{

	$guiObj = new CostCenterGuiMgr();
	if($guiObj->saveGuiMgr($_POST))
	{
		$smarty->assign('sysInfoMessage','GUI details successfully saved');
	}
	else {
		 $smarty->assign('sysErrorMessage','Error in saving the GUI details.');
	}
}

#$smarty->assign('form_start', '<form name="package_form" method="POST" action="'.$_SERVER['PHP_SELF'].'" onsubmit="return validate()">');


$smarty->assign('package_submit', '<input type="submit" id="package_submit" value="" />');
$smarty->assign('package_cancel', '<a href="'.$breakfile.'" id="package_cancel"></a>');
$smarty->assign('is_submitted', '<input type="hidden" name="is_submitted" value="TRUE" />');
ob_start();
?>
<input type="hidden" name="sid" value="<?php echo $sid ?>">
<input type="hidden" name="lang" value="<?php echo $lang ?>">
<input type="hidden" name="userck" value="<?php echo $userck ?>">
<input type="hidden" name="key" id="key">
<input type="hidden" name="pagekey" id="pagekey">
<?
$sTemp = ob_get_contents();
ob_end_clean();
$smarty->assign('breakfile',$breakfile); //Close button
$smarty->assign('sMainBlockIncludeFile','system_admin/cost_center_gui_mgr/price_menu.tpl'); //Assign the new_package template to the frameset
$smarty->assign('sMainFrameBlockData',$sTemp);
$smarty->display('common/mainframe.tpl'); //Display he contents of the frame
