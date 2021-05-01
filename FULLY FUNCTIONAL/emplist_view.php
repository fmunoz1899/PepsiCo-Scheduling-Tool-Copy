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
	if(!isset($_SESSION['username']) || !isset($_SESSION['emp']) || $_SESSION['emp']===false)
		header("location:login.php?emp_no_tok");
?>

    <body>
        <nav class="navbar navbar-expand-sm ">
          <ul class="navbar-nav">	
            <li class="nav-item active"><a class="nav-link" href="empList_View.php">Schedule</a></li>
			<li class="nav-item active"><a class="nav-link" href="emploc_View.php">Locations</a></li>
			<li class="nav-item active"><a class="nav-link a2" href="login.php">Log Out</a></li>
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

 <!--------------- Modal Code For Updating Workorder Time Complete -------------->

 <div class="modal fade" id="UpdateWOET" tabindex="-1" role="dialog"  aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
	<h5 class="modal-title" >Employee Schedule </h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
		<div class="modal-body modalContent">
			<form class = "form1" id = "UpdateP" action = "emplist_view.php" method = "POST">
			<label> Workorder Completion Time </label></br>
        <div class="form-group">
          <select name="EndTime18" required>
          <option  id = "Et"  value='' selected>--Completion Time--</option>
		  <option value=''>N/A</option>
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
				<input type = "hidden" id = "EmpID" required>
				<input type="hidden" id="WOIDs" name="WOID">
				<!-- I need info passed to the modal to be make the update-->
				<br>
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

<?php
//Dont know how you want this, if you only want work items that are fully scheduled or not
	include('connect.php');
	date_default_timezone_set("America/New_York");
	
	$curdate=date("Y-m-d");
	$future=date("Y-m-d", strtotime($curdate.'+7days'));
	//username is cleaned before set as a session variable
	$sqlWI="SELECT workitem.ItemID, LocationName, Method, ActualEndTime, StartTime, EndTime, Date
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
					<th>Update</th>
					<th style='display:none'> Item ID </th>
					<th> Location </th>
					<th> Delivery Method </th>
					<th> ActualEndTime </th>
					<th> Start Time </th>
					<th> End Time </th>
					<th> Date </th>
				  </tr>";
      
			while($row = $result->fetch_assoc()) 
      {
        //echo"<script>alert('".$row["ActualEndTime"]."');</script>";								/*LINE176!!!!!*/
        $UID = $row['ItemID'];
				echo "<tr>
					<td><button class='btn btn-success CompWOTime'type='button' data-toggle='modal' data-target='#UpdateWOET' value='" . $row['ItemID'] . "'>Edit</button></td>
					<td style='display:none' id=".$UID."ID>". $row["ItemID"]. "</td>
					<td>" . $row["LocationName"]."</td>
					<td>" . $row["Method"]."</td>";
          if($row["ActualEndTime"] == "")
            echo"<td id = ".$UID."AET>" .$row["ActualEndTime"]."</td>";
		
          else
            echo"	<td id = ".$UID."AET>" . date('g:ia',strtotime($row["ActualEndTime"]))."</td>";
		
		
          echo"
					<td>" . date('g:ia',strtotime($row["StartTime"]))."</td>
					<td>" . date('g:ia',strtotime($row["EndTime"]))."</td>
					<td>" . date('n/j/Y',strtotime($row["Date"]))."</td>
				</tr>";
			}
	echo'</div>';
	mysqli_close($link);
?>
      <!------------------------------------->


            
    </body>
</html>