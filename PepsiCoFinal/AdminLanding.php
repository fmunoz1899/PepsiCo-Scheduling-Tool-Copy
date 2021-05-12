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
	
	
	
	 <nav class="navbar navbar-expand-sm ">
          <ul class="navbar-nav">	
            <li class="nav-item active"><button type="button" class="btn btn-outline-primary navButtons" onclick ="window.location.href = 'AdminLanding.php';">Employees</button></li>
			<li class="nav-item active"><button type="button" class="btn btn-outline-primary navButtons" onclick ="window.location.href = 'adminloc.php';">Locations</button></li>
			</ul>
			<div class="navbar-nav ml-auto">
				<li class="nav-item active"><button type="button" class="btn btn-outline-danger" onclick ="window.location.href = 'login.php';" >Log Out</button></li>
			</div>
			
      </nav>
	  
        


            <div class="jumbotron text-center jumbotron2">
                <h1 class="font-weight-bold text-center">Admin Employees</h1>
                <img class = "img1"  src = "pepsi.png"> 
                <hr class = "hr1">
            </div>

            <div>
                <h1 class="h1_1">Displaying all Employees</h1>
            </div>
			
			
			<div class="row div_space">
				<div class="col-md-1"></div>
				<div class="col-md-7"> <!-- fuck this fix it please thanks -->
					<form method="POST" action="Filter.php" class = "form2">
						<label>Search for an Employee:</label>
						<input type="text" name="filterfirst" placeholder="First Name"> 
						<br>
						<input type="text" name="filterlast" placeholder="Last Name"> 
						<button class="btn btn-primary" type="submit">Filter</button>
					</form>
				</div>
			</div>
					
					

          <div class="row"> 
            <div class="col-md-9"> </div>
                <div class="col-md-3">
                    <button type="button" class="btn btn-primary mbut" data-toggle="modal" data-target="#newEmp"> New Employee</button>
                </div>
            </div>
        </div>


         <!--------------- Modal Code -------------->
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
	
		$time= $_SERVER['REQUEST_TIME'];
		$timeout=300;
	
			if(isset($_SESSION['last'])===true && ($time-$_SESSION['last'])>$timeout)
			{
				header("location:login.php?st=".rand());
				exit;
			}
			
		$_SESSION['last']=$time;

	if(isset($_SESSION['admin']) && $_SESSION['admin']===true)
	{
		if(isset($_POST['transfer'])) //make sure to make it check if the worker being removed is not scheduled for a work order
		{
			$clean=filter_var(htmlentities($_POST['transfer'],  ENT_QUOTES,  'utf-8'),FILTER_SANITIZE_EMAIL); // to get the users empID
			$empid=$link->prepare("SELECT email.employeeID, firstName, lastName FROM email, employee WHERE email.email=? and email.EmployeeID=employee.EmployeeID");
			$empid->bind_param("s",$clean);
			$empid->execute();
			$result=$empid->get_result();
			$row=$result->fetch_assoc();

			$check=$link->prepare("SELECT employeeID FROM workitem WHERE EmployeeID=?");
			$check->bind_param("i",$row['employeeID']);
			$check->execute();
			$final= $check->get_result();
			$rowcount=mysqli_num_rows($final);
			
			$mancheck=$link->prepare("SELECT *
									  FROM team
									  WHERE managerid=?");
			$mancheck->bind_param("i",$row['employeeID']);
			$mancheck->execute();
			$final2=$mancheck->get_result();
			$rowcount2=mysqli_num_rows($final2);
			
			
			$holder=$row['employeeID'];
			
			if($rowcount==0 && $rowcount2==0) //not allowing the removal of an employee if the have a work item scheduled
			{
				//make sure to remove from blockout
				$remove=$link->prepare("DELETE email, employee, phone, employeeprivlege, ahours, team
										FROM email 
										INNER JOIN employee on employee.EmployeeID=email.EmployeeID 
										INNER JOIN phone on phone.EmployeeID=employee.EmployeeID 
										INNER JOIN employeeprivlege on employeeprivlege.EmployeeID=phone.EmployeeID 
										INNER JOIN ahours on ahours.employeeID=employeeprivlege.employeeID 
										INNER JOIN team on team.employeeID=ahours.employeeID
										WHERE email.EmployeeID=?");
				$remove->bind_param("i",$holder);
				$remove->execute();
				$getall=$link->prepare("SELECT blackoutid
										FROM blackout
										WHERE employeeid=?");
				$getall->bind_param("i",$holder);
				$getall->execute();
				$blackid=$getall->get_result();
			while($bid=$blackid->fetch_assoc())
			{				
				$remove2=$link->prepare("DELETE FROM blackout
										 WHERE employeeid=?
										 AND blackoutid=?");
				$remove2->bind_param("ii",$holder,$bid['blackoutid']);
				$remove2->execute();
			}
				echo"<script>alert('" . $row['firstName'] . " " . $row['lastName'] . " was removed!')</script>";
			}
			else if($rowcount!=0 && $rowcount2==0)
				echo"<script>alert('" . $row['firstName'] . " " . $row['lastName'] . " is scheduled to work!')</script>";
			else if($rowcount==0 && $rowcount2!=0)
				echo"<script>alert('" . $row['firstName'] . " " . $row['lastName'] . " is managing a team!')</script>";
		}
		
		if(isset($_POST['fname']) && isset($_POST['lname']) && isset($_POST['em']) && isset($_POST['pnum']) && isset($_POST['pword']) && isset($_POST['pwordc']))
		{
			if($_POST['pword']==$_POST['pwordc'])
			{
				$cleanfname=filter_var($_POST['fname'], FILTER_SANITIZE_STRING);
				$cleanlname=filter_var($_POST['lname'], FILTER_SANITIZE_STRING);
				$cleanem=filter_var($_POST['em'], FILTER_SANITIZE_EMAIL);
				$cleanpnum=filter_var($_POST['pnum'], FILTER_SANITIZE_NUMBER_INT);
				$cleanpword=filter_var($_POST['pword'], FILTER_SANITIZE_STRING);
				
				$emailcheck=$link->prepare("SELECT email FROM email WHERE email.email=?");
				$emailcheck->bind_param("s",$cleanem);
				
				$emailcheck->execute();
				$final= $emailcheck->get_result();
				$rowcount=mysqli_num_rows($final);
				
				$numcheck=$link->prepare("SELECT phoneNumber FROM phone WHERE phone.phoneNumber=?");
				$numcheck->bind_param("s",$cleanpnum);
				
				$numcheck->execute();
				$final2= $numcheck->get_result();
				$rowcount2=mysqli_num_rows($final2);
			
				
				if($rowcount>0 && $rowcount2>0) 
					echo "<script>alert('The email and phone number is already in use');</script>"; //warning if phone and email is already in use
					
				else if($rowcount2>0)
					echo "<script>alert('The phone number is already in use');</script>"; //warning if phone is already in use
					
				else if($rowcount>0)
					echo "<script>alert('The email is already in use');</script>"; //warning if email is already in use
				
				else if($_POST['fname']!=$cleanfname || $cleanlname!=$_POST['lname'] || $cleanem!=$_POST['em'] || $_POST['pnum']!=$cleanpnum || $cleanpword!= $_POST['pword'])
					echo "<script>alert('There are invalid characters in the input values, please try again');</script>"; //warning to say not same as after sanitization
				
				else if($_POST['pword']!=$cleanpword)
					echo"<script>alert('Your passwords do not match');</script>";
							
				else
				{
					$pass=hash('sha256' , filter_var($cleanpword, FILTER_SANITIZE_STRING));
					$emptable=$link->prepare("insert into employee values(default,?,?,?)");
					$emptable->bind_param("sss",$cleanfname,$cleanlname,$pass);
					$emptable->execute(); 
					
					$empid="SELECT Max(EmployeeID) FROM employee"; //does not need to be bind or prepared as no user input !!might need to be changed!!
					$result=mysqli_query($link,$empid);
					$row = mysqli_fetch_array($result);
					
					$emailprep=$link->prepare("INSERT into email values(?," . $row['Max(EmployeeID)'] . ",'Work')");
					$emailprep->bind_param("s",$cleanem);
					$emailprep->execute();
					
					
					$numprep=$link->prepare("INSERT into phone values(?," . $row['Max(EmployeeID)'] . ",'Work')");
					$numprep->bind_param("s",$cleanpnum);
					$numprep->execute();
					
					$emppriv="INSERT into employeeprivlege values(" . $row['Max(EmployeeID)'] . ", '" . $_POST['role'] . "')";
					mysqli_real_query($link,$emppriv);
					
					$hours="INSERT into ahours values(" . $row['Max(EmployeeID)'] . ", 'Sun', default, default)";
					mysqli_real_query($link,$hours);
					$hours="INSERT into ahours values(" . $row['Max(EmployeeID)'] . ", 'Mon', default, default)";
					mysqli_real_query($link,$hours);
					$hours="INSERT into ahours values(" . $row['Max(EmployeeID)'] . ", 'Tue', default, default)";
					mysqli_real_query($link,$hours);
					$hours="INSERT into ahours values(" . $row['Max(EmployeeID)'] . ", 'Wed', default, default)";
					mysqli_real_query($link,$hours);
					$hours="INSERT into ahours values(" . $row['Max(EmployeeID)'] . ", 'Thu', default, default)";
					mysqli_real_query($link,$hours);
					$hours="INSERT into ahours values(" . $row['Max(EmployeeID)'] . ", 'Fri', default, default)";
					mysqli_real_query($link,$hours);
					$hours="INSERT into ahours values(" . $row['Max(EmployeeID)'] . ", 'Sat', default, default)";
					mysqli_real_query($link,$hours);
					

					$team="INSERT into team values(default,".$row['Max(EmployeeID)'].")";
					mysqli_real_query($link,$team);

				
					header("Refresh:0"); //to force refresh to show new employee in filter
				}
			}
			
			else
				echo"<script>alert('Your passwords do not match!')</script>";
		}
		$link->close();
	}
	
	else
		header("location:login.php?amdin_no_admintok");
?>
      <div class="modal fade" id="newEmp" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Create New Employee</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body modalContent">
              <form class = "form1" action = "AdminLanding.php" method = "POST">
                <div class="form-group">
                    <label>First Name</label>
                    <input type="Text" class="form-control" name="fname" placeholder="First Name" required>
                </div>   
                <div class="form-group">
                    <label>Last Name</label>
                    <input type="Text" class="form-control" name="lname" placeholder="Last Name" required>
                </div>
                <div class="form-group">
                    <label>Primary Email</label>
                    <input type="email" class="form-control" name="em" placeholder="example@pepsico.org" required>
                </div>
                <div class="form-group">
                    <label>Primary Phone Number</label>
                    <input type="tel" class="form-control" name="pnum" type="phone number" minlength="10" maxlength="10" placeholder="Phone Number" required> <!--modify warning if other than numbers-->
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input class="form-control" pattern="(?=^.{6,}$)((?=.*\d)(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$" title="Must contain at least 1 number, 1 uppercase, 1 lowercase letter, and 1 special character" name="pword" placeholder="Password" type="password" required> <!-- we need to end up hashing this-->
					<!-- title='Must be 8-20 characters long and contain a number' type="password" pattern="(?=.*\d).{8,20}" <-- that line forces at least 1 num and max/min len -->
                </div>   
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input class="form-control" pattern="(?=^.{6,}$)((?=.*\d)(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$" title="Must contain at least 1 number, 1 uppercase, 1 lowercase letter, and 1 special character" name="pwordc" placeholder="Password" type="password" required> <!-- we need to end up hashing this-->
					<!-- title='Must be 8-20 characters long and contain a number' type="password" pattern="(?=.*\d).{8,20}" <-- that line forces at least 1 num and max/min len -->
                </div>
                <!-- JAVASCRIPT TO CHECK IF PASSWORDS MATCH FILTER AND SANATIZE EVERYTHING HERE!!!! -->
                <div class="form-group">
                    <label>Employee Role</label><br/>
                    <select class = "checkSpace" name = "role">
                        <option value = "E"> Engineer </option> 
                        <option value = "M"> Manager </option> 
                        <option value = "A"> Admin </option> 
                    </select>
                </div>       
                <button type="submit" name="submit" class="btn btn-primary">Create</button> 
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

echo"
        <div class='row tbl_space'> 
            <div class='col-md-1'> </div>
            <div class = 'col-md-10'>
			<table>
                    <tr>
					  <th>Edit</th>
                      <th>Last Name</th>
					  <th>First Name</th>
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
					  <th>Manager</th>
                    </tr>
                    <tr>"; 

					$peopleinfo="SELECT firstName, lastName, email, phoneNumber, PrivilegeID, employee.employeeID, managerid
					FROM employee, employeeprivlege, email, phone, team 
					WHERE employee.EmployeeID=email.EmployeeID 
					AND email.Type='Work' 
					AND employee.EmployeeID=phone.EmployeeID 
					AND phone.Type='Work' 
					AND employee.EmployeeID=employeeprivlege.EmployeeID
					AND team.employeeID=employee.employeeid
					ORDER BY CASE
					WHEN privilegeid='E' then 1
					WHEN privilegeid='M' then 2
					WHEN privilegeid='A' then 3
					ELSE 0
					END asc, lastname, firstname
					 
				";
					
					$result=mysqli_query($link,$peopleinfo);
					
					while($row=$result->fetch_assoc())
					{
						$mgrID = $row['managerid'];
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
						<td><form method='POST' class = 'hiddenform' action='EmployeeInfo.php'> <input name='transfer'  type='hidden' value='" . $row['email'] . "'> <input type='hidden' name='manid2' value=".$row['managerid']."> <button class='btn btn-success getMgr' type='submit' value ='".$row['managerid']."' >Edit</button></form></td>
                        <td>" . $row['lastName'] . "</td>
						<td>" . $row['firstName'] . "</td>
                        <td>" . $row['email'] . "</td>
                        <td>(" . substr($row['phoneNumber'],0,3) . ")-".substr($row['phoneNumber'],3,3)."-".substr($row['phoneNumber'],6,4)."</td>";
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

						<td>" . $row['PrivilegeID'] . "</td>";
							
						
						if($row['managerid']!='')
						{
							$name1=$link->prepare("SELECT firstname, lastname
												   FROM employee
												   WHERE employeeID=?");
							$name1->bind_param("i",$row['managerid']);
							$name1->execute();
							$temp1=$name1->get_result();
							$row2 = mysqli_fetch_array($temp1);
							echo"<td id = ".$mgrID."mname>".$row2[0]." ".$row2[1]."</td>";
						}
						
						else
						{
							echo"<td>-</td>";
						}
							echo"</tr>";
					}
	$link->close();
					?>
					
					
				  
                  </table>
				  <br>
				  <br>
            </div>
            <div class="col-md-1"> </div>
        </div>  
    </body>
</html>
