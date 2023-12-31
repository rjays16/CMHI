<?php
require('./roots.php');
require($root_path.'include/inc_environment_global.php');
require($root_path.'modules/or/ajax/op-request-new.common.php');
require_once($root_path.'include/care_api_classes/billing/class_ops.php');
require_once($root_path."include/care_api_classes/class_order.php");  //load the SegOrder class
require_once($root_path."include/care_api_classes/class_equipment.php");  //load the Equipment class
require_once($root_path."include/care_api_classes/class_equipment_order.php");  //load the Equipment class
include_once($root_path.'include/care_api_classes/class_department.php');
include_once($root_path.'include/care_api_classes/class_personell.php');
require_once($root_path.'include/care_api_classes/class_radiology.php');
require_once($root_path.'include/care_api_classes/class_labservices_transaction.php');
require_once($root_path.'include/care_api_classes/class_tabview.php');
require($root_path.'include/care_api_classes/class_discount.php');
require_once($root_path.'include/care_api_classes/class_ward.php'); //load the ward class
require_once($root_path.'include/care_api_classes/or/class_segOr_miscCharges.php'); //load the SegOR_MiscCharges class
require_once($root_path.'include/care_api_classes/class_company.php'); //added by mai 07-19-2014
/**
 * populate services
 * Fetches the database for available services associated
 * with a specific service group
 */
function psrv($grp){
	$objResponse = new xajaxResponse();
	$objService = new SegRadio();

	$recordSet = $objService->getRadioServices("group_code='$grp'");

	$objResponse->addScriptCall("crow");
	$recCount = $objService->count;
	$count = 0;

	if($recCount>0){

		$objResponse->addScriptCall("ajxClearTable", $myid);
		$chk=0;

		if($recordSet){
			while($row = $recordSet->FetchRow()){
				$count++;
				$price = $iscash? $row['price_cash']: $row['price_charge'];
				if(!$price) $price="N/A";
				else $price = number_format($price,2,'.','');
				$objResponse->addScriptCall("appendServiceItemGroup",$row['group_code'],$row['service_code'], $row['name'], $price, $chk);
			}
		}else{
			$objResponse->addScriptCall("ajxClearTable", $grp);
			$objResponse->addScriptCall("appendServiceItemToGroup2", $grp);
		}
	}else{
		$objResponse->addScriptCall("ajxClearTable", $grp);
		$objResponse->addScriptCall("appendServiceItemToGroup2", $grp);
	}
	return $objResponse;
} // end of function psrv


function srvList($enc){
	$objResponse = new xajaxResponse();
	$objSrv = new SegRadio();

	#$objResponse->addAlert("srvList(enc)=" . $enc);

	$srvRecord = $objSrv->getAllRadioInfoByEncounter($enc);
	//$srvRecord = $objSrv->getAllRadioInfoByBatch($batch_nr);

	if($srvRecord){
	#	$objResponse->addAlert("xajax_srvList() srvRecord->".$srvRecord);
	 //  $objResponse->addAlert(print_r($srvRecord));
		$count = 1;
		while($row = $srvRecord->FetchRow()){
		 #	$objResponse->addAlert("row-> srevice code=".$row['service_code'], "\n count=".$count."\n batch_nr=".$row['batch_nr']);
			#$objResponse->addAlert("service_dept_nr->".$row['service_dept_nr']);
			$objResponse->addScriptCall("guiSrvTabContent",$count,$row['batch_nr'],$row['request_date'],$row['service_code'],$row['service_name'],$row['service_dept_nr'], $enc);
			$objResponse->addScriptCall("guiSrvTabAll",$count,$row['batch_nr'],$row['request_date'],$row['service_code'],$row['service_name'],$row['service_dept_nr'], $enc);
			$count++;

		}
		//
	}else{
		$objResponse->addScriptCall("clrSrvList");
		$objResponse->addScriptCall("srvListNoRecord");
	}

	return $objResponse;
} // end of function srvList

function populateSrvListAll($enc){
	$objResponse = new xajaxResponse();
	$objRadio = new SegRadio();

	$objResponse->addAlert("populateSrvListAll: encounter=".$enc);

	$recordSet = $objRadio->getAllRadioInfoByEncounter($enc);
	if($recordSet){
		$count = 1;
		while($row = $recordSet->FetchRow()){
			$objResponse->addScriptCall("guiSrvTabAll",$count,$row['batch_nr'],$row['request_date'],$row['service_code'],$row['service_name'],$row['service_dept_nr'], $enc);
			$count++;
		}
	}
	return $objResponse;
}

function delSrv($tabValue,$RowNo,$batchNr, $enc){
	$objResponse =  new xajaxResponse();
	$objRadio = new SegRadio();

	$objResponse->addAlert("tabvalue=".$tabValue."\n RowNo=".$RowNo."\n batch_nr=".$batchNr." \n enc=".$enc);
	#$objResponse->addAlert("Request service with Batch No. ".$batchNr." has been deleted.");

	$result = $objRadio->deleteRadioRequest($batchNr);
	#$result = true;
	if($result){
		$objResponse->addScriptCall("guiSrvDelete", $tabValue, $RowNo);
		$objResponse->addAlert("Request service with Batch No. ".$batchNr." has been deleted.");

		$objResponse->addAlert("delSrv : enc =".$enc);
		$objResponse->addScriptCall("guiSrvClearRows", 'all');
		$objResponse->addScriptCall("xajax_populateSrvListAll", $enc);
	}else{
		$objResponse->addAlert("Failed to delete. Batch No. ".$batchNr);
	}

	//guiSrvDelete(tabvalue, rowno)

	return $objResponse;
}

//function getConstructedTab($tabArray){
function getConstructedTab(){
	$objResponse = new xajaxResponse();
	$objTab = new GuiTabView;
	$objTab->setTabViewName("mainTab");
	$objTab->setTabViewRoot($root_path);

	$tbody1 = "<tbody id=\"grpTabALL\"></tbody></table></div>";
	$tbody2 = "<tbody id=\"grpTabCT\"></tbody></table></div>";
	$tbody3 = "<tbody id=\"grpTabGR\"></tbody></table></div>";
	$tbody4 = "<tbody id=\"grpTabSP\"></tbody></table></div>";
	$tbody5 = "<tbody id=\"grpTabUS\"></tbody></table></div>";
	//ContentPane
	//$objResponse->addAlert("service Group->".$srvGrp);<div style="width:85%;height:90%;overflow:scroll;border:1px solid black">
	$tableAll	 =	 "<br><div style=\"width:98%;height:90%;overflow:auto;border:0px solid black\"><table id=\"srvTableALL\" style=\"border:1px solid #666666;border-bottom:0px\" width=\"98%\" border=\"0\" cellpadding=\"1\" cellspacing=\"1\">";
	$tableCT	 =	 "<br><div style=\"width:98%;height:90%;overflow:auto;border:0px solid black\"><table id=\"srvTableCT\" style=\"border:1px solid #666666;border-bottom:0px\" width=\"98%\" border=\"0\" cellpadding=\"1\" cellspacing=\"1\">";
	$tableGR	 =	 "<br><div style=\"width:98%;height:90%;overflow:auto;border:0px solid black\"><table id=\"srvTableGR\" style=\"border:1px solid #666666;border-bottom:0px\" width=\"98%\" border=\"0\" cellpadding=\"1\" cellspacing=\"1\">";
	$tableSP	 =	 "<br><div style=\"width:98%;height:90%;overflow:auto;border:0px solid black\"><table id=\"srvTableSP\" style=\"border:1px solid #666666;border-bottom:0px\" width=\"98%\" border=\"0\" cellpadding=\"1\" cellspacing=\"1\">";
	$tableUS	 =	 "<br><div style=\"width:98%;height:90%;overflow:auto;border:0px solid black\"><table id=\"srvTableUS\" style=\"border:1px solid #666666;border-bottom:0px\" width=\"98%\" border=\"0\" cellpadding=\"1\" cellspacing=\"1\">";

	$thead1  =   	"<thead id=\"grphead\" class=\"reg_list_titlebar\" style=\"height:0;overflow:visible;font-weight:bold;padding:4px;\" >";
	$thead1	.=	 		"<td width=\"2%\" nowrap>No.</td>";
	$thead1 .=   		"<td width=\"10%\" nowrap>Batch No.</td>";
	$thead1 .=	 		"<td width=\"15%\" nowrap>Date Requested</td>";
	$thead1 .=	 		"<td width=\"15%\" nowrap>Service Code</td>";
	$thead1 .=	 		"<td width=\"70%\" nowrap>Description</td>";
	$thead1 .=	 		"<td width=\"5%\" nowrap>Delete</td>";
	$thead1	.=	 	"</thead>";
	#$thead1	.=	  $tbody;
	//$thead1	.=	  "<tr><td><div id=\"divTbodyTab\"></div></td></tr>";
	//$thead1	.=	"</table>";

	//$sTabContents <div id="all"></div><div id="ct"></div><div id="gr"></div>
	 $tabArray = array(array("all", "All", $tableAll.$thead1.$tbody1),
						array("ct", "Computed Tomography", $tableCT.$thead1.$tbody2),
						array("gr", "General Radiography", $tableGR.$thead1.$tbody3),
						array("sp", "Special Procedure", $tableSP.$thead1.$tbody4),
						array("us", "Ultrasound", $tableUS.$thead1.$tbody5));


		$tabBody = "<tr bgcolor=\"#ffffff\"><td valign=\"top\" colspan=2>";
	$tabBody .= $objTab->getTabContainer($tabArray);
	$tabBody .= "</td></tr>";

	$objResponse->addAssign("tbViewTabs", "innerHTML", $tabBody);
	return $objResponse;
}// end of fucntion getConstructedTab


function srvGui($grpCode, $grpName){
	$objResponse = new xajaxResponse();

	$thead  =	"<thead class=\"\"><td colspan=\"4\">";
	$thead .=	"<table width=\"100%\" cellspacing=\"1\" cellpadding=\"1\" border=\"0\"><tr>";
	$thead .=    "<td width=\"*\" class=\"reg_header\">".$grpName."</td>";
	$thead .=	"<td width=\"1%\" align=\"right\" style=\"padding:2px;2px;font-weight:normal\" class=\"reg_header\">";
	$thead .=	"<span class=\"reglink\" onclick=\"toggleDisplay2('grpBody".$grpCode."');\">Show/Hide</span>";
	$thead .=	"</td>";
	$thead .=    "</tr></table>";
	$thead .=	"</td></thead>";

	//$thead1  =   "<table width=\"100%\" cellspacing=\"1\" cellpadding=\"1\" border=\"0\">";
	$thead1 =   "<thead id=\"grphead".$grpCode."\" class=\"reg_list_titlebar\" style=\"height:0;overflow:visible;font-weight:bold;padding:4px;\" cellpadding=\"1\" cellspacing=\"1\">";
	$thead1 .=   "<td width=\"1\"><input type=\"checkbox\" id=\"chk_all_".$grpCode."\" name=\"chk_all_".$grpCode."\" onChange=\"checkAll(this.checked);countItem('".$grpCode."', 1);\"></td>";
	//$thead1 .=   "<td width=\"1\"><input id=\"chk_all_".$grpCode."\" name=\"chk_all\" type=\"checkbox\" onClink=\"countItem(1);\"></td>";
	$thead1	.=	 "<td width=\"15%\" nowrap>Code</td>";
	$thead1 .=   "<td width=\"60%\" nowrap>Description</td>";
	$thead1 .=	 "<td width=\"15%\" nowrap>Price</td>";
	$thead1	.=	 "</thead>";

	#$objResponse->addAlert("thead1->".$thead1);

	$tbody = "<tbody id=\"grpBody".$grpCode."\" style=\"height:0; overflow:visible\"></tbody>";

	#$objResponse->addAlert("grpCode->".$grpCode);

	$html = $thead.$thead1.$tbody;

	$objResponse->addAssign("srcRowsTable", "innerHTML", $html);

	return $objResponse;
}

function getAjxGui($grp){
	$objResponse = new xajaxResponse();
	//$objResponse->addScriptCall("xajax_srvGui", $grp, $name);
	$objResponse->addScriptCall("xajax_psrv", $grp);

	return $objResponse;
}

function getServiceGroup($dept_nr=''){
	$objResponse = new xajaxResponse();
	$objService = new SegRadio();

	$rs = $objService->getRadioServiceGroups2("department_nr='$dept_nr'");

	if($rs){
		$objResponse->addScriptCall("ajxClearOptions");
		if($objService->count > 0){
			$objResponse->addScriptCall("ajxAddOption", "Select Service Group", 0);
		}else{
			$objResponse->addScriptCall("ajxAddOption", "No Service Group", 0);
		}

		while ($row = $rs->FetchRow()){
			$objResponse->addScriptCall("ajxAddOption", $row['name'], $row['group_code']);
		}
	}else{
		$objResponse->addScriptCall("ajxClearOptions");
		$objResponse->addScriptCall("ajxAddOption", "No Service Group", 0);


		#$objResponse->addAlert("hello mark ajxclearTable is next to be executed");
		#$objResponse->addScriptCall("ajxClearTable");
		#$objResponse->addScriptCall("xajax_getAjxGui", 0);
	}
	return $objResponse;
}

/*******       burn added : August 31, 2007       *******/

	function populateOpsCodeListByRefNo($refno=0){
		global $db;
		$objResponse = new xajaxResponse();
		$ops_obj=new SegOps();

#$objResponse->addAlert("populateOpsCodeListByRefNo : refno='".$refno."'");
		$rs = $ops_obj->getOpsServDetailsInfo($refno);
#		$objResponse->addAlert("populateOpsCodeListByRefNo : objRadio->sql='".$objRadio->sql."'");
#		$objResponse->addAlert("populateOpsCodeListByRefNo : rs : \n".print_r($rs,TRUE));
		if ($rs){
			while($result=$rs->FetchRow()) {
	#			$objResponse->addAlert("populateOpsCodeListByRefNo : inside while loop : result : \n".print_r($result,TRUE));
				$objResponse->addScriptCall("initialOpsCodeList",trim($result['ops_code']), trim($result['description']),
											trim($result['rvu']), trim($result['multiplier']),trim($result['ops_charge']));
			}
		}else{
			$objResponse->addScriptCall("emptyIntialListById",'order-list');
		}
		return $objResponse;
	}# end of function populateOpsCodeListByRefNo


	function get_charity_discounts( $nr=0 ) {
/*
		global $db;
		$objResponse = new xajaxResponse();
		$discount= new SegDiscount();
		$ergebnis=$discount->GetEncounterCharityGrants( $nr );
		$objResponse->addAlert("get_charity_discounts : ergebnis='".$ergebnis."'; \ndiscount->sql".$discount->sql."'");
		$objResponse->addAlert("get_charity_discounts : ".print_r($ergebnis,TRUE));
		$objResponse->addScriptCall("clearCharityDiscounts");
		if ($ergebnis) {
			$rows=$ergebnis->RecordCount();
			while($result=$ergebnis->FetchRow()) {
				$objResponse->addScriptCall("addCharityDiscount",$result["discountid"],$result["discount"]);
			}
		}
		return $objResponse;
*/
	}

	/*
	*
	* @param int role_type_nr : 7,surgeon; 8,assistant surgeon; 12,anesthesiologist;	9,scrub nurse; 10,rotating nurse;
	*/
	function populatePersonnel($refno=0,$role_type_nr='',$list_id='',$pers_type){
		global $db;
		$objResponse = new xajaxResponse();
		$ops_obj=new SegOps();

		$pers_id_array = $ops_obj->getOpsPersonellNr($refno,$role_type_nr);
		$pers_info = $ops_obj->setPersonellNrNamePID($pers_id_array);

		if (is_array($pers_info) && !empty($pers_info)){
			foreach($pers_info as $pers_nr=>$pers_pidName){
				$objResponse->addScriptCall("initialPersonnelList",$pers_nr,$pers_pidName['name'],$list_id,$pers_type);
			}
		}else{
			$objResponse->addScriptCall("emptyIntialListById",$list_id);
		}
		return $objResponse;
	}# end of function populatePersonnel

	#----------------added by VAN 06-24-08
	function populateORroomByDept($dept=0){
		global $db;
		$objResponse = new xajaxResponse();
		$dept_obj=new Department;

		$rs = $dept_obj->getAllActiveORNrsByDept($dept);
		#$objResponse->addAlert('dept = '.$dept_obj->sql);
		$objResponse->addScriptCall("clearRoomList","ORRoomList");
		if ($rs){
			while($result=$rs->FetchRow()) {
				#$objResponse->addAlert('room_nr, info = '.$result['room_nr']." , ".$result['info']);
				$objResponse->addScriptCall("addRoomToList","ORRoomList",$result['nr'], $result['room_nr'], $result['info']);
			}
		}else{
			$objResponse->addScriptCall("addRoomToList","ORRoomList",NULL);
		}
		return $objResponse;
	}# end of function populateORroomByDept

	#----------------------------------

		function populate_or_main_anesthesia($or_main_refno) {
				$obj_response = new xajaxResponse();
				#---modified by CHa 01-06-2010---
				global $db;
				$seg_ops = new SegOps();
				$db_result = $seg_ops->get_or_main_anesthesia($or_main_refno);

				if ($db_result) {

					$iterator = 0;
					while ($row = $db_result->FetchRow()) {

						$query = "select sa.or_main_refno,sa.order_refno,sa.anesthetic_id, so.quantity, so.pricecash, so.pricecharge, cp.artikelname
						from seg_encounter_anesthetic as sa
						left join seg_pharma_order_items as so
						on sa.order_refno=so.refno and sa.anesthetic_id=so.bestellnum
						inner join care_pharma_products_main as cp on so.bestellnum=cp.bestellnum
						inner join seg_encounter_anesthesia  sea on sea.anesthesia_care_id=sa.anesthesia_care_id and sea.anesthesia='".$row['anesthesia']."'
						where sa.or_main_refno=".$db->qstr($or_main_refno);
						#$objResponse->addAlert($query);
						$result = $db->Execute($query);
						while($row2 = $result->FetchRow())
						{
							$anesthetics[] = $row2['anesthetic_id'];
							$srvname[] = $row2['artikelname'];
							$srvqty[] = $row2['quantity'];
							$srvcash[] = $row2['pricecash'];
							$srvcharge[] = $row2['pricecharge'];
							$order_refno = $row2['order_refno'];
						}
						$details[] = array(
							'anesthetics' => $anesthetics,
							'anesthetics_count' => count($anesthetics),
							'srvname' => $srvname,
							'srvqty' => $srvqty,
							'srvcash' => $srvcash,
							'srvcharge' => $srvcharge,
							'time_begun' => $row['time_begun'],
							'time_ended' => $row['time_ended'],
							'tb_meridian' => $row['tb_meridian'],
							'te_meridian' => $row['te_meridian'],
							'name_category' => $row['category'],
							'name_specific' => $row['specific'],
							'id' => $row['anesthesia_nr'],
							'or_main_refno' => $or_main_refno,
							'order_refno' => $order_refno
						);
						unset($anesthetics);
						unset($srvname);
						unset($srvqty);
						unset($srvcash);
						unset($srvcharge);
						unset($order_refno);
						#$obj_response->alert(print_r($details,true));
						#$obj_response->addScriptCall('add_or_main_anesthesia', 'anesthesia_procedure_list', $row['anesthesia_nr'], $row['name']);
						#$obj_response->addScriptCall('populate_anesthesia_fields', $details, $iterator);
						$iterator++;

					}
					#$obj_response->alert(print_r($details));
					$obj_response->addScriptCall("populate_anesthesia_procedure",'anesthesia_procedure_list',$details,count($details));

				}
				else {

					$obj_response->call('append_empty_anesthesia');
				}
				return $obj_response;
		}
	function populate_order( $refno, $discountID, $disabled=NULL ) {

		global $db;
		$objResponse = new xajaxResponse();

		$order_obj = new SegOrder("pharma");
		$result = $order_obj->getOrderItemsFullInfo($refno, $discountID);
		#$objResponse->alert($order_obj->sql);
		$objResponse->call("clearOrderSupplies",NULL);
		$rows = 0;
		if ($result) {
			$rows=$result->RecordCount();
			while ($row=$result->FetchRow()) {
				$obj->id = $row["bestellnum"];
				$obj->name = $row["artikelname"];
				$obj->desc= $row["description"];
				$obj->prcCash = $row["cshrpriceppk"];
				$obj->prcCharge = $row["chrgrpriceppk"];
				$obj->prcCashSC = $row["cashscprice"];
				$obj->prcChargeSC = $row["chargescprice"];
				$obj->prcDiscounted = $row["dprice"];
				$obj->isSocialized = $row["is_socialized"];
				$obj->forcePrice = $row["force_price"];
				$obj->qty = $row["quantity"];
				$obj->isConsigned = $row['is_consigned'];
				$obj->serveStatus = $row["request_flag"];	//added by cha, july 7, 2010

				$obj->disable = 0;
				if($row["request_flag"])
					$obj->disable = 1;
				$objResponse->call("appendOrderSupplies", NULL, $obj, $disabled);
			}
			if (!$rows) $objResponse->call("appendOrderSupplies",NULL,NULL);
			$objResponse->call("refreshDiscountSupplies");
		}
		else {
			if (true) {
				$objResponse->call("display",$order_obj->sql);
				# $objResponse->alert($sql);
			}
			else {
				$objResponse->alert("A database error has occurred. Please contact your system administrator...");
			}
		}
		return $objResponse;
	}

		function add_misc($table, $code, $source, $account_type) {
			global $db;
			$objResponse = new xajaxResponse();
			$seg_ops = new SegOps();

			$misc_item = $seg_ops->get_misc_details($code, $source);
			if ($misc_item) {
				$details = new stdclass();
				$details->code = $misc_item['service_code'];
				$details->name = $misc_item['name'];
				$details->description = isset($misc_item['description']) ? $misc_item['description'] : 'No description';
				$details->price = number_format($misc_item['price'], 2, '.', ',');
				$details->account_type = $account_type;
				$objResponse->call('append_misc', $table, $details);
			}
			else {
				$objResponse->alert('error');
			}

			return $objResponse;


		}

		function add_equipment($table, $equipment_id) {
			global $db;

			$objResponse = new xajaxResponse();

			$equipment = new SegEquipment($equipment_id);
			$equipment_item = $equipment->get_equipment_details();

			if ($equipment_item) {

				$details = new stdclass();
				$details->equipment_id = $equipment_item['equipment_id'];
				$details->equipment_name = $equipment_item['equipment_name'];
				$details->equipment_description = $equipment_item['equipment_description'];
				$details->equipment_unit = $equipment_item['equipment_unit'];
				$details->equipment_charge = $equipment_item['equipment_charge'];
				$details->equipment_cash = $equipment_item['equipment_cash'];
				$details->equipment_is_socialized = $equipment_item['is_socialized'];

				if ($equipment_id != 'OT')
					$objResponse->call('append_equipment', $table, $details);
				else
					$objResponse->call('assign_oxygen', $table, $details);
				//$objResponse->call('assign_equipment_values', $details, $iterator);
			}

		return $objResponse;
		}

		function add_oxygen($table, $serial_no) {
			global $db;
			$objResponse = new xajaxResponse();
			$equipment_id = 'OT';
			$equipment = new SegEquipment($equipment_id);
			$equipment_item = $equipment->get_equipment_details();
			$oxygen_item = $equipment->get_oxygen_details($serial_no);

			if ($equipment_item && $oxygen_item) {

				$details = new stdclass();
				$details->equipment_id = $equipment_item['equipment_id'];
				$details->equipment_name = $equipment_item['equipment_name'];
				$details->equipment_description = $equipment_item['equipment_description'];
				$details->equipment_unit = $equipment_item['equipment_unit'];
				$details->equipment_charge = $equipment_item['equipment_charge'];
				$details->equipment_cash = $equipment_item['equipment_cash'];
				$details->equipment_is_socialized = $equipment_item['is_socialized'];
				$details->serial_no = $serial_no;
				$details->remaining_quantity = $oxygen_item['qty'];
				$objResponse->call('append_oxygen', $table, $details);
				$objResponse->call('hide_oxygen');
			}
			return $objResponse;
		}

		function set_pharma_refno($encounter_nr, $area, $discount_id) {
			global $db;
			$objResponse = new xajaxResponse();
			$seg_ops = new SegOps();
			$refno = $seg_ops->get_pharma_order_mode($encounter_nr, $area);

			if ($refno) {
				$objResponse->call('xajax_populate_order', $refno, $discount_id);
			}
			else {
				$objResponse->call("emptyTraySupplies");
				$objResponse->call("refreshDiscountSupplies");
			}

			return $objResponse;
		}

		function set_equipment_refno($encounter_nr, $table, $area) {
			global $db;
			$objResponse = new xajaxResponse();
			$equipment = new SegEquipmentOrder();
			$equipment_refno = $equipment->get_equipment_refno_other($encounter_nr, $area);

		//  if ($equipment_refno) {
				$objResponse->call('xajax_populate_equipment_oxygen', $equipment_refno, 'equipment_list', $area);
				$objResponse->call('xajax_populate_equipment_order', $equipment_refno, 'equipment_list', $area);

		//  }


			return $objResponse;
		}

		function populate_accommodation($encounter_nr, $area) {
			global $db ;
			$objResponse = new xajaxResponse();
			$seg_ops = new SegOps();
			if ($result = $seg_ops->get_accommodation($encounter_nr, $area)) {
				$first = true;
				while ($row = $result->FetchRow()) {
					$details = new stdclass();
					$details->ward_nr = $row['ward_nr'];
					$details->ward_name = $row['ward_name'];
					$details->room_nr = $row['room_nr'];
					$details->room_type = $row['room_type'];
					/**if ($first) {
						$details->is_removable = true;
						$first = false;
					}
					else {
						$details->is_removable = false;
					}  **/ //for future LIFO room deletion
					//$details->room_days = $row['room_days'];
					//$details->room_hours = $row['room_hours'];
					$room_hours = (int)($row['room_hours']/24);
					$details->room_hours = $room_hours == 0 ? $row['room_hours'] : (($row['room_hours']) - ($room_hours * 24));
					$details->room_days = $row['room_days'] + $room_hours;

					$details->room_rate = number_format($row['room_rate'], 2, '.', '');
					$details->room_number = $row['room_number'];
					$computed_days = $details->room_hours > 5 ? $details->room_days + 1 : $details->room_days;
					$details->total = ($computed_days) . 'day'.(($computed_days > 1) ? 's ' : ' ') . '= '.number_format($computed_days * $details->room_rate, 2, '.', '');

					$details->total_accommodation = $computed_days * $details->room_rate;

					$objResponse->call('populate_accommodation', $details);
				}
			}
			return $objResponse;
		}

		function populate_misc_order($table, $encounter_nr, $area) {
			global $db;
			$objResponse = new xajaxResponse();
			//$seg_ops = new SegOps();
			$seg_ormisc = new SegOR_MiscCharges();

			if ($result = $seg_ormisc->getMiscOrderItems($encounter_nr, $area)) {
				while ($misc_item = $result->FetchRow()) {
				 // $objResponse->alert($misc_item['code']);
					$details = new stdclass();
					$details->code = $misc_item['code'];
					$details->name = $misc_item['name'];
					//$details->description = isset($misc_item['description']) ? $misc_item['description'] : 'No description';
					$details->type_name = $misc_item['name_short'];
					//$details->price = number_format($misc_item['chrg_amnt'], 2, '.', ',');
					$details->price = $misc_item['chrg_amnt'];
					$details->account_type = $misc_item['account_type'];
					$details->quantity = $misc_item['quantity'];

					if ($misc_item['area'] == $area) {
						$details->is_removable = 1;
					}
					else {
						$details->is_removable = 0;
					}
					$details->status = $misc_item['request_flag'];

					$details->disable = 0;
					if($misc_item["request_flag"]!="")
						$details->disable = 1;
					$objResponse->call('retrieve_misc', $table, $details);
				}
			}
			//$objResponse->alert($seg_ormisc->sql);

			return $objResponse;
		}


		function populate_room_list($ward_nr, $room_combo_id) {
			global $db;
			$objResponse = new xajaxResponse();
			$ward = new Ward();

			$result = $ward->getRoomsData($ward_nr);
			if ($result->RecordCount() > 0) {
				$first = 0;
				while ($row = $result->FetchRow()) {
					if (!$first)
						$first = $row['room_nr'];
					$innerHTML .= '<option value="'.$row['nr'].'">Room '.$row['room_nr'].'</option>';

				}
				$objResponse->assign($room_combo_id, 'disabled', false);

			}
			else {
				$innerHTML = '<option value="0">No room is available under this ward</option>';
				$objResponse->assign($room_combo_id, 'innerHTML', $innerHTML);
				$objResponse->assign($room_combo_id, 'disabled', true);
				$objResponse->assign('room_rate', 'disabled', true);
			}
			$objResponse->assign($room_combo_id, 'innerHTML', $innerHTML);
			$objResponse->call('get_room_rate');
			return $objResponse;
		}

		function get_room_rate($room_nr, $room_rate_id) {
			global $db;
			$objResponse = new xajaxResponse();
			$ward = new Ward();

			$row = $ward->get_room_rate($room_nr);
			if ($row) {
				$rate = number_format($row['room_rate'], 2, '.', '');;
			}
			else {
				$rate = number_format('0', 2, '.', '');
			}
			$objResponse->assign($room_rate_id, 'value', $rate);
			$objResponse->assign('room_type', 'value', $row['type']);
			if ($room_nr != 0) {
				$objResponse->assign($room_rate_id, 'disabled', false);
				$objResponse->assign('room_days', 'disabled', false);
				$objResponse->assign('room_hours', 'disabled', false);
			}
			else {
				$objResponse->assign('room_days', 'disabled', true);
				$objResponse->assign('room_hours', 'disabled', true);
			}
			return $objResponse;
		}

		function populate_equipment_order($equipment_refno, $table, $area=NULL) {
			global $db;
			$objResponse = new xajaxResponse();

			if ($equipment_refno == 0) {
				$objResponse->call('append_empty', $table);
			}
			else {
			$equipment_order = new SegEquipmentOrder();
			$result = $equipment_order->get_order_items($equipment_refno, $area);
				if ($result) {
					while($value = $result->FetchRow()) {
						$details = new stdclass();
						$details->equipment_id = $value['equipment_id'];
						$details->equipment_name = $value['equipment_name'];
						$details->equipment_description = $value['equipment_description'];
						$details->equipment_unit = $value['equipment_unit'];
						$details->original_price = $value['original_price'];
						$details->adjusted_price = $value['discounted_price'];
						$details->account_total = $value['amount'];
						$details->number_of_usage = $value['number_of_usage'];
						$details->discount = $value['discount'];
						$details->discountid = $value['discountid'];
						$details->is_cash = $value['is_cash'];
						$details->is_sc = ($value['discountid'] == 'SC') ? 1 : 0;
						$objResponse->call('retrieve_equipment', $table, $details);
					}
				}
				else {
					$objResponse->call('append_empty', $table);
				}
			}
			return $objResponse;
		}

		function populate_equipment_oxygen($equipment_refno, $table, $area='OR') {
			global $db;
			$objResponse = new xajaxResponse();



			$equipment_order = new SegEquipmentOrder();
			$result = $equipment_order->get_order_oxygen($equipment_refno, $area);
				if ($result) {
					while($value = $result->FetchRow()) {
						$details = new stdclass();
						$details->equipment_id = $value['equipment_id'];
						$details->equipment_name = $value['equipment_name'];
						$details->equipment_description = $value['equipment_description'];
						$details->equipment_unit = $value['equipment_unit'];
						$details->original_price = $value['original_price'];
						$details->adjusted_price = $value['discounted_price'];
						$details->account_total = $value['amount'];
						$details->number_of_usage = $value['number_of_usage'];
						$details->discount = $value['discount'];
						$details->discountid = $value['discountid'];
						$details->is_cash = $value['is_cash'];
						$details->is_sc = ($value['discountid'] == 'SC') ? 1 : 0;
						$details->serial_no = $value['serial_no'];
						$objResponse->call('retrieve_oxygen', $table, $details);
					}
				}

			return $objResponse;
		}

		/*added by mai 07-18-2014*/
		function getChargeCompanyBalance($encounter_nr, $trans_source, $refno){ /*added by mai 07-15-2014*/
			$company = new Company();
			$objResponse = new xajaxResponse();

			if($refno){
				$balance=$company->getchargeBalance($encounter_nr, $trans_source, $refno);
			}else{
				$balance=$company->getchargeBalance($encounter_nr, $trans_source);
			}
			
			if($balance){
				$objResponse->assign('charge_comp_balance', 'value', $balance);
				$objResponse->call('chargeToCompany');
			}else{
				$objResponse->assign('charge_comp_balance', 'value', 0);
			}

			return $objResponse;

		}
		/*end added by mai*/
		function populate_events($month, $day, $year, $get_what) {

			global $db;
			$objResponse = new xajaxResponse();
			//$objResponse->Assign("body", "innerHTML", "<div id=\"calback\"></div>");
			$ops_obj = new SegOps();
			$personell = new Personell();
			$result = $ops_obj->get_events($month, $day, $year, $get_what);
			if ($result) {

				$objResponse->Assign("body", "innerHTML", "");
				while ($row = $result->FetchRow()) {
					$details = new stdclass();
					$details->refno = $row['refno'];
					$details->patient_name = $row['patient_name'];
					$details->request_priority = $row['request_priority'];
					$details->is_first = $is_first;
					$details->event = $row['event'];

					if ($row['event']=='request' && ($get_what=='requests' || $get_what=='request_operation')) {

						$details->request_time = ($row['request_time'] == '') ? $row['joined_time'] : $row['request_time'];
					}

					if ($row['event']=='operation') {
						$details->operation_time = ($row['operation_time'] == '') ? $row['joined_time'] : $row['operation_time'];
						if (trim($row['surgeon']) == '' || trim($row['surgeon']) == 'N;') {
							$details->surgeon = 'Not specified';
						}
						else {
							$surgeon = array();
							foreach (unserialize($row['surgeon']) as $value) {
								$temp_surgeon = $personell->get_Person_name($value);
								$surgeon[] = $temp_surgeon['dr_name'];
							}
							$details->surgeon = implode(', ', $surgeon);
						}
						if (trim($row['assistant_surgeon']) == '' || trim($row['assistant_surgeon']) == 'N;') {
							$details->assistant_surgeon = 'Not specified';
						}
						else {
							$assistant_surgeon = array();
							foreach (unserialize($row['assistant_surgeon']) as $value) {
								$temp_assistant = $personell->get_Person_name($value);
								$assistant_surgeon[] = $temp_assistant['dr_name'];
							}
							$details->assistant_surgeon = implode(', ', $assistant_surgeon);
						}
						if (trim($row['anesthesiologist']) == '' || trim($row['anesthesiologist']) == 'N;') {
							$details->anesthesiologist = 'Not specified';
						}
						else {
							$anesthesiologist = array();
							foreach (unserialize($row['anesthesiologist']) as $value) {
								$temp_anesthesio = $personell->get_Person_name($value);
								$anesthesiologist[] = $temp_anesthesio['dr_name'];
							}
							$details->anesthesiologist = implode(', ', $anesthesiologist);
						}

					}
				 $objResponse->call('append_event', $details);

				}
			}
			return $objResponse;
		}

		function laboratory_test($encounter_nr, $pid, $refno) {
			global $db;
			$objResponse = new xajaxresponse();

			$query = "SELECT sls.refno, ss.name AS tests, ss.service_code, slr.status, slsd.is_served,
								slr.service_code as existing_result, omor.pid FROM seg_lab_serv sls
								INNER JOIN or_main_other_requests omor ON (sls.refno=omor.request_refno)
								INNER JOIN seg_lab_servdetails slsd ON (slsd.refno=sls.refno)
								INNER JOIN seg_lab_services ss ON (ss.service_code=slsd.service_code)
								LEFT JOIN seg_lab_resultdata slr ON (slr.refno=sls.refno AND slr.service_code=slsd.service_code  AND slr.status<>'deleted')
								WHERE omor.encounter_nr='$encounter_nr' AND omor.pid='$pid' AND omor.or_refno='$refno'
								AND omor.location=1 AND sls.status<>'deleted' AND slsd.status<>'deleted'";
			$objResponse->alert($query);
			/**
			$result = $db->Execute($query);
			if ($count = $result->RecordCount()) {
				$str = '';
				while ($row = $result->FetchRow()) {
					$current_refno = $row['refno'];

					if ($row['is_served']==1) {
						$image = '<img class="segSimulatedLink" src="../../../images/or_main_images/requests/official_result.png" onclick="view_laboratory_result('.$row['refno'].','.$row['pid'].',\''.$row['service_code'].'\', 1)" />';
						$img_refno = '';
					}

					elseif (trim($row['existing_result'])) {
						$image = '<img class="segSimulatedLink" src="../../../images/or_main_images/requests/unofficial_result.png" onclick="view_laboratory_result('.$row['refno'].','.$row['pid'].',\''.$row['service_code'].'\', 0)" />';
						$img_refno = '';
					}

					else {
						$image = '<img class="segSimulatedLink" src="../../../images/close_small.gif" border="0" onclick="delete_laboratory_service_code('.$row['refno'].',\''.$row['service_code'].'\')" />';
					}

					if ($current_refno != $previous_refno && isset($previous_refno)) {
						$str .= '</tbody>
										 </table>
										 </td>
										 </tr>';
					}
					if ($current_refno != $previous_refno) {
						$query2 = "SELECT (SELECT COUNT(service_code) FROM seg_lab_resultdata WHERE refno='$current_refno'
												AND status<>'deleted') as unofficial_result,
												(SELECT COUNT(service_code) FROM seg_lab_servdetails WHERE refno='$current_refno') as official_result";
						$result2 = $db->Execute($query2);
						$row2 = $result2->FetchRow();
						if ((int)$row2['unofficial_result'] > 0 || (int)$row['official_result'] > 0) {
							$img_refno = '<img src="../../../images/close_small_disabled.gif" border="0" />';
						}
						else {
							$img_refno = '<img class="segSimulatedLink" src="../../../images/close_small.gif" border="0" onclick="delete_laboratory_request('.$row['refno'].')" />';
						}
						$str .= '<tr">
										 <td colspan="3">
											 <table style="font-size:12px; font-weight:bold">
												 <tr>
													 <td>'.$img_refno.'</td>
													 <td colspan="2">Reference Number: '.$row['refno'].'</td>
												 </tr>
												 <tbody id="'.$row['refno'].'">';
					}
					$str .= '<tr>
										 <td></td>
										 <td width="15">'.$image.'</td>
										 <td>'.$row['tests'].'</td>
									 </tr>';

					$previous_refno = $row['refno'];
				}
				$objResponse->assign('lab_tbody', 'innerHTML', $str);
			}
			else {
				$empty_row = '<tr><td colspan="2">No Request Yet...</td></tr>';
				$objResponse->assign('lab_tbody', 'innerHTML', $empty_row);
			}   **/
		 return $objResponse;
		}

		function blood_test($encounter_nr, $pid, $refno) {
			global $db;
			$objResponse = new xajaxresponse();


			/*$query = "SELECT sls.refno, ss.name AS tests, ss.service_code FROM seg_lab_serv sls
								INNER JOIN or_main_other_requests omor ON (sls.refno=omor.request_refno)
								INNER JOIN seg_lab_servdetails slsd ON (slsd.refno=sls.refno)
								INNER JOIN seg_lab_services ss ON (ss.service_code=slsd.service_code)
								WHERE omor.encounter_nr='$encounter_nr' AND omor.pid='$pid' AND omor.or_refno='$refno'
								AND omor.location=2 AND sls.status<>'deleted' AND slsd.status<>'deleted'";*/

			$query = "SELECT sls.refno, ss.name AS tests, ss.service_code, slr.status, slsd.is_served,
								slr.service_code as existing_result, omor.pid FROM seg_lab_serv sls
								INNER JOIN or_main_other_requests omor ON (sls.refno=omor.request_refno)
								INNER JOIN seg_lab_servdetails slsd ON (slsd.refno=sls.refno)
								INNER JOIN seg_lab_services ss ON (ss.service_code=slsd.service_code)
								LEFT JOIN seg_lab_resultdata slr ON (slr.refno=sls.refno AND slr.service_code=slsd.service_code  AND slr.status<>'deleted')
								WHERE omor.encounter_nr='$encounter_nr' AND omor.pid='$pid' AND omor.or_refno='$refno'
								AND omor.location=2 AND sls.status<>'deleted' AND slsd.status<>'deleted'";

			$result = $db->Execute($query);
			if ($count = $result->RecordCount()) {
				$str = '';
				while ($row = $result->FetchRow()) {
					$current_refno = $row['refno'];

					if ($row['is_served']==1) {
						$image = '<img class="segSimulatedLink" src="../../../images/or_main_images/requests/official_result.png" onclick="view_laboratory_result('.$row['refno'].','.$row['pid'].',\''.$row['service_code'].'\', 1)" />';
						$img_refno = '';
					}

					elseif (trim($row['existing_result'])) {
						$image = '<img class="segSimulatedLink" src="../../../images/or_main_images/requests/unofficial_result.png" onclick="view_laboratory_result('.$row['refno'].','.$row['pid'].',\''.$row['service_code'].'\', 0)" />';
						$img_refno = '';
					}

					else {
						$image = '<img class="segSimulatedLink" src="../../../images/close_small.gif" border="0" onclick="delete_blood_service_code('.$row['refno'].',\''.$row['service_code'].'\')" />';
					}

					if ($current_refno != $previous_refno && isset($previous_refno)) {
						$str .= '</tbody>
										 </table>
										 </td>
										 </tr>';
					}
					if ($current_refno != $previous_refno) {
						$query2 = "SELECT (SELECT COUNT(service_code) FROM seg_lab_resultdata WHERE refno='$current_refno'
												AND status<>'deleted') as unofficial_result,
												(SELECT COUNT(service_code) FROM seg_lab_servdetails WHERE refno='$current_refno') as official_result";
						$result2 = $db->Execute($query2);
						$row2 = $result2->FetchRow();
						if ((int)$row2['unofficial_result'] > 0 || (int)$row['official_result'] > 0) {
							$img_refno = '<img src="../../../images/close_small_disabled.gif" border="0" />';
						}
						else {
							$img_refno = '<img class="segSimulatedLink" src="../../../images/close_small.gif" border="0" onclick="delete_blood_request('.$row['refno'].')" />';
						}
						$str .= '<tr">
										 <td colspan="3">
											 <table style="font-size:12px; font-weight:bold">
												 <tr>
													 <td>'.$img_refno.'</td>
													 <td colspan="2">Reference Number: '.$row['refno'].'</td>
												 </tr>
												 <tbody id="'.$row['refno'].'">';
					}
					$str .= '<tr>
										 <td></td>
										 <td width="15">'.$image.'</td>
										 <td>'.$row['tests'].'</td>
									 </tr>';

					$previous_refno = $row['refno'];
				}
				$objResponse->assign('blood_tbody', 'innerHTML', $str);
			}
			else {
				$empty_row = '<tr><td colspan="2">No Request Yet...</td></tr>';
				$objResponse->assign('blood_tbody', 'innerHTML', $empty_row);
			}
		 return $objResponse;
		}

		function radiology_test($encounter_nr, $pid, $refno) {
			global $db;
			$objResponse = new xajaxresponse();

			$query = "SELECT srs.refno, ss.name AS tests, ss.service_code FROM seg_radio_serv srs
								INNER JOIN or_main_other_requests omor ON (srs.refno=omor.request_refno)
								INNER JOIN care_test_request_radio ctrr ON (ctrr.refno=srs.refno)
								INNER JOIN seg_radio_services ss ON (ss.service_code=ctrr.service_code)
								WHERE omor.encounter_nr='$encounter_nr' AND omor.pid='$pid' AND omor.or_refno='$refno'
								AND omor.location=3 AND srs.status<>'deleted' AND ctrr.status<>'deleted'";

			$result = $db->Execute($query);
			if ($count = $result->RecordCount()) {

				$str = '';
				while ($row = $result->FetchRow()) {
					$current_refno = $row['refno'];

					if ($current_refno != $previous_refno && isset($previous_refno)) {
					$str .= '</tbody>
									 </table>
									 </td>
									 </tr>';
					}
					if ($current_refno != $previous_refno) {
						$str .= '<tr">
										 <td colspan="3">
											 <table style="font-size:12px; font-weight:bold">
												 <tr>
													 <td><img class="segSimulatedLink" src="../../../images/close_small.gif" border="0" onclick="delete_radiology_request('.$row['refno'].')" /></td>
													 <td colspan="2">Reference Number: '.$row['refno'].'</td>
												 </tr>
												 <tbody id="'.$row['refno'].'">';
					}
					$str .= '<tr>
										 <td></td>
										 <td width="15"><img class="segSimulatedLink" src="../../../images/close_small.gif" border="0" onclick="delete_radiology_service_code('.$row['refno'].',\''.$row['service_code'].'\')" /></td>
										 <td>'.$row['tests'].'</td>
									 </tr>';

					$previous_refno = $row['refno'];
				}
				$objResponse->assign('radio_tbody', 'innerHTML', $str);
			}
			else {
				$empty_row = '<tr><td colspan="2">No Request Yet...</td></tr>';
				$objResponse->assign('radio_tbody', 'innerHTML', $empty_row);
			}
		 return $objResponse;
		}

		function populate_sponge_list($or_main_refno) {
			global $db;
			$objResponse = new xajaxresponse();

			$seg_ops = new SegOps();
			$result = $seg_ops->get_sponge_count($or_main_refno);
			$str = '';
			if ($count = $result->RecordCount()) {
				while ($row = $result->FetchRow()) {
					$str .= '<tr id="sponge_row'.$row['sponge_code'].'">
										 <td align="center">'.$row['artikelname'].'<input name="sponges[]" value="'.$row['sponge_code'].'" type="hidden"></td>
										 <td align="center"><input name="sponge_quantity[]" id="sponge_qty'.$row['sponge_code'].'" readonly="readonly" type="text" value="'.$row['initial_count'].'"></td>
										 <td align="center">
											 <table>
												 <tr>
													 <td>
														 <input onkeyup="total_sponge_count(\'first'.$row['sponge_code'].'\')" id="first'.$row['sponge_code'].'0" name="first0[]" type="text" value="'.$row['f_count_table'].'">
													 </td>
													 <td>
														 <input onkeyup="total_sponge_count(\'first'.$row['sponge_code'].'\')" id="first'.$row['sponge_code'].'1" name="first1[]" type="text" value="'.$row['f_count_floor'].'">
													 </td>
													 <td>
														 <input onkeyup="total_sponge_count(\'first'.$row['sponge_code'].'\')" id="first'.$row['sponge_code'].'2" name="first2[]" readonly="readonly" type="text" value="'.($row['f_count_table']+$row['f_count_floor']).'">
													 </td>
												 </tr>
											 </table>
										 </td>
										 <td align="center">
											 <table>
												 <tr>
													 <td>
														 <input onkeyup="total_sponge_count(\'second'.$row['sponge_code'].'\')" id="second'.$row['sponge_code'].'0" name="second0[]" type="text" value="'.$row['s_count_table'].'">
													 </td>
													 <td>
														 <input onkeyup="total_sponge_count(\'second'.$row['sponge_code'].'\')" id="second'.$row['sponge_code'].'1" name="second1[]" type="text" value="'.$row['s_count_floor'].'">
													 </td>
													 <td>
														 <input onkeyup="total_sponge_count(\'second'.$row['sponge_code'].'\')" id="second'.$row['sponge_code'].'2" name="second2[]" readonly="readonly" type="text" value="'.($row['s_count_table']+$row['s_count_floor']).'">
													 </td>
												 </tr>
											 </table>
										 </td>
										</tr>';
				}
			}
			else {
				$str .= '<tr id="empty_sponge_row"><td colspan="4">No sponge item was added yet to the list of supplies...</td></tr>';
			}

			$objResponse->assign('sponge_item_tbody', 'innerHTML', $str);
			return $objResponse;
		}

		function delete_blood_request($refno, $encounter_nr, $hospital_number, $or_refno) {
				global $db;
				$srv=new SegLab;
				$objResponse = new xajaxResponse();

				$sql = "SELECT * FROM seg_pay_request WHERE ref_source = 'LD' AND ref_no = '$refno'";

				 $res=$db->Execute($sql);
				 $row=$res->RecordCount();

				if ($row==0){

						$status=$srv->deleteRequestor($refno);
						if ($status) {
								$srv->deleteLabServ_details($refno);
								$objResponse->addScriptCall("xajax_blood_test", $encounter_nr, $hospital_number, $or_refno);
								$objResponse->addAlert("The request was successfully deleted.");
						}
				 }else{
								 $objResponse->addAlert("The request cannot be deleted. It is already or partially paid.");
				 }
				return $objResponse;
		}

		function delete_laboratory_request($refno, $encounter_nr, $hospital_number, $or_refno){
			global $db;
			$srv=new SegLab;
			$objResponse = new xajaxResponse();

			$sql = "SELECT * FROM seg_pay_request WHERE ref_source = 'LD' AND ref_no = '$refno'";

			$res=$db->Execute($sql);
			$row=$res->RecordCount();

			if ($row==0){
				$status=$srv->deleteRequestor($refno);
				if ($status) {
					$srv->deleteLabServ_details($refno);
					$objResponse->addAlert("The request was successfully deleted.");
				}
				$objResponse->addScriptCall("xajax_laboratory_test", $encounter_nr, $hospital_number, $or_refno);
			}
			else {
				$objResponse->addAlert("The request cannot be deleted. It is already or partially paid.");
			}
			return $objResponse;
		}

		function delete_radiology_request($refno, $encounter_nr, $hospital_number, $or_refno){
			$objResponse = new xajaxResponse();
			$radio_obj = new SegRadio;

			if ($radio_obj->deleteRefNo($refno)) {
				$objResponse->addScriptCall("xajax_radiology_test", $encounter_nr, $hospital_number, $or_refno);
				$objResponse->alert("The request was successfully deleted.");
			}
			else{
				$objResponse->alert("The request cannot be deleted. It is already or partially paid.");
			}
			return $objResponse;
	 }

	 function delete_laboratory_service_code($refno, $service_code, $encounter_nr, $hospital_number, $or_refno, $delete_refno) {
		 $objResponse = new xajaxResponse();
		 global $db;
		 $seg_lab = new SegLab();

		 $result = $seg_lab->remove_service_code_by_refno($refno, $service_code);
		 if ($result) {
			 $objResponse->alert($service_code . ' successfully removed!');

			 if ($delete_refno) {
				 $sql = "SELECT * FROM seg_pay_request WHERE ref_source = 'LD' AND ref_no = '$refno'";
				 $res=$db->Execute($sql);
				 $row=$res->RecordCount();
				 if ($row==0){
					 $status=$seg_lab->deleteRequestor($refno);
					 if ($status) {
						 $seg_lab->deleteLabServ_details($refno);
					 }
				 }
				else {
					$objResponse->alert("The request cannot be deleted. It is already or partially paid.");
				}
			 }
			 $objResponse->addScriptCall("xajax_laboratory_test", $encounter_nr, $hospital_number, $or_refno);
		 }
		 else {
			 $objResponse->alert('Failed in removing the service code.');
		 }
		 return $objResponse;
	 }

	 function delete_blood_service_code($refno, $service_code, $encounter_nr, $hospital_number, $or_refno, $delete_refno) {
		 $objResponse = new xajaxResponse();
		 global $db;
		 $seg_lab = new SegLab();

		 $result = $seg_lab->remove_service_code_by_refno($refno, $service_code);
		 if ($result) {
			 $objResponse->alert($service_code . ' successfully removed!');

			 if ($delete_refno) {
				 $sql = "SELECT * FROM seg_pay_request WHERE ref_source = 'LD' AND ref_no = '$refno'";
				 $res=$db->Execute($sql);
				 $row=$res->RecordCount();
				 if ($row==0){
					 $status=$seg_lab->deleteRequestor($refno);
					 if ($status) {
						 $seg_lab->deleteLabServ_details($refno);
					 }
				 }
				else {
					$objResponse->alert("The request cannot be deleted. It is already or partially paid.");
				}
			 }
			 $objResponse->addScriptCall("xajax_blood_test", $encounter_nr, $hospital_number, $or_refno);
		 }
		 else {
			 $objResponse->alert('Failed in removing the service code.');
		 }
		 return $objResponse;
	 }

	 function delete_radiology_service_code($refno, $service_code, $encounter_nr, $hospital_number, $or_refno, $delete_refno) {
		 $objResponse = new xajaxResponse();
		 global $db;
		 $radio_obj = new SegRadio;

		 $result = $radio_obj->remove_service_code_by_refno($refno, $service_code);
		 if ($result) {
			 $objResponse->alert($service_code . ' successfully removed!');
			 if ($delete_refno) {
				 if (!$radio_obj->deleteRefNo($refno)) {
					 $objResponse->alert("The request cannot be deleted. It is already or partially paid.");
				 }
			 }
			 $objResponse->addScriptCall("xajax_radiology_test", $encounter_nr, $hospital_number, $or_refno);
		 }
		 else {
			 $objResponse->alert('Failed in removing the service code.');
		 }
		 return $objResponse;
	 }

	 function is_already_billed($encounter_nr) {
		 $objResponse = new xajaxResponse();
		 global $db;
		 $query = "SELECT COUNT(bill_nr) AS billed FROM seg_billing_encounter WHERE encounter_nr='$encounter_nr'";
		 $result = $db->Execute($query);
		 $row = $result->FetchRow();
		 if ($row['billed'] > 0) {
			 $objResponse->addScriptCall("J.blockUI({message: J('#billed_notification'), css: {width:'275px'}})");
		 }
		 else {
			 $objResponse->addScriptCall('J.unblockUI()');
		 }
		 return $objResponse;
	 }

	 //added by omick, August 25, 2009
	 function select_dr_patient($refno) {
		 global $db;
		 $objResponse = new xajaxResponse();
		 #edited by cha 11-11-09
		 $query = "SELECT sos.refno, sos.encounter_nr, cr.info as op_room, sos.pid, cp.name_last, cp.name_first, cp.name_middle,
							 CAST(fn_calculate_age(date_birth, NOW()) AS SIGNED INT) AS age, ce.admission_dt, ce.encounter_date, ce.consulting_dr as physician, cp.blood_group as blood_type
							 FROM seg_ops_serv sos
							 INNER JOIN care_encounter_op ceo ON (sos.refno = ceo.refno)
							 INNER JOIN care_room cr ON (cr.room_nr=ceo.op_room)
							 INNER JOIN care_person cp ON (cp.pid=sos.pid)
							 INNER JOIN care_encounter ce ON (ce.encounter_nr=sos.encounter_nr) WHERE sos.refno='$refno'";
		 $result = $db->Execute($query);
		 $row = $result->FetchRow();
		 $objResponse->assign('patient_name', 'value', $row['name_last'] . ', '. $row['name_first'] . ' ' . $row['name_middle']);
		 $objResponse->assign('patient_age', 'value', $row['age']);
		 $objResponse->assign('date_admitted', 'value',  date('F d, Y h:ia', strtotime($row['admission_dt'])));
		 $objResponse->assign('room_ward', 'value', $row['op_room']);
		 $objResponse->assign('pid', 'value', $row['pid']);
		 $objResponse->assign('encounter_nr', 'value', $row['encounter_nr']);
		 $objResponse->assign('ref_no', 'value', $row['refno']);
		 $objResponse->assign('physician', 'value', $row['physician']);
		 $objResponse->assign('hosp_num', 'value', $row['pid']);
		 $objResponse->assign('date_confinement', 'value',date('F d, Y h:ia', strtotime($row['encounter_date'])));
		 $objResponse->call("setBloodType","blood_type",$row['blood_type']);

		 #added by cha 11-11-09
		 $query2 = "select * from seg_or_delivery where refno=".$db->qstr($refno);
		 $result = $db->Execute($query2);
		 $row = $result->FetchRow();
		 #print_r($row);
		 $objResponse->assign('gravida', 'value', $row['gravida']);
		 $objResponse->assign('para', 'value', $row['para']);
		 $objResponse->assign('abortion', 'value', $row['abortion']);
		 $objResponse->assign('pregnancy_complications', 'value', $row['pregnancy_complications']);
		 $objResponse->assign('heart', 'value', $row['heart']);
		 $objResponse->assign('lungs', 'value', $row['lungs']);
		 $objResponse->assign('bp_1', 'value', $row['bp_1']);
		 $objResponse->assign('pulse_1', 'value', $row['pulse_1']);
		 $objResponse->assign('cervix_cm', 'value', $row['cervix_cm']);
		 $objResponse->assign('onset_date_time', 'value', date("m/d/Y H:i",strtotime($row['onset_date_time'])));
		 $objResponse->assign('dilation_date_time', 'value', date("m/d/Y H:i",strtotime($row['dilation_date_time'])));
		 $objResponse->assign('childborn_date_time', 'value', date("m/d/Y H:i",strtotime($row['childborn_date_time'])));
		 $objResponse->assign('ergonovine_date_time', 'value', date("m/d/Y H:i",strtotime($row['ergonovine_date_time'])));
		 $objResponse->assign('labor_duration_hour', 'value', $row['labor_duration_hour']);
		 $objResponse->assign('labor_duration_minute', 'value', $row['labor_duration_min']);
		 $objResponse->assign('blood_given', 'value', $row['blood_given']);
		 $objResponse->assign('operative', 'value', $row['operative']);
		 $objResponse->assign('episiotomy', 'value', $row['episiotomy']);
		 $objResponse->assign('analgesic_given', 'value', $row['analgesic_given']);
		 $objResponse->assign('anesthesia_given', 'value', $row['anesthesia_given']);
		 $objResponse->assign('complications', 'value', $row['complications']);
		 $objResponse->assign('fundus', 'value', $row['fundus']);
		 $objResponse->assign('umbiculus', 'value', $row['umbiculus']);
		 $objResponse->assign('post_bp', 'value', $row['post_bp']);
		 $objResponse->assign('post_temp', 'value', $row['post_temp']);
		 $objResponse->assign('post_pulse', 'value', $row['post_pulse']);
		 $objResponse->assign('post_resprate', 'value', $row['post_resprate']);
		 $objResponse->assign('deliver_dr', 'value', $row['deliver_dr']);
		 $objResponse->call("setPrenatal", $row['prenatal_care']);
		 $objResponse->call("setBloodType",$row['blood_type']);
		 $objResponse->call("setGenCondition",$row['general_condition'],$row['general_condition_others']);
		 $objResponse->call("setMembrane",$row['membrane_ruptured']);
		 $objResponse->call("setCervix",$row['cervix_condition']);
		 $objResponse->call("setDelivery",$row['delivery_spont']);
		 $objResponse->call("setPerineal",$row['perineal_tear']);
		 $objResponse->call("setBleeding",$row['bleeding']);
		 $objResponse->call("setLabor",$row['labor_onset']);
		 $objResponse->call("setLaborDurationHr",$row['labor_duration_hour']);
		 $objResponse->call("setLaborDurationMin",$row['labor_duration_min']);
		 return $objResponse;
	 }

		function populate_sub_anesthesia($anesthesia_type)
		{
				 global $db;
				 $objResponse = new xajaxResponse();

				 $query = "SELECT sub_anesth_id as id, description from seg_or_sub_anesthesia where anesthesia_id=".$db->qstr($anesthesia_type)." order by description asc";
				 $result = $db->Execute($query);
				 $options="";
				 if(is_object($result)){
							while($row = $result->FetchRow())
							{
								$options.="<option value='".$row['id']."'>".$row['description']."</option>";
							}
				 }

				 $objResponse->assign("sub_anaesthesia_list", "innerHTML", $options);
				 return $objResponse;
		}

		function show_added_anesthesia($anesth,$sub_anesth,$time_start,$ts_meridian,$time_end,$te_meridian)
		{
				global $db;
				$objResponse = new xajaxResponse();
				$query= "select ca.name as `category`, sa.description as `specific`, ca.nr as `anesthesia_id` from seg_or_sub_anesthesia as sa left join care_type_anaesthesia as ca".
				" on sa.anesthesia_id=ca.id where sa.sub_anesth_id=".$db->qstr($sub_anesth);
				#echo $query;

				$result = $db->Execute($query);
				$row = $result->FetchRow();
				if($row['category']=='' && $sub_anesth=='isoflurane')
					$row['category'] = "Combined G.A. + R.A.";
				if($row['category']=='' && $sub_anesth=='sedation')
					$row['category'] = "Sedation";

				$text1="View Anesthetics";
				$text2="Add Anesthetics";
				$rowSrc="<tr class='wardlistrow' id='row_anesthesia_".$anesth.$sub_anesth."'>".
				"<td width='5%' align='center'><img src='../../../images/btn_delitem.gif' style='cursor: pointer;' onclick='removeAnesthesia(\"".$anesth.$sub_anesth."\");'/></td>".
				"<td width='20%' align='center'>".$row['category']."<input type='hidden' id='anesth_category_".$anesth.$sub_anesth."' value='".$row['category']."'/></td>".
				"<td width='30%' align='center'>[".$row['specific']."]<input type='hidden' id='anesth_specific_".$anesth.$sub_anesth."' value='".$row['specific']."'/></td>".
				"<td width='10%' align='center'>".date("h:i",strtotime($time_start))." ".$ts_meridian."<input type='hidden' id='anesth_timestart_".$anesth.$sub_anesth."' value='".$time_start."'/></td>".
				"<td width='10%' align='center'>".date("h:i",strtotime($time_end))." ".$te_meridian."<input type='hidden' id='anesth_timeend_".$anesth.$sub_anesth."' value='".$time_end."'/></td>".
				"<td align='center'><img src='../../../images/btn_additems.gif' style='cursor: pointer;' onclick='return order_anesthetics(\"".$anesth.$sub_anesth."\");' onmouseover='tooltip(\"".$text2."\");' onmouseout='nd();'/></td>".
				"<td align='center' style='display:none' id='view-anesth".$anesth.$sub_anesth."'><img src='../../../images/cashier_view.gif' style='cursor: pointer;' onclick='return view_anesthetics(\"".$anesth.$sub_anesth."\");' onmouseover='tooltip(\"".$text1."\");' onmouseout='nd();'/></td>".
				"<td width='5%' style='' id='rowspacer".$anesth.$sub_anesth."'></td>".
				"<input type='hidden' id='row_anesthesia_name' name='row_anesthesia_name' value='".$anesth.$sub_anesth."'/>".
				"<input type='hidden' id='anesth_ts_meridian_".$anesth.$sub_anesth."' value='".$ts_meridian."'/>".
				"<input type='hidden' id='anesth_te_meridian_".$anesth.$sub_anesth."' value='".$te_meridian."'/>".
				"<input type='hidden' id='anesth_id_".$anesth.$sub_anesth."' value='".$row['anesthesia_id']."'/>".
				"</tr>";

				$objResponse->assign("empty_anesthesia_row", "style.display", "none");
				$objResponse->append("or_anesthesia_table-body", "innerHTML", $rowSrc);
				#$objResponse->assign("add_anesthetics_div", "style.display", "");
				#$objResponse->assign("submit_or_anesthesia_div", "style.display", "");
				return $objResponse;
		}

		#----added by cha, January 8, 2010-----
		function refresh_anesthesia($id, $details)
		{
			global $db;
			$objResponse = new xajaxResponse();
			$anesthetic_id = explode(",",$details['anesthetic_id']);
			for($i=0;$i<count($anesthetic_id);$i++)
			{
				if($id==$anesthetic_id[$i])
					$target_id = $i;
			}
			$anesthetic_qty = explode(",",$details['anesthetic_qty']);
			$anesthetic_cash = explode(",",$details['anesthetic_cash']);
			$anesthetic_charge = explode(",",$details['anesthetic_charge']);
			$cnt=0;
			for($i=0;$i<count($anesthetic_id);$i++)
			{
				if($i!=$target_id)
				{
					$new_id.="".$anesthetic_id[$i];
					$new_qty.="".$anesthetic_qty[$i];
					$new_cash.="".$anesthetic_cash[$i];
					$new_charge.="".$anesthetic_charge[$i];

					$query = "SELECT artikelname from care_pharma_products_main where bestellnum=".$db->qstr($anesthetic_id[$i]);
					$result = $db->Execute($query);
					$row = $result->FetchRow();
					$new_text.="".$row['artikelname'];
					$new_name.="".$row['artikelname'];
					$cnt++;
				}
				$max = count($anesthetic_id)-1;
					if($cnt!=$max)
					{
						$new_id.=",";
						$new_qty.=",";
						$new_cash.=",";
						$new_charge.=",";
						$new_name.=",";
						$new_text.=",";
					}
					else
					{
						$new_id.="";
						$new_qty.="";
						$new_cash.="";
						$new_charge.="";
						$new_name.="";
						$new_text.="";
					}
			}

			$objResponse->assign("rowtext".$details['anesthesia_id'], "innerHTML", $new_text);
			$new_rowimg = "<img src='../../../images/cashier_view_red.gif' style='cursor: pointer;' onclick='view_anesthetic_tray(\"".$new_name."\",\"".$new_qty."\",\"".$new_id."\",\"".$new_cash."\",\"".$new_charge."\",\"".$details['anesthesia_id']."\");'/>";
			$objResponse->assign("rowimg".$details['anesthesia_id'], "innerHTML", $new_rowimg);
			$objResponse->assign("anesthetic_id[]", "value", $new_id);
			$objResponse->assign("anesthetic_qty[]", "value", $new_qty);
			$objResponse->assign("anesthetic_pcash[]", "value", $new_cash);
			$objResponse->assign("anesthetic_pcharge[]", "value", $new_charge);
			return $objResponse;
		}

	function refresh_order_anesthetics($anesthetics)
	{
		global $db;
		$obj_response = new xajaxResponse();
		$details_id = explode(",",$anesthetics);
		for($i=0;$i<count($details_id);$i++)
		{
			$obj_response->call("removeSupplies",$details_id[$i]);
		}
		return $obj_response;
	}

function get_package_clinics($package_id) {
		 global $db;
		 $objResponse = new xajaxResponse();
		 $query = "SELECT spc.clinic_id, cd.name_formal FROM seg_packages_clinics spc INNER JOIN
												care_department cd ON (spc.clinic_id = cd.nr) WHERE spc.package_id = $package_id";

		 $result = $db->Execute($query);
		 if ($result) {
			 if ($result->RecordCount() > 0) {
				 while ($row = $result->FetchRow()) {
					 $details = new stdclass();
					 $details->clinic_id = $row['clinic_id'];
					 $details->name = $row['name_formal'];
					 $objResponse->addScriptCall('add_to_department', $details);
				 }
			 }
		 }
		 return $objResponse;
	 }

	 function get_package_item_details($package_id)
	 {
			global $db;
			$objResponse = new xajaxResponse();

			#$query = "SELECT d.item_purpose, d.item_id, m.artikelname, m.generic, m.price_cash, m.price_charge, d.quantity ".
			#" FROM seg_package_details AS d LEFT JOIN care_pharma_products_main AS m ".
			#"ON d.item_id=m.bestellnum  WHERE d.package_id=".$db->qstr($package_id);
			#revised code by angelo m. 09.13.2010
			$query="SELECT  d.item_purpose,
								d.item_id,
								m.artikelname,
								m.generic,
								m.price_cash,
								m.price_charge,
								d.quantity
							FROM seg_package_details AS d
								INNER JOIN care_pharma_products_main AS m ON d.item_id = m.bestellnum
								WHERE d.package_id = '$package_id'
							UNION ALL
							SELECT
							d.item_purpose,
								d.item_id,
								srs.name AS artikelname,
								srs.name AS generic,
								srs.price_cash,
								srs.price_charge,
								d.quantity
							FROM seg_package_details AS d
							 INNER JOIN seg_radio_services AS srs ON srs.service_code = d.item_id
							WHERE d.package_id = '$package_id'
							UNION ALL
							SELECT
							d.item_purpose,
								d.item_id,
								sls.name AS artikelname,
								sls.name AS generic,
								sls.price_cash,
								sls.price_charge,
								d.quantity
							FROM seg_package_details AS d
							 INNER JOIN seg_lab_services AS sls ON sls.service_code = d.item_id
							WHERE d.package_id = '$package_id'
							UNION ALL
							SELECT
								d.item_purpose,
								d.item_id,
								sos.name          AS artikelname,
								sos.name          AS generic,
								sos.price AS price_cash,
								sos.price AS price_charge,
								d.quantity
							FROM seg_package_details AS d
								INNER JOIN seg_other_services AS sos
									ON sos.service_code = d.item_id
							WHERE d.package_id = '$package_id'";

			$result = $db->Execute($query);
			if ($db->Affected_Rows()) {
				 if ($result->RecordCount() > 0) {
					 while ($row = $result->FetchRow()) {
							$details->mode = $row['item_purpose'];
							$details->id = $row['item_id'];
							$details->name = $row['artikelname'];
							$details->desc = $row['generic'];
							$details->qty = $row['quantity'];
							#$details->unit = $row['unit'];
							$details->restricted = 0;
						 $objResponse->addScriptCall('add_to_itemlist', $details);
					 }
				 }
			 }
			return $objResponse;
	 }

	 function get_misc_request_by_refno($table, $refno) {
			global $db;
			$objResponse = new xajaxResponse();
			$seg_ormisc = new SegOR_MiscCharges();

			if ($result = $seg_ormisc->getMiscOrderItemsByRefno($refno)) {
				while ($misc_item = $result->FetchRow()) {
					$details = new stdclass();
					$details->code = $misc_item['code'];
					$details->name = $misc_item['name'];
					$details->type_name = $misc_item['name_short'];
					$details->price = $misc_item['chrg_amnt'];
					$details->account_type = $misc_item['account_type'];
					//added by:ian
					$details->lock = $misc_item['is_not_socialized'];
					$details->quantity = $misc_item['quantity'];
					if ($misc_item['area'] == $area) {
						$details->is_removable = 1;
					}
					else {
						$details->is_removable = 0;
					}
					$details->status = $misc_item['request_flag'];

					$details->disable = 0;
					if($misc_item["request_flag"]!="")
						$details->disable = 1;
					$objResponse->call('retrieve_misc', $table, $details);
				}
			}
			//$objResponse->alert($seg_ormisc->sql);

			return $objResponse;
		}

		function isSpongeType($bestellnum,$val,$type){
			global $db;


			$isSpongeType=0;
			$objResponse=new xajaxResponse();
			$query="SELECT COUNT(cp.bestellnum) AS isSpongeType
							FROM care_pharma_products_main AS cp
							WHERE cp.bestellnum = '".$bestellnum."'
									AND cp.artikelname LIKE 'sponge%'
									 OR cp.generic LIKE 'sponge%';";
			$result=$db->Execute($query);
			if($result){
				$row=$result->FetchRow();
				$isSpongeType=$row["isSpongeType"];
			}
			if($isSpongeType==1){
				$details->id = $bestellnum;
				$details->qty = $val;
				$details->type = $type;
				$objResponse->call('adjust_sponge_quantity',$bestellnum,$val,$type);
			}

			return $objResponse;
		}




$xajax->processRequests();
?>