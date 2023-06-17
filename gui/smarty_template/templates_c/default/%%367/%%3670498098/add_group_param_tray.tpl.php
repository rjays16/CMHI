<?php /* Smarty version 2.6.0, created on 2017-01-11 17:57:27
         compiled from laboratory/test_manager/add_group_param_tray.tpl */ ?>
<?php echo $this->_tpl_vars['form_start']; ?>

<div>
	<table class="segPanel" align="center" cellpadding="2" cellspacing="2" border="0" width="100%">
		<tbody>
			<tr>
				<td align="left"><b>Parameter Group Name :</b></td>
				<td><input type="text" class="segInput" id="param_grp_name" name="param_grp_name" style="width:140px"/></td>
				<td><?php echo $this->_tpl_vars['searchParamGrp'];  echo $this->_tpl_vars['saveParamGrp']; ?>
</td>
			</tr>
		</tbody>
	</table>
</div>
<br/>
<div id="param-group-list">
</div>
<?php echo $this->_tpl_vars['form_end']; ?>