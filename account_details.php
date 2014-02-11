<?php 
/**
 *  Purpose:  Displays userdata based on the provided data.
 *  Author: Erin Cook - ecook@micron.com
 *
 *  Additional functionality that could be added....
 *   user statistics
 */
?>

<?php 
$header3 = 'Account Details for ' . $user['full_name'];
if ($userdata['user_id'] == $user['id'] || $userdata['is_admin'])
{
?>
<div class='header3_container'>
	<div class='header3_left'><?php echo $header3?></div>
	<div class='header3_right'><?php echo anchor('account/editUser/' . $user['id'], 'Edit', 'class="btn"'); ?></div>
</div>
<?php
}
else
{
?>
	<div class='header3'><?php echo $header3; ?></div>
<?php
}
?>
<table class='center'>
<tbody>
	<tr><th class='text-right'>First Name:</th><td><?php echo $user['first_name']; ?></td></tr>
	<tr><th class='text-right'>Last Name:</th><td><?php echo $user['last_name']; ?></td></tr>
	<tr><th class='text-right'>Email:</th><td><?php echo $user['email']; ?></td></tr>
	<tr><th class='text-right'>Department Name: </th><td><?php echo $user['department_name']; ?></td></tr>
</tbody>	
</table>