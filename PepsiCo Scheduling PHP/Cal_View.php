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

      <div class = "row div_space "> 
        <div class = "col-md-1"></div>
        <div class = "col-md-10  cal_bg">
        <div id="items">
		<?php
		include('connect.php');
		$Emps="SELECT EmployeeID, FirstName, LastName FROM pepsi_database.employee";
		$Eresult=$link->query($Emps);
		
		while($row=$Eresult->fetch_assoc()){
echo'			<div class="item">
            <table class = "cal_tbl_bg">
              <tr>
		<th colspan="2">';
		echo $row["FirstName"]. " " . $row["LastName"];
		'<b></b></th>';
			$eid=$row["EmployeeID"];
			$WI="SELECT StartTime, workItem.ItemID, Description, LocationName
				FROM workitem, employee, wi_schedule, location
				where workitem.EmployeeID=employee.EmployeeID and workitem.ItemID=wi_schedule.ItemID
                and workitem.LocationID=location.LocationID
				and employee.EmployeeID=$eid;";

			$Wresult=$link->query($WI);
			while($Wrow=$Wresult->fetch_assoc()){
//Insert Into Table
echo'			<tr class = "row_height">
                <td class = "hour">8:00am</td>';
				$time = $Wrow["StartTime"];
				if($time == "08:00:00"){
					echo'<td>';
					echo "Item ID: ". $Wrow["ItemID"]. "<br>Location: ".$Wrow["LocationName"]. "<br> Description: ". $Wrow["Description"];
					echo'</td>';
				}
				else{
					echo'<td>
					</td>';
				}
echo'			<tr class = "row_height">
                <td class = "hour">8:30am</td>';
				$time = $Wrow["StartTime"];
				if($time == "08:30:00"){
					echo'<td>';
					echo "Item ID: ". $Wrow["ItemID"]. "<br>Location: ".$Wrow["LocationName"]. "<br> Description: ". $Wrow["Description"];
					echo'</td>';
				}
				else{
					echo'<td>
					</td>';
				}
echo'			<tr class = "row_height">
                <td class = "hour">9:00am</td>';
				$time = $Wrow["StartTime"];
				if($time == "09:00:00"){
					echo'<td>';
					echo "Item ID: ". $Wrow["ItemID"]. "<br>Location: ".$Wrow["LocationName"]. "<br> Description: ". $Wrow["Description"];
					echo'</td>';
				}
				else{
					echo'<td>
					</td>';
				}
echo'			<tr class = "row_height">
                <td class = "hour">9:30am</td>';
				$time = $Wrow["StartTime"];
				if($time == "09:30:00"){
					echo'<td>';
					echo "Item ID: ". $Wrow["ItemID"]. "<br>Location: ".$Wrow["LocationName"]. "<br> Description: ". $Wrow["Description"];
					echo'</td>';
				}
				else{
					echo'<td>
					</td>';
				}
echo'			<tr class = "row_height">
                <td class = "hour">10:00am</td>';
				$time = $Wrow["StartTime"];
				if($time == "10:00:00"){
					echo'<td>';
					echo "Item ID: ". $Wrow["ItemID"]. "<br>Location: ".$Wrow["LocationName"]. "<br> Description: ". $Wrow["Description"];
					echo'</td>';
				}
				else{
					echo'<td>
					</td>';
				}
echo'			<tr class = "row_height">
                <td class = "hour">10:30am</td>';
				$time = $Wrow["StartTime"];
				if($time == "10:30:00"){
					echo'<td>';
					echo "Item ID: ". $Wrow["ItemID"]. "<br>Location: ".$Wrow["LocationName"]. "<br> Description: ". $Wrow["Description"];
					echo'</td>';
				}
				else{
					echo'<td>
					</td>';
				}
echo'			<tr class = "row_height">
                <td class = "hour">11:00am</td>';
				$time = $Wrow["StartTime"];
				if($time == "11:00:00"){
					echo'<td>';
					echo "Item ID: ". $Wrow["ItemID"]. "<br>Location: ".$Wrow["LocationName"]. "<br> Description: ". $Wrow["Description"];
					echo'</td>';
				}
				else{
					echo'<td>
					</td>';
				}
echo'			<tr class = "row_height">
                <td class = "hour">11:30am</td>';
				$time = $Wrow["StartTime"];
				if($time == "11:30:00"){
					echo'<td>';
					echo "Item ID: ". $Wrow["ItemID"]. "<br>Location: ".$Wrow["LocationName"]. "<br> Description: ". $Wrow["Description"];
					echo'</td>';
				}
				else{
					echo'<td>
					</td>';
				}
echo'			<tr class = "row_height">
                <td class = "hour">12:00pm</td>';
				$time = $Wrow["StartTime"];
				if($time == "12:00:00"){
					echo'<td>';
					echo "Item ID: ". $Wrow["ItemID"]. "<br>Location: ".$Wrow["LocationName"]. "<br> Description: ". $Wrow["Description"];
					echo'</td>';
				}
				else{
					echo'<td>
					</td>';
				}
echo'			<tr class = "row_height">
                <td class = "hour">12:30pm</td>';
				$time = $Wrow["StartTime"];
				if($time == "12:30:00"){
					echo'<td>';
					echo "Item ID: ". $Wrow["ItemID"]. "<br>Location: ".$Wrow["LocationName"]. "<br> Description: ". $Wrow["Description"];
					echo'</td>';
				}
				else{
					echo'<td>
					</td>';
				}
echo'			<tr class = "row_height">
                <td class = "hour">1:00pm</td>';
				$time = $Wrow["StartTime"];
				if($time == "13:00:00"){
					echo'<td>';
					echo "Item ID: ". $Wrow["ItemID"]. "<br>Location: ".$Wrow["LocationName"]. "<br> Description: ". $Wrow["Description"];
					echo'</td>';
				}
				else{
					echo'<td>
					</td>';
				}
echo'			<tr class = "row_height">
                <td class = "hour">1:30pm</td>';
				$time = $Wrow["StartTime"];
				if($time == "13:30:00"){
					echo'<td>';
					echo "Item ID: ". $Wrow["ItemID"]. "<br>Location: ".$Wrow["LocationName"]. "<br> Description: ". $Wrow["Description"];
					echo'</td>';
				}
				else{
					echo'<td>
					</td>';
				}
echo'			<tr class = "row_height">
                <td class = "hour">2:00pm</td>';
				$time = $Wrow["StartTime"];
				if($time == "14:00:00"){
					echo'<td>';
					echo "Item ID: ". $Wrow["ItemID"]. "<br>Location: ".$Wrow["LocationName"]. "<br> Description: ". $Wrow["Description"];
					echo'</td>';
				}
				else{
					echo'<td>
					</td>';
				}
echo'			<tr class = "row_height">
                <td class = "hour">2:30pm</td>';
				$time = $Wrow["StartTime"];
				if($time == "14:30:00"){
					echo'<td>';
					echo "Item ID: ". $Wrow["ItemID"]. "<br>Location: ".$Wrow["LocationName"]. "<br> Description: ". $Wrow["Description"];
					echo'</td>';
				}
				else{
					echo'<td>
					</td>';
				}
echo'			<tr class = "row_height">
                <td class = "hour">3:00pm</td>';
				$time = $Wrow["StartTime"];
				if($time == "15:00:00"){
					echo'<td>';
					echo "Item ID: ". $Wrow["ItemID"]. "<br>Location: ".$Wrow["LocationName"]. "<br> Description: ". $Wrow["Description"];
					echo'</td>';
				}
				else{
					echo'<td>
					</td>';
				}
echo'			<tr class = "row_height">
                <td class = "hour">3:30pm</td>';
				$time = $Wrow["StartTime"];
				if($time == "15:30:00"){
					echo'<td>';
					echo "Item ID: ". $Wrow["ItemID"]. "<br>Location: ".$Wrow["LocationName"]. "<br> Description: ". $Wrow["Description"];
					echo'</td>';
				}
				else{
					echo'<td>
					</td>';
				}
echo'			<tr class = "row_height">
                <td class = "hour">4:00pm</td>';
				$time = $Wrow["StartTime"];
				if($time == "16:00:00"){
					echo'<td>';
					echo "Item ID: ". $Wrow["ItemID"]. "<br>Location: ".$Wrow["LocationName"]. "<br> Description: ". $Wrow["Description"];
					echo'</td>';
				}
				else{
					echo'<td>
					</td>';
				}
echo'			<tr class = "row_height">
                <td class = "hour">4:30pm</td>';
				$time = $Wrow["StartTime"];
				if($time == "16:30:00"){
					echo'<td>';
					echo "Item ID: ". $Wrow["ItemID"]. "<br>Location: ".$Wrow["LocationName"]. "<br> Description: ". $Wrow["Description"];
					echo'</td>';
				}
				else{
					echo'<td>
					</td>';
				}
echo'			<tr class = "row_height">
                <td class = "hour">5:00pm</td>';
				$time = $Wrow["StartTime"];
				if($time == "17:00:00"){
					echo'<td>';
					echo "Item ID: ". $Wrow["ItemID"]. "<br>Location: ".$Wrow["LocationName"]. "<br> Description: ". $Wrow["Description"];
					echo'</td>';
				}
				else{
					echo'<td>
					</td>';
				}
echo'			<tr class = "row_height">
                <td class = "hour">5:30pm</td>';
				$time = $Wrow["StartTime"];
				if($time == "17:30:00"){
					echo'<td>';
					echo "Item ID: ". $Wrow["ItemID"]. "<br>Location: ".$Wrow["LocationName"]. "<br> Description: ". $Wrow["Description"];
					echo'</td>';
				}
				else{
					echo'<td>
					</td>';
				}
echo'			<tr class = "row_height">
                <td class = "hour">6:00pm</td>';
				$time = $Wrow["StartTime"];
				if($time == "18:00:00"){
					echo'<td>';
					echo "Item ID: ". $Wrow["ItemID"]. "<br>Location: ".$Wrow["LocationName"]. "<br> Description: ". $Wrow["Description"];
					echo'</td>';
				}
				else{
					echo'<td>
					</td>';
				}
echo'			<tr class = "row_height">
                <td class = "hour">6:30pm</td>';
				$time = $Wrow["StartTime"];
				if($time == "18:30:00"){
					echo'<td>';
					echo "Item ID: ". $Wrow["ItemID"]. "<br>Location: ".$Wrow["LocationName"]. "<br> Description: ". $Wrow["Description"];
					echo'</td>';
				}
				else{
					echo'<td>
					</td>';
				}
echo'			<tr class = "row_height">
                <td class = "hour">7:00pm</td>';
				$time = $Wrow["StartTime"];
				if($time == "19:00:00"){
					echo'<td>';
					echo "Item ID: ". $Wrow["ItemID"]. "<br>Location: ".$Wrow["LocationName"]. "<br> Description: ". $Wrow["Description"];
					echo'</td>';
				}
				else{
					echo'<td>
					</td>';
				}
echo'			<tr class = "row_height">
                <td class = "hour">7:30pm</td>';
				$time = $Wrow["StartTime"];
				if($time == "19:30:00"){
					echo'<td>';
					echo "Item ID: ". $Wrow["ItemID"]. "<br>Location: ".$Wrow["LocationName"]. "<br> Description: ". $Wrow["Description"];
					echo'</td>';
				}
				else{
					echo'<td>
					</td>';
				}						

			}
		}

		mysqli_close($link);
		?>
 

        

            
        
            
    </body>
</html>
