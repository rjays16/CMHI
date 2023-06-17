<?php /* Smarty version 2.6.0, created on 2016-02-04 12:07:24
         compiled from system_admin/fis_mapping_update.tpl */ ?>
<?php echo $this->_tpl_vars['form_start']; ?>

<div style="width:780px" align="center">
	<table border="0" cellspacing="1" cellpadding="0" width="80%" align="center" style="">
		<tbody>
			<tr>
				<td colspan="4" class="segPanelHeader">Create or Update Items account in accounting</td>
			</tr>
			<tr>
				<td class="segPanel">
					<table border="0" cellspacing="1" cellpadding="2" width="100%" style="font-family:Arial, Helvetica, sans-serif">
						<tbody>
							<tr>
								<td width="25%" align="right"><strong>Name</strong>&nbsp;</td>
								<td align="left"><?php echo $this->_tpl_vars['sItemName']; ?>
</td>
							</tr>
							<tr>
								<td width="25%" align="right"><strong>Cost Center Area</strong>&nbsp;</td>
								<td align="left"><?php echo $this->_tpl_vars['sCostArea']; ?>
</td>
							</tr>
							<tr>
								<td width="25%" align="right"><strong>Account transaction</strong>&nbsp;</td>
								<td align="left"><?php echo $this->_tpl_vars['sTransaction']; ?>
</td>
							</tr>

							<?php echo $this->_tpl_vars['sDebit']; ?>

							<?php echo $this->_tpl_vars['sDebitAccount']; ?>

							
							<?php echo $this->_tpl_vars['sCredit']; ?>

							<?php echo $this->_tpl_vars['sCreditAccount']; ?>

							
							<?php echo $this->_tpl_vars['sIncome']; ?>

							<?php echo $this->_tpl_vars['sIncomeAccount']; ?>

							
							<?php echo $this->_tpl_vars['sCash']; ?>

							<?php echo $this->_tpl_vars['sCashAccount']; ?>

							
							<?php echo $this->_tpl_vars['sTax']; ?>

							<?php echo $this->_tpl_vars['sTaxAccount']; ?>

							
							<?php echo $this->_tpl_vars['sInventory']; ?>

							<?php echo $this->_tpl_vars['sInventoryAccount']; ?>

							
							<?php echo $this->_tpl_vars['sCOGS']; ?>

							<?php echo $this->_tpl_vars['sCOGSCredit']; ?>

							<tr>
								<td align="right"><?php echo $this->_tpl_vars['sbtnsave']; ?>
</td>
								<td align="left"><?php echo $this->_tpl_vars['sbtnCancel']; ?>
</td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
		</tbody>
	</table>
</div>


<?php echo $this->_tpl_vars['form_end']; ?>