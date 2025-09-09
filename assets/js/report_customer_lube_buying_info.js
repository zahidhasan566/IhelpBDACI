$(document).ready(function () {
    $('#reporttable').hide(); 
    $( "#ReportOrder" ).submit(function( event ) {
        var DateFrom = $("#DateFrom").val();
        var DateTo = $("#DateTo").val();
        var CustomerCode = $("#CustomerCode").val();
        var ApprovedType = $('#ApprovedType').val();
        var string = "DateFrom=" + DateFrom + "&DateTo=" + DateTo + "&CustomerCode=" + CustomerCode + "&reporttype=" + reporttype + "&page=" + page + "&ApprovedType=" + ApprovedType;
        var savestring = "DateFrom=" + DateFrom + "&DateTo=" + DateTo + "&CustomerCode=" + CustomerCode + "&reporttype=" + reporttype;
        console.log(ApprovedType);
        $("#string").val(savestring);
        
        loadajaxfunction(string,page,searchstring=''); 
        
        return false;    
    });
        
    $('#DateFrom').datepicker({  dateFormat: 'yy-mm-dd'  })     
    $('#DateTo').datepicker({   dateFormat: 'yy-mm-dd'   })     
    /*
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
    });*/     
});  
 


function loadajaxfunction(string,page,searchstring){
    if(!searchstring){ $("#searchstring").val(''); }
    datasting = string;        
    // console.log(string  + "&searchstring=" + searchstring);
    $.ajax({
            url: base_url + "report/loadCustomerLubeBuyingInfo",
            type: "post",
            data: string  + "&searchstring=" + searchstring,
            dataType: "json",
            beforeSend: function(){
                $('#loading').show();
                $('#loading').html("<h3 style='padding-left: 10px; color: red;'>loading................</h3>");    
                $('#reporttable').hide();     
            },               
            success: function (response) {
               // you will get response from your php page (what you echo or print)  
               //document.getElementById("reporttable").style.display = "block";
            //    console.log(response);
               $('#reporttable').show();
               $('#ExportToExcel').show();
               $('#loading').hide();
               $('#dataloadtable tbody').empty(); 
               
               WarrantyReport = response['WarrantyReport'];
               Page = response['PagingList'];
               
               if(WarrantyReport.length == 0){               
                    $('#dataloadtable tbody').append('<tr><td colspan="7" style="color: red; font-size: 18px; font-weight: bold; text-align: center;">No data found..</td></tr>');   
                    $('#ExportToExcel').hide();  
               } 
               //var t = $('#dataloadtable').DataTable();
               
               
               $('#dataloadtable tbody').empty(); 
               string1 = '';                
               string2 = '';
               string3 = '';                
               for(var i=0; i<WarrantyReport.length; i++){
                   var sl = i + 1;
                   string = '<tr><td>' + WarrantyReport[i]['SL'] + '</td><td>' + WarrantyReport[i]['InvoiceID'] + '</td>';                        
                   string1 = '<td>' + WarrantyReport[i]['InvoiceDate']+ '</td>';                        
                   string2 = '<td>' + WarrantyReport[i]['MasterCode'] + '</td>\
                   <td>' + WarrantyReport[i]['DealerName'] + '</td>\
                   <td>' + WarrantyReport[i]['CustomerName'] + '</td>\
                   <td>' + WarrantyReport[i]['JobCardNo'] + '</td>\
                   \n\<td>' + WarrantyReport[i]['Mileage'] + '</td>\\n\
                   \n\<td>' + WarrantyReport[i]['ProductCode'] + '</td>\\n\
                   \n\<td>' + WarrantyReport[i]['ProductName'] + '</td>\\n\
                   \n\\n\<td>' + WarrantyReport[i]['Quantity'] + '</td>';

                   string3 = '</tr>';
                        
         
                   string = string + string1 + string2 + string3;
                   $('#dataloadtable tbody').append(string); 
                   //t.rows.add($(string)).draw(); 
                   //t.rows.add($(string)).draw(); 
               }    
                   $('#pagination').empty(); 
               
                   previous = page - 1;
                   next = page + 1;
                   
                   if(Page.length != 0 && page != 1){
                        $('#pagination').append('<li><a onclick="load(' + previous + ')" href="#" id="PageNo">Previous</a></li>');        
                   }
                   for(var i=0; i<Page.length; i++){
                       if(page == Page[i]['PageNo']){ classcontent = ' class="active"'; }else{ classcontent = ''; }
                        $('#pagination').append('<li'+classcontent+'><a onclick="load(' + Page[i]['PageNo'] + ')" href="#" id="PageNo">' + Page[i]['PageNo'] + '</a></li>');    
                   }  
                   if(Page.length != 0 && page != Page.length){
                        $('#pagination').append('<li><a onclick="load(' + next + ')" href="#" id="PageNo">Next</a></li>');        
                   } 
                   
                   $("#a_ExportToExcel").attr("href", base_url + "report/customerLubeBuyingInfoexcelexport/?" + datasting  + "&excelfilename=" + excelfilename);    
                         
            
            },
            error: function(jqXHR, textStatus, errorThrown) {
               console.log(textStatus, errorThrown);
            } 
        }); 
        
}

function load(pageno){
    savestring = $("#string").val();
    searchstring = $("#searchstring").val();
    ajaxstring = savestring + "&page=" + pageno;
    // console.log(ajaxstring);
    loadajaxfunction(ajaxstring,pageno,searchstring);
} 
