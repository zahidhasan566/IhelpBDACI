 function findInvoiceNo(){
    var invoiceno = $("#invoiceno").val(); 
    $.ajax({
        type: "POST",
        url: base_url + "invoice/returninvoicenosearch",
        data: "invoiceno=" + invoiceno,
        cache: false,
        success: function (res) {
            var result = JSON.parse(res);
            if(result.length > 0){
                $('#invoiceno').val(result[0].InvoiceNo);
                $('#invoiceid').val(result[0].InvoiceID);
            }else{
                $('#invoiceid').val('');
            }
        },
        error: function (msg) {
            response([]);
        }
    }) 
    return false;                    
}

function findInvoice(){
    var invoiceid = $("#invoiceid").val(); 
    $.ajax({
        type: "POST",
        url: base_url + "invoice/returninvoicesearch",
        data: "invoiceid=" + invoiceid,
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