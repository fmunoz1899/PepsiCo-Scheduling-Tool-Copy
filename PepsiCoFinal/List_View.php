<!DOCTYPE HTML>
<html> 
<?php 
	date_default_timezone_set("America/New_York");
	session_start();
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
                <h1 class="font-weight-bold text-center">Manager Workorder List View</h1>
                <img class = "img1"  src = "pepsi.png"> 
                <hr class = "hr1">
            </div>

            <div> 
                <h1 class='h1_1'>Displaying all Scheduled Workorders</h1>
				<h3 class="h1_1">For Today</h3>
            </div>
			<form method='POST' action='list_view.php'>
				 <label> Filter by reporting manager:</label>
					 <select name='filter'>
					 <?php
					 include('connect.php');
					 
					 if(!isset($_SESSION['manager']) || $_SESSION===false)
					{
						header("location:login.php?list_view_no_man_tok");
						exit;
					}
						$mans="SELECT firstname, lastname, employee.employeeid
							   FROM employee, employeeprivlege
							   WHERE employee.employeeID=employeeprivlege.employeeID
							   AND privilegeid='M'
							   ORDER BY lastname,firstname";
					   $result=$link->query($mans);
					 
						if(isset($_POST['filter']))
						{
							echo"<option value='%'>N/A</option>";
							$_SESSION['username']=$_POST['filter'];
						}
						
						else
							echo"<option value='%' selected>N/A</option>";
						
						while($row=$result->fetch_assoc())
						{			
							
								if($row['employeeid']==$_SESSION['username'])
									echo"<option value='".$row['employeeid']."'selected>".$row['firstname']." ".$row['lastname']."</option>";
								else
									echo"<option value='".$row['employeeid']."'>".$row['firstname']." ".$row['lastname']."</option>";
							
															
						}
					 ?>
					 
					 </select>&nbsp;
					  <button class = "btn btn-primary" type="submit" name="submit">Filter</button> 
					  </form>
            
 <?php 
	
	include('connect.php');
	$time= $_SERVER['REQUEST_TIME'];
		$timeout=300;
	
			if(isset($_SESSION['last'])===true && ($time-$_SESSION['last'])>$timeout)
			{
				header("location:login.php?st=".rand());
				exit;
			}
			
		$_SESSION['last']=$time;
	
	//to delete a workorder
	if(isset($_POST['id']) && $_POST['id']!='')
	{
		$id=filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
		$rem=$link->prepare("DELETE workitem, wi_schedule FROM workitem INNER JOIN wi_schedule on wi_schedule.ItemID=workitem.ItemID WHERE workitem.itemid=?");
		$rem->bind_param("i",$id);
		$rem->execute();
	}

	
	//to update a workorder
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
		
		else if($curdate==date("Y-m-d") && $startTime<=$curtime )
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
 //to insert a single workorder
	if(isset($_POST['sTimel']) && isset($_POST['eTimel']) && isset($_POST['loc']) && isset($_POST['e1']) && isset($_POST['meth']) && !isset($_POST['reoccurring']))
	{
		$des=filter_var(htmlentities($_POST['des'],  ENT_QUOTES,  'utf-8'),FILTER_SANITIZE_STRING);
		$curday=date("D",strtotime($_POST['datepicker3']));
		$curdate=date("Y-m-d",strtotime($_POST['datepicker3']));
		$curtime= date('H:i:s');
			
		if($_POST['sTimel']>=$_POST['eTimel'])
			echo"<script>alert('You cannot start at or after the end time');</script>";
		
		else if($curdate==date("Y-m-d") && $_POST['sTimel']<=$curtime)
			echo"<script>alert('You cannnot make it for the past');</script>";
		
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
//pussy
						$please=$link->prepare("INSERT INTO wi_schedule
											   VALUES (default,?,default,?,?,?,default)");
						$please->bind_param("isss",$num,$_POST['sTimel'],$_POST['eTimel'],$curdate);
						$please->execute();
					}
					
		}
	}
	//to insert a reoccurring workorder
	else if(isset($_POST['sTimel']) && isset($_POST['eTimel']) && isset($_POST['loc']) && isset($_POST['e1']) && isset($_POST['meth']) && isset($_POST['reoccurring']) && isset($_POST['datepicker8']))
	{
		$today=date("Y-m-d");
		$curdate=date("Y-m-d", strtotime($_POST['datepicker3']));
		$enddate=date("Y-m-d", strtotime($_POST['datepicker8']));
		$curtime= date('H:i:s');
		$entered=false;
		$list2=array();
		$dates='\n';
		
			if($_POST['sTimel']>=$_POST['eTimel'])
			{
				echo"<script>alert('You cannot start at or after the end time');</script>";
				$entered=true;
			}
			
			else if(($curdate==date("Y-m-d") && $_POST['sTimel']<=$curtime) || $curdate>=$enddate)
			{
				echo"<script>alert('You cannnot make it for the past');</script>";
				$entered=true;
			}
			
			else if($_POST['e2']==$_POST['e3'] && $_POST['e3']!='')
			{
				echo"<script>alert('You cannnot have the same secondary and tertiary employee');</script>";
				$entered=true;
			}
			
			else if($_POST['e1']==$_POST['e3'])
			{
				echo"<script>alert('You cannnot have the same primary and tertiary employee');</script>";
				$entered=true;
			}
			else if($_POST['e1']==$_POST['e2'])
			{
				echo"<script>alert('You cannnot have the same primary and secondary employee');</script>";
				$entered=true;
			}
		
		if(!$entered)
		{
			$count=0;
			while($curdate<=$enddate)
			{
				
				$des=filter_var(htmlentities($_POST['des'],  ENT_QUOTES,  'utf-8'),FILTER_SANITIZE_STRING);
				$curday=date("D",strtotime($curdate));
				
				
				
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
							
							if($rowh[0]!=0 || $rowb[0]!=0 || $roww[0]!=0 ||($curdate==$today && $_POST['sTimel']<=$curtime))
								array_push($list2,$curdate);
	
							else if($rowh[0]==0 && $rowb[0]==0 && $roww[0]==0 && !($curdate==$today && $_POST['sTimel']<=$curtime))
							{
								$count++;
								$worki=$link->prepare("INSERT INTO workitem 
													VALUES (default,?,?,?,?,default,?,?)");
								$worki->bind_param("iiisii",$_POST['e1'],$_POST['loc'],$_POST['meth'],$des,$_POST['e2'],$_POST['e3']);
								$worki->execute();

								$max="SELECT MAX(itemid) 
									FROM workitem";
								$result=$link->query($max);
								$row = $result->fetch_assoc();
								$num=$row['MAX(itemid)'];
	//pussy
								$please=$link->prepare("INSERT INTO wi_schedule
													   VALUES (default,?,default,?,?,?,?)");
								$please->bind_param("issss",$num,$_POST['sTimel'],$_POST['eTimel'],$curdate,$enddate);
								$please->execute();
							}
							
				

				$curdate=date("Y-m-d", strtotime($curdate.'+7days'));
			}
			
			foreach($list2 as $id)
				$dates.=date("m/d/Y",strtotime($id)).'\n';
				
			$cant="SELECT firstname, lastname
								   FROM employee
								   WHERE employeeid=".$_POST['e1'];
							$result=$link->query($cant);
							
							$row = $result->fetch_assoc();

			if(count($list2)!=0 && $count!=0)
				echo"<script>alert('These are the dates ".$row['firstname']." ".$row['lastname']." are unavalible: ".$dates.'\n'."The rest have been scheduled!');</script>";
			else if(count($list2)!=0 && $count==0)
				echo"<script>alert('These are the dates ".$row['firstname']." ".$row['lastname']." are unavalible: ".$dates.'\n'."');</script>";
			else
				echo"<script>alert('There were no conflicts!');</script>";
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
                      <input name="datepicker" type="text" id="datepicker" readonly='true'>
                      <button type="submit" name="submit" class="btn btn-primary">Filter</button>
                  </form > 
				  
				
				 <!-- <button type="submit" name="clear" id="clear" class="btn btn-primary">Clear All</button>
				  <br>If no date selected with first and/or last name, date will default today-->
                </div>
	

   

                <div class="col-md-1"></div>
				 <div class="col-md-1"> 
				 
				  </div>
				  <div class="col-md-1"></div>
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
							FROM employee, employeeprivlege, team
							WHERE employee.employeeID=employeeprivlege.employeeID
							AND managerid LIKE '".$_SESSION['username']."'
							AND team.employeeid=Employee.EmployeeID 
							AND privilegeid='E' ORDER BY lastname,firstname";
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
							FROM employee, employeeprivlege, team
							WHERE employee.employeeID=employeeprivlege.employeeID
							AND managerid LIKE '".$_SESSION['username']."'
							AND team.employeeid=Employee.EmployeeID 
							AND privilegeid='E' ORDER BY lastname,firstname";
					$result=$link->query($people);

					while($row = $result->fetch_assoc()) 
						echo"<option value='".$row['employeeid']."'>".$row['firstname']." ".$row['lastname']."</option>";
?>
                  </select>
              </div>
              <div class="form-group">
                <label> Tertiary Engineer:</label><br>
                <select name="e3" id = "e3" >
				 <option value="" selected>None</option>
 <?php				
                    $people="SELECT firstname,lastname,employee.employeeid
							FROM employee, employeeprivlege, team
							WHERE employee.employeeID=employeeprivlege.employeeID
							AND managerid LIKE '".$_SESSION['username']."'
							AND team.employeeid=Employee.EmployeeID 
							AND privilegeid='E' ORDER BY lastname,firstname";
					$result=$link->query($people);

					while($row = $result->fetch_assoc()) 
						echo"<option value='".$row['employeeid']."'>".$row['firstname']." ".$row['lastname']."</option>";
?>  
                  </select>
              </div>
              <div class="form-group">
                  <label> Date:</label>
                   <input name="datepicker3" type="text" id="datepicker3" readonly='true'><br>
				   <input type="checkbox" id="reoccurring" name="reoccurring" value="yes">
					<label for="reoccurring"> Reoccurring</label>
              </div>
			   <div class="form-group">
                  <label> End Date:</label>
                   <input name="datepicker8" type="text" id="datepicker8" readonly='true' disabled = "true">
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
				  <textarea rows = "10" maxlength="1000" cols = "25" name="des" id = "des"></textarea>
				  <span id="charsleft">1000</span> Character(s) Remaining
              </div>
                <button type="submit" name="submit" class="btn btn-primary subBut">Schedule</button> 
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              
            </div>
          </div>
        </div>
      </div>
	  

      <!------------------------------------->
	   <!--------------- Modal Code To Edit -------------->
	   <div class="modal fade" id="EdWO" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" >Edit Existing Workorder</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span> </button>
            </div>
            <div class="modal-body modalContent">
              <form class = "form1" action = "list_view.php" method = "POST" >
              <div class="form-group">
                <label> Primary Engineer:</label>
                    <select name="e1e" id = "e1" required>
                      <option id = "ee1" value = "" selected>--Select--</option>
						<?php
							$people="SELECT firstname,lastname,employee.employeeid
									FROM employee, employeeprivlege, team
									WHERE employee.employeeID=employeeprivlege.employeeID
									AND managerid LIKE '".$_SESSION['username']."'
									AND team.employeeid=Employee.EmployeeID
									AND privilegeid='E' ORDER BY lastname,firstname";
							$result=$link->query($people);

							while($row = $result->fetch_assoc()) 
								echo"<option value='".$row['employeeid']."'>".$row['firstname']." ".$row['lastname']."</option>";
						?>                     
                    </select>
					
              </div>
              <div class="form-group">
                <label> Secondary Engineer:</label>
                <select name="e2e" id = "e2" >
				 <option name="ee2" id = "ee2"  value="" selected>--Select--</option>
				 <option value=''>None</option>
					<?php				
						$people="SELECT firstname,lastname,employee.employeeid
								FROM employee, employeeprivlege, team
								WHERE employee.employeeID=employeeprivlege.employeeID
								AND managerid LIKE '".$_SESSION['username']."'
								AND team.employeeid=Employee.EmployeeID
								AND privilegeid='E' ORDER BY lastname,firstname";
						$result=$link->query($people);

						while($row = $result->fetch_assoc()) 
							echo"<option value='".$row['employeeid']."'>".$row['firstname']." ".$row['lastname']."</option>";
					?>
                  </select>
              </div>
              <div class="form-group">
                <label> Tertiary Engineer:</label>
                <select name="e3e" id = "e3" >
				 <option name="ee3" id = "ee3" value="" selected>--Select--</option>
				 <option value=''>None</option>
 					<?php				
						$people="SELECT firstname,lastname,employee.employeeid
								FROM employee, employeeprivlege, team 
								WHERE employee.employeeID=employeeprivlege.employeeID
								AND managerid LIKE '".$_SESSION['username']."'
								AND team.employeeid=Employee.EmployeeID
								AND privilegeid='E' ORDER BY lastname,firstname";
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
                  <select name='startTime' id='sTime' required>
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
                  <label> End Time:</label>
				<select name='endTime' id='eTime' required>
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
				<input type='radio' id='bulkEdit' name='methe' value='1'>
				<label for='bulkEdit'>BULK</label></br>

				<input type='radio' id='geoEdit' name='methe' value='2' >
				<label for='geoEdit'>GEO</label></br>

				<input type='radio' id='fsvEdit' name='methe' value='3' >
				<label for='fsvEdit'>FSV</label></br>

				<input type='radio' id='dbayEdit' name='methe' value='4' >
				<label for='dbayEdit'>DBAY</label></br>
              </div>
              <div class="form-group">
                <label> Description:</label>
                  <textarea rows = "10" maxlength="1000" cols = "25" id = "woDesc" name="woDesc"></textarea>
				  <span id="charsleft2">1000</span> Character(s) Remaining
              </div>
			  <input type = "hidden" id = "orderID"  name='orderID' required>
                <button type="submit" name="submit" class="btn btn-primary subBut">Update</button> 
              </form>
            </div>
            <div class="modal-footer">
				<form method='POST' action='list_view.php' class = 'hiddenform'><input name='id' type='hidden' id = "orderIDDel"><button class = 'btn btn-danger'>Cancel</button></form>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              
            </div>
          </div>
        </div>
      </div>
	  

      <!------------------------------------->
	  
	 <!--------------- Modal Code For Details -------------->
	 <div class="modal fade" id="Details" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" >View Workorder Details</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span> </button>
            </div>
            <div class="modal-body modalContent">
              <form class = "form1" action = "cal_view.php" method = "POST" >
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
                <label> Actual End Time:</label>
                  <input type = "text" rows = "10" cols = "25" id = "AETDet"  readonly='true'>
              </div>
              <div class="form-group">
                <label> Description:</label>
                  <textarea rows = "10" cols = "25" id = "woDescDet" name="woDesc" readonly='true'></textarea>
              </div>
			  
              </form>
            </div>
            <div class="modal-footer">
				<button type="button" class="btn btn-secondary form2" data-dismiss="modal">Close</button>

			</div>
			
                
              
            </div>
          </div>
        </div>
      </div>
	  
<!-- ----------------------------------------------- -->
<?php

	include('connect.php');
	$curdate=date("Y-m-d");
	$curday=substr(date("r"),0,3);
	$curtime=date("H:i:s");
	
				
		
	
	$sqlWI=$link->prepare("SELECT firstName, lastName, workitem.ItemID, LocationName, Method, StartTime, EndTime, Date, preferrede1, preferrede2, description, wi_schedule.actualendtime, employee.employeeID, location.locationid
			FROM workitem,employee,delivery,wi_schedule,location, employeeprivlege, team
			where workitem.EmployeeID=employee.EmployeeID 
			and workitem.ItemID=wi_schedule.ItemID 
			and workitem.LocationID=location.LocationID 
			and workitem.DeliveryID=delivery.DeliveryID
			and workitem.EmployeeID=employeeprivlege.EmployeeID
            and PRIVILEGEid='E'
			and managerid LIKE '".$_SESSION['username']."'
			and team.employeeid=Employee.EmployeeID
			and Date=?
			ORDER BY Date, StartTime, EndTime");
	$sqlWI->bind_param("s",$curdate);
	$sqlWI->execute();
	$result=$sqlWI->get_result();
	
	
	
	echo'<div class="row tbl_space">';

    echo "<div class='col-md-1'> </div>";
        echo "<div class = 'col-md-10'>";
          echo " <table> ";
          echo "<tr>
					<th> Edit </th>
					<th style='display:none'> Item ID </th> 
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
				if($row['EndTime']>$curtime)
					echo"<td><button class='btn btn-success EditWO' type='button' data-toggle='modal' name='editbut' data-target='#EdWO' value='" . $row['ItemID'] . "'>Edit</button>";
				else
					echo"<td><button class='btn btn-primary WODetailsLV' type='button' data-toggle='modal' data-target='#Details' value='" . $row['ItemID'] . "'>COMPLETED</button>";			
				
				echo"</td>
				
				
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
						echo"<td style = 'display:none' id = ".$info."sid>".$row2['employeeid']."</td>"; //might need to aly with location of line
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
						echo"<td style = 'display:none' style = 'display:none' id = ".$info."tid>".$row2['employeeid']."</td>";			
					}
					else
					
						echo"<td id = ".$info."tname>None</td>";
					
						
					
					echo"<td class = 'descCell' id = ".$info."desc>" . $row["description"]."</td>";
	/*here*/				echo"<td  style = 'display:none' id = ".$info."lid>".$row['locationid']."</td> 
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
    </body>

</html>