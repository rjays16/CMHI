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
		$headerType = "COMPREHENSIVE REPORT";
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
		$this->Ln(3);
		$this->SetFont($this->fontstyle,'B',$this->fontsize+3);
		$this->Cell(0, 4, $headerType, 0, 1, 'C');
		
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
		
		//Name
		$patient_name = $this->p_data['name_first'].' '.$this->p_data['name_last'];
		$this->Cell(14, $this->min_height,'Name : ',$this->border, 0, 'L');
		$this->Cell(75, $this->min_height,$patient_name,"B", 0, 'C');

		//HRN
		$this->Cell(25, $this->min_height,'Hospital No. : ',$this->border, 0, 'L');
		$this->Cell(15, $this->min_height,$this->p_data['pid'],"B", 0, 'C');

		//Room Ward
		$ward_room = $this->p_data['ward_name']." Room # ".$this->p_data['current_room_nr'];
		$this->Cell(23, $this->min_height,'Room/Ward : ',$this->border, 0, 'L');
		$this->Cell(0, $this->min_height,$ward_room,"B", 1, 'C');

		//Age
		$this->Cell(14, $this->min_height,'Age : ',$this->border, 0, 'L');
		$this->Cell(75, $this->min_height,$this->p_data['age'].($this->p_data['age'] ? ' old' : ''),"B", 0, 'C');
		
		//Weight
		$this->Cell(15, $this->min_height,'Weight : ',$this->border, 0, 'L');
		$this->Cell(45, $this->min_height,($this->v_data['weight'] ? $this->v_data['weight'].' Kg' : ''),"B", 1, 'C');
		
		$this->Ln(10);
	}

	function reportDetails(){

		$this->SetFont($this->fontstyle,'B',$this->fontsize);
		$this->Cell(33, $this->min_height,'Chief Complaints :',$this->border, 0, 'L');
		$this->SetFont($this->fontstyle,'',$this->fontsize);
		$this->MultiCell(0, $this->min_height,($this->p_data['chief_complaint']),$this->border, 'J');
		
		$this->SetFont($this->fontstyle,'B',$this->fontsize);
		$this->Cell(40, $this->min_height,'Admission Impression :',$this->border, 0, 'L');
		$this->SetFont($this->fontstyle,'',$this->fontsize);
		$this->MultiCell(0, $this->min_height,($this->p_data['er_opd_diagnosis']),$this->border, 'J');

		$this->SetFont($this->fontstyle,'B',$this->fontsize);
		$this->Cell(45, $this->min_height,'History of Present illness :',$this->border, 0, 'L');
		$this->SetFont($this->fontstyle,'',$this->fontsize);
		$this->MultiCell(0, $this->min_height, $this->c_data['histo_illness'], $this->border, 'J');
	
		$this->SetFont($this->fontstyle,'B',$this->fontsize);
		$this->Cell(45, $this->min_height,'PE/Review of Systems :',$this->border, 1, 'L');
			$this->Cell(45, $this->min_height,'General Survey :',$this->border, 0, 'R');
			$this->SetFont($this->fontstyle,'',$this->fontsize);	
			$this->Cell(45, $this->min_height,$this->c_data['general_survey'],$this->border, 1, 'L');

				$this->Cell(25, $this->min_height,'',$this->border,'L');
				$this->Cell(22, $this->min_height,'Heart Rate :',$this->border, 0, 'L');
				$this->SetFont($this->fontstyle,'',$this->fontsize);
				$this->Cell(20, $this->min_height, $this->v_data['pulse_rate'].($this->v_data['pulse_rate'] ? ' b/m' : ''),$this->border, 0, 'L');
				
				$this->SetFont($this->fontstyle,'B',$this->fontsize);
				$this->Cell(30, $this->min_height,'Respiratory Rate :',$this->border, 0, 'L');
				$this->SetFont($this->fontstyle,'',$this->fontsize);
				$this->Cell(20, $this->min_height, $this->v_data['resp_rate'].($this->v_data['resp_rate'] ? ' br/m' : ''),$this->border, 0, 'L');

				$this->SetFont($this->fontstyle,'B',$this->fontsize);
				$this->Cell(10, $this->min_height,'BP :',$this->border, 0, 'L');
				$this->SetFont($this->fontstyle,'',$this->fontsize);
				$this->Cell(30, $this->min_height, $this->v_data['systole'].'/'.$this->v_data['diastole'].($this->v_data['systole'] ? ' mm/Hg' : ''),$this->border, 0, 'L');

				$this->SetFont($this->fontstyle,'B',$this->fontsize);
				$this->Cell(13, $this->min_height,'Temp :',$this->border, 0, 'L');
				$this->SetFont($this->fontstyle,'',$this->fontsize);
				$this->Cell(0, $this->min_height, $this->v_data['temp'].($this->v_data['temp'] ? ' C' : ''),$this->border, 1, 'L');

				$this->Cell(25, $this->min_height,'',$this->border,'L');
				$this->SetFont($this->fontstyle,'B',$this->fontsize);
				$this->Cell(12, $this->min_height,'Skin :',$this->border, 0, 'L');
				$this->SetFont($this->fontstyle,'',$this->fontsize);
				$this->Cell(0, $this->min_height, $this->c_data['skin'],$this->border, 1, 'L');
				
				$this->Cell(25, $this->min_height,'',$this->border,'L');
				$this->SetFont($this->fontstyle,'B',$this->fontsize);
				$this->Cell(30, $this->min_height,'Head and Neck :',$this->border, 0, 'L');
				$this->SetFont($this->fontstyle,'',$this->fontsize);
				$this->Cell(0, $this->min_height, $this->c_data['head_and_neck'],$this->border, 1, 'L');
				
				$this->Cell(25, $this->min_height,'',$this->border,'L');
				$this->SetFont($this->fontstyle,'B',$this->fontsize);
				$this->Cell(12, $this->min_height,'Eye :',$this->border, 0, 'L');
				$this->SetFont($this->fontstyle,'',$this->fontsize);
				$this->Cell(0, $this->min_height, $this->c_data['eye'],$this->border, 1, 'L');

				$this->Cell(25, $this->min_height,'',$this->border,'L');
				$this->SetFont($this->fontstyle,'B',$this->fontsize);
				$this->Cell(12, $this->min_height,'Ear :',$this->border, 0, 'L');
				$this->SetFont($this->fontstyle,'',$this->fontsize);
				$this->Cell(0, $this->min_height, $this->c_data['ear'],$this->border, 1, 'L');

				$this->Cell(25, $this->min_height,'',$this->border,'L');
				$this->SetFont($this->fontstyle,'B',$this->fontsize);
				$this->Cell(25, $this->min_height,'Chest/Back :',$this->border, 0, 'L');
				$this->SetFont($this->fontstyle,'',$this->fontsize);
				$this->Cell(0, $this->min_height, $this->c_data['chest_lungs'],$this->border, 1, 'L');

				$this->Cell(25, $this->min_height,'',$this->border,'L');
				$this->SetFont($this->fontstyle,'B',$this->fontsize);
				$this->Cell(15, $this->min_height,'Lungs :',$this->border, 0, 'L');
				$this->SetFont($this->fontstyle,'',$this->fontsize);
				$this->Cell(0, $this->min_height, $this->c_data['lungs'],$this->border, 1, 'L');

				$this->Cell(25, $this->min_height,'',$this->border,'L');
				$this->SetFont($this->fontstyle,'B',$this->fontsize);
				$this->Cell(12, $this->min_height,'CVS :',$this->border, 0, 'L');
				$this->SetFont($this->fontstyle,'',$this->fontsize);
				$this->Cell(0, $this->min_height, $this->c_data['cvs'],$this->border, 1, 'L');
	
				$this->Cell(25, $this->min_height,'',$this->border,'L');
				$this->SetFont($this->fontstyle,'B',$this->fontsize);
				$this->Cell(20, $this->min_height,'Abdomen :',$this->border, 0, 'L');
				$this->SetFont($this->fontstyle,'',$this->fontsize);
				$this->Cell(0, $this->min_height, $this->c_data['abdomen'],$this->border, 1, 'L');
	
				$this->Cell(25, $this->min_height,'',$this->border,'L');
				$this->SetFont($this->fontstyle,'B',$this->fontsize);
				$this->Cell(23, $this->min_height,'Extremities :',$this->border, 0, 'L');
				$this->SetFont($this->fontstyle,'',$this->fontsize);
				$this->Cell(0, $this->min_height, $this->c_data['extremities'],$this->border, 1, 'L');
				
				$this->Cell(25, $this->min_height,'',$this->border,'L');
				$this->SetFont($this->fontstyle,'B',$this->fontsize);
				$this->Cell(25, $this->min_height,'Neuro Exam :',$this->border, 0, 'L');
				$this->SetFont($this->fontstyle,'',$this->fontsize);
				$this->Cell(0, $this->min_height, $this->c_data['neuro'],$this->border, 1, 'L');
	
		$this->SetFont($this->fontstyle,'B',$this->fontsize);
		$this->Cell(38, $this->min_height,'Past Medical History :',$this->border, 0, 'L');
		$this->SetFont($this->fontstyle,'',$this->fontsize);
		$this->MultiCell(0, $this->min_height, $this->c_data['past_medical_history'], $this->border, 'J');
		
		$this->SetFont($this->fontstyle,'B',$this->fontsize);
		$this->Cell(30, $this->min_height,'Family History :',$this->border, 0, 'L');
		$this->SetFont($this->fontstyle,'',$this->fontsize);
		$this->MultiCell(0, $this->min_height, $this->c_data['family_history'], $this->border, 'J');
		
		$this->SetFont($this->fontstyle,'B',$this->fontsize);
		$this->Cell(45, $this->min_height,'Personal / Social History :',$this->border, 0, 'L');
		$this->SetFont($this->fontstyle,'',$this->fontsize);
		$this->MultiCell(0, $this->min_height, $this->c_data['persona_social_history'], $this->border, 'J');
	
		$this->SetFont($this->fontstyle,'B',$this->fontsize);
		$this->Cell(45, $this->min_height,'Immunization History :',$this->border, 0, 'L');
		$this->SetFont($this->fontstyle,'',$this->fontsize);
		$this->MultiCell(0, $this->min_height, $this->c_data['immu_history'], $this->border, 'J');

		$this->SetFont($this->fontstyle,'B',$this->fontsize);
		$this->Cell(45, $this->min_height,'Obstetrical History :',$this->border, 0, 'L');
		$this->SetFont($this->fontstyle,'',$this->fontsize);
		$this->MultiCell(0, $this->min_height, $this->c_data['obs_history'], $this->border, 'J');
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