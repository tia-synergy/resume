<?php
	include "connection.php";
	session_start();
	if (!(isset($_SESSION['email'])))
	{
		header("Location: login.php");
	}
	$userid = $_SESSION['userid'];
	$resume_id =  $_GET["resume_id"];
	
	$selectResume = "select * from resume where id=".$resume_id;
	$resultResume = mysql_query($selectResume);
	$recResume = mysql_fetch_array($resultResume);
?>
<html>
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
<div class="cont_box1" style="">
<form name="form_events" action="<?php echo $_SERVER['REQUEST_URI'];?>" method="post" encType="multipart/form-data" >
		<div class="tabing_main" align="center">
				<div class="resume-heading"> <h1>Resume Details</h1></div>
				<table align="center" width="55%" border="0" cellpadding="5" cellspacing="0" style="border:1px solid #DBE4E9;padding:20px;">
					<tr class="header04">
						<td align="right" valign="top">Resume : </td>
						<td align="left" valign="middle" class="text">
							
							<div id="divimgfile">
								<label><?php echo $recResume["resume_file"]; ?></label>
							</div>
							<span id="imgfile"></span>
							
						</td>
					</tr>
					<tr class="header04">
						<td width="20%" align="right" valign="middle">Category : </td>
						
						<td align="left" valign="middle">
							<?php echo $recResume["category"]; ?>
						</td>
						
					</tr>
					<tr class="header04">
						<td width="20%" align="right" valign="middle">Expert : </td>
						<td align="left" valign="middle"><?php echo $recResume["expert"]; ?></td>
					</tr>
					<tr class="header04">
						<td width="20%" align="right" valign="middle">Resume receive Date : </td>
						<td align="left" valign="middle"><?php echo $recResume["receive_date"]; ?></td>
					</tr>
					<tr class="header04">
						<td align="right" valign="top">Interviewed : </td>
						<td align="left" valign="middle" class="text"><?php echo $recResume["interview_flag"]; ?></td>
					</tr>
					<tr class="header04">
						<td width="20%" align="right" valign="middle">Interview Date : </td>
						<td align="left" valign="middle"><?php echo $recResume["interview_date"]; ?></td>
					</tr>
					<tr class="header04">
						<td width="20%" align="right" valign="middle">Interview Score : </td>
						<td align="left" valign="middle"><?php echo $recResume["interview_score"]; ?>  based on 10
						</td>
					</tr>
					<tr>
						<td width="20%" align="right" valign="middle">Province: </td>
						<td align="left" valign="middle"><?php echo $recResume["province"]; ?></td>
					</tr>
					<tr>
						<td width="20%" align="right" valign="middle">City: </td>
						<td align="left" valign="middle"><?php echo $recResume["city"]; ?></td>
						
					</tr>
					<tr>
						<td width="20%" align="right" valign="middle">Email: </td>
						<td align="left" valign="middle"><?php echo $recResume["email"]; ?></td>
					</tr>
					<tr>
						<td width="20%" align="right" valign="middle">Phone: </td>
						<td align="left" valign="middle"><?php echo $recResume["phone"]; ?></td>
					</tr>
					<tr>
						<td width="20%" align="right" valign="middle">Comments: </td>
						<td align="left" valign="middle"><pre><?php echo $recResume["comments"]; ?></pre></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td align="left"><!--<a href="add_resume.php?resume_id=<?php echo $recResume["id"]; ?>" class="btn" >Edit</a>-->
						
						&nbsp;
						<input type="button" name="cancel" id="cancel" value="Cancel" class="btn" onClick="document.location.href='list_resume.php'"; /></td>
						
					</tr>
				</table>
			</div>
		</form>
	</div>
</body>
</html>