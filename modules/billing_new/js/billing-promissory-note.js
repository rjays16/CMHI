var dialogSelEnc;

function preset(){
	var pageSelEnc = "../../modules/billing_new/billing-search-final-bill.php?";
    dialogSelEnc = $j('<div></div>')
                    .html('<iframe style="border: 0px; " src="' + pageSelEnc + '" width="100%" height=400px></iframe>')
                    .dialog({
                    autoOpen: false,
                    modal: true,
                    show: 'fade',
                    hide: 'fade',
                    height: 'auto',
                    width: '800',
                    title: 'Select Registered Person',
                    position: 'top',
                  });
     dialogSelEnc.dialog('open');
}

function closeSelEncDiaglog(){
    dialogSelEnc.dialog('close');
}

function clickHandler(enc, bill_dt) {
   dialogSelEnc.dialog('close');
}

function checkFields(){

    if($('pid').value){
        var r = confirm("Are you sure you want to save this promissory note?");

        if(r == true){  
            if(!$('sum_pay').checked && !$('install_pay').checked){
                alert('Unable to save note. Please select payment type.');
                return false;
            }

            if(!($('amount').value).trim() || isNaN($('amount').value)){
                alert('Unable to save note. Please indicate a proper amount.');
                $('amount').focus();
                return false;
            }

            var is_sum = 0, is_install = 0;

            if($('sum_pay').checked){
                is_sum = 1;
            }else if($('install_pay').checked){
                is_install = 1;
            }

            xajax_savePromi($('mode_of_promi').value, $('duedate').value, $('encounter_nr').value, $('amount').value, $('remarks').value, is_sum, is_install, $('refno_promi').value);
        }    
    }   
}


function resetData(due_date, is_sum, is_install, amount, remarks, format_duedate){
    console.log(format_duedate);
    $('show_duedate').innerHTML = format_duedate;
    $('duedate').value = due_date;
    $('sum_pay').checked = is_sum;
    $('install_pay').checked = is_install;
    $('amount').value = amount;
    $('remarks').value = remarks;
}

function getData(refno){
    xajax_getPromiDetails(refno);
}

function printPromi(){
    if($('mode_of_promi').value == 'edit'){
        var refno = $('refno_promi').value;
        window.open('billing-promissory-print.php?refno='+refno, "_blank", "width=800, height=800");
    }else{
        alert("Please save the promissory note details first!");
    }
}