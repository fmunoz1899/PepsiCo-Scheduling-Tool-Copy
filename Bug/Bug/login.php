<?php 
	include('connect.php');

	session_start();
	$_SESSION = array();
	session_destroy();
	
	//date_default_timezone_set("America/New_York");   <-- used to set the time zone
	
	/*if(isset($_SESSION["entered"]) && $_SESSION["entered"] === true) 
    {
		header("location: login.php");
		exit;
	}*/ //not sure what this is for even tho i wrote it 

	if(isset($_GET['st'])) //is ok to be get as there is no importance to this number
		echo"<script>alert('You have been inactive for 5 minutes');</script>";
	
	$Email="";
	$Epassword="";
	
	if (isset($_POST['Email']) && isset($_POST['Epassword']))
	{
		$emailcheck= filter_var($_POST['Email'], FILTER_SANITIZE_EMAIL);
		$passwordcheck= filter_var($_POST['Epassword'], FILTER_SANITIZE_STRING);
		$typing="Work";
		
		$result = $link->prepare("SELECT employeeprivlege.PrivilegeID, employeeprivlege.EmployeeID, email.type FROM employeeprivlege, email, employee WHERE Email.type=? and Email.Email=? and email.EmployeeID= employeeprivlege.EmployeeID and email.EmployeeID=employee.EmployeeID and employee.Epassword=?") ;
		$result->bind_param("sss",$typing,$emailcheck,$passwordcheck);
	
		if($result->execute())
		{
			$final= $result->get_result();
            $rowcount=mysqli_num_rows($final); 
			if($rowcount>0)
			{
				$row = $final->fetch_assoc();
                session_start();
                $_SESSION["entered"]= true;
				$_SESSION['last']=$_SERVER['REQUEST_TIME'];
                //$_SESSION['username']= $row['EmployeeID']; //session that has the employee ID not sure if really needed
				
		
                if($row['PrivilegeID']=='A')
				{
					$_SESSION['admin']=true;
					header("location:AdminLanding.php"); 
				}						
				
				else if($row['PrivilegeID']=='M')
				{
					$_SESSION['manager']=true;
					header("location:list_view.php");
				}
				
                else
				{	
					$_SESSION['emp']=true;
					$_SESSION['username']= $row['EmployeeID']; //session that has the employee ID not sure if really needed
                    header("location:empList_View.php"); 
				}
					
            }
			
            else
				echo "<script>alert('The username and/or password is incorrect')</script>";
		}
		mysqli_close($link);
	}

?>
<!DOCTYPE HTML>
<html> 
<head>
    <title>Pepsi Scheduling Tool</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-show-password/1.0.3/bootstrap-show-password.min.js"></script>
    <link rel='stylesheet' type='text/css' href='CSS/pepsi_styles.css'>
    <script language='Javascript' type='text/javascript' src='JavaScript/doStuff.js'></script>
    <script>
        $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();   
        });               
    </script>
    </head>

    <body>

            <div class="jumbotron text-center jumbotronImg">
                <h1 class="font-weight-bold text-center">PepsiCo. Scheduling Tool</h1>
                <img class = "img1"  src = "pepsi.png"> 
                <hr class = "hr1">
            </div>

            <div>
                <h1 class="h1_1">Account Log In</h1>
            </div>
            
            <form action = "login.php" method = "POST">
                <div class="form-group">
                    <label>Account ID</label>
                    <input type="Text" class="form-control" name="Email" placeholder="Email" required>
                </div>
                         
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" id = "pwd" class="form-control" name="Epassword" placeholder="Password" required data-toggle="password">
                </div>     
                   
                    <button type="submit" name="submit" class="btn btn-primary">Log In</button>           
            </form>
            <div id="err" class = "div1 text-danger"></div>
                <!--<a href = "List_View.php"><button class="button2">TEMP TO GET TO PAGE</button></a> -->
                <!--<a href = "AdminLanding.html"><button class="button2">TEMP TO GET TO ADMIN</button></a> use log in to get to admin -->
       
    </body>
</html>
