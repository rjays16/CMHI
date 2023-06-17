var currentPage=0, lastPage=0;
var FIRST_PAGE=1, PREV_PAGE=2, NEXT_PAGE=3, LAST_PAGE=4, SET_PAGE=0;

function display(str) {
	if($('ajax_display')) $('ajax_display').innerHTML = str.replace('\n','<br>');
}

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

function setPagination(pageno, lastpage, pagen, total) {
	currentPage=parseInt(pageno);
	lastPage=parseInt(lastpage);
	firstRec = (parseInt(pageno)*pagen)+1;
	if (currentPage==lastPage)
		lastRec = total;
	else
		lastRec = (parseInt(pageno)+1)*pagen;
	if (parseInt(total))
		$("pageShow").innerHTML = '<span>Showing '+(formatNumber(firstRec))+'-'+(formatNumber(lastRec))+' out of '+(formatNumber(parseInt(total)))+' record(s)</span>'
	else
		$("pageShow").innerHTML = ''
	$("pageFirst").className = (currentPage>0 && lastPage>0) ? "segSimulatedLink" : "segDisabledLink";
	$("pagePrev").className = (currentPage>0 && lastPage>0) ? "segSimulatedLink" : "segDisabledLink";
	$("pageNext").className = (currentPage<lastPage) ? "segSimulatedLink" : "segDisabledLink";
	$("pageLast").className = (currentPage<lastPage) ? "segSimulatedLink" : "segDisabledLink";
}

function jumpToPage(el, jumpType, set) {
	if (el.className=="segDisabledLink") return false;
	if (lastPage==0) return false;
	switch(jumpType) {
		case FIRST_PAGE:
			if (currentPage==0) return false;
			startAJAXSearch('search',0);
		break;
		case PREV_PAGE:
			if (currentPage==0) return false;
			startAJAXSearch('search',currentPage-1);
		break;
		case NEXT_PAGE:
			if (currentPage >= lastPage) return false;
			startAJAXSearch('search',parseInt(currentPage)+1);
		break;
		case LAST_PAGE:
			if (currentPage >= lastPage) return false;
			startAJAXSearch('search',lastPage);
		break;
	}
}

//added by mai 10-01-2014

function prepareAddExternal(){
	if(window.parent.$('encounter_type_show').innerHTML != 'WALK-IN'){
		if(($('search').value).trim()){
			var details = new Object();

			details.id = $('search').value;
			details.name = $('search').value;
			details.desc =  $('search').value;
			details.prcCash = 0;
			details.prcCharge=  0;
			details.prcCashSC=  0;
			details.prcChargeSC=  0;
			details.isSocialized=  0;
			details.prcDiscounted=  0;
			details.isExternal = 1;

			details.priceincreaseperoomPHIC = 0;
			details.priceincreaseperoomNONPHIC = 0;
			details.Ifhasroom = 0;

			details.qty = 1;
			details.dosage = "";
			details.period_count="";
			details.period_interval = "";

			var list = window.parent.document.getElementById('order-list');
			result = window.parent.appendOrder(list,details);

			if (!result)  {
				alert('Failed to add item...');
			}

			window.parent.$('save_prescription').checked = true;
		}
	}else{
		alert("Cannot add external medicines for walk-in");
	}
}

//end added by mai

function prepareAdd(id) {
	var details = new Object();
	var cash = parseFloatEx($('cash'+id).value),
			charge = parseFloatEx($('charge'+id).value),
			qty=1;

	if ( isNaN(cash) || (cash < 0) || isNaN(charge) || (charge < 0) ) {
		alert("Price not set. Cannot add the product to the order yet...")
		return false
	}

	details.id = $('id'+id).innerHTML
	details.name = $('name'+id).innerHTML
	details.desc = $('desc'+id).innerHTML
	details.prcCash = parseFloatEx($('cash'+id).value)
	details.prcCharge= parseFloatEx($('charge'+id).value)
	details.prcCashSC= parseFloatEx($('cashsc'+id).value)
	details.prcChargeSC= parseFloatEx($('chargesc'+id).value)
	details.priceincreaseperoomPHIC= parseFloatEx($('priceincreaseperoomPHIC'+id).value)
	details.priceincreaseperoomNONPHIC= parseFloatEx($('priceincreaseperoomNONPHIC'+id).value)
	details.Ifhasroom= parseFloatEx($('Ifhasroom'+id).value)
	//added by julius 02-07-2017


	//end

	details.isSocialized= $('soc'+id).value
	details.prcDiscounted= parseFloatEx($('d'+id).value)
	details.isExternal = 0;
	details.dosage = "";
	details.period_count="";
	details.period_interval = "";
	
	//alert(details.prcDiscounted);
//	if ($('noqty'+id).value != '1') {
//		while (qty) {
//		}
//		while (isNaN(parseFloat(qty)) || parseFloat(qty)<=0) {
//			qty = prompt("Enter quantity:")
//			if (qty === null) return false;
//		}
//	}

	details.qty = qty;

	var list = window.parent.document.getElementById('order-list')
	result = window.parent.appendOrder(list,details)
	if (result)  {
		//alert('Item added to order list...');
	}
	else
		alert('Failed to add item...');
	if (window.parent && window.parent.refreshDiscount) window.parent.refreshDiscount()
}

function clearList(listID) {
	// Search for the source row table element
	var list=$(listID),dRows, dBody;
	if (list) {
		dBody=list.getElementsByTagName("tbody")[0];
		if (dBody) {
			dBody.innerHTML = "";
			return true;	// success
		}
		else return false;	// fail
	}
	else return false;	// fail
}

function addProductToList(listID, details ) {
	// ,id, name, desc, cash, charge, cashsc, chargesc, d, soc

	var list=$(listID), dRows, dBody, rowSrc;
	var i;
	if (list) {
		dBody=list.getElementsByTagName("tbody")[0];
		dRows=dBody.getElementsByTagName("tr");

		// get the last row id and extract the current row no.

		if (typeof(details)=="object") {
			var id = details.id,
				name = details.name,
				desc = details.desc,
				cash = details.cash,
				//added by julius
				priceincreaseperoomPHIC = details.priceincreaseperoomPHIC,
				priceincreaseperoomNONPHIC = details.priceincreaseperoomNONPHIC,
				Ifhasroom = details.Ifhasroom,

				//end
				charge = details.charge,
				cashsc = details.cashsc,
				chargesc = details.chargesc,
                qty = details.qty,
				d = details.d,
				soc = details.soc,
				noqty = details.noqty;
                isCritical = details.isCritical;

			var cashHTML, chargeHTML;
			var cashSeniorHTML, chargeSeniorHTML;

            if(isCritical) {
                qtyColumn = '<span style="color: red">' + qty + '</span>';
            } else {
                qtyColumn = '<span style="color: green">' + qty + '</span>';
            }

            //added by moylua for checking qty in inventory
            var disableButton = '';
			if (d>=0)

				// alert(priceincreaseperoomPHIC);

			rowSrc = '<tr onclick=\"prepareAdd(\''+id+'\')\" >'+
									'<td>'+
										'<span id="name'+id+'" style="font:bold 12px Arial;color:#000066">'+name+'</span><br />'+
										'<div style=""><div id="desc'+id+'" style="font:normal 11px Arial; color:#404040">'+desc+'</div></div>'+
									'</td>'+
									'<td align="center">'+
										'<input id="soc'+id+'" type="hidden" value="'+soc+'"/>'+
										'<span id="id'+id+'" style="font:bold 11px Arial;color:#660000">'+id+'</span></td>'+
									'<td align="right" '+(cash<=0 ? '' : '')+'>'+
										'<input id="noqty'+id+'" type="hidden" value="'+(noqty ? '1' : '0')+'"/>'+
										'<input id="d'+id+'" type="hidden" value="'+d+'"/>'+
										'<input id="cash'+id+'" type="hidden" value="'+cash+'"/>'+
											(d!=cash ? '<span style="color:#008000">' : '')+
											(d>0 ?
												formatNumber(d,2) : formatNumber(cash,2))+
											(d!=cash ? '</span>' : '')+
										'</td>'+
									'<td align="right">'+
										'<input id="charge'+id+'" type="hidden" value="'+charge+'"/>'+(charge>0 ? formatNumber(charge,2) : '-')+'</td>'+
									'<td align="right">'+
										'<input id="cashsc'+id+'" type="hidden" value="'+cashsc+'"/>'+(cashsc>0 ? formatNumber(cashsc,2) : '-')+'</td>'+
									'<td align="right">'+
										'<input id="chargesc'+id+'" type="hidden" value="'+chargesc+'"/>'+(chargesc>0 ? formatNumber(chargesc,2) : '-')+
										'<input type="hidden" id = priceincreaseperoomPHIC' + id + ' value = "' + priceincreaseperoomPHIC + '"/>' +
										'<input type="hidden" id = priceincreaseperoomNONPHIC' + id + ' value = "' + priceincreaseperoomNONPHIC + '"/>' +
										'<input type="hidden" id = Ifhasroom' + id + ' value = "' + Ifhasroom + '"/>' +
									'</td>'+
                                    '<td align="center">' +
                                        qtyColumn
                                    +'</td>'+
									'<td>'+
                                        '<input type="hidden" id = qty' + id + ' value = "' + qty + '"/>' +
										'<input type="button" value=">" style="font-weight:bold; padding:0px 2px" '+
											'onclick="prepareAdd(\''+id+'\')"' + disableButton +
										'/>'+
									'</td>'+
								'</tr>';
		}
		else {
			rowSrc = '<tr><td colspan="8" style="">No such product exists...</td></tr>';
		}
		dBody.innerHTML += rowSrc;
	}
}