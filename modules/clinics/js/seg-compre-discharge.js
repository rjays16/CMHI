var encounter_nr;
var pid;
var icd_codes = new Array();
var icd = new Array();
var images = new Array();

function refreshPage() {
	window.location.reload();
}

$(document).ready(function(){
	$("#file").change(function(){
		var preview = $J("#images-thumb");
		var src= $J("#file").val();

		if(src!=""){
			var formdata= new FormData(); 
			var numfiles= this.files.length; 
			var i, file, progress, size;
		
			for(i=0;i<numfiles;i++){
				file = this.files[i];
				size = this.files[i].size;
				name = this.files[i].name;
				
				if (!!file.type.match(/image.*/)){
					if((Math.round(size))<=(1024*1024)){
						
						formdata.append("file[]", file);
						
						if(i==(numfiles-1)){
							$J.ajax({
								url: "seg-upload-compre-image.php?encounter_nr="+encounter_nr,
								type: "POST",
								data: formdata,
								processData: false,
								contentType: false,
								beforeSend: function(){
									$J("#info").html("Uploading images...");
								},
								success: function(res){
									if(res!="0")
										$J("#info").html("Successfully Uploaded");
									else
										$J("#info").html("Error in upload. Retry");
									
									getImages();
								},
								error: function(error){
									$J("#info").html("Error uploading images");
									console.log(error);
								}
							});
						}

					}else{
						$J("#info").html(name+"Size limit exceeded");
						return;
					}
				}else{
					$J("#info").html(name+"Not image file");
					return;
				}
				
			}
			
		}else{
			$J("#info").html("Select an image file");
			return;
		}

		return false;
	});
	
	$("#tabs").tabs({
			selected: 0,
			select: function(event, ui) {
				var selected = ui.index;
			}
		});

	encounter_nr = $J('#encounter_nr').val();
	pid = $J('#pid').val();
	getICD();
	getImages();
});

Array.prototype.CheckExistICD = function(code){
	return this.indexOf(code);
}

Array.prototype.pushDescription = function(){
	for(i=0; i<this.length; i++){
		var data = new Array();

		data = {
			code: this[i],
			description: $J("#code_"+this[i].replace('.','_')).val()
		};

		icd.push(data);
	}
}

function getICD(){
	var details = new Object();

	details = {
		encounter_nr: $J('#encounter_nr').val(),
		dr_nr : $J('#dr_nr').val()
	};

	xajax_getICD(details);
}

function saveCompre(){
	var details = new Object();

	details = {
		chief_complaint: $J('#chief_complaint').val(),
		histo_illness : $J('#histo_illness').val(),
		vitalsign_no : $J('#vitalsign_no').val(),
		pulse_rate : $J('#heart_rate').val(),
		resp_rate : $J('#resp_rate').val(),
		systole : $J('#bp_sys').val(),
		diastole : $J('#bp_dia').val(),
		temp : $J('#temp').val(),
		skin : $J('#skin').val(),
		head_and_neck : $J('#head_neck').val(),
		eye : $J('#eye').val(),
		ear : $J('#ear').val(),
		chest_lungs : $J('#chest_lungs').val(),
		lungs : $J('#lungsC').val(),
		general_survey : $('#general_survey').val(),
		cvs : $J('#cvs').val(),
		abdomen : $J('#abdomen').val(),
		extremities : $J('#extremities').val(),
		neuro : $J('#neuro').val(),
		past_med_history : $J('#med_hist').val(),
		family_history : $J('#fam_hist').val(),
		persona_social_history : $J('#perso_hist').val(),
		immu_history : $J('#immu_hist').val(),
		obs_history : $J('#obs_hist').val(),
		encounter_type : $J('#encounter_type').val(),
		encounter_nr : encounter_nr,
		pid : pid,
		adm_diagnosis: $J('#adm_diagnosis').val()
	};

	xajax_saveCompre(details);
}

function scrollTop(){
	$J('body').scrollTop(0);
}

function saveDisc(){
	var details = new Object();

	icd_codes.pushDescription();

	details = {
		medication : $J('#medication').val(),
		procedure : $J('#procedure').val(),
		course_ward : $J('#course_ward').val(),
		no_of_infections : $J('#no_infections').val(),
		recommendations : $J('#reco').val(),
		encounter_nr : encounter_nr,
		pid : pid,
		icd: icd,
		dr_nr : $J('#dr_nr').val(),
		notes: $J('#notes').val(),
		note: $J('#note').val(),
		cond: $J('#cond').val()
	};

	xajax_saveDischrgInfo(details);
}

function savePE(){
	var details = new Object();
	
	details = {
		systole : $J('#bp_sysp').val(),
		diastole : $J('#bp_diap').val(),
		height_ft : $J('#height_ft').val(),
		height_in : $J('#height_in').val(),
		weight : $J('#weight').val(),
		vitalsign_no : $J('#vitalsign_no').val(),
		
		build : $J('#build').val(),
		deformity : $J('#deformity').val(),
		skin : $J('#skinP').val(),
		head_and_neck : $J('#head_neckP').val(),
		chest_lungs : $J('#chest_lungsP').val(),
		lungs : $J('#lungsP').val(),
		eyes : $J('#eyeP').val(),
		vision : $J('#vision').val(),
		ears : $J('#earP').val(),
		heart : $J('#heart').val(),
		abdomen : $J('#abdomenP').val(),
		previous_hosp : $J('#previous_hosp').val(),
		remarks : $J('#remarks').val(),
		encounter_nr: encounter_nr,
		pid: pid
	};

	xajax_savePE(details);
}

function searchICD(){
	if((($J('#searchDiagnosis').val()).trim()).length > 2){ //at least 3 characters
		xajax_searchICD($('searchDiagnosis').value);
	}
}

function hideSearchResults(){
	$J('#searchResults').hide();
}

function emptySearchBox(){
	$J('#searchDiagnosis').val('');
}

function addDiagnosis(code, description){
	if(icd_codes.CheckExistICD(code) <= -1){ 
		icd_codes.push(code);

		var table = document.getElementById('tableDiagnosis');
		var tr = document.createElement('tr');	
		tr.id = code;

		//code
		var td1 = document.createElement('td');
		td1.appendChild(document.createTextNode(code));
		tr.appendChild(td1);
		
		//description
		var td2 = document.createElement('td');
		var input = document.createElement('input');
		input.id =  "code_"+code.replace('.', '_');
		input.value = description;
		input.size = 80;
		td2.appendChild(input);
		tr.appendChild(td2);

		//delete button
		var td3 = document.createElement('td');
		var rebutton = document.createElement('button');
		rebutton.innerHTML = 'X';
		rebutton.onclick = function (){
								table.removeChild(tr);
								removeICD(code);
							}

		td3.appendChild(rebutton);
		tr.appendChild(td3);

		table.appendChild(tr);
	}else{
		alert('This code is already on the list.');
	}
}

function removeICD(code){
	var index = icd_codes.indexOf(code);

	if(index!=-1){
	   icd_codes.splice(index, 1);
	}
}

function printReport(report){
	var url;
	var report_name;

	switch(report){
		case 'comprehensive':
			url = "seg-compre-report-pdf.php";
			report_name = "Comprehensive Report";
			break;

		case 'discharge-info':
			url = "seg-dischargeinfo-report-pdf.php";
			report_name = "Discharge Information";
			break;

		case 'PE':
			url = "seg-pe-report-pdf.php";
			report_name = "Physical Exam";
			break;
	}

	window.open(url+"?encounter_nr="+encounter_nr, report_name, "width=700, height=700, top=100, left=500");

}

function getImages(){
	var details = new Object();

	$J("#images-thumb").empty();

	details = {
		encounter_nr: encounter_nr
	};

	xajax_getImages(details);
}

function appendImages(filename){
	var thumbDiv = $J("#images-thumb");
	var id = filename;

	images.push(filename);

	thumbDiv.append('<div class="img-wrap"><span onclick="removeImage(\''+id+'\');" class="close">&times;</span><img onclick="previewImage(\''+images.indexOf(id)+'\'); return false;" style="max-height:100%; width: 100%;" src="'+$J("#img_path").val()+filename+'"/></div>');
}

function removeImage(id){
	
	var ans = confirm("Are you sure you want to delete this image?");
	if(ans){

		var details = new Object();

		details = {
			encounter_nr: encounter_nr,
			filename: id
		};

		xajax_removeImage(details);

	}
}

function displayLabResult(service_code, group_id, refno){
	var url = "../../modules/repgen/pdf_lab_results.php";

	if(group_id == 15){
		url = "../../modules/repgen/pdf_lab_results_bloodbank.php";
	}
	
	window.open(url+"?pid="+pid+"&refno="+refno+"&group_id="+group_id+"&service_code="+service_code,"",
						"width=700, height=700, top=100, left=500");
}

function displayRadResult(refno){
	var url = "../../modules/radiology/certificates/seg-radio-report-pdf.php";

	window.open(url+"?pid="+pid+"&batch_nr_grp="+refno,"",
						"width=700, height=700, top=100, left=500");
}

function previewRadResult(img, patient_name, service_name){
	if(img){
		url = "../../modules/radiology/seg_radio_imagepreview.php";
		window.open(url+"?r_img="+img+"&file_num=0&patient_name="+patient_name+"&service_name="+service_name,"",
						"width=700, height=700, top=100, left=500");
	}else{
		alert("No Image Available");
	}
}

function previewImage(filenameIndex){
	
	var url = "../../modules/clinics/preview-image-compre.php";

	window.open(url+"?encounter_nr="+encounter_nr+"&index="+filenameIndex,"",
						"width=700, height=700, top=100, left=500");
}