<style>
	* {
		font: normal 12px Arial;
	}
	table.transaction_details_table {
		border-collapse: collapse;

	}
	table.transaction_details_table tr td {
		/**border: 1px #000000 solid;    **/
	}
	table.transaction_details_table table tr td {
		border: none;
	}
	#transaction_date_display {
		background: #FFFFFF;
		padding: 3px;
		border: 1px #99BBE8 solid;
		width: 170px;
	}
	.date_time_picker {
		cursor: pointer;
	}
</style>
<div>
{{$form_start}}
<div style="width:700px; margin-top:10px" align="center">
	<table border="0" cellspacing="2" cellpadding="2" align="center" width="100%">
		<tbody>
			<tr>
				<td class="segPanelHeader" width="*">Request Details</td>
			</tr>
			<tr>
				<td class="segPanel" align="left" valign="top">
					<table  width="100%" class="transaction_details_table" cellpadding="3" cellspacing="0" style="font:normal 12px Arial; padding:2px" >
						<tr>
							<td width="80%">
								<table width="100%" cellpadding="0" cellspacing="0">
									<tr>
										<td align="right" nowrap="nowrap"><strong>Type:</strong></td>
										<td>
											<div style="font:bold 18px Arial; color:#006000">
												{{html_radios name="transaction_type" options=$transaction_types selected=$transaction_type id="transaction_type"}}
                                                {{$sIsCash}}
                                                {{$sIsCharge}} {{$sChargeTyp}}
											</div>
										</td>
									</tr>
									<tr>
										<td width="30%" align="right" nowrap="nowrap"><strong>Name:</strong></td>
										<td>&nbsp;&nbsp;{{$patient_name}}</td>
									</tr>
									<tr>
										<td width="30%" align="right" nowrap="nowrap"><strong>Patient Type:</strong></td>
										<td>&nbsp;&nbsp;{{$encounter_type}}</td>
									</tr>
                                    <tr>
                                        <td width="30%" align="right" nowrap="nowrap"><strong>Classification:</strong></td>
                                        <td>&nbsp;&nbsp;{{$sClassification}}</td>
                                    </tr>
                                    <!-- added by mai 07-19-2014 -->
                                    <tr>
                                    	<td width="30%" align="right" nowrap="nowrap">C/O: </td>
                                    	<td>&nbsp;&nbsp;{{$sCompName}}</td>
                                    </tr>
                                    <tr>
                                    	<td align="right" nowrap="nowrap" id="cov_type"></td>
                                    	<td id="cov_amount" style="font-weight: bold; color:red; "></td>
                                    </tr>
                                    <!-- end added by mai -->
								</table>
							</td>
							<td>
								<table width="100%" cellpadding="0" cellspacing="0">
									<tr>
										<td width="30%" align="right" nowrap="nowrap"><strong>Reference No:</strong></td>
										<td>&nbsp;{{$reference_no}}</td>
									</tr>
									<tr>
										<td align="right"><b>Request Date:</b></td>
										<td>
											<table cellpadding="0" cellspacing="2">
												<tr>
													<td valign="bottom">{{$transaction_date_display}}</td>
													<td>{{$transaction_date_picker}}{{$transaction_date}}{{$transaction_date_calendar_script}}</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</tbody>
	</table>
	<div id="or_main_schedule" align="left">
	<br/>
	<fieldset>
		<legend>Professional Fee Charges</legend>
		<table width="100%" cellpadding="0" cellspacing="0">
			<tr>
				<div style="float:left;">{{$add_misc_btn}}&nbsp;{{$empty_misc_btn}}</div>
				<div id="or_main_schedule" style="float:right;">
					{{$other_charges_submit}}
					{{$other_charges_cancel}}
				</div>
			</tr>
		</table>
		<table class="segList" width="100%" id="misc_list" cellpadding="0" cellspacing="0">
			<thead>
				<tr>
					<th width="1%" nowrap="nowrap"></th>
					<th width="15%" align="center">Doctor's #</th>
					<th align="center">Doctor's Name</th>
					<th width="5%" align="center">Professional Fee</th>
				</tr>
			</thead>
			<tbody>
				<tr id="empty_misc_row"><td colspan="4">Professional Fee Charges is empty...</td></tr>
			</tbody>
		</table>
		<table width="100%" style="font-size: 12px; margin-top: 5px" border="0" cellspacing="1">
		<tbody>
			<tr>
				<td width="*" align="right" style="padding:4px" height=""><strong>Sub-Total</strong></th>
				<td id="misc_subtotal" align="right" width="17% "style="background-color:#e0e0e0; color:#000000; font-family:Arial; font-size:15px; font-weight:bold"></td>
			</tr>
			<tr>
				<td align="right" style="padding:4px"><strong>Discount</strong></th>
				<td id="misc_discount_total" align="right" style="background-color:#cfcfcf; color:#006600; font-family:Arial; font-size:15px; font-weight:bold"></td>
			</tr>
			<tr>
				<td align="right" style="padding:4px"><strong>Net Total</strong></th>
				<td id="misc_net_total" align="right" style="background-color:#bcbcbc; color:#000066; font-family:Arial; font-size:15px; font-weight:bold"></td>
			</tr>
		 </tbody>
	</table>
	</fieldset>
	</div>
    {{$sBtnDiscounts}}
{{$pid}}
{{$transaction_type}} 
{{$view_from}}
{{$discount}}
{{$encounter_nr}}
{{$impression}}
{{$submitted}}
{{$mode}}
{{$area}}
{{$form_end}}

</div>


