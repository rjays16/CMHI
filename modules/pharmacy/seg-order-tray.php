<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path."modules/pharmacy/ajax/order-tray.common.php");
require($root_path.'include/inc_environment_global.php');
/**
* CARE2X Integrated Hospital Information System Deployment 2.1 - 2004-10-02
* GNU General Public License
* Copyright 2002,2003,2004,2005 Elpidio Latorilla
* elpidio@care2x.org, 
*
* See the file "copy_notice.txt" for the licence notice
*/
define('LANG_FILE','products.php');
define('NO_2LEVEL_CHK',1);
define('LANG_FILE','products.php');
$local_user='ck_prod_db_user';
require_once($root_path.'include/inc_front_chain_lang.php');

//$db->debug=1;

$thisfile=basename(__FILE__);

# Start Smarty templating here
 /**
 * LOAD Smarty
 */

 # Note: it is advisable to load this after the inc_front_chain_lang.php so
 # that the smarty script can use the user configured template theme

 require_once($root_path.'gui/smarty_template/smarty_care.class.php');
 $smarty = new smarty_care('common');

 $smarty->assign('bHideTitleBar',TRUE);
 $smarty->assign('bHideCopyright',TRUE);

 # Title in the title bar
 $smarty->assign('sToolbarTitle',"$title $LDPharmaDb $LDSearch");

 # href for the back button
// $smarty->assign('pbBack',$returnfile);

 # href for the help button
 $smarty->assign('pbHelp',"javascript:gethelp('products_db.php','search','$from','$cat')");

 # href for the close button
 $smarty->assign('breakfile',$breakfile);

 # Window bar title
 $smarty->assign('sWindowTitle',"$title $LDPharmaDb $LDSearch");

 # Assign Body Onload javascript code
	$onLoadJS="onload=\"init();\"";
	$smarty->assign('sOnLoadJs',$onLoadJS);


 # Collect javascript code
 ob_start()

?>
<script language="javascript" >
<!--
var AJAXTimerID=0;
var lastSearch="", lastSearchPage=-1;
var disableInput = <?= $_GET['noinput'] ? "1" : "0" ?>


function init() {
	shortcut.add('ESC', closeMe,
		{
			'type':'keydown',
			'propagate':false,
		}
	);
	
	setTimeout("$('search').focus()",100);
}

function closeMe() {
	window.parent.cClick();
}

function prepareAddEx() {
	var prod = document.getElementsByName('prod[]');
	var qty = document.getElementsByName('qty[]');
	var prcCash = document.getElementsByName('prcCash[]');
	var prcCharge = document.getElementsByName('prcCharge[]');
	var nm = document.getElementsByName('pname[]');
	
	var details = new Object();
	var list = window.opener.document.getElementById('order-list');
	var result=false;
	var msg = "";
	for (var i=0;i<prod.length;i++) {
		result = false;
		if (prod[i].checked) {
			details.id = prod[i].value;
			details.name = nm[i].value;
			details.qty = qty[i].value;
			details.prcCash = prcCash[i].value;
			details.prcCharge = prcCharge[i].value;
			result = window.opener.appendOrder(list,details);
			msg += "     x" + qty[i].value + " " + nm[i].value + "\n";
			qty[i].value = 0;
			prod[i].checked = false;
		}
	}
	window.opener.refreshTotal();
	if (msg)
		msg = "The following items were added to the order tray:\n" + msg;
	else
		msg = "An error has occurred! The selected items were not added...";	
	alert(msg);
}

function startAJAXSearch(searchID, page) {


	var searchEL = $(searchID);
	if (!page) page = 0;

	var last_page;
	/*
	if (window.parent.$('area')) {
		areaSelected = window.parent.$('area').options[window.parent.$('area').selectedIndex].value;
	}
	*/
	var areaSelected = "<?= $_GET['area'] ?>";
	var discountID = <?= $_GET['d'] ? ("'".$_GET['d']."'") : "null" ?>; 
	var pid = <?= $_GET['pid'] ? ("'".$_GET['pid']."'") : "null" ?>; 
	var enc = <?= $_GET['enc'] ? ("'".$_GET['enc']."'") : "null" ?>; //addded by julius 02-07-2017


	
	//if (window.parent.$('discountid')) discountID=window.parent.$('discountid').value;
	// if (searchEL && (lastSearch!=searchEL.value || lastSearchPage!=page)) {
	if (true) {
		searchEL.style.color = "#0000ff";
		if (AJAXTimerID) clearTimeout(AJAXTimerID);
		$("ajax-loading").style.display = "";
		var script = "xajax_populateProductList('"+searchID+"',"+page+",'"+searchEL.value+"'" +
			",'"+pid+"'" +
			",'"+discountID+"'" +
			",'"+areaSelected+"'"+
			",'"+enc+"'"+
			", "+disableInput+")";
		AJAXTimerID = setTimeout(script,200);
		lastSearch = searchEL.value;
		lastSearchPage = page;
	}
}

function endAJAXSearch(searchID) {
	var searchEL = $(searchID);
	if (searchEL) {
		$("ajax-loading").style.display = "none";
		searchEL.style.color = "";
	}
}

// -->
</script> 
<script type="text/javascript" src="<?=$root_path?>modules/pharmacy/js/order-tray-gui.js?t=<?=time()?>"></script>
<script type="text/javascript" src="<?=$root_path?>js/NumberFormat154.js"></script>
<script type="text/javascript" src="<?=$root_path?>js/shortcut.js"></script>
<script type="text/javascript" src="<?=$root_path?>js/jsprototype/prototype.js"></script>

<?php
$xajax->printJavascript($root_path.'classes/xajax');
$sTemp = ob_get_contents();
ob_end_clean();

$smarty->append('JavaScript',$sTemp);

# Buffer page output

ob_start();

?>

	<span id="ajax_display"></span>
	<table width="100%" cellspacing="2" cellpadding="2" style="">
		<tbody>
			<tr>
				<td>
					<div style="padding:4px 2px; padding-left:10px;background-color:#e5e5e5;">
						<table width="100%" border="0" cellpadding="0" cellspacing="2">
							<tr>
								<td width="12%" nowrap="nowrap"><span style="font:bold 12px Arial;color:#2d2d2d;">Search product</span></td>
								<td width="40%"><input id="search" class="jedInput" type="text" style="width:95%; margin-left:10px; font: bold 12px Arial" align="absmiddle" onkeyup="if (event.keyCode==13) startAJAXSearch(this.id)" /></td>
								<td width="*">
									<button class="segButton" onclick="startAJAXSearch('search');return false;"><img src="<?= $root_path ?>gui/img/common/default/magnifier.png"/>Search</button>
									<button class="segButton" onclick="prepareAddExternal();return false;"><img src="<?= $root_path ?>gui/img/common/default/pill_add.png"/>Add External</button> <!-- added by mai 10-02-2014 -->
								</td>
							</tr>
						</table>
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<div style="display:block; border:1px solid #0d4688; overflow-y:scroll; height:290px; width:100%; background-color:#e5e5e5">
						<table id="product-list" class="jedList" cellpadding="1" cellspacing="1" width="100%">
							<thead>
								<tr class="nav">
									<th colspan="9">
										<div id="pageFirst" class="segDisabledLink" style="float:left" onclick="jumpToPage(this,FIRST_PAGE)">
											<img title="First" src="<?= $root_path ?>images/start.gif" border="0" align="absmiddle"/>
											<span title="First">First</span>
										</div>
										<div id="pagePrev" class="segDisabledLink" style="float:left" onclick="jumpToPage(this,PREV_PAGE)">
											<img title="Previous" src="<?= $root_path ?>images/previous.gif" border="0" align="absmiddle"/>
											<span title="Previous">Previous</span>
										</div>
										<div id="pageShow" style="float:left; margin-left:10px">
											<span></span>
										</div>
										<div id="pageLast" class="segDisabledLink" style="float:right" onclick="jumpToPage(this,LAST_PAGE)">
											<span title="Last">Last</span>
											<img title="Last" src="<?= $root_path ?>images/end.gif" border="0" align="absmiddle"/>
										</div>
										<div id="pageNext" class="segDisabledLink" style="float:right" onclick="jumpToPage(this,NEXT_PAGE)">
											<span title="Next">Next</span>
											<img title="Next" src="<?= $root_path ?>images/next.gif" border="0" align="absmiddle"/>
										</div>
									</th>
								</tr>
								<tr>
									<th width="*">Name/Description</th>
									<th width="15%" align="center">Code</th>
									<th width="20%" style="" colspan="2" nowrap="nowrap">Cash/Charge<?= $_GET['d'] ? " (".$_GET['d'].")" : "" ?></th>
									<th width="20%" style="font-size:10px" colspan="2" nowrap="nowrap">Cash/Charge<br />(Senior Citizen)</th>
                                    <th width="5%" align="center">Quantity</th>
									<th width="1%">&nbsp;</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td colspan="8">No such product exists...</td>
								</tr>
							</tbody>
						</table>
					</div>
					<img id="ajax-loading" src="<?= $root_path ?>images/loading6.gif" align="absmiddle" border="0" style="display:none"/>
				</td>
			</tr>
		</tbody>
	</table>

	<input type="hidden" name="sid" value="<?php echo $sid?>">
	<input type="hidden" name="lang" value="<?php echo $lang?>">
	<input type="hidden" name="cat" value="<?php echo $cat?>">
	<input type="hidden" name="userck" value="<?php echo $userck ?>">
	<input type="hidden" name="mode" value="search">


<?php

# Workaround to force display of results  form
$bShowThisForm = TRUE;

# If smarty object is not available create one
if(!isset($smarty)){
	/**
 * LOAD Smarty
 * param 2 = FALSE = dont initialize
 * param 3 = FALSE = show no copyright
 * param 4 = FALSE = load no javascript code
 */
	include_once($root_path.'gui/smarty_template/smarty_care.class.php');
	$smarty = new smarty_care('common',FALSE,FALSE,FALSE);
	
	# Set a flag to display this page as standalone
	$bShowThisForm=TRUE;
}

?>

<form action="<?php echo $breakfile?>" method="post">
	<input type="hidden" name="sid" value="<?php echo $sid ?>">
	<input type="hidden" name="lang" value="<?php echo $lang ?>">
	<input type="hidden" name="userck" value="<?php echo $userck ?>">
</form>
<?php if ($from=="multiple")
echo '
<form name=backbut onSubmit="return false">
<input type="hidden" name="sid" value="'.$sid.'">
<input type="hidden" name="lang" value="'.$lang.'">
<input type="hidden" name="userck" value="'.$userck.'">
</form>
';
?>
</div>
<?php

$sTemp = ob_get_contents();
ob_end_clean();

# Assign the form template to mainframe

 $smarty->assign('sMainFrameBlockData',$sTemp);

 /**
 * show Template
 */
 $smarty->display('common/mainframe.tpl');
?>
