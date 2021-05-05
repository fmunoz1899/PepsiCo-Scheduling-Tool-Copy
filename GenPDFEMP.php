<?php
    include('connect.php');
    require('FPDF/fpdf.php');
    date_default_timezone_set("America/New_York");
    session_start();

    if(!isset($_POST['pdf']) && !isset($_SESSION['manager']))
    {
        header("location:login.php?no_a_tok_no_pdf");
        exit;
    }

    if(!isset($_POST['pdf']) && isset($_SESSION['manager']))
    {
        header("location:employees.php?no_pdf");
        exit;
    }
?>

<?php
class PDF extends FPDF
{
    var $widths;
    var $aligns;
    
    
    // DEFAULT METHODS
    function SetWidths($w)
    {
        //Set the array of column widths
        $this->widths=$w;
    }

    function SetAligns($a)
    {
        //Set the array of column alignments
        $this->aligns=$a;
    }

    function Row($data, $r, $g, $b)
    {			
           //Calculate the height of the row
        $nb=0;
        for($i=0;$i<count($data);$i++)
            $nb=max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        $h=5*$nb;
        //Issue a page break first if needed
        $this->CheckPageBreak($h);
        //Draw the cells of the row
        for($i=0;$i<count($data);$i++)
        {
            $w=$this->widths[$i];
            $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
            //Save the current position
            $x=$this->GetX();
            $y=$this->GetY();
            //Draw the border
            $this->SetFillColor($r, $g, $b);
            $this->SetDrawColor(0,0,0);
            $this->SetLineWidth(.05);
            $this->Rect($x, $y, $w-.01, $h+1, 'DF');
                
            //	$this->SetFont('','B');
            //Print the text
            $this->MultiCell($w, 5, $data[$i], "LTR", $a, true);
            //Put the position to the right of the cell
            $this->SetXY($x+$w, $y);
        }
        //Go to the next line
        $this->Ln($h);
    }
    
    function CheckPageBreak($h)
    {
        //If the height h would cause an overflow, add a new page immediately
        if($this->GetY()+$h>$this->PageBreakTrigger)
            $this->AddPage($this->CurOrientation);
    }

    function NbLines($w, $txt)
    {
        //Computes the number of lines a MultiCell of width w will take
        $cw=&$this->CurrentFont['cw'];
        if($w==0)
            $w=$this->w-$this->rMargin-$this->x;
        $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
        $s=str_replace("\r", '', $txt);
        $nb=strlen($s);
        if($nb>0 and $s[$nb-1]=="\n")
            $nb--;
        $sep=-1;
        $i=0;
        $j=0;
        $l=0;
        $nl=1;
        while($i<$nb)
        {
            $c=$s[$i];
            if($c=="\n")
            {
                $i++;
                $sep=-1;
                $j=$i;
                $l=0;
                $nl++;
                continue;
            }
            if($c==' ')
                $sep=$i;
            $l+=$cw[$c];
            if($l>$wmax)
            {
                if($sep==-1)
                {
                    if($i==$j)
                        $i++;
                }
                else
                    $i=$sep+1;
                $sep=-1;
                $j=$i;
                $l=0;
                $nl++;
            }
            else
                $i++;
        }
        return $nl;
    }


    


    // HEADER and FOOTER - automatically executed
    function Header()
    {
       
        $this->Image('pepsi.png',125,5,50);

        $this->Ln(25);
        $this->SetFont('Arial','B',20);
        $this->Cell(300,10,'Employee Information Report '.date('m/d/Y'),0,0,'C');
    }


    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial','I',8);
        $this->Cell(0, 10, 'Page '.$this->PageNo().' of {nb}', 0, 0, 'L');
        $this->Cell(0, 10, date('m/d/Y H:i:s'), 0, 0, 'R');		    
    }
    // ---------- My Custom Tables ---------
    function ClientTable($header, $data, $widths)
    {
        // Color R/G/B parameters between 0 and 255 each
        $this->SetFillColor(0, 180, 252);
        $this->SetTextColor(0); // If only one argument, it's assumed to be gray scale level. Otherwise use 3 parameters for red/green/blue values (0-255 each)
        $this->SetDrawColor(0,0,0);
        $this->SetLineWidth(.1); 
        $this->SetFont('','B');// '' means "keep current font family". 'B' is "bold"

        $w = $widths;
        for($i=0;$i<count($header);$i++)
            $this->Cell($widths[$i],7,$header[$i],1,0,'C',true);
        $this->Ln();

        $this->SetFillColor(225,234,224);
        $this->SetTextColor(0);
        $this->SetDrawColor(0,0,0);
        $this->SetFont('Arial', '', 9);
        $this->SetWidths($widths);
        
        foreach($data as $row)
        {
            $this->Row(array($row[0], $row[1]), 255, 255, 255);				  
        }

    }


    function EventsTable($header, $data, $widths)
		{
			// Color R/G/B parameters between 0 and 255 each
			$this->SetFillColor(0, 180, 252);
			$this->SetTextColor(0); // If only one argument, it's assumed to be gray scale level. Otherwise use 3 parameters for red/green/blue values (0-255 each)
			$this->SetDrawColor(0,0,0);
			$this->SetLineWidth(.1); 
			$this->SetFont('','B');// '' means "keep current font family". 'B' is "bold"

			$w = $widths;
			for($i=0;$i<count($header);$i++)
				$this->Cell($widths[$i],7,$header[$i],1,0,'C',true);
			$this->Ln();

			$this->SetFillColor(225,234,224);
			$this->SetTextColor(0);
			$this->SetDrawColor(0,0,0);
			$this->SetFont('Arial', '', 9);
			$this->SetWidths($widths);
			
			foreach($data as $row)
			{
				$this->Row(array($row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7], $row[8], $row[9]), 255, 255, 255, 255, 255, 255, 255, 255, 255);				  
			}

        }		
        function EventsTable2($header, $data, $widths)
		{
			// Color R/G/B parameters between 0 and 255 each
			$this->SetFillColor(0, 180, 252);
			$this->SetTextColor(0); // If only one argument, it's assumed to be gray scale level. Otherwise use 3 parameters for red/green/blue values (0-255 each)
			$this->SetDrawColor(0,0,0);
			$this->SetLineWidth(.1); 
			$this->SetFont('','B');// '' means "keep current font family". 'B' is "bold"

			$w = $widths;
			for($i=0;$i<count($header);$i++)
				$this->Cell($widths[$i],7,$header[$i],1,0,'C',true);
			$this->Ln();

			$this->SetFillColor(225,234,224);
			$this->SetTextColor(0);
			$this->SetDrawColor(0,0,0);
			$this->SetFont('Arial', '', 9);
			$this->SetWidths($widths);
			
			foreach($data as $row)
			{
				$this->Row(array($row[0], $row[1], $row[2], $row[3], $row[4]), 255, 255, 255);				  
			}

		}		
}

//==========================    MAIN CODE   =============================


	/* ------------ Get the POST variables and access the database  ------------- */

    include('connect.php');


    $peopleinfo="SELECT firstName, lastName, email, phoneNumber, PrivilegeID, employee.employeeID
						FROM employee, employeeprivlege, email, phone, team 
						where employee.EmployeeID=email.EmployeeID 
						and email.Type='Work' 
						and employee.EmployeeID=phone.EmployeeID 
						and phone.Type='Work' 
						and managerid LIKE '".$_SESSION['username']."'
						and team.employeeid=Employee.EmployeeID
						and employee.EmployeeID=employeeprivlege.EmployeeID
						and PrivilegeID='E'";
						
						$result=mysqli_query($link,$peopleinfo);
                    if ($result->num_rows > 0) 
                    {
						$empArray = array();
						while($row=$result->fetch_assoc())
						{

							$schedule="SELECT DayID, TIME_FORMAT(StartTime, '%l:%i%p') AS StartTime, TIME_FORMAT(EndTime, '%l:%i%p') AS EndTime, employeeID 
							FROM ahours WHERE EmployeeID=".$row['employeeID']." order by case 
							when DayID='Sun' then 1 
							when DayID='Mon' then 2 
							when DayID='Tue' then 3 
							when DayID='Wed' then 4 
							when DayID='Thr' then 5 
							when DayID='Fri' then 6 
							when DayID='Sat' then 7 
							else 8 end asc";
							$quick=mysqli_query($link,$schedule);
							
                            $Fname = $row['firstName'];
                            $Lname = $row['lastName'];
                            $Ename = $row['lastName'].", ".$row['firstName'];
                            $email = $row['email'];
                            $phone = "(". substr($row['phoneNumber'],0,3) .")-".substr($row['phoneNumber'],3,3)."-".substr($row['phoneNumber'],6,4);
                            $sunTime = '';
                            $monTime = '';
                            $tueTime = '';
                            $wedTime = '';
                            $thuTime = '';
                            $friTime = '';
                            $satTime = '';
							while($fullschd=$quick->fetch_assoc())
							{
								if($fullschd['DayID']=='Sun')
                                {
                                    $sunStime = $fullschd['StartTime'];
                                    $sunEtime = $fullschd['EndTime'];
                                    $sunTime = $sunStime."-".$sunEtime;
                                }
								if($fullschd['DayID']=='Mon')
                                {
                                    $monStime = $fullschd['StartTime'];
                                    $monEtime = $fullschd['EndTime'];
                                    $monTime = $monStime ."-".$monEtime;
                                    
                                }
								if($fullschd['DayID']=='Tue')
                                {
                                    $tueStime = $fullschd['StartTime'];
                                    $tueEtime = $fullschd['EndTime'];
									$tueTime = $tueStime ."-". $tueEtime;
                                }
								if($fullschd['DayID']=='Wed')
                                {
                                    $wedStime = $fullschd['StartTime'];
                                    $wedEtime = $fullschd['EndTime']; 
                                    $wedTime = $wedStime ."-". $wedEtime;
                                }
								if($fullschd['DayID']=='Thu')
                                {
                                    $thuStime = $fullschd['StartTime'];
                                    $thuEtime = $fullschd['EndTime'];
                                    $thuTime = $thuStime ."-". $thuEtime;
                                }
								if($fullschd['DayID']=='Fri')
                                {
                                    $friStime = $fullschd['StartTime'];
                                    $friEtime = $fullschd['EndTime'];
                                    $friTime = $friStime ."-". $friEtime;
                                }
								if($fullschd['DayID']=='Sat')
                                {
                                    $satStime = $fullschd['StartTime'];
                                    $satEtime = $fullschd['EndTime'];
									$satTime = $satStime ."-". $satEtime;
                                }
                                
                                
							}
                            array_push($empArray, array($Ename, $email, $phone, $sunTime, $monTime, $tueTime, $wedTime, $thuTime, $friTime, $satTime));
                           

                        }
                        
                    
                    }


                    else
                    {
                        echo "Nothing Found";
                    }
                    	/* ------------ START THE PDF GENERATION ------------- */
        $pdf = new PDF();
        
        $pdf->AliasNbPages();	
        $pdf->AddPage('L',"A4");
        $pdf->Ln(5);
        $pdf->SetFont('Arial', '', 9);
        $pdf->SetDrawColor(0,0,0);
        
        $eventdetailhead = array('Name', 'Email', 'Phone', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
        $eventWidth2=array(20,40,27,28,28,28,28,28,28,28,28);
        $pdf->Ln(5);
        $pdf->EventsTable($eventdetailhead, $empArray, $eventWidth2);
        
        $tempFileName = 'Report'.time().'.pdf';
        $pdf->Output('F:/Downloads/'.$tempFileName, 'F');
        
        echo "<script>window.location.href='employees.php?st=".$tempFileName."'; </script>";

		
		$link->close();



?>