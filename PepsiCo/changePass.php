<!DOCTYPE HTML>
<html> 
<head>
    <title>Pepsi Scheduling Tool</title>
<!-- will only work once we get empID -->
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

$time= $_SERVER['REQUEST_TIME'];
		$timeout=300;
	
			if(isset($_SESSION['last'])===true && ($time-$_SESSION['last'])>$timeout)
			{
				header("location:login.php?st=".rand());
				exit;
			}
			
		$_SESSION['last']=$time;

if(!isset($_SESSION['admin']) || $_SESSION['admin']===false)
{
	header('location:login.php?admin_no_tok');
	exit;
}


		if(isset($_POST['transfer']))
		{
			$ID=filter_var(htmlentities($_POST['transfer'],  ENT_QUOTES,  "utf-8"), FILTER_SANITIZE_EMAIL);
			if($ID==$_POST['transfer']) //has been sanitized
			{
				$dispname=$link->prepare("SELECT email.employeeID, firstName, lastName FROM email, employee WHERE email.email=? and email.employeeID=employee.employeeID");
				$dispname->bind_param("s",$ID);
				$dispname->execute();
				$final= $dispname->get_result();
				$row = $final->fetch_assoc();

				echo"
					 <h1 class='h1_1'>Change Password For " . $row['firstName']. " " . $row['lastName'] . "</h1></div>";
				   
				
				if(isset($_POST['pword']) && isset($_POST['pwordc']))
				{
					$cleanpword=filter_var($_POST['pword'],FILTER_SANITIZE_STRING);
					$cleancheck=filter_var($_POST['pwordc'],FILTER_SANITIZE_STRING);
					
					if($_POST['pword']!=$_POST['pwordc'])
						echo"<script>alert('Your passwords do not match');</script>";
					
					else if($cleanpword!=$_POST['pword'] && $cleancheck!=$_POST['pwordc'])
						echo"<script>alert('Invalid Characters');</script>";

					else if($_POST['pword']==$_POST['pwordc'] && $cleanpword==$_POST['pword'] && $cleancheck==$_POST['pwordc'])
					{
						$pass=hash('sha256' , $cleanpword);
						$update=$link->prepare("UPDATE employee SET epassword=? WHERE employee.employeeID=?");
						$update->bind_param("si",$pass, $row['employeeID']);
						$update->execute();
						echo"<script>alert('The password has been changed!'); window.location='AdminLanding.php';</script>";
					}
	
				}
			}
			else
				header("location:adminlanding?nope.php");
		
		}

			else
			{
				header("location:login.php?passchange_no_transfer");
				exit;
			}
			//(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*?[#?!@$%^&*-]);

$link->close();
?>

          <form action="changePass.php" method = "POST">
		 <?php echo"<input type='hidden' name='transfer' value='" . filter_var(htmlentities($_POST['transfer'],  ENT_QUOTES,  'utf-8'),FILTER_SANITIZE_EMAIL) . "'> ";?>
            <div class="form-group">
                <label>New Password</label>
                <input type="password" pattern="(?=^.{6,}$)((?=.*\d)(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$" title="Must contain at least 1 number, 1 uppercase, 1 lowercase letter, and 1 special character" class="form-control" name="pword" placeholder="Password" required>
            </div>   
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" pattern="(?=^.{6,}$)((?=.*\d)(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$" title="Must contain at least 1 number, 1 uppercase, 1 lowercase letter, and 1 special character" class="form-control" name="pwordc" placeholder="Password" required>
            </div>
			<div class = "row">
			<div class="col-md-3"></div>
			<div class="col-md-6">
				<button type="submit" name="submit" class="btn btn-primary">Confirm Password</button> 
			</div>  
			    <!-- Cancel button for password -->
			<div class="col-md-4">

		</div>
        </form>
		<br><br>
		<?php	echo"<form action='employeeinfo.php' method='POST'><input type='hidden' name='transfer' value=".$ID."></input><button type='submit'class='btn btn-primary btn-danger'>Go Back</button> </form>";?>

    </body>
</html>