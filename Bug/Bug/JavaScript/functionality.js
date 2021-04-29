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
                $('#Number').val(phone);
                
            });
    $(".EmailUpdate").click(
        function(e)
        {
            var email = e.target.value;
            $('#EmailToEdit').val(email);
            
        });

    // $(".sid").click(
    //     function(e)
    //     {
    //         var woID = e.target.value;
    //         var primEmp = String("#"+ woID + "pe")
    //         console.log(primEmp);
    //         var name = $(primEmp).val();
    //         $('#MainEng').val(name);
    //         console.log(name);
    //     });
    // $('#Details').on('show.bs.modal', function (e) {
    //     var woID = e.target.value;
    //     $.ajax({
    //         type : 'post',
    //         url : 'cal_view.php', //Here you will fetch records 
    //         data :  'rowid='+ woID, //Pass $id
    //         success : function(data){
    //             console.log(data);
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
                $('#state').val(state);
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
                    $('#state').val(state);
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
                $('#datepicker').datepicker('setDate',null);		
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
            
            $(document).on('change', '#forall', function(){
                if($(this).prop('checked')){
                    $('#who').attr('disabled', 'disabled');
                } else {
                    $('#who').removeAttr('disabled');
        }
    });
      
        });
