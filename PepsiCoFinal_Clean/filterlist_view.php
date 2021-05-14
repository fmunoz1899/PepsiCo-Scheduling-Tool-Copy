<!DOCTYPE HTML>
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
        <script language='Javascript' type='text/javascript' src='Javascript/functionality.js'></script> 
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
	date_default_timezone_set("America/New_York"); 

$time= $_SERVER['REQUEST_TIME'];
		$timeout=300;
	
			if(isset($_SESSION['last'])===true && ($time-$_SESSION['last'])>$timeout)
			{
				header("location:login.php?st=".rand());
				exit;
			}
			
		$_SESSION['last']=$time;
	
	if(!isset($_SESSION['manager']) || $_SESSION['manager']===false)
	{
		header("location:login.php");
		exit;
	}
	


		if(isset($_POST['orderID']))
		{		

			$startTime=date("H:i:s", strtotime($_POST['startTime']));
			$endTime=date("H:i:s", strtotime($_POST['endTime']));
			$woDesc=filter_var(htmlentities($_POST['woDesc'],  ENT_QUOTES,  'utf-8'),FILTER_SANITIZE_STRING);
			$curday=date("D",strtotime($_POST['datepicker5']));
			$curdate=date("Y-m-d",strtotime($_POST['datepicker5']));
			$curtime= date('H:i:s');
				
			if($startTime>=$endTime)
				echo"<script>alert('You cannot start at or after the end time');</script>";
			
			else if($curdate==date("Y-m-d") && $startTime<=$curtime)
				echo"<script>alert('You cannnot make it for the past');</script>";
			
			else if($_POST['e2e']==$_POST['e3e'] && $_POST['e3e']!='')
				echo"<script>alert('You cannnot have the same secondary and tertiary employee');</script>";
			else if($_POST['e1e']==$_POST['e3e'])
				echo"<script>alert('You cannnot have the same primary and tertiary employee');</script>";
			else if($_POST['e1e']==$_POST['e2e'])
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
						$checkhours->bind_param("isss",$_POST['e1e'],$curday,$startTime,$endTime);
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
						$checkblock->bind_param("isssss",$_POST['e1e'],$curdate,$startTime,$endTime,$startTime,$endTime);
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
										AND workitem.ItemID!=?
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
						$checkwork->bind_param("iisssss",$_POST['e1e'],$_POST['orderID'],$curdate,$startTime,$endTime,$startTime,$endTime);
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
							$upwo1=$link->prepare("UPDATE workitem
												   SET employeeid=?,
												   locationid=?,
												   deliveryid=?,
												   description=?,
												   preferrede1=?,
												   preferrede2=?
												   WHERE itemid=?");
							$upwo1->bind_param("iiisiii",$_POST['e1e'],$_POST['loce'],$_POST['methe'],$woDesc,$_POST['e2e'],$_POST['e3e'],$_POST['orderID']);
							$upwo1->execute();
							
							$upwo2=$link->prepare("UPDATE wi_schedule
												   SET starttime=?,
												   endtime=?,
												   date=?
												   WHERE itemid=?");
							$upwo2->bind_param("sssi",$startTime,$endTime,$curdate,$_POST['orderID']);
							$upwo2->execute();
						}	
			}
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
		header("location:list_view.php");
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
		
			
	if($_SESSION['username']!='%')
	{
		$sqlWI=$link->prepare("SELECT firstName, lastName, workitem.ItemID, LocationName, Method, StartTime, EndTime, Date, preferrede1, preferrede2, description, wi_schedule.actualendtime, employee.employeeID, location.locationid
				FROM workitem,employee,delivery,wi_schedule,location, employeeprivlege, team
				where workitem.EmployeeID=employee.EmployeeID 
				and workitem.ItemID=wi_schedule.ItemID 
				and workitem.LocationID=location.LocationID 
				and workitem.DeliveryID=delivery.DeliveryID
				and Date='".$curdate."'
				and workitem.EmployeeID=employeeprivlege.EmployeeID
				and PRIVILEGEid='E'
				and firstName LIKE ? 
				and managerid LIKE '".$_SESSION['username']."'
				and team.employeeid=Employee.EmployeeID
				and lastName LIKE ?
				ORDER BY Date, StartTime, EndTime");
			
		$sqlWI->bind_param("ss",$searchfirst,$searchlast);
		
		$result=$sqlWI->execute();
		$result=$sqlWI->get_result();
	}
	
	else
	{
		$sqlWI=$link->prepare("SELECT firstName, lastName, workitem.ItemID, LocationName, Method, StartTime, EndTime, Date, preferrede1, preferrede2, description, wi_schedule.actualendtime, employee.employeeID, location.locationid
				FROM workitem,employee,delivery,wi_schedule,location, employeeprivlege, team
				where workitem.EmployeeID=employee.EmployeeID 
				and workitem.ItemID=wi_schedule.ItemID 
				and workitem.LocationID=location.LocationID 
				and workitem.DeliveryID=delivery.DeliveryID
				and Date='".$curdate."'
				and workitem.EmployeeID=employeeprivlege.EmployeeID
				and PRIVILEGEid='E'
				and firstName LIKE ? 
				and team.employeeid=Employee.EmployeeID
				and lastName LIKE ?
				ORDER BY Date, StartTime, EndTime");
			
		$sqlWI->bind_param("ss",$searchfirst,$searchlast);
		
		$result=$sqlWI->execute();
		$result=$sqlWI->get_result();
	}
	
echo"

            <div>";
			if($searchfirst == '%%' && $searchlast == '%%')
                echo"<h1 class='h1_1'>Filtered results for ". date("n/j/y",strtotime($_POST['datepicker']))."</h1>";
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

	
$today=date("Y-m-d");
$curtime=date("H:i:s");
date_default_timezone_set("America/New_York");
	echo'<div class="row tbl_space">';
	
    echo "<div class='col-md-1'> </div>";
        echo "<div class = 'col-md-10'>";
          echo " <table> ";
          echo "<tr>
			<th> Edit </th>
			<th style='display:none'> Item ID </th> <!--which to show scheduleID or itemID-->
			<th> Primary Eng. </th>
			<th> Secondary Eng. </th>
			<th> Tertiary Eng. </th>
			<th> Description </th>
			<th> Location </th>
			<th> Delivery Method </th>
			<th> Start Time </th>
			<th> End Time </th>
			<th> Date </th>
				  </tr>";
			while($row = $result->fetch_assoc()) 
			{
				$info = $row['ItemID'];
				echo "<tr>";
				if(($curdate==$today && $row['EndTime']<$curtime) || $today>$curdate)
					echo"<td><button class='btn btn-primary WODetailsLV' type='button' data-toggle='modal' data-target='#Details' value='" . $row['ItemID'] . "'>COMPLETED</button>";
				else
					echo"<td><button class='btn btn-success EditWO' type='button' data-toggle='modal' data-target='#EdWOFltr' value='" . $row['ItemID'] . "'>Edit</button>";
				
				echo"
				</td>
				
				
				<td style='display:none'>". $row["ItemID"]. "</td>
					<td id = ".$info."name>".$row["firstName"]. " " . $row["lastName"] . "</td>";
					echo"<td  style = 'display:none' id = ".$info."pid>".$row['employeeID']."</td>"; //might need to aly with location of line
					$name1=$link->prepare("SELECT firstname, lastname, employeeid
										   FROM employee
										   WHERE employeeID=?");
					$name1->bind_param("i",$row['preferrede1']);
					$name1->execute();
					$temp1=$name1->get_result();
					$row2 = mysqli_fetch_array($temp1);
					
					if($row2[0]!='' && $row2[1]!='')
					{
						echo"<td id = ".$info."sname>".$row2[0]. " " . $row2[1] . "</td>";
						echo"<td  style = 'display:none' id = ".$info."sid>".$row2['employeeid']."</td>"; //might need to aly with location of line
					}
					
					else
						echo"<td id = ".$info."sname>None</td>";

					$name1=$link->prepare("SELECT firstname, lastname, employeeid
										   FROM employee
										   WHERE employeeID=?");
					$name1->bind_param("i",$row['preferrede2']);
					$name1->execute();
					$temp1=$name1->get_result();
					$row2 = mysqli_fetch_array($temp1);
					
					if($row2[0]!='' && $row2[1]!='')
					{
						echo"<td id = ".$info."tname>".$row2[0]. " " . $row2[1] . "</td>";
						echo"<td  style = 'display:none' id = ".$info."tid>".$row2['employeeid']."</td>";
						
					}
					else
						echo"<td id = ".$info."tname>None</td>";
					
					echo"<td class = 'descCell' id = ".$info."desc>" . $row["description"]."</td>";
echo"<td  style = 'display:none' id = ".$info."lid>".$row['locationid']."</td> 
					<td id = ".$info."lname>" . $row["LocationName"]."</td>
					<td id = ".$info."meth>" . $row["Method"]."</td>
					<td id = ".$info."stime>" . date('g:ia',strtotime($row["StartTime"]))."</td>
					<td id = ".$info."etime>" . date('g:ia',strtotime($row["EndTime"]))."</td>
					<td id = ".$info."date>" . date('n/j/Y',strtotime($row["Date"]))."</td>";
				echo"</tr>";
			}
	echo'</div>';
	mysqli_close($link);
?>
<!--------------- Modal Code For Details -------------->
	 <div class="modal fade" id="Details" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" >View Workorder Details</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span> </button>
            </div>
			<div class = "row">
			<div class ="col-md-3"></div>
			<div class = "col-md-9">
            <div class="modal-body modalContent">
			
				  <form action = "cal_view.php" method = "POST" >
				  <div class="form-group">
					<label> Primary Engineer:</label>
					<input type = "text" id = "ee1Det"  readonly='true'>					
				  </div>
				  <div class="form-group">
					<label> Secondary Engineer:</label>
					<input type = "text" id = "ee2Det"  readonly='true'>
				  </div>
				  <div class="form-group">
					<label> Tertiary Engineer:</label>
					<input type = "text" id = "ee3Det"  readonly='true'>
				  </div>
				  <div class="form-group">
					  <label> Date:</label>
					   <input name="datepicker5" type="text" id="datepicker5Det" readonly='true'>
				  </div>
				  <div class="form-group">
					  <label> Start Time:</label>
					  <input type = "text" id = "startTimeDet"   readonly='true'>
				  </div>
				  <div class="form-group">
					  <label> End Time:</label>
					  <input type = "text" id = "endTimeDet"   readonly='true'>
				</div>
				  <div class="form-group">
					<label> Location:</label><br>
					<input type = "text" id = "locuDet"   readonly='true'>
				  </div>
				  <div class="form-group">
					<label> Delivery method: </label></br>
						<input type = "text" id = "methDet"   readonly='true'>

				  </div>
				  <div class="form-group">
					<label> Description:</label>
					  <textarea rows = "10" cols = "25" id = "woDescDet" name="woDesc" readonly='true'></textarea>
				  </div>
				  
				  </form>
				</div>
				</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary form2" data-dismiss="modal">Close</button>
				</div>
            </div>
          </div>
        </div>

	  
<!-- -------------------------------------------------->
	<div class="modal fade" id="EdWOFltr" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" >Edit Existing Workorder</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span> </button>
            </div>
			<div class = "row">
				<div class = "col-md-3"></div>
				<div class = "col-md-9">
					<div class="modal-body modalContent">
					<form class = "form1" action = "filterlist_view.php" method = "POST" >
					<div class="form-group">
						<label> Primary Engineer:</label>
							<select name="e1e" id = "e1" required>
							<option name="ee1" id = "ee1" selected>--Select--</option>
								<?php
								include('connect.php');
						if($_SESSION['username']!='%')
						{
							$people="SELECT firstname,lastname,employee.employeeid
									FROM employee, employeeprivlege, team
									WHERE employee.employeeID=employeeprivlege.employeeID
									AND managerid LIKE '".$_SESSION['username']."'
									AND team.employeeid=Employee.EmployeeID
									AND privilegeid='E' ORDER BY lastname,firstname";
							$result=$link->query($people);
						}
						
						else
						{
							$people="SELECT firstname,lastname,employee.employeeid
									FROM employee, employeeprivlege, team
									WHERE employee.employeeID=employeeprivlege.employeeID
									AND team.employeeid=Employee.EmployeeID
									AND privilegeid='E' ORDER BY lastname,firstname";
							$result=$link->query($people);
						}

							while($row = $result->fetch_assoc()) 
								echo"<option value='".$row['employeeid']."'>".$row['firstname']." ".$row['lastname']."</option>";
						?>                    
							</select>
					</div>
					<div class="form-group">
						<label> Secondary Engineer:</label>
						<select name="e2e" id = "e2" >
						<option name="ee2" id = "ee2"  value="" selected>--Select--</option>
						<option value="" >None</option>
							<?php
						if($_SESSION['username']!='%')
						{
							$people="SELECT firstname,lastname,employee.employeeid
									FROM employee, employeeprivlege, team
									WHERE employee.employeeID=employeeprivlege.employeeID
									AND managerid LIKE '".$_SESSION['username']."'
									AND team.employeeid=Employee.EmployeeID
									AND privilegeid='E' ORDER BY lastname,firstname";
							$result=$link->query($people);
						}
						
						else
						{
							$people="SELECT firstname,lastname,employee.employeeid
									FROM employee, employeeprivlege, team
									WHERE employee.employeeID=employeeprivlege.employeeID
									AND team.employeeid=Employee.EmployeeID
									AND privilegeid='E' ORDER BY lastname,firstname";
							$result=$link->query($people);
						}

							while($row = $result->fetch_assoc()) 
								echo"<option value='".$row['employeeid']."'>".$row['firstname']." ".$row['lastname']."</option>";
						?>   
						</select>
					</div>
					<div class="form-group">
						<label> Tertiary Engineer:</label>
						<select name="e3e" id = "e3" >
						<option name="ee3" id = "ee3" value="" selected>--Select--</option>
						<option value="" >None</option>
							<?php
						if($_SESSION['username']!='%')
						{
							$people="SELECT firstname,lastname,employee.employeeid
									FROM employee, employeeprivlege, team
									WHERE employee.employeeID=employeeprivlege.employeeID
									AND managerid LIKE '".$_SESSION['username']."'
									AND team.employeeid=Employee.EmployeeID
									AND privilegeid='E' ORDER BY lastname,firstname";
							$result=$link->query($people);
						}
						
						else
						{
							$people="SELECT firstname,lastname,employee.employeeid
									FROM employee, employeeprivlege, team
									WHERE employee.employeeID=employeeprivlege.employeeID
									AND team.employeeid=Employee.EmployeeID
									AND privilegeid='E' ORDER BY lastname,firstname";
							$result=$link->query($people);
						}

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
						<select name='startTime' id='sTime' required>
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
						<select name='endTime' id='eTime' required>
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
							<select name="loce" id="loc" required>
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
						<input type='radio' id='bulk' name='methe' value='1' checked>
						<label for='bulk'>BULK</label></br>

						<input type='radio' id='geo' name='methe' value='2' >
						<label for='geo'>GEO</label></br>

						<input type='radio' id='fsv' name='methe' value='3' >
						<label for='fsv'>FSV</label></br>

						<input type='radio' id='dbay' name='methe' value='4' >
						<label for='dbay'>DBAY</label></br>
					</div>
					<div class="form-group">
						<label> Description:</label>
						<textarea rows = "10" cols = "25" id = "woDesc" name="woDesc" maxlength="1000"></textarea>
						 <span id="charsleft2">1000</span> Character(s) Remaining
					</div>
					<input type = "hidden" id = "orderID"  name="orderID" required>
					<?php 
					
					echo"<input type='hidden' name='datepicker' value='".filter_var(htmlentities($_POST['datepicker'],  ENT_QUOTES,  'utf-8'),FILTER_SANITIZE_STRING)."'>";
					echo"<input type='hidden' name='filterfirst' value='".filter_var(htmlentities($_POST['filterfirst'],  ENT_QUOTES,  'utf-8'),FILTER_SANITIZE_STRING)."'>";
					echo"<input type='hidden' name='filterlast' value='".filter_var(htmlentities($_POST['filterlast'],  ENT_QUOTES,  'utf-8'),FILTER_SANITIZE_STRING)."'>";
					?>
						<button type="submit" name="submit" class="btn btn-primary subBut">Update</button> 
					</form>
					</div>
					</div>
					</div>
					<div class="modal-footer">
					<form method='POST' action='list_view.php' class = 'hiddenform'><input name='id' type='hidden' id = "orderIDDel"><button class = 'btn btn-danger'>Cancel</button></form>

					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					
					</div>
				</div>
				</div>
			</div> 
    </body>
</html>