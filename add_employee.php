<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<?php
	include "connection.php";
	session_start();
		
		if (!isset($_SESSION['email']))
		{
			header("Location: login.php");
		}
		$userid = $_SESSION['userid'];
		$error="";
		$success = "";
		
		if(isset($_GET["emp_id"]))
		{
			$selectEmp1 = "select * from usermaster where id=".$_GET["emp_id"];
			$resSelectEmp1 = mysql_query($selectEmp1);
			$recSelectEmp1 = mysql_fetch_array($resSelectEmp1);
				$_POST["emp_name"] = $recSelectEmp1["username"];
				$_POST["loginid"] = $recSelectEmp1["email"];
				
				$_POST["active"] = $recSelectEmp1["active"];
				if(isset($_POST["admin"]))
					$admin = 1;
				else
					$admin = 2;
		}
		if($_SERVER['REQUEST_METHOD'] == "POST")
		{
			
				 $name = $_POST["emp_name"];
				 $loginid = $_POST["loginid"];
				 $pass = $_POST["emp_pass"];
				$active = $_POST["active"];
				if(isset($_POST["admin"]))
					$admin = 1;
				else
					$admin = 2;
				
				
				if (!(filter_var($loginid, FILTER_VALIDATE_EMAIL)) )
				{
					$error = "$loginid is not a valid loginid";
				}
				else
				{
					if(!empty($_POST['submit']))
					{
						$selectEmp2 = "select * from usermaster where email='".$loginid."'";
						$resSelectEmp2 = mysql_query($selectEmp2);
						$affected = mysql_num_rows($resSelectEmp2);
					
						if($affected == 0)
						{
						
							$insertUser = "insert into usermaster (username,email,password,active,operation) values ('".$name."','".$loginid."','".md5($pass)."','".$active."',".$admin.")";
							$resInsertUser = mysql_query($insertUser);
					
							header("Location: list_employee.php");
						}
						else
						{
							$error = "Login ID  already exist";
						}
					}
					
					if(!empty($_POST['update']))
					{
						
						if($pass == "")
							$pass=$recSelectEmp1["password"];
						else
							$pass=md5($pass);
						$updateEmp = "update usermaster set username='".$name."',password='".$pass."',active='".$active."',operation=".$admin." where id= ".$_GET['emp_id'];
						$resUpdateEmp = mysql_query($updateEmp);
						header("Location: list_employee.php");
					}
				}
				
			}
			
	
?>


<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Resume</title>
		<link rel="stylesheet" href="mystyle.css">
		<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
		<link rel="stylesheet" href="/resources/demos/style.css">
		<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		<script>
			$(document).ready(function(){
				if($("#hidden_id").val() !=""){
					$("#emp_pass").hide();
				}
				$('#chk_pass').click(function() {
					if (!$(this).is(':checked')) {
						 $("#emp_pass").toggle();
					}
					if ($(this).is(':checked')) {
						 $("#emp_pass").toggle();
					}
				});
			});
</script>
	
	</head>
	<body>
	<input type="hidden" name="hidden_id" id="hidden_id" value="<?php echo (isset($_GET["emp_id"]))?$_GET["emp_id"]:''; ?>"/>
	<div class="cl" style="height:3px;">
				<?php include "header.php"; ?>
	</div>
	<div class="cont_box1">
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
	<form name="form_events" action="<?php echo $_SERVER['REQUEST_URI'];?>" method="post" encType="multipart/form-data" >
		<div class="tabing_main " align="center">
				<?php
					if(isset($_GET["emp_id"]))
					{
						?>
						<div class="resume-heading"> <h1>Edit Employee</h1></div>
						<?php
					}
					else
					{
						?>
						<div class="resume-heading"> <h1>Add Employee</h1></div>
						<?php
					}
				?>
				<table align="center" width="70%" border="0" cellpadding="5" cellspacing="0" style="border:1px solid #DBE4E9">
					<tr class="header04">
						<td width="20%" align="right" valign="middle">Name : </td>
						<td align="left" valign="middle"><input type="text" name="emp_name" id="emp_name" class="textboxsmallsty"  style="width:120px;" value="<?php echo (isset($_POST['emp_name']))?$_POST['emp_name']:''; ?>" required /></td>
					</tr>
					<tr class="header04">
						<td width="20%" align="right" valign="middle">Login ID : </td>
						<td align="left" valign="middle"><input type="text" name="loginid" id="loginid" class="textboxsmallsty"  style="width:120px;" value="<?php echo (isset($_POST['loginid']))?$_POST['loginid']:''; ?>" <?php echo (isset($_GET['emp_id']))?'readonly':''; ?> required /></td>
					</tr>
					
					
					<tr class="header04" id="pass_row">
						
						<td width="20%" align="right" valign="middle">Password : </td>
						<td align="left" valign="middle">
						<?php
							if(isset($_GET["emp_id"]))
							{
						?>
						<input type="checkbox" name="chk_pass" id="chk_pass"  /> 
					
						<?php
							}
						?>
						<input type="password" name="emp_pass" id="emp_pass" class="textboxsmallsty"  style="width:120px;" value="" <?php echo (isset($_GET['emp_id']))?'':'required'; ?>/>
						</td>
					</tr>
					
					<tr class="header04">
						<td width="20%" align="right" valign="middle">Active: </td>
						
						<td align="left" valign="middle">
							<select required name="active" id="active" >
								<option value="">-select-</option>
								<option value="yes" <?php if(isset($_POST['active'])){ if($_POST['active'] == 'yes') echo 'selected="selected"'; }?> >yes</option>
								<option value="no" <?php if(isset($_POST['active'])){ if($_POST['active'] == 'no') echo 'selected="selected"'; } ?> >no</option>
							</select>
						</td>
					</tr>
					<tr class="header04">
						<td width="20%" align="right" valign="middle">Make admin : </td>
						<td align="left" valign="middle"><input type="checkbox" name="admin" id="admin"  <?php if(isset($_GET["emp_id"])) echo ($recSelectEmp1['operation'] == 1)?'checked':''; ?> class="textboxsmallsty"  /></td>
					</tr>
					<tr>
					<?php
					if(!(isset($_GET["emp_id"])))
					{
						?>
						<td width="20%" align="right" valign="middle"><input type="submit" name="submit" id="submit" value="Add" /></td>
						<?php
					}
					else
					{
						?>
						<td width="20%" align="right" valign="middle"><input type="submit" name="update" id="update" value="Update" /></td>
						<?php
					}
					?>
						<td width="20%" align="left" valign="middle"><input type="reset" name="cancel" id="cancel" value="Cancel" onClick="document.location.href='list_employee.php';"/></td>
					</tr>
					
				</table>
			</div>
		</form>
	
	</body>
</html>
	