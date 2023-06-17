<?php /* Smarty version 2.6.0, created on 2017-01-06 12:13:14
         compiled from nursing/discharge_patient_form.tpl */ ?>

<ul>

<div class="prompt"><?php echo $this->_tpl_vars['sPrompt']; ?>
</div>

<form action="<?php echo $this->_tpl_vars['thisfile']; ?>
" name="discform" method="post" onSubmit="return pruf(this)">

	<table border=0 cellspacing="1">
		<tr>
			<td colspan=2 class="adm_input">
				<?php echo $this->_tpl_vars['sBarcodeLabel']; ?>
 <?php echo $this->_tpl_vars['img_source']; ?>

			</td>
		</tr>
		<tr>
			<td class="adm_item"><?php echo $this->_tpl_vars['LDLocation']; ?>
:</td>
			<td class="adm_input"><?php echo $this->_tpl_vars['sLocation']; ?>
</td>
		</tr>
			<td class="adm_item"><span id="w_date"></span><?php echo $this->_tpl_vars['LDDate']; ?>
:</td>
			<td class="adm_input">
				<?php if ($this->_tpl_vars['released']): ?>
					<?php echo $this->_tpl_vars['x_date']; ?>

				<?php else: ?>
					<?php echo $this->_tpl_vars['sDateInput']; ?>
 <?php echo $this->_tpl_vars['sDateMiniCalendar']; ?>
 <?php echo $this->_tpl_vars['jsCalendarSetup']; ?>

				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<td class="adm_item"><span id="w_time"></span><?php echo $this->_tpl_vars['LDClockTime']; ?>
:</td>
			<td class="adm_input">
				<?php if ($this->_tpl_vars['released']): ?>
					<?php echo $this->_tpl_vars['x_time']; ?>

				<?php else: ?>
					<?php echo $this->_tpl_vars['sTimeInput']; ?>

				<?php endif; ?>
			</td>
		</tr>
		<tr id="row_disctype" style="display:none">
			<td class="adm_item"><?php echo $this->_tpl_vars['LDReleaseType']; ?>
:</td>
			<td class="adm_input">
				<?php echo $this->_tpl_vars['sDischargeTypes']; ?>

			</td>
		</tr>
				<tr id="row_deaths" style="display:none">
						<td class="adm_item">Death Options : </td>
						<td class="adm_input"><?php echo $this->_tpl_vars['sDeathRows']; ?>
</td>
				</tr>
		<tr id="row_notes" style="display:none">
			<td class="adm_item"><?php echo $this->_tpl_vars['LDNotes']; ?>
:</td>
			<td class="adm_input">
				<?php if ($this->_tpl_vars['released']): ?>
					<?php echo $this->_tpl_vars['info']; ?>

				<?php else: ?>
					<textarea name="info" cols=40 rows=3></textarea>
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<!--<td class="adm_item"><?php echo $this->_tpl_vars['LDNurse']; ?>
:</td> -->
			<td class="adm_item">Encoded By:</td>
			<td class="adm_input">
				<?php if ($this->_tpl_vars['released']): ?>
					<?php echo $this->_tpl_vars['encoder']; ?>

				<?php else: ?>
					<input type="text" name="encoder" readonly="1" size=50 maxlength=30 value="<?php echo $this->_tpl_vars['encoder']; ?>
">
				<?php endif; ?>
			</td>
		</tr>

	<?php if ($this->_tpl_vars['bShowValidator']): ?>
		<tr id='row_undo' style="display:none">
			<td class="adm_item"><?php echo $this->_tpl_vars['stoggleIcon']; ?>
</td>
			<td class="adm_input"><?php echo $this->_tpl_vars['sToggleText']; ?>
</td>
		</tr>

		<tr id='row_mgh' style="display:none">
			<td class="adm_item"><?php echo $this->_tpl_vars['stoggleIcon2']; ?>
</td>
			<td class="adm_input"><?php echo $this->_tpl_vars['sToggleText2']; ?>
</td>
		</tr>

		<tr id='row_discharge' style="display:none">
			<td class="adm_item"><?php echo $this->_tpl_vars['pbSubmit']; ?>
</td>
			<td class="adm_input"><?php echo $this->_tpl_vars['sValidatorCheckBox']; ?>
 <?php echo $this->_tpl_vars['LDYesSure']; ?>
</td>
		</tr>

		<tr id='row_undo_discharge' style="display:none">
			<td class="adm_item"><?php echo $this->_tpl_vars['sUndoDischarge']; ?>
</td>
			<td class="adm_input"><?php echo $this->_tpl_vars['sUndoDischargeText']; ?>
</td>
		</tr>

	<?php endif; ?>

	</table>

	<?php echo $this->_tpl_vars['sHiddenInputs']; ?>


</form>

<?php echo $this->_tpl_vars['pbCancel']; ?>


</ul>