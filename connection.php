<?php
	$con = mysql_connect("localhost","root","");
	$db = mysql_select_db("resume");
	
	if (!$con)
	{
		die('Could not connect: ' . mysql_error());
	}
?>