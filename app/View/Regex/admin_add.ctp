<div class="whitelists form">
<?php echo $this->Form->create('Regex');?>
	<fieldset>
		<legend><?php echo __('Add Import Whitelist'); ?></legend>
	<?php
		echo $this->Form->input('regex');
		echo $this->Form->input('replacement');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Regexs'), array('admin' => true, 'action' => 'index'));?></li>
	</ul>
</div>
