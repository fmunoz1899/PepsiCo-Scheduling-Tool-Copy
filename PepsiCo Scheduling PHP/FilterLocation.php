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
        <script language='Javascript' type='text/javascript' src='JS/functionality.js'></script> 
        <script>
                $(document).ready(function(){
                  $('[data-toggle="tooltip"]').tooltip();   
                });               
        </script>
    </head>

    <body>
        <nav class="navbar navbar-expand-sm fixed-top nav">
            <ul class="navbar-nav">	
              <li class="nav-item active"><a class="nav-link" href="List_View.php">Schedule</a></li>
              <li class="nav-item active"><a class="nav-link a2" href="employees.php">Employees</a></li>
              <li class="nav-item active"><a class="nav-link a2" href="locations.php">Locations</a></li>
			  <li class="nav-item active"><a class="nav-link a2" href="login.php">Log Out</a></li>
        </nav>


            <div class="jumbotron text-center jumbotron2">
                <h1 class="font-weight-bold text-center">Filtered Locations</h1>
                <img class = "img1"  src = "pepsi.png"> 
                <hr class = "hr1">
            </div>

            <div>
<?php
		include('connect.php');
		session_start();
			/*$time= $_SERVER['REQUEST_TIME'];
			$timeout=5;
		
				if(isset($_SESSION['last'])===true && ($time-$_SESSION['last'])>$timeout)
				{
					header("location:login.php?st=".rand());
					exit;
				}
				
			$_SESSION['last']=$time;*/  
		
		if(isset($_SESSION['manager']) && $_SESSION['manager']===true)
		{
			
			if((isset($_POST['filterl'])))
			{
				$cleanloc=filter_var(htmlentities($_POST['filterl'],  ENT_QUOTES,  'utf-8'),FILTER_SANITIZE_STRING);
				
	echo"		<h1 class='h1_1'>Filtered Results For: " . $cleanloc . "</h1></div>";

			}
			
			else
			{
				header("location:locations.php?filter_no_transfer");
				exit;
			}
		}
		else
		{
			header("location:login.php?loc_no_man_tok");
			exit;
		}
	
?>
 
          </div>
          <div class="row "> 
            <div class="col-md-9"> </div>
                <div class="col-md-3">
                    <button type="button" class="btn btn-primary mbut" onclick="location.href='locations.php';">Go Back</button>
                </div>
            </div>
        </div>
<?php
include('connect.php');


$searchl=str_replace(' ','',filter_var(htmlentities($_POST['filterl'],  ENT_QUOTES,  'utf-8'),FILTER_SANITIZE_STRING));
$searchl="%".$searchl."%";
		$sqlL=$link->prepare("SELECT LocationName, StreetAdress, State, Zip
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
					
