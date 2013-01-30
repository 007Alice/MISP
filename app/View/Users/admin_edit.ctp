<div class="users form">
<?php echo $this->Form->create('User');?>
	<fieldset>
		<legend><?php echo __('Admin Edit User'); ?></legend>
	<?php
		echo $this->Form->input('email');
		echo $this->Form->input('password');
		echo $this->Form->input('confirm_password', array('type' => 'password', 'div' => array('class' => 'input password required')));
if ($currentOrg == 'ADMIN') {
	echo $this->Form->input('org');
}
		echo $this->Form->input('role_id', array('label' => 'Role'));	// TODO ACL, User edit role_id.
		echo $this->Form->input('autoalert');
		echo $this->Form->input('authkey');
		echo $this->Form->input('nids_sid');
		echo $this->Form->input('termsaccepted');
		echo $this->Form->input('change_pw', array('type' => 'checkbox', 'label' => 'Change Password'));
		echo $this->Form->input('newsread');
		echo $this->Form->input('gpgkey');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>
<div class="actions">
	<ul>
		<?php echo $this->element('actions_menu'); ?>
	</ul>
</div>
