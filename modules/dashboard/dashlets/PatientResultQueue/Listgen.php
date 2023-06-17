<?php
/*
*List of Pending Patients
*Created by Maimai
*Created on 10-13-2014
*/

require "./roots.php";
require_once $root_path."include/inc_environment_global.php";
require_once $root_path."include/care_api_classes/dashboard/DashletSession.php";
require_once $root_path."classes/json/json.php";

header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" );
header("Cache-Control: no-cache, must-revalidate" );
header("Pragma: no-cache" );
header("Content-type: text/x-json");

$session = DashletSession::getInstance(DashletSession::SCOPE_DASHBOARD, $_SESSION['activeDashboard']);

$sql = "SELECT personell_nr FROM care_users WHERE login_id=".$db->qstr($_SESSION["sess_temp_userid"]);
$drNr = $db->GetOne($sql);

$page = (int) $_REQUEST['page'];
$maxRows = (int) $_REQUEST['mr'];
$offset = ($page-1) * $maxRows;
$count = 1;

global $db;

$query = "SELECT 
              ce.pid,
              ce.encounter_nr,
              fn_get_person_lastname_first (ce.`pid`) AS patient_name,
              GROUP_CONCAT(sprq.`area`) AS areas,
              GROUP_CONCAT(sprq.`queue_id`) AS queue_id,
              GROUP_CONCAT(sprq.`ref_no`) AS ref_no,
              sprq.queue_status,
              GROUP_CONCAT(
                CASE
                  sprq.`area` 
                    WHEN 'radio' 
                  THEN 
                  (SELECT 
                    NAME 
                  FROM
                    seg_radio_services srs
                    LEFT JOIN care_test_request_radio ctrr
                      ON srs.service_code = ctrr.service_code 
                  WHERE ctrr.batch_nr = sprq.`ref_no`) 
                  WHEN 'lab' 
                  THEN 
                  (SELECT 
                    NAME 
                  FROM
                    seg_lab_services sls 
                    LEFT JOIN seg_lab_servdetails slsd 
                      ON slsd.service_code = sls.service_code 
                  WHERE slsd.refno = sprq.`ref_no`) 
                
                END
              ) AS services 
            FROM
              seg_patient_result_queue sprq 
              LEFT JOIN care_encounter ce 
                ON ce.encounter_nr = (
                  CASE
                    sprq.`area` 
                    WHEN 'lab' 
                    THEN 
                    (SELECT 
                      encounter_nr 
                    FROM
                      seg_lab_serv 
                    WHERE refno = sprq.ref_no) 
                    WHEN 'radio' 
                    THEN 
                    (SELECT 
                      srs.encounter_nr 
                    FROM
                      care_test_request_radio ctrr 
                      LEFT JOIN seg_radio_serv srs 
                        ON srs.refno = ctrr.refno 
                    WHERE ctrr.batch_nr = sprq.ref_no) 
                  END
                ) 
            WHERE sprq.queue_status <> 'done' 
              AND ce.current_att_dr_nr = ".$db->qstr($drNr)
          ."  AND DATE(ce.encounter_date) = DATE(NOW())
              GROUP BY ce.`encounter_nr`,
              sprq.`queue_status` 
              ORDER BY UNIX_TIMESTAMP(ce.`encounter_date`)
           LIMIT $offset, $maxRows ";

$result = $db->Execute($query);
if($result){
  while($row = $result->FetchRow()){
    $data[] = array(
      'number'=>$count++,
      'patient_name'=>$row['patient_name'],
      'queue_id'=> $row['queue_id'],
      'encounter_nr'=>$row['encounter_nr'],
      'status'=>$row['queue_status'],
      'examinations'=> $row['services']
      );

    $total++;
  } 
}

if(!$data){
  $total = 0;
  $data = array();
}

$response = array(
  'currentPage'=>$page,
  'total'=>$total,
  'data'=>$data
  );

$json = new Services_JSON;
print $json->encode($response);