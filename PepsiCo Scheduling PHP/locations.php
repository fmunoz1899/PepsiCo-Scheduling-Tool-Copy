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
              <li class="nav-item active"><a class="nav-link" href="List_View.php">Schedule</a></li>
              <li class="nav-item active"><a class="nav-link a2" href="employees.html">Employees</a></li>
              <li class="nav-item active"><a class="nav-link a2" href="location.php">Locations</a></li>
  
        </nav>
        <div class="jumbotron text-center jumbotron2">
            <h1 class="font-weight-bold text-center">Workorder List View</h1>
            <img class = "img1"  src = "pepsi.png"> 
            <hr class = "hr1">
        </div>
            <div>
                <h1 class="h1_1">Displaying All Locations</h1>
            </div>
<?php
    include('connect.php');   
	session_start();
		
	$LocationName="SELECT LocationName, LocationID FROM location";
	$result=$link->query($LocationName);
echo" 
            <div class='row div_space'> 
                <div class='col-md-1'> </div>
                <div class='col-md-4'> 
                    <label>Select location to filter results:</label> <!-- how exactly are we going to do the the filter for the locations -->
                    <select name='locations' id='lid'>
						<option>--Select one--</option>";
                        while($row=$result->fetch_assoc()) 
							echo '<option value="'.$row["LocationID"].'">'.$row["LocationName"].'</option>';
echo"
                    </select>
                </div>
	";
echo'
	<div class="col-md-9"> </div>
      <div class="col-md-3">
          <button type="button" class="btn btn-primary mbut" data-toggle="modal" data-target="#newLoc"> Create New Location</button>
      </div>

	';
	mysqli_close($link);
 ?>              
      <!--------------- Modal Code -------------->
<?php
	include('connect.php');
	
	if(isset($_POST['LocationName']) && isset($_POST['Address']) && isset($_POST['state']) && isset($_POST['ZipCode']) && isset($_POST['city']) && $_POST['state']!='stop')
	{
		$cleanloc=filter_var($_POST['LocationName'], FILTER_SANITIZE_STRING);
		$cleanadd=filter_var($_POST['Address'], FILTER_SANITIZE_STRING);
		$cleanstate=filter_var($_POST['state'], FILTER_SANITIZE_STRING);
		$cleanzip=filter_var($_POST['ZipCode'], FILTER_SANITIZE_NUMBER_INT);
		$cleancity=filter_var($_POST['city'], FILTER_SANITIZE_STRING);
		
		if($cleanloc==$_POST['LocationName'] && $cleanadd==$_POST['Address'] && $cleanstate==$_POST['state'] && $cleanzip==$_POST['ZipCode'] && $cleancity==$_POST['city'])
		{
			$insert=$link->prepare("INSERT into location values(DEFAULT,?,?,?,?,?)");
			$insert->bind_param("ssssi",$cleanloc,$cleanadd,$cleancity,$cleanstate,$cleanzip);
			$insert->execute();
		}
		
		else
		{
			echo"<script>alert('make sure you enter appropriate characters try again');</script>";
		}
	}

?>
      <div class="modal fade" id="newLoc" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Create New Location</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form action="location.php" method="POST">
                <p>Location Name: 
                 <input type="text" name="LocationName" required>
                </p>
                <p>Address: 
                 <input type="text" name="Address" required> <!-- ?make it so there are no 2 addresses the same? -->
                </p>
				<p>City:
				<input type="text" name="city" required>
				</p>
                <p>State: 
                <select name="state" id="state">  <!--insert JS to make sure that --Select One-- is not submitted-->
				  <option value="none" selected>--Select One--</option>
				  <option value="AL">Alabama</option>
				  <option value="AK">Alaska</option>
				  <option value="AZ">Arizona</option>
				  <option value="AR">Arkansas</option>
				  <option value="CA">California</option>
				  <option value="CO">Colorado</option>
				  <option value="CT">Connecticut</option>
				  <option value="DE">Delaware</option>
				  <option value="DC">District Of Columbia</option>
				  <option value="FL">Florida</option>
				  <option value="GA">Georgia</option>
				  <option value="HI">Hawaii</option>
				  <option value="ID">Idaho</option>
				  <option value="IL">Illinois</option>
				  <option value="IN">Indiana</option>
				  <option value="IA">Iowa</option>
				  <option value="KS">Kansas</option>
				  <option value="KY">Kentucky</option>
				  <option value="LA">Louisiana</option>
				  <option value="ME">Maine</option>
				  <option value="MD">Maryland</option>
				  <option value="MA">Massachusetts</option>
				  <option value="MI">Michigan</option>
				  <option value="MN">Minnesota</option>
				  <option value="MS">Mississippi</option>
				  <option value="MO">Missouri</option>
				  <option value="MT">Montana</option>
				  <option value="NE">Nebraska</option>
				  <option value="NV">Nevada</option>
				  <option value="NH">New Hampshire</option>
				  <option value="NJ">New Jersey</option>
				  <option value="NM">New Mexico</option>
				  <option value="NY">New York</option>
				  <option value="NC">North Carolina</option>
				  <option value="ND">North Dakota</option>
				  <option value="OH">Ohio</option>
				  <option value="OK">Oklahoma</option>
				  <option value="OR">Oregon</option>
				  <option value="PA">Pennsylvania</option>
				  <option value="RI">Rhode Island</option>
				  <option value="SC">South Carolina</option>
				  <option value="SD">South Dakota</option>
				  <option value="TN">Tennessee</option>
				  <option value="TX">Texas</option>
				  <option value="UT">Utah</option>
				  <option value="VT">Vermont</option>
				  <option value="VA">Virginia</option>
				  <option value="WA">Washington</option>
				  <option value="WV">West Virginia</option>
				  <option value="WI">Wisconsin</option>
				  <option value="WY">Wyoming</option>
				</select>
                </p>
                <p>Zip Code: 
                  <input type="text" name="ZipCode" minlength="5" maxlength="5" required> <!-- make it so it is just 5 numbers -->
                </p>
				<button type="submit" name="submit2" id="submit2" class="submit2"  disabled>Enter</button> 
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
              
            </div>
          </div>
        </div>
      </div>
    </div>      
    
      <!------------------------------------->
<?php
	include('connect.php');
	$sqlL="SELECT LocationName, StreetAdress, State, Zip FROM location";
	$result=$link->query($sqlL);



	echo'<div class="row tbl_space">';

    echo "<div class='col-md-1'> </div>";
        echo "<div class = 'col-md-10'>";
          echo " <table> ";
          echo "<tr>
              <th> Location Name </th>
              <th> Address </th>
              <th> State </th>
              <th> Zip </th>
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
