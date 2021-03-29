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
echo"
                <h1 class='h1_1'>Filtered Results For: \"" . $_POST['filterbutton'] . "\" </h1>
            </div>
			";
?>
 
          </div>
          <div class="row "> 
            <div class="col-md-9"> </div>
                <div class="col-md-3">
                    <button type="button" class="btn btn-primary mbut" onclick="location.href='AdminLanding.php';">Go Back</button>
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
				
				if($_POST['fname']!=$cleanfname || $cleanlname!=$_POST['lname'] || $cleanem!=$_POST['em'] || $_POST['pnum']!=$cleanpnum || $cleanpword!= $_POST['pword'])
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
     

      <!------------------------------------->
<?php
include('connect.php');

echo"
        <div class='row tbl_space'> 
            <div class='col-md-1'> </div>
            <div class = 'col-md-10'>
			<table>
                    <tr>
					  <th>Edit</th> <!-- this still needs to send the emp id to be able to edit properly -->
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
					  <td>E</td>
                    </tr>
                    <tr>"; //this will be left here for now as way to remember to do the edit and remove, whether it be button or hyperlink
					
					$search=filter_var($_POST['filterbutton'], FILTER_SANITIZE_STRING);
					$search="%".$search."%";
					
					$peopleinfo=$link->prepare("SELECT firstName, lastName, email, phoneNumber, PrivilegeID 
					FROM employee, employeeprivlege, email, phone 
					where employee.EmployeeID=email.EmployeeID 
					and email.Type='Work' 
					and employee.EmployeeID=phone.EmployeeID 
					and phone.Type='Work' 
					and employee.EmployeeID=employeeprivlege.EmployeeID
					and (firstName LIKE ? or lastName LIKE ?)");
					
					$peopleinfo->bind_param("ss",$search,$search);
					$peopleinfo->execute();
					$result=$peopleinfo->get_result();
					$entered=False;
					while($row=$result->fetch_assoc())
					{
						$entered=True;
echo"		
						<td><button value='asdas'><a href='EmployeeInfo.php' style= color:black>Edit</button></a></td>
                        <td>" . $row['firstName'] . "</td>
                        <td>" . $row['lastName'] . "</td>
                        <td>" . $row['email'] . "</td>
                        <td>" . $row['phoneNumber'] . "</td>
                        <td>Schedule Info Here</td>
						<td>" . $row['PrivilegeID'] . "</td>
                      </tr>
					  ";
					}
					if($entered!=True)
						echo"<script>alert('There are no search results!'); window.location='AdminLanding.php'; </script>"; //displays message of no results and sends back to adminlanding

					
	$link->close();
					?>
				  
                  </table>
            </div>
            <div class="col-md-1"> </div>
        </div>  
    </body>
</html>


