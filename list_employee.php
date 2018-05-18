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
							
		$selectUser1 = "select * from usermaster Limit $start, $perpage";
		$resUser1 = mysql_query($selectUser1);		
		
		if($_SERVER['REQUEST_METHOD'] == "POST")
		{
			if(!empty($_POST['search']))
			{
				if($_POST["search_name"] != "")
				{
					$name = $_POST["search_name"];
			
					$selectUser1 = "select * from usermaster where username='".$name."'";
				    $resUser1 = mysql_query($selectUser1);
					$start = 0;
				}
				else
				{
					$selectUser1 = "select * from usermaster Limit $start, $perpage";
					$resUser1 = mysql_query($selectUser1);	
				}
			}
		}
		if(isset($_GET["emp_id"]))
		{
			$emp_id =  $_GET["emp_id"];
			
			$deleteEmp = "delete from usermaster where id=".$emp_id;
			$resultEmp = mysql_query($deleteEmp);
			header("Location: list_employee.php");
		}
						
	$total_record1 = mysql_num_rows($resUser1);
		
?>

<html>
	<head>
		<title>List Employee</title>
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
					<span style="text-align:center;"><h1>List Employee</h1></span>
					
				</div>
				<!-- SEARCH & VIEW ALL PART STARTS -->
				<div style="border: 1px solid;border-color:#DBE4E9;padding:10px;">
				<div class="fl" style="padding:8px 0 5px 0;margin-left:15px;">
					<form name= "list_form" method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>">
					<!-- search start -->
						<div class="textboxdv fl" style="width:auto;">
							<span class="searchtext">Name  : </span>
							<input name="search_name" id="search_name" value="" type="text" class="textboxsty" />
							<input type="submit"   value="SEARCH" name="search" id="search">
						</div>
						<!-- search start -->
						
					</form>
				</div>
				
				<!-- SEARCH & VIEW ALL PART ENDS -->
				
				
				
				<!-- MAIN CONTENT STARTS -->
				<table align="center" border="1" cellpadding="5" cellspacing="0" class="tablesty" bordercolor="#dbe4e9" style="border-collapse:collapse;width:700px;">
				<?php
					
					$k= ($start + 1);
				if($total_record1 > 0)
				{
				?>
					<tr>
						<th  height="33"  align="center" class="header02">Sr</th>
						<th  height="33" width="40%" align="center" class="header02">Name</th>
						<th  height="33" width="40%" align="center" class="header02">Login ID</th>
						
						<th  height="33" align="center" class="header02">Active</th>
						
						<th  height="33" width="40%" align="center" class="header02">Action</th>
					</tr>
					<?php
						
							while($recUser1 = mysql_fetch_array($resUser1))
							{
								
								$uid = $recUser1["id"];
								$name = $recUser1["username"];
								$loginid = $recUser1["email"];
								$active = $recUser1["active"];
								
								?>
								<tr>
								<td  height="33" align="center" class="header03"><?php echo $k; ?></td>
								<td  height="33" align="center" class="header02"><?php echo $name; ?></td>
								<td  height="33" align="center" class="header02"><?php echo $loginid; ?></td>
								
								<td height="33" align="center" class="header02"><?php echo $active; ?></td>
								
								<td  height="33" align="center" class="header02">
								
								<a href="add_employee.php?emp_id=<?php echo $uid; ?>">Edit</a> | 
								<a href="list_employee.php?emp_id=<?php echo $uid; ?>" onclick="return confirm_alert(this);">Delete</a>
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

		$result = mysql_query("select Count(*) As Total from usermaster");
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
			echo "<span><a id='page_a_link' href='list_employee.php?page=$j'>< Prev</a></span>";
		}

		for($i=1; $i <= $totalPages; $i++)
		{
			if($i<>$page)
			{
				echo "<span><a id='page_a_link' href='list_employee.php?page=$i'>$i</a></span>";
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
			echo "<span><a id='page_a_link' href='list_employee.php?page=$j'>Next</a></span>";
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