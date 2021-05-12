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
	session_start();
	
	$time= $_SERVER['REQUEST_TIME'];
		$timeout=300;
	
			if(isset($_SESSION['last'])===true && ($time-$_SESSION['last'])>$timeout)
			{
				header("location:login.php?st=".rand());
				exit;
			}
			
		$_SESSION['last']=$time;
	if(!isset($_SESSION['username']) || !isset($_SESSION['emp']) || $_SESSION['emp']===false)
	{
		header("location:login.php?emp_no_tok");
		exit;
	}
	
?>

    <body>
                <nav class="navbar navbar-expand-sm ">
          <ul class="navbar-nav">	
            <li class="nav-item active"><button type="button" class="btn btn-outline-primary navButtons" onclick ="window.location.href = 'empList_View.php';">Schedule</button></li>
			<li class="nav-item active"><button type="button" class="btn btn-outline-primary navButtons" onclick ="window.location.href = 'emploc_View.php';">Locations</button></li>
			</ul>
			<div class="navbar-nav ml-auto">
				<li class="nav-item active"><button type="button" class="btn btn-outline-danger" onclick ="window.location.href = 'login.php';" >Log Out</button></li>
			</div>
			
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

            <div>
                <h1 class="h1_1">Displaying all Scheduled Block Out Times</h1>
                <h3 class="h1_1">Within a week</h3>
				
            </div>
           
            <div class='row div_space'> 
                <div class='col-md-1'> </div>
                <div class='col-md-4'> 
				<form class = "form2" method="POST" name="cal_form" id="cal_form" action="filteremp_block.php">
                    <label>Select Date:</label> 
                      <input name="datepicker2" type="text" id="datepicker2" readonly='true' placeholder="Click Here to Select Date">
                      <button type="submit" name="submit" class="btn btn-primary">Filter</button>
                  </form > 
                </div>
 

                <div class="col-md-3"></div>
                  <div class="col-md-3 divborder">
                      <form class = "select_form">
                          <input class = "choice" type="radio" name="choice" id = "ELV"> Work Order List View <br>
						  <input class = "choice" type="radio" name="choice" id = "BCV" checked> Block Out List View <br>
                          <input class = "choice" type="radio" name="choice" id = "ECV"> Calendar View <br>
                      </form>
                  </div>
              </div>
          </div>
          <div class="row "> 
            <div class="col-md-9"> </div>
              <div class="col-md-3">
              </div>
          </div>
      </div>
</div>

<?php
//Dont know how you want this, if you only want work items that are fully scheduled or not
	include('connect.php');
	date_default_timezone_set("America/New_York");
	
	$curtime=date("H:i:s");
	$curdate=date("Y-m-d");
	$future=date("Y-m-d", strtotime($curdate.'+7days'));
	//username is cleaned before set as a session variable
	$sqlWI="SELECT startTime, endTime, bdate, reason, blackoutid
			FROM blackout 
			WHERE blackout.EmployeeID=".$_SESSION['username']."
			and bdate>='".$curdate."' 
			and bdate<='".$future."'			
			ORDER BY bdate, starttime, endtime";
	$result=mysqli_query($link,$sqlWI);
	
	$name="SELECT firstname, lastname
		   FROM employee
		   WHERE employeeid=".$_SESSION['username'];
	$res2=mysqli_query($link,$name);
	$rown=mysqli_fetch_array($res2);
	
	echo'<div class="row tbl_space">';

    echo "<div class='col-md-1'> </div>";
        echo "<div class = 'col-md-10'>";
          echo " <table> ";
          echo "<tr>
					<th> Status </th>
					<th> Reason </th>
					<th> Start Time </th>
					<th> End Time </th>
					<th> Date </th>
				  </tr>";
			while($row = $result->fetch_assoc()) 
			{
				$BID =  $row['blackoutid'];
				echo "<tr>";
				
				if(($curtime>=$row['endTime'] && $curdate==$row['bdate']) || ($curdate>$row['bdate']))
					echo"<td><button class='btn btn-primary BOCalDet' type='button' data-toggle='modal' value='".$row['blackoutid']."'>PASSED</button>";
				else
				{
					echo"<td class = 'bo1'><button class='btn btn-success EditBOLV' type='button' data-toggle='modal' value='" . $row['blackoutid'] . "'>Upcoming</button></td>";
					
				}
					echo"<td style = 'display:none' id = ".$BID."name>".$rown[0]. " " . $rown[1] . "</td>
					<td class = 'boReason' id = ".$BID."reason>" . $row['reason'] . " </td>
					<td id = ".$BID."sTime>" . date('g:ia',strtotime($row["startTime"]))."</td>
					<td id = ".$BID."eTime>" . date('g:ia',strtotime($row["endTime"]))."</td>
					<td id = ".$BID."date>" . date('n/j/Y',strtotime($row["bdate"]))."</td>
				</tr>";
			}
	echo'</div>';
	mysqli_close($link);
?>
      <!------------------------------------->
	   <!--------------- Modal Code to View Details Blockout Time-------------->
	<div class="modal fade" id="DetBO" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">View Blockout Details</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
			
            <div class="modal-body">
			<div class = "row">
			
			<div class = "col-md-9">
              <form>
              <div class="form-group">
                  <label>Start Time:</label><br>
				  <input type = "text"  id='startTimeDetBO' readonly='true'>
              </div>
                       
              <div class="form-group">
                <label>End Time:</label><br>
				<input type = "text"  id='endTimeDetBO' readonly='true'>
            </div>  
			<div class="form-group">
                <label>Date:</label><br>
				<input type = "text"  id='datepicker6DetBO' readonly='true'>
            </div>
			
			<div class="form-group">
                <label>Reason:</label><br>
				<input type = "text"  id='reasonEditDetBO' readonly='true'>
            </div>
              </form>
            </div>
			</div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              
			</div>

          </div>
        </div>
      </div>
	  </div>

      <!------------------------------------->
	  <div class="modal fade" id="EditBO" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Edit Blockout</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
			
            <div class="modal-body">
			<div class = "row">
				<div class = "col-md-3"></div>
				<div class = "col-md-9">
				<form>
              <div class="form-group">
                  <label>Start Time:</label><br>
				  <input type = "text"  id='startTime' readonly='true'>
              </div>
                       
              <div class="form-group">
                <label>End Time:</label><br>
				<input type = "text"  id='endTime' readonly='true'>
            </div>  
			<div class="form-group">
                <label>Date:</label><br>
				<input type = "text"  id='datepicker6EmpLV' readonly='true'>
            </div>
			
			<div class="form-group">
                <label>Reason:</label><br>
				<input type = "text"  id='reasonEdit' readonly='true'>
            </div>
              </form>
            
		
					<div class="modal-footer">
					  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					</div>  
				</div>
			</div>
          </div>
        </div>
		</div>
      </div>
	  

      <!------------------------------------->


            
    </body>
</html>