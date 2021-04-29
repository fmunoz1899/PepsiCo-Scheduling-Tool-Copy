<!DOCTYPE HTML>
<html> 
<head>
    <title>Pepsi Scheduling Tool</title>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
       <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <link href='https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/ui-lightness/jquery-ui.css'rel='stylesheet'>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.11/jquery-ui.min.js"></script>
        <script type='text/javascript' src='/js/jquery.mousewheel.min.js'></script>
        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <link rel='stylesheet' type='text/css' href='CSS/pepsi_styles.css'>
        <script language='Javascript' type='text/javascript' src="Javascript/functionality.js"></script> 
        <script>
                $(document).ready(function(){
                  $('[data-toggle="tooltip"]').tooltip();   
                });               
        </script>
    </head>
	
<?php
	include('connect.php');
	session_start();
	
	if(!isset($_SESSION['emp']) || $_SESSION['emp']===false)
	{
		header("location:login.php?emp_no_tok");
		exit;
	}
	
	if(!isset($_POST['datepicker2']))
	{
		header("location:empblocklist_view.php?emp_block_no_filter");
		exit;
	}
?>

    <body>
        <nav class="navbar navbar-expand-sm ">
          <ul class="navbar-nav">	
            <li class="nav-item active"><a class="nav-link" href="empList_View.php">Schedule</a></li>
			<li class="nav-item active"><a class="nav-link" href="emploc_View.php">Locations</a></li>
			<li class="nav-item active"><a class="nav-link a2" href="login.php">Log Out</a></li>
      </nav>


            <div class="jumbotron text-center jumbotron2">
                <h1 class="font-weight-bold text-center">Engineer Blockout List View</h1>
<?php 
	include('connect.php');
		$name="SELECT firstname, lastname
			   FROM employee
			   WHERE employeeID=".$_SESSION['username'];
		$name=$link->query($name);
			   $row = $name->fetch_assoc();
		echo" <h1 class='font-weight-bold text-center'>Welcome Back ".$row['firstname']." ".$row['lastname']."</h1>";
 ?>
                <img class = "img1"  src = "pepsi.png"> 
                <hr class = "hr1">
            </div>
<?php
	include('connect.php');
	date_default_timezone_set("America/New_York");
	
$curdate=date("Y-m-d",strtotime($_POST['datepicker2']));

	$sqlWI="SELECT startTime, endTime, bdate, reason
			FROM blackout 
			WHERE blackout.EmployeeID=".$_SESSION['username']."
			and bdate='".$curdate."' 			
			ORDER BY bdate, starttime, endtime";
	$result=$link->query($sqlWI);
	
echo"
            <div>
                <h1 class='h1_1'>Filtered for ".date('n/j/Y',strtotime($curdate))."</h1>".
					mysqli_num_rows($result)." result(s)
            </div>
           
            <div class='row div_space'> 
                <div class='col-md-1'> </div>
                <div class='col-md-4'>
                </div>
 

                <div class='col-md-3'></div>
                  <div class='col-md-3 divborder'>
                      <a href='empblocklist_view.php'><button class='btn btn-primary'>Go Back</button></a>
                  </div>
              </div>
          </div>
          <div class='row '> 
            <div class='col-md-9'> </div>
              <div class='col-md-3'>
              </div>
          </div>
      </div>
</div>";


//Dont know how you want this, if you only want work items that are fully scheduled or not
	
	//username is cleaned before set as a session variable
	
	echo'<div class="row tbl_space">';

    echo "<div class='col-md-1'> </div>";
        echo "<div class = 'col-md-10'>";
          echo " <table> ";
          echo "<tr>
					<th> Reason </th>
					<th> Start Time </th>
					<th> End Time </th>
					<th> Date </th>
				  </tr>";
			while($row = $result->fetch_assoc()) {
				echo "<tr>
					<td>" . $row['reason'] . " </td>
					<td>" . date('g:ia',strtotime($row["startTime"]))."</td>
					<td>" . date('g:ia',strtotime($row["endTime"]))."</td>
					<td>" . date('n/j/Y',strtotime($row["bdate"]))."</td>
				</tr>";
			}
	echo'</div>';
	mysqli_close($link);
?>
      <!------------------------------------->


            
    </body>
</html>