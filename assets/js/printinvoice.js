$(document).ready(function () {                    
	       
});


  
function findbike(){
    var chassisno = $("#chassisno").val(); 
    console.log(chassisno);    
    $.ajax({
        type: "POST",
        url: base_url + "invoice/printinvoicesearch",
        data: "chassisno=" + chassisno,
        cache: false,
        success: function (res) {
            $("#loadcontent").html(res);  
            console.log(res);                   
        },
        error: function (msg) {
            response([]);
        }
    }) 
    return false;                    
}