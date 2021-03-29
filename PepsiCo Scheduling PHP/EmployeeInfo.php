<!DOCTYPE HTML>
<html> 
<head>
    <title>Pepsi Scheduling Tool</title>
	<!--need empID to be able to make changes -->
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

            <div class = "formDiv1">
<?php
			include('connect.php');
			$dispname="SELECT firstName, lastName FROM email, employee WHERE email.email='".$_POST['transfer']."' and email.employeeID=employee.employeeID";
			$result=mysqli_query($link,$dispname);
			$row = mysqli_fetch_array($result);
echo"
                <h1 class='h1_1'>" . $row[0] . " " . $row[1] . "</h1>
";			
?>
                <a href = "changePass.php">Change Password</a>
          </div>
               
            </div>
            

            <div class="row div_space"> 
                <div class="col-md-1"> </div>
                <div class="col-md-6"></div>
                <div class="col-md-2"></div>
                  <div class="col-md-3 ">
                    <button type="button" class="btn btn-primary mbut" data-toggle="modal" data-target="#EmpInfo"> Add Information</button>

                  </div>
              </div>
          </div>


         <!--------------- Modal Code -------------->
      <div class="modal fade" id="EmpInfo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Add Employee Information</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body modalContent">
              <form class = "form1" action = "EmployeeInfo.html" method = "POST">
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" class="form-control" name="em" placeholder="example@pepsico.org" required>
                </div>
                <div class="form-group">
                    <label> Phone Number</label>
                    <input type="tel" class="form-control" name="pnum" minlength="10" maxlength="10" placeholder="Phone Number" required>
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
    <!--------------- Modal Code For Password -------------->
    <div class="modal fade" id="pwrdCng" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Add Employee Information</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body modalContent">
            <form class = "form1" action = "EmployeeInfo.html" method = "POST">
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" class="form-control" name="pword" placeholder="Password" required>
                </div>   
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" class="form-control" name="pword" placeholder="Password" required>
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
	<!--include('connect.php');
			$dispname="SELECT firstName, lastName FROM email, employee WHERE email.email='".$_POST['transfer']."' and email.employeeID=employee.employeeID";
			$result=mysqli_query($link,$dispname);
			$row = mysqli_fetch_array($result);-->
<?php
include('connect.php');
/*
SELECT Email
from Email
where email.EmployeeID=
(select  EmployeeID
 from Email
 where Email.Email='r')
 */
 
 /*SELECT phoneNumber FROM phone WHERE phone.EmployeeID=(SELECT EmployeeID FROM Email WHERE Email.Email=?)*/
 
		$ID=filter_var($_POST['transfer'], FILTER_SANITIZE_EMAIL);

		$soloemail=$link->prepare("SELECT email FROM Email WHERE email.EmployeeID=(SELECT EmployeeID FROM Email WHERE Email.Email=?)");
		$soloemail->bind_param("s",$ID);
		
		
		$solonum=$link->prepare("SELECT phoneNumber FROM phone WHERE phone.EmployeeID=(SELECT EmployeeID FROM Email WHERE Email.Email=?)");
		$solonum->bind_param("s",$ID);
	
			
		$soloemail->execute();
		
		
		//echo var_dump($soloemail);
		//echo var_dump($solonum);
		
		$finalem=$soloemail->get_result();
		
		
		//$numrow=$finalnum->fetch_assoc();
		
 //This area is still not !00% complete just table issues
echo"
        <div class='row tbl_space'> 
            <div class='col-md-3'> </div>
            <div class = 'col-md-7'>
                <table>
                    <tr>
                      <th>Emails</th>
                      <th>Phone Numbers</th>
                    </tr>
                    ";
					
					while($emrow=$finalem->fetch_assoc())
echo"                 <td>" . $emrow['email'] . "</td></tr>";

					$solonum->execute();
					$finalnum=$solonum->get_result();
					
					while($numrow=$finalnum->fetch_assoc())
echo"                 <td>" . $numrow['phoneNumber'] . "</td>
                    </tr>
";
?>
				</table>
            </div>
            <div class="col-md-1"> </div>
        </div>
           
    </body>
</html>

