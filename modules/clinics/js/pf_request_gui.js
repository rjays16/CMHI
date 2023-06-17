var array_of_servcode = new Array();

J().ready(function() {
	J('#misc_charge')
		.jqDrag('.jqDrag')
		.jqResize('.jqResize');

		J('#misc_charge').jqm({
			overlay: 80
		});

		if($('mode').value=='edit') {
		    if($('view_from').value=="ssview"){
		        $('add_misc_btn').disabled = true;
		        $('empty_misc_btn').disabled = true;
		        $('or_main_cancel').style.display = "none";
		        $('or_main_submit').style.display = "none";       
		        $('iscash1').disabled = true;
		        $('iscash0').disabled = true;    
		    }else{
		        $('btndiscount').style.display = "none";            
		    } 

		    xajax_getPf($('refno').value);
		}else{
		    $('btndiscount').style.display = "none";
		}

		preset();

		if(($('area').value).toLowerCase() == 'doctor' && $('mode').value != 'edit'){
			xajax_addDoctor($('personell_nr').value);
		}
		update_total_misc();
		J.unblockUI();
});

function formatNumber(num,dec) {
	var nf = new NumberFormat(num);
	if (isNaN(dec)) dec = nf.NO_ROUNDING;
	nf.setPlaces(dec);
	return nf.toFormatted();
}

function parseFloatEx(x) {
	var str = x.toString().replace(/\,|\s/,'')
	return parseFloat(str)
}

function warnClear() {
	var items = document.getElementsByName('doc_nr[]');
	if (items.length == 0) return true;
	else return confirm('Performing this action will clear the order tray. Do you wish to continue?');
}

function preset(){
	if($('comp_id').value){
		var grantSelect = $('grant_type');
		var grantOption = document.createElement('option');
		grantOption.value = "company";
		grantOption.innerHTML = "COMPANY/PERSON";
		grantSelect.appendChild(grantOption);

		if(!$('refno').value || $('trans_amount').value){
			grantSelect.value="company";

			$('iscash0').checked = true;
			$('iscash1').checked = false;
			
			changeTransaction(0);
		}
	}
}

function empty_misc() {
	var table1 = $('misc_list').getElementsByTagName('tbody').item(0);
	table1.innerHTML = '<tr id="empty_misc_row"><td colspan="7">Professional Fee Charges is empty...</td></tr>';
	J("input[@name='doc_nr[]']").remove();
	J("input[@name='docfee_[]']").remove();
	update_total_misc();
}

function update_total_misc() {
	var misc = document.getElementsByName('doc_fee[]');

	var sub_total = 0;
	var discount_total = 0;
	var net_total = 0;

	for (var i=0; i<misc.length; i++) {
		if(misc[i].value){
			sub_total += parseFloat((misc[i].value).replace(",",""));
		}
	}

    net_total = parseFloat(sub_total - discount_total);

	J('#misc_subtotal').html(formatNumber(sub_total, 2));
	J('#misc_discount_total').html('-'+formatNumber(discount_total, 2));
	J('#misc_net_total').html(formatNumber(net_total, 2));

	if($('grant_type').value=="company" && $('transaction_type').value!=1){
    	xajax_getChargeCompanyBalance($('encounter_nr').value, 'DOC', $('refno').value);
    }
}

function validate()
{
    if($('view_from').value!="ssview")
	    var rep = confirm("Process this request?");
    else
        var rep = confirm("Apply discount?");
	if(rep) {
		var el = document.getElementsByName('doc_nr[]');
		if(el.length<=0) {
			alert("Cannot save this transaction. No doctors in the list.")
			return false;
		}else { 
			document.main_or_form.submit();
		}
	}
}

function show_popup_misc() {
    var pid = $('pid').value;
    var enc = $('encounter_nr').value;
    var current_dept_nr = $('current_dept_nr').value;
	
	return overlib(OLiframeContent('pf_request_tray.php?pid='+pid+'&encounter_nr='+enc+'&current_dept_nr='+current_dept_nr, 600, 330, 'fMiscFees', 0, 'no'),
		WIDTH,600, TEXTPADDING,0, BORDER,0,
		STICKY, SCROLL, CLOSECLICK, MODAL,
		CLOSETEXT, '<img src=../../images/close_red.gif border=0 >',
		CAPTION,'Add Doctor',
		MIDX,0, MIDY,0,
		STATUS,'Add Doctor');
}

function key_check(e, value) {
	 var character = String.fromCharCode(e.keyCode);
	 var number = /^\d+$/;
	 if ((e.keyCode==46 || e.keyCode==8 || e.keyCode==16 || e.keyCode==9 || (e.keyCode==191 || e.keyCode==111) || (e.keyCode>=36 && e.keyCode<=40) || (e.keyCode>=96 && e.keyCode<=105))) {
		 return true;
	 }
	 if (character.match(number)==null) {
		 return false;
	 }
	 else {
		 return true;
	 }
}

function key_check2(e, value) {
	 var character = String.fromCharCode(e.keyCode);
	 var number = /^\d+$/;
	 var reg = /^[-+]?[0-9]+((\.)|(\.[0-9]+))?$/;
	 if (character=='?') {
		 character = '.';
	 }
	 var text_value = value+character;
	 if ((e.keyCode==46 || e.keyCode==8 || e.keyCode==16 || e.keyCode==9 || (e.keyCode>=36 && e.keyCode<=40) || (e.keyCode>=96 && e.keyCode<=105))) {
		 return true;
	 }
	 if (character.match(number)==null) {
		 return false;
	 }
}

function changeTransaction(iscash){
    $('transaction_type').value = iscash;  
    if(iscash==0){
    	$('grant_type').show();
	    var ctype = $('grant_type').value;
	    if(ctype == "company"){
	    	chargeToCompany();
	    	$('cov_type').show();
	    	$('cov_amount').show();
	    }else{
	    	$('cov_type').hide();
	    	$('cov_amount').hide();
	    }
	}else{
		$('grant_type').value="";
		$('grant_type').hide();
		$('cov_type').update('');
		$('cov_amount').update('');
		$('cov_type').hide();
	    $('cov_amount').hide();
	}
    
}

function removeItem(id){
	var rmvRow = $("docListRow"+id);
	var table = $('misc_list');

	var rndx = rmvRow.rowIndex-1;
	table.deleteRow(rmvRow.rowIndex);
}

function editPf(id){
   var newPf = parseFloat($('docfee_'+id).value);
   if (!isNaN(newPf) && newPf!=null){
        document.getElementById("docfee_"+id).setAttribute("value",newPf);
   }else{
        document.getElementById("docfee_"+id).setAttribute("value",'');    
   }
}

function adjustPf(obj){
	var id = obj.getAttribute("dr_nr");

	if (isNaN(obj.value)) {
		obj.value = obj.getAttribute("prevValue");
		return false;
	}

	if (parseFloatEx(obj.value) != parseFloatEx(obj.getAttribute("prevValue"))) {
		update_total_misc();

		var totalAmount = parseFloat($('misc_net_total').innerHTML.replace(",",""));
		if(!checkExceedLimit(totalAmount, id)){
			var prevValue =  obj.getAttribute("prevValue");
			obj.value = prevValue;
			editPf(id);
			update_total_misc();
			return false;
		}
	}

	obj.setAttribute("prevValue",parseFloatEx(obj.value));
	return true;
}

function prepareAdd(doctor_nr, dr_name){
	var details = new Object();
	
	details.doctor_nr = doctor_nr;
	details.dr_name = dr_name;
	details.fee = "";

	appendDoctor(details);
}

function appendDoctor(details){
	var dBod = $('misc_list').getElementsByTagName('tbody')[0];
	var src = '';
	
	if(dBod){
		var items = document.getElementsByName('doc_nr[]');

		if(details){
			if(items){ //doctor is already in list
				for(var i=0; i<items.length; i++){
					if(items[i].value == details.doctor_nr){
						if(($('area').value).toLowerCase()!= 'doctor'){
							alert('Doctor is already in the list.');
						}
						return true;
					}
				}
			}
				
				if($('empty_misc_row')){
					$('empty_misc_row').remove();
				}

				deleteIcon = '<a href="javascript: removeItem(\''+details.doctor_nr+'\'); update_total_misc();">'+
								 '	<img src="../../images/btn_delitem.gif" border="0"/></a>';

				src = '<tr id="docListRow'+details.doctor_nr+'">'+
							'<td class="centerAlign">'+deleteIcon+'</td>'+
							'<td><input type="hidden" name="doc_nr[]" value="'+details.doctor_nr+'">'+details.doctor_nr+'</td>'+
							'<td>'+details.dr_name+'</td>'+
							'<td width="5%" align="right"><input type="text" class="segInput" id="docfee_'+details.doctor_nr+'" style="width:100%;text-align:center" dr_nr="'+details.doctor_nr+'" name="doc_fee[]" prevValue="'+details.fee+'" value="'+details.fee+'" onkeyup="editPf('+"'"+details.doctor_nr+"'"+');" onblur="adjustPf(this);"></td>'+
					  '</tr>';
		}else{
			src = "<tr id='empty_misc_row'><td colspan=\"10\">Doctor's list is currently empty...</td></tr>";
		}

		dBod.innerHTML += src;
		return true;
	}

	return false;
}

function addPfFromRef(info){
	for(var i=0; i<info.length; i++){
		var details = new Object();
		details.doctor_nr = info[i].doctor_nr;
		details.fee = info[i].fee;
		details.request_flag = info[i].request_flag;
		details.dr_name = info[i].dr_name;

		appendDoctor(details);
	}
}

function chargeToCompany(){
	var balance = $('charge_comp_balance').value;
	if(balance != "NO LIMIT"){
		var net_total = parseFloat($('misc_net_total').innerHTML.replace(",","")); //net total
		var	remaining_charged_company = parseFloat((balance - net_total));
		$('cov_amount').update('&nbsp;&nbsp;'+formatNumber(remaining_charged_company,2));
	}else{
		$('cov_amount').update('&nbsp;&nbsp;NO LIMIT');
	}
	$('cov_type').update("C/O Charge: ");
}

function checkExceedLimit(totalAmount, id){
	if($('grant_type').value=="company"){
		if($('cov_amount').innerHTML!="NO LIMIT"){
			xajax_getChargeCompanyBalance($('encounter_nr').value, 'DOC', $('refno').value);
			var compBalance = parseFloat($('charge_comp_balance').value);
					
			if(!array_of_servcode.in_array(id)){
				if(totalAmount > compBalance){
					var ans = confirm("You already exceeded the limit for this value. Are you sure you want to add this doctor's fee?");
					if(!ans){
						return false;
					}
				}
			}
		}
	}
	return true;
}

Array.prototype.in_array = function(p_val) {
    for(var i = 0, l = this.length; i < l; i++) {
        if(this[i] == p_val) {
            return true;
        }
    }
    return false;
}