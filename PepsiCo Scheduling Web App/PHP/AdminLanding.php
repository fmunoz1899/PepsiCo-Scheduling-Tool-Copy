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
                <li class="nav-item active"><a class="nav-link a2" href="AdminLanding.html">Employees</a></li>
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
 <?php 
	include('connect.php');
	session_start();  //not sure off top of head if session variable for admin is needed
	$names="SELECT firstName, lastName FROM employee";
	$result=$link->query($names);
echo" 
            <!-- All names will be taken from the database -->
            <div class='row div_space'> 
                <div class='col-md-1'> </div>
                <div class='col-md-4'> 
                    <label>Select employee to filter results:</label>
                    <select name='employees' id='emps'>
						<option>None</option>";
                        while($row=$result->fetch_assoc()) 
							echo"<option value=" . $row["firstName"] . " " . $row["lastName"] . ">" . $row["firstName"] . " " . $row["lastName"] . "</option>";
echo"
                    </select>
                </div>
	";
	mysqli_close($link);
 ?>              
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
				
				$emptable=$link->prepare("insert into employee values(default,?,?,?)");
				$emptable->bind_param("sss",$cleanfname,$cleanlname,$cleanpword);
				$enter=$emptable->execute();
				
				$empid="SELECT Max(EmployeeID) FROM employee";
				$result=mysqli_query($link,$empid);
				$row = mysqli_fetch_array($result);
				
				//I tired and annyoed with this garbage so I'm leaving it at this for now, kinda still broken :( 
				$holder=$row['Max(EmployeeID)'];
				
				$email="INSERT into email values($holder,$cleanem)";
				mysqli_query($link,$email);
				
				$phone="INSERT into phone values($holder,$cleanpnum)";
				mysqli_query($link, $phone);
				/*$emailenter=$link->prepare("INSERT into email values(?,?)"); MUST FIGURE OUT HOW TO MAKE SANATIZE
				$emailenter->bind_param("is",$holder,$cleanem);
				$pnumenter=$link->prepare("INSERT into phone values(" . $row['Max(EmployeeID)'] . ",?");
				$pnumenter->bind_param("s",$cleanpnum);
				$emailenter->execute();
				$pnumenter->execute();
				
				$emptable->close();
				$emailenter->close();
				$pnumenter->close();*/
				
				//prepare and bind query
				//enter into database
				//get role to the new user within emp_priv table
				//
			}
			
			else
				echo"<script>alert('Your passwords do not match!')</script>";
		}
		
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
                    <input type="tel" class="form-control" name="pnum" type="phone number" minlength="10" maxlength="10" placeholder="Phone Number" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input class="form-control" name="pword" placeholder="Password" type="password" required>
					<!-- title='Must be 8-20 characters long and contain a number' type="password" pattern="(?=.*\d).{8,20}" <-- that line forces at least 1 num and max/min len -->
                </div>   
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input class="form-control" name="pwordc" placeholder="Password" type="password" required>
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
	  <!-- depending upon how to do filter, this will change therefore it shall wait for the time being -->
        <div class="row tbl_space"> 
            <div class="col-md-1"> </div>
            <div class = "col-md-10">
			<table style= width:75%>
                    <tr>
                      <th>First Name</th>
                      <th>Last Name</th>
                      <th>Primary Email	</th>
                      <th>Primary Phone Number</th>
                      <th>Schedule Information</th>
                    </tr>
                    <tr>
                      <td><a href = "EmployeeInfo.html">John </a></td>
                      <td>Doe</td>
                      <td>jd2301@pepsico.org</td>
                      <td>9147852654</td>
                      <td></td>
                    </tr>
                    <tr>
                        <td>Robert</td>
                        <td>Johnson</td>
                        <td>rjohnson1234@pepsico.org</td>
                        <td>9845652123</td>
                        <td></td>
                      </tr>
                      <tr>
                        <td>Fwankie</td>
                        <td>Munoz</td>
                        <td>fmunny@pepsico.org</td>
                        <td>6352100045</td>
                        <td></td>
                      </tr>                           
                  </table>
            </div>
            <div class="col-md-1"> </div>
        </div>
        
            
    </body>
</html>

