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
             <li class="nav-item active"><a class="nav-link" href="empList_View.php">Schedule</a></li>
			<li class="nav-item active"><a class="nav-link" href="emploc_View.php">Locations</a></li>
			<li class="nav-item active"><a class="nav-link a2" href="login.php">Log Out</a></li>
        </nav>
        <div class="jumbotron text-center jumbotron2">
		
<?php
    include('connect.php');   
	session_start();
	if(isset($_SESSION['emp']) && $_SESSION['emp']===true)
	{
		
		$name="SELECT firstname, lastname
			   FROM employee
			   WHERE employeeID=".$_SESSION['username'];
		$name=$link->query($name);
			   $row = $name->fetch_assoc();
		echo"<h1 class='font-weight-bold text-center'>Engineer Locations View</h1>";
		echo" <h1 class='font-weight-bold text-center'>Welcome Back ".$row['firstname']." ".$row['lastname']."</h1>";
 
 echo"           <img class = 'img1'  src = 'pepsi.png'> 
            <hr class = 'hr1'>
        </div>
            <div>
                <h1 class='h1_1'>Displaying All Locations</h1>
            </div>";
		$LocationName="SELECT LocationName, LocationID FROM location"; //no user input 
		$result=$link->query($LocationName);
	echo" 
				<div class='row div_space'> 
					<div class='col-md-1'> </div>
					<div class='col-md-7'> 
						<form method='POST' action='empfilterlocation.php' class = 'form2'>
							<label>Search for a location:</label>
							<input type='text' name='filterl' placeholder='Location Name' required>
							<button class='btn btn-primary' type='submit'>Filter</button>
						</form>
					</div>
		";
	echo'
		<div class="col-md-9"> </div>
		  <div class="col-md-3">
			 
		  </div>

		';
		mysqli_close($link);
	}
	
	else
	{
		header("location:login.php?loc_no_emp_tok");
		exit;
	}
		
echo "</div>";


	include('connect.php');
	$sqlL="SELECT LocationName, StreetAdress, State, Zip FROM location";
	$result=$link->query($sqlL);



	echo'<div class="row tbl_space">';

    echo "<div class='col-md-1'> </div>";
        echo "<div class = 'col-md-10'>";
          echo " <table> ";
          echo "<tr>
              <th>Location Name</th>
              <th>Address</th>
              <th>State</th>
              <th>Zip</th>
              </tr>";
          while($row = $result->fetch_assoc()) 
          {
            echo "<tr>
              <td>".$row["LocationName"]. "</td>
              <td>". $row["StreetAdress"]. "</td>
              <td>" . $row["State"]."</td>
              <td>" . $row["Zip"]."</td>
            </tr>";
          }
        echo"</table>
      </div>
      <div class='col-md-1'> </div>
  </div>
  </div>";
	mysqli_close($link);
?>

        
        
            
    </body>
</html>