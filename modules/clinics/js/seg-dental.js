var tooth = new Array(new Array());
var tooth_nos = new Array();
var encounter_nr;

$(document).ready(function(){
	encounter_nr = $("#encounter_nr").val();

	xajax_getTeeth(encounter_nr);
});

Array.prototype.CheckExistICD = function(code){
	return this.indexOf(code);
}

function getTooth(){
	xajax_getToothParts(encounter_nr);
}

function setVal(){
	var tooth_no = $("#tooth_no").val();
	
	$("#ops").val("");
	$("#con").val("");

	document.getElementById("tooth_0").checked = false;
	document.getElementById("tooth_1").checked = false;
	document.getElementById("tooth_2").checked = false;
	document.getElementById("tooth_3").checked = false;
	document.getElementById("tooth_4").checked = false;

	if(tooth[tooth_no]){

		var ops = tooth[tooth_no]["ops"];
		var con = tooth[tooth_no]["con"];
		var zero = tooth[tooth_no]["zero"] == 1 ? true: false;
		var one = tooth[tooth_no]["one"] == 1 ? true: false;
		var two = tooth[tooth_no]["two"] == 1 ? true: false;
		var three = tooth[tooth_no]["three"] == 1 ? true: false;
		var four = tooth[tooth_no]["four"] == 1 ? true: false;

		$("#ops").val(ops ? ops : "");
		$("#con").val(con ? con : "");
		
		document.getElementById("tooth_0").checked = zero;
		document.getElementById("tooth_1").checked = one;
		document.getElementById("tooth_2").checked = two;
		document.getElementById("tooth_3").checked = three;
		document.getElementById("tooth_4").checked = four;
	}

	$("#saveB").hide();
}

function showSave(){
	$("#saveB").show();
}

function ops_con(){
	
	var conf = confirm("Are you sure you want to save this operation?");

	if(conf){
		var tooth_no = $("#tooth_no").val();
		var con = $("#con").val();
		var ops = $("#ops").val();

		//tooth parts
		var zero = $("#tooth_0").is(':checked') ? 1 : 0;
		var one = $("#tooth_1").is(':checked') ? 1 : 0;
		var two = $("#tooth_2").is(':checked') ? 1 : 0;
		var three = $("#tooth_3").is(':checked') ? 1 : 0;
		var four = $("#tooth_4").is(':checked') ? 1 : 0;

		tooth[tooth_no] = {con: con, ops:ops, zero: zero, one: one, two: two, three: three, four: four};

		if(tooth_nos.CheckExistICD(tooth_no)<=-1){
			tooth_nos.push(tooth_no);
		}

		$("#saveB").hide();
	}
}

function saveDental(){
	var info = new Object();

	info = {
		tongue : $("#tongue").val(),
		palate : $("#palate").val(),
		tonsils : $("#tonsils").val(),
		lips : $("#lips").val(),
		floor_of_mouth : $("#floor_mouth").val(),
		cheeks: $('#cheeks').val(),
		allergies : $("#allergies").val(),
		heart_disease : $("#heart_disease").val(),
		blood_dyscracia : $("#blood_dys").val(),
		diabetes : $("#diabetes").val(),
		kidney : $("#kidney").val(),
		liver : $("#liver").val(),
		others : $("#others").val(),
		hygiene : $("#hygiene").val(),
		tooth_count : $("#tooth_count").val(),
		services : $("#details_services").val(),
		diagnosis : $("#diagnosis").val(),
		operator : $("#operator").val(),
		checked_by : $("#checked_by").val()
	};

	var data = new Array(new Array());
	var tooth_parts = new Array(new Array());

	for(var i=0; i<tooth_nos.length; i++){
		data[i] = {tooth_no: tooth_nos[i], 
			ops: tooth[tooth_nos[i]]["ops"] ? tooth[tooth_nos[i]]["ops"] : "",
			con: tooth[tooth_nos[i]]["con"] ? tooth[tooth_nos[i]]["con"] : "",
			encounter_nr: $("#encounter_nr").val()
		};

		tooth_parts[i] = {
			tooth_no: tooth_nos[i],
			zero: tooth[tooth_nos[i]]["zero"] ? tooth[tooth_nos[i]]["zero"] : "",
			one: tooth[tooth_nos[i]]["one"] ? tooth[tooth_nos[i]]["one"] : "",
			two: tooth[tooth_nos[i]]["two"] ? tooth[tooth_nos[i]]["two"] : "",
			three: tooth[tooth_nos[i]]["three"] ? tooth[tooth_nos[i]]["three"] : "",
			four: tooth[tooth_nos[i]]["four"] ? tooth[tooth_nos[i]]["four"] : "",
			encounter_nr: $("#encounter_nr").val()
		};
	}

	xajax_saveDental(data, $("#encounter_nr").val(), info, tooth_parts);
}

function setTeeth(data){
	tooth[data.tooth_no] = {con: data.con, ops:data.ops};

	if(tooth_nos.CheckExistICD(data.tooth_no)<=-1){
		tooth_nos.push(data.tooth_no);
	}

	if($("#tooth_no").val() == data.tooth_no){
		$("#ops").val(data.ops);
		$("#con").val(data.con);
	}
}

function setToothParts(data){
	
	tooth[data.tooth_no]["zero"] = data.zero;
	tooth[data.tooth_no]["one"] = data.one;		
	tooth[data.tooth_no]["two"] = data.two;
	tooth[data.tooth_no]["three"] = data.three;
	tooth[data.tooth_no]["four"] = data.four;

	if(tooth_nos.CheckExistICD(data.tooth_no)<=-1){
		tooth_nos.push(data.tooth_no);
	}

	if($("#tooth_no").val() == data.tooth_no){

		var zero = data.zero == 1 ? true: false;
		var one = data.one == 1 ? true: false;
		var two = data.two == 1 ? true: false;
		var three = data.three == 1 ? true: false;
		var four = data.four == 1 ? true: false;

		document.getElementById("tooth_0").checked = zero;
		document.getElementById("tooth_1").checked = one;
		document.getElementById("tooth_2").checked = two;
		document.getElementById("tooth_3").checked = three;
		document.getElementById("tooth_4").checked = four;

	}
}

function printDental(){
	var url = "seg-dental-report.php";
	var report_name = "Dental Record";

	window.open(url+"?encounter_nr="+encounter_nr, report_name, "width=700, height=700, top=100, left=500");
}