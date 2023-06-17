<?php
/**
*Created by mai
*Created on 09-13-2014
*/

require('./roots.php');
require_once($root_path."classes/fpdf/fpdf.php");
require_once($root_path."include/inc_environment_global.php");
require_once($root_path.'include/care_api_classes/prescription/class_prescription_writer.php');
require_once($root_path."include/care_api_classes/class_hospital_admin.php");
require_once($root_path."include/care_api_classes/class_encounter.php");
require_once($root_path.'include/care_api_classes/class_vitalsign.php');
require_once($root_path.'include/care_api_classes/class_compre_discharge.php');
require_once($root_path.'include/care_api_classes/class_labservices_transaction.php');
require_once($root_path.'include/care_api_classes/class_personell.php');
require_once($root_path.'include/care_api_classes/class_radioservices_transaction.php');

class CompreHensivePDF extends FPDF{
	var $encounter_nr;
	var $p_data;
	var $v_data;
	var $c_data;
	var $d_data;
	var $dr_data;

	var $lab_res;
	var $rad_res;
	var $prec_res;

	/*fdpf*/
	var $fontsize;
	var $fonttype;
	var $fontstyle;
	var $newline;
	var $border;
	var $min_height;
	var $discharge_data;

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
		$headerType = "DISCHARGE SUMMARY";
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
		
		//vital signs
		$vital = new SegVitalsign();
		$this->v_data = $vital->get_latest_vital_signs($this->p_data['pid'], $this->p_data['encounter_nr']);
	
		//comprehensive report
		$compre_disc = new Compre_discharge();
		$this->c_data = $compre_disc->selectCompre($this->encounter_nr);
		
		//discharge information
		$this->d_data = $compre_disc->selectDischarge($this->encounter_nr);
	
		//lab requests
		$lab = new SegLab();
		$this->lab_res = $lab->getLabRequestsEncounter($_GET['encounter_nr']);
		
		//radio requests
		$rad = new SegRadio();
		$this->rad_res = $rad->getRadioRequestsEncounter($this->encounter_nr);
		
		//admitting doctor
		$personell = new Personell();
		$this->dr_data = $personell->get_Personell_info($this->p_data['current_att_dr_nr']);

		//final diagnosis
		$compre_disc->details = array('encounter_nr'=>$this->encounter_nr, 'dr_nr'=>$this->p_data['attending_physician_nr']);
		$this->discharge_data = $compre_disc->getICD();

		//prescription
		$presc = new SegPrescription();
		$this->presc_res = $presc->getListMeds($this->encounter_nr);
	}

	function patientDetails(){
		$this->SetFont($this->fontstyle, '', $this->fontsize);
		
		//Name
		$patient_name = $this->p_data['name_first'].' '.$this->p_data['name_last'];
		$this->Cell(14, $this->min_height,'Name : ',$this->border, 0, 'L');
		$this->Cell(73, $this->min_height,$patient_name,"B", 0, 'C');

		//Age
		$this->Cell(10, $this->min_height,'Age : ',$this->border, 0, 'L');
		$this->Cell(40, $this->min_height,$this->p_data['age'],"B", 0, 'C');

		//Sex
		$this->Cell(10, $this->min_height,'Sex : ',$this->border, 0, 'L');
		$this->Cell(5, $this->min_height, strtoupper($this->p_data['sex']),"B", 0, 'C');

		//Case No
		$this->Cell(15, $this->min_height,'Case # : ',$this->border, 0, 'L');
		$this->Cell(0, $this->min_height,$this->p_data['encounter_nr'],"B", 1, 'C');
		
		//Address
		$address = $this->p_data['street_name'].', '.$this->p_data['brgy_name'].' '.$this->p_data['mun_name'].' '.$this->p_data['prov_name'];
		$this->Cell(17, $this->min_height,'Address : ',$this->border, 0, 'L');
		$this->MultiCell(0, $this->min_height, strtoupper($address),"B", 'C');
		
		//Date admitted
		$date_admitted = date('m-d-Y', strtotime($this->p_data['admission_dt']));
		$this->Cell(32, $this->min_height,'Date Admitted : ',$this->border, 0, 'L');
		$this->Cell(30, $this->min_height-3, strtoupper($date_admitted),"B", 0, 'C');
		
		$this->Cell(8,10,'');

		//Date Discharge
		$discharge_dt = date('m-d-Y', strtotime($this->p_data['discharge_dt']));
		$this->Cell(23, $this->min_height,'Discharge : ',$this->border, 0, 'L');
		$this->Cell(30, $this->min_height-3, strtoupper($discharge_dt),"B", 0, 'C');
		$this->Ln(15);
	}

	function reportDetails(){

		$this->SetFont($this->fontstyle,'B',$this->fontsize);
		$this->Cell(30, $this->min_height,'Brief History :',$this->border, 0, 'L');
		$this->SetFont($this->fontstyle,'',$this->fontsize);
		$this->MultiCell(0, $this->min_height, $this->c_data['histo_illness'], $this->border, 'J');
	
		$this->SetFont($this->fontstyle,'B',$this->fontsize);
		$this->Cell(45, $this->min_height,'Pertinent Physical Findings :',$this->border, 1, 'L');
			$this->Cell(45, $this->min_height,'General Survey :',$this->border, 0, 'R');
				$this->Cell(0, $this->min_height, $this->c_data['general_survey'],$this->border, 1, 'L');

				$this->Cell(25, $this->min_height,'',$this->border,'L');
				$this->Cell(22, $this->min_height,'Heart Rate :',$this->border, 0, 'L');
				$this->SetFont($this->fontstyle,'',$this->fontsize);
				$this->Cell(20, $this->min_height, $this->v_data['pulse_rate'].($this->v_data['pulse_rate'] ? ' b/m' : ''),$this->border, 0, 'L');
				
				$this->SetFont($this->fontstyle,'B',$this->fontsize);
				$this->Cell(32, $this->min_height,'Respiratory Rate :',$this->border, 0, 'L');
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
		$this->Cell(43, $this->min_height,'Admission Impression :',$this->border, 0, 'L');
		$this->SetFont($this->fontstyle,'',$this->fontsize);
		$this->MultiCell(0, $this->min_height,($this->p_data['er_opd_diagnosis'] ? $this->p_data['er_opd_diagnosis'] : $this->p_data['chief_complaint']),$this->border, 'J');

		$this->SetFont($this->fontstyle,'B',$this->fontsize);
		$this->Cell(25, $this->min_height,'Medication :',$this->border, 0, 'L');
		$this->SetFont($this->fontstyle,'',$this->fontsize);
		$this->MultiCell(0, $this->min_height, $this->d_data['medication'], $this->border, 'J');
		$this->Ln(2);

		while($row = $this->presc_res->FetchRow()){
			$this->Cell(25, $this->min_height,'',$this->border, 0, 'L');
			$this->Cell(0, $this->min_height, $row['med'], $this->border, 1, 'L');
		}

		$this->SetFont($this->fontstyle,'B',$this->fontsize);
		$this->Cell(25, $this->min_height,'Procedure :',$this->border, 0, 'L');
		$this->SetFont($this->fontstyle,'',$this->fontsize);
		$this->MultiCell(0, $this->min_height, $this->d_data['proc'], $this->border, 'J');

		$this->SetFont($this->fontstyle,'B',$this->fontsize);
		$this->Cell(38, $this->min_height,'Laboratory Results :',$this->border, 0, 'L');
		$this->SetFont($this->fontstyle,'',$this->fontsize);
		$this->Cell(0, $this->min_height, '', $this->border, 1, 'L');

		if($this->lab_res){
			while($row=$this->lab_res->FetchRow()){
				$this->Cell(38, $this->min_height,'',$this->border, 0, 'L');
				$this->Cell(0, $this->min_height, chr(149).' '.$row['name'], $this->border, 1, 'L');
			}
		}else{
			$this->Cell(0, $this->min_height, '', $this->border, 1, 'L');
		}

		$this->SetFont($this->fontstyle,'B',$this->fontsize);
		$this->Cell(38, $this->min_height,'Radiology Results :',$this->border, 0, 'L');
		$this->SetFont($this->fontstyle,'',$this->fontsize);
		$this->Cell(0, $this->min_height, '', $this->border, 1, 'L');

		if($this->rad_res){
			while($row=$this->rad_res->FetchRow()){
				$this->Cell(38, $this->min_height,'',$this->border, 0, 'L');
				$this->Cell(0, $this->min_height, chr(149).' '.$row['name'], $this->border, 1, 'L');
			}
		}else{
			$this->Cell(0, $this->min_height, '', $this->border, 1, 'L');
		}

		$this->SetFont($this->fontstyle,'B',$this->fontsize);
		$this->Cell(40, $this->min_height,'Course in the Ward :',$this->border, 0, 'L');
		$this->SetFont($this->fontstyle,'',$this->fontsize);
		$this->MultiCell(0, $this->min_height, $this->d_data['course_ward'], $this->border, 'J');

		$this->SetFont($this->fontstyle,'B',$this->fontsize);
		$this->Cell(30, $this->min_height,'Final Diagnosis :',$this->border, 0, 'L');
		$this->SetFont($this->fontstyle,'',$this->fontsize);
		$this->MultiCell(0, $this->min_height, $this->d_data['notes'], $this->border, 'J');
	
		$this->SetFont($this->fontstyle,'B',$this->fontsize);
		$this->Cell(33, $this->min_height,'No. of Infections :',$this->border, 0, 'L');
		$this->SetFont($this->fontstyle,'',$this->fontsize);
		$this->MultiCell(0, $this->min_height, $this->d_data['no_of_infections'], $this->border, 'J');

		$this->SetFont($this->fontstyle,'B',$this->fontsize);
		$this->Cell(35, $this->min_height,'Recommendations :',$this->border, 0, 'L');
		$this->SetFont($this->fontstyle,'',$this->fontsize);
		$this->MultiCell(0, $this->min_height, $this->d_data['recommendations'], $this->border, 'J');
		
		$this->SetFont($this->fontstyle,'B',$this->fontsize);
		$this->Cell(43, $this->min_height,'Condition on discharge :',$this->border, 0, 'L');
		$this->SetFont($this->fontstyle,'',$this->fontsize);
		$this->MultiCell(0, $this->min_height, $this->d_data['cond'], $this->border, 'J');

		$this->Cell(0, $this->min_height+2, '', $this->border, 1);
		$dr_name = $this->dr_data['name'];
		$this->SetFont($this->fontstyle,'B',$this->fontsize);
		$this->Cell(39, $this->min_height,'Physician Signature :',$this->border, 0, 'L');
		$this->SetFont($this->fontstyle,'U',$this->fontsize);
		$this->Cell(0, $this->min_height, $dr_name.($this->dr_data['other_title'] ? ', '.$this->dr_data['other_title'] : ''), $this->border, 1, 'J');

		$this->Cell(39, $this->min_height,'',$this->border, 0, 'L');
		$this->SetFont($this->fontstyle,'',$this->fontsize);
		$this->Cell(0, $this->min_height, $this->dr_data['license_nr'], $this->border, 1, 'J');
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