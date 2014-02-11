<div class='header3' >Page Loads</div>
<div class='page_description'>
	Viewing page loads for between <?php echo date('m/d/Y h:i A', $startUNIXTS); ?> and <?php echo date('m/d/Y h:i A', $endUNIXTS); ?>.
</div>

<div class='center' style="display:table;">
	<div id='site_traffic_container'>
		<div class='header4'>Total Hits vs. Time</div>
		<div id='siteTraffic' style="width:620px; height:300px;">
			
		</div>
	</div>
	<div id='site_traffic_container'>
		<div class='header4'>Unique Hits vs. Time</div>
		<div id='uniqueSiteTraffic' style="width:620px; height:300px;">
			
		</div>
	</div>
</div>

<div class='header3' >Browser Usage - for all time</div>
<div class='center' style='display:table;'>
	<div class='piecontainer float_left'>
		<div class='header4'>Browser Usage</div>
		<div id='browserPie' class='pieChart' style="width:300px; height:250px"></div>
	</div>
	
	<div class='piecontainer float_left'>
		<div class='header4 versionLabel'>Version Usage</div>
		<div id='versionPie' class='pieChart' style="width:300px; height:250px"></div>
	</div>
</div>
<div class='header3' >Hits per page for the past month</div>
<div class='center' style="display:table;">
	<div role="button" id="getSitestatsPerPage" class='center btn btn-inverse btn-large'>Get hits per page!</div>
	<table id="numHitsPerPageTable" class="table table-bordered table-hover table-striped"></table>
</div>
