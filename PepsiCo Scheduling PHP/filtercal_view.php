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
        <script language='Javascript' type='text/javascript' src="JS/fuck.js"></script> 
        <script>
                $(document).ready(function(){
                  $('[data-toggle="tooltip"]').tooltip();   
                });               
        </script>
    </head>
<?php
if((!isset($_POST['datepicker']) || $_POST['datepicker']=='') && (!isset($_POST['filterfirst']) || $_POST['filterfirst']=='') && (!isset($_POST['filterlast']) || $_POST['filterlast']==''))
{
	header("location:cal_view.php?no_filters");
	exit;
}

echo"
    <body>
      <nav class='navbar navbar-expand-sm'>
        <ul class='navbar-nav'>	
          <li class='nav-item active'><a class='nav-link' href='List_View.php'>Schedule</a></li>
          <li class='nav-item active'><a class='nav-link a2' href='employees.php'>Employees</a></li>
          <li class='nav-item active'><a class='nav-link a2' href='locations.php'>Locations</a></li>
		  <li class='nav-item active'><a class='nav-link a2' href='login.php'>Log Out</a></li>

    </nav>


            <div class='jumbotron text-center jumbotron2'>
                <h1 class='font-weight-bold text-center'>Manager Workorder List View</h1>
                <img class = 'img1'  src = 'pepsi.png'> 
                <hr class = 'hr1'>
            </div>";
			
include('connect.php');
date_default_timezone_set("America/New_York"); //timezone will need to change before giving 

if($_POST['datepicker']!='')
{
		$curday=date("D",strtotime($_POST['datepicker'])); //this gets me the day abbrivated for the ahours
		$curdate=date("Y-m-j",strtotime($_POST['datepicker'])); //this gets the current date and formats it in yyy-mm-dd
}

else
{
	$curday=substr(date("r"),0,3); //this gets me the day abbrivated for the ahours
	$curdate=date("Y-m-j"); //this gets the current date and formats it in yyy-mm-dd
}

$fname=str_replace(' ','',"%".filter_var(htmlentities($_POST['filterfirst'],  ENT_QUOTES,  'utf-8'),FILTER_SANITIZE_STRING)."%");
$lname=str_replace(' ','',"%".filter_var(htmlentities($_POST['filterlast'],  ENT_QUOTES,  'utf-8'),FILTER_SANITIZE_STRING)."%");

$empID=$link->prepare("SELECT employee.employeeID 
				FROM employee, ahours 
				WHERE employee.employeeID=ahours.employeeID 
				and StartTime!='NULL' 
				and EndTime!='NULL' 
				and DayID=?
				and firstName LIKE ? 
				and lastName LIKE ?");
				
				$empID->bind_param("sss",$curday,$fname,$lname);
				$empID->execute();	
				$result=$empID->get_result();
echo"

            <div>";
			if($fname == '%%' && $lname == '%%')
                echo"<h1 class='h1_1'>Filter results on ". date("n/j/y",strtotime($curdate))."</h1>";
			else
				echo"<h1 class='h1_1'>Filter results for".substr($fname,1,-1)." ".substr($lname,1,-1)." on ". date("n/j/y",strtotime($curdate))."</h1>";
			echo mysqli_num_rows($result)." result(s)";
			echo"
		
            </div>
            
            <!-- All names will be taken from the database -->
           <div class='row div_space'> 
                <div class='col-md-1'> </div>
                <div class='col-md-4'>                 
                </div>
                <div class='col-md-3'></div>
                  <div class='col-md-3 divborder'>
                      <!--<form class = 'select_form'>
                          <input class = 'choice' type='radio' name='choice' id = 'LV'> List View 
                          <input class = 'choice' type='radio' name='choice' id = 'CV' checked> Calendar View
                      </form>-->
					 <a href='cal_view.php'><button class='btn btn-primary'>Go Back</button></a>
                  </div>
              </div>
          <div class='row '> 
            <div class='col-md-9'> </div>
              <div class='col-md-3'>
                <button type='button' class='btn btn-primary mbut' data-toggle='modal' data-target='#newWO'> Create New Workorder</button>
				<br>
              </div>
          </div>
      <!--------------- Modal Code -------------->
      <div class='modal fade' id='newWO' tabindex='-1' role='dialog' aria-labelledby='exampleModalLongTitle' aria-hidden='true'>
        <div class='modal-dialog' role='document'>
          <div class='modal-content'>
            <div class='modal-header'>
              <h5 class='modal-title' id='exampleModalLongTitle'>Schedule New Workorder</h5>
              <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                <span aria-hidden='true'>&times;</span>
              </button>
            </div>
            <div class='modal-body'>
              <form >
                <p>Employee: 
                  <select name='employee' required>
                    <option value='' desabled selected>Select One</option>
                    <option>Main Employee</option>
                    <option>Second Employee</option>
                    <option>Third Employee</option>
                  </select>
                </p>
                <p>Date: 
                  <select name='date'>
                    <option value='' desabled selected>Select Date</option>
                    <option>Day 1</option>
                    <option>Day 2</option>
                    <option>Day 3</option>
                  </select>
                </p>
                <p>Start Time: 
                  <select name='startTime'>
                    <option value='' desabled selected>Select One</option>
                    <option>9:00</option>
                    <option>9:15</option>
                    <option>9:30</option>
                  </select>
                </p>
                <p>Job Length: 
                  <select name='jobLenght'>
                    <option value='' desabled selected>Select One</option>
                    <option>15 minutes</option>
                    <option>30 minutes</option>
                    <option>45 minutes</option>
                  </select>
                </p>
                <p>Location: 
                  <select name='SelectLocation'>
                    <option value='' desabled selected>Select One</option>
                    <option>Location 1</option>
                    <option>Location 2</option>
                    <option>Location 3</option>
                  </select>
                </p>
                <p>Delivery method: 
                  <select name='SelectDelivery'>
                    <option value='' desabled selected>Select One</option>
                    <option>Delivery 1</option>
                    <option>Delivery 2</option>
                    <option>Delivery 3</option>
                  </select>
                </p>
                <p>Description:
                  <input type='text' name='Description'>
                </p>
                <p>Main Employee: 
                  <select name='EmpMain'>
                    <option value='' desabled selected>Select One</option>
                    <option>Employee 1</option>
                    <option>Employee 2</option>
                    <option>Employee 3</option>
                  </select>
                </p>
                <p>Second Employee: 
                  <select name='EmpSec'>
                    <option value='' desabled selected>Select One</option>
                    <option>Employee 1</option>
                    <option>Employee 2</option>
                    <option>Employee 3</option>
                  </select>
                </p>
                <p>Third Employee: 
                  <select name='EmpThird'>
                    <option value='' desabled selected>Select One</option>
                    <option>Employee 1</option>
                    <option>Employee 2</option>
                    <option>Employee 3</option>
                  </select>
                <button type='submit' name='submit' class='btn btn-primary'>Schedule</button> 
              </form>
            </div>
            <div class='modal-footer'>
              <button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>
              
            </div>
          </div>
        </div>
      </div>

      <!------------------------------------->

       <!--------------- Modal Code -------------->
       <div class='modal fade' id='newBO' tabindex='-1' role='dialog' aria-labelledby='exampleModalLongTitle' aria-hidden='true'>
        <div class='modal-dialog' role='document'>
          <div class='modal-content'>
            <div class='modal-header'>
              <h5 class='modal-title' id='exampleModalLongTitle'>Schedule New Blockout</h5>
              <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                <span aria-hidden='true'>&times;</span>
              </button>
            </div>
            <div class='modal-body'>
              <form action = 'cal_view.php' method = 'POST'>
              <div class='form-group'>
                  <label>Start Time</label>
                  <input type='Time' class='form-control' name='sTime' required>
              </div>
                       
              <div class='form-group'>
                <label>End Time</label>
                <input type='Time' class='form-control' name='eTime' required>
            </div>  
            <div class='form-group'>
                <label>Reason</label>
                <input type='text' name='Description'>
            </div>
                <button type='submit' name='submit' class='btn btn-primary'>Blockout</button> 
              </form>
            </div>
            <div class='modal-footer'>
              <button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>
              
            </div>
          </div>
        </div>
      </div>

      <!------------------------------------->";




echo"
      <div class = 'row div_space '> 
        <div class = 'col-md-1'></div>
        <div class = 'col-md-10  cal_bg'>
        <div id='items'>";
		
		
		
				
		
		
		while($rowID=$result->fetch_assoc())
		{
			$done=false; //to show that a work order was completed but only on the first blcok
			$counter=1; //for schedule cycling
			$counter2=0; //for blokcout cycling
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
                <th><button type='button' data-toggle='modal' data-target='#newBO'>Blockout</button></th>
                <th colspan='2'><b>".$rowsch['firstName']." ".$rowsch['lastName']."</b></th>";
				
				$items="SELECT starttime, endtime, actualendtime FROM wi_schedule, workitem WHERE wi_schedule.ItemID=workitem.ItemID and workitem.employeeID=".$rowID['employeeID']." and wi_schedule.Date='".$curdate."' order by StartTime";
				$result2=mysqli_query($link,$items);
				$row = mysqli_fetch_array($result2);
				
				$ahours="SELECT StartTime, endtime FROM ahours WHERE ahours.EmployeeID=".$rowID['employeeID']." and ahours.DayID='".$curday."' order by StartTime";
				$result3=mysqli_query($link,$ahours);
				$rowahours = mysqli_fetch_array($result3);
				
				$blackout="SELECT starttime, endtime FROM blackout WHERE blackout.BDate='".$curdate."' and blackout.EmployeeID=".$rowID['employeeID']." order by StartTime";
				$result4=mysqli_query($link,$blackout);
				$rowblock = mysqli_fetch_array($result4);
							
				while($time!='22:00:00') //time day ends 
				{
					$entered=false; //if entered for schdule
 echo"	            <tr class = 'row_height'>";
					echo"<td class = 'hour'>".date('g:ia',strtotime($time))."</td>";
					
					
					
					if($time<$rowahours[0] || $time>=$rowahours[1]) //to show when an employee is or is not working
					{
						if(strtotime('+15 minutes',strtotime($rowahours[0]))==strtotime($rowahours[1]))
							echo "<td class='no_work_15_min'>	</td>";
						else
							echo "<td class='no_work'>	</td>";
						$entered=true;
						$entered2=true;
					}
					
					if($time>=$row[0] && $time<$row[1]) //to show when an employee has an appointment
					{
						if($row[2]!='' && !$done)//checks if an appointment was updated, which would then be considered completed
						{
							if(strtotime('+15 minutes',strtotime($row[0]))==strtotime($row[1]))
								echo "<td class='sched_work_15_min'>Completed</td>";
							else
								echo "<td class='sched_work'>Completed</td>";
							$done=true;
						}
						
						else
						{
							if(strtotime('+15 minutes',strtotime($row[0]))==strtotime($row[1]))
								echo "<td class='sched_work_15_min'>	</td>";
							else
								echo "<td class='sched_work'>	</td>";
							
						}
						
						$entered=true;
					}
					
					else if(mysqli_data_seek($result2,$counter) && $time>$row[1]) //increments the appointments manually 
					{
						mysqli_data_seek($result2,$counter);
						$row = mysqli_fetch_array($result2);
						$counter+=1;
						$done=false;
					}
					
					if($time>=$rowblock[0] && $time<$rowblock[1] && !$entered) //to show when an employee has a blockout time 
					{
						if(strtotime('+15 minutes',strtotime($rowblock[0]))==strtotime($rowblock[1]))
							echo "<td class='blockout_15_min'>	</td>";
						else
							echo "<td class='blockout'>	</td>";
						$entered=true;
					}
					
					else if(mysqli_data_seek($result4,$counter2) && $time>$rowblock[1]) //increments the appointments manually 
					{
						mysqli_data_seek($result4,$counter2);
						$rowblock = mysqli_fetch_array($result4);
						$counter2+=1;
					}
					
					if(!$entered) //if there is nothing happening at a specific time while the employee is on the clock
						echo "<td>	</td>
										
					</tr>";
					
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