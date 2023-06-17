<?php /* Smarty version 2.6.0, created on 2017-01-06 09:27:15
         compiled from ../../../modules/dashboard/dashlets/PatientResultQueue/templates/ListView.tpl */ ?>
<?php require_once(SMARTY_DIR . 'core' . DIRECTORY_SEPARATOR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', '../../../modules/dashboard/dashlets/PatientResultQueue/templates/ListView.tpl', 17, false),)), $this); ?>
<div id="PatientList-<?php echo $this->_tpl_vars['dashlet']['id']; ?>
" style="width:100%; overflow:hidden; padding:0"></div>
<script type="text/javascript">
ListGen.create("PatientList-<?php echo $this->_tpl_vars['dashlet']['id']; ?>
", {
	id:'PatientListObject-<?php echo $this->_tpl_vars['dashlet']['id']; ?>
',
	width: "100%",
	height: "auto",
	url: "dashlets/PatientResultQueue/Listgen.php",
	showFooter: true,
	iconsOnly: true,
	params: {
		filter: '<?php echo $this->_tpl_vars['settings']['filter']; ?>
'
	},
	pageStat: 'Items {from}-{to} of {total}',
	effects: true,
	dataSet: [],
	autoLoad: true,
	maxRows: <?php echo ((is_array($_tmp=@$this->_tpl_vars['settings']['pageSize'])) ? $this->_run_mod_handler('default', true, $_tmp, '5') : smarty_modifier_default($_tmp, '5')); ?>
,
	rowHeight: 32,
	layout: [
		['#first', '#prev', '#pagestat', '#next', '#last', '#refresh'],
		['#thead'],
		['#tbody']
	],
	columnModel:[
		{
			name: "number",
			label: "Priority Number",
			width: 120,
			styles: {
				color: "#000080",
				textAlign: "center"
			},
			sorting: ListGen.SORTING.desc,
			sortable: true,
			visible: true
		},
		{
			name: "patient_name",
			label: "Patient Name",
			width: 150,
			sortable: false,
			visible: true,
			styles: {
				fontSize: "12px",
				color: "#c00000"
			}
		},
		{
			name: "Action",
			label: '',
			width: 200,
			sortable: false,
			visible: true,
			styles: {
				textAlign: "center",
				whiteSpace: "nowrap"
			},
			render: function(data, index)
			{
				var row = data[index];
				var img;
				var text;
				var state;

				switch(row['status']){
					case 'pending':
						img = "door_in.png";
						text = 'Enter';
						state = 'onqueue';
						break;
					case 'onqueue':
						img = "accept.png";
						text = 'Active';
						state = 'active'
						break;
					case 'active':
						img = "home2.gif";
						state= 'done';
						text = 'Done';
				}

				if(row['status']){
	               	button = '<button class="button" onclick="changeResultStatus(\''+row['encounter_nr']+'\', \''+state+'\', \''+row['patient_name']+'\', \''+row['queue_id']+'\');return false;"><img class="link" src="../../gui/img/common/default/'+img+'" />'+text+'</button>';
	               	
	               	if(row['status'] != 'pending'){
	               	button += '<button class="button" onclick="changeResultStatus(\''+row['encounter_nr']+'\',\'pending\', \''+row['patient_name']+'\', \''+row['queue_id']+'\'); return false;"><img src="../../gui/img/common/default/cancel.png" clas="link">Cancel</button>';
	               	}

	               	return button;
	            }
			}

		},
		{
			name: "examinations",
			label: "Examinations",
			width: 150,
			sortable: false,
			visible: true,
			styles: {
				fontSize: "12px",
				color: "#00000"
			}
		}
	]
});


function changeResultStatus(encounter_nr, status, patient_name, queue_id){
	$J.ajax({
		url: "../../modules/dashboard/dashlets/PatientResultQueue/PatientStatus.php",
		data: {encounter_nr: encounter_nr, queue_status: status, queue_id: queue_id},
		type: "POST",
		dataType: "JSON",
		success: function(data){
			console.log(data);
			if(status == 'active'){
				Dashboard.dashlets.sendAction('<?php echo $this->_tpl_vars['dashlet']['id']; ?>
', 'openFile', {file: encounter_nr});
			}else{
				Dashboard.dashlets.refresh('<?php echo $this->_tpl_vars['dashlet']['id']; ?>
');
			}

			sendResultAlert(status, patient_name);
		},
		error: function(){
			alert("An error occured. Please try again.");
		}
	});
}

function sendResultAlert(status, patient_name){
	if(status != 'pending' && status != 'active'){
		var message	= '';

		switch(status){
			case 'onqueue':
				message = patient_name + " please proceed to Dr. "+$('dr_name').innerHTML+ "'s clinic.";
			break;
			case 'done':
				message = "Dr. "+$('dr_name').innerHTML+" is done consulting "+patient_name;
			break;
		}

		send_custom_notif('_a_1_queing_patient_queue', message, status);
	}
}

function refreshResultOnQue()
{
	var delay=3000;

    setTimeout(function()
    {
    	Dashboard.dashlets.refresh('<?php echo $this->_tpl_vars['dashlet']['id']; ?>
');
    },delay); 
}

</script>