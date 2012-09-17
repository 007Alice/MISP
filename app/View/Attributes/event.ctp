<div class="event view"><!-- bluntly copied from View/Events/view.ctp -->
<div class="actions" style="float:right;">
<?php if ( 0 == $event['Event']['published'] && ($isAdmin || $event['Event']['org'] == $me['org'])):
// only show button if alert has not been sent  // LATER show the ALERT button in red-ish
?>
    <ul><li><?php
    echo $this->Form->postLink('Publish Event', array('controller' => 'events', 'action' => 'alert', $event['Event']['id']), null, 'Are you sure this event is complete and everyone should be informed?');
    echo $this->Form->postLink('Publish (no email)', array('controller' => 'events', 'action' => 'publish', $event['Event']['id']), null, 'Publish but do NOT send alert email? Only for minor changes!');
    ?> </li></ul>
<?php elseif (0 == $event['Event']['published']): ?>
    <ul><li>Not published</li></ul>
<?php else: ?>
    <!-- ul><li>Alert already sent</li></ul -->
<?php endif; ?>
    <ul><li><?php echo $this->Html->link(__('Contact reporter', true), array('controller' => 'events', 'action' => 'contact', $event['Event']['id'])); ?> </li></ul>
</div>



<h2>Event</h2>
	<dl>
		<dt>ID</dt>
		<dd>
			<?php echo h($event['Event']['id']); ?>
			&nbsp;
		</dd>
		<?php if ('true' == Configure::read('CyDefSIG.showorg') || $isAdmin): ?>
		<dt>Org</dt>
		<dd>
			<?php echo h($event['Event']['org']); ?>
			&nbsp;
		</dd>
		<dt>Email</dt>
		<dd>
			<?php echo h($event['User']['email']); ?>
			&nbsp;
		</dd>
		<?php endif; ?>
		<dt>Date</dt>
		<dd>
			<?php echo h($event['Event']['date']); ?>
			&nbsp;
		</dd>
		<dt<?php echo ' title="' . $event_descriptions['risk']['desc'] . '"';?>>Risk</dt>
		<dd>
			<?php echo $event['Event']['risk']; ?>
			&nbsp;
		</dd>
		<?php if ('true' == Configure::read('CyDefSIG.sync')): ?>
		<dt>Private</dt>
		<dd>
			<?php echo ($event['Event']['private'])? 'Yes, never upload Event or any Attributes.' : 'No, upload Event and all Attributes except those marked as Private.'; ?>
			&nbsp;
		</dd>
		<?php endif; ?>
		<!-- dt>UUID</dt>
		<dd>
			<?php echo $event['Event']['uuid']; ?>
			&nbsp;
		</dd -->
		<dt>Info</dt>
		<dd>
			<?php echo nl2br(h($event['Event']['info'])); ?>
			&nbsp;
		</dd>
	</dl>
	<?php if (!empty($relatedEvents)):?>
	<div class="related">
		<h3>Related Events</h3>
		<ul>
		<?php foreach ($relatedEvents as $relatedEvent): ?>
		<li><?php
		if ('db' == Configure::read('CyDefSIG.correlation')) {	// TODO array key
			$link_text = $relatedEvent['date'].' ('.$relatedEvent['id'].')';
			echo $this->Html->link($link_text, array('controller' => 'attributes', 'action' => 'event', $relatedEvent['id']));
		} else {
			$link_text = $relatedEvent['Event']['date'].' ('.$relatedEvent['Event']['id'].')';
			echo $this->Html->link($link_text, array('controller' => 'attributes', 'action' => 'event', $relatedEvent['Event']['id']));
		}
		?></li>
	    <?php endforeach; ?>
	    </ul>
	</div>
	<?php endif; ?>

    <div class="related">
    	<h3>Attributes</h3>
    	<?php if (!empty($event['Attribute'])):?>
    	<table cellpadding = "0" cellspacing = "0">
    	<tr>
    		<th>Category</th>
    		<th>Type</th>
    		<th>Value</th>
    		<th>Related Events</th>
    		<th <?php echo "title='" . $attr_descriptions['signature']['desc'] . "'";?>>IDS Signature</th>
    		<?php if ('true' == Configure::read('CyDefSIG.sync')): ?>
    		<th <?php echo "title='" . $attr_descriptions['private']['desc'] . "'";?>>Private</th>
    		<?php endif;?>
    		<?php if ($isAdmin || $event['Event']['org'] == $me['org']): ?>
    		<th class="actions">Actions</th>
    		<?php endif;?>
    	</tr>
    	<?php
        foreach ($categories as $category):
			$first = 1;
    		foreach ($attributes as $attribute):
    			if($attribute['Attribute']['category'] != $category) continue;
    		?>
    		<tr>
    		    <td class="short" title="<?php if('' != $attribute['Attribute']['category']) echo $category_definitions[$attribute['Attribute']['category']]['desc'];?>"><?php
    		    if ($first) {
    		        if ('' == $attribute['Attribute']['category']) echo '(no category)';
    		        echo $attribute['Attribute']['category'];
    		    } else {
    		        echo '&nbsp;';
    		    }
    		    ?></td>
    			<td class="short" title="<?php echo $type_definitions[$attribute['Attribute']['type']]['desc'];?>"><?php echo $attribute['Attribute']['type'];?></td>
    			<td><?php
    			$sig_display = nl2br(h($attribute['Attribute']['value']));
    			if('attachment' == $attribute['Attribute']['type'] ||
    			        'malware-sample' == $attribute['Attribute']['type'] ) {
			        $filename_hash = explode('|', h($attribute['Attribute']['value']));
			        echo $this->Html->link($filename_hash[0], array('controller' => 'attributes', 'action' => 'download', $attribute['Attribute']['id']));
			        if (isset($filename_hash[1])) echo ' | '.$filename_hash[1];
    			} elseif (strpos($attribute['Attribute']['type'], '|') !== false) {
    			    $filename_hash = explode('|', h($attribute['Attribute']['value']));
    			    echo $filename_hash[0];
    			    if (isset($filename_hash[1])) echo ' | '.$filename_hash[1];
    			} elseif ('vulnerability' == $attribute['Attribute']['type']) {
    			    echo $this->Html->link($sig_display, 'http://www.google.com/search?q='.$sig_display, array('target'=> '_blank'));
			} elseif ('link' == $attribute['Attribute']['type']) {
			    echo $this->Html->link($sig_display, $sig_display);
    			} else {
    			    echo $sig_display;
    			}
    		    ?></td>
    			<td class="short" style="text-align: center;">
    			<?php
    			$first = 0;
                if (isset($relatedAttributes[$attribute['Attribute']['id']]) && (null != $relatedAttributes[$attribute['Attribute']['id']])) {
    			    foreach ($relatedAttributes[$attribute['Attribute']['id']] as $relatedAttribute) {
						if ('db' == Configure::read('CyDefSIG.correlation')) {	// TODO array key
    			    		echo $this->Html->link($relatedAttribute['Correlation']['event_id'], array('controller' => 'events', 'action' => 'view', $relatedAttribute['Correlation']['event_id']));
						} else {
    			    		echo $this->Html->link($relatedAttribute['Attribute']['event_id'], array('controller' => 'events', 'action' => 'view', $relatedAttribute['Attribute']['event_id']));
						}
    			        echo ' ';
    			    }
    			}
    			?>&nbsp;
    			</td>
    			<td class="short" style="text-align: center;"><?php echo $attribute['Attribute']['to_ids'] ? 'Yes' : 'No';?></td>
    			<?php if ('true' == Configure::read('CyDefSIG.sync')): ?>
    			<td class="short" style="text-align: center;"><?php echo $attribute['Attribute']['private'] ? 'Private' : '&nbsp;';?></td>
    			<?php endif;?>
    			<?php if ($isAdmin || $event['Event']['org'] == $me['org']): ?>
    			<td class="actions">
    				<?php
    				    echo $this->Html->link(__('Edit', true), array('controller' => 'attributes', 'action' => 'edit', $attribute['Attribute']['id']));
    				    echo $this->Form->postLink(__('Delete'), array('controller' => 'attributes', 'action' => 'delete', $attribute['Attribute']['id']), null, __('Are you sure you want to delete this attribute?'));
    				?>
    			</td>
    			<?php endif;?>
    		</tr>
    	    <?php endforeach; ?>
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
        <?php endif; ?>
    	<?php if ($isAdmin || $event['Event']['org'] == $me['org']): ?>
    	<div class="actions">
    		<ul>
    			<li><?php echo $this->Html->link('Add Attribute', array('controller' => 'attributes', 'action' => 'add', $event['Event']['id']));?> </li>
    			<li><?php echo $this->Html->link('Add Attachment', array('controller' => 'attributes', 'action' => 'add_attachment', $event['Event']['id']));?> </li>
    		</ul>
    	</div>
    	<?php endif; ?>
    </div>

</div>

<div class="actions">
	<ul>
	<?php if ($isAdmin || $event['Event']['org'] == $me['org']): ?>
    	<li><?php echo $this->Html->link(__('Add Attribute', true), array('controller' => 'attributes', 'action' => 'add', $event['Event']['id']));?> </li>
		<li><?php echo $this->Html->link(__('Add Attachment', true), array('controller' => 'attributes', 'action' => 'add_attachment', $event['Event']['id']));?> </li>
		<li><?php echo $this->Html->link(__('Edit Event', true), array('controller' => 'events', 'action' => 'edit', $event['Event']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Event'), array('controller' => 'events', 'action' => 'delete', $event['Event']['id']), null, __('Are you sure you want to delete # %s?', $event['Event']['id'])); ?></li>
		<li>&nbsp;</li>
	<?php endif; ?>
        <?php echo $this->element('actions_menu'); ?>
	</ul>
</div>