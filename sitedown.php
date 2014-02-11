<?php 
if(file_exists('downsettings.php'))
{
	include 'downsettings.php';
}
?>

<html>
<head>
<title>Down for maintenance!</title>
</head>
<body>
<h1>This site is down, but no need for alarm</h1>
<p>
You were right, it wasn't you.. it was us.</p>
<p>But we're different now, and we really <em>want</em> to change.</p>
<p>Give us another chance, and by tomorrow we'll be a whole new site, a better site, promise.</p>
<h4>Service should be restored by <?php if (isset($expectedUp)) { echo $expectedUp; } else { echo date('r', strtotime('+2 hours')); } ?></h4>
<p>We apologize for any inconveniences caused.</p>
<p>Please contact <a href="mailto:ecook@micron.com">Erin Cook</a> with any concerns/issues. </p>
</body>
</html>