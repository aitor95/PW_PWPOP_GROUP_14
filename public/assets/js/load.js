//enviar una petició ajax de post/get a /?size={{size}}

$(document).ready(function(){

  $("#loadMore").click(function(){

    var size = $("#loadMore").text();
    var size = parseInt(size);
    var size = size + 5;

    $.ajax({url: "/?size="+size, success: function(result){

        window.location.href = "/?size="+size;

    }});
  });
});