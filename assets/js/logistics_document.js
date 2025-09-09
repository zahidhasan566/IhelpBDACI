/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */



$("#loadcontent").hide();

function checkinvoice(){
    var invoiceno = $("#invoiceno").val();
    var invoicenosubmit = $("#invoicenosubmit").val();
    if(invoicenosubmit){
        if(invoiceno == invoicenosubmit){
            return true;
        }
    }
    loadajaxfunction(invoiceno);
    return false;
}

function loadajaxfunction(invoiceno){
    
    $.ajax({
        url: base_url + "logistics/loadinvoicedetails",
        type: "post",
        data: "invoiceno=" + invoiceno,
        dataType: "json",
        beforeSend: function(){
            $('#loading').show();
            $("#loadcontent").hide();
            $('#loading').html("<h3 style='padding-left: 10px; color: red;'>loading................</h3>");    
        },               
        success: function (response) {
            $('#loading').html(""); 
            $('#datatable > tbody').html('');
            var invoiedata = response['invoicedata'];
            var countdata = response['countdata'];
            
            if(invoiedata.length == 0){
                if(countdata.length > 0){
                    $('#loading').html("<h3 style='padding-left: 10px; color: red;'>This invoice is already submitted.</h3>"); 
                }else{
                    $('#loading').html("<h3 style='padding-left: 10px; color: red;'>Invalid invoice no.</h3>"); 
                }                
            }else{
                $("#divinvoiceno").html(': ' + response['invoicedata'][0]['invoiceno']);
                $("#divinvoicedate").html(': ' + response['invoicedata'][0]['invoicedate']);
                $("#divcustomer").html(': ' + response['invoicedata'][0]['customercode'] + ' - ' + response['invoicedata'][0]['customername']);
                var string = '';
                for(i = 0; i < response['invoicedata'].length; i++){
                    sl = i + 1;
                    string = string + "<tr>\n\
                                            <td><input type=\"checkbox\" value=\""+invoiedata[i]['chassisno']+"_"+invoiedata[i]['engineno']+"_"+invoiedata[i]['productcode']+"\" name=\"chassisno[]\"></td>\n\
                                            <td>"+sl+"</td>\n\
                                            <td>"+invoiedata[i]['chassisno']+"</td>\n\
                                            <td>"+invoiedata[i]['engineno']+"</td>\n\
                                            <td>"+invoiedata[i]['productcode'] + " - " + invoiedata[i]['productname'] +"</td>\n\
                                            <td>"+invoiedata[i]['quantity']+"</td>\n\
                                        </tr>";
                }
                $('#datatable > tbody:last-child').append(string);
                $('#datatable > tbody:last-child').append("<tr><td colspan=\"8\"><button type=\"submit\" class=\"btn btn-success\">Submit</font></td></tr>");
                $("#invoicenosubmit").val(invoiceno);
                $("#customercode").val(response['invoicedata'][0]['customercode']);
                $("#loadcontent").show();
            }
            $('#senddate').datepicker({
                dateFormat: 'yy-mm-dd'
            })
            
        },
        error: function(jqXHR, textStatus, errorThrown) {
           console.log(textStatus, errorThrown);
        } 
    });
        
}

function toggle(source) {
  checkboxes = document.getElementsByName("chassisno[]");
  for(var i=0, n=checkboxes.length;i<n;i++) {
    checkboxes[i].checked = source.checked;
  }
}