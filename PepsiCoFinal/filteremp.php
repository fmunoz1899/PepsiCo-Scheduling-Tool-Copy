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
      <nav class="navbar navbar-expand-sm ">
          <ul class="navbar-nav">	
            <li class="nav-item active"><button type="button" class="btn btn-outline-primary navButtons" onclick ="window.location.href = 'List_View.php';">Schedule</button></li>
			<li class="nav-item active"><button type="button" class="btn btn-outline-primary navButtons" onclick ="window.location.href = 'employees.php';">Employees</button></li>
			<li class="nav-item active"><button type="button" class="btn btn-outline-primary navButtons" onclick ="window.location.href = 'locations.php';">Locations</button></li>
			<li class="nav-item active"><button type="button" class="btn btn-outline-primary navButtons" onclick ="window.location.href = 'transfer.php';">Transfer</button></li>

			</ul>
			<div class="navbar-nav ml-auto">
				<li class="nav-item active"><button type="button" class="btn btn-outline-danger" onclick ="window.location.href = 'login.php';" >Log Out</button></li>
			</div>
	</nav>

            <div class="jumbotron text-center jumbotron2">
                <h1 class="font-weight-bold text-center">Manager Employees</h1>
                <img class = "img1"  src = "pepsi.png"> 
                <hr class = "hr1">
            </div>

            <div>
                
			
				<?php
include('connect.php');
session_start();

$time= $_SERVER['REQUEST_TIME'];
		$timeout=300;
	
			if(isset($_SESSION['last'])===true && ($time-$_SESSION['last'])>$timeout)
			{
				header("location:login.php?st=".rand());
				exit;
			}
			
		$_SESSION['last']=$time;
	if(isset($_SESSION['manager']) && $_SESSION['manager']===true)
	{
		
		if((isset($_POST['filterfirst']) && $_POST['filterfirst']!='') || (isset($_POST['filterlast']) && $_POST['filterlast']!=''))
		{
			$cleanfirst=str_replace(' ','',filter_var(htmlentities($_POST['filterfirst'],  ENT_QUOTES,  'utf-8'),FILTER_SANITIZE_STRING));
			$cleanlast=str_replace(' ','',filter_var(htmlentities($_POST['filterlast'],  ENT_QUOTES,  'utf-8'),FILTER_SANITIZE_STRING));
			$searchfirst="%".$cleanfirst."%";
			$searchlast="%".$cleanlast."%";

	
$peopleinfo=$link->prepare("SELECT firstName, lastName, email, phoneNumber, PrivilegeID, employee.employeeID
						FROM employee, employeeprivlege, email, phone, team
						where employee.EmployeeID=email.EmployeeID 
						and email.Type='Work' 
						and employee.EmployeeID=phone.EmployeeID 
						and phone.Type='Work'
						and managerid LIKE '".$_SESSION['username']."'
						and team.employeeid=Employee.EmployeeID						
						and employee.EmployeeID=employeeprivlege.EmployeeID
						and PrivilegeID='E'
						and (firstName LIKE ? and lastName LIKE ?)
						ORDER BY lastname, firstname");
						$peopleinfo->bind_param("ss",$searchfirst,$searchlast);
						$peopleinfo->execute();
						$result=$peopleinfo->get_result();	
	echo"
	  <h1 class='h1_1'>Filtered Results For: " . $cleanfirst . " " . $cleanlast . " </h1></div>
	  <div><center>". mysqli_num_rows($result)." result(s)
	  </center></div>";
	  ?>
	  
	  <div class="row div_space"> 
                <div class="col-md-8"> </div>
                <div class="col-md-4"> 
                <button type="button" class="btn btn-primary mbut" onclick="location.href='employees.php';">Go Back</button>
                </div>
                
          </div>
<?php
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
	}
	
	else
		header("location:employees.php?manager_no_filter");
	}
	
	else
		header("location:login.php?manager_no_mantok");
echo"					                         
                  </table>
            </div>
            <div class='col-md-1'> </div>
        </div>";
		?>
            </div>
         
      </div>     
            
    </body>
</html>