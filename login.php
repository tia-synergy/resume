<?php
	include "connection.php";
	 
	session_start();
	
	if (!(isset($_SESSION['index'])))
	{
		header("Location: index.php");
	}
	
	$error = "";
	$success = "";
	
	if($_SERVER['REQUEST_METHOD'] == "POST")
	{
		
		if(!empty($_POST['submit']))
		{
			$email = $_POST["email"];
			$pass = $_POST["pass"];
			
			if (!(filter_var($email, FILTER_VALIDATE_EMAIL))) {
				$error = "$email is not a valid email address";
			}
			else
			{
				$select = "select * from usermaster where email='".$email."' && password='".md5($pass)."' && active='yes'";
				$resSelect = mysql_query($select);
				$count = mysql_num_rows($resSelect);
			
				if($count == 1)
				{
					$rowSelect = mysql_fetch_array($resSelect);
				
					$_SESSION['email'] = $email;
					$_SESSION['userid'] = $rowSelect["id"];
				
					header("Location: list_resume.php");
				}
				else
				{
					$error =  "Email and Password does no match";
				}
			}
		}
	}
	
?>
<html>
	<head>
		<title>Login</title>
		<link rel="stylesheet" href="mystyle.css">
		<style>
			
		</style>
	</head>
	<body>
		
		<div class="content">
		<div class="">
		<?php
			if($error != "")
			{
				?>
				<h2 style="color:red;"><?php echo $error; ?></h2>
				<?php
			}
			if($success != "")
			{
				?>
				<h2 style="color:green;"><?php echo $success; ?></h2>
				<?php
			}
		?>
		</div>
			<div class="resume-heading"> <h1>LOGIN</h1></div>
			<div class="login-border">
			<form method="post" action="">
			<div class="input-item">
				<label>Email:</label>
				<input type="text" name="email" id="email" value="<?php // echo $email; ?>" required />
			</div>
			<div class="input-item">
				<label>Password:</label>
				<input type="password" name="pass" id="pass" value="<?php // echo $pass; ?>" required />
			</div>
			<div>
				<input type="submit" name="submit" id="submit" value="Login" />
				<input type="reset" name="reset" id="reset" value="Cancel" onClick="document.location.href='index.php';" />
			</div>
			</form>
			</div>
		</div>
	</body>
</html>