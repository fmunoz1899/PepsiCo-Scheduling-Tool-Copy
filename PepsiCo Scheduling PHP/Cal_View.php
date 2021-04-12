<!DOCTYPE HTML>
<html> 
<head>
    <title>Pepsi Scheduling Tool</title>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
        <link rel='stylesheet' type='text/css' href='CSS/pepsi_styles.css'>
        <script language='Javascript' type='text/javascript' src='JavaScript/functionality.js'></script> 
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
            
            <!-- All names will be taken from the database -->
            <div class="row div_space"> 
                <div class="col-md-1"> </div>
                <div class="col-md-4"> 
                <label>Select employee to filter results:</label>
                <select name="employees" id="emps">
                    <option value="John Doe">John Doe</option>
                    <option value="John Smith">John Smith</option>
                    <option value="Robert Johnson">Robert Johnson</option>
                  </select>
                </div>
                <div class="col-md-3"></div>
                  <div class="col-md-3 divborder">
                      <form class = "select_form">
                          <input class = "choice" type="radio" name="choice" id = "LV"> List View 
                          <input class = "choice" type="radio" name="choice" id = "CV" checked> Calendar View
                      </form>
                  </div>
              </div>
          <div class="row "> 
            <div class="col-md-9"> </div>
              <div class="col-md-3">
                <button type="button" class="btn btn-primary mbut" data-toggle="modal" data-target="#newWO"> Create New Workorder</button>
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
            <div class="modal-body">
              <form >
                <p>Employee: 
                  <select name="employee" required>
                    <option value="" desabled selected>Select One</option>
                    <option>Main Employee</option>
                    <option>Second Employee</option>
                    <option>Third Employee</option>
                  </select>
                </p>
                <p>Date: 
                  <select name="date">
                    <option value="" desabled selected>Select Date</option>
                    <option>Day 1</option>
                    <option>Day 2</option>
                    <option>Day 3</option>
                  </select>
                </p>
                <p>Start Time: 
                  <select name="startTime">
                    <option value="" desabled selected>Select One</option>
                    <option>9:00</option>
                    <option>9:15</option>
                    <option>9:30</option>
                  </select>
                </p>
                <p>Job Length: 
                  <select name="jobLenght">
                    <option value="" desabled selected>Select One</option>
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
                <button type="submit" name="submit" class="btn btn-primary">Schedule</button> 
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              
            </div>
          </div>
        </div>
      </div>

      <!------------------------------------->

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
              <form action = "cal_view.php" method = "POST">
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
include('connect.php');
date_default_timezone_set("America/New_York");
echo"
      <div class = 'row div_space '> 
        <div class = 'col-md-1'></div>
        <div class = 'col-md-10  cal_bg'>
        <div id='items'>";
		
		$curday=substr(date("r"),0,3); //this gets me the day abbrivated for the blockout
		$empID="SELECT employeeID from employee";
		//SELECT firstName, lastName, employee.employeeID, DayID, StartTime, EndTime FROM employee, ahours WHERE employee.employeeID=ahours.employeeID and StartTime=EndTime and StartTime!='NULL' and employee.employeeID=ahours.employeeID and DayID='".$curday."'
		// used to show schedule of those working today	
				
		$result=mysqli_query($link,$empID);
		
		while($rowID=$result->fetch_assoc())
		{
			$count=0;
			$time=date('08:00:00'); //starting time of the day
			
			$sched="SELECT firstName, lastName, employee.employeeID, DayID, StartTime, EndTime
					FROM employee, ahours
					WHERE employee.employeeID=".$rowID['employeeID']." and employee.employeeID=ahours.employeeID and DayID='".$curday."'";
					
			$full=mysqli_query($link,$sched);
			$rowsch=$full->fetch_assoc();
echo"
          <div class='item'>
            <table class = 'cal_tbl_bg'>
              <tr>
                <th><button type='button' data-toggle='modal' data-target='#newBO'>Blockout</button></th>
                <th colspan='2'><b>".$rowsch['firstName']." ".$rowsch['lastName']."</b></th>";
				while($time!='22:30:00') //time day ends plus the time increment
				{
 echo"	            <tr class = 'row_height'>";
					echo"<td class = 'hour'>".date('g:ia',strtotime($time))."</td> 
					<td>	</td>
					</tr>";
					$time = date('H:i:s',strtotime('+30 minutes',strtotime($time))); //to increment the schdule by 30 minutes
				}
 echo"            
            </table>
			</div>
		";}
 ?>

            </table>
        </div>
       </div>
	  </div>
        

            
        
            
    </body>
</html>
