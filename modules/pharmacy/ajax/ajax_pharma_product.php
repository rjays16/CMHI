<?php
	#Created by Jarel 10/07/2014
	require('./roots.php');
	require($root_path.'include/inc_environment_global.php');
	require($root_path.'include/care_api_classes/class_pharma_product.php');
	require($root_path.'include/care_api_classes/class_discount.php');
    require "{$root_path}classes/json/json.php";

	global $db, $config;
    

	$keyword = $_GET['term'];
	$pid= $_GET['pid'];
	$discountID=$_GET['discountid'];
	$area=$_GET['area'];
	$disable_qty=false;

	$getencounter = "SELECT encounter_nr from care_encounter where pid=".$db->qstr($pid)."ORDER BY encounter_nr DESC LIMIT 1";
	$rs_stat = $db->Execute($getencounter);
		$row_stat = $rs_stat->FetchRow();

		if ($row_stat['encounter_nr'])
			$encounter_nr = $row_stat['encounter_nr'];



	$dbtable='care_pharma_products_main';
	$prctable = 'seg_pharma_prices';
	$pc = new SegPharmaProduct();

	
	$insurance_type = $pc->getinsrnceTyp($encounter_nr);
	$room_types=$pc->getRoomTypes($encounter_nr);
	if(!empty($insurance_type))
	{	
		
			$INCPHICaddtional =".".$pc->insurancePHIC($room_types,$insurance_type);
			$INCNONPHICaddtional = ".".$pc->insuranceNONPHIC($room_types);
			$Ifhasroom = $room_types ? 1 : 0;
	}else
	{
		$INCPHICaddtional =0;
		$INCNONPHICaddtional =0;
		$Ifhasroom = 0;
	}
	
	
	$maxRows = 10;
	$offset = $page * $maxRows;

	$ergebnis = $pc->search_products_for_tray($keyword, $discountID, $area, $offset, $maxRows);

	if ($ergebnis) {
		$total = $pc->FoundRows();
		$lastPage = floor($total/$maxRows);
		if ($page > $lastPage) $page=$lastPage;

		$rows=$ergebnis->RecordCount();

		while($result=$ergebnis->FetchRow()) {

			//added by EJ 08/26/2014
			/*if ($discountID == 'PHS' || $pc->checkDependent($pid) == TRUE) {
				$phsdiscount = $pc->getPhsDiscounts($result["bestellnum"]);
    			if($phsdiscount){
    				while($rowprice=$phsdiscount->FetchRow()) {
            		$result["cshrpriceppk"] = $rowprice["price"];
					$result["chrgrpriceppk"] = $rowprice["price"];
					$result["dprice"] = $rowprice["price"];
            		}
    			}
				else if ($phsdiscount == null) {
					$result["cshrpriceppk"] = 0;
					$result["chrgrpriceppk"] = 0;
					$result["cashscprice"] = 0;
					$result["chargescprice"] = 0;
					$result["dprice"] = 0;
				}
				
			}*/

			$data[] = array(
               	'description' => trim($result["generic"]),
               	'label' => trim($result["artikelname"]),
               	'value' => '',
				'id' => $result["bestellnum"],
				'name' => $result["artikelname"],
				'desc' => $result["generic"],
				'prcCash' => $result["cshrpriceppk"],
				'prcCharge' => $result["chrgrpriceppk"],
				'prcCashSC' => $result["cashscprice"],
				'prcChargeSC' => $result["chargescprice"],
				'prcDiscounted' => $result["dprice"],
				'isSocialized' => $result["is_socialized"],
				'noqty' => ($disable_qty==1) ? TRUE : FALSE,
				'qty'=> 1,
				'priceincreaseperoomPHIC'=>$INCPHICaddtional,
				'priceincreaseperoomNONPHIC'=>$INCNONPHICaddtional,
				'Ifhasroom'=>$Ifhasroom,
				'restricted' => $result["is_restricted"]
			 );

		}
	}

	$json = new Services_JSON;
    echo $json->encode($data);

