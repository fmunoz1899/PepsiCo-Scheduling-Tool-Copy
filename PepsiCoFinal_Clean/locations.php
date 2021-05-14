<!DOCTYPE HTML>
<html> 
<head>
<?php
include('click.php');
?>
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
	
	<nav class="navbar navbar-expand-sm ">
          <ul class="navbar-nav">	
            <li class="nav-item active"><button type="button" class="btn btn-outline-primary navButtons" onclick ="window.location.href = 'List_View.php';">Schedule</button></li>
			<li class="nav-item active"><button type="button" class="btn btn-outline-primary navButtons" onclick ="window.location.href = 'employees.php';">Employees</button></li>
			<li class="nav-item active"><button type="button" class="btn btn-outline-primary navButtons" onclick ="window.location.href = 'locations.php';">Locations</button></li>
			<li class="nav-item active"><button type="button" class="btn btn-outline-primary navButtons" onclick ="window.location.href = 'transfer.php';">Transfer</button></li>

			</ul>
			<div class="navbar-nav ml-auto">
				<li class="nav-item active"><button type="button" class="btn btn-outline-danger" onclick ="window.location.href = 'login.php';" >Log Out</button></li>
			</div>
	</nav>
        <div class="jumbotron text-center jumbotron2">
            <h1 class="font-weight-bold text-center">Manager Locations</h1>
            <img class = "img1"  src = "pepsi.png"> 
            <hr class = "hr1">
        </div>
            <div>
                <h1 class="h1_1">Displaying All Locations</h1>
            </div>
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
	
	if(isset($_SESSION['manager']) && $_SESSION['manager']===true)
	{
		$LocationName="SELECT LocationName, LocationID FROM location"; //no user input 
		$result=$link->query($LocationName);
	echo" 
				<div class='row div_space'> 
					<div class='col-md-1'> </div>
					<div class='col-md-7'> 
						<form method='POST' action='filterlocation.php' class = 'form2'>
							<label>Search for a location:</label>
							<input type='text' name='filterl' placeholder='Location Name' required>
							<button class='btn btn-primary' type='submit'>Filter</button>
						</form>
					</div>
					
		";
	echo'
		<div class="col-md-9"> </div>
		  <div class="col-md-3">
			  <button type="button" class="btn btn-primary mbut" data-toggle="modal" data-target="#newLoc"> Create New Location</button>
		  </div>
		  </div>

		';
		mysqli_close($link);
	}
	
	else
	{
		header("location:login.php");
		exit;
	}
		
 ?>              
      <!--------------- Modal Code -------------->
<?php
	include('connect.php');
	
	if(isset($_POST['id']) && $_POST['id']!='')
	{
		
		$check=$link->prepare("SELECT locationID FROM workitem WHERE locationID=?");
			$check->bind_param("i",$_POST['id']);
			$check->execute();
			
			$final= $check->get_result();
            $rowcount=mysqli_num_rows($final);
		if($rowcount==0)
		{
			$id=filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
			$rem=$link->prepare("DELETE FROM location WHERE locationID=?");
			$rem->bind_param("i",$id);
			$rem->execute();
		}
		else
			echo "<script>alert('The location is currently in use!');</script>";
	}
	
	
	if(isset($_POST['LocationName']) && isset($_POST['Address']) && isset($_POST['city']) && isset($_POST['state']) && isset($_POST['ZipCode']) && isset($_POST['LID']))
	{
		$cleanloc=filter_var($_POST['LocationName'], FILTER_SANITIZE_STRING);
		$cleanadd=filter_var($_POST['Address'], FILTER_SANITIZE_STRING);
		$cleanstate=filter_var($_POST['state'], FILTER_SANITIZE_STRING);
		$cleanzip=filter_var($_POST['ZipCode'], FILTER_SANITIZE_NUMBER_INT);
		$cleancity=filter_var($_POST['city'], FILTER_SANITIZE_STRING);
		
		$update=$link->prepare("UPDATE location
								SET locationname=?,
								streetadress=?,
								city=?,
								state=?,
								zip=?
								WHERE locationid=?");
		$update->bind_param("ssssii",$cleanloc,$cleanadd,$cleancity,$cleanstate,$cleanzip,$_POST['LID']);
		$update->execute();
	}
		
	
	else if(isset($_POST['LocationName']) && isset($_POST['Address']) && isset($_POST['state']) && isset($_POST['ZipCode']) && isset($_POST['city']) && $_POST['state']!='')
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
            <div class="modal-body modalContent">
				<div class = "row">
				<div class = "col-md-4"></div>
				<div class = "col-md-8">
              <form action="locations.php" class = "form1" method="POST">
                 <div class="form-group">
				 <label>Location Name:</label>
                 <input type="text" name="LocationName" placeholder="Location Name" maxlength="45" required>
                </div>
                 <div class="form-group">
				 <label>Address:</label> 
                 <input type="text" name="Address" placeholder="Address" maxlength="45" required> 
                </div>
				 <div class="form-group">
				 <label>City:</label>
				<input type="text" name="city" placeholder="City" maxlength="45" required>
				</div>
                 <div class="form-group">
				 <label>State:</label> 
                <select name="state" id="state" required> 
				  <option value="" selected>--Select One--</option>
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
                </div>
                <div class="form-group">
				<label>Zip Code:</label>
                  <input type="text" name="ZipCode" minlength="5" maxlength="5" placeholder="Zip Code" required> 
                </div>
				 <button type="submit" name="submit" class="btn btn-primary">Enter</button> 
              </form>
          
            </div>
            </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
              
            </div>
          </div>
        </div>
      </div>     
    
      <!------------------------------------->
	   <!-- Update Loc Modal -->
	   <div class="modal fade" id="EditLocMgr" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Edit Location</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span> </button>
            </div>
            <div class="modal-body modalContent">
				<div class = "row">
				<div class = "col-md-4"></div>
				<div class = "col-md-8">
              <form class = "form1" action = "locations.php" method = "POST" >
              <div class="form-group">
			  <label> Location Name:</label>
                 	<input type="text" id = "LN" name="LocationName" maxlength="45" required>
              </div>
              <div class="form-group">
			  <label> Address:</label> 
                 	<input type="text" id = "add" name="Address" maxlength="45" required> 
              </div>
              <div class="form-group">
			  <label> City:</label><br>
					<input type="text" id = "cit" name="city" maxlength="45" required>
              </div>
              <div class="form-group">
			  <label> State:</label> <br>
                	<select name="state" id="stateEdit" required>  
				  <option value="" selected>--Select One--</option>
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
              </div>
              <div class="form-group">
			  		<label> Zip Code:</label>
                  <input type="text" id = "ZC" name="ZipCode" minlength="5" maxlength="5" required> 
              </div>
			  <input type = "hidden" id = "LID" name="LID" required>
                <button type="submit" name="submit" class="btn btn-primary subBut">Update</button> 
              </form>
            </div>
            </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>

	<!-- End loc update -->
<?php
	include('connect.php');
	$sqlL="SELECT LocationID, LocationName, StreetAdress, State, City, Zip 
		   FROM location
		   ORDER BY LocationName";
	$result=$link->query($sqlL);



	echo'<div class="row tbl_space">';

    echo "<div class='col-md-1'> </div>";
        echo "<div class = 'col-md-10'>";
          echo " <table> ";
          echo "<tr>
			  <th>Edit</th>
			  <th>Remove</th>
              <th>Location Name</th>
              <th>Street Address</th>
			  <th>City</th>
              <th>State</th>
              <th>Zip</th>
              </tr>";
          while($row = $result->fetch_assoc()) 
          {
            $locID = $row['LocationID'];
            echo "<tr>
			  <td style = 'width: 80px'><button class='btn btn-success ELocMgr' type='button' data-toggle='modal' data-target='#EditLocMgr' value='" . $row['LocationID'] . "'>Edit</button></td>
			  <td style = 'width: 80px' ><form class = 'hiddenform' method='POST' action='locations.php'><input name='id' type='hidden' value=".$row['LocationID']."><button class = 'btn btn-danger'>Remove</button></form></td>
              <td id = ".$locID."locname>".$row["LocationName"]. "</td>
              <td id = ".$locID."addr>". $row["StreetAdress"]. "</td>
			  <td id = ".$locID."cit>". $row["City"]. "</td>
              <td id = ".$locID."state>" . $row["State"]."</td>
              <td id = ".$locID."zip>" . $row["Zip"]."</td>
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