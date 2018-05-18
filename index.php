<?php
	include "connection.php";
	session_start();
	
	$_SESSION["index"] = "index";
?>
<html>
	<head>
		<title>Resume Portal</title>
		<link rel="stylesheet" href="mystyle.css">
		
	</head>
	<body>
		<form method="post" action="login.php">
		<div class="content">
			<label>Hiretechs Resume</label>
			<input type="submit" name="submit1" value="Launch" />
		</div>
		</form>
	</body>
</html>