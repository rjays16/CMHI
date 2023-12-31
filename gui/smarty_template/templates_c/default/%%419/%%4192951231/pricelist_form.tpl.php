<?php /* Smarty version 2.6.0, created on 2017-01-30 12:03:54
         compiled from price_adjustments/pricelist_form.tpl */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title></title>

<?php if (count($_from = (array)$this->_tpl_vars['css_and_js'])):
    foreach ($_from as $this->_tpl_vars['script']):
?>
		<?php echo $this->_tpl_vars['script']; ?>

<?php endforeach; unset($_from); endif; ?>

</head>

<body>
<?php echo $this->_tpl_vars['form_start']; ?>

<div id="new_package" align="center" style="width:70%;">
	<ul>
		<li><a href="#edit_price" onClick="assignValue(1);"><span>Edit Prices</span></a></li>
		<li><a href="#view_history" onClick="assignValue(0);"><span>View History</span></a></li>
	</ul>
	<div id="edit_price">
		<div>
		 <table align="center" cellpadding="2" cellspacing="2" border="0" width="100%" style="border-collapse: collapse; border: 1px solid rgb(204, 204, 204);">
				<tbody>
						<tr>
							<td class="segPanelHeader" colspan="3"><strong>Search Services</strong></td>
						</tr>
						<tr>
							<td  width="20%" nowrap="nowrap" align="right" class="segPanel"><b>Patient Type</b></td>
							<td width="80%" nowrap="nowrap" class="segPanel"><?php echo $this->_tpl_vars['sAreas']; ?>
</td>
						</tr>
						<tr>
							<td  width="20%" nowrap="nowrap" align="right" class="segPanel"><b>Area</b></td>
							<td width="80%" nowrap="nowrap" class="segPanel"><?php echo $this->_tpl_vars['sCostCenters']; ?>
</td>
						</tr>
						<tr>
							<td  width="20%" nowrap="nowrap" align="right" class="segPanel"><b>Service name</b></td>
							<td width="80%" nowrap="nowrap" class="segPanel"><?php echo $this->_tpl_vars['search_service'];  echo $this->_tpl_vars['searchserv_btn']; ?>
</td>
						</tr>
				</tbody>
			</table>
		 </div>
		 <div id="pricelist" style="padding: 2px; height: 300px; overflow-y: auto; background-color: rgb(229, 229, 229); border: 1px solid rgb(140, 173, 192);">

			 <div  style="display:block; border:1px solid #8cadc0; overflow-y:hidden; width:100%; background-color:#e5e5e5">
				<table class="segList" width="100%" border="0" cellpadding="0" cellspacing="0">
					<thead>
						<tr class="nav">
						<th colspan="10">
							<!--<div id="pageFirst" class="segDisabledLink" style="float:left" onclick="jumpToPage(this,FIRST_PAGE), initialize(modifiedCode,modifiedCash,modifiedCharge,modLen)">-->
							<div id="pageFirst" class="segDisabledLink" style="float:left" onclick="jumpToPage(this,FIRST_PAGE)">
								<img title="First" src="../../images/start.gif" border="0" align="absmiddle"/>
								<span title="First">First</span>
							</div>
							<!--<div id="pagePrev" class="segDisabledLink" style="float:left" onclick="jumpToPage(this,PREV_PAGE), initialize(modifiedCode,modifiedCash,modifiedCharge,modLen)">-->
							<div id="pagePrev" class="segDisabledLink" style="float:left" onclick="jumpToPage(this,PREV_PAGE)">
								<img title="Previous" src="../../images/previous.gif" border="0" align="absmiddle"/>
								<span title="Previous">Previous</span>
							</div>
							<div id="pageShow1" style="float:left; margin-left:10px">
								<span></span>
							</div>
							<!--<div id="pageLast" class="segDisabledLink" style="float:right" onclick="jumpToPage(this,LAST_PAGE), initialize(modifiedCode,modifiedCash,modifiedCharge,modLen)">-->
							<div id="pageLast" class="segDisabledLink" style="float:right" onclick="jumpToPage(this,LAST_PAGE)">
								<span title="Last">Last</span>
								<img title="Last" src="../../images/end.gif" border="0" align="absmiddle"/>
							</div>
							<!--<div id="pageNext" class="segDisabledLink" style="float:right" onclick="jumpToPage(this,NEXT_PAGE), initialize(modifiedCode,modifiedCash,modifiedCharge,modLen)">-->
							<div id="pageNext" class="segDisabledLink" style="float:right" onclick="jumpToPage(this,NEXT_PAGE)">
								<span title="Next">Next</span>
								<img title="Next" src="../../images/next.gif" border="0" align="absmiddle"/>
							</div>
						</th>
						</tr>
					</thead>
				</table>
			 </div>
			 <div style="display:block; border:1px solid #8cadc0; overflow-y:hidden; width:100%; background-color:#e5e5e5">
				<table id="PriceList" class="segList" width="100%" border="0" cellpadding="0" cellspacing="0">
						<thead>
								<tr>
										<th rowspan="3" width="1%"></th>
										<th rowspan="3" width="15%" align="left">Name</th>
										<th rowspan="3" width="15%" align="center">Service Code</th>
										<th rowspan="3" width="10%" align="center">Price in Cash</th>
										<th rowspan="3" width="10%" align="center">Price in Charge</th>
								</tr>
						</thead>
						<tbody id="PriceList-body">
								<tr><td colspan="6" style="">No service area selected...</td></tr>
						</tbody>
				</table>
			 </div>
		 </div>
		 <br>
		 <div>
		 <table align="center" cellpadding="2" cellspacing="2" border="1" width="100%" style="border-collapse: collapse; border: 1px solid rgb(204, 204, 204);">
				<tbody>
						<tr>
							<td width="10%" nowrap="nowrap" align="left" class="segPanel"><b>Options</b></td>
							<td class="segPanel" style="white-space:nowrap;vertical-align:bottom"><?php echo $this->_tpl_vars['saveBtn'];  echo $this->_tpl_vars['cancelBtn']; ?>
</td>
						</tr>
				</tbody>
			</table>
		 </div>
	</div>
	<div class="blues" id="view_history">
		<table align="center" cellpadding="2" cellspacing="2" border="0" width="100%" style="border-collapse: collapse; border: 1px solid rgb(204, 204, 204);">
			<tbody>
				<tr>
					<td class="segPanelHeader" colspan="3"><strong>Search Service Pricelist</strong></td>
				</tr>
				<tr>
						<td  width="20%" nowrap="nowrap" align="right" class="segPanel"><b>Patient Type</b></td>
						<td width="80%" nowrap="nowrap" class="segPanel"><?php echo $this->_tpl_vars['sAreas2']; ?>
</td>
				</tr>
				<tr>
						<td  width="20%" nowrap="nowrap" align="right" class="segPanel"><b>Area</b></td>
						<td width="80%" nowrap="nowrap" class="segPanel"><?php echo $this->_tpl_vars['sCostCenters2']; ?>
</td>
				</tr>
				<tr>
					<td  width="20%" nowrap="nowrap" align="right" class="segPanel"><b>Service name</b></td>
					<td width="80%" nowrap="nowrap" class="segPanel"><?php echo $this->_tpl_vars['searchkeyText'];  echo $this->_tpl_vars['searchdate_btn']; ?>
</td>
				</tr>

			</tbody>
		</table>
		<div class="segContentPane">
		<table class="jedList" width="100%" border="0" cellpadding="0" cellspacing="0">
				<thead>
						<tr class="nav">
								<th colspan="10">
                                        <!--modified by jasper 12/05/2012-->
										<!--<div id="pageFirst" class="segDisabledLink" style="float:left" onclick="jumpToPage(this,FIRST_PAGE), initialize(modifiedCode,modifiedCash,modifiedCharge,modLen)">-->
										<div id="pageFirst2" class="segDisabledLink" style="float:left" onclick="jumpToPage(this,FIRST_PAGE);">
												<img title="First" src="../../images/start.gif" border="0" align="absmiddle"/>
												<span title="First">First</span>
										</div>
										<!--<div id="pagePrev" class="segDisabledLink" style="float:left" onclick="jumpToPage(this,PREV_PAGE), initialize(modifiedCode,modifiedCash,modifiedCharge,modLen)">-->
										<div id="pagePrev2" class="segDisabledLink" style="float:left" onclick="jumpToPage(this,PREV_PAGE);">
												<img title="Previous" src="../../images/previous.gif" border="0" align="absmiddle"/>
												<span title="Previous">Previous</span>
										</div>
										<div id="pageShow2" style="float:left; margin-left:10px">
												<span></span>
										</div>
										<!--<div id="pageLast" class="segDisabledLink" style="float:right" onclick="jumpToPage(this,LAST_PAGE), initialize(modifiedCode,modifiedCash,modifiedCharge,modLen)">-->
										<div id="pageLast2" class="segDisabledLink" style="float:right" onclick="jumpToPage(this,LAST_PAGE);">
												<span title="Last">Last</span>
												<img title="Last" src="../../images/end.gif" border="0" align="absmiddle"/>
										</div>
										<!--<div id="pageNext" class="segDisabledLink" style="float:right" onclick="jumpToPage(this,NEXT_PAGE), initialize(modifiedCode,modifiedCash,modifiedCharge,modLen)">-->
										<div id="pageNext2" class="segDisabledLink" style="float:right" onclick="jumpToPage(this,NEXT_PAGE);">
												<span title="Next">Next</span>
												<img title="Next" src="../../images/next.gif" border="0" align="absmiddle"/>
										</div>
								</th>
						</tr>
				</thead>
		</table>
		<table id="PriceHistory" class="jedList" width="100%" border="0" cellpadding="0" cellspacing="0">
				<thead>
						<tr>
								<th width="1%"></th>
								<th width="20%" align="left">Name</th>
								<th width="10%" align="center">Service Code</th>
								<th width="5%" align="center">Price in Cash</th>
								<th width="5%" align="center">Price in Charge</th>
								<th width="12%" align="center">Date Created</th>
								<th width="10%" align="center">Area</th>
								<th width="5%">Options</th>
								<th colspan="2"></th>
						</tr>
				</thead>
				<tbody id="PriceHistory-body">
					<tr><td colspan="10" style="">No date selected...</td></tr>
				</tbody>
		</table>
		<img id="ajax-loading" src="<?php echo '<?='; ?>
 $root_path <?php echo '?>'; ?>
images/loading6.gif" align="absmiddle" border="0" style="display:none"/>
		</div>
	</div>
</div>
<div id="service_changes">
</div>
<br/>
<input type="hidden" name="key" id="key">
<input type="hidden" name="pagekey" id="pagekey">
<?php echo $this->_tpl_vars['date_cal']; ?>

<?php echo $this->_tpl_vars['form_end']; ?>


</body>

</html>