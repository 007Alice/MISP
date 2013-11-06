<div class="roles form">
<?php echo $this->Form->create('Role');?>
	<fieldset>
		<legend><?php echo __('Edit Role'); ?></legend>
	<?php
		echo $this->Form->input('name');?>
		<?php echo $this->Form->input('permission', array('label' => 'Permissions', 'type' => 'select', 'options' => $options), array('value' => '3'));?>
		<div class = 'input clear'></div>

		<?php echo $this->Form->input('perm_sync', array('type' => 'checkbox', 'label' => 'Sync Actions', 'style' => 'vertical-align: middle'));?>
		<?php echo $this->Form->input('perm_admin', array('type' => 'checkbox', 'label' => 'Administration Actions', 'style' => 'vertical-align: middle'));?>
		<?php echo $this->Form->input('perm_audit', array('type' => 'checkbox', 'label' => 'Audit Actions', 'style' => 'vertical-align: middle'));?>
		<?php echo $this->Form->input('perm_auth', array('type' => 'checkbox', 'label' => 'Auth Key Access', 'style' => 'vertical-align: middle'));?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>
<?php 
	echo $this->element('side_menu', array('menuList' => 'admin', 'menuItem' => 'editRole'));

	$this->Js->get('#RolePermission')->event('change', 'deactivateActions()');
	
	$this->Js->get('#RolePermSync')->event('change', 'checkPerms("RolePermSync")');
	$this->Js->get('#RolePermAdmin')->event('change', 'checkPerms("RolePermAdmin")');
	$this->Js->get('#RolePermAudit')->event('change', 'checkPerms("RolePermAudit")');
?>

<script type="text/javascript">
// only be able to tick perm_sync if manage org events and above.

function deactivateActions() {
	var e = document.getElementById("RolePermission");
	if (e.options[e.selectedIndex].value == '0' || e.options[e.selectedIndex].value == '1') {
		document.getElementById("RolePermSync").checked = false;
		document.getElementById("RolePermAdmin").checked = false;
		document.getElementById("RolePermAudit").checked = false;
	}
}

function checkPerms(id) {
	var e = document.getElementById("RolePermission");
	if (e.options[e.selectedIndex].value == '0' || e.options[e.selectedIndex].value == '1') {
		document.getElementById(id).checked = false;
	}
}

</script>
<?php echo $this->Js->writeBuffer();