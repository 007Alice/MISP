<div class="organisations view">
<h2><?php  echo 'Organisation ' . $org['Organisation']['name'];?></h2>
	<dl style="width:600px;">
		<dt><?php echo 'Id'; ?></dt>
		<dd>
			<?php echo h($org['Organisation']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo 'Organisation name'; ?></dt>
		<dd>
			<?php echo h($org['Organisation']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo 'Description'; ?></dt>
		<dd>
			<?php echo h($org['Organisation']['description']); ?>
			&nbsp;
		</dd>
		<?php if ($fullAccess): ?>
			<dt><?php echo 'Uuid'; ?></dt>
			<dd>
				<?php echo h($org['Organisation']['uuid']); ?>
				&nbsp;
			</dd>
		<?php endif;?>
		<?php if ($isSiteAdmin): ?>
			<dt><?php echo 'Created by'; ?></dt>
			<dd>
				<?php echo h($creator['User']['email']); ?>
				&nbsp;
			</dd>
		<?php endif;?>
		<?php 
			$optionalFields = array('sector' => 'Sector', 'nationality' => 'Nationality', 'type' => 'Organisation type', 'contacts' => 'Contact information');
			foreach ($optionalFields as $k => $field):
				if (!empty($org['Organisation'][$k])): 
		?>
					<dt><?php echo $field; ?></dt>
					<dd>
						<?php echo h($org['Organisation'][$k]); ?>
						&nbsp;
					</dd>
		<?php 
				endif;
			endforeach;
		?>
	</dl>
	<br />
	<button id="button_description" class="btn btn-inverse toggle-left qet orgViewButton" onClick="organisationViewContent('description', '<?php echo $id;?>');">Description</button>
	<button id="button_description_active" style="display:none;" class="btn btn-primary toggle-left qet orgViewButtonActive" onClick="organisationViewContent('description', '<?php echo $id;?>');">Description</button>
	
	<button id="button_members" class="btn btn-inverse toggle qet orgViewButton" onClick="organisationViewContent('members', '<?php echo $id;?>');">Members</button>
	<button id="button_members_active" style="display:none;" class="btn btn-primary toggle qet orgViewButtonActive" onClick="organisationViewContent('members', '<?php echo $id;?>');">Members</button>
	
	<button id="button_events" class="btn btn-inverse toggle-right qet orgViewButton" onClick="organisationViewContent('events', '<?php echo $id;?>');">Events</button>
	<button id="button_events_active" style="display:none;" class="btn btn-primary toggle-right qet orgViewButtonActive" onClick="organisationViewContent('events', '<?php echo $id;?>');">Events</button>
	<br /><br />
	<div id="ajaxContent" style="width:100%;"></div>
</div>
<script type="text/javascript">
	$(document).ready(function () {
		organisationViewContent('members', '<?php echo $id;?>');
	});
</script>