<div id="px-history-{{$dashlet.id}}" style="border:0; padding:0; width:100%; overflow:hidden;"></div>
<script type="text/javascript">
ListGen.create("px-history-{{$dashlet.id}}", {
	id:'px-hist-obj-{{$dashlet.id}}',
	width: "100%",
	height: "auto",
	url: "dashlets/PatientHistory/Listgen.php",
	showFooter: true,
	iconsOnly: true,
	effects: true,
	dataSet: [],
	autoLoad: true,
	maxRows: {{$settings.pageSize|default:"5"}},
	rowHeight: 32,
	layout: [
		['#first', '#prev', '#pagestat', '#next', '#last', '#refresh'],
		['#thead'],
		['#tbody']
	],
	columnModel:[
		{
			name: "date",
			label: "Case Date",
			width: 80,
			styles: {
				color: "#000080",
				textAlign: "center"
			},
			sorting: ListGen.SORTING.desc,
			sortable: true,
			visible: true
		},
		{
			name: "admission",
			label: "Admission",
			width: 80,
			sorting: ListGen.SORTING.none,
			sortable: true,
			visible: true,
			styles: {
				fontSize: "11px"
			},
			render: function(data, index)
			{
				var row=data[index];
				return '<div>'+row['admission']+'</div>'+
					'<div style="font:normal 11px Tahoma; color:#0000c4">'+row['encounter']+'</div>';
			}
		},
		{
			name: "department",
			label: "Department",
			width: 120,
			sorting: ListGen.SORTING.none,
			sortable: true,
			visible: true,
			styles: {
				fontSize: "12px",
				color: "#c00000"
			}
		},
		{
			name: "options",
			label: 'Comprehensive Report',
			width: 150,
			sortable: false,
			visible: true,
			styles: {
				textAlign: "center",
				whiteSpace: "nowrap"
			},
			render: function(data, index)
			{
				var row = data[index];
				return '<img class="link" src="../../images/cashier_view.gif" onclick="openDrNotesView(\''+row["encounter"]+'\', \''+row["pid"]+'\')"/>';
			}
		},
		{
			name: "options",
			label: 'Dental',
			width: 70,
			sortable: false,
			visible: true,
			styles: {
				textAlign: "center",
				whiteSpace: "nowrap"
			},
			render: function(data, index)
			{
				var row = data[index];
				return '<img class="link" src="../../images/cashier_reports.gif" onclick="openDentalView(\''+row["encounter"]+'\')"/>';
			}
		}
	]
});

function openDrNotesView(encounter_nr, pid)
{
	var pageLabRequest = '../../modules/clinics/seg-compre-discharge.php?encounter_nr='+encounter_nr+"&pid="+pid+"&area=doctor";
 
        var dialogLabRequest = $J('<div></div>')
        .html('<iframe style="border: 0px; " src="' + pageLabRequest + '" width=100% height=400px></caiframe>')
        .dialog({
            autoOpen: true,
            modal: true,
            show: 'fade',
            hide: 'fade',
            height: 'auto',
            width: '80%',
            title: 'Comprehensive/Discharge Information',
            position: 'top',
            buttons: {
            	"Close": function(){
                    $J( this ).dialog( "close" );
                }
             }
        });
        return false;
}

function openDentalView(encounter_nr){
	var pageLabRequest = '../../modules/clinics/seg-dental.php?encounter_nr='+encounter_nr;
 
        var dialogLabRequest = $J('<div></div>')
        .html('<iframe style="border: 0px; " src="' + pageLabRequest + '" width=100% height=400px></caiframe>')
        .dialog({
            autoOpen: true,
            modal: true,
            show: 'fade',
            hide: 'fade',
            height: 'auto',
            width: '80%',
            title: 'Dental Information',
            position: 'top',
            buttons: {
            	"Close": function(){
                    $J( this ).dialog( "close" );
                }
             }
        });
        return false;
}
</script>