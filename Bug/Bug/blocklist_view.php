<!DOCTYPE HTML>
<?php 
	date_default_timezone_set("America/New_York");
?>
<html> 
<head>
    <title>Pepsi Scheduling Tool</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <link href='https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/ui-lightness/jquery-ui.css'rel='stylesheet'>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.11/jquery-ui.min.js"></script>
        <script type='text/javascript' src='/js/jquery.mousewheel.min.js'></script>
        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <link rel='stylesheet' type='text/css' href='CSS/pepsi_styles.css'>
        <script language='Javascript' type='text/javascript' src="Javascript/functionality.js"></script> 
        <script>
                $(document).ready(function(){
                  $('[data-toggle="tooltip"]').tooltip();   
                });               
        </script>
    </head>

    <body>
        <nav class="navbar navbar-expand-sm ">
          <ul class="navbar-nav">	
            <li class="nav-item active"><a class="nav-link" href="List_View.php">Schedule</a></li>
            <li class="nav-item active"><a class="nav-link a2" href="employees.php">Employees</a></li>
            <li class="nav-item active"><a class="nav-link a2" href="locations.php">Locations</a></li>
			<li class="nav-item active"><a class="nav-link a2" href="login.php">Log Out</a></li>
      </nav>


            <div class="jumbotron text-center jumbotron2">
                <h1 class="font-weight-bold text-center">Manager Workorder List View</h1>
                <img class = "img1"  src = "pepsi.png"> 
                <hr class = "hr1">
            </div>

            <div>
                <h1 class="h1_1">Displaying all Scheduled Blockout Times</h1>
				<h3 class="h1_1">For Today</h3>
            </div>
            
 <?php 
	session_start();
	include('connect.php');
	if(!isset($_SESSION['manager']) || $_SESSION['manager']===false)
	{
		header("location:login.php?list_view_no_man_tok");
		exit;
	}
	
	if(isset($_POST['id']) && $_POST['id']!='')
	{
		$id=filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
		$rem=$link->prepare("DELETE blackout FROM blackout WHERE blackoutid=?");
		$rem->bind_param("i",$id);
		$rem->execute();
	}
	


if((isset($_POST['forall']) || isset($_POST['who'])) && isset($_POST['sTime']) && isset($_POST['eTime']))
{
	$curtime= date('G:i:s');
	
	$reason=filter_var(htmlentities($_POST['reason'],  ENT_QUOTES,  'utf-8'),FILTER_SANITIZE_STRING);
	
		if($_POST['sTime']>=$_POST['eTime'])
			echo"<script>alert('You cannot start at or after the end time');</script>";
		
		else if(($_POST['datepicker3']==date("m/j/Y") && $_POST['sTime']<$curtime)  && ($curtime<='06:00:00' && $curtime>='22:00:00'))
			echo"<script>alert('You cannnot make it for the past');</script>";
		
		
		else //if the start and end time is not in the past
		{
			$list=array();
			$people='\n';
			$curday=date("D",strtotime($_POST['datepicker3']));
			$curdate=date("Y-m-d",strtotime($_POST['datepicker3']));
			
			
			if(isset($_POST['forall'])) //blockout for everyone
			{
				$allid="SELECT employee.employeeid
						FROM employee, employeeprivlege, ahours
						WHERE employee.EmployeeID=employeeprivlege.EmployeeID
						AND PRIVILEGEid='E'
						AND ahours.EmployeeID=employeeprivlege.EmployeeID
						AND dayid='".$curday."'
						AND starttime!=''";
				
				$result=$link->query($allid);
				
				
				while($row = $result->fetch_assoc()) 
				{
					//to check if blockout is within their working hours
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
													
					$checkhours->bind_param("isss",$row['employeeid'],$curday,$_POST['sTime'],$_POST['eTime']);
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

					$checkblock->bind_param("isssss",$row['employeeid'],$curdate,$_POST['sTime'],$_POST['eTime'],$_POST['sTime'],$_POST['eTime']);
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
					$checkwork->bind_param("isssss",$row['employeeid'],$curdate,$_POST['sTime'],$_POST['eTime'],$_POST['sTime'],$_POST['eTime']);
					$checkwork->execute();
					$wres=$checkwork->get_result();
					$roww = mysqli_fetch_array($wres);
					
					
					if($rowh[0]!=0 || $rowb[0]!=0 || $roww[0]!=0)
						array_push($list,$row['employeeid']);
					
					else if($rowh[0]==0 && $rowb[0]==0 && $roww[0]==0)
					{
						$block=$link->prepare("INSERT INTO blackout 
											VALUES (default,?,?,?,?,?)");
						$block->bind_param("issss",$row['employeeid'],$_POST['sTime'],$_POST['eTime'],$curdate,$reason);
						$block->execute();
					}
				
				}
				
				foreach($list as $id)
				{
					$cant="SELECT firstname, lastname
						   FROM employee
						   WHERE employeeid=".$id;
					$result=$link->query($cant);
					
					while($row = $result->fetch_assoc())
						$people.=$row['firstname']." ".$row['lastname'].'\n';
				}
				echo"<script>alert('These people are busy at that time: ".$people.'\n'."Everyone else has been blocked out');</script>";
				
			}
			
			else //blockout for specific person
			{
				//to check if blockout is within their working hours
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
				$checkhours->bind_param("isss",$_POST['who'],$curday,$_POST['sTime'],$_POST['eTime']);
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
				$checkblock->bind_param("isssss",$_POST['who'],$curdate,$_POST['sTime'],$_POST['eTime'],$_POST['sTime'],$_POST['eTime']);
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
				$checkwork->bind_param("isssss",$_POST['who'],$curdate,$_POST['sTime'],$_POST['eTime'],$_POST['sTime'],$_POST['eTime']);
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
					$block=$link->prepare("INSERT INTO blackout 
										VALUES (default,?,?,?,?,?)");
					$block->bind_param("issss",$_POST['who'],$_POST['sTime'],$_POST['eTime'],$curdate,$reason);
					$block->execute();
				}
			}
		}	
}
?>


            <div class='row div_space'> 
                <div class='col-md-1'> </div>
                <div class='col-md-4'> 
                    <form class = "form2" method="POST" name="cal_form" id="cal_form" action="filterblock_list.php">
                    <label>Search for an Employee:</label>
                    <input type="text" name="filterfirst" placeholder="First Name"> <!-- might need to change so it can filter by first and last name separately-->
                    <input type="text" name="filterlast" placeholder="Last Name"><br>
                    <label>Select Date:</label> 
                      <input name="datepicker" type="text" id="datepicker" readonly='true' placeholder="Click Here to Select Date">
                      <button type="submit" name="submit" class="btn btn-primary">Filter</button>
                  </form > 
				  <button type="submit" name="clear" id="clear" class="btn btn-primary">Clear All</button>
				  <br>If no date selected with first and/or last name, date will default today

                    </select>
                </div>


                <div class="col-md-3"></div>
                  <div class="col-md-3 divborder">
                      <form class = "select_form">
                          <input class = "choice" type="radio" name="choice" id = "LV"> Work Order List View</input><br>
						   <input class = "choice" type="radio" name="choice" id = "BV" checked> Blockout List View</input></br>
                          <input class = "choice" type="radio" name="choice" id = "CV"> Calendar View</input>
                      </form>
                  </div>
              </div>
          </div>
          <div class="row "> 
            <div class="col-md-9"> </div>
              <div class="col-md-3">
                <button type="button" class="btn btn-primary mbut" data-toggle="modal" data-target="#newBO"> Create Block Out</button>
              </div>
          </div>
      </div>
</div>
      <!--------------- Modal Code -------------->
	<div class="modal fade" id="newBO" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Schedule New Blockout</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form action = "blocklist_view.php" method = "POST">
              <div class="form-group">
                  <label>Start Time</label>
                 <select name='sTime' id='sTime' required>
				<option value='' selected>--Time--</option>
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
                 <select name='eTime' id='eTime' required>
				<option value='' selected>--Time--</option>
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
                <input name="datepicker3" type="text" id="datepicker3" readonly='true'>
            </div>
			<div class="form-group">
                <label>All</label>
                <input type="checkbox" id="forall" name="forall" checked>
            </div>
			<div class="form-group">
                <label>Who</label>
                <select name='who' id='who' disabled required>
				<option value='' selected>--Select--</option>
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
                <input type="text" name="reason" id="reason">
            </div>
                <button type="submit" name="submit" class="btn btn-primary">Blockout</button> 
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              
            </div>
          </div>
        </div>
      </div>
	  

      <!------------------------------------->
	  <!--------------- Modal Code to update Blockout Time-------------->
	<div class="modal fade" id="EditBO" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Edit Blockout</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
			
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
	  

      <!------------------------------------->
<?php
//Make sure to change this so that it only shows the engineers
	include('connect.php');
	
	$curdate=date("Y-m-d");
	
	
	$sqlWI="SELECT firstName, lastName, startTime, endTime, bdate, reason, blackoutid, blackout.EmployeeID
			FROM blackout, employee, employeeprivlege
			WHERE bdate='".$curdate."'
			and blackout.EmployeeID=employeeprivlege.EmployeeID
            and PRIVILEGEid='E'
			and blackout.EmployeeID=employee.EmployeeID 
			ORDER BY bdate, starttime, endtime";
			
	$result=$link->query($sqlWI);
	echo'<div class="row tbl_space">';

    echo "<div class='col-md-1'> </div>";
        echo "<div class = 'col-md-10'>";
          echo " <table > ";
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
					<td><button class='btn btn-success EditBO' type='button' data-toggle='modal' data-target='#EditBO' value='" . $row['blackoutid'] . "'>Edit</button></td>
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
 
    </body>
</html>
