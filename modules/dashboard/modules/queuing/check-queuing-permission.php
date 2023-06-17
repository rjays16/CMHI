<?php
/**
*	Created By Jarel 10/27/2014
*	
*/
require_once("./roots.php");
require_once($root_path."include/inc_environment_global.php");
require_once($root_path."include/care_api_classes/class_acl.php");
require($root_path."classes/json/json.php");

$objAcl = new Acl($_SESSION['sess_temp_userid']);
//json_encode(explode(" ", $_SESSION['sess_permission']));
$permission = $_GET['permission'];
$allow_Notify = $objAcl->checkPermissionRaw($permission);
$allow_Notify = (is_null($allow_Notify) ? false : $allow_Notify);

$json = new Services_JSON;
echo $json->encode($allow_Notify); 
?> 