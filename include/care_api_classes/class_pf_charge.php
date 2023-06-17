<?php
/*created by mai 08-20-2014*/

require_once($root_path.'include/care_api_classes/class_core.php');

class Pf_charge extends Core {
	var $tb_pf = 'seg_pf_service';
	var $tb_pf_details = 'seg_pf_service_details';
	var $sql;
	var $error_msg;
	var $result;

	function getPfRefno($charge_date){
		global $db;
		$this->sql = "SELECT fn_get_new_refno_pf_service(".$db->qstr($charge_date).")";
		$refno = $db->GetOne($this->sql);
		if($refno!==FALSE) {
			return $refno;
		} else {
			$this->error_msg = $db->ErrorMsg();
			return FALSE;
		}
	}

	function savePfCharges($details){
		global $db;
		$db->StartTrans();
		extract($details);   
		$no_error = false;
		$author = $_SESSION['sess_temp_userid'];  

		if ((count($dr_nr) > 0)) {
			if ($this->addPfOrder($chrge_dte, $encounter_nr, $pid, $is_cash, $request_source, $author)!=FALSE) {
				$no_error = $this->addPfOrderItemsByBulk(array("refno"=>$refno, "dr_nr"=>$dr_nr, "chrg_amount"=>$chrg_amount));
				 }else $no_error = FALSE;
			}else $no_error = FALSE;

			if ($no_error) {
				 $db->CompleteTrans();
				 return TRUE;
			}
			else {
				$db->FailTrans();
				$this->error_msg = $db->ErrorMsg();
				return FALSE;
			}
	}

	function updatePfCharges($details){
		global $db;
		$db->StartTrans();
		extract($details);   
		$no_error = false;
		$author = $_SESSION['sess_temp_userid'];  

		if ($refno) {
			if($no_error = $this->updatePfOrder($refno, $chrge_dte, $encounter_nr, $pid, $is_cash, $request_source, $author)){
				if ($no_error = $this->deletePfOrders($refno)) {
					if ((count($dr_nr) > 0)) {
						//if ($this->addPfOrder($chrge_dte, $encounter_nr, $pid, $is_cash, $request_source, $author)!=FALSE) {
						if($no_error = $this->addPfOrderItemsByBulk(array("refno"=>$refno, "dr_nr"=>$dr_nr, "chrg_amount"=>$chrg_amount))){
						}else $no_error = FALSE;
					}else $no_error = FALSE;
				}
			}
		}

		if ($no_error) {
			$db->CompleteTrans();
			return TRUE;
		}else {
			$db->FailTrans();
			$this->error_msg = $db->ErrorMsg();
			return FALSE;
		}
	}

	function updatePfOrder($refno, $chrge_dte, $encounter_nr, $pid, $is_cash, $request_source, $author){
		global $db;
		$this->sql ="UPDATE ".$this->tb_pf." SET chrge_dte = ".$db->qstr($chrge_dte)
					.", is_cash =".$db->qstr($is_cash)
					.", request_source=".$db->qstr($request_source)
					.", modify_dt = NOW(), modify_id=".$db->qstr($author)
					." WHERE refno=".$db->qstr($refno);

		if($db->Execute($this->sql)){
			return true;
		}else{
			return false;
		}
	}

	function deletePfOrders($refno){
		global $db;
		
		$this->sql = "DELETE FROM ".$this->tb_pf_details." WHERE refno=".$db->qstr($refno);

		if($db->Execute($this->sql)){
			return true;
		}else{
			return false;
		}	
	}

	function addPfOrder($charge_date, $encounter_nr, $pid, $is_cash, $request_source, $author){
		global $db;

		$refno = $db->GetOne("SELECT fn_get_new_refno_pf_service(".$db->qstr($charge_date).")");

		$this->sql = "INSERT INTO ".$this->tb_pf." (refno, chrge_dte, encounter_nr, pid, 
							modify_id, modify_dt, create_id, create_dt, 
							is_cash, request_source) 
								VALUES(".$db->qstr($refno).", ".$db->qstr($charge_date).",".$db->qstr($encounter_nr).", ".$db->qstr($pid).", ".$db->qstr($author).", NOW(), ".$db->qstr($author).", NOW(), ".$db->qstr($is_cash).",".$db->qstr($request_source).")";

		if ($result = $db->Execute($this->sql)) {
			return $refno;
		}
		else {
			$this->error_msg = $db->ErrorMsg();
			return FALSE;
		}
	}

	function addPfOrderItemsByBulk($details){
		global $db;
		extract($details);
		$order_items = array();
		
		for($i=0; $i<count($dr_nr); $i++) {
			$items_array = array($dr_nr[$i], $chrg_amount[$i]);
			$order_items[] = $items_array;
		}

		$index = 'refno, dr_nr, chrg_amount';
		$values = "'$refno', ?, ?";

		$this->sql = "INSERT INTO ".$this->tb_pf_details." ($index) VALUES ($values)";

		$result = $db->Execute($this->sql, $order_items);
			if ($result==FALSE) {
				$this->error_msg = $db->ErrorMsg();
				return FALSE;
			}
			else {
				return TRUE;
			}
	 }

	 function getPfByRefno($refno){
	 	global $db;
	 	$this->sql = "SELECT 
					  s.*,
					  c.`name_last`,
					  c.name_first,
					  c.`name_middle` 
					FROM
					  ".$this->tb_pf_details." s 
					  LEFT JOIN care_personell p 
					    ON s.`dr_nr` = p.`short_id` 
					  LEFT JOIN care_person c 
					    ON p.pid = c.pid 
					WHERE refno = ".$db->qstr($refno);

		$this->result = $db->Execute($this->sql);
		if($this->result){
			return $this->result;
		}else{
			return false;
		}
	 }

	 function getPfRequests($chrge_dte, $encounter_nr){
	 	global $db;

		$this->sql = "SELECT DISTINCT 
					  (spsd.refno),
					  IF(sps.is_cash = 0, 'Charge', 'Cash') AS charge_type,
					  sps.is_cash,
					  sps.request_source 
					FROM
					  seg_pf_service_details spsd 
					  LEFT JOIN seg_pf_service sps 
					    ON spsd.refno = sps.refno 
					WHERE encounter_nr = ".$db->qstr($encounter_nr)." 
					  AND DATE(chrge_dte) = DATE(".$db->qstr($chrge_dte).") 
					ORDER BY DATE(chrge_dte),
					  refno DESC ";

		$this->result = $db->Execute($this->sql);
		if($this->result){
			return $this->result;
		}else{
			return false;
		}
	 }

	 function getPfRequestsByRefno($refno){
	 	global $db;
	 	$this->sql = "SELECT 
					  ps.refno,
					  0 AS is_not_socialized,
					  psd.`request_flag`,
					  ps.encounter_nr,
					  ps.request_source AS area,
					  fn_get_personell_name (cp.nr) AS name,
					  'HospBills' AS name_short,
					  psd.chrg_amount AS net_price,
					  psd.`chrg_amount` AS chrg_amnt,
					  psd.dr_nr AS code,
					  1 AS quantity,
					  0 AS account_type,
					  ps.`create_id`,
					  ps.`modify_id`,
					  ps.`chrge_dte`,
					  ps.is_cash 
					FROM
					  seg_pf_service_details psd 
					  LEFT JOIN seg_pf_service ps 
					    ON ps.refno = psd.refno 
					  LEFT JOIN care_personell cp 
					    ON psd.dr_nr = cp.short_id 
					WHERE psd.refno = ".$db->qstr($refno);

		$this->result = $db->Execute($this->sql);
		if($this->result){
			return $this->result;
		}else{
			return false;
		}
	 }

	 function deletePfRequest($refno){
	 	global $db;
	 	if($this->deletePfOrders($refno)){
	 		$this->sql = "DELETE FROM ".$this->tb_pf." WHERE refno=".$db->qstr($refno);
	 		if($db->Execute($this->sql)){
	 			return true;
	 		}
	 	}

	 	return false;
	 }

	 function deletePfByEncounter($encounter_nr, $personell_id){
	 	global $db;
	 	$this->sql = "DELETE 
						FROM
						  seg_pf_service_details 
						WHERE refno IN 
						  (SELECT 
						    refno 
						  FROM
						    seg_pf_service 
						  WHERE encounter_nr = ".$db->qstr($encounter_nr) 
						   ." AND is_cash = 0) 
						  AND dr_nr IN 
						  (SELECT 
						    short_id 
						  FROM
						    care_personell 
						  WHERE nr = ".$db->qstr($personell_id).")";
		
		if($db->Execute($this->sql)){
			return true;
		}

		return false;
	 }
}

?>