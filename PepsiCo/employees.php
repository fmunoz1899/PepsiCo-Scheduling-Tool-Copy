<!DOCTYPE HTML>
<html> 
<head>
    <title>Pepsi Scheduling Tool</title>
<!--needs a filter, simliar to employeeinfo filter -->
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
                <h1 class="font-weight-bold text-center">Manager Employees</h1>
                <img class = "img1"  src = "pepsi.png"> 
                <hr class = "hr1">
            </div>

            <div>
                <h1 class="h1_1">Displaying all Employees</h1>
            </div>
			<form method='POST' action='employees.php'>
			
				 <label> Filter by reporting manager:</label>
					 <select name='filter'>
					 <?php
					 
					 
					 session_start();
					 include('connect.php');
					 
					 if(!isset($_SESSION['manager']) || $_SESSION['manager']===false)
					 {
						 header("location:login.php?manager_no_mantok");
						 exit;
					 }

						$mans="SELECT firstname, lastname, employee.employeeid
							   FROM employee, employeeprivlege
							   WHERE employee.employeeID=employeeprivlege.employeeID
							   AND privilegeid='M'";
					   $result=$link->query($mans);
					 
						if(isset($_POST['filter']))
						{
							echo"<option value='%'>N/A</option>";
							$_SESSION['username']=$_POST['filter'];
						}
						
						else
							echo"<option value='%' selected>N/A</option>";
						
						while($row=$result->fetch_assoc())
						{			
							
								if($row['employeeid']==$_SESSION['username'])
									echo"<option value='".$row['employeeid']."'selected>".$row['firstname']." ".$row['lastname']."</option>";
								else
									echo"<option value='".$row['employeeid']."'>".$row['firstname']." ".$row['lastname']."</option>";
				
															
						}
					 ?>
					 
					 </select>&nbsp;
					  <button class = "btn btn-primary" type="submit" name="submit">Filter</button> 
					  </form>
            
            <!-- All names will be taken from the database -->
            <div class="row div_space"> 
                <div class="col-md-1"> </div>
                <div class="col-md-4"> 
                <form method="POST" action="Filteremp.php" class = "form2">
						<label>Search for an Employee:</label>
						<input type="text" name="filterfirst" placeholder="First Name"> 
						<br>
						<input type="text" name="filterlast" placeholder="Last Name"> 
						<button class="btn btn-primary" type="submit">Filter</button>
					</form>
                </div>				
                <div class = "col-md-3"></div>
				<div class = 'col-md-4'>
				<form action='GenPDFEMP.php' target='_blank' method='POST'><input type='hidden' name='pdf' value=18><button class = 'btn btn-primary'>Generate PDF </button></form>
				</div>

                
          </div>
         
      </div>

<?php
include('connect.php');


$time= $_SERVER['REQUEST_TIME'];
		$timeout=300;
	
			if(isset($_SESSION['last'])===true && ($time-$_SESSION['last'])>$timeout)
			{
				header("location:login.php?st=".rand());
				exit;
			}
			
		$_SESSION['last']=$time;
		
	
	
		/*if(isset($_GET['st']))
			echo"<script>alert('Report enerated with name ".$_GET['st']." in C:/Downloads.');</script>";
		else if(isset($_GET['no']))
			echo"<script>alert('There are no records to record');</script>";*/
	echo"
			<div class='row tbl_space'> 
				<div class='col-md-1'> </div>
				<div class = 'col-md-10'>
				<table>
						<tr>
						  <th>First Name</th>
						  <th>Last Name</th>
						  <th>Primary Email	</th>
						  <th>Primary Phone Number</th>
						  <th>Sunday</th>
						  <th>Monday</th>
						  <th>Tuesday</th>
						  <th>Wednesday</th>
						  <th>Thursday</th>
						  <th>Friday</th>
						  <th>Saturday</th>
						</tr>
						<tr>"; 
						
						$peopleinfo="SELECT firstName, lastName, email, phoneNumber, PrivilegeID, employee.employeeID
						FROM employee, employeeprivlege, email, phone, team 
						where employee.EmployeeID=email.EmployeeID 
						and email.Type='Work' 
						and employee.EmployeeID=phone.EmployeeID 
						and phone.Type='Work' 
						and managerid LIKE '".$_SESSION['username']."'
						and team.employeeid=Employee.EmployeeID
						and employee.EmployeeID=employeeprivlege.EmployeeID
						and PrivilegeID='E'";
						
						$result=mysqli_query($link,$peopleinfo);
						
						while($row=$result->fetch_assoc())
						{
							$schedule="SELECT DayID, TIME_FORMAT(StartTime, '%l:%i%p') AS StartTime, TIME_FORMAT(EndTime, '%l:%i%p') AS EndTime, employeeID 
							FROM ahours WHERE EmployeeID=".$row['employeeID']." order by case 
							when DayID='Sun' then 1 
							when DayID='Mon' then 2 
							when DayID='Tue' then 3 
							when DayID='Wed' then 4 
							when DayID='Thr' then 5 
							when DayID='Fri' then 6 
							when DayID='Sat' then 7 
							else 8 end asc";
							$quick=mysqli_query($link,$schedule);
							
	echo"					
							<td>" . $row['firstName'] . "</td>
							<td>" . $row['lastName'] . "</td>
							<td>" . $row['email'] . "</td>
							<td>(" . substr($row['phoneNumber'],0,3) . ")-".substr($row['phoneNumber'],3,3)."-".substr($row['phoneNumber'],6,4) . "</td>";
							while($fullschd=$quick->fetch_assoc())
							{
								if($fullschd['DayID']=='Sun')
									echo"<td>".$fullschd['StartTime']." - ".$fullschd['EndTime']."</td>";
								if($fullschd['DayID']=='Mon')
									echo"<td>".$fullschd['StartTime']." - ".$fullschd['EndTime']."</td>";
								if($fullschd['DayID']=='Tue')
									echo"<td>".$fullschd['StartTime']." - ".$fullschd['EndTime']."</td>";
								if($fullschd['DayID']=='Wed')
									echo"<td>".$fullschd['StartTime']." - ".$fullschd['EndTime']."</td>";
								if($fullschd['DayID']=='Thu')
									echo"<td>".$fullschd['StartTime']." - ".$fullschd['EndTime']."</td>";
								if($fullschd['DayID']=='Fri')
									echo"<td>".$fullschd['StartTime']." - ".$fullschd['EndTime']."</td>";
								if($fullschd['DayID']=='Sat')
									echo"<td>".$fullschd['StartTime']." - ".$fullschd['EndTime']."</td>";
							}
	echo"
						  </tr>
						  ";
						}
		$link->close();
	
	
	
		
					?>                          
                  </table>
            </div>
            <div class="col-md-1"> </div>
        </div>
        
            
    </body>
</html>