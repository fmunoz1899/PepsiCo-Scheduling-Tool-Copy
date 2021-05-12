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

if(!isset($_SESSION['manager']) || $_SESSION['manager']===false)
{
	header('location:login.php?cal_man_no_tok');
	exit;
}

	if(isset($_POST['orderID']) && !isset($_POST['CalWOID']))
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
									AND wi_schedule.itemid!=?			
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
						$upwo=$link->prepare("UPDATE wi_schedule
											  SET starttime=?,
											  endtime=?,
											  date=?
											  WHERE itemid=?");
						$upwo->bind_param("sssi",$startTime,$endTime,$curdate,$_POST['orderID']);
						$upwo->execute();
						
						$upwo2=$link->prepare("UPDATE workitem
											   SET employeeID=?,
											   locationID=?,
											   deliveryid=?,
											   description=?,
											   preferrede1=?,
											   preferrede2=?
											   WHERE itemid=?");
						$upwo2->bind_param("iiisiii",$_POST['e1e'],$_POST['loce'],$_POST['methe'],$woDesc,$_POST['e2e'],$_POST['e3e'],$_POST['orderID']);
						$upwo2->execute();
					}
					
		}

}

if(isset($_POST['EmpID']) && isset($_POST['BOtimeID']) && !isset($_POST['CalWOID']))
{
	$curtime= date('H:i:s');
	$curday=date("D",strtotime($_POST['datepicker6']));
	$curdate=date("Y-m-d",strtotime($_POST['datepicker6']));
	$startTimeBO=date("H:i:s", strtotime($_POST['startTimeBO']));
	$endTimeBO=date("H:i:s", strtotime($_POST['endTimeBO']));
	
	$reason=filter_var(htmlentities($_POST['reasonEditBO'],  ENT_QUOTES,  'utf-8'),FILTER_SANITIZE_STRING);
	
		if($startTimeBO>=$endTimeBO)
			echo"<script>alert('You cannot start at or asdafter the end time');</script>";
		
		else if($curdate==date("Y-m-d") && $startTimeBO<=$curtime)
			echo"<script>alert('You cannnot make it for the past');</script>";
		
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
				$checkhours->bind_param("isss",$_POST['EmpID'],$curday,$startTimeBO,$endTimeBO);
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
								AND blackoutID!=?
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
				$checkblock->bind_param("iisssss",$_POST['EmpID'],$_POST['BOtimeID'],$curdate,$startTimeBO,$endTimeBO,$startTimeBO,$endTimeBO);
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
				$checkwork->bind_param("isssss",$_POST['EmpID'],$curdate,$startTimeBO,$endTimeBO,$startTimeBO,$endTimeBO);
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
					$upblock=$link->prepare("UPDATE blackout
											 SET starttime=?,
											 endtime=?,
											 bdate=?,
											 reason=?
											 WHERE blackoutID=?
											 AND employeeID=?");
					$upblock->bind_param("ssssii",$startTimeBO,$endTimeBO,$curdate,$reason,$_POST['BOtimeID'],$_POST['EmpID']);
					$upblock->execute();
				}
		}
		
}

if(isset($_POST['CalWOID']))
{
	$worem=$link->prepare("DELETE FROM wi_schedule
						   WHERE itemid=?");
	$worem->bind_param("i",$_POST['CalWOID']);
	$worem->execute();
	
	$worem2=$link->prepare("DELETE FROM workitem
						   WHERE itemid=?");
	$worem2->bind_param("i",$_POST['CalWOID']);
	$worem2->execute();
}

if(isset($_POST['BOtimeID2']) && isset($_POST['EmpID2']))
{
	$rembo=$link->prepare("DELETE FROM blackout
						   WHERE blackoutID=?
						   AND employeeID=?");
	$rembo->bind_param("ii",$_POST['BOtimeID2'],$_POST['EmpID2']);
	$rembo->execute();
}
//to insert as a single workorder
if(isset($_POST['sTimel']) && isset($_POST['eTimel']) && isset($_POST['loc']) && isset($_POST['e1']) && isset($_POST['e2']) && isset($_POST['e3']) && !isset($_POST['reoccurring']))
	{
		$des=filter_var(htmlentities($_POST['des'],  ENT_QUOTES,  'utf-8'),FILTER_SANITIZE_STRING);
		$curday=date("D",strtotime($_POST['datepicker3']));
		$curdate=date("Y-m-d",strtotime($_POST['datepicker3']));
		$curtime= date('H:i:s');
			
		if($_POST['sTimel']>=$_POST['eTimel'])
			echo"<script>alert('You cannot start at or after the end time');</script>";
		
		else if($curdate==date("Y-m-d") && $_POST['sTimel']<$curtime)
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
	
	//to insert as a reoccurring workorder
	else if(isset($_POST['sTimel']) && isset($_POST['eTimel']) && isset($_POST['loc']) && isset($_POST['e1']) && isset($_POST['meth']) && isset($_POST['reoccurring']) && isset($_POST['datepicker9']))
	{
		$today=date("Y-m-d");
		$curdate=date("Y-m-d", strtotime($_POST['datepicker3']));
		$enddate=date("Y-m-d", strtotime($_POST['datepicker9']));
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

		
if((isset($_POST['forall']) || isset($_POST['who'])) && isset($_POST['sTimec']) && isset($_POST['eTimec']))
{
	$curtime= date('H:i:s');
	$curday=date("D",strtotime($_POST['datepicker4']));
	$curdate=date("Y-m-d",strtotime($_POST['datepicker4']));
	
	$reason=filter_var(htmlentities($_POST['reason'],  ENT_QUOTES,  'utf-8'),FILTER_SANITIZE_STRING);
	
		if($_POST['sTimec']>=$_POST['eTimec'])
			echo"<script>alert('You cannot start at or after the end time');</script>";
		
		else if(($curdate==date("Y-m-d") && $_POST['sTimec']<=$curtime))
			echo"<script>alert('You cannnot make it for the past');</script>";

		
		else //if the start and end time is not in the past
		{
			$list=array();
			$people='\n';
			
			
			
			if(isset($_POST['forall'])) //blockout for everyone
			{
				$allid="SELECT employee.employeeid
						FROM employee, employeeprivlege, ahours, team
						WHERE employee.EmployeeID=employeeprivlege.EmployeeID
						AND PRIVILEGEid='E'
						AND managerid LIKE '".$_SESSION['username']."'
						AND team.employeeid=Employee.EmployeeID
						AND ahours.EmployeeID=employeeprivlege.EmployeeID
						AND dayid='".$curday."'";
				
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
				
				if(count($list)!=0)
					echo"<script>alert('These people are unavailable: ".$people.'\n'."Everyone else has been blocked out');</script>";
				else
					echo"<script>alert('Everyone was blocked out for that date and time');</script>";
				
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
                <h1 class="font-weight-bold text-center">Manager Calendar View</h1>
                <img class = "img1"  src = "pepsi.png"> 
                <hr class = "hr1">
            </div>

            <div>
                <h1 class="h1_1">Displaying all Scheduled Workorders</h1>
				 <h3 class="h1_1">For Today</h3>
				 <form method='POST' action='cal_view.php'>
				 <label> Filter by reporting manager:</label>
					 <select name='filter'>
					 <?php

					 include('connect.php');
						$mans="SELECT firstname, lastname, employee.employeeid
							   FROM employee, employeeprivlege
							   WHERE employee.employeeID=employeeprivlege.employeeID
							   AND privilegeid='M'
							   ORDER BY lastname, firstname";
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
                      <input name="datepicker" type="text" id="datepicker" readonly='true'>
                      <button type="submit" name="submit" class="btn btn-primary">Filter</button>
                  </form > 
				 <!-- <button type="submit" name="clear" id="clear" class="btn btn-primary">Clear All</button>
				   <br>If no date selected with first and/or last name, date will default today-->
                
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
		  <div class="row "> 
		  <div class="col-md-1"></div>
            <div class="col-md-2"> 
				<table class = "colKey"><td class = "boCol"></td><td>Blockout</td></table>
				<table class = "colKey"><td class = "woCol"></td><td>Workorder</td></table>	
				<table class = "colKey"><td class = "noCol"></td><td>Not Scheduled</td></table>
			</div>
              <div class="col-md-3">
               
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
                    <input type="checkbox" id="reoccurringCal" name="reoccurring" value="yes">
					<label for="reoccurring"> Reoccurring</label>
              </div>
			   <div class="form-group">
                  <label> End Date:</label>
                   <input name="datepicker9" type="text" id="datepicker9" readonly='true' disabled = "true">
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
				<textarea rows = "10" cols = "25" name="des" id = "des" maxlength="1000"></textarea>
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
						FROM employee, employeeprivlege, team
						WHERE privilegeid='E'
						and employee.employeeid=employeeprivlege.employeeid
						AND managerid LIKE '".$_SESSION['username']."'
						AND team.employeeid=Employee.EmployeeID
						ORDER BY lastname, firstname";
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
                <input type="text" name="reason" maxlength="100" id="reason">
				<span id="reasonsleft">100</span> Character(s) Remaining
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
 <!--------------- Modal Code To Edit -------------->
	   <div class="modal fade" id="EdWOCal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" >Edit Existing Workorder</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span> </button>
            </div>
            <div class="modal-body modalContent">
              <form class = "form1" action = "cal_view.php" method = "POST" >
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
                  <textarea rows = "10" cols = "25" id = "woDesc" name="woDesc" maxlength="1000"></textarea>
				  <span id="charsleft2">1000</span> Character(s) Remaining
              </div>
			  <input type = "hidden" id = "orderID"  name='orderID' required>
                <button type="submit" name="submit" class="btn btn-primary subBut">Update</button> 
              </form>
            </div>
			
            <div class="modal-footer">
			<form method='POST' action='cal_view.php' class = 'hiddenform form2'>
				<input name='CalWOID' type='hidden' id = "CalWOID" value=""><button class = 'btn btn-danger'>Cancel</button>
			</form>
				<button type="button" class="btn btn-secondary form2" data-dismiss="modal">Close</button>

				
			</div>
			
                
              
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
			<div class = "row">
				<div class = "col-md-4"></div>
				<div class = "col-md-9">
					  <form action = "cal_view.php" method = "POST">
					  <div class="form-group">
						  <label>Start Time:</label><br>
						 <select name='startTimeBO' required>
						<option  id='startTimeBO'  selected>--Time--</option>
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
						<label>End Time:</label><br>
						 <select name='endTimeBO' required>
						<option  id='endTimeBO'   selected>--Time--</option>
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
						<label>Date:</label><br>
						<input name="datepicker6" type="text" id="datepicker6" readonly='true'>
					</div>
					<div class="form-group">
						<label>Who:</label><br>
						<input type = "text" id='whoEditBO' name="whoEditBO" readonly='true'>
					</div>
					<div class="form-group">
						<label>Reason:</label><br>
						<input maxlength="100" type="text" name="reasonEditBO" id="reasonEdit">
						<span id="reasonsleft2">100</span> Character(s) Remaining
					</div>
					<input type = "hidden" id = "EmpIDBO"  name="EmpID" required>
					<input type = "hidden" id = "BOtimeID"  name="BOtimeID" required>
						<button type="submit" name="submit" class="btn btn-primary">Edit</button> 
					  </form>
            
		
					<div class="modal-footer">
					  
					  
					  <form method='POST' action='cal_view.php' class = 'hiddenform form2'>
					  <input type='hidden' name = "BOtimeID2" id = "BOtimeID2" value = "" required>
					  <input type = "hidden" id = "EmpIDBO2"  name="EmpID2" value = "" required>
					  <button type="submit" class = 'btn btn-danger'>Remove</button></form>
					  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					</div>  
				</div>
			</div>
          </div>
        </div>
		</div>
      </div>
	  

      <!------------------------------------->
	   <!--------------- Modal Code to View Details Blockout Time-------------->
	<div class="modal fade" id="DetBO" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">View Blockout Details</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
			
            <div class="modal-body">
			<div class = "row">
			
			<div class = "col-md-9">
              <form>
              <div class="form-group">
                  <label>Start Time:</label><br>
				  <input type = "text"  id='startTimeDetBO' readonly='true'>
              </div>
                       
              <div class="form-group">
                <label>End Time:</label><br>
				<input type = "text"  id='endTimeDetBO' readonly='true'>
            </div>  
			<div class="form-group">
                <label>Date:</label><br>
				<input type = "text"  id='datepicker6DetBO' readonly='true'>
            </div>
			<div class="form-group">
                <label>Who:</label><br>
				<input type = "text"  id='whoEditDetBO' readonly='true'>
            </div>
			<div class="form-group">
                <label>Reason:</label><br>
				<input type = "text"  id='reasonEditDetBO' readonly='true'>
            </div>
              </form>
            </div>
			</div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              
			</div>

          </div>
        </div>
      </div>
	  </div>

      <!------------------------------------->
	  
	  
<?php
include('connect.php');
date_default_timezone_set("America/New_York"); //timezone will need to change before giving 

$today=date("Y-m-d");
		$curday=substr(date("r"),0,3); //this gets me the day abbrivated for the ahours
		$curdate=date("Y-m-d"); //this gets the current date and formats it in yyy-mm-dd
echo"
      <div class = 'row div_space '> 
        <div class = 'col-md-1'></div>
        <div class = 'col-md-10  cal_bg'>
        <div id='items'>";
		
		$empID=$link->prepare("SELECT employee.employeeID
		FROM employee, ahours, employeeprivlege, team
		WHERE employee.employeeID=ahours.employeeID
		and ahours.EmployeeID=employeeprivlege.EmployeeID
		and PrivilegeID='E'
		and DayID=?
		and managerid LIKE ?
		and team.employeeid=Employee.EmployeeID
		ORDER BY lastname, firstname");
		
		$empID->bind_param("ss",$curday,$_SESSION['username']);									
		$empID->execute();
		$result=$empID->get_result();
		
		while($rowID=$result->fetch_assoc())
		{
			$curtime= date('H:i:s');
			$first=true; //check if it's first td in workorder
			$first2=true; //check if first td in blockout
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
				
				$items="SELECT starttime, endtime, actualendtime, workitem.itemid, preferrede1, preferrede2, description, locationID, deliveryid, date
				FROM wi_schedule, workitem 
				WHERE wi_schedule.ItemID=workitem.ItemID 
				AND workitem.employeeID=".$rowID['employeeID']." 
				AND wi_schedule.Date='".$curdate."' 
				ORDER BY StartTime";
				$result2=mysqli_query($link,$items);
				$row = mysqli_fetch_array($result2);
				
				$ahours="SELECT StartTime, endtime 
				FROM ahours 
				WHERE ahours.EmployeeID=".$rowID['employeeID']." 
				AND ahours.DayID='".$curday."' 
				ORDER BY StartTime";
				$result3=mysqli_query($link,$ahours);
				$rowahours = mysqli_fetch_array($result3);
				
				$blackout="SELECT starttime, endtime, blackoutID, reason, BDate
				FROM blackout 
				WHERE blackout.BDate='".$curdate."' 
				AND blackout.EmployeeID=".$rowID['employeeID']." 
				ORDER BY StartTime";
				$result4=mysqli_query($link,$blackout);
				$rowblock = mysqli_fetch_array($result4);
			
				
				
				while($time!='22:00:00') //time day ends 
				{
					$entered=false; //if there is something during that specific time, otherwise put blank
 echo"	            <tr class = 'row_height'>";
					echo"<td class = 'hour'>&nbsp;".date('g:ia',strtotime($time))."&nbsp; </td>";
					
					if($time<$rowahours[0] || $time>=$rowahours[1]) //to show when an employee is or is not working
					{
						if(strtotime('+15 minutes',strtotime($rowahours[0]))==strtotime($rowahours[1]))
							echo "<td class='no_work_15_min'></td>";
						else if(strtotime('+15 minutes',strtotime($time))==strtotime($rowahours[0]))
							echo "<td class='no_work_top'>  </td>";
						else
							echo "<td class='no_work'>  </td>";
						
						$entered=true;
					}
					
					if(mysqli_data_seek($result2,$counter) && $time>=$row[1]) //increments the workorders manually 
					{
						mysqli_data_seek($result2,$counter);
						$row = mysqli_fetch_array($result2);
						$counter+=1;
						$first=true;
					}
					
					if($time>=$row[0] && $time<$row[1] && !$entered) //to show when an employee has a workorder
					{
						if($first)
						{
							if(($curtime>=$row[1] && $today==$curdate) || $today>$row[9]) 
							{
								if(strtotime('+15 minutes',strtotime($row[0]))==strtotime($row[1]))
								{
									$WOCalID = $row[3];
									echo "<td class='sched_work_15_min'>
									<button class='btn btn-primary WODetails' type='button' data-toggle='modal' data-target='#Details' value='" . $row[3] . "'>COMPLETED</button>
										<table style = 'display:none'>
											<tr>
												<td id = ".$WOCalID."name>".$rowsch["firstName"]. " " . $rowsch["lastName"] . "</td>
												<td id = ".$WOCalID."pid>".$rowsch['employeeID']."</td>";
												$name1=$link->prepare("SELECT firstname, lastname, employeeid
													FROM employee
													WHERE employeeID=?");
													$name1->bind_param("i",$row[4]);
													$name1->execute();
													$temp1=$name1->get_result();
													$row2 = mysqli_fetch_array($temp1);
													
													if($row2[0]!='' && $row2[1]!='')
													{
														echo"<td id = ".$WOCalID."sname>".$row2[0]. " " . $row2[1] . "</td>";
														echo"<td id = ".$WOCalID."sid>".$row2['employeeid']."</td>"; //might need to play with location of line
													}
													
													else
														echo"<td id = ".$WOCalID."sname>None</td>";
												$name1=$link->prepare("SELECT firstname, lastname, employeeid
													FROM employee
													WHERE employeeID=?");
													$name1->bind_param("i",$row[5]);
													$name1->execute();
													$temp1=$name1->get_result();
													$row2 = mysqli_fetch_array($temp1);
												
													if($row2[0]!='' && $row2[1]!='')
													{
														echo"<td id = ".$WOCalID."tname>".$row2[0]. " " . $row2[1] . "</td>";
														echo"<td id = ".$WOCalID."tid>".$row2['employeeid']."</td>";			
													}
													else
														echo"<td id = ".$WOCalID."tname>None</td>";
												echo"<td class = 'descCell' id = ".$WOCalID."desc>" . $row[6]."</td>
													<td id = ".$WOCalID."lid>".$row[7]."</td>";
													$locname=$link->prepare("SELECT locationname
															  FROM location 
															  WHERE locationid=?");
													$locname->bind_param("i",$row[7]);
													$locname->execute();
													$temp1=$locname->get_result();
													$rowl=mysqli_fetch_array($temp1);
													echo"<td id = ".$WOCalID."lname>" . $rowl[0]."</td>";
													$meth=$link->prepare("SELECT method
															  FROM delivery 
															  WHERE deliveryid=?");
													$meth->bind_param("i",$row[8]);
													$meth->execute();
													$temp1=$meth->get_result();
													$rowm=mysqli_fetch_array($temp1);
													
													echo"<td id = ".$WOCalID."meth>" . $rowm[0]."</td>
													<td id = ".$WOCalID."stime>" . date('g:ia',strtotime($row[0]))."</td>
													<td id = ".$WOCalID."etime>" . date('g:ia',strtotime($row[1]))."</td>
													<td id = ".$WOCalID."date>" . date('n/j/Y',strtotime($row[9]))."</td>";
											echo"</tr>
										</table>
									</td>";
								}
								else if(strtotime('+15 minutes',strtotime($time))==strtotime($row[1]))
								{
									$WOCalID = $row[3];
									echo "<td class='sched_work_top'>
									<button class='btn btn-primary WODetails' type='button' data-toggle='modal' data-target='#Details' value='" . $row[3] . "'>COMPLETED</button> 
									
										<table style = 'display:none'>
											<tr>
												<td id = ".$WOCalID."name>".$rowsch["firstName"]. " " . $rowsch["lastName"] . "</td>
												<td id = ".$WOCalID."pid>".$rowsch['employeeID']."</td>";
												$name1=$link->prepare("SELECT firstname, lastname, employeeid
													FROM employee
													WHERE employeeID=?");
													$name1->bind_param("i",$row[4]);
													$name1->execute();
													$temp1=$name1->get_result();
													$row2 = mysqli_fetch_array($temp1);
													
													if($row2[0]!='' && $row2[1]!='')
													{
														echo"<td id = ".$WOCalID."sname>".$row2[0]. " " . $row2[1] . "</td>";
														echo"<td id = ".$WOCalID."sid>".$row2['employeeid']."</td>"; //might need to aly with location of line
													}
													
													else
														echo"<td id = ".$WOCalID."sname>None</td>";
												$name1=$link->prepare("SELECT firstname, lastname, employeeid
													FROM employee
													WHERE employeeID=?");
													$name1->bind_param("i",$row[5]);
													$name1->execute();
													$temp1=$name1->get_result();
													$row2 = mysqli_fetch_array($temp1);
												
													if($row2[0]!='' && $row2[1]!='')
													{
														echo"<td id = ".$WOCalID."tname>".$row2[0]. " " . $row2[1] . "</td>";
														echo"<td id = ".$WOCalID."tid>".$row2['employeeid']."</td>";			
													}
													else
														echo"<td id = ".$WOCalID."tname>None</td>";
												echo"<td class = 'descCell' id = ".$WOCalID."desc>" . $row[6]."</td>
													<td id = ".$WOCalID."lid>".$row[7]."</td>";
													$locname=$link->prepare("SELECT locationname
															  FROM location 
															  WHERE locationid=?");
													$locname->bind_param("i",$row[7]);
													$locname->execute();
													$temp1=$locname->get_result();
													$rowl=mysqli_fetch_array($temp1);
													echo"<td id = ".$WOCalID."lname>" . $rowl[0]."</td>";
													$meth=$link->prepare("SELECT method
															  FROM delivery 
															  WHERE deliveryid=?");
													$meth->bind_param("i",$row[8]);
													$meth->execute();
													$temp1=$meth->get_result();
													$rowm=mysqli_fetch_array($temp1);
													
													echo"<td id = ".$WOCalID."meth>" . $rowm[0]."</td>
													<td id = ".$WOCalID."stime>" . date('g:ia',strtotime($row[0]))."</td>
													<td id = ".$WOCalID."etime>" . date('g:ia',strtotime($row[1]))."</td>
													<td id = ".$WOCalID."date>" . date('n/j/Y',strtotime($row[9]))."</td>";
													if($row[2] == "")
													  echo"<td id = ".$WOCalID."aet>" .$row[2]."</td>";
													else
													  echo"	<td id = ".$WOCalID."aet>" . date('g:ia',strtotime($row[2]))."</td>";
											echo"</tr>
										</table>
									</td>";
								}
								else
								{
									$WOCalID = $row[3];
									echo "<td class='sched_work'>
									<button class='btn btn-primary WODetails' type='button' data-toggle='modal' data-target='#Details' value='" . $row[3] . "'>COMPLETED</button>
										<table style = 'display:none'>
											<tr>
												<td id = ".$WOCalID."name>".$rowsch["firstName"]. " " . $rowsch["lastName"] . "</td>
												<td id = ".$WOCalID."pid>".$rowsch['employeeID']."</td>";
												$name1=$link->prepare("SELECT firstname, lastname, employeeid
													FROM employee
													WHERE employeeID=?");
													$name1->bind_param("i",$row[4]);
													$name1->execute();
													$temp1=$name1->get_result();
													$row2 = mysqli_fetch_array($temp1);
													
													if($row2[0]!='' && $row2[1]!='')
													{
														echo"<td id = ".$WOCalID."sname>".$row2[0]. " " . $row2[1] . "</td>";
														echo"<td id = ".$WOCalID."sid>".$row2['employeeid']."</td>"; //might need to aly with location of line
													}
													
													else
														echo"<td id = ".$WOCalID."sname>None</td>";
												$name1=$link->prepare("SELECT firstname, lastname, employeeid
													FROM employee
													WHERE employeeID=?");
													$name1->bind_param("i",$row[5]);
													$name1->execute();
													$temp1=$name1->get_result();
													$row2 = mysqli_fetch_array($temp1);
												
													if($row2[0]!='' && $row2[1]!='')
													{
														echo"<td id = ".$WOCalID."tname>".$row2[0]. " " . $row2[1] . "</td>";
														echo"<td id = ".$WOCalID."tid>".$row2['employeeid']."</td>";			
													}
													else
														echo"<td id = ".$WOCalID."tname>None</td>";
												echo"<td class = 'descCell' id = ".$WOCalID."desc>" . $row[6]."</td>
													<td id = ".$WOCalID."lid>".$row[7]."</td>";
													$locname=$link->prepare("SELECT locationname
															  FROM location 
															  WHERE locationid=?");
													$locname->bind_param("i",$row[7]);
													$locname->execute();
													$temp1=$locname->get_result();
													$rowl=mysqli_fetch_array($temp1);
													echo"<td id = ".$WOCalID."lname>" . $rowl[0]."</td>";
													$meth=$link->prepare("SELECT method
															  FROM delivery 
															  WHERE deliveryid=?");
													$meth->bind_param("i",$row[8]);
													$meth->execute();
													$temp1=$meth->get_result();
													$rowm=mysqli_fetch_array($temp1);
													
													echo"<td id = ".$WOCalID."meth>" . $rowm[0]."</td>
													<td id = ".$WOCalID."stime>" . date('g:ia',strtotime($row[0]))."</td>
													<td id = ".$WOCalID."etime>" . date('g:ia',strtotime($row[1]))."</td>
													<td id = ".$WOCalID."date>" . date('n/j/Y',strtotime($row[9]))."</td>";
													
											echo"</tr>
										</table>
									</td>";
								}
								$first=false;
							}
							
							else
							{
								if(strtotime('+15 minutes',strtotime($row[0]))==strtotime($row[1]))
								{
									$WOCalID = $row[3];
									echo "<td class='sched_work_15_min'>
									<button class='btn btn-success EditWOCal' type='button' data-toggle='modal' data-target='#EdWOCal' value='" . $row[3] . "'>Edit</button>
										<table style = 'display:none'>
											<tr>
												<td id = ".$WOCalID."name>".$rowsch["firstName"]. " " . $rowsch["lastName"] . "</td>
												<td id = ".$WOCalID."pid>".$rowsch['employeeID']."</td>";
												$name1=$link->prepare("SELECT firstname, lastname, employeeid
													FROM employee
													WHERE employeeID=?");
													$name1->bind_param("i",$row[4]);
													$name1->execute();
													$temp1=$name1->get_result();
													$row2 = mysqli_fetch_array($temp1);
													
													if($row2[0]!='' && $row2[1]!='')
													{
														echo"<td id = ".$WOCalID."sname>".$row2[0]. " " . $row2[1] . "</td>";
														echo"<td id = ".$WOCalID."sid>".$row2['employeeid']."</td>"; //might need to play with location of line
													}
													
													else
														echo"<td id = ".$WOCalID."sname>None</td>";
												$name1=$link->prepare("SELECT firstname, lastname, employeeid
													FROM employee
													WHERE employeeID=?");
													$name1->bind_param("i",$row[5]);
													$name1->execute();
													$temp1=$name1->get_result();
													$row2 = mysqli_fetch_array($temp1);
												
													if($row2[0]!='' && $row2[1]!='')
													{
														echo"<td id = ".$WOCalID."tname>".$row2[0]. " " . $row2[1] . "</td>";
														echo"<td id = ".$WOCalID."tid>".$row2['employeeid']."</td>";			
													}
													else
														echo"<td id = ".$WOCalID."tname>None</td>";
												echo"<td class = 'descCell' id = ".$WOCalID."desc>" . $row[6]."</td>
													<td id = ".$WOCalID."lid>".$row[7]."</td>";
													$locname=$link->prepare("SELECT locationname
															  FROM location 
															  WHERE locationid=?");
													$locname->bind_param("i",$row[7]);
													$locname->execute();
													$temp1=$locname->get_result();
													$rowl=mysqli_fetch_array($temp1);
													echo"<td id = ".$WOCalID."lname>" . $rowl[0]."</td>";
													$meth=$link->prepare("SELECT method
															  FROM delivery 
															  WHERE deliveryid=?");
													$meth->bind_param("i",$row[8]);
													$meth->execute();
													$temp1=$meth->get_result();
													$rowm=mysqli_fetch_array($temp1);
													
													echo"<td id = ".$WOCalID."meth>" . $rowm[0]."</td>
													<td id = ".$WOCalID."stime>" . date('g:ia',strtotime($row[0]))."</td>
													<td id = ".$WOCalID."etime>" . date('g:ia',strtotime($row[1]))."</td>
													<td id = ".$WOCalID."date>" . date('n/j/Y',strtotime($row[9]))."</td>";
													
											echo"</tr>
										</table>
									</td>";	
								}
								
								
								else if(strtotime('+15 minutes',strtotime($time))==strtotime($row[1]))
								{
									$WOCalID = $row[3];
									echo "<td class='sched_work_top'>
									<button class='btn btn-success EditWOCal' type='button' data-toggle='modal' data-target='#EdWOCal' value='" . $row[3] . "'>Edit</button>  
									
										<table style = 'display:none'>
											<tr>
												<td id = ".$WOCalID."name>".$rowsch["firstName"]. " " . $rowsch["lastName"] . "</td>
												<td id = ".$WOCalID."pid>".$rowsch['employeeID']."</td>";
												$name1=$link->prepare("SELECT firstname, lastname, employeeid
													FROM employee
													WHERE employeeID=?");
													$name1->bind_param("i",$row[4]);
													$name1->execute();
													$temp1=$name1->get_result();
													$row2 = mysqli_fetch_array($temp1);
													
													if($row2[0]!='' && $row2[1]!='')
													{
														echo"<td id = ".$WOCalID."sname>".$row2[0]. " " . $row2[1] . "</td>";
														echo"<td id = ".$WOCalID."sid>".$row2['employeeid']."</td>"; //might need to aly with location of line
													}
													
													else
														echo"<td id = ".$WOCalID."sname>None</td>";
												$name1=$link->prepare("SELECT firstname, lastname, employeeid
													FROM employee
													WHERE employeeID=?");
													$name1->bind_param("i",$row[5]);
													$name1->execute();
													$temp1=$name1->get_result();
													$row2 = mysqli_fetch_array($temp1);
												
													if($row2[0]!='' && $row2[1]!='')
													{
														echo"<td id = ".$WOCalID."tname>".$row2[0]. " " . $row2[1] . "</td>";
														echo"<td id = ".$WOCalID."tid>".$row2['employeeid']."</td>";			
													}
													else
														echo"<td id = ".$WOCalID."tname>None</td>";
												echo"<td class = 'descCell' id = ".$WOCalID."desc>" . $row[6]."</td>
													<td id = ".$WOCalID."lid>".$row[7]."</td>";
													$locname=$link->prepare("SELECT locationname
															  FROM location 
															  WHERE locationid=?");
													$locname->bind_param("i",$row[7]);
													$locname->execute();
													$temp1=$locname->get_result();
													$rowl=mysqli_fetch_array($temp1);
													echo"<td id = ".$WOCalID."lname>" . $rowl[0]."</td>";
													$meth=$link->prepare("SELECT method
															  FROM delivery 
															  WHERE deliveryid=?");
													$meth->bind_param("i",$row[8]);
													$meth->execute();
													$temp1=$meth->get_result();
													$rowm=mysqli_fetch_array($temp1);
													
													echo"<td id = ".$WOCalID."meth>" . $rowm[0]."</td>
													<td id = ".$WOCalID."stime>" . date('g:ia',strtotime($row[0]))."</td>
													<td id = ".$WOCalID."etime>" . date('g:ia',strtotime($row[1]))."</td>
													<td id = ".$WOCalID."date>" . date('n/j/Y',strtotime($row[9]))."</td>";
													if($row[2] == "")
													  echo"<td id = ".$WOCalID."aet>" .$row[2]."</td>";
													else
													  echo"	<td id = ".$WOCalID."aet>" . date('g:ia',strtotime($row[2]))."</td>";
											echo"</tr>
										</table>
									</td>";
								}
								
								else
								{
									$WOCalID = $row[3];
									echo "<td class='sched_work'>
									<button class='btn btn-success EditWOCal' type='button' data-toggle='modal' data-target='#EdWOCal' value='" . $row[3] . "'>Edit</button>  
										<table style = 'display:none'>
											<tr>
												<td id = ".$WOCalID."name>".$rowsch["firstName"]. " " . $rowsch["lastName"] . "</td>
												<td id = ".$WOCalID."pid>".$rowsch['employeeID']."</td>";
												$name1=$link->prepare("SELECT firstname, lastname, employeeid
													FROM employee
													WHERE employeeID=?");
													$name1->bind_param("i",$row[4]);
													$name1->execute();
													$temp1=$name1->get_result();
													$row2 = mysqli_fetch_array($temp1);
													
													if($row2[0]!='' && $row2[1]!='')
													{
														echo"<td id = ".$WOCalID."sname>".$row2[0]. " " . $row2[1] . "</td>";
														echo"<td id = ".$WOCalID."sid>".$row2['employeeid']."</td>"; //might need to aly with location of line
													}
													
													else
														echo"<td id = ".$WOCalID."sname>None</td>";
												$name1=$link->prepare("SELECT firstname, lastname, employeeid
													FROM employee
													WHERE employeeID=?");
													$name1->bind_param("i",$row[5]);
													$name1->execute();
													$temp1=$name1->get_result();
													$row2 = mysqli_fetch_array($temp1);
												
													if($row2[0]!='' && $row2[1]!='')
													{
														echo"<td id = ".$WOCalID."tname>".$row2[0]. " " . $row2[1] . "</td>";
														echo"<td id = ".$WOCalID."tid>".$row2['employeeid']."</td>";			
													}
													else
														echo"<td id = ".$WOCalID."tname>None</td>";
												echo"<td class = 'descCell' id = ".$WOCalID."desc>" . $row[6]."</td>
													<td id = ".$WOCalID."lid>".$row[7]."</td>";
													$locname=$link->prepare("SELECT locationname
															  FROM location 
															  WHERE locationid=?");
													$locname->bind_param("i",$row[7]);
													$locname->execute();
													$temp1=$locname->get_result();
													$rowl=mysqli_fetch_array($temp1);
													echo"<td id = ".$WOCalID."lname>" . $rowl[0]."</td>";
													$meth=$link->prepare("SELECT method
															  FROM delivery 
															  WHERE deliveryid=?");
													$meth->bind_param("i",$row[8]);
													$meth->execute();
													$temp1=$meth->get_result();
													$rowm=mysqli_fetch_array($temp1);
													
													echo"<td id = ".$WOCalID."meth>" . $rowm[0]."</td>
													<td id = ".$WOCalID."stime>" . date('g:ia',strtotime($row[0]))."</td>
													<td id = ".$WOCalID."etime>" . date('g:ia',strtotime($row[1]))."</td>
													<td id = ".$WOCalID."date>" . date('n/j/Y',strtotime($row[9]))."</td>";
													
											echo"</tr>
										</table>
									</td>";
								}
							}
							$first=false;
						}
						
						else
						{
							if(strtotime('+15 minutes',strtotime($row[0]))==strtotime($row[1]))
									echo "<td class='sched_work_15_min'>	</td>";
								else if(strtotime('+15 minutes',strtotime($time))==strtotime($row[1]))
									echo "<td class='sched_work_top'>	</td>";
								else
									echo "<td class='sched_work'>	</td>";
						}						
						$entered=true;
					}
					
					if(mysqli_data_seek($result4,$counter2) && $time>=$rowblock[1]) //increments the blockouts manually 
					{
						mysqli_data_seek($result4,$counter2);
						$rowblock = mysqli_fetch_array($result4);
						$counter2+=1;
						$first2=true;
					}
					

					if($time>=$rowblock[0] && $time<$rowblock[1] && !$entered) //to show when an employee has a blockout time 
					{
						if($first2)
						{
							if(($curtime>=$rowblock[1] && $today==$curdate) || $today>$rowblock[4])
							{ 
								$BID =  $rowblock[2];
								if(strtotime('+15 minutes',strtotime($rowblock[0]))==strtotime($rowblock[1]))
								{
									echo "<td class='blockout_15_min'>
									<button class='btn btn-primary BOCalDet' type='button' data-toggle='modal' data-target='#DetBO'  value='".$rowblock[2]."'>PASSED</button>
									<table style = 'display:none'>
									<tr>
										<td id = ".$BID."name>".$rowsch["firstName"]. " " . $rowsch["lastName"] . "</td>
										<td id = ".$BID."sTime>" . date('g:ia',strtotime($rowblock[0]))."</td>
										<td id = ".$BID."eTime>" . date('g:ia',strtotime($rowblock[1]))."</td>
										
										<td id = ".$BID."reason>" . $rowblock[3] . "</td>
										<td id = ".$BID."date>" . date('n/j/Y',strtotime($rowblock[4]))."</td>
										<td id = ".$BID."eid>".$rowsch['employeeID']."</td>

									</tr>
								</table>
									</td>";
								}
								else if(strtotime('+15 minutes',strtotime($time))==strtotime($rowblock[1]))
								{
										echo "<td class='blockout_top'>
									<button class='btn btn-primary BOCalDet' type='button' data-toggle='modal' data-target='#DetBO'  value='".$rowblock[2]."'>PASSED</button>
									<table style = 'display:none'>
										<tr>
											<td id = ".$BID."name>".$rowsch["firstName"]. " " . $rowsch["lastName"] . "</td>
											<td id = ".$BID."sTime>" . date('g:ia',strtotime($rowblock[0]))."</td>
											<td id = ".$BID."eTime>" . date('g:ia',strtotime($rowblock[1]))."</td>
											
											<td id = ".$BID."reason>" . $rowblock[3] . "</td>
											<td id = ".$BID."date>" . date('n/j/Y',strtotime($rowblock[4]))."</td>
											<td id = ".$BID."eid>".$rowsch['employeeID']."</td>

										</tr>
									</table>
									</td>";
								}
								else
								{
										echo "<td class='blockout'>
									<button class='btn btn-primary BOCalDet' type='button' data-toggle='modal' data-target='#DetBO'  value='".$rowblock[2]."'>PASSED</button>
									<table style = 'display:none'>
										<tr>
											<td id = ".$BID."name>".$rowsch["firstName"]. " " . $rowsch["lastName"] . "</td>
											<td id = ".$BID."sTime>" . date('g:ia',strtotime($rowblock[0]))."</td>
											<td id = ".$BID."eTime>" . date('g:ia',strtotime($rowblock[1]))."</td>
											
											<td id = ".$BID."reason>" . $rowblock[3] . "</td>
											<td id = ".$BID."date>" . date('n/j/Y',strtotime($rowblock[4]))."</td>
											<td id = ".$BID."eid>".$rowsch['employeeID']."</td>

										</tr>
									</table>
									</td>";
								}
							}
							
							else
							{
								if(strtotime('+15 minutes',strtotime($rowblock[0]))==strtotime($rowblock[1]))
								{
									$BID =  $rowblock[2];
									echo "<td class='blockout_15_min'>
									<button class='btn btn-success EditBOCal' type='button' data-toggle='modal' data-target='#EditBO'  value='".$rowblock[2]."'>Edit</button>
									<table style = 'display:none'>
									<tr>
										<td id = ".$BID."name>".$rowsch["firstName"]. " " . $rowsch["lastName"] . "</td>
										<td id = ".$BID."sTime>" . date('g:ia',strtotime($rowblock[0]))."</td>
										<td id = ".$BID."eTime>" . date('g:ia',strtotime($rowblock[1]))."</td>
										
										<td id = ".$BID."reason>" . $rowblock[3] . "</td>
										<td id = ".$BID."date>" . date('n/j/Y',strtotime($rowblock[4]))."</td>
										<td id = ".$BID."eid>".$rowsch['employeeID']."</td>

									</tr>
								</table>
								</td>";
								}
								else if(strtotime('+15 minutes',strtotime($time))==strtotime($rowblock[1]))
								{
									$BID =  $rowblock[2];
									echo "<td class='blockout_top'>
									<button class='btn btn-success EditBOCal' type='button' data-toggle='modal' data-target='#EditBO'  value='".$rowblock[2]."'>Edit</button>
									<table style = 'display:none'>
									<tr>
										<td id = ".$BID."name>".$rowsch["firstName"]. " " . $rowsch["lastName"] . "</td>
										<td id = ".$BID."sTime>" . date('g:ia',strtotime($rowblock[0]))."</td>
										<td id = ".$BID."eTime>" . date('g:ia',strtotime($rowblock[1]))."</td>
										
										<td id = ".$BID."reason>" . $rowblock[3] . "</td>
										<td id = ".$BID."date>" . date('n/j/Y',strtotime($rowblock[4]))."</td>
										<td id = ".$BID."eid>".$rowsch['employeeID']."</td>

									</tr>
								</table>
								</td>";
								}
								else
								{
									$BID =  $rowblock[2];
									echo "<td class='blockout'>
									<button class='btn btn-success EditBOCal' type='button' data-toggle='modal' data-target='#EditBO'  value='".$rowblock[2]."'>Edit</button>
									<table style = 'display:none'>
									<tr>
										<td id = ".$BID."name>".$rowsch["firstName"]. " " . $rowsch["lastName"] . "</td>
										<td id = ".$BID."sTime>" . date('g:ia',strtotime($rowblock[0]))."</td>
										<td id = ".$BID."eTime>" . date('g:ia',strtotime($rowblock[1]))."</td>
										
										<td id = ".$BID."reason>" . $rowblock[3] . "</td>
										<td id = ".$BID."date>" . date('n/j/Y',strtotime($rowblock[4]))."</td>
										<td id = ".$BID."eid>".$rowsch['employeeID']."</td>

									</tr>
								</table>
								</td>";
									
								}
							}
							$first2=false;
						}
						
						else
						{
							if(strtotime('+15 minutes',strtotime($rowblock[0]))==strtotime($rowblock[1]))
								echo "<td class='blockout_15_min'></td>";
							else if(strtotime('+15 minutes',strtotime($time))==strtotime($rowblock[1]))
								echo "<td class='blockout_top'></td>";
							else
								echo "<td class='blockout'></td>";
						}
						
						$entered=true;
					}
					if(!$entered) //if there is nothing happening at a specific time while the employee is on the clock
						echo "<td></td>";

										
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