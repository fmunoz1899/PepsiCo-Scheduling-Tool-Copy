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
                <h1 class="font-weight-bold text-center">Employees</h1>
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
						<input type="text" name="filterbutton" placeholder="First and/or Last name" required>
						<button class="btn btn-primary" type="submit">Filter</button>
					</form>
				</div>
			</div>
					
					

          <div class="row "> 
            <div class="col-md-9"> </div>
                <div class="col-md-3">
                    <button type="button" class="btn btn-primary mbut" data-toggle="modal" data-target="#newEmp"> New Employee</button>
                </div>
            </div>
        </div>


         <!--------------- Modal Code -------------->
<?php
	include('connect.php');

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
					
							
				else
				{
					$emptable=$link->prepare("insert into employee values(default,?,?,?)");
					$emptable->bind_param("sss",$cleanfname,$cleanlname,$cleanpword);
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
					mysqli_query($link,$emppriv);
				
					header("Refresh:0"); //to force refresh to show new employee in filter
				}
			}
			
			else
				echo"<script>alert('Your passwords do not match!')</script>";
		}
		$link->close();
		
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
                    <input class="form-control" name="pword" placeholder="Password" type="password" required> <!-- we need to end up hashing this-->
					<!-- title='Must be 8-20 characters long and contain a number' type="password" pattern="(?=.*\d).{8,20}" <-- that line forces at least 1 num and max/min len -->
                </div>   
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input class="form-control" name="pwordc" placeholder="Password" type="password" required> <!-- we need to end up hashing this-->
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
                      <th>First Name</th>
                      <th>Last Name</th>
                      <th>Primary Email	</th>
                      <th>Primary Phone Number</th>
                      <th>Schedule Information</th>
					  <th>Role</th>
                    </tr>
                    <tr>
					  <td></td>
                      <td><a href = 'EmployeeInfo.php'>John </a></td>
                      <td>Doe</td>
                      <td>jd2301@pepsico.org</td>
                      <td>9147852654</td>
                      <td></td>
					  <td></td>
                    </tr>
                    <tr>"; // ^^this will be left here for now as way to remember to do the edit and remove, whether it be button or hyperlink
					
					$peopleinfo="SELECT firstName, lastName, email, phoneNumber, PrivilegeID 
					FROM employee, employeeprivlege, email, phone 
					where employee.EmployeeID=email.EmployeeID 
					and email.Type='Work' 
					and employee.EmployeeID=phone.EmployeeID 
					and phone.Type='Work' 
					and employee.EmployeeID=employeeprivlege.EmployeeID";
					
					$result=mysqli_query($link,$peopleinfo);
					
					while($row=$result->fetch_assoc())
					{
echo"					
						<td><form method='POST' action='EmployeeInfo.php'> <input name='transfer' type='hidden' value='" . $row['email'] . "'> <button class='btn btn-primary' type='submit'>Edit</button></form></td>
                        <td>" . $row['firstName'] . "</td>
                        <td>" . $row['lastName'] . "</td>
                        <td>" . $row['email'] . "</td>
                        <td>" . $row['phoneNumber'] . "</td>
                        <td>Schedule Info Here</td>  <!-- this is where the schedule will be placed -->
						<td>" . $row['PrivilegeID'] . "</td>
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

