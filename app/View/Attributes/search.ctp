<div class="attributes form">
<?php echo $this->Form->create('Attribute');?>
	<fieldset>
		<legend><?php echo __('Search Attribute'); ?></legend>
	<?php
		echo $this->Form->input('keyword');
		echo $this->Form->input('type', array('between' => $this->Html->div('forminfo', '', array('id' => 'AttributeTypeDiv'))));
		echo $this->Form->input('category', array('between' => $this->Html->div('forminfo', '', array('id' => 'AttributeCategoryDiv'))));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Search', true));?>
</div>
<div class="actions">
	<ul>
		<?php echo $this->element('actions_menu'); ?>
	</ul>
</div>
<script type="text/javascript">

var formInfoValues = new Array();
<?php
foreach ($typeDefinitions as $type => $def) {
	$info = isset($def['formdesc']) ? $def['formdesc'] : $def['desc'];
	echo "formInfoValues['$type'] = \"$info\";\n";
}

foreach ($categoryDefinitions as $category => $def) {
	$info = isset($def['formdesc']) ? $def['formdesc'] : $def['desc'];
	echo "formInfoValues['$category'] = \"$info\";\n";
}
$this->Js->get('#AttributeType')->event('change', 'showFormInfo("#AttributeType")');
$this->Js->get('#AttributeCategory')->event('change', 'showFormInfo("#AttributeCategory")');
?>

formInfoValues['ALL'] = '';

function showFormInfo(id) {
	idDiv = id+'Div';
	// LATER use nice animations
	//$(idDiv).hide('fast');
	// change the content
	var value = $(id).val();    // get the selected value
	$(idDiv).html(formInfoValues[value]);    // search in a lookup table

	// show it again
	$(idDiv).fadeIn('slow');
}

// hide the formInfo things
$('#AttributeTypeDiv').hide();
$('#AttributeCategoryDiv').hide();

</script>
<?php echo $this->Js->writeBuffer(); // Write cached scripts