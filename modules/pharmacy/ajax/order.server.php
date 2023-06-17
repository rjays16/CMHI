<?php
#added by bryan on Sept 18,2008
#

function reset_referenceno() {
	global $db;
	$objResponse = new xajaxResponse();

	$order_obj = new SegOrder("pharma");
	$lastnr = $order_obj->getLastNr(date("Y-m-d"));

	if ($lastnr)
		$objResponse->call("resetRefNo",$lastnr);
	else
		$objResponse->call("resetRefNo","Error!",1);
	return $objResponse;
}

function get_charity_discounts( $nr ) {
	global $db;
	$objResponse = new xajaxResponse();
	$discount= new SegDiscount();
	$ergebnis=$discount->GetEncounterCharityGrants( $nr );
	$objResponse->call("clearCharityDiscounts");
	if ($ergebnis) {
		$rows=$ergebnis->RecordCount();
		while($result=$ergebnis->FetchRow()) {
			$objResponse->call("addCharityDiscount",$result["discountid"],$result["discount"]);
		}
	}
	$objResponse->call("cClick");
	$objResponse->call("refreshDiscount()");
	return $objResponse;
}

function populate_order( $refno, $discountID,$disabled=NULL) {
	global $db, $config;
	$objResponse = new xajaxResponse();
	$order_obj = new SegOrder("pharma");
	$pharma = new SegPharmaProduct();
	$result = $order_obj->getOrderItemsFullInfo($refno, $discountID);
	$objResponse->call("clearOrder",NULL);
	$encounter_nr = $order_obj->getencounter($refno);

	//$objResponse->alert($encounter_nr);

	$insurance_type = $pharma->getinsrnceTyp($encounter_nr);
	$room_types=$pharma->getRoomTypes($encounter_nr);	
	$INCPHICaddtional =".".$pharma->insurancePHIC($room_types,$insurance_type);
	$INCNONPHICaddtional = ".".$pharma->insuranceNONPHIC($room_types);
	$Ifhasroom = $room_types ? 1 : 0;

	$rows = 0;
	if ($result) {
		$rows=$result->RecordCount();
		$instructions = "";
		$hasPrescription = false;
		while ($row=$result->FetchRow()) {
			
			//added by mai 10-02-2014	
			if(($row['prescription_id'])){

				if($row["item_code"]){
				
					$obj->id = $row["item_code"];
					$obj->name = $row["artikelname"];
					$obj->desc= $row["description"];
					$obj->prcCash = $row["cshrpriceppk"];
					$obj->prcCharge = $row["chrgrpriceppk"];
					$obj->prcCashSC = $row["cashscprice"];
					$obj->prcChargeSC = $row["chargescprice"];
					$obj->prcDiscounted = $row["dprice"];
					$obj->isSocialized = $row["is_socialized"];
					$obj->forcePrice = $row["force_price"];
					$obj->dos_qty = $row["quantity_prescription"];
					$obj->qty = $row["quantity"];
					$obj->isConsigned = $row['is_consigned'];
					$obj->priceincreaseperoomPHIC=$INCPHICaddtional;
					$obj->priceincreaseperoomNONPHIC=$INCNONPHICaddtional;
					$obj->Ifhasroom=$Ifhasroom;
					$obj->isExternal = 0;
					$hasPrescription = true;
				}else{
				// $objResponse->alert('2');
					$obj->id = "";
					$obj->name = $row["item_name"];
					$obj->desc= "";
					$obj->prcCash = 0;
					$obj->prcCharge = 0;
					$obj->prcCashSC = 0;
					$obj->prcChargeSC = 0;
					$obj->prcDiscounted = 0;
					$obj->isSocialized = 0;
					$obj->forcePrice = 0;
					$obj->dos_qty = $row["quantity_prescription"];
					$obj->qty = $row["quantity_prescription"];
					$obj->isConsigned = 0;
					$obj->isExternal = 1;
				}
			//	$objResponse->alert('3');
				$obj->prescription_id = $row['prescription_id'];
				$obj->item_code = $row['item_code'];
				$obj->item_name = $row['item_name'];
				$obj->dosage = $row['dosage'];
				$obj->period_count = $row['period_count'];
				$obj->period_interval = $row['period_interval'];
					$obj->orgchrgprc = $row["chrgrpriceppk"];
				$obj->orgpcash = $row["cashscprice"];
				$obj->orgpcashsc = $row["chargescprice"];
				$obj->d = $row["dprice"];

				$instructions = $row['instructions'];
			}else{
				// $objResponse->alert('4');
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
				$obj->dos_qty = $row["quantity"];
				$obj->qty = $row["quantity"];
				$obj->isConsigned = $row['is_consigned'];
				$obj->isExternal = 0;
				$obj->prescription_id = "";
				$obj->item_code = "";
				$obj->item_name = $row["artikelname"];
				$obj->dosage = "";
				$obj->period_count = "";
				$obj->period_interval = "";
				$obj->isExternal = 0;
				$obj->priceincreaseperoomPHIC=$INCPHICaddtional;
				$obj->priceincreaseperoomNONPHIC=$INCNONPHICaddtional;
				$obj->Ifhasroom=$Ifhasroom;

				$instructions = "";

				
			}
			
			//end added by mai
			
			// $objResponse->alert(print_r($obj,TRUE));
			$existingOrder = true;
			$objResponse->call("appendOrder", NULL, $obj, $disabled, $existingOrder); //updated by mai 07/28/2-14, added existingOrder
		}
		
		//added by mai
		$objResponse->assign('instructions', 'value', $instructions);
		$objResponse->assign('save_prescription', 'checked', $hasPrescription);
		$objResponse->assign('prescription_id', 'value', $obj->prescription_id);
		//end mai

		if (!$rows) $objResponse->call("appendOrder",NULL,NULL);
		$objResponse->call("refreshDiscount");
	}
	else {
		if ($config['debug']) {
			$objResponse->alert("SQL error: ",$order_obj->sql);
			# $objResponse->alert($sql);
		}
		else {
			$objResponse->alert("A database error has occurred. Please contact your system administrator...");
		}
	}
	return $objResponse;
}


function add_item( $discountID, $items, $qty, $prc, $consigned ) {
	global $db;
	$dbtable='care_pharma_products_main';
	$prctable = 'seg_pharma_prices';
	$objResponse = new xajaxResponse();

	# Later: Put this in a Class
	if (!is_array($items)) $item = array($items);
	if (!is_array($qty)) $qty = array($qty);
	if (!is_array($prc)) $prc = array($prc);
	if (!is_array($consigned)) $prc = array($consigned);

	foreach ($items as $i=>$item) {

		$sql="SELECT a.*,\n".
			"IFNULL((SELECT d1.price FROM seg_service_discounts AS d1 WHERE d1.service_code=a.bestellnum AND d1.service_area='PH' AND d1.discountid='SC'),b.cshrpriceppk*(1-IFNULL((SELECT discount FROM seg_discount WHERE discountid='SC'),0.2))) AS cashscprice,\n".
			"IFNULL((SELECT d1.price FROM seg_service_discounts AS d1 WHERE d1.service_code=a.bestellnum AND d1.service_area='PH' AND d1.discountid='SC'),b.cshrpriceppk*(1-IFNULL((SELECT discount FROM seg_discount WHERE discountid='SC'),0.2))) AS chargescprice,\n".
			"IFNULL(b.ppriceppk,0) AS ppriceppk,\n".
			"IFNULL(b.chrgrpriceppk,0) AS chrgrpriceppk,\n".
			"IF(a.is_socialized,\n".
				"IFNULL((SELECT d2.price FROM seg_service_discounts AS d2 WHERE d2.service_code=a.bestellnum AND d2.service_area='PH' AND d2.discountid='$discountID'),b.cshrpriceppk),\n".
				"cshrpriceppk) AS dprice,\n".
			"IFNULL(b.cshrpriceppk,0) AS cshrpriceppk\n".
			"FROM care_pharma_products_main AS a\n".
			"LEFT JOIN seg_pharma_prices AS b ON a.bestellnum=b.bestellnum\n".
			"WHERE a.bestellnum = '$item'";
		$ergebnis=$db->Execute($sql);

#			$objResponse->alert(print_r($qty,true));
		if ($ergebnis) {
			$rows=$ergebnis->RecordCount();
			$objResponse->call("clearOrder",NULL);
			while($result=$ergebnis->FetchRow()) {
				$obj = (object) 'details';
				$obj->id = $result["bestellnum"];
				$obj->name = $result["artikelname"];
				$obj->desc= $result["description"];
				$obj->prcCash = $result["cshrpriceppk"];
				$obj->prcCharge = $result["chrgrpriceppk"];
				$obj->prcCashSC = $result["cashscprice"];
				$obj->prcChargeSC = $result["chargescprice"];
				$obj->prcDiscounted = $result["dprice"];
				$obj->isSocialized = $result["is_socialized"];
				$obj->forcePrice = $prc[$i];
				$obj->qty = $qty[$i];
				$obj->isConsigned = $consigned[$i];
				$objResponse->call("appendOrder", NULL, $obj);
			}
		}
		else {
			if (defined('__DEBUG_MODE'))
				$objResponse->call("display",$sql);
			else
				$objResponse->alert("A database error has occurred. Please contact your system administrator...");
		}
	}
	return $objResponse;
}

#added by bryan on Sept 18,2008
function populateOrderList($page_num=0, $max_rows=10, $sort_obj=NULL, $args=NULL) {
	global $config;

	$objResponse = new xajaxResponse();
	$oclass = new SegOrder();
	$presc_obj = new SegPrescription();
	$selpayor = "";
	$seldate = "";
	$selarea = "";
	$selpayor = $args["selpayor"];
	$seldate = $args["seldate"];
	$selarea = $args["selarea"];

	$filters = array();
	if($selpayor!="") {
		switch(strtolower($args["selpayor"])) {
			case "name":
				$filters["NAME"] = $args["name"];
			break;
			case "pid":
				$filters["PID"] = $args["pid"];
			break;
			case "patient":
				$filters["PATIENT"] = $args["patientname"];
			break;
			case "inpatient":
				$filters["INPATIENT"] = $args["inpatientname"];
			break;
		}
	}

	if($args["seldate"]!="") {
		switch(strtolower($args["seldate"])) {
			case "today":
				$search_title = "Today's Active Requests";
				$filters['DATETODAY'] = "";
			break;
			case "thisweek":
				$search_title = "This Week's Active Requests";
				$filters['DATETHISWEEK'] = "";
			break;
			case "thismonth":
				$search_title = "This Month's Active Requests";
				$filters['DATETHISMONTH'] = "";
			break;
			case "specificdate":
				$search_title = "Active Requests On " . date("F j, Y",strtotime($args["specificdate"]));
				$dDate = date("Y-m-d",strtotime($args["specificdate"]));
				$filters['DATE'] = $dDate;
			break;
			case "between":
				$search_title = "Active Requests From " . date("F j, Y",strtotime($args["between1"])) . " To " . date("F j, Y",strtotime($args["between2"]));
				$dDate1 = date("Y-m-d",strtotime($args["between1"]));
				$dDate2 = date("Y-m-d",strtotime($args["between2"]));
				$filters['DATEBETWEEN'] = array($dDate1,$dDate2);
			break;
		}
	}

	if ($args["selarea"]!="") {
		$filters["AREA"] = $args["selarea"];
	}

	$offset = $page_num * $max_rows;
	$sortColumns = array('orderdate','refno','name_last','','is_urgent','area_full');
	$sort = array();
	if (is_array($sort_obj)) {
		foreach ($sort_obj as $i=>$v) {
			$col = $sortColumns[$i] ? $sortColumns[$i] : "orderdate";
			if ((int)$v < 0) $sort[] = "$col DESC";
			elseif ((int)$v > 0) $sort[] = "$col ASC";
		}
	}
	if ($sort) $sort_sql = implode(',', $sort);
	else $sort_sql = 'orderdate DESC';

	if($args["is_prescription"] == "true"){ //added by mai 10-21-2014
		$result=$presc_obj->getPrescriptionOrders($filters, $offset);
	}else{
		$result=$oclass->getActiveOrders($filters, $offset, $list_rows, $sort_sql);
	}
	
//	if ($_SESSION['sess_temp_userid'] === 'admin') {
//		$objResponse->alert($oclass->sql);
//	}

	if($result) {
		$found_rows = $oclass->FoundRows();
		$last_page = ceil($found_rows/$max_rows)-1;
		if ($page_num > $last_page) $page_num=$last_page;

		if($data_size=$result->RecordCount()) {
			$temp=0;
			$i=0;
			$objResponse->contextAssign('currentPage', $page_num);
			$objResponse->contextAssign('lastPage', $last_page);
			$objResponse->contextAssign('maxRows', $max_rows);
			$objResponse->contextAssign('listSize', $found_rows);

			$DATA = array();
			while($row = $result->FetchRow()) {

				$urgency = $row["is_urgent"]?"Urgent":"Normal";
				$name = strtoupper($row["name"]);
				if (!$name) $name='<i styl	e="font-weight:normal">No name</i>';
				$class = (($count%2)==0)?"":"alt";

				//$items = explode("\n",$row["items"]);
				//$items = implode(", ",$items);
				//'stock_date','stock_nr','ward_name','items','encoder','area_full',

				$items_result = explode("\n",$row["items"]);
				$items = array();
				$served = 0;
				$is_paid = 0;
				$is_lingap = 0;
				$is_cmap = 0;
				$is_charity = 0;
				foreach ( $items_result as $j=>$v ) {
//          if (substr($v,0,1)=='S') $served=1;
//          $items[$j] = substr($v,2);
					$item_parse = explode("\t", $v);
					switch(strtolower($item_parse[0])) {
						case 'paid':
							$is_paid=1;
						break;
						case 'lingap':
							$is_lingap=1;
						break;
						case 'cmap':
							$is_cmap=1;
						break;
						case 'charity':
							$is_charity=1;
						break;
					}
					if (strtoupper($item_parse[1])=='S')
						$served=1;
					$items[$j] = $item_parse[2];
				}
				$items = implode(", ",$items);

				// determine FLAG
				$flag = '';
				if ($is_lingap)
					$flag = 'lingap';
				if ($is_cmap)
					$flag = 'cmap';
				if ($is_charity)
					$flag = 'charity';
				if ($is_paid)
					$flag = 'paid';

				$DATA[$i]['orderdate'] = nl2br(date("Y-m-d\nh:ia",strtotime($row['orderdate'])));
				$DATA[$i]['refno'] = $row['refno'];
				$DATA[$i]['name'] = $name;
				$DATA[$i]['items'] = $items;
				$DATA[$i]['is_cash'] = $row['is_cash'];
				$DATA[$i]['urgency'] = $urgency;
				$DATA[$i]['area_full'] = $row['area_full'];
				$DATA[$i]['paid'] = $is_paid;
				$DATA[$i]['lingap'] = $is_lingap;
				$DATA[$i]['cmap'] = $is_cmap;
				$DATA[$i]['charity'] = $is_charity;
				$DATA[$i]['flag'] = $flag;
				$DATA[$i]['served'] = $served;
				$DATA[$i]['FLAG'] = 1;
				$DATA[$i]['prescription_id'] = ($row["is_cash"] == '' ? $row["prescription_id"] : "");
				$DATA[$i]['encounter_nr'] = ($row["is_cash"] == '' ? $row["encounter_nr"] : "");
				$i++;
			} //end while
			if (!$_REQUEST['selpayor']) $_REQUEST['selpayor']='name';

			$objResponse->contextAssign('dataSize', $data_size);
			$objResponse->contextAssign('listData', $DATA);
		}
		else {
			$objResponse->contextAssign('dataSize', 0);
			$objResponse->contextAssign('listData', NULL);

//			if ($config['debug'])
//				$objResponse->alert("SQL empty result: ".$oclass->sql);
		}

	} else {
		// error
			if (!$config['debug']){
				$objResponse->alert("A database error has occurred. Please contact your system administrator...");
			}

		$objResponse->contextAssign('dataSize', -1);
		$objResponse->contextAssign('listData', NULL);
	}

	$objResponse->script('this.fetchDone()');
	return $objResponse;
}

function deleteOrder($refno) {
	global $db;
	$objResponse = new xajaxResponse();
	$oclass = new SegOrder();
	$comp_obj = new Company();

	if ($oclass->deleteOrder($refno)) {
#    if (true) {
		/*added by mai ^_^ 07-16-2014*/
       if ($comp_obj->deleteChargetoCompanyTransaction($refno, 'PHA')){ //delete ledger charged to company
            $objResponse->call('prepareDelete',$refno);
        }else{
            $objResponse->call('lateAlert',"Unable to delete the request from company ledger.",1000);
        }
       /*end added by mai*/
	}
	else {
		$objResponse->call('lateAlert',$db->ErrorMsg(), 1000);
	}
	return $objResponse;
}

function updatePHICCoverage($enc_nr) {
	$objResponse = new xajaxResponse();
	if ($enc_nr) {
		$bill_date = strftime("%Y-%m-%d %H:%M:%S");
		$bc = new Billing($enc_nr, $bill_date);
		$bc->getConfinementType();
		#$bc->getMedicineBenefits();
		#$meds = $bc->getMedConfineBenefits();
		$bc->getConfineBenefits('MS','M');
		$confine = $bc->med_confine_benefits;

		$amount = 0;
		foreach ($confine as $v) {
			if ($v->hcare_id == 18) {
				$amount = $v->hcare_amountlimit;
			}
		}

		$objResponse->assign('phic_cov','innerHTML', number_format($amount,2));
		//$objResponse->script('alert($("phic_cov").innerHTML)');
	}
	else
		$objResponse->assign('phic_cov','innerHTML', 'None');
	return $objResponse;
}

function updateCoverage($enc_nr, $type, $nr='') {
	global $db;

	$objResponse = new xajaxResponse();
	$amount = 0;

	//$objResponse->alert($type);
	//$objResponse->alert($enc_nr);
	if ($enc_nr) {
		if ($type=='PHIC') {
			$bill_date = strftime("%Y-%m-%d %H:%M:%S");
			$bc = new Billing($enc_nr, $bill_date);
			$bc->getConfinementType();
			$amount = 0;

			define('__HCARE_ID__',18);
			
			$bc->getConfineBenefits('MS','M', 0, true);
            $confine = $bc->med_confine_benefits;
            $amount = 0;
 			
 			/*$setmorethanone = '0';
            if($setmorethanone){
            	$total_coverage = $bc->getActualMedCoverage(__HCARE_ID__);
            	$HasManualCoverage=$bc->GetManualPhicCoverage($enc_nr, $area='pharma');
            	
            	if($HasManualCoverage){
            		$total_benefits = $HasManualCoverage;
            	}else{
	            	foreach ($confine as $v) {
	                if ($v->hcare_id == __HCARE_ID__) {
	                    $total_benefits = $v->hcare_amountlimit;
	                }
	            }
        	}
            }else{
            	$HasManualCoverage=$bc->GetManualPhicCoverageAll($enc_nr);        
	            
	            if($HasManualCoverage){
	            	$total_coverageLR = $bc->getActualSrvCoverage(__HCARE_ID__);
	            	$total_coverageM = $bc->getActualMedCoverage(__HCARE_ID__);
	            	$total_coverage = $total_coverageLR + $total_coverageM;
	            	$total_benefits = $HasManualCoverage;
	            }else{
	            	$total_coverage = $bc->getActualMedCoverage(__HCARE_ID__);
		            foreach ($confine as $v) {
		                if ($v->hcare_id == __HCARE_ID__) {
		                    $total_benefits = $v->hcare_amountlimit;
		                }
		            }
	        	}
            }*/
        	
        	//added by mai 10-03-2014
            $enc = new Encounter();
            $total_benefits = $enc->getManualCoverage($enc_nr);
            $total_coverage = $enc->getPhicCosts($enc_nr, 'pha', $nr);
            //end added by mai

			$objResponse->assign('coverage','value', (float)$total_benefits - (float)$total_coverage);
			$objResponse->call('refreshTotal');
		}
		elseif ($type=='LINGAP') {
			$lc = new SegLingapPatient();
			$pid = $db->GetOne("SELECT pid FROM care_encounter WHERE encounter_nr=".$db->qstr($enc_nr));
			$amount = $lc->getBalance($pid);
			#$objResponse->assign('cov_amount','innerHTML', number_format($amount,2));
			$objResponse->assign('coverage','value', $amount);
			$objResponse->call('refreshTotal');
		}
		elseif ($type=='CMAP') {
			$amount = 0;
			#$objResponse->assign('cov_amount','innerHTML', number_format($amount,2));

			$pc = new SegCMAPPatient();
			$pid = $db->GetOne("SELECT pid FROM care_encounter WHERE encounter_nr=".$db->qstr($enc_nr));
			$amount = $pc->getBalance($pid);

			$objResponse->assign('coverage','value', $amount);
			$objResponse->call('refreshTotal');
		}
		else {
			$objResponse->assign('cov_type','innerHTML', '');
			$objResponse->assign('cov_amount','innerHTML', '');
			$objResponse->assign('coverage','value', -1);
			$objResponse->call('refreshTotal');
		}

		//$objResponse->script('alert($("phic_cov").innerHTML)');
	}
	else
		$objResponse->assign('cov_amount','innerHTML', '');
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

require('./roots.php');
require($root_path.'include/inc_environment_global.php');
require($root_path.'include/care_api_classes/class_discount.php');
require($root_path.'include/care_api_classes/class_order.php');
require_once($root_path."include/care_api_classes/billing/class_billing.php");
require_once($root_path."include/care_api_classes/sponsor/class_lingap_patient.php");
require_once($root_path."include/care_api_classes/sponsor/class_cmap_patient.php");
require_once($root_path.'modules/pharmacy/ajax/order.common.php');
require_once($root_path."include/care_api_classes/class_company.php"); /*added by mai 07-15-2014*/
require_once($root_path."include/care_api_classes/class_encounter.php"); /*added by mai 07-15-2014*/
require_once($root_path."include/care_api_classes/prescription/class_prescription_writer.php"); //mai 10-21-2014
require_once($root_path.'include/care_api_classes/class_pharma_product.php'); //adde by julz
$xajax->processRequest();
