<?php
/**
*Created By: Maimai
*Created On: 09/10/2014
*/

require_once('roots.php');
require_once($root_path.'include/care_api_classes/class_core.php');

class Promissory_note extends Core{
	var $sql;
	var $result;
	var $tbl_promi = "seg_promissory_note";

	function insertPromi($due_date, $encounter_nr, $amount, $remarks, $is_sum, $is_installment){
		global $db, $HTTP_SESSION_VARS;
		
		$refno = $this->getNewRefno();

		if($refno){
		$this->sql = "INSERT INTO ".$this->tbl_promi."(refno, encounter_nr, amount, due_date, remarks, is_sum, is_installment, create_id, create_dt)".
					" VALUES (".$db->qstr($refno).", ".$db->qstr($encounter_nr).",".$db->qstr($amount)
							.",".$db->qstr($due_date).",".$db->qstr($remarks)
							.",".$db->qstr($is_sum).",".$db->qstr($is_installment)
							.",".$db->qstr($_SESSION['sess_temp_userid']).", NOW()"
							.")";

			if($db->Execute($this->sql)){
				return $refno;
			}
		}else{
			return false;
		}
	}

	function getNewRefno(){
		global $db;
		$this->sql = "SELECT fn_get_new_refno_promi(NOW()) as refno";
		if($this->result = $db->Execute($this->sql)){
			if( $row = $this->result->Fetchrow()){
				return $row['refno'];
			}
		}

		return false;
	}

	function updatePromi($refno, $due_date, $amount, $remarks, $is_sum, $is_installment){
		global $db, $HTTP_SESSION_VARS;
		
		$this->sql = "UPDATE ".$this->tbl_promi
					." SET due_date = ".$db->qstr($due_date)
					.", amount = ".$db->qstr($amount)
					.", remarks = ".$db->qstr($remarks)
					.", is_sum = ".$db->qstr($is_sum)
					.", is_installment = ".$db->qstr($is_installment)
					.", modify_id = ".$db->qstr($_SESSION['sess_temp_userid'])
					.", modify_dt = NOW()"
					." WHERE refno =".$db->qstr($refno)." AND note_status <> 'deleted'";

		if($db->Execute($this->sql)){
			return true;
		}else{
			return false;
		}
	}

	function selectPromiDetails($refno){
		global $db;
		$this->sql = "SELECT 
					  spn.`encounter_nr`,
					  fn_get_person_lastname_first (ce.`pid`) AS patient_name,
					  DATE(ce.`encounter_date`) AS encounter_date,
					  ce.`discharge_date`,
					  spn.amount,
					  is_sum,
					  is_installment,
					  due_date,
					  DATE(due_date) - DATE(create_dt) AS days_to_pay,
					  remarks 
					FROM
					  seg_promissory_note spn 
					  LEFT JOIN care_encounter ce 
					    ON ce.`encounter_nr` = spn.`encounter_nr` 
					  LEFT JOIN care_person cp 
					    ON cp.pid = ce.`pid` 
					WHERE refno =  ".$db->qstr($refno);

		$this->result = $db->Execute($this->sql);
		if($this->result){
			return $this->result;
		}else{
			return false;
		}

	}

	function getSummary($date_from, $date_to){
		global $db;

		$this->sql = "SELECT 
					  spn.`due_date`,
					  sbe.`bill_nr`,
					  spn.`encounter_nr`,
					  fn_get_person_lastname_first (ce.`pid`) AS pname,
					  spn.is_sum,
					  spn.`is_installment`,
					  spn.`amount`,
					 fn_billing_compute_net_amount (sbe.`bill_nr`) AS total_bill,
					  (
					    sbe.total_prevpayments - (
					      (SELECT 
					        IFNULL(SUM(a.amount), 0) amount 
					      FROM
					        `seg_person_ledger_d` a 
					      WHERE a.`bill_nr` = sbe.`bill_nr` 
					        AND a.`entry_type` = 'credit' 
					        AND a.`pay_type` = 'memo') + 
					      (SELECT 
					        IFNULL(SUM(a.amount), 0) amount 
					      FROM
					        `seg_person_ledger_d` a 
					      WHERE a.`bill_nr` = sbe.`bill_nr` 
					        AND a.`entry_type` = 'debit')
					    )
					  ) AS total_payment,
					  spn.`remarks` 
					FROM
					  seg_promissory_note spn 
					  LEFT JOIN care_encounter ce 
					    ON ce.`encounter_nr` = spn.`encounter_nr` 
					  LEFT JOIN seg_billing_encounter sbe 
					    ON (
					      sbe.`encounter_nr` = spn.`encounter_nr` 
					      AND sbe.is_deleted IS NULL
					    ) WHERE DATE(spn.`create_dt`) BETWEEN DATE(".$db->qstr($date_from).") AND DATE(".$db->qstr($date_to).")
					ORDER BY DATE(spn.due_date),
					  fn_get_person_lastname_first (ce.`pid`)";
		
		if($this->result = $db->Execute($this->sql)){
			return $this->result;
		}

		return false;
	}
}

?>