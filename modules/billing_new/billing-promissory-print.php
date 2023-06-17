<?php
/**
*Created by mai 09-10-2014
*/

require('./roots.php');
require_once($root_path."classes/fpdf/fpdf.php");
require_once($root_path.'include/inc_environment_global.php');
require_once($root_path.'include/inc_date_format_functions.php');
require_once($root_path.'include/care_api_classes/class_hospital_admin.php');

class PromiPDF extends FPDF{
	/*params*/
	var $refno;
	var $patient_name;
	var $encounter_date;
	var $dischare_date;
	var $due_date;
	var $days_to_pay;
	var $due_amount;
	var $is_sum;
	var $is_installment;
	var $is_sum_due;
	var $is_install_due;

	/*fdpf*/
	var $fontsize;
	var $fonttype;
	var $fontstyle;
	var $newline;

	function PromiPDF($refno){
		 $this->refno = $refno;

		 $this->fontstyle = "Times";
		 $this->fontsize = 11;
		 $this->fonttype = '';
		 $this->newline = 1;

		 $pg_size = array($this->in2mm(8.5), $this->in2mm(11));                 // Default to long bond paper --- modified by LST - 04.13.2009
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

	function Footer(){

	}

	function ReportTitle(){
		$this->Ln(7);
		$this->Cell(0, 10, 'PROMISSORY NOTE', 0, 1, 'C');
	}

	function ReportOut(){
		$this->Output();
	}

	function in2mm($inches){
		return $inches * 25.4;
	}

	function printData(){
		$this->Cell(0, 10, date('F j, Y'), 0, 1, 'R');
		$this->Ln(6);
		$this->SetFont($this->fontstyle, '', $this->fontsize);
		$this->Cell(110, 10, 'For value consisting in the hospitalization and treatment of the patient', 0, 0, 'J');
		$this->SetFont($this->fontstyle, 'B', $this->fontsize);
		$this->Cell(0, 7, $this->patient_name, "B", 1, 'C');
		$this->SetFont($this->fontstyle, '', $this->fontsize);
		$this->Cell(60, 10, 'at the Cainglet Medical Hospital Inc.', 0, 0, 'J');
		$this->SetFont($this->fontstyle, '', $this->fontsize);
		$this->Cell(20, 7, $this->encounter_date, "B", 0, 'C');
		$this->SetFont($this->fontstyle, '', $this->fontsize);
		$this->Cell(8, 10, 'to', 0, 0, 'C');
		$this->SetFont($this->fontstyle, '', $this->fontsize);
		$this->Cell(20, 7, $this->discharge_date, "B", 0, 'C');
		$this->SetFont($this->fontstyle, '', $this->fontsize);
		$this->Cell(0, 10, 'I, we, either any of us undersigned, jointly', 0, 1, 'J');
		$this->Cell(0, 10, 'and severally PROMISE to the Cainglet Medical Hospital, Inc. located ad Salvacion National Highway, Panabo City, DDN', 0, 1, 'J');
		$this->Cell(0, 10, 'on the charges for such hospitalization and treatment, including professional fees, in the princiapl amount of', 0, 1, 'J');
		$amount = ucwords($this->convert_number_to_words($this->due_amount)." Pesos Only")." ( P ".number_format($this->due_amount, 2)." )";
		$this->Cell(strlen($amount)*1.8, 7, $amount, "B", 0, 'C');
		$this->Cell(0, 10, " either: ", 0, 1, 'L');
		
		$this->Ln(4);
		$this->SetFont($this->fontstyle, '', $this->fontsize);
		$this->Cell(112, 10, "(".$this->is_sum.") in one sum payable on or before", 0, 0, 'R');
		$this->SetFont($this->fontstyle, 'U', $this->fontsize);
		$this->Cell(0, 10, $this->is_sum_due, 0, 1, 'L');
		$this->SetFont($this->fontstyle, '', $this->fontsize);
		$this->Cell(0, 10, "(".$this->is_installment.") in installment according to the following schedule: ", 0, 1, 'C');
		$this->Ln(3);
		if(trim($this->is_installment)){
			$this->Cell(112, 10, "(".$this->days_to_pay." day (s) to pay ) Due Date ", 0, 0, 'R');
			$this->SetFont($this->fontstyle, 'U', $this->fontsize);
			$this->Cell(0, 10, $this->is_install_due, 0, 1, 'L');
		}

		$this->Ln(7);
		$this->SetFont($this->fontstyle, '', $this->fontsize);
		$this->MultiCell(0, 7, 'In the event of non-payment, the CMHI shall not be obliged to issue any reports relating to the aforementioned hospitalization, and may except for emergency first aid treatment, refuse the admission of the aforementioned patient or the undesigned or their relatives within the first degree.', 0, 'J');
		
		$this->Ln(5);
		$this->MultiCell(0, 7, 'In case of referral to an attorney for collection, I/we agree to an additional twenty five percent (25%) of the total amount due as and for attorney\'s fee, as well as costs of collection and/or litigation and incidental expenses.', 0, 'J');
		
		$this->Ln(4);
		$this->Cell(0, 3, "", "B", 0, 'L');
		$this->Ln(8);
		$this->SetFont($this->fontstyle, 'B', $this->fontsize);
		$this->Cell(25, 5, "Relationship", 0, 0, 'L');
		$this->Cell(78, 5, "Name", 0, 0, 'C');
		$this->Cell(40, 5, "Age", 0, 0, 'C');
		$this->Cell(0, 5, "Contact No", 0, 1, 'C');

		$this->Ln(4);
		$this->SetFont($this->fontstyle, '', $this->fontsize);
		$this->Cell(25, 5, "Spouse", 0, 0, 'L');
		$this->Cell(78, 5, "", "B", 0, 'C');
		$this->Cell(5, 5, "", 0, 0, 'L');
		$this->Cell(40, 5, "", "B", 0, 'C');
		$this->Cell(5, 5, "", 0, 0, 'L');
		$this->Cell(0, 5, "", "B", 1, 'C');

		$this->Cell(25, 5, "Children", 0, 0, 'L');
		$this->Cell(78, 5, "", "B", 0, 'C');
		$this->Cell(5, 5, "", 0, 0, 'L');
		$this->Cell(40, 5, "", "B", 0, 'C');
		$this->Cell(5, 5, "", 0, 0, 'L');
		$this->Cell(0, 5, "", "B", 1, 'C');

		$this->Cell(25, 5, "", 0, 0, 'L');
		$this->Cell(78, 5, "", "B", 0, 'C');
		$this->Cell(5, 5, "", 0, 0, 'L');
		$this->Cell(40, 5, "", "B", 0, 'C');
		$this->Cell(5, 5, "", 0, 0, 'L');
		$this->Cell(0, 5, "", "B", 1, 'C');

		$this->Cell(25, 5, "", 0, 0, 'L');
		$this->Cell(78, 5, "", "B", 0, 'C');
		$this->Cell(5, 5, "", 0, 0, 'L');
		$this->Cell(40, 5, "", "B", 0, 'C');
		$this->Cell(5, 5, "", 0, 0, 'L');
		$this->Cell(0, 5, "", "B", 1, 'C');

		$this->Cell(25, 5, "Father", 0, 0, 'L');
		$this->Cell(78, 5, "", "B", 0, 'C');
		$this->Cell(5, 5, "", 0, 0, 'L');
		$this->Cell(40, 5, "", "B", 0, 'C');
		$this->Cell(5, 5, "", 0, 0, 'L');
		$this->Cell(0, 5, "", "B", 1, 'C');

		$this->Cell(25, 5, "Mother", 0, 0, 'L');
		$this->Cell(78, 5, "", "B", 0, 'C');
		$this->Cell(5, 5, "", 0, 0, 'L');
		$this->Cell(40, 5, "", "B", 0, 'C');
		$this->Cell(5, 5, "", 0, 0, 'L');
		$this->Cell(0, 5, "", "B", 1, 'C');

		$this->Ln(15);
		$this->Cell(25, 5, "", 0, 0, 'L');
		$this->Cell(78, 5, "Signature Over Printed Name", "T", 0, 'C');
	}

	function getDetails(){
		global $db;

		$sql = "SELECT 
					  spn.`encounter_nr`,
					  fn_get_person_lastname_first (ce.`pid`) AS patient_name,
					  DATE(ce.`encounter_date`) AS encounter_date,
					  sbe.`bill_dte` AS discharge_date,
					  spn.amount,
					  is_sum,
					  is_installment,
					  due_date,
					  DATE(spn.due_date) - DATE(spn.create_dt) AS days_to_pay,
					  remarks 
					FROM
					  seg_promissory_note spn 
					  LEFT JOIN care_encounter ce 
					    ON ce.`encounter_nr` = spn.`encounter_nr` 
					  LEFT JOIN care_person cp 
					    ON cp.pid = ce.`pid` 
					  LEFT JOIN seg_billing_encounter sbe 
					    ON sbe.encounter_nr = spn.encounter_nr  
					WHERE refno =  ".$db->qstr($this->refno);
					
		$result=$db->Execute($sql);
		if($row = $result->FetchRow()){
			$this->patient_name = $row['patient_name'];
			$this->due_amount = $row['amount'];
			$encounter_date = new Datetime($row['encounter_date']);
			$this->encounter_date = date_format($encounter_date, 'm/d/Y');
			$this->days_to_pay = $row['days_to_pay'];

			$due_date = new Datetime($row['due_date']);
			$due_date = date_format($due_date, 'm/d/Y');
			if($row['is_sum']){
				$this->is_sum = '/';
				$this->is_sum_due = $due_date;
			}else{
				$this->is_sum = ' ';
			}

			if($row['is_installment']){
				$this->is_installment = '/'; 
				$this->is_install_due = $due_date;
			}else{
				$this->is_installment = ' ';
			}

			if($row['discharge_date']){
				$discharge_date = new Datetime($row['discharge_date']);
				$this->discharge_date = date_format($discharge_date, 'm/d/Y');
			}else{
				$this->discharge_date = '//';
			}
		}
	}

	function convert_number_to_words($number) {
   
	    $hyphen      = '-';
	    $conjunction = ' and ';
	    $separator   = ', ';
	    $negative    = 'negative ';
	    $decimal     = ' point ';
	    $dictionary  = array(
	        0                   => 'zero',
	        1                   => 'one',
	        2                   => 'two',
	        3                   => 'three',
	        4                   => 'four',
	        5                   => 'five',
	        6                   => 'six',
	        7                   => 'seven',
	        8                   => 'eight',
	        9                   => 'nine',
	        10                  => 'ten',
	        11                  => 'eleven',
	        12                  => 'twelve',
	        13                  => 'thirteen',
	        14                  => 'fourteen',
	        15                  => 'fifteen',
	        16                  => 'sixteen',
	        17                  => 'seventeen',
	        18                  => 'eighteen',
	        19                  => 'nineteen',
	        20                  => 'twenty',
	        30                  => 'thirty',
	        40                  => 'fourty',
	        50                  => 'fifty',
	        60                  => 'sixty',
	        70                  => 'seventy',
	        80                  => 'eighty',
	        90                  => 'ninety',
	        100                 => 'hundred',
	        1000                => 'thousand',
	        1000000             => 'million',
	        1000000000          => 'billion',
	        1000000000000       => 'trillion',
	        1000000000000000    => 'quadrillion',
	        1000000000000000000 => 'quintillion'
	    );
	   
	    if (!is_numeric($number)) {
	        return false;
	    }
	   
	    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
	        // overflow
	        trigger_error(
	            'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
	            E_USER_WARNING
	        );
	        return false;
	    }

	    if ($number < 0) {
	        return $negative . convert_number_to_words(abs($number));
	    }
	   
	    $string = $fraction = null;
	   
	    if (strpos($number, '.') !== false) {
	        list($number, $fraction) = explode('.', $number);
	    }
	   
	    switch (true) {
	        case $number < 21:
	            $string = $dictionary[$number];
	            break;
	        case $number < 100:
	            $tens   = ((int) ($number / 10)) * 10;
	            $units  = $number % 10;
	            $string = $dictionary[$tens];
	            if ($units) {
	                $string .= $hyphen . $dictionary[$units];
	            }
	            break;
	        case $number < 1000:
	            $hundreds  = $number / 100;
	            $remainder = $number % 100;
	            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
	            if ($remainder) {
	                $string .= $conjunction . $this->convert_number_to_words($remainder);
	            }
	            break;
	        default:
	            $baseUnit = pow(1000, floor(log($number, 1000)));
	            $numBaseUnits = (int) ($number / $baseUnit);
	            $remainder = $number % $baseUnit;
	            $string = $this->convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
	            if ($remainder) {
	                $string .= $remainder < 100 ? $conjunction : $separator;
	                $string .= $this->convert_number_to_words($remainder);
	            }
	            break;
	    }
	   
	    if (null !== $fraction && is_numeric($fraction)) {
	        $string .= $decimal;
	        $words = array();
	        foreach (str_split((string) $fraction) as $number) {
	            $words[] = $dictionary[$number];
	        }
	        $string .= implode(' ', $words);
	    }
	   
	    return $string;
	}
}

$pdf = new PromiPDF($_GET['refno']);
$pdf->getDetails();
$pdf->printData();
$pdf->ReportOut();
?>