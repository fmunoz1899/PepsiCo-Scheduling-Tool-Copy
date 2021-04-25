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

    <body>
        <nav class="navbar navbar-expand-sm ">
          <ul class="navbar-nav">	
            <li class="nav-item active"><a class="nav-link" href="List_View.php">Schedule</a></li>
            <li class="nav-item active"><a class="nav-link a2" href="employees.php">Employees</a></li>
            <li class="nav-item active"><a class="nav-link a2" href="locations.php">Locations</a></li>
			<li class="nav-item active"><a class="nav-link a2" href="login.php">Log Out</a></li>
      </nav>


            <div class="jumbotron text-center jumbotron2">
                <h1 class="font-weight-bold text-center">Manager Workorder List View</h1>
                <img class = "img1"  src = "pepsi.png"> 
                <hr class = "hr1">
            </div>

            <div>
                <h1 class="h1_1">Displaying all Scheduled Blockout Times</h1>
            </div>
            
 <?php 
	session_start();
	
	if(!isset($_SESSION['manager']) || $_SESSION['manager']===false)
	{
		header("location:login.php?list_view_no_man_tok");
		exit;
	}
?>


            <div class='row div_space'> 
                <div class='col-md-1'> </div>
                <div class='col-md-4'> 
                    <form class = "form2" method="POST" name="cal_form" id="cal_form" action="filterblock_list.php">
                    <label>Search for an Employee:</label>
                    <input type="text" name="filterfirst" placeholder="First Name"> <!-- might need to change so it can filter by first and last name separately-->
                    <input type="text" name="filterlast" placeholder="Last Name"><br>
                    <label>Select Date:</label> 
                      <input name="datepicker" type="text" id="datepicker" readonly='true' placeholder="Click Here to Select Date">
                      <button type="submit" name="submit" class="btn btn-primary">Filter</button>
                  </form > 
				  <button type="submit" name="clear" id="clear" class="btn btn-primary">Clear All</button>
				  <br>If no date selected with first and/or last name, date will default today

                    </select>
                </div>


                <div class="col-md-3"></div>
                  <div class="col-md-3 divborder">
                      <form class = "select_form">
                          <input class = "choice" type="radio" name="choice" id = "LV"> Work Order List View</input><br>
						   <input class = "choice" type="radio" name="choice" id = "BV" checked> Blockout List View</input></br>
                          <input class = "choice" type="radio" name="choice" id = "CV"> Calendar View</input>
                      </form>
                  </div>
              </div>
          </div>
          <div class="row "> 
            <div class="col-md-9"> </div>
              <div class="col-md-3">
                <button type="button" class="btn btn-primary mbut" data-toggle="modal" data-target="#newBO"> Create Block Out</button>
              </div>
          </div>
      </div>
</div>
      <!--------------- Modal Code -------------->
	<div class="modal fade" id="newBO" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Schedule New Blockout</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form action = "blocklist_view.php" method = "POST">
              <div class="form-group">
                  <label>Start Time</label>
                  <input type="Time" class="form-control" name="sTime" required>
              </div>
                       
              <div class="form-group">
                <label>End Time</label>
                <input type="Time" class="form-control" name="eTime" required>
            </div>  
            <div class="form-group">
                <label>Reason</label>
                <input type="text" name="Description">
            </div>
			<div class="form-group">
                <label>All</label>
                <input type="checkbox" name="forall">
            </div>
			<div class="form-group">
                <label>Who</label>
                <select name='who' id='who'>
<?php
				

?>
				</select>
            </div>
			<div class="form-group">
                <label>Date</label>
                <input name="datepicker" type="text" id="datepicker" readonly='true' placeholder="Click Here to Select Date">
            </div>
                <button type="submit" name="submit" class="btn btn-primary">Blockout</button> 
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              
            </div>
          </div>
        </div>
      </div>
	  

      <!------------------------------------->
<?php
//Make sure to change this so that it only shows the engineers
	include('connect.php');
	date_default_timezone_set("America/New_York");
	$curdate=date("Y-m-d");
	
	
	$sqlWI="SELECT firstName, lastName, startTime, endTime, bdate 
			FROM blackout, employee 
			WHERE bdate='".$curdate."' 
			and blackout.EmployeeID=employee.EmployeeID 
			ORDER BY bdate, starttime, endtime";
			
	$result=$link->query($sqlWI);
	echo'<div class="row tbl_space">';

    echo "<div class='col-md-1'> </div>";
        echo "<div class = 'col-md-10'>";
          echo " <table> ";
          echo "<tr>
					<th> Employee Name </th>
					<th> Start Time </th>
					<th> End Time </th>
					<th> Date </th>
				  </tr>";
			while($row = $result->fetch_assoc()) {
				echo "<tr>
					<td>".$row["firstName"]. " " . $row["lastName"] . "</td>
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