<?php
	$mayModify = (($isAclModify && $event['Event']['user_id'] == $me['id'] && $event['Orgc']['id'] == $me['org_id']) || ($isAclModifyOrg && $event['Orgc']['id'] == $me['org_id']));
	$mayPublish = ($isAclPublish && $event['Orgc']['id'] == $me['org_id']);
?>

<div id="distribution_graph_container">
	<div id="eventdistri_graph" style="height: 400px; width: 400px;" data-event-id="<?php echo h($event['Event']['id']); ?>" data-event-distribution="<?php echo h($event['Event']['distribution']); ?>" data-user-manipulation="<?php echo $mayModify || $isSiteAdmin ? 'true' : 'false'; ?>" data-extended="<?php echo $extended; ?>">
		<canvas id="distribution_graph_canvas" width="100" height="100" ></canvas>
	</div>
	<div id="eventdistri_pb_container" style="margin-top: 20px;">
		<div id="eventdistri_pb_background" style="width: 400px;" class="progress">
			<div id="eventdistri_pb" class="progress" role="progressbar" style="width: 0%; height:100%; margin-left: 0px;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
		</div>
	</div>
</div>

<?php
	echo $this->Html->script('Chart.min');
	echo $this->Html->script('event-distribution-graph');
?>
