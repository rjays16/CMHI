<?php /* Smarty version 2.6.0, created on 2017-01-06 01:09:38
         compiled from nursing/ward_occupancy_list.tpl */ ?>

<table cellspacing="0" width="100%" border="0">
<tbody>
	<tr>
		<td class="wardlisttitlerow" width="1%">&nbsp;</td>
		<td class="wardlisttitlerow" width="9%"><?php echo $this->_tpl_vars['LDRoom']; ?>
</td>
		<td class="wardlisttitlerow" width="6%"><?php echo $this->_tpl_vars['LDBed']; ?>
</td>
		<td class="wardlisttitlerow" width="*"><?php echo $this->_tpl_vars['LDFamilyName']; ?>
, <?php echo $this->_tpl_vars['LDName']; ?>
</td>
		<td class="wardlisttitlerow" width="11%"><?php echo $this->_tpl_vars['LDBirthDate']; ?>
</td>
		<td class="wardlisttitlerow" width="13%"><?php echo $this->_tpl_vars['LDPatNr']; ?>
</td>
		<!--<td class="wardlisttitlerow" width="13%"><?php echo $this->_tpl_vars['LDInsuranceType']; ?>
</td>-->
		<td class="wardlisttitlerow" width="13%"><?php echo $this->_tpl_vars['LDCaseNo']; ?>
</td>
		
		<!-- added by mai 07-04-2014 -->
		<td class="wardlisttitlerow" width="5%"><?php echo $this->_tpl_vars['LDMgh']; ?>
</td>
		<td class="wardlisttitlerow" width="8%"><?php echo $this->_tpl_vars['LDMembership']; ?>
</td>
		<!-- end added by mai -->
		<td class="wardlisttitlerow" width="11%"><?php echo $this->_tpl_vars['LDOptions']; ?>
</td>
	</tr>

	<?php echo $this->_tpl_vars['sOccListRows']; ?>


 </tbody>
</table>