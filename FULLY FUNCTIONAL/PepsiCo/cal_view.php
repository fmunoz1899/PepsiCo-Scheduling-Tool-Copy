<!DOCTYPE HTML>
<html> 

<?php 
	date_default_timezone_set("America/New_York");
?>
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
      <nav class="navbar navbar-expand-sm">
        <ul class="navbar-nav">	
          <li class="nav-item active"><a class="nav-link" href="List_View.php">Schedule</a></li>
          <li class="nav-item active"><a class="nav-link a2" href="employees.php">Employees</a></li>
          <li class="nav-item active"><a class="nav-link a2" href="locations.php">Locations</a></li>
		  <li class="nav-item active"><a class="nav-link a2" href="login.php">Log Out</a></li>

    </nav>
<?php
session_start();
include('connect.php');

if(!isset($_SESSION['manager']) || $_SESSION['manager']===false)
{
	header('location:login.php?cal_man_no_tok');
	exit;
}
if(isset($_POST['sTimel']) && isset($_POST['eTimel']) && isset($_POST['loc']) && isset($_POST['e1']) && isset($_POST['e2']) && isset($_POST['e3']))
	{
		$des=filter_var(htmlentities($_POST['des'],  ENT_QUOTES,  'utf-8'),FILTER_SANITIZE_STRING);
		$curday=date("D",strtotime($_POST['datepicker3']));
		$curdate=date("Y-m-d",strtotime($_POST['datepicker3']));
		$curtime= date('G:i:s');
			
		if($_POST['sTimel']>=$_POST['eTimel'])
			echo"<script>alert('You cannot start at or after the end time');</script>";
		
		//else if((($_POST['datepicker3']==date("m/j/Y") && $_POST['sTimel']<$curtime)) || ($curtime<='06:00:00' || $curtime>='22:00:00'))
			//echo"<script>alert('You cannnot make it for the past');</script>";
		
		else if($_POST['e2']==$_POST['e3'] && $_POST['e3']!='')
			echo"<script>alert('You cannnot have the same secondary and tertiary employee');</script>";
		else if($_POST['e1']==$_POST['e3'])
			echo"<script>alert('You cannnot have the same primary and tertiary employee');</script>";
		else if($_POST['e1']==$_POST['e2'])
			echo"<script>alert('You cannnot have the same primary and secondary employee');</script>";
		else
		{
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
					$checkhours->bind_param("isss",$_POST['e1'],$curday,$_POST['sTimel'],$_POST['eTimel']);
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
					$checkblock->bind_param("isssss",$_POST['e1'],$curdate,$_POST['sTimel'],$_POST['eTimel'],$_POST['sTimel'],$_POST['eTimel']);
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
					$checkwork->bind_param("isssss",$_POST['e1'],$curdate,$_POST['sTimel'],$_POST['eTimel'],$_POST['sTimel'],$_POST['eTimel']);
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
						
						$worki=$link->prepare("INSERT INTO workitem 
											VALUES (default,?,?,?,?,default,?,?)");
						$worki->bind_param("iiisii",$_POST['e1'],$_POST['loc'],$_POST['meth'],$des,$_POST['e2'],$_POST['e3']);
						$worki->execute();

						$max="SELECT MAX(itemid) 
							FROM workitem";
						$result=$link->query($max);
						$row = $result->fetch_assoc();
						$num=$row['MAX(itemid)'];

						$please=$link->prepare("INSERT INTO wi_schedule
											   VALUES (default,?,default,?,?,?)");
						$please->bind_param("isss",$num,$_POST['sTimel'],$_POST['eTimel'],$curdate);
						$please->execute();
					}
					
		}
	}

		
if((isset($_POST['forall']) || isset($_POST['who'])) && isset($_POST['sTimec']) && isset($_POST['eTimec']))
{
	$curtime= date('G:i:s');
	
	$reason=filter_var(htmlentities($_POST['reason'],  ENT_QUOTES,  'utf-8'),FILTER_SANITIZE_STRING);
	
		if($_POST['sTimec']>=$_POST['eTimec'])
			echo"<script>alert('You cannot start at or after the end time');</script>";
		
		//else if(($_POST['datepicker4']==date("m/j/Y") && $_POST['sTimec']<$curtime))
			//echo"<script>alert('You cannnot make it for the past');</script>";

		
		else //if the start and end time is not in the past
		{
			$list=array();
			$people='\n';
			$curday=date("D",strtotime($_POST['datepicker4']));
			$curdate=date("Y-m-d",strtotime($_POST['datepicker4']));
			
			
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
													
					$checkhours->bind_param("isss",$row['employeeid'],$curday,$_POST['sTimec'],$_POST['eTimec']);
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

					$checkblock->bind_param("isssss",$row['employeeid'],$curdate,$_POST['sTimec'],$_POST['eTimec'],$_POST['sTimec'],$_POST['eTimec']);
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
					$checkwork->bind_param("isssss",$row['employeeid'],$curdate,$_POST['sTimec'],$_POST['eTimec'],$_POST['sTimec'],$_POST['eTimec']);
					$checkwork->execute();
					$wres=$checkwork->get_result();
					$roww = mysqli_fetch_array($wres);
					
					
					if($rowh[0]!=0 || $rowb[0]!=0 || $roww[0]!=0)
						array_push($list,$row['employeeid']);
					
					else if($rowh[0]==0 && $rowb[0]==0 && $roww[0]==0)
					{
						$block=$link->prepare("INSERT INTO blackout 
											VALUES (default,?,?,?,?,?)");
						$block->bind_param("issss",$row['employeeid'],$_POST['sTimec'],$_POST['eTimec'],$curdate,$reason);
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
				$checkhours->bind_param("isss",$_POST['who'],$curday,$_POST['sTimec'],$_POST['eTimec']);
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
				$checkblock->bind_param("isssss",$_POST['who'],$curdate,$_POST['sTimec'],$_POST['eTimec'],$_POST['sTimec'],$_POST['eTimec']);
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
				$checkwork->bind_param("isssss",$_POST['who'],$curdate,$_POST['sTimec'],$_POST['eTimec'],$_POST['sTimec'],$_POST['eTimec']);
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
					$block->bind_param("issss",$_POST['who'],$_POST['sTimec'],$_POST['eTimec'],$curdate,$reason);
					$block->execute();
				}
			}
		}	
}
?>
            <div class="jumbotron text-center jumbotron2">
                <h1 class="font-weight-bold text-center">Manager Workorder List View</h1>
                <img class = "img1"  src = "pepsi.png"> 
                <hr class = "hr1">
            </div>

            <div>
                <h1 class="h1_1">Displaying all Scheduled Workorders</h1>
				 <h3 class="h1_1">For Today</h3>
            </div>
            
            <!-- All names will be taken from the database -->
           <div class="row div_space"> 
                <div class="col-md-1"> </div>
                <div class="col-md-4"> 
                  <form class = "form2" method="POST" name="cal_form" id="cal_form" action="filtercal_view.php">
                    <label>Search for an Employee:</label>
                    <input type="text" name="filterfirst" placeholder="First Name"> <!-- might need to change so it can filter by first and last name separately-->
                    
                    <input type="text" name="filterlast" placeholder="Last Name"><br>
                    <label>Select Date:</label> 
                      <input name="datepicker" type="text" id="datepicker" readonly='true' placeholder="Click Here to Select Date">
                      <button type="submit" name="submit" class="btn btn-primary">Filter</button>
                  </form > 
				  <button type="submit" name="clear" id="clear" class="btn btn-primary">Clear All</button>
				   <br>If no date selected with first and/or last name, date will default today
                
                </div>
                <div class="col-md-3"></div>
                  <div class="col-md-3 divborder">
                      <form class = "select_form">
                          <input class = "choice" type="radio" name="choice" id = "LV"> Work Order List View</input><br>
						   <input class = "choice" type="radio" name="choice" id = "BV"> Blockout List View</input></br>
                          <input class = "choice" type="radio" name="choice" id = "CV" checked> Calendar View</input>
                      </form>
                  </div>
              </div>
          <div class="row "> 
            <div class="col-md-9"> </div>
              <div class="col-md-3">
                <button type="button" class="btn btn-primary mbut" data-toggle="modal" data-target="#newWO"> Create New Workorder</button>
				<button type="button" class="btn btn-primary mbut" data-toggle="modal" data-target="#newBO"> Create Blockout</button>
              </div>
          </div>
      <!--------------- Modal Code -------------->
    	   <div class="modal fade" id="newWO" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Schedule New Workorder</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span> </button>
            </div>
            <div class="modal-body modalContent">
              <form class = "form1" action = "cal_view.php" method = "POST" >
              <div class="form-group">
                <label> Primary Engineer:</label>
                    <select name="e1" id = "e1" required>
                      <option value="" selected>--Select--</option>
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
                <label> Secondary Engineer:</label>
                <select name="e2" id = "e2" >
				 <option value="" selected>None</option>
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
				 <option value="" selected>None</option>
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
                   <input name="datepicker3" type="text" id="datepicker3" readonly='true'>
              </div>
              <div class="form-group">
                  <label> Start Time:</label>
                  <select name='sTimel' id='sTimel' required>
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
                  <label> End Time:</label>
				<select name='eTimel' id='eTimel' required>
				<option value='' selected>--Time--</option>
<?php
				$time=date('06:15:00');
				while($time!='22:15:00') //time day ends 
				{
					echo"<option value='".$time."'>". date('g:ia',strtotime($time))."</option>";
					$time = date('H:i:s',strtotime('+15 minutes',strtotime($time)));
				}
?>
			</select>              </div>
              <div class="form-group">
                <label> Location:</label><br>
                    <select name="loc" id="loc" required>
					<option value="">--Select--</option>
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
                <label> Delivery method: </label>
                    <select name="meth" id="meth" required>
                    <option value="">--Select--</option>
<?php				
                    $people="SELECT method, deliveryid
							FROM delivery
							ORDER BY method";

					$result=$link->query($people);

					while($row = $result->fetch_assoc()) 
						echo"<option value='".$row['deleiveryid']."'>".$row['method']."</option>";
?> 
                    </select>
              </div>
              <div class="form-group">
                <label> Description:</label>
                  <input type="text" name="des" id = "des">
              </div>
                <button type="submit" name="submit" class="btn btn-primary subBut">Schedule</button> 
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              
            </div>
          </div>
        </div>
      </div

      <!------------------------------------->

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
              <form action = "cal_view.php" method = "POST">
              <div class="form-group">
                  <label>Start Time</label>
                 <select name='sTimec' id='sTimec' required>
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
                 <select name='eTimec' id='eTimec' required>
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
                 <input name="datepicker4" type="text" id="datepicker4" readonly='true'>
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

	 <!--------------- Modal Code For Details -------------->
	 <div class="modal fade" id="Details" tabindex="-1" role="dialog"  aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Workorder Details</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span> </button>
            </div>
<?php
			$fill="SELECT preferrede1, preferrede2, description, date, starttime, endtime, locationname, method, firstname, lastname
					FROM workitem,delivery, location, employee, wi_schedule
					WHERE wi_schedule=";

?>
            <div class="modal-body modalContent">
			<form class = "form1" action = "List_View.html" method = "POST" >
              <div class="form-group">
                <label> First Choice Engineer:</label>
                    <select name="employee" id = "MainEng" disabled>
                      <option value="" disabled selected>Select One</option>
                      <option value="m">Main Employee</option>
                      <option value="s">Second Employee</option>
                      <option value="t">Third Employee</option>
                    </select>
              </div>
              <div class="form-group">
                <label> Second Choice Engineer:</label>
                <select name="employee" id = "SecEng" disabled>
                    <option value="" disabled selected>Select One</option>
                    <option value="m">Main Employee</option>
                    <option value="s">Second Employee</option>
                    <option value="t">Third Employee</option>
                  </select>
              </div>
              <div class="form-group">
                <label> Third Choice Engineer:</label>
                <select name="employee" id = "ThirdEng" disabled>
                    <option value="" disabled selected>Select One</option>
                    <option value="m">Main Employee</option>
                    <option value="s">Second Employee</option>
                    <option value="t">Third Employee</option>
                  </select>
              </div>
              <div class="form-group">
                  <label> Date:</label>
                  <input type="date" id = "WODate" class="form-control" name="date" disabled>
              </div>
              <div class="form-group">
                  <label> Start Time:</label>
                  <input type="time" id = "stime" class="form-control" name="time" disabled>
              </div>
              <div class="form-group">
                  <label> End Time:</label>
                  <input type="time" id = "etime" class="form-control" name="endtime" disabled>
              </div>
              <div class="form-group">
                <label> Location:</label><br>
                    <select name="SelectLocation" disabled>
                          <option value=""  selected>Select One</option>
                          <option>Location 1</option>
                          <option>Location 2</option>
                          <option>Location 3</option>
                    </select>
              </div>
              <div class="form-group">
                <label> Delivery method: </label>
                    <select name="SelectDelivery" disabled>
                          <option value=""  selected>Select One</option>
                          <option>Delivery 1</option>
                          <option>Delivery 2</option>
                          <option>Delivery 3</option>
                    </select>
              </div>
              <div class="form-group">
                <label> Description:</label>
                  <input type="text" name="Description" id = "desc" name = "woDescription" disabled>
              </div>
				</form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              
            </div>
          </div>
        </div>
      </div>
<!-- ----------------------------------------------- -->

<?php
include('connect.php');
date_default_timezone_set("America/New_York"); //timezone will need to change before giving 

		$curday=substr(date("r"),0,3); //this gets me the day abbrivated for the ahours
		$curdate=date("Y-m-d"); //this gets the current date and formats it in yyy-mm-dd
echo"
      <div class = 'row div_space '> 
        <div class = 'col-md-1'></div>
        <div class = 'col-md-10  cal_bg'>
        <div id='items'>";
		
		$empID="SELECT employee.employeeID 
		FROM employee, ahours, employeeprivlege
		WHERE employee.employeeID=ahours.employeeID
		and ahours.EmployeeID=employeeprivlege.EmployeeID
		and PrivilegeID='E'
		and StartTime!='NULL' 
		and EndTime!='NULL' 
		and DayID='".$curday."'";
		
		$result=mysqli_query($link,$empID);
		
		while($rowID=$result->fetch_assoc())
		{
			$first = 1; //check if it's first td in schedule
			$done=false; //to show that a work order was completed but only on the first blcok
			$counter=1; //for schedule cycling
			$counter2=1; //for blokcout cycling
			$time=date('06:00:00'); //starting time of the day
			
			$sched="SELECT firstName, lastName, employee.employeeID, DayID, StartTime, EndTime
					FROM employee, ahours
					WHERE employee.employeeID=".$rowID['employeeID']." and employee.employeeID=ahours.employeeID and DayID='".$curday."'";
					
			$full=mysqli_query($link,$sched);
			$rowsch=$full->fetch_assoc();
echo"
          <div class='item'>
            <table class = 'cal_tbl_bg'>
              <tr>
                
                <th colspan='2'><b>".$rowsch['firstName']." ".$rowsch['lastName']."</b></th>";
				
				$items="SELECT starttime, endtime, actualendtime, scheduleid FROM wi_schedule, workitem WHERE wi_schedule.ItemID=workitem.ItemID and workitem.employeeID=".$rowID['employeeID']." and wi_schedule.Date='".$curdate."' order by StartTime";
				$result2=mysqli_query($link,$items);
				$row = mysqli_fetch_array($result2);
				
				$ahours="SELECT StartTime, endtime FROM ahours WHERE ahours.EmployeeID=".$rowID['employeeID']." and ahours.DayID='".$curday."' order by StartTime";
				$result3=mysqli_query($link,$ahours);
				$rowahours = mysqli_fetch_array($result3);
				
				$blackout="SELECT starttime, endtime FROM blackout WHERE blackout.BDate='".$curdate."' and blackout.EmployeeID=".$rowID['employeeID']." order by StartTime";
				$result4=mysqli_query($link,$blackout);
				$rowblock = mysqli_fetch_array($result4);
							
				while($time!='22:00:00') //time day ends 
				{
					
					$entered=false; //if entered for schedule
 echo"	            <tr class = 'row_height'>";
					echo"<td class = 'hour'>&nbsp;".date('g:ia',strtotime($time))."&nbsp; </td>";
					
					if($time<$rowahours[0] || $time>=$rowahours[1]) //to show when an employee is or is not working
					{
						if(strtotime('+15 minutes',strtotime($rowahours[0]))==strtotime($rowahours[1]))
							echo "<td class='no_work_15_min'>	
							
							</td>";
						else
							echo "<td class='no_work'>	
							
							</td>";
						$entered=true;
					}
					
					if($time>=$row[0] && $time<$row[1]) //to show when an employee has an appointment
					{
						if($row[2]!='' && !$done)//checks if an appointment was updated, which would then be considered completed
						{
							if(strtotime('+15 minutes',strtotime($row[0]))==strtotime($row[1]))
								echo "<td class='sched_work_15_min'>Completed </td>";
							else
								echo "<td class='sched_work'>Completed </td>";
							$done=true;
						}
						
						else
						{
							if(strtotime('+15 minutes',strtotime($row[0]))==strtotime($row[1]))
								echo "<td class='sched_work_15_min'></td>";
							else
							{
								if ($first == 1)
								{
									echo "<td class='sched_work'> <button class='btn btn-success sid' type='button' data-toggle='modal' data-target='#Details' value='" . $row['scheduleid'] . "'>Details</button>
									 <button> Edit </button> </td>";
									$first += 1;
								}
								else
								{
									echo "<td class='sched_work'> </td>";

								}

							}

								
							
						}
						
						$entered=true;
					}
					
					else if(mysqli_data_seek($result2,$counter) && $time>=$row[1]) //increments the appointments manually 
					{
						mysqli_data_seek($result2,$counter);
						$row = mysqli_fetch_array($result2);
						$counter+=1;
						$done=false;
					}
					
						



					if($time>=$rowblock[0] && $time<$rowblock[1] && !$entered) //to show when an employee has a blockout time 
					{
						if(strtotime('+15 minutes',strtotime($rowblock[0]))==strtotime($rowblock[1]))
							echo "<td class='blockout_15_min'>	<button> edit </button></td>";
						else
							echo "<td class='blockout'> 	</td>";
						$entered=true;
					}
					
					 else if(mysqli_data_seek($result4,$counter2) && $time>=$rowblock[1]) //increments the blockouts manually 
					{
						mysqli_data_seek($result4,$counter2);
						$rowblock = mysqli_fetch_array($result4);
						$counter2+=1;
					}
					
					
					if(!$entered) //if there is nothing happening at a specific time while the employee is on the clock
						echo "<td>ffdfdfd</td>";

										
echo"					</tr>";
					
					$time = date('H:i:s',strtotime('+15 minutes',strtotime($time))); //to increment the schdule by 15 minutes
				}
 echo"            
            </table>
			</div>
		";}
 ?>

            </table>
        </div>
       </div>
	  </div>      
    </body>
</html>