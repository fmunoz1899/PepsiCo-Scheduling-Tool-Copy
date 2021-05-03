<?php
session_start();
include('connect.php');
date_default_timezone_set("America/New_York"); //timezone will need to change before giving 
?>
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
        <script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js?ver=1.3.2'></script>
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
<?php
	if(!isset($_SESSION['emp']) || $_SESSION['emp']===false)
	{
		header("location:login.php?emp_no_tok");
		exit;
	}
	
	$time= $_SERVER['REQUEST_TIME'];
		$timeout=300;
	
			if(isset($_SESSION['last'])===true && ($time-$_SESSION['last'])>$timeout)
			{
				header("location:login.php?st=".rand());
				exit;
			}
			
		$_SESSION['last']=$time;
	
	if(!isset($_POST['datepicker2']))
	{
		header("location:empcal_view.php?emp_cal_no_filter");
		exit;
	}
	
	if(isset($_POST['WOID']))
	{
		if($_POST['EndTime18']=='na')
		{
			$upewo=$link->prepare("UPDATE wi_schedule
					SET actualendtime=default
					WHERE itemid=?");
			$upewo->bind_param("i",$_POST['WOID']);
			$upewo->execute();
		}
		
		else
		{
			$upewo=$link->prepare("UPDATE wi_schedule
					SET actualendtime=?
					WHERE itemid=?";
			$upewo->bind_param("si",$_POST['EndTime18'],$_POST['WOID']));
			$upewo->execute();
		}
	}
?>
    <body>
      <nav class="navbar navbar-expand-sm">
        <ul class="navbar-nav">	
          <li class="nav-item active"><a class="nav-link" href="empList_View.php">Schedule</a></li>
			<li class="nav-item active"><a class="nav-link" href="emploc_View.php">Locations</a></li>
			<li class="nav-item active"><a class="nav-link a2" href="login.php">Log Out</a></li>
    </nav>


            <div class="jumbotron text-center jumbotron2">
                <h1 class="font-weight-bold text-center">Eningeer Workorder Calendar View</h1>
<?php 
	include('connect.php');
		$name="SELECT firstname, lastname
			   FROM employee
			   WHERE employeeID=".$_SESSION['username'];
		$name=$link->query($name);
			   $row = $name->fetch_assoc();
		echo" <h1 class='font-weight-bold text-center'>Welcome Back ".$row['firstname']." ".$row['lastname']."</h1>";
 ?>
                <img class = "img1"  src = "pepsi.png"> 
                <hr class = "hr1">
            </div>
<?php







echo"
            <div>";
			$curdate=date("Y-m-d",strtotime($_POST['datepicker2'])); 
                echo"<h1 class='h1_1'>Filtered week after ".date('n/j/Y',strtotime($curdate))."</h1>
            </div>
            
            <!-- All names will be taken from the database -->
           <div class='row div_space'> 
                <div class='col-md-1'> </div>
                <div class='col-md-4'> 
                <a href='empcal_view.php'><button class='btn btn-primary'>Go Back</button></a>
                </div>
                <div class='col-md-3'></div>
                  <div class='col-md-3 divborder'>
                  </div>
              </div>
          <div class='row '> 
            <div class='col-md-9'> </div>
              <div class='col-md-3'>
               
              </div>
          </div>";
    


		$curdate=date("Y-m-d", strtotime($_POST['datepicker2'])); //this gets the current date and formats it in yyy-mm-dd
		$future=date("Y-m-d", strtotime($curdate.'+7days')); //to get the next 7 days //to get the specific date
echo"
      <div class = 'row div_space '> 
        <div class = 'col-md-1'></div>
        <div class = 'col-md-10  cal_bg'>
        <div id='items'>";
		$sched="SELECT firstName, lastName, employee.employeeID
					FROM employee
					WHERE employee.employeeID=".$_SESSION['username'];
					
			$full=mysqli_query($link,$sched);
			$rowsch=$full->fetch_assoc();
		
		while($curdate<=$future)
		{
			$curtime= date('H:i:s');
			$done=false; //to show that a work order was completed but only on the first blcok
			$counter=1; //for schedule cycling
			$counter2=0; //for blokcout cycling
			$time=date('06:00:00'); //starting time of the day
			$first=true; //check if it's first td in a workorder
			$first2=true; //check if first td in blockout
			$curday=date("D",strtotime($curdate));//this gets me the day abbrivated for the ahours
			
			echo"
          <div class='item'>
            <table class = 'cal_tbl_bg'>
              <tr>
                <th></th>
                <th colspan='2'><b>".date('n/j/Y',strtotime($curdate))."</b></th>";
				
				$items="SELECT starttime, endtime, actualendtime, workitem.itemid, preferrede1, preferrede2, description, locationID, deliveryid, date
						FROM wi_schedule, workitem 
						WHERE wi_schedule.ItemID=workitem.ItemID 
						AND workitem.employeeID=".$_SESSION['username']." 
						AND wi_schedule.Date='".$curdate."' 
						ORDER BY StartTime";
				$result2=mysqli_query($link,$items);
				$row = mysqli_fetch_array($result2);
				
				$ahours="SELECT StartTime, endtime 
						FROM ahours 
						WHERE ahours.EmployeeID=".$_SESSION['username']." 
						AND ahours.DayID='".$curday."' 
						ORDER BY StartTime";
				$result3=mysqli_query($link,$ahours);
				$rowahours = mysqli_fetch_array($result3);
				
				$blackout="SELECT starttime, endtime, blackoutID, reason, BDate
						   FROM blackout 
						   WHERE blackout.BDate='".$curdate."' 
						   AND blackout.EmployeeID=".$_SESSION['username']." 
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
							if($row[2]=='')
							{
								if(strtotime('+15 minutes',strtotime($row[0]))==strtotime($row[1]))
								{
									$WOCalID = $row[3];
									echo "<td class='sched_work_15_min'>
									<button class='btn btn-success EditWOCalEmp' type='button' data-toggle='modal' data-target='#EmpWOCAl' value='" . $row[3] . "'>Edit</button>
									<table style = 'display:none'>
											<tr>
												<td id = ".$WOCalID."name>".$rowsch["firstName"]. " " . $rowsch["lastName"] . "</td>
												<td id = ".$WOCalID."pid>".$rowsch['employeeID']."</td>";
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
								
								else if(strtotime('+15 minutes',strtotime($time))==strtotime($row[1]))
								{
									$WOCalID = $row[3];
									echo "<td class='sched_work_15'>
									<button class='btn btn-success EditWOCalEmp' type='button' data-toggle='modal' data-target='#EmpWOCAl' value='" . $row[3] . "'>Edit</button>
									<table style = 'display:none'>
											<tr>
												<td id = ".$WOCalID."name>".$rowsch["firstName"]. " " . $rowsch["lastName"] . "</td>
												<td id = ".$WOCalID."pid>".$rowsch['employeeID']."</td>";
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
									</td";
								}
								
								else
								{
									$WOCalID = $row[3];
									
									echo "<td class='sched_work'>
									<button class='btn btn-success EditWOCalEmp' type='button' data-toggle='modal' data-target='#EmpWOCAl' value='" . $row[3] . "'>Edit</button>
									<table style = 'display:none'>
											<tr>
												<td id = ".$WOCalID."name>".$rowsch["firstName"]. " " . $rowsch["lastName"] . "</td>
												<td id = ".$WOCalID."pid>".$rowsch['employeeID']."</td>";
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
								
							}
							
							else
							{
								if(strtotime('+15 minutes',strtotime($row[0]))==strtotime($row[1]))
								{
									$WOCalID = $row[3];
									echo "<td class='sched_work_15_min'>
									<button class='btn btn-primary EmpWOCAlPassed' type='button' data-toggle='modal' data-target='#EmpPassedWO' value='" . $row[3] . "'>COMPLETED</button>
									<table style = 'display:none'>
											<tr>
												<td id = ".$WOCalID."name>".$rowsch["firstName"]. " " . $rowsch["lastName"] . "</td>
												<td id = ".$WOCalID."pid>".$rowsch['employeeID']."</td>";
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
								
								else if(strtotime('+15 minutes',strtotime($time))==strtotime($row[1]))
								{
									$WOCalID = $row[3];
									echo "<td class='sched_work_top'>
									<button class='btn btn-primary EmpWOCAlPassed' type='button' data-toggle='modal' data-target='#EmpPassedWO' value='" . $row[3] . "'>COMPLETED</button>
									<table style = 'display:none'>
											<tr>
												<td id = ".$WOCalID."name>".$rowsch["firstName"]. " " . $rowsch["lastName"] . "</td>
												<td id = ".$WOCalID."pid>".$rowsch['employeeID']."</td>";
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
									<button class='btn btn-primary EmpWOCAlPassed' type='button' data-toggle='modal' data-target='#EmpPassedWO' value='" . $row[3] . "'>COMPLETED</button>
									<table style = 'display:none'>
											<tr>
												<td id = ".$WOCalID."name>".$rowsch["firstName"]. " " . $rowsch["lastName"] . "</td>
												<td id = ".$WOCalID."pid>".$rowsch['employeeID']."</td>";
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
							if($curtime>=$rowblock[1])
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
								$BID =  $rowblock[2];
								if(strtotime('+15 minutes',strtotime($rowblock[0]))==strtotime($rowblock[1]))
								{
									
									echo "<td class='blockout_15_min'>
									<button class='btn btn-success EditBOCal' type='button' data-toggle='modal' data-target='#EditBO'  value='".$rowblock[2]."'>Details</button>
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
									<button class='btn btn-success EditBOCal' type='button' data-toggle='modal' data-target='#EditBO'  value='".$rowblock[2]."'>Details</button>
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
									<button class='btn btn-success EditBOCal' type='button' data-toggle='modal' data-target='#EditBO'  value='".$rowblock[2]."'>Details</button>
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
						echo "<td>	</td>
										
					</tr>";
					
					$time = date('H:i:s',strtotime('+15 minutes',strtotime($time))); //to increment the schdule by 15 minutes
				}
			$curdate=date("Y-m-d", strtotime($curdate.'+1days'));
			
			 echo"            
            </table>
			</div>";
			
		}
 ?>

            </table>
        </div>
       </div>
	  </div> 

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
			
			<div class="form-group"
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
	<!--------------- Modal Code For Updating Workorder Time Complete -------------->

 <div class="modal fade" id="EmpWOCAl" tabindex="-1" role="dialog"  aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
	<h5 class="modal-title" >Workorder Details</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
		<div class="modal-body modalContent">
		 <form class = "form1" id = "CalUpdateTime" action = "filteremp_cal.php" method = "POST">
              <div class="form-group">
                  <label> Date:</label>
                   <input name="datepicker5" type="text" id="datepicker5DetEdt" readonly='true'>
              </div>
              <div class="form-group">
                  <label> Start Time:</label>
				  <input type = "text" id = "startTimeDetEdt"   readonly='true'>
              </div>
              <div class="form-group">
                  <label> End Time:</label>
				  <input type = "text" id = "endTimeDetEdt"   readonly='true'>
			</div>
              <div class="form-group">
                <label> Location:</label><br>
				<input type = "text" id = "locuDetEdt"   readonly='true'>
              </div>
              <div class="form-group">
                <label> Delivery method: </label></br>
					<input type = "text" id = "methDetEdt"   readonly='true'>
              </div>
               <label> Workorder Completion Time </label></br>
				<div class="form-group">
				  <select name="EndTime18" required>
				  <option  id = "AETEdt" value='' selected>--Completion Time--</option>
				  <option   value='na' >N/A</option>
				  <?php
					$time=date('06:00:00');
					while($time!='22:15:00') //time day ends 
					{
					  echo"<option value='".$time."'>". date('g:ia',strtotime($time))."</option>";
					  $time = date('H:i:s',strtotime('+15 minutes',strtotime($time)));
					}
				  ?>
			  </select>
              </div>
              <div class="form-group">
                <label> Description:</label>
                  <textarea rows = "10" cols = "25" id = "woDescDetEdt" name="woDesc" readonly='true'></textarea>
              </div>
				<input type = "hidden" id = "EmpIDEdt" required>
				<input type="hidden" id="WOIDEdt" name="WOID">
				<!-- I need info passed to the modal to be make the update-->
				<br>
				
				<?php echo"<input type='hidden' name='datepicker2' value='".filter_var(htmlentities($_POST['datepicker2'],  ENT_QUOTES,  "utf-8"),FILTER_SANITIZE_STRING)."'>"; ?>
			<button type="submit" name="submit" class="btn btn-primary">Confirm</button>
              </form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
        </div>
    </div>

      <!------------------------------------->
	  <!--------------- Modal Code For Updating Past Workorder Time Complete -------------->

 <div class="modal fade" id="EmpPassedWO" tabindex="-1" role="dialog"  aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
	<h5 class="modal-title" >Workorder Details</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
		<div class="modal-body modalContent">
		 <form class = "form1" id = "CalUpdateTime" action = "filteremp_cal.php" method = "POST">
              <div class="form-group">
                  <label> Date:</label>
                   <input name="datepicker5" type="text" id="datepicker5DetPass" readonly='true'>
              </div>
              <div class="form-group">
                  <label> Start Time:</label>
				  <input type = "text" id = "startTimeDetPass"   readonly='true'>
              </div>
              <div class="form-group">
                  <label> End Time:</label>
				  <input type = "text" id = "endTimeDetPass"   readonly='true'>
			</div>
              <div class="form-group">
                <label> Location:</label><br>
				<input type = "text" id = "locuDetPass"   readonly='true'>
              </div>
              <div class="form-group">
                <label> Delivery method: </label></br>
					<input type = "text" id = "methDetPass"   readonly='true'>
              </div>
               <label> Workorder Completion Time </label></br>
				<div class="form-group">
				  <select name="EndTime18"   required>
				  <option  id = "AETPass" value='' selected>--Completion Time--</option>
				  <option  value='na'>N/A</option>
				  <?php
					$time=date('06:00:00');
					while($time!='22:15:00') //time day ends 
					{
					  echo"<option value='".$time."'>". date('g:ia',strtotime($time))."</option>";
					  $time = date('H:i:s',strtotime('+15 minutes',strtotime($time)));
					}
				  ?>
			  </select>
              </div>
              <div class="form-group">
                <label> Description:</label>
                  <textarea rows = "10" cols = "25" id = "woDescDetPass" name="woDesc" readonly='true'></textarea>
              </div>
				<input type = "hidden" id = "EmpIDPass" required>
				<input type="hidden" id="WOIDPass" name="WOID">
				<!-- I need info passed to the modal to be make the update-->
				<br>
				<?php echo"<input type='hidden' name='datepicker2' value='".filter_var(htmlentities($_POST['datepicker2'],  ENT_QUOTES,  "utf-8"),FILTER_SANITIZE_STRING)."'>"; ?>
			<button type="submit" name="submit" class="btn btn-primary">Confirm</button>
              </form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
        </div>
    </div>
      <!------------------------------------->
	  
    </body>
</html>