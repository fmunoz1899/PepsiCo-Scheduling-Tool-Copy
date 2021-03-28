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
        
               
        
        var modal = document.getElementById('myModal'); 

        var img = $('.myImg');
        var modalImg = $("#img01");
        var captionText = document.getElementById("#caption");
        $('.myImg').click(function(){
            modal.style.display = "block";
            var newSrc = this.src;
            modalImg.attr('src', newSrc);
            captionText.innerHTML = this.alt;
        });
        
        var span = document.getElementById("#close");
        
        span.click = function() {
          modal.style.display = "none";
        }
    });
    