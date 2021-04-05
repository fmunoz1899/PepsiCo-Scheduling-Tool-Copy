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
            </ul>
      </nav>


            <div class="jumbotron text-center jumbotron2">
                <h1 class="font-weight-bold text-center">Filtered Employees</h1>
                <img class = "img1"  src = "pepsi.png"> 
                <hr class = "hr1">
            </div>

            <div>
<?php
		session_start();
			/*$time= $_SERVER['REQUEST_TIME'];
			$timeout=5;
		
				if(isset($_SESSION['last'])===true && ($time-$_SESSION['last'])>$timeout)
				{
					header("location:login.php?st=".rand());
					exit;
				}
				
			$_SESSION['last']=$time;*/
		
		if(isset($_SESSION['admin']))
		{
			
		if((isset($_POST['filterfirst']) && $_POST['filterfirst']!='') || (isset($_POST['filterlast']) && $_POST['filterlast']!=''))
		{
			$cleanfirst=filter_var(htmlentities($_POST['filterfirst'],  ENT_QUOTES,  'utf-8'),FILTER_SANITIZE_STRING);
			$cleanlast=filter_var(htmlentities($_POST['filterlast'],  ENT_QUOTES,  'utf-8'),FILTER_SANITIZE_STRING);
			
echo"		<h1 class='h1_1'>Filtered Results For: " . $cleanfirst . " " . $cleanlast . " </h1></div>";
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
 
          </div>
          <div class="row "> 
            <div class="col-md-9"> </div>
                <div class="col-md-3">
                    <button type="button" class="btn btn-primary mbut" onclick="location.href='AdminLanding.php';">Go Back</button>
                </div>
            </div>
        </div>
<?php
include('connect.php');

echo"
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
                      <th>Schedule Information</th>
					  <th>Role</th>
                    </tr>
                    <tr>"; //this will be left here for now as way to remember to do the edit and remove, whether it be button or hyperlink
					
					$searchfirst=filter_var($_POST['filterfirst'], FILTER_SANITIZE_STRING);
					$searchlast=filter_var($_POST['filterlast'], FILTER_SANITIZE_STRING);
					$searchfirst="%".$searchfirst."%";
					$searchlast="%".$searchlast."%";

					$peopleinfo=$link->prepare("SELECT firstName, lastName, email, phoneNumber, PrivilegeID 
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
					$entered=False;
					while($row=$result->fetch_assoc())
					{
						$entered=True;
echo"		
						<td><form method='POST' action='EmployeeInfo.php'> <input name='transfer' type='hidden' value='" . $row['email'] . "'> <button class='btn btn-primary' type='submit'>Edit</button></form></td>
                        <td>" . $row['firstName'] . "</td>
                        <td>" . $row['lastName'] . "</td>
                        <td>" . $row['email'] . "</td>
                        <td>" . $row['phoneNumber'] . "</td>
                        <td>Schedule Info Here</td>
						<td>" . $row['PrivilegeID'] . "</td>
                      </tr>
					  ";
					}
					if($entered!=True);
						//echo"<script>alert('There are no search results!'); window.location='AdminLanding.php'; </script>"; //displays message of no results and sends back to adminlanding

					
	$link->close();
					?>
				  
                  </table>
            </div>
            <div class="col-md-1"> </div>
        </div>  
    </body>
</html>


