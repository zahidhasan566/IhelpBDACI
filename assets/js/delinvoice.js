$(document).ready(function () {                    
	       
});

function deleteInvoice(chassisno){
    if (window.confirm("Are you sure you want to delete this invoice?")) {
        $.ajax({
            type: "POST",
            url: base_url + "invoice/deleteinvoice",
            data: "chassisno=" + chassisno,
            cache: false,
            dataType: "json",
            success: function (res) {
                console.log(res);  
                if(res == true){
                    $("#loadcontent").html('<div class="alert alert-success">strong>Success!</strong> Successfully delete.</div>');       
                }                    
            },
            error: function (msg) {
                alert(msg);
            }
        })
    }else{
        alert('false');
    }    
}
  
function findbike(){
    var chassisno = $("#chassisno").val(); 
    console.log(chassisno);    
    $.ajax({
        type: "POST",
        url: base_url + "invoice/delinvoicesearch",
        data: "chassisno=" + chassisno,
        cache: false,
        success: function (res) {
            $("#loadcontent").html(res);                     
        },
        error: function (msg) {
            response([]);
        }
    }) 
    return false;                    
}