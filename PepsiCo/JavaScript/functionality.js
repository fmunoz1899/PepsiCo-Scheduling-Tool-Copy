$(document).ready(

    function()
    {
      $("#LV").click(
        function()
        {
            //alert("clicked");
            location.href = "List_View.php";
        });
		
        $("#CV").click(
            function()
            {
                location.href = "Cal_View.php";
            });
        $("#cancelPwd").click(
            function()
            {
                location.href = "EmployeeInfo.php";
            });
       //$("#pwd").password('toggle');



//------------------ New JS Code ---------------------
	$(".UpdateNnum").click(
            function(e)
            {
                var phone = e.target.value;
                //var empID = String("#"+phone + "phone")
                var phoneID = $("#eIDPhone").val();
               // //console.log(phone);
                ////console.log(phoneID);

				$('#HiddenNum').val(phone);
                $('#Number').val(phone);
                $('#EmpIDForPN2').val(phoneID);
                
            });
    $(".EmailUpdate").click(
        function(e)
        {
            var email = e.target.value;
            var emailID = $("#eIDEmail").val();
            ////console.log(email);
           // //console.log(emailID);
            
			$('#HiddenEm').val(email);
            $('#EmailToEdit').val(email);
            $('#EmpIDForEM').val(emailID);
            
        });
    $(".CompWOTime").click(
        function(e)
        {
            var id = e.target.value;

            var time = String("#"+id + "AET");
            var tme = $(time).text();
			
			var itemid = String("#"+id + "ID");
			var itid = $(itemid).text();
			
			$('#WOIDs').val(itid);
			
			$('#EmpID').val(id);
            
			if(tme != "")
            {
               
               $('#Et').text(tme);
               $('#Et').val(tme);//Updated Value
            }
            else
            {
                $('#Et').text("N/A");
				$('#Et').val("na");//Updated Value

            }

            
           
            
        });

    // $(".sid").click(
    //     function(e)
    //     {
    //         var woID = e.target.value;
    //         var primEmp = String("#"+ woID + "pe")
    //         //console.log(primEmp);
    //         var name = $(primEmp).val();
    //         $('#MainEng').val(name);
    //         //console.log(name);
    //     });
    // $('#Details').on('show.bs.modal', function (e) {
    //     var woID = e.target.value;
    //     $.ajax({
    //         type : 'post',
    //         url : 'cal_view.php', //Here you will fetch records 
    //         data :  'rowid='+ woID, //Pass $id
    //         success : function(data){
    //             //console.log(data);
    //             $('#MainEng').text(data);//Show fetched data from database
    //         }
    //     });
    //     });

    $(".ELoc").click(
        function(e)
        {
            
            var locID = e.target.value;
            var locationN = String("#"+locID + "locname")
            var name = $(locationN).text();
            var address = String("#"+locID + "addr")
            var adr = $(address).text();
            var city = String("#"+locID + "cit")
            var cit = $(city).text();
            var st = String("#"+locID + "state")
            var state = $(st).text();
            var zc = String("#"+locID + "zip")

            var zipc = $(zc).text();

            $('#LID').val(locID);
            $('#LN').val(name);
            $('#add').val(adr);
            $('#cit').val(cit);
            $('#state').val(state);
            $('#ZC').val(zipc);
            
        });
        $(".ELocMgr").click(
            function(e)
            {
                
                var locID = e.target.value;
                var locationN = String("#"+locID + "locname")
                var name = $(locationN).text();
                var address = String("#"+locID + "addr")
                var adr = $(address).text();
                var city = String("#"+locID + "cit")
                var cit = $(city).text();
                var st = String("#"+locID + "state")
                var state = $(st).text();
                var zc = String("#"+locID + "zip")
                
                var zipc = $(zc).text();
    
                $('#LID').val(locID);
                $('#LN').val(name);
                $('#add').val(adr);
                $('#cit').val(cit);
                $('#stateEdit').val(state);
                $('#ZC').val(zipc);
                  
            });
            $(".ELocFltrMgr ").click(
                function(e)
                {
                    
                    var locID = e.target.value;
                    var locationN = String("#"+locID + "locname")
                    var name = $(locationN).text();
                    var address = String("#"+locID + "addr")
                    var adr = $(address).text();
                    var city = String("#"+locID + "cit")
                    var cit = $(city).text();
                    var st = String("#"+locID + "state")
                    var state = $(st).text();
                    var zc = String("#"+locID + "zip")
                    
                    var zipc = $(zc).text();
        
                    $('#LID').val(locID);
                    $('#LN').val(name);
                    $('#add').val(adr);
                    $('#cit').val(cit);
                    $('#state').val(state);
                    $('#ZC').val(zipc);
                     
                });
            $(".ELocAdmn ").click(
                function(e)
                {
                    
                    var locID = e.target.value;
                    var locationN = String("#"+locID + "locname")
                    var name = $(locationN).text();
                    var address = String("#"+locID + "addr")
                    var adr = $(address).text();
                    var city = String("#"+locID + "cit")
                    var cit = $(city).text();
                    var st = String("#"+locID + "state")
                    var state = $(st).text();
                    var zc = String("#"+locID + "zip")
                    
                    var zipc = $(zc).text();
        
                    $('#LID').val(locID);
                    $('#LN').val(name);
                    $('#add').val(adr);
                    $('#cit').val(cit);
                    $('#stateAEdit').val(state);
                    $('#ZC').val(zipc);
                    
                });
            $(".ELocAdmnFltr ").click(
                function(e)
                {
                    
                    var locID = e.target.value;
                    var locationN = String("#"+locID + "locname")
                    var name = $(locationN).text();
                    var address = String("#"+locID + "addr")
                    var adr = $(address).text();
                    var city = String("#"+locID + "cit")
                    var cit = $(city).text();
                    var st = String("#"+locID + "state")
                    var state = $(st).text();
                    var zc = String("#"+locID + "zip")
                    
                    var zipc = $(zc).text();
        
                    $('#LID').val(locID);
                    $('#LN').val(name);
                    $('#add').val(adr);
                    $('#cit').val(cit);
                    $('#state').val(state);
                    $('#ZC').val(zipc);
                        
                });

                $(".EdSched ").click(
                    function(e)
                    {
                        
                        var eID = e.target.value;

                        var SundayS = String("#"+ eID + "SunSt")
                        var StartSun = $(SundayS).text();
                        var SundayE = String("#"+ eID + "SunEd")
                        var EndSun = $(SundayE).text();

                        var MondayS = String("#"+ eID + "MonSt")
                        var StartMon = $(MondayS).text();
                        var MondayE = String("#"+ eID + "MonEd")
                        var EndMon = $(MondayE).text();
                        
                        var TuesdayS = String("#"+ eID + "TueSt")
                        var StartTues = $(TuesdayS).text();
                        var TuesdayE = String("#"+ eID + "TuesEd")
                        var EndTues = $(TuesdayE).text();

                        var WednesdayS = String("#"+ eID + "WedSt")
                        var StartWed = $(WednesdayS).text();
                        var WednesdayE = String("#"+ eID + "WedEd")
                        var EndWed = $(WednesdayE).text();

                        var ThursdayS = String("#"+ eID + "ThuSt")
                        var StartThurs = $(ThursdayS).text();
                        var ThursdayE = String("#"+ eID + "ThuEd")
                        var EndThurs = $(ThursdayE).text();


                        var FriedayS = String("#"+ eID + "FriSt")
                        var StartFri = $(FriedayS).text();
                        var FridayE = String("#"+ eID + "FriEd")
                        var EndFri = $(FridayE).text();

                        var SaturdayS = String("#"+ eID + "SatSt")
                        var StartSat = $(SaturdayS).text();
                        var SaturdayE = String("#"+ eID + "SatEd")
                        var EndSat = $(SaturdayE).text();
                        
            
                        $('#EmpID').val(eID);

                        $('#SuS').text(StartSun);
                        $('#SuS').val(StartSun);//Sunday Start value
                        $('#SuE').text(EndSun);
                        $('#SuE').val(EndSun);//Sunday End value
  
                        $('#MS').text(StartMon);
                        $('#MS').val(StartMon);//Monday Start value
                        $('#ME').text(EndMon);
                        $('#ME').val(EndMon);//Monday End value

                        $('#TS').text(StartTues);
                        $('#TS').val(StartTues);//Tuesday Start value
                        $('#TE').text(EndTues);
                        $('#TE').val(EndTues);//Tuesday End value

                        $('#WS').text(StartWed);
                        $('#WS').val(StartWed);//Wednesday Start value
                        $('#WE').text(EndWed);
                        $('#WE').val(EndWed);//Wednesday End value

                        $('#ThS').text(StartThurs);
                        $('#ThS').val(StartThurs);//Thursday Start value
                        $('#ThE').text(EndThurs);
                        $('#ThE').val(EndThurs);//Thursday End value

                        $('#FS').text(StartFri);
                        $('#FS').val(StartFri);//Friday Start value
                        $('#FE').text(EndFri);
                        $('#FE').val(EndFri);//Friday End value

                        $('#SaS').text(StartSat);
                        $('#SaS').val(StartSat);//Saturday Start value
                        $('#SaE').text(EndSat);
                        $('#SaE').val(EndSat);//Sunday End value
      
                    });
                $(".EditWOCal").click(
                    function(e)
                    {
                        
                        var WOID = e.target.value;
						//console.log(WOID);
                        var Pname = String("#"+ WOID + "name")
                        var name = $(Pname).text();
						
						var pnID = String("#"+ WOID + "pid")
                        var pid = $(pnID).text();
						
						var locID = String("#"+ WOID + "lid") //here
                        var lid = $(locID).text();

                        var Sname = String("#"+ WOID + "sname")
                        var sname = $(Sname).text();
						
						var snID = String("#"+ WOID + "sid")
                        var sid = $(snID).text();

                        var Tname = String("#"+ WOID + "tname")
                        var tname = $(Tname).text();
						
						var tnID = String("#"+ WOID + "tid")
                        var tid = $(tnID).text();

                        var LocN = String("#"+ WOID + "lname")
                        var location = $(LocN).text();

                        var start = String("#"+ WOID + "stime")
                        var times = $(start).text();

                        var end = String("#"+ WOID + "etime")
                        var timee = $(end).text();

                        var dte = String("#"+ WOID + "date")
                        var date = $(dte).text();

                        var mth = String("#"+ WOID + "meth")
                        var method = $(mth).text();

                        var des = String("#"+ WOID + "desc")
                        var description = $(des).text();

                        

                        $('#orderID').val(WOID);

                        $('#ee1').text(name);
                        $('#ee1').val(pid); // value set
						
						console.log(name);
						console.log(pid);

                        $('#ee2').text(sname);
                        $('#ee2').val(sid); // value set
						
						//console.log(sname);
						//console.log(sid);

                        $('#ee3').text(tname);
                        $('#ee3').val(tid); // value set
						
						//console.log(tname);
						//console.log(tid);

                        $('#datepicker5').text(date);
                        $('#datepicker5').val(date); // value set

                        $('#startTime').text(times);
                        $('#startTime').val(times); // value set

                        $('#endTime').text(timee);
                        $('#endTime').val(timee); // value set

                        $('#locu').text(location);
                        $('#locu').val(lid); // value set
						
						//console.log(location);
						//console.log(lid);

                        if(method == "BULK")
                        {
                            $("#bulkEdit").prop("checked", true); 
                        }
                        else if(method == "GEO")
                        {
                            $("#geoEdit").prop("checked", true); 
                        }
                        else if(method == "FSV")
                        {

                            $("#fsvEdit").prop('checked', true); 
                        }
                        else if(method == "DBAY")
                        {
                            $("#dbayEdit").prop("checked", true); 
                        }
                        $('#woDesc').text(description);
						$('#CalWOID').val(WOID); 
						$('#CalWOID').text(WOID);
                    });
				 $(".EditWO ").click(
                    function(e)
                    {
                        
                        var WOID = e.target.value;
						//console.log(WOID);
                        var Pname = String("#"+ WOID + "name")
                        var name = $(Pname).text();
						
						var pnID = String("#"+ WOID + "pid")
                        var pid = $(pnID).text();
						
						var locID = String("#"+ WOID + "lid") //here
                        var lid = $(locID).text();

                        var Sname = String("#"+ WOID + "sname")
                        var sname = $(Sname).text();
						
						var snID = String("#"+ WOID + "sid")
                        var sid = $(snID).text();

                        var Tname = String("#"+ WOID + "tname")
                        var tname = $(Tname).text();
						
						var tnID = String("#"+ WOID + "tid")
                        var tid = $(tnID).text();

                        var LocN = String("#"+ WOID + "lname")
                        var location = $(LocN).text();

                        var start = String("#"+ WOID + "stime")
                        var times = $(start).text();

                        var end = String("#"+ WOID + "etime")
                        var timee = $(end).text();

                        var dte = String("#"+ WOID + "date")
                        var date = $(dte).text();

                        var mth = String("#"+ WOID + "meth")
                        var method = $(mth).text();

                        var des = String("#"+ WOID + "desc")
                        var description = $(des).text();

                        

                        $('#orderID').val(WOID);
						$('#orderIDDel').val(WOID);
						$('#orderIDDel').text(WOID);

                        $('#ee1').text(name);
                        $('#ee1').val(pid); // value set
						
						//console.log(name);
						//console.log(pid);

                        $('#ee2').text(sname);
                        $('#ee2').val(sid); // value set
						
						//console.log(sname);
						//console.log(sid);

                        $('#ee3').text(tname);
                        $('#ee3').val(tid); // value set
						
						//console.log(tname);
						//console.log(tid);

                        $('#datepicker5').text(date);
                        $('#datepicker5').val(date); // value set

                        $('#startTime').text(times);
                        $('#startTime').val(times); // value set

                        $('#endTime').text(timee);
                        $('#endTime').val(timee); // value set

                        $('#locu').text(location);
                        $('#locu').val(lid); // value set
						
						//console.log(location);
						//console.log(lid);
			
                        if(method == "BULK")
                        {
                            $("#bulkEdit").prop("checked", true); 
                        }
                        else if(method == "GEO")
                        {
                            $("#geoEdit").prop("checked", true); 
                        }
                        else if(method == "FSV")
                        {

                            $("#fsvEdit").prop('checked', true); 
                        }
                        else if(method == "DBAY")
                        {
                            $("#dbayEdit").prop("checked", true); 
                        }
                        $('#woDesc').text(description);
						 

                            
                    });
					$(".WODetails ").click(
						function(e)
						{
							
							var WOID = e.target.value;
							//console.log(WOID);
							var Pname = String("#"+ WOID + "name")
							var name = $(Pname).text();
							
							var pnID = String("#"+ WOID + "pid")
							var pid = $(pnID).text();
							
							var locID = String("#"+ WOID + "lid") //here
							var lid = $(locID).text();

							var Sname = String("#"+ WOID + "sname")
							var sname = $(Sname).text();
							
							var snID = String("#"+ WOID + "sid")
							var sid = $(snID).text();

							var Tname = String("#"+ WOID + "tname")
							var tname = $(Tname).text();
							
							var tnID = String("#"+ WOID + "tid")
							var tid = $(tnID).text();

							var LocN = String("#"+ WOID + "lname")
							var location = $(LocN).text();

							var start = String("#"+ WOID + "stime")
							var times = $(start).text();

							var end = String("#"+ WOID + "etime")
							var timee = $(end).text();

							var dte = String("#"+ WOID + "date")
							var date = $(dte).text();

							var mth = String("#"+ WOID + "meth")
							var method = $(mth).text();

							var des = String("#"+ WOID + "desc")
							var description = $(des).text();

							var actual = String("#"+ WOID + "aet");
							var aend = $(actual).text();


							//$('#ee1Det').text(name);
							$('#ee1Det').val(name); // value set
							
							//console.log(name);
							//console.log(pid);

							//$('#ee2Det').text(sname);
							$('#ee2Det').val(sname); // value set
							
							//console.log(sname);
							//console.log(sid);

							//$('#ee3Det').text(tname);
							$('#ee3Det').val(tname); // value set
							
							//console.log(tname);
							//console.log(tid);

							$('#datepicker5Det').text(date);
							$('#datepicker5Det').val(date); // value set

							$('#startTimeDet').text(times);
							$('#startTimeDet').val(times); // value set

							$('#endTimeDet').text(timee);
							$('#endTimeDet').val(timee); // value set

							//$('#locuDet').text(location);
							$('#locuDet').val(location); // value set
							
							$('#methDet').text(method);
							$('#methDet').val(method);
							
							$('#AETDet').text(aend);
							$('#AETDet').val(aend);

							//console.log(method);


							$('#woDescDet').text(description);
								
						});
					$(".WODetailsLV").click(
						function(e)
						{
							
							var WOID = e.target.value;
							//console.log(WOID);
							var Pname = String("#"+ WOID + "name")
							var name = $(Pname).text();
							
							var pnID = String("#"+ WOID + "pid")
							var pid = $(pnID).text();
							
							var locID = String("#"+ WOID + "lid") //here
							var lid = $(locID).text();

							var Sname = String("#"+ WOID + "sname")
							var sname = $(Sname).text();
							
							var snID = String("#"+ WOID + "sid")
							var sid = $(snID).text();

							var Tname = String("#"+ WOID + "tname")
							var tname = $(Tname).text();
							
							var tnID = String("#"+ WOID + "tid")
							var tid = $(tnID).text();

							var LocN = String("#"+ WOID + "lname")
							var location = $(LocN).text();

							var start = String("#"+ WOID + "stime")
							var times = $(start).text();

							var end = String("#"+ WOID + "etime")
							var timee = $(end).text();

							var dte = String("#"+ WOID + "date")
							var date = $(dte).text();

							var mth = String("#"+ WOID + "meth")
							var method = $(mth).text();

							var des = String("#"+ WOID + "desc")
							var description = $(des).text();

							var actual = String("#"+ WOID + "aet");
							var aend = $(actual).text();


							//$('#ee1Det').text(name);
							$('#ee1Det').val(name); // value set
							
							//console.log(name);
							//console.log(pid);

							//$('#ee2Det').text(sname);
							$('#ee2Det').val(sname); // value set
							
							//console.log(sname);
							//console.log(sid);

							//$('#ee3Det').text(tname);
							$('#ee3Det').val(tname); // value set
							
							//console.log(tname);
							//console.log(tid);

							$('#datepicker5Det').text(date);
							$('#datepicker5Det').val(date); // value set

							$('#startTimeDet').text(times);
							$('#startTimeDet').val(times); // value set

							$('#endTimeDet').text(timee);
							$('#endTimeDet').val(timee); // value set

							//$('#locuDet').text(location);
							$('#locuDet').val(location); // value set
							
							$('#methDet').text(method);
							$('#methDet').val(method);
							
							$('#AETDet').text(aend);
							$('#AETDet').val(aend);

							//console.log(method);


							$('#woDescDet').text(description);
								
						});

                $(".EditBO").click(
                    function(e)
                    {
                        
                        var BOID = e.target.value;
						//console.log(BOID);
                        var Ename = String("#"+ BOID + "name")
                        var name = $(Ename).text();
                        //console.log(name);

                        var start = String("#"+ BOID + "sTime")
                        var stime = $(start).text();
                        //console.log(stime);

                        var end = String("#"+ BOID + "eTime")
                        var etime = $(end).text();
                        //console.log(etime);

                        var reason = String("#"+ BOID + "reason")
                        var editReason = $(reason).text();
                        //console.log(editReason);

                        var date = String("#"+ BOID + "date")
                        var dte = $(date).text();
                        //console.log(dte);

                        var eid = String("#"+ BOID + "eid")
                        var id = $(eid).text();
                        //console.log(id);

                        $('#BOtimeID').val(BOID);
                        $('#BOtimeID').text(BOID);
						
						$('#BOtimeIDRM').val(BOID);
                        $('#BOtimeIDRM').text(BOID);

                        $('#EmpID').val(id);
                        $('#EmpID').text(id);

                        $('#startTime').text(stime);
                        $('#startTime').val(stime); // value set

                        $('#endTime').text(etime);
                        $('#endTime').val(etime); // value set
						
						//console.log(dte);
                        $('#datepicker6').text(dte);
                        $('#datepicker6').val(dte); // value set

                        $('#whoEdit').text(name);
                        $('#whoEdit').val(name); // value set

                        $('#reasonEdit').text(editReason);
                        $('#reasonEdit').val(editReason); // value set

                            
                    });
					$(".EditBOLV").click(
                    function(e)
                    {
                        
                        var BOID = e.target.value;
						//console.log(BOID);
                        var Ename = String("#"+ BOID + "name")
                        var name = $(Ename).text();
                        //console.log(name);

                        var start = String("#"+ BOID + "sTime")
                        var stime = $(start).text();
                        //console.log(stime);

                        var end = String("#"+ BOID + "eTime")
                        var etime = $(end).text();
                        //console.log(etime);

                        var reason = String("#"+ BOID + "reason")
                        var editReason = $(reason).text();
                        //console.log(editReason);

                        var date = String("#"+ BOID + "date")
                        var dte = $(date).text();
                        //console.log(dte);

                        var eid = String("#"+ BOID + "eid")
                        var id = $(eid).text();
                        //console.log(id);

                        $('#BOtimeID').val(BOID);
                        $('#BOtimeID').text(BOID);
						
						$('#BOtimeIDRM').val(BOID);
                        $('#BOtimeIDRM').text(BOID);

                        $('#EmpID').val(id);
                        $('#EmpID').text(id);

                        $('#startTime').text(stime);
                        $('#startTime').val(stime); // value set

                        $('#endTime').text(etime);
                        $('#endTime').val(etime); // value set
						
						//console.log(dte);
                        $('#datepicker6EmpLV').text(dte);
                        $('#datepicker6EmpLV').val(dte); // value set

                        $('#whoEdit').text(name);
                        $('#whoEdit').val(name); // value set

                        $('#reasonEdit').text(editReason);
                        $('#reasonEdit').val(editReason); // value set

                            
                    });
				
                $(".EditBOCal").click(
                    function(e)
                    {
                        
                        var BOID = e.target.value;
                        var Ename = String("#"+ BOID + "name")
                        var name = $(Ename).text();
                        //console.log(name);

                        var start = String("#"+ BOID + "sTime")
                        var stime = $(start).text();
                        //console.log(stime);

                        var end = String("#"+ BOID + "eTime")
                        var etime = $(end).text();
                        //console.log(etime);

                        var reason = String("#"+ BOID + "reason")
                        var editReason = $(reason).text();
                        //console.log(editReason);

                        var date = String("#"+ BOID + "date")
                        var dte = $(date).text();
                        //console.log(dte);

                        var eid = String("#"+ BOID + "eid")
                        var id = $(eid).text();
                        //console.log(id);

                        $('#BOtimeID').val(BOID);
                        $('#BOtimeID').text(BOID);
						$('#BOtimeID2').val(BOID);
						$('#BOtimeID2').text(BOID);
						//console.log(BOID);
                        $('#EmpIDBO').val(id);
						$('#EmpIDBO2').val(id);
                        //$('#EmpID').text(id);

                        $('#startTimeBO').text(stime);
                        $('#startTimeBO').val(stime); // value set

                        $('#endTimeBO').text(etime);
                        $('#endTimeBO').val(etime); // value set

                        $('#datepicker6').text(dte);
                        $('#datepicker6').val(dte); // value set

                        $('#whoEditBO').text(name);
                        $('#whoEditBO').val(name); // value set

                        $('#reasonEdit').text(editReason);
                        $('#reasonEdit').val(editReason); // value set

                            
                    });
					$(".BOCalDet").click(
                    function(e)
                    {
                        
                        var BOID = e.target.value;
                        var Ename = String("#"+ BOID + "name")
                        var name = $(Ename).text();
                        //console.log(name);

                        var start = String("#"+ BOID + "sTime")
                        var stime = $(start).text();
                        //console.log(stime);

                        var end = String("#"+ BOID + "eTime")
                        var etime = $(end).text();
                        //console.log(etime);

                        var reason = String("#"+ BOID + "reason")
                        var editReason = $(reason).text();
                        //console.log(editReason);

                        var date = String("#"+ BOID + "date")
                        var dte = $(date).text();
                        //console.log(dte);

                        var eid = String("#"+ BOID + "eid")
                        var id = $(eid).text();
                        //console.log(id);

                        

                        $('#EmpIDBO').val(id);
                        //$('#EmpID').text(id);

                        $('#startTimeDetBO').text(stime);
                        $('#startTimeDetBO').val(stime); // value set

                        $('#endTimeDetBO').text(etime);
                        $('#endTimeDetBO').val(etime); // value set

                        $('#datepicker6DetBO').text(dte);
                        $('#datepicker6DetBO').val(dte); // value set

                        $('#whoEditDetBO').text(name);
                        $('#whoEditDetBO').val(name); // value set

                        $('#reasonEditDetBO').text(editReason);
                        $('#reasonEditDetBO').val(editReason); // value set

                            
                    });
				$(".BOListFltrDet").click(
                    function(e)
                    {
                        
                        var BOID = e.target.value;
                        var Ename = String("#"+ BOID + "name")
                        var name = $(Ename).text();
                        //console.log(name);

                        var start = String("#"+ BOID + "sTime")
                        var stime = $(start).text();
                        //console.log(stime);

                        var end = String("#"+ BOID + "eTime")
                        var etime = $(end).text();
                        //console.log(etime);

                        var reason = String("#"+ BOID + "reason")
                        var editReason = $(reason).text();
                        //console.log(editReason);

                        var date = String("#"+ BOID + "date")
                        var dte = $(date).text();
                        //console.log(dte);

                        var eid = String("#"+ BOID + "eid")
                        var id = $(eid).text();
                        //console.log(id);

                        

                        $('#EmpIDBO').val(id);
                        //$('#EmpID').text(id);
						

                        $('#startTimeDetBO').text(stime);
                        $('#startTimeDetBO').val(stime); // value set

                        $('#endTimeDetBO').text(etime);
                        $('#endTimeDetBO').val(etime); // value set

                        $('#datepicker6DetBO').text(dte);
                        $('#datepicker6DetBO').val(dte); // value set

                        $('#whoEditDetBO').text(name);
                        $('#whoEditDetBO').val(name); // value set

                        $('#reasonEditDetBO').text(editReason);
                        $('#reasonEditDetBO').val(editReason); // value set

                            
                    });
                $(".EditBOFltr").click(
                    function(e)
                    {
                        
                        var BOID = e.target.value;
                        var Ename = String("#"+ BOID + "name")
                        var name = $(Ename).text();
                        ////console.log(name);

                        var start = String("#"+ BOID + "sTime")
                        var stime = $(start).text();
                        ////console.log(stime);

                        var end = String("#"+ BOID + "eTime")
                        var etime = $(end).text();
                        ////console.log(etime);

                        var reason = String("#"+ BOID + "reason")
                        var editReason = $(reason).text();
                       // //console.log(editReason);

                        var date = String("#"+ BOID + "date")
                        var dte = $(date).text();
                        ////console.log(dte);

                        var eid = String("#"+ BOID + "eid")
                        var id = $(eid).text();
                       // //console.log(id);

                        $('#BOtimeID').val(BOID);
                        //$('#BOtimeID').text(BOID);

                        $('#EmpID').val(id);
                        //$('#EmpID').text(id);
						$('#BOtimeIDRM').val(BOID);
                        $('#BOtimeIDRM').text(BOID);
						//console.log(id);

                        $('#startTime').text(stime);
                        $('#startTime').val(stime); // value set

                        $('#endTime').text(etime);
                        $('#endTime').val(etime); // value set

                        $('#datepicker7').text(dte);
                        $('#datepicker7').val(dte); // value set

                        $('#whoEdit').text(name);
                        $('#whoEdit').val(name); // value set

                        $('#reasonEdit').text(editReason);
                        $('#reasonEdit').val(editReason); // value set

                            
                    });
					
					$(".EditWOCalEmp ").click(
						function(e)
						{
							
							var WOID = e.target.value;
							
							var pnID = String("#"+ WOID + "pid")
							var pid = $(pnID).text();
							
							var locID = String("#"+ WOID + "lid") //here
							var lid = $(locID).text();

							var LocN = String("#"+ WOID + "lname")
							var location = $(LocN).text();

							var start = String("#"+ WOID + "stime")
							var times = $(start).text();

							var end = String("#"+ WOID + "etime")
							var timee = $(end).text();

							var dte = String("#"+ WOID + "date")
							var date = $(dte).text();

							var mth = String("#"+ WOID + "meth")
							var method = $(mth).text();

							var des = String("#"+ WOID + "desc")
							var description = $(des).text();

							var actual = String("#"+ WOID + "aet");
							var aend = $(actual).text();

							$('#WOIDEdt').text(WOID);
							$('#WOIDEdt').val(WOID);
							
							$('#datepicker5DetEdt').text(date);
							$('#datepicker5DetEdt').val(date); // value set

							$('#startTimeDetEdt').text(times);
							$('#startTimeDetEdt').val(times); // value set

							$('#endTimeDetEdt').text(timee);
							$('#endTimeDetEdt').val(timee); // value set

							$('#locuDetEdt').text(location);
							$('#locuDetEdt').val(location); // value set
							
							$('#methDetEdt').text(method);
							$('#methDetEdt').val(method);
							
							if(aend != "")
							{
							   
							   $('#AETEdt').text(aend);
							   $('#AETEdt').val(aend);//Updated Value
							}
							else
							{
								$('#AETEdt').text("N/A");
								$('#AETEdt').val("na");//Updated Value

							}
							//console.log(method);


							$('#woDescDetEdt').text(description);
								
						});
						$(".EmpWOCAlPassed").click(
						function(e)
						{
							
							var WOID = e.target.value;
							
							var pnID = String("#"+ WOID + "pid")
							var pid = $(pnID).text();
							
							var locID = String("#"+ WOID + "lid") //here
							var lid = $(locID).text();

							var LocN = String("#"+ WOID + "lname")
							var location = $(LocN).text();

							var start = String("#"+ WOID + "stime")
							var times = $(start).text();

							var end = String("#"+ WOID + "etime")
							var timee = $(end).text();

							var dte = String("#"+ WOID + "date")
							var date = $(dte).text();

							var mth = String("#"+ WOID + "meth")
							var method = $(mth).text();

							var des = String("#"+ WOID + "desc")
							var description = $(des).text();

							var actual = String("#"+ WOID + "aet");
							var aend = $(actual).text();

							$('#WOIDPass').text(WOID);
							$('#WOIDPass').val(WOID);

							$('#datepicker5DetPass').text(date);
							$('#datepicker5DetPass').val(date); // value set

							$('#startTimeDetPass').text(times);
							$('#startTimeDetPass').val(times); // value set

							$('#endTimeDetPass').text(timee);
							$('#endTimeDetPass').val(timee); // value set

							$('#locuDetPass').text(location);
							$('#locuDetPass').val(location); // value set
							
							$('#methDetPass').text(method);
							$('#methDetPass').val(method);
							
							if(aend != "")
							{
							   
							   $('#AETPass').text(aend);
							   $('#AETPass').val(aend);//Updated Value
							}
							else
							{
								$('#AETPass').text("N/A");
								$('#AETPass').val("na");//Updated Value

							}
							$('#woDescDetPass').text(description);
							
								
						});
					$(".getMgr").click(
						function(e)
						{
							
							var mgID = e.target.value;
							console.log(mgID);
							var pnID = String("#"+ mgID + "pid")
							var pid = $(pnID).text();
							
							

							
							
								
						});



//------------------ End More will be placed above ---------------------	
		$("#BV").click(
            function()
            {
                location.href = "blocklist_view.php";
            });
			
	   $("#ELV").click(
        function()
        {
            //alert("clicked");
            location.href = "empList_View.php";
        });
		
        $("#ECV").click(
            function()
            {
                location.href = "empCal_View.php";
            });
			
		$("#BCV").click(
            function()
            {
                location.href = "empblocklist_View.php";
            });
			

			
       			
            $.fn.hScroll = function (amount) {
                amount = amount || 120;
                $(this).bind("DOMMouseScroll mousewheel", function (event) {
                    var oEvent = event.originalEvent, 
                        direction = oEvent.detail ? oEvent.detail * -amount : oEvent.wheelDelta, 
                        position = $(this).scrollLeft();
                    position += direction > 0 ? -amount : amount;
                    $(this).scrollLeft(position);
                    event.preventDefault();
                })
            };
            $("#clear").click(
                    function()
                    {
                        document.getElementById("cal_form").reset();
                    });
            
            $(document).ready(function() {
                $('#items').hScroll(60); 
            });
            
            $(document).ready(function() {		
                $('#datepicker').datepicker({
                    minDate: new Date("today")+-7,
                    dateFormat: "mm/dd/yy"
                });
                $('#datepicker').datepicker('setDate','today');		
            });	
            
            $(document).ready(function() {		
                $('#datepicker2').datepicker({
                    minDate: new Date("today")+-7,
                    dateFormat: "mm/dd/yy"
                });
                $('#datepicker2').datepicker('setDate','today');		
            });	
            
            $(document).ready(function() {		
                $('#datepicker3').datepicker({
                    minDate: new Date("today")+0,
                    dateFormat: "mm/dd/yy"
                });
                $('#datepicker3').datepicker('setDate','today');		
            });	
            
            $(document).ready(function() {		
                $('#datepicker4').datepicker({
                    minDate: new Date("today")+0,
                    dateFormat: "mm/dd/yy"
                });
                $('#datepicker4').datepicker('setDate','today');		
            });	
            $(document).ready(function() {		
                $('#datepicker5').datepicker({
                    minDate: new Date("today")+0,
                    dateFormat: "mm/dd/yy"
                });
                $('#datepicker5').datepicker('setDate','today');		
            });	
            $(document).ready(function() {		
                $('#datepicker6').datepicker({
                    minDate: new Date("today")+0,
                    dateFormat: "mm/dd/yy"
                });
                $('#datepicker6').datepicker('setDate','today');		
            });	
			$(document).ready(function() {		
                $('#datepicker7').datepicker({
                    minDate: new Date("today")+0,
                    dateFormat: "mm/dd/yy"
                });
                $('#datepicker7').datepicker('setDate','today');		
            });	
            
            $(document).on('change', '#forall', function(){
                if($(this).prop('checked')){
                    $('#who').attr('disabled', 'disabled');
                } else {
                    $('#who').removeAttr('disabled');
        }
    });
      
        });