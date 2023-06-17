<?php
/**
*Created by mai
*Created on 02-06-2015
*/

require_once ($root_path.'include/care_api_classes/class_core.php');

class Holiday extends Core{
	var $tb_holiday = "seg_holidays";

	var $sql;
	var $result;
	var $db;
	var $rec_count;

	var $details; 

	function insertHoliday(){
		global $db;

		$this->sql = "INSERT INTO ".$this->tb_holiday.
						"(holiday, dynamic_date, day, month, year, create_id, create_dt, modify_id, modify_dt) VALUES(".
							$db->qstr($this->details['holiday']).",".
							$db->qstr($this->details['dynamic_date']).",".
							$db->qstr($this->details['day']).",".
							$db->qstr($this->details['month']).",".
							$db->qstr($this->details['year']).",".
							$db->qstr($this->details['create_id']).", NOW(),".
							$db->qstr($this->details['create_id']).", NOW()".
							")";

		if($db->Execute($this->sql)){
			return true;
		}

		return false;
	}

	function selectHoliday($id = ''){
		global $db;
		$where = '';
		if($nr){
			$where = " AND id <> ".$db->qstr($id); 
		}

		$this->sql = "SELECT id FROM ".$this->tb_holiday.
						" WHERE is_deleted <> 1 AND day = ".$db->qstr($this->details['day']).
						" AND month = ".$db->qstr($this->details['month']).$where;

		return $db->GetOne($this->sql);
	}

	function getHolidayNr(){
		global $db;

		$this->sql = "SELECT * FROM ".$this->tb_holiday.
						" WHERE id = ".$db->qstr($this->details['id']);

		if($this->result = $db->Execute($this->sql)){
			return $this->result;
		}

		return false;
	}

	function searchHoliday(){

	}

	function updateHoliday(){
		global $db;

		$this->sql = "UPDATE ".$this->tb_holiday.
						" SET holiday = ".$db->qstr($this->details['holiday']).",
						dynamic_date = ".$db->qstr($this->details['dynamic_date']).",
						day = ".$db->qstr($this->details['day']).",
						month = ".$db->qstr($this->details['month']).",
						year = ".$db->qstr($this->details['year']).",
						modify_id = ".$db->qstr($this->details['modify_id']).",
						modify_dt = NOW() ".
						" WHERE id = ".$db->qstr($this->details['id']);

		if($db->Execute($this->sql)){
			return true;
		}

		return false;

	}

	function getLimitHoliday($len=30,$so=0,$sortby='holiday',$sortdir='ASC'){
				global $db;
				
				if($sortby == 'month'){
					$sortby = " MONTH ASC, ";
					$sortdir = " DAY ASC ";
				}

				$this->sql="SELECT * FROM ".$this->tb_holiday." WHERE is_deleted <> 1 ORDER BY $sortby $sortdir";
				
				if($this->result['id']=$db->SelectLimit($this->sql,$len,$so)){
						if($this->rec_count=$this->result['id']->RecordCount()){
								return $this->result['id'];
						}else{ return FALSE; }
				}else{ return FALSE; }
	}

	function countAllHoliday(){
		global $db;
			$this->sql="SELECT id FROM ".$this->tb_holiday." WHERE is_deleted <> 1";
			if($buffer=$db->Execute($this->sql)){
					return $buffer->RecordCount();
			} else { return 0; }
	}


	function removeHoliday(){
		global $db;

		$this->sql = "UPDATE ".$this->tb_holiday.
							" SET is_deleted = 1".
							" WHERE id = ".$db->qstr($this->details['id']);

		if($db->Execute($this->sql)){
			return true;
		}

		return false;
	}
}
?>