$(document).ready(function () {                    
	       
});
 
function findbike(){
    var invoiceno = $("#invoiceno").val(); 
    $.ajax({
        type: "POST",
        url: base_url + "invoice/checkinvoicesearch",
        data: "invoiceno=" + invoiceno,
        cache: false,
        dataType: "json",
        beforeSend: function () {
            $("#tbody").empty();
            $("#tbodydetails").empty();
            $("#tbodysummery").empty();
        },
        success: function (res) {            
            successsummery = res['successsummery'];
            for(i = 0; i < successsummery.length; i++){          
                $("#tbody").append("<tr><td>"+successsummery[i]['msgtype']+"</td><td>"+successsummery[i]['msg']+"</td></tr>");
            }            
            invoicedetails = res['invoicedetails'];
            for(i = 0; i < invoicedetails.length; i++){          
                $("#tbodydetails").append("<tr><td>"+invoicedetails[i]['SL']+"</td><td>"+invoicedetails[i]['SDMS_Chassis_No']+"</td><td>"+invoicedetails[i]['DMS_Chassis_No']+"</td><td>"+invoicedetails[i]['Chassis_Status']+"</td><td>"+invoicedetails[i]['SDMS_Engine_No']+"</td><td>"+invoicedetails[i]['DMS_Engine_No']+"</td><td>"+invoicedetails[i]['Engine_Status']+"</td><td>"+invoicedetails[i]['Sold_Status']+"</td><td>"+invoicedetails[i]['SDMS_Product']+"</td><td>"+invoicedetails[i]['DMS_Product']+"</td><td>"+invoicedetails[i]['Product_Status']+"</td><td>"+invoicedetails[i]['Process']+"</td></tr>");
            }
            invoicesummery = res['invoicesummery'];
            for(i = 0; i < invoicesummery.length; i++){        
                $("#tbodysummery").append("<tr><td>"+invoicesummery[i]['Bike_Receive']+"</td><td>"+invoicesummery[i]['SDMSSalesQnty']+"</td><td>"+invoicesummery[i]['Un_sold_Bike']+"</td><td>"+invoicesummery[i]['sold_Bike']+"</td></tr>")
            }
        },
        error: function (msg) {
            response([]);
        }
    }) 
    return false;                    
}