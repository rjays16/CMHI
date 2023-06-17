<?php
error_reporting(E_COMPILE_ERROR | E_CORE_ERROR | E_ERROR);  //set the error level reporting
require('./roots.php'); //traverse the root= directory
$local_user='ck_op_pflegelogbuch_user'; //I don't get this, but it has something to do with page authorization access
require($root_path.'include/inc_environment_global.php');
require_once($root_path.'include/inc_front_chain_lang.php');
require_once($root_path.'gui/smarty_template/smarty_care.class.php'); //load the extended smarty template
include_once($root_path.'include/care_api_classes/class_department.php');
require_once($root_path.'modules/or/ajax/order.common.php');
$department = new Department();

$breakfile=$root_path.'main/op-doku.php'.URL_APPEND;

$smarty = new Smarty_Care('select_or_request');
$smarty->assign('sToolbarTitle',"Operating Room ASU::List of Pending Requests"); //Assign a toolbar title
$smarty->assign('sWindowTitle',"Operating Room ASU::List of Pending Requests"); //Assign a toolbar title
$css_and_js = array('<link rel="stylesheet" href="'.$root_path.'modules/or/css/select_or_request.css" type="text/css" />'
										,'<link rel="stylesheet" type="text/css" href="'.$root_path.'modules/or/js/flexigrid/css/flexigrid/flexigrid.css">'
										,'<script type="text/javascript" src="'.$root_path.'modules/or/js/flexigrid/lib/jquery/jquery.js"></script>'
										,'<script type="text/javascript" src="'.$root_path.'modules/or/js/flexigrid/flexigrid.js"></script>'
										,'<script type="text/javascript" src="'.$root_path.'modules/or/js/jquery.tabs/jquery.tabs.pack.js"></script>'
										,'<link rel="stylesheet" type="text/css" href="'.$root_path.'modules/or/js/jquery.tabs/jquery.tabs.css" />'
										,'<link rel="stylesheet" type="text/css" href="'.$root_path.'modules/or/js/jqmodal/jqModal.css">'
										,'<script type="text/javascript" src="'.$root_path.'modules/or/js/jqmodal/jqModal.js"></script>'
										,$xajax->printJavascript($root_path.'classes/xajax_0.5'));
$smarty->assign('css_and_js', $css_and_js);

$list_dept = array();
$surgery_department=$department->getAllActiveWithSurgery();
foreach ($surgery_department as $dept) {
		$list_dept[$dept['nr']] = $dept['name_formal'];
}

$list_dept['all'] = 'All Department';



$number_of_pages = array('5'=>'5', '10'=>'10', '15'=>'15', '20'=>'20', '25'=>'25', '30'=>'30');
$smarty->assign('number_of_pages', $number_of_pages);
$smarty->assign('page_number', '<input type="text" id="page_number" name="page_number" />');
$smarty->assign('search_field', '<input type="text" id="search_field" name="search_field" />');
$smarty->assign('departments', $list_dept);
$smarty->assign('selected_department', 'all');
$smarty->assign('search_button', '<input type="submit" id="search_button" value="Search" />');

$smarty->assign('return', '<a href="'.$breakfile.'" id="return_button"></a>');
$smarty->assign('breakfile',$breakfile); //Close button
#$smarty->assign('sMainBlockIncludeFile','or/select_or_main_approve.tpl'); //Assign the select_or_request template to the frameset
$smarty->assign('sMainBlockIncludeFile','or/select_or_asu_approve.tpl');
$smarty->display('common/mainframe.tpl'); //Display he contents of the frame


?>
<script>



$(document).ready(function() {

$('#or_request_table').flexigrid
({
 url: '<?=$root_path?>modules/or/ajax/ajax_or_asu_reschedule.php',
 dataType: 'json',
 colModel : [
						 {display: 'Reference Number', width:90, name : 'refno', sortable : true, align: 'left'},
						 {display: 'Case Number', width:90, name : 'encounter_nr', sortable : true, align: 'left'},
						 {display: 'Request Date', width:100, name:'request_date', sortable: true, align: 'left'},
						 {display: 'Patient ID', width:75, name:'patient_id', sortable: false, align: 'left'},
						 {display: 'Patient Name', width:100, name:'patient_name', sortable: false, align: 'left'},
						 {display: 'Status', width:55, name:'edit', sortable: false, align: 'left'},
						 {display: 'Operation', width:95, name:'cancel', sortable: false, align: 'left'}
						 ],
sortname: ["request_date"],
domain: ['approve_or'],
sortorder: "desc",
useRp: true,
rp: 5,
resizable: true
});

});



$('#cancel_or_main_request').jqm({
overlay: 80,
onShow: function(h) {
	h.w.fadeIn(1000, function(){h.o.show();});
},
onHide: function(h){
	h.w.fadeOut(1000, function(){h.o.remove();});
	$('#or_request_table').flexReload();
}});

function show_popup(refno) {
	$('#cancel_or_main_request').jqmShow();
	$("input[@name='refno']").val('');
	$("input[@name='refno']").val('');
	$("input[@name='refno']").val(refno);
	$("input[@name='mode']").val('new');
	$('#error_form_input').html('');
	$("textarea[@name='cancellation_reason']").val('');
}

function hide_popup() {
	$('#cancel_or_main_request').jqmHide();
}

function cancel_request() {
	if (validate()) {

		var refno = $("input[@name='refno']").val();
		var mode = $("input[@name='mode']").val();
		var cancellation_reason = $("textarea[@name='cancellation_reason']").val();

		xajax_cancel_or_main_request(refno, cancellation_reason, mode);

	}
	return false;
}

function change_mode(my_mode) {
	$("input[@name='mode']").val(my_mode);
}

function validate() {

	var array_elements = [ {field: $("textarea[@name='cancellation_reason']"),
												 field_value: $("textarea[@name='cancellation_reason']").val(),
												 msg: 'Please provide the reason for cancellation',
												 msg_dest: $('#error_form_input')
												 }
												 ];
	var errors = new Array();
	for (var i=0; i<array_elements.length; i++) {
		if (array_elements[i].field_value == '' || !array_elements[i].field_value) {
			array_elements[i].msg_dest.html(array_elements[i].msg);
			errors.push(array_elements[i].field);
			if (array_elements[i].is_textfield) {
				array_elements[i].field.addClass('error_field');
			}
		}
		else {
			array_elements[i].msg_dest.html('');
			array_elements[i].field.removeClass('error_field');
		}
	}
	if (errors.length > 0) {
		errors[0].focus();
		return false;
	}
	else {
		return true;
	}
}

</script>

