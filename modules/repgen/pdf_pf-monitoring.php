<?php

/*Created by Maimai 
*November 25, 2014
*PF Monitoring Report
*/

require('./roots.php');

include_once($root_path.'include/care_api_classes/class_personell.php');
include_once($root_path."include/care_api_classes/class_hospital_admin.php");
require($root_path.'include/inc_environment_global.php');
require($root_path."classes/fpdf/fpdf.php");

class RepGen_Pf_Monitoring extends FPDF {
	var $from_date;
	var $to_date;
	var $p_type;
	var $dates;
	var $data;

	var $DEFAULT_FONTSIZE;
	var $DEFAULT_FONTTYPE;
	var $border2 = 0;
	var $border1 = 1;

	function RepGen_Pf_Monitoring($from, $to, $ptype){
		$pg_size = array($this->in2mm(8.5),$this->in2mm(13));                 // Default to long bond paper --- modified by LST - 04.13.2009
		$this->FPDF("L","mm", $pg_size);
		$this->AliasNbPages();
		$this->AddPage("L");

		$this->DEFAULT_FONTTYPE = "Arial";
		$this->DEFAULT_FONTSIZE = 11;

		$this->from_date = $from;
		$this->to_date = $to;
		$this->p_type = $ptype;

		$objInfo = new Hospital_Admin();
		$dept_obj = new Department();

		if ($row = $objInfo->getAllHospitalInfo()) {
			$row['hosp_agency'] = strtoupper($row['hosp_agency']);
			$row['hosp_name']   = strtoupper($row['hosp_name']);
		}
		else {
			$row['hosp_name']    = "CAINGLET MEDICAL HOSPITAL INCORPORATED";
			$row['hosp_addr1']   = "2081 NATIONAL HIGHWAY PANABO CITY";
		}

		$this->LogoX = 30;
		$this->LogoY = 3;
		$this->Image('../../gui/img/logos/cmhi_logo.jpg',$this->LogoX,$this->LogoY,23,23);
		
		$this->SetFont("Arial","B","13");
		$this->Cell(0,4,$row['hosp_name'],$this->border2,1,'C');
		$this->SetFont("Arial","B","11");
		$this->Cell(0,4,$row['hosp_addr1'],$this->border2,1,'C');
		$this->SetFont('Arial','B',11);
		
		$this->Ln(2);
		$this->SetFont("Arial","B","11");
		$this->Cell(0,4,$this->Caption,$this->border2,1,'C');
		
		$from_dt=strtotime($this->from_date);
		$to_dt=strtotime($this->to_date);
		
		$this->Cell(0,4,"DOCTORS DAILY PX MONITORING",$this->border2,1,'C');
		$this->Cell(0,4,strtoupper($this->p_type),$this->border2,1,'C');
		if (!empty($this->from_date) && !empty($this->to_date))
			$this->Cell(0,5,
				sprintf('%s to %s',date("m/d/Y",$from_dt),date("m/d/Y",$to_dt)),
				$border2,1,'C');
		$this->Cell(0,4,"WEEK # ".strftime("%U", $from_dt),$this->border2,1,'C');
		$this->Ln(10);
	}

	
	function ReportHeader(){
		$between=date_diff(date_create($this->to_date),date_create($this->from_date));
		$diff=$between->format("%a");

		$this->SetFont('Arial','B',11);
		$this->Cell(78,6, "DOCTOR", $this->border1, 0,'C');

		for($i=0; $i<=$diff; $i++){
			$this->dates[] = strtotime("$this->from_date +$i day");
			$this->Cell(27,6, strftime("%m/%d (%a)", $this->dates[$i]), $this->border1, 0,'C');
		}

		$this->Cell(0,6,'Total',$this->border1, 1, 'C');
	}

	function FetchData(){
		
		$personell_obj = new Personell();

		$result = $personell_obj->getAllDoctors();

		$sum_patient = array();	
		$count = 0;
		
		while($row = $result->FetchRow()){
			$this->SetFont('Arial','',11);
			$this->data['dr_name'][$count] = $row['dr_name'];
			
			$totalPatients = 0;
			$totalPercentage = 0;

			for($i=0; $i<count($this->dates); $i++){
				
				if(strtolower($this->p_type) == 'ipd'){
					$patientNumber = $personell_obj->countPatientsDischargeDate($row['dr_nr'], strftime("%Y-%m-%d", $this->dates[$i]), 3);
				}else{
					$patientNumber = $personell_obj->countPatientsEncounterDate($row['dr_nr'], strftime("%Y-%m-%d", $this->dates[$i]), (strtolower($this->p_type) == 'opd' ? 2 : 3));
				}
			
				//sum
				$this->SetFont('Arial','',11);
				$this->data[$count][$this->dates[$i]]['total'] = $patientNumber['total_patient'] ? $patientNumber['total_patient'] : "";
				
				//percentage
				$percentage = $patientNumber['sum_patient'] ? ($patientNumber['total_patient']/$patientNumber['sum_patient'])*100 : 0;
				$totalPercentage += $percentage;
				$this->data[$count][$this->dates[$i]]['percent'] = $patientNumber['total_patient'] ? "(".number_format($percentage,1)."%)" : "";
				
				//total patients per week
				$totalPatients+=$patientNumber['total_patient'];

				$sum_patient[$this->dates[$i]] = $patientNumber['sum_patient'];
			}

			$this->SetFont('Arial','',11);
			$this->data['sum_total'][$count] = $totalPatients ? $totalPatients : "";

			$this->SetFont('Arial','',9);
			$count++;
		}

		$this->SetFont('Arial','',7);

		$this->Cell(78,6,"",$this->border1,0,'L');

		for($i=0; $i<count($this->dates); $i++){
			$this->Cell(13,6,"TOTAL","TLB", 0, 'C');
			$this->Cell(14,6,"%","LBR", 0, 'C');
		}

		$this->Cell(20,6,"TOTAL","TLB", 0, 'C');
		$this->Cell(0,6, "%","LBR", 1, 'C');

		for($x=0; $x<count($this->data['dr_name']); $x++){
			
			$this->SetFont('Arial','',11);
			$this->Cell(78,6,strtoupper($this->data['dr_name'][$x]),$this->border1,0,'L');

			for($i=0; $i<count($this->dates); $i++){
				$this->SetFont('Arial','',11);
				$this->Cell(13,6,$this->data[$x][$this->dates[$i]]['total'],"TLB", 0, 'R');

				$this->SetFont('Arial','',9);
				$this->Cell(14,6,$this->data[$x][$this->dates[$i]]['percent'],"LBR", 0, 'R');
			}

			$this->SetFont('Arial','',11);
			$this->Cell(20,6,$this->data['sum_total'][$x],"TLB", 0, 'R');

			$this->SetFont('Arial','',9);
			$this->Cell(0,6,$this->data['sum_total'][$x] ? "(".number_format(($this->data['sum_total'][$x]/array_sum($sum_patient))*100,1)."%)" : "","LBR", 1, 'L');
			
		}


		$this->SetFont('Arial','B',11);
		$this->Cell(78,6, "TOTAL",$this->border1, 0, 'R');

		for($i=0; $i<count($sum_patient); $i++){
			$this->SetFont('Arial','B',11);
			$this->Cell(13,6,$sum_patient[$this->dates[$i]],"TLB", 0, 'R');

			$this->SetFont('Arial','',9);
			$this->Cell(14,6,"(100%)","LBR", 0, 'R');
		}

		$this->SetFont('Arial','B',11);
		$this->Cell(20,6,array_sum($sum_patient),"TLB", 0, 'R');
		
		$this->SetFont('Arial','',9);
		$this->Cell(0,6,"(100%)","LBR", 0, 'L');
	}


	function in2mm($inches){
		return $inches * 25.4;
	}

	function ReportOut(){
		$this->Output();
	}
}

$rep = new RepGen_Pf_Monitoring($_GET['from'], $_GET['to'], $_GET['modkey']);
$rep->ReportHeader();
$rep->FetchData();
$rep->ReportOut();
?>