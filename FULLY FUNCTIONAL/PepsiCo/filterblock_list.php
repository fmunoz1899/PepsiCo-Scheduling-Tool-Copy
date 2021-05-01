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
	
	if(isset($_POST['EmpID']) && isset($_POST['BOtimeID']))
	{
		if(isset($_POST['EmpID']) && isset($_POST['BOtimeID']))
{
	$reason=filter_var(htmlentities($_POST['reasonEdit'],  ENT_QUOTES,  'utf-8'),FILTER_SANITIZE_STRING);
	$startTime=date("H:i:s", strtotime($_POST['startTime']));
	$endTime=date("H:i:s", strtotime($_POST['endTime']));
	$curtime= date('H:i:s');
	
		if($startTime>=$endTime)
			echo"<script>alert('You cannot start at or after the end time');</script>";
		
		else if(($_POST['datepicker6']==date("m/j/Y") && $startTime<$curtime)  && ($curtime<='06:00:00' && $curtime>='22:00:00'))
			echo"<script>alert('You cannnot make it for the past');</script>";
		
		else
		{
			$curday=date("D",strtotime($_POST['datepicker6']));
			$curdate=date("Y-m-d",strtotime($_POST['datepicker6']));
			
			$checkhours=$link->prepare("SELECT CASE WHEN EXISTS
											(
												SELECT * 
												FROM employee, ahours
												WHERE employee.EmployeeID=?
												AND Employee.EmployeeID=ahours.EmployeeID
												AND dayid=?
												AND ahours.StartTime<=?
												AND ahours.EndTime>=?
											)
												THEN false
												ELSE true
												END");
				$checkhours->bind_param("isss",$_POST['EmpID'],$curday,$startTime,$endTime);
				$resulth=$checkhours->get_result();
				$checkhours->execute();
				$hres=$checkhours->get_result();
				$rowh = mysqli_fetch_array($hres);
				
				
				//to check if blockout overlaps another blockout time
				$checkblock=$link->prepare("SELECT CASE WHEN EXISTS
							(
								SELECT * 
								FROM employee, blackout
								WHERE employee.EmployeeID=?
								AND Blackout.EmployeeID=Employee.EmployeeID
								AND Blackoutid!=?
								AND BDate=?
								
								AND 
								NOT(
									(Blackout.StartTime>=?
									 and Blackout.Starttime>=?)
								   OR
									 (Blackout.endTime<=?
									  and Blackout.EndTime<=?)
								)									 
							)
								THEN true
								ELSE false
								END");				
				$checkblock->bind_param("iisssss",$_POST['EmpID'],$_POST['BOtimeID'],$curdate,$startTime,$endTime,$startTime,$endTime);
				$checkblock->execute();
				$bres=$checkblock->get_result();
				$rowb = mysqli_fetch_array($bres);
				
				
				
				//to check if blockout overlaps workitem
				$checkwork=$link->prepare("SELECT CASE WHEN EXISTS
							(
								SELECT * 
								FROM employee, wi_schedule, workitem
								WHERE employee.EmployeeID=?
								AND workitem.EmployeeID=employee.EmployeeID
								AND workitem.ItemID=wi_schedule.ItemID
								AND Date=? 
								
								AND 
								NOT(
									 (wi_schedule.StartTime>=?
									 and wi_schedule.Starttime>=?)
								   OR
									 (wi_schedule.endTime<=?
									  and wi_schedule.EndTime<=?)
								)									 
							)
								THEN true
								ELSE false
								END");
				$checkwork->bind_param("isssss",$_POST['EmpID'],$curdate,$startTime,$endTime,$startTime,$endTime);
				$checkwork->execute();
				$wres=$checkwork->get_result();
				$roww = mysqli_fetch_array($wres);
				
				if($rowh[0]!=0)
					echo "<script>alert('The employee is not working on that day and/or time');</script>";
				
				else if($rowb[0]!=0)
					echo "<script>alert('The employee already has a blockout during that day and/or time');</script>";
				
				else if($roww[0]!=0)
					echo "<script>alert('The employee already has a work item during that day and/or time');</script>";
				
				else if($rowh[0]==0 && $rowb[0]==0 && $roww[0]==0)
				{
					$upbo=$link->prepare("UPDATE blackout
										  SET starttime=?,
										  endtime=?,
										  bdate=?,
										  reason=?
										  WHERE blackoutid=?
										  AND employeeid=?");
					$upbo->bind_param("ssssii",$startTime,$endTime,$curdate,$reason,$_POST['BOtimeID'],$_POST['EmpID']);
					$upbo->execute();
				}
			
		}
	
}
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
					  filter_var(htmlentities($_POST['datepicker'],  ENT_QUOTES,  'utf-8'),FILTER_SANITIZE_STRING)
					  $BID =  $row['blackoutid'];
					  echo "<tr>
						  <td><button class='btn btn-success EditBOFltr' type='button' data-toggle='modal' data-target='#EditBOFltr' value='" . $row['blackoutid'] . "'>Edit</button></td>
						  <td>
						  <form method='POST' action='filterblock_list.php'>
						  <input name='id' type='hidden' value=".$row['blackoutid'].">
						  <button class = 'btn btn-danger'>Remove</button>
						    <input name='id' type='hidden' value=".$row['blackoutid'].">
						  <input type='hidden' name='datepicker' value='".filter_var(htmlentities($_POST['datepicker'],  ENT_QUOTES,  'utf-8'),FILTER_SANITIZE_STRING)."'>
						  <input type='hidden' name='filterfirst' value='".filter_var(htmlentities($_POST['filterfirst'],  ENT_QUOTES,  'utf-8'),FILTER_SANITIZE_STRING)."'>
						  <input type='hidden' name='filterlast' value='".filter_var(htmlentities($_POST['filterlast'],  ENT_QUOTES,  'utf-8'),FILTER_SANITIZE_STRING)."'>
						  </form>
						  </td>
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
					<form action = "filterblock_list.php" method = "POST">
					<div class="form-group">
						<label>Start Time</label>
						<select  name='startTime' required>
						<option  id='startTime' value='' selected>--Time--</option>
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
						<select  name='endTime' required>
						<option  id='endTime' value='' selected>--Time--</option>
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
						<label>Who</label>
						<input type = "text" id='whoEdit' name="whoEdt" readonly='true' required>
					</div>
					<div class="form-group">
						<label>Reason</label>
						<input type="text" name="reasonEdit" id="reasonEdit">
					</div>
					<?php echo"  <input name='id' type='hidden' value=".$row['blackoutid'].">
						  <input type='hidden' name='datepicker' value='".filter_var(htmlentities($_POST['datepicker'],  ENT_QUOTES,  'utf-8'),FILTER_SANITIZE_STRING)."'>
						  <input type='hidden' name='filterfirst' value='".filter_var(htmlentities($_POST['filterfirst'],  ENT_QUOTES,  'utf-8'),FILTER_SANITIZE_STRING)."'>
						  <input type='hidden' name='filterlast' value='".filter_var(htmlentities($_POST['filterlast'],  ENT_QUOTES,  'utf-8'),FILTER_SANITIZE_STRING)."'>"; ?>
					<input type = "hidden" id = "EmpID" name="EmpID" required>
					<input type = "hidden" id = "BOtimeID" name="BOtimeID" required>
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
