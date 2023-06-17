<?php
/**
*Created by mai
*Created on 09-13-2014
*/

require('./roots.php');
require_once($root_path."classes/fpdf/fpdf.php");
require_once($root_path."include/inc_environment_global.php");
require_once($root_path."include/care_api_classes/class_hospital_admin.php");
require_once($root_path."include/care_api_classes/class_encounter.php");
require_once($root_path.'include/care_api_classes/class_vitalsign.php');
require_once($root_path.'include/care_api_classes/class_compre_discharge.php');

class CompreHensivePDF extends FPDF{
	var $encounter_nr;
	var $p_data;
	var $v_data;
	var $c_data;

	/*fdpf*/
	var $fontsize;
	var $fonttype;
	var $fontstyle;
	var $newline;
	var $border;
	var $min_height;

	function CompreHensivePDF($encounter_nr){
		$this->encounter_nr = $encounter_nr;

		 $this->fontstyle = "Times";
		 $this->fontsize = 11;
		 $this->fonttype = '';
		 $this->newline = 1;
		 $this->border = 0;
		 $this->min_height = 8;

		 $pg_size = array($this->in2mm(8.5), $this->in2mm(13));
		 $this->FPDF("P","mm", $pg_size);
		 $this->AliasNbPages();
		 $this->AddPage("P");
	}

	function Header(){
		$hospInfo = new Hospital_Admin();
		
		if($row = $hospInfo->getAllHospitalInfo()){
			$row['hosp_name'] = strtoupper($row['hosp_name']);
			$row['hosp_addr1'] = strtoupper($row['hosp_addr1']);
		}else{
			$row['hosp_name'] = "CAINGLET MEDICAL HOSPITAL, INC.";
			$row['hosp_addr1'] = "#2081 National Highway, Salvacion, Panabo City";
		}

		$this->Ln(5);
		//hospital name
		$this->SetFont($this->fontstyle,'B',$this->fontsize+8);
		$this->Cell(0, 4, $row['hosp_name'], 0, 1, 'C');

		//hospital address
		$this->Ln(1);
		$this->SetFont($this->fontstyle,'B',$this->fontsize);
		$this->Cell(0, 4, $row['hosp_addr1'], 0, 1, 'C');
		
		//logo
		$this->Image('../../gui/img/logos/cmhi_logo.jpg',10,10,22,20);
		$this->ReportTitle();
	}

	function getData(){
		//patient data
		$enc = new Encounter();
		$this->p_data = $enc->getEncounterInfo($this->encounter_nr);
		
		//vitalsigns
		$vital = new SegVitalsign();
		$this->v_data = $vital->get_latest_vital_signs($this->p_data['pid'], $this->p_data['encounter_nr']);
	
		//comprehensive report
		$compre_disc = new Compre_discharge();
		$this->c_data = $compre_disc->selectCompre($this->encounter_nr);
	}

	function patientDetails(){
		$this->SetFont($this->fontstyle, '', $this->fontsize);
		
		//Date
		$date= date_format(date_create($this->c_data['create_dt']), 'M d, Y');
		$this->Cell(150, $this->min_height,"",$this->border, 0, 'L');
		$this->Cell(14, $this->min_height,'Date : ',$this->border, 0, 'L');
		$this->SetFont($this->fontstyle, 'B', $this->fontsize);
		$this->Cell(0, $this->min_height,$date,"B", 1, 'C');

		$this->Ln(10);

		$this->SetFont($this->fontstyle, '', $this->fontsize);

		//Name
		$patient_name = $this->p_data['name_first'].' '.$this->p_data['name_last'];
		$this->Cell(14, $this->min_height,'Name : ',$this->border, 0, 'L');
		$this->Cell(0, $this->min_height,$patient_name,"B", 1, 'C');

		//Age
		$this->Cell(14, $this->min_height,'Age : ',$this->border, 0, 'L');
		$this->Cell(75, $this->min_height,$this->p_data['age'].($this->p_data['age'] ? ' old' : ''),"B", 0, 'C');

		//Sex
		$this->Cell(12, $this->min_height,'Sex : ',$this->border, 0, 'L');
		$this->Cell(12, $this->min_height,($this->p_data['sex'] == 'f' ? ' F' : 'M'),"B", 0, 'C');

		//Civil Status
		$this->Cell(23, $this->min_height,'Civil Status : ',$this->border, 0, 'L');
		$this->Cell(0, $this->min_height,$this->p_data['civil_status'],"B", 1, 'C');

		//Address
		$address = $this->p_data['street_name']." ".$this->p_data['brgy_name'].", ".$this->p_data['mun_name']." ".$this->p_data['prov_name'];
		$this->Cell(20, $this->min_height,'Address : ',$this->border, 0, 'L');
		$this->Cell(0, $this->min_height,$address,"B", 1, 'C');

		//Height
		$height = $this->v_data['height_ft'] ? $this->v_data['height_ft']." ft. " : "";
		$height .= $this->v_data['height_in'] ? $this->v_data['height_in']." in. " : "";
		$this->Cell(14, $this->min_height,'Height : ',$this->border, 0, 'L');
		$this->Cell(40, $this->min_height,$height,"B", 0, 'C');

		//Weight
		$this->Cell(15, $this->min_height,'Weight : ',$this->border, 0, 'L');
		$this->Cell(40, $this->min_height,($this->v_data['weight'] ? $this->v_data['weight'].' Kg' : ''),"B", 0, 'C');

		//BP
		$this->Cell(30, $this->min_height,'Blood Pressure : ',$this->border, 0, 'L');
		$this->Cell(0, $this->min_height,$this->v_data['diastole']."/".$this->v_data['systole'],"B", 1, 'C');
		
		$this->Ln(5);
	}

	function reportDetails(){

		$this->Cell(15, $this->min_height,'Build :',$this->border, 0, 'L');
		$this->MultiCell(0, $this->min_height, $this->c_data['build'], 'B', 'J');
	
		$this->Cell(23, $this->min_height,'Deformity :',$this->border, 0, 'L');
		$this->MultiCell(0, $this->min_height, $this->c_data['deformity'], 'B', 'J');
		
		$this->Cell(13, $this->min_height,'Skin :',$this->border, 0, 'L');
		$this->MultiCell(0, $this->min_height, $this->c_data['skin'], 'B', 'J');
	
		$this->Cell(27, $this->min_height,'Head & Neck :',$this->border, 0, 'L');
		$this->MultiCell(0, $this->min_height, $this->c_data['head_and_neck'], 'B', 'J');	

		$this->Cell(27, $this->min_height,'Chest & Back :',$this->border, 0, 'L');
		$this->MultiCell(0, $this->min_height, $this->c_data['chest_lungs'], 'B', 'J');

		$this->Cell(15, $this->min_height,'Lungs :',$this->border, 0, 'L');
		$this->MultiCell(0, $this->min_height, $this->c_data['lungs'], 'B', 'J');

		$this->Cell(13, $this->min_height,'Eyes :',$this->border, 0, 'L');
		$this->MultiCell(0, $this->min_height, $this->c_data['eye'], 'B', 'J');

		$this->Cell(15, $this->min_height,'Vision :',$this->border, 0, 'L');
		$this->MultiCell(0, $this->min_height, $this->c_data['vision'], 'B', 'J');

		$this->Cell(15, $this->min_height,'Ears :',$this->border, 0, 'L');
		$this->MultiCell(0, $this->min_height, $this->c_data['ear'], 'B', 'J');

		$this->Cell(15, $this->min_height,'Heart :',$this->border, 0, 'L');
		$this->MultiCell(0, $this->min_height, $this->c_data['heart'], 'B', 'J');

		$this->Cell(20, $this->min_height,'Abdomen :',$this->border, 0, 'L');
		$this->MultiCell(0, $this->min_height, $this->c_data['abdomen'], 'B', 'J');

		$this->Cell(43, $this->min_height,'Previous Hospitalization :',$this->border, 0, 'L');
		$this->MultiCell(0, $this->min_height, $this->c_data['previous_hosp'], 'B', 'J');

		$this->Cell(20, $this->min_height,'Remarks :',$this->border, 0, 'L');
		$this->MultiCell(0, $this->min_height, $this->c_data['remarks'], 'B', 'J');
	
		$this->Ln(23);

		//Signatories
		$this->SetFont($this->fontstyle, 'B', $this->fontsize);
		$this->Cell(90, $this->min_height,'EILEEN THERESA C. CAINGLET, M.D., DFM','T', 0, 'C');
		$this->Cell(13, $this->min_height, '', '', 0, 'L');
		$this->SetFont($this->fontstyle, 'B', $this->fontsize);
		$this->Cell(0, $this->min_height,'FELIX U. CAINGLET, M.D.','T', 1, 'C');

		$this->SetFont($this->fontstyle, '', $this->fontsize);
		$this->Cell(90, $this->min_height,'License No.: 85804', 0, 0, 'C');
		$this->Cell(13, $this->min_height, '', '', 0, 'L');
		$this->SetFont($this->fontstyle, '', $this->fontsize);
		$this->Cell(0, $this->min_height,'License No.:33617', 0, 0, 'C');

		$this->Ln(23);
		$this->SetFont($this->fontstyle, 'B', $this->fontsize);
		$this->Cell(90, $this->min_height,'ALBERT RODERICK C. CAINGLET, M.D.','T', 0, 'C');
		$this->Cell(13, $this->min_height, '', '', 0, 'L');
		$this->SetFont($this->fontstyle, 'B', $this->fontsize);
		$this->Cell(0, $this->min_height,'MARIKIT N. CAINGLET, M.D.','T', 1, 'C');

		$this->SetFont($this->fontstyle, '', $this->fontsize);
		$this->Cell(90, $this->min_height,'License No.: 89445', 0, 0, 'C');
		$this->Cell(13, $this->min_height, '', '', 0, 'L');
		$this->SetFont($this->fontstyle, '', $this->fontsize);
		$this->Cell(0, $this->min_height,'License No.:102579', 0, 0, 'C');
	}

	function Footer(){

	}

	function ReportTitle(){
		$this->Ln(7);
		$this->Cell(0, 10, '', 0, 1, 'C');
	}

	function reportOut(){
		$this->Output();
	}

	function in2mm($inches){
		return $inches * 25.4;
	}

}

$pdf = new CompreHensivePDF($_GET['encounter_nr']);
$pdf->getData();
$pdf->patientDetails();
$pdf->reportDetails();
$pdf->reportOut();
?>