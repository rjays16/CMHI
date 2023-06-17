<?php /* Smarty version 2.6.0, created on 2017-02-01 12:01:09
         compiled from ../../modules/codetable/dynamicfields/flag/field_flag_editview.tpl */ ?>
<?php require_once(SMARTY_DIR . 'core' . DIRECTORY_SEPARATOR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'cat', '../../modules/codetable/dynamicfields/flag/field_flag_editview.tpl', 2, false),)), $this); ?>
<input id="<?php echo $this->_tpl_vars['options']['id']; ?>
" name="<?php echo $this->_tpl_vars['name']; ?>
"  type="hidden" value="<?php echo $this->_tpl_vars['value']; ?>
"/>
<input id="<?php echo ((is_array($_tmp=$this->_tpl_vars['options']['id'])) ? $this->_run_mod_handler('cat', true, $_tmp, '_option') : smarty_modifier_cat($_tmp, '_option')); ?>
" class="input" type="checkbox" <?php if ($this->_tpl_vars['value'] != 0): ?>checked="checked"<?php endif; ?> onclick="$('<?php echo $this->_tpl_vars['options']['id']; ?>
').value=this.checked?1:0"/>