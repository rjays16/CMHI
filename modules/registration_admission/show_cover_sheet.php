<?php
	#edited by VAN 05-01-08
	include("roots.php");
	include_once($root_path."/classes/fpdf/fpdf.php");
	#include_once($root_path."/classes/fpdf/pdf.class.php"); commented by art 01/18/2014
	include_once($root_path."/classes/fpdf/footer.php"); #added by art 01/18/2014
	require_once($root_path.'include/inc_environment_global.php');
	include_once($root_path.'include/inc_date_format_functions.php');

	require_once($root_path.'/include/care_api_classes/class_drg.php');
	$objDRG= new DRG;

	include_once($root_path.'include/care_api_classes/class_encounter.php');
	$enc_obj=new Encounter;
	
	require_once($root_path.'include/care_api_classes/class_hospital_admin.php');
	$objInfo = new Hospital_Admin();
	
	//added by mai 09-24-2014
	require_once($root_path.'include/care_api_classes/class_company.php');
	$comp_obj = new Company();
	//end added by mai

#	include_once($root_path.'include/care_api_classes/class_personell.php');
#	$personell_obj=new Personell;

	if ($_GET['encounter_nr']) {
		if (!($enc_info = $enc_obj->getEncounterInfo($_GET['encounter_nr']))){
			#echo "enc_obj->sql = '".$enc_obj->sql."' <br> \n";		
			echo '<em class="warn">Sorry but the page cannot be displayed!</em>';
			exit();
		}
		#echo "enc_obj->sql = '".$enc_obj->sql."' <br> \n";		
		extract($enc_info);
		#$personell_obj->getPersonellInfo($referrer_dr);
	}else{
			echo '<em class="warn">Sorry but the page cannot be displayed! <br>Invalid Case Number! </em>';
			exit();	
	}
	#echo "enc_obj->sql = '".$enc_obj->sql."' <br> \n";
	#print_r($enc_info);
	$border="1";
	$border2="0";
	$space=2;
	#$fontSizeLabel=9;
	$fontSizeName=15;
	$fontSizeLabel=10;
	$fontSizeInput=12;
	$fontSizeHeading=13;

	//Cell(float w [, float h [, string txt [, mixed border [, int ln [, string align [, int fill [, mixed link]]]]]]])
	#$pdf = new FPDF();
	$pdf = new PDF();
	$pdf = new PDF("P",'mm',array(215.9, 330.2));
	$pdf->AliasNbPages();   #--added
	#$pdf->SetAutoPageBreak('true','10');
	$pdf->AddPage("P");
	#$pdf->SetFont("Arial","B",$fontSizeLabel-2);
	#$pdf->Cell(0,3,'MRFI 01-1',$border2,1,'R');	
	/*
	#$pdf->SetFont("Arial","","10");
	$pdf->SetFont("Arial","","11");
	$pdf->Cell(0,4,'Republic of the Philippines',$border2,1,'C');
	#$pdf->SetFont("Arial","","11");
	$pdf->Cell(0,4,'DEPARTMENT OF HEALTH',$border2,1,'C');

	$pdf->Ln(1);
	$pdf->SetFont("Arial","B","12");
	$pdf->Cell(0,4,'DAVAO MEDICAL CENTER',$border2,1,'C');

	#$pdf->SetFont("Arial","","10");
	$pdf->SetFont("Arial","","11");
	$pdf->Cell(0,4,'Bajada, Davao City',$border2,1,'C');
	*/
	if ($row = $objInfo->getAllHospitalInfo()) {			
		$row['hosp_agency'] = strtoupper($row['hosp_agency']);
		$row['hosp_name']   = strtoupper($row['hosp_name']);
		$row['hosp_addr1'] = "PANABO CITY";
	}
	else {
		$row['hosp_country'] = "Republic of the Philippines";
		$row['hosp_agency']  = "DEPARTMENT OF HEALTH";
		$row['hosp_name']    = "CAINGLET MEDICAL HOSPITAL INC.";
		$row['hosp_addr1']   = "PANABO CITY";		
	}

	//added by art 01/18/2014
	//$pdf->Image('image/logo_doh.jpg',25,10,20,20);
	//$pdf->Image('image/cmhi_logo.jpg',170,10,20,20);
	$pdf->Image('image/cmhi_logo.jpg',25,10,20,20);
	//end art

	// $pdf->SetFont("Arial","","11");
	// $pdf->Cell(0,4,$row['hosp_country'],$border2,1,'C');
	// #$pdf->SetFont("Arial","","11");
	// $pdf->Cell(0,4,$row['hosp_agency'],$border2,1,'C');
	$pdf->Ln(1);
	$pdf->Ln(1);
	$pdf->Ln(1);
	$pdf->SetFont("Arial","B","12");
	$pdf->Cell(0,4,$row['hosp_name'],$border2,1,'C');

	$pdf->SetFont("Arial","","11");
	$pdf->Cell(0,4,$row['hosp_addr1'],$border2,1,'C');
	
	$pdf->SetFont('Arial','B',"13");
	$pdf->Cell(0,5,'Clinical Cover Sheet',$border2,1,'C');

	$pdf->Ln($space*7.5);
	$pid2 = $pid;
	$pid2[0] = 0;
 	$pdf->SetFont('Arial','',$fontSizeLabel+2);
	$pdf->Cell(15,4,'HRN : ',"$border2",0,'L');
	$pdf->SetFont('Arial','B',$fontSizeHeading+5);
	$pdf->Cell(15,4,$pid,"$border2",0,'L');
	$pdf->SetFont('Arial','',$fontSizeLabel+3);
	$pdf->Cell(50,4,'Case No. : ',"$border2",0,'R');
	$pdf->SetFont('Arial','B',$fontSizeHeading+5);
	$pdf->Cell(40,4	,$encounter_nr,"$border2",0,'L');
	
	global $db;
	
	$year = substr($encounter_nr,0,4);
	
	$sql = "SELECT extract(YEAR FROM encounter_date) AS years, pid, count(encounter_nr) AS no_trxn
					FROM care_encounter as e 
					WHERE encounter_type IN (3,4)
					AND pid='".$pid."'
					AND extract(YEAR FROM encounter_date)='".$year."'
					AND status NOT IN ('deleted','hidden','inactive','void')
					GROUP BY pid, extract(YEAR FROM encounter_date)";
				
	$rs = $db->Execute($sql);
	if ($rs){
			$row = $rs->FetchRow();
			$no_trxn_ipd = $row['no_trxn'];
	}
	
	$pdf->SetFont('Arial','',"8");
	$pdf->Cell(5,4,"(".$no_trxn_ipd.")","$border2",0,'L');
		
	$pdf->SetFont('Arial','B',"9");
	#ward_name
    
    $ward = mb_strtoupper($ward_id)." Room ".$current_room_nr;
	#$pdf->Cell(60,4,mb_strtoupper($ward_name),"0",1,'R');
    $pdf->Cell(60,4,$ward,"0",1,'R');
	#$pdf->Cell(30,4,'CHARITY',"$border2",0,'R');
	#$pdf->Cell(10,4	,'Ward',"$border2",1,'L');
	$pdf->Ln($space);
	
	$pdf->SetFont('Arial','I',$fontSizeLabel);
	$pdf->Cell(48,4,'Last Name',"TR",0,'L');
	$pdf->Cell(48,4,'First Name',"TLR",0,'L');
	$pdf->Cell(48,4,'Middle Name',"TLR",0,'L');
	$pdf->Cell(48,4,'Maiden Name',"TL",0,'L');
	
	$pdf->Ln();	
	
	$x = $pdf->GetX();
	$y = $pdf->GetY();
	
	$pdf->SetFont('Arial','B',$fontSizeName);
	$pdf->Cell(48,12,'',"RB",0,'L');
	$pdf->Cell(48,12,'',"LRB",0,'L');
	$pdf->Cell(48,12,'',"LRB",0,'L');
	$pdf->Cell(48,12,'',"LB",0,'L');
	
	$pdf->SetXY($x, $y);
	$pdf->MultiCell(47, 4, mb_strtoupper($name_last), '', 'L','');
	
	$pdf->SetXY($x+48, $y);
	$pdf->MultiCell(47, 4, mb_strtoupper($name_first), '', 'L','');
	
	$pdf->SetXY($x+96, $y);
	$pdf->MultiCell(47, 4, mb_strtoupper($name_middle), '', 'L','');
	
	$pdf->SetXY($x+144, $y);
	$pdf->MultiCell(47, 4, mb_strtoupper($name_maiden), '', 'L','');
	
	$pdf->SetY($y+12);
	#$pdf->Ln($space*2);
	$pdf->SetFont('Arial','I',$fontSizeLabel);
	$pdf->Cell(10,8,'Age : ',"",0,'L');
	$pdf->SetFont('Arial','',$fontSizeLabel);
	#if ($age)
	#	$age = $age." old ";
	/*
	edited by mai 06-20-2014
	if (stristr($age,'years')){
		$age = substr($age,0,-5);
		if ($age>1)
			$labelyear = "years";
		else
			$labelyear = "year";
				
		$age = floor($age)." ".$labelyear;
	}elseif (stristr($age,'year')){	
		$age = substr($age,0,-4);
		if ($age>1)
			$labelyear = "years";
		else
			$labelyear = "year";
			
		$age = floor($age)." ".$labelyear;
		
	}elseif (stristr($age,'months')){	
		$age = substr($age,0,-6);
		if ($age>1)
			$labelmonth = "months";
		else
			$labelmonth = "month";
			
		$age = floor($age)." ".$labelmonth;	
		
	}elseif (stristr($age,'month')){	
		$age = substr($age,0,-5);
		
		if ($age>1)
			$labelmonth = "months";
		else
			$labelmonth = "month";
			
		$age = floor($age)." ".$labelmonth;		
		
	}elseif (stristr($age,'days')){	
		$age = substr($age,0,-4);
					
		if ($age>30){
			$age = $age/30;
			if ($age>1)
				$label = "months";
			else
				$label = "month";
			
		}else{
			if ($age>1)
				$label = "days";
			else
				$label = "day";
		}
						
		$age = floor($age).' '.$label;	
			
	}elseif (stristr($age,'day')){	
		$age = substr($age,0,-3);
		
		if ($age>1)
			$labelday = "days";
		else
			$labelday = "day";
			
		$age = floor($age)." ".$labelday;		
	}else{
		if ($age){
			if ($age>1)
				$labelyear = "years";
			else
				$labelyear = "year";
			
			$age = $age." ".$labelyear;
		}else
			$age = "0 day";	
	}
	end edited by mai
	*/
	
	$pdf->Cell(38, 8, $age, '', 0,'L');
	
	$pdf->SetFont('Arial','I',$fontSizeLabel);
	$pdf->Cell(10,8,'Sex : ',"",0,'L');
	$pdf->SetFont('Arial','',$fontSizeLabel);
	
	if ($sex=='f')
		$gender = 'FEMALE';
	elseif ($sex=='m')
		$gender = 'MALE';	
	
	$pdf->Cell(38, 8, mb_strtoupper($gender), '', 0,'L');
	
	$pdf->SetFont('Arial','I',$fontSizeLabel);
	$pdf->Cell(20,8,'Civil Status : ',"",0,'L');
	$pdf->SetFont('Arial','',$fontSizeLabel);
	$pdf->Cell(28, 8, mb_strtoupper($civil_status), '', 0,'L');
	
	$pdf->SetFont('Arial','I',$fontSizeLabel);
	$pdf->Cell(25,8,'Contact No. : ',"",0,'L');
	$pdf->SetFont('Arial','',$fontSizeLabel);
	
	$contact = $phone_1_nr;
	if (!isset($contact) || empty($contact)) $contact = $cellphone_1_nr;
	if (!isset($contact) || empty($contact)) $contact = $phone_2_nr;
	if (!isset($contact) || empty($contact)) $contact = $cellphone_2_nr;
	
	$pdf->Cell(22, 8, $contact_no, '', 1,'L');
	
	$pdf->SetY($y+17);
	
	$pdf->SetFont('Arial','I',$fontSizeLabel);
	$pdf->Cell(20,8,'Address : ',"",0,'L');
	
	$pdf->SetFont('Arial','I',$fontSizeLabel);
	
	$x = $pdf->GetX();
	$y = $pdf->GetY();
	
	$pdf->SetFont('Arial','',$fontSizeLabel);
	$pdf->SetXY($x-2, $y+2);
	/*
	if ($street_name)
		$street_name = "$street_name, ";
	else
		$street_name = "";	
	
	#$address = "$street_name $brgy_name, $mun_name $zipcode $prov_name";
	if (!($brgy_name))
		$brgy_name = "NOT PROVIDED";
	
	#$address = trim($street_name)." ".trim($brgy_name).", ".trim($mun_name)." ".trim($zipcode)." ".trim($prov_name);
	if(stristr(trim($mun_name), 'city') === FALSE){
		if (!empty($mun_name)){
			$province = ", ".trim($prov_name);
		}else{
			$province = trim($prov_name);;
		}
	}	
	
	$address = trim($street_name)." ".trim($brgy_name).", ".trim($mun_name)." ".$province;
	*/
	
	if ($street_name){
		if ($brgy_name!="NOT PROVIDED")
			$street_name = $street_name.", ";
		else
			$street_name = $street_name.", ";	
	}#else
		#$street_name = "";	
				
				
		
	if ((!($brgy_name)) || ($brgy_name=="NOT PROVIDED"))
		$brgy_name = "";
	else 
		$brgy_name  = $brgy_name.", ";	
					
	if ((!($mun_name)) || ($mun_name=="NOT PROVIDED"))
		$mun_name = "";		
	else{	
		if ($brgy_name)
			$mun_name = $mun_name;	
		#else
			#$mun_name = $mun_name;		
	}			
	
	if ((!($prov_name)) || ($prov_name=="NOT PROVIDED"))
		$prov_name = "";		
	#else
	#	$prov_name = $prov_name;			
				
	if(stristr(trim($mun_name), 'city') === FALSE){
		if ((!empty($mun_name))&&(!empty($prov_name))){
			if ($prov_name!="NOT PROVIDED")	
				$prov_name = ", ".trim($prov_name);
			else
				$prov_name = "";	
		}else{
			#$province = trim($prov_name);
			$prov_name = "";
		}
	}else
		$prov_name = " ";	
				
	$address = $street_name.$brgy_name.$mun_name.$prov_name;
	
	$pdf->MultiCell(170, 4,mb_strtoupper($address), '0', 'L','');
	#$pdf->Cell(50,8,'',"L",0,'L');
	#$pdf->Cell(25,8,'PHIC member/beneficiaries : ',"L",0,'L');
	
	$pdf->SetXY($x+120, $y+2);
	
	#if ($firm_id)
	#echo "str = ".stristr($firm_id,'Philam');
	if ((stristr($firm_id,'PhilHealth')) || (stristr($firm_id,'PHIC')))
		$ismember = 1;
	else
		$ismember = 0;	
		
	if ($ismember)
		$pdf->MultiCell(50, 4,'PHIC member/beneficiary', '0', 'R','');
	else
		$pdf->MultiCell(50, 4,'', '', 'R','');	
	
	$pdf->Ln($space);
	#$x = $pdf->GetX();
	#$y = $pdf->GetY();
	#$pdf->SetXY($x, $y);
	$pdf->SetY($y+5);
	
	$pdf->SetFont('Arial','I',$fontSizeLabel);
	$pdf->Cell(55,8,'Country of Nationality : ',"",0,'L');
	$pdf->Cell(55,8,'Religion : ',"",0,'L');
	$pdf->Cell(55,8,'Occupation : ',"",0,'L');
	$pdf->Ln($space*2);
	
	$pdf->SetFont('Arial','',$fontSizeLabel);
	$pdf->Cell(55, 8, mb_strtoupper($citizenship), '', 0,'L');
	$pdf->Cell(55, 8, mb_strtoupper($religion), '', 0,'L');
	$pdf->Cell(55, 8, mb_strtoupper($occupation), '', 0,'L');
	
	$pdf->Ln($space);
	$pdf->SetY($y+13);
	$pdf->SetFont('Arial','I',$fontSizeLabel);
	$pdf->Cell(55,8,'Birth Place : ',"",0,'L');
	$pdf->Cell(55,8,'Birth Date : ',"",0,'L');
	$pdf->Cell(55,8,'Department : ',"",0,'L');
	
	$pdf->Ln($space*3);
	
	$pdf->SetFont('Arial','',$fontSizeLabel);
	#$pdf->Cell(55, 4, mb_strtoupper($place_birth), '', 0,'L');
	$x = $pdf->GetX();
	$y = $pdf->GetY();
	
	$pdf->SetXY($x, $y);
	
	$pdf->MultiCell(55, 4,mb_strtoupper($place_birth), '', 'L','');
	
	$pdf->SetXY($x+55, $y);
	if ($date_birth)
		#$pdf->Cell(55,4,@formatDate2Local($date_birth,$date_format),"",0,'L');
		$pdf->MultiCell(50, 4,@formatDate2Local($date_birth,$date_format), '', 'L','');
	else
		#$pdf->Cell(55,4,'',"",0,'L');
		$pdf->MultiCell(50, 4,'', '', 'L','');
		
	$pdf->SetXY($x+110, $y);	
	#$pdf->Cell(55, 4, mb_strtoupper($name_formal), '', 0,'L');
	$pdf->MultiCell(80, 4,mb_strtoupper($name_formal), '', 'L','');
	
	$pdf->Ln($space);
	
	$pdf->SetFont('Arial','I',$fontSizeLabel);
	$pdf->Cell(70,8,'Name of Father : ',"T",0,'L');
	$pdf->Cell(70,8,'Name of Mother : ',"T",0,'L');
	$pdf->Cell(52,8,'Name of Guardian :',"T",0,'L');
	
	$pdf->Ln($space*3);
	
	$x = $pdf->GetX();
	$y = $pdf->GetY();
	
	$pdf->SetXY($x, $y);
	
	$pdf->SetFont('Arial','',$fontSizeLabel);
	
	$FmiddleInitial = "";
		if (trim($father_mname)!=""){
			$thisMI=split(" ",$father_mname);	
			foreach($thisMI as $value){
				if (!trim($value)=="")
					$FmiddleInitial .= $value[0];
			}
			if (trim($FmiddleInitial)!="")
				$FmiddleInitial = " ".$FmiddleInitial.".";
		}
	
	$father_name = $father_fname." ".$FmiddleInitial." ".$father_lname;
	$pdf->MultiCell(68, 4,mb_strtoupper($father_name), '', 'L','');
	
	$pdf->SetXY($x+70, $y);	
	#$pdf->Cell(55, 4, mb_strtoupper($name_formal), '', 0,'L');
	#added by VAN 05-20-08
	$MmiddleInitial = "";
		if (trim($mother_mname)!=""){
			$thisMI=split(" ",$mother_mname);	
			foreach($thisMI as $value){
				if (!trim($value)=="")
					$MmiddleInitial .= $value[0];
			}
			if (trim($MmiddleInitial)!="")
				$MmiddleInitial = " ".$MmiddleInitial.".";
		}
	
	$mother_name = $mother_fname." ".$MmiddleInitial." ".$mother_lname;
	$pdf->MultiCell(68, 4,mb_strtoupper($mother_name), '', 'L','');
	
	$pdf->SetXY($x+140, $y);	
	#$pdf->Cell(55, 4, mb_strtoupper($name_formal), '', 0,'L');
	$pdf->MultiCell(55, 4,mb_strtoupper($guardian_name), '', 'L','');
	
	$pdf->Ln($space);
	
	$pdf->SetY($y+5);
	
	$pdf->SetFont('Arial','I',$fontSizeLabel);
	$pdf->Cell(70,8,'Name of Spouse : ',"",0,'L');
	$pdf->Cell(70,8,'Company : ',"",0,'L');
	$pdf->Cell(52,8,'MSS CLASS : ',"",0,'L');
	

	$pdf->Ln($space);
	
	$x = $pdf->GetX();
	$y = $pdf->GetY();
	
	$pdf->SetXY($x, $y+4);
	
	$pdf->SetFont('Arial','',$fontSizeLabel);
	$pdf->MultiCell(68, 4,mb_strtoupper($spouse_name), '', 'L','');
	
	$pdf->SetXY($x+70, $y+4);	
	#$pdf->Cell(55, 4, mb_strtoupper($name_formal), '', 0,'L');
	$pdf->MultiCell(68, 4,mb_strtoupper($comp_obj->getCompanyName($encounter_nr)), '', 'L','');
	
	$pdf->SetXY($x+140, $y+4);	
	#$pdf->Cell(55, 4, mb_strtoupper($name_formal), '', 0,'L');
	#$pdf->MultiCell(52, 4,$mss_id ." (".mb_strtoupper($mss_class).")", '', 'L','');
	$pdf->SetFont('Arial','',$fontSizeLabel+2);
	$pdf->MultiCell(55, 4,'     '.$mss_id, '', 'L','');
	
	$pdf->Ln($space);
	$pdf->SetY($y+10);
	
	$pdf->SetFont('Arial','I',$fontSizeLabel);
	$pdf->Cell(70,8,'Informant\'s Name : ',"",0,'L');
	#$pdf->Cell(70,8,'Informant\'s Address : ',"",0,'L');
	$pdf->Cell(70,8,'Relation to Patient : ',"",0,'L');
	$pdf->Cell(52,8,'Hospitalization Plan : ',"",0,'L'); #added by mai 05-26-2014
	
	$pdf->Ln($space);
	
	$x = $pdf->GetX();
	$y = $pdf->GetY();


	$pdf->SetXY($x, $y+4);
	
	$pdf->SetFont('Arial','',$fontSizeLabel);
	$pdf->MultiCell(60, 4,mb_strtoupper($informant_name), '', 'L','');
	
	$pdf->SetXY($x+70, $y+4);	
	#$pdf->Cell(55, 4, mb_strtoupper($name_formal), '', 0,'L');
	#$pdf->MultiCell(60, 4,mb_strtoupper($info_address), '', 'L','');
	$pdf->MultiCell(60, 4,mb_strtoupper($relation_informant), '', 'L','');
	
	$pdf->SetXY($x+140, $y+4);
	
	if($firm_id){ #added by mai 05-26-2014 - display hospitalization plan
		if($firm_id == "PhilHealth"){
			$pdf->MultiCell(60, 4,mb_strtoupper($firm_id), '', 'L','');
		}

		else{
			$pdf->MultiCell(60, 4,mb_strtoupper('PhilHealth('.$firm_id.')'), '', 'L','');
		}
	}	

	else{
		$pdf->MultiCell(60, 4,mb_strtoupper('non-med'), '', 'L','');
	}
	

	$pdf->Ln($space);
	$pdf->Cell(192,4,'',"T",0,'L');
	
	$pdf->Ln($space);
	
	
	if (isset($is_discharged) && $is_discharged){
		if ( ($encounter_type==3) || ($encounter_type==4) ){
	#		$admitting_dr=$er_opd_admitting_physician_nr;
	#		$attending_dr=$attending_physician_nr;
			$admitting_dr=$er_opd_admitting_physician_name;
			$attending_dr=$attending_physician_name;
		}else{
	#		$attending_dr=$attending_physician_nr;
			$attending_dr=$attending_physician_name;
		}
	}else{
			# assuming that ONLY ecnounters with encounter_type==3 or 4
		#$attending_dr='';
		$attending_dr=$attending_physician_name;
		$admitting_dr=$er_opd_admitting_physician_name;	
	}
	
	$pdf->SetFont('Arial','I',$fontSizeLabel);
	$pdf->Cell(27,8,'Attending Dr.   : ',"",0,'L');
	$pdf->SetFont('Arial','',$fontSizeLabel);
	$pdf->Cell(65,8,mb_strtoupper($attending_dr),"",0,'L');
	
	$pdf->SetFont('Arial','I',$fontSizeLabel);
	$pdf->Cell(27,8,'Admitting Dr.    : ',"",0,'L');
	$pdf->SetFont('Arial','',$fontSizeLabel);
	$pdf->Cell(65,8,mb_strtoupper($admitting_dr),"",0,'L');
	
	$pdf->Ln($space*3);
	
	$pdf->SetFont('Arial','I',$fontSizeLabel);
	$pdf->Cell(27,8,'Consultant Dr. : ',"",0,'L');
	$pdf->SetFont('Arial','',$fontSizeLabel);
	
	$consulting_dr = "";
	if(stristr(trim($ward_name), 'charity') === FALSE)
		$consulting_dr = mb_strtoupper($attending_dr);
				
	$pdf->Cell(65,8,$consulting_dr,"",0,'L');
	
	$pdf->SetFont('Arial','I',$fontSizeLabel);
	$pdf->Cell(27,8,'Admitting Clerk : ',"",0,'L');
	$pdf->SetFont('Arial','',$fontSizeLabel);
	#echo "<br>1".$admitting_clerk;
	#echo "<br>1".$admitting_clerk_er_opd;
	#if ($admitting_clerk)
	#	$clerk = mb_strtoupper($admitting_clerk);
	#else
		$clerk = mb_strtoupper($admitting_clerk_er_opd);
	
	$pdf->Cell(65,8,$clerk,"",0,'L');
	
	$pdf->Ln($space*5);
	
	$pdf->SetFont('Arial','I',$fontSizeLabel);
	$pdf->Cell(35,8,'Admitting Diagnosis : ',"",0,'L');
	
	$pdf->Ln($space*2);
	$pdf->Cell(10,8,'',"",0,'L');
	$x = $pdf->GetX();
	$y = $pdf->GetY();
	
	$pdf->SetXY($x, $y+2);
	$pdf->SetFont('Arial','',$fontSizeLabel);
	$pdf->MultiCell(180, 6, mb_strtoupper($admitting_diagnosis), '0', 'J','');
	
	$pdf->Ln($space);
	
	$pdf->SetXY($x-10, $y+17);
	
	$pdf->SetFont('Arial','I',$fontSizeLabel);
	$pdf->Cell(25,5,'Principal Diagnosis:',"0",1,'L');
	
	
	$pdf->SetFont('Arial','',$fontSizeLabel);
	$result_diagnosis = array();
	if (isset($is_discharged) && $is_discharged){
		if ($rs_diagnosis = $objDRG->getDiagnosisCodes($_GET['encounter_nr'])){
			$rowsDiagnosis = $rs_diagnosis->RecordCount();
			while($temp=$rs_diagnosis->FetchRow()){
				$temp_diagnosis = array();
				$temp_diagnosis['type'] = $temp['type'];
				$temp_diagnosis['code'] = $temp['code'];
				$temp_diagnosis['diagnosis'] = $temp['diagnosis'];
				array_push($result_diagnosis,$temp_diagnosis);
			}			
		}
	}
	
	if (isset($is_discharged) && ($rowsDiagnosis)){
		$count=0;
		foreach ($result_diagnosis as $value) {
			if ($value['type']==1){
				$pdf->Cell(10,5,"","0",0,'L');
				$pdf->Cell(71,5,$value['code']." : ".$value['diagnosis'],"0",1,'L');
				$count++;
			}
		}
		if (1-$count){
			$pdf->Ln($space*(1-$count));
		}
	}else{
		$pdf->Ln($space*7);
	}
	
	$pdf->Ln($space);
	
	$pdf->SetFont('Arial','I',$fontSizeLabel);
	$pdf->Cell(25,5,'Other Diagnosis:',"0",1,'L');
	
	
	$pdf->SetFont('Arial','',$fontSizeLabel);
	
	if (isset($is_discharged) && ($rowsDiagnosis)){
		$count=0;
		foreach ($result_diagnosis as $value) {
			if ($value['type']==0){
				$pdf->Cell(10,5,"","0",0,'L');
				$pdf->Cell(71,5,$value['code']." : ".$value['diagnosis'],"0",1,'L');
				$count++;
			}
		}
		if (1-$count){
			$pdf->Ln($space*(1-$count));
		}
	}else{
		$pdf->Ln($space*7);
	}
	
	$pdf->Ln($space);
	
	$pdf->SetFont('Arial','I',$fontSizeLabel);
	$pdf->Cell(35,8,'Operations : ',"",0,'L');
	
	$result_therapy = array();
	if (isset($is_discharged) && $is_discharged){
		if ($rs_therapy = $objDRG->getProcedureCodes($_GET['encounter_nr'])){
			$rowsTherapy = $rs_therapy->RecordCount();
			while($temp=$rs_therapy->FetchRow()){
				#echo $temp['code']." : ".$temp['diagnosis']." <br> \n";
				$temp_therapy = array();
				$temp_therapy['type'] = $temp['type'];
				$temp_therapy['code'] = $temp['code'];
				$temp_therapy['therapy'] = $temp['therapy'];
				array_push($result_therapy,$temp_therapy);
			}			
		}
	}
	$pdf->Ln($space*3);
	$pdf->SetFont('Arial','',$fontSizeLabel);
	if (isset($is_discharged) && ($rowsTherapy)){
		$count=0;
		foreach ($result_therapy as $value) {
				$pdf->Cell(10,5,"","0",0,'L');
				$pdf->Cell(71,4,$value['code']." : ".$value['therapy'],"0",1,'L');
				$count++;
		}
		#if (13-$count){
		#	$pdf->Ln($space*(13-$count));
		#}else{
		#	$pdf->Ln($space);		
		#}
	}else{
		$pdf->Ln($space*7);
	}
	
	#if (isset($is_discharged) && (($result_therapy)||($result_diagnosis))
	#	$pdf->Ln($space*13);
	#else
		$pdf->Ln($space*5);
	
	#added by mai 09-24-2014
	
	$result_code = $enc_obj->getPatientEncounterRes($_GET['encounter_nr']);
	//var_dump($result_code);
	$chk_recovered = '   '; $chk_improved = '   '; $chk_unimproved = '   '; $chk_died = '   '; $chk_auto = '   '; $chk_no_auto = '   ';

	switch($result_code['result_code']){
		case 1:
			$chk_recovered = ' / ';
		break;
		case 2:
			$chk_improved = ' / ';
		break;
		case 3:
			$chk_unimproved = ' / ';
		break;
		case 4:
			$chk_died = ' / ';
		break;
		case 5:
			$chk_recovered = ' / ';
		break;
		case 6:
			$chk_improved = ' / ';
		break;
		case 7:
			$chk_unimproved = ' / ';
		break;
		case 8:
			$chk_died = ' / ';
		break;
		case 9:
			$chk_auto = ' / ';
		break;
		case 10:
			$chk_no_auto = ' / ';
		break;
	}
	
	$disp_code = $enc_obj->getPatientEncounterDisp($_GET['encounter_nr']);
	//var_dump($disp_code);
	$chk_dischrg = '   '; $chk_transfrd = '   '; $chk_DAMA = '   '; $chk_abs = '   ';

	switch($disp_code['disp_code']){
		case 2:
			$chk_dischrg = ' / ';
		break;
		case 3:
			$chk_transfrd = ' / ';
		break;
		case 4:
			$chk_DAMA = ' / ';
		break;
		case 5:
			$chk_abs = ' / ';
		break;
		case 7:
			$chk_dischrg = ' / ';
		break;
		case 8:
			$chk_transfrd = ' / ';
		break;
		case 9:
			$chk_DAMA = ' / ';
		break;
		case 10:
			$chk_abs = ' / ';
		break;

	}
	#end added by mai
	$pdf->SetFont('Arial','I',$fontSizeLabel-1);
	$note="Note: Always indicate diagnosis/procedure in order of importance, also indicate if procedure is Minor/Major.";
	$pdf->Cell(25,8,$note,"0",1,'L');

	$pdf->SetFont('Arial','',$fontSizeLabel);
	$pdf->Cell(60,4,'Result',"TLR",0,'L');
	$pdf->Cell(60,4,'Disposition',"TLR",0,'L');
	$pdf->SetFont('Arial','I',$fontSizeLabel);
	$pdf->Cell(0,4,'Admission Date/Time',"TLR",1,'L');
	
	$pdf->SetFont('Arial','',$fontSizeLabel);
	$pdf->Cell(7,4,'['.$chk_recovered.']',"L",0,'R');
	$pdf->Cell(21,4,'Recovered',"0",0,'L');
	$pdf->Cell(7,4,'['.$chk_died.']',"0",0,'R');
	$pdf->Cell(25,4,'Died',"R",0,'L');

	$pdf->Cell(7,4,'['.$chk_dischrg.']',"L",0,'R');
	$pdf->Cell(21,4,'Discharged',"0",0,'L');
	$pdf->Cell(7,4,'['.$chk_abs.']',"0",0,'R');
	$pdf->Cell(25,4,'Absconded',"R",0,'L');

	$pdf->SetFont('Arial','',$fontSizeInput);
	if ($admission_dt)
		$pdf->Cell(0,4,"     ".@formatDate2Local($admission_dt,$date_format,1),"BR",1,'L');
	else
		$pdf->Cell(0,4,"","BR",1,'L');

	$pdf->SetFont('Arial','',$fontSizeLabel);
	$pdf->Cell(7,4,'['.$chk_improved.']',"L",0,'R');
	$pdf->Cell(21,4,'Improved',"0",0,'L');
	$pdf->Cell(7,4,'['.$chk_auto.']',"0",0,'R');
	$pdf->Cell(25,4,'Autopsy',"R",0,'L');

	$pdf->Cell(7,4,'['.$chk_transfrd.']',"L",0,'R');
	$pdf->Cell(21,4,'Transferred',"0",0,'L');
	$pdf->Cell(32,4,'',"R",0,'L');
	$pdf->Cell(0,4,'Discharge Date/Time',"R",1,'L');
	
	$pdf->Cell(7,4,'['.$chk_unimproved .']',"L",0,'R');
	$pdf->Cell(21,4,'Unimproved',"0",0,'L');
	$pdf->Cell(7,4,'['.$chk_no_auto.']',"0",0,'R');
	$pdf->Cell(25,4,'No Autopsy',"R",0,'L');

	$pdf->Cell(7,4,'['.$chk_DAMA.']',"L",0,'R');
	$pdf->Cell(21,4,'DAMA',"0",0,'L');
	$pdf->Cell(32,4,'',"R",0,'L');

	$pdf->SetFont('Arial','',$fontSizeInput);
	if (isset($is_discharged) && $is_discharged)
		$pdf->Cell(0,4,"     ".@formatDate2Local($discharge_dt,$date_format,1),"R",1,'L');
	else
		$pdf->Cell(0,4,'',"R",1,'L');
	
	$pdf->SetFont('Arial','',$fontSizeLabel);
	$pdf->Cell(60,2,'',"BLR",0,'R');
	$pdf->Cell(60,2,'',"BLR	",0,'R');
	$pdf->Cell(0,2,'',"BLR",1,'L');

	$pdf->Ln($space*5);

	$pdf->SetFont('Arial','I',$fontSizeLabel);
	$note="I have reviewed this record and found it to be accurate and complete.";
	$pdf->Cell(0,8,$note,"0",1,'C');
	
	$pdf->Ln($space*4);
	
	$pdf->SetFont('Arial','I',$fontSizeLabel);
	$pdf->Cell(40,5,"THUMB MARK","0",0,'L');
	/*
	#$pdf->Cell(40,5,"","0",0,'L');
	$pdf->SetFont('Arial','B',$fontSizeLabel);
	$pdf->Cell(55,5,mb_strtoupper($attending_dr),"B",0,'L');
	$pdf->SetFont('Arial','I',$fontSizeLabel);
	$pdf->Cell(20,5,'',"",0,'L');
	$pdf->Cell(50,5,'',"B",1,'L');
	
	#$pdf->Ln($space*2);
	$pdf->Cell(40,5,"","0",0,'L');	
	$pdf->Cell(55,5,"Informant / Patient's Signature","0",0,'L');
	$pdf->Cell(20,5,"","0",0,'L');
	$pdf->Cell(50,5,"ATTENDING PHYSICIAN","0",0,'L');
	*/
	$pdf->Cell(50,5,'',"B",0,'L');
	$pdf->Cell(30,5,'',"",0,'L');
	$pdf->SetFont('Arial','',$fontSizeLabel);
	$pdf->Cell(55,5,mb_strtoupper($attending_dr),"B",0,'L');
	$pdf->Cell(20,5,"","0",1,'L');
	#$pdf->Ln($space*2);
	$pdf->SetFont('Arial','I',$fontSizeLabel);
	$pdf->Cell(40,5,"","0",0,'L');	
	$pdf->Cell(55,5,"Informant / Patient's Signature","0",0,'L');
	$pdf->Cell(25,5,'',"",0,'L');
	$pdf->Cell(50,5,"ATTENDING PHYSICIAN","0",0,'L');
	$pdf->Ln($space*.05);
	
	$x=$pdf->GetX();
	$y=$pdf->GetY();
	#echo "x, y here = ".$x." , ".$y;
	$pdf->Rect($x+1.5, $y, 20, 20);
	$pdf->SetFont('Arial','I',$fontSizeLabel);
	
	//added by art 01/10/2014
	$pdf->SetFont('Arial','B',12);
	$pdf->setY(33);
	//$pdf->Cell(0,4,  "DMHI-F-MRI-01A", "",0, 'R');
	//end art
	$pdf->Output();	
?>