/*created by mai 08-19-2014*/

function preset(){
	var dept_nr = $('current_dept_nr').value;
	if(dept_nr){
		var request_dept = $('request_dept');
		request_dept.value = dept_nr;	
	}

	jsSetDoctorsOfDept();
}

function jsSetDoctorsOfDept(){
	var dept_nr = $('request_dept').value;
	var keyword = $('search').value;

	xajax_getDoctors(dept_nr,keyword);
}

function clearList(){
	var list = $('request-list');
	var listBod = list.getElementsByTagName("tbody")[0]; 
	listBod.innerHTML = '';
}

function listDoctor(doctor_nr, doctor_name){
	var list = $('request-list');
	var listBod = list.getElementsByTagName("tbody")[0]; 
	var rowSrc = '';
	
	if(doctor_nr !=0){
		rowSrc += "<tr onclick='addDoctor("+doctor_nr+");'><td colspan='1' style='font-weight:bold'>"+doctor_name+"</td></tr>";
	}else{
		rowSrc += "<tr><td colspan='1' style='font-weight:bold'>No Doctor Available ...</td></tr>";
	}

	listBod.innerHTML += rowSrc;
}

function addDoctor(doctor_nr){
	xajax_addDoctor(doctor_nr);
}

function prepareAdd(doctor_nr, dr_name){
	var details = new Object();
	
	details.doctor_nr = doctor_nr;
	details.dr_name = dr_name;
	details.fee = "";

	window.parent.appendDoctor(details);
}
