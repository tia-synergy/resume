<?php
		include "connection.php";
		
		session_start();
		
		if (!isset($_SESSION['email']))
		{
			header("Location: login.php");
		}
		
		$userid = $_SESSION['userid'];
		
		$selectUser = "select * from usermaster where id =".$userid;
		$resUser = mysql_query($selectUser);
		$rowUser = mysql_fetch_array($resUser);
		
		$error = "";
		$success = "";
		$construct = "";
		// pagination
							$perpage = 4;
							if(isset($_GET["page"]))
							{
								$page = intval($_GET["page"]);
							}
							else 
							{
								$page = 1;
							}
							$calc = $perpage * $page;
							$start = $calc - $perpage;
							
							
		$selectResume = "select * from resume Limit $start, $perpage";
		$resSelectResume = mysql_query($selectResume);
		
		if($_SERVER['REQUEST_METHOD'] == "POST")
		{
			if(!empty($_POST['search']))
			{
				$category = $_POST["search_category"];
				$expert = $_POST["search_expert"];
				$location = $_POST["search_location"];
				$flag = $_POST["interview_flag"];
				
				if($category != "")
					$construct .= "|| category = '".$category."'";
				if($expert != "")
					$construct .= "|| expert like '%".$expert."%'";
				if($location != "")	
					$construct .= "|| city like '%".$location."%' || province like '%".$location."%'";
				if($flag != "")
					$construct .= "|| interview_flag=".$flag;
				if($construct != "")
				{
					$construct2 = substr($construct,2);
					$selectResume=" SELECT * FROM resume WHERE $construct2 ";
					$resSelectResume = mysql_query($selectResume);
					$start = 0;
				}
				else
				{
					$selectResume = "select * from resume Limit $start, $perpage";
					$resSelectResume = mysql_query($selectResume);
				}
			}
		}
		if(isset($_GET["resume_id"]))
		{
			$resume_id =  $_GET["resume_id"];
			$selectResume = "select * from resume where id=".$resume_id;
			$resultResume = mysql_query($selectResume);
			$recResume = mysql_fetch_array($resultResume);
					
			unlink("uploads/".$recResume["resume_file"]);
			$deleteResume = "delete from resume where id=".$resume_id;
			$resultResume = mysql_query($deleteResume);
			header("Location: list_resume.php");
		}
		$total_record = mysql_num_rows($resSelectResume);
?>

<html>
	<head>
		<title>Resume</title>
		<link rel="stylesheet" href="mystyle.css">
		<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
		<link rel="stylesheet" href="/resources/demos/style.css">
		<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		<style>
			page_links
			{
				font-family: arial, verdana;
				font-size: 12px;
				border:1px #000000 solid;
				padding: 6px;
				margin: 3px;
				background-color: #cccccc;
				text-decoration: none;
			}
			#page_a_link
			{
				font-family: arial, verdana;
				font-size: 12px;
				border:1px #000000 solid;
				color: #ff0000;
				background-color: #cccccc;
				padding: 6px;
				margin: 3px;
				text-decoration: none;
			}
	
	</style>
	</head>
	<body>
	<div>
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
		<div class="bodypart" >
			
			
			<div class="cl" style="height:3px;">
				<?php include "header.php"; ?>
			</div>
			<div class="cont_box1">
				<div class="cont_box1_header fl">
					<span style="text-align:center;"><h1>List Resume</h1></span>
					
				</div>
				<!-- SEARCH & VIEW ALL PART STARTS -->
				<div style="border: 1px solid;border-color:#DBE4E9;padding:10px;">
				<div class="fl" style="padding:8px 0 5px 0;margin-left:15px;">
					<form name= "list_form" method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>">
					<!-- search start -->
						<div class="textboxdv fl" style="width:auto;">
							<span class="searchtext">Category : </span>
							<!--<input name="search_category" id="search_category" value="" type="text" class="textboxsty" />-->
							<select name="search_category" id="search_category" >
								<option value="">-Area of Expertise-</option>
								<option value="IT Communications" >IT Communications</option>
								<option value="IT Network Infrastructure" >IT Network Infrastructure</option>
								<option value="IT Application" >IT Application</option>
								<option value="IT Managed Services" >IT Managed Services</option>
								<option value="Other" >Other</option>
							</select>
							<span class="searchtext">Expert : </span>
							<input name="search_expert" id="search_expert" value="" type="text" class="textboxsty" />
							
							<span class="searchtext">Location : </span>
							<input name="search_location" id="search_location" value="" type="text" class="textboxsty" />

							<span class="searchtext">Interviewed : </span>
							<select name="interview_flag" id="interview_flag" class="textboxsty" style="width:100px;">
								<option value="">-- all --</option>
								<option value="2" >No</option>
								<option value="1" >Yes</option>
							</select>
						</div>
						<div class="cl" style="height:10px;">&nbsp;</div>
						<!--<div class="fl">
							<span class="searchtext">Receive Date from : </span>
							<input name="receiveSdate" type="text" id="receiveSdate" size="10" maxlength="10"  class="textboxsty" style="width:100px;"  value=""/>
							
							<input type="button" id="cb_receiveSdate" onclick="document.getElementById('receiveSdate').value = '';" value="Clear" />
							<script type="text/javascript">
								$( function() {
									$( "#receiveSdate" ).datepicker({ dateFormat: 'yy-mm-dd' });
							} );    
							</script>
							&nbsp;&nbsp;
							<span class="searchtext">To</span>
							<input name="receiveEdate" type="text" id="receiveEdate" size="10" maxlength="10"  class="reg_input textboxsty" style="width:100px;"  value=""/>
							
							<input type="button" id="cb_receiveEdate" onclick="document.getElementById('receiveEdate').value = '';" value="Clear" />
							<script type="text/javascript">
								$( function() {
									$( "#receiveEdate" ).datepicker({ dateFormat: 'yy-mm-dd' });
							} );
							</script>
							&nbsp;&nbsp;
						</div>	
						<div class="cl" style="height:10px;">&nbsp;</div>
						<div class="fl">
							<span class="searchtext">Interview Date from : </span>
							<input name="interviewSdate" type="text" id="interviewSdate" size="10" maxlength="10"  class="reg_input textboxsty" style="width:100px;"  value=""/>
							
							<input type="button" id="cb_interviewSdate" onclick="document.getElementById('interviewSdate').value = '';" value="Clear" />
							<script type="text/javascript">
								$( function() {
									$( "#interviewSdate" ).datepicker({ dateFormat: 'yy-mm-dd' });
							} );      
							</script>
							&nbsp;&nbsp;
							<span class="searchtext">To</span>
							<input name="interviewEdate" type="text" id="interviewEdate" size="10" maxlength="10"  class="reg_input textboxsty" style="width:100px;"  value=""/>
							<input type="button" id="cb_interviewEdate" onclick="document.getElementById('interviewEdate').value = '';" value="Clear" />
							<script type="text/javascript">
								$( function() {
									$( "#interviewEdate" ).datepicker({ dateFormat: 'yy-mm-dd' });
							} );
							</script>
							&nbsp;&nbsp;
						</div>	-->
						<!--<div class="search_bt fl"><a href="#" onclick="window.location='<? //echo $_SERVER['PHP_SELF']; ?>?search_key='+document.getElementById('search_key').value"></a></div>-->
						
						<div class="textboxdv fl" style="width:auto;padding-left:5px;">
							<input type="submit"   value="SEARCH" name="search" id="search">
							<!--<a href="add_resume.php" class="btn" style="float:right;margin-right:120px;" >Add Resume</a>-->
						</div>
						
						<!-- search start -->
						
					</form>
				</div>
				
				<!-- SEARCH & VIEW ALL PART ENDS -->
				
				
				
				<!-- MAIN CONTENT STARTS -->
				<table align="center" border="1" cellpadding="5" cellspacing="0" class="tablesty" bordercolor="#dbe4e9" style="border-collapse:collapse;margin-left:15px;margin-right:15px;">
				<?php
				$k= ($start + 1);
				if($total_record > 0)
				{
				?>
					<tr>
						<th  height="33" align="center" class="header02">Sr</th>
						<th  height="33" align="center" class="header02">Category</th>
						<th  height="33" align="center" class="header02">Expert</th>
						
						<th  height="33" align="center" class="header02">Interviewed</th>
						<th  height="33" align="center" class="header02">Interview Date</th>
						<th width="10%" height="33" align="center" class="header02">Email</th>
						<th  height="33" align="center" class="header02">Location</th>
						<th  height="33" align="center" class="header02">Phone</th>
						<th width="15%" height="33" align="center" class="header02">Resume</th>
						<th  height="33" align="center" class="header02">Action</th>
					</tr>
					<?php
						
							while($recResume = mysql_fetch_array($resSelectResume))
							{
								
								$rid = $recResume["id"];
								$file = $recResume["resume_file"];
								$category = $recResume["category"];
								$expert = $recResume["expert"];
								$receive_date = $recResume["receive_date"];
								$flag = $recResume["interview_flag"];
								$interview_date = $recResume["interview_date"];
								$interview_score = $recResume["interview_score"];
								$province = $recResume["province"];
								$city = $recResume["city"];
								$email = $recResume["email"];
								$phone = $recResume["phone"];
								$comments = $recResume["comments"];
								
								if($province != "" || $city != "")
								{
									if($province == "")
										$location = ucfirst(strtolower($city));
									elseif($city == "")
										$location = ucfirst(strtolower($province));
									else
										$location = ucfirst(strtolower($city)).",".ucfirst(strtolower($province));
								}
								
								else
								{
									$location = "-";
								}
								if($phone == "")
								{
									$phone = "-";
								}
								?>
								<tr>
								<td  height="33" align="center" class="header03"><?php echo $k; ?></td>
								<td  height="33" align="center" class="header02"><?php echo $category; ?></td>
								<td  height="33" align="center" class="header02"><?php echo ucfirst(strtolower($expert)); ?></td>
								
								<td height="33" align="center" class="header02"><?php echo ($flag==2)?'No':'Yes'; ?></td>
								<td  height="33" align="center" class="header02"><?php echo $interview_date; ?></td>
								<td width="10%" height="33" align="center" class="header02"><?php echo $email; ?></td>
								<td  height="33" align="center" class="header02"><?php echo $location; ?></td>
								<td  height="33" align="center" class="header02"><?php echo $phone; ?></td>
								<td width="15%" height="33" align="center" class="header02"><a href="filedownload.php?filename=<?php echo $file; ?>"><?php echo $file; ?></a></td>
								
								<td  height="33" align="center" class="header02">
								<?php
								
									if($rowUser["operation"] == 1)
									{
								?>
								<a href="add_resume.php?resume_id=<?php echo $rid; ?>">Edit</a> | 
								<?php
									}
								?>
								<a href="view_resume.php?resume_id=<?php echo $rid; ?>">View</a> 
								<?php
									if($rowUser["operation"] == 1)
									{
								?>
								| <a href="list_resume.php?resume_id=<?php echo $rid; ?>" onclick="return confirm_alert(this);">Delete</a>
								<?php
									}
								?>
								
								</td>
								</tr>
								<?php
								$k++;
							}
						}
						else
						{
							echo "No record found";
						}
					?>
				</table>
		
		<div style="float:right;margin-top:10px;margin-bottom:10px;">
		<?php
		//if($total_record > 5)
		//{
		if(isset($page))
		{

		$result = mysql_query("select Count(*) As Total from resume");
		$rows = mysql_num_rows($result);

		if($rows)
		{
			$rs = mysql_fetch_assoc($result);
			$total = $rs["Total"];
		}
		$totalPages = ceil($total / $perpage);
		if($page <=1 )
		{
			echo "<span id='page_links' style='font-weight: bold;'>Prev</span>";
		}
		else
		{
			$j = $page - 1;
			echo "<span><a id='page_a_link' href='list_resume.php?page=$j'>< Prev</a></span>";
		}

		for($i=1; $i <= $totalPages; $i++)
		{
			if($i<>$page)
			{
				echo "<span><a id='page_a_link' href='list_resume.php?page=$i'>$i</a></span>";
			}
			else
			{
				echo "<span id='page_links' style='font-weight: bold;'>$i</span>";
			}
		}
		if($page == $totalPages )
		{
			echo "<span id='page_links' style='font-weight: bold;'>Next ></span>";
		}
		else
		{
			$j = $page + 1;
			echo "<span><a id='page_a_link' href='list_resume.php?page=$j'>Next</a></span>";
		}
		}
		//}
		?>
		</div>
		<div style="clear:both;"></div>
		</div>
		</div>
	</div>
	<script type="text/javascript">
		function confirm_alert(node) 
		{
			return confirm("Are you sure you want delete?");
		}
	</script>
	</body>
</html>
