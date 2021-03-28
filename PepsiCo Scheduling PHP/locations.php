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
              <li class="nav-item active"><a class="nav-link" href="List_View.html">Schedule</a></li>
              <li class="nav-item active"><a class="nav-link a2" href="employees.html">Employees</a></li>
              <li class="nav-item active"><a class="nav-link a2" href="locations.html">Locations</a></li>
  
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
	$LocationName="SELECT LocationName, LocationID FROM location";
	$result=$link->query($LocationName);
echo" 
            <div class='row div_space'> 
                <div class='col-md-1'> </div>
                <div class='col-md-4'> 
                    <label>Select location to filter results:</label>
                    <select name='locations' id='lid'>
						<option>Select one</option>";
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
              <form >
                <p>Location Name: 
                 <input type="text" name="LocationName">
                </p>
                <p>Address: 
                 <input type="text" name="Address">
                </p>
                <p>State: 
                    <input type="text" name="State">
                </p>
                <p>Zip Code: 
                  <input type="text" name="ZipCode">
                </p>
				<button type="submit" name="submit" class="btn btn-primary">Enter</button> 
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
