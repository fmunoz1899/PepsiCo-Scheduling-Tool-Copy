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
        <nav class="navbar navbar-expand-sm fixed-top nav">
          <ul class="navbar-nav">	
            <li class="nav-item active"><a class="nav-link" href="List_View.php">Schedule</a></li>
            <li class="nav-item active"><a class="nav-link a2" href="employees.php">Employees</a></li>
            <li class="nav-item active"><a class="nav-link a2" href="locations.php">Locations</a></li>
			<li class="nav-item active"><a class="nav-link a2" href="login.php">Log Out</a></li>
      </nav>
	  	   <!--------------- Modal Code To Edit -------------->


 <?php 
 echo"
            <div class='jumbotron text-center jumbotron2'>
                <h1 class='font-weight-bold text-center'>Manager Workorder List View</h1>
                <img class = 'img1'  src = 'pepsi.png'> 
                <hr class = 'hr1'>
            </div>";

           
            

	session_start();
	
	include('connect.php');
	date_default_timezone_set("America/New_York"); //timezone will need to change before giving 

	
	if(!isset($_SESSION['manager']) || $_SESSION['manager']===false)
	{
		header("location:login.php?list_view_no_man_tok");
		exit;
	}
	

	if(isset($_POST['id']) && $_POST['id']!='' && isset($_POST['datepicker']) && isset($_POST['filterfirst']) && isset($_POST['filterlast']))
	{
		$id=filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
		$rem=$link->prepare("DELETE workitem, wi_schedule FROM workitem INNER JOIN wi_schedule on wi_schedule.ItemID=workitem.ItemID WHERE workitem.itemid=?");
		$rem->bind_param("i",$id);
		$rem->execute();
	}
	
	if((!isset($_POST['datepicker']) || $_POST['datepicker']=='') && (!isset($_POST['filterfirst']) || $_POST['filterfirst']=='') && (!isset($_POST['filterlast']) || $_POST['filterlast']==''))
	{
		header("location:list_view.php?block_no_filters");
		exit;
	}
	
	if($_POST['datepicker']!='')
	{
		$curday=date("D",strtotime($_POST['datepicker'])); //this gets me the day abbrivated for the ahours
		$curdate=date("Y-m-d",strtotime($_POST['datepicker'])); //this gets the current date and formats it in yyy-mm-dd
	}

	else
	{
		$curday=substr(date("r"),0,3); //this gets me the day abbrivated for the ahours
		$curdate=date("Y-m-d"); //this gets the current date and formats it in yyy-mm-dd
	}
	
	$cleanfirst=str_replace(' ','',filter_var(htmlentities($_POST['filterfirst'],  ENT_QUOTES,  'utf-8'),FILTER_SANITIZE_STRING));
	$cleanlast=str_replace(' ','',filter_var(htmlentities($_POST['filterlast'],  ENT_QUOTES,  'utf-8'),FILTER_SANITIZE_STRING));
	
	$searchfirst="%".$cleanfirst."%";
	$searchlast="%".$cleanlast."%";
		
			
	
	$sqlWI=$link->prepare("SELECT firstName, lastName, workitem.ItemID, LocationName, Method, StartTime, EndTime, Date, preferrede1, preferrede2, description, wi_schedule.actualendtime
			FROM workitem,employee,delivery,wi_schedule,location, employeeprivlege
			where workitem.EmployeeID=employee.EmployeeID 
			and workitem.ItemID=wi_schedule.ItemID 
			and workitem.LocationID=location.LocationID 
			and workitem.DeliveryID=delivery.DeliveryID
			and Date='".$curdate."'
			and workitem.EmployeeID=employeeprivlege.EmployeeID
            and PRIVILEGEid='E'
			and firstName LIKE ? 
			and lastName LIKE ?
			ORDER BY Date, StartTime, EndTime");
		
	$sqlWI->bind_param("ss",$searchfirst,$searchlast);
	
	$result=$sqlWI->execute();
	$result=$sqlWI->get_result();
	
echo"

            <div>";
			if($searchfirst == '%%' && $searchlast == '%%')
                echo"<h1 class='h1_1'>Filtered results for ". date("n/j/y",strtotime($curdate))."</h1>";
			else
				echo"<h1 class='h1_1'>Filtered results for ".$cleanfirst." ".$cleanlast." on ". date("n/j/y",strtotime($curdate))."</h1>";
			echo"<div><center>";
				echo mysqli_num_rows($result)." result(s)";
			echo"</div></center>";
			echo"
		
            </div>";
echo"
            <div class='row div_space'> 
                <div class='col-md-1'> </div>
                <div class='col-md-4'> 
                </div>

                <div class='col-md-3'></div>
                  <div class='col-md-3 '>
                           <a href='list_view.php'><button class='btn btn-primary mbut'>Go Back</button></a>
                  </div>
              </div>
          </div>
          <div class='row '> 
            <div class='col-md-9'> </div>
              <div class='col-md-3'>
              </div>
          </div>
      </div>
</div>";

//Make sure to change this so that it only shows the engineers	
	echo'<div class="row tbl_space">';

    echo "<div class='col-md-1'> </div>";
        echo "<div class = 'col-md-10'>";
          echo " <table> ";
          echo "<tr>
			<th> Edit </th>
			<th> Remove </th>
			<th> Item ID </th> <!--which to show scheduleID or itemID-->
			<th> Primary Eng. </th>
			<th> Secondary Eng. </th>
			<th> Tertiary Eng. </th>
			<th> Description </th>
			<th> Location </th>
			<th> Delivery Method </th>
			<th> Start Time </th>
			<th> End Time </th>
			<th> Date </th>
			<th> Actual End Time </th>
				  </tr>";
			while($row = $result->fetch_assoc()) 
			{
				$info = $row['ItemID'];
				echo "<tr>
				<td><button class='btn btn-success EditWO' type='button' data-toggle='modal' data-target='#EdWOFltr' value='" . $row['ItemID'] . "'>Edit</button></td>
				
				<td><form method='POST' action='list_view.php' class = 'hiddenform'><input name='id' type='hidden' value=".$row['ItemID']."><button class = 'btn btn-danger'>Cancel</button></form></td>
				<td>". $row["ItemID"]. "</td>
					<td id = ".$info."name>".$row["firstName"]. " " . $row["lastName"] . "</td>";
					$name1=$link->prepare("SELECT firstname, lastname
										   FROM employee
										   WHERE employeeID=?");
					$name1->bind_param("i",$row['preferrede1']);
					$name1->execute();
					$temp1=$name1->get_result();
					$row2 = mysqli_fetch_array($temp1);
					
					echo"<td id = ".$info."sname>".$row2[0]. " " . $row2[1] . "</td>";

					$name1=$link->prepare("SELECT firstname, lastname
										   FROM employee
										   WHERE employeeID=?");
					$name1->bind_param("i",$row['preferrede2']);
					$name1->execute();
					$temp1=$name1->get_result();
					$row2 = mysqli_fetch_array($temp1);
					
					echo"<td id = ".$info."tname>".$row2[0]. " " . $row2[1] . "</td>
					
					<td class = 'descCell' id = ".$info."desc>" . $row["description"]."</td>

					<td id = ".$info."lname>" . $row["LocationName"]."</td>
					<td id = ".$info."meth>" . $row["Method"]."</td>
					<td id = ".$info."stime>" . date('g:ia',strtotime($row["StartTime"]))."</td>
					<td id = ".$info."etime>" . date('g:ia',strtotime($row["EndTime"]))."</td>
					<td id = ".$info."date>" . date('n/j/Y',strtotime($row["Date"]))."</td>";
					//CHANGE ID FOR EDITING 
					if($row["actualendtime"] == "")
					{
					  echo"<td id = ".$info."aet>" .$row["actualendtime"]."</td>";
		  
					}
					else
					{
					  echo"	<td id = ".$info."aet>" . date('g:ia',strtotime($row["actualendtime"]))."</td>";
					}
				echo"</tr>";
			}
	echo'</div>';
	mysqli_close($link);
?>
	<div class="modal fade" id="EdWOFltr" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" >Edit Existing Workorder</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span> </button>
            </div>
			<div class = row>
				<div class = "col-md-2"></div>
				<div class = "col-md-9">
					<div class="modal-body modalContent">
					<form class = "form1" action = "filterlist_view.php" method = "POST" >
					<div class="form-group">
						<label> Primary Engineer:</label>
							<select name="e1" id = "e1" required>
							<option name="ee1" id = "ee1" selected>--Select--</option>
								<?php
								include('connect.php');
									$people="SELECT firstname,lastname,employee.employeeid
											FROM employee, employeeprivlege
											WHERE employee.employeeID=employeeprivlege.employeeID
											AND privilegeid='E' ORDER BY firstname,lastname";
									$result=$link->query($people);

									while($row = $result->fetch_assoc()) 
										echo"<option value='".$row['employeeid']."'>".$row['firstname']." ".$row['lastname']."</option>";
								?>                     
							</select>
					</div>
					<div class="form-group">
						<label> Secondary Engineer:</label>
						<select name="e2" id = "e2" >
						<option name="ee2" id = "ee2"  value="" selected>None</option>
							<?php				
								$people="SELECT firstname,lastname,employee.employeeid
										FROM employee, employeeprivlege
										WHERE employee.employeeID=employeeprivlege.employeeID
										AND privilegeid='E' ORDER BY firstname,lastname";
								$result=$link->query($people);

								while($row = $result->fetch_assoc()) 
									echo"<option value='".$row['employeeid']."'>".$row['firstname']." ".$row['lastname']."</option>";
							?>
						</select>
					</div>
					<div class="form-group">
						<label> Tertiary Engineer:</label>
						<select name="e3" id = "e3" >
						<option name="ee3" id = "ee3" value="" selected>None</option>
							<?php				
								$people="SELECT firstname,lastname,employee.employeeid
										FROM employee, employeeprivlege
										WHERE employee.employeeID=employeeprivlege.employeeID
										AND privilegeid='E' ORDER BY firstname,lastname";
								$result=$link->query($people);

								while($row = $result->fetch_assoc()) 
									echo"<option value='".$row['employeeid']."'>".$row['firstname']." ".$row['lastname']."</option>";
							?>  
						</select>
					</div>
					<div class="form-group">
						<label> Date:</label>
						<input name="datepicker5" type="text" id="datepicker5" readonly='true'>
					</div>
					<div class="form-group">
						<label> Start Time:</label>
						<select name='sTime' id='sTime' required>
						<option name='startTime' id='startTime' value='' selected>--Time--</option>
							<?php
								$time=date('06:00:00');
								while($time!='22:00:00') //time day ends 
								{
									echo"<option value='".$time."'>". date('g:ia',strtotime($time))."</option>";
									$time = date('H:i:s',strtotime('+15 minutes',strtotime($time)));
								}
							?>
						</select>
					</div>
					<div class="form-group">
						<label> End Time:</label>
						<select name='eTime' id='eTime' required>
						<option  name='endTime' id='endTime' value='' selected>--Time--</option>
							<?php
								$time=date('06:15:00');
								while($time!='22:15:00') //time day ends 
								{
									echo"<option value='".$time."'>". date('g:ia',strtotime($time))."</option>";
									$time = date('H:i:s',strtotime('+15 minutes',strtotime($time)));
								}
							?>
					</select>             
					</div>
					<div class="form-group">
						<label> Location:</label><br>
							<select name="loc" id="loc" required>
							<option name="locu" id="locu" value="">--Select--</option>
								<?php	
									$locs="SELECT locationname, locationid
										FROM location
										ORDER BY locationname";
									$result=$link->query($locs);

									while($row = $result->fetch_assoc()) 
										echo"<option value=".$row['locationid'].">".$row['locationname']."</option>";
								?>
							</select>
					</div>
					<div class="form-group">
						<label> Delivery method: </label></br>
						<input type='radio' id='bulk' name='meth' value='1' checked>
						<label for='bulk'>BULK</label></br>

						<input type='radio' id='geo' name='meth' value='2' >
						<label for='geo'>GEO</label></br>

						<input type='radio' id='fsv' name='meth' value='3' >
						<label for='fsv'>FSV</label></br>

						<input type='radio' id='dbay' name='meth' value='4' >
						<label for='dbay'>DBAY</label></br>
					</div>
					<div class="form-group">
						<label> Description:</label>
						<textarea rows = "10" cols = "25" id = "woDesc">

						</textarea>
					</div>
					<input type = "hidden" id = "orderID"  required>
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
	</div>
	  

      <!------------------------------------->


            
    </body>
</html>