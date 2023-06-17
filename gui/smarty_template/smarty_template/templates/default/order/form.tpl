{{* form.tpl  Form template for orders module (pharmacy & meddepot) 2004-07-04 Elpidio Latorilla *}}
<script type="text/javascript">
function openWindow(url) {
	window.open(url,null,"width=800,height=600,menubar=no,resizable=yes,scrollbars=no");
}
</script>
{{$sFormStart}}
	<div style="width:760px" align="center">
        <span>{{$sWarning}}</span>
		<table border="0" align="center" style="margin-bottom:2px" >
			<tr>
				<td width="1"><strong style="white-space:nowrap">Pharmacy area</strong></td>
				<td width="*">{{$sSelectArea}}</td>
			</tr>
		</table>
		<table border="0" cellspacing="2" cellpadding="2" align="center" width="100%">
			<tbody>
				<tr>
					<td class="segPanelHeader" width="*">
						Request Details
					</td>
					<td class="segPanelHeader" width="170">
						Reference No.
					</td>
					<td class="segPanelHeader" width="215">
						Request Date
					</td>
				</tr>
				<tr>
					<td rowspan="3" class="segPanel" align="left" valign="top">
						<table width="100%" border="0" cellpadding="2" cellspacing="0" style="font:normal 12px Arial" >
							<tr height="22">
								<td align="right">Type:</td>
								<td valign="top" colspan="3">
									{{$sIsCash}}
									{{$sIsCharge}}
									{{$sChargeType}}
									<span style="display:none">{{$sIsTPL}}</span>
								</td>
							</tr>

							<tr>
								<td align="right" valign="top"><strong>Name:</strong></td>
								<td width="1" valign="middle">
									{{$sOrderEncNr}}
									{{$sOrderEncID}}
									{{$sOrderDiscountID}}
									{{$sOrderDiscount}}
									{{$sOrderName}}
								</td>
								<td width="1" valign="middle">
									{{$sSelectEnc}}
								</td>
								<td valign="middle">
									{{$sClearEnc}}
								</td>
							</tr>
							<tr>
								<td align="right" valign="top"><strong>Address:</strong></td>
								<td colspan="3">{{$sOrderAddress}}</td>
							</tr>
							<tr>
								<td></td>
								<td valign="top">
									<strong>Patient type:</strong>
									{{$sOrderEncType}}
									<span id="encounter_type_show" style="font-weight:bold;color:#000080">{{$sOrderEncTypeShow}}</span>
								</td>
								<td colspan="2">
									<strong>Age:</strong> {{$sAge}}
								</td>
							</tr>
							<tr>
								<td></td>
								<td valign="top">
									PHIC no:
									<span id="phic_nr" style="font-weight:bold;color:#000080">{{$sPhicNo}}</span>
								</td>
								<td colspan="2">
									<strong>Gender:</strong> {{$sGender}}
								</td>
							</tr>
                            <tr>
                                <td></td>
                                <td valign="top">
                                    Category:
                                    <span id="mem_category" style="font-weight:bold;color:#000080">{{$sMemCategory}}</span>
                                </td>
                                <td colspan="2">
									<strong>Weight:</strong> {{$sWeight}}
								</td>
                            </tr>
                             <!-- added by mai 07-18-2014 -->
                            <tr>
                            	<td></td>
                                <td>
                                	C/O:
                                	<span id="comp_name">{{$sCompName}}</span>
                                </td>
                            </tr>
                             <!-- end added by mai -->
						</table>
					</td>
					<td class="segPanel" align="center" nowrap="nowrap">
						{{$sRefNo}}
						{{$sResetRefNo}}
					</td>
					<td class="segPanel" align="center" valign="middle" nowrap="nowrap">
						{{$sOrderDate}}{{$sCalendarIcon}}
					</td>
				</tr>
				<tr>
					<td class="segPanelHeader">Discounts</td>
					<td class="segPanelHeader">Request options</td>
				</tr>
				<tr>
					<td class="segPanel" align="center">
						<table style="font:bold 12px Arial">
							<tr>
{{if $ssView}}
{{else}}
								<td valign="middle">
									<div style=""><strong>Classification: </strong><span id="sw-class" style="font:bold 14px Arial;color:#006633">{{$sSWClass}}</span></div>
									<div style="margin-top:5px; vertical-align:middle; ">{{$sDiscountShow}}</div>
								</td>
{{/if}}
							</tr>
						</table>
						{{$sBtnDiscounts}}
					</td>
					<td class="segPanel" align="center" style="padding-bottom:5px;">
						<table border="0" cellpadding"0" cellspacing="0" style="font:normal 11.5px Arial;">
							<tr>
								<td align="right">
									<strong>Priority</strong>
								</td>
								<td>
									{{$sNormalPriority}}
									{{$sUrgentPriority}}
								</td>
							</tr>
							<tr>
								<td align="right" valign="top">
									<strong>Notes</strong>
								</td>
								<td>
									{{$sComments}}
								</td>
							</tr>
							<tr>
								<td style="font-weight: bold">Req. Dept:</td>
								<td style="color: red; font-weight: bold;">{{$sReqDept}}</td>
							</tr>
							<tr>
								<td style="font-weight: bold">Req. By:</td>
								<td style="color: red; font-weight: bold;">{{$sReqPers}}</td>
							</tr>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div style="width:760px" align="center">
		<table width="100%">
			<tr>
				<td nowrap="nowrap" width="30%" align="left">
					{{$sSearchService}}
					{{$sBtnAddExternal}}
					{{$sBtnAddItem}}
					{{$sBtnEmptyList}}
					{{$sBtnCoverage}}
					{{$sBtnPDF}}
				</td>

				<td nowrap="nowrap" width="20%">
					<input id="coverage" type="hidden" value="-1" />
					<span id="cov_type" style="font:bold 12px Tahoma"></span>
					<span id="cov_amount" style="font:bold 12px Tahoma;color:#000044"></span>

					<span style="font:bold 12px Tahoma; display:none">PHIC Coverage:</span>
					<span id="phic_cov" style="font:bold 12px Tahoma; color:#000044; display:none"></span>
					<img id="phic_ajax" src="images/ajax_spinner.gif" border="0" title="Loading..." style="display:none" />
				</td>
				<td>
				<td align="right">
					{{$sPrintPrescription}}
					{{$sPrintButton}}
					{{$sContinueButton}}
					{{$sBreakButton}}
				</td>
			</tr>
			<!-- added by mai 10-01-2014 -->
			<tr>
				<td nowrap="nowrap" width="30%" align="left"></td>
				<td nowrap="nowrap" width="20%"></td>
				<td nowrap="nowrap" width="20%"></td>
				<td align="right">
					<input type="checkbox" id="save_prescription" name="save_prescription"/><strong>Save Prescription</strong>
				</td>
			</tr>
			<!-- end added by mai -->
		</table>
		<table id="order-list" class="segList" border="0" cellpadding="0" cellspacing="0" width="100%">
			<thead>
				<tr id="order-list-header">
					<th width="5%" nowrap="nowrap"></th>
					<th width="5%" nowrap="nowrap" class="centerAlign">Item No.</th>
					<th width="10%" nowrap="nowrap" class="leftAlign">Item Description</th>
					<!-- <th width="10%" nowrap="nowrap" class="centerAlign">Consigned</th> -->
					<th width="7%" class="centerAlign" nowrap="nowrap">Rx</th>
					<!-- added by mai 10-01-2014 -->
					<th width="19%" nowrap="nowrap" class="centerAlign">Dosage</th>
					<th width="19%" nowrap="nowrap" class="centerAlign">Period</th>
					<!-- end added by mai -->
					<th width="5%" class="centerAlign" nowrap="nowrap">Order</th>
					<th width="10%" class="rightAlign" nowrap="nowrap">Price(Orig)</th>
					<th width="10%" class="rightAlign" nowrap="nowrap">Price(Adj)</th>
					<th width="10%" class="rightAlign" nowrap="nowrap">Total</th>
				</tr>
			</thead>
			<tbody>
{{$sOrderItems}}
			</tbody>
		</table>

		<!-- added by mai 10-02-2014 -->
		<table width="100%">
			<tbody>
				<tr><td style="text-align:left; font-weight: bold;"> Special Instructions: </td></tr>
				<tr>
					<td>{{$sInstructions}}</td>
				</tr>
			</tbody>
		</table>
		<!-- end added by mai -->

		<table width="100%" style="font-size: 12px; margin-top: 5px" border="0" cellspacing="1">
			<tbody>
				<tr>
					<td width="*" align="right" style="background-color:#ffffff; padding:4px" height=""><strong>Sub-Total</strong></th>
					<td id="show-sub-total" align="right" width="17% "style="background-color:#e0e0e0; color:#000000; font-family:Arial; font-size:15px; font-weight:bold"></th>
				</tr>
				<tr>
					<td align="right" style="background-color:#ffffff; padding:4px"><strong>Discount</strong></th>
					<td id="show-discount-total" align="right" style="background-color:#cfcfcf; color:#006600; font-family:Arial; font-size:15px; font-weight:bold"></th>
				</tr>
				<tr>
					<td align="right" style="background-color:#ffffff; padding:4px"><strong>Net Total</strong></th>
					<td id="show-net-total" align="right" style="background-color:#bcbcbc; color:#000066; font-family:Arial; font-size:15px; font-weight:bold"></th>
				</tr>
			</tbody>
		</table>
	</div>

{{$sHiddenInputs}}
{{$jsCalendarSetup}}
<br/>
<img src="" vspace="2" width="1" height="1"><br/>
{{$sDiscountControls}}
<span id="tdShowWarnings" style="font-family:Arial, Helvetica, sans-serif;font-size:12px;font-weight:normal;"></span>
<br/>

<div style="width:80%">
{{$sUpdateControlsHorizRule}}
{{$sUpdateOrder}}
{{$sCancelUpdate}}
</div>


</div>
<span style="font:bold 15px Arial">{{$sDebug}}</span>
{{$sFormEnd}}
{{$sTailScripts}}
