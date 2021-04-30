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
	
	if((!isset($_POST['datepicker']) || $_POST['datepicker']=='') && (!isset($_POST['filterfirst']) || $_POST['filterfirst']=='') && (!isset($_POST['filterlast']) || $_POST['filterlast']==''))
	{
		header("location:blocklist_view.php?block_no_filters");
		exit;
	}
	
	if($_POST['datepicker']!='')
	{
		$curday=date("D",strtotime($_POST['datepicker'])); //this gets me the day abbrivated for the ahours
		$curdate=date("Y-m-j",strtotime($_POST['datepicker'])); //this gets the current date and formats it in yyy-mm-dd
	}

	else
	{
		$curday=substr(date("r"),0,3); //this gets me the day abbrivated for the ahours
		$curdate=date("Y-m-d"); //this gets the current date and formats it in yyy-mm-dd
	}
	
	if(isset($_POST['id']) && $_POST['id']!='' && isset($_POST['datepicker']) && isset($_POST['filterfirst']) && isset($_POST['filterlast']))
	{
		$id=filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
		$rem=$link->prepare("DELETE blackout FROM blackout WHERE blackoutid=?");
		$rem->bind_param("i",$id);
		$rem->execute();
	}
	
	$cleanfirst=str_replace(' ','',filter_var(htmlentities($_POST['filterfirst'],  ENT_QUOTES,  'utf-8'),FILTER_SANITIZE_STRING));
	$cleanlast=str_replace(' ','',filter_var(htmlentities($_POST['filterlast'],  ENT_QUOTES,  'utf-8'),FILTER_SANITIZE_STRING));
	
	$searchfirst="%".$cleanfirst."%";
	$searchlast="%".$cleanlast."%";
	
	$sqlWI=$link->prepare("SELECT firstName, lastName, startTime, endTime, bdate, reason, blackoutid, blackout.EmployeeID
			FROM blackout, employee, employeeprivlege
			WHERE bdate='".$curdate."'  
			and blackout.EmployeeID=employee.EmployeeID 
			and blackout.EmployeeID=employeeprivlege.EmployeeID
            and PRIVILEGEid='E'
			and firstName LIKE ? 
			and lastName LIKE ?
			ORDER BY bdate, starttime, endtime");
		
	$sqlWI->bind_param("ss",$searchfirst,$searchlast);
	
	$result=$sqlWI->execute();
	$result=$sqlWI->get_result();
	
echo"

            <div>";
			if($searchfirst == '%%' && $searchlast == '%%')
                echo"<h1 class='h1_1'>Filtered results for ". date("n/j/y",strtotime($curdate))."</h1>";
			else
				echo"<h1 class='h1_1'>Filtered results for ".$cleanfirst." ".$cleanlast." on ". date("n/j/y",strtotime($curdate))."</h1>";
			echo "<div><center>";
				echo mysqli_num_rows($result)." result(s)";
			echo"</center></div>";
			echo"
		
            </div>";
echo"
            <div class='row div_space'> 
                <div class='col-md-1'> </div>
                <div class='col-md-4'> 
                </div>

                <div class='col-md-3'></div>
                  <div class='col-md-3 '>
                           <a href='blocklist_view.php'><button class='btn btn-primary mbut'>Go Back</button></a>
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
					<th> Employee Name </th>
					<th> Start Time </th>
					<th> End Time </th>
					<th> Reason </th>
					<th> Date </th>
				  </tr>";
				  while($row = $result->fetch_assoc()) 
				  {
					  $BID =  $row['blackoutid'];
					  echo "<tr>
						  <td><button class='btn btn-success EditBOFltr' type='button' data-toggle='modal' data-target='#EditBOFltr' value='" . $row['blackoutid'] . "'>Edit</button></td>
						  <td><form method='POST' action='blocklist_view.php'><input name='id' type='hidden' value=".$row['blackoutid']."><button class = 'btn btn-danger'>Remove</button></form></td>
						  <td id = ".$BID."name>".$row["firstName"]. " " . $row["lastName"] . "</td>
						  <td id = ".$BID."sTime>" . date('g:ia',strtotime($row["startTime"]))."</td>
						  <td id = ".$BID."eTime>" . date('g:ia',strtotime($row["endTime"]))."</td>
						  <td id = ".$BID."reason>" . $row['reason'] . "</td>
						  <td id = ".$BID."date>" . date('n/j/Y',strtotime($row["bdate"]))."</td>
						  <td style = 'display: none' id = ".$BID."eid>".$row['EmployeeID']."</td>
					  </tr>";
				  }
	echo'</div>';
	mysqli_close($link);
?>
      <!------------------------------------->

	  <!--------------- Modal Code to update Blockout Time-------------->
	  <div class="modal fade" id="EditBOFltr" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Edit Blockout</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
			<div class = row>
				<div class = "col-md-2"></div>
				<div class = "col-md-9">
					<div class="modal-body">
					<form action = "blocklist_view.php" method = "POST">
					<div class="form-group">
						<label>Start Time</label>
						<select  required>
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
						<label>End Time</label>
						<select  required>
						<option name='endTime' id='endTime' value='' selected>--Time--</option>
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
						<label>Date</label>
						<input name="datepicker6" type="text" id="datepicker6" readonly='true'>
					</div>
					<div class="form-group">
						<label>All</label>
						<input type="checkbox" id="forallEdit" name="forallEdit" disabled>
					</div>
					<div class="form-group">
						<label>Who</label>
						<select   disabled required>
						<option name='who' id='whoEdit' value=''  selected>--Select--</option>
						<?php
							include('connect.php');
							$names="SELECT firstname, lastname, employee.employeeid
									FROM employee, employeeprivlege
									WHERE privilegeid='E'
									and employee.employeeid=employeeprivlege.employeeid
									ORDER BY firstname";
							$result=$link->query($names);
							while($row = $result->fetch_assoc()) 
							{
								echo"<option value=".$row['employeeid'].">".$row['firstname']." ".$row['lastname']."</option>";
							}

						?>
						</select>
					</div>
					<div class="form-group">
						<label>Reason</label>
						<input type="text" name="reasonEdit" id="reasonEdit">
					</div>
					<input type = "hidden" id = "EmpID"  required>
					<input type = "hidden" id = "BOtimeID"  required>
						<button type="submit" name="submit" class="btn btn-primary">Edit</button> 
					</form>
					
				</div>
				<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
            </div>
          </div>
        </div>
      </div>
	  

      <!------------------------------------->
            
    </body>
</html>
