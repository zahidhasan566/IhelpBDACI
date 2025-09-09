$(document).ready(function () {
    $('#loadcontent').hide();  
});  

function checkinvoice(){
    var invoiceno = $("#invoiceno").val();
    loadajaxfunction(invoiceno);
    return false;
}

function loadajaxfunction(invoiceno){
    
    $.ajax({
        url: base_url + "corporatesales/loadinvoicedetails",
        type: "post",
        data: "&invoiceno=" + invoiceno,
        dataType: "json",
        beforeSend: function(){
            $('#loading').show();
            $('#loading').html("<h3 style='padding-left: 10px; color: red;'>loading................</h3>");    
        },               
        success: function (response) {
            $('#loading').html(""); 
            $('#datatable > tbody').html('');
            customer = response['customer'];
            invoice = response['invoice'];
            invoiedata = response['invoiedata'];
            console.log(invoiedata);
            if(customer.length == 0){
                $('#loading').html("<h3 style='padding-left: 10px; color: red;'>Invalid customer.</h3>");
                return false;
            }
            if(invoice.length != 0){
                $('#loading').html("<h3 style='padding-left: 10px; color: red;'>Already inputed this invoice</h3>");
                return false;
            }
            if(customer.length != 0 || invoice.length == 0){
                $('#loadcontent').show(); 
                if(invoiedata.length > 0){
                    var string = '';
                    for(i = 0; i < invoiedata.length; i++ ){
                        sl = i + 1;
                        string = string + "<tr>\n\
                                                <td>"+sl+"</td>\n\
                                                <td>"+invoiedata[i]['mastercode']+" - "+invoiedata[i]['customername']+"</td>\n\
                                                <td>"+invoiedata[i]['invoicedate']+"</td>\n\
                                                <td>"+invoiedata[i]['batchno']+"</td>\n\
                                                <td>"+invoiedata[i]['engineno']+"</td>\n\
                                                <td>"+invoiedata[i]['product']+"</td>\n\
                                                <td>"+invoiedata[i]['unitprice']+"</td>\n\
                                                <td>"+invoiedata[i]['vat']+"</td>\n\
                                            </tr>";
                    }
                    $('#datatable > tbody:last-child').append(string);
                    $('#datatable > tbody:last-child').append("<tr><td colspan=\"8\"><font onclick=\"submitcorporate()\" class=\"btn btn-success\">Submit corporate sales</font></td></tr>");
                    $("#invoicenosubmit").val(invoiceno);
                    
                }
                
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
           console.log(textStatus, errorThrown);
        } 
    });
        
}

function submitcorporate(){
    var invoiceno = $("#invoicenosubmit").val();
    $.ajax({
        url: base_url + "corporatesales/docreate",
        type: "post",
        data: "&invoiceno=" + invoiceno,
        dataType: "json",
        beforeSend: function(){
            $('#loading').show();
            $('#loading').html("<h3 style='padding-left: 10px; color: red;'>loading................</h3>");    
        },               
        success: function (response) {
            if(response['success'] == 1){
                $('#loading').show();
                $('#loading').html("<h3 style='padding-left: 10px; color: green;'>Successfully inserted corporate customer.</h3>");
                $('#loadcontent').hide();
            }else{
                $('#loadcontent').hide();  
                $('#loading').show();
                $('#loading').html("<h3 style='padding-left: 10px; color: red;'>Something wrong.</h3>");    
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
           console.log(textStatus, errorThrown);
        } 
    });
}