<?php	
	require('./roots.php');
	require_once($root_path.'classes/xajax_0.5/xajax_core/xajax.inc.php');
	$xajax = new xajax($root_path.'modules/billing_new/ajax/billing_new-misc-services-tray.server.php');

	$xajax->register(XAJAX_FUNCTION, "populateServicesList");    
	$xajax->register(XAJAX_FUNCTION, "getPharmaAreas");
?>