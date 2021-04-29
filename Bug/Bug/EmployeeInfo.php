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
				<li class="nav-item active"><a class="nav-link a2" href="login.php">Log Out</a></li>
            </ul>
      </nav>


            <div class="jumbotron text-center jumbotron2">
                <h1 class="font-weight-bold text-center">Admin Employees</h1>
                <img class = "img1"  src = "pepsi.png"> 
                <hr class = "hr1">
            </div>

            <div class = "formDiv1">
<?php


			include('connect.php');
			session_start();
			
			/*$time= $_SERVER['REQUEST_TIME'];
			$timeout=300; 
	
			if(isset($_SESSION['last'])===true && ($time-$_SESSION['last'])>$timeout)
			{
				header("location:login.php?st=".rand());
				exit;
			}
			
			$_SESSION['last']=$time;*/
			
			if(isset($_POST['transfer1']) && isset($_POST['transferID']))
			{
				$ID=filter_var($_POST['transfer1'], FILTER_SANITIZE_EMAIL); //email
				$ID2=filter_var($_POST['transferID'], FILTER_SANITIZE_NUMBER_INT); //empID
				$remove=$link->prepare("DELETE from email where email.email=? and email.employeeID=?");
				$remove->bind_param("si",$ID,$ID2);
				$remove->execute();
			}
			
			if(isset($_POST['transfer2']) && isset($_POST['transferID']))
			{
				$ID=filter_var($_POST['transfer2'], FILTER_SANITIZE_NUMBER_INT); //phone number //not excatly sure off top of head if correct sanitize for phone number 
				$ID2=filter_var($_POST['transferID'], FILTER_SANITIZE_NUMBER_INT);//empID
				$remove=$link->prepare("DELETE from phone where phone.phoneNumber=? and phone.employeeID=?");
				$remove->bind_param("si",$ID,$ID2);
				$remove->execute();
			}
			
			if(isset($_POST['transfer']))
			{
				$ID=filter_var($_POST['transfer'], FILTER_SANITIZE_EMAIL);
				$dispname=$link->prepare("SELECT firstName, lastName, employee.employeeID FROM email, employee WHERE email.email=? and email.employeeID=employee.employeeID");
				$dispname->bind_param("s",$ID);
				$dispname->execute();
				$final= $dispname->get_result();
				$row = $final->fetch_assoc();

		echo"
				<h1 class='h1_1'>" . $row['firstName'] . " " . $row['lastName'] . "</h1>
";			}


			else
			{
				header("location:login.php?empinfo_no_transfer");
				exit;
			}
?>
				<form method="POST" action="changePass.php">
				<?php
				$clean=filter_var(htmlentities($_POST['transfer'],  ENT_QUOTES,  'utf-8'),FILTER_SANITIZE_EMAIL);
echo"			<input type='hidden' value='".$clean."' name='transfer'> ";?>
				<button class='btn btn-primary' type='submit'>Change Password</button>
				</form> 
				<br>
				<form method="POST" action="AdminLanding.php">
				<?php
				$clean=filter_var(htmlentities($_POST['transfer'],  ENT_QUOTES,  'utf-8'),FILTER_SANITIZE_EMAIL);
echo"			<input type='hidden' value='".$clean."' name='transfer'> ";?>
				<button class='btn btn-primary' type='submit'>Remove Employee</button>
				</form> 
          </div>
               
            </div>
            

            <div class="row div_space"> 
                <div class="col-md-2"> </div>
				<div class='col-md-2'><a type="button" class="btn btn-primary mbut" href="adminlanding.php">Go back</a></div>
                <div class="col-md-3"></div>
                <div class="col-md-2"></div>
                  <div class="col-md-3 ">
                    <button type="button" class="btn btn-primary mbut" data-toggle="modal" data-target="#EmpInfo"> Add Information</button>

                  </div>
              </div>
          </div>


         <!--------------- Modal Code -------------->
		 <!-- make JS that makes Modal add button disabled unless one or both of the text fields are filled-->
<?php
include('connect.php');
		$flag=0;
		
		if((isset($_POST['addnum']) && $_POST['addnum']!='') || (isset($_POST['addemail']) && $_POST['addemail']!=''))
		{
			if($_POST['addnum']!='') //if the number isn't filled don't do
			{
				$cleannum=filter_var($_POST['addnum'],FILTER_SANITIZE_NUMBER_INT);
				if($cleannum==$_POST['addnum']) //if original not the same as sanitized don't do
				{
					$numcheck=$link->prepare("SELECT phoneNumber FROM phone WHERE phoneNumber=? and type=?");
					$typing="Work";
					$numcheck->bind_param("ss",$cleannum,$typing);
					$numcheck->execute();
					$final= $numcheck->get_result();
					$rowcount=mysqli_num_rows($final); //check if the work number is already in use
					
					if($rowcount==0 && $_POST['type']==filter_var($_POST['type'],FILTER_SANITIZE_STRING)) //radio button check
					{
						$tempID=$link->prepare("SELECT EmployeeID FROM Email WHERE Email.Email=?");
						
						$passed=$_POST['transfer'];
						$tempID->bind_param("s",$passed);
						$tempID->execute();
						$final= $tempID->get_result();
						$row = $final->fetch_assoc();
						$tempID=$row['EmployeeID'];
						
						$qnum=$link->prepare("INSERT INTO phone VALUES(?,?,?)");
						$qnum->bind_param("sis",$cleannum,$tempID,$_POST['type']);
						
						$qnum->execute();
					}
					else
						$flag=$flag+20;
					
				}
				
				else
					$flag=$flag+2;
					
			}
			
			if($_POST['addemail']!='') //if the email isn't filled don't do
			{
				$cleanemail=filter_var($_POST['addemail'],FILTER_SANITIZE_EMAIL);
				if($cleanemail==$_POST['addemail']) //if original not the same as sanitized don't do
				{
					$emailcheck=$link->prepare("SELECT email FROM email WHERE email=? and type=?");
					$typing="Work";
					$emailcheck->bind_param("ss",$cleanemail,$typing);
					$emailcheck->execute();
					$final= $emailcheck->get_result();
					$rowcount=mysqli_num_rows($final); //check if the work number is already in use
					
					if($rowcount==0)
					{
						$tempID=$link->prepare("SELECT EmployeeID FROM Email WHERE Email.Email=?");
						
						$passed=$_POST['transfer'];
						$tempID->bind_param("s",$passed);
						$tempID->execute();
						$final= $tempID->get_result();
						$row = $final->fetch_assoc();
						$tempID=$row['EmployeeID'];
						$temptype="Personal";
						
						$qemail=$link->prepare("INSERT INTO email VALUES(?,?,?)");
						$qemail->bind_param("sis",$cleanemail,$tempID,$temptype);
						
						$qemail->execute();
					}
					else
						$flag=$flag+30;
					
				}
				
				else
					$flag=$flag+3;
					
				}
			
			if($flag==2)
				echo"<script>alert('There are invaild characters in the phone number');</script>";
			else if($flag==3)
				echo"<script>alert('There are invaild characters in the email');</script>";
			else if($flag==5)
				echo"<script>alert('There are invaild characters in the phone number and email');</script>";
			
			if($flag==20)
				echo"<script>alert('The phone number is in use');</script>";
			else if($flag==30)
				echo"<script>alert('The email is in use');</script>";
			else if($flag==50)
				echo"<script>alert('The phone number and email are in use');</script>";
			
		}
		$link->close();

?>
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
              <form class = "form1" action = "EmployeeInfo.php" method = "POST">
			  <input type="hidden" name="transfer" value=<?php echo filter_var(htmlentities($_POST['transfer'],  ENT_QUOTES,  'utf-8'),FILTER_SANITIZE_EMAIL);?>><!-- this allows the primary email continue to be used for the specific employee-->
                <div class="form-group">
                    <label>Email (will be set as personal)</label>
                    <input type="email" class="form-control" name="addemail" placeholder="example@pepsico.org">
                </div>
                <div class="form-group">
                    <label> Phone Number</label>
                    <input type="tel" class="form-control" name="addnum" minlength="10" maxlength="10" placeholder="Phone Number">
					<input id="personal" type="radio" name="type" value="Personal" checked>
					<label for="personal">Personal</label><br>
					<input type="radio" id="home" name="type" value="Home">
					<label for="home">Home</label>
                </div>
                <button type="submit" name="submit" class="btn btn-primary">Add</button> 
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              
            </div>
          </div>
        </div>
      </div>

      <!------------------------------------->
	  <!--------------- Modal Code For Updating email -------------->

	<div class="modal fade" id="UpdateEm" tabindex="-1" role="dialog" aria-labelledby="AddInformation" aria-hidden="true">
        <div class="modal-dialog" role="document">
	<div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title">Update Employee Email</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
		<div class="modal-body modalContent">
			<form class = "form1" action = "EmployeeInfo.php" method = "POST">
				<input type="hidden" name="transfer" value=<?php echo filter_var(htmlentities($_POST['transfer'],  ENT_QUOTES,  'utf-8'),FILTER_SANITIZE_EMAIL);?>><!-- this allows the primary email continue to be used for the specific employee-->
					<div class="form-group">
						<label>Email (will be set as personal)</label>
						<input type="email" class="form-control" name="addemail" id = "EmailToEdit">
					</div>
				<button type="submit" name="submit" class="btn btn-primary">Update</button> 
			</form>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		</div>
	</div>
        </div>
    </div>


      <!------------------------------------->
     <!--------------- Modal Code For Updating Phone -------------->

 <div class="modal fade" id="UpdatePhone" tabindex="-1" role="dialog" aria-labelledby="UpdateNumber" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
	<h5 class="modal-title" id="exampleModalLongTitle">Update Employee Phone Number </h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
		<div class="modal-body modalContent">
			<form class = "form1" id = "UpdateP" action = "EmployeeInfo.php" method = "POST">
				<div class="form-group">
					<label> Phone Number</label>
					<input type="tel" id = "Number" class="form-control" name="addnum" minlength="10" maxlength="10" >
					<input id="personal" type="radio" name="type" value="Personal" checked>
					<label for="personal">Personal</label><br>
					<input type="radio" id="home" name="type" value="Home">
					<label for="home">Home</label>
				</div>
			<button type="submit" name="submit" class="btn btn-primary">Update</button> 
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
if(isset($_POST['monS']))
		echo"<script>alert('".$_POST['monS']."');</script>";
	  ?>
	   <!--------------- Modal Code For Updating Sched -------------->

 <div class="modal fade" id="EditSched" tabindex="-1" role="dialog" aria-labelledby="UpdateNumber" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
	<h5 class="modal-title" id="exampleModalLongTitle">Employee Schedule </h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
		<div class="modal-body modalContent">
			<form class = "form1" id = "UpdateP" action = "EmployeeInfo.php" method = "POST">
			<label> Sunday </label></br>
				<div class="input-group">
				<select required>
				<option name="SunS" id = "SuS"  value='' selected>--Start--</option>
				<?php
					$time=date('06:00:00');
					while($time!='22:00:00') //time day ends 
					{
						echo"<option value='".$time."'>". date('g:ia',strtotime($time))."</option>";
						$time = date('H:i:s',strtotime('+15 minutes',strtotime($time)));
					}
				?>
				</select>
					<span class="input-group-addon">-</span>
					<select required>
					<option name="SunE" id = "SuE" value='' selected>--Time--</option>
					<?php
						$time=date('06:15:00');
						while($time!='22:15:00') //time day ends 
						{
							echo"<option value='".$time."'>". date('g:ia',strtotime($time))."</option>";
							$time = date('H:i:s',strtotime('+15 minutes',strtotime($time)));
						}
					?>
			</select>
				</div>
			<label> Monday </label></br>
				<div class="input-group">
					<select required>
					<option  name='monS' id = 'MS' value=" " selected>--Start--</option>
					<?php
					$time=date('06:00:00');
					while($time!='22:00:00') //time day ends 
					{
						echo"<option  value='".$time."'>". date('g:ia',strtotime($time))."</option>";
						$time = date('H:i:s',strtotime('+15 minutes',strtotime($time)));
					}
					?>
					</select>
					<span class="input-group-addon">-</span>
					<select required>
					<option  name='monE' id = 'ME' value='' selected>--End--</option>
						<?php
						$time=date('06:15:00');
						while($time!='22:15:00') //time day ends 
						{
							echo"<option  value='".$time."'>". date('g:ia',strtotime($time))."</option>";
							$time = date('H:i:s',strtotime('+15 minutes',strtotime($time)));
						}
						?>
					</select>
				</div>
				<label> Tuesday </label></br>
					<div class="input-group">
							<select required>
							<option name="TueS" id = "TS" value='' selected>--Start--</option>
							<?php
								$time=date('06:00:00');
								while($time!='22:00:00') //time day ends 
								{
									echo"<option value='".$time."'>". date('g:ia',strtotime($time))."</option>";
									$time = date('H:i:s',strtotime('+15 minutes',strtotime($time)));
								}
							?>
							</select>
								<span class="input-group-addon">-</span>
								<select required>
							<option name="TueE" id = "TE" value='' selected>--End--</option>
							<?php
								$time=date('06:15:00');
								while($time!='22:15:00') //time day ends 
								{
									echo"<option value='".$time."'>". date('g:ia',strtotime($time))."</option>";
									$time = date('H:i:s',strtotime('+15 minutes',strtotime($time)));
								}
							?>
						</select>
					</div>
					<label> Wednesday </label></br>
						<div class="input-group">
							<select required>
							<option name="WedS" id = "WS" value='' selected>--Start--</option>
							<?php
								$time=date('06:00:00');
								while($time!='22:00:00') //time day ends 
								{
									echo"<option value='".$time."'>". date('g:ia',strtotime($time))."</option>";
									$time = date('H:i:s',strtotime('+15 minutes',strtotime($time)));
								}
							?>
							</select>
								<span class="input-group-addon">-</span>
								<select required>
								<option name="WedE" id = "WE" value='' selected>--End--</option>
									<?php
									$time=date('06:15:00');
									while($time!='22:15:00') //time day ends 
									{
										echo"<option value='".$time."'>". date('g:ia',strtotime($time))."</option>";
										$time = date('H:i:s',strtotime('+15 minutes',strtotime($time)));
									}
									?>
								</select>
					</div>
					<label> Thusday </label></br>
						<div class="input-group">
						<select required>
							<option name="ThuS" id = "ThS" value='' selected>--Start--</option>
							<?php
								$time=date('06:00:00');
								while($time!='22:00:00') //time day ends 
								{
									echo"<option value='".$time."'>". date('g:ia',strtotime($time))."</option>";
									$time = date('H:i:s',strtotime('+15 minutes',strtotime($time)));
								}
							?>
						</select>
								<span class="input-group-addon">-</span>
								<select required>
									<option name="ThuE"  id = "ThE" value='' selected>--End--</option>
									<?php
										$time=date('06:15:00');
										while($time!='22:15:00') //time day ends 
										{
											echo"<option value='".$time."'>". date('g:ia',strtotime($time))."</option>";
											$time = date('H:i:s',strtotime('+15 minutes',strtotime($time)));
										}
									?>
								</select>
					</div>
					<label> Friday </label></br>
						<div class="input-group">
						<select required>
							<option name="FriS" id = "FS" value='' selected>--Start--</option>
							<?php
								$time=date('06:00:00');
								while($time!='22:00:00') //time day ends 
								{
									echo"<option value='".$time."'>". date('g:ia',strtotime($time))."</option>";
									$time = date('H:i:s',strtotime('+15 minutes',strtotime($time)));
								}
							?>
						</select>
								<span class="input-group-addon">-</span>
								<select required>
									<option name="FriE" id = "FE" value='' selected>--End--</option>
									<?php
										$time=date('06:15:00');
										while($time!='22:15:00') //time day ends 
										{
											echo"<option value='".$time."'>". date('g:ia',strtotime($time))."</option>";
											$time = date('H:i:s',strtotime('+15 minutes',strtotime($time)));
										}
										?>
								</select>
					</div>
					<label> Saturday </label></br>
						<div class="input-group">
						<select required>
							<option name="SatS" id = "SaS"  value='' selected>--Start--</option>
							<?php
							$time=date('06:00:00');
								while($time!='22:00:00') //time day ends 
								{
									echo"<option value='".$time."'>". date('g:ia',strtotime($time))."</option>";
									$time = date('H:i:s',strtotime('+15 minutes',strtotime($time)));
								}
							?>
						</select>
						<span class="input-group-addon">-</span>
						<select required>
							<option name="SatE" id = "SaE" value='' selected>--End--</option>
							<?php
								$time=date('06:15:00');
								while($time!='22:15:00') //time day ends 
								{
									echo"<option value='".$time."'>". date('g:ia',strtotime($time))."</option>";
									$time = date('H:i:s',strtotime('+15 minutes',strtotime($time)));
								}
							?>
						</select>
					</div>

				
				
				
				
				
				
				<input type = "hidden" id = "EmpID"  required>
				<br>
			<input type="hidden" name="transfer" value="<?php $_POST['transfer']?>"">
			<button type="submit" name="submit" class="btn btn-primary">Confirm</button> 
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
 
		$ID=filter_var(htmlentities($_POST['transfer'],  ENT_QUOTES,  'utf-8'),FILTER_SANITIZE_EMAIL);
		

		$soloemail=$link->prepare("SELECT email, type, employeeID FROM Email WHERE email.EmployeeID=(SELECT EmployeeID FROM Email WHERE Email.Email=?) ORDER BY type DESC");
		$soloemail->bind_param("s",$ID);
		
		
		$solonum=$link->prepare("SELECT phoneNumber, type, employeeID FROM phone WHERE phone.EmployeeID=(SELECT EmployeeID FROM Email WHERE Email.Email=?) ORDER BY type DESC");
		$solonum->bind_param("s",$ID);
	
			
		$soloemail->execute();
		$finalem=$soloemail->get_result();
	//	echo"<script>alert('".$_POST['transferID']."');</script>";
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
		
//make edit pop up a modal with the info inserted already
echo"
        <div class='row tbl_space'> 
            <div class='col-md-2'> </div>
            <div class = 'col-md-4'>
                <table>
                    <tr>
					  <th>Edit</th>
					  <th>Delete</th>
                      <th>Emails</th>
					  <th>Type</th>
                    </tr>";
					
					while($emrow=$finalem->fetch_assoc())
					{
					   if($emrow['type']!='Work')
					   {
							echo"<tr>
							<td>
							<button id = 'UEm' class='btn btn-success EmailUpdate'type='button' data-toggle='modal' data-target='#UpdateEm' value='" . $emrow['email'] . "'>Edit</button></td>";
							echo"<td>
							<form method='POST' action='EmployeeInfo.php'> 
							<input name='transfer' type='hidden' value='" . $ID . "'> 
							<input name='transfer1' type='hidden' value='" . $emrow['email'] . "'> 
							<input name='transferID' type='hidden' value='" . $emrow['employeeID'] . "'>
							<button class='btn btn-danger' type='submit'>Remove</button>
							</form>
							</td>";
						}
					
					   else
					   {
					      echo"<td></td>";
					      echo"<td></td>";
					   }
echo"                  <td>" . $emrow['email'] . "</td>
				       <td>" . $emrow['type'] . "</td></tr>";
					}
echo"				</table></div>";

					$solonum->execute();
					$finalnum=$solonum->get_result();
	
	
echo"
			<div class='col-md-4'>
				<table>
					<tr>
					    <th>Edit</th>
						<th>Delete</th>
						<th>Phone Numbers</th>
						<th>Type</th>
					</tr>";
					
					while($numrow=$finalnum->fetch_assoc())
					{
					if($numrow['type']!='Work')
					{
						echo"<tr>
						<td>
						<button id = 'UPnum' class='btn btn-success UpdateNnum'type='button' data-toggle='modal' data-target='#UpdatePhone' value='" . $numrow['phoneNumber'] ."'>Edit</button></td>";
						echo"<td>
						<form method='POST' action='EmployeeInfo.php'> 
						<input name='transfer' type='hidden' value='" . $ID . "'> 
						<input name='transfer2' type='hidden' value='" . $numrow['phoneNumber'] . "'> 
						<input name='transferID' type='hidden' value='" . $numrow['employeeID'] . "'> 
						<button class='btn btn-danger' type='submit'>Remove</button>
						</form>
						</td>";	
					}
					else
					{
					   echo" <td></td>";
					   echo"<td></td>";
					}
echo"                  <td>" . $numrow['phoneNumber'] . "</td>
						<td>" . $numrow['type'] . "</td></tr>";
					}
echo"				</div>";

					
?>
				</table>

            </div>
            <div class="col-md-2"> </div>

        </div>
<?php
	$ThisEID = $row['employeeID'];
	echo"<br>
	<div class='row tbl_space'> 
	<div class='col-md-1'> </div>
	<div class = 'col-md-10'>
	<table>
			<tr>
			<th><button class='btn btn-success EdSched' type='button' data-toggle='modal' data-target='#EditSched' value='" . $row['employeeID'] . "'>Edit</button></th>
			<th>Sunday</th>
			<th>Monday</th>
			<th>Tuesday</th>
			<th>Wednesday</th>
			<th>Thursday</th>
			<th>Friday</th>
			<th>Saturday</th>
			</tr>
			<tr>
			<td>Start Time</td>";
	while($fullschd=$quick->fetch_assoc())
					{
						
						if($fullschd['DayID']=='Sun')
							echo"<td id = ".$ThisEID."SunSt>".$fullschd['StartTime']." </td>";
						if($fullschd['DayID']=='Mon')
							echo"<td id = ".$ThisEID."MonSt>".$fullschd['StartTime']." </td>";
						if($fullschd['DayID']=='Tue')
							echo"<td id = ".$ThisEID."TueSt>".$fullschd['StartTime']."</td>";
						if($fullschd['DayID']=='Wed')
							echo"<td id = ".$ThisEID."WedSt>".$fullschd['StartTime']."</td>";
						if($fullschd['DayID']=='Thu')
							echo"<td id = ".$ThisEID."ThuSt>" .$fullschd['StartTime']." </td>";
						if($fullschd['DayID']=='Fri')
							echo"<td id = ".$ThisEID."FriSt>".$fullschd['StartTime']." </td>";
						if($fullschd['DayID']=='Sat')
							echo"<td id = ".$ThisEID.">".$fullschd['StartTime']." </td>";
					}
			echo"</tr>
			<td>End Time</td>";
			$quick=mysqli_query($link,$schedule);
			while($fullschd=$quick->fetch_assoc())
					{
		
						if($fullschd['DayID']=='Sun')
							echo"<td id = ".$ThisEID."SunEd>".$fullschd['EndTime']."</td>";
						if($fullschd['DayID']=='Mon')
							echo"<td id = ".$ThisEID."MonEd>".$fullschd['EndTime']."</td>";
						if($fullschd['DayID']=='Tue')
							echo"<td id = ".$ThisEID."TuesEd>".$fullschd['EndTime']."</td>";
						if($fullschd['DayID']=='Wed')
							echo"<td id = ".$ThisEID."WedEd>".$fullschd['EndTime']."</td>";
						if($fullschd['DayID']=='Thu')
							echo"<td id = ".$ThisEID."ThuEd>".$fullschd['EndTime']."</td>";
						if($fullschd['DayID']=='Fri')
							echo"<td id = ".$ThisEID."FriEd>".$fullschd['EndTime']."</td>";
						if($fullschd['DayID']=='Sat')
							echo"<td id = ".$ThisEID."SatEd>".$fullschd['EndTime']."</td>";
					}
			echo"</tr>
				</table>";
?>
    </body>
</html>

