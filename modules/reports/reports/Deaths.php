<?php
    error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
    require_once('./roots.php');
    require_once($root_path.'include/inc_environment_global.php');
    
    include('parameters.php');
    
    #TITLE of the report
    $params->put("hospital_name", mb_strtoupper($hosp_name));
    $params->put("header", $report_title);
    $params->put("department", $death_hours_label);
    #$params->put("icd_class", $icd_class);
    $params->put("column_name","Type of Service");
    
    $patient_type = '3,4';
    
    $sql = "SELECT d.name_formal AS Type_Of_Service,  
            $age_bracket
            FROM care_department AS d
            INNER JOIN care_encounter AS e ON IF(e.current_dept_nr,e.current_dept_nr,e.consulting_dept_nr)=d.nr
            LEFT JOIN seg_encounter_result AS sr ON sr.encounter_nr = e.encounter_nr
            LEFT JOIN seg_encounter_disposition AS sd ON sd.encounter_nr = e.encounter_nr
            INNER JOIN care_person AS p ON p.pid = e.pid #AND p.death_encounter_nr=e.encounter_nr
            WHERE e.STATUS NOT IN ('deleted','hidden','inactive','void')
            AND e.discharge_date IS NOT NULL
            AND e.encounter_type IN ($patient_type)
            AND sr.result_code IN (4,8,9,10)
            $cond_death_hours 
            AND DATE(e.discharge_date) BETWEEN ".$db->qstr($from_date_format)." AND ".$db->qstr($to_date_format)." 
            GROUP BY d.name_formal
            ORDER BY d.name_formal";
           
    #echo $sql; 
    #exit();
    $rs = $db->Execute($sql);
    
    $rowindex = 0;
    $grand_total = 0;
    $data = array();
    if (is_object($rs)){
        while($row=$rs->FetchRow()){
            $male_total = (int) $row['male_below1'] + (int) $row['male_1to4'] + (int) $row['male_5to9']
                          + (int) $row['male_10to14'] + (int) $row['male_15to19'] +  (int) $row['male_20to44']
                          + (int) $row['male_45to59'] + (int) $row['male_60up'];
            $female_total = (int) $row['female_below1'] + (int) $row['female_1to4'] + (int) $row['female_5to9']
                          + (int) $row['female_10to14'] + (int) $row['female_15to19'] +  (int) $row['female_20to44']
                          + (int) $row['female_45to59'] + (int) $row['female_60up'];
            $total = $male_total + $female_total;
            
            $grand_total += $total;
            
            $data[$rowindex] = array('rowindex' => $rowindex+1,
                              'Type_Of_Service' => $row['Type_Of_Service'], 
                              'male_below1' => (int) $row['male_below1'],
                              'female_below1' => (int) $row['female_below1'],
                              'male_1to4' => (int) $row['male_1to4'],
                              'female_1to4' => (int) $row['female_1to4'],
                              'male_5to9' => (int) $row['male_5to9'],
                              'female_5to9' => (int) $row['female_5to9'],
                              'male_10to14' => (int) $row['male_10to14'],
                              'female_10to14' => (int) $row['female_10to14'],
                              'male_15to19' => (int) $row['male_15to19'],
                              'female_15to19' => (int) $row['female_15to19'],
                              'male_20to44' => (int) $row['male_20to44'],
                              'female_20to44' => (int) $row['female_20to44'],
                              'male_45to59' => (int) $row['male_45to59'],
                              'female_45to59' => (int) $row['female_45to59'],
                              'male_60up' => (int) $row['male_60up'],
                              'female_60up' => (int) $row['female_60up'],
                              'male_total' => (int) $male_total,
                              'female_total' => (int) $female_total,
                              'total' => (int) $total
                              );
                              
           $rowindex++;
        }  
          
    }else{
        $data[0]['code'] = NULL; 
    }     