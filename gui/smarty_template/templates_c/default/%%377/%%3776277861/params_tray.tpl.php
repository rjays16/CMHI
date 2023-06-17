<?php /* Smarty version 2.6.0, created on 2017-01-11 17:43:44
         compiled from laboratory/test_manager/params_tray.tpl */ ?>
<?php echo $this->_tpl_vars['form_start']; ?>

<div class="dashlet" style="margin-top:10px;">
	<table align="center" cellpadding="2" cellspacing="2" border="0" width="100%" style="border-collapse: collapse; border: 1px solid rgb(204, 204, 204);">
		<tbody>
			<tr>
				<table class="segPanel" align="center" cellpadding="2" cellspacing="2" border="0" width="100%" style="border-collapse: collapse; border: 1px solid rgb(204, 204, 204);">
					<tr>
						<td style="width:50px" nowrap="nowrap" align="right"><b>Test Group</b></td>
						<td nowrap="nowrap"><?php echo $this->_tpl_vars['testGroup'];  echo $this->_tpl_vars['testGroupid']; ?>
</td>
					</tr>
					<tr>
						<td style="width:50px" nowrap="nowrap" align="right"><b>Parameter Name</b></td>
						<td nowrap="nowrap"><?php echo $this->_tpl_vars['paramName']; ?>
</td>
					</tr>
					<tr>
						<td style="width:50px" nowrap="nowrap" align="right"><b>Assign Param Group</b></td>
						<td nowrap="nowrap"><?php echo $this->_tpl_vars['paramGroups']; ?>
</td>
					</tr>
					<tr>
						<td style="width:50px" nowrap="nowrap" align="right" ><b>Data Type</b></td>
						<td nowrap="nowrap"><?php echo $this->_tpl_vars['dataTypes']; ?>
</td>
					</tr>
					<tr>
						<td style="width:50px" nowrap="nowrap" align="right"><b>Order Number</b></td>
						<td nowrap="nowrap"><?php echo $this->_tpl_vars['orderNumber']; ?>
</td>
					</tr>
					<tr>
						<td style="width:50px" nowrap="nowrap" align="right"><b>Norm Type</b></td>
						<td nowrap="nowrap"><?php echo $this->_tpl_vars['gender']; ?>
</td>
					</tr>
					<tr>
						<td style="width:50px" nowrap="nowrap" align="right"><b>SI Range</b></td>
						<td nowrap="nowrap"><?php echo $this->_tpl_vars['siLow']; ?>
-<?php echo $this->_tpl_vars['siHigh']; ?>
&nbsp;<?php echo $this->_tpl_vars['siUnit']; ?>
</td>
					</tr>
					<tr>
						<td style="width:50px" nowrap="nowrap" align="right"><b>CU Range</b></td>
						<td nowrap="nowrap"><?php echo $this->_tpl_vars['cuLow']; ?>
-<?php echo $this->_tpl_vars['cuHigh']; ?>
&nbsp;<?php echo $this->_tpl_vars['cuUnit']; ?>
</td>
					</tr>
					<tr>
						<td style="width:50px" nowrap="nowrap" align="center" colspan = '2'><?php echo $this->_tpl_vars['saveBtn']; ?>
&nbsp;<?php echo $this->_tpl_vars['cancelBtn']; ?>
</td>
					</tr>
				</table>
			</tr>
		</tbody>
	</table>
</div>
<?php echo $this->_tpl_vars['service_code']; ?>

<?php echo $this->_tpl_vars['mode']; ?>

<?php echo $this->_tpl_vars['param_id']; ?>

<?php echo $this->_tpl_vars['form_end']; ?>
