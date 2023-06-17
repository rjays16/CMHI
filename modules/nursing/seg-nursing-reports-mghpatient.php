<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/inc_environment_global.php');
require($root_path.'/modules/repgen/repgen.inc.php');

/**
* SegHIS - Hospital Information System (DMC Deployment)
* Enhanced by Segworks Technologies Corporation
* RESUSE By Genesis D. Ortiz (05-29-2014)
*/

	class RepGen_Mgh_Patient extends RepGen {
	var $area;
	var $date;
	var $encoder;
	var $shift_start;
	var $shift_end;

	function RepGen_Mgh_Patient ($area, $date, $encoder, $shift_start, $shift_end, $full) {
		global $db;
		$this->RepGen("LIST OF ADMITTED PATIENT");
		#$this->ColumnWidth = array(25,60,20,18,18,20,100,21);
		# 165
		$this->colored = FALSE;
		$this->ColumnWidth = array(28,28,55,45,40,30);
		$this->RowHeight = 6;
		$this->Alignment = array('L','L','R','R','R','R');
		$this->PageOrientation = "P";
		if ($date) $this->date=date("Y-m-d",strtotime($date));
		$this->encoder=$encoder;
		$this->shift_start=$shift_start;
		$this->shift_end=$shift_end;
		$this->area=$area;
		$this->full=$full;
		if ($this->colored)	$this->SetDrawColor(0xDD);
	}
		
		

	function Header() {
		global $root_path, $db;
		
		if ($this->encoder) {
			$sql = "SELECT name FROM care_users WHERE login_id=".$db->qstr($this->encoder);
			$this->encoderName = $db->GetOne($sql);
		}
		
		$this->Image($root_path.'gui/img/logos/cmhi_logo.jpg',25,10,20,20);
		
		$this->SetFont("Arial","I","9");
		$total_w = 165;
		// $this->Cell(17,4);
  // 	$this->Cell($total_w,4,'Republic of the Philippines',$border2,1,'C');
		// $this->Cell(17,4);
	 //  $this->Cell($total_w,4,'DEPARTMENT OF HEALTH',$border2,1,'C');
      	$this->Ln(10);
		 $this->SetFont("Arial","B","10");
		 $this->Cell(17,4);
  	$this->Cell($total_w,4,'CAINGLET MEDICAL HOSPITAL INCORPORATED',$border2,1,'C');
		$this->SetFont("Arial","","9");
		$this->Cell(17,4);
  	$this->Cell($total_w,4,'Panabo City',$border2,1,'C');
  	$this->Ln(4);
	  $this->SetFont('Arial','B',12);
		$this->Cell(17,5);
  	$this->Cell($total_w,4,'LIST OF MGH PATIENT',$border2,1,'C');
	  $this->SetFont('Arial','B',9);
		$this->Cell(17,5);
		if ($this->date) {
			$text = "For ".date("F j, Y",strtotime($this->date));
#			print_r($this->shift_start);
#			print_r($this->shift_end);
#			print_r($_GET);
#			
#			print_r(date("Y-m-d",strtotime($this->date)));
#			exit;
			
			if ($this->shift_start != $this->shift_end) {
				$time1 = (int)$this->shift_start;
				if ($time1 == 0) $time1 = "12:00mn";
				elseif ($time1 == 12) $time1 = "12:00nn";
				else $time1 = ($time1%12).":00".($time1/12>0 ? "am" : "pm");
				
				$time2 = (int)$this->shift_end;
				if ($time2 == 0) $time2 = "12:00mn";
				elseif ($time2 == 12) $time2 = "12:00nn";
				else $time2 = ($time2%12).":00".($time2/12>0 ? "am" : "pm");
				
				$text .= " ($time1 to $time2)";
			}
		}
		else
	  	$text = "All MGH Patients";
			
  	$this->Cell($total_w,4,$text,$border2,1,'C');
		$this->Ln(5);

		# Print table header
    $this->SetFont('Arial','B',9);
		if ($this->colored) $this->SetFillColor(0xED);
		$this->SetTextColor(0);
		$row=10;
		$this->Cell($this->ColumnWidth[0],$row,'HRN',1,0,'C',1);
		$this->Cell($this->ColumnWidth[1],$row,'Case No.',1,0,'C',1);
		$this->Cell($this->ColumnWidth[2],$row,'First Name',1,0,'C',1);
		$this->Cell($this->ColumnWidth[3],$row,'Middle Name',1,0,'C',1);
		$this->Cell($this->ColumnWidth[4],$row,"Last Name",1,0,'C',1);
		$this->Ln();
	}
	
	function Footer()
	{
		$this->SetY(-23);
		$this->SetFont('Arial','I',8);
		$this->Cell(0,10,'Page '.$this->PageNo().' of {nb}. Generated: '.date("Y-m-d h:i:sa"),0,0,'R');
	}

	function BeforeData() {
		if ($this->colored) {
			$this->DrawColor = array(0xDD,0xDD,0xDD);
		}
		$this->ColumnFontSize = 9;
	}
	
	function BeforeCellRender() {
		$this->FONTSIZE = 8;
		if ($this->colored) {
			if (($this->RENDERPAGEROWNUM%2)>0) 
				$this->RENDERCELL->FillColor=array(0xee, 0xef, 0xf4);
			else
				$this->RENDERCELL->FillColor=array(255,255,255);
		}
	}
	
	function AfterData() {
		global $db;
		
		if (!$this->_count) {
			$this->SetFont('Arial','B',9);
			$this->SetFillColor(255);
			$this->SetTextColor(0);
			$this->Cell(0, $this->RowHeight, "No records found for this report...", 1, 1, 'L', 1);
		}
		
		$cols = array();
	}
	
	function FetchData() {		
		global $db;

		if ($this->date) {
			$whereOrder[]="DATE(o.orderdate)='$this->date'";
			$whereWard[]="DATE(w.stock_date)='$this->date'";
			//$whereReturn[]="DATE(r.return_date)='$this->date'";
			
			$dTime = strtotime($this->date);
			if (is_numeric($this->shift_start) && is_numeric($this->shift_end)) {
				if ($this->shift_start != $this->shift_end) {
					if ($this->shift_start >= $this->shift_end) {
						$start_time = $dTime + $this->shift_start*3600;
						$end_time = $dTime + $this->shift_end*3600;
					}
					else {
						$start_time = $dTime + $this->shift_start*3600;
						$end_time = $dTime + $this->shift_end*3600+86400;
					}
					$whereOrder[] = "o.orderdate>='".date("YmdHis",$start_time)."' AND o.orderdate<='".date("YmdHis",$end_time)."'";
					$whereWard[] = "w.stock_date>='".date("YmdHis",$start_time)."' AND w.stock_date<='".date("YmdHis",$end_time)."'";
					$whereReturn[] = "r.return_date>='".date("YmdHis",$start_time)."' AND r.return_date<='".date("YmdHis",$end_time)."'";
				}
			}
			elseif (is_numeric($this->shift_start)) {
				$start_time = $dTime + $this->shift_start*3600;
				$whereOrder[] = "o.orderdate>='".date("YmdHis",$start_time)."' AND o.orderdate<=NOW()";
				$whereWard[] = "w.stock_date>='".date("YmdHis",$start_time)."' AND w.stock_date<=NOW()";
				$whereReturn[] = "r.return_date>='".date("YmdHis",$start_time)."' AND r.return_date<=NOW()";
			}
		}
		if ($this->encoder) {
			$whereOrder[]="o.create_id=".$db->qstr($this->encoder);
			$whereWard[]="w.create_id=".$db->qstr($this->encoder);
			$whereReturn[]="r.create_id=".$db->qstr($this->encoder);
		}

		if ($whereOrder)
			$sqlOrder = "AND (".implode(") AND (",$whereOrder).")";
		if ($whereReturn)
			$sqlReturn = "AND (".implode(") AND (",$whereReturn).")";
		if ($whereWard)
			$sqlWard = "AND (".implode(") AND (",$whereWard).")";
		

		if($this->date){
			$date = date("Y-m-d",strtotime($this->date));
			$sql = "SELECT ce.pid, ce.encounter_nr, name_first, name_middle, name_last
				FROM (care_person AS cp INNER JOIN care_encounter AS ce ON ce.pid = cp.pid) 
				WHERE is_maygohome = 1 AND mgh_setdte <= DATE_ADD('$date', INTERVAL 1 DAY)";
		}
		else{
			$sql = "SELECT ce.pid, ce.encounter_nr, name_first, name_middle, name_last
				FROM (care_person AS cp INNER JOIN care_encounter AS ce ON ce.pid = cp.pid) 
				WHERE is_maygohome = 1 ";
		}

		
		if (strtolower($this->full) != "yes") //$sql.= "HAVING total_issued>0 OR total_returns>0 OR total_wardstocks>0\n";
		//$sql .= "ORDER BY p.artikelname";
		$result=$db->Execute($sql);
		if ($result) {
			$this->_count = $result->RecordCount();
			$this->Data=array();
			while ($row=$result->FetchRow()) {
				$this->Data[]=array($row['pid'],$row['encounter_nr'],$row['name_first'],$row['name_middle'],$row['name_last']);
			}
		}
		else {
			print_r($sql);
			print_r($db->ErrorMsg());
			exit;
			# Error
		}			
	}
}

$rep = new RepGen_Mgh_Patient($_GET['area'],$_GET['date'],$_GET['encoder'],$_GET['shiftstart'], $_GET['shiftend']);
$rep->AliasNbPages();
$rep->FetchData();
$rep->Report();
?>