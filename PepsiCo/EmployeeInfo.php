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
				<li class="nav-item active"><a class="nav-link a2" href="adminloc.php">Locations</a></li>
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
			
			$time= $_SERVER['REQUEST_TIME'];
			$timeout=300; 
	
			if(isset($_SESSION['last'])===true && ($time-$_SESSION['last'])>$timeout)
			{
				header("location:login.php?st=".rand());
				exit;
			}
			
			$_SESSION['last']=$time;
			
			if(isset($_POST['empid']))
			{
				if($_POST['manid']=='na')
				{
					$newteam=$link->prepare("UPDATE team
											 SET managerid=default
											 WHERE employeeID=?");
					$newteam->bind_param("i",$_POST['empid']);
					$newteam->execute();
				}
				else
				{
					$newteam=$link->prepare("UPDATE team
											 SET managerid=?
											 WHERE employeeID=?");
					$newteam->bind_param("ii",$_POST['manid'],$_POST['empid']);
					$newteam->execute();
				}
			}
			
			if(isset($_POST['addemail']) && isset($_POST['EmpIDForEM']))
			{
				$cleanemail=filter_var($_POST['addemail'],FILTER_SANITIZE_EMAIL);
				
				$emailcheck=$link->prepare("SELECT email FROM email WHERE email=? and type=?");
				$typing="Work";
				$emailcheck->bind_param("ss",$cleanemail,$typing);
				$emailcheck->execute();
				$final= $emailcheck->get_result();
				$rowcount=mysqli_num_rows($final); //check if the work number is already in use
					
				if($rowcount==0)
				{
					$upem=$link->prepare("UPDATE email
										   SET email=?,
									       type='Personal'
									       WHERE employeeID=?
									       AND email=?");
					$upem->bind_param("sis",$cleanemail,$_POST['EmpIDForEM'],$_POST['hidem']);
					$upem->execute();
				}
				
				else
					echo"<script>alert('That is a current work email in use');</script>";
			}
			
			if(isset($_POST['addnum']) && isset($_POST['EmpIDForPN'])) 
			{				
				$cleannum=filter_var($_POST['addnum'],FILTER_SANITIZE_NUMBER_INT);
				
				$numcheck=$link->prepare("SELECT phoneNumber FROM phone WHERE phoneNumber=? and type=?");
				$typing="Work";
				$numcheck->bind_param("ss",$cleannum,$typing);
				$numcheck->execute();
				$final= $numcheck->get_result();
				$rowcount=mysqli_num_rows($final); //check if the work number is already in use
					
				if($rowcount==0)
				{ 
					$upnum=$link->prepare("UPDATE phone
										   SET phonenumber=?,
									       type=?
									       WHERE employeeID=?
									       AND phonenumber=?");
					$upnum->bind_param("ssis",$cleannum,$_POST['type'],$_POST['EmpIDForPN'],$_POST['hidnum']);
					$upnum->execute();
				}
					
					else
						echo"<script>alert('That is a current work number in use');</script>";
				
				
			}
			
			
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
				$dispname=$link->prepare("SELECT firstName, lastName, employee.employeeID, managerid
										  FROM email, employee, team 
										  WHERE email.email=? 
										  AND email.employeeID=employee.employeeID
										  AND team.employeeid=employee.employeeid");
				$dispname->bind_param("s",$ID);
				$dispname->execute();
				$final= $dispname->get_result();
				$row = $final->fetch_assoc();
				$empID2=$row['employeeID'];

		echo"
				<div class=''><h1 class='h1_1'>" . $row['firstName'] . " " . $row['lastName'] . "</h1></div>";
				if($row['managerid']!='')
				{
					$name1=$link->prepare("SELECT firstname, lastname
												   FROM employee
												   WHERE employeeID=?");
							$name1->bind_param("i",$row['managerid']);
							$name1->execute();
							$temp1=$name1->get_result();
							$row2 = mysqli_fetch_array($temp1);
							echo"<label>Managed By: ".$row2[0]." ".$row2[1]."</label>";
				}
				else
					echo"<label>Managed By: N/A</label>";
				echo"<br>";
			}

			
			else
			{
				header("location:adminlanding.php?empinfo_no_transfer");
				exit;
			}
			
?>
				<form method="POST" action="changePass.php">
				<?php
				$clean=filter_var(htmlentities($_POST['transfer'],  ENT_QUOTES,  'utf-8'),FILTER_SANITIZE_EMAIL);
echo"			<input type='hidden' value='".$clean."' name='transfer'> ";
				if(isset($_POST['manid2']))
					echo"<input type='hidden' name='manid2' value='". htmlentities($_POST['manid2'])."'>";
				else if(isset($_POST['manid']))
					echo"<input type='hidden' name='manid' value='". htmlentities($_POST['manid'])."'>";
					
?>
				<button class='btn btn-primary' type='submit'>Change Password</button>
				</form> 
				<br>
				<form method="POST" action="AdminLanding.php">
				<?php
				$clean=filter_var(htmlentities($_POST['transfer'],  ENT_QUOTES,  'utf-8'),FILTER_SANITIZE_EMAIL);
echo"			<input type='hidden' value='".$clean."' name='transfer'> ";
				if(isset($_POST['manid2']))
					echo"<input type='hidden' name='manid2' value='". htmlentities($_POST['manid2'])."'>";
				else if(isset($_POST['manid']))
					echo"<input type='hidden' name='manid' value='". htmlentities($_POST['manid'])."'>";
				?>
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
		
		if(isset($_POST['SunS']) && isset($_POST['SunE']) && isset($_POST['monS']) && isset($_POST['monE']) && isset($_POST['TueS']) && isset($_POST['TueE']) && isset($_POST['WedS']) && isset($_POST['WedE']) && isset($_POST['ThuS']) && isset($_POST['ThuE']) && isset($_POST['FriS']) && isset($_POST['FriE']) && isset($_POST['SatS']) && isset($_POST['SatE']))
		{
			$SunS=date("H:i:s", strtotime($_POST['SunS']));
			$SunE=date("H:i:s", strtotime($_POST['SunE']));
			$monS=date("H:i:s", strtotime($_POST['monS']));
			$monE=date("H:i:s", strtotime($_POST['monE']));
			$TueS=date("H:i:s", strtotime($_POST['TueS']));
			$TueE=date("H:i:s", strtotime($_POST['TueE']));
			$WedS=date("H:i:s", strtotime($_POST['WedS']));
			$WedE=date("H:i:s", strtotime($_POST['WedE']));
			$ThuS=date("H:i:s", strtotime($_POST['ThuS']));
			$ThuE=date("H:i:s", strtotime($_POST['ThuE']));
			$FriS=date("H:i:s", strtotime($_POST['FriS']));
			$FriE=date("H:i:s", strtotime($_POST['FriE']));
			$SatS=date("H:i:s", strtotime($_POST['SatS']));
			$SatE=date("H:i:s", strtotime($_POST['SatE']));
			
			$flag=false;
			if(($SunS=='00:00:00' && $SunE!='00:00:00') || ($SunE=='00:00:00' && $SunS!='00:00:00'))
			{
				echo"<script>alert('Both start and end must be off on Sunday');</script>";
				$flag=true;
			}
			else if($SunS>=$SunE && $SunS!='00:00:00')
			{
				echo"<script>alert('The start time cannot be the same or after the end time on Sunday');</script>";
				$flag=true;
			}
			
			if(($monS=='00:00:00' && $monE!='00:00:00') || ($monE=='00:00:00' && $monS!='00:00:00'))
			{
				echo"<script>alert('Both start and end must be off on Monday');</script>";
				$flag=true;
			}
			else if($monS>=$monE && $monS!='00:00:00')
			{
				echo"<script>alert('The start time cannot be the same or after the end time on Monday');</script>";
				$flag=true;
			}
			
			if(($TueS=='00:00:00' && $TueE!='00:00:00') || ($TueE=='00:00:00' && $TueS!='00:00:00'))
			{
				echo"<script>alert('Both start and end must be off on Tuesday');</script>";
				$flag=true;
			}
			else if($TueS>=$TueE && $TueS!='00:00:00')
			{
				echo"<script>alert('The start time cannot be the same or after the end time on Tuesday');</script>";
				$flag=true;
			}
			
			if(($WedS=='00:00:00' && $WedE!='00:00:00') || ($WedE=='00:00:00' && $WedS!='00:00:00'))
			{
				echo"<script>alert('Both start and end must be off on Wednesday');</script>";
				$flag=true;
			}
			else if($WedS>=$WedE && $WedS!='00:00:00')
			{
				echo"<script>alert('The start time cannot be the same or after the end time on Wednesday');</script>";
				$flag=true;
			}
			
			if(($ThuS=='00:00:00' && $ThuE!='00:00:00') || ($ThuE=='00:00:00' && $ThuS!='00:00:00'))
			{
				echo"<script>alert('Both start and end must be off on Thursday');</script>";
				$flag=true;
			}
			else if($ThuS>=$ThuE && $ThuS!='00:00:00')
			{
				echo"<script>alert('The start time cannot be the same or after the end time on Thursday');</script>";
				$flag=true;
			}
			
			if(($FriS=='00:00:00' && $FriE!='00:00:00') || ($FriE=='00:00:00' && $FriS!='00:00:00'))
			{
				echo"<script>alert('Both start and end must be off on Friday');</script>";
				$flag=true;
			}
			else if($FriS>=$FriE && $FriS!='00:00:00')
			{
				echo"<script>alert('The start time cannot be the same or after the end time on Friday');</script>";
				$flag=true;
			}
			
			if(($SatS=='00:00:00' && $SatE!='00:00:00') || ($SatE=='00:00:00' && $SatS!='00:00:00'))
			{
				echo"<script>alert('Both start and end must be off on Saturday');</script>";
				$flag=true;
			}
			else if($SatS>=$SatE && $SatS!='00:00:00')
			{
				echo"<script>alert('The start time cannot be the same or after the end time on Saturday');</script>";
				$flag=true;
			}
			
			if(!$flag)
			{
				
				if($SunS=='00:00:00')
				{
					$sunday=$link->prepare("UPDATE ahours	
											SET starttime=default,
											endtime=default
											WHERE employeeid=?
											AND dayid='Sun'");
					$sunday->bind_param("i",$_POST['EmpID']);
					$sunday->execute();
				}
				else
				{
					$sunday=$link->prepare("UPDATE ahours	
											SET starttime=?,
											endtime=?
											WHERE employeeid=?
											AND dayid='Sun'");
					$sunday->bind_param("ssi",$SunS,$SunE,$_POST['EmpID']);
					$sunday->execute();
				}
				
				
				if($monS=='00:00:00')
				{
					$monday=$link->prepare("UPDATE ahours	
											SET starttime=default,
											endtime=default
											WHERE employeeid=?
											AND dayid='Mon'");
					$monday->bind_param("i",$_POST['EmpID']);
					$monday->execute();
				}
				else
				{
					$monday=$link->prepare("UPDATE ahours	
											SET starttime=?,
											endtime=?
											WHERE employeeid=?
											AND dayid='Mon'");
					$monday->bind_param("ssi",$monS,$monE,$_POST['EmpID']);
					$monday->execute();
				}
				
				
				if($TueS=='00:00:00')
				{
					$tuesday=$link->prepare("UPDATE ahours	
											SET starttime=default,
											endtime=default
											WHERE employeeid=?
											AND dayid='Tue'");
					$tuesday->bind_param("i",$_POST['EmpID']);
					$tuesday->execute();
				}
				else
				{
					$tuesday=$link->prepare("UPDATE ahours	
											SET starttime=?,
											endtime=?
											WHERE employeeid=?
											AND dayid='Tue'");
					$tuesday->bind_param("ssi",$TueS,$TueE,$_POST['EmpID']);
					$tuesday->execute();
				}
				
				
				if($WedS=='00:00:00')
				{
					$wednesday=$link->prepare("UPDATE ahours	
											SET starttime=default,
											endtime=default
											WHERE employeeid=?
											AND dayid='Wed'");
					$wednesday->bind_param("i",$_POST['EmpID']);
					$wednesday->execute();
				}
				else
				{
					$wednesday=$link->prepare("UPDATE ahours	
											SET starttime=?,
											endtime=?
											WHERE employeeid=?
											AND dayid='Wed'");
					$wednesday->bind_param("ssi",$WedS,$WedE,$_POST['EmpID']);
					$wednesday->execute();
				}
				
				
				if($ThuS=='00:00:00')
				{
					$thursday=$link->prepare("UPDATE ahours	
											SET starttime=default,
											endtime=default
											WHERE employeeid=?
											AND dayid='Thu'");
					$thursday->bind_param("i",$_POST['EmpID']);
					$thursday->execute();
				}
				else
				{
					$thursday=$link->prepare("UPDATE ahours	
											SET starttime=?,
											endtime=?
											WHERE employeeid=?
											AND dayid='Thu'");
					$thursday->bind_param("ssi",$ThuS,$ThuE,$_POST['EmpID']);
					$thursday->execute();
				}
				
				
				if($FriS=='00:00:00')
				{
					$friday=$link->prepare("UPDATE ahours	
											SET starttime=default,
											endtime=default
											WHERE employeeid=?
											AND dayid='Fri'");
					$friday->bind_param("i",$_POST['EmpID']);
					$friday->execute();
				}
				else
				{
					$friday=$link->prepare("UPDATE ahours	
											SET starttime=?,
											endtime=?
											WHERE employeeid=?
											AND dayid='Fri'");
					$friday->bind_param("ssi",$FriS,$FriE,$_POST['EmpID']);
					$friday->execute();
				}
				
				if($SatS=='00:00:00')
				{
					$saturday=$link->prepare("UPDATE ahours	
											SET starttime=default,
											endtime=default
											WHERE employeeid=?
											AND dayid='Sat'");
					$saturday->bind_param("i",$_POST['EmpID']);
					$saturday->execute();
				}
				else
				{
					$saturday=$link->prepare("UPDATE ahours	
											SET starttime=?,
											endtime=?
											WHERE employeeid=?
											AND dayid='Sat'");
					$saturday->bind_param("ssi",$SatS,$SatE,$_POST['EmpID']);
					$saturday->execute();
				}
			}
		
		}
		if(((isset($_POST['addnum']) && $_POST['addnum']!='') || (isset($_POST['addemail']) && $_POST['addemail']!='')) && !isset($_POST['EmpIDForPN']) && !isset($_POST['EmpIDForEM']))
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
	  <!--------------- Modal Code For adding email or phone-------------->
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
				<?php
						if(isset($_POST['manid2']))
							echo"<input type='hidden' name='manid2' value='". htmlentities($_POST['manid2'])."'>";
						else if(isset($_POST['manid']))
							echo"<input type='hidden' name='manid' value='". htmlentities($_POST['manid'])."'>";
				?>
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
					<input  id = "EmpIDForEM" name='EmpIDForEM' type="hidden" required>
					<input type="hidden" id = "HiddenEm" class="form-control" name="hidem">
					<?php
						if(isset($_POST['manid2']))
							echo"<input type='hidden' name='manid2' value='". htmlentities($_POST['manid2'])."'>";
						else if(isset($_POST['manid']))
							echo"<input type='hidden' name='manid' value='". htmlentities($_POST['manid'])."'>";
					?>
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
				<input type="hidden" id = "EmpIDForPN2" name="EmpIDForPN" required>
			<input type="hidden" id = "HiddenNum" class="form-control" name="hidnum"> <!--this is the line to give the hidden original number-->
			<button type="submit" name="submit" class="btn btn-primary">Update</button> 
			<?php
						if(isset($_POST['manid2']))
							echo"<input type='hidden' name='manid2' value='". htmlentities($_POST['manid2'])."'>";
						else if(isset($_POST['manid']))
							echo"<input type='hidden' name='manid' value='". htmlentities($_POST['manid'])."'>";
			?>
			<?php echo"<input type='hidden' name='transfer' value='".filter_var(htmlentities($_POST['transfer'],  ENT_QUOTES,  'utf-8'),FILTER_SANITIZE_EMAIL)."'>";?>
			</form>
		</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
        </div>
    </div>
      <!------------------------------------->
	   <!--------------- Modal Code For Updating Sched -------------->
	   <!-- Insert day off or somethingt like that  -->

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
				<select name="SunS" required>
				<option  id = "SuS"  value='' selected>--Start--</option>
				<option value="12:00am">Off</option>
				<?php
					$time=date('06:00:00');
					while($time!='22:00:00') //time day ends 
					{
						echo"<option value='".$time."'>". date('g:iA',strtotime($time))."</option>";
						$time = date('H:i:s',strtotime('+15 minutes',strtotime($time)));
					}
				?>
				</select>
					<span class="input-group-addon">-</span>
					<select name="SunE" required>
					<option  id = "SuE" value='' selected>--End--</option>
					<option value="12:00am">Off</option>
					<?php
						$time=date('06:15:00');
						while($time!='22:15:00') //time day ends 
						{
							echo"<option value='".$time."'>". date('g:iA',strtotime($time))."</option>";
							$time = date('H:i:s',strtotime('+15 minutes',strtotime($time)));
						}
					?>
			</select>
				</div>
			<label> Monday </label></br>
				<div class="input-group">
					<select name='monS' required>
					<option   id = 'MS' value=" " selected>--Start--</option>
					<option value="12:00am">Off</option>
					<?php
					$time=date('06:00:00');
					while($time!='22:00:00') //time day ends 
					{
						echo"<option  value='".$time."'>". date('g:iA',strtotime($time))."</option>";
						$time = date('H:i:s',strtotime('+15 minutes',strtotime($time)));
					}
					?>
					</select>
					<span class="input-group-addon">-</span>
					<select name='monE' required>
					<option   id = 'ME' value='' selected>--End--</option>
					<option value="12:00am">Off</option>
						<?php
						$time=date('06:15:00');
						while($time!='22:15:00') //time day ends 
						{
							echo"<option  value='".$time."'>". date('g:iA',strtotime($time))."</option>";
							$time = date('H:i:s',strtotime('+15 minutes',strtotime($time)));
						}
						?>
					</select>
				</div>
				<label> Tuesday </label></br>
					<div class="input-group">
							<select name="TueS" required>
							<option  id = "TS" value='' selected>--Start--</option>
							<option value="12:00am">Off</option>
							<?php
								$time=date('06:00:00');
								while($time!='22:00:00') //time day ends 
								{
									echo"<option value='".$time."'>". date('g:iA',strtotime($time))."</option>";
									$time = date('H:i:s',strtotime('+15 minutes',strtotime($time)));
								}
							?>
							</select>
								<span class="input-group-addon">-</span>
								<select name="TueE" required>
							<option  id = "TE" value='' selected>--End--</option>
							<option value="12:00am">Off</option>
							<?php
								$time=date('06:15:00');
								while($time!='22:15:00') //time day ends 
								{
									echo"<option value='".$time."'>". date('g:iA',strtotime($time))."</option>";
									$time = date('H:i:s',strtotime('+15 minutes',strtotime($time)));
								}
							?>
						</select>
					</div>
					<label> Wednesday </label></br>
						<div class="input-group">
							<select name="WedS" required>
							<option  id = "WS" value='' selected>--Start--</option>
							<option value="12:00am">Off</option>
							<?php
								$time=date('06:00:00');
								while($time!='22:00:00') //time day ends 
								{
									echo"<option value='".$time."'>". date('g:iA',strtotime($time))."</option>";
									$time = date('H:i:s',strtotime('+15 minutes',strtotime($time)));
								}
							?>
							</select>
								<span class="input-group-addon">-</span>
								<select name="WedE" required>
								<option id = "WE" value='' selected>--End--</option>
								<option value="12:00am">Off</option>
									<?php
									$time=date('06:15:00');
									while($time!='22:15:00') //time day ends 
									{
										echo"<option value='".$time."'>". date('g:iA',strtotime($time))."</option>";
										$time = date('H:i:s',strtotime('+15 minutes',strtotime($time)));
									}
									?>
								</select>
					</div>
					<label> Thusday </label></br>
						<div class="input-group">
						<select name="ThuS" required>
							<option  id = "ThS" value='' selected>--Start--</option>
							<option value="12:00am">Off</option>
							<?php
								$time=date('06:00:00');
								while($time!='22:00:00') //time day ends 
								{
									echo"<option value='".$time."'>". date('g:iA',strtotime($time))."</option>";
									$time = date('H:i:s',strtotime('+15 minutes',strtotime($time)));
								}
							?>
						</select>
								<span class="input-group-addon">-</span>
								<select name="ThuE" required>
									<option id = "ThE" value='' selected>--End--</option>
									<option value="12:00am">Off</option>
									<?php
										$time=date('06:15:00');
										while($time!='22:15:00') //time day ends 
										{
											echo"<option value='".$time."'>". date('g:iA',strtotime($time))."</option>";
											$time = date('H:i:s',strtotime('+15 minutes',strtotime($time)));
										}
									?>
								</select>
					</div>
					<label> Friday </label></br>
						<div class="input-group">
						<select name="FriS" required>
							<option  id = "FS" value='' selected>--Start--</option>
							<option value="12:00am">Off</option>
							<?php
								$time=date('06:00:00');
								while($time!='22:00:00') //time day ends 
								{
									echo"<option value='".$time."'>". date('g:iA',strtotime($time))."</option>";
									$time = date('H:i:s',strtotime('+15 minutes',strtotime($time)));
								}
							?>
						</select>
								<span class="input-group-addon">-</span>
								<select name="FriE" required>
									<option  id = "FE" value='' selected>--End--</option>
									<option value="12:00am">Off</option>
									<?php
										$time=date('06:15:00');
										while($time!='22:15:00') //time day ends 
										{
											echo"<option value='".$time."'>". date('g:iA',strtotime($time))."</option>";
											$time = date('H:i:s',strtotime('+15 minutes',strtotime($time)));
										}
										?>
								</select>
					</div>
					<label> Saturday </label></br>
						<div class="input-group">
						<select name="SatS" required>
							<option  id = "SaS"  value='' selected>--Start--</option>
							<option value="12:00am">Off</option>
							<?php
							$time=date('06:00:00');
								while($time!='22:00:00') //time day ends 
								{
									echo"<option value='".$time."'>". date('g:iA',strtotime($time))."</option>";
									$time = date('H:i:s',strtotime('+15 minutes',strtotime($time)));
								}
							?>
						</select>
						<span class="input-group-addon">-</span>
						<select name="SatE" required>
							<option id = "SaE" value='' selected>--End--</option>
							<option value="12:00am">Off</option>
							<?php
								$time=date('06:15:00');
								while($time!='22:15:00') //time day ends 
								{
									echo"<option value='".$time."'>". date('g:iA',strtotime($time))."</option>";
									$time = date('H:i:s',strtotime('+15 minutes',strtotime($time)));
								}
							?>
						</select>
					</div>
					<?php
						if(isset($_POST['manid2']))
							echo"<input type='hidden' name='manid2' value='". htmlentities($_POST['manid2'])."'>";
						else if(isset($_POST['manid']))
							echo"<input type='hidden' name='manid' value='". htmlentities($_POST['manid'])."'>";
					?>
				<input type = "hidden" id = "EmpID" name="EmpID"  required>
				<br>
			<?php echo"<input type='hidden' name='transfer' value='". filter_var(htmlentities($_POST['transfer'],  ENT_QUOTES,  'utf-8'),FILTER_SANITIZE_EMAIL)."'>";?>
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
		$schedule="SELECT DayID, TIME_FORMAT(StartTime, '%l:%i%p') AS StartTime, TIME_FORMAT(EndTime, '%l:%i%p') AS EndTime, employeeID 
		FROM ahours 
		WHERE EmployeeID=".$empID2." ORDER BY CASE 
		when DayID='Sun' then 1 
		when DayID='Mon' then 2 
		when DayID='Tue' then 3 
		when DayID='Wed' then 4 
		when DayID='Thu' then 5 
		when DayID='Fri' then 6 
		when DayID='Sat' then 7 
		else 8 end asc";
		$quick=mysqli_query($link,$schedule);
		
		$forteam=$link->prepare("SELECT privilegeid, email.employeeID
				  FROM employeeprivlege, email
				  WHERE email.employeeID=employeeprivlege.employeeid
				  AND email=?");
		$forteam->bind_param("s",$ID);
		$forteam->execute();
		$temp=$forteam->get_result();
		$role=$temp->fetch_assoc();
 
		
//make edit pop up a modal with the info inserted already
echo"
        <div class='row tbl_space'> 
            <div class='col-md-2'>";
			
			if($role['privilegeid']=='E')
			{
				echo"
				<form method='POST' action='employeeinfo.php'>
				<div class='form-group'>
				<label>Team:</label>
				<input type='hidden' value=".$role['employeeID']." name='empid'>";
				
				echo"
				<input type='hidden' value='".$clean."' name='transfer'>";
			echo"
				<select name='manid'>
				<option value='na'>N/A</option>";
				
				$mans="SELECT firstname, lastname, employee.employeeid
					   FROM employee, employeeprivlege
					   WHERE employeeprivlege.employeeID=employee.employeeID
					   AND privilegeid='M'"; 
				$result=$link->query($mans);

						while($rowop = $result->fetch_assoc())
						{
							if(!isset($_POST['manid']))
							{
								if($_POST['manid2']==$rowop['employeeid'])
									echo"<option value=".$rowop['employeeid']." selected>".$rowop['firstname']." ".$rowop['lastname']."</option>";
								else 
									echo"<option value=".$rowop['employeeid'].">".$rowop['firstname']." ".$rowop['lastname']."</option>";
							}
							
							else
							{
								if($_POST['manid']==$rowop['employeeid'])
									echo"<option value=".$rowop['employeeid']." selected>".$rowop['firstname']." ".$rowop['lastname']."</option>";
								else 
									echo"<option value=".$rowop['employeeid'].">".$rowop['firstname']." ".$rowop['lastname']."</option>";
							}
						
						}
				
	echo"			</select>
				<br><br><button class='btn btn-primary'>Submit</button>
				</div>
				</form>";
			}
	echo"
			
			</div>
			
			
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
							$emEID = $emrow['employeeID'];
							echo"<tr>
							<td>
							<button id = 'UEm' class='btn btn-success EmailUpdate' type='button' data-toggle='modal' data-target='#UpdateEm' value='" . $emrow['email'] . "'>Edit</button></td>";
							echo"<td>
							<form method='POST' action='EmployeeInfo.php'> 
							<input name='transfer' type='hidden' value='" . $ID . "'> 
							<input name='transfer1' type='hidden' value='" . $emrow['email'] . "'> 
							<input name='transferID' id = 'eIDEmail' type='hidden' value='" . $emrow['employeeID'] . "'>";
							if(isset($_POST['manid2']))
								echo"<input type='hidden' name='manid2' value='". htmlentities($_POST['manid2'])."'>";
							else if(isset($_POST['manid']))
								echo"<input type='hidden' name='manid' value='". htmlentities($_POST['manid'])."'>";
							echo"<button class='btn btn-danger' type='submit'>Remove</button>
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
						$rowPnum = $numrow['employeeID'];
						if($numrow['type']!='Work')
						{
							echo"<tr>
							<td>
							<button id = 'UPnum' class='btn btn-success UpdateNnum'type='button' data-toggle='modal' data-target='#UpdatePhone' value='" . $numrow['phoneNumber'] ."'>Edit</button></td>";
							echo"<td>
							<form method='POST' action='EmployeeInfo.php'> 
							<input name='transfer' type='hidden' value='" . $ID . "'> 
							<input name='transfer2' type='hidden' value='" . $numrow['phoneNumber'] . "'> 
							<input name='transferID' id = 'eIDPhone' type='hidden' value='" . $numrow['employeeID'] . "'>";
							if(isset($_POST['manid2']))
								echo"<input type='hidden' name='manid2' value='". htmlentities($_POST['manid2'])."'>";
							else if(isset($_POST['manid']))
								echo"<input type='hidden' name='manid' value='". htmlentities($_POST['manid'])."'>";
							echo"<button class='btn btn-danger' type='submit'>Remove</button>
							</form>
							</td>";

						}
						else
						{
						   echo" <td></td>";
						   echo"<td></td>";
						}
						
	echo"                  <td>(" . substr($numrow['phoneNumber'],0,3) . ")-".substr($numrow['phoneNumber'],3,3)."-".substr($numrow['phoneNumber'],6,4) . "</td>
							<td>" . $numrow['type'] . "</td></tr>";
					}
echo"				</div>";

					
?>
				</table>

            </div>
            <div class="col-md-2">






			</div>

        </div>
<?php
	$ThisEID = $empID2;
	echo"<br>
	<div class='row tbl_space'> 
	<div class='col-md-1'> </div>
	<div class = 'col-md-10'>
	
	<table>
			<tr>
			<th><button class='btn btn-success EdSched' type='button' data-toggle='modal' data-target='#EditSched' value='" . $empID2 . "'>Edit</button></th>
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
						{
							if($fullschd['StartTime']!='')
								echo"<td id = ".$ThisEID."SunSt>".$fullschd['StartTime']." </td>";
							else
								echo"<td id = ".$ThisEID."SunSt>Off</td>";
						}
						if($fullschd['DayID']=='Mon')
						{
							if($fullschd['StartTime']!='')
								echo"<td id = ".$ThisEID."MonSt>".$fullschd['StartTime']." </td>";
							else
								echo"<td id = ".$ThisEID."MonSt>Off</td>";
						}
						if($fullschd['DayID']=='Tue')
						{
							if($fullschd['StartTime']!='')
								echo"<td id = ".$ThisEID."TueSt>".$fullschd['StartTime']."</td>";
							else
								echo"<td id = ".$ThisEID."TueSt>Off</td>";
						}
						if($fullschd['DayID']=='Wed')
						{
							if($fullschd['StartTime']!='')
								echo"<td id = ".$ThisEID."WedSt>".$fullschd['StartTime']."</td>";
							else
								echo"<td id = ".$ThisEID."WedSt>Off</td>";
						}
						if($fullschd['DayID']=='Thu')
						{
							if($fullschd['StartTime']!='')
								echo"<td id = ".$ThisEID."ThuSt>" .$fullschd['StartTime']." </td>";
							else
								echo"<td id = ".$ThisEID."ThuSt>Off</td>";
							
						}
						if($fullschd['DayID']=='Fri')
						{
							if($fullschd['StartTime']!='')
								echo"<td id = ".$ThisEID."FriSt>".$fullschd['StartTime']." </td>";
							else
								echo"<td id = ".$ThisEID."FriSt>Off</td>";
						}
						if($fullschd['DayID']=='Sat')
						{
							if($fullschd['StartTime']!='')
								echo"<td id = ".$ThisEID."SatSt>".$fullschd['StartTime']." </td>";
							else
								echo"<td id = ".$ThisEID."SatSt>Off</td>";
						}
					}
			echo"</tr>
			<td>End Time</td>";
			$quick=mysqli_query($link,$schedule);
			while($fullschd=$quick->fetch_assoc())
					{
						if($fullschd['DayID']=='Sun')
						{
							if($fullschd['EndTime']!='')
								echo"<td id = ".$ThisEID."SunEd>".$fullschd['EndTime']."</td>";
							else
								echo"<td id = ".$ThisEID."SunEd>Off</td>";								
						}
						if($fullschd['DayID']=='Mon')
						{
							if($fullschd['EndTime']!='')
								echo"<td id = ".$ThisEID."MonEd>".$fullschd['EndTime']."</td>";
							else
								echo"<td id = ".$ThisEID."MonEd>Off</td>";
						}
						if($fullschd['DayID']=='Tue')
						{
							if($fullschd['EndTime']!='')
								echo"<td id = ".$ThisEID."TuesEd>".$fullschd['EndTime']."</td>";
							else
								echo"<td id = ".$ThisEID."TuesEd>Off</td>";
						}
						if($fullschd['DayID']=='Wed')
						{
							if($fullschd['EndTime']!='')
								echo"<td id = ".$ThisEID."WedEd>".$fullschd['EndTime']."</td>";
							else
								echo"<td id = ".$ThisEID."WedEd>Off</td>";
						}
						if($fullschd['DayID']=='Thu')
						{
							if($fullschd['EndTime']!='')
								echo"<td id = ".$ThisEID."ThuEd>".$fullschd['EndTime']."</td>";
							else
								echo"<td id = ".$ThisEID."ThuEd>Off</td>";
						}
						if($fullschd['DayID']=='Fri')
						{
							if($fullschd['EndTime']!='')
								echo"<td id = ".$ThisEID."FriEd>".$fullschd['EndTime']."</td>";
							else
								echo"<td id = ".$ThisEID."FriEd>Off</td>";
						}
						if($fullschd['DayID']=='Sat')
						{
							if($fullschd['EndTime']!='')
								echo"<td id = ".$ThisEID."SatEd>".$fullschd['EndTime']."</td>";
							else
								echo"<td id = ".$ThisEID."SatEd>Off</td>";
						}
					}
			echo"</tr>
				</table>";
?>
    </body>
</html>
