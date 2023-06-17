<?php
	error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
	require('./roots.php');
	require($root_path.'modules/clinics/ajax/seg_pf_request.common.php');
	require($root_path.'include/inc_environment_global.php');

	define('NO_2LEVEL_CHK',1);
	$local_user='ck_pflege_user';

	require_once($root_path.'include/inc_front_chain_lang.php');

	require_once($root_path.'include/care_api_classes/class_encounter.php');
	$enc_obj=new Encounter;

	require_once($root_path.'include/care_api_classes/class_department.php');
	$dept_obj = new Department;

	require_once($root_path.'gui/smarty_template/smarty_care.class.php');
	$smarty = new smarty_care('common');

	$breakfile = 'javascript:window.parent.cClick();';
	$smarty->assign('breakfile', $breakfile);
	$smarty->assign('bHideTitleBar',TRUE);
	$smarty->assign('bHideCopyright',TRUE);
	$smarty->assign('breakfile',$breakfile);
	$smarty->assign('sOnLoadJs','onLoad="preset();"');

	ob_start();

	global $db;
	$encounter_nr = $_GET['encounter_nr'];
	$dept_nr = $_GET['current_dept_nr'];
	$pid = $_GET['pid'];
?>

<script type="text/javascript" src="<?=$root_path?>js/jquery/jquery-1.8.2.js"></script>
<script type="text/javascript" src="<?=$root_path?>js/jsprototype/prototype.js"></script>
<script type="text/javascript" src="js/pf_request_tray.js?t=<?=time()?>"></script>
<script type="text/javascript" src="<?=$root_path?>js/NumberFormat154.js"></script>

<?php
	$xajax->printJavascript($root_path.'classes/xajax');
	$sTemp = ob_get_contents();
	ob_end_clean();
	$smarty->append('JavaScript',$sTemp);
	ob_start();
?>
	<input id="current_dept_nr" type="hidden" value="<?=$dept_nr?>"/>

	<table width="98%" cellspacing="2" cellpadding="2" style="margin:0.7%">
		<tbody>

			<tr>
				<td style="font:bold 12px Arial; background-color:#e5e5e5; color: #2d2d2d" >
					<div style="padding:4px 2px; padding-left:10px; ">
						<table width="95%" border="0" cellpadding="2" cellspacing="0" style="margin-top:8px">
							<tr>
								<td class="segPanelHeader" colspan="2">
									Details
								</td>
							</tr>
							<tr>
								<td valign="top" width="30%" align="right"><strong>Department</strong></td>
								<td align="left">
									<select name="request_dept" id="request_dept" onChange="jsSetDoctorsOfDept();">
										<option value='0'>SELECT DEPARTMENT</option>
										<?php 
											$dept_arr = $dept_obj->getDept();
											while($result=$dept_arr->FetchRow()){
												echo "<option value='".$result['nr']."'>".$result['name_formal']."</option>";
											}
										?>
									</select>
								</td>
							</tr>
						</table>
					</div>
				</td>
			</tr>
			<tr>
				<td style="font:bold 12px Arial; background-color:#e5e5e5; color: #2d2d2d" >
					<div style="padding:4px 2px; padding-left:10px; ">
						Search Doctor <input id="search" name="search" class="segInput" type="text" style="width:55%; margin-left:10px; font: bold 12px Arial" align="absmiddle" onkeyup="$('search2').value=this.value; if (this.value.length >= 3) jsSetDoctorsOfDept();"/>
                        <input id="search2" name="search2" class="segInput" type="hidden" style="width:55%; margin-left:10px; font: bold 12px Arial" align="absmiddle"/>
						<input type="image" id="search_img" name="search_img" src="<?= $root_path ?>images/his_searchbtn.gif" onclick="jsSetDoctorsOfDept();return false;" align="absmiddle" />
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<div style="display:block; border:1px solid #8cadc0; overflow-y:hidden; width:100%; background-color:#e5e5e5">
						<table class="segList" cellpadding="1" cellspacing="1" width="100%">
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
									<th width="*" align="left">&nbsp;&nbsp;Doctor's Name</th>
								</tr>
							</thead>
						</table>
						</div>
						<div style="display:block; border:1px solid #8cadc0; overflow-y:scroll; height:160px; width:100%; background-color:#e5e5e5">
							<table id="request-list" class="segList" cellpadding="1" cellspacing="1" width="100%">
								<tbody>
									
								</tbody>
							</table>
						<img id="ajax-loading" src="<?= $root_path ?>images/loading6.gif" align="absmiddle" border="0" style="display:none"/>
					</div>
				</td>
			</tr>
		</tbody>
	</table>

<?php
	$bShowThisForm = TRUE;

	if(!isset($smarty)){
		include_once($root_path.'gui/smarty_template/smarty_care.class.php');
		$smarty = new smarty_care('common',FALSE,FALSE,FALSE);
		$bShowThisForm=TRUE;
	}
?>
</div>

<?php

$sTemp = ob_get_contents();
ob_end_clean();

$smarty->assign('sMainFrameBlockData',$sTemp);
$smarty->display('common/mainframe.tpl');
?>
