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
  
    });
 
