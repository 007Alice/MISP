<?php
	$fixed_fields = array('decription', 'source', 'authors');
	foreach ($event['Galaxy'] as $galaxy):
?>
		<div>
			<span title="<?php echo isset($galaxy['description']) ? h($galaxy['description']) : h($galaxy['name']);?>" class="bold blue" style="font-size:14px;">
				<?php echo h($galaxy['name']); ?>
			</span>
	<?php
		foreach ($galaxy['GalaxyCluster'] as $cluster):
	?>
			<div style="margin-left:20px;">
				<span class="bold blue expandable useCursorPointer"><?php echo h($cluster['value']); ?></span>&nbsp;
				<a href="<?php echo $baseurl; ?>/events/index/searchtag:<?php echo h($cluster['tag_id']); ?>" class="icon-th-list" title="View all events containing this cluster."></a>
				<?php
					echo $this->Form->postLink('',
						$baseurl . '/galaxy_clusters/detachFromEvent/' . $event['Event']['id'] . '/' . $cluster['tag_id'],
						array('class' => 'icon-trash', 'title' => 'Delete'),
						__('Are you sure you want to detach %s from this event?', h($cluster['value']))
					);
				?>
				<div style="margin-left:40px;" class="hidden blue">
					<table style="width:100%">
						<?php
							foreach ($fixed_fields as $fixed_field):
								if (isset($cluster[$fixed_field])):
						?>
									<tr>
										<td style="width:25%;vertical-align: text-top; padding-bottom:10px;"><?php echo h(ucfirst($fixed_field)); ?></td>
										<td style="width:75%; padding-bottom:10px;">
											<?php 
												if (is_array($cluster[$fixed_field])) {	
													$cluster[$fixed_field] = implode("\n", $cluster[$fixed_field]);
													echo nl2br(h($cluster[$fixed_field]));
												} else {
													echo h($cluster[$fixed_field]);
												}
											?>
										</td>
									</tr>
						<?php
								endif;
							endforeach;
							foreach ($cluster['meta'] as $key => $value):
						?>
								<tr>
									<td style="width:25%;vertical-align: text-top; padding-bottom:10px;"><?php echo h(ucfirst($key)); ?></td>
									<td style="width:75%; padding-bottom:10px;">
										<?php 
											if ($key == 'refs'):
												foreach ($value as $k => $v):
													$value[$k] = '<a href="' . h($v) . '">' . h($v) . '</a>';
												endforeach;
												echo nl2br(implode("\n", $value));
											else:
												echo nl2br(h(implode("\n", $value)));
											endif; 
										?>
									</td>
								</tr>
						<?php 
							endforeach;
						?>
					</table>
					<?php 
						
					?>
				</div>
			</div>
<?php
		endforeach;
?>
	</div>
<?php 
	endforeach;
?>
<script type="text/javascript">
$(document).ready(function () {
	$('.expandable').click(function() {
		$(this).parent().children('div').toggle();
	});
	$('.delete-cluster').click(function() {
		var tagName = $(this).data('tag-name');
		removeTag($id = false, $tag_id = false, $galaxy = false);
	});
});
</script>