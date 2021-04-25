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
        <nav class="navbar navbar-expand-sm fixed-top nav">
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
                <h1 class="h1_1">Displaying all Scheduled Workorders</h1>
            </div>
            
 <?php 
	session_start();
	
	if(!isset($_SESSION['manager']) || $_SESSION===false)
	{
		header("location:login.php?list_view_no_man_tok");
		exit;
	}
?>

            <!-- All names will be taken from the database -->
            <div class='row div_space'> 
                <div class='col-md-1'> </div>
                <div class='col-md-4'> 
                    <form class = "form2" method="POST" name="cal_form" id="cal_form" action="filterlist_view.php">
                    <label>Search for an Employee:</label>
                    <input type="text" name="filterfirst" placeholder="First Name"> <!-- might need to change so it can filter by first and last name separately-->
                    <input type="text" name="filterlast" placeholder="Last Name"><br>
                    <label>Select Date:</label> 
                      <input name="datepicker" type="text" id="datepicker" readonly='true' placeholder="Click Here to Select Date">
                      <button type="submit" name="submit" class="btn btn-primary">Filter</button>
                  </form > 
				  <button type="submit" name="clear" id="clear" class="btn btn-primary">Clear All</button>
				  <br>If no date selected with first and/or last name, date will default today
                </div>
	

   

                <div class="col-md-3"></div>
                  <div class="col-md-3 divborder">
                      <form class = "select_form">
                          <input class = "choice" type="radio" name="choice" id = "LV" checked> Work Order List View</input><br>
						   <input class = "choice" type="radio" name="choice" id = "BV"> Blockout List View</input></br>
                          <input class = "choice" type="radio" name="choice" id = "CV"> Calendar View</input>
                      </form>
                  </div>
              </div>
          </div>
          <div class="row "> 
            <div class="col-md-9"> </div>
              <div class="col-md-3">
                <button type="button" class="btn btn-primary mbut" data-toggle="modal" data-target="#newWO"> Create New Workorder</button>
              </div>
          </div>
      </div>
</div>
      <!--------------- Modal Code -------------->
	   <div class="modal fade" id="newWO" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Schedule New Workorder</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body modalContent">
              <form action = "List_View.html" method = "POST" >
                <p>Employee: 
                  <select name="employee" required>
                    <option value="" disabled selected>Select One</option>
                    <option value="m">Main Employee</option>
                    <option value="s">Second Employee</option>
                    <option value="t">Third Employee</option>
                  </select>
                </p>
                <p>Date: 
                  <select name="date">
                    <option value="" disabled selected>Select Date</option>
                    <option>Day 1</option>
                    <option>Day 2</option>
                    <option>Day 3</option>
                  </select>
                </p>
                <p>Start Time: 
                  <select name="startTime">
                    <option value="" disabled selected>Select One</option>
                    <option>9:00</option>
                    <option>9:15</option>
                    <option>9:30</option>
                  </select>
                </p>
                <p>Job Length: 
                  <select name="jobLenght">
                    <option value="" disabled selected>Select One</option>
                    <option>15 minutes</option>
                    <option>30 minutes</option>
                    <option>45 minutes</option>
                  </select>
                </p>
                <p>Location: 
                  <select name="SelectLocation">
                    <option value="" desabled selected>Select One</option>
                    <option>Location 1</option>
                    <option>Location 2</option>
                    <option>Location 3</option>
                  </select>
                </p>
                <p>Delivery method: 
                  <select name="SelectDelivery">
                    <option value="" desabled selected>Select One</option>
                    <option>Delivery 1</option>
                    <option>Delivery 2</option>
                    <option>Delivery 3</option>
                  </select>
                </p>
                <p>Description:
                  <input type="text" name="Description">
                </p>
                <p>Main Employee: 
                  <select name="EmpMain">
                    <option value="" desabled selected>Select One</option>
                    <option>Employee 1</option>
                    <option>Employee 2</option>
                    <option>Employee 3</option>
                  </select>
                </p>
                <p>Second Employee: 
                  <select name="EmpSec">
                    <option value="" desabled selected>Select One</option>
                    <option>Employee 1</option>
                    <option>Employee 2</option>
                    <option>Employee 3</option>
                  </select>
                </p>
                <p>Third Employee: 
                  <select name="EmpThird">
                    <option value="" desabled selected>Select One</option>
                    <option>Employee 1</option>
                    <option>Employee 2</option>
                    <option>Employee 3</option>
                  </select>
                  
                <button type="submit" name="submit" class="btn btn-primary subBut">Schedule</button> 
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
	include('connect.php');
	date_default_timezone_set("America/New_York");
	$curdate=date("Y-m-d");
	
	$sqlWI="SELECT firstName, lastName, workitem.ItemID, LocationName, Method, ActualEndTime, StartTime, EndTime, Date
			FROM workitem,employee,delivery,wi_schedule,location,employeeprivlege
			where workitem.EmployeeID=employee.EmployeeID 
			and workitem.ItemID=wi_schedule.ItemID 
			and workitem.LocationID=location.LocationID 
			and workitem.DeliveryID=delivery.DeliveryID
            and employee.EmployeeID=employeeprivlege.EmployeeID
            and PrivilegeID='E'
            and Date='".$curdate."' 
			ORDER BY Date, StartTime, EndTime";
	$result=$link->query($sqlWI);
	echo'<div class="row tbl_space">';

    echo "<div class='col-md-1'> </div>";
        echo "<div class = 'col-md-10'>";
          echo " <table> ";
          echo "<tr>
					<th> Employee Name </th>
					<th> Workorder ID </th>
					<th> Location </th>
					<th> Delivery Method </th>
					<th> Actual End Time </th>
					<th> Start Time </th>
					<th> End Time </th>
					<th> Date </th>
				  </tr>";
			while($row = $result->fetch_assoc()) {
				echo "<tr>
					<td>".$row["firstName"]. " " . $row["lastName"] . "</td>
					<td>". $row["ItemID"]. "</td>
					<td>" . $row["LocationName"]."</td>
					<td>" . $row["Method"]."</td>
					<td>" . date('g:ia',strtotime($row["ActualEndTime"]))."</td>
					<td>" . date('g:ia',strtotime($row["StartTime"]))."</td>
					<td>" . date('g:ia',strtotime($row["EndTime"]))."</td>
					<td>" . date('n/j/Y',strtotime($row["Date"]))."</td>
				</tr>";
			}
	echo'</div>';
	mysqli_close($link);
?>
      <!------------------------------------->


            
    </body>
</html>
