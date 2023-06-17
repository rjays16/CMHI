<?php /* Smarty version 2.6.0, created on 2015-04-27 14:59:20
         compiled from order/form.tpl */ ?>
<script type="text/javascript">
function openWindow(url) {
	window.open(url,null,"width=800,height=600,menubar=no,resizable=yes,scrollbars=no");
}
</script>
<?php echo $this->_tpl_vars['sFormStart']; ?>

	<div style="width:760px" align="center">
        <span><?php echo $this->_tpl_vars['sWarning']; ?>
</span>
		<table border="0" align="center" style="margin-bottom:2px" >
			<tr>
				<td width="1"><strong style="white-space:nowrap">Pharmacy area</strong></td>
				<td width="*"><?php echo $this->_tpl_vars['sSelectArea']; ?>
</td>
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
									<?php echo $this->_tpl_vars['sIsCash']; ?>

									<?php echo $this->_tpl_vars['sIsCharge']; ?>

									<?php echo $this->_tpl_vars['sChargeType']; ?>

									<span style="display:none"><?php echo $this->_tpl_vars['sIsTPL']; ?>
</span>
								</td>
							</tr>

							<tr>
								<td align="right" valign="top"><strong>Name:</strong></td>
								<td width="1" valign="middle">
									<?php echo $this->_tpl_vars['sOrderEncNr']; ?>

									<?php echo $this->_tpl_vars['sOrderEncID']; ?>

									<?php echo $this->_tpl_vars['sOrderDiscountID']; ?>

									<?php echo $this->_tpl_vars['sOrderDiscount']; ?>

									<?php echo $this->_tpl_vars['sOrderName']; ?>

								</td>
								<td width="1" valign="middle">
									<?php echo $this->_tpl_vars['sSelectEnc']; ?>

								</td>
								<td valign="middle">
									<?php echo $this->_tpl_vars['sClearEnc']; ?>

								</td>
							</tr>
							<tr>
								<td align="right" valign="top"><strong>Address:</strong></td>
								<td colspan="3"><?php echo $this->_tpl_vars['sOrderAddress']; ?>
</td>
							</tr>
							<tr>
								<td></td>
								<td valign="top">
									<strong>Patient type:</strong>
									<?php echo $this->_tpl_vars['sOrderEncType']; ?>

									<span id="encounter_type_show" style="font-weight:bold;color:#000080"><?php echo $this->_tpl_vars['sOrderEncTypeShow']; ?>
</span>
								</td>
								<td colspan="2">
									<strong>Age:</strong> <?php echo $this->_tpl_vars['sAge']; ?>

								</td>
							</tr>
							<tr>
								<td></td>
								<td valign="top">
									PHIC no:
									<span id="phic_nr" style="font-weight:bold;color:#000080"><?php echo $this->_tpl_vars['sPhicNo']; ?>
</span>
								</td>
								<td colspan="2">
									<strong>Gender:</strong> <?php echo $this->_tpl_vars['sGender']; ?>

								</td>
							</tr>
                            <tr>
                                <td></td>
                                <td valign="top">
                                    Category:
                                    <span id="mem_category" style="font-weight:bold;color:#000080"><?php echo $this->_tpl_vars['sMemCategory']; ?>
</span>
                                </td>
                                <td colspan="2">
									<strong>Weight:</strong> <?php echo $this->_tpl_vars['sWeight']; ?>

								</td>
                            </tr>
                             <!-- added by mai 07-18-2014 -->
                            <tr>
                            	<td></td>
                                <td>
                                	C/O:
                                	<span id="comp_name"><?php echo $this->_tpl_vars['sCompName']; ?>
</span>
                                </td>
                            </tr>
                             <!-- end added by mai -->
						</table>
					</td>
					<td class="segPanel" align="center" nowrap="nowrap">
						<?php echo $this->_tpl_vars['sRefNo']; ?>

						<?php echo $this->_tpl_vars['sResetRefNo']; ?>

					</td>
					<td class="segPanel" align="center" valign="middle" nowrap="nowrap">
						<?php echo $this->_tpl_vars['sOrderDate'];  echo $this->_tpl_vars['sCalendarIcon']; ?>

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
<?php if ($this->_tpl_vars['ssView']): ?>
<?php else: ?>
								<td valign="middle">
									<div style=""><strong>Classification: </strong><span id="sw-class" style="font:bold 14px Arial;color:#006633"><?php echo $this->_tpl_vars['sSWClass']; ?>
</span></div>
									<div style="margin-top:5px; vertical-align:middle; "><?php echo $this->_tpl_vars['sDiscountShow']; ?>
</div>
								</td>
<?php endif; ?>
							</tr>
						</table>
						<?php echo $this->_tpl_vars['sBtnDiscounts']; ?>

					</td>
					<td class="segPanel" align="center" style="padding-bottom:5px;">
						<table border="0" cellpadding"0" cellspacing="0" style="font:normal 11.5px Arial;">
							<tr>
								<td align="right">
									<strong>Priority</strong>
								</td>
								<td>
									<?php echo $this->_tpl_vars['sNormalPriority']; ?>

									<?php echo $this->_tpl_vars['sUrgentPriority']; ?>

								</td>
							</tr>
							<tr>
								<td align="right" valign="top">
									<strong>Notes</strong>
								</td>
								<td>
									<?php echo $this->_tpl_vars['sComments']; ?>

								</td>
							</tr>
							<tr>
								<td style="font-weight: bold">Req. Dept:</td>
								<td style="color: red; font-weight: bold;"><?php echo $this->_tpl_vars['sReqDept']; ?>
</td>
							</tr>
							<tr>
								<td style="font-weight: bold">Req. By:</td>
								<td style="color: red; font-weight: bold;"><?php echo $this->_tpl_vars['sReqPers']; ?>
</td>
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
					<?php echo $this->_tpl_vars['sSearchService']; ?>

					<?php echo $this->_tpl_vars['sBtnAddExternal']; ?>

					<?php echo $this->_tpl_vars['sBtnAddItem']; ?>

					<?php echo $this->_tpl_vars['sBtnEmptyList']; ?>

					<?php echo $this->_tpl_vars['sBtnCoverage']; ?>

					<?php echo $this->_tpl_vars['sBtnPDF']; ?>

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
					<?php echo $this->_tpl_vars['sPrintPrescription']; ?>

					<?php echo $this->_tpl_vars['sPrintButton']; ?>

					<?php echo $this->_tpl_vars['sContinueButton']; ?>

					<?php echo $this->_tpl_vars['sBreakButton']; ?>

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
					<th width="5%" class="centerAlign" nowrap="nowrap">Rx</th>
					<!-- added by mai 10-01-2014 -->
					<th width="20%" nowrap="nowrap" class="centerAlign">Dosage</th>
					<th width="20%" nowrap="nowrap" class="centerAlign">Period</th>
					<!-- end added by mai -->
					<th width="5%" class="centerAlign" nowrap="nowrap">Order</th>
					<th width="10%" class="rightAlign" nowrap="nowrap">Price(Orig)</th>
					<th width="10%" class="rightAlign" nowrap="nowrap">Price(Adj)</th>
					<th width="10%" class="rightAlign" nowrap="nowrap">Total</th>
				</tr>
			</thead>
			<tbody>
<?php echo $this->_tpl_vars['sOrderItems']; ?>

			</tbody>
		</table>

		<!-- added by mai 10-02-2014 -->
		<table width="100%">
			<tbody>
				<tr><td style="text-align:left; font-weight: bold;"> Special Instructions: </td></tr>
				<tr>
					<td><?php echo $this->_tpl_vars['sInstructions']; ?>
</td>
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

<?php echo $this->_tpl_vars['sHiddenInputs']; ?>

<?php echo $this->_tpl_vars['jsCalendarSetup']; ?>

<br/>
<img src="" vspace="2" width="1" height="1"><br/>
<?php echo $this->_tpl_vars['sDiscountControls']; ?>

<span id="tdShowWarnings" style="font-family:Arial, Helvetica, sans-serif;font-size:12px;font-weight:normal;"></span>
<br/>

<div style="width:80%">
<?php echo $this->_tpl_vars['sUpdateControlsHorizRule']; ?>

<?php echo $this->_tpl_vars['sUpdateOrder']; ?>

<?php echo $this->_tpl_vars['sCancelUpdate']; ?>

</div>


</div>
<span style="font:bold 15px Arial"><?php echo $this->_tpl_vars['sDebug']; ?>
</span>
<?php echo $this->_tpl_vars['sFormEnd']; ?>

<?php echo $this->_tpl_vars['sTailScripts']; ?>
