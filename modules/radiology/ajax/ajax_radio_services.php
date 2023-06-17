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
	require_once($root_path.'include/care_api_classes/class_radiology.php');
    require "{$root_path}classes/json/json.php";

    global $db;
    
    $glob_obj = new GlobalConfig($GLOBAL_CONFIG);
	$glob_obj->getConfig('pagin_patient_search_max_block_rows');
	$maxRows = $GLOBAL_CONFIG['pagin_patient_search_max_block_rows'];

	$srv=new SegSpecialLab();
	$pharma=new SegPharmaProduct();
	$objSS = new SocialService;
	$radio_obj = new SegRadio();
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
	if(!empty($insurance_type))
	{

			$INCPHICaddtional =".".$pharma->insurancePHIC($room_types,$insurance_type);
			$INCNONPHICaddtional = ".".$pharma->insuranceNONPHIC($room_types);
			$Ifhasroom = $room_types ? 1 : 0;		
		
	}
	else{
		$INCPHICaddtional =0;
		$INCNONPHICaddtional =0;
		$Ifhasroom = 0;
	}

		
	

			if (!$discount)
			$discount = 0;

		$ssInfo = $objSS->getSSClassInfo($discountid);

		//commented for senior toggle 4/23/2014
		// if (($discountid=='SC')&& ($is_senior))
		// 	$is_senior = 1;
		// else
		// 	$is_senior = 0;

		// if ($discountid!='SC'){
		// if ($ssInfo['parentid'])
		// 	$discountid = $ssInfo['parentid'];
		// }

		$sc_walkin_discount = 0;
		#if ((($is_senior) && ($is_walkin)) || ((($is_senior)&&($is_cash==0)))){
		if (($is_senior) && ($is_walkin)){
			$discountid='SC';

			$sql_sc = "SELECT * FROM seg_default_value WHERE name='senior discount' AND source='SS'";
			$rs_sc = $db->Execute($sql_sc);
			$row_sc = $rs_sc->FetchRow();

			if ($row_sc['value'])
				$sc_walkin_discount = $row_sc['value'];
		}

		if ($isStat){
			$sql_stat = "SELECT * FROM seg_default_value WHERE name='stat charge' AND source IS NULL";
			$rs_stat = $db->Execute($sql_stat);
			$row_stat = $rs_stat->FetchRow();

			if ($row_stat['value'])
				$stat_additional = $row_stat['value'];
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
		
		//added by EJ 08/19/2014
		$requestDate = $date;
		$requestTime = $time;
		$requestDay = date("l", mktime(0, 0, 0, $month, $day, $year));
		
		$is_holiday = $radio_obj->checkHoliday($requestDate);

		$ergebnis=$radio_obj->SearchService2($source_req, $is_charge2comp, $compID, $dept_nr,$is_cash,$discountid,$discount, $is_senior, $is_walkin, $sc_walkin_discount,$codenum,$searchkey,$multiple,$maxRows,$offset,$area,$sect);

		$total = $radio_obj->FoundRows();

		$lastPage = floor($total/$maxRows);

		if ((floor($total%10))==0)
			$lastPage = $lastPage-1;

		if ($page > $lastPage) $page=$lastPage;
		$rows=0;

		//added by ken 3/28/2014
		$room_type = $radio_obj->getRoomType($enc);
		if(!$room_type)
			if(strtotime(date('H:i:s'))>=mktime(17,0,0) || strtotime(date('H:i:s'))<=mktime(7,0,0))
				$room_type = '3';
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
				//added by ken 3/28/2014
				if($room_type){
					$other_rates = $radio_obj->getServiceRates($room_type, $result['service_code'], 'RD', $is_cash);
					if($other_rates){
						$result["price_cash"] = $other_rates["cash_price"];
						$result["price_charge"] = $other_rates["charge_price"];
						$result["net_price"] = $other_rates["net_price"];
					}
					if($is_senior == 'true'){
						$sql_sc = "SELECT * FROM seg_default_value WHERE name='senior discount' AND source='SS'";
						$rs_sc = $db->Execute($sql_sc);
						$row_sc = $rs_sc->FetchRow();

						if ($row_sc['value'])
							$result["net_price"] = $result["net_price"] - ($result["net_price"] * $row_sc['value']);
					}
				}

				#added by VAN 07-14-2010
				if ($area_type){
						$query4 = "SELECT IF($is_cash,p.price_cash,p.price_charge) AS net_price,
												p.price_cash, p.price_charge
												FROM seg_service_pricelist AS p
												WHERE p.service_code=".$db->qstr($result["service_code"])."
												AND p.ref_source='RD' AND p.area_code='$area_type'";
					
						$radio_serv2 = $db->GetRow($query4);
						if ($radio_serv2){
							$result["price_cash"] = $radio_serv2["price_cash"];
							$result["price_charge"] = $radio_serv2["price_charge"];
							$result["net_price"] = $radio_serv2["net_price"];
						}

						#add additional charges
						if ($area_type!='pw'){
							if ($isStat){
									$result["price_cash"] = $result["price_cash"] + ($result["price_cash"] * $stat_additional);
									$result["price_charge"] = $result["price_charge"] + ($result["price_charge"] * $stat_additional);
									$result["net_price"] = $result["net_price"] + ($result["net_price"] * $stat_additional);
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
			 	if(($requestTime >='17' || $requestDay == "Sunday" || $is_holiday == TRUE) && $is_notphic == 2) {
			 		$other_rates = $radio_obj->getPriceIncrease($result['service_code']);
					if($other_rates){
						$result["price_cash"] = $other_rates["cash_price"];
						$result["price_charge"] = $other_rates["charge_price"];
						$result["net_price"] = $other_rates["net_price"];
					}
			 	}

		 		$data[] = array(
	           		'id'=>trim($result['service_code']),
                   	'description' => trim($name),
                   	'label' => trim($result['service_code'])." ".trim($name),
                   	'value' => '',
					'list' => 'request-list',
					'service_code' => $result["service_code"],
					'name' => $name,
					'idGrp' => $result["group_code"],
					'dept' => $result["dept_name"],
					'prcCash' => number_format($result["price_cash"], 2, '.', ''),
					'prcCharge' => number_format($result["price_charge"], 2, '.', ''),
					'is_socialized' => $result['is_socialized'],
					'in_lis' => $result['in_lis'],
					'oservice_code' => $result['oservice_code'],
					'ipdservice_code' => $result['ipdservice_code'],
					'net_price' => number_format($result['net_price'], 2, '.', ''),
					'incprCePHIC' => $INCPHICaddtional,
					'incprCeNONPHIC' => $INCNONPHICaddtional,
					'hasroom' => $Ifhasroom,
					'available' => $available
				);
				
			}#end of while
		} #end of if

 	
 	$json = new Services_JSON;
    echo $json->encode($data);

