<?php

require_once("./roots.php");
require_once($root_path."include/inc_environment_global.php");
require_once($root_path."include/care_api_classes/class_patient_queue.php");

$patient_queue = new Patient_queue();
$patient_queue->details = array('encounter_nr'=> $_POST['encounter_nr'],
									'queue_status'=> $_POST['queue_status'], 
									'queue_id'=> $_POST['queue_id'],
									'table_name'=> 'seg_patient_queue');

if(!$patient_queue->updateStatus()){
	echo json_encode("Unable to update status");
}