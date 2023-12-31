<?php /* Smarty version 2.6.0, created on 2017-01-06 11:38:14
         compiled from ../../../modules/dashboard/dashlets/PatientRadioResults/templates/ListView.tpl */ ?>
<?php require_once(SMARTY_DIR . 'core' . DIRECTORY_SEPARATOR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', '../../../modules/dashboard/dashlets/PatientRadioResults/templates/ListView.tpl', 13, false),)), $this); ?>
<div id="px-list-<?php echo $this->_tpl_vars['dashlet']['id']; ?>
" style="width:100%; overflow:hidden; padding:0"></div>
<script type="text/javascript">
ListGen.create("px-list-<?php echo $this->_tpl_vars['dashlet']['id']; ?>
", {
	id:'px-obj-<?php echo $this->_tpl_vars['dashlet']['id']; ?>
',
	width: "100%",
	height: "auto",
	url: "dashlets/PatientRadioResults/Listgen.php",
	showFooter: true,
	iconsOnly: true,
	effects: true,
	dataSet: [],
	autoLoad: true,
	maxRows: <?php echo ((is_array($_tmp=@$this->_tpl_vars['settings']['pageSize'])) ? $this->_run_mod_handler('default', true, $_tmp, '5') : smarty_modifier_default($_tmp, '5')); ?>
,
	rowHeight: 32,
	layout: [
		//['<h1>My Patients</h1>'],
		['#first', '#prev', '#pagestat', '#next', '#last', '#refresh'],
		['#thead'],
		['#tbody']
	],
	columnModel:[
		{
			name: "date",
			label: "Request Date",
			width: 100,
			styles: {
				color: "#000080",
				textAlign: "center"
			},
			sorting: ListGen.SORTING.desc,
			sortable: true,
			visible: true
		},
		{
			name: "service",
			label: "Service(s) requested",
			width: 150,
			sortable: false,
			visible: true,
			styles: {
				fontSize: "12px",
				color: "#c00000"
			}
		},
		{
			name: "options",
			label: '',
			width: 85,
			sortable: false,
			visible: true,
			styles: {
				textAlign: "center",
				whiteSpace: "nowrap"
			},
			render: function(data, index)
			{
				var row = data[index];
					/*edited by mai 08-13-2014*/
					return '<button class="button" onclick="PatientRadio_OpenResult(\''+row["refno"]+'\',\''+row["pid"]+'\');return false;"><img class="link" src="../../gui/img/common/default/film.png" />Results</button><a href="javascript: void(0);" onclick="PreviewImage_Result(\''+row["r_img"]+'\',\''+row['patient_name']+'\',\''+row['service']+'\');"> - Preview Image Results</a>';
			}
		}
	]
});

function PatientRadio_OpenResult(refno, pid) {
	var options = {
		url: '../../modules/radiology/certificates/seg-radio-report-pdf.php',
		data: {
			batch_nr_grp:refno,
			pid:pid
			}
	};
	Dashboard.openWindow(options);
}

function PreviewImage_Result(r_img,patient_name,service_name){ /*added by mai 08-13-2014*/
	if(r_img!=""){
		var options = {
			url: '../../modules/radiology/seg_radio_imagepreview.php',
			data: {
				r_img:r_img,
				file_num: 0,
				patient_name: patient_name,
				service_name: service_name
				}
		};
		Dashboard.openWindow(options);

	}else{
		alert('No Image Available.');
	}
}
</script>