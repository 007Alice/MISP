<div class="users form">
<?php echo $this->Form->create('User');?>
	<fieldset>
		<legend><?php __('Edit User'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('email');
   		echo $this->Form->input('password');
		if ($isAdmin) echo $this->Form->input('org');
		else echo $this->Form->input('org', array('disabled' => 'disabled'));
		echo $this->Form->input('autoalert');
		echo $this->Form->input('nids_sid');
		echo $this->Form->input('gpgkey');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>

<div class="actions">
	<ul>
		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('User.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('User.id'))); ?></li>
        <li>&nbsp;</li>
        <?php echo $this->element('actions_menu'); ?>
	</ul>
</div>
