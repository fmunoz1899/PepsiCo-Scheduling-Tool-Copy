<!DOCTYPE HTML>
<html> 
<head>
    <title>Pepsi Scheduling Tool</title>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
        <link rel='stylesheet' type='text/css' href='CSS/pepsi_styles.css'>
        <script language='Javascript' type='text/javascript' src='JS/functionality.js'></script> 
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
        <nav class="navbar navbar-expand-sm fixed-top nav">
          <ul class="navbar-nav">	
            <li class="nav-item active"><a class="nav-link" href="empList_View.php">Schedule</a></li>
			<li class="nav-item active"><a class="nav-link a2" href="login.php">Log Out</a></li>
      </nav>


            <div class="jumbotron text-center jumbotron2">
                <h1 class="font-weight-bold text-center">Engineer Workorder List View</h1>
                <img class = "img1"  src = "pepsi.png"> 
                <hr class = "hr1">
            </div>

            <div>
                <h1 class="h1_1">Displaying all Scheduled Workorders</h1>
            </div>
           
            <div class='row div_space'> 
                <div class='col-md-1'> </div>
                <div class='col-md-4'> 
                </div>
 

                <div class="col-md-3"></div>
                  <div class="col-md-3 divborder">
                      <form class = "select_form">
                          <input class = "choice" type="radio" name="choice" id = "ELV" checked> List View 
                          <input class = "choice" type="radio" name="choice" id = "ECV"> Calendar View
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
	$sqlWI="SELECT firstName, lastName, workitem.ItemID, LocationName, Method, ActualEndTime, StartTime, EndTime, Date
			FROM workitem,employee,delivery,wi_schedule,location
			where workitem.EmployeeID=employee.EmployeeID and workitem.ItemID=wi_schedule.ItemID and
			workitem.LocationID=location.LocationID and workitem.DeliveryID=delivery.DeliveryID and employee.EmployeeID=".$_SESSION['username']." ORDER BY firstName";
	$result=$link->query($sqlWI);
	echo'<div class="row tbl_space">';

    echo "<div class='col-md-1'> </div>";
        echo "<div class = 'col-md-10'>";
          echo " <table> ";
          echo "<tr>
					<th>Update</th>
					<th> Employee Name </th>
					<th> Workorder ID </th>
					<th> Location </th>
					<th> Delivery Method </th>
					<th> ActualEndTime </th>
					<th> Start Time </th>
					<th> End Time </th>
					<th> Date </th>
				  </tr>";
			while($row = $result->fetch_assoc()) {
				echo "<tr>
					<td><button>Update</button></td> <!--This will be done with a modal once we figure how to pass data to modal -->
					<td>".$row["firstName"]. " " . $row["lastName"] . "</td>
					<td>". $row["ItemID"]. "</td>
					<td>" . $row["LocationName"]."</td>
					<td>" . $row["Method"]."</td>
					<td>" . date('g:ia',strtotime($row["ActualEndTime"]))."</td>
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
