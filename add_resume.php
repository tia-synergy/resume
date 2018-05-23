<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<?php
	include "connection.php";
	session_start();
		
		if (!isset($_SESSION['email']))
		{
			header("Location: login.php");
		}
		$userid = $_SESSION['userid'];
	$error = "";
	$success = "";
	$category = "";
	$expert = "";
	$receive_date = "";
	$flag = "";
	$interview_date = "";
	$interview_score = "";
	$province = "";
	$city = "";
	$email = "";
	$phone = "";
	$comments = "";
	
	if(isset($_GET["resume_id"]))
	{
		$resume_id = $_GET["resume_id"];
		
		$selectResume = "select * from resume where id=".$resume_id;
		$resultResume = mysql_query($selectResume);
		$recResume = mysql_fetch_array($resultResume);
		
			$category = $recResume["category"];
			$_POST["expert"] = $recResume["expert"];
			$_POST["receive_date"] = $recResume["receive_date"];
			$flag = $recResume["interview_flag"];
			$_POST["interview_date"] = $recResume["interview_date"];
			$_POST["interview_score"] = $recResume["interview_score"];
			$_POST["province"] = $recResume["province"];
			$_POST["city"] = $recResume["city"];
			$_POST["email"] = $recResume["email"];
			$_POST["phone"] = $recResume["phone"];
			$_POST["comments"] = $recResume["comments"];
	}	
			
		
		
	if($_SERVER['REQUEST_METHOD'] == "POST")
	{
		if(!empty($_POST['save']))
		{
			$file_name =  $_FILES["resume"]["name"];
			$category = $_POST["category"];
			$expert = $_POST["expert"];
			$receive_date = $_POST["receive_date"];
			$flag = $_POST["interview_flag"];
			$interview_date = $_POST["interview_date"];
			$interview_score = $_POST["interview_score"];
			$province = $_POST["province"];
			$city = $_POST["city"];
			$email = $_POST["email"];
			$phone = $_POST["phone"];
			$comments = $_POST["comments"];
			
			if (!(filter_var($email, FILTER_VALIDATE_EMAIL)) )
			{
				$error = "$email is not a valid email address";
				
			}
			if($phone != "")
			{
				if(!(preg_match("/^[0-9]{3}[0-9]{3}[0-9]{4}$/", $phone)) )
					$error = "$phone is not a valid";
			}
			
					if($file_name!="")
					{
						$file_name = $_FILES['resume']['name']; //take the path (filename with extension)
						$allowedExts = array("doc", "docx");
						$extension = end(explode(".", $_FILES["resume"]["name"]));
						if ( ($_FILES["resume"]["type"] == "application/msword") || ($_FILES["resume"]["type"] == "application/vnd.openxmlformats-officedocument.wordprocessingml.document")  && in_array($extension, $allowedExts))
						{
						
							if ($_FILES["resume"]["error"] > 0)
							{
								$error = "Please select .doc/.docx file";
							}
							else
							{
								$infoimg = pathinfo($file_name); //store info like filename, base path and extension in $info array
								$extimg = ".".$infoimg['extension']; //store extension in a variable with prefix of dot (.).
								$rimg = "_".rand(1111,9999);
								$org_imgfilename = $infoimg['filename'];
								$new_imgfile_name=$org_imgfilename.$rimg.$extimg;
								$file_name= "uploads/".$new_imgfile_name; 
								move_uploaded_file($_FILES['resume'] ['tmp_name'],$file_name);
								
								$insertResume = "insert into resume (resume_file,category,expert,receive_date,interview_flag,interview_date,interview_score,province,city,email,phone,comments)
								values ('".$new_imgfile_name."','".$category."','".$expert."','".$receive_date."','".$flag."','".$interview_date."','".$interview_score."','".$province."','".$city."','".$email."','".$phone."','".$comments."')";
								$resultResume = mysql_query($insertResume);
								//$success = "Record added successfully";
								header("Location: list_resume.php");
							}
						}
						else
						{
							$error = "Please select .doc/.docx file";
						}
						
					}
			}
		
	}
	if(!empty($_POST['update']))
	{
			
			$file_name =  $_FILES["resume"]["name"];
			$category = $_POST["category"];
			$expert = $_POST["expert"];
			$receive_date = $_POST["receive_date"];
			$flag = $_POST["interview_flag"];
			$interview_date = $_POST["interview_date"];
			$interview_score = $_POST["interview_score"];
			$province = $_POST["province"];
			$city = $_POST["city"];
			$email = $_POST["email"];
			$phone = $_POST["phone"];
			$comments = $_POST["comments"];
			
			if (!(filter_var($email, FILTER_VALIDATE_EMAIL)) )
			{
				$error = "$email is not a valid email address";
				
			}
			if($phone != "")
			{
				if(!(preg_match("/^[0-9]{3}[0-9]{3}[0-9]{4}$/", $phone)) )
					$error = "$phone is not a valid";
			}
			
					if($file_name == "")
					{
						
							$new_imgfile_name = $recResume["resume_file"];
					}	
					else
					{
						unlink("uploads/".$recResume["resume_file"]);
					
					
					
						//$file_name = $_FILES['resume']['name']; //take the path (filename with extension)
						$allowedExts = array("doc", "docx");
						$extension = end(explode(".", $_FILES["resume"]["name"]));
						if ( ($_FILES["resume"]["type"] == "application/msword") || ($_FILES["resume"]["type"] == "application/vnd.openxmlformats-officedocument.wordprocessingml.document")  && in_array($extension, $allowedExts))
						{
						
							if ($_FILES["resume"]["error"] > 0)
							{
								$error = "Please select .doc/.docx file";
							}
							else
							{
								$infoimg = pathinfo($file_name); //store info like filename, base path and extension in $info array
								$extimg = ".".$infoimg['extension']; //store extension in a variable with prefix of dot (.).
								$rimg = "_".rand(1111,9999);
								$org_imgfilename = $infoimg['filename'];
								$new_imgfile_name=$org_imgfilename.$rimg.$extimg;
								$file_name= "uploads/".$new_imgfile_name; 
								move_uploaded_file($_FILES['resume'] ['tmp_name'],$file_name);
													
								
								/*$selectResume = "select * from resume where id=".$_GET["resume_id"];
								$resultResume = mysql_query($selectResume);
								$recResume = mysql_fetch_array($resultResume);*/
							}
								
						}
						else
						{
							$error = "Please select .doc/.docx file";
						}
					}
						echo $updateResume = "update resume set resume_file = '".$new_imgfile_name."',
													   category = '".$category."',
													   expert = '".$expert."',
													   receive_date = '".$receive_date."',
													   interview_flag = '".$flag."',
													   interview_date = '".$interview_date."',
													   interview_score = '".$interview_score."',
													   province = '".$province."',
													   city = '".$city."',
													   email = '".$email."',
													   phone = '".$phone."',
													   comments = '".$comments."' where id=".$_GET["resume_id"];
								$resUpdateResume = mysql_query($updateResume);
								header("Location: list_resume.php");
							
					
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
	
	
	</head>
	<body>
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
					if(isset($_GET["resume_id"]))
					{
						?>
						<div class="resume-heading"> <h1>Edit Resume123</h1></div>
						<?php
					}
					else
					{
						?>
						<div class="resume-heading"> <h1>Add Resume123</h1></div>
						<?php
					}
				?>
				
				<table align="center" width="70%" border="0" cellpadding="5" cellspacing="0" style="border:1px solid #DBE4E9">
					<tr class="header04">
						<td align="right" valign="top">Upload Resume : </td>
						<td align="left" valign="middle" class="text">
							
							<div id="divimgfile">
								<?php if(isset($_GET["resume_id"])) echo $recResume["resume_file"]; ?>
								<input type="file" name="resume" id="resume"  class="fileboxsty" size="22" maxlength="200" border="0"  <?php echo (isset($_GET['resume_id']))?'':'required'?> />
							</div>
							<span id="imgfile"></span>
							
						</td>
					</tr>
					<tr class="header04">
						<td width="20%" align="right" valign="middle">Category : </td>
						
						<td align="left" valign="middle">
							<select required name="category" id="category" >
								<option value="">-Area of Expertise-</option>
								<option value="IT Communications" <?php if($category=="IT Communications") echo 'selected="selected"'; ?>>IT Communications</option>
								<option value="IT Network Infrastructure" <?php if($category=="IT Network Infrastructure") echo 'selected="selected"'; ?>>IT Network Infrastructure</option>
								<option value="IT Application" <?php if($category=="IT Application") echo 'selected="selected"'; ?>>IT Application</option>
								<option value="IT Managed Services" <?php if($category=="IT Managed Services") echo 'selected="selected"'; ?>>IT Managed Services</option>
								<option value="Other" <?php if($category=="Other") echo 'selected="selected"'; ?>>Other</option>
							</select>
						</td>
						
					</tr>
					<tr class="header04">
						<td width="20%" align="right" valign="middle">Expert : </td>
						<td align="left" valign="middle"><input type="text" name="expert" id="expert" class="textboxsmallsty"  style="width:120px;" value="<?php if (isset($_POST['expert'])) echo $_POST['expert']; ?>" required /></td>
					</tr>
					<tr class="header04">
						<td width="20%" align="right" valign="middle">Resume receive Date : </td>
						<td align="left" valign="middle">
						<input name="receive_date" type="text" id="receive_date" size="10" maxlength="10" class="dateboxsty"  value="<?php if (isset($_POST['receive_date'])) echo $_POST['receive_date']; ?>" required />
						
						<input type="button" id="cb_receive_date" onclick="document.getElementById('receive_date').value = '';" value="Clear" />
						<script type="text/javascript">
							$( function() {
									$( "#receive_date" ).datepicker({ dateFormat: 'yy-mm-dd' });
							} );
                        </script>
						&nbsp;&nbsp;(YYYY-MM-DD) </td>
					</tr>
					<tr class="header04">
						<td align="right" valign="top">Interviewed : </td>
						<td align="left" valign="middle" class="text">
							<select name="interview_flag" id="interview_flag" class="textboxsty" style="width:80px;">
								<option value="2" <?php if($flag==2) echo 'selected="selected"'; ?> > No</option>
								<option value="1" <?php if($flag==1) echo 'selected="selected"'; ?> >Yes</option>
							</select>
						</td>
					</tr>
					<tr class="header04">
						<td width="20%" align="right" valign="middle">Interview Date : </td>
						<td align="left" valign="middle">
						<input name="interview_date" type="text" id="interview_date" size="10" maxlength="10" class="dateboxsty"  value="<?php if (isset($_POST['interview_date'])) echo $_POST['interview_date']; ?>" required />
						
						<input type="button" id="cb_interview_date" onclick="document.getElementById('interview_date').value = '';" value="Clear" />
						<script type="text/javascript">
							$( function() {
									$( "#interview_date" ).datepicker({ dateFormat: 'yy-mm-dd' });
							} );
                        </script>
						&nbsp;&nbsp;(YYYY-MM-DD) </td>
					</tr>
					<tr class="header04">
						<td width="20%" align="right" valign="middle">Interview Score : </td>
						<td align="left" valign="middle"><input type="text" name="interview_score" id="interview_score" class="textboxsmallsty" maxlength="15" style="width:120px;" value="<?php if (isset($_POST['interview_score'])) echo $_POST['interview_score']; ?>" required />Rate based on 10
						</td>
					</tr>
					<tr>
						<td width="20%" align="right" valign="middle">Province: </td>
						<td align="left" valign="middle"><input type="text" name="province" id="province" class="textboxsmallsty"  style="width:120px;" value="<?php if (isset($_POST['province'])) echo $_POST['province']; ?>"  />
						</td>
					</tr>
					<tr>
						<td width="20%" align="right" valign="middle">City: </td>
						<td align="left" valign="middle">
						<input type="text" name="city" id="city" class="textboxsmallsty"  style="width:120px;" value="<?php if (isset($_POST['city'])) echo $_POST['city']; ?>" />
						</td>
						
					</tr>
					<tr>
						<td width="20%" align="right" valign="middle">Email: </td>
						<td align="left" valign="middle"><input type="text" name="email" id="email" class="textboxsmallsty"  style="width:120px;" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>" required />
						</td>
					</tr>
					<tr>
						<td width="20%" align="right" valign="middle">Phone: </td>
						<td align="left" valign="middle"><input type="text" name="phone" id="phone" class="textboxsmallsty" maxlength="10" style="width:120px;" value="<?php if (isset($_POST['phone'])) echo $_POST['phone']; ?>" /> (000 000 0000)
						</td>
					</tr>
					<tr>
						<td width="20%" align="right" valign="middle">Comments: </td>
						<td align="left" valign="middle">
						<textarea name="comments" id="comments" cols="35" rows="5"><?php if (isset($_POST['comments'])) echo $_POST['comments']; ?></textarea>
						</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<?php
							if(isset($_GET["resume_id"]))
							{
								?>
								<td align="left"><input type="submit" name="update" id="update" value="Update" class="btn"  />
								<?php
							}
							else
							{
								?>
								<td align="left"><input type="submit" name="save" id="save" value="Save" class="btn"  />
								<?php
							}
						?>
						
						
						&nbsp;
						<input type="button" name="cancel" id="cancel" value="Cancel" class="btn" onClick="document.location.href='list_resume.php'" /></td>
						<!--<td><a href="list_resume.php" class="btn">View All</a></td>-->
					</tr>
				</table>
			</div>
		</form>
	
	</body>
</html>
	
