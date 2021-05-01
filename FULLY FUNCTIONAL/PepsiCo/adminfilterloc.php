<!DOCTYPE HTML>
<html> 
<head>
	<?php
			session_start();
			?>
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
        <nav class="navbar navbar-expand-sm fixed-top nav">
            <ul class="navbar-nav">	
				<li class="nav-item active"><a class="nav-link a2" href="AdminLanding.php">Employees</a></li>
				<li class="nav-item active"><a class="nav-link a2" href="adminloc.php">Locations</a></li>
				<li class="nav-item active"><a class="nav-link a2" href="login.php">Log Out</a></li>
        </nav>


            <div class="jumbotron text-center jumbotron2">
                <h1 class="font-weight-bold text-center">Filtered Locations</h1>
                <img class = "img1"  src = "pepsi.png"> 
                <hr class = "hr1">
            </div>
<?php  
if(isset($_SESSION['admin']) && $_SESSION['admin']===true)
		{
			include('connect.php');
			if(isset($_POST['id']) && $_POST['id']!='' && isset($_POST['filterl']))
			{
				$id=filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
				$rem=$link->prepare("DELETE FROM location WHERE locationID=?");
				$rem->bind_param("i",$id);
				$rem->execute();
			}
			
			if((isset($_POST['filterl'])))
			{
				$cleanloc=filter_var(htmlentities($_POST['filterl'],  ENT_QUOTES,  'utf-8'),FILTER_SANITIZE_STRING);
				
	echo"		<h1 class='h1_1'>Filtered Results For: " . $cleanloc . "</h1></div>";

			}
			
			else
			{
				//echo "<script>alert('".$_POST['filter1']."');</script>";
				header("location:adminloc.php?adfilter_no_transfer");
				exit;
			}
		}
		else
		{
			header("location:login.php?adloc_no_admin_tok");
			exit;
		}
		?>
            <div>
				<!-- Update Loc Modal -->
				<div class="modal fade" id="EditLocAdmFltr" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Create New Location</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span> </button>
            </div>
            <div class="modal-body modalContent">
              <form class = "form1" action = "adminfilterloc.php" method = "POST" >
			  
                

              <div class="form-group">
			  <label> Location Name:</label>
                 	<input type="text" id = "LN" name="LocationName" required>
              </div>
              <div class="form-group">
			  <label> Address:</label> 
                 	<input type="text" id = "add" name="Address" required> <!-- ?make it so there are no 2 addresses the same? -->
              </div>
              <div class="form-group">
			  <label> City:</label><br>
					<input type="text" id = "cit" name="city" required>
              </div>
              <div class="form-group">
			  <label> State:</label> <br>
                	<select name="state" id="state" required>  <!--insert JS to make sure that --Select One-- is not submitted-->
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
                  <input type="text" id = "ZC" name="ZipCode" minlength="5" maxlength="5" required> <!-- make it so it is just 5 numbers -->
              </div>
			  <input type = "hidden" id = "LID" name="LID" required>
			  
			 <?php echo" <input type='hidden' name='filterl' value='".filter_var(htmlentities($_POST['filterl'],  ENT_QUOTES,  'utf-8'),FILTER_SANITIZE_STRING)."'>"; ?>
                <button type="submit" name="submit" class="btn btn-primary subBut">Update</button> 
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              
            </div>
          </div>
        </div>
      </div>
</div>
	<!-- End loc update -->
<?php
		include('connect.php');

			/*$time= $_SERVER['REQUEST_TIME'];
			$timeout=5;
		
				if(isset($_SESSION['last'])===true && ($time-$_SESSION['last'])>$timeout)
				{
					header("location:login.php?st=".rand());
					exit;
				}
				
			$_SESSION['last']=$time;*/  
		

	if(isset($_POST['LocationName']) && isset($_POST['Address']) && isset($_POST['state']) && isset($_POST['ZipCode']) && isset($_POST['city']) && $_POST['state']!='' && isset($_POST['LID']))
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

echo" 
          </div>
          <div class='row '> 
            <div class='col-md-9'> </div>
                <div class='col-md-3'>
                    <a href='adminloc.php'><button type='button' class='btn btn-primary mbut'>Go Back</button></a>
                </div>
            </div>
        </div>";
		


$searchl=str_replace(' ','',filter_var(htmlentities($_POST['filterl'],  ENT_QUOTES,  'utf-8'),FILTER_SANITIZE_STRING));
$searchl="%".$searchl."%";
		$sqlL=$link->prepare("SELECT LocationName, StreetAdress, State, City, Zip, LocationID
				FROM location 
                where locationName LIKE ?");
		$sqlL->bind_param("s",$searchl);
		$sqlL->execute();
		$result=$sqlL->get_result();
		
		echo"<div><center>";
			echo mysqli_num_rows($result)." result(s)";
		echo"</div></center>";
		if(mysqli_num_rows($result)>=0)
		{
	echo"   <div class='row tbl_space'> 
				<div class='col-md-1'> </div>
				<div class = 'col-md-10'>
				<table>
						<tr>
						<th>Edit</th>
						<th>Remove</th>
						<th>Location Name</th>
						<th>Address</th>
						<th>City</th>
						<th>State</th>
						<th>Zip</th>
						</tr>";
			while($row = $result->fetch_assoc()) 
			{
					$locID = $row['LocationID'];
					echo "<tr>
					<td><button class='btn btn-success ELocAdmnFltr' type='button' data-toggle='modal' data-target='#EditLocAdmFltr' value='" . $row['LocationID'] . "'>Edit</button></td>
					<td><form method='POST' action='adminfilterloc.php'><input name='id' type='hidden' value=".$row['LocationID']."><input name='filterl' type='hidden' value=".$_POST['filterl']."><button class = 'btn btn-danger'>Remove</button></form></td>
					<td id = ".$locID."locname>".$row["LocationName"]. "</td>
					<td id = ".$locID."addr>". $row["StreetAdress"]. "</td>
					<td id = ".$locID."cit>". $row["City"]. "</td>
					<td id = ".$locID."state>" . $row["State"]."</td>
					<td id = ".$locID."zip>" . $row["Zip"]."</td>
				</tr>";
			}
		}

	mysqli_close($link);
?>
					