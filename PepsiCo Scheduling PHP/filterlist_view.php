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
        <script language='Javascript' type='text/javascript' src='Javascript/functionality.js'></script> 
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

 <?php 
 echo"
            <div class='jumbotron text-center jumbotron2'>
                <h1 class='font-weight-bold text-center'>Manager Workorder List View</h1>
                <img class = 'img1'  src = 'pepsi.png'> 
                <hr class = 'hr1'>
            </div>";

           
            

	session_start();
	
	include('connect.php');
	date_default_timezone_set("America/New_York"); //timezone will need to change before giving 

	
	if(!isset($_SESSION['manager']) || $_SESSION['manager']===false)
	{
		header("location:login.php?list_view_no_man_tok");
		exit;
	}
	
	if((!isset($_POST['datepicker']) || $_POST['datepicker']=='') && (!isset($_POST['filterfirst']) || $_POST['filterfirst']=='') && (!isset($_POST['filterlast']) || $_POST['filterlast']==''))
	{
		header("location:list_view.php?block_no_filters");
		exit;
	}
	
	if($_POST['datepicker']!='')
	{
		$curday=date("D",strtotime($_POST['datepicker'])); //this gets me the day abbrivated for the ahours
		$curdate=date("Y-m-j",strtotime($_POST['datepicker'])); //this gets the current date and formats it in yyy-mm-dd
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
	
	
			
			
	
	$sqlWI=$link->prepare("SELECT firstName, lastName, workitem.ItemID, LocationName, Method, ActualEndTime, StartTime, EndTime, Date
			FROM workitem,employee,delivery,wi_schedule,location
			where workitem.EmployeeID=employee.EmployeeID 
			and workitem.ItemID=wi_schedule.ItemID 
			and workitem.LocationID=location.LocationID 
			and workitem.DeliveryID=delivery.DeliveryID
			and Date='".$curdate."'
			and firstName LIKE ? 
			and lastName LIKE ?
			ORDER BY Date, StartTime, EndTime");
		
	$sqlWI->bind_param("ss",$searchfirst,$searchlast);
	
	$result=$sqlWI->execute();
	$result=$sqlWI->get_result();
	
echo"

            <div>";
			if($searchfirst == '%%' && $searchlast == '%%')
                echo"<h1 class='h1_1'>Filtered results for ". date("n/j/y",strtotime($curdate))."</h1>";
			else
				echo"<h1 class='h1_1'>Filtered results for ".$cleanfirst." ".$cleanlast." on ". date("n/j/y",strtotime($curdate))."</h1>";
			echo mysqli_num_rows($result)." result(s)";
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

//Make sure to change this so that it only shows the engineers	
	echo'<div class="row tbl_space">';

    echo "<div class='col-md-1'> </div>";
        echo "<div class = 'col-md-10'>";
          echo " <table> ";
          echo "<tr>
					<th> Employee Name </th>
					<th> Workorder ID </th>
					<th> Location </th>
					<th> Delivery Method </th>
					<th> Actual End Time </th>
					<th> Start Time </th>
					<th> End Time </th>
					<th> Date </th>
				  </tr>";
			while($row = $result->fetch_assoc()) {
				echo "<tr>
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