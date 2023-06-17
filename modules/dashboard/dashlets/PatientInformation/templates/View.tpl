<div id="px-info-{{$dashlet.id}}" style="width:100%; padding:0; background-color: #a9a9a9">
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td class="segPanel">
				<table width="100%" cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td width="*" style="" valign="top">
							<table width="100%" cellpadding="4" cellspacing="1" border="0">
								<tr>
									<td align="right" width="18%" bgcolor="#eaeaea">HRN:</td>
									<td align="left" width="32%">
										<span style="font:bold 12px Verdana">{{$pat.pid}}</span>
									</td>

									<td align="right" width="18%" bgcolor="#eaeaea">Case No.:</td>
									<td align="left" width="32%"><span style="font:bold 12px Verdana">{{$pat.encounter}}</span></td>
								</tr>
								<tr>
									<td align="right" valign="top" bgcolor="#eaeaea">Full name:</td>
									<td align="left" valign="top" ><span style="font:bold 12px Verdana">{{$pat.fullname}}</span></td>

									<td align="right" valign="top" bgcolor="#eaeaea">Address:</td>
									<td align="left" valign="top"><span style="font:bold 11px Verdana">{{$pat.address}}</span></td>
								</tr>
								<tr>
									<td align="right" bgcolor="#eaeaea">Age:</td>
									<td align="left"><span style="font:bold 12px Verdana">{{$pat.age}}</span></td>

									<td align="right" bgcolor="#eaeaea">Gender:</td>
									<td align="left"><span style="font:bold 12px Verdana">{{$pat.gender}}</span></td>
								</tr>
								<tr>
									<td align="right" bgcolor="#eaeaea">Height:</td>
									<td align="left"><span style="font:bold 12px Verdana">{{$pat.height}}</span></td>

									<td align="right" bgcolor="#eaeaea">Weight:</td>
									<td align="left"><span style="font:bold 12px Verdana">{{$pat.weight}}</span></td>
								</tr>
								<tr>
									<td align="right" bgcolor="#eaeaea">History of Smoking:</td>
									<td align="left"><span style="font:bold 12px Verdana">{{$pat.smoker}}</span></td>

									<td align="right" bgcolor="#eaeaea">Alcohol Drinker:</td>
									<td align="left"><span style="font:bold 12px Verdana">{{$pat.drinker}}</span></td>
								</tr>
								<tr>
									<td align="right" bgcolor="#eaeaea">Religion:</td>
									<td align="left"><span style="font:bold 12px Verdana">{{$pat.religion}}</span></td>
									
									<td align="right" bgcolor="#eaeaea">Important Info:<span><img src="../../gui/img/common/default/articles.gif" style="-moz-border-radius:8px; cursor: pointer;" title="edit" onclick="editImportantInfo();"/></span></td>
									<td align="left"><span style="font:bold 12px Verdana; color:red;" id="important_info_span">{{$pat.important_info}}</span>
									</td>
								</tr>
							</table>
						</td>
						<td width="1" valign="top" align="center">
							<span><img src="../../fotos/photo.php?pid={{$pat.pid}}&w=96" style="-moz-border-radius:8px" /></span>
							<br/>
							<br/>
							{{$doctors}}
						</td>
					</tr>
					<tr>
						<td align="left" colspan="2" style="padding:4px">
							<!--<button class="button" onclick="PatientHistory_openClinical();return false" {{$disable}}><img src="../../gui/img/common/default/folder_explore.png"/>Clinical Sheet</button>-->
							<button class="button" onclick="PatientHistory_openRequest();return false" {{$disable}}><img src="../../gui/img/common/default/folder_explore.png"/>Requests</button>
							<!-- <button class="button" onclick="PatientHistory_openPrescription();return false;" {{$disable}}><img src="../../gui/img/common/default/rx.png"/>Prescription</button> -->
							{{$requestPf}} <!-- added by mai 08-29-2014 -->
							{{$compreDischarge}} <!-- added by mai 09-11-2014 -->
							{{$btn_untagDoctor}}
							{{$btn_tagDoctor}}
							<br/>
							{{$refer}}
							{{$updateSmokerDrinkerData}}
							{{$dentalRecord}}
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</div>
<div class="segPanel" id="refer_patient" style="display:none" align="center">
    <div align="center" style="overflow:hidden">
	    <table border="0" cellspacing="2" cellpadding="2" width="95%" align="center">
	        <tbody>
	            <tr>
	                <td rowspan="3" class="segPanel" align="center" valign="top">
	                    <table width="95%" border="0" cellpadding="2" cellspacing="0" style="margin-top:8px">
	                        <tr>
	                            <td valign="top"><strong>Refer to</strong></td>
	                            </tr>
	                		<tr>
	                			<td>{{$sDept}}</td>
	                		</tr>
	                        <tr>
	                           <td valign="top"><strong>Reason for Referral</strong> </td>
	                        </tr>
	                        <tr>
	                			<td>{{$sReason}}</td>
	                		</tr>
	                    </table>
	                </td>
	            </tr>
	        </tbody>
	    </table>
    </div>
</div>
<div class="segPanel" id="smoker_drinker_patient" style="display:none" align="center">
    <div align="center" style="overflow:hidden">
	    <table border="0" cellspacing="2" cellpadding="2" width="95%" align="center">
	        <tbody>
	            <tr>
	                <td rowspan="3" class="segPanel" align="center" valign="top">
	                    <table width="95%" border="0" cellpadding="1" cellspacing="0" style="font-size:11px" >
	                        <tr>
								<td class="reg_item"><STRONG>History of Smoking:</STRONG></td>
								{{$sSmoker}}
							</tr>
							<tr>
								<td class="reg_item"><STRONG>Alcohol Drinker:</STRONG></td>
								{{$sDrinker}}
							</tr>
	                    </table>
	                </td>
	            </tr>
	        </tbody>
	    </table>
    </div>
</div>
<div class="segPanel" id="important_info_div" style="display:none" align="center">
	    <table border="0" cellspacing="2" cellpadding="2" width="95%" align="center">
	        <tbody>
	            <tr>
	                <td rowspan="3" align="center" valign="top">
	                    <table width="95%" border="0" cellpadding="1" cellspacing="0" style="font-size:11px" >
	                        <tr>
								{{$sImportantInfo}}
							</tr>
	                    </table>
	                </td>
	            </tr>
	        </tbody>
	    </table>
</div>
<div id="history_referral" style="width:100%; overflow:hidden; padding:0;" align="center">
    <div id="PatientInfo-referral-list1-{{$dashlet.id}}" style="width:100%; overflow:hidden; padding:0"></div>
</div>    
{{$jsCalendarSetup}}
<script type="text/javascript">

function PatientHistory_openPrescription()
{
	Dashboard.launcher.launch({
		title:'Write prescription',
		href:'../../modules/prescription/seg-clinic-new-prescription.php{{$URL_APPEND}}&checkintern=1&encounter_nr={{$pat.encounter}}',
		width: 820,
		height: 450
	})
}

function PatientHistory_openRequest()
{
	var pageLabRequest = '../../modules/clinics/seg-clinic-charges.php{{$URL_APPEND}}&checkintern=1&encounter_nr={{$pat.encounter}}&ptype=doctor'+'&pid={{$pat.pid}}'+'&doc_nr={{$pat.doc_nr}}'+'&or_no={{$pat.or}}';
 
        var dialogLabRequest = $J('<div></div>')
        .html('<iframe style="border: 0px; " src="' + pageLabRequest + '" width=100% height=550px></caiframe>')
        .dialog({
            autoOpen: true,
            modal: false,
            show: 'fade',
            hide: 'fade',
            height: 'auto',
            width: '80%',
            title: 'Clinical Requests',
            position: 'top',
            buttons: {
            	"Close": function(){
                    $J( this ).dialog( "close" );
                }
             }
        });

        return false;
}

function PatientHistory_setDoctors() {
	Dashboard.dashlets.sendAction("{{$dashlet.id}}", "setDoctors", {
		data:"{{$pat.encounter}}",
		or : "{{$pat.or}}"
	});	
}

function PatientHistory_unsetDoctors() {
	Dashboard.dashlets.sendAction("{{$dashlet.id}}", "unsetDoctors", {
		data:"{{$pat.encounter}}"
	});	
}

function PatientHistory_referPatient(doc_nr){
	$J( "#refer_patient" ).dialog({	
        autoOpen: true,
        modal:true,
        height: 240,
        width: 330,
        show: "blind",
        hide: "explode",
        title: "Refer Patient",
        position: "center",
        buttons: {
                Submit: function() {
                	if(validateReferral()){
                		Dashboard.dashlets.sendAction("{{$dashlet.id}}", "referPatient", {
							dept: $J('#dept').val(),
						 reason : $J('#reason').val(),
						 doc_nr : doc_nr,
							 enc: "{{$pat.encounter}}",
							 pid: "{{$pat.pid}}"
						});
                		//$('PatientInfo-referral-list-{{$dashlet.id}}').list.refresh();
                		$J('#dept').val('');
                		$J('#reason').val('');
						$J(this).dialog( "close" ); 
					}
            		 
				},
                Cancel: function(){
					$J(this).dialog( "close" );
                }
        }
    });
}

function PatientHistory_undoReferPatient(ref){
	var msg = confirm("Are you sure you want to Delete the referral?")
	if(msg){
		Dashboard.dashlets.sendAction("{{$dashlet.id}}", "undoReferPatient", {
			ref : ref
		});
	}
}

/*added by mai 08-29-2014*/
function Patient_AddPf(){
	var pageLabRequest = '../../modules/clinics/seg_pf_request.php{{$URL_APPEND}}&checkintern=1&encounter_nr={{$pat.encounter}}&ptype=doctor'+'&pid={{$pat.pid}}'+'&doc_nr={{$pat.doc_nr}}'+'&mode=new'+'&area=doctor';
 
        var dialogLabRequest = $J('<div></div>')
        .html('<iframe style="border: 0px; " src="' + pageLabRequest + '" width=100% height=550px></caiframe>')
        .dialog({
            autoOpen: true,
            modal: false,
            show: 'fade',
            hide: 'fade',
            height: 'auto',
            width: '80%',
            title: 'Clinical Requests',
            position: 'top',
            buttons: {
            	"Close": function(){
                    $J( this ).dialog( "close" );
                }
             }
        });

        return false;
}
/*end added by mai*/

//maimai added 09112014
function Patient_CompreDischarge(){
	var pageLabRequest = '../../modules/clinics/seg-compre-discharge.php{{$URL_APPEND}}&checkintern=1&encounter_nr={{$pat.encounter}}&pid={{$pat.pid}}&doc_nr={{$pat.doc_nr}}&area=doctor';
 
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
//end mai

//maimai added 09112014
function Patient_Dental(){
	var pageLabRequest = '../../modules/clinics/seg-dental.php{{$URL_APPEND}}&checkintern=1&encounter_nr={{$pat.encounter}}&pid={{$pat.pid}}&doc_nr={{$pat.doc_nr}}&area=doctor';
 
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
//end mai

function PatientHistory_updateSmokerDrinkerData(){
	$J( "#smoker_drinker_patient" ).dialog({
        autoOpen: true,
        modal:true,
        height: 180,
        width: 330,
        show: "blind",
        hide: "explode",
        title: "Update Smoker/Drinker Data",
        position: "center",
        buttons: {
                Submit: function() {
                		Dashboard.dashlets.sendAction("{{$dashlet.id}}", "updateSmokerDrinkerData", {
							smoker: $J('[name="smoker"]:checked').val(),
						   drinker: $J('[name="drinker"]:checked').val(),
							   enc: "{{$pat.encounter}}"
						});
            		$J(this).dialog( "close" );  
				},
                Cancel: function(){
					$J(this).dialog( "close" );
                }
        }
    });
}

function validateReferral(){
	if($J('#dept').val()==""){
		alert("Please select a department");
		$J('#department').focus();
		return false;
	}else if($J('#reason').val()==""){
		alert("Please select a reason for referral");
		$J('#referral_reason').focus();
		return false;
	}else{
		return true;
	}
}

function showHistoryReferral(doc_nr){
	
	$J('#PatientInfo-referral-list-{{$dashlet.id}}').attr('id','PatientInfo-referral-list1-{{$dashlet.id}}');
		ListGen.create("PatientInfo-referral-list1-{{$dashlet.id}}", {
			id:'PatientInfo-referral-listgen-{{$dashlet.id}}',
			width: "100%",
			height: "auto",
			url: "dashlets/PatientInformation/ReferralHistory.php",
			showFooter: true,
			iconsOnly: true,
			effects: true,
			dataSet: [],
			autoLoad: true,
			maxRows: 5,
			rowHeight: 32,
			layout: [
				//['<h1>My Patients</h1>'],
				['#first', '#prev', '#pagestat', '#next', '#last', '#refresh'],
				['#thead'],
				['#tbody']
			],
			columnModel:[
				{
					name: "",
					label: '',
					width: 30,
					sortable: false,
					visible: true,
					styles: {
						textAlign: "center",
						whiteSpace: "nowrap"
					},
					render: function(data, index)
					{
						var row = data[index];
							return '<img class="link" src="../../images/cashier_delete_small.gif" onclick="PatientHistory_undoReferPatient(\''+row["referral_nr"]+'\');return false;"/>';
					}
				},
				{
					name: "date",
					label: "Date",
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
					name: "department",
					label: "Department",
					width: 150,
					sorting: ListGen.SORTING.none,
					sortable: true,
					visible: true,
					styles: {
						fontSize: "12px",
						color: "#c00000"
					}
				},
				{
					name: "reason",
					label: "Reason for Referral",
					width: 150,
					sorting: ListGen.SORTING.none,
					sortable: true,
					visible: true,
					styles: {
						fontSize: "12px",
						color: "#c00000"
					}
				}
			]
		});
		
	$J('#PatientInfo-referral-list1-{{$dashlet.id}}').attr('id','PatientInfo-referral-list-{{$dashlet.id}}');
	
	$J( "#history_referral" ).dialog({
        autoOpen: true,
        modal:true,
        width: "auto",
		height: "auto",
        show: "blind",
        hide: "explode",
        title: "Referral History",
        position: "center",
        buttons: {
                Create_Referral: function() {
                		PatientHistory_referPatient(doc_nr);
				},
                Cancel: function(){
					$J(this).dialog( "close" );
                }
        }
    });
}

function refreshReferral(){
	$('PatientInfo-referral-list-{{$dashlet.id}}').list.refresh(); 
}

function assignValue(elem,val){
	if(elem==1){
		$J('#dept').val(val);
	}else{
		$J('#reason').val(val);
	}
}

function editImportantInfo(){
	$J( "#important_info_div" ).dialog({
        autoOpen: true,
        modal:true,
        height: 180,
        width: 500,
        show: "blind",
        hide: "explode",
        title: "Imporant Info",
        position: "center",
        buttons: {
                Submit: function() {
                		Dashboard.dashlets.sendAction("{{$dashlet.id}}", "updateImportantInfo", {
							important_info: $J('#important_info_text').val(),
							   enc: "{{$pat.encounter}}"
						});

            		$J(this).dialog( "close" );  
				},
                Cancel: function(){
					$J(this).dialog( "close" );
                }
        }
    });
}

function assignInfo(info){
	$J("#important_info_span").html(info);
}
</script>