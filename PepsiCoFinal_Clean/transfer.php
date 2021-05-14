<!DOCTYPE HTML>
<html> 
<head>
<?php 
include('click.php');
date_default_timezone_set("America/New_York");
?>
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
        <script language='Javascript' type='text/javascript' src='JavaScript/functionality.js'></script> 
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
            <h1 class="font-weight-bold text-center">Manager Transfer Workorder</h1>
            <img class = "img1"  src = "pepsi.png"> 
            <hr class = "hr1">
        </div>
            <div>
                <h1 class="h1_1">Information</h1>
				<h5 class="h1_1">To transfer a single day, keep the start and end date the same</h5>
            </div>
			
<?php
    include('connect.php');   
	session_start();
	
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
	
	if(isset($_POST['datepicker10']) && isset($_POST['datepicker11']) && isset($_POST['tran1']) && isset($_POST['tran2']))
	{
		$list2=array();
		$output='';
		$today=date("Y-m-d");
		$curtime=date("H:i:s");
		
		$startdate=date("Y-m-d",strtotime($_POST['datepicker10']));
		$enddate=date("Y-m-d",strtotime($_POST['datepicker11']));
		if($_POST['tran1']==$_POST['tran2'])
			echo"<script>alert('You cannot transfer to the same user');</script>";
		else if ($startdate > $enddate)
			echo"<script>alert('The start date cannot be later than the end date');</script>";
		else
		{
			
			while($startdate<=$enddate)
			{
				$string='';
				$curday=date("D",strtotime($startdate));
				$wo=$link->prepare("SELECT StartTime, EndTime, workitem.ItemID, PreferredE2, PreferredE1
									FROM wi_schedule, workitem
									WHERE wi_schedule.ItemID=workitem.ItemID
									AND employeeid=?
									AND date=?");
				$wo->bind_param("is",$_POST['tran1'],$startdate);
				$wo->execute();
				$ans=$wo->get_result();
				$rowcount=mysqli_num_rows($ans); 
				
					while($row=$ans->fetch_assoc())
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
								$checkhours->bind_param("isss",$_POST['tran2'],$curday,$row['StartTime'],$row['EndTime']);
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
								$checkblock->bind_param("isssss",$_POST['tran2'],$startdate,$row['StartTime'],$row['EndTime'],$row['StartTime'],$row['EndTime']);
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
								$checkwork->bind_param("isssss",$_POST['tran2'],$starttime,$row['StartTime'],$row['EndTime'],$row['StartTime'],$row['EndTime']);
								$checkwork->execute();
								$wres=$checkwork->get_result();
								$roww = mysqli_fetch_array($wres);
								
								if($rowh[0]!=0 || $rowb[0]!=0 || $roww[0]!=0 ||($startdate==$today && $row['StartTime']<=$curtime))
								{
									array_push($list2,date("m/d/Y",strtotime($startdate)));
									array_push($list2,' ');
									array_push($list2,date("g:ia",strtotime($row['StartTime'])));
									array_push($list2,' - ');
									array_push($list2,date("g:ia",strtotime($row['EndTime'])));
									array_push($list2,'\n');
								}
						
								else if($rowh[0]==0 && $rowb[0]==0 && $roww[0]==0 && !($startdate==$today && $row['StartTime']<=$curtime))
								{
									if($_POST['tran2']==$row['PreferredE1'])
									{
										$worki=$link->prepare("UPDATE workitem
															   SET EmployeeID=?,
															   PreferredE1=default,
															   PreferredE2=?
															   WHERE itemid=?");
										$worki->bind_param("iii",$_POST['tran2'],$row['PreferredE2'],$row['ItemID']);											
									}
									else if($_POST['tran2']==$row['PreferredE2'])
									{
										$worki=$link->prepare("UPDATE workitem
															   SET EmployeeID=?,
															   PreferredE1=?,
															   PreferredE2=default
															   WHERE itemid=?");
										$worki->bind_param("iii",$_POST['tran2'],$row['PreferredE1'],$row['ItemID']);
									}
									else
									{
										$worki=$link->prepare("UPDATE workitem
															   SET EmployeeID=?,
															   PreferredE1=?,
															   PreferredE2=?
															   WHERE itemid=?");
										$worki->bind_param("iiii",$_POST['tran2'],$row['PreferredE1'],$row['PreferredE2'],$row['ItemID']);
									}
									
									$worki->execute();
								}
					}
						
						$startdate=date("Y-m-d", strtotime($startdate.'+1days'));
			}
			
					foreach($list2 as $id)
						$output.=$id;
					
					if(count($list2)!=0)
						echo"<script>alert('These are the dates and times that were not transferred: ".'\n'.$output."');</script>";
					else
						echo"<script>alert('The transfer was complete');</script>";
		}
	}
		
	?>  
<div class = "row">
<div class  ="col-md-4"></div>
	<div class  ="col-md-4 ">
      <form class = "form1" method='POST' action='transfer.php'>
				 <label> Filter by reporting manager:</label><br>
					 <select name='filter'>
					 <?php
					 include('connect.php');
					 
					 if(!isset($_SESSION['manager']) || $_SESSION===false)
					{
						header("location:login.php");
						exit;
					}
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
		</div>
		<hr class = "formhr">
	<div class = "row">
	<div class = "col-md-4"></div>
	<div class = "col-md-4">
	 <form class = "form1" action = "transfer.php" method = "POST" >
				  <div class="form-group">
					 <label> Transfer From:</label><br>
						<select name="tran1" id = "v" required>
						  <option value="" selected>--Select--</option>
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
					<label> Transfer To:</label><br>
					<select name="tran2" id = "tran2" >
					 <option value="" selected>--Select--</option>
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
					  <label> Start Date:</label><br>
					   <input name="datepicker10" type="text" id="datepicker10" readonly='true'>
				  </div>
				   <div class="form-group">
					  <label>  End Date:</label><br>
					   <input name="datepicker11" type="text" id="datepicker11" readonly='true' >
				  </div>
				  
					<button type="submit" name="submit" class="btn btn-primary subBut">Transfer</button> 
				  </form>
		</div>
		</div>    
    </body>
</html>