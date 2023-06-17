<?php
  #created by Cherry 01-11-11
  #Admission List
  
  error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
  require('./roots.php');
  require($root_path.'include/inc_environment_global.php');
  require_once($root_path."/classes/excel/Writer.php");
  include_once($root_path.'include/care_api_classes/class_personell.php');
  require_once($root_path.'include/care_api_classes/class_hospital_admin.php');
  require_once($root_path.'include/care_api_classes/class_department.php'); 
  
  class Excel_PfMonitoing extends Spreadsheet_Excel_Writer
  {
      var $worksheet;
      var $Headers;
      var $format1, $format2, $format3;
      
      var $from_date;
      var $to_date;
      var $from, $to;
      var $dept_nr;
      var $lrow = 2;
      var $dates;
      var $p_type;
      var $data;
      var $diff;

      function Excel_PfMonitoing($from, $to, $ptype)
      {
          $this->Spreadsheet_Excel_Writer();
          $this->worksheet = & $this->addWorksheet();
          $this->worksheet->setPaper(1);      // Letter
          $this->worksheet->setPortrait();
          $this->worksheet->setMarginTop(1.9);
          $this->worksheet->setMarginLeft(0.5);
          $this->worksheet->setMarginRight(0.5);
          $this->worksheet->setMarginBottom(0.5);
        
          $this->Caption = "ADMISSION LIST";
          
          $this->format1=& $this->addFormat();
          $this->format1->setSize(9);
          $this->format1->setBold();
          $this->format1->setAlign('center');
          
          $this->format2=& $this->addFormat();
          $this->format2->setSize(8);
          $this->format2->setAlign('left');
          $this->format2->setTextWrap(1);
          
          $this->format3=& $this->addFormat();
          $this->format3->setSize(9);
          $this->format3->setBold();
          $this->format3->setAlign('center');
          
          if ($from) $this->from=date("Y-m-d",strtotime($from));
          if ($to) $this->to=date("Y-m-d",strtotime($to)); 
          $this->p_type = $ptype;  

          $between=date_diff(date_create($this->to),date_create($this->from));
          $this->diff=$between->format("%a");
      }
      
      function Header(){
        $objInfo = new Hospital_Admin();
        $dept_obj = new Department();

        if ($row = $objInfo->getAllHospitalInfo()) {
          $row['hosp_addr1'] = strtoupper($row['hosp_addr1']);
          $row['hosp_name']   = strtoupper($row['hosp_name']);
        }
        else {
          $row['hosp_name']    = "CAINGLET MEDICAL HOSPITAL INCORPORATED";
          $row['hosp_addr1']   = "2081 NATIONAL HIGHWAY PANABO CITY";
        }

        $col_count = ($this->diff * 2) + 4;

        //header
        $this->worksheet->write(0,0, $row['hosp_name'], $this->format3);
        $this->worksheet->setMerge(0, 0, 0, $col_count);
        $this->worksheet->write(1,0, $row['hosp_addr1'], $this->format3);
        $this->worksheet->setMerge(1, 0, 1, $col_count);
        $this->worksheet->write(2,0, "DOCTORS DAILY PX MONITORING", $this->format3);
        $this->worksheet->setMerge(2, 0, 2, $col_count);
        $this->worksheet->write(3,0, strtoupper($this->p_type), $this->format3);
        $this->worksheet->setMerge(3, 0, 3, $col_count);
        $this->worksheet->write(4,0, "WEEK # ".strftime("%U", (strtotime($this->from))), $this->format3);
        $this->worksheet->setMerge(4, 0, 4, $col_count);

      }

      function ReportHeader(){
      
        //column header
        $this->worksheet->write(6,0, "DOCTOR", $this->format3);
        $col = 1;

        for($i=0; $i<=$this->diff; $i++){
          $this->dates[] = strtotime("$this->from +$i day");
          $this->worksheet->write(6,$col+($i*2), strftime("%m/%d (%a)", $this->dates[$i]), $this->format3);
          $this->worksheet->setMerge(6, $col+($i*2), 6, $col+($i*2)+1);
        }

        $this->worksheet->write(6,$col+($i*2),"TOTAL",$this->format3);
        $this->worksheet->setMerge(6, $col+($i*2), 6, $col+($i*2)+1);
      }
          
      function FetchData(){
        $i = 1;
        $newrow=7;

        $personell_obj = new Personell();

        $result = $personell_obj->getAllDoctors();

        $sum_patient = array(); 
        $count = 0;
    
        while($row = $result->FetchRow()){
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
              $this->data[$count][$this->dates[$i]]['total'] = $patientNumber['total_patient'] ? $patientNumber['total_patient'] : "";
              
              //percentage
              $percentage = $patientNumber['sum_patient'] ? ($patientNumber['total_patient']/$patientNumber['sum_patient'])*100 : 0;
              $totalPercentage += $percentage;
              $this->data[$count][$this->dates[$i]]['percent'] = $patientNumber['total_patient'] ? "(".number_format($percentage,1)."%)" : "";
              
              //total patients per week
              $totalPatients+=$patientNumber['total_patient'];

              $sum_patient[$this->dates[$i]] = $patientNumber['sum_patient'];
            }

            $this->data['sum_total'][$count] = $totalPatients ? $totalPatients : "";
            $count++;
        }

        for($x=0; $x<count($this->data['dr_name']); $x++){
            $col = 0;
            $this->worksheet->write($newrow, $col, $this->data['dr_name'][$x], $this->format2);
            
            for($i=0; $i<count($this->dates); $i++){
              $this->worksheet->write($newrow, $col+1+($i*2), $this->data[$x][$this->dates[$i]]['total'], $this->format2);
              $this->worksheet->write($newrow, $col+2+($i*2), $this->data[$x][$this->dates[$i]]['percent'], $this->format2);
            }

            $this->worksheet->write($newrow, $col+1+($i*2), $this->data['sum_total'][$x], $this->format2);
            $this->worksheet->write($newrow, $col+2+($i*2), $this->data['sum_total'][$x] ? "(".number_format(($this->data['sum_total'][$x]/array_sum($sum_patient))*100,1)."%)" : "", $this->format2);
            
            $newrow++;
        }

         $this->worksheet->write($newrow, $col, "TOTAL", $this->format2);
          
         for($i=0; $i<count($sum_patient); $i++){
            $this->worksheet->write($newrow, $col+1+($i*2), $sum_patient[$this->dates[$i]], $this->format2);
            $this->worksheet->write($newrow, $col+2+($i*2), "100%", $this->format2);
          }

          $this->worksheet->write($newrow, $col+1+($i*2), array_sum($sum_patient), $this->format2);
          $this->worksheet->write($newrow, $col+2+($i*2), "100%", $this->format2);

      }
      
      function AfterData()
      {
          if (!$this->count) 
          {
            $this->worksheet->write($this->lrow, 0, "No records found for this report...");
          }
      } 
  }
  
  $rep = new Excel_PfMonitoing($_GET['from'], $_GET['to'], $_GET['modkey']);
  $rep->Header();
  $rep->ReportHeader();
  $rep->FetchData();
  //$rep->AfterData(); 
  $rep->send('pf_monitoring.xls');
  $rep->close();
?>