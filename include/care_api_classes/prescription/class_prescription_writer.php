<?php
	require('./roots.php');
	require_once($root_path.'include/care_api_classes/class_core.php');

	class SegPrescription extends Core {
		var $tb_prescription = "seg_prescription";
		var $tb_prescription_items = "seg_prescription_items";
		var $tb_template = "seg_prescription_template";
		var $tb_template_items = "seg_prescription_template_items";
		var $fld_prescription =
			array(
				'id',
				'encounter_nr',
				'prescription_date',
				'instructions',
                'clinical_impression',
				'is_deleted',
				'reason_for_deletion',
				'history',
				'create_id',
				'create_time',
				'modify_id',
				'modify_time',
				'pharma_refno'
			);
		var $fld_prescription_items =
			array(
				'prescription_id',
				'item_code',
				'item_name',
				'quantity',
				'unit',
				'dosage',
				'period_count',
				'period_interval'
			);
		var $fld_template =
			array(
				'id',
				'name',
				'owner',
				'is_deleted',
				'history',
				'create_id',
				'create_time',
				'modify_id',
				'modify_time'
			);
		var $fld_template_items =
			array(
				'template_id',
				'item_code',
				'item_name',
				'quantity',
				'unit',
				'dosage',
				'period_count',
				'period_interval'
			);

		function SegPrescription()
		{

		}

		function usePrescription()
		{
			$this->coretable = $this->tb_prescription;
			$this->ref_array = $this->fld_prescription;
		}

		function usePrescriptionItems()
		{
			$this->coretable = $this->tb_prescription_items;
			$this->ref_array = $this->fld_prescription_items;
		}

		function useTemplates()
		{
			$this->coretable = $this->tb_template;
			$this->ref_array = $this->fld_template;
		}

		function useTemplateItems()
		{
			$this->coretable = $this->tb_template_items;
			$this->ref_array = $this->fld_template_items;
		}
        
        
        /**
        * put your comment there...
        * 
        * @param mixed $encounter
        * @param mixed $checkId
        */
        function getLatestClinicalImpression($encounter, $checkId = true) {
            global $db;
            
            $sessionUserId = $_SESSION['sess_temp_userid'];
            $this->sql = "SELECT clinical_impression FROM seg_prescription WHERE encounter_nr=" . $db->qstr($encounter) ."\n";
            if ($checkid) {
                $this->sql .= "AND create_id=" . $db->qstr($sessionUserId) . "\n";
            }
            $this->sql .= "ORDER BY modify_time DESC";
            
            $this->result = $db->GetOne($this->sql);
            if ($this->result === false) {
                echo $db->ErrorMsg();
                echo "<pre>" . $ths->sql . "</pre>";
            }
            
            return $this->result;
        }
        
        

		function getRecentMeds($item_code, $offset, $maxcount)
		{
			global $db;

			if (!$offset) $offset = 0;
			if (!$maxcount) $maxcount = 10;

			$this->sql = "(SELECT SQL_CALC_FOUND_ROWS DISTINCT ph.generic,pi.item_name, pi.item_code, pi.quantity, \n".
										"pi.dosage, pi.period_count, pi.period_interval, 'Available' AS `availability` \n".
									"FROM seg_prescription_items AS pi \n".
									"INNER JOIN seg_prescription AS p ON pi.prescription_id=p.id \n".
									"INNER JOIN care_pharma_products_main AS ph ON ph.bestellnum=pi.item_code \n".
									"WHERE pi.item_code=".$db->qstr($item_code)." ORDER BY p.modify_time DESC)\n".
									"UNION\n".
									"(SELECT DISTINCT ph.generic, ti.item_name, ti.item_code, ti.quantity, ti.dosage, \n".
										"ti.period_count, ti.period_interval, 'Available' AS `availability` \n".
									"FROM seg_prescription_template_items AS ti \n".
									"INNER JOIN seg_prescription_template AS t ON ti.template_id=t.id \n".
									"INNER JOIN care_pharma_products_main AS ph ON ph.bestellnum=ti.item_code \n".
									"WHERE ti.item_code=".$db->qstr($item_code)."\n".
									"ORDER BY t.modify_time DESC )LIMIT $offset, $maxcount";
			if($this->result=$db->Execute($this->sql)) {
				return $this->result;
			} else { return false; }
		}

		function getTemplates($name, $offset, $maxcount, $sort)
		{
			global $db;
			if (!$offset || !is_numeric($offset))
                $offset = 0;
			if (!$maxcount || !is_numeric($maxcount))
                $maxcount = 10;

            
            if (!in_array($sort, array('item_name', 'name'))) {
                $sort = 'name';
            }
            
			$this->sql = "SELECT SQL_CALC_FOUND_ROWS t.*, t.name, t.owner, ti.item_code, ti.item_name, ti.dosage, \n".
			    "ti.quantity, ti.period_count, ti.period_interval, 'Available' AS `availability`, \n".
			    "(SELECT ph.generic FROM care_pharma_products_main AS ph WHERE ph.bestellnum=ti.item_code) AS `generic`, \n".
			    "(SELECT ph.is_restricted FROM care_pharma_products_main AS ph WHERE ph.bestellnum=ti.item_code) AS `is_restricted` \n".
			    "FROM seg_prescription_template AS t \n".
			    "INNER JOIN seg_prescription_template_items AS ti ON ti.template_id=t.id \n".
			    //"WHERE t.is_deleted=0 AND t.name like '$name%' \n".
                "WHERE t.is_deleted=0 AND create_id=" . $db->qstr($_SESSION['sess_temp_userid']) . " AND t.name LIKE " . $db->qstr($name . '%') . "\n".
			    "ORDER BY {$sort} LIMIT $offset, $maxcount";
			if($this->result=$db->Execute($this->sql)) {
				return $this->result;
			} else { return false; }
		}

		function savePrescription($data)
		{
			global $db;
			//edited by mai 10-02-2014W
			$existId = ($data['pharma_refno'] ? $this->selectPrescription($data['pharma_refno']) : ""); 
			if($existId){
				if($this->updatePrescription($data) && $this->deletePrescriptionItems($existId)){
					return $existId;
				}

			}else{
				$id = create_guid();
				$data['id'] = $id;
				$this->usePrescription();
				$this->setDataArray($data);
				if($this->insertDataFromInternalArray() !== FALSE) {
					return $id;
				} else {
					$this->error_msg = $db->ErrorMsg();
					return FALSE;
				}
			}
			return false;
		}

		//added by mai 10-02-2014
		function deletePrescriptionItems($id){
			global $db;
			
			$this->sql = "DELETE FROM seg_prescription_items WHERE prescription_id =".$db->qstr($id);
			
			if($db->Execute($this->sql)){
				return true;
			}else{
				return false;
			}
		}

		function updatePrescription($data){
			global $db;
			$this->sql = "UPDATE seg_prescription SET instructions =".$db->qstr($data['instructions']).",".
													   "history = CONCAT(history, ".$db->qstr($data['instructions'])."),".
													   "modify_id =  ".$db->qstr($data['modify_id']).",".
													   "modify_time =  ".$db->qstr($data['modify_time']).
													   " WHERE pharma_refno = ".$db->qstr($data['pharma_refno']);
			if($db->Execute($this->sql)){
				return true;
			}else{
				return false;
			}
		}

		function selectPrescription($refno){
			global $db;
			$this->sql = "SELECT id FROM seg_prescription WHERE pharma_refno = ".$db->qstr($refno);
			$this->result = $db->Execute($this->sql);
			
			if($this->result){
				if($row = $this->result->FetchRow()){
					return $row['id'];
				}
			}

			return false;
		}
		//end added by mai
		function savePrescriptionItems($id, $itemsArray)
		{
			global $db;
			$id = $db->qstr($id);
			$this->sql = "INSERT INTO $this->tb_prescription_items (prescription_id,item_code,item_name,quantity,dosage,\n".
									"period_count,period_interval) VALUES($id,?,?,?,?,?,?)";
			if($buf=$db->Execute($this->sql,$itemsArray)) {
				return TRUE;
			} else { $this->error_msg = $db->ErrorMsg(); return FALSE; }
		}

		function saveTemplate($data)
		{
			global $db;
			$id = create_guid();
			$data['id'] = $id;
			$this->useTemplates();
			$this->setDataArray($data);
			if($this->insertDataFromInternalArray() !== FALSE) {
				return $id;
			} else {
				$this->error_msg = $db->ErrorMsg();
				return FALSE;
			}
		}

		function saveTemplateItems($id, $itemsArray)
		{
			global $db;
			$id = $db->qstr($id);
			$this->sql = "INSERT INTO $this->tb_template_items (template_id,item_code,item_name,quantity,dosage,\n".
									"period_count,period_interval) VALUES($id,?,?,?,?,?,?)";
			if($buf=$db->Execute($this->sql,$itemsArray)) {
				return TRUE;
			} else { $this->error_msg = $db->ErrorMsg();  return FALSE; }
		}

		function listTemplates($name, $sort, $offset, $maxcount)
		{
			global $db;
			if (!$offset) $offset = 0;
			if (!$maxcount) $maxcount = 10;

			$this->sql = "SELECT SQL_CALC_FOUND_ROWS fn_get_person_name(cp.pid) AS `owner_name`,t.* \n".
									"FROM seg_prescription_template AS t \n".
									"LEFT JOIN care_users AS cu ON t.owner=cu.login_id \n".
									"LEFT JOIN care_personell AS p ON p.nr=cu.personell_nr \n".
									"LEFT JOIN care_person AS cp ON cp.pid=p.pid \n".
									"WHERE t.is_deleted=0 AND t.name like '$name%' \n".
									"ORDER BY {$sort} LIMIT $offset, $maxcount";
			if($this->result=$db->Execute($this->sql)) {
				return $this->result;
			} else { $this->error_msg = $db->ErrorMsg(); return false; }
		}

		function getTemplateItems($id)
		{
			global $db;

			$this->sql = "SELECT SQL_CALC_FOUND_ROWS ti.*, ph.generic \n".
									"FROM seg_prescription_template_items AS ti \n".
									"INNER JOIN seg_prescription_template AS t ON t.id=ti.template_id \n".
									"LEFT JOIN care_pharma_products_main AS ph ON ph.bestellnum=ti.item_code \n".
									"WHERE t.is_deleted=0 AND t.id='$id' \n".
									"ORDER BY ti.item_name ASC";
			if($this->result=$db->Execute($this->sql)) {
				return $this->result;
			} else { $this->error_msg = $db->ErrorMsg(); return false; }
		}

		function deleteTemplate($id)
		{
			global $db;
			$this->sql = "UPDATE $this->tb_template SET is_deleted=1, \n".
						"history=CONCAT(history,'\nDeleted: ".date('Y-m-d H:i:s')." [".addslashes($_SESSION['sess_temp_userid'])."]'), \n".
						"modify_time=".$db->qstr(date('Y-m-d H:i:s')).", modify_id=".$db->qstr($_SESSION['sess_temp_userid'])." \n".
						"WHERE id=".$db->qstr($id);
			if($this->result=$db->Execute($this->sql)) {
				if($db->Affected_Rows()) {
					return TRUE;
				}
			}else {
				$this->error_msg = $db->ErrorMsg();
				return FALSE;
			}
		}

		function clearTemplateItems($id)
		{
			global $db;
			$this->sql = "DELETE FROM $this->tb_template_items WHERE template_id=".$db->qstr($id);
			if($this->result=$db->Execute($this->sql)) {
				return TRUE;
			} else {
				$this->error_msg = $db->ErrorMsg();
				return FALSE;
			}
		}

		function updateTemplate($id, $name)
		{
			global $db;
			$this->sql = "UPDATE $this->tb_template SET name=".$db->qstr($name).", \n".
								"history=CONCAT(history,'\nUpdated: ".date('Y-m-d H:i:s')." [".addslashes($_SESSION['sess_temp_userid'])."]'), \n".
								"modify_time=".$db->qstr(date('Y-m-d H:i:s')).", modify_id=".$db->qstr($_SESSION['sess_temp_userid'])." \n".
								"WHERE id=".$db->qstr($id);
			if($this->result=$db->Execute($this->sql)) {
				return TRUE;
			} else {
				$this->error_msg = $db->ErrorMsg();
				return FALSE;
			}
		}
        
        
        function getPrescriptionInfo($prescription_id) {
            global $db;
            $this->sql = "SELECT id, encounter_nr, prescription_date, instructions,\n" .
                "clinical_impression\n" . 
                "FROM seg_prescription\n" .
                "WHERE id=" . $db->qstr($prescription_id);
            return $db->GetRow($this->sql);
        }
        

		function getPrescription($encounter_nr, $prescription_id)
		{
			global $db;
			$this->sql = "SELECT SQL_CALC_FOUND_ROWS\n".
                    "product.artikelname, product.generic,\n".
                    "spi.item_name, spi.quantity, spi.dosage, spi.period_count, \n".
			        "spi.period_interval\n".
                    //"fn_get_person_name_first_mi_last(cp.pid) AS `writer` ,\n".
                    //"cp.license_nr, cp.prescription_license_nr\n".
			    "FROM seg_prescription_items AS spi \n".
			        //"INNER JOIN seg_prescription AS p ON pi.prescription_id=p.id \n".
                    "LEFT JOIN care_pharma_products_main product ON product.bestellnum=spi.item_code\n".
			        //"LEFT JOIN care_users AS u ON p.create_id=u.login_id \n".
			        //"LEFT JOIN care_personell AS cp ON u.personell_nr=cp.nr \n".
			    //"WHERE p.encounter_nr=".$db->qstr($encounter_nr)." AND p.prescription_date=DATE(NOW()) ORDER BY item_name ASC";
			    "WHERE spi.prescription_id=".$db->qstr($prescription_id)." ORDER BY item_name ASC";
			if($this->result=$db->Execute($this->sql)) {
				return $this->result;
			}else {
				$this->error_msg = $db->ErrorMsg();
				return FALSE;
			}
		}

		function getPrescriptionPrint($prescription_id){ //added by mai 10-20-2014
			global $db;
			$this->sql = "SELECT 
							  SQL_CALC_FOUND_ROWS product.artikelname,
							  product.generic,
							  spi.item_name,
							  spi.quantity - IFNULL(spoi.quantity, 0) AS quantity,
							  spi.dosage,
							  spi.period_count,
							  spi.period_interval 
							FROM
							  seg_prescription_items AS spi 
							  LEFT JOIN care_pharma_products_main product 
							    ON product.bestellnum = spi.item_code 
							  LEFT JOIN seg_prescription AS sp 
							    ON sp.id = spi.prescription_id 
							  LEFT JOIN seg_pharma_order_items spoi 
							    ON (
							      spoi.refno = sp.`pharma_refno` 
							      AND spoi.bestellnum = spi.`item_code`
							    ) 
							WHERE spi.prescription_id = ".$db->qstr($prescription_id)." AND (spi.quantity - IFNULL(spoi.quantity, 0) <> 0 )
							ORDER BY item_name ASC ";

			$this->result = $db->Execute($this->sql);
			if($this->result){
				return $this->result;
			}else{
				$this->error_msg = $db->ErrorMsg();
				return FALSE;
			}
		}
        
        function getPrescriberInfo($nr) {
            global $db;
        /*
		edited by Nick, 11/18/2013 4:07 PM
		added a return field name_formal to identify the department
		of the doctor
        */    
            $this->sql = "SELECT\n" .
                    "fn_get_person_name_first_mi_last(personnel.pid) AS `name`,\n".
                    "personnel.license_nr, personnel.prescription_license_nr, personnel.ptr_nr, personnel.s2_nr,dept.`name_formal` \n".
                "FROM seg_prescription prescription\n".
                    "INNER JOIN care_users user ON prescription.create_id=user.login_id \n".
                    "INNER JOIN care_personell personnel ON user.personell_nr=personnel.nr \n".
                    "INNER JOIN care_personell_assignment cpa ON cpa.`personell_nr`=user.`personell_nr` \n".
                    "INNER JOIN care_department dept ON dept.nr = cpa.`location_nr` \n".
                //"WHERE prescription.id=" . $db->qstr($prescriptionId);
                    "WHERE personnel.nr = ".$db->qstr($nr);
        /*
        end Nick
        */
            return $db->GetRow($this->sql);
        }
        

		function isLicensedPersonell()
		{
			global $db;
			$this->sql = "SELECT EXISTS( SELECT cp.prescription_license_nr \n".
								"FROM care_personell AS cp \n".
								"LEFT JOIN care_users AS cu ON cp.nr=cu.personell_nr \n".
								"WHERE cu.login_id=".$db->qstr($_SESSION['sess_temp_userid'])." \n".
								"AND (!ISNULL(cp.prescription_license_nr) OR cp.prescription_license_nr!='') \n".
								") AS `has_license`";
			if($has_license=$db->GetOne($this->sql)) {
				return $has_license;
			} else {
				$this->error_msg = $db->ErrorMsg();
				return FALSE;
			}
		}
        
        
		function getPrescriptionLicense()
		{
			global $db;
			$this->sql = "SELECT cp.prescription_license_nr FROM care_personell AS cp \n".
				"LEFT JOIN care_users AS cu ON cp.nr=cu.personell_nr \n".
				"WHERE cu.login_id=".$db->qstr($_SESSION['sess_temp_userid']);
			if($license=$db->GetOne($this->sql)) {
				return $license;
			} else {
				$this->error_msg = $db->ErrorMsg();
				return FALSE;
			}
		}

		function getListMeds($encounter_nr){
			global $db;

			$this->sql = "SELECT 
							   CONCAT(
								    i.`item_name`,
								    ' (x',
								    SUM(i.`quantity`),
								    ')'
								  ) med  
							FROM
							  seg_prescription_items i 
							  LEFT JOIN seg_prescription p 
							    ON p.`id` = i.`prescription_id` 
							WHERE p.`encounter_nr` = ".$db->qstr($encounter_nr)." 
							GROUP BY i.`item_name` ";

			if($this->result = $db->Execute($this->sql)){
				return $this->result;
			}

			return false;
		}

		//added by mai 10-07-2014
		function getEncPrescription($encounter_nr){
			global $db;
			$this->sql = "SELECT 
							  t.prescription_id,
							  t.instructions,
							  t.prescription_date,
							  t.pharma_refno 
							FROM
							  (
							    (SELECT 
							      id AS prescription_id,
							      instructions,
							      prescription_date,
							      IF(pharma_refno = '', id, pharma_refno) AS pharma_refno 
							    FROM
							      seg_prescription 
							    WHERE encounter_nr = ".$db->qstr($encounter_nr).") 
							    UNION
							    (SELECT 
							      NULL AS prescription_id,
							      NULL AS instructions,
							      DATE(orderdate) AS prescription_date,
							      refno AS pharma_refno 
							    FROM
							      seg_pharma_orders 
							    WHERE encounter_nr = ".$db->qstr($encounter_nr).")
							  ) t GROUP BY t.pharma_refno
								ORDER BY t.prescription_date DESC ";

			$this->result = $db->Execute($this->sql);

			if($this->result){
				return $this->result;
			}else{
				return false;
			}
		}


		function getMeds($refno){
			global $db;

			$this->sql = "SELECT 
						  SQL_CALC_FOUND_ROWS product.artikelname,
						  product.generic,
						  product.`artikelname` AS item_name,
						  p.quantity,
						  NULL dosage,
						  NULL period_count,
						  NULL period_interval 
						FROM
						  seg_pharma_order_items AS p
						  LEFT JOIN care_pharma_products_main product 
						    ON product.bestellnum = p.bestellnum 
						WHERE p.refno = ".$db->qstr($refno)." 
						ORDER BY item_name ASC ";
			
			$this->result = $db->Execute($this->sql);
			if($this->result){
				return $this->result;
			}else{
				return false;
			}
		}
		//end added by mai


		function getPrescriptionOrders($filters, $offset=0, $rowcount=15){
			global $db;

			if(is_array($filters)){
				foreach($filters as $i=>$v){
					switch(strtolower($i)){
						case 'datetoday':
							$phFilters [] = "DATE(sp.create_time) = DATE(NOW())";
							break;
						case 'datethisweek':
							$phFilters [] = "WEEK(DATE(sp.create_time)) = WEEK(DATE(NOW()))";
							break;
						case 'datethismonth':
							$phFilters [] = "MONTH(DATE(sp.create_time)) = MONTH(DATE(NOW()))";
							break;
						case 'date':
							$phFilters[] = "DATE(sp.create_time)=".$db->qstr($v);
							break;
						case 'datebetween':
							$phFilters[] = "DATE(sp.create_time) BETWEEN ".$db->qstr($v[0])." AND ".$db->qstr($v[1]);
							break;
						case 'name':
							if (strpos($v,',')!==false) {
								$split_name = explode(',', $v);
								$phFilters[] = "cp.name_last LIKE ".$db->qstr(trim($split_name[0]).'%');
								$phFilters[] = "cp.name_first LIKE ".$db->qstr(trim($split_name[1]).'%');
							}
							else {
								if ($v) {
									$phFilters[] = "cp.name_last LIKE ".$db->qstr(trim($v).'%');
								}
							}
							break;
						case 'pid':
							$phFilters [] = "cp.pid ".$db->qstr($v);
							break;
						case 'inpatient':
							$phFilters [] = "ce.encounter_nr".$db->qstr($v);
							break;
					}
				}
			}

			if(!$phFilters){
				$phFilters[] = "sp.`create_time` >= NOW() - INTERVAL 1 MONTH";
			}

			$phWhere = implode(") AND (",$phFilters);

			if ($phWhere) 
				$phWhere = "($phWhere)";

			$this->sql = "SELECT SQL_CALC_FOUND_ROWS 
							  sp.modify_time AS orderdate,
							  sp.id AS prescription_id,
  							  ce.encounter_nr,
							  sp.pharma_refno AS refno,
							  ce.pid,
							  fn_get_person_name (ce.pid) AS `name`,
							  '' AS is_cash,
							  '' AS charge_type,
							  '' AS area_full,
							  0 AS is_urgent,
							  (SELECT 
							     GROUP_CONCAT(
							      CONCAT(
							        '',
							        '\t',
							        'N',
							        '\t',
							        spi.item_name
							      ) SEPARATOR '\n'
							    ) 
							  FROM
							    seg_prescription_items spi 
							  WHERE spi.prescription_id = sp.id) AS items 
							FROM
							  seg_prescription sp 
							  LEFT JOIN care_encounter ce 
							    ON ce.`encounter_nr` = sp.`encounter_nr` 
							  LEFT JOIN care_person cp 
    							ON ce.`pid` = cp.pid 
							WHERE (
							    (
							       $phWhere 
							    )
							  ) 
							ORDER BY sp.`create_time` DESC 
							LIMIT $offset, $rowcount ";

			$this->result = $db->Execute($this->sql);
			if($this->result){
				return $this->result;
			}

			return false;
		}

		function getPresrciptionEncounter($encounter_nr){
			global $db;

			$this->sql = "SELECT 
						  sp.`id`,
						  sp.`instructions`
						FROM
						  seg_prescription sp 
						WHERE sp.`is_deleted` = 0 
						  AND encounter_nr =  ".$db->qstr($encounter_nr);

			if($this->result = $db->Execute($this->sql)){
				return $this->result;
			}

			return false;

		}

		function getPrescriptionItem($id){
			global $db;

			$this->sql = 'SELECT 
							  item_name,
							  quantity,
							  dosage,
							  period_count,
							  CASE
							    (period_interval) 
							    WHEN ("M") 
							    THEN "Months" 
							    WHEN ("D") 
							    THEN "Days" 
							    WHEN ("W") 
							    THEN "Week" 
							    ELSE "" 
							  END period_interval 
							FROM
							  seg_prescription_items 
							WHERE prescription_id = '.$db->qstr($id);

			if($this->result = $db->Execute($this->sql)){
				return $this->result;
			}

			return false;
		}
	}//end of class prescription writer
?>
