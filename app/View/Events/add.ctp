<div class="events form">
<?php echo $this->Form->create('Event');?>
	<fieldset>
		<legend><?php echo __('Add Event'); ?></legend>
<?php
echo $this->Form->input('date');
if ('true' == Configure::read('CyDefSIG.sync')) {
	echo $this->Form->input('private', array(
			'before' => $this->Html->div('forminfo', isset($eventDescriptions['private']['formdesc']) ? $eventDescriptions['private']['formdesc'] : $eventDescriptions['private']['desc']),));
}
echo $this->Form->input('risk', array(
		'before' => $this->Html->div('forminfo', isset($eventDescriptions['risk']['formdesc']) ? $eventDescriptions['risk']['formdesc'] : $eventDescriptions['risk']['desc'])));
echo $this->Form->input('info');

?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<ul>
		<?php echo $this->element('actions_menu'); ?>

	</ul>
</div>