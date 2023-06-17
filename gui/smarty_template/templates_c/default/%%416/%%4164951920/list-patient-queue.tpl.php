<?php /* Smarty version 2.6.0, created on 2017-01-06 07:38:25
         compiled from registration_admission/list-patient-queue.tpl */ ?>
<style>

#list_queue table{
	text-align: left;
	width: 50%;
	margin-bottom: 5px;
}

.pending{
	color: red;
}

.active{
	color: green;
}

.onqueue{
	color: #ffff00;
}

</style>

<div id="list_queue" width="80%">
	<?php echo $this->_tpl_vars['sList']; ?>

</div>