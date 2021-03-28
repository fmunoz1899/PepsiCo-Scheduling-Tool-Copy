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
            <li class="nav-item active"><a class="nav-link" href="List_View.html">Schedule</a></li>
            <li class="nav-item active"><a class="nav-link a2" href="employees.html">Employees</a></li>
            <li class="nav-item active"><a class="nav-link a2" href="locations.html">Locations</a></li>

      </nav>


            <div class="jumbotron text-center jumbotron2">
                <h1 class="font-weight-bold text-center">Workorder List View</h1>
                <img class = "img1"  src = "pepsi.png"> 
                <hr class = "hr1">
            </div>

            <div>
                <h1 class="h1_1">Displaying all Scheduled Workorders</h1>
            </div>
            
 <?php 
	include('connect.php');
	$names="SELECT firstName, lastName FROM employee";
	$result=$link->query($names);
echo" 
            <!-- All names will be taken from the database -->
            <div class='row div_space'> 
                <div class='col-md-1'> </div>
                <div class='col-md-4'> 
                    <label>Select employee to filter results:</label>
                    <select name='employees' id='emps'>
						<option>Select One</option>";
                        while($row=$result->fetch_assoc()) 
							echo"<option value=" . $row["firstName"] . " " . $row["lastName"] . ">" . $row["firstName"] . " " . $row["lastName"] . "</option>";
echo"
                    </select>
                </div>
	";
	mysqli_close($link);
 ?>  
                <div class="col-md-3"></div>
                  <div class="col-md-3 divborder">
                      <form class = "select_form">
                          <input class = "choice" type="radio" name="choice" id = "LV" checked> List View 
                          <input class = "choice" type="radio" name="choice" id = "CV"> Calendar View
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

      <!--------------- Modal Code -------------->
	  
<?php
//Dont know how you want this, if you only want work items that are fully scheduled or not
	include('connect.php');
	$sqlWI="SELECT firstName, lastName, workitem.ItemID, LocationName, Method, TotalWorkTime, StartTime, EndTime, EndDate
			FROM workitem,employee,delivery,wi_schedule,location
			where workitem.EmployeeID=employee.EmployeeID and workitem.ItemID=wi_schedule.ItemID and
			workitem.LocationID=location.LocationID and workitem.DeliveryID=delivery.DeliveryID;";
	$result=$link->query($sqlWI);
	echo'<div class="table">';
	echo "<h2> Displaying all Scheduled Workorders </h2>";
			echo " <table border='1'> ";
			echo "<tr>
					<th> Employee Name </th>
					<th> Workorder ID </th>
					<th> Location </th>
					<th> Delivery Method </th>
					<th> Scheduled Hours </th>
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
					<td>" . $row["TotalWorkTime"]."</td>
					<td>" . $row["StartTime"]."</td>
					<td>" . $row["EndTime"]."</td>
					<td>" . $row["EndDate"]."</td>
				</tr>";
			}
	echo'</div>';
	mysqli_close($link);
?>
      <!------------------------------------->


            
    </body>
</html>