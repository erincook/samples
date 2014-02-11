<div class='header3'>Mutliple Equipment Found</div>
<div class='page_description'>Please select the equipment that you wish to view alarms for.</div>

<div class='tableContainer'>
<table class='dataTable'>
<thead>
	<tr>
		<th>
			Equipment Name
		</th>
		<th>
			Department
		</th>
		<th>
			Equipment State
		</th>
		<th>
			Equipment Type ID
		</th>
	</tr>
</thead>
<tbody>
<?php 
	foreach ($equipFound as $equip)
	{
	?>
		<tr>
			<td>
			<!--
			$toolName = NULL,
		$groupBy = false,
		$startDate = null, 
		$endDate = null, 
		$blockE3 = false,
		$blockToolFaults = false,
		$downEventsOnly = true
		-->
				<?php
				echo anchor(
					'alarms/search/' . $equip['equip_id'] . '/' . $groupBy . '/' . $startDate . '/' . $endDate . '/' . $blockE3 . '/' . $blockToolFaults . '/' . $downEventsOnly,
					$equip['equip_id'],
					array('class' => 'displayLoadingPrompt')
				);
				?>
			</td>
			<td>
				<?php echo humanize($equip['mfg_area_id']); ?>
			</td>
			<td>
				<?php echo humanize($equip['equip_state_id']); ?>
			</td>
			<td>
				<?php echo humanize($equip['equip_type_id']); ?>
			</td>
	</tr>
	<?php
	}
?>
</tbody>
</table>
</div>