<?php
require('./roots.php');
require_once($root_path.'classes/xajax_0.5/xajax_core/xajax.inc.php');
$xajax = new xajax($root_path.'modules/clinics/ajax/seg-compre-discharge.server.php');

$xajax->setCharEncoding("ISO-8859-1");
$xajax->register(XAJAX_FUNCTION, "saveCompre");
$xajax->register(XAJAX_FUNCTION, "savePE");
$xajax->register(XAJAX_FUNCTION, "saveDischrgInfo");
$xajax->register(XAJAX_FUNCTION, "searchICD");
$xajax->register(XAJAX_FUNCTION, "getICD");
$xajax->register(XAJAX_FUNCTION, "getImages");
$xajax->register(XAJAX_FUNCTION, "removeImage");
?>