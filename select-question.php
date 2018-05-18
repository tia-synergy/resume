<?php
	include_once "db_connect.php";
	
	session_start();
	if( !isset($_SESSION['email']) )
	{
		header("location:login.php");
	}
	
	$client_id = $_SESSION['clientid'];
	$question_added = 0;
	
	//$selectCquestion = "select * from tb_cquestion where client_id='$client_id'";
	$selectCquestion = "select * from tb_questions where question_default=1";
	$resSelectCquestion = mysql_query($selectCquestion);
	
	if(mysql_num_rows($resSelectCquestion)>0)
	{
		$question_added = 1;
	}
	else
	{
		$selectCquestion = "select * from tb_questions where question_default=1";
		$resSelectCquestion = mysql_query($selectCquestion);
	}
	
	if($_SERVER['REQUEST_METHOD'] == "POST")
	{
		if(!empty($_POST['question-submit']))
		{
			$total_que = count($_POST["que"]);
			$que_id = array();
			if($_POST["que"] != "" && $total_que <= 10)
			{
				foreach($_POST["que"] as $selectedQue)
				{
					
					//$insertCustQue = "insert into tb_selectedquestions (client_id,question_id) values (".$client_id.",".$selectedQue.")";
					//$resultCustQue = mysql_query($insertCustQue);
					$que_id[] = $selectedQue;
					
				}
				$_SESSION["questions"] = $que_id;
				header("location:firstcustomer.php");
				
			}
			else
			{
				$error = "Please select minimum 1 or maximum 10 questions";
			}
			
		}
		if(isset($_GET["search_id"]))
		{
			$search = $_POST[ 'search' ]; 
		
			if( strlen( $search ) <= 1 ) 
				$error = "Search term too short"; 
			else 
			{ 
			 
				$search_exploded = explode ( " ", $search ); 
				$x = 0; 
				foreach( $search_exploded as $search_each ) 
				{ 
					$x++; 
					$construct = ""; 
					if( $x == 1 ) 
						$construct .="question_name LIKE '%$search_each%' || question LIKE '%$search_each%'"; 
					else 
						$construct .="AND question_name LIKE '%$search_each%' || question LIKE '%$search_each%'"; 
				} 
				$selectCquestion = " SELECT * FROM tb_questions WHERE $construct "; 
				$resSelectCquestion = mysql_query( $selectCquestion ); 
				$foundnum = mysql_num_rows($resSelectCquestion); 
				if ($foundnum == 0) 
					$error = "Sorry, there are no matching result for <b> $search </b>. </br> </br> 1. Try more general words. for example: If you want to search 'how to create a website' then use general keyword like 'create' 'website' </br> 2. Try different words with similar meaning </br> 3. Please check your spelling"; 
				else 
				{ 
					$success = "$foundnum results found !<p>"; 
					
				} 
			} 
	
		}
		if(!empty($_POST['delete-que']))
		{
			foreach($_POST["que"] as $selectedQue)
			{
				$deleteSelectedQue = "delete from tb_questions where id=".$selectedQue;
				$resultDeleteQue = mysql_query($deleteSelectedQue);
				
			}
			$success = "Questions deleted successfully";
		}
		if(isset($_GET["addQue"]))
		{
			
			$title1 = $_POST['heading_add'];
			$question1 = $_POST['question_add'];
			
			$select = "select max(question_no) as maxId from tb_questions";
			$resSelect = mysql_query($select);
			$rowSelect = mysql_fetch_array($resSelect);
			$num = $rowSelect["maxId"]+1;
			if($title1 != "" && $question1 != "")
			{
				$insert = "insert tb_questions (question_name,question,question_no,question_default) values ('".$title1."','".$question1."',".$num.",1)";
				$result = mysql_query($insert);
			
				//$success = "Question added successfully";
				header("Location: select-question.php");
			}
			else
			{
				$error = "Please enter data";
			}
			
		}
	}
?>
<!DOCTYPE html>
<html>
<head>

	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/> 
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1,requiresActiveX=true">
    
	<title>Customers</title>
	<meta name="description" content=" add description  ... ">
    
    <!--<link href="_layout/css/tooltip.css" rel="stylesheet" type="text/css" />-->
	
	<link href="_layout/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
	<link href="_layout/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
	<link href="_layout/css/_all-skins.min.css" rel="stylesheet" type="text/css" />
	
	<!-- /// Favicons ////////  -->
    <link rel="shortcut icon" href="favicon.png">
	<link rel="apple-touch-icon-precomposed" sizes="144x144" href="apple-touch-icon-144-precomposed.png">

	<!-- /// Google Fonts ////////  -->
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800">
	<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=PT+Sans:400,400italic,700,700italic">
    
	<link rel="stylesheet" href="_layout/css/foundation.min.css">
	
    <!-- /// FontAwesome Icons 4.2.0 ////////  -->
	<link rel="stylesheet" href="_layout/css/fontawesome/font-awesome.min.css">
    
    <!-- /// Custom Icon Font ////////  -->
    <link rel="stylesheet" href="_layout/css/iconfontcustom/icon-font-custom.css">  
    
	<!-- /// Template CSS ////////  -->
    <link rel="stylesheet" href="_layout/css/base.css">
    <link rel="stylesheet" href="_layout/css/grid.css">
    <link rel="stylesheet" href="_layout/css/elements.css">
    <link rel="stylesheet" href="_layout/css/layout.css">
    
	<!-- /// Boxed layout ////////  -->
	<!-- <link rel="stylesheet" href="_layout/css/boxed.css"> -->
    
	<!-- /// JS Plugins CSS ////////  -->
	<link rel="stylesheet" href="_layout/js/revolutionslider/css/settings.css">
	<link rel="stylesheet" href="_layout/js/revolutionslider/css/custom.css">
    <link rel="stylesheet" href="_layout/js/bxslider/jquery.bxslider.css">
    <link rel="stylesheet" href="_layout/js/magnificpopup/magnific-popup.css">
    <link rel="stylesheet" href="_layout/js/animations/animate.min.css">
	<link rel="stylesheet" href="_layout/js/itplayer/css/YTPlayer.css">
    
    <!-- /// Template Skin CSS ////////  -->
	<!-- <link rel="stylesheet" href="_layout/css/skins/default.css"> -->
    <!-- <link rel="stylesheet" href="_layout/css/skins/red.css"> -->
	<!-- <link rel="stylesheet" href="_layout/css/skins/yellow.css"> -->
	<!-- <link rel="stylesheet" href="_layout/css/skins/green.css"> -->
    <script src="_layout/js/vendor/modernizr.js"></script>
    
	
	<style>
body {font-family: Arial, Helvetica, sans-serif;}

/* The Modal (background) */
.modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    padding-top: 100px; /* Location of the box */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
    background-color: #fefefe;
    margin: auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
}

/* The Close Button */
.close {
    color: #aaaaaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}
.reveal-modal-bg {
    position: absolute !important;
}

/** LIGHTBOX MARKUP **/

.lightbox {
	/** Default lightbox to hidden */
	display: none;

	/** Position and style */
	position: fixed;
	z-index: 999;
	width: 100%;
	height: 100%;
	text-align: center;
	top: 0;
	left: 0;
	background: rgba(0,0,0,0.8);
}

.lightbox img {
	/** Pad the lightbox image */
	max-width: 90%;
	max-height: 80%;
	margin-top: 2%;
}

.lightbox:target {
	/** Remove default browser outline */
	outline: none;

	/** Unhide lightbox **/
	display: block;
}
</style>


</head>
<body>
	<noscript>
        <div class="javascript-required">
            <i class="fa fa-times-circle"></i> You seem to have Javascript disabled. This website needs javascript in order to function properly!
        </div>
    </noscript>
    <div id="wrap">
		<div id="header-top">
        <!-- /// HEADER-TOP  //////////////////////////////////////////////////////////////////////////////////////////////////////// -->
            
            
        <!-- //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->
        </div><!-- end #header-top -->
        <div id="header" style="top:30px;">
        <!-- /// HEADER ////////////////////////////////////////////////////// -->
			<div class="row">
				<div class="span3">
					<!-- // Logo // -->
					<a href="select-question.php" id="logo">
                    	<img class="responsive-img" src="_layout/images/logo.png" alt="">
                    </a>
				</div><!-- end .span3 -->
				<div class="span9">
					<div style="float:right;">
						<a href="">Your Settings</a> | 
						<a href="logout.php">Logout</a>
					</div>
					<!-- // Mobile menu trigger // -->
					<a href="#" id="mobile-menu-trigger">
                    	<i class="fa fa-bars"></i>
                    </a>
                	<!-- // Menu // -->
					<?php /*<ul class="sf-menu fixed" id="menu">
						<li>
                        	<a href="logout.php">Results</a>                              
                        </li>
						<li>
                        	<a href="logout.php">Customers</a>                              
                        </li>
						<li>
                        	<a href="logout.php">Heartbeats</a>                              
                        </li>
						<li>
                        	<a href="logout.php">Report</a>                              
                        </li>
                        <li>
                        	<a href="logout.php">Logout</a>                              
                        </li>
					</ul>*/ ?>
				</div><!-- end .span9 -->
			</div><!-- end .row -->            
        <!-- ///////////////////////////////////////////////////////////////////////////////////////////////////////////// -->
		</div><!-- end #header -->		
		<div id="content" style="margin-top:150px;">
		<!-- /// CONTENT  //////////////////////////////////////////////////////////////////////////////////////////////// -->
			<div class="row">
				<div id="formstatus">
					<?php
					if("" != $success)
					{
					?>
						<div class="alert success"><i class="fa fa-check-circle-o"></i><?php echo $success;?></div>
					<?php
					}
					elseif("" != $error)
					{
					?>
						<div class="alert error"><i class="fa fa-times-circle"></i><?php echo $error;?></div>
					<?php
					}
					?>
				</div>
				<div class="span12">
                    <div class="tabs-container" id="tabs-container">
				        <ul class="tabs-menu fixed">
                            <li id="tab-1">
                            <?php
							$selectCompany = "select * from tb_company where client_id='$client_id'";
							$resSelectCompany = mysql_query($selectCompany);
							if(mysql_num_rows($resSelectCompany)>0)
							{
							?>
								<a href="prepare.php">SETUP YOUR COMPANY</a>
							<?php
							}
							else
							{
							?>
								<a href="" style="opacity:0.5; cursor:default;">SETUP YOUR COMPANY</a>
							<?php
							}
							?>
                            </li>
                            <li id="tab-2" class="active">
                                <a href="select-question.php">CREATE YOUR SURVEY</a>
						    </li>
                            <li id="tab-3">
                                <?php
								if($question_added==1)
								{
								?>
									<a href="firstcustomer.php">ADD YOUR CUSTOMERS</a>
								<?php
								}
								else
								{
								?>
									<a href="" style="opacity:0.5; cursor:default;">ADD YOUR CUSTOMERS</a>
								<?php
								}
								?>
							</li>
                            <li id="tab-4">
                                <?php
								$selectCustomer = "select * from tb_customer where client_id='$client_id'";
								$resSelectCustomer = mysql_query($selectCustomer);
								
								if(mysql_num_rows($resSelectCustomer)>0)
								{
								?>
									<a href="#content-tab-1-4">PREVIEW SURVEY</a>
								<?php
								}
								else
								{
								?>
									<a href="" style="opacity:0.5; cursor:default;">PREVIEW SURVEY</a>
								<?php
								}
								?>
                            </li>
                        </ul><!-- end .tabs-menu -->
                        <div class="tabs">
                            <div class="tab-content">
                            	<h3 align="center">
                                	<strong>Create your survey</strong> <br>
                                </h3>
								<div>
								<form method="post" action="select-question.php?search_id=1">
									<div style="float:left;margin-right:5px;"><label>Search:</label></div>
									<div style="float:left;margin-right:5px;"><input type="text" name="search" id="search"  /></div>
									<div><input type="submit" name="search_submit" value="Search" id="search_submit" class="btn"/></div>
								</form>
								</div>
								<div style="clear:both;">
									<input type="checkbox" name="queall" id="queall"  style="-webkit-appearance:checkbox;float:left;margin-top:6px;"><label>Select all</label>
								</div>
								<form class="fixed" id="question-form" name="question-form" method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>">
									<div class="accordion">
										<?php
										// while($row_selectquestion = mysql_fetch_array($res_selectquestion))
										$resSelectCquestion = mysql_query($selectCquestion);
										$i = 0;
										while($rowSelectCquestion = mysql_fetch_array($resSelectCquestion))
										{
											$i = $i + 1;
											// $questionnameid = "question".$rowSelectCquestion['question_no']."_name";
											// $questionid = "question".$rowSelectCquestion['question_no'];
											
											// $divid = 'question'.$rowSelectCquestion['question_no'].'div';
											// $contentdiv = 'quescontent'.$rowSelectCquestion['question_no'];
											
											$questionnameid = "question".$i."_name";
											$questionid = "question".$i;
											
											$divid = 'question'.$i.'div';
											$contentdiv = 'quescontent'.$i;
										?>
										<input type="checkbox" name="que[]" value="<?php echo $rowSelectCquestion['id']; ?>"  style="-webkit-appearance:checkbox;float:left;margin-top:30px;">
											<a class="accordion-item" href="#" id=<?php echo $contentdiv;?>>
												<?php echo $rowSelectCquestion['question_name']; ?>
												<p style="font-size:14px;width:705px;"><?php echo $rowSelectCquestion['question']; ?></p>
											</a>
											<div class="accordion-item-content" style="width: 823px;margin-left: 26px;">
												<a href="#<?php echo $divid;?>" class="btn" data-reveal-id="<?php echo $divid;?>" style="float:right;">Change question</a>
											</div>
											<input type="hidden" name="<?php echo $questionnameid;?>" id="<?php echo $questionnameid;?>" value="<?php echo $rowSelectCquestion['question_name'];?>"/>
											<input type="hidden" name="<?php echo $questionid;?>" id="<?php echo $questionid;?>" value="<?php echo $rowSelectCquestion['question'];?>"/>
											<div id="<?php echo $divid;?>" class="lightbox"  >
											<div style="width: 500px;margin: auto;padding: 20px;margin-top: 230px;background-color:white;">
												<h5>Change to your own question</h5>
												<input type="text" name="<?php echo $questionnameid.'new';?>" id="<?php echo $questionnameid.'new';?>" value="<?php echo $rowSelectCquestion['question_name'];?>"/>
												<textarea name="<?php echo $questionid.'new';?>" id="<?php echo $questionid.'new';?>"><?php echo $rowSelectCquestion['question'];?></textarea>
												<?php /*<input type="button" class="close-reveal-modal" name="change<?php echo $rowSelectCquestion['question_no'];?>" id="change<?php echo $rowSelectCquestion['question_no'];?>" value="Change"/> */?>

												<input type="button" class="close-reveal-modal btn" name="change<?php echo $i;?>" id="change<?php echo $i;?>" value="Change"/>
											</div>
											</div>
										<?php
										}
										?>
										
									</div>
									
									<!--<input  type="submit" class="btn" id="delete-que" name="delete-que" value="Delete" />-->
									<input id="question-submit" name="question-submit" type="submit" class="btn" value="Take me to the next step" />
								</form>
								
								<button id="myBtn" class="btn" style="float:right;margin-top:-53px;">Add more</button>
								<div id="myModal" class="modal">
								<div class="modal-content">
								<span class="close">&times;</span>
								<form method="post" action="select-question.php?addQue=1" name="addq" id="addq">		
												<h5>Add your own question</h5>
											
												<input type="text" name="heading_add" id="heading_add" placeholder="Enter title"/>
												<textarea name="question_add" id="question_add" placeholder="Enter question"></textarea>
												<input type="submit" class="close-reveal-modal btn" name="addquestion" id="addquestion" value="Add" onclick="document.getElementById('addq').submit();" />
								</form>		
								</div>
								
                            </div><!-- end .tab-content -->
                            
                            
						</div><!-- end .tabs -->  
                    
                    </div><!-- end .tab-container -->
                    
                </div><!-- end .span12 -->
            </div><!-- end .row -->
		<!-- //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->
		</div><!-- end #content -->
        <div id="footer-bottom">
        <!-- /// FOOTER-BOTTOM     ////////////////////////////////////////////////////////////////////////// -->	
			<div class="row">
				<div class="span6" id="footer-bottom-widget-area-1">
					<div class="widget widget_text">
                        <div class="textwidget">
                            <p class="last">Integrity &copy; 2014 All rights reserved</p>
                        </div><!-- edn .textwidget -->
                    </div><!-- end .widget_text -->
				</div><!-- end .span6 -->
				<div class="span6" id="footer-bottom-widget-area-2">
					<div class="widget ewf_widget_social_media"> 
                        <div class="fixed">
		                    <a href="#" class="googleplus-icon social-icon">
								<i class="fa fa-google-plus"></i>
							</a>
                            <a href="#" class="pinterest-icon social-icon">
								<i class="fa fa-pinterest"></i>
							</a>
                            <a href="#" class="facebook-icon social-icon">
								<i class="fa fa-facebook"></i>
							</a>
                            <a href="#" class="twitter-icon social-icon">
								<i class="fa fa-twitter"></i>
							</a>
                        </div>
                    </div><!-- end .ewf_widget_social_media -->
				</div><!-- end .span6 -->
			</div><!-- end .row -->
		<!-- ////////////////////////////////////////////////////////////////////////////////////////////////////////// -->    
		</div><!-- end #footer-bottom -->
	</div><!-- end #wrap -->
    
    <a id="back-to-top" href="#">
    	<i class="ifc-up4"></i>
    </a>
	<?php
	/*for($i=0;$i<4;$i++)
	{
	?>
		<div id="<?php echo 'question'.$i;?>" class="reveal-modal" style="width:525px;" data-reveal aria-labelledby="modalTitle" aria-hidden="true" role="dialog">
	
		</div>
	<?php
	}*/
	?>
	
	<?php /*<div class="modal" id="questionFormScreen">
		<div class="modal-dialog modal-sm">
		<div class="modal-content">
		<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				Oops..	
			</div>
		<div class="modal-body text-center">
			Your reward Points are less that product points.
		</div>
		</div>
		</div>
  </div>*/ ?>
  
    <!-- /// jQuery ////////  -->
	<script src="_layout/js/vendor/fastclick.js"></script>

	<!-- /// jQuery ////////  -->
	<script src="_layout/js/jquery-2.1.1.min.js"></script>
	
	<script src="_layout/js/foundation.min.js"></script>
    
	<!-- /// ViewPort ////////  -->
	<script src="_layout/js/viewport/jquery.viewport.js"></script>    
    <!-- /// Easing ////////  -->
	<script src="_layout/js/easing/jquery.easing.1.3.js"></script>
    <!-- /// SimplePlaceholder ////////  -->
	<script src="_layout/js/simpleplaceholder/jquery.simpleplaceholder.js"></script>
    <!-- /// Fitvids ////////  -->
    <script src="_layout/js/fitvids/jquery.fitvids.js"></script>
    <!-- /// Animations ////////  -->
    <script src="_layout/js/animations/animate.js"></script> 
    <!-- /// Superfish Menu ////////  -->
	<script src="_layout/js/superfish/hoverIntent.js"></script>
    <script src="_layout/js/superfish/superfish.js"></script>
    <!-- /// Revolution Slider ////////  -->
    <script src="_layout/js/revolutionslider/js/jquery.themepunch.tools.min.js"></script>
    <script src="_layout/js/revolutionslider/js/jquery.themepunch.revolution.min.js"></script> 
    <!-- /// bxSlider ////////  -->
	<script src="_layout/js/bxslider/jquery.bxslider.min.js"></script>
   	<!-- /// Magnific Popup ////////  -->
	<script src="_layout/js/magnificpopup/jquery.magnific-popup.min.js"></script>
    <!-- /// Isotope ////////  -->
	<script src="_layout/js/isotope/imagesloaded.pkgd.min.js"></script>
	<script src="_layout/js/isotope/isotope.pkgd.min.js"></script>
    <!-- /// Parallax ////////  -->
	<script src="_layout/js/parallax/jquery.parallax.min.js"></script>
	<!-- /// EasyPieChart ////////  -->
	<script src="_layout/js/easypiechart/jquery.easypiechart.min.js"></script>
	<!-- /// YTPlayer ////////  -->
	<script src="_layout/js/itplayer/jquery.mb.YTPlayer.js"></script>
	
    <!-- /// Easy Tabs ////////  -->
    <script src="_layout/js/easytabs/jquery.easytabs.min.js"></script>	
    
    <!-- /// Form validate ////////  -->
    <script src="_layout/js/jqueryvalidate/jquery.validate.min.js"></script>
    
	<!-- /// Form submit ////////  -->
    <script src="_layout/js/jqueryform/jquery.form.min.js"></script>
    
    <!-- /// Twitter ////////  -->
	<script src="_layout/js/twitter/twitterfetcher.js"></script>
	
	<!-- /// Custom JS ////////  -->
	<script src="_layout/js/plugins.js"></script>	
	<script src="_layout/js/scripts.js"></script>
	
	<script type="text/javascript">
		// Foundation.set_namespace = function() {};
		$(document).foundation();
		$('#queall').click(function(event) 
		{
		if(this.checked) 
		{
				// Iterate each checkbox
				$(':checkbox').each(function() {
				this.checked = true;
		});
		}
		else 
		{
			$(':checkbox').each(function() {
			this.checked = false;
		});
		}
		});
		
			
// Get the modal
var modal = document.getElementById('myModal');

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal 
btn.onclick = function() {
    modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
	</script>
    

</body>
</html>