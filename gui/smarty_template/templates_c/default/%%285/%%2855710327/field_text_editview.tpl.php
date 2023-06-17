<?php /* Smarty version 2.6.0, created on 2017-02-01 12:01:09
         compiled from ../../modules/codetable/dynamicfields/text/field_text_editview.tpl */ ?>
<?php require_once(SMARTY_DIR . 'core' . DIRECTORY_SEPARATOR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', '../../modules/codetable/dynamicfields/text/field_text_editview.tpl', 2, false),array('modifier', 'escape', '../../modules/codetable/dynamicfields/text/field_text_editview.tpl', 2, false),)), $this); ?>
<?php if ($this->_tpl_vars['options']['rows'] <= 1): ?>
<input id="<?php echo $this->_tpl_vars['options']['id']; ?>
" name="<?php echo $this->_tpl_vars['options']['name']; ?>
" class="<?php echo ((is_array($_tmp=@$this->_tpl_vars['options']['className'])) ? $this->_run_mod_handler('default', true, $_tmp, 'input') : smarty_modifier_default($_tmp, 'input')); ?>
" type="text" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['value'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" style="width:<?php echo $this->_tpl_vars['options']['width']; ?>
" <?php if ($this->_tpl_vars['options']['required']): ?>required="required"<?php endif; ?> />
<?php else: ?>
<textarea id="<?php echo $this->_tpl_vars['options']['id']; ?>
" name="<?php echo $this->_tpl_vars['options']['name']; ?>
" class="<?php echo ((is_array($_tmp=@$this->_tpl_vars['options']['className'])) ? $this->_run_mod_handler('default', true, $_tmp, 'input') : smarty_modifier_default($_tmp, 'input')); ?>
" rows="<?php echo $this->_tpl_vars['options']['rows']; ?>
" style="width:<?php echo $this->_tpl_vars['width']; ?>
" <?php if ($this->_tpl_vars['options']['required']): ?>required="required"<?php endif; ?> ><?php echo $this->_tpl_vars['value']; ?>
</textarea>
<?php endif; ?>
<script type="text/javascript">
$('<?php echo $this->_tpl_vars['options']['id']; ?>
').validator=function() {
o = $J(this);
if (o.is('[required]')) {
return o.val() !== '';
}
else
return true;
};
</script>