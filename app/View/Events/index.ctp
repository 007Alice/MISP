<div class="events index">
	<h2>Events</h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('Valid.');?></th>
			<?php
if ('true' == Configure::read('CyDefSIG.showorg') || $isAdmin): ?>
			<th><?php echo $this->Paginator->sort('org');?></th>
			<?php
endif; ?>
			<?php
if ($isAdmin): ?>
			<th><?php echo $this->Paginator->sort('owner org');?></th>
			<?php
endif; ?>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('attribute_count', '#Attr.');?></th>
			<?php
if ('true' == Configure::read('CyDefSIG.showowner') || $isAdmin): ?>
			<th><?php echo $this->Paginator->sort('user_id', 'Email');?></th>
			<?php
endif; ?>
			<th><?php echo $this->Paginator->sort('date');?></th>
			<th<?php echo ' title="' . $eventDescriptions['risk']['desc'] . '"';?>>
			<?php echo $this->Paginator->sort('risk');?></th>
			<th<?php echo ' title="' . $eventDescriptions['analysis']['desc'] . '"';?>>
			<?php echo $this->Paginator->sort('analysis');?></th>
			<th><?php echo $this->Paginator->sort('info');?></th>
			<?php
if ('true' == Configure::read('CyDefSIG.sync')): ?>
				<?php
	if ('true' == Configure::read('CyDefSIG.private')): ?>
			<th<?php echo ' title="' . $eventDescriptions['distribution']['desc'] . '"';?>>
			<?php echo $this->Paginator->sort('distribution');?></th>
						<?php else: ?>
			<th<?php echo ' title="' . $eventDescriptions['private']['desc'] . '"';?>>
			<?php echo $this->Paginator->sort('private');?></th>
			<?php
	endif; ?><?php
endif; ?>
			<th class="actions"><?php echo __('Actions');?></th>
	</tr><?php
foreach ($events as $event):?>
	<tr>
		<td class="short" onclick="document.location ='<?php echo $this->Html->url(array('action' => 'view', $event['Event']['id']), true);?>';"><?php
	if ($event['Event']['published'] == 1) {
			echo $this->Html->image('yes.png', array('title' => 'Validated', 'alt' => 'Validated', 'width' => '16', 'hight' => '16'));
	} else {
			echo $this->Html->image('no.png', array('title' => 'Not validated', 'alt' => 'Not Validated', 'width' => '16', 'hight' => '16'));
	}?>
		&nbsp;</td><?php
	if ('true' == Configure::read('CyDefSIG.showorg') || $isAdmin): ?>
		<td class="short" onclick="document.location ='<?php echo $this->Html->url(array('action' => 'view', $event['Event']['id']), true);?>';"><?php
		$imgRelativePath = 'orgs' . DS . h($event['Event']['orgc']) . '.png';
		$imgAbsolutePath = APP . WEBROOT_DIR . DS . 'img' . DS . $imgRelativePath;
		if (file_exists($imgAbsolutePath)) echo $this->Html->image('orgs/' . h($event['Event']['orgc']) . '.png', array('alt' => h($event['Event']['orgc']),'width' => '48','hight' => '48'));
		else echo $this->Html->tag('span', h($event['Event']['orgc']), array('class' => 'welcome', 'style' => 'float:right;'));?><?php
		?>
		&nbsp;</td><?php
	endif;
	if ('true' == $isAdmin): ?>
		<td class="short" onclick="document.location ='<?php echo $this->Html->url(array('action' => 'view', $event['Event']['id']), true);?>';"><?php
		$imgRelativePath = 'orgs' . DS . h($event['Event']['org']) . '.png';
		$imgAbsolutePath = APP . WEBROOT_DIR . DS . 'img' . DS . $imgRelativePath;
		if (file_exists($imgAbsolutePath)) echo $this->Html->image('orgs/' . h($event['Event']['org']) . '.png', array('alt' => h($event['Event']['org']),'width' => '48','hight' => '48'));
		else echo $this->Html->tag('span', h($event['Event']['org']), array('class' => 'welcome', 'style' => 'float:right;'));?><?php
		?>
		&nbsp;</td><?php
	endif; ?>
		<td class="short">
		<?php echo $this->Html->link($event['Event']['id'], array('controller' => 'events', 'action' => 'view', $event['Event']['id'])); ?>
		&nbsp;</td>
		<td class="short" onclick="document.location ='<?php echo $this->Html->url(array('action' => 'view', $event['Event']['id']), true);?>';">
		<?php echo $event['Event']['attribute_count']; ?>&nbsp;</td><?php
	if ('true' == Configure::read('CyDefSIG.showowner') || $isAdmin): ?>
		<td class="short" onclick="document.location ='<?php echo $this->Html->url(array('action' => 'view', $event['Event']['id']), true);?>';">
		<?php echo h($event['User']['email']); ?>&nbsp;</td><?php
	endif; ?>
		<td class="short" onclick="document.location ='<?php echo $this->Html->url(array('action' => 'view', $event['Event']['id']), true);?>';">
		<?php echo $event['Event']['date']; ?>&nbsp;</td>
		<td class="short" onclick="document.location ='<?php echo $this->Html->url(array('action' => 'view', $event['Event']['id']), true);?>';">
		<?php echo $event['Event']['risk']; ?>&nbsp;</td>
		<td class="short" onclick="document.location ='<?php echo $this->Html->url(array('action' => 'view', $event['Event']['id']), true);?>';">
		<?php echo $analysisLevels[$event['Event']['analysis']]; ?>&nbsp;</td>
		<td onclick="document.location ='<?php echo $this->Html->url(array('action' => 'view', $event['Event']['id']), true);?>';">
		<?php echo nl2br($event['Event']['info']); ?>&nbsp;</td>
		<?php
	if ('true' == Configure::read('CyDefSIG.sync')): ?><?php
		if ('true' == Configure::read('CyDefSIG.private')): ?>
			<td class="short" onclick="document.location ='<?php echo $this->Html->url(array('action' => 'view', $event['Event']['id']), true);?>';">
			<?php echo $event['Event']['distribution'] != 'All communities' ? $event['Event']['distribution'] : 'All';?></td><?php
		else: ?>
			<td class="short" onclick="document.location ='<?php echo $this->Html->url(array('action' => 'view', $event['Event']['id']), true);?>';">
			<?php echo ($event['Event']['private']) ? 'Private' : ''; ?>&nbsp;</td><?php
		endif; ?>
		<?php
	endif; ?>
		<td class="actions">
			<?php
			if (0 == $event['Event']['published'] && ($isAdmin || ($isAclPublish && $event['Event']['org'] == $me['org'])))
				echo $this->Form->postLink('Publish Event', array('action' => 'alert', $event['Event']['id']), array('action' => 'alert', $event['Event']['id']), 'Are you sure this event is complete and everyone should be informed?');
			elseif (0 == $event['Event']['published']) echo 'Not published';
			?>
		<?php
	if ($isAdmin || ($isAclModify && $event['Event']['user_id'] == $me['id']) || ($isAclModifyOrg && $event['Event']['org'] == $me['org'])) {
		echo $this->Html->link(__('Edit', true), array('action' => 'edit', $event['Event']['id']), null);
		echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $event['Event']['id']), null, __('Are you sure you want to delete # %s?', $event['Event']['id']));
	}?>
			<?php echo $this->Html->link(__('View', true), array('controller' => 'events', 'action' => 'view', $event['Event']['id'])); ?>
		</td>
	</tr>
	<?php
endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>

	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<div class="actions">
	<ul>
		<?php echo $this->element('actions_menu'); ?>
	</ul>
</div>