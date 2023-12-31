<?php
/**
 * Helper class for Cashier module
 *
 *
 */

require "./roots.php";
require_once $root_path.'include/care_api_classes/class_core.php';
require_once $root_path.'include/care_api_classes/sponsor/class_request.php';
#added by janken 10/13/2014 for requiring curl class
require_once $root_path.'include/care_api_classes/curl/class_curl.php';

class SegCashier extends Core {

	var $target;
	var $items_tb;
	var $discounts_tb;
	var $prod_tb;

	var $pay_tb = "seg_pay";
	var $pharma_tb = "seg_pharma_orders";
	var $pharma_details_tb = "seg_pharma_order_items";
	var $pharma_items_tb = "care_products_main";
	var $lab_tb = "seg_lab_serv";
	var $lab_details_tb = "seg_lab_servdetails";
	var $lab_items_tb = "seg_lab_services";
	var $rad_tb = "seg_radio_serv";
	var $rad_details_tb = "care_test_request_radio";
	var $rad_items_tb = "seg_radio_services";
	var $or_tb = "seg_ops_serv";
	var $or_details_tb = "care_test_request_radio";
	var $or_items_tb = "seg_radio_services";

	var $req_tb = "seg_pay_request";
	var $req_check_tb = "seg_pay_checks";
	var $req_card_tb = "seg_pay_credit_cards";
	var $pay_deposit_tb = "seg_pay_deposit";

	var $fld_pay = array(
		"or_no",
		"account_type",
		"cancel_date",
		"cancelled_by",
		"or_date",
		"or_name",
		"or_address",
		"encounter_nr",
		"pid",
		"amount_tendered",
                "discount_tendered",
		"vat_amount",
		"amount_due",
		"remarks",
		"history",
		"create_id",
		"create_dt",
		"modify_id",
		"modify_dt"
	);

	var $fld_pay_deposit = array(
        "or_no",
        "encounter_nr",
        "deposit",
        "ref_no",
        "ref_source"
    );

	function SegCashier() {
		$this->coretable = $this->pay_tb;
		$this->setupLogger('cashier');
	}

	function usePay() {
		$this->coretable = $this->pay_tb;
		$this->setRefArray($this->fld_pay);
	}

	function usePayDeposit() {
		$this->coretable = $this->pay_deposit_tb;
		$this->setRefArray($this->fld_pay_deposit);
	}

	/**
	*
	*
	* @param mixed $ref
	* @param mixed $code
	* @param mixed $flag
	*/
	function flag($OR, $flag='') {
		global $db;
		// retrieve item details for this payment item
		$items = $db->GetRows("SELECT ref_no, ref_source, service_code FROM seg_pay_request WHERE or_no=".$db->qstr($OR));
		if ($items !== false) {
			if ($items) {
				if (!$flag) $flag=NULL;
				else $flag = $db->qstr($flag);

				$cost_center_items = array();
				$cost_center_items['PH'] = array();
				$cost_center_items['RD'] = array();
				$cost_center_items['LD'] = array();

				$cost_centers = Array(
					'PH' => array(
						'coreTable' => 'seg_pharma_orders',
						'detailsTable' => 'seg_pharma_order_details',
						'referenceNo' => 'refno',
						'itemCode' =>'bestellnum',
						'flagField' => 'request_flag'
					),
					'LD' => array(
						'coreTable' => 'seg_lab_serv',
						'detailsTable' => 'seg_lab_servdetails',
						'referenceNo' => 'refno',
						'itemCode' =>'service_code',
						'flagField' => 'request_flag'
					),
					'RD' => array(
						'coreTable' => 'seg_radio_serv',
						'detailsTable' => 'care_test_request_radio',
						'referenceNo' => 'refno',
						'itemCode' =>'service_code',
						'flagField' => 'request_flag'
					)
				);
				// group items into corresponding table
				foreach ($items as $rowIndex=>$item) {
					if ( in_array(strtoupper($item['ref_source']), array_keys($cost_centers)) ) {
						$cost_center_items[ strtoupper($item['ref_source']) ][] = $item;
					}
				}

				foreach ( array_keys($cost_centers) as $center_code ) {
					if ($cost_center_items[ $center_code ]) {
							$this->sql = sprintf("UPDATE %s SET %s={$flag} WHERE %s=? AND %s=?",
								$cost_centers[$center_code]['detailsTable'],
								$cost_centers[$center_code]['flagField'],
								$cost_centers[$center_code]['referenceNo'],
								$cost_centers[$center_code]['itemCode']
							);
						if ( ($this->result=$db->Execute($this->sql, $cost_center_items[ $center_code ])) !== false) {
							// success!!
						}
						else {
							$this->logger->error("Error on request flag: ".$db->ErrorMsg()."\nQuery:".$this->sql);
							return false;
						}
					}
				}
			}
			return true;
		}
		else
			return false;
	}

	function DeleteOR($orno) {
		global $db;
		if ($saveOK = $this->CancelOR($orno)) {
			$this->sql = "DELETE FROM seg_pay WHERE or_no=".$db->qstr($orno);
			if (($this->result=$db->Execute($this->sql))  !== false) {
				return true;
			}
			else {
				return false;
			}
		}
		else {
			return false;
		}
	}

	function CancelOR($orno) {
		global $db;
		$db->SetFetchMode(ADODB_FETCH_ASSOC);

		#added by janken 11/04/2014 for the curl class
		$curl_obj = new Rest_Curl;

		$by = $db->qstr($_SESSION['sess_temp_userid']);
		$saveOK = false;

// # Code for Pre-paid Consultation, disabled for now...
//		$prepaidObj = new SegPrepaid();
//		if ($prepaidObj->hasPrepaidByOrno($orno)) {
//			$this->setErrorMsg('Cannot cancel OR #'.$orno.'. Consultation item was already used.');
//			return false;
//		}

		/* Get payment items */
		$this->sql = "SELECT ref_source, ref_no, service_code FROM seg_pay_request WHERE or_no=".$db->qstr($orno);
		$rs = $db->Execute($this->sql);
		if ($rs !== false) {
			$saveOK = true;
			$rows = $rs->GetRows();
			foreach ($rows as $row) {
				switch (strtoupper($row['ref_source'])) {
					case 'PH': $type=SegRequest::PHARMACY_REQUEST; break;
					case 'LD': $type=SegRequest::LABORATORY_REQUEST; break;
					case 'RD': $type=SegRequest::RADIOLOGY_REQUEST; break;
					case 'FB': $type=SegRequest::BILLING_REQUEST; break;
					case 'MISC': $type=SegRequest::MISC_REQUEST; break;
					case 'COM':  //added by mai 07/24/2014
						$saveOK = false;
						$this->sql= "UPDATE seg_company_ledger SET trans_type = 'CRMEMO' WHERE trans_type ='PAYMNT' AND trans_source = 'CSH' AND trans_refno = ".$db->qstr($orno);
						if($res=$db->Execute($this->sql)){
							if($db->Affected_Rows()){
								$saveOK = true;					
							}
						}//end added by mai
					break;
					case 'PF': /*added by mai 08-25-2014*/
						$saveOK = false;
						$this->sql= "UPDATE seg_pf_service_details SET request_flag = NULL WHERE dr_nr =".$db->qstr($row['service_code'])." AND refno=".$db->qstr($row['ref_no']);
						if($res=$db->Execute($this->sql)){
							if($db->Affected_Rows()){
								$saveOK = true;					
							}
						}
					break; /*end added by mai*/
				}

				// for Cost Centers
				if ($type) {
					$request = new SegRequest( $type, array('refNo'=>$row['ref_no'], 'itemNo'=>$row['service_code']));
					$saveOK=$request->flag(null);
					if (!$saveOK) {
						$this->setErrorMsg('Unable to unflag request...');
						break;
					}
				}
			}
		}else{
				$saveOK = false;
				$this->setErrorMsg('Cannot retrieve payment items...');
		}
		
		if ($saveOK) {
			$this->sql = "UPDATE seg_pay SET cancel_date=NOW(),cancelled_by=$by,history=CONCAT(history,'Cancelled: ".date('Y-m-d H:i:s')." [".addslashes($_SESSION['sess_temp_userid'])."]\n') WHERE or_no=".$db->qstr($orno);
			$saveOK=$db->Execute($this->sql);
			if (!$saveOK) {
				$this->setErrorMsg('Cannot update payment information...');
			}
		}

		if($saveOK){
		#added by janken 10/29/2014 for passing data in FIS
		     if(ENABLE_FIS){
		$curl_obj->inpatientDeleteBillEntry($orno);
		     }
                }

		return $saveOK;
	}

	function UnCancelOR($orno) {
		global $db;
		$db->SetFetchMode(ADODB_FETCH_ASSOC);

		#added by janken 11/04/2014 for the curl class
		$curl_obj = new Rest_Curl;

		$this->sql = "UPDATE seg_pay SET cancel_date=NULL,cancelled_by=NULL,\n".
			"history=CONCAT(history,".$db->qstr("Uncancelled: ".date('Y-m-d H:i:s')." [".$_SESSION['sess_temp_userid']."]\n").") \n".
			"WHERE or_no=".$db->qstr($orno);

		$saveOK = $db->Execute($this->sql);
		if ($saveOK === false) {
			$this->setErrorMsg('Unable to update cancelled payment...');
			return false;
		}

		if ($saveOK) {
			/* Get payment items */
			$this->sql = "SELECT ref_source, ref_no, service_code FROM seg_pay_request WHERE or_no=".$db->qstr($orno);
			$rs = $db->Execute($this->sql);
			if ($rs !== false) {
				$saveOK = true;
				$rows = $rs->GetRows();
				foreach ($rows as $row) {
					switch (strtoupper($row['ref_source'])) {
						case 'PH': $type=SegRequest::PHARMACY_REQUEST; break;
						case 'LD': $type=SegRequest::LABORATORY_REQUEST; break;
						case 'RD': $type=SegRequest::RADIOLOGY_REQUEST; break;
						case 'FB': $type=SegRequest::BILLING_REQUEST; break;
						case 'MISC': $type=SegRequest::MISC_REQUEST; break;
						case 'COM':  //added by mai 07/24/2014
								$saveOK = false;
								$this->sql= "UPDATE seg_company_ledger SET trans_type = 'PAYMNT' WHERE trans_type ='CRMEMO' AND trans_source = 'CSH' AND trans_refno = ".$db->qstr($orno);
								if($res=$db->Execute($this->sql)){
									if($db->Affected_Rows()){
										$saveOK = true;					
									}
								}//end added by mai
							break;
						case 'PF': /*added by mai 08-25-2014*/
						$saveOK = false;
						$this->sql= "UPDATE seg_pf_service_details SET request_flag = 'paid' WHERE dr_nr =".$db->qstr($row['service_code'])." AND refno=".$db->qstr($row['ref_no']);
						//var_dump($this->sql);
						if($res=$db->Execute($this->sql)){
							if($db->Affected_Rows()){
								$saveOK = true;					
							}
						}
					break; /*end added by mai*/
					}

					// for Cost Centers
					if ($type) {
						$request = new SegRequest( $type, array('refNo'=>$row['ref_no'], 'itemNo'=>$row['service_code']));
						$saveOK=$request->flag('paid');
						//updated by ken 4/14/2014 for transferring patient to waiting list after uncancel the or
						$check_nr = "SELECT encounter_nr FROM seg_pay WHERE or_no=".$db->qstr($orno);
						$result = $db->GetRow($check_nr);
						if (!$saveOK) {
							$this->setErrorMsg('Unable to flag PAID request...');
							break;
						}
						else{
							$this->update = "UPDATE care_encounter_location AS cel
												INNER JOIN care_encounter AS ce
													ON ce.`encounter_nr` = cel.`encounter_nr`
												SET cel.`location_nr` = '0',
													ce.`in_ward` = '0'
												WHERE cel.`type_nr` = '5' 
													AND cel.`encounter_nr` =".$db->qstr($result['encounter_nr']);
							$this->res = $db->Execute($this->update);
						}
					}
				}
			}
			else{
					$saveOK = false;
					$this->setErrorMsg('Cannot retrieve payment items...');
			}
		}

		if($saveOK){
		#added by janken 10/29/2014 for passing data in FIS
		     if(ENABLE_FIS){
		$curl_obj->cashTransactions($orno);
		     }
                }

		return $saveOK;
	}

	function GetRequestInfo($refno, $mode="PH",$orno=NULL) {
		global $db;
		$qrefno = $db->qstr($refno);

		$sql = "";
		switch (strtolower($mode)) {
			case "rd":
				$sql =
					"SELECT\n".
						"'RD' AS `source_dept`,r.refno AS `reference_no`,r.request_date AS `request_date`,".
						"r.pid AS `request_pid`,r.encounter_nr AS `request_encounter`,\n".
						"fn_get_person_name(r.pid) AS `request_name`,r.orderaddress AS `request_address`,\n".
						"r.is_urgent AS `request_priority`,\n".
						"(SELECT GROUP_CONCAT(rs.name SEPARATOR '\\n')\n".
							"FROM care_test_request_radio `rt`\n".
							"INNER JOIN seg_radio_services `rs` ON rt.service_code=rs.service_code\n".
							"WHERE r.refno = rt.refno AND rt.status!='deleted') `request_items`,\n".
						"(SELECT ca.amount FROM seg_charity_amount `ca`\n".
							"WHERE ca.ref_no=r.refno AND ca.ref_source='RD') `grant_amount`,\n".
						"(SELECT SUM(cg.discount) FROM seg_charity_grants `cg`\n".
							"WHERE cg.encounter_nr=r.encounter_nr) `grant_discount`\n".
					"FROM seg_radio_serv r\n".
					"WHERE r.refno=$qrefno";
			break;
			case "ld":
				$sql =
					"SELECT\n".
						"'LD' AS `source_dept`,refno AS `reference_no`,serv_dt AS `request_date`,\n".
						"IFNULL( pid , walkin_pid) AS `request_pid`,encounter_nr AS `request_encounter`,\n".
						"fn_get_person_name(l.pid) AS `request_name`,orderaddress AS `request_address`,\n".
						"is_urgent AS `request_priority`,\n".
						"(SELECT GROUP_CONCAT(sv.name SEPARATOR '\\n')\n".
							"FROM seg_lab_servdetails `ld`\n".
							"INNER JOIN seg_lab_services `sv` ON ld.service_code=sv.service_code\n".
							"WHERE l.refno = ld.refno AND ld.status!='deleted') `request_items`,\n".
						"(SELECT ca.amount FROM seg_charity_amount `ca`\n".
							"WHERE ca.ref_no=l.refno AND ca.ref_source='LD') `grant_amount`,\n".
						"(SELECT SUM(cg.discount) FROM seg_charity_grants `cg`\n".
							"WHERE cg.encounter_nr=l.encounter_nr) `grant_discount`\n".
					"FROM seg_lab_serv l\n".
					"WHERE l.refno=$qrefno";
			break;
			case "ph":
				$req_tb = $this->pharma_tb;
				$req_details_tb = $this->pharma_details_tb;
				$req_items_tb = $this->pharma_items_tb;
				$sql =
					"SELECT\n".
						"'PH' AS `source_dept`,refno AS `reference_no`,orderdate AS `request_date`,\n".
						"IFNULL( pid , walkin_pid) AS `request_pid`,encounter_nr AS `request_encounter`,\n".
						"IF( ISNULL(o.pid), fn_get_walkin_name(o.walkin_pid), fn_get_person_name(o.pid)) AS `request_name`,\n".
						"orderaddress AS `request_address`, is_urgent AS `request_priority`,\n".
						"(SELECT GROUP_CONCAT(pp.artikelname SEPARATOR '\\n')\n".
							"FROM seg_pharma_order_items `oi`\n".
							"LEFT JOIN care_pharma_products_main AS pp ON oi.bestellnum=pp.bestellnum\n".
							"WHERE o.refno = oi.refno) `request_items`,\n".
						"(SELECT ca.amount FROM seg_charity_amount `ca`\n".
							"WHERE ca.ref_no=o.refno AND ca.ref_source='PH') `grant_amount`,\n".
						"(SELECT SUM(cg.discount) FROM seg_charity_grants `cg`\n".
							"WHERE cg.encounter_nr=o.encounter_nr) AS `grant_discount`\n".
					"FROM seg_pharma_orders o\n".
					"WHERE o.refno=$qrefno";
 			break;

			#added by cha, july 12, 2010
			case "misc":
				$sql =
				"SELECT\n".
					"'MISC' AS `source_dept`, m.refno AS `reference_no`, chrge_dte AS `request_date`,\n".
					"ce.pid AS `request_pid`, m.encounter_nr AS `request_encounter`,\n".
					"fn_get_person_name(ce.pid) AS `request_name`, fn_get_complete_address(ce.pid) AS 'request_address',\n".
					"0 AS `request_priority`,\n".
					" (SELECT GROUP_CONCAT(CONCAT(IFNULL(md.request_flag,'NULL'),':',s.name)SEPARATOR '\\n')\n".
					" FROM seg_misc_service_details md INNER JOIN seg_other_services AS s \n".
					" WHERE s.alt_service_code=md.service_code) AS `request_items`,\n".
					"(SELECT ca.amount FROM seg_charity_amount `ca`\n".
							"WHERE ca.ref_no=m.refno AND ca.ref_source='MISC') `grant_amount`,\n".
					"(SELECT SUM(cg.discount) FROM seg_charity_grants `cg`\n".
						"WHERE cg.encounter_nr=m.encounter_nr) AS `grant_discount`\n".
				" FROM seg_misc_service m\n".
				" INNER JOIN care_encounter ce ON m.encounter_nr=ce.encounter_nr\n".
				"WHERE m.refno=$qrefno";
			break;
			#end cha---------------------
			/*added by mai 08-21-2014*/
			case "pf":
				$sql = "SELECT 
						  'PF' AS source_dept,
						  p.is_cash,
						  p.refno AS reference_no,
						  p.chrge_dte AS request_date,
						  ce.pid AS request_pid,
						  p.encounter_nr AS request_encounter,
						  fn_get_person_name (ce.pid) AS `request_name`,
						  fn_get_complete_address (ce.pid) AS request_address,
						  0 AS `request_priority`,
						  (SELECT 
						    GROUP_CONCAT(
						      CONCAT(
						        IFNULL(pf.request_flag, 'NULL'),
						        ':',
						        'DR. ',
						        fn_get_person_name (c.pid),
						        ' MD'
						      ) SEPARATOR ' '
						    ) 
						  FROM
						    seg_pf_service_details pf 
						    LEFT JOIN care_personell c 
						      ON pf.`dr_nr` = c.short_id 
						  WHERE p.refno = pf.refno 
						    AND pf.request_flag IS NULL) AS `request_items`,
						  NULL AS grant_amount,
						  NULL AS grant_discount 
						FROM
						  seg_pf_service p 
						  INNER JOIN care_encounter ce 
						    ON p.encounter_nr = ce.encounter_nr 
						  INNER JOIN care_person cp 
						    ON ce.pid = cp.pid 
						WHERE p.refno = ".$qrefno;
			break;
			/*end added by mai*/
		}

		$this->sql = $sql;
		if ($sql && ($this->result=$db->Execute($sql)) !== false) {
			return $this->result;
		}
		else {
			return false;
		}
	}


	function GetRequests($filters, $offset=0, $rowcount=15) {
		global $db;

		$phFilters = array();
		$ldFilters = array("l.status!='deleted'");
		$rdFilters = array("r.status!='deleted'");
		$interval = 1;
		foreach ($filters as $rowIndex=>$v) {
			//print_r($rowIndex.'='.$v);
			switch (strtoupper($rowIndex)) {
				case 'DEPT':
					$dept = strtolower($v);
				break;
				case 'REFNO':
					$phFilters[] = "refno=$v";
					$ldFilters[] = "refno=$v";
					$rdFilters[] = "refno=$v";
					$miscFilters[] = "m.refno=$v";
					$pfFilters[] = "p.refno=$v"; //mai
				break;
				case 'ISCASH':
					$phFilters[] = "is_cash=$v";
					$ldFilters[] = "is_cash=$v";
					$rdFilters[] = "is_cash=$v";
					$miscFilters[] = "m.is_cash=$v";
					$pfFilters[] = "p.is_cash=$v"; //mai
				break;
				case 'DATETODAY':
					$dateNow = date('Y-m-d');
					$phFilters[] = 'orderdate LIKE '.$db->qstr($dateNow.'%');
					$ldFilters[] = 'serv_dt LIKE '.$db->qstr($dateNow.'%');
					$rdFilters[] = 'request_date LIKE '.$db->qstr($dateNow.'%');
					$miscFilters[] = 'm.chrge_dte LIKE '.$db->qstr($dateNow.'%');
					$pfFilters[] = 'p.chrge_dte LIKE '.$db->qstr($dateNow.'%'); //mai
					$interval = false;
				break;
				case 'DATETHISWEEK':
					$query = '{date} BETWEEN {start} AND {end}';
					$timeWeekStart = mktime(1, 0, 0, date('m'), date('d')-date('w'), date('Y'));
					$start = $db->qstr(date('Y-m-d', $timeWeekStart) . ' 00:00:00');
					$end = $db->qstr(date('Y-m-d', strtotime('+1 weeks', $timeWeekStart)) . ' 00:00:00');
					$phFilters[] = strtr($query, array('date' => 'orderdate', 'start' => $start, 'end' => $end));
					$ldFilters[] = strtr($query, array('date' => 'serv_dt', 'start' => $start, 'end' => $end));
					$rdFilters[] = strtr($query, array('date' => 'request_date', 'start' => $start, 'end' => $end));
					$miscFilters[] = strtr($query, array('date' => 'm.chrge_dte', 'start' => $start, 'end' => $end));
					$pfFilters[] = strtr($query, array('date' => 'p.chrge_dte', 'start' => $start, 'end' => $end)); //mai
					$interval = false;
				break;
				break;
				case 'DATETHISMONTH':
					$phFilters[] = 'YEAR(orderdate)=YEAR(NOW()) AND MONTH(orderdate)=MONTH(NOW())';
					$ldFilters[] = 'YEAR(serv_dt)=YEAR(NOW()) AND MONTH(serv_dt)=MONTH(NOW())';
					$rdFilters[] = 'YEAR(request_date)=YEAR(NOW()) AND MONTH(request_date)=MONTH(NOW())';
					$miscFilters[] = 'YEAR(m.chrge_dte)=YEAR(NOW()) AND MONTH(m.chrge_dte)=MONTH(NOW())';
					$pfFilters[] = 'YEAR(p.chrge_dte)=YEAR(NOW()) AND MONTH(p.chrge_dte)=MONTH(NOW())';

					$interval = false;
				break;
				case 'DATE':
					$phFilters[] = "DATE(orderdate)=".$db->qstr($v);
					$ldFilters[] = "DATE(serv_dt)=".$db->qstr($v);
					$rdFilters[] = "DATE(request_date)=".$db->qstr($v);
					$miscFilters[] = "DATE(m.chrge_dte)=".$db->qstr($v);
					$pfFilters[] = "DATE(p.chrge_dte)=".$db->qstr($v);

					$interval = false;
				break;
				case 'DATEBETWEEN':
					$phFilters[] = "DATE(orderdate)>=".$db->qstr($v[0])." AND DATE(orderdate)<=".$db->qstr($v[1]);
					$ldFilters[] = "DATE(serv_dt)>=".$db->qstr($v[0])." AND DATE(serv_dt)<=".$db->qstr($v[1]);
					$rdFilters[] = "DATE(request_date)>=".$db->qstr($v[0])." AND DATE(request_date)<=".$db->qstr($v[1]);
					$miscFilters[] = "DATE(m.chrge_dte)>=".$db->qstr($v[0])." AND DATE(m.chrge_dte)<=".$db->qstr($v[1]);
					$pfFilters[] = "DATE(p.chrge_dte)>=".$db->qstr($v[0])." AND DATE(p.chrge_dte)<=".$db->qstr($v[1]);

					$interval = false;
				break;
				case 'WALKINPID':
					if ($v) {
						$phFilters[] = "o.walkin_pid=".$db->qstr($v);
//						$ldFilters[] = "o.walkin_pid=".$db->qstr($v);
//						$rdFilters[] = "o.walkin_pid=".$db->qstr($v);
					}
				break;
				case 'PID+NAME':
					$phFilters[] = "o.pid=".$db->qstr($v[0]);
					$ldFilters[] = "l.pid=".$db->qstr($v[0]);
					$rdFilters[] = "r.pid=".$db->qstr($v[0]);
					$miscFilters[] = "ce.pid=".$db->qstr($v[0]);
					$pfFilters[] = "ce.pid=".$db->qstr($v[0]);

					$interval = false;
				break;
				case 'OR':
					if ($v) {
					$limit_or = 'AND expayr.or_no<>'.$db->qstr($v);
					}
					$interval = false;
				break;
				case 'NAME':
					if ($v) {
						if (strpos($v,',') !== false) {
							$names = explode(',', $v);
							if ($filters['WALKIN']) {
								# for pharma only
								$phFilters[] = 'wp.name_last LIKE '.$db->qstr(trim($names[0]).'%');
								$phFilters[] = 'wp.name_first LIKE '.$db->qstr(trim($names[1]).'%');
							}
							else {
								$phFilters[] = 'p.name_last LIKE '.$db->qstr(trim($names[0]).'%').' OR wp.name_last LIKE '.$db->qstr(trim($names[0]).'%');
								$phFilters[] = 'p.name_first LIKE '.$db->qstr(trim($names[1]).'%').' OR wp.name_first LIKE '.$db->qstr(trim($names[1]).'%');

								$ldFilters[] = 'p.name_last LIKE '.$db->qstr(trim($names[0]).'%');
								$ldFilters[] = 'p.name_first LIKE '.$db->qstr(trim($names[1]).'%');

								$rdFilters[] = 'p.name_last LIKE '.$db->qstr(trim($names[0]).'%');
								$rdFilters[] = 'p.name_first LIKE '.$db->qstr(trim($names[1]).'%');

								$miscFilters[] = 'cp.name_last LIKE '.$db->qstr(trim($names[0]).'%');
								$miscFilters[] = 'cp.name_first LIKE '.$db->qstr(trim($names[1]).'%');

								$pfFilters[] = 'cp.name_last LIKE '.$db->qstr(trim($names[0]).'%');
								$pfFilters[] = 'cp.name_first LIKE '.$db->qstr(trim($names[1]).'%');

							}
						}
						else {
							$phFilters[] = "ordername LIKE ".$db->qstr($v."%");
							$ldFilters[] = "ordername LIKE ".$db->qstr($v."%");
							$rdFilters[] = "ordername LIKE ".$db->qstr($v."%");
//							$miscFilters[] = "CONCAT(cp.name_last,', ',cp.name_first) LIKE ".$db->qstr($v."%");
							$miscFilters[] = "cp.name_search LIKE ".$db->qstr($v."%");
							$pfFilters[] = "cp.name_search LIKE ".$db->qstr($v."%");
						}
						$interval = false;
					}
				break;
				case 'PID':
					$phFilters[] = "o.pid=".$db->qstr($v);
					$ldFilters[] = "l.pid=".$db->qstr($v);
					$rdFilters[] = "r.pid=".$db->qstr($v);
					$miscFilters[] = "ce.pid=".$db->qstr($v);
					$pfFilters[] = "ce.pid=".$db->qstr($v);

					$interval = false;
				break;
				case 'PATIENT':
					$phFilters[] = "o.pid=".$db->qstr($v);
					$ldFilters[] = "l.pid=".$db->qstr($v);
					$rdFilters[] = "r.pid=".$db->qstr($v);
					$miscFilters[] = "ce.pid=".$db->qstr($v);
					$pfFilters[] = "ce.pid=".$db->qstr($v);
					$interval = false;
				break;
				case 'INPATIENT':
					$phFilters[] = "o.encounter_nr=".$db->qstr($v);
					$ldFilters[] = "o.encounter_nr=".$db->qstr($v);
					$rdFilters[] = "o.encounter_nr=".$db->qstr($v);
					$miscFilters[] = "m.encounter_nr=".$db->qstr($v);
					$pfFilters[] = "p.encounter_nr=".$db->qstr($v);
					$interval = false;
				break;
				case 'WALKIN':
					// for Pharmacy only
					$phFilters[] = "o.walkin_pid IS NOT NULL";
					$dept = 'ph';
//					$ldFilters[] = "walkin_pid IS NOT NULL AND walkin_pid!=''";
//					$rdFilters[] = "walkin_pid IS NOT NULL AND walkin_pid!=''";
					$interval = false;
				break;
			}
		}

		if ($interval) {
			# if no date is specified, fetch only requests w/c at most 3 months old
			$phFilters[] = "orderdate > (NOW()-INTERVAL $interval MONTH)";
			$ldFilters[] = "serv_dt > (NOW()-INTERVAL $interval MONTH)";
			$rdFilters[] = "request_date > (NOW()-INTERVAL $interval MONTH)";
			$miscFilters[] = "m.chrge_dte > (NOW()-INTERVAL $interval MONTH)";
			$pfFilters[] = "p.chrge_dte > (NOW()-INTERVAL $interval MONTH)";
		}

		#added by cha 11-09-2009
//		if($filters['target']=='walkin')
//			$walkin_where = " and (walkin_pid!='') ";
//		else
//			$walkin_where = " ";
		#end cha

#		$havingClause = implode(") AND (",$filters);
#		if ($havingClause) $havingClause = "HAVING ($havingClause)";

		$sql = array();
		$calc = "";
		if (!$dept || $dept == "ph") {
			$phWhere=implode(")\n AND (",$phFilters);
			if ($phWhere) $phWhere = "($phWhere)";
			$sql[] =
			"(SELECT ".(!$calc ? $calc="SQL_CALC_FOUND_ROWS" : "") ."\n".
				"'PH' AS `source_dept`,is_cash,\n".
				"refno AS `reference_no`,orderdate AS `request_date`,\n".
				"IFNULL(o.pid,CONCAT('W',walkin_pid)) AS `request_pid`,\n".
				"encounter_nr AS `request_encounter`,ordername AS `request_name`,\n".
				"orderaddress AS 'request_address',is_urgent AS `request_priority`,\n".
				"(SELECT GROUP_CONCAT(CONCAT(IFNULL(oi.request_flag,'NULL'),':',pp.artikelname) SEPARATOR '\\n')\n".
					"FROM seg_pharma_order_items AS oi\n".
					"LEFT JOIN care_pharma_products_main AS pp ON oi.bestellnum=pp.bestellnum\n".
					"WHERE o.refno = oi.refno AND oi.request_flag IS NULL) AS request_items,\n".
				"(SELECT GROUP_CONCAT(pp.is_vat)\n".
					"FROM seg_pharma_order_items AS oi\n".
					"LEFT JOIN care_pharma_products_main AS pp ON oi.bestellnum=pp.bestellnum\n".
					"WHERE o.refno = oi.refno AND oi.request_flag IS NULL) AS is_vat \n".
			"FROM seg_pharma_orders o\n".
			"LEFT JOIN care_person p ON p.pid=o.pid\n".
			"LEFT JOIN seg_walkin wp ON wp.pid=o.walkin_pid\n".
			"WHERE {$phWhere} HAVING request_items IS NOT NULL)\n";
		}
#   NOT EXISTS ( SELECT * FROM seg_pay_request AS expt WHERE expt.ref_no=o.refno AND ref_source='PH')
		if (!$dept || $dept == "ld") {
			$ldWhere=implode(") AND (",$ldFilters);
			if ($ldWhere) $ldWhere = "($ldWhere)";
			$sql[] = "(SELECT ".(!$calc ? $calc="SQL_CALC_FOUND_ROWS" : "") ."\n".
				"'LD' AS `source_dept`,is_cash,\n".
				"refno AS `reference_no`,CONCAT(serv_dt,' ',serv_tm) AS `request_date`,\n".
				"l.pid AS `request_pid`,encounter_nr AS `request_encounter`,ordername AS `request_name`,\n".
				"orderaddress AS 'request_address',is_urgent AS `request_priority`,\n".
				"(SELECT GROUP_CONCAT(CONCAT(IFNULL(ld.request_flag,'NULL'),':',sv.name) SEPARATOR '\\n')\n".
					"FROM seg_lab_servdetails AS ld\n".
					"LEFT JOIN seg_lab_services AS sv ON ld.service_code=sv.service_code\n".
					"WHERE l.refno = ld.refno AND ld.status!='deleted' AND ld.request_flag IS NULL) AS request_items,\n".
				"0 AS is_vat\n".
			"FROM seg_lab_serv l\n".
			"LEFT JOIN care_person p ON p.pid=l.pid\n".
			"WHERE\n".
				"$ldWhere HAVING request_items IS NOT NULL)\n";
		}

		if (!$dept || $dept == 'rd') {
			$rdWhere=implode(") AND (",$rdFilters);
			if ($rdWhere) $rdWhere = "($rdWhere)";
			$sql[] = "(SELECT ".(!$calc ? $calc="SQL_CALC_FOUND_ROWS" : "") ."\n".
				"'RD' AS `source_dept`,is_cash,\n".
				"refno AS `reference_no`,CONCAT(request_date,' ',request_time) AS `request_date`,\n".
				"r.pid AS `request_pid`,encounter_nr AS `request_encounter`,ordername AS `request_name`,orderaddress AS 'request_address',is_urgent AS `request_priority`,\n".
				"(SELECT GROUP_CONCAT(CONCAT(IFNULL(rt.request_flag,'NULL'),':',rs.name) SEPARATOR '\\n')\n".
					"FROM care_test_request_radio AS rt\n".
					"LEFT JOIN seg_radio_services AS rs ON rt.service_code=rs.service_code\n".
					"WHERE r.refno = rt.refno AND rt.status!='deleted' AND rt.request_flag IS NULL) AS request_items,\n".
					"0 AS is_vat\n".
			"FROM seg_radio_serv r\n".
			"LEFT JOIN care_person p ON p.pid=r.pid\n".
			"WHERE\n".
				"$rdWhere HAVING request_items IS NOT NULL)\n";
		}

		//added by cha, july 11, 2010
		if(!$dept || $dept=="misc") {
			$miscWhere=implode(") AND (",$miscFilters);
			if($miscWhere)	$miscWhere = "($miscWhere)";
			$sql[] = "(SELECT ".(!$calc ? $calc="SQL_CALC_FOUND_ROWS" : "") ."\n".
				"'MISC' AS `source_dept`,m.is_cash, m.refno AS `reference_no`,\n".
				" m.chrge_dte AS `request_date`, ce.pid AS `request_pid`, m.encounter_nr AS `request_encounter`,\n".
				"	fn_get_person_name(ce.pid) AS `request_name`,	fn_get_complete_address(ce.pid) AS 'request_address',\n".
				"	0 AS `request_priority`,\n".
				" (SELECT GROUP_CONCAT(CONCAT(IFNULL(md.request_flag,'NULL'),':',s.name)SEPARATOR '\n')\n".
					" FROM seg_misc_service_details md\n".
					"INNER JOIN seg_other_services AS s ON s.alt_service_code=md.service_code\n".
					"WHERE m.refno=md.refno AND md.request_flag IS NULL) AS `request_items`,\n".
					"0 AS is_vat\n".
				" FROM seg_misc_service m\n".
				" INNER JOIN care_encounter ce ON m.encounter_nr=ce.encounter_nr\n".
				" INNER JOIN care_person cp ON ce.pid=cp.pid\n".
				"WHERE\n".
				"$miscWhere HAVING request_items IS NOT NULL)\n";
		}
		//end cha

		/*added by mai 08-21-2014*/
		if (!$dept || $dept == "pf") {
			$pfWhere=implode(") AND (",$pfFilters);
			if($pfWhere)	$pfWhere = "($pfWhere)";
			$sql [] = "(SELECT 
						  'PF' AS source_dept,
						  p.is_cash,
						  p.refno AS reference_no,
						  p.chrge_dte AS request_date,
						  ce.pid AS request_id,
						  p.encounter_nr AS request_encounter,
						  fn_get_person_name (ce.pid) AS `request_name`,
						  fn_get_complete_address (ce.pid) AS request_address,
						  0 AS `request_priority`,
						  (SELECT 
						    GROUP_CONCAT(
						      CONCAT(
						        IFNULL(pf.request_flag, 'NULL'),
						        ':',
						        'DR. ',
						        fn_get_person_name (c.pid),
						        ' MD'
						      ) SEPARATOR ' '
						    ) 
						  FROM
						    seg_pf_service_details pf 
						    LEFT JOIN care_personell c 
						      ON pf.`dr_nr` = c.short_id 
						  WHERE  p.refno = pf.refno 
						    AND pf.request_flag IS NULL) AS `request_items`,
						  0 AS is_vat 
						FROM
						  seg_pf_service p 
						  INNER JOIN care_encounter ce 
						    ON p.encounter_nr = ce.encounter_nr 
						  INNER JOIN care_person cp 
						    ON ce.pid = cp.pid 
						WHERE 
						$pfWhere HAVING request_items IS NOT NULL)";
		}
		/*end added by mai*/

		$this->sql = implode("\n UNION ALL\n", $sql);
		if ($this->sql) $this->sql .= "ORDER BY `request_date` DESC,`request_name` ASC,`request_priority` DESC, `reference_no` ASC\n";
		$this->sql .=	"LIMIT $offset, $rowcount";

		if ($_SESSION['sess_temp_userid'] == 'admin') {
			var_dump($filters);
			echo '<hr>';
			var_dump($this->sql);
			echo '<hr>';
		}

		if (($this->result=$db->Execute($this->sql))!==false) {
			return $this->result;
		} else { return false; }
	}

	#Added by Jarel 07/24/2013
	function GetRequestsFromSocial($filters, $offset=0, $rowcount=15){
		global $db;

	
		$interval = 1;
		foreach ($filters as $rowIndex=>$v) {
			switch (strtoupper($rowIndex)) {
				case 'DEPT':
					$dept = strtolower($v);
				break;
				case 'REFNO':
					$miscFilters[] = "m.refno=$v";
				break;
				case 'ISCASH':
					$miscFilters[] = "m.is_cash=$v";
				break;
				case 'PID+NAME':
					$miscFilters[] = "cp.pid=".$db->qstr($v[0]);
					$interval = false;
				break;
			}
		}

		if ($interval) {
			# if no date is specified, fetch only requests w/c at most 3 months old
			$miscFilters[] = "m.chrge_dte > (NOW()-INTERVAL $interval MONTH)";
		}


		$sql = array();
		$calc = "";

		if(!$dept || $dept=="misc") {
			$miscWhere=implode(") AND (",$miscFilters);
			if($miscWhere)	$miscWhere = "($miscWhere)";
			$sql = "(SELECT ".(!$calc ? $calc="SQL_CALC_FOUND_ROWS" : "") ."\n".
				"'MISC' AS `source_dept`,m.is_cash, m.refno AS `reference_no`,\n".
				" m.chrge_dte AS `request_date`, cp.pid AS `request_pid`, m.encounter_nr AS `request_encounter`,\n".
				"	fn_get_person_name(cp.pid) AS `request_name`,	fn_get_complete_address(cp.pid) AS 'request_address',\n".
				"	0 AS `request_priority`,\n".
				" (SELECT GROUP_CONCAT(CONCAT(IFNULL(md.request_flag,'NULL'),':',s.name)SEPARATOR '\n')\n".
					" FROM seg_misc_service_details md\n".
					"INNER JOIN seg_other_services AS s ON s.alt_service_code=md.service_code\n".
					"WHERE m.refno=md.refno) AS `request_items`\n".
				" FROM seg_misc_service m\n".
				" INNER JOIN care_person cp ON m.pid=cp.pid\n".
				"WHERE\n".
				"$miscWhere AND m.encounter_nr='' AND DATE(m.chrge_dte)=DATE(NOW()))\n";
		}
		
		$this->sql = $sql;
		if ($this->sql) $this->sql .= "ORDER BY `request_date` DESC,`request_name` ASC,`request_priority` DESC, `reference_no` ASC\n";
		$this->sql .=	"LIMIT $offset, $rowcount";

		if ($_SESSION['sess_temp_userid'] == 'admin') {
			var_dump($filters);
			echo '<hr>';
			var_dump($this->sql);
			echo '<hr>';
		}

		if (($this->result=$db->Execute($this->sql))!==false) {
			return $this->result;
		} else { return false; }	
	}

	function GetPayeeRequests($filters, $offset=0, $rowcount=15) {
		global $db;

		$phFilters = array();
		$ldFilters = array("l.status!='deleted'");
		$rdFilters = array("r.status!='deleted'");

		foreach ($filters as $rowIndex=>$v) {
			switch (strtolower($rowIndex)) {
				case 'pid+name':
					$filter = array();
					if ($v[0])
						$filter[] = "pid=".$db->qstr($v[0]);
#					if ($v[1])
#						$filter[] = "ordername REGEXP '[[:<:]]".substr($db->qstr($v[1]),1);
					$filter = implode(" OR ",$filter);
					if ($filter) {
						$phFilters[] = $filter;
						$ldFilters[] = $filter;
						$rdFilters[] = $filter;
					}
				break;
				case 'or':
					$limit_or = 'AND expayr.or_no<>'.$db->qstr($v);
				break;
				case 'name':
					$phFilters[] = "ordername LIKE ".$db->qstr($v);
					$ldFilters[] = "ordername LIKE ".$db->qstr($v);
					$rdFilters[] = "ordername LIKE ".$db->qstr($v);
				break;
				case 'pid':
					$phFilters[] = "pid LIKE ".$db->qstr($v);
					$ldFilters[] = "pid LIKE ".$db->qstr($v);
					$rdFilters[] = "pid LIKE ".$db->qstr($v);
				break;
				case 'patient':
					$phFilters[] = "pid=".$db->qstr($v[0]);
					$ldFilters[] = "pid=".$db->qstr($v[0]);
					$rdFilters[] = "pid=".$db->qstr($v[0]);
				break;
				case 'inpatient':
					$phFilters[] = "encounter_nr=".$db->qstr($v);
					$ldFilters[] = "encounter_nr=".$db->qstr($v);
					$rdFilters[] = "encounter_nr=".$db->qstr($v);
				break;
				case 'walkin':
					$phFilters[] = "NOT ISNULL(walkin_pid)";
					$ldFilters[] = "ISNULL(pid)";
					$rdFilters[] = "ISNULL(pid)";
				break;
			}
		}

#		$havingClause = implode(") AND (",$filters);
#		if ($havingClause) $havingClause = "HAVING ($havingClause)";
		$sql = array();
		$calc = "";
		if (!$dept || $dept == "ph") {
			$phWhere=implode(") AND (",$phFilters);
			if ($phWhere) $phWhere = "AND ($phWhere)";
			$sql[] = "
(SELECT ".(!$calc ? $calc="SQL_CALC_FOUND_ROWS" : "") ."
  'PH' AS `source_dept`,is_cash,
  refno AS `reference_no`,orderdate AS `request_date`,pid AS `request_pid`,encounter_nr AS `request_encounter`,ordername AS `request_name`,orderaddress AS 'request_address',is_urgent AS `request_priority`,
  (SELECT GROUP_CONCAT(pp.artikelname SEPARATOR '\n')
    FROM seg_pharma_order_items AS oi
    LEFT JOIN care_pharma_products_main AS pp ON oi.bestellnum=pp.bestellnum
    WHERE o.refno = oi.refno) AS request_items
FROM seg_pharma_orders AS o WHERE
  EXISTS (
    SELECT * FROM seg_pharma_order_items AS expt WHERE expt.refno=o.refno AND expt.bestellnum NOT IN
      (SELECT service_code FROM seg_pay_request AS expayr LEFT JOIN seg_pay AS expay ON expay.or_no=expayr.or_no WHERE expayr.ref_no=o.refno AND expayr.ref_source='PH' AND expay.cancel_date IS NULL $limit_or))
$phWhere)";
		}
#   NOT EXISTS ( SELECT * FROM seg_pay_request AS expt WHERE expt.ref_no=o.refno AND ref_source='PH')
		if (!$dept || $dept == "ld") {
			$ldWhere=implode(") AND (",$ldFilters);
			if ($ldWhere) $ldWhere = "AND ($ldWhere)";
			$sql[] = "
(SELECT ".(!$calc ? $calc="SQL_CALC_FOUND_ROWS" : "") ."
 'LD' AS `source_dept`,is_cash,
  refno AS `reference_no`,serv_dt AS `request_date`,pid AS `request_pid`,encounter_nr AS `request_encounter`,ordername AS `request_name`,orderaddress AS 'request_address',is_urgent AS `request_priority`,
  (SELECT GROUP_CONCAT(sv.name SEPARATOR '\n')
    FROM seg_lab_servdetails AS ld
    LEFT JOIN seg_lab_services AS sv ON ld.service_code=sv.service_code
    WHERE l.refno = ld.refno) AS request_items
FROM seg_lab_serv AS l WHERE
  EXISTS (
    SELECT * FROM seg_lab_servdetails AS exlt WHERE exlt.refno=l.refno AND exlt.service_code NOT IN
      (SELECT service_code FROM seg_pay_request AS expayr LEFT JOIN seg_pay AS expay ON expay.or_no=expayr.or_no WHERE expayr.ref_no=l.refno AND expayr.ref_source='LD' AND expay.cancel_date IS NULL $limit_or))
$ldWhere)";
		}

		if (!$dept || $dept == 'rd') {
			$rdWhere=implode(") AND (",$rdFilters);
			if ($rdWhere) $rdWhere = "AND ($rdWhere)";
			$sql[] = "
(SELECT ".(!$calc ? $calc="SQL_CALC_FOUND_ROWS" : "") ."
 'RD' AS `source_dept`,is_cash,
  refno AS `reference_no`,request_date AS `request_date`,pid AS `request_pid`,encounter_nr AS `request_encounter`,ordername AS `request_name`,orderaddress AS 'request_address',is_urgent AS `request_priority`,
  (SELECT GROUP_CONCAT(rs.name SEPARATOR '\n')
    FROM care_test_request_radio AS rt
    LEFT JOIN seg_radio_services AS rs ON rt.service_code=rs.service_code
    WHERE r.refno = rt.refno) AS request_items
FROM seg_radio_serv AS r WHERE
  EXISTS (
    SELECT * FROM care_test_request_radio AS exrt WHERE exrt.refno=r.refno AND exrt.service_code NOT IN
      (SELECT service_code FROM seg_pay_request AS expayr LEFT JOIN seg_pay AS expay ON expay.or_no=expayr.or_no WHERE expayr.ref_no=r.refno AND expayr.ref_source='RD' AND expay.cancel_date IS NULL $limit_or))
$rdWhere)";
		}

		$this->sql = implode(" UNION ALL ", $sql);
		if ($this->sql) $this->sql .= "\nGROUP BY `requuest_pid`,`request_name`,'request_date' ORDER BY `request_date` DESC,`request_name` ASC,`request_priority` DESC, `reference_no` ASC\n";
		$this->sql .=	"LIMIT $offset, $rowcount";
		if($this->result=$db->Execute($this->sql)) {
			return $this->result;
		} else { return false; }
	}

	function getRequestItems($refno,$dept=NULL) {
		global $db;
		if (!is_array($refno))	$refno = array($refno);
		if (!is_array($dept)) $dept = array($dept);
		$lab_where=array("0");
		$rad_where=array("0");
		$pha_where=array("0");
		foreach ($dept as $rowIndex=>$v) {
			switch (strtolower($v)) {
				case 'ld':
					$lab_where[] = "refno=".$db->qstr($refno[$rowIndex]);
				break;
				case 'rd':
					$rad_where[] = "refno=".$db->qstr($refno[$rowIndex]);
				break;
				case 'ph':
					$pha_where[] = "refno=".$db->qstr($refno[$rowIndex]);
				break;
				default:
			 		$pha_where[] = "refno=".$db->qstr($refno[$rowIndex]);
					$lab_where[] = "refno=".$db->qstr($refno[$rowIndex]);
					$rad_where[] = "refno=".$db->qstr($refno[$rowIndex]);
				break;
			}
		}
		$pha_where = implode(" OR ",$pha_where);
		$lab_where = implode(" OR ",$lab_where);
		$rad_where = implode(" OR ",$rad_where);
		$this->sql=
		"(SELECT o.refno AS `refno`,'PH' AS `source_dept`,o.bestellnum AS `item_no`,oi.artikelname AS `item_name`,\n".
			"(CASE oi.prod_class WHEN 'M' THEN 'Medicine' ELSE 'Supply' END) AS `item_group`,\n".
			"o.pricecash AS `price_cash`,o.pricecharge AS `price_charge`,o.quantity AS `quantity`,\n".
			"(o.pricecash*o.quantity) AS `total_cash`,(o.pricecharge*o.quantity) AS `total_charge`\n".
		"FROM seg_pharma_order_items AS o\n".
			"INNER JOIN care_pharma_products_main AS oi ON o.bestellnum=oi.bestellnum\n".
		"WHERE $pha_where)\n".
		"UNION ALL\n".
		"(SELECT l.refno AS `refno`,'LD' AS `source_dept`,l.service_code AS `item_no`,li.name AS `item_name`,lg.name AS `item_group`,\n".
			"l.price_cash AS `price_cash`,l.price_charge AS `price_charge`,l.quantity AS `quantity`,\n".
			"l.price_cash*l.quantity AS `total_cash`,l.price_charge*l.quantity AS `total_charge`\n".
		"FROM seg_lab_servdetails AS l\n".
			"INNER JOIN seg_lab_services AS li ON l.service_code=li.service_code\n".
			"INNER JOIN seg_lab_service_groups AS lg ON li.group_code=lg.group_code\n".
		"WHERE l.status!='deleted' AND ($lab_where))\n".
		"UNION ALL\n".
		"(SELECT r.refno AS `refno`,'RD' AS `source_dept`,r.service_code AS `item_no`,ri.name AS `item_name`,rg.name AS `item_group`,\n".
			"r.price_cash AS `price_cash`,r.price_charge AS `price_charge`,1 AS `quantity`,\n".
			"r.price_cash AS `total_cash`,r.price_charge AS `total_charge`\n".
		"FROM care_test_request_radio AS r\n".
			"INNER JOIN seg_radio_services AS ri ON r.service_code=ri.service_code\n".
			"INNER JOIN seg_radio_service_groups AS rg ON ri.group_code=rg.group_code\n".
		"WHERE r.status!='deleted' AND ($rad_where))\n".
		"ORDER BY `source_dept`,`refno`";
		if($this->result=$db->Execute($this->sql)) {
			return $this->result;
		} else { return false; }
	}


	function GetRequestOrNumber($refno, $dept, $item) {
		global $db;
		$this->sql = "SELECT r.or_no\n".
			"FROM seg_pay_request r\n".
			"INNER JOIN seg_pay p ON p.or_no=r.or_no\n".
			"WHERE ISNULL(p.cancel_date)\n".
				"AND r.ref_no=".$db->qstr($refno)."\n".
				"AND r.ref_source=".$db->qstr($dept).
				"AND r.service_code=".$db->qstr($item);
		return $db->GetOne(	$this->sql );
	}

	function getSeniorDiscount($orNo, $refno, $item, $dept){ //added by maimai 11-21-2014
		global $db;
		
		$this->sql = "SELECT is_senior FROM seg_pay_request WHERE or_no =".$db->qstr($orNo).
								" AND ref_no = ".$db->qstr($refno).
								" AND service_code = ".$db->qstr($item).
								" AND ref_source = ".$db->qstr($dept);
									
		return $db->GetOne($this->sql);
	}

	function GetRequestDetails($refno,$dept='PH',$orno=NULL) {
		global $db;
		$refno = $db->qstr($refno);
		if ($orno) {
			$orno = $db->qstr($orno);
			if ($orno) $limit_or = "AND pr.or_no<>$orno";
		}
		switch (strtolower($dept)) {
			case 'ld':
				$this->sql="(SELECT l.refno `refno`,'LD' `source_dept`,l.service_code `item_no`,\n".
					"li.name `item_name`,lg.name `item_group`,\n".
					//"EXISTS(SELECT * FROM seg_pay_request pr INNER JOIN seg_pay p ON p.or_no=pr.or_no WHERE pr.ref_no=l.refno AND pr.ref_source='LD' AND pr.service_code=l.service_code AND p.cancel_date IS NULL $limit_or) `is_paid`,\n".
					"l.price_cash `price_cash`,l.price_charge `price_charge`,l.quantity `quantity`,\n".
					"(l.price_cash*l.quantity) `total_cash`,(l.price_cash*l.quantity) `total_charge`,\n".
					"l.price_cash_orig `price_cash_orig`, l.price_cash_orig `price_charge_orig`,\n".
					"l.request_flag\n".
				"FROM seg_lab_servdetails l\n".
				"INNER JOIN seg_lab_services li ON l.service_code=li.service_code\n".
				"LEFT JOIN seg_lab_service_groups lg ON li.group_code=lg.group_code\n".
				"WHERE refno=$refno AND l.status!='deleted')\n";
			break;
			case 'rd':
				$this->sql="(SELECT r.refno `refno`,'RD' `source_dept`,r.service_code `item_no`,\n".
					"ri.name `item_name`,rg.name `item_group`,\n".
					//"EXISTS(SELECT * FROM seg_pay_request pr INNER JOIN seg_pay p ON p.or_no=pr.or_no WHERE pr.ref_no=r.refno AND pr.ref_source='RD' AND pr.service_code=r.service_code AND p.cancel_date IS NULL $limit_or) `is_paid`,\n".
					"r.price_cash `price_cash`,r.price_charge `price_charge`,1 `quantity`,\n".
					"r.price_cash `total_cash`,r.price_cash `total_charge`,\n".
					"r.price_cash_orig `price_cash_orig`, r.price_cash_orig `price_charge_orig`,\n".
					"r.request_flag\n".
				"FROM care_test_request_radio r\n".
				"LEFT JOIN seg_radio_services ri ON r.service_code=ri.service_code\n".
				"LEFT JOIN seg_radio_service_groups rg ON ri.group_code=rg.group_code\n".
				"WHERE refno=$refno AND r.status!='deleted')\n";
			break;
			case 'ph':
				$this->sql="(SELECT o.refno `refno`,'PH' `source_dept`,o.bestellnum `item_no`,oi.artikelname `item_name`,\n".
					"(CASE oi.prod_class WHEN 'M' THEN 'Medicine' ELSE 'Supply' END) `item_group`,\n".
					//"EXISTS(SELECT * FROM seg_pay_request pr INNER JOIN seg_pay p ON p.or_no=pr.or_no WHERE pr.ref_no=o.refno AND pr.ref_source='PH' AND pr.service_code=o.bestellnum AND p.cancel_date IS NULL $limit_or) `is_paid`,\n".
					"o.pricecash `price_cash`,o.pricecharge `price_charge`,o.quantity `quantity`,\n".
					"(o.pricecash*o.quantity) `total_cash`,(o.pricecharge*o.quantity) `total_charge`,\n".
					"o.price_orig `price_cash_orig`, o.price_orig `price_charge_orig`,\n".
					"o.request_flag\n".
				"FROM seg_pharma_order_items o\n".
				"LEFT JOIN care_pharma_products_main oi ON o.bestellnum=oi.bestellnum\n".
				"WHERE refno=$refno)\n";
			break;
			case 'misc':
				$this->sql="(SELECT md.refno `refno`, 'MISC' `source_dept`, md.service_code `item_no`, s.name `item_name`,\n".
				"t.name_short `item_group`, md.adjusted_amnt `price_cash`, md.chrg_amnt `price_charge`, md.quantity,\n".
				"(md.adjusted_amnt*md.quantity) `total_cash`, (md.chrg_amnt*md.quantity) `total_charge`,\n".
				"md.chrg_amnt `price_cash_orig`, md.chrg_amnt `price_charge_orig`, md.request_flag\n".
				"FROM seg_misc_service_details md\n".
				"LEFT JOIN seg_other_services s ON md.service_code=s.alt_service_code\n".
				"LEFT JOIN seg_cashier_account_subtypes AS t ON s.account_type=t.type_id\n".
				"LEFT JOIN seg_cashier_account_types AS p ON t.parent_type=p.type_id\n".
				"WHERE md.refno=$refno)\n";
			break;
			case 'pf':
				$this->sql = "(SELECT 
								  md.refno AS refno,
								  'PF' AS source_dept,
								  md.dr_nr AS `item_no`,
								  (SELECT 
								    CONCAT(
								      'DR.',
								      fn_get_person_name (ce.pid),
								      'MD'
								    ) 
								  FROM
								    care_personell cp 
								    LEFT JOIN care_person ce 
								      ON cp.pid = ce.pid 
								  WHERE cp.short_id = md.dr_nr) AS item_name,
								  'HospBills' AS item_group,
								  md.chrg_amount AS price_cash,
								  md.chrg_amount AS price_charge,
								  '1' AS quantity,
								  md.chrg_amount AS total_cash,
								  md.chrg_amount AS total_charge,
								  md.chrg_amount `price_cash_orig`,
								  md.chrg_amount `price_charge_orig`,
								  md.request_flag 
								FROM
								  seg_pf_service_details md 
								WHERE md.refno=".$refno.")";
			break;
		}

		if($this->result=$db->Execute($this->sql)) {
			return $this->result;
		} else { return false; }
	}

	function CreatePayment($no, $date, $amt, $remarks) {
		global $db;
		$no = $db->qstr($no);
		$date = $db->qstr($date);
		$amt = $db->qstr($amt);
		$remarks = $db->qstr($remarks);
		$history = $this->ConcatHistory("Create ".date('Y-m-d H:i:s')." ".$_SESSION['sess_user_name']."\n");
		$this->sql = "INSERT INTO $this->coretable(or_no,or_date,amount_tendered,remarks,history) VALUES($no,$date,$amt,$remarks,$history)";
		if ($db->Execute($this->sql)) {
				return TRUE;
			}else{ return FALSE; }
	}

	function AttachDepositDetails(&$data) {
		global $db;

		$or_no = $db->qstr($data['or_no']);
		if ($data['encounter_nr'])
			$encounter_nr = $db->qstr($data['encounter_nr']);
		else
			$encounter_nr='NULL';
		$deposit = $db->qstr($data['deposit']);
		$this->sql = "INSERT INTO $this->pay_deposit_tb(or_no,encounter_nr,deposit)\n".
			"VALUES($or_no,$encounter_nr,$deposit)";
		if ($db->Execute($this->sql)) {
				return TRUE;
			}else{ return FALSE; }
		return $save_ok;
	}

    /**
     *
     * @global ADOConnection $db
     * @param string $or_no
     * @return boolean
     */
	public function unsetCheque($or_no) {
		global $db;
		$this->sql="DELETE FROM $this->req_check_tb WHERE or_no=".$db->qstr($or_no);
		if ($db->Execute($this->sql)) {
				return TRUE;
		}
		else
			return FALSE;
	}

    /**
     *
     * @global ADOConnection $db
     * @param type $or_no
     * @param type $check_no
     * @param type $check_date
     * @param type $bank_name
     * @param type $payor
     * @param type $amount
     * @param type $checkcompany : Added by Jarel 09/25/2013
     * @return boolean
     */
	public function setCheque($or_no, $check_no, $check_date, $bank_name, $payor, $amount, $checkcompany)
    {
		global $db;

		$result = $db->Replace(
			$this->req_check_tb,
			array(
				'or_no'=>$db->qstr($or_no),
				'check_no'=>$db->qstr($check_no),
				'check_date'=>$db->qstr($check_date),
				'bank_name'=>$db->qstr($bank_name),
				'payee'=>$db->qstr($payor),
				'amount'=>$db->qstr($amount),
				'company_name'=>$db->qstr($checkcompany)
			),
			'or_no'
		);
		return ($result > 0) ? TRUE : FALSE;
	}

	function removeCardDetails($or_no) {
		global $db;
		$this->sql="DELETE FROM $this->req_card_tb WHERE or_no=".$db->qstr($or_no);
		if ($db->Execute($this->sql)) {
				return TRUE;
		}
		else
			return FALSE;
	}

	function AttachCardDetails($or_no, $card_no, $card_bank, $card_brand, $card_name, $card_expr, $card_code, $amount) {
		global $db;

//		$or_no = $db->qstr($or_no);
//		$card_no = $db->qstr($card_no);
//		$card_bank = $db->qstr($card_bank);
//		$card_brand = $db->qstr($card_brand);
//		$card_name = $db->qstr($card_name);
//		$card_expr = $db->qstr($card_expr);
//		$card_code = $db->qstr($card_code);
//		$amount = $db->qstr($amount);

//		$this->sql = "INSERT INTO $this->req_card_tb(or_no,card_no,issuing_bank,card_brand,cardholder_name,expiry_date,security_code,amount) ".
//			"VALUES($or_no,$card_no,$card_bank,$card_brand,$card_name,$card_expr,$card_code,$amount)";
//		if ($db->Execute($this->sql)) {
//			if ($db->Affected_Rows()) {
//				return TRUE;
//			}else{ return FALSE; }
//		}else{ return FALSE; }

		$result = $db->Replace(
			$this->req_card_tb,
			array(
				'or_no'=>$or_no,
				'card_no'=>$card_no,
				'issuing_bank'=>$card_bank,
				'card_brand'=>$card_brand,
				'cardholder_name'=>$card_name,
				'expiry_date'=>$card_expr,
				'security_code'=>$card_code,
				'amount'=>$amount
			),
			'or_no', $autoQoute=TRUE
		);
		return ($result > 0) ? TRUE : FALSE;
	}

	function AttachRequest($no, $refno, $refsource,$amt,$scode) {
		global $db;
		$no = $db->qstr($no);
		$refno = $db->qstr($refno);
		$refsource = $db->qstr($refsource);
		if (!$scode) {}
		$amt = $db->qstr($amt);
		$this->sql = "INSERT INTO $this->req_tb(or_no,ref_no,ref_source,amount_due) VALUES($no,$refno,$refsource,$amt)";
		if ($db->Execute($this->sql)) {
			if ($db->Affected_Rows()) {
				return TRUE;
			}else{ return FALSE; }
		}else{ return FALSE; }
	}

	/**
	 * [ClearPayRequests description]
	 * @param [type] $or_no [description]
	 * @deprecated This function is now deprecated in favor of SegCashier::deletePaymentDetails
	 */
	function ClearPayRequests($or_no) {
		global $db;
		$or_no = $db->qstr($or_no);
		$this->sql = "DELETE FROM $this->req_tb WHERE or_no=$or_no";
		if ($db->Execute($this->sql)) {
				return TRUE;
			}else{ return FALSE; }
	}

	/**
	 * [AttachMultipleRequests description]
	 * @param [type] $no         [description]
	 * @param [type] $refNos     [description]
	 * @param [type] $sources    [description]
	 * @param [type] $itemCodes  [description]
	 * @param [type] $amountsDue [description]
	 * @deprecated This function is now deprecated in favor of SegCashier::processRequestArray
	 */
	function AttachMultipleRequests($no, $refNos, $sources, $itemCodes, $amountsDue) {
		global $db;
		$no = $db->qstr($no);
		$bulk = array();
		foreach ($refNos as $rowIndex=>$v) {
			$bulk[] = array($refNos[$rowIndex], $sources[$rowIndex], $itemCodes[$rowIndex], $amountsDue[$rowIndex]);
		}
		$this->sql = "INSERT INTO $this->req_tb(or_no,ref_no,ref_source,service_code,amount_due) VALUES($no,?,?,?,?)";
		if ($db->Execute($this->sql,$bulk)) {
			if ($db->Affected_Rows()) {
				return TRUE;
			}else{ return FALSE; }
		}else{ return FALSE; }
	}

	/**
	 * Removes the items of a payment transaction from the databank while
	 * keeping the transaction header details
	 *
	 * @param  string $orNo The OR# of the payment
	 * @return boolean Returns TRUE if the deletion was successful
	 */
	public function deletePaymentDetails($orNo)
	{
		global $db;
		$this->sql = "DELETE FROM $this->req_tb WHERE or_no=" .
			$db->qstr($orNo);
		if ($db->Execute($this->sql) !== false) {
			return true;
		} else {
			return false;
		}
	}

    /**
     * Saves the items of a payment transaction to the database
     *
     * This method handles the flagging of requests for each item in the
     * payment transaction
     *
     * @global ADOConnection $db
     * @param string $no
     * @param array $refNos
     * @param array $sources
     * @param array $itemCodes
     * @param array $quantities
     * @param array $amountsDue
     * @return boolean
     * @throws Exception
     * @todo Refactor to avoid block processing of items
     * @todo Separate request flagging logic to appropriate classes
     */
	public function processRequestArray(
        $no,
        $refNos,
        $sources,
        $itemCodes,
        $quantities,
        $amountsDue,
        $partialAmount, /*added by mai 07/29/2014, for companies*/
        $seniorDisc
    ) {
		global $db;
//		$core = new Core;
		$bulk = array();

		#added by janken 10/13/2014 for the curl class
		$curl_obj = new Rest_Curl;

		foreach ($refNos as $rowIndex => $dummy) {
    		$refNo = $db->qstr($refNos[$rowIndex]);
    		$itemCode = $db->qstr($itemCodes[$rowIndex]);
			
			$is_partial = 0;
			$flag = "'paid'";
			if($partialAmount !=0){ //added by mai 07/29/2014
        		$amountsDue[$rowIndex] = $partialAmount;
        		$is_partial = 1;
        		$flag = "'partial'";
        	}

			$data = array(
				'or_no'	=> $db->qstr($no),
				'ref_no' => $refNo,
				'ref_source' => $db->qstr($sources[$rowIndex]),
				'qty' => $db->qstr($quantities[$rowIndex]),
				'amount_due' => $db->qstr($amountsDue[$rowIndex]),
				'is_senior'=> $db->qstr($seniorDisc[$rowIndex]), //added by maimai 11-21-2014
				'service_code' => $itemCode,
				'account_type' => sprintf("fn_get_account_type(%s,%s,%s,'N')",
                    $refNo,
                    $db->qstr($sources[$rowIndex]),
                    $itemCode
                )
			);

			$result = $db->Replace(
				'seg_pay_request',
				$data,
				array('or_no','ref_no','ref_source','service_code', 'is_senior'),
				$autoQuote=FALSE
			);


			if (!$result) {
				return false;
			} else {
				// update request Flags for Pharma, Lab, Radio and others
				$saveok = true;
                $source = strtoupper($sources[$rowIndex]);

                // Do request flagging for the following items only...
                if (in_array($source, array('FB', 'PH', 'LD', 'RD', 'MISC', 'COM', 'PF'))) { //edited by mai 07/23/2014, added COM 

                	$existsSql = "SELECT 1 FROM {table} " .
                		"WHERE request_flag=$flag AND {cond}";

                    switch ($source) {
                        // Hospital bill
                        case 'FB':
                        	$exists = $db->GetOne(strtr($existsSql, array(
                        		'{table}' => 'seg_billing_encounter',
                        		'{cond}' => 'bill_nr=' . $itemCode
                    		)));
                            $this->sql = "UPDATE seg_billing_encounter " .
                                "SET request_flag= $flag " .
                                "WHERE bill_nr=" .
                                    $itemCode;
                            break;
                        #added by art 05/17/2014
                        // IC
                        case 'IC':
                        	$exists = $db->GetOne(strtr($existsSql, array(
                        		'{table}' => 'seg_industrial_bill_h',
                        		'{cond}' => 'bill_nr=' . $itemCode
                    		)));
                            $this->sql = "UPDATE seg_industrial_bill_h " .
                                "SET request_flag='paid' " .
                                "WHERE bill_nr=" .
                                    $itemCode;
                            break;
                        #end art
                        // Pharmacy
                        case 'PH':
                        	$exists = $db->GetOne(strtr($existsSql, array(
                        		'{table}' => 'seg_pharma_order_items',
                        		'{cond}' => 'refno=' . $refNo .
                        			' AND bestellnum=' . $itemCode
                    		)));
                            $this->sql = "UPDATE seg_pharma_order_items " .
                                "SET request_flag='paid' " .
                                ", serve_status='N' " .
                                "WHERE refno=" . $refNo .
                                    " AND bestellnum=" .
                                    $itemCode;
                            break;
                        // Laboratory
                        case 'LD':
                        	$exists = $db->GetOne(strtr($existsSql, array(
                        		'{table}' => 'seg_lab_servdetails',
                        		'{cond}' => 'refno=' . $refNo .
                        			' AND service_code=' . $itemCode
                    		)));
                            $history = $this->ConcatHistory(
                                "Update request flag [paid] and Serve Status Serve at cashier " .
                                    date('Y-m-d H:i:s') . " " .
                                    $_SESSION['sess_user_name']."\n"
                            );
                            $this->sql = "UPDATE seg_lab_servdetails " .
                                "SET request_flag='paid', ".
                                "history=CONCAT(history,{$history}) " .
                                "WHERE refno=" . $refNo .
                                    " AND service_code=" .
                                    $itemCode;
                            break;
                        // Radiology
                        case 'RD':
                        	$exists = $db->GetOne(strtr($existsSql, array(
                        		'{table}' => 'care_test_request_radio',
                        		'{cond}' => 'refno=' . $refNo .
                        			' AND service_code=' . $itemCode
                    		)));
                            $this->sql = "UPDATE care_test_request_radio " .
                                "SET request_flag='paid' " .
                                "WHERE refno=" . $refNo .
                                    " AND service_code=" .
                                    $itemCode;
                            break;
                        // Miscellaneous services
                        case 'MISC':
                        	$exists = $db->GetOne(strtr($existsSql, array(
                        		'{table}' => 'seg_misc_service_details',
                        		'{cond}' => 'refno=' . $refNo .
                        			' AND service_code=' . $itemCode
                    		)));
                            $this->sql = "UPDATE seg_misc_service_details " .
                                "SET request_flag='paid' " .
                                "WHERE refno=".$refNo .
                                    " AND service_code=" .
                                    $itemCode;
                            break;
                        //Company
                        case 'COM': //added by mai 07/24/2014
                        	$exists = false;
                        
        					$explode_var = explode("'",$data['amount_due']);
        					$amount_due = $db->qstr(0 - $explode_var[1]);
    

                        	if(!$this->hasPaidCompBill($data['service_code'], $data['or_no'])){ //added by mai 07/24/2014
                        		$this->sql = "INSERT INTO seg_company_ledger (
											  post_id,
											  post_dt,
											  comp_id,
											  trans_type,
											  trans_source,
											  trans_refno,
											  trans_refdt,
											  trans_amount,
											  comp_bill_nr
											) 
											SELECT 
											  UUID(),
											  NOW(),
											  (SELECT 
												  comp_id 
												FROM
												  seg_company_billing_h 
												WHERE comp_bill_nr = ".$data['service_code'] 
												  ." AND is_deleted != 1),
											  'PAYMNT',
											  'CSH', ".
											  $data['or_no'].",
											  NOW(), ".
											  $amount_due.",". 
											  $data['service_code'];	
                        	}else{
                        		$this->sql= "UPDATE 
											  seg_company_ledger 
											SET
											  trans_amount = ".$amount_due
											.", trans_refno = ".$data['or_no']." WHERE trans_type = 'PAYMNT' and trans_source='CSH' AND comp_bill_nr = ".$data['service_code'];
                        	}
                        
                        	break;
                        	/*added by mai 08-21-2014*/
                        	case 'PF':
	                        	$exists = $db->GetOne(strtr($existsSql, array(
	                        		'{table}' => 'seg_pf_service_details',
	                        		'{cond}' => 'refno=' . $refNo .
	                        			' AND dr_nr=' . $itemCode
	                    		)));
	                            $this->sql = "UPDATE seg_pf_service_details " .
	                                "SET request_flag='paid' " .
	                                "WHERE refno=".$refNo .
	                                    " AND dr_nr=" .
	                                    $itemCode;
                            break;
                            /*end added by mai*/
                        // How did you get here???
                        default:
                            throw new Exception(
                                'Unable to flag request for source: ' .
                                    $source
                            );
                            break;
                    }

                    // Do flagging
                    if (!$exists) {
                    	$saveok = $db->Execute($this->sql);
	                    /**
	                     * Check if we updated an actual row
	                     */
	                    if (($saveok && $db->Affected_Rows()) || ($saveok && $source == 'COM')) { //updated by mai 07/24/2014 added OR COM
	                        /**
	                         * Write history entry to header table row
	                         */
	                        $historyLog = $db->qstr(sprintf(
	                            "Request #%s [PAID]: %s %s\n",
	                            $itemCodes[$rowIndex],
	                            date('Y-m-d H:i:s'),
	                            @$_SESSION['sess_temp_userid']
	                        ));

	                        switch ($source) {
	                            case 'PH':
	                                $query = "UPDATE seg_pharma_orders " .
	                                    "SET history=CONCAT(history,{$historyLog}) " .
	                                    "WHERE refno=".$refNo;
	                                break;
	                            case 'LD':
	                                $query = "UPDATE seg_lab_serv " .
	                                    "SET history=CONCAT(history,{$historyLog}) " .
	                                    "WHERE refno=".$refNo;
	                                break;
	                            case 'RD':
	                                $query = "UPDATE seg_radio_serv " .
	                                    "SET history=CONCAT(history,{$historyLog}) " .
	                                    "WHERE refno=".$refNo;
	                                break;
	                            case 'MISC':
	                                $query = "UPDATE seg_misc_service " .
	                                    "SET history=CONCAT(history,{$historyLog}) " .
	                                    "WHERE refno=".$refNo;
	                                break;
	                            case 'PF':
	                                $query = "UPDATE seg_misc_service " .
	                                    "SET history={$historyLog} " .
	                                    "WHERE refno=".$refNo;
	                                break;
	                            default:
	                                // do nothing for other items
	                                $query = false;
	                                break;

	                        }

	                        if ($query) {
	                            //do header update
	                            $db->Execute($query);
	                        }
	                    } else {
	                        // If no rows were  updated, we fail the entire process
	                        return false;
	                    }
                    } else {
                    	// Do no flagging if the item was flagged already
                    }

                }   // if in_array...

            }

		} // for-each loop...
                #added by janken 10/17/2014 for passing data in FIS
		if(ENABLE_FIS){
		$curl_obj->cashTransactions($no);
		}
        // everything went A-OK!
		return true;
	}

	function hasPaidCompBill($comp_bill_nr, $or_no){
		global $db;

		$this->sql = "SELECT 
					  post_id 
					FROM
					  seg_company_ledger 
					WHERE trans_type = 'PAYMNT' AND trans_source = 'CSH' AND comp_bill_nr = ".$comp_bill_nr." AND trans_refno = ".$or_no;
		if($this->result=$db->Execute($this->sql)){
			if($row=$this->result->Fetchrow()){
				return true;
			}
		}

		return false;
	}//end added by mai

	function GetPayeeInformationFromRequest($src, $refno) {
		global $db;
		$qrefno = $db->qstr($refno);
		switch(strtoupper($src)) {
			case "PH":
				$this->sql = "SELECT pid,encounter_nr,ordername AS name,orderaddress AS address FROM seg_pharma_orders WHERE refno=$qrefno";
			break;
			case "LD":
				$this->sql = "SELECT pid,encounter_nr,ordername AS name,orderaddress AS address FROM seg_lab_serv WHERE refno=$qrefno";
			break;
			case "RD":
				$this->sql = "SELECT pid,encounter_nr,ordername AS name,orderaddress AS address FROM seg_radio_serv WHERE refno=$qrefno";
			break;
		}
		if ($result=$db->Execute($this->sql)) {
			$row = $result->FetchRow();
			return $row;
		}else{ return FALSE; }
	}

	function GetRequestsByPayeeInfo($pid, $encounter_nr, $name) {
		$filters = array();
		$filters["ISCASH"] = 1;
		if ($pid)	$filters["PATIENT"] = $pid;
		elseif ($encounter_nr) $filters["INPATIENT"] = $encounter_nr;
		elseif ($name)	$filters["WALKIN"] = $name;
		return $this->GetRequests($filters);
	}

	function GetPatientBillingEncounter($pid, $orno, $offset=0, $rowcount=15) {
		global $db;
		$pid = $db->qstr($pid);
		if ($orno) {
			$orno = $db->qstr($orno);
			if ($orno) $limit_or = "AND pr.or_no<>$orno";
		}
        //edited by jasper 05/07/2013 - AND b.is_deleted IS NULL
		$this->sql = "SELECT e.pid,e.encounter_nr,b.bill_nr,b.bill_dte,b.bill_frmdte,\n".
			"fn_get_person_name(e.pid) AS fullname,\n".
			"fn_billing_compute_net_amount(b.bill_nr) - total_prevpayments AS total,\n".
			"b.request_flag\n".
			"FROM seg_billing_encounter AS b\n".
			"INNER JOIN care_encounter AS e on e.encounter_nr=b.encounter_nr\n".
			"WHERE e.pid=$pid AND b.is_deleted IS NULL\n".
			"ORDER BY b.bill_dte DESC\n";
		if($this->result=$db->Execute($this->sql)) {
			return $this->result;
		} else { return false; }
	}

	function GetBillingDetails($nr) {
		global $db;
		$nr = $db->qstr($nr);
		$this->sql = "SELECT p.pid,e.encounter_nr,\n".
				"fn_get_person_name(p.pid) AS fullname,\n".
				"CONCAT(p.street_name,', ',sb.brgy_name,', ',sm.mun_name,', ',sp.prov_name,', ',sm.zipcode) AS address,\n".
				"e.encounter_nr,b.bill_nr,b.bill_dte,b.bill_frmdte\n,".
				"b.total_acc_charge AS acc,".
				"b.total_med_charge AS med,".
				"b.total_sup_charge AS sup,".
				"b.total_srv_charge AS srv,".
				"b.total_ops_charge AS ops,".
				"b.total_doc_charge AS doc,".
				"b.total_msc_charge AS msc,".
				"b.total_prevpayments AS prev\n".
			"FROM seg_billing_encounter AS b\n".
				"INNER JOIN care_encounter AS e ON e.encounter_nr=b.encounter_nr\n".
				"LEFT JOIN care_person AS p ON p.pid=e.pid\n".
				"LEFT JOIN seg_barangays AS sb ON sb.brgy_nr=p.brgy_nr\n".
				"LEFT JOIN seg_municity AS sm ON sm.mun_nr=sb.mun_nr\n".
				"LEFT JOIN seg_provinces AS sp ON sp.prov_nr=sm.prov_nr\n".
			"WHERE b.bill_nr=$nr\n";
		if($this->result=$db->Execute($this->sql)) {
			$row = $this->result->FetchRow();
			return $row;
		} else { return false; }
	}

	function GetBillingCoverage($nr) {
		global $db;
		$nr = $db->qstr($nr);
        //edited by jasper 05/13/2013
	/*	$this->sql = "SELECT ".
				"SUM(b.total_acc_coverage) AS acc,".
				"SUM(b.total_med_coverage) AS med,".
				"SUM(b.total_sup_coverage) AS sup,".
				"SUM(b.total_srv_coverage) AS srv,".
				"SUM(b.total_ops_coverage) AS ops,".
				"SUM(b.total_doc_coverage) AS doc,".
				"SUM(b.total_msc_coverage) AS msc\n".
				"FROM seg_billing_coverage AS b\n".
			"WHERE b.bill_nr=$nr\n";  */
  $this->sql = "SELECT ".
               "b.total_acc_coverage AS acc,".
               "b.total_med_coverage AS med,".
               "b.total_sup_coverage AS sup,".
               "b.total_srv_coverage AS srv,".
               "b.total_ops_coverage AS ops,".
               "b.total_d1_coverage + b.total_d2_coverage + b.total_d3_coverage + b.total_d4_coverage AS doc,".
               "b.total_msc_coverage AS msc ".
               "FROM seg_billing_coverage AS b WHERE b.bill_nr = ".$nr;

		if($this->result=$db->Execute($this->sql)) {
			$row = $this->result->FetchRow();
			return $row;
		} else { return false; }
	}

    //added by jasper 05/15/2013
    function GetBillingComputedDiscount($nr) {
        global $db;
        $nr = $db->qstr($nr);
        $this->sql = "SELECT ".
                     "sbd.total_acc_discount as acc, ".
                     "sbd.total_med_discount as med, ".
                     "sbd.total_sup_discount as sup, ".
                     "sbd.total_srv_discount as srv, ".
                     "sbd.total_ops_discount as ops, ".
                     "sbd.total_d1_discount + sbd.total_d2_discount + sbd.total_d3_discount + sbd.total_d4_discount as doc, ".
                     "sbd.total_msc_discount as msc ".
                     "FROM seg_billingcomputed_discount AS sbd WHERE sbd.bill_nr = ".$nr;

        if($this->result=$db->Execute($this->sql)) {
            $row = $this->result->FetchRow();
            return $row;
        } else { return false; }
    }
    //added by jasper 05/15/2013

	function GetBillingDiscount($nr) {
		global $db;
		$nr = $db->qstr($nr);

        $this->sql = "SELECT SUM(discount_amnt) FROM seg_billing_discount AS b WHERE b.bill_nr=$nr";
        $this->result = $db->GetOne($this->sql);
        $discount_amnt = $this->result;

        if ((float)$discount_amnt > 1) {
            return $discount_amnt;
        } else {
            $this->sql = "SELECT SUM(discount) FROM seg_billing_discount AS b WHERE b.bill_nr=$nr";
            $this->result = $db->GetOne($this->sql);

            $discount = $this->result;
            if ((float)$discount < 0) $discount = 0.0;
            if ((float)$discount > 1.0) $discount = 1.0;
            return $discount;
        }
		/*$this->sql = "SELECT SUM(discount) FROM seg_billing_discount AS b WHERE b.bill_nr=$nr";
		$this->result = $db->GetOne($this->sql);

		$discount = $this->result;
		if ((float)$discount < 0) $discount = 0.0;
		if ((float)$discount > 1.0) $discount = 1.0;
		return $discount;*/
	}

	function GetPayInfo($orno, $show_details=FALSE) {
		global $db;
		$orno = $db->qstr($orno);
		if ($show_details) {
			$this->sql =
		"SELECT pay.or_no, pay.or_date, pay.or_name, pay.or_address, pay.pid, pay.encounter_nr, pay.amount_due,pay.amount_tendered,pay.remarks,pay.discount_tendered,pay.vat_amount,chk.company_name,\n".
			"chk.or_no AS `check_or_no`,chk.check_no,chk.check_date,chk.bank_name AS `check_bank_name`,chk.payee AS `check_name`,chk.amount AS `check_amount`,".
			"crd.or_no AS `card_or_no`,crd.card_no,crd.issuing_bank AS `card_bank_name`,crd.card_brand,crd.cardholder_name AS `card_name`,crd.expiry_date AS `card_expiry_date`,crd.security_code AS `card_security_code`,crd.amount AS `card_amount`\n".
			"FROM seg_pay AS pay\n".
			"LEFT JOIN seg_pay_checks AS chk ON chk.or_no=pay.or_no\n".
			"LEFT JOIN seg_pay_credit_cards AS crd ON crd.or_no=pay.or_no\n".
			"WHERE pay.or_no = $orno";
		}
		else {
			$this->sql =
		"SELECT pay.or_no, pay.or_date, pay.or_name, pay.or_address, pay.pid, pay.encounter_nr, pay.amount_due\n".
			"FROM seg_pay AS pay\n".
			"WHERE pay.or_no = $orno";
		}
		if($this->result=$db->Execute($this->sql)) {
			$row = $this->result->FetchRow();
			return $row;
		} else { return false; }
	}

	function GetPayReferences($orno) {
		global $db;
		$orno = $db->qstr($orno);
		$this->sql =
		"SELECT DISTINCT ref_no,ref_source FROM seg_pay_request WHERE or_no = $orno";
		if($this->result=$db->Execute($this->sql)) {
			return $this->result;
		} else { return false; }
	}

	function GetPayRequests($orno) {
		global $db;
		$orno = $db->qstr($orno);
		$this->sql =
		"SELECT ref_no,ref_source,service_code,qty,amount_due FROM seg_pay_request WHERE or_no = $orno";
		if($this->result=$db->Execute($this->sql)) {
			return $this->result;
		} else { return false; }
	}

	function GetPayDetails($orno, $offset=NULL, $rows=NULL, $order_by='service') {
		global $db;
		$orno = $db->qstr($orno);
        //edited by jasper 08/29/2103 -Fix for OB Annex co-payments BUG#:279
        //edited by art 05/25/2014 added IC
        //edited by marc 05/26/2014
        //edited case statement to support OR statement for MHC
		$this->sql = "SELECT SQL_CALC_FOUND_ROWS r.ref_no,r.service_code,r.ref_source, r.qty, r.amount_due, IFNULL(r.service_code,'') `service_code`, fn_get_account_type(r.ref_no, r.ref_source, r.service_code, 'S') AS account_code,\n".
            "(CASE \n" .
                "WHEN r.ref_source = 'PH' THEN\n" .
							"IFNULL((SELECT CONCAT(CAST(artikelname AS BINARY),CASE prod_class WHEN 'M' THEN '' WHEN 'S' THEN '' END)FROM care_pharma_products_main AS p WHERE p.bestellnum=r.service_code),'[Deleted Item]')\n".
                "WHEN r.ref_source = 'RD' THEN\n" .
							"IFNULL((SELECT name FROM seg_radio_services AS rs WHERE rs.service_code=r.service_code),'[Deleted Item]')\n".
                "WHEN r.ref_source = 'LD' THEN\n" .
							"IFNULL((SELECT name FROM seg_lab_services AS l WHERE l.service_code=r.service_code),'[Deleted Item]')\n".
                "WHEN r.ref_source = 'OTHER' THEN\n" .
							"IFNULL((SELECT name FROM seg_other_services AS o WHERE SUBSTRING(r.service_code,1,LENGTH(r.service_code)-1)=o.service_code),'[Deleted Item]')\n".
                "WHEN r.ref_source IN ('PP', 'MHC') THEN\n" .
							"IF(r.service_code='PARTIAL','Partial Payment',\n".
                                "IF(r.service_code='OBANNEX','Co-Payment: OB Annex',\n".
								"IF(r.service_code='DEPOSIT','Deposit: Hospital Fees',\n".
									"IFNULL(\n".
										"CONCAT('Deposit:',\n".
											"(SELECT description FROM care_ops301_en WHERE code=SUBSTRING(r.service_code,2))\n".
											"), 'Deposit:Unknown Item'\n".
												 "))))\n".
            "WHEN r.ref_source = 'IC' THEN\n" .
			"'Billing Account'".											   
                "WHEN r.ref_source = 'FB' THEN\n" .
			"'Billing Account'".
				 "WHEN r.ref_source = 'PF' THEN\n" .
			"IFNULL(
		          (SELECT 
		            CONCAT(fn_get_person_name (ce.pid), 'MD') 
		          FROM
		            care_personell cp 
		            LEFT JOIN care_person ce 
		              ON cp.pid = ce.pid 
		          WHERE cp.short_id = r.service_code),
		          '[Deleted Item]'
		        )\n".
                "WHEN r.ref_source = 'MISC' THEN\n" .
			"IFNULL((SELECT name FROM seg_other_services AS o WHERE r.service_code=o.alt_service_code),'[Deleted Item]')\n".
						"ELSE 'No source found...'\n".
					"END) AS service\n".
					"FROM seg_pay_request AS r\n".
					"WHERE or_no = $orno\n";
		$this->sql .=	"ORDER BY $order_by\n";
		if ($rows) $this->sql .= "LIMIT $offset, $rows";
		if($this->result=$db->Execute($this->sql)) {
			return $this->result;
		} else { return false; }
	}
	//Edited by borj 2014-21-1
	//edited by art 05/25/2014 added IC
	function GetPFOItemInfo($src, $ref, $code) {
		global $db;
		switch(strtolower($src)) {
			case 'pp':
				$rowIndex = strtolower(substr($code,0,1));
				$code = substr($code,1);
				if ($rowIndex=='o')
					$this->sql="SELECT description AS `name`,'Procedure' AS `desc` FROM care_ops301_en WHERE code=".$db->qstr($code);
				elseif ($rowIndex=='r')
					$this->sql="SELECT r.name AS `name`, g.name AS `desc` FROM seg_radio_services AS r LEFT JOIN seg_radio_service_groups AS g ON r.group_code=g.group_code WHERE r.service_code=".$db->qstr($code);
				elseif ($rowIndex=='l')
					$this->sql="SELECT r.name AS `name`, g.name AS `desc` FROM seg_lab_services AS r LEFT JOIN seg_lab_service_groups AS g ON r.group_code=g.group_code WHERE r.service_code=".$db->qstr($code);
				elseif ($rowIndex.strtolower($code)=='partial')
					return array('name'=>'Partial Payment','desc'=>'Deposit');
				elseif ($rowIndex.strtolower($code)=='deposit')
					return array('name'=>'Deposit: Hospital Fees','desc'=>'Deposit');
			break;
			case 'other':
				$code = $db->qstr(substr($code,0,-1));
				$this->sql = "SELECT s.name AS `name`,t.name_long AS `desc` FROM seg_other_services AS s LEFT JOIN seg_cashier_account_subtypes AS t ON t.type_id=s.account_type WHERE s.service_code=$code";
			break;
			case 'fb':
				$nr = $db->qstr($code);
				$this->sql = "SELECT CONCAT(p.name_last,', ',p.name_first,IFNULL(CONCAT(' ',SUBSTRING(p.name_middle,1,1)),''),'(',LOWER(DATE_FORMAT(be.bill_dte,'%Y-%m-%d %h:%i%p')),')') AS `name`,
'Billing' AS `desc`
FROM seg_billing_encounter AS be
INNER JOIN care_encounter AS e ON be.encounter_nr=e.encounter_nr
LEFT JOIN care_person AS p ON p.pid=e.pid
WHERE be.bill_nr=$nr";
			break;
			case 'com': //added by mai 07/24/2014
				$nr = $db->qstr($code);
				$this->sql="SELECT 
		  					 c.comp_name AS name,
		  					 'Billing' AS `desc`
							FROM 
							  	 seg_company_billing_h h 
							  LEFT JOIN seg_company AS c 
							    ON c.comp_id = h.comp_id 
							WHERE h.is_deleted = 0 
							   AND h.comp_bill_nr = ".$nr;
			break;

		}

		if($this->result=$db->Execute($this->sql)) {
			return $this->result->FetchRow();
		} else { return false; }
	}

	function GetPayments($filters, $offset=0, $rowcount=15) {
		global $db;
		$wFilters = array();
		$mode = 'AND';
		foreach ($filters as $rowIndex=>$v) {
			switch (strtolower($rowIndex)) {
				case 'mode':
					$mode = $v;
				break;
				case 'nocancel':
					$wFilters[] = "p.cancel_date IS NULL";
				break;
				case 'src':
					$v_arr = $v;
					if (!is_array($v)) $v_arr = array($v);
					$wFilters[] = "EXISTS(SELECT 1 FROM seg_pay_request AS prx WHERE prx.or_no=p.or_no AND prx.ref_source IN ('".implode("','",$v_arr)."'))";
				break;
				case 'or+name':
					$wFilters[] = "p.or_no=".$db->qstr($v). " OR or_name REGEXP '[[:<:]]".substr($db->qstr($v),1);
				break;
				case 'orno':
					$wFilters[] = "CAST(p.or_no AS UNSIGNED)=".$db->qstr($v);
				break;
				case 'daysago':
					$wFilters[] = "DATEDIFF(NOW(),p.or_date)<=".$db->qstr($v);
				break;
				case 'datetoday':
					$wFilters[] = 'DATE(p.or_date)=DATE(NOW())';
				break;
				case 'datethisweek':
					$wFilters[] = 'YEAR(p.or_date)=YEAR(NOW()) AND WEEK(p.or_date)=WEEK(NOW())';
				break;
				case 'datethismonth':
					$wFilters[] = 'YEAR(p.or_date)=YEAR(NOW()) AND MONTH(p.or_date)=MONTH(NOW())';
				break;
				case 'date':
					$wFilters[] = "DATE(p.or_date)=".$db->qstr($v);
				break;
				case 'datebetween':
					$wFilters[] = "DATE(p.or_date)>='".$v[0]."' AND DATE(p.or_date)<='".$v[1]."'";
				break;
				case 'name':
					$wFilters[] = "or_name LIKE ".$db->qstr($v.'%');
				break;
				case 'pid':
					$wFilters[] = "pid LIKE ".$db->qstr($v.'%');
				break;
				case 'patient':
					$wFilters[] = "pid=".$db->qstr($v);
				break;
				case 'inpatient':
					$wFilters[] = "encounter_nr=".$db->qstr($v);
				break;
				case 'walkin':
					$wFilters[] = "or_name=".$db->qstr($v)." AND (ISNULL(pid) OR LENGTH(pid)=0) AND (ISNULL(encounter_nr) OR LENGTH(encounter_nr)=0)";
				break;
				case 'encoder':
					$wFilters[] = "p.create_id=".$db->qstr($v);
				break;
			}
		}
        //edited by marc 05/26/2014
        //edited case statement to support OR statement for MHC
		$this->sql = "SELECT SQL_CALC_FOUND_ROWS p.or_no,p.or_date,p.or_name,p.amount_due,p.or_address,p.pid,p.encounter_nr,p.cancel_date,p.cancelled_by,create_id,
typ.name_long AS `type_main`,sub.name_long AS `type_sub`,
(SELECT GROUP_CONCAT(r.ref_source SEPARATOR '+') FROM seg_pay_request AS r WHERE r.or_no=p.or_no) AS `sources`,
(SELECT GROUP_CONCAT(
	CASE
		WHEN r.ref_source = 'PH' THEN
			IFNULL((SELECT artikelname FROM care_pharma_products_main AS p WHERE p.bestellnum=r.service_code),'[Deleted Item]')
		WHEN r.ref_source = 'RD' THEN
			IFNULL((SELECT name FROM seg_radio_services AS rs WHERE rs.service_code=r.service_code),'[Deleted Item]')
		WHEN r.ref_source = 'LD' THEN
			IFNULL((SELECT name FROM seg_lab_services AS l WHERE l.service_code=r.service_code),'[Deleted Item]')
		WHEN r.ref_source = 'OTHER' THEN
			IFNULL((SELECT name FROM seg_other_services AS o WHERE SUBSTRING(r.service_code,1,LENGTH(r.service_code)-1)=o.service_code),'[Deleted Item]')
		WHEN r.ref_source IN ('PP', 'MHC') THEN
			IF(r.service_code IS NOT NULL,
				CASE r.service_code
					WHEN 'DEPOSIT' THEN 'Deposit'
					WHEN 'PARTIAL' THEN 'Partial Payment'
                    WHEN 'OBANNEX' THEN 'Co-Payment: OB Annex'
					ELSE
						CASE SUBSTRING(r.service_code,1,1)
							WHEN 'O' THEN
								IFNULL((SELECT CONCAT('Deposit: ',description) FROM care_ops301_en WHERE code=SUBSTRING(r.service_code,2)),'[Deleted Item]')
							WHEN 'R' THEN
								IFNULL((SELECT CONCAT('Deposit: ',name) FROM seg_radio_services WHERE service_code=SUBSTRING(r.service_code,2)),'[Deleted Item]')
							WHEN 'L' THEN
								IFNULL((SELECT CONCAT('Deposit: ',name) FROM seg_lab_services WHERE service_code=SUBSTRING(r.service_code,2)),'[Deleted Item]')
							WHEN '-' THEN
								'Partial Payment'
							ELSE '[Unknown Item] '
						END
				END,
				'Partial payment')
		WHEN r.ref_source = 'FB' THEN
			'Billing Account'
		WHEN r.ref_source = 'COM' THEN 
			'Billing Account' 
		WHEN r.ref_source = 'PF' 
        THEN IFNULL(
          (SELECT 
            CONCAT(fn_get_person_name (ce.pid), 'MD') 
          FROM
            care_personell cp 
            LEFT JOIN care_person ce 
              ON cp.pid = ce.pid 
          WHERE cp.short_id = r.service_code),
          '[Deleted Item]'
        ) 
		WHEN r.ref_source = 'MISC' THEN
			IFNULL((SELECT name FROM seg_other_services AS o WHERE r.service_code=o.alt_service_code),'[Deleted Item]')
		ELSE 'No source found...'
	END SEPARATOR '\n') FROM seg_pay_request AS r WHERE r.or_no=p.or_no) AS `items`
FROM seg_pay AS p
LEFT JOIN seg_cashier_account_subtypes AS sub ON p.account_type=sub.type_id
LEFT JOIN seg_cashier_account_types AS typ ON sub.parent_type=typ.type_id\n";
		if ($wFilters) {
			$where = implode(") $mode (",$wFilters);
			$this->sql .= "WHERE ($where)";
		}
		if ($this->sql) $this->sql .= " ORDER BY p.or_date DESC";
		if ($rowcount) $this->sql .=  " LIMIT $offset, $rowcount";

		if($this->result=$db->Execute($this->sql)) {
			return $this->result;
		} else { return false; }
	}


	function getNextORNum($login_id, $vat) {

		global $db;
		$sql = "SELECT value
FROM care_config_global
WHERE type='cashier_or_number_digits'";
		$pad_num = $db->GetOne($sql);
		if(empty($pad_num)){
			$pad_num=7;
		}
		$sql = "SELECT sao.or_from, sao.or_to,
(SELECT COUNT(or_no) FROM seg_pay WHERE CAST(or_no AS UNSIGNED) > CAST(sao.or_from AS UNSIGNED) AND CAST(or_no AS UNSIGNED) <= CAST(sao.or_to AS UNSIGNED)) as or_used
FROM seg_assigned_ornos AS sao
WHERE is_deleted=0 AND is_locked=0 AND (sao.from_date < DATE(NOW()) OR sao.from_date=DATE(NOW())) AND (sao.to_date>DATE(NOW()) OR sao.to_date=DATE(NOW()))
AND sao.login_id='$login_id'
				AND is_vat = '$vat'
ORDER BY sao.or_from ASC";
		$rs = $db->Execute($sql);
		if($rs==NULL){
			$this->ornum_error = "There are no free assigned OR numbers for this user";
			return false;
		}
		else{
			while($rs!=NULL && $result=$rs->FetchRow()){
				$sql = "SELECT sp.or_no
FROM seg_pay AS sp
WHERE (CAST(sp.or_no AS UNSIGNED) > ".(int)$result["or_from"]." OR CAST(sp.or_no AS UNSIGNED)=".(int)$result["or_from"].")
	AND (CAST(sp.or_no AS UNSIGNED) < ".(int)$result["or_to"]." OR CAST(sp.or_no AS UNSIGNED)=".(int)$result["or_to"].")
	ORDER BY sp.or_no DESC, sp.or_date DESC";
				$rs2 = $db->Execute($sql);
				if($rs2==NULL){
					$or_num = str_pad($result["or_from"],$pad_num,'0',STR_PAD_LEFT);
					return $or_num;
				}
				else{
					if($val = $rs2->FetchRow()){
						if((int)$val["or_no"] < (int)$result["or_to"]){
							$or_num = (int)$val["or_no"] + 1;
							$or_num = str_pad($or_num,$pad_num,'0',STR_PAD_LEFT);
							return $or_num;
						}
					}
					else{
						$or_num = str_pad($result["or_from"],$pad_num,'0',STR_PAD_LEFT);
						return $or_num;
					}
				}
			}
			$this->ornum_error = "OR numbers assigned for this user have all been used up or locked";
			return false;
}

	}


	#added by daryl
	function get_ifvatable($orno_,$target){
		global $db;
		$orno = $db->qstr($orno_);

		if ($target == "vat"){
			$query = "sp.`vat_amount`";
		}

		else if ($target == "discount"){
			$query = "sp.`discount_tendered`";
		}

	$sql="SELECT $query 
			FROM seg_pay AS sp 
			WHERE sp.`or_no` = $orno";
	$rs = $db->Execute($sql);
	if ($rs){
		$result=$rs->FetchRow();
		# 1 -- vatable
		# 2 -- non-vatable
	if ($target == "vat"){
		$vat_amount = $result['vat_amount'];
			if ($vat_amount>0){
				$result = 1;
			}else{
				$result = 0;
			}
	}

	else if ($target == "discount"){
		$result = number_format($result['discount_tendered'],2);
	}
	}
		return $result;
	}

}