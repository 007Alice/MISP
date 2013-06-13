<div class="actions" style="width:15%">
	<ol class="nav nav-list">
		<li><?php echo $this->Html->link('General Layout', array('controller' => 'pages', 'action' => 'display', 'documentation')); ?></li>
		<li><?php echo $this->Html->link('General Concepts', array('controller' => 'pages', 'action' => 'display', 'concepts')); ?></li>
		<li><?php echo $this->Html->link('User Management and Global actions', array('controller' => 'pages', 'action' => 'display', 'user_management')); ?></li>
		<li><?php echo $this->Html->link('Using the system', array('controller' => 'pages', 'action' => 'display', 'using_the_system')); ?></li>
		<li><?php echo $this->Html->link('Administration', array('controller' => 'pages', 'action' => 'display', 'administration')); ?></li>
		<li class="active"><?php echo $this->Html->link('Categories and Types', array('controller' => 'pages', 'action' => 'display', 'categories_and_types')); ?></li>
	</ol>
</div>
<div class="index" style="width:80%">
<?php
// Load the Attribute model to extract the documentation from the defintions
App::import('Model', 'Attribute');
$attr = new Attribute();
?>
<h2>Attribute Categories and Types</h2>
<h3>Attribute Categories vs Types</h3>
<table class="table table-striped table-hover table-condensed table-bordered">
	<tr>
		<th>Category</th>
		<?php foreach ($attr->categoryDefinitions as $cat => $catDef):	?>
		<th style="width:5%; text-align:center; white-space:normal">
			<a href="#<?php echo $cat; ?>"><?php echo $cat; ?></a>
		</th>
		<?php endforeach; ?>
		<th>Category</th>
	</tr>
	<?php foreach ($attr->typeDefinitions as $type => $def): ?>
	<tr>
		<th><a href="#<?php echo $type; ?>"><?php echo $type; ?></a></th>
		<?php foreach ($attr->categoryDefinitions as $cat => $catDef): ?>
		<td style="text-align:center">
			<?php echo in_array($type, $catDef['types'])? 'X' : ''; ?>
		</td>
		<?php endforeach; ?>
		<th><a href="#<?php echo $type; ?>"><?php echo $type; ?></a></th>
	<?php endforeach; ?>
	</tr>
<tr>
	<th>Category</th>
	<?php foreach ($attr->categoryDefinitions as $cat => $catDef): ?>
	<th style="width:5%; text-align:center; white-space:normal">
		<a href="#<?php echo $cat; ?>"><?php echo $cat; ?></a>
	</th>
	<?php endforeach; ?>
	<th>Category</th>
</tr>
</table>
<h3>Categories</h3>
<table class="table table-striped table-condensed table-bordered">
	<tr>
		<th>Category</th>
		<th>Description</th>
	</tr>
	<?php foreach ($attr->categoryDefinitions as $cat => $def): ?>
	<tr>
		<th><a id="<?php echo $cat; ?>"></a>
			<?php echo $cat; ?>
		</th>
		<td>
			<?php echo isset($def['formdesc'])? $def['formdesc'] : $def['desc']; ?>
		</td>
	</tr>
	<?php endforeach; ?>
</table>
<h3>Types</h3>
<table class="table table-striped table-condensed table-bordered">
	<tr>
		<th>Type</th>
		<th>Description</th>
	</tr>
	<?php foreach ($attr->typeDefinitions as $type => $def): ?>
	<tr>
		<th><a id="<?php echo $type; ?>"></a>
			<?php echo $type; ?>
		</th>
		<td>
			<?php echo isset($def['formdesc'])? $def['formdesc'] : $def['desc']; ?>
		</td>
	</tr>
	<?php endforeach;?>
</table>


</div>
