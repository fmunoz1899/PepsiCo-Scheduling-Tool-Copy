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
        <nav class="navbar navbar-expand-sm fixed-top nav">
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
                <h1 class="h1_1">Displaying all Scheduled Workorders</h1>
				<h3 class="h1_1">For Today</h3>
            </div>
            
 <?php 
	session_start();
	include('connect.php');
	if(!isset($_SESSION['manager']) || $_SESSION===false)
	{
		header("location:login.php?list_view_no_man_tok");
		exit;
	}
	
	if(isset($_POST['id']) && $_POST['id']!='')
	{
		$id=filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
		$rem=$link->prepare("DELETE workitem, wi_schedule FROM workitem INNER JOIN wi_schedule on wi_schedule.ItemID=workitem.ItemID WHERE workitem.itemid=?");
		$rem->bind_param("i",$id);
		$rem->execute();
	}
	
	if(isset($_POST['sTimel']) && isset($_POST['eTimel']) && isset($_POST['loc']) && isset($_POST['e1']) && isset($_POST['e2']) && isset($_POST['e3']) && isset($_POST['meth']))
	{
		$des=filter_var(htmlentities($_POST['des'],  ENT_QUOTES,  'utf-8'),FILTER_SANITIZE_STRING);
		$curday=date("D",strtotime($_POST['datepicker3']));
		$curdate=date("Y-m-d",strtotime($_POST['datepicker3']));
		$curtime= date('G:i:s');
			
		if($_POST['sTimel']>=$_POST['eTimel'])
			echo"<script>alert('You cannot start at or after the end time');</script>";
		
		//else if((($_POST['datepicker3']==date("m/j/Y") && $_POST['sTimel']<$curtime)) || ($curtime<='06:00:00' || $curtime>='22:00:00'))
		//	echo"<script>alert('You cannnot make it for the past');</script>";
		
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
?>

            <!-- All names will be taken from the database -->
            <div class='row div_space'> 
                <div class='col-md-1'> </div>
                <div class='col-md-4'> 
                    <form class = "form2" method="POST" name="cal_form" id="cal_form" action="filterlist_view.php">
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
                          <input class = "choice" type="radio" name="choice" id = "LV" checked> Work Order List View</input><br>
						   <input class = "choice" type="radio" name="choice" id = "BV"> Blockout List View</input></br>
                          <input class = "choice" type="radio" name="choice" id = "CV"> Calendar View</input>
                      </form>
                  </div>
              </div>
          </div>
          <div class="row "> 
            <div class="col-md-9"> </div>
              <div class="col-md-3">
                <button type="button" class="btn btn-primary mbut" data-toggle="modal" data-target="#newWO"> Create New Workorder</button>
              </div>
          </div>
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
              <form class = "form1" action = "list_view.php" method = "POST" >
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
			</select>             
			</div>
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
<?php
//Make sure to change this so that it only shows the engineers
	include('connect.php');
	$curdate=date("Y-m-d");
	$curday=substr(date("r"),0,3);


				
		
	
	$sqlWI=$link->prepare("SELECT firstName, lastName, workitem.ItemID, LocationName, Method, StartTime, EndTime, Date, preferrede1, preferrede2, description, wi_schedule.actualendtime
			FROM workitem,employee,delivery,wi_schedule,location, employeeprivlege
			where workitem.EmployeeID=employee.EmployeeID 
			and workitem.ItemID=wi_schedule.ItemID 
			and workitem.LocationID=location.LocationID 
			and workitem.DeliveryID=delivery.DeliveryID
			and workitem.EmployeeID=employeeprivlege.EmployeeID
            and PRIVILEGEid='E'
			and Date=?
			ORDER BY Date, StartTime, EndTime");
	$sqlWI->bind_param("s",$curdate);
	$sqlWI->execute();
	$result=$sqlWI->get_result();
	
	//$result=$link->query($sqlWI);
	
	
	echo'<div class="row tbl_space">';

    echo "<div class='col-md-1'> </div>";
        echo "<div class = 'col-md-10'>";
          echo " <table> ";
          echo "<tr>
					<th> Edit </th>
					<th> Remove </th>
					<th> Item ID </th> <!--which to show scheduleID or itemID-->
					<th> Primary Employee </th>
					<th> Secondary Employee </th>
					<th> Tertiary Employee </th>
					<th> Location </th>
					<th> Delivery Method </th>
					<th> Start Time </th>
					<th> End Time </th>
					<th> Date </th>
					<th> Actual End Time </th>
				  </tr>";
			while($row = $result->fetch_assoc()) 
			{
				echo "<tr>
				<td><button>Edit</button></td>
				<td><form method='POST' action='list_view.php'><input name='id' type='hidden' value=".$row['ItemID']."><button>Remove</button></form></td>
				<td>". $row["ItemID"]. "</td>
					<td>".$row["firstName"]. " " . $row["lastName"] . "</td>";
					$name1=$link->prepare("SELECT firstname, lastname
										   FROM employee
										   WHERE employeeID=?");
					$name1->bind_param("i",$row['preferrede1']);
					$name1->execute();
					$temp1=$name1->get_result();
					$row2 = mysqli_fetch_array($temp1);
					
echo"				<td>".$row2[0]. " " . $row2[1] . "</td>";

					$name1=$link->prepare("SELECT firstname, lastname
										   FROM employee
										   WHERE employeeID=?");
					$name1->bind_param("i",$row['preferrede2']);
					$name1->execute();
					$temp1=$name1->get_result();
					$row2 = mysqli_fetch_array($temp1);
					
echo"				<td>".$row2[0]. " " . $row2[1] . "</td>

					<td>" . $row["LocationName"]."</td>
					<td>" . $row["Method"]."</td>
					<td>" . date('g:ia',strtotime($row["StartTime"]))."</td>
					<td>" . date('g:ia',strtotime($row["EndTime"]))."</td>
					<td>" . date('n/j/Y',strtotime($row["Date"]))."</td>
					<td>" . date('g:ia',strtotime($row["actualendtime"]))."</td>
				</tr>";
			}
	echo'</div>';
	mysqli_close($link);
?>
      <!------------------------------------->


            
    </body>
</html>