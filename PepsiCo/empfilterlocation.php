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
        <script language='Javascript' type='text/javascript' src='Javascript/functionality.js'></script> 
        <script>
                $(document).ready(function(){
                  $('[data-toggle="tooltip"]').tooltip();   
                });               
        </script>
    </head>

    <body>
        <nav class="navbar navbar-expand-sm">
            <ul class="navbar-nav">	
			<li class="nav-item active"><a class="nav-link" href="empList_View.php">Schedule</a></li>
			<li class="nav-item active"><a class="nav-link" href="emploc_View.php">Locations</a></li>
			<li class="nav-item active"><a class="nav-link a2" href="login.php">Log Out</a></li>
        </nav>


           
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
		
		if(isset($_SESSION['emp']) && $_SESSION['emp']===true)
		{			
			if((isset($_POST['filterl'])))
			{
				
	echo"			 <div class='jumbotron text-center jumbotron2'>
                <h1 class='font-weight-bold text-center'>Filtered Locations</h1>";
				$name="SELECT firstname, lastname
			   FROM employee
			   WHERE employeeID=".$_SESSION['username'];
		$name=$link->query($name);
			   $row = $name->fetch_assoc();
		echo" <h1 class='font-weight-bold text-center'>Welcome Back ".$row['firstname']." ".$row['lastname']."</h1>";
        echo"        <img class = 'img1'  src = 'pepsi.png'> 
                <hr class = 'hr1'>
            </div>

            <div>";
				$cleanloc=filter_var(htmlentities($_POST['filterl'],  ENT_QUOTES,  'utf-8'),FILTER_SANITIZE_STRING);
				
	echo"		<h1 class='h1_1'>Filtered Results For: " . $cleanloc . "</h1></div>";

			}
			
			else
			{
				//echo "<script>alert('".$_POST['filter1']."');</script>";
				header("location:emploc_view.php?emplocfilter_no_transfer");
				exit;
			}
		}
		else
		{
			header("location:login.php?emploc_no_emp_tok");
			exit;
		}
	

echo" 
          </div>
          <div class='row '> 
            <div class='col-md-9'> </div>
                <div class='col-md-3'>
                    <a href='emploc_view.php'><button type='button' class='btn btn-primary mbut'>Go Back</button></a>
                </div>
            </div>
        </div>";
		


$searchl=str_replace(' ','',filter_var(htmlentities($_POST['filterl'],  ENT_QUOTES,  'utf-8'),FILTER_SANITIZE_STRING));
$searchl="%".$searchl."%";
		$sqlL=$link->prepare("SELECT LocationName, StreetAdress, State, Zip, LocationID
				FROM location 
                where locationName LIKE ?");
		$sqlL->bind_param("s",$searchl);
		$sqlL->execute();
		$result=$sqlL->get_result();
		
		echo mysqli_num_rows($result)." result(s)";
		if(mysqli_num_rows($result)>=0)
		{
echo"   <div class='row tbl_space'> 
            <div class='col-md-1'> </div>
            <div class = 'col-md-10'>
			<table>
                    <tr>
                      <th>Location</th>
                      <th>Street Address</th>
                      <th>State</th>
                      <th>Zip</th>
                    </tr>";
		while($row = $result->fetch_assoc()) {
				echo "<tr>
					<td>".$row["LocationName"]. "</td>
					<td>". $row["StreetAdress"]. "</td>
					<td>" . $row["State"]."</td>
					<td>" . $row["Zip"]."</td>
				</tr>";
			}
		}

	mysqli_close($link);
?>
					