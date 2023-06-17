<?php

require_once("./roots.php");
require_once($root_path."include/inc_environment_global.php");
require_once($root_path."include/care_api_classes/class_patient_queue.php");
require_once $root_path."classes/json/json.php";

$patient_queue = new Patient_queue();
$patient_queue->details = array('queue_id'=> $_POST['queue_id'], 'encounter_nr'=> $_POST['encounter_nr']);

$response = array("result"=>$patient_queue->checkDr());

$json = new Services_JSON;
print $json->encode($response);