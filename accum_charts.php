

<div class='header3'>
	Accumulation Charts
</div>
<div class='post_header_content'>

<div>
	
	<?php echo form_open('pmplanner/accum'); ?>
	
	<!-- new row -->

	<!-- end row -->
	<?php 
	
	?>

	<?php 
	//$showSearchOptions = false;
	//var_dump($showSearchOptions);
	if ( $showSearchOptions ) 
	{
	?>
		<!-- New Row -->
		<div id="workstationFilterContainer">
			<?php echo $workstationSelect; ?>
			<div class='option_container'>
				
				<div id='equip_dropdown_container'>
					<div class='header4'>Equipment</div>
					<?php echo $equip_dropdown; ?>
				</div>
			</div>
		</div>
		<!-- end row -->
	<?php 
	}
	else
	{
	?>
	<div id="equipmentFilterContainer" >
		<div class='option_container'>
			<div class="header4">Equipment Name</div>
			<input type="text" name="equipment" id="equipment" value="<?php if (isset($equip_name)) echo $equip_name; ?>"/>
		</div>
	</div>
	<?php
	}
	?>
	<!-- new row -->
	<div>
		<div class='option_container'>
			<div class='header4'>Start Date (mm/yy/dd) </div>
			<input type='text' class='input-small start datePicker' name="startDateForm" id='startDateForm' value="<?php echo $startDate; ?>"/>
		</div>
		<div class='option_container'>
			<div class='header4'>End Date (mm/yy/dd) </div>
			<input type='text' class='input-small end datePicker' name="endDateForm" id='endDateForm' value="<?php echo $endDate; ?>"/>
		</div>
	</div>
	<!-- end row -->
	<?php
		$pmAccum = null;
		
		if(!$showSearchOptions and !is_null($pmAccum))
		{
	?>
		<div>
			<div class='option container'>
				<div class='header4'>PMs to Overlay</div>
				<select multiple name="pmSelect" id="pmSelect">
					
				</select>
			</div>
		</div>
	<?php
	}
	else
	{
	?>
		<div class='header4'>This tool does not contain any PMs with Accumulation PMs.</div>
	<?php
	}
	?>
	<!-- new row -->
	
	<div class='btn_form_row'>
			<input type="submit" class="btn btn-primary displayLoadingPrompt" name="submit" value="Submit" />
		<?php	echo anchor('welcome', 'Cancel', 'class="btn cancel"'); ?>
		</div>
	<?php echo form_close(); ?>
</div>
	<div class='post_header_content'>
		<div class='chart_container'>
			<div class='equip_usage_chart' style='width:900px; height:400px;'> </div>
		</div>
	</div>
	
	
		<div class='post_header_content'>
			<div class='chart_container'>
				<div class='equip_rf_chart' style='width:900px; height:500px;'> </div>
			</div>
		</div>
</div>