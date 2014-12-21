<div class="popover_choice">
	<legend><?php echo __('Choose the format that you wish to export the event in'); ?></legend>
	<div class="popover_choice_main" id ="popover_choice_main">
		<table style="width:100%;">
		<?php foreach ($exports as $k => $export): ?>
			<tr style="border-bottom:1px solid black;" class="templateChoiceButton">
				<td style="padding-left:10px; text-align:left;width:50%;" onClick="exportChoiceSelect('<?php echo $export['url']; ?>', '<?php echo $k; ?>', '<?php echo $export['checkbox']; ?>')"><?php echo $export['text']; ?></td>
				<td style="padding-right:10px; width:50%;text-align:right;">
					<?php if ($export['checkbox']): 
						echo $export['checkbox_text'];
					?>
						<input id = "<?php echo $k . '_toggle';?>" type="checkbox" style="align;vertical-align:top;margin-top:8px;">
						<span id ="<?php echo $k?>_set" style="display:none;"><?php echo h($export['checkbox_set']); ?></span>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>
	</div>
	<div class="templateChoiceButton templateChoiceButtonLast" onClick="cancelPopoverForm();">Cancel</div>
</div>
<script type="text/javascript">	
	$(document).ready(function() {
		resizePopoverBody();
	});
	
	$(window).resize(function() {
		resizePopoverBody();
	});
</script>