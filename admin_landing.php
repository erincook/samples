<div class='header3'>this is the admin landing!</div>

<!-- general site related stuff! -->
<h5>Site things</h5>
<ul>
	<li><?php echo anchor('admin/viewConfig', 'View Site Config'); ?></li>
	<li><?php echo anchor('admin/viewSiteStatistics', 'View Site Statistics'); ?></li>
	<li><?php echo anchor('admin/manageServices', 'View Services'); ?></li>
</ul>

<!-- change log -->
<h5>Change Log Actions</h5>
<ul>
	<li><?php echo anchor('admin/clVersions', 'View Change Log Versions'); ?></li>
	<li><?php echo anchor('admin/clVersion',  'Create Log Versions'); ?></li>
	<li><?php echo anchor('admin/clCategories', 'View Change Log Categories'); ?></li>
	<li><?php echo anchor('admin/clCategory', 'Create Change Log Category'); ?></li>
	<li><?php echo anchor('admin/clChanges', 'View Change Log Changes'); ?></li>
	<li><?php echo anchor('admin/clChange', 'Create Change Log Changes'); ?></li>
</ul>

<!-- QDR! --> 
<h5>QDR</h5>
<ul>
	<li><?php echo anchor('admin/qdrFaults', 'Generic Fault'); ?></li>
	<li><?php echo anchor('admin/rmLinks', 'Reaction Meachnism Links'); ?></li>
	
</ul> <!-- passdown! --> 
<h5>Passdown</h5>
<ul>
	<li><?php echo anchor('admin/passdown', 'Passdown Creation'); ?></li>
	<li><?php echo anchor('admin/passdownCategories', 'Passdown Categories'); ?></li>
	<li><?php echo anchor('admin/passdownCategoryEquipTypes', 'Passdown Category Equip Type Relationships'); ?></li>
</ul> 

<!-- Tool Filters -->
<h5>Tool Filters</h5>
<ul>
	<li><?php echo anchor('admin/pmRules', 'view pm rules!'); ?></li>
</ul>

<!-- PM Aliases -->
<h5>PM Aliases</h5>
<ul>
	<li><?php echo anchor('admin/managePMAliases', 'view pm aliases!'); ?></li>
</ul>

<!-- PM planner settings -->
<h5> Pm Planner Settings </h5> 
<ul>
	<li><?php echo anchor('admin/manageIntervalPM', 'PM Interval Ignore Rules'); ?></li>
</ul>

<!-- watchdog settings -->
<h5> Watchdog Settings </h5>
<ul>
	<li><?php echo anchor('admin/manageWatchdog', 'Manage Workstations in Watchdog'); ?></li>
</ul>

<!-- LTD settings -->
<h5> LTD Settings </h5>
<ul>
	<li><?php echo anchor('admin/manageLTD', 'Manage LTD'); ?></li>
</ul>


<h5>User Settings</h5>
<ul>
	<li><?php echo anchor('admin/manageUsers', 'Manage Users') ?></li>
	<li><?php echo anchor('account/newUser', 'Create a new user'); ?></li>
</ul>


<h5>Email Settings</h5>
<ul>
	<li><?php echo anchor('admin/manageEmails', 'Manage Predefined Emails'); ?></li>
</ul>
