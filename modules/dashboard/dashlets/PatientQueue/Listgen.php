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
            fn_get_person_lastname_first (ce.`pid`) AS patient_name,
            pq.* 
          FROM
            seg_patient_queue pq 
            LEFT JOIN care_encounter ce 
              ON ce.`encounter_nr` = pq.`encounter_nr` 
          WHERE (pq.dr_nr = ".$db->qstr($drNr)." OR pq.dr_nr = '0') 
                  AND pq.queue_status <> 'done' AND DATE(ce.encounter_date) = DATE(NOW())
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
      'dr_label'=>($row['dr_nr'] ? '' : 'No Preferred Doctor'),
      'dr_nr'=>$row['dr_nr']
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