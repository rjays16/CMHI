<?php /* Smarty version 2.6.0, created on 2017-01-11 18:01:14
         compiled from laboratory/test_manager/add_request_tray.tpl */ ?>
<?php echo $this->_tpl_vars['form_start']; ?>

<div style="width:550px;">
	<table width="100%">
		<tbody>
			<tr>
				<td style="font: bold 12px Arial; background-color: rgb(229, 229, 229); color: rgb(45, 45, 45);">
					<div style="padding:4px 2px; padding-left:10px; ">
						Laboratory Service Section &nbsp;&nbsp;&nbsp;&nbsp;
						<?php echo $this->_tpl_vars['labSections']; ?>

						<img src="../../../gui/img/common/default/redpfeil_l.gif">
					</div>
				</td>
			</tr>
			<tr>
				<td style="font:bold 12px Arial; background-color:#e5e5e5; color: #2d2d2d" >
					<div style="padding:4px 2px; padding-left:10px; ">
						Search Laboratory Test<?php echo $this->_tpl_vars['labSearchInput'];  echo $this->_tpl_vars['labSearchBtn']; ?>

					</div>
				</td>
			</tr>
		</tbody>
	</table>
	<div id="service_list">
	</div>
</div>
<?php echo $this->_tpl_vars['form_end']; ?>