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
        <script language='Javascript' type='text/javascript' src="Javascript/functionality.js"></script> 
        <script>
                $(document).ready(function(){
                  $('[data-toggle="tooltip"]').tooltip();   
                });               
        </script>
    </head>
	
<?php
	session_start();
	
	$time= $_SERVER['REQUEST_TIME'];
		$timeout=300;
	
			if(isset($_SESSION['last'])===true && ($time-$_SESSION['last'])>$timeout)
			{
				header("location:login.php?st=".rand());
				exit;
			}
			
		$_SESSION['last']=$time;
	if(!isset($_SESSION['username']) || !isset($_SESSION['emp']) || $_SESSION['emp']===false)
		header("location:login.php?emp_no_tok");
?>

    <body>
                <nav class="navbar navbar-expand-sm ">
          <ul class="navbar-nav">	
            <li class="nav-item active"><button type="button" class="btn btn-outline-primary navButtons" onclick ="window.location.href = 'empList_View.php';">Schedule</button></li>
			<li class="nav-item active"><button type="button" class="btn btn-outline-primary navButtons" onclick ="window.location.href = 'emploc_View.php';">Locations</button></li>
			</ul>
			<div class="navbar-nav ml-auto">
				<li class="nav-item active"><button type="button" class="btn btn-outline-danger" onclick ="window.location.href = 'login.php';" >Log Out</button></li>
			</div>
			
      </nav>


            <div class="jumbotron text-center jumbotron2">
				<h1 class="font-weight-bold text-center">Engineer Workorder List View</h1>
 <?php 
include('connect.php');
$name="SELECT firstname, lastname
	   FROM employee
	   WHERE employeeID=".$_SESSION['username'];
$name=$link->query($name);
	   $row = $name->fetch_assoc();
echo" <h1 class='font-weight-bold text-center'>Welcome Back ".$row['firstname']." ".$row['lastname']."</h1>";


if(isset($_POST['WOID']))
{
	if($_POST['EndTime18']=='')
	{
		$uptime=$link->prepare("UPDATE wi_schedule
							SET actualendtime=default
							WHERE itemid=?");
		$uptime->bind_param("i",$_POST['WOID']);
		$uptime->execute();
	}
	else
	{
		$uptime=$link->prepare("UPDATE wi_schedule
							SET actualendtime=?
							WHERE itemid=?");
		$uptime->bind_param("si",$_POST['EndTime18'],$_POST['WOID']);
		$uptime->execute();
	}
	
}
 ?>
                <img class = "img1"  src = "pepsi.png"> 
                <hr class = "hr1">
            </div>

            <div>
                <h1 class="h1_1">Displaying all Scheduled Workorders</h1>
				<h3 class="h1_1">Within a week</h3>
            </div>
           
            <div class='row div_space'> 
                <div class='col-md-1'> </div>
                <div class='col-md-4'> 
				<form class = "form2" method="POST" name="cal_form" id="cal_form" action="filteremp_list.php">
                    <label>Select Date:</label> 
                      <input name="datepicker2" type="text" id="datepicker2" readonly='true' placeholder="Click Here to Select Date">
                      <button type="submit" name="submit" class="btn btn-primary">Filter</button>
                  </form > 
                </div>
 

                <div class="col-md-3"></div>
                  <div class="col-md-3 divborder">
                      <form class = "select_form">
                          <input class = "choice" type="radio" name="choice" id = "ELV" checked> Work Order List View <br>
						  <input class = "choice" type="radio" name="choice" id = "BCV"> Block Out List View <br>
                          <input class = "choice" type="radio" name="choice" id = "ECV"> Calendar View <br>
                      </form>
                  </div>
              </div>
          </div>
          <div class="row "> 
            <div class="col-md-9"> </div>
              <div class="col-md-3">
              </div>
          </div>
      </div>
</div>

<?php
//Dont know how you want this, if you only want work items that are fully scheduled or not
	include('connect.php');
	date_default_timezone_set("America/New_York");
	
	$today=date("Y-m-d");
	$curtime=date("H:i:s");
	$curdate=date("Y-m-d");
	$future=date("Y-m-d", strtotime($curdate.'+7days'));
	//username is cleaned before set as a session variable
	$sqlWI="SELECT workitem.ItemID, LocationName, Method, ActualEndTime, StartTime, EndTime, Date, description
			FROM workitem,employee,delivery,wi_schedule,location
			WHERE workitem.EmployeeID=employee.EmployeeID 
			and workitem.ItemID=wi_schedule.ItemID 
			and workitem.LocationID=location.LocationID 
			and workitem.DeliveryID=delivery.DeliveryID 
			and employee.EmployeeID=".$_SESSION['username']."
			and Date>='".$curdate."' 
			and Date<='".$future."'
			ORDER BY Date, StartTime, EndTime";
	$result=$link->query($sqlWI);
	echo'<div class="row tbl_space">';

    echo "<div class='col-md-1'> </div>";
        echo "<div class = 'col-md-10'>";
          echo " <table> ";
          echo "<tr>
					<th>Status</th>
					<th style='display:none'> Item ID </th>
					<th> Location </th>
					<th> Delivery Method </th>
					<th> Start Time </th>
					<th> End Time </th>
					<th> Date </th>
					<th> Description </th>
				  </tr>";
      
			while($row = $result->fetch_assoc()) 
      {
        $UID = $row['ItemID'];
				echo "<tr><td>";

				if(($curtime>=$row['EndTime'] && $today==$curdate) || $today>$row['Date'])
					echo"<button class='btn btn-primary CompWOTime'type='button'>COMPLETED</button></td>";
				else
					echo"<button class='btn btn-success CompWOTime'type='button'>Upcoming</button></td>";
				echo"
					<td style='display:none' id=".$UID."ID>". $row["ItemID"]. "</td>
					<td class = 'empWOnonDesc'>" . $row["LocationName"]."</td>
					<td class = 'empWOnonDesc'>" . $row["Method"]."</td>";
     		
          echo"
					<td class = 'empWOnonDesc'>" . date('g:ia',strtotime($row["StartTime"]))."</td>
					<td class = 'empWOnonDesc'>" . date('g:ia',strtotime($row["EndTime"]))."</td>
					<td class = 'empWOnonDesc'>" . date('n/j/Y',strtotime($row["Date"]))."</td>";
					
				echo"<td class = 'empWODesc'>".$row['description']."</td>";	
				echo"</tr>";
				$curdate=date("Y-m-d", strtotime($curdate.'+1days'));
			}
	echo'</div>';
	mysqli_close($link);
?>
      <!------------------------------------->


            
    </body>
</html>