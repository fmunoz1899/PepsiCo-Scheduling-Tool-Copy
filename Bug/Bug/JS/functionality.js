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
                console.log("hi");
                location.href = "EmployeeInfo.php";
            });
            
//------------------ New JS Code ---------------------
	$(".UpdateNnum").click(
            function(e)
            {
                var phone = e.target.value;
                console.log(phone);
                $('#Number').val(phone);
                
            });
        $(".EmailUpdate").click(
            function(e)
            {
                var email = e.target.value;
                console.log(email);
                $('#EmailToEdit').val(email);
                
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
  
    });
 
