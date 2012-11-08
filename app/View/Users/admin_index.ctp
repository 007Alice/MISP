<?php
$buttonAddStatus = $isAclAdd ? 'button_on':'button_off';
$buttonModifyStatus = ($isAclModify || $isAclModifyOrg) ? 'button_on':'button_off';
$buttonPublishStatus = $isAclPublish ? 'button_on':'button_off';
?>
<div class="users index">
	<h2><?php echo __('Users');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('org');?></th>
			<th><?php echo $this->Paginator->sort('group_id', 'Role');?></th>
			<th><?php echo $this->Paginator->sort('email');?></th>
			<th><?php echo $this->Paginator->sort('autoalert');?></th>
			<th><?php echo $this->Paginator->sort('gpgkey');?></th>
			<th><?php echo $this->Paginator->sort('nids_sid');?></th>
			<th><?php echo $this->Paginator->sort('termsaccepted');?></th>
			<th><?php echo $this->Paginator->sort('newsread');?></th>
			<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
	foreach ($users as $user): ?>
	<tr>
		<td class="short" onclick="document.location ='<?php echo $this->Html->url(array('admin' => true, 'action' => 'view', $user['User']['id']), true);?>';">
		<?php echo h($user['User']['id']); ?>&nbsp;</td>
		<td class="short" onclick="document.location ='<?php echo $this->Html->url(array('admin' => true, 'action' => 'view', $user['User']['id']), true);?>';">
		<?php echo h($user['User']['org']); ?>&nbsp;</td>
		<td class="short" onclick="document.location ='<?php echo $this->Html->url(array('admin' => true, 'action' => 'view', $user['User']['id']), true);?>';">
		<?php echo $this->Html->link($user['Group']['name'], array('controller' => 'groups', 'action' => 'view', $user['Group']['id'])); ?></td>
		<td class="short" onclick="document.location ='<?php echo $this->Html->url(array('admin' => true, 'action' => 'view', $user['User']['id']), true);?>';">
		<?php echo h($user['User']['email']); ?>&nbsp;</td>
		<td class="short" onclick="document.location ='<?php echo $this->Html->url(array('admin' => true, 'action' => 'view', $user['User']['id']), true);?>';">
		<?php echo $user['User']['autoalert']? 'Yes' : 'No'; ?>&nbsp;</td>
		<td class="short" onclick="document.location ='<?php echo $this->Html->url(array('admin' => true, 'action' => 'view', $user['User']['id']), true);?>';">
		<?php echo $user['User']['gpgkey']? 'Yes' : 'No'; ?>&nbsp;</td>
		<td class="short" onclick="document.location ='<?php echo $this->Html->url(array('admin' => true, 'action' => 'view', $user['User']['id']), true);?>';">
		<?php echo h($user['User']['nids_sid']); ?>&nbsp;</td>
		<td class="short" onclick="document.location ='<?php echo $this->Html->url(array('admin' => true, 'action' => 'view', $user['User']['id']), true);?>';">
		<?php echo h($user['User']['termsaccepted']); ?>&nbsp;</td>
		<td class="short" onclick="document.location ='<?php echo $this->Html->url(array('admin' => true, 'action' => 'view', $user['User']['id']), true);?>';">
		<?php echo h($user['User']['newsread']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('admin' => true, 'action' => 'view', $user['User']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('admin' => true, 'action' => 'edit', $user['User']['id']), $isAclModify || ($isAclModifyOrg && ($user['User']['org'] == $me['org'])) ? null : array('class' => $buttonModifyStatus)); ?>
			<?php if ($isAclModify || ($isAclModifyOrg && $user['User']['org'] == $me['org'])) echo $this->Form->postLink(__('Delete'), array('admin' => true, 'action' => 'delete', $user['User']['id']), null, __('Are you sure you want to delete # %s?', $user['User']['id']));
			else echo $this->Html->link(__('Delete'), array('admin' => true, 'action' => 'delete', $user['User']['id']), array('class' => $buttonModifyStatus));
			?>
		</td>
	</tr>
<?php endforeach; ?>
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