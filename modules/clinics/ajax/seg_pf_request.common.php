<?php
/*created by mai 08-19-2014*/

require('./roots.php');
require_once($root_path.'classes/xajax/xajax.inc.php');
$xajax = new xajax($root_path.'modules/clinics/ajax/seg_pf_request.server.php');

$xajax->setCharEncoding("iso-8859-1");
$xajax->registerFunction('get_pf_request_by_refno');
$xajax->registerFunction('getChargeCompanyBalance');
$xajax->registerFunction('setALLDepartment');
$xajax->registerFunction('getDoctors');
$xajax->registerFunction('addDoctor');
$xajax->registerFunction('getPf');
?>