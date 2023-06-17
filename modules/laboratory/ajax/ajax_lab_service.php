<?php
	#Created by Jarel 10/07/2014
    require('./roots.php');
    require($root_path.'include/inc_environment_global.php');
	require_once($root_path.'include/care_api_classes/class_department.php');
	require_once($root_path.'include/care_api_classes/class_personell.php');
	require_once($root_path.'include/care_api_classes/class_globalconfig.php');
	require_once($root_path.'include/care_api_classes/class_special_lab.php');
	require_once($root_path.'include/care_api_classes/class_encounter.php');
	require_once($root_path.'include/care_api_classes/class_social_service.php');
	require_once($root_path.'include/care_api_classes/class_pharma_product.php');
    require "{$root_path}classes/json/json.php";

    global $db;
 
    $glob_obj = new GlobalConfig($GLOBAL_CONFIG);
	$glob_obj->getConfig('pagin_patient_search_max_block_rows');
	$maxRows = $GLOBAL_CONFIG['pagin_patient_search_max_block_rows'];

	$srv=new SegSpecialLab();
	$pharma=new SegPharmaProduct();
	$objSS = new SocialService;
	$offset = $page * $maxRows;
    $searchkey = $_GET['term'];
  	$pid = $_GET['pid'];
	$date = date('Y-m-d' , strtotime($_GET['date']));
	$time = date('H' , strtotime($_GET['date']));
	$day= date('d' , strtotime($_GET['date']));
	$month= date('m' , strtotime($_GET['date']));
	$year= date('Y' , strtotime($_GET['date']));
	$area= $_GET['area'];
	$area_type= $_GET['area_type'];
	$encounter_nr= $_GET['encounter_nr'];
	$is_cash=$_GET['is_cash']; 
	$discountid=$_GET['discountid']; 
	$discount=$_GET['discount']; 
	$is_senior=$_GET['is_senior']; 
	$is_walkin=0; 
	$source_req=$_GET['source_req']; 
	$is_notphic=$_GET['is_notphic']; 


	    $insurance_type = $pharma->getinsrnceTyp($encounter_nr);
	    $room_types=$pharma->getRoomTypes($encounter_nr);	
	  if(!empty($encounter_nr))
	  {
	  	$INCPHICaddtional =".".$pharma->insurancePHIC($room_types,$insurance_type);
		$INCNONPHICaddtional = ".".$pharma->insuranceNONPHIC($room_types);
	  } 
	  else
	  {
	  	
		$INCPHICaddtional=0;
	  	$INCNONPHICaddtional=0;
	  }
		
		

		
		$Ifhasroom = $room_types ? 1 : 0;

	if (!$discount)
		$discount = 0;

	$ssInfo = $objSS->getSSClassInfo($discountid);

	$sc_walkin_discount = 0;

    if ($is_senior){
		$discountid='SC';

		$sql_sc = "SELECT * FROM seg_default_value WHERE name='senior discount' AND source='SS'";
		$rs_sc = $db->Execute($sql_sc);
		$row_sc = $rs_sc->FetchRow();

		if ($row_sc['value'])
			$sc_walkin_discount = $row_sc['value'];
	}

	if ($isStat){
		$sql_stat = "SELECT * FROM seg_default_value WHERE name='stat charge' AND source='LD'";
		$rs_stat = $db->Execute($sql_stat);
		$row_stat = $rs_stat->FetchRow();

		if ($row_stat['value'])
			$stat_additional = $row_stat['value'];
	}

	if ($area_type=='pw'){
		$sql_pw = "SELECT * FROM seg_default_value WHERE name='payward charge' AND source='LD'";
		$rs_pw = $db->Execute($sql_pw);
		$row_pw = $rs_pw->FetchRow();

		if ($row_pw['value'])
			$pw_additional = $row_pw['value'];
	}

	if (stristr($searchkey,",")){
		$keyword_multiple = explode(",",$searchkey);
		$codenum = 0;
		if (is_numeric($keyword_multiple[0]))
				$codenum = 1;

		for ($i=0;$i<sizeof($keyword_multiple);$i++){
			$keyword .= "'".trim($keyword_multiple[$i])."',";
		}

		$word = trim($keyword);
		$searchkey = substr($word,0,strlen($word)-1);
		$multiple = 1;
	}else{
		$multiple = 0;
	}

	//added by poliam 03/28/2014
	if($encounter_nr){
		$RoomTypePrice=$srv->GetRoomType($encounter_nr);
	}else{
		$RoomTypePrice=$srv->GetRoomType($encounter_nr);
	}
	//ended by poliam 03/28/2014

	//added by EJ 08/19/2014
	$requestDate = $date;
	$requestTime = $time;
	$requestDay = date("l", mktime(0, 0, 0, $month, $day, $year));

	$is_holiday = $srv->checkHoliday($requestDate);
	
	$increase = $objSS->getIncreasedValue();
	if($increase){
		while($rowprice=$increase->FetchRow()) {
			$increased_value = $rowprice['increase'];
    		}
		}
	else {
		$increased_value = 1;
	}

	$ergebnis=$srv->SearchService($source_req, $is_charge2comp, $compID, $ref_source,$is_cash,$discountid,$discount, $is_senior, $is_walkin, $sc_walkin_discount,$group_code,$codenum,$searchkey,$multiple,$maxRows,$offset,$area);
	#$objResponse->alert($srv->sql);
	$total = $srv->FoundRows();

	$lastPage = floor($total/$maxRows);

	if ((floor($total%10))==0)
		$lastPage = $lastPage-1;

	if ($page > $lastPage) $page=$lastPage;
	$rows=0;

	if ($ergebnis) {
		$rows=$ergebnis->RecordCount();
		while($result=$ergebnis->FetchRow()) {
			$name = $result["name"];
			if (strlen($name)>40)
				$name = substr($result["name"],0,40)."...";

			if ($result['status']=='unavailable')
					$available = 0;
			else
					$available = 1;

			#added by VAN 07-14-2010
			if ($area_type){
					$query4 = "SELECT IF($is_cash,p.price_cash,p.price_charge) AS net_price,
											p.price_cash, p.price_charge
											FROM seg_service_pricelist AS p
											WHERE p.service_code=".$db->qstr($result["service_code"])."
											AND p.ref_source='LB' AND p.area_code='$area_type'";
					#$objResponse->alert($query4);
					$lab_serv2 = $db->GetRow($query4);
					if ($lab_serv2){
						$result["price_cash"] = $lab_serv2["price_cash"];
						$result["price_charge"] = $lab_serv2["price_charge"];
						$result["net_price"] = $lab_serv2["net_price"];
					}else{
						$result["price_cash"] = $result["price_cash"] + ($result["price_cash"] * $pw_additional);
						$result["price_charge"] = $result["price_charge"] + ($result["price_charge"] * $pw_additional);
						$result["net_price"] = $result["net_price"] + ($result["net_price"] * $pw_additional);
					}

					#add additional charges
					if ($area_type!='pw'){
						if ($isStat){
								$result["price_cash"] = $result["price_cash"] + ($result["price_cash"] * $stat_additional);
								$result["price_charge"] = $result["price_charge"] + ($result["price_charge"] * $stat_additional);
								$result["net_price"] = $result["net_price"] + ($result["net_price"] * $stat_additional);
						}
					}else{
						if ($isStat){
								$price_cash = $result["price_cash"] + ($result["price_cash"] * $stat_additional);
								$result["price_cash"] = round($price_cash);
								$price_charge = $result["price_charge"] + ($result["price_charge"] * $stat_additional);
								$result["price_charge"] = round($price_charge);
								$net_price = $result["net_price"] + ($result["net_price"] * $stat_additional);
								$result["net_price"] = round($net_price);
						}
					}
		 }else{
			 #add additional charges
			 if ($isStat){
					$result["price_cash"] = $result["price_cash"] + ($result["price_cash"] * $stat_additional);
					$result["price_charge"] = $result["price_charge"] + ($result["price_charge"] * $stat_additional);
					$result["net_price"] = $result["net_price"] + ($result["net_price"] * $stat_additional);
			 }
		 }
            
            #get the list of child test
            if ($result['is_profile']){
                $sql_child = "SELECT fn_get_labtest_child_code(".$db->qstr($result["service_code"]).") AS childtest";
                $child_test = $db->GetOne($sql_child);
            }
          	

          	//added by EJ 08/19/2014
            	if ($srv->checkIfEmployee($pid) == TRUE || $pharma->checkDependent($pid) == TRUE) {
            		$labfree = $srv->getLaboratoryFree($result["service_code"]); 
            		if ($labfree) {{
                		$result["price_cash"] = 0;
                		$result["price_charge"] = 0;
                		$result["net_price"] = 0;
                		}
            		}
            		else {
            			$discount_price = $srv->getDiscount();
                		$result["price_cash"] = $result["price_cash"] - ($result["price_cash"]*$discount_price);
                		$result["price_charge"] = $result["price_charge"] - ($result["price_charge"]*$discount_price);
                		$result["net_price"] = $result["net_price"] -($result["net_price"]*$discount_price);

            		}
            	}
            	else if(($requestTime >='17' || $requestTime <= '7' || $requestDay == "Sunday" || $is_holiday == TRUE) && $is_notphic == 2)
    			$prices = $srv->getPriceIncrease($result["service_code"]);
    			if($prices){
    				while($rowprice=$prices->FetchRow()) {
            		$other_cash = $rowprice['cash_price'];
            		$other_charge = $rowprice['charge_price'];
            		$other_net = $rowprice['net_price'];
            		}
    			}
    			else {
          		$prices = $srv->GetRoomPrice($result["service_code"], $RoomTypePrice, $source='LB', $is_cash);
    			}
            	
            	if($prices){
            		while($rowprice=$prices->FetchRow()) {
            		$other_cash = $rowprice['cash_price'];
            		$other_charge = $rowprice['charge_price'];
            		$other_net = $rowprice['net_price'];
            		}
            	}
            	
            	if($prices){

		           	$data[] = array(
		           		'id'=>trim($result['service_code']),
                       	'description' => trim($name),
                       	'label' => trim($result['service_code'])." ".trim($name),
                       	'value' => '',
						'list' => 'request-list',
						'service_code' => $result["service_code"],
						'name' => $name,
						'idGrp' => $result["group_code"],
						'code_num' => $result["code_num"],
						'codenum' => $codenum,
						'prcCash' => number_format($other_cash, 2, '.', ''),
						'prcCharge' => number_format($other_charge, 2, '.', ''),
						'is_socialized' => $result['is_socialized'],
						'in_lis' => $result['in_lis'],
						'oservice_code' => $result['oservice_code'],
						'ipdservice_code' => $result['ipdservice_code'],
						'net_price' => number_format($other_net, 2, '.', ''),
						'available' => $available,
						'is_blood_product' => $result['is_blood_product'],
						'is_package' => $result['is_package'],
						'is_profile' => $result['is_profile'],
						'incprCepPHIC' => $INCPHICaddtional,
						'incprCepNONPHIC' => $INCNONPHICaddtional,
						'hasrooms' => $Ifhasroom,
						'child_test' => $child_test
					);

            	}else{
            		if($encounter_nr){
            			$data[] = array(
            						'id'=>trim($result['service_code']),
		                           	'description' => trim($name),
		                           	'label' => trim($result['service_code'])." ".trim($name),
		                           	'value' => '',
            						'list' => 'request-list',
            						'service_code' => $result["service_code"],
            						'name' => $name,
            						'idGrp' => $result["group_code"],
            						'code_num' => $result["code_num"],
            						'codenum' => $codenum,
            						'prcCash' => number_format($result["price_cash"], 2, '.', ''),
            						'prcCharge' => number_format($result["price_charge"], 2, '.', ''),
            						'is_socialized' => $result['is_socialized'],
            						'in_lis' => $result['in_lis'],
            						'oservice_code' => $result['oservice_code'],
            						'ipdservice_code' => $result['ipdservice_code'],
            						'net_price' => number_format($result['net_price'], 2, '.', ''),
            						'available' => $available,
            						'is_blood_product' => $result['is_blood_product'],
            						'is_package' => $result['is_package'],
            						'is_profile' => $result['is_profile'],
            						'incprCepPHIC' => $INCPHICaddtional,
						'incprCepNONPHIC' => $INCNONPHICaddtional,
						'hasrooms' => $Ifhasroom,
            						'child_test' => $child_test
            					);
            		}else{
            			//modified by EJ 08/19/2014
            			if($requestTime >='17' || $requestDay == "Sunday" || $is_holiday == TRUE)
            			$prices_more = $srv->getPriceIncrease($result["service_code"]);
            			if($prices_more){
            				while($rowprice=$prices_more->FetchRow()) {
	                		$other_cash = $rowprice['cash_price'];
	                		$other_charge = $rowprice['charge_price'];
	                		$other_net = $rowprice['net_price'];
	                		}
            			}

            			if($prices_more){

            				$data[] = array(
            						'id'=>trim($result['service_code']),
		                           	'description' => trim($name),
		                           	'label' => trim($result['service_code'])." ".trim($name),
		                           	'value' => '',
            						'list' => 'request-list',
            						'service_code' => $result["service_code"],
            						'name' => $name,
            						'idGrp' => $result["group_code"],
            						'code_num' => $result["code_num"],
            						'codenum' => $codenum,
            						'prcCash' => number_format($other_cash, 2, '.', ''),
            						'prcCharge' => number_format($other_charge, 2, '.', ''),
            						'is_socialized' => $result['is_socialized'],
            						'in_lis' => $result['in_lis'],
            						'oservice_code' => $result['oservice_code'],
            						'ipdservice_code' => $result['ipdservice_code'],
            						'net_price' => number_format($other_net, 2, '.', ''),
            						'available' => $available,
            						'is_blood_product' => $result['is_blood_product'],
            						'is_package' => $result['is_package'],
            						'is_profile' => $result['is_profile'],
            						'incprCepPHIC' => $INCPHICaddtional,
						'incprCepNONPHIC' => $INCNONPHICaddtional,
						'hasrooms' => $Ifhasroom,
            						'child_test' => $child_test
            					);

            			}else{
            				$data[] = array(
		            				'id'=>trim($result['service_code']),
		                           	'description' => trim($name),
		                           	'label' => trim($result['service_code'])." ".trim($name),
		                           	'value' => '',
            						'list' => 'request-list',
            						'service_code' => $result["service_code"],
            						'name' => $name,
            						'idGrp' => $result["group_code"],
            						'code_num' => $result["code_num"],
            						'codenum' => $codenum,
            						'prcCash' => number_format($result["price_cash"], 2, '.', ''),
            						'prcCharge' => number_format($result["price_charge"], 2, '.', ''),
            						'is_socialized' => $result['is_socialized'],
            						'in_lis' => $result['in_lis'],
            						'oservice_code' => $result['oservice_code'],
            						'ipdservice_code' => $result['ipdservice_code'],
            						'net_price' => number_format($result['net_price'], 2, '.', ''),
            						'available' => $available,
            						'is_blood_product' => $result['is_blood_product'],
            						'is_package' => $result['is_package'],
            						'is_profile' => $result['is_profile'],
            						'incprCepPHIC' => $INCPHICaddtional,
						'incprCepNONPHIC' => $INCNONPHICaddtional,
						'hasrooms' => $Ifhasroom,
            						'child_test' => $child_test
            					);
            				
            			}
            		}
            }
		}#end of while
	} #end of if

 	$json = new Services_JSON;

    echo $json->encode($data);
