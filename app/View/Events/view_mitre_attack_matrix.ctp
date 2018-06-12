<div class="attack-matrix-options">
    <span id="matrix-heatmap-legend-caret">
	<span id="matrix-heatmap-legend-caret-value">0</span>
	<span class="fa fa-caret-down"></span>
    </span>
    <div>
	<span>0</span>
	<div id="matrix-heatmap-legend"></div>
	<span id="matrix-heatmap-maxval"><?php echo h($maxScore); ?></span>
    </div>
    <label style="display: inline-block; margin-left: 30px;"><input type="checkbox" id="checkbox_attackMatrix_showAll" checked><span class="fa fa-filter"> Show all</span></input></label>
</div>

<?php if($pickingMode): ?>
    <div class="hidden">
    	<?php
    		echo $this->Form->create('Galaxy', array('url' => '/galaxies/attachCluster/' . $target_id . '/' . (empty($target_type) ? 'attribute' : $target_type), 'style' => 'margin:0px;'));
    		echo $this->Form->input('target_id', array('type' => 'text'));
    		echo $this->Form->end();
    	?>
    </div>
<?php endif; ?>

<div id="matrix_container" class="fixed-table-container-inner" style="height: 670px;" data-picking-mode="<?php echo $pickingMode ? 'true' : 'false'; ?>">
	<div class="header-background"></div>
	<div class="fixed-table-container-inner" style="height: 670px;">
	<table class="table table-condensed matrix-table">
	<thead>
	<tr>
	<?php
		foreach($killChainNames as $kc) {
			$name = str_replace("-", " ", $kc);
			echo '<th>
				<div class="extra-wrap">
				    <div class="th-inner">'.ucfirst($name).'</div>
				</div>
			    </th>';
		}
	?>
	</tr>
	</thead>
	<tbody style="overflow-y: scroll;">
	<?php
		$added = false;
		$i = 0;
		do {
			$added = false;
			echo '<tr>';
				foreach($killChainOrder as $kc) {
					$clusters = $attackClusters[$kc];
					$td = '<td ';
					if ($i < count($clusters)) {
						$clusterId = $clusters[$i]['id'];
						$tagName = $clusters[$i]['tag_name'];
						$score = empty($scores[$tagName]) ? 0 : $scores[$tagName];
						$name = join(" ", array_slice(explode(" ", $clusters[$i]['value']), 0, -2)); // remove " - external_id"
						$td .= ' class="heatCell matrix-interaction ' . ($pickingMode ? 'cell-picking"' : '"');
						$td .= isset($colours[$tagName]) ? ' style="background: ' . $colours[$tagName] . '; color: ' . $this->TextColour->getTextColour($colours[$tagName]) . '"' : '' ;
						$td .= ' data-score="'.h($score).'"';
						$td .= ' data-tag_name="'.h($tagName).'"';
						if ($pickingMode) {
							$td .= ' data-target-type="attribute"';
							$td .= ' data-target-id="'.h($target_id).'"';
							$td .= ' data-cluster-id="'.h($clusterId).'"';
						}
						$td .= ' title="'.h($clusters[$i]['external_id']).'"';
						$td .= '>' . h($name);
						$added = true;
					} else {
						$td .= 'class="">';
					}
					$td .=  '</td>';
					echo $td;
				}
			echo '</tr>';
			$i++;
		} while($added);
	?>
	</tbody>
	</table>
	</div>
</div>

<?php if($pickingMode): ?>
<div role="button" tabindex="0" aria-label="Cancel" title="Cancel" class="templateChoiceButton templateChoiceButtonLast" onClick="cancelPopoverForm('#popover_form_large');">Cancel</div>
<?php endif; ?>

<?php
	echo $this->Html->script('attack_matrix');
	echo $this->Html->css('attack_matrix');
?>
