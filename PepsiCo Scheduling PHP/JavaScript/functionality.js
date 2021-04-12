$(document).ready(

    function()
    {

      $("#LV").click(
        function()
        {
            //alert("clicked");
            location.href = "List_View.html";
        });
        $("#CV").click(
            function()
            {
                location.href = "Cal_View.html";
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
            $('#datepicker').datepicker();
            $('#datepicker').datepicker('setDate', 'today');
           
        });
  
    });
 
