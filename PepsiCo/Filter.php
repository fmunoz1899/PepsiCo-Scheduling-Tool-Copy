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
                <li class="nav-item active"><a class="nav-link a2" href="AdminLanding.php">Employees</a></li>
				<li class="nav-item active"><a class="nav-link a2" href="adminloc.php">Locations</a></li>
				<li class="nav-item active"><a class="nav-link a2" href="login.php">Log Out</a></li>
            </ul>
      </nav>


            <div class="jumbotron text-center jumbotron2">
                <h1 class="font-weight-bold text-center">Admin Filtered Employees</h1>
                <img class = "img1"  src = "pepsi.png"> 
                <hr class = "hr1">
            </div>

            
<?php
		session_start();
			$time= $_SERVER['REQUEST_TIME'];
			$timeout=5;
		
				if(isset($_SESSION['last'])===true && ($time-$_SESSION['last'])>$timeout)
				{
					header("location:login.php?st=".rand());
					exit;
				}
				
			$_SESSION['last']=$time;
		
		if(isset($_SESSION['admin']) && $_SESSION['admin']===true)
		{ 	
			if((isset($_POST['filterfirst']) && $_POST['filterfirst']!='') || (isset($_POST['filterlast']) && $_POST['filterlast']!=''))
			{
				$cleanfirst=filter_var(htmlentities($_POST['filterfirst'],  ENT_QUOTES,  'utf-8'),FILTER_SANITIZE_STRING);
				$cleanlast=filter_var(htmlentities($_POST['filterlast'],  ENT_QUOTES,  'utf-8'),FILTER_SANITIZE_STRING);
				
	
			}
		
		
			else
			{
				header("location:adminlanding.php?filter_no_transfer");
				exit;
			}
		}
		
		else
		{
			header("location:login.php?filter_no_admintok");
			exit;
		}
?>
 
         
<?php
include('connect.php');
echo"		<div><h1 class='h1_1'>Filtered Results For: " . $cleanfirst . " " . $cleanlast . " </h1></div>";
echo"

 </div>
          <div class='row'> 
            <div class='col-md-9'> </div>
                <div class='col-md-3'>
                    <a href='adminlanding.php'><button type='button' class='btn btn-primary mbut'>Go Back</button></a>
                </div>
            </div>
        </div>
        <div class='row tbl_space'> 
            <div class='col-md-1'> </div>
            <div class = 'col-md-10'>
			<table>
                    <tr>
					  <th>Edit</th>
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
					  <th>Role</th>
                    </tr>
                    <tr>"; //this will be left here for now as way to remember to do the edit and remove, whether it be button or hyperlink
					
					$searchfirst=str_replace(' ','',filter_var($_POST['filterfirst'], FILTER_SANITIZE_STRING));
					$searchlast=str_replace(' ','',filter_var($_POST['filterlast'], FILTER_SANITIZE_STRING));
					$searchfirst="%".$searchfirst."%";
					$searchlast="%".$searchlast."%";

					$peopleinfo=$link->prepare("SELECT firstName, lastName, email, phoneNumber, PrivilegeID, employee.employeeID
					FROM employee, employeeprivlege, email, phone 
					where employee.EmployeeID=email.EmployeeID 
					and email.Type='Work' 
					and employee.EmployeeID=phone.EmployeeID 
					and phone.Type='Work' 
					and employee.EmployeeID=employeeprivlege.EmployeeID
					and (firstName LIKE ? and lastName LIKE ?)"); 
					
					$peopleinfo->bind_param("ss",$searchfirst,$searchlast);
					$peopleinfo->execute();
					$result=$peopleinfo->get_result();
					echo mysqli_num_rows($result)." result(s)";
					while($row=$result->fetch_assoc())
					{
						$schedule="SELECT DayID, TIME_FORMAT(StartTime, '%l:%i%p') AS StartTime, TIME_FORMAT(EndTime, '%l:%i%p') AS EndTime, employeeID 
						FROM ahours WHERE EmployeeID=".$row['employeeID']." ORDER BY CASE 
						when DayID='Sun' then 1 
						when DayID='Mon' then 2 
						when DayID='Tue' then 3 
						when DayID='Wed' then 4 
						when DayID='Thu' then 5 
						when DayID='Fri' then 6 
						when DayID='Sat' then 7 
						else 8 end asc";
						$quick=mysqli_query($link,$schedule);
echo"		
						<td><form method='POST' class = 'hiddenform' action='EmployeeInfo.php'> <input name='transfer'  type='hidden' value='" . $row['email'] . "'> <button class='btn btn-success' type='submit'>Edit</button></form></td>

                        <td>" . $row['firstName'] . "</td>
                        <td>" . $row['lastName'] . "</td>
                        <td>" . $row['email'] . "</td>
                        <td>" . $row['phoneNumber'] . "</td>";
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
echo"						<td>" . $row['PrivilegeID'] . "</td>
                      </tr>
					  ";
					}
						//echo"<script>alert('There are no search results!'); window.location='AdminLanding.php'; </script>"; //displays message of no results and sends back to adminlanding

					
	$link->close();
					?>
				  
                  </table>
            </div>
            <div class="col-md-1"> </div>
        </div>  
    </body>
</html>

