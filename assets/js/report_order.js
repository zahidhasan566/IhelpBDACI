$(document).ready(function () {
    $('#reporttable').hide(); 
    $( "#ReportOrder" ).submit(function( event ) {
        var DateFrom = $("#DateFrom").val();
        var DateTo = $("#DateTo").val();
        var CustomerCode = $("#CustomerCode").val();
        var ProductCode = $("#ProductCode").val();
        var string = "DateFrom=" + DateFrom + "&DateTo=" + DateTo + "&CustomerCode=" + CustomerCode + "&ProductCode=" + ProductCode;
        //alert(base_url);
        //console.log(string);
        $.ajax({
            url: base_url + "report/loadOrder",
            type: "post",
            data: string,
            dataType: "json",
            beforeSend: function(){
                $('#loading').show();
                $('#loading').html("<h3 style='padding-left: 10px; color: red;'>loading................</h3>");    
                $('#reporttable').hide();     
            },               
            success: function (response) {
               // you will get response from your php page (what you echo or print)  
               //document.getElementById("reporttable").style.display = "block";
               console.log(response);
               $('#reporttable').show();
               $('#ExportToExcel').show();
               $('#loading').hide();
               $('#dataloadtable tbody').empty(); 
               if(response.length == 0){               
                    $('#dataloadtable tbody').append('<tr><td colspan="7" style="color: red; font-size: 18px; font-weight: bold; text-align: center;">No data found..</td></tr>');   
                    $('#ExportToExcel').hide();  
               } 
               for(var i=0; i<response.length; i++){
                   var sl = i + 1;
                   var string1 = '';
                   var string = '<tr><td>' + sl + '</td>\
                        <td>' + response[i]['OrderNo'] + '</td>\
                        <td>' + response[i]['OrderDate'] + '</td>';                                                                          
                   if(grpUser == 1){
                        var string1 = '<td>' + response[i]['MasterCode'] + ' - ' +  response[i]['CustomerName'] + '</td>';  
                   }                     
                   var string2 = '<td>' + response[i]['ProductCode'] + ' - ' +  response[i]['ProductName'] + '</td>\
                        <td style="text-align: right;">' + response[i]['Quantity'] + '</td>\
                        <td style="text-align: right;">' + response[i]['UnitPrice'] + '</td>\
                        <td style="text-align: right;">' + response[i]['Total'] + '</td>\
                        </tr>';
                   string.concat(string1, string2);                   
                   $('#dataloadtable tbody').append(string);        
               }            
            
            },
            error: function(jqXHR, textStatus, errorThrown) {
               console.log(textStatus, errorThrown);
            } 
        });  
        return false;    
    });
        
    $('#DateFrom').datepicker({  dateFormat: 'yy-mm-dd'  })     
    $('#DateTo').datepicker({   dateFormat: 'yy-mm-dd'   })     
    $(".allproduct").autocomplete({        
        source: function(request, response){
           $.ajax({
               type: "POST",
               url: base_url + "orders/allproductlist",
               data: {search : request.term},               
               dataType: "json",
               cache: false,
               
               success: function (res) {
                    var transformed = $.map(res.data, function (el) {
                        return {
                            label: el.productname,
                            value: el.productcode,
                            unitprice: el.unitprice,
                            vat: el.vat
                        };
                    });
                    response(transformed);                   
               },
               error: function (msg) {
                   response([]);
               }
           })
       },
        focus: function (event, ui) {
            event.preventDefault();
            //$(this).val(ui.item.label);
        },
        minLength: 1
    }).bind('focus', function () {
        $(this).autocomplete("search");
    });     
});  
 

