<?php




	#Created by Jarel 10/07/2014
	require('./roots.php');
	require($root_path.'include/inc_environment_global.php');
	require_once($root_path.'include/care_api_classes/class_cashier_service.php');
	require_once($root_path.'include/care_api_classes/class_pharma_product.php'); //added by julius
    require "{$root_path}classes/json/json.php";

	global $db;
	$csClass = new SegCashierService();
	$pharma = new SegPharmaProduct();
	$keyword = $_GET['term'];
	$encounter_nr = $_GET['enc'];
	
	$insurance_type = $pharma->getinsrnceTyp($encounter_nr);
	$room_types=$pharma->getRoomTypes($encounter_nr);

	if(!empty($room_types))
	{	
			$INCNONPHICaddtional = ".".$pharma->insuranceNONPHIC($room_types);
	}
	else
	{
			$INCNONPHICaddtional = 0;
	}
	

	$maxRows = 25;
	$offset = $page * $maxRows;

	$ergebnis = $csClass->searchServices($keyword, $type, FALSE, $offset, $maxRows, 's.name,s.price');

	while($result=$ergebnis->FetchRow()) {
		$data[] = array(
			'id' => $result["alt_code"],
			'name' => $result['name'],
			'label' => trim($result["alt_code"])."   ".trim($result['name']),
			'value' => '',
			'lock' => $result['is_not_socialized'],
			'desc' => $result['name_short'],
			'price' => ($result['price'] * $INCNONPHICaddtional) + $result['price'], 
			'origprice' => $result['price'],
			'dept_name' => $result['dept_name']
			);
	}

	$json = new Services_JSON;
    echo $json->encode($data);
?>
