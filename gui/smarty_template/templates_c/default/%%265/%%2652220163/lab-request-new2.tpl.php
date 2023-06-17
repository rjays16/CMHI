<?php /* Smarty version 2.6.0, created on 2017-01-06 10:35:04
         compiled from laboratory/lab-request-new2.tpl */ ?>
<div align="center" style="font:bold 12px Tahoma; color:#990000; "><?php echo $this->_tpl_vars['sWarning']; ?>
</div><br />

<?php echo $this->_tpl_vars['sFormStart']; ?>

	<span><?php echo $this->_tpl_vars['sWARNERLAB']; ?>
</span>
	<table border="0" cellspacing="2" cellpadding="2" width="95%" align="center">
		<tbody>
			<tr>
				<td class="segPanelHeader" width="*">
					Request Details
				</td>
				<td class="segPanelHeader" width="15%">
					<!--Reference No.-->
					Batch No.
				</td>
				<td class="segPanelHeader" width="20%">
					Request Date
				</td>
			</tr>
			<tr>
				<td rowspan="3" class="segPanel" align="center" valign="top">
					<table width="95%" border="0" cellpadding="1" cellspacing="0" style="font-size:11px" >
						<tr>
							<td><strong>Transaction type</strong>
							&nbsp;&nbsp;&nbsp;
								<?php echo $this->_tpl_vars['sIsCash']; ?>

								<?php echo $this->_tpl_vars['sIsCharge']; ?>
<span id="type_charge" style="display:none"><?php echo $this->_tpl_vars['sChargeTyp']; ?>
</span>
								<!--&nbsp;&nbsp;&nbsp;<?php echo $this->_tpl_vars['sIsTPL']; ?>
-->
							</td>
						</tr>
					</table>
					<table width="95%" border="0" cellpadding="2" cellspacing="0" style="margin-top:8px">
						
						<tr>
							<td align="right" width="1" valign="top"><strong>Name</strong></td>
							<td width="1" valign="middle">
								<?php echo $this->_tpl_vars['sOrderEncID']; ?>

								<?php echo $this->_tpl_vars['sOrderName']; ?>

							</td>
						</tr>
					<tr>
						<td colspan="4">
						<table width="100%" border="0" cellpadding="2" cellspacing="0" style="margin-top:8px">
							
							<tr>
                                <td valign="top" align="left" width="5%"><strong>PHIC no</strong></td>
                                <td valign="top" align="left" width="1%">
                                    <strong>:</strong>
                                </td>
                                <td valign="top" width="30%">
                                    <span id="phic_nr" style="font-weight:bold;color:#000080"><?php echo $this->_tpl_vars['sPhicNo']; ?>
</span>
                                </td>
                                <!-- added by mai 07-8-2014 display company name-->
                                <td valign="top" align="left" width="5%"><strong>C/O </strong></td>
                                <td valign="top" align="left" width="1%">
                                    <strong>:</strong>
                                </td>
                                <td valign="top" width="30%">
                                    <span id="comp_name" style="font-weight:bold;color:#000080"><?php echo $this->_tpl_vars['sCompName']; ?>
</span>
                                </td>
                                <!-- end added by mai -->
                            </tr>
                            <tr>
                                <td valign="top" align="left" width="5%"><strong>Category</strong></td>
                                <td valign="top" align="left" width="1%">
                                    <strong>:</strong>
                                </td>
                                <td valign="top" width="30%">
                                    <span id="mem_category" style="font-weight:bold;color:#000080"><?php echo $this->_tpl_vars['sMemCategory']; ?>
</span>
                                </td>
                            </tr> 
                           	<tr> 
								<td nowrap="nowrap" width="20%">
									<input id="coverage" type="hidden" value="-1" />
									<span id="cov_type" style="font:bold 12px Tahoma"></span>

									<span style="font:bold 12px Tahoma; display:none">PHIC Coverage:</span>
									<span id="phic_cov" style="font:bold 12px Tahoma; color:#000044; display:none"></span>
									<img id="phic_ajax" src="images/ajax_spinner.gif" border="0" title="Loading..." style="display:none" />
								</td>
								<td></td>
								<td valign="top" align="left" width="1%">
                                    <span id="cov_amount" style="font:bold 12px Tahoma;color:#000044"></span>
                                </td>
							</tr>   	
						</table>
					 </td>
					</table>
				</td>

				<td class="segPanel" align="center">
					<?php echo $this->_tpl_vars['sRefNo']; ?>

					<?php echo $this->_tpl_vars['sResetRefNo']; ?>

				</td>
				<td class="segPanel" align="center" valign="middle">
					<?php echo $this->_tpl_vars['sOrderDate']; ?>

					<?php echo $this->_tpl_vars['sCalendarIcon']; ?>

					<!--<strong style="font-size:10px">mm/dd/yyyy</strong>-->
				</td>
			</tr>
			<tr>
				<td class="segPanelHeader">Discounts</td>
				<td class="segPanelHeader">Request Options</td>
			</tr>

			<tr>
				<td class="segPanel" align="center" valign="top">
						<table width="100%">
							<tr>
								<td valign="middle">
									<div style=""><strong>Classification: </strong><span id="sw-class" style="font:bold 14px Arial; color:#0000FF;"><?php echo $this->_tpl_vars['sClassification']; ?>
</span></div>
									<div style="margin-top:5px; vertical-align:middle; "><?php echo $this->_tpl_vars['sDiscountShow']; ?>
</div>
									<br>
									<span id='override_row' style="display:none; font:bold 11px Tahoma;">Discount:
										<br>
										Free All <?php echo $this->_tpl_vars['sFree']; ?>

										<br><?php echo $this->_tpl_vars['sAdjustedAmount']; ?>
</span>
								</td>
								<td><?php echo $this->_tpl_vars['sDiscountInfo']; ?>
</td>
							</tr>
							</table>
						<?php echo $this->_tpl_vars['sBtnDiscounts']; ?>

					</td>
				<!-- -->
				<td class="segPanel" align="center" valign="top">
					<table>
						 <tr>
							 <td valign="top" width="5%"><strong>Priority</strong></td>
							 <td valign="top" width="5%"><?php echo $this->_tpl_vars['sNormalPriority']; ?>
</td>
							 <td valign="top" width="5%"><?php echo $this->_tpl_vars['sUrgentPriority']; ?>
</td>
						 </tr>
						 <tr>
							 <td valign="top" width="5%" colspan="3"><strong style="float:left; margin-top:10px">Comments </strong></td>
						 </tr>
						 <tr>
							 <td align="center" valign="middle" width="5%" colspan="3"><?php echo $this->_tpl_vars['sComments']; ?>
</td>
						 </tr>
					</table>
				</td>
			</tr>
		</tbody>
	</table>


	<div align="left" style="width:95%">
		<table width="100%">
			<tr>
				<td>
					<?php echo $this->_tpl_vars['sSearchService']; ?>

				</td>
				<td align="right">
					<?php echo $this->_tpl_vars['sContinueButton']; ?>

					<?php echo $this->_tpl_vars['sBreakButton']; ?>

				</td>
			</tr>
		</table>
		<table id="order-list" class="segList" border="0" cellpadding="0" cellspacing="0" width="100%">
			<thead>
				<tr id="order-list-header">
					<th width="4%" nowrap align="left">Cnt : <span id="counter">0</span></th>
					<th width="0.5%"></th>
					<th width="5%" nowrap align="left">&nbsp;&nbsp;Code</th>
					<th width="*" nowrap align="left">&nbsp;&nbsp;Service Description</th>
					<th colspan="3" width="10%" nowrap align="center">Date & Time</th>
					<!--<th width="5%" nowrap align="left">for Monitor</th>
					<th width="5%" nowrap align="left">Every Hr</th>-->
					<!--<th width="5%" nowrap align="left">No of Takes</th>-->
					<th width="5%" nowrap align="left">W/ Sample<input type="checkbox" id="check_all" name="check_all" onclick="setSampleCheckInStatus();"></th>
					<th width="15%" align="center">Original Price</th>
					<!--<th width="13%">Discount Type</th> -->
					<th width="17%" align="center">Net Price</th>
				</tr>
			</thead>
			<tbody>
<?php echo $this->_tpl_vars['sOrderItems']; ?>


			<tbody id="socialServiceNotes" style="display:none">
				<tr>
					<td colspan="12"><?php echo $this->_tpl_vars['sSocialServiceNotes']; ?>
</td>
				</tr>
			</tbody>
		</table>

		<table width="100%" style="font-size: 12px; margin-top: 5px" border="0" cellspacing="1">
			<tr>
			<tr>
					<td width="*" align="right" style="background-color:#ffffff; padding:4px" height=""><strong>Sub-Total</strong>
					<td id="show-sub-total" align="right" width="17% "style="background-color:#e0e0e0; color:#000000; font-family:Arial; font-size:15px; font-weight:bold">
			</tr>
				<tr>
					<td align="right" style="background-color:#ffffff; padding:4px"><strong>Discount</strong>
					<td id="show-discount-total" align="right" style="background-color:#cfcfcf; color:#006600; font-family:Arial; font-size:15px; font-weight:bold">
				</tr>
				<tr>
					<td align="right" style="background-color:#ffffff; padding:4px"><strong>Net Total</strong>
					<td id="show-net-total" align="right" style="background-color:#bcbcbc; color:#000066; font-family:Arial; font-size:15px; font-weight:bold">
				</tr>


		</table>
		<div align="center">
			<?php echo $this->_tpl_vars['sViewPDF']; ?>
 &nbsp; <?php echo $this->_tpl_vars['sClaimStub']; ?>

		</div>
	</div>


<?php echo $this->_tpl_vars['sHiddenInputs']; ?>

<?php echo $this->_tpl_vars['jsCalendarSetup']; ?>

<?php echo $this->_tpl_vars['sIntialRequestList']; ?>

<br/>
<img src="" vspace="2" width="1" height="1"><br/>
<?php echo $this->_tpl_vars['sDiscountControls']; ?>

<span id="tdShowWarnings" style="font-family:Arial, Helvetica, sans-serif;font-size:12px;font-weight:normal;"></span>
<br/>



<span style="font:bold 15px Arial"><?php echo $this->_tpl_vars['sDebug']; ?>
</span>
<?php echo $this->_tpl_vars['sFormEnd']; ?>

<?php echo $this->_tpl_vars['sTailScripts']; ?>

<hr/>
<!--
<input type="button" name="btnRefreshDiscount" id="btnRefreshDiscount" onclick="refreshDiscount()" value="Refresh Discount">
<input type="button" name="btnRefreshTotal" id="btnRefreshTotal" onclick="refreshTotal()" value="Refresh Totals">
-->
<?php echo $this->_tpl_vars['sRefreshDiscountButton']; ?>

<?php echo $this->_tpl_vars['sRefreshTotalButton']; ?>