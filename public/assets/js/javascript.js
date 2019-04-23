$(document).ready(function() {

    $("#grillechien").hide();

    $(".chat").click(function(){
        $("#grillechat").show();
        $("#grillechien").hide();
    });
    $(".chien").click(function(){
        $("#grillechat").hide();
        $("#grillechien").show();
    });
});
