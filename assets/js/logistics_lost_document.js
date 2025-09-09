/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$("#loadcontent").hide();

function checklostinvoice(){
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
        url: base_url + "logistics/loadlostinvoicedetails",
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
                console.log(invoiedata)
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
                let checkSelected = [];
                for(i = 0; i < response['invoicedata'].length; i++){
                    sl = i + 1;
                    let checkboxchassisno = invoiedata[i]['chassisno'];
                    let selectengineno = invoiedata[i]['engineno'];
                    string = string + "<tr>\n\
                                            <td><input  type=\"checkbox\"  value=\""+invoiedata[i]['chassisno']+"_"+invoiedata[i]['engineno']+"_"+invoiedata[i]['productcode']+"\" name=\"chassisno[]\"></td>\n\
                                            <td>"+sl+"</td>\n\
                                            <td>"+invoiedata[i]['chassisno']+"</td>\n\
                                            <td>"+invoiedata[i]['engineno']+"</td>\n\
                                            <td>"+invoiedata[i]['productcode'] + " - " + invoiedata[i]['productname'] +"</td>\n\
                                            <td>"+invoiedata[i]['quantity']+"</td>\n\
                                        </tr>";


                }

                $('#datatable > tbody:last-child').append(string);
                $('#datatable > tbody:last-child').append("<tr><td colspan=\"8\"> <label for=\"lostdocumentreasonlebel\" class=\"btn\" style=\"padding: 0;color: blue\">Lost Document Reason* </label></td></tr>");
                $('#datatable > tbody:last-child').append("<tr><td><select  id=\"lostdocumentreason\"  name=\"lostdocumentreason\"  class=\"form-control\"> <option value=\"\">select an option</option><option value=\"Lost of Document\" >Lost Of Document </option><option value=\"Accidental Issue\">Accidental Issue <option value=\"Changed Document\">Changed Document</option <option value =\"Others\" > Others < /option></select ></td></tr>");
                $('#datatable > tbody:last-child').append("<tr><td colspan=\"8\"> <label for=\"gdcopy\" class=\"btn\" style=\"padding: 0;color: blue\">Upload G.D Copy*</label></td></tr>");
                $('#datatable > tbody:last-child').append("<tr><td colspan=\"8\"><input type=\"file\"  multiple class=\"col-xl-2\" name=\"gdcopy\" style=\"border: 0px; float: left; width: 200px;\"></td></tr>");
                $('#datatable > tbody:last-child').append("<tr><td colspan=\"8\"> <label for=\"deliverychalan\" class=\"btn\" style=\"padding: 0;color: blue\">Upload Delivery Challan*</label></td></tr>");
                $('#datatable > tbody:last-child').append("<tr><td colspan=\"8\"><input type=\"file\" multiple  class=\"col-xl-2\" name=\"deliverychalan\" style=\"border: 0px; float: left; width: 200px;\"></td></tr>");
                $('#datatable > tbody:last-child').append("<tr><td colspan=\"8\"> <label for=\"othersdocument\" class=\"btn\"style=\"padding: 0;color: blue\" >Upload Others Document*</label></td></tr>");
                $('#datatable > tbody:last-child').append("<tr><td colspan=\"8\"><input type=\"file\"  multiple class=\"col-xl-2\" name=\"othersdocument\" style=\"border: 0px; float: left; width: 200px;\"></td></tr>");
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