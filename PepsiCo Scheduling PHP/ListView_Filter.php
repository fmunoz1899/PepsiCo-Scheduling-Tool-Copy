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
				<li class="nav-item active"><a class="nav-link a2" href="login.php">Log Out</a></li>
            </ul>
      </nav>


            <div class="jumbotron text-center jumbotron2">
                <h1 class="font-weight-bold text-center">Filtered Workorders</h1>
                <img class = "img1"  src = "pepsi.png"> 
                <hr class = "hr1">
            </div>

            
<?php
		session_start();
			/*$time= $_SERVER['REQUEST_TIME'];
			$timeout=5;
		
				if(isset($_SESSION['last'])===true && ($time-$_SESSION['last'])>$timeout)
				{
					header("location:login.php?st=".rand());
					exit;
				}
				
			$_SESSION['last']=$time;*/ 
		
		/*if(isset($_SESSION['admin']) && $_SESSION['admin']===true)
		{
			
		if((isset($_POST['filterfirst']) && $_POST['filterfirst']!='') || (isset($_POST['filterlast']) && $_POST['filterlast']!=''))
		{
			$cleanfirst=filter_var(htmlentities($_POST['filterfirst'],  ENT_QUOTES,  'utf-8'),FILTER_SANITIZE_STRING);
			$cleanlast=filter_var(htmlentities($_POST['filterlast'],  ENT_QUOTES,  'utf-8'),FILTER_SANITIZE_STRING);
			
echo"		<h1 class='h1_1'>Filtered Results For: " . $cleanfirst . " " . $cleanlast . " </h1></div>";
		}
		
		
		else
		{
			header("location:adminlanding.php?filter_no_transfer");
			exit;
		}
		}
		else
		{
			header("location:login.php?filter_no_admintok");
			exit;
		}
		*/
?>
 
          </div>
          <div class="row "> 
            <div class="col-md-9"> </div>
                <div class="col-md-3">
                    <button type="button" class="btn btn-primary mbut" onclick="location.href='List_View.php';">Go Back</button>
                </div>
            </div>
        </div>
<?php
	include('connect.php');
	$searchFirst=$_POST["filterfirst"];
	$searchLast=$_POST["filterlast"];
	$sqlE="SELECT firstName, lastName, workitem.ItemID, LocationName, Method, ActualEndTime, StartTime, EndTime, Date
			FROM workitem,employee,delivery,wi_schedule,location
			where workitem.EmployeeID=employee.EmployeeID and workitem.ItemID=wi_schedule.ItemID and
			workitem.LocationID=location.LocationID and workitem.DeliveryID=delivery.DeliveryID
            and firstName LIKE '%$searchFirst%' and lastName LIKE '%$searchLast%'";
	$result=$link->query($sqlE);
	if($result->num_rows > 0){
	echo"
        <div class='row tbl_space'> 
            <div class='col-md-1'> </div>
            <div class = 'col-md-10'>
			<table>
                    <tr>
                      <th>Employee Name</th>
                      <th>Workorder ID</th>
                      <th>Location</th>
                      <th>Delivery Method</th>
                      <th>Scheduled Hours</th>
					  <th>Start Time</th>
					  <th>End Time</th>
					  <th>Date</th>
                    </tr>";
		while($row = $result->fetch_assoc()) {
			echo "<tr>
					<td>".$row["firstName"]. $row["lastName"]. "</td>
					<td>". $row["ItemID"]. "</td>
					<td>" . $row["LocationName"]."</td>
					<td>" . $row["Method"]."</td>
					<td>" . $row["ActualEndTime"]."</td>
					<td>" . $row["StartTime"]."</td>
					<td>" . $row["EndTime"]."</td>
					<td>" . $row["Date"]."</td>
				</tr>";
		}
		} else{
			echo "0 results for \"" . $searchl . "\"";
		}
	mysqli_close($link);
					?>
				  



