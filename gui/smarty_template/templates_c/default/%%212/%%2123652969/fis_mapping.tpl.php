<?php /* Smarty version 2.6.0, created on 2016-02-04 12:06:13
         compiled from system_admin/fis_mapping.tpl */ ?>
<?php echo $this->_tpl_vars['form_start']; ?>

<div style="width:800px">
	<table border="0" cellspacing="1" cellpadding="0" width="80%" align="center" style="">
		<tbody>
			<tr>
				<td colspan="4" class="segPanelHeader">Search Details</td>
			</tr>
			<tr>
				<td class="segPanel">
					<table border="0" cellspacing="1" cellpadding="2" width="100%" style="font-family:Arial, Helvetica, sans-serif">
						<tbody>
							<tr>
								<td width="20%" align="right"><strong>Account transaction</strong>&nbsp;</td>
								<td align="left"><?php echo $this->_tpl_vars['accountTransaction']; ?>
</td>
							</tr>
							<tr>
								<td width="20%" align="right"><strong>Cost Center Area</strong>&nbsp;</td>
								<td align="left"><?php echo $this->_tpl_vars['serviceArea']; ?>
</td>
							</tr>
							<tr>
								<td width="20%" align="right"><strong>Search Service/Item</strong></td>
								<td align="left">
									<?php echo $this->_tpl_vars['patientOptions']; ?>

									<span id="p_name" style="display;"><?php echo $this->_tpl_vars['pSearchName']; ?>
</span>
									<span id="p_pid" style="display:none"><?php echo $this->_tpl_vars['pSearchId']; ?>
</span>
									<span id="p_enc" style="display:none"><?php echo $this->_tpl_vars['pSearchEnc']; ?>
</span>
									<?php echo $this->_tpl_vars['search_btn'];  echo $this->_tpl_vars['AddAccount_btn']; ?>

								</td>
							</tr>
							<tr>
						</tbody>
					</table>
				</td>
			</tr>
		</tbody>
	</table>
	<div class="dashlet" style="margin-top:20px">
		<table width="100%" cellpadding="0" cellspacing="0" border="0" class="dashletHeader" style="font: bold 11px Tahoma;">
			<tbody>
				<tr>
					<td width="30%" valign="top"><h1 style="white-space:nowrap">List of Services</h1></td>
				</tr>
			</tbody>
		</table>
		<div id="request-list"></div>
	</div>
</div>


<?php echo $this->_tpl_vars['form_end']; ?>